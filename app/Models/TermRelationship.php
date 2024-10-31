<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TermRelationship extends Model {

	public $apiKey;
	protected $table;

	public function __construct($apiKey)
	{
		global $wpdb;

		$this->apiKey = $apiKey;

		$this->table = $wpdb->term_relationships;
	}

	public function getByObject($id)
	{
		global $wpdb;
		return $wpdb->get_row("SELECT * FROM $this->table WHERE object_id = $id");
	}

	public function count()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM $this->table");
	}

	public function get($object_id, $term_taxonomy_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $this->table WHERE object_id = $object_id AND term_taxonomy_id = $term_taxonomy_id");
	}

	public function update($object_id, $term_taxonomy_id, $data)
	{
		global $wpdb;

		return $wpdb->update($this->table, $data, array('object_id' => $object_id, 'term_taxonomy_id' => $term_taxonomy_id));
	}

	public function create($data)
	{
		global $wpdb;

		return $wpdb->insert($this->table, $data);
	}

	
	public function getRemoteAllTermRelationship()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('term/relationship', [
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

	public function restoreTermRelationshipToSite($termRelationship)
	{
		foreach($termRelationship as $tr) {
			$termRelationship = $this->get($tr->object_id, $tr->term_taxonomy_id);
			if(!is_null($termRelationship)) {
				$this->update($tr->object_id, $tr->term_taxonomy_id, [
					'term_taxonomy_id' => $tr->term_taxonomy_id,
					'term_order' => $tr->term_order,
				]);
			} else {
				$this->create([
					'object_id' => $tr->object_id,
					'term_taxonomy_id' => $tr->term_taxonomy_id,
					'term_order' => $tr->term_order,
				]);
			}
		}
	}
}