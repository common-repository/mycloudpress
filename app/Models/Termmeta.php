<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Termmeta extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countTermmeta()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->termmeta");
	}

	public function getTermmeta($meta_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->termmeta WHERE meta_id = $meta_id");
	}

	public function updateTermmeta($meta_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->termmeta, $data, array('meta_id' => $meta_id));
	}

	public function createTerm($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->termmeta, $data);
	}

	
	public function getRemoteAllTermmeta()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('termmeta', [
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

	public function restoreTermmetaToSite($termmeta)
	{
		foreach($termmeta as $tm) {
			$termmeta = $this->getTermmeta($tm->meta_id);
			if(!is_null($termmeta)) {
				$this->updateTermmeta($tm->meta_id, [
					'term_id' => $tm->term_id,
					'meta_key' => $tm->meta_key,
					'meta_value' => $tm->meta_value,
				]);
			} else {
				$this->createTermmeta([
					'meta_id' => $tm->meta_id,
					'term_id' => $tm->term_id,
					'meta_key' => $tm->meta_key,
					'meta_value' => $tm->meta_value,
				]);
			}
		}
	}
}