<?php

namespace MyCloudPress\Services;

use MyCloudPress\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SyncCommentmetaService extends Service {

	
	protected $apiKey;

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

    public function save($data)
    {
        $client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->request('POST', 'commentmeta', [
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

    public function delete($data)
    {
        $client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->request('DELETE', 'commentmeta', [
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
	
}