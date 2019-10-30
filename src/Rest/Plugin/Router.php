<?php
/**
* File: Router.php
*
* Setup the Router.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\Rest\Plugin;

/**
* Class: Router
*
* Setup the Router.
*
* @since 2.0.0
*/
class Router {

	protected $pluginInstaller;

	public function register() {
		$this->pluginInstaller = new Installer();

		add_action( 'rest_api_init', function () {
			$this->registerInstallPlugin();
			$this->registerPluginList();
			$this->registerPluginRemove();
		} );
	}

	/**
	 * List user plugins.
	 *
	 * @since 2.0.0
	 */
	private function registerPluginList() {
		register_rest_route( 'bgc/v1', '/plugins/', array(
			'methods' => 'GET',
			'callback' => function () {
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$response = [];
				foreach( \get_plugins() as $filePath => $plugin ) {
					$plugin['File'] = $filePath;
					$response[] = $plugin;
				}

				$response = new \WP_REST_Response( $response );

				return $response;
			},
			/*'permission_callback' => function () {
				return current_user_can( 'activate_plugins' );
			},*/
		) );
	}

	private function registerInstallPlugin() {
		register_rest_route( 'bgc/v1', '/plugins/', array(
			'methods' => 'POST',
			'callback' => function ( $request ) {
				$param = $request->get_param( 'slug' );
				$param = 'https://downloads.wordpress.org/plugin/boldgrid-backup.latest-stable.zip';
				$data = [
					'plugin-activated' => [	$param ]
				];

				$success = $this->pluginInstaller->install( $param );

				// Create the response object
				$response = new \WP_REST_Response( $data );

				// Add a custom status code
				$response->set_status( 201 );

				return $response;
			},
			/*'permission_callback' => function () {
				return current_user_can( 'activate_plugins' );
			},*/
			'args' => [
				'slug' => array(
					'sanitize_callback' => function($param, $request, $key) {
						return sanitize_title( $param );
					}
				),
			]
		) );
	}
}
