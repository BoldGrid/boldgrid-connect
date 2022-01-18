<?php
/**
 * File: class-boldgrid-connect.php
 *
 * @link       https://www.boldgrid.com
 * @since      1.0.0
 *
 * @package    Boldgrid_Connect
 * @subpackage Boldgrid_Connect/includes
 * @copyright  BoldGrid.com
 * @version    $Id$
 * @author     BoldGrid <support@boldgrid.com>
 */

 use BoldGrid\Connect;

/**
 * Class: Boldgrid_Connect
 *
 * @since      1.0.0
 * @package    Boldgrid_Connect
 * @subpackage Boldgrid_Connect/includes
 * @author     BoldGrid <support@boldgrid.com>
 */
class Boldgrid_Connect {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Boldgrid_Connect_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->load_analytics();
		$this->load_rest_api();
		$this->upgrade();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Boldgrid_Connect_Loader. Orchestrates the hooks of the plugin.
	 * - Boldgrid_Connect_Service. Handles service objects for the plugin.
	 * - BoldGrid_Connect_Config. Defines configuration properties of the plugin.
	 * - Boldgrid_Connect_Login. Facilitates use of a remote secure login token.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @see Boldgrid_Connect_Loader()
	 */
	private function load_dependencies() {
		require_once BOLDGRID_CONNECT_PATH . '/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin.
		 */
		require_once BOLDGRID_CONNECT_PATH . '/includes/class-boldgrid-connect-loader.php';

		/**
		 * The class responsible for handling service objects.
		 */
		require_once BOLDGRID_CONNECT_PATH . '/includes/class-boldgrid-connect-service.php';

		/**
		 * The class responsible for loading the configuration array as a service object.
		 */
		require_once BOLDGRID_CONNECT_PATH . '/includes/class-boldgrid-connect-config.php';

		/**
		 * The class responsible for login via secure token.
		 */
		require_once BOLDGRID_CONNECT_PATH . '/includes/class-boldgrid-connect-login.php';

		/**
		 * The class responsible for login via secure token.
		 */
		require_once BOLDGRID_CONNECT_PATH . '/includes/class-boldgrid-connect-upgrade.php';

		$this->loader = new Boldgrid_Connect_Loader();
	}

	/**
	 * Load the rest API.
	 *
	 * @since 2.0.0
	 */
	private function load_rest_api() {
		$server = new Connect\Rest\Server();
		$server->initialize();
	}

	/**
	 * Count front end page views.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function load_analytics() {
		$views = new Connect\Analytics\Views();
		$views->initialize();
	}

	/**
	 * Adds version upgrade specific functionality.
	 *
	 * @return void
	 */
	public function upgrade() {
		$upgrade = new Boldgrid_Connect_Upgrade();
		$this->loader->add_action( 'plugins_loaded', $upgrade, 'upgrade_db_check' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$this->overrideConfigs();

		$config = new Boldgrid_Connect_Config();
		$this->loader->add_action( 'init', $config, 'setup_configs' );

		$login = new Boldgrid_Connect_Login();
		$login->setup();

		$connectNotice = new Connect\View\Central\ConnectNotice();
		$connectNotice->initialize();

		$installedPlugins = new Connect\Plugin\Installed();
		$installedPlugins->initialize();

		$inspirations = new Connect\Integrations\Inspirations();
		$inspirations->initialize();
	}

	/**
	 * Override the configs.
	 *
	 * @since 2.0.0
	 */
	private function overrideConfigs() {
		$optionConfigs = get_option( 'bg_connect_configs', [] );

		add_filter( 'BoldgridDemo/configs', function( $configs ) use ( $optionConfigs ) {
			$configs['servers']['asset'] = ! empty( $optionConfigs['asset_server'] ) ? $optionConfigs['asset_server'] : $configs['servers']['asset'];
			return $configs;
		} );

		// Inspirations
		add_filter( 'boldgrid_inspirations_configs', function ( $configs ) use ( $optionConfigs ) {
			$configs[ 'asset_server' ] = ! empty( $optionConfigs['asset_server'] ) ? $optionConfigs['asset_server'] : $configs['servers']['asset'];
			return $configs;
		} );

		// BoldGrid Library
		add_filter( 'Boldgrid\Library\Configs\set', function( $configs ) use ( $optionConfigs ) {
			$configs[ 'api' ] = ! empty( $optionConfigs['asset_server'] ) ? $optionConfigs['asset_server'] : $configs[ 'api' ];
			return $configs;
		} );

		// BoldGrid Connect Plugin.
		add_filter( 'boldgrid_connect_config_setup_configs', function( $configs ) use ( $optionConfigs ) {
			$configs['asset_server'] = ! empty( $optionConfigs['asset_server'] ) ? $optionConfigs['asset_server'] : $configs[ 'asset_server' ];
			$configs['central_url'] = ! empty( $optionConfigs['central_url'] ) ? $optionConfigs['central_url'] : $configs[ 'central_url' ];
			return $configs;
		} );

		/**
		 * Filters the bg_connect_configs option to merge option with static configs provided.
		 *
		 * @param false|mixed $value   Pre-option value. Default false.
		 * @param string      $option  Option name.
		 * @param mixed       $default Default option value.
		 * @return false|mixed (Maybe) filtered pre-option value.
		 */
		add_filter( 'pre_option_bg_connect_configs', function( $pre_option, $option, $default ) use ( $optionConfigs ) {
			$conf = \Boldgrid_Connect_Service::get( 'configs' );		 
			return array_merge( $conf, $optionConfigs );
		}, 10, 3 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Boldgrid_Connect_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
