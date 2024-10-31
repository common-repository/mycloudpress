<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TermTaxonomy extends Model {

	public $apiKey;

	protected $table;

	public function __construct($apiKey)
	{
		global $wpdb;

		$this->apiKey = $apiKey;

		$this->table = $wpdb->term_taxonomy;
	}

	public function getByObject($id)
	{
		global $wpdb;
		return $wpdb->get_row("SELECT * FROM $this->table WHERE term_taxonomy_id = $id");
	}

	public function count()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $this->table");
	}

	public function get($term_taxonomy_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $this->table WHERE term_taxonomy_id = $term_taxonomy_id");
	}

	public function update($term_taxonomy_id, $data)
	{
		global $wpdb;

		return $wpdb->update($this->table, $data, array('term_taxonomy_id' => $term_taxonomy_id));
	}

	public function create($data)
	{
		global $wpdb;

		return $wpdb->insert($this->table, $data);
	}

	
	public function getRemoteAllTermTaxonomy()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('term/taxonomy', [
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

	public function restoreTermTaxonomyToSite($termTaxonomy)
	{
		foreach($termTaxonomy as $tt) {
			$termTaxonomy = $this->get($tt->term_taxonomy_id);
			if(!is_null($termTaxonomy)) {
				$this->update($tt->term_taxonomy_id, [
					'term_id' => $tt->term_id,
					'taxonomy' => $tt->taxonomy,
					'description' => $tt->description,
					'parent' => $tt->parent,
					'count' => $tt->count,
				]);
			} else {
				$this->create([
					'term_taxonomy_id' => $tt->term_taxonomy_id,
					'term_id' => $tt->term_id,
					'taxonomy' => $tt->taxonomy,
					'description' => $tt->description,
					'parent' => $tt->parent,
					'count' => $tt->count,
				]);
			}
		}
	}
}