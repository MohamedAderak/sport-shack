<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverMenu' ) ) {
	class WPCleverMenu {
		function __construct() {
			// do nothing, moved to WPCleverDashboard
		}
	}

	new WPCleverMenu();
}

if ( ! class_exists( 'WPCleverDashboard' ) ) {
	class WPCleverDashboard {
		function __construct() {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
			add_action( 'wp_ajax_wpc_get_plugins', [ $this, 'ajax_get_plugins' ] );
			add_action( 'wp_ajax_wpc_get_suggestion', [ $this, 'ajax_get_suggestion' ] );
		}

		function enqueue_scripts() {
			wp_enqueue_style( 'wpc-dashboard', WPC_URI . 'includes/dashboard/css/dashboard.css' );
			wp_enqueue_script( 'wpc-dashboard', WPC_URI . 'includes/dashboard/js/backend.js', [ 'jquery' ] );
			wp_localize_script( 'wpc-dashboard', 'wpc_dashboard_vars', [
					'nonce' => wp_create_nonce( 'wpc_dashboard' ),
				]
			);
		}

		function admin_menu() {
			add_menu_page(
				'WPClever',
				'WPClever',
				'manage_options',
				'wpclever',
				[ $this, 'admin_menu_content' ],
				WPC_URI . 'includes/dashboard/images/wpc-icon.svg',
				26
			);
			add_submenu_page( 'wpclever', 'WPC About', 'About', 'manage_options', 'wpclever' );
		}

		function admin_menu_content() {
			add_thickbox();
			?>
            <div class="wpclever_page wpclever_welcome_page wrap">
                <h1>WPClever | Make clever moves</h1>
                <div class="card">
                    <h2 class="title">About</h2>
                    <p>
                        We are a team of passionate developers of plugins for WordPress, whose aspiration is to bring smart utilities and functionalities to life for WordPress users, especially for those on WooCommerce platform. Visit our website:
                        <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>
                    </p>
                </div>
                <div class="card wpclever_plugins">
                    <h2 class="title">Plugins
                        <span class="wpclever_plugins_order"><a href="#" class="wpclever_plugins_order_a" data-o="p">popular</a> |
						<a href="#" class="wpclever_plugins_order_a" data-o="u">last updated</a></span>
                    </h2>
                    <div class="wpclever_plugins_wrapper"></div>
                </div>
            </div>
			<?php
		}

		function ajax_get_plugins() {
			if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'wpc_dashboard' ) ) {
				die( 'Permissions check failed!' );
			}

			if ( false === ( $plugins_arr = get_transient( 'wpclever_plugins' ) ) ) {
				$args    = (object) [
					'author'   => 'wpclever',
					'per_page' => '120',
					'page'     => '1',
					'fields'   => [
						'slug',
						'name',
						'version',
						'downloaded',
						'active_installs',
						'last_updated',
						'rating',
						'num_ratings',
						'short_description'
					]
				];
				$request = [
					'action'  => 'query_plugins',
					'timeout' => 30,
					'request' => serialize( $args )
				];
				// https://codex.wordpress.org/WordPress.org_API
				$url      = 'http://api.wordpress.org/plugins/info/1.0/';
				$response = wp_remote_post( $url, [ 'body' => $request ] );

				if ( ! is_wp_error( $response ) ) {
					$plugins_arr = [];
					$plugins     = unserialize( $response['body'] );

					if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
						foreach ( $plugins->plugins as $pl ) {
							$plugins_arr[] = [
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							];
						}
					}

					set_transient( 'wpclever_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
				} else {
					echo 'Have an error while loading the plugin list. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
				}
			}

			if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
				array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );
				$i = 1;

				foreach ( $plugins_arr as $pl ) {
					if ( ! str_contains( $pl['name'], 'WPC' ) ) {
						continue;
					}

					echo '<div class="item" data-p="' . esc_attr( $pl['active_installs'] ?? 0 ) . '" data-u="' . esc_attr( $pl['last_updated'] ?? 0 ) . '" data-d="' . esc_attr( $pl['downloaded'] ?? 0 ) . '"><a class="thickbox" href="' . esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $pl['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' ) ) . '" title="' . esc_attr( $pl['name'] ) . '"><span class="num">' . esc_html( $i ) . '</span><span class="title">' . esc_html( $pl['name'] ) . '</span><br/><span class="info">' . esc_html( 'Version ' . $pl['version'] . ( isset( $pl['last_updated'] ) ? ' - Last updated: ' . wp_date( 'M j, Y', $pl['last_updated'] ) : '' ) ) . '</span></a></div>';
					$i ++;
				}
			} else {
				echo 'Have an error while loading the plugin list. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
			}

			wp_die();
		}

		function ajax_get_suggestion() {
			if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'wpc_dashboard' ) ) {
				die( 'Permissions check failed!' );
			}

			$get_suggestion = '';

			if ( false === ( $plugins_arr = get_transient( 'wpclever_plugins' ) ) ) {
				$plugins_arr = [];
				$args        = (object) [
					'author'   => 'wpclever',
					'per_page' => '120',
					'page'     => '1',
					'fields'   => [
						'slug',
						'name',
						'version',
						'downloaded',
						'active_installs',
						'last_updated',
						'rating',
						'num_ratings',
						'short_description'
					]
				];
				$request     = [
					'action'  => 'query_plugins',
					'timeout' => 30,
					'request' => serialize( $args )
				];
				// https://codex.wordpress.org/WordPress.org_API
				$url      = 'http://api.wordpress.org/plugins/info/1.0/';
				$response = wp_remote_post( $url, [ 'body' => $request ] );

				if ( ! is_wp_error( $response ) ) {
					$plugins = unserialize( $response['body'] );

					if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
						foreach ( $plugins->plugins as $pl ) {
							$plugins_arr[] = [
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							];
						}
					}

					set_transient( 'wpclever_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
				}
			}

			if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
				array_multisort( array_column( $plugins_arr, 'last_updated' ), SORT_DESC, $plugins_arr );
				$plugins_arr = array_slice( $plugins_arr, 0, 5 );

				foreach ( $plugins_arr as $sg ) {
					$get_suggestion .= '<div><a href="' . esc_url( 'https://wordpress.org/plugins/' . $sg['slug'] . '/' ) . '" target="_blank">' . esc_html( $sg['name'] ) . '</a> - ' . esc_html( $sg['short_description'] ) . '</div>';
				}
			}

			echo wp_kses_post( $get_suggestion );

			wp_die();
		}
	}

	new WPCleverDashboard();
}