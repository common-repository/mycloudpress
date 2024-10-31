<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Comment extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countComments()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->comments");
	}

	public function getComment($comment_ID)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID = $comment_ID");
	}

	public function updateComment($comment_ID, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->comments, $data, array('comment_ID' => $comment_ID));
	}

	public function createComment($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->comments, $data);
	}

	
	public function getRemoteAllComments()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('comment', [
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

	public function restoreCommentsToSite($comments)
	{
		foreach($comments as $c) {
			$comment = $this->getComment($c->comment_ID);
			if(!is_null($comment)) {
				$this->updateComment($c->comment_ID, [
					'comment_post_ID' => $c->comment_post_ID,
					'comment_author' => $c->comment_author,
					'comment_author_email' => $c->comment_author_email,
					'comment_author_url' => $c->comment_author_url,
					'comment_author_IP' => $c->comment_author_IP,
					'comment_date' => $c->comment_date,
					'comment_date_gmt' => $c->comment_date_gmt,
					'comment_content' => $c->comment_content,
					'comment_karma' => $c->comment_karma,
					'comment_approved' => $c->comment_approved,
					'comment_agent' => $c->comment_agent,
					'comment_type' => $c->comment_type,
					'comment_parent' => $c->comment_parent,
					'user_id' => $c->user_id,
				]);
			} else {
				$this->createComment([
					'comment_ID' => $c->comment_ID,
					'comment_post_ID' => $c->comment_post_ID,
					'comment_author' => $c->comment_author,
					'comment_author_email' => $c->comment_author_email,
					'comment_author_url' => $c->comment_author_url,
					'comment_author_IP' => $c->comment_author_IP,
					'comment_date' => $c->comment_date,
					'comment_date_gmt' => $c->comment_date_gmt,
					'comment_content' => $c->comment_content,
					'comment_karma' => $c->comment_karma,
					'comment_approved' => $c->comment_approved,
					'comment_agent' => $c->comment_agent,
					'comment_type' => $c->comment_type,
					'comment_parent' => $c->comment_parent,
					'user_id' => $c->user_id,
				]);
			}
		}
	}
}