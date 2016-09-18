<?php

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
		$this->queue->put(['fetchTweets' => $query]);
		$this->queue->choose('recent');
		$this->queue->put(['fetchRecentTweets' => $query]);
		return "Initializing workers";
	}


}

