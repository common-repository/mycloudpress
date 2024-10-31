<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Post extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countPosts()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->posts");
	}

	public function getPost($post_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = $post_id");
	}

	public function updatePost($post_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->posts, $data, array('ID' => $post_id));
	}

	public function createPost($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->posts, $data);
	}

	
	public function getRemoteAllPosts()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('post', [
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

	public function restorePostsToSite($posts)
	{
		foreach($posts as $p) {
			$post = $this->getPost($p->wp_post_id);
			if(!is_null($post)) {
				$this->updatePost($p->wp_post_id, [
					'post_author' => $p->post_author,
					'post_date' => $p->post_date,
					'post_date_gmt' => $p->post_date_gmt,
					'post_content' => $p->post_content,
					'post_title' => $p->post_title,
					'post_excerpt' => $p->post_excerpt,
					'post_status' => $p->post_status,
					'comment_status' => $p->comment_status,
					'ping_status' => $p->ping_status,
					'post_password' => $p->post_password,
					'post_name' => $p->post_name,
					'to_ping' => $p->to_ping,
					'pinged' => $p->pinged,
					'post_modified' => $p->post_modified,
					'post_modified_gmt' => $p->post_modified_gmt,
					'post_content_filtered' => $p->post_content_filtered,
					'post_parent' => $p->post_parent,
					'guid' => $p->guid,
					'menu_order' => $p->menu_order,
					'post_type' => $p->post_type,
					'post_mime_type' => $p->post_mime_type,
					'comment_count' => $p->comment_count,
				]);
			} else {
				$this->createPost([
					'ID' => $p->wp_post_id,
					'post_author' => $p->post_author,
					'post_date' => $p->post_date,
					'post_date_gmt' => $p->post_date_gmt,
					'post_content' => $p->post_content,
					'post_title' => $p->post_title,
					'post_excerpt' => $p->post_excerpt,
					'post_status' => $p->post_status,
					'comment_status' => $p->comment_status,
					'ping_status' => $p->ping_status,
					'post_password' => $p->post_password,
					'post_name' => $p->post_name,
					'to_ping' => $p->to_ping,
					'pinged' => $p->pinged,
					'post_modified' => $p->post_modified,
					'post_modified_gmt' => $p->post_modified_gmt,
					'post_content_filtered' => $p->post_content_filtered,
					'post_parent' => $p->post_parent,
					'guid' => $p->guid,
					'menu_order' => $p->menu_order,
					'post_type' => $p->post_type,
					'post_mime_type' => $p->post_mime_type,
					'comment_count' => $p->comment_count,
				]);
			}
		}
	}
}