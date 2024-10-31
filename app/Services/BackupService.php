<?php

namespace MyCloudPress\Services;

use MyCloudPress\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\str;

class BackupService extends Service {

	protected $apiKey;

	
	protected $allPosts;

	
	protected $allComments;

	
	protected $allUsers;

	
	protected $allTerms;

	
	protected $allOptions;

	
	protected $posts;

	
	protected $pages;

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
		$this->allPosts = array_chunk($this->getAllPosts(), 10);
		$this->allComments = array_chunk($this->getAllComments(), 10);
		$this->allOptions = array_chunk($this->getAllOptions(), 10);
		$this->allUsers = array_chunk($this->getAllUsers(), 15);
		$this->allTerms = array_chunk($this->getAllTerms(), 10);
		$this->allTermsTaxonomy = array_chunk($this->getAllTermsTaxonomy(), 10);
		$this->allTermsRelationships = array_chunk($this->getAllTermsRelationships(), 10);
	}

	
	private function getAllPosts()
	{
		global $wpdb;
		$wpdb->query('SET SESSION group_concat_max_len = 10000'); 		$query = "
		    SELECT p.*,
		    GROUP_CONCAT(pm.meta_id ORDER BY pm.meta_id DESC SEPARATOR '||') as meta_ids,
		    GROUP_CONCAT(pm.meta_key ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_keys,
		    GROUP_CONCAT(pm.meta_value ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_values
		    FROM $wpdb->posts p
		    LEFT JOIN $wpdb->postmeta pm on pm.post_id = p.ID
		    GROUP BY p.ID
		";

		$posts = $wpdb->get_results($query);
		return $posts;
	}

	
	private function getAllComments()
	{
		global $wpdb;
		$wpdb->query('SET SESSION group_concat_max_len = 10000'); 		$query = "
		    SELECT c.*,
		    GROUP_CONCAT(cm.meta_id ORDER BY cm.meta_key DESC SEPARATOR '||') as meta_ids,
		    GROUP_CONCAT(cm.meta_key ORDER BY cm.meta_key DESC SEPARATOR '||') as meta_keys,
		    GROUP_CONCAT(cm.meta_value ORDER BY cm.meta_key DESC SEPARATOR '||') as meta_values
		    FROM $wpdb->comments c
		    LEFT JOIN $wpdb->commentmeta cm on cm.comment_id = c.comment_ID
		    GROUP BY c.comment_ID
		";

		$comments = $wpdb->get_results($query);
		return $comments;
	}

	
	private function getAllOptions()
	{
		global $wpdb;

		$query = "
		    SELECT *
		    FROM $wpdb->options
		    WHERE
    			LENGTH(option_value) < 100000
			AND 
				option_name NOT LIKE ('%_transient_%');
		";

		$options = $wpdb->get_results($query);
				return $options;
	}

	
	private function getAllUsers()
	{
		global $wpdb;
		$wpdb->query('SET SESSION group_concat_max_len = 10000'); 		$query = "
		    SELECT u.*,
		    GROUP_CONCAT(um.umeta_id ORDER BY um.meta_key DESC SEPARATOR '||') as umeta_ids,
		    GROUP_CONCAT(um.meta_key ORDER BY um.meta_key DESC SEPARATOR '||') as meta_keys,
		    GROUP_CONCAT(um.meta_value ORDER BY um.meta_key DESC SEPARATOR '||') as meta_values
		    FROM $wpdb->users u
		    LEFT JOIN $wpdb->usermeta um on um.user_id = u.ID
		    GROUP BY u.ID
		";

		$users = $wpdb->get_results($query);
		return $users;
	}

	
	private function getAllTermsRelationships()
	{
		global $wpdb;
		$query = "
		    SELECT *
		    FROM $wpdb->term_relationships
		";

		$terms = $wpdb->get_results($query);
		return $terms;
	}

	
	private function getAllTermsTaxonomy()
	{
		global $wpdb;
		$query = "
		    SELECT *
		    FROM $wpdb->term_taxonomy
		";

		$terms = $wpdb->get_results($query);
		return $terms;
	}

	
	private function getAllTerms()
	{
		global $wpdb;
		$wpdb->query('SET SESSION group_concat_max_len = 10000'); 		$query = "
		    SELECT t.*,
		    GROUP_CONCAT(tm.meta_id ORDER BY tm.meta_key DESC SEPARATOR '||') as meta_ids,
		    GROUP_CONCAT(tm.meta_key ORDER BY tm.meta_key DESC SEPARATOR '||') as meta_keys,
		    GROUP_CONCAT(tm.meta_value ORDER BY tm.meta_key DESC SEPARATOR '||') as meta_values
		    FROM $wpdb->terms t
		    LEFT JOIN $wpdb->termmeta tm on tm.term_id = t.term_id
		    GROUP BY t.term_id
		";

		$terms = $wpdb->get_results($query);
		return $terms;
	}

	
	private function getPosts()
	{
		global $wpdb;
		$wpdb->query('SET SESSION group_concat_max_len = 10000'); 		$query = "
		    SELECT p.*,
		    GROUP_CONCAT(pm.meta_key ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_keys,
		    GROUP_CONCAT(pm.meta_value ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_values
		    FROM $wpdb->posts p
		    LEFT JOIN $wpdb->postmeta pm on pm.post_id = p.ID
		    WHERE p.post_type = 'post'
		    GROUP BY p.ID
		";

		$posts = $wpdb->get_results($query);
		return $posts;
	}

	
	private function getPages()
	{
		global $wpdb;
		$wpdb->query('SET SESSION group_concat_max_len = 10000'); 		$query = "
		    SELECT p.*,
		    GROUP_CONCAT(pm.meta_key ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_keys,
		    GROUP_CONCAT(pm.meta_value ORDER BY pm.meta_key DESC SEPARATOR '||') as meta_values
		    FROM $wpdb->posts p
		    LEFT JOIN $wpdb->postmeta pm on pm.post_id = p.ID
		    WHERE p.post_type = 'page'
		    GROUP BY p.ID
		";

		$posts = $wpdb->get_results($query);

		return $posts;
	}

	public function run()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			foreach($this->allPosts as $posts) {
				$response = $client->request('POST', 'post', [
					'form_params' => [
						'api_key' => $this->apiKey,
						'data' => $posts
					]
				]);
			}
			foreach($this->allComments as $comments) {
				$response = $client->request('POST', 'comment', [
					'form_params' => [
						'api_key' => $this->apiKey,
						'data' => $comments
					]
				]);
			}
						foreach($this->allOptions as $options) {
				$response = $client->request('POST', 'option', [
					'form_params' => [
						'api_key' => $this->apiKey,
						'data' => $options
					]
				]);
			}
			foreach($this->allUsers as $users) {
				$response = $client->request('POST', 'user', [
					'form_params' => [
						'api_key' => $this->apiKey,
						'data' => $users
					]
				]);
			}
			foreach($this->allTerms as $terms) {
				$response = $client->request('POST', 'term', [
					'form_params' => [
						'api_key' => $this->apiKey,
						'data' => $terms
					]
				]);
			}
			foreach($this->allTermsTaxonomy as $terms) {
				$response = $client->request('POST', 'term/taxonomy', [
					'form_params' => [
						'api_key' => $this->apiKey,
						'data' => $terms
					]
				]);
			}
			foreach($this->allTermsRelationships as $terms) {
				$response = $client->request('POST', 'term/relationship', [
					'form_params' => [
						'api_key' => $this->apiKey,
						'data' => $terms
					]
				]);
			}
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
		return ['status' => 'success'];
	}
}