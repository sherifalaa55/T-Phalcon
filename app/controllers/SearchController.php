<?php
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
/**
 * Phalcon StarterKit
 *
 * A simple starterKit for PhalconPHP. 
 *
 * @package		StarterKit
 * @author		Jeremie Ges & Laurent Schaffner
 * @link		https://github.com/GesJeremie/PhalconPHP-StarterKit
 * @since		Version 0.1
 */

// ------------------------------------------------------------------------

/**
 * HelloController
 *
 * A simple example of controller system.
 *
 * @package		PhalconPHP
 * @subpackage	Controllers
 * @category	Controllers
 */

class SearchController extends ControllerBase
{
	public function initialize()
	{
		global $queue;
		$this->queue = $queue; 
	}


	public function searchTweetsAction()
	{
		$query = $_GET['q'];
		$this->queue->put(['fetchRecentTweets' => $query]);
	}

	public function createAction()
	{
		// print_r(get_loaded_extensions());

		$queue = new Phalcon\Queue\Beanstalk(
		    array(
		        'host' => '127.0.0.1',
		        'port' => '11300'
		    )
		);

		$mongo = new MongoClient();
		// $mongo->selectDB("pval");
		$collection = $mongo->pval->tweets;
		$usersCollection = $mongo->pval->users;

		while (($job = $queue->reserve())) {

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

	public function runAction()
	{

		$firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);

		// --- storing an array ---
		$test = array(
		    "foo" => "bar",
		    "i_love" => "lamp",
		    "id" => 42
		);
		$dateTime = new DateTime();
		$firebase->set(DEFAULT_PATH . '/' . $dateTime->format('c'), $test);

		// --- storing a string ---
		$firebase->set(DEFAULT_PATH . '/name/contact001', "John Doe");

		// --- reading the stored string ---
		$name = $firebase->get(DEFAULT_PATH . '/name/contact001');
		var_dump($name);
	}
}

