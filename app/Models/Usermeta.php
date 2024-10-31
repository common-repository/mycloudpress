<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Usermeta extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countUsermeta()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->usermeta");
	}

	public function getUsermeta($meta_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE umeta_id = $meta_id");
	}

	public function updateUsermeta($umeta_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->usermeta, $data, array('umeta_id' => $umeta_id));
	}

	public function createUsermeta($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->usermeta, $data);
	}

	
	public function getRemoteAllUsermeta()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('usermeta', [
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

	public function restoreUsermetaToSite($usermeta)
	{
		foreach($usermeta as $meta) {
			$usermeta = $this->getUsermeta($meta->umeta_id);
			if(!is_null($usermeta)) {
				$this->updateUsermeta($meta->umeta_id, [
					'user_id' => $meta->user_id,
					'meta_key' => $meta->meta_key,
					'meta_value' => $meta->meta_value,
				]);
			} else {
				$this->createUsermeta([
					'umeta_id' => $meta->umeta_id,
					'user_id' => $meta->user_id,
					'meta_key' => $meta->meta_key,
					'meta_value' => $meta->meta_value,
				]);
			}
		}
	}
}