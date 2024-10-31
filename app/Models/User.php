<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class User extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	
	public function getRemoteAllUsers()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('user', [
				'query' => [
					'api_key' => $this->apiKey,
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}

		return json_decode($response->getBody()->getContents());
	}

	public function countUsers()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->users");
	}

	public function getUser($user_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->users WHERE id = $user_id");
	}

	public function updateUser($user_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->users, $data, array('ID' => $user_id));
	}

	public function createUser($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->users, $data);
	}

	public function restoreUsersToSite($users)
	{
		foreach($users as $u) {
			$user = $this->getUser($u->user_ID);
			if(!is_null($user)) {
				$this->updateUser($u->user_ID, [
					'user_login' => $u->user_login,
					'user_pass' => $u->user_pass,
					'user_nicename' => $u->user_nicename,
					'user_email' => $u->user_email,
					'user_url' => $u->user_url,
					'user_registered' => $u->user_registered,
					'user_activation_key' => $u->user_activation_key,
					'user_status' => $u->user_status,
					'display_name' => $u->display_name,
				]);
			} else {
				$this->createUser([
					'ID' => $u->user_ID,
					'user_login' => $u->user_login,
					'user_pass' => $u->user_pass,
					'user_nicename' => $u->user_nicename,
					'user_email' => $u->user_email,
					'user_url' => $u->user_url,
					'user_registered' => $u->user_registered,
					'user_activation_key' => $u->user_activation_key,
					'user_status' => $u->user_status,
					'display_name' => $u->display_name,
				]);
			}
		}
	}
}