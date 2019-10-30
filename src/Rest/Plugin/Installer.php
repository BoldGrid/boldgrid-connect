<?php
/**
* File: Installer.php
*
* Plugin install actions.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\Rest\Plugin;

/**
* Class: Installer
*
* Plugin install actions.
*
* @since 2.0.0
*/
class Installer
{
	public function __construct() {
		// include any needed files.
	}

	public function replace() {
		// modify these variables with your new/old plugin values
		$plugin_slug = 'wp-reset/wp-reset.php';
		$plugin_zip = 'https://downloads.wordpress.org/plugin/boldgrid-backup.latest-stable.zip';
		$old_plugin_slug = 'reset-wp/reset-wp.php';

		echo 'If things are not done in a minute <a href="plugins.php">click here to return to Plugins page</a><br><br>';
		echo 'Starting ...<br><br>';

		echo 'Check if new plugin is already installed - ';
		if (is_plugin_installed($plugin_slug)) {
			echo 'it\'s installed! Making sure it\'s the latest version.';
			upgrade_plugin($plugin_slug);
			$installed = true;
		} else {
			echo 'it\'s not installed. Installing.';
			$installed = install_plugin($plugin_zip);
		}

		if (!is_wp_error($installed) && $installed) {
			echo 'Activating new plugin.';
			$activate = activate_plugin($plugin_slug);

			if (is_null($activate)) {
				echo '<br>Deactivating old plugin.<br>';
				deactivate_plugins(array($old_plugin_slug));

				echo '<br>Done! Everything went smooth.';
			}
		} else {
			echo 'Could not install the new plugin.';
		}
	}

	public function isInstalled( $slug ) {
		if (!function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$all_plugins = get_plugins();

		if (!empty($all_plugins[$slug])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Install a plugin.
	 *
	 * @since 2.0.0
	 */
	public function install( $plugin_zip ) {
		include_once ABSPATH . 'wp-admin/includes/misc.php';
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		include_once BOLDGRID_CONNECT_PATH . 'includes/class-boldgrid-connect-upgrader.php';

		add_filter( 'upgrader_package_options', function ( $options ) {
			$options['clear_destination'] = true;
			$options['clear_working'] = true;

			return $options;
		} );

		wp_cache_flush();

		$upgrader = new \Plugin_Upgrader( new \Boldgrid_Connect_Upgrader_Skin() );
		$installed = $upgrader->install( $plugin_zip );

		return $installed;
	}

	public function upgrade( $plugin_slug ) {
		wp_cache_flush();

		$upgrader = new \Boldgrid_Connect_Upgrader();
		$upgraded = $upgrader->upgrade($plugin_slug);

		return $upgraded;
	}
}
