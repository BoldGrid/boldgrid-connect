<?php
/**
 * File: config.plugin.php
 *
 * Plugin configuration file.
 *
 * @link       https://www.boldgrid.com
 * @since      1.0.0
 *
 * @package    Boldgrid_Connect
 * @subpackage Boldgrid_Connect/admin
 * @copyright  BoldGrid.com
 * @version    $Id$
 * @author     BoldGrid <support@boldgrid.com>
 */

if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

return array(
	'ajax_calls'            => array(
		'get_plugin_version' => '/api/open/get-plugin-version',
		'get_asset'          => '/api/open/get-asset',
		'verify_site_token'  => '/v1/connect-keys/sites/token',
		'verify_env_token'   => '/v1/environments/auth',
		'validate_env'       => '/v1/register-environment',
	),
	'asset_server'          => 'https://api.boldgrid.com',
	'central_url'           => 'https://www.boldgrid.com/central',
	'plugin_name'           => 'boldgrid-connect',
	'plugin_key_code'       => 'connect',
	'main_file_path'        => BOLDGRID_CONNECT_PATH . 'boldgrid-connect.php',
	'plugin_transient_name' => 'boldgrid_connect_version_data',
);
