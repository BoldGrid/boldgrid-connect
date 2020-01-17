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

namespace BoldGrid\Connect\Rest\Health;

/**
* Class: Router
*
* Setup the Router.
*
* @since 2.0.0
*/
class Router {

	/**
	 * Register routes.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'rest_api_init', function () {
			$this->registerScan();
		} );
	}

	/**
	 * Setup route to remove plugins.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function registerScan() {
		register_rest_route( 'bgc/v1', '/health/', [
			'methods' => 'GET',
			'callback' => function () {
				$response = new \WP_REST_Response( $this->getSiteHealth() );

				return $response;
			},
			'permission_callback' => [ $this, 'pluginPermissionCheck' ],
		] );
	}

	/**
	 * Get the results from the site heatlth page.
	 *
	 * @since 2.0.0
	 *
	 * @return array Site Health Results.
	 */
	protected function getSiteHealth() {
		include_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
		include_once ABSPATH . 'wp-admin/includes/screen.php';
		include_once ABSPATH . 'wp-admin/includes/update.php';
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		include_once ABSPATH . 'wp-admin/includes/misc.php';


		// Disable Rest Test & async tests
		add_filter( 'site_status_tests', function ( $tests ) {
			$tests['async'] = [];
			unset( $tests['direct']['rest_availability'] );

			return $tests;
		} );

		// Capture all the results as they run.
		$allTests = [];
		add_filter( 'site_status_test_result', function ( $result ) use ( &$allTests ) {
			$allTests[] = $result;
			return $result;
		} );

		set_current_screen('site-health');
		$siteHealth = new \WP_Site_Health();
		$siteHealth->enqueue_scripts();

		return $allTests;
	}

	/**
	 * Make sure current can activate plugins for all Plugin api calls.
	 *
	 * @since 2.0.0
	 *
	 * @return boolean Has Access?
	 */
	public function pluginPermissionCheck() {
		return current_user_can( 'activate_plugins' );
	}
}
