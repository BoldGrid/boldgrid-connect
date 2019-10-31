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

	public function pluginPermissionCheck() {
		return true;
		return current_user_can( 'activate_plugins' );
	}

		/**
	 * List user plugins.
	 *
	 * @since 2.0.0
	 */
	private function registerPluginRemove() {
		register_rest_route( 'bgc/v1', '/plugins/', array(
			'methods' => 'DELETE',
			'callback' => function ( $request ) {
				$files = $request->get_param( 'files' );
				$deactivate = $request->get_param( 'deactivate' );

				if ( $deactivate ) {
					deactivate_plugins( $files );
				} else {
					wp_cache_delete( 'plugins', 'plugins' );
					$this->pluginInstaller->delete( $files );
				}

				$updatedPluginList = $this->pluginInstaller->list();

				$response = new \WP_REST_Response( $updatedPluginList );

				return $response;
			},
			'permission_callback' => [ $this, 'pluginPermissionCheck' ]
		) );
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
				$response = $this->pluginInstaller->list();

				$response = new \WP_REST_Response( $response );

				return $response;
			},
			'permission_callback' => [ $this, 'pluginPermissionCheck' ]
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
			'permission_callback' => [ $this, 'pluginPermissionCheck' ],
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
