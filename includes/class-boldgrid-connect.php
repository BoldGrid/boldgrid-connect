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
		$this->load_rest_api();
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
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$config = new Boldgrid_Connect_Config();
		$this->loader->add_action( 'init', $config, 'setup_configs' );

		$login = new Boldgrid_Connect_Login();
		$login->setup();
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
