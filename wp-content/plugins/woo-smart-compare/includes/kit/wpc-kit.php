<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverKit' ) ) {
	class WPCleverKit {
		protected static $plugins = [
			'wc-save-for-later'              => 'wpc-save-for-later.php',
			'woo-product-bundle'             => 'wpc-product-bundles.php',
			'woo-bought-together'            => 'wpc-frequently-bought-together.php',
			'woo-smart-compare'              => 'wpc-smart-compare.php',
			'woo-smart-quick-view'           => 'wpc-smart-quick-view.php',
			'woo-smart-wishlist'             => 'wpc-smart-wishlist.php',
			'woo-fly-cart'                   => 'wpc-fly-cart.php',
			'woo-product-timer'              => 'wpc-product-timer.php',
			'woo-added-to-cart-notification' => 'wpc-added-to-cart-notification.php',
			'woo-order-notes'                => 'wpc-order-notes.php'
		];

		function __construct() {
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
			add_action( 'wp_ajax_wpc_get_essential_kit', [ $this, 'ajax_get_essential_kit' ] );
		}

		function admin_scripts( $hook ) {
			if ( strpos( $hook, 'wpclever-kit' ) ) {
				wp_enqueue_style( 'wpc-kit', WPC_URI . 'includes/kit/css/backend.css' );
				wp_enqueue_script( 'wpc-kit', WPC_URI . 'includes/kit/js/backend.js', [ 'jquery' ] );
				wp_localize_script( 'wpc-kit', 'wpc_kit_vars', [
						'nonce' => wp_create_nonce( 'wpc_kit' ),
					]
				);
			}
		}

		function admin_menu() {
			add_submenu_page( 'wpclever', esc_html__( 'WPC Essential Kit', 'wpc-kit' ), esc_html__( 'Essential Kit', 'wpc-kit' ), 'manage_options', 'wpclever-kit', [
				$this,
				'admin_menu_content'
			], 2 );
		}

		function admin_menu_content() {
			add_thickbox();

			if ( ! function_exists( 'plugins_api' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}

			if ( isset( $_GET['action'], $_GET['plugin'] ) && ( $_GET['action'] === 'activate' ) && wp_verify_nonce( $_GET['_wpnonce'], 'activate-plugin_' . $_GET['plugin'] ) ) {
				activate_plugin( $_GET['plugin'], '', false, true );
			}

			if ( isset( $_GET['action'], $_GET['plugin'] ) && ( $_GET['action'] === 'deactivate' ) && wp_verify_nonce( $_GET['_wpnonce'], 'deactivate-plugin_' . $_GET['plugin'] ) ) {
				deactivate_plugins( $_GET['plugin'], '', false, true );
			}
			?>
            <div class="wpclever_page wpclever_essential_kit_page wrap">
                <h1 style="margin-bottom: 20px">WPClever | Essential Kit</h1>
                <div class="wp-list-table widefat plugin-install-network wpclever_essential_kit_wrapper"></div>
            </div>
			<?php
		}

		function ajax_get_essential_kit() {
			if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'wpc_kit' ) ) {
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
				//https://codex.wordpress.org/WordPress.org_API
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
					echo 'Have an error while loading the essential kit. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
				}
			}

			if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
				array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

				foreach ( $plugins_arr as $plugin ) {
					$plugin_slug = $plugin['slug'];

					if ( isset( self::$plugins[ $plugin_slug ] ) ) {
						$plugin_file = self::$plugins[ $plugin_slug ];
					} else {
						$plugin_file = $plugin_slug . '.php';
					}

					$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );
					?>
                    <div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
                        <div class="plugin-card-top">
                            <a href="<?php echo esc_url( $details_link ); ?>" class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>">
                                <img src="<?php echo esc_url( 'https://api.wpclever.net/images/' . $plugin_slug . '.png' ); ?>" class="plugin-icon" alt="<?php echo esc_attr( $plugin['name'] ); ?>"/>
                            </a>
                            <div class="name column-name">
                                <h3>
                                    <a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
										<?php echo esc_html( $plugin['name'] ); ?>
                                    </a>
                                </h3>
                            </div>
                            <div class="action-links">
                                <ul class="plugin-action-buttons">
                                    <li>
										<?php if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
											if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
												?>
                                                <a href="<?php echo esc_url( $this->deactivate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button deactivate-now">
													<?php esc_html_e( 'Deactivate', 'wpc-kit' ); ?>
                                                </a>
												<?php
											} else {
												?>
                                                <a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button activate-now">
													<?php esc_html_e( 'Activate', 'wpc-kit' ); ?>
                                                </a>
												<?php
											}
										} else { ?>
                                            <a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
												<?php esc_html_e( 'Install Now', 'wpc-kit' ); ?>
                                            </a>
										<?php } ?>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( sprintf( /* translators: plugin name */ esc_html__( 'More information about %s', 'wpc-kit' ), $plugin['name'] ) ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
											<?php esc_html_e( 'More Details', 'wpc-kit' ); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="desc column-description">
                                <p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
                            </div>
                        </div>
						<?php
						echo '<div class="plugin-card-bottom">';

						if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) { ?>
                            <div class="vers column-rating">
								<?php
								wp_star_rating(
									[
										'rating' => $plugin['rating'],
										'type'   => 'percent',
										'number' => $plugin['num_ratings'],
									]
								);
								?>
                                <span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
                            </div>
						<?php }

						if ( isset( $plugin['version'] ) ) { ?>
                            <div class="column-updated">
                                <strong><?php esc_html_e( 'Version:', 'wpc-kit' ); ?></strong>
                                <span><?php echo esc_html( $plugin['version'] ); ?></span>
                            </div>
						<?php }

						if ( isset( $plugin['active_installs'] ) ) { ?>
                            <div class="column-downloaded">
								<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'wpc-kit' ); ?>
                            </div>
						<?php }

						if ( isset( $plugin['last_updated'] ) ) { ?>
                            <div class="column-compatibility">
                                <strong><?php esc_html_e( 'Last Updated:', 'wpc-kit' ); ?></strong>
                                <span><?php printf( /* translators: updated time */ esc_html__( '%s ago', 'wpc-kit' ), esc_html( human_time_diff( $plugin['last_updated'] ) ) ); ?></span>
                            </div>
						<?php }

						echo '</div>';

						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file, true ) ) {
							?>
                            <div class="plugin-card-bottom premium">
                                <div class="text">
                                    <strong>✓ Premium version was installed.</strong>
                                </div>
                                <div class="btn">
									<?php
									if ( $this->is_plugin_active( $plugin_slug, $plugin_file, true ) ) {
										?>
                                        <a href="<?php echo esc_url( $this->deactivate_plugin_link( $plugin_slug, $plugin_file, true ) ); ?>" class="button deactivate-now">
											<?php esc_html_e( 'Deactivate', 'wpc-kit' ); ?>
                                        </a>
										<?php
									} else {
										?>
                                        <a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file, true ) ); ?>" class="button activate-now">
											<?php esc_html_e( 'Activate', 'wpc-kit' ); ?>
                                        </a>
										<?php
									}
									?>
                                </div>
                            </div>
							<?php
						}
						?>
                    </div>
					<?php
				}
			} else {
				echo 'Have an error while loading the essential kit. Please visit our website <a href="https://wpclever.net?utm_source=visit&utm_medium=menu&utm_campaign=wporg" target="_blank">https://wpclever.net</a>';
			}

			wp_die();
		}

		public function plugin_index_by_slug( $slug, $array ) {
			foreach ( $array as $key => $val ) {
				if ( $val['slug'] === $slug ) {
					return $key;
				}
			}

			return null;
		}

		public function is_plugin_installed( $plugin_slug, $plugin_file, $premium = false ) {
			if ( $premium ) {
				return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '-premium/' . $plugin_file );
			} else {
				return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
			}
		}

		public function is_plugin_active( $plugin_slug, $plugin_file, $premium = false ) {
			if ( $premium ) {
				return is_plugin_active( $plugin_slug . '-premium/' . $plugin_file );
			} else {
				return is_plugin_active( $plugin_slug . '/' . $plugin_file );
			}
		}

		public function install_plugin_link( $plugin_slug ) {
			return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
		}

		public function activate_plugin_link( $plugin_slug, $plugin_file, $premium = false ) {
			if ( $premium ) {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=activate&plugin=' . $plugin_slug . '-premium/' . $plugin_file . '#' . $plugin_slug ), 'activate-plugin_' . $plugin_slug . '-premium/' . $plugin_file );
			} else {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#' . $plugin_slug ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
			}
		}

		public function deactivate_plugin_link( $plugin_slug, $plugin_file, $premium = false ) {
			if ( $premium ) {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=deactivate&plugin=' . $plugin_slug . '-premium/' . $plugin_file . '#' . $plugin_slug ), 'deactivate-plugin_' . $plugin_slug . '-premium/' . $plugin_file );
			} else {
				return wp_nonce_url( admin_url( 'admin.php?page=wpclever-kit&action=deactivate&plugin=' . $plugin_slug . '/' . $plugin_file . '#' . $plugin_slug ), 'deactivate-plugin_' . $plugin_slug . '/' . $plugin_file );
			}
		}
	}

	new WPCleverKit();
}
