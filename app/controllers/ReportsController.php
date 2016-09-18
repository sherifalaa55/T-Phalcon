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
		print_r(iterator_to_array($tweets));
	}
}

