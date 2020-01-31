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

namespace BoldGrid\Connect\Authentication;

/**
* Class: Router
*
* Setup the Router.
*
* @since 2.0.0
*/
class Router {

	/**
	 * Reference to plugin installer.
	 *
	 * @since 2.0.0
	 *
	 * @var Installer
	 */
	protected $pluginInstaller;

	/**
	 * Register routes.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'rest_api_init', function () {
			$this->registerAuthenticate();
		} );
	}

	/**
	 * Setup route to activate plugins.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function registerAuthenticate() {
		register_rest_route( 'bgc/v1', '/auth/', [
			'methods' => 'POST',
			'callback' => function ( $request ) {
				$userId = $request->get_param( 'user_id' );

				// Find the requested user, or default.
				$user = $this->selectUser( $userId );

				// User found, create token and return to view.
				if ( $user ) {

					$tokenHelper = new Token;
					$accessToken = $tokenHelper->create( $user );
					$response = new \WP_REST_Response( $accessToken );

				// User not found.
				} else {
					$response = new \WP_REST_Response( [
						'errors' => [
							'name' => 'user_not_found',
							'message' => 'Unable to find a user to authenticate as.',
						],
					] );

					$response->set_status( 400 );
				}

				return $response;
			},
			'permission_callback' => function( $request ) {
				$environmentId = $request->get_param( 'environment_id' );
				$tokenVal = $request->get_param( 'token' );
				$token = new Token();

				// If this site has never connected, register it.
				$token->registerSite( $environmentId );
return true;
				return $token->remoteValidate( $tokenVal );
			},
			'args' => [
				'token' => [
					'required' => true,
					'type' => 'string',
					'description' => 'BoldGrid Authentication token',
				],
				'user_id' => [
					'required' => false,
					'type' => 'string',
					'description' => 'User ID to authenticate as',
				],
			]
		 ] );
	}

	/**
	 * Find a user to log in as.
	 *
	 * @since 2.0.0
	 *
	 * @param string $userId User Id.
	 * @return WP_User User to login as.
	 */
	private function selectUser( $userId ) {
		if ( $userId ) {
			$user = get_user_by( 'id', $userId );
		}

		if ( empty( $user ) ) {
			$login = new \Boldgrid_Connect_Login();
			$user = $login->get_user();
		}

		return $user;
	}
}
