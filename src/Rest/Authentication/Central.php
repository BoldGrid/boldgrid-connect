<?php
/**
* File: Central.php
*
* Setup BoldGrid Central authentication method.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\Rest\Authentication;

/**
* Class: Central
*
* Setup BoldGrid Central authentication method.
*
* @since 2.0.0
*/
class Central {

	/**
	 * Connect to listeners.
	 *
	 * @since 2.0.0
	 */
	public function initialize() {
		$this->addRemoteAuth();
	}

	public function addRemoteAuth() {
		add_action( 'rest_api_init', function( $restServer ) {
			// Preffered method pf getting headers not working.
			// $headers = $restServer->get_headers( wp_unslash( $_SERVER ) );
			$headers = getallheaders();
			if ( ! empty( $headers['Authorization'] ) ) {
				$authValue = $headers['Authorization'];

				// If valid, set the user.
				if ( $authValue ) {
					$users = get_users();
					wp_set_current_user( $users[0]->ID );
					$response = true;

				// If not valid respond with error.
				} else {
					$response = new WP_Error(
						'restx_logged_out',
						'Sorry, you must be logged in to make a request.',
						[ 'status' => 401 ]
					);
				}
			}

			add_filter( 'rest_authentication_errors', function( $response ) use ( $restServer ) {
			// Another auth method already processed the request.
				if ( ! empty( $response ) ) {
					return $response;
				}

				return $response;
			} );
		} );
	}
}