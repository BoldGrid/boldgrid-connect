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
 * Description:       Safe and easy management for all of your WordPress websites. SEO, Backups, 1-click login, site transfers, and more on one dashboard.
 * Version:           2.0.0
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

if ( ! defined( 'BOLDGRID_CONNECT_FILE' ) ) {
	define( 'BOLDGRID_CONNECT_FILE', __FILE__ );
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

	/*
	$load = new Boldgrid\Library\Util\Load(
		array(
			'type'            => 'plugin',
			'file'            => plugin_basename( __FILE__ ),
			'loader'          => $loader,
			'keyValidate'     => true,
			'licenseActivate' => false,
		)
	);
	*/

	// Load the plugin.
	$plugin = new Boldgrid_Connect();
	$plugin->run();
}

// Temporary-- contents should be moved into other plugins.
include BOLDGRID_CONNECT_PATH . '/src/temp.php';

run_boldgrid_connect();

add_action( 'add_meta_boxes_admin_page_my-inspiration', function() {
	add_meta_box(
		'current_inspiration',
		esc_html__( 'Publish Website', 'boldgrid-inspirations' ),
		function () { ?>
			<p>You've deployed this site on a development environment. To make
			this website public, you'll need to transfer to a production environment.
			Head back over to WordPress Central when you're done making changes to deploy your website.</p>
			<a href="#" class="button button-primary">Publish Site</a>
		<?php
		},
		'admin_page_my-inspiration',
		'container6'
	);
}, 99 );

add_filter( 'plugin_row_meta', function ( $meta, $slug ) {
	$pluginName = 'boldgrid-connect.php';
	$length = strlen( $pluginName );
	$hasPluginFilename = substr($slug, -$length) === $pluginName;
	if ( $hasPluginFilename ) {
		$meta[] = '<a href="' . admin_url( 'tools.php' ) . '">My Connection</a>';
	}

	return $meta;
}, 10, 2 );
