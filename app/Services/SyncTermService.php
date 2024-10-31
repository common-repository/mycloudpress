<?php

namespace MyCloudPress\Services;

use MyCloudPress\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SyncTermService extends Service {

	
	protected $apiKey;

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function saveTerm($term)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->post('term/sync', [
				'form_params' => [
					'api_key' => $this->apiKey,
					'data' => $term
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function savePostmeta($meta)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->request('POST', 'postmeta', [
				'form_params' => [
					'api_key' => $this->apiKey,
					'data' => $meta
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function deletePost($post_id)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->delete('post/' . $post_id, [
				'form_params' => [
					'api_key' => $this->apiKey,
					'data' => $post_id
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function saveComment($comment)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->request('POST', 'comment/sync', [
				'form_params' => [
					'api_key' => $this->apiKey,
					'data' => $comment
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function deleteTerm($term_id)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->delete('term/' . $term_id, [
				'form_params' => [
					'api_key' => $this->apiKey,
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function saveTermRelationship($termRelationship)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->post('term/relationship/sync', [
				'form_params' => [
					'api_key' => $this->apiKey,
					'data' => $termRelationship
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function deleteTermRelationship($object_id)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->delete('term/relationship/' . $object_id, [
				'form_params' => [
					'api_key' => $this->apiKey,
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function saveTermTaxonomy($data)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->post('term/taxonomy/sync', [
				'form_params' => [
					'api_key' => $this->apiKey,
					'data' => $data
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}

	public function deleteTermTaxonomy($object_id)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->delete('term/taxonomy/' . $object_id, [
				'form_params' => [
					'api_key' => $this->apiKey,
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}
}