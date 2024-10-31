<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Postmeta extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countPostmeta()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->postmeta");
	}

	public function getPostmeta($meta_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_id = $meta_id");
	}

	public function updatePostmeta($meta_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->postmeta, $data, array('meta_id' => $meta_id));
	}

	public function createPostmeta($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->postmeta, $data);
	}

	
	public function getRemoteAllPostmeta()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('postmeta', [
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

	public function restorePostmetaToSite($postmeta)
	{
		foreach($postmeta as $p) {
			$post = $this->getPostmeta($p->wp_meta_id);
			if(!is_null($post)) {
				$this->updatePostmeta($p->wp_meta_id, [
					'post_id' => $p->post_id,
					'meta_key' => $p->meta_key,
					'meta_value' => $p->meta_value,
				]);
			} else {
				$this->createPostmeta([
					'meta_id' => $p->wp_meta_id,
					'post_id' => $p->post_id,
					'meta_key' => $p->meta_key,
					'meta_value' => $p->meta_value,
				]);
			}
		}
	}
}