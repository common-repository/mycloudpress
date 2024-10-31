<?php
/*
Plugin Name: My Cloud Press
Plugin URI:  https://mycloudpress.com
Description: Realtime backups to the cloud!
Version:     1.0.1
Author:      bigbeartechworld.com
Author URI:  bigbeartechworld.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: MCP
Domain Path: /languages
*/

use MyCloudPress\App;
use MyCloudPress\Controllers\AdminEventsController;
use MyCloudPress\Controllers\AdminSyncController;

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'MCP_BBTW_START', microtime(true) );
define( 'MCP_BBTW_VERSION', '1.0.0' );
define( 'MCP_BBTW_MINIMUM_WP_VERSION', '3.7' );
define( 'MCP_BBTW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MCP_BBTW_PLUGIN_URL', WP_PLUGIN_URL . '/mycloudpress' );
define( 'MCP_BBTW_API_URL', 'https://mycloudpress.com/api/v1/' );

require MCP_BBTW_PLUGIN_DIR . '/vendor/autoload.php';

$mcpApp = new App;
$mcpAdminSyncController = new AdminSyncController;
$mcpAdminEventsController = new AdminEventsController;

register_activation_hook( __FILE__, array( $mcpApp, 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( $mcpApp, 'plugin_deactivation' ) );

add_action('admin_menu', array($mcpAdminSyncController, 'MCPBuildMenu'));

add_action( 'admin_enqueue_scripts', array($mcpAdminSyncController, 'MCPBuildJs') );

add_action( 'wp_ajax_backup_with_bigbearwpsync', array($mcpAdminSyncController, 'MCPBackupSite') );
add_action( 'wp_ajax_restore_with_bigbearwpsync', array($mcpAdminSyncController, 'MCPRestoreSite') );
add_action( 'wp_ajax_stats_with_bigbearwpsync', array($mcpAdminSyncController, 'MCPStatsSite') );
add_action( 'wp_ajax_site_stats_with_bigbearwpsync', array($mcpAdminSyncController, 'MCPWpSiteStats') );

add_action( 'user_register', array($mcpAdminEventsController, 'onSaveUser') );
add_action( 'wpmu_new_user', array($mcpAdminEventsController, 'onSaveUser') );
add_action( 'profile_update', array($mcpAdminEventsController, 'onSaveUser') );
add_action( 'deleted_user', array($mcpAdminEventsController, 'onDeleteUser') );

add_action( 'updated_user_meta', array($mcpAdminEventsController, 'onSaveUsermeta'));
add_action( 'added_user_meta', array($mcpAdminEventsController, 'onSaveUsermeta'));
add_action( 'deleted_user_meta', array($mcpAdminEventsController, 'onDeleteUsermeta'));

add_action('save_post', array($mcpAdminEventsController, 'onSavePost'));
add_action('wp_insert_post', array($mcpAdminEventsController, 'onSavePost'));
add_action('add_attachment', array($mcpAdminEventsController, 'onSavePost'));
add_action('edit_attachment', array($mcpAdminEventsController, 'onSavePost'));
add_action('deleted_post', array($mcpAdminEventsController, 'onDeletePost'));

add_action('updated_post_meta', array($mcpAdminEventsController, 'onSavePostmeta'));
add_action('added_post_meta', array($mcpAdminEventsController, 'onSavePostmeta'));
add_action('deleted_post_meta', array($mcpAdminEventsController, 'onDeletePostmeta'));

add_action('edit_comment', array($mcpAdminEventsController, 'onSaveComment'));
add_action('spammed_comment', array($mcpAdminEventsController, 'onSaveComment'));
add_action('unspammed_comment', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_post', array($mcpAdminEventsController, 'onSaveComment'));
add_action('trashed_comment', array($mcpAdminEventsController, 'onSaveComment'));
add_action('untrashed_comment', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_hold_', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_hold_pingback', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_hold_trackback', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_unapproved_', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_unapproved_pingback', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_unapproved_trackback', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_approved_', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_approved_pingback', array($mcpAdminEventsController, 'onSaveComment'));
add_action('comment_approved_trackback', array($mcpAdminEventsController, 'onSaveComment'));
add_action('delete_comment', array($mcpAdminEventsController, 'onDeleteComment'));

add_action('updated_comment_meta', array($mcpAdminEventsController, 'onAddedCommentMeta'));
add_action('added_comment_meta', array($mcpAdminEventsController, 'onAddedCommentMeta'));
add_action('deleted_comment_meta', array($mcpAdminEventsController, 'onDeleteCommentMeta'));

add_action('created_term', array($mcpAdminEventsController, 'onCreateTerm'));
add_action('edited_term', array($mcpAdminEventsController, 'onSaveTerm'));
add_action('delete_term', array($mcpAdminEventsController, 'onDeleteTerm'));

add_action('updated_term_meta', array($mcpAdminEventsController, 'onAddedTermMeta'));
add_action('added_term_meta', array($mcpAdminEventsController, 'onAddedTermMeta'));
add_action('deleted_term_meta', array($mcpAdminEventsController, 'onDeleteTermMeta'));

add_action('added_term_relationship', array($mcpAdminEventsController, 'onAddedTermRelationship'));
add_action('deleted_term_relationships', array($mcpAdminEventsController, 'onDeletedTermRelationship'));

add_action('edited_term_taxonomy', array($mcpAdminEventsController, 'onEditedTermTaxonomy'));
add_action('deleted_term_taxonomy', array($mcpAdminEventsController, 'onDeletedTermTaxonomy'));

add_action('updated_option', array($mcpAdminEventsController, 'onSaveOption'));
add_action('added_option', array($mcpAdminEventsController, 'onSaveOption'));
add_action('deleted_option', array($mcpAdminEventsController, 'onDeleteOption'));

function mcp_output_buffer() {
	  			if(session_id() == '') {
        session_start();
    }
}
add_action('init', 'mcp_output_buffer');
