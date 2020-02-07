<?php
/**
* File: Server.php
*
* Setup the Rest Router extension.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\View\Central;

/**
* Class: Router
*
* Setup the Rest Server extension.
*
* @since 2.0.0
*/
class Page {

	public function initialize() {
		add_action( 'admin_menu', [ $this, 'add_submenu' ] );
	}

	public function add_submenu() {
		add_submenu_page(
			'tools.php',
			__( 'BoldGrid Connect', 'boldgrid-connect' ),
			__( 'BoldGrid Connect', 'boldgrid-connect' ),
			'activate_plugins',
			'boldgrid-connect-central',
			[ $this, 'render' ],
		);
	}

	public function render() {

		wp_enqueue_style( 'bgc-connect-styles',
			plugins_url( './style.css', __FILE__ ), array(), BOLDGRID_CONNECT_VERSION );

		include __DIR__ . './Content.php';
	}

}