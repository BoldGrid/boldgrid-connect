<?php
/**
 * Class: FileSystem
 *
 * Functions for interacting with WordPress Filesystem.
 *
 * @since      2.0.0
 * @package    BoldGrid\Connect
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

namespace Boldgrid\Connect\Analytics;

use BoldGrid\Connect\Analytics\Option;

/**
 * Class: FileSystem
 *
 * Functions for interacting with WordPress Filesystem.
 *
 * @since      2.0.0
 */
class Views {

	protected $viewCounted = false;
	protected $statusCode;

	public function initialize() {
		add_filter( 'status_header', function ( $statusHeader, $code ) {
			$this->statusCode = $code;
			return $statusHeader;
		}, 10, 3 );

		add_action( 'wp_print_footer_scripts', function () {
			if ( ! $this->viewCounted && 200 === $this->statusCode ) {
				$this->countView();
			}
		} );
	}

	public function countView() {
		$this->viewCounted = true;
		$date = $this->getDateFormat();
		$value = Option::get( $date );
		$value++;
		Option::update( $date, $value );

		error_log( print_r( get_option( 'boldgrid_connect_analytics' ), 1 ) );
	}

	public function getDateFormat() {
		return date( 'Y-m-d' );
	}
}
