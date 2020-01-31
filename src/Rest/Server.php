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

use BoldGrid\Connect;

/**
* Class: Server
*
* Setup the Rest Server extension.
*
* @since 2.0.0
*/
class Server {

	/**
	 * Bind necessarry listeners for the REST API endpoints.
	 *
	 * @since 2.0.0
	 */
	public function initialize() {

		// Allow for remote authetication to the API by validating tokens with BoldGrid Central.
		$authentication = new Connect\Authentication\Central();
		$authentication->initialize();

		// Setup plugin Routes.
		$pluginRouter = new Connect\Plugin\Router();
		$pluginRouter->register();

		// Setup Theme Routes.
		$themeRouter = new Connect\Theme\Router();
		$themeRouter->register();

		// Setup Options Routes.
		$optionRouter = new Connect\Option\Router();
		$optionRouter->register();

		// Setup Site Health Routes.
		$healthRouter = new Connect\Health\Router();
		$healthRouter->register();

		// Setup Site Cache Routes.
		$cacheRouter = new Connect\Cache\Router();
		$cacheRouter->register();

		// Setup Site Cache Routes.
		$analyticsRouter = new Connect\Analytics\Router();
		$analyticsRouter->register_routes();

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
