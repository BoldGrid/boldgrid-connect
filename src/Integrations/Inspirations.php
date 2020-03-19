<?php
/**
* File: Installed.php
*
* Modifications to the installed plugin listing page.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Integrations
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\Integrations;

use BoldGrid\Connect\Option;
use BoldGrid\Connect\View\Central;

/**
* Class: Installed
*
* Modifications to the installed plugin listing page.
*
* @since 2.0.0
*/
class Inspirations {

	/**
	 * Bind any hooks.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function initialize() {
		add_action( 'add_meta_boxes_admin_page_my-inspiration', array( $this, 'addMetaBox' ), 99 );
	}

	/**
	 * Create the meta box for the inspirations dashboard informing the user that they need to deploy to production.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function addMetaBox() {
		// Prompt currently only shows on cloud wordpress staging sites, should be updated to show on any dev environment.
		if ( ! defined( 'BOLDGRID_DEMO_VERSION' ) || ! Central\ConnectNotice::isConnected() ) {
			//return;
		}

		$configs = \Boldgrid_Connect_Service::get( 'configs' );

		add_meta_box(
			'publish_website',
			esc_html__( 'Publish Website', 'boldgrid-inspirations' ),
			function () use ( $configs ) {
				printf(
					'<p>%1$s</p><a target="_blank" href="' . $configs['central_url'] . '/projects?environment_id=' . Option\Connect::get( 'environment_id' ) .
						'" class="button button-primary">%2$s</a>',
					esc_html__( 'You\'ve deployed this site on a development environment. To make this website public, ' .
					'you\'ll need to transfer to a production environment. Head back over to WordPress Central '.
					'when you\'re done making changes to deploy your website.' ),
					esc_html__( 'Publish Site' )
				);
			},
			'admin_page_my-inspiration',
			'container1'
		);
	}
}