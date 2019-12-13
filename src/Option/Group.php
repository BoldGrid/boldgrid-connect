<?php
/**
 * Class: Option
 *
 * Option accessors within a namespace.
 *
 * @since      2.0.0
 * @package    BoldGrid\Connect
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

namespace BoldGrid\Connect\Option;

/**
 * Class: Option
 *
 * Option accessors within a namespace.
 *
 * @since      2.0.0
 */
abstract class Group {

	const OPTION_NAMESPACE = 'boldgrid_plugin';

	/**
	 * Retrieve an option from the stored list of editor options.
	 *
	 * @since 1.3
	 *
	 * @param string $key Index of value.
	 * @param mixed  $default Default value if not found.
	 *
	 * @return mixed editor option
	 */
	public static function get( $key, $default = null ) {
		$option = get_option( static::OPTION_NAMESPACE, array() );
		return ! empty( $option[ $key ] ) ? $option[ $key ] : $default;
	}

	/**
	 * Store an option for the plugin in a single option.
	 *
	 * @since 1.3
	 *
	 * @param string $key Name of value of value.
	 * @param mixed  $value Value to store.
	 */
	public static function update( $key, $value ) {
		$option = get_option( static::OPTION_NAMESPACE, array() );
		$option = is_array( $option ) ? $option : array();
		$option[ $key ] = $value;
		update_option( static::OPTION_NAMESPACE, $option );
	}

	/**
	 * Delete key from options array.
	 *
	 * @since 1.6
	 *
	 * @param  string $key
	 */
	public static function delete( $key ) {
		$option = get_option( static::OPTION_NAMESPACE, array() );
		if ( isset( $option[ $key ] ) ) {
			unset( $option[ $key ] );
			update_option( static::OPTION_NAMESPACE, $option );
		}
	}

	/**
	 * Delete the BoldGrid Editor Option.
	 *
	 * @since 1.6
	 */
	public static function clear() {
		delete_option( static::OPTION_NAMESPACE );
	}
}
