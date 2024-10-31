<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Commentmeta extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countCommentmeta()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->commentmeta");
	}

	public function getCommentmeta($meta_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->commentmeta WHERE meta_id = $meta_id");
	}

	public function updateCommentmeta($meta_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->commentmeta, $data, array('meta_id' => $meta_id));
	}

	public function createCommentmeta($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->commentmeta, $data);
	}

	
	public function getRemoteAllCommentmeta()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('commentmeta', [
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

	public function restoreCommentmetaToSite($commentmeta)
	{
		foreach($commentmeta as $cm) {
			$commentmeta = $this->getCommentmeta($cm->meta_id);
			if(!is_null($commentmeta)) {
				$this->updateCommentmeta($cm->meta_id, [
					'comment_id' => $cm->comment_id,
					'meta_key' => $cm->meta_key,
					'meta_value' => $cm->meta_value,
				]);
			} else {
				$this->createCommentmeta([
					'meta_id' => $cm->meta_id,
					'comment_id' => $cm->comment_id,
					'meta_key' => $cm->meta_key,
					'meta_value' => $cm->meta_value,
				]);
			}
		}
	}
}