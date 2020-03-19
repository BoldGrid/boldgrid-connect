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

use BoldGrid\Connect\Option;

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
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : null;
		if ( $page === 'boldgrid-connect-central' ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		}

		add_action( 'admin_menu', [ $this, 'add_submenu' ] );
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
		return !! Option\Connect::get( 'environment_id' );
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

	/**
	 * Add the submenu item labeled Central Connection.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_submenu() {
		add_options_page(
			__( 'Central Connection', 'boldgrid-connect' ),
			__( 'Central Connection', 'boldgrid-connect' ),
			'activate_plugins',
			'boldgrid-connect-central',
			function () {
				$configs = \Boldgrid_Connect_Service::get( 'configs' );
				$centralUrl = $configs['central_url'] . '/projects?environment_id=' . Option\Connect::get( 'environment_id' );

				?>
				<div class="bg-container"> <?php
					if ( self::isConnected() ) { ?>
						<div class="bgc-connect-active">
							<h2 class="bgc-connect-active__heading"><?php print esc_html__( 'Site Connected', 'boldgrid-connect' ) ?></h2>
							<p class="bgc-connect-active__sub-heading">
								<span class="dashicons dashicons-yes-alt"></span>
								<?php print esc_html__( 'This site\'s connection is working properly.', 'boldgrid-connect' ) ?></p>
							<p>
								<?php print esc_html__( 'Log into Central and access this site\'s controls. Manage your backups, SEO, page speed and more!', 'boldgrid-connect' ) ?>
							</p>
							<a target="_blank" class="button button-primary"
								href="<?php print $centralUrl; ?>"><?php print esc_html__( 'Manage In Central', 'boldgrid-connect' ) ?></a>
						</div>
					<?php } else {
						$this->render();
					}
				?>
				</div>
			<?php
			}
		);
	}

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
	 * Get the url used for connecting a new site.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function getConnectUrl() {
		$configs = \Boldgrid_Connect_Service::get( 'configs' );

		$query = http_build_query( [
			'url' => get_site_url(),
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'site_title' => get_bloginfo( 'name' )
		] );

		return trailingslashit( $configs['central_url'] ) . 'connect/wordpress?' . $query;
	}

	/**
	 * Render the connection notice.
	 *
	 * @since 2.0.0
	 */
	public function render() {
		$connectUrl = self::getConnectUrl();
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