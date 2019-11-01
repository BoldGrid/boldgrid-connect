<?php
/**
* File: Server.php
*
* Setup the Rest Server extension.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\Rest;

/**
* Class: Server
*
* Setup the Rest Server extension.
*
* @since 2.0.0
*/
class Server {

	/**
	 * Bind all necessarry listeners for the REST API endpoints.
	 *
	 * @since 2.0.0
	 */
	public function initialize() {

		// Allow for remote authetication to the API by validating tokens with BoldGrid Central.
		$authentication = new Authentication\Central();
		$authentication->initialize();

		// Setup all plugin Routes.
		$pluginRouter = new Plugin\Router();
		$pluginRouter->register();

		// Setup all plugin Routes.
		$themeRouter = new Theme\Router();
		$themeRouter->register();

		$this->enableHeadCors();
	}

	/**
	 * Force enable cors request for HEAD requests.
	 *
	 * @since 2.0.0
	 */
	private function enableHeadCors() {
		add_action( 'send_headers', function() {
			if ( ! did_action('rest_api_init') && $_SERVER['REQUEST_METHOD'] == 'HEAD' ) {
				header( 'Access-Control-Allow-Origin: *' );
				header( 'Access-Control-Expose-Headers: Link' );
				header( 'Access-Control-Allow-Methods: HEAD' );
			}
		} );
	}
}
