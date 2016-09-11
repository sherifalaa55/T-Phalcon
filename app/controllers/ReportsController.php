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

class ReportsController extends ControllerBase
{
	public function initialize()
	{
		$this->mongo = new MongoClient();
	}


	public function getReportsAction()
	{
		$tweetsCollection = $this->mongo->pval->tweets;
		$usersCollection = $this->mongo->pval->users;
		$users = $usersCollection->find()->sort(["followers_count" => -1])->limit(20);
		$users = iterator_to_array($users);
		$users_ids = array_column($users, 'id');
		$tweets = $tweetsCollection->find(["user.id" => ['$in' => $users_ids ] ])->sort(["retweet_count" => -1])->limit(20);
		var_dump(iterator_to_array($tweets));
	}
}

