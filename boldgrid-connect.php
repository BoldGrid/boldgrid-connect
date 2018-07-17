<?php
/**
 * File: boldgrid-connect.php
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.boldgrid.com
 * @since             1.0.0
 * @package           Boldgrid_Connect
 *
 * @wordpress-plugin
 * Plugin Name:       BoldGrid Connect
 * Plugin URI:        https://www.boldgrid.com
 * Description:       Toolkit for BoldGrid Connect functionality.
 * Version:           1.0.0-alpha.2
 * Author:            BoldGrid
 * Author URI:        https://www.boldgrid.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       boldgrid-connect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'BOLDGRID_CONNECT_VERSION' ) ) {
	define( 'BOLDGRID_CONNECT_VERSION', implode( get_file_data( __FILE__, array( 'Version' ), 'plugin' ) ) );
}

if ( ! defined( 'BOLDGRID_CONNECT_PATH' ) ) {
	define( 'BOLDGRID_CONNECT_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require BOLDGRID_CONNECT_PATH . '/includes/class-boldgrid-connect.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_boldgrid_connect() {
	// Load the BoldGrid Library.
	$loader = require BOLDGRID_CONNECT_PATH . '/vendor/autoload.php';

	$load = new Boldgrid\Library\Util\Load(
		array(
			'type'            => 'plugin',
			'file'            => plugin_basename( __FILE__ ),
			'loader'          => $loader,
			'keyValidate'     => true,
			'licenseActivate' => false,
		)
	);

	// Load the plugin.
	$plugin = new Boldgrid_Connect();
	$plugin->run();
}

run_boldgrid_connect();
