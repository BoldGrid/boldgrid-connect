<?php
/**
 * File: class-boldgrid-connect-admin.php
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

/**
 * Class: Boldgrid_Connect_Admin
 *
 * @since      1.0.0
 * @package    Boldgrid_Connect
 * @subpackage Boldgrid_Connect/admin
 * @author     BoldGrid <support@boldgrid.com>
 */
class Boldgrid_Connect_Admin {
	/**
	 * Prepare the plugin update class.
	 *
	 * @since 1.0.0
	 *
	 * @see Boldgrid_Connect_Admin_Update()
	 * @see Boldgrid_Connect_Admin_Update::add_hooks()
	 */
	public function prepare_plugin_update() {
		$is_cron  = ( defined( 'DOING_CRON' ) && DOING_CRON );
		$is_wpcli = ( defined( 'WP_CLI' ) && WP_CLI );

		if ( $is_cron || $is_wpcli || is_admin() ) {
			require_once BOLDGRID_CONNECT_PATH .
				'/admin/class-boldgrid-connect-admin-update.php';

			$plugin_update = new Boldgrid_Connect_Admin_Update();
			$plugin_update->add_hooks();
		}
	}
}
