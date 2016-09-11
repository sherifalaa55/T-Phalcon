<?php

/**
* interaction with firebase db
*/
class FirebaseRepository
{
	/**
	 * pushes top 20 users to firebase d
	 * @param array users
	 */
	public function pushTopUsers(array $users)
	{
		$firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);

		return $firebase->set(DEFAULT_PATH . '/users', $users);

		// // --- reading the stored string ---
		// $name = $firebase->get(DEFAULT_PATH . '/name/contact001');
	}
}