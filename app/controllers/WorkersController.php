<?php


class WorkersController extends ControllerBase
{
	public $queue;
	public $firebaseRepo;

	public function initialize()
	{
		$this->firebaseRepo = new FirebaseRepository();
		global $queue;
		$this->queue = $queue; 
	}

	public function recentAction()
	{
		$this->queue->watch('recent');
		$this->queue->choose('recent');
		$mongo = new MongoClient();
		$collection = $mongo->pval->tweets;
		$usersCollection = $mongo->pval->users;

		while (($job = $this->queue->peekReady()) !== false) {

		    $message = $job->getBody();
		    die(var_dump($message));

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
		    	$st[] = array_intersect_key($status, array_flip(['text' , 'user' , 'created_at','retweet_count']));
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
		        	// $status['user'] = $status['user']['id'];
		        	$st[] = array_intersect_key($status, array_flip(['text' , 'user' , 'created_at','retweet_count']));
		        	$users[] = $status['user'];
		        }

		        $last_tweet = end($st);
		    }
		    die(var_dump($st));
	        $collection->batchInsert($st);
	        $user_ids = [];
	        $unique_users = [];
	        foreach ($users as $user) {
	        	if(!in_array($user['id'], $user_ids)){
		        	$user_ids[] = $user['id'];
		        	$unique_users[] = $user;
	        	}
	        }
	        $usersCollection->batchInsert($users);

		    $job->delete();
		    die();
		}
	}

	public function mixedAction()
	{
		$mongo = new MongoClient();
		$collection = $mongo->pval->tweets;
		$usersCollection = $mongo->pval->users;

		while (($job = $this->queue->reserve())) {

		    $message = $job->getBody();

		    $settings = array(
		    	'oauth_access_token' => "427628232-v9GN4suDydtodmVITs4KQhwLr8wViE8cjeByG9GB",
		    	'oauth_access_token_secret' => "6fFGxWKgtPS4GT1DXkoWmik9WohSjesxQOnT3DEwTZZC2",
		    	'consumer_key' => "tiflyjQuubx5J3nc3TvXxXzxw",
		    	'consumer_secret' => "SxZ4xmq79ViOlr7thnFLQa7fHlzthxF2BdrIlTTgOsBxkphJhP"
		    );

		    $url = 'https://api.twitter.com/1.1/search/tweets.json';
		    $getfield = '?q='.$message['fetchRecentTweets'].'&count=100';
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
		    	$st[] = array_intersect_key($status, array_flip(['text' , 'user' , 'created_at','retweet_count']));
		    	$users[] = $status['user'];
		    }


		    $last_tweet = end($st);
		    for($i = 0; $i<1 ; $i++){
		        $getfield = '?q='.$message['fetchRecentTweets'].'&count=100&max_id=' . $last_tweet['id'];
		        $request = $twitter->setGetfield($getfield)
		                     ->buildOauth($url, $requestMethod)
		                     ->performRequest();
		        $request = json_decode($request, true);

		        
		        foreach ($result['statuses'] as $status) {
		        	// $status['user'] = $status['user']['id'];
		        	$st[] = array_intersect_key($status, array_flip(['text' , 'user' , 'created_at','retweet_count']));
		        	$users[] = $status['user'];
		        }

		        $last_tweet = end($st);
		    }

	        $collection->batchInsert($st);
	        $user_ids = [];
	        $unique_users = [];
	        foreach ($users as $user) {
	        	if(!in_array($user['id'], $user_ids)){
		        	$user_ids[] = $user['id'];
		        	$unique_users[] = $user;
	        	}
	        }
	        $usersCollection->batchInsert($users);

		    $job->delete();
		    die();
		}
	}
}

