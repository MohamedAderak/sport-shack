<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swatch_AovUp_Freemium {
	private $api_server = 'https://dw.aovup.com/';

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'install_notice' ) );
		add_action( 'admin_init', array( $this, 'check_to_dismiss_notice_forever' ) );
	}

	/**
     * Hidden the Core install notice 4ever
     *
	 * @return void
	 */
	public function check_to_dismiss_notice_forever() {
		$dismiss_option = filter_input( INPUT_GET, 'aovup-swatch-notice-dismiss' );
		if ( is_string( $dismiss_option ) ) {
			update_option( "aovup_dismissed_swatch", true );
			wp_send_json_success();
		}
	}

	/**
     * Showing the notice to install Core plugin
     *
	 * @return void
	 */
	public function install_notice() {
        global $aovup_is_shown_core_notice;
		$plugins_to_install = $this->get_inactive_required_plugins();
        $is_dismissed_notice = get_option('aovup_dismissed_swatch');

		if ( empty( $plugins_to_install ) || $aovup_is_shown_core_notice === true || $is_dismissed_notice ) {
			return;
		}
		$aovup_is_shown_core_notice = true;
		?>
        <div id="aovup-core-install-notice" class="updated aovup-core-install-notice notice is-dismissible">
			<?php wp_nonce_field( 'aovup-core-installer-nonce', 'aovup-core-installer-notice-nonce' ); ?>
            <div class="aovup-core-install-notice-logo">
                <img width="100"
                     src="<?php echo esc_url( WCVS_PLUGIN_URL.'assets/images/aio-upgrade.png' ); ?>"
                     alt="<?php esc_html_e( 'AovUp Logo', '' ); ?>"/>
            </div>
            <div class="aovup-core-install-notice-content">
                <h2><?php echo esc_html( 'Upgrade your Checkout for Free', '' ); ?></h2>
                <p><?php printf( "Experience the best free WooCommerce checkout solution for improved conversions. It offers a one-page checkout, a checkout field editor, and more. Try it today!" ); ?></p>
                <a class="aovup-core-install-button button button-primary"
                   href="https://aovup.com/plugins/aio-checkout/?utm_source=dashboard&utm_medium=banner&utm_campaign=aio-free-offer&utm_id=dashboard-c"
                   target="_blank" rel="noreferrer">
                    <span class="button-text">
                    <?php echo esc_html__( 'Download AIO Checkout Plugin', '' ); ?>
                    </span>
                    <span class="dashicons dashicons-external"></span>
                </a>
            </div>
        </div>
		<?php
	}

	/**
	 * Get the list of inactive required plugins
	 *
	 * @return array
	 */
	private function get_inactive_required_plugins() {
		$list_of_plugin_to_install = $this->get_required_plugins();
		foreach ( $list_of_plugin_to_install as $plugin_slug => $plugin_data ) {
			if ( self::is_plugin_activated( $plugin_data['path'] ) ) {
				unset( $list_of_plugin_to_install[ $plugin_slug ] );
			}
		}

		return $list_of_plugin_to_install;
	}

	/**
	 * Get the list of plugin that required for installing
	 *
	 * @return array
	 */
	private function get_required_plugins() {
		return array(
			'woosuite-core' => array(
				'name'         => __( 'AovUp Core', '' ),
				'path'         => 'woosuite-core/woosuite-core.php',
				'download_url' => $this->get_plugin_download_link( 'woosuite-core-14' ),
			)
		);
	}

	/**
	 * check if plugin is active by a given path
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public static function is_plugin_activated( $path ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( $path );
	}

	private function get_plugin_download_link( $slug ) {
		return $this->api_server . '/wp-content/uploads/ofs-files/' . $slug . '.zip?wp_url=' . site_url();
	}

}
if ( current_user_can( 'install_plugins' ) ) {
	new Swatch_AovUp_Freemium();
}