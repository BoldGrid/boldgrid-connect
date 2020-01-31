<?php
/**
* File: Central.php
*
* Setup Access Token authentication method.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\Authentication;

/**
* Class: Central
*
* Setup Access Token authentication method.
*
* @since 2.0.0
*/
class Central {

	protected $restServer;

	/**
	 * Connect to listeners.
	 *
	 * @since 2.0.0
	 */
	public function initialize() {
		add_action( 'rest_api_init', function( $restServer ) {

			// Store the current Rest server.
			$this->resetServer = $restServer;

			add_filter( 'rest_authentication_errors', [ $this, 'addRemoteAuth' ] );
		} );

		// Setup authentication routes.
		$router = new Router();
		$router->register();
	}

	/**
	 * Authenticate the user through BoldGrid Central.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $response Null, true or WP_Error from other auth methods.
	 * @return mixed          Null, true or WP_Error from current auth method.
	 */
	public function addRemoteAuth( $response ) {
		// Another auth method already processed the request.
		if ( ! empty( $response ) ) {
			return $response;
		}

		// Preffered method pf getting headers not working.
		// $headers = $restServer->get_headers( wp_unslash( $_SERVER ) );
		$headers = getallheaders();
		if ( ! empty( $headers['Authorization'] ) ) {
			$authValue = $headers['Authorization'];

			// Validate the access token for a user and set them as the current user.
			$tokenHelper = new Token();
			$user = $tokenHelper->getAuthenticatedUser( $authValue );
			if ( $user ) {
				wp_set_current_user( $user->ID );
				$response = true;

			// If not valid respond with error.
			} else {
				sleep( 3 );

				$response = new \WP_Error(
					'restx_logged_out',
					'Sorry, your access token is invalid.',
					[ 'status' => 401 ]
				);
			}
		}

		return $response;
	}
}