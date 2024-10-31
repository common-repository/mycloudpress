<?php
namespace MyCloudPress\Controllers;

abstract class Controller {

	public function render($view, $params=[])
	{
		ob_start();
		if(is_array($params)) {
			foreach($params as $key => $param) {
				${$key} = $param;
			}
		}
   		require_once MCP_BBTW_PLUGIN_DIR . "/resources/views/" . $view . '.php';
   		$includedhtml = ob_get_contents();
		ob_end_clean();
		echo $includedhtml;
	}
}