<?php

require_once(dirname(__FILE__).'/bootstrap.php');

$queue->watch('recent');
$queue->choose('recent');
$mongo = new MongoClient();
$collection = $mongo->pval->tweets;
$usersCollection = $mongo->pval->users;

while (($job = $queue->peekReady()) !== false) {

    $message = $job->getBody();
    // die(var_dump($message));
    $settings = array(
    	'oauth_access_token' => "427628232-v9GN4suDydtodmVITs4KQhwLr8wViE8cjeByG9GB",
    	'oauth_access_token_secret' => "6fFGxWKgtPS4GT1DXkoWmik9WohSjesxQOnT3DEwTZZC2",
    	'consumer_key' => "tiflyjQuubx5J3nc3TvXxXzxw",
    	'consumer_secret' => "SxZ4xmq79ViOlr7thnFLQa7fHlzthxF2BdrIlTTgOsBxkphJhP"
    );

    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $getfield = '?q='.$message['fetchRecentTweets'].'&result_type=recent&count=100';
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);

    $result = $twitter->setGetfield($getfield)
    			 ->buildOauth($url, $requestMethod)
    			 ->performRequest();

    $result = json_decode($result, true);
    $st = [];
    $users = [];
    foreach ($result['statuses'] as $key => $status) {
    	// $status['user'] = $status['user']['id'];
    	$st[] = array_intersect_key($status, array_flip(['text' , 'user' , 'created_at','retweet_count','id']));
    	$users[] = $status['user'];
    }


    $last_tweet = end($st);
    for($i = 0; $i<1 ; $i++){
        $getfield = '?q='.$message['fetchRecentTweets'].'&result_type=recent&count=100&max_id=' . $last_tweet['id'];
        $request = $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
        $request = json_decode($request, true);

        
        foreach ($result['statuses'] as $status) {
        	$st[] = array_intersect_key($status, array_flip(['text' , 'user' , 'created_at','retweet_count','id']));
        	$users[] = $status['user'];
        }

        $last_tweet = end($st);
    }
    $user_ids = [];
    $unique_users = [];
    foreach ($users as $user) {
    	if(!in_array($user['id'], $user_ids)){
        	$user_ids[] = $user['id'];
        	$unique_users[] = $user;
    	}
    }
    $collection->batchInsert($st);
    $usersCollection->remove([]);
    $usersCollection->batchInsert($unique_users);

    $topUsers = iterator_to_array($usersCollection->find()->sort(["followers_count" => -1])->limit(20));
    $topUsers = json_encode($topUsers);

    $firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);
    $firebase->set(DEFAULT_PATH . '/name/contact002', "John Does");
    $firebase->set(DEFAULT_PATH . '/top', $topUsers);

    $job->delete();
}