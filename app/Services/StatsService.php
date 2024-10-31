<?php

namespace MyCloudPress\Services;

use MyCloudPress\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class StatsService extends Service {
	
	protected $apiKey;

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function ping()
	{
		$error = [];
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('stats', [
				'query' => [
					'api_key' => $this->apiKey,
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
				$error = ['error' => 'true'];
		    }
		}
		if(empty($error)) {
			return true;
		}
		return false;
	}

	public function run()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('stats', [
				'query' => [
					'api_key' => $this->apiKey,
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
		$body = json_decode((string) $response->getBody());
		return $body;
	}
}