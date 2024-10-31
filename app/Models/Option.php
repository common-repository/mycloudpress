<?php

namespace MyCloudPress\Models;

use MyCloudPress\Models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Option extends Model {

	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function countOptions()
	{
		global $wpdb;
		return $wpdb->query("SELECT * FROM {$wpdb->options} WHERE option_name NOT LIKE ('%_transient_%');");
	}

	public function getOption($option_name)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM {$wpdb->options} WHERE option_name='$option_name' AND LENGTH(option_value) < 100000  AND option_name NOT LIKE ('%_transient_%');");
	}

	public function clearAllCache($clearSiteCache=false)
	{
		global $wpdb;

		if($clearSiteCache) {
			$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE ('_site_transient_%');");
		}

		$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE ('_transient_%');");
	}

	public function getOptionById($option_id)
	{
		global $wpdb;

		return $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_id = $option_id");
	}

	public function updateOption($option_id, $data)
	{
		global $wpdb;

		return $wpdb->update($wpdb->options, $data, array('option_id' => $option_id));
	}

	public function createOption($data)
	{
		global $wpdb;

		return $wpdb->insert($wpdb->options, $data);
	}

	
	public function getRemoteAllOptions()
	{
		$client = new Client(['base_uri' => MCP_BBTW_API_URL]);
		try {
			$response = $client->get('option', [
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
	

	public function restoreOptionsToSite($options)
	{
		foreach($options as $o) {
			$option = $this->getOptionById($o->option_id);
			$option_value = $o->option_value;
			if($o->option_name === 'siteurl' || $o->option_name  === 'home') {
				$option_value = mcp_url();
			}
			if(!is_null($option)) {
				$this->updateOption($o->option_id, [
					'option_name' => $o->option_name,
					'option_value' => $option_value,
					'autoload' => $o->autoload,
				]);
			} else {
				$this->createOption([
					'option_id' => $o->option_id,
					'option_name' => $o->option_name,
					'option_value' => $option_value,
					'autoload' => $o->autoload,
				]);
			}
		}
	}
}