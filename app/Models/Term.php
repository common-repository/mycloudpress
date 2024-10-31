<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Term extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countTerms()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $wpdb->terms");
	}

	public function getTerm($term_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->terms WHERE term_id = $term_id");
	}

	public function updateTerm($term_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->terms, $data, array('term_id' => $term_id));
	}

	public function createTerm($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->terms, $data);
	}

	
	public function getRemoteAllTerms()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('term', [
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

	public function restoreTermsToSite($terms)
	{
		foreach($terms as $t) {
			$term = $this->getTerm($t->term_id);
			if(!is_null($term)) {
				$this->updateTerm($t->term_id, [
					'name' => $t->name,
					'slug' => $t->slug,
					'term_group' => $t->term_group,
				]);
			} else {
				$this->createTerm([
					'term_id' => $t->term_id,
					'name' => $t->name,
					'slug' => $t->slug,
					'term_group' => $t->term_group,
				]);
			}
		}
	}
}