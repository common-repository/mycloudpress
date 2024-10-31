<?php

namespace MyCloudPress\Services;

use MyCloudPress\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SyncOptionService extends Service {

    
	protected $apiKey;

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

    public function updateOption($data) {
        $client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->request('POST', 'option/sync', [
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

	public function deleteOption($option_name)
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->delete('option/' . $option_name, [
				'form_params' => [
					'api_key' => $this->apiKey,
					'data' => $option_name
				]
			]);
		} catch (RequestException $e) {
		    		    if ($e->hasResponse()) {
		        		    }
		}
	}
}