<?php
namespace MyCloudPress\Controllers;

use MyCloudPress\Controllers\Controller;
use MyCloudPress\Services\BackupService;
use MyCloudPress\Services\RestoreService;
use MyCloudPress\Services\StatsService;
use MyCloudPress\Services\WpSiteStatsService;

class AdminSyncController extends Controller {

	protected $apiKey;

	public function __construct()
	{
		$this->apiKey = mcp_api_key();
	}

	
	public function MCPSyncPage()
	{
			    if (!current_user_can('manage_options')) {
	        return;
	    }

	    	    	    	    
																				
		if(isset($_POST['api_key'])) {
			if(empty($_POST['api_key'])) {
				mcp_flash()->queue_flash_message('error', 'Api key cannot be empty!')->show_flash_message();
			} elseif(!mcp_check_api_key_exists($_POST['api_key'])) {
				mcp_flash()->queue_flash_message('error', 'Api key was invalid please try again!')->show_flash_message();
			}
		} 
		if(!mcp_check_api_key()) {
			return $this->render('admin/register', []);
		} else {
			return $this->render('admin/mycloudpress');
		}
	}

	public function MCPBuildMenu()
	{
		add_menu_page(
	        'My Cloud Press',
	        'MyCloudPress',
	        'manage_options',
	        'mycloudpress',
	        array($this, 'MCPSyncPage'),
	        'dashicons-cloud',
	        99
	    );
	}

	public function MCPBuildJs($hook)
	{
		if( 'toplevel_page_mycloudpress' != $hook ) return;
	    
		wp_register_script( 'mcp_ajax_script',
	        MCP_BBTW_PLUGIN_URL . '/resources/assets/js/mycloudpress.min.js',
	        array( 'jquery' )
	    );
		wp_localize_script( 'mcp_ajax_script', 'mcpAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

		wp_enqueue_script( 'jquery' );
   		wp_enqueue_script( 'mcp_ajax_script' );

	    wp_enqueue_style('mcp-ajax-style', MCP_BBTW_PLUGIN_URL . '/resources/assets/css/mycloudpress.min.css');
	}

	public function MCPBackupSite()
	{
		if(!current_user_can( 'manage_options' )) {
			wp_send_json( ['permission' => false], 403 );
		}
		wp_verify_nonce( $_REQUEST['nonce'], 'mycloudpress_backup' );
		$backupService = new BackupService($this->apiKey);
		wp_send_json_success( $backupService->run(), 200 );

				exit();
	}

	public function MCPRestoreSite()
	{
		if(!current_user_can( 'manage_options' )) {
			wp_send_json( ['permission' => false], 403 );
		}
		wp_verify_nonce( $_REQUEST['nonce'], 'mycloudpress_restore' );
		$restoreService = new RestoreService($this->apiKey);
		wp_send_json_success( $restoreService->run(), 200 );

				exit();
	}

	public function MCPStatsSite()
	{
		if(!current_user_can( 'manage_options' )) {
			wp_send_json( ['permission' => false], 403 );
		}
		$statsService = new StatsService($this->apiKey);
		wp_send_json_success( $statsService->run(), 200 );

				exit();
	}

	public function MCPWpSiteStats()
	{
		if(!current_user_can( 'manage_options' )) {
			wp_send_json( ['permission' => false], 403 );
		}
		$statsService = new WpSiteStatsService($this->apiKey);
		wp_send_json_success( $statsService->run(), 200 );

				exit();
	}
}