<?php

namespace MyCloudPress\Services;

use MyCloudPress\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SyncCommentService extends Service {

	
	protected $apiKey;

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
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

	public function deleteComment($comment)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->delete('comment/' . $comment, [
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