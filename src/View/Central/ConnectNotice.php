<?php
/**
* File: Server.php
*
* Setup the Rest Router extension.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\View\Central;

/**
* Class: Router
*
* Setup the Rest Server extension.
*
* @since 2.0.0
*/
class ConnectNotice {

	/**
	 * Setup hooks for pages that show notice.
	 *
	 * @since 2.0.0
	 */
	public function initialize() {
		add_action( 'admin_init', function () {
			global $pagenow;

			if ( ! current_user_can( 'manage_options' ) && ! self::isConnected() ) {
				return;
			}

			if (
				( 'index.php' === $pagenow || 'plugins.php' === $pagenow )
				&& ! self::isConnected()
				&& current_user_can( 'manage_options' )
			) {
				add_action( 'admin_notices', [ $this, 'render'] );
				add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
				//add_action( 'admin_menu', [ $this, 'add_submenu' ] );
			}
		} );
	}

	/**
	 * Is this site connected.
	 *
	 * @since 2.0.0
	 *
	 * @return boolean
	 */
	public static function isConnected() {
		return false;
	}

	/**
	 * Enqueue Scripts.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'bgc-connect-styles',
			plugins_url( './assets/style/admin.css', BOLDGRID_CONNECT_FILE ), array(), BOLDGRID_CONNECT_VERSION );

		wp_enqueue_script( 'bgc-connect-script',
			plugins_url( './assets/js/admin.js', BOLDGRID_CONNECT_FILE ), array( 'jquery' ), BOLDGRID_CONNECT_VERSION, true );
	}

	/*
	public function add_submenu() {
		add_submenu_page(
			'tools.php',
			__( 'BoldGrid Connect', 'boldgrid-connect' ),
			__( 'BoldGrid Connect', 'boldgrid-connect' ),
			'activate_plugins',
			'boldgrid-connect-central',
			[ $this, 'render' ],
		);
	}*/

	/**
	* Prints a TOS blurb used throughout the connection prompts.
	*
	* @since 2.0.0
	*
	* @echo string
	*/
	public static function termsOfService() {
		printf(
			wp_kses(
				/* Translators: placeholders are links. */
				__( 'By clicking the <strong>Connect to Central</strong> button, you agree to our <a href="%1$s" target="_blank">Terms of Service</a> and our <a href="%2$s" target="_blank">privacy policy</a>.', 'boldgrid-connect' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
					'strong' => true,
				)
			),
			'https://www.boldgrid.com/terms-of-service/',
			'https://www.boldgrid.com/software-privacy-policy/'
		);
	}

	/**
	 * Render the connection notice.
	 *
	 * @since 2.0.0
	 */
	public function render() {
		$configs = \Boldgrid_Connect_Service::get( 'configs' );

		$query = http_build_query( [
			'url' => get_site_url(),
			'nonce' => wp_create_nonce( 'wp_rest' )
		] );

		$connectUrl = trailingslashit( $configs['central_url'] ) . 'connect/wordpress?' . $query;
		?>

		<div class="bgc-panel bgc-connect-prompt">
			<div class="bgc-connect-prompt__attn">
				<span class="dashicons dashicons-info"></span>
				<?php esc_html_e( 'Finish setup by connecting to Central to unlock multiple WordPress environments,
					peformance optimization, site protection and more!' ); ?>
				<span class="notice-dismiss" title="Dismiss this notice"></span>
			</div>
			<div class="bgc-connect-prompt__body">
				<div class="bgc-connect-prompt__logo">
					<a href="https://www.boldgrid.com"><img
						src="<?php echo plugins_url( '/assets/img/boldgrid-logo-vertical-black.svg', BOLDGRID_CONNECT_FILE ) ?>"
						alt="<?php esc_attr_e( 'Connect your site', 'boldgrid-connect' ); ?>"
						/></a>
				</div>
				<div class="bgc-connect-prompt__description">
					<h2><?php esc_html_e( 'Optimize your Workflow and Connect to Central' ); ?></h2>
					<p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt vestibulum nisl, at egestas libero fringilla vel. Aenean sodales malesuada euismod. Interdum et malesuada fames ac ante ipsum primis in faucibus.' ); ?></p>
					<p><?php esc_html_e( 'Nunc sit amet mollis magna, id sagittis turpis. Integer sit amet ultricies nisi. Sed sit amet dui ut massa lobortis mattis. Ut nec ligula at turpis sodales dapibus nec sed lectus. '); ?></p>
					<div class="bgc-connect-prompt__description__action">
						<a class="button-primary" target="_blank" href="<?php echo $connectUrl ?>"><?php
							esc_html_e( 'Connect to Central' ); ?></a> <?php echo self::termsOfService() ?><span>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
}