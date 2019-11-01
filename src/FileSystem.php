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

namespace Boldgrid\Connect;

/**
 * Class: FileSystem
 *
 * Functions for interacting with WordPress Filesystem.
 *
 * @since      2.0.0
 */
class FileSystem {

	public function __construct() {
		$this->wpFilesystem = $this->init();

		return $this;
	}

	/**
	 * Accessor.
	 *
	 * @since 1.6
	 *
	 * @return wp_filesystem Wordpress global.
	 */
	public function get_wp_filesystem() {
		return $this->wpFilesystem;
	}

	/**
	 * Initialize the WP_Filesystem.
	 *
	 * @since 1.6
	 * @global $wp_filesystem WordPress Filesystem global.
	 */
	public function init() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	/**
	 * Save Compiled SCSS.
	 *
	 * @since 1.6
	 *
	 * @param string $content Content to save.
	 * @param string $file File to write to.
	 */
	public function save( $content, $file ) {

		// Write output to CSS file.
		$chmodFile = ( 0644 & ~ umask() );
		if ( defined( 'FS_CHMOD_FILE' ) ) {
			$chmodFile = FS_CHMOD_FILE;
		}

		return $this->wpFilesystem->put_contents( $file, $content, $chmodFile );
	}
}
