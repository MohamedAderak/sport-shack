<?php
/*
Plugin Name: WPC Smart Compare for WooCommerce
Plugin URI: https://wpclever.net/
Description: Smart products compare for WooCommerce.
Version: 6.4.2
Author: WPClever
Author URI: https://wpclever.net
Text Domain: woo-smart-compare
Domain Path: /languages/
Requires Plugins: woocommerce
Requires at least: 4.0
Tested up to: 6.7
WC requires at least: 3.0
WC tested up to: 9.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOSC_VERSION' ) && define( 'WOOSC_VERSION', '6.4.2' );
! defined( 'WOOSC_LITE' ) && define( 'WOOSC_LITE', __FILE__ );
! defined( 'WOOSC_FILE' ) && define( 'WOOSC_FILE', __FILE__ );
! defined( 'WOOSC_URI' ) && define( 'WOOSC_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOSC_DIR' ) && define( 'WOOSC_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WOOSC_SUPPORT' ) && define( 'WOOSC_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=woosc&utm_campaign=wporg' );
! defined( 'WOOSC_REVIEWS' ) && define( 'WOOSC_REVIEWS', 'https://wordpress.org/support/plugin/woo-smart-compare/reviews/?filter=5' );
! defined( 'WOOSC_CHANGELOG' ) && define( 'WOOSC_CHANGELOG', 'https://wordpress.org/plugins/woo-smart-compare/#developers' );
! defined( 'WOOSC_DISCUSSION' ) && define( 'WOOSC_DISCUSSION', 'https://wordpress.org/support/plugin/woo-smart-compare' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOSC_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'woosc_init' ) ) {
	add_action( 'plugins_loaded', 'woosc_init', 11 );

	function woosc_init() {
		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'woosc_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWoosc' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWoosc {
				protected static $settings = [];
				protected static $localization = [];
				protected static $fields = [];
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$settings     = (array) get_option( 'woosc_settings', [] );
					self::$localization = (array) get_option( 'woosc_localization', [] );

					// add query var
					add_filter( 'query_vars', [ $this, 'query_vars' ], 1 );

					// init
					add_action( 'init', [ $this, 'init' ] );
					add_action( 'wp_login', [ $this, 'login' ], 10, 2 );
					add_action( 'wp_footer', [ $this, 'footer' ] );
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
					add_filter( 'wp_dropdown_cats', [ $this, 'dropdown_cats_multiple' ], 10, 2 );

					// update product
					add_action( 'save_post', [ $this, 'save_post' ], 10, 2 );

					// frontend ajax search
					add_action( 'wc_ajax_woosc_search', [ $this, 'ajax_search' ] );

					// frontend ajax share
					add_action( 'wc_ajax_woosc_share', [ $this, 'ajax_share' ] );

					// frontend ajax load
					add_action( 'wc_ajax_woosc_load', [ $this, 'ajax_load' ] );

					// add to compare
					add_action( 'template_redirect', [ $this, 'add_by_link' ] );

					// settings page
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// backend ajax add field
					add_action( 'wp_ajax_woosc_add_field', [ $this, 'ajax_add_field' ] );

					// settings link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// menu items
					add_filter( 'wp_nav_menu_items', [ $this, 'nav_menu_items' ], 99, 2 );

					// my account
					if ( self::get_setting( 'page_myaccount', 'yes' ) === 'yes' ) {
						add_filter( 'woocommerce_account_menu_items', [ $this, 'account_items' ], 99 );
						add_action( 'woocommerce_account_compare_endpoint', [ $this, 'account_endpoint' ], 99 );
					}

					// quick table
					if ( self::get_setting( 'quick_table_enable', 'no' ) === 'yes' ) {
						$quick_table_position = self::get_setting( 'quick_table_position', 'above_related' );

						switch ( $quick_table_position ) {
							case 'above_related':
								add_action( 'woocommerce_after_single_product_summary', [
									$this,
									'show_quick_table'
								], 19 );
								break;
							case 'under_related':
								add_action( 'woocommerce_after_single_product_summary', [
									$this,
									'show_quick_table'
								], 21 );
								break;
							case 'replace_related':
								remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
								add_action( 'woocommerce_after_single_product_summary', [
									$this,
									'show_quick_table'
								], 20 );
								break;
						}
					}

					// wpml
					add_filter( 'wcml_multi_currency_ajax_actions', [ $this, 'wcml_multi_currency' ], 99 );

					// WPC Smart Messages
					add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );
				}

				function query_vars( $vars ) {
					$vars[] = 'woosc_id';

					return $vars;
				}

				function init() {
					// load text-domain
					load_plugin_textdomain( 'woo-smart-compare', false, basename( WOOSC_DIR ) . '/languages/' );

					// fields
					self::$fields = apply_filters( 'woosc_fields', [
						'image'        => self::localization( 'field_image', esc_html__( 'Image', 'woo-smart-compare' ) ),
						'sku'          => self::localization( 'field_sku', esc_html__( 'SKU', 'woo-smart-compare' ) ),
						'rating'       => self::localization( 'field_rating', esc_html__( 'Rating', 'woo-smart-compare' ) ),
						'price'        => self::localization( 'field_price', esc_html__( 'Price', 'woo-smart-compare' ) ),
						'stock'        => self::localization( 'field_stock', esc_html__( 'Stock', 'woo-smart-compare' ) ),
						'availability' => self::localization( 'field_availability', esc_html__( 'Availability', 'woo-smart-compare' ) ),
						'add_to_cart'  => self::localization( 'field_add_to_cart', esc_html__( 'Add to cart', 'woo-smart-compare' ) ),
						'description'  => self::localization( 'field_description', esc_html__( 'Description', 'woo-smart-compare' ) ),
						'content'      => self::localization( 'field_content', esc_html__( 'Content', 'woo-smart-compare' ) ),
						'weight'       => self::localization( 'field_weight', esc_html__( 'Weight', 'woo-smart-compare' ) ),
						'dimensions'   => self::localization( 'field_dimensions', esc_html__( 'Dimensions', 'woo-smart-compare' ) ),
						'additional'   => self::localization( 'field_additional', esc_html__( 'Additional information', 'woo-smart-compare' ) )
					] );

					// rewrite
					if ( $page_id = self::get_page_id() ) {
						$page_slug = get_post_field( 'post_name', $page_id );

						if ( $page_slug !== '' ) {
							add_rewrite_rule( '^' . $page_slug . '/([\w]+)/?', 'index.php?page_id=' . $page_id . '&woosc_id=$matches[1]', 'top' );
							add_rewrite_rule( '(.*?)/' . $page_slug . '/([\w]+)/?', 'index.php?page_id=' . $page_id . '&woosc_id=$matches[2]', 'top' );
						}
					}

					// my account page
					if ( self::get_setting( 'page_myaccount', 'yes' ) === 'yes' ) {
						add_rewrite_endpoint( 'compare', EP_PAGES );
					}

					// shortcode
					add_shortcode( 'woosc', [ $this, 'shortcode_btn' ] );
					add_shortcode( 'woosc_btn', [ $this, 'shortcode_btn' ] );
					add_shortcode( 'woosc_link', [ $this, 'shortcode_link' ] );
					add_shortcode( 'woosc_list', [ $this, 'shortcode_list' ] );
					add_shortcode( 'woosc_quick_table', [ $this, 'shortcode_quick_table' ] );

					// image sizes
					add_image_size( 'woosc-large', 600, 600, true );
					add_image_size( 'woosc-small', 96, 96, true );

					// add button for archive
					$btn_a = apply_filters( 'woosc_button_position_archive', self::get_setting( 'button_archive', apply_filters( 'woosc_button_position_archive_default', 'after_add_to_cart' ) ) );

					if ( ! empty( $btn_a ) ) {
						switch ( $btn_a ) {
							case 'before_title':
								add_action( 'woocommerce_shop_loop_item_title', [ $this, 'add_button' ], 9 );
								break;
							case 'after_title':
								add_action( 'woocommerce_shop_loop_item_title', [ $this, 'add_button' ], 11 );
								break;
							case 'after_rating':
								add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'add_button' ], 6 );
								break;
							case 'after_price':
								add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'add_button' ], 11 );
								break;
							case 'before_add_to_cart':
								add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button' ], 9 );
								break;
							case 'after_add_to_cart':
								add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button' ], 11 );
								break;
							default:
								add_action( 'woosc_button_position_archive_' . $btn_a, [ $this, 'add_button' ] );
						}
					}

					// add button for single
					$btn_s = apply_filters( 'woosc_button_position_single', self::get_setting( 'button_single', apply_filters( 'woosc_button_position_single_default', '31' ) ) );

					if ( ! empty( $btn_s ) ) {
						if ( is_numeric( $btn_s ) ) {
							add_action( 'woocommerce_single_product_summary', [ $this, 'add_button' ], (int) $btn_s );
						} else {
							add_action( 'woosc_button_position_single_' . $btn_s, [ $this, 'add_button' ] );
						}
					}
				}

				function login( $user_login, $user ) {
					if ( isset( $user->data->ID ) ) {
						$hash          = self::get_setting( 'hash', '6' );
						$user_key      = self::get_user_key( $user->data->ID );
						$user_products = get_user_meta( $user->data->ID, 'woosc_products', true );
						$user_fields   = get_user_meta( $user->data->ID, 'woosc_fields_' . $hash, true );

						if ( ! empty( $user_products ) ) {
							setcookie( 'woosc_products_' . $user_key, $user_products, time() + 604800, '/' );
						}

						if ( ! empty( $user_fields ) ) {
							setcookie( 'woosc_fields_' . $hash . '_' . $user_key, $user_fields, time() + 604800, '/' );
						}
					}
				}

				function enqueue_scripts() {
					// hint
					wp_enqueue_style( 'hint', WOOSC_URI . 'assets/libs/hint/hint.min.css' );

					// print
					if ( self::get_setting( 'bar_print', 'yes' ) === 'yes' ) {
						wp_enqueue_script( 'print', WOOSC_URI . 'assets/libs/print/jQuery.print.js', [ 'jquery' ], WOOSC_VERSION, true );
					}

					// table head fixer
					wp_enqueue_script( 'table-head-fixer', WOOSC_URI . 'assets/libs/table-head-fixer/table-head-fixer.js', [ 'jquery' ], WOOSC_VERSION, true );

					// perfect srollbar
					if ( self::get_setting( 'perfect_scrollbar', 'yes' ) === 'yes' ) {
						wp_enqueue_style( 'perfect-scrollbar', WOOSC_URI . 'assets/libs/perfect-scrollbar/css/perfect-scrollbar.min.css' );
						wp_enqueue_style( 'perfect-scrollbar-wpc', WOOSC_URI . 'assets/libs/perfect-scrollbar/css/custom-theme.css' );
						wp_enqueue_script( 'perfect-scrollbar', WOOSC_URI . 'assets/libs/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js', [ 'jquery' ], WOOSC_VERSION, true );
					}

					// notiny
					if ( self::get_setting( 'button_action', 'show_table' ) === 'show_message' ) {
						wp_enqueue_style( 'notiny', WOOSC_URI . 'assets/libs/notiny/notiny.css' );
						wp_enqueue_script( 'notiny', WOOSC_URI . 'assets/libs/notiny/notiny.js', [ 'jquery' ], WOOSC_VERSION, true );
					}

					if ( self::get_setting( 'button_icon', 'no' ) !== 'no' ) {
						wp_enqueue_style( 'woosc-icons', WOOSC_URI . 'assets/css/icons.css', [], WOOSC_VERSION );
					}

					// frontend css & js
					wp_enqueue_style( 'woosc-frontend', WOOSC_URI . 'assets/css/frontend.css', [], WOOSC_VERSION );
					wp_enqueue_script( 'woosc-frontend', WOOSC_URI . 'assets/js/frontend.js', [
						'jquery',
						'jquery-ui-sortable'
					], WOOSC_VERSION, true );
					wp_localize_script( 'woosc-frontend', 'woosc_vars', apply_filters( 'woosc_vars', [
							'wc_ajax_url'        => WC_AJAX::get_endpoint( '%%endpoint%%' ),
							'nonce'              => wp_create_nonce( 'woosc-security' ),
							'hash'               => self::get_setting( 'hash', '6' ),
							'user_id'            => self::get_user_key(),
							'page_url'           => self::get_page_url(),
							'open_button'        => esc_attr( self::get_setting( 'open_button', '' ) ),
							'hide_empty_row'     => apply_filters( 'woosc_hide_empty_row', 'yes' ),
							'reload_count'       => self::get_setting( 'reload_count', 'no' ),
							'variations'         => self::get_setting( 'variations', 'yes' ),
							'open_button_action' => self::get_setting( 'open_button_action', 'open_popup' ),
							'menu_action'        => self::get_setting( 'menu_action', 'open_popup' ),
							'button_action'      => self::get_setting( 'button_action', 'show_table' ),
							'sidebar_position'   => self::get_setting( 'sidebar_position', 'right' ),
							'message_position'   => self::get_setting( 'message_position', 'right-top' ),
							'message_added'      => self::localization( 'message_added', esc_html__( '{name} has been added to Compare list.', 'woo-smart-compare' ) ),
							'message_removed'    => self::localization( 'message_removed', esc_html__( '{name} has been removed from the Compare list.', 'woo-smart-compare' ) ),
							'message_exists'     => self::localization( 'message_exists', esc_html__( '{name} is already in the Compare list.', 'woo-smart-compare' ) ),
							'open_bar'           => self::get_setting( 'open_bar_immediately', 'no' ),
							'bar_filter'         => self::get_setting( 'bar_filter', 'no' ),
							'bar_bubble'         => self::get_setting( 'bar_bubble', 'no' ),
							'adding'             => self::get_setting( 'adding', 'prepend' ),
							'click_again'        => self::get_setting( 'click_again', 'no' ),
							'hide_empty'         => self::get_setting( 'hide_empty', 'no' ),
							'click_outside'      => self::get_setting( 'click_outside', 'yes' ),
							'freeze_column'      => self::get_setting( 'freeze_column', 'yes' ),
							'freeze_row'         => self::get_setting( 'freeze_row', 'yes' ),
							'scrollbar'          => self::get_setting( 'perfect_scrollbar', 'yes' ),
							'limit'              => self::get_setting( 'limit', '100' ),
							'remove_all'         => self::localization( 'bar_remove_all_confirmation', esc_html__( 'Do you want to remove all products from the compare?', 'woo-smart-compare' ) ),
							'limit_notice'       => self::localization( 'limit', esc_html__( 'You can add a maximum of {limit} products to the comparison table.', 'woo-smart-compare' ) ),
							'copied_text'        => self::localization( 'share_copied', /* translators: link */ esc_html__( 'Share link %s was copied to clipboard!', 'woo-smart-compare' ) ),
							'button_text'        => apply_filters( 'woosc_button_text', self::localization( 'button', esc_html__( 'Compare', 'woo-smart-compare' ) ) ),
							'button_text_added'  => apply_filters( 'woosc_button_text_added', self::localization( 'button_added', esc_html__( 'Compare', 'woo-smart-compare' ) ) ),
							'button_normal_icon' => apply_filters( 'woosc_button_normal_icon', self::get_setting( 'button_normal_icon', 'woosc-icon-1' ) ),
							'button_added_icon'  => apply_filters( 'woosc_button_added_icon', self::get_setting( 'button_added_icon', 'woosc-icon-74' ) ),
							'quick_table_fixed'  => apply_filters( 'woosc_quick_table_fixed', json_encode( apply_filters( 'woosc_quick_table_fixed_arr', [
								'pc' => 2,
								'ta' => 1,
								'mo' => 0,
							] ) ) ),
						] )
					);
				}

				function admin_enqueue_scripts( $hook ) {
					if ( str_contains( $hook, 'woosc' ) ) {
						wp_enqueue_style( 'wp-color-picker' );
						wp_enqueue_style( 'fonticonpicker', WOOSC_URI . 'assets/libs/fonticonpicker/css/jquery.fonticonpicker.css' );
						wp_enqueue_script( 'fonticonpicker', WOOSC_URI . 'assets/libs/fonticonpicker/js/jquery.fonticonpicker.min.js', [ 'jquery' ] );
						wp_enqueue_style( 'woosc-icons', WOOSC_URI . 'assets/css/icons.css', [], WOOSC_VERSION );
						wp_enqueue_style( 'woosc-backend', WOOSC_URI . 'assets/css/backend.css', [ 'woocommerce_admin_styles' ], WOOSC_VERSION );
						wp_enqueue_script( 'woosc-backend', WOOSC_URI . 'assets/js/backend.js', [
							'jquery',
							'wp-color-picker',
							'jquery-ui-sortable',
							'selectWoo',
						], WOOSC_VERSION, true );
					}
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings             = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-woosc&tab=settings' ) ) . '">' . esc_html__( 'Settings', 'woo-smart-compare' ) . '</a>';
						$links['wpc-premium'] = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-woosc&tab=premium' ) ) . '">' . esc_html__( 'Premium Version', 'woo-smart-compare' ) . '</a>';
						array_unshift( $links, $settings );
					}

					return (array) $links;
				}

				function row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = [
							'support' => '<a href="' . esc_url( WOOSC_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'woo-smart-compare' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function register_settings() {
					// settings
					register_setting( 'woosc_settings', 'woosc_settings' );

					// localization
					register_setting( 'woosc_localization', 'woosc_localization' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Smart Compare', 'woo-smart-compare' ), esc_html__( 'Smart Compare', 'woo-smart-compare' ), 'manage_options', 'wpclever-woosc', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					add_thickbox();
					$active_tab = sanitize_key( $_GET['tab'] ?? 'settings' );
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Smart Compare', 'woo-smart-compare' ) . ' ' . esc_html( WOOSC_VERSION ) . ' ' . ( defined( 'WOOSC_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'woo-smart-compare' ) . '</span>' : '' ); ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( /* translators: stars */ esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'woo-smart-compare' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOSC_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'woo-smart-compare' ); ?></a> |
                                <a href="<?php echo esc_url( WOOSC_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'woo-smart-compare' ); ?></a> |
                                <a href="<?php echo esc_url( WOOSC_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'woo-smart-compare' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
							flush_rewrite_rules();
							?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'woo-smart-compare' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosc&tab=settings' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'woo-smart-compare' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosc&tab=localization' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Localization', 'woo-smart-compare' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosc&tab=premium' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'woo-smart-compare' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-kit' ) ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'woo-smart-compare' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								$disable_unauthenticated = self::get_setting( 'disable_unauthenticated', 'no' );
								$adding                  = self::get_setting( 'adding', 'prepend' );
								$hide_checkout           = self::get_setting( 'hide_checkout', 'yes' );
								$hide_empty              = self::get_setting( 'hide_empty', 'no' );
								$reload_count            = self::get_setting( 'reload_count', 'no' );
								$variations              = self::get_setting( 'variations', 'yes' );
								$button_type             = self::get_setting( 'button_type', 'button' );
								$button_icon             = self::get_setting( 'button_icon', 'no' );
								$button_normal_icon      = self::get_setting( 'button_normal_icon', 'woosc-icon-1' );
								$button_added_icon       = self::get_setting( 'button_added_icon', 'woosc-icon-74' );
								$button_action           = self::get_setting( 'button_action', 'show_table' );
								$message_position        = self::get_setting( 'message_position', 'right-top' );
								$click_again             = self::get_setting( 'click_again', 'no' );
								$sidebar_position        = self::get_setting( 'sidebar_position', 'right' );
								$link                    = self::get_setting( 'link', 'yes' );
								$table_settings          = self::get_setting( 'table_settings', 'yes' );
								$remove                  = self::get_setting( 'remove', 'yes' );
								$freeze_column           = self::get_setting( 'freeze_column', 'yes' );
								$freeze_row              = self::get_setting( 'freeze_row', 'yes' );
								$perfect_scrollbar       = self::get_setting( 'perfect_scrollbar', 'yes' );
								$close_button            = self::get_setting( 'close_button', 'yes' );
								$bar_bubble              = self::get_setting( 'bar_bubble', 'no' );
								$bar_filter              = self::get_setting( 'bar_filter', 'no' );
								$bar_print               = self::get_setting( 'bar_print', 'yes' );
								$bar_share               = self::get_setting( 'bar_share', 'yes' );
								$bar_add                 = self::get_setting( 'bar_add', 'yes' );
								$bar_remove              = self::get_setting( 'bar_remove', 'no' );
								$bar_pos                 = self::get_setting( 'bar_pos', 'bottom' );
								$bar_align               = self::get_setting( 'bar_align', 'right' );
								$click_outside           = self::get_setting( 'click_outside', 'yes' );
								$quick_table_enable      = self::get_setting( 'quick_table_enable', 'no' );
								$quick_table_position    = self::get_setting( 'quick_table_position', 'above_related' );
								$quick_table_label       = self::get_setting( 'quick_table_label', 'no' );
								$page_myaccount          = self::get_setting( 'page_myaccount', 'yes' );
								$menu_action             = self::get_setting( 'menu_action', 'open_popup' );
								$open_button_action      = self::get_setting( 'open_button_action', 'open_popup' );
								?>
                                <form method="post" action="options.php">
                                    <input type="hidden" name="woosc_settings[hash]" value="<?php echo esc_attr( self::generate_key( 4, true ) ); ?>"/>
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'woo-smart-compare' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Disable for unauthenticated users', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[disable_unauthenticated]">
                                                        <option value="yes" <?php selected( $disable_unauthenticated, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $disable_unauthenticated, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Product adding', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[adding]">
                                                        <option value="prepend" <?php selected( $adding, 'prepend' ); ?>><?php esc_html_e( 'Prepend', 'woo-smart-compare' ); ?></option>
                                                        <option value="append" <?php selected( $adding, 'append' ); ?>><?php esc_html_e( 'Append', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'The new product will be added to the first or last of the list?', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Hide on cart & checkout page', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[hide_checkout]">
                                                        <option value="yes" <?php selected( $hide_checkout, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $hide_checkout, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Hide the comparison table and comparison bar on the cart & checkout page?', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Hide if empty', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[hide_empty]">
                                                        <option value="yes" <?php selected( $hide_empty, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $hide_empty, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Hide the comparison table and comparison bar if haven\'t any product.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Compare variations', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[variations]">
                                                        <option value="yes" <?php selected( $variations, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $variations, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Compare selected variation instead of the main variable product.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Limit', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="number" min="1" max="100" step="1" name="woosc_settings[limit]" value="<?php echo self::get_setting( 'limit', '100' ); ?>"/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'The maximum of products can be added to the comparison table.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Comparison page', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php wp_dropdown_pages( [
													'selected'          => self::get_setting( 'page_id', '' ),
													'name'              => 'woosc_settings[page_id]',
													'show_option_none'  => esc_html__( 'Choose a page', 'woo-smart-compare' ),
													'option_none_value' => '',
												] ); ?>
                                                <span class="description"><?php printf( /* translators: shortcode */ esc_html__( 'Add shortcode %s to display the comparison table on this page.', 'woo-smart-compare' ), '<code>[woosc_list]</code>' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Reload the count', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[reload_count]">
                                                        <option value="yes" <?php selected( $reload_count, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $reload_count, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Reload the count when opening the page?', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Compare button', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Type', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[button_type]">
                                                        <option value="button" <?php selected( $button_type, 'button' ); ?>><?php esc_html_e( 'Button', 'woo-smart-compare' ); ?></option>
                                                        <option value="link" <?php selected( $button_type, 'link' ); ?>><?php esc_html_e( 'Link', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Use icon', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <select name="woosc_settings[button_icon]" class="woosc_button_icon">
                                                        <option value="left" <?php selected( $button_icon, 'left' ); ?>><?php esc_html_e( 'Icon on the left', 'woo-smart-compare' ); ?></option>
                                                        <option value="right" <?php selected( $button_icon, 'right' ); ?>><?php esc_html_e( 'Icon on the right', 'woo-smart-compare' ); ?></option>
                                                        <option value="only" <?php selected( $button_icon, 'only' ); ?>><?php esc_html_e( 'Icon only', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $button_icon, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr class="woosc-show-if-button-icon">
                                            <th><?php esc_html_e( 'Normal icon', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <select name="woosc_settings[button_normal_icon]" class="woosc_icon_picker">
														<?php for ( $i = 1; $i <= 79; $i ++ ) {
															echo '<option value="woosc-icon-' . $i . '" ' . selected( $button_normal_icon, 'woosc-icon-' . $i, false ) . '>woosc-icon-' . $i . '</option>';
														} ?>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr class="woosc-show-if-button-icon">
                                            <th><?php esc_html_e( 'Added icon', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <select name="woosc_settings[button_added_icon]" class="woosc_icon_picker">
														<?php for ( $i = 1; $i <= 79; $i ++ ) {
															echo '<option value="woosc-icon-' . $i . '" ' . selected( $button_added_icon, 'woosc-icon-' . $i, false ) . '>woosc-icon-' . $i . '</option>';
														} ?>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Action', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <select name="woosc_settings[button_action]" class="woosc_button_action">
                                                        <option value="show_bar" <?php selected( $button_action, 'show_bar' ); ?>><?php esc_html_e( 'Open comparison bar', 'woo-smart-compare' ); ?></option>
                                                        <option value="show_table" <?php selected( $button_action, 'show_table' ); ?>><?php esc_html_e( 'Open comparison table', 'woo-smart-compare' ); ?></option>
                                                        <option value="show_sidebar" <?php selected( $button_action, 'show_sidebar' ); ?>><?php esc_html_e( 'Open comparison sidebar', 'woo-smart-compare' ); ?></option>
                                                        <option value="show_message" <?php selected( $button_action, 'show_message' ); ?>><?php esc_html_e( 'Show message', 'woo-smart-compare' ); ?></option>
                                                        <option value="none" <?php selected( $button_action, 'none' ); ?>><?php esc_html_e( 'Add to compare solely', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Action triggered by clicking on the compare button.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="woosc_button_action_hide woosc_button_action_show_message">
                                            <th scope="row"><?php esc_html_e( 'Message position', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[message_position]">
                                                        <option value="right-top" <?php selected( $message_position, 'right-top' ); ?>><?php esc_html_e( 'right-top', 'woo-smart-compare' ); ?></option>
                                                        <option value="right-bottom" <?php selected( $message_position, 'right-bottom' ); ?>><?php esc_html_e( 'right-bottom', 'woo-smart-compare' ); ?></option>
                                                        <option value="fluid-top" <?php selected( $message_position, 'fluid-top' ); ?>><?php esc_html_e( 'center-top', 'woo-smart-compare' ); ?></option>
                                                        <option value="fluid-bottom" <?php selected( $message_position, 'fluid-bottom' ); ?>><?php esc_html_e( 'center-bottom', 'woo-smart-compare' ); ?></option>
                                                        <option value="left-top" <?php selected( $message_position, 'left-top' ); ?>><?php esc_html_e( 'left-top', 'woo-smart-compare' ); ?></option>
                                                        <option value="left-bottom" <?php selected( $message_position, 'left-bottom' ); ?>><?php esc_html_e( 'left-bottom', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Extra class (optional)', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_settings[button_class]" value="<?php echo self::get_setting( 'button_class', '' ); ?>"/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'Add extra class for action button/link, split by one space.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position on archive page', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$btn_a = apply_filters( 'woosc_button_position_archive', 'default' );
												$pos_a = apply_filters( 'woosc_button_positions_archive', [
													'before_title'       => esc_html__( 'Above title', 'woo-smart-compare' ),
													'after_title'        => esc_html__( 'Under title', 'woo-smart-compare' ),
													'after_rating'       => esc_html__( 'Under rating', 'woo-smart-compare' ),
													'after_price'        => esc_html__( 'Under price', 'woo-smart-compare' ),
													'before_add_to_cart' => esc_html__( 'Above add to cart button', 'woo-smart-compare' ),
													'after_add_to_cart'  => esc_html__( 'Under add to cart button', 'woo-smart-compare' ),
													'0'                  => esc_html__( 'None (hide it)', 'woo-smart-compare' ),
												] );
												?>
                                                <label>
                                                    <select name="woosc_settings[button_archive]" <?php echo esc_attr( $btn_a !== 'default' ? 'disabled' : '' ); ?>>
														<?php
														if ( $btn_a === 'default' ) {
															$btn_a = self::get_setting( 'button_archive', apply_filters( 'woosc_button_position_archive_default', 'after_add_to_cart' ) );
														}

														foreach ( $pos_a as $k => $p ) {
															echo '<option value="' . esc_attr( $k ) . '" ' . ( ( strval( $k ) === strval( $btn_a ) ) || ( $k === $btn_a ) || ( empty( $btn_a ) && empty( $k ) ) ? 'selected' : '' ) . '>' . esc_html( $p ) . '</option>';
														}
														?>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position on single page', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$btn_s = apply_filters( 'woosc_button_position_single', 'default' );
												$pos_s = apply_filters( 'woosc_button_positions_single', [
													'6'  => esc_html__( 'Under title', 'woo-smart-compare' ),
													'11' => esc_html__( 'Under rating', 'woo-smart-compare' ),
													'21' => esc_html__( 'Under excerpt', 'woo-smart-compare' ),
													'29' => esc_html__( 'Above add to cart button', 'woo-smart-compare' ),
													'31' => esc_html__( 'Under add to cart button', 'woo-smart-compare' ),
													'41' => esc_html__( 'Under meta', 'woo-smart-compare' ),
													'51' => esc_html__( 'Under sharing', 'woo-smart-compare' ),
													'0'  => esc_html__( 'None (hide it)', 'woo-smart-compare' ),
												] );
												?>
                                                <label>
                                                    <select name="woosc_settings[button_single]" <?php echo esc_attr( $btn_s !== 'default' ? 'disabled' : '' ); ?>>
														<?php
														if ( $btn_s === 'default' ) {
															$btn_s = self::get_setting( 'button_single', apply_filters( 'woosc_button_position_single_default', '31' ) );
														}

														foreach ( $pos_s as $k => $p ) {
															echo '<option value="' . esc_attr( $k ) . '" ' . ( ( strval( $k ) === strval( $btn_s ) ) || ( $k === $btn_s ) || ( empty( $btn_s ) && empty( $k ) ) ? 'selected' : '' ) . '>' . esc_html( $p ) . '</option>';
														}
														?>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Manual', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <span class="description"><?php printf( /* translators: shortcode */ esc_html__( 'You can use the shortcode %1$s, e.g. %2$s for the product with ID is 99.', 'woo-smart-compare' ), '<code>[woosc id="{product id}"]</code>', '<code>[woosc id="99"]</code>' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Categories', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$cats = self::get_setting( 'search_cats', [] );

												if ( empty( $cats ) ) {
													$cats = [ '0' ];
												}

												wc_product_dropdown_categories(
													[
														'name'             => 'woosc_settings[search_cats]',
														'id'               => 'woosc_settings_cats',
														'hide_empty'       => 0,
														'value_field'      => 'id',
														'multiple'         => true,
														'show_option_all'  => esc_html__( 'All categories', 'woo-smart-compare' ),
														'show_option_none' => '',
														'selected'         => implode( ',', $cats )
													] );
												?>
                                                <span class="description"><?php esc_html_e( 'Only show the compare button for products in selected categories.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Remove when clicking again', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[click_again]">
                                                        <option value="yes" <?php selected( $click_again, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $click_again, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Do you want to remove product when clicking again?', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Comparison sidebar', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[sidebar_position]">
                                                        <option value="right" <?php selected( $sidebar_position, 'right' ); ?>><?php esc_html_e( 'Right', 'woo-smart-compare' ); ?></option>
                                                        <option value="left" <?php selected( $sidebar_position, 'left' ); ?>><?php esc_html_e( 'Left', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Comparison table', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Fields', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <p class="description"><?php esc_html_e( 'Choose fields that you want to show on the comparison table. You also can drag/drop to rearrange these fields.', 'woo-smart-compare' ); ?></p>
                                                <div class="woosc-fields-wrapper">
                                                    <div class="woosc-fields">
														<?php
														$saved_fields6 = self::get_fields();

														foreach ( $saved_fields6 as $key => $field ) {
															$field = array_merge( [
																'type'  => '',
																'name'  => '',
																'label' => ''
															], $field );

															$type  = $field['type'];
															$title = $field['name'];

															switch ( $type ) {
																case 'default':
																	if ( isset( self::$fields[ $title ] ) ) {
																		$title = self::$fields[ $title ];
																	}

																	break;
																case 'attribute':
																	$title = wc_attribute_label( $title );

																	break;
																case 'custom_attribute':
																	$title = esc_html__( 'Custom attribute', 'woo-smart-compare' );

																	break;
																case 'custom_field':
																	$title = esc_html__( 'Custom field', 'woo-smart-compare' );

																	break;
																case 'shortcode':
																	$title = esc_html__( 'Custom text/shortcode', 'woo-smart-compare' );

																	break;
															}

															self::field_html( $key, 'fields6', $field['type'], $field['name'], $title, $field['label'] );
														}
														?>
                                                    </div>
                                                    <div class="woosc-fields-more">
                                                        <label> <select class="woosc-field-types">
																<?php
																// default fields
																if ( ! empty( self::$fields ) ) {
																	echo '<optgroup label="' . esc_attr__( 'Default', 'woo-smart-compare' ) . '">';

																	foreach ( self::$fields as $fk => $fv ) {
																		echo '<option value="' . esc_attr( $fk ) . '" data-type="default">' . esc_html( $fv ) . '</option>';
																	}

																	echo '</optgroup>';
																}

																// attributes
																if ( $wc_attributes = wc_get_attribute_taxonomies() ) {
																	echo '<optgroup label="' . esc_attr__( 'Attributes', 'woo-smart-compare' ) . '">';
																	echo '<option value="all" data-type="attribute" disabled>' . esc_html__( 'All attributes', 'woo-smart-compare' ) . '</option>';

																	foreach ( $wc_attributes as $wc_attribute ) {
																		echo '<option value="' . esc_attr( urlencode( 'pa_' . $wc_attribute->attribute_name ) ) . '" data-type="attribute" disabled>' . esc_html( $wc_attribute->attribute_label ) . '</option>';
																	}

																	echo '</optgroup>';
																}
																?>
                                                                <optgroup label="<?php esc_attr_e( 'Custom', 'woo-smart-compare' ); ?>">
                                                                    <option value="custom_field" data-type="custom_field" disabled><?php esc_html_e( 'Custom field', 'woo-smart-compare' ); ?></option>
                                                                    <option value="custom_attribute" data-type="custom_attribute" disabled><?php esc_html_e( 'Custom attribute', 'woo-smart-compare' ); ?></option>
                                                                    <option value="shortcode" data-type="shortcode"><?php esc_html_e( 'Custom text/shortcode', 'woo-smart-compare' ); ?></option>
                                                                </optgroup>
                                                            </select> </label>
                                                        <button type="button" class="button woosc-field-add" data-setting="fields6"><?php esc_html_e( '+ Add', 'woo-smart-compare' ); ?></button>
                                                    </div>
                                                    <span class="description" style="color: #c9356e">
                                                        * Adding attribute/custom-attribute/custom-field only available on Premium Version. Click
                                                        <a href="https://wpclever.net/downloads/smart-compare?utm_source=pro&utm_medium=woosc&utm_campaign=wporg" target="_blank">here</a> to buy, just $29!
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Image size', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$image_size          = self::get_setting( 'image_size', 'woosc-large' );
												$image_sizes         = self::get_image_sizes();
												$image_sizes['full'] = [
													'width'  => '',
													'height' => '',
													'crop'   => false
												];

												if ( ! empty( $image_sizes ) ) {
													echo '<select name="woosc_settings[image_size]">';

													foreach ( $image_sizes as $image_size_name => $image_size_data ) {
														echo '<option value="' . esc_attr( $image_size_name ) . '" ' . ( $image_size_name === $image_size ? 'selected' : '' ) . '>' . esc_attr( $image_size_name ) . ( ! empty( $image_size_data['width'] ) ? ' ' . $image_size_data['width'] . '&times;' . $image_size_data['height'] : '' ) . ( $image_size_data['crop'] ? ' (cropped)' : '' ) . '</option>';
													}

													echo '</select>';
												}
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Link to individual product', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[link]">
                                                        <option value="yes" <?php selected( $link, 'yes' ); ?>><?php esc_html_e( 'Yes, open in the same tab', 'woo-smart-compare' ); ?></option>
                                                        <option value="yes_blank" <?php selected( $link, 'yes_blank' ); ?>><?php esc_html_e( 'Yes, open in the new tab', 'woo-smart-compare' ); ?></option>
                                                        <option value="yes_popup" <?php selected( $link, 'yes_popup' ); ?>><?php esc_html_e( 'Yes, open quick view popup', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $link, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label> <span class="description">If you choose "Open quick view popup", please install <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-quick-view&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Smart Quick View">WPC Smart Quick View</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Settings" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[table_settings]">
                                                        <option value="yes" <?php selected( $table_settings, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $table_settings, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Show the settings popup to customize fields (show/ hide / rearrange).', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Default settings', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <label><input type="checkbox" name="woosc_settings[default_settings][]" value="hide_similarities" <?php echo esc_attr( in_array( 'hide_similarities', self::get_setting( 'default_settings', [] ) ) ? 'checked' : '' ); ?>/>
															<?php esc_html_e( 'Hide similarities', 'woo-smart-compare' ); ?>
                                                        </label></li>
                                                    <li>
                                                        <label><input type="checkbox" name="woosc_settings[default_settings][]" value="highlight_differences" <?php echo esc_attr( in_array( 'highlight_differences', self::get_setting( 'default_settings', [] ) ) ? 'checked' : '' ); ?>/>
															<?php esc_html_e( 'Highlight differences', 'woo-smart-compare' ); ?>
                                                        </label></li>
                                                </ul>
                                                <span class="description"><?php esc_html_e( 'Check the settings that you want to enable by default.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( '"Remove" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[remove]">
                                                        <option value="yes" <?php selected( $remove, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $remove, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Show remove button beside product name.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Freeze first column', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[freeze_column]">
                                                        <option value="yes" <?php selected( $freeze_column, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $freeze_column, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Freeze the first column (fields and attributes title) when scrolling horizontally.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Freeze first row', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[freeze_row]">
                                                        <option value="yes" <?php selected( $freeze_row, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $freeze_row, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Freeze the first row (product name) when scrolling vertically.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Use perfect-scrollbar', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[perfect_scrollbar]">
                                                        <option value="yes" <?php selected( $perfect_scrollbar, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $perfect_scrollbar, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php printf( /* translators: link */ esc_html__( 'Read more about %s', 'woo-smart-compare' ), '<a href="https://github.com/mdbootstrap/perfect-scrollbar" target="_blank">perfect-scrollbar</a>' ); ?>.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Close button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[close_button]">
                                                        <option value="yes" <?php selected( $close_button, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $close_button, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Enable the close button at top-right conner of comparison table?', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Comparison bar', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Open immediately', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="woosc_settings[open_bar_immediately]" value="yes" <?php echo esc_attr( self::get_setting( 'open_bar_immediately', 'no' ) === 'yes' ? 'checked' : '' ); ?>/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'Check it if you want to open the comparison bar immediately on page loaded.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Bubble', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_bubble]">
                                                        <option value="yes" <?php selected( $bar_bubble, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $bar_bubble, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Use the bubble instead of a fully comparison bar.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Smart filter', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_filter]">
                                                        <option value="no" <?php selected( $bar_filter, 'no' ); ?>><?php esc_html_e( 'None (disable)', 'woo-smart-compare' ); ?></option>
														<?php
														$taxonomies = get_object_taxonomies( 'product', 'objects' ); //$taxonomies = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );

														foreach ( $taxonomies as $taxonomy ) {
															echo '<option value="' . esc_attr( $taxonomy->name ) . '" ' . selected( $bar_filter, $taxonomy->name, false ) . '>' . esc_html( $taxonomy->label ) . '</option>';
														}
														?>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Enable smart filter by selected taxonomy.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Print" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_print]">
                                                        <option value="yes" <?php selected( $bar_print, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $bar_print, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Show the print button.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Share" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_share]">
                                                        <option value="yes" <?php selected( $bar_share, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $bar_share, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Show the share button.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Add more" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_add]">
                                                        <option value="yes" <?php selected( $bar_add, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $bar_add, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Add the button to search product and add to compare list immediately.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Add more" count', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="number" min="1" max="100" name="woosc_settings[search_count]" value="<?php echo self::get_setting( 'search_count', 10 ); ?>"/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'The result count of search function when clicking on "Add more" button.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Remove all" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_remove]">
                                                        <option value="yes" <?php selected( $bar_remove, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $bar_remove, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Add the button to remove all products from compare immediately.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Background color', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php $bar_bg_color_default = apply_filters( 'woosc_bar_bg_color_default', '#292a30' ); ?>
                                                <label>
                                                    <input type="text" class="woosc_color_picker" name="woosc_settings[bar_bg_color]" value="<?php echo self::get_setting( 'bar_bg_color', $bar_bg_color_default ); ?>"/>
                                                </label>
                                                <span class="description"><?php printf( /* translators: color */ esc_html__( 'Choose the background color for the comparison bar, default %s', 'woo-smart-compare' ), '<code>' . $bar_bg_color_default . '</code>' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Button color', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php $bar_btn_color_default = apply_filters( 'woosc_bar_btn_color_default', '#00a0d2' ); ?>
                                                <label>
                                                    <input type="text" class="woosc_color_picker" name="woosc_settings[bar_btn_color]" value="<?php echo self::get_setting( 'bar_btn_color', $bar_btn_color_default ); ?>"/>
                                                </label>
                                                <span class="description"><?php printf( /* translators: color */ esc_html__( 'Choose the color for the button on comparison bar, default %s', 'woo-smart-compare' ), '<code>' . $bar_btn_color_default . '</code>' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_pos]">
                                                        <option value="bottom" <?php selected( $bar_pos, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'woo-smart-compare' ); ?></option>
                                                        <option value="top" <?php selected( $bar_pos, 'top' ); ?>><?php esc_html_e( 'Top', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Align', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[bar_align]">
                                                        <option value="right" <?php selected( $bar_align, 'right' ); ?>><?php esc_html_e( 'Right', 'woo-smart-compare' ); ?></option>
                                                        <option value="left" <?php selected( $bar_align, 'left' ); ?>><?php esc_html_e( 'Left', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Click outside to hide', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[click_outside]">
                                                        <option value="yes" <?php selected( $click_outside, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="yes_empty" <?php selected( $click_outside, 'yes_empty' ); ?>><?php esc_html_e( 'Yes if empty', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $click_outside, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Quick comparison table', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Enable', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[quick_table_enable]">
                                                        <option value="yes" <?php selected( $quick_table_enable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $quick_table_enable, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label> <span class="description">Enable this to display the quick comparison table with related products on single product pages. You can customize the list of related products using our <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-custom-related-products&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Custom Related Products">WPC Custom Related Products</a> plugin.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[quick_table_position]">
                                                        <option value="above_related" <?php selected( $quick_table_position, 'above_related' ); ?>><?php esc_html_e( 'Above related', 'woo-smart-compare' ); ?></option>
                                                        <option value="under_related" <?php selected( $quick_table_position, 'under_related' ); ?>><?php esc_html_e( 'Under related', 'woo-smart-compare' ); ?></option>
                                                        <option value="replace_related" <?php selected( $quick_table_position, 'replace_related' ); ?>><?php esc_html_e( 'Replace related', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Label column', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[quick_table_label]">
                                                        <option value="yes" <?php selected( $quick_table_label, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $quick_table_label, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Fields', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <p class="description"><?php esc_html_e( 'Choose fields that you want to show on the comparison table. You also can drag/drop to rearrange these fields.', 'woo-smart-compare' ); ?></p>
                                                <div class="woosc-fields-wrapper">
                                                    <div class="woosc-fields">
														<?php
														$saved_fields6 = self::get_fields( 'quick_table' );

														foreach ( $saved_fields6 as $key => $field ) {
															$field = array_merge( [
																'type'  => '',
																'name'  => '',
																'label' => ''
															], $field );

															$type  = $field['type'];
															$title = $field['name'];

															switch ( $type ) {
																case 'default':
																	if ( isset( self::$fields[ $title ] ) ) {
																		$title = self::$fields[ $title ];
																	}

																	break;
																case 'attribute':
																	$title = wc_attribute_label( $title );

																	break;
																case 'custom_attribute':
																	$title = esc_html__( 'Custom attribute', 'woo-smart-compare' );

																	break;
																case 'custom_field':
																	$title = esc_html__( 'Custom field', 'woo-smart-compare' );

																	break;
																case 'shortcode':
																	$title = esc_html__( 'Custom text/shortcode', 'woo-smart-compare' );

																	break;
															}

															self::field_html( $key, 'quick_fields6', $field['type'], $field['name'], $title, $field['label'] );
														}
														?>
                                                    </div>
                                                    <div class="woosc-fields-more">
                                                        <label> <select class="woosc-field-types">
																<?php
																// default fields
																if ( ! empty( self::$fields ) ) {
																	echo '<optgroup label="' . esc_attr__( 'Default', 'woo-smart-compare' ) . '">';

																	foreach ( self::$fields as $fk => $fv ) {
																		echo '<option value="' . esc_attr( $fk ) . '" data-type="default">' . esc_html( $fv ) . '</option>';
																	}

																	echo '</optgroup>';
																}

																// attributes
																if ( $wc_attributes = wc_get_attribute_taxonomies() ) {
																	echo '<optgroup label="' . esc_attr__( 'Attributes', 'woo-smart-compare' ) . '">';
																	echo '<option value="all" data-type="attribute" disabled>' . esc_html__( 'All attributes', 'woo-smart-compare' ) . '</option>';

																	foreach ( $wc_attributes as $wc_attribute ) {
																		echo '<option value="' . esc_attr( urlencode( 'pa_' . $wc_attribute->attribute_name ) ) . '" data-type="attribute" disabled>' . esc_html( $wc_attribute->attribute_label ) . '</option>';
																	}

																	echo '</optgroup>';
																}
																?>
                                                                <optgroup label="<?php esc_attr_e( 'Custom', 'woo-smart-compare' ); ?>">
                                                                    <option value="custom_field" data-type="custom_field" disabled><?php esc_html_e( 'Custom field', 'woo-smart-compare' ); ?></option>
                                                                    <option value="custom_attribute" data-type="custom_attribute" disabled><?php esc_html_e( 'Custom attribute', 'woo-smart-compare' ); ?></option>
                                                                    <option value="shortcode" data-type="shortcode"><?php esc_html_e( 'Custom text/shortcode', 'woo-smart-compare' ); ?></option>
                                                                </optgroup>
                                                            </select> </label>
                                                        <button type="button" class="button woosc-field-add" data-setting="quick_fields6"><?php esc_html_e( '+ Add', 'woo-smart-compare' ); ?></button>
                                                    </div>
                                                    <span class="description" style="color: #c9356e">
                                                        * Adding attribute/custom-attribute/custom-field only available on Premium Version. Click
                                                        <a href="https://wpclever.net/downloads/smart-compare?utm_source=pro&utm_medium=woosc&utm_campaign=wporg" target="_blank">here</a> to buy, just $29!
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Menu', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Menu(s)', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$nav_args  = [
													'hide_empty' => false,
													'fields'     => 'id=>name',
												];
												$nav_menus = get_terms( 'nav_menu', $nav_args );

												if ( $nav_menus ) {
													echo '<ul>';
													$saved_menus = self::get_setting( 'menus', [] );

													foreach ( $nav_menus as $nav_id => $nav_name ) {
														echo '<li><label><input type="checkbox" name="woosc_settings[menus][]" value="' . $nav_id . '" ' . ( is_array( $saved_menus ) && in_array( $nav_id, $saved_menus ) ? 'checked' : '' ) . '/> ' . $nav_name . '</label></li>';
													}

													echo '</ul>';
												} else {
													echo '<p>' . esc_html__( 'Haven\'t any menu yet. Please go to Appearance > Menus to create one.', 'woo-smart-compare' ) . '</p>';
												}
												?>
                                                <span class="description"><?php esc_html_e( 'Choose the menu(s) you want to add the "compare menu" at the end.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Action', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[menu_action]">
                                                        <option value="open_page" <?php selected( $menu_action, 'open_page' ); ?>><?php esc_html_e( 'Open comparison page', 'woo-smart-compare' ); ?></option>
                                                        <option value="open_popup" <?php selected( $menu_action, 'open_popup' ); ?>><?php esc_html_e( 'Open comparison table', 'woo-smart-compare' ); ?></option>
                                                        <option value="open_sidebar" <?php selected( $menu_action, 'open_sidebar' ); ?>><?php esc_html_e( 'Open comparison sidebar', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Action when clicking on the "compare menu".', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Add Comparison page to My Account', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[page_myaccount]">
                                                        <option value="yes" <?php selected( $page_myaccount, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?></option>
                                                        <option value="no" <?php selected( $page_myaccount, 'no' ); ?>><?php esc_html_e( 'No', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Custom menu', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_settings[open_button]" value="<?php echo self::get_setting( 'open_button', '' ); ?>" placeholder="<?php esc_html_e( 'button class or id', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                                <span class="description"><?php printf( /* translators: selector */ esc_html__( 'Example %1$s or %2$s', 'woo-smart-compare' ), '<code>.open-compare-btn</code>', '<code>#open-compare-btn</code>' ); ?></span>
                                                <p class="description"><?php esc_html_e( 'The class or id of the menu, when clicking on this menu the comparison page or comparison table will be opened.', 'woo-smart-compare' ); ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Custom menu action', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label> <select name="woosc_settings[open_button_action]">
                                                        <option value="open_page" <?php selected( $open_button_action, 'open_page' ); ?>><?php esc_html_e( 'Open comparison page', 'woo-smart-compare' ); ?></option>
                                                        <option value="open_popup" <?php selected( $open_button_action, 'open_popup' ); ?>><?php esc_html_e( 'Open comparison table', 'woo-smart-compare' ); ?></option>
                                                        <option value="open_sidebar" <?php selected( $open_button_action, 'open_sidebar' ); ?>><?php esc_html_e( 'Open comparison sidebar', 'woo-smart-compare' ); ?></option>
                                                    </select> </label>
                                                <span class="description"><?php esc_html_e( 'Action when clicking on the "custom menu".', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
												<?php settings_fields( 'woosc_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'localization' ) { ?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'General', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'woo-smart-compare' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Need to login', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[login]" value="<?php echo esc_attr( self::localization( 'login' ) ); ?>" placeholder="<?php esc_attr_e( 'Please log in to use the product comparison!', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Limit notice', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[limit]" value="<?php echo esc_attr( self::localization( 'limit' ) ); ?>" placeholder="<?php esc_attr_e( 'You can add a maximum of {limit} products to the comparison table.', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'The notice when reaching the limit. Use {limit} to show the number.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Message', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Added', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[message_added]" value="<?php echo esc_attr( self::localization( 'message_added' ) ); ?>" placeholder="<?php esc_attr_e( '{name} has been added to Compare list.', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'Use {name} for product name.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Removed', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[message_removed]" value="<?php echo esc_attr( self::localization( 'message_removed' ) ); ?>" placeholder="<?php esc_attr_e( '{name} has been removed from the Compare list.', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'Use {name} for product name.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Exists', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[message_exists]" value="<?php echo esc_attr( self::localization( 'message_exists' ) ); ?>" placeholder="<?php esc_attr_e( '{name} is already in the Compare list.', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                                <span class="description"><?php esc_html_e( 'Use {name} for product name.', 'woo-smart-compare' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Compare button', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Button text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[button]" value="<?php echo esc_attr( self::localization( 'button' ) ); ?>" placeholder="<?php esc_attr_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Button (added) text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[button_added]" value="<?php echo esc_attr( self::localization( 'button_added' ) ); ?>" placeholder="<?php esc_attr_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Comparison table', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Remove', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[table_remove]" value="<?php echo esc_attr( self::localization( 'table_remove' ) ); ?>" placeholder="<?php esc_attr_e( 'remove', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Close', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[table_close]" value="<?php echo esc_attr( self::localization( 'table_close' ) ); ?>" placeholder="<?php esc_attr_e( 'Close', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Empty', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[table_empty]" value="<?php echo esc_attr( self::localization( 'table_empty' ) ); ?>" placeholder="<?php esc_attr_e( 'No product is added to the comparison table.', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Settings', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[table_settings]" value="<?php echo esc_attr( self::localization( 'table_settings' ) ); ?>" placeholder="<?php esc_attr_e( 'Settings', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Hide similarities', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[hide_similarities]" value="<?php echo esc_attr( self::localization( 'hide_similarities' ) ); ?>" placeholder="<?php esc_attr_e( 'Hide similarities', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Highlight differences', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[highlight_differences]" value="<?php echo esc_attr( self::localization( 'highlight_differences' ) ); ?>" placeholder="<?php esc_attr_e( 'Highlight differences', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Select fields description', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_select_fields_desc]" value="<?php echo esc_attr( self::localization( 'bar_select_fields_desc' ) ); ?>" placeholder="<?php esc_attr_e( 'Select the fields to be shown. Others will be hidden. Drag and drop to rearrange the order.', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Comparison sidebar', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Heading', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[sidebar_heading]" value="<?php echo esc_attr( self::localization( 'sidebar_heading' ) ); ?>" placeholder="<?php esc_attr_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Close', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[sidebar_close]" value="<?php echo esc_attr( self::localization( 'sidebar_close' ) ); ?>" placeholder="<?php esc_attr_e( 'Close', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Remove', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[sidebar_remove]" value="<?php echo esc_attr( self::localization( 'sidebar_remove' ) ); ?>" placeholder="<?php esc_attr_e( 'Remove', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Button text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[sidebar_button]" value="<?php echo esc_attr( self::localization( 'sidebar_button' ) ); ?>" placeholder="<?php esc_attr_e( 'Let\'s Compare!', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Continue shopping', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[sidebar_continue]" value="<?php echo esc_attr( self::localization( 'sidebar_continue' ) ); ?>" placeholder="<?php esc_attr_e( 'Continue shopping', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Comparison bar', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Button text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_button]" value="<?php echo esc_attr( self::localization( 'bar_button' ) ); ?>" placeholder="<?php esc_attr_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Add product', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_add]" value="<?php echo esc_attr( self::localization( 'bar_add' ) ); ?>" placeholder="<?php esc_attr_e( 'Add product', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Search placeholder', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_search_placeholder]" value="<?php echo esc_attr( self::localization( 'bar_search_placeholder' ) ); ?>" placeholder="<?php esc_attr_e( 'Type any keyword to search...', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'No results', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_search_no_results]" value="<?php echo esc_attr( self::localization( 'bar_search_no_results' ) ); ?>" placeholder="<?php /* translators: keyword */
													esc_attr_e( 'No results found for "%s"', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Remove', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_remove]" value="<?php echo esc_attr( self::localization( 'bar_remove' ) ); ?>" placeholder="<?php esc_attr_e( 'Remove', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Remove all', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_remove_all]" value="<?php echo esc_attr( self::localization( 'bar_remove_all' ) ); ?>" placeholder="<?php esc_attr_e( 'Remove all', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Remove all confirmation', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_remove_all_confirmation]" value="<?php echo esc_attr( self::localization( 'bar_remove_all_confirmation' ) ); ?>" placeholder="<?php esc_attr_e( 'Do you want to remove all products from the compare?', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Print', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_print]" value="<?php echo esc_attr( self::localization( 'bar_print' ) ); ?>" placeholder="<?php esc_attr_e( 'Print', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Share', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_share]" value="<?php echo esc_attr( self::localization( 'bar_share' ) ); ?>" placeholder="<?php esc_attr_e( 'Share', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Share description', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[share_description]" value="<?php echo esc_attr( self::localization( 'share_description' ) ); ?>" placeholder="<?php esc_attr_e( 'Share link was generated! Now you can copy below link to share.', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Share on', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[share_on]" value="<?php echo esc_attr( self::localization( 'share_on' ) ); ?>" placeholder="<?php esc_attr_e( 'Share on:', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Share link was copied', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[share_copied]" value="<?php echo esc_attr( self::localization( 'share_copied' ) ); ?>" placeholder="<?php /* translators: link */
													esc_attr_e( 'Share link %s was copied to clipboard!', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Click outside', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_click_outside]" value="<?php echo esc_attr( self::localization( 'bar_click_outside' ) ); ?>" placeholder="<?php esc_attr_e( 'Click outside to hide the comparison bar', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Filter', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_filter]" value="<?php echo esc_attr( self::localization( 'bar_filter' ) ); ?>" placeholder="<?php /* translators: count */
													esc_attr_e( 'Filter', 'woo-smart-compare' ); ?>"/> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Filter all', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_filter_all]" value="<?php echo esc_attr( self::localization( 'bar_filter_all' ) ); ?>" placeholder="<?php /* translators: count */
													esc_attr_e( 'All (%d)', 'woo-smart-compare' ); ?>"/> </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Filter other', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[bar_filter_other]" value="<?php echo esc_attr( self::localization( 'bar_filter_other' ) ); ?>" placeholder="<?php /* translators: term & count */
													esc_attr_e( '%1$s (%2$d)', 'woo-smart-compare' ); ?>"/> </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Quick comparison table', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Heading', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[quick_table_heading]" value="<?php echo esc_attr( self::localization( 'quick_table_heading' ) ); ?>" placeholder="<?php esc_attr_e( 'Quick Comparison', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Fields', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Name', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_name]" value="<?php echo esc_attr( self::localization( 'field_name' ) ); ?>" placeholder="<?php esc_attr_e( 'Name', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Image', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_image]" value="<?php echo esc_attr( self::localization( 'field_image' ) ); ?>" placeholder="<?php esc_attr_e( 'Image', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'SKU', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_sku]" value="<?php echo esc_attr( self::localization( 'field_sku' ) ); ?>" placeholder="<?php esc_attr_e( 'SKU', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Rating', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_rating]" value="<?php echo esc_attr( self::localization( 'field_rating' ) ); ?>" placeholder="<?php esc_attr_e( 'Rating', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Price', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_price]" value="<?php echo esc_attr( self::localization( 'field_price' ) ); ?>" placeholder="<?php esc_attr_e( 'Price', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Stock', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_stock]" value="<?php echo esc_attr( self::localization( 'field_stock' ) ); ?>" placeholder="<?php esc_attr_e( 'Stock', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Availability', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_availability]" value="<?php echo esc_attr( self::localization( 'field_availability' ) ); ?>" placeholder="<?php esc_attr_e( 'Availability', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Add to cart', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_add_to_cart]" value="<?php echo esc_attr( self::localization( 'field_add_to_cart' ) ); ?>" placeholder="<?php esc_attr_e( 'Add to cart', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Description', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_description]" value="<?php echo esc_attr( self::localization( 'field_description' ) ); ?>" placeholder="<?php esc_attr_e( 'Description', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Content', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_content]" value="<?php echo esc_attr( self::localization( 'field_content' ) ); ?>" placeholder="<?php esc_attr_e( 'Content', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Weight', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_weight]" value="<?php echo esc_attr( self::localization( 'field_weight' ) ); ?>" placeholder="<?php esc_attr_e( 'Weight', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Dimensions', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_dimensions]" value="<?php echo esc_attr( self::localization( 'field_dimensions' ) ); ?>" placeholder="<?php esc_attr_e( 'Dimensions', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Additional information', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[field_additional]" value="<?php echo esc_attr( self::localization( 'field_additional' ) ); ?>" placeholder="<?php esc_attr_e( 'Additional information', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Menu', 'woo-smart-compare' ); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Menu item label', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <label>
                                                    <input type="text" class="regular-text" name="woosc_localization[menu]" value="<?php echo esc_attr( self::localization( 'menu' ) ); ?>" placeholder="<?php esc_attr_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
												<?php settings_fields( 'woosc_localization' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>Get the Premium Version just $29!
                                        <a href="https://wpclever.net/downloads/smart-compare?utm_source=pro&utm_medium=woosc&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/smart-compare</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Support customization of all attributes.</li>
                                        <li>- Support customization of all product fields, custom fields.</li>
                                        <li>- Free support of compare button’s adjustment to customers’ theme design.
                                        </li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div><!-- /.wpclever_settings_page_content -->
                        <div class="wpclever_settings_page_suggestion">
                            <div class="wpclever_settings_page_suggestion_label">
                                <span class="dashicons dashicons-yes-alt"></span> Suggestion
                            </div>
                            <div class="wpclever_settings_page_suggestion_content">
                                <div>
                                    To display custom engaging real-time messages on any wished positions, please install
                                    <a href="https://wordpress.org/plugins/wpc-smart-messages/" target="_blank">WPC Smart Messages</a> plugin. It's free!
                                </div>
                                <div>
                                    Wanna save your precious time working on variations? Try our brand-new free plugin
                                    <a href="https://wordpress.org/plugins/wpc-variation-bulk-editor/" target="_blank">WPC Variation Bulk Editor</a> and
                                    <a href="https://wordpress.org/plugins/wpc-variation-duplicator/" target="_blank">WPC Variation Duplicator</a>.
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
				}

				function get_filter() {
					$filter = self::get_setting( 'bar_filter', 'no' );
					$select = '';

					if ( $filter !== 'no' ) {
						$filter_terms = [];
						$products     = self::get_products();

						if ( ! empty( $products ) ) {
							foreach ( $products as $product_id ) {
								if ( $product_terms = get_the_terms( $product_id, $filter ) ) {
									foreach ( $product_terms as $term ) {
										if ( ! isset( $filter_terms[ $term->term_id ] ) ) {
											$filter_terms[ $term->term_id ] = [
												'term_id' => $term->term_id,
												'name'    => $term->name,
												'slug'    => $term->slug,
												'count'   => 1,
											];
										} else {
											$filter_terms[ $term->term_id ]['count'] ++;
										}
									}
								}
							}

							if ( ! empty( $filter_terms ) ) {
								$select   .= '<select id="woosc-filter">';
								$select   .= '<option value="_all_">' . sprintf( /* translators: count */ self::localization( 'bar_filter_all', esc_html__( 'All (%d)', 'woo-smart-compare' ) ), count( $products ) ) . '</option>';
								$selected = true;

								foreach ( $filter_terms as $filter_term ) {
									$select   .= '<option value="' . esc_attr( $filter_term['slug'] ) . '" ' . ( $selected ? 'selected' : '' ) . '>' . esc_html( sprintf( /* translators: term & count */ self::localization( 'bar_filter_other', esc_html__( '%1$s (%2$d)', 'woo-smart-compare' ) ), $filter_term['name'], $filter_term['count'] ) ) . '</option>';
									$selected = false;
								}

								$select .= '</select>';
							}
						}
					}

					return apply_filters( 'woosc_get_filter', $select );
				}

				function get_bar() {
					// get items
					$bar      = '';
					$products = self::get_products();
					$filter   = self::get_setting( 'bar_filter', 'no' );

					if ( ! empty( $products ) ) {
						foreach ( $products as $product_id ) {
							$product_obj = wc_get_product( $product_id );

							if ( ! $product_obj || ! apply_filters( 'woosc_product_visible', $product_obj->is_visible(), $product_obj, 'bar' ) ) {
								continue;
							}

							$product_name  = apply_filters( 'woosc_product_name', $product_obj->get_name() );
							$product_class = 'woosc-bar-item';

							if ( ( $filter !== 'no' ) && ( $product_terms = get_the_terms( $product_id, $filter ) ) ) {
								foreach ( $product_terms as $term ) {
									$product_class .= ' woosc-bar-item-' . $term->slug;
								}
							}

							$bar .= '<div class="' . esc_attr( apply_filters( 'woosc_bar_product_class', $product_class, $product_obj ) ) . '" data-id="' . esc_attr( $product_id ) . '">';
							$bar .= '<span class="woosc-bar-item-img hint--top" role="button" aria-label="' . esc_attr( apply_filters( 'woosc_product_name', wp_strip_all_tags( $product_name ), $product_obj ) ) . '">' . $product_obj->get_image( 'woosc-small' ) . '</span>';
							$bar .= '<span class="woosc-bar-item-remove hint--top" role="button" aria-label="' . esc_attr( self::localization( 'bar_remove', esc_html__( 'Remove', 'woo-smart-compare' ) ) ) . '" data-id="' . $product_id . '"></span></div>';
						}
					}

					return apply_filters( 'woosc_get_bar', $bar );
				}

				function get_sidebar() {
					// get items
					$sidebar  = '';
					$link     = self::get_setting( 'link', 'yes' );
					$products = self::get_products();

					if ( ! empty( $products ) ) {
						foreach ( $products as $product_id ) {
							$product_obj = wc_get_product( $product_id );

							if ( ! $product_obj || ! apply_filters( 'woosc_product_visible', $product_obj->is_visible(), $product_obj, 'sidebar' ) ) {
								continue;
							}

							$product_name  = $product_obj->get_name();
							$product_image = $product_obj->get_image();

							if ( $link !== 'no' ) {
								$product_name  = apply_filters( 'woosc_product_name', '<a ' . ( $link === 'yes_popup' ? 'class="woosq-link" data-id="' . $product_id . '" data-context="woosc"' : '' ) . ' href="' . $product_obj->get_permalink() . '" draggable="false" ' . ( $link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . wp_strip_all_tags( $product_name ) . '</a>', $product_obj );
								$product_image = apply_filters( 'woosc_product_image', '<a ' . ( $link === 'yes_popup' ? 'class="woosq-link" data-id="' . $product_id . '" data-context="woosc"' : '' ) . ' href="' . $product_obj->get_permalink() . '" draggable="false" ' . ( $link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $product_image . '</a>', $product_obj );
							} else {
								$product_name  = apply_filters( 'woosc_product_name', wp_strip_all_tags( $product_name ), $product_obj );
								$product_image = apply_filters( 'woosc_product_image', $product_image, $product_obj );
							}

							$sidebar .= '<div class="woosc-sidebar-item" data-id="' . esc_attr( $product_id ) . '"><div class="woosc-sidebar-item-inner">';
							$sidebar .= '<div class="woosc-sidebar-item-remove"><span class="hint--right" role="button" aria-label="' . esc_attr( self::localization( 'sidebar_remove', esc_html__( 'Remove', 'woo-smart-compare' ) ) ) . '"> &times; </span></div>';
							$sidebar .= '<div class="woosc-sidebar-item-thumb">' . $product_image . '</div>';
							$sidebar .= '<div class="woosc-sidebar-item-info">';
							$sidebar .= '<div class="woosc-sidebar-item-name">' . $product_name . '</div>';
							$sidebar .= '<div class="woosc-sidebar-item-price">' . $product_obj->get_price_html() . '</div>';
							$sidebar .= '</div>';
							$sidebar .= '<div class="woosc-sidebar-item-action">' . do_shortcode( '[add_to_cart style="" show_price="false" id="' . esc_attr( $product_id ) . '"]' ) . '</div>';
							$sidebar .= '</div></div>';
						}
					} else {
						$sidebar .= '<div class="woosc-sidebar-no-items">' . esc_html__( 'There are no products on the Compare!', 'woo-smart-compare' ) . '</div>';
					}

					return apply_filters( 'woosc_get_sidebar', $sidebar );
				}

				function get_table( $ajax = true, $products = null, $context = '' ) {
					// get items
					$table         = '';
					$products_data = [];
					$is_share      = false;

					if ( $ajax && ( self::get_setting( 'disable_unauthenticated', 'no' ) === 'yes' ) && ! is_user_logged_in() ) {
						$table = '<div class="woosc-unauthenticated">' . self::localization( 'login', esc_html__( 'Please log in to use the product comparison!', 'woo-smart-compare' ) ) . '</div>';

						return apply_filters( 'woosc_get_table_unauthenticated', $table );
					}

					if ( is_null( $products ) ) {
						if ( get_query_var( 'woosc_id' ) ) {
							$is_share = true;
							$key      = get_query_var( 'woosc_id' );
							$products = explode( ',', get_option( 'woosc_list_' . $key ) ?: '' );
						} else {
							if ( is_user_logged_in() ) {
								update_user_meta( get_current_user_id(), 'woosc_products', self::get_products( 'string' ) );
							}

							$products = self::get_products();
						}
					}

					if ( ! empty( $products ) ) {
						$fields         = self::get_fields( $context );
						$link           = apply_filters( 'woosc_page_product_link', self::get_setting( 'link', 'yes' ) );
						$remove         = apply_filters( 'woosc_page_product_remove', self::get_setting( 'remove', 'yes' ) === 'yes' );
						$remove_all     = apply_filters( 'woosc_page_remove_all', self::get_setting( 'bar_remove', 'no' ) === 'yes' );
						$table_settings = self::get_setting( 'table_settings', 'yes' ) === 'yes';
						$filter         = self::get_setting( 'bar_filter', 'no' );

						global $post;

						foreach ( $products as $product_id ) {
							$post = get_post( $product_id );
							setup_postdata( $post );

							$product_obj    = wc_get_product( $product_id );
							$parent_product = false;

							if ( ! $product_obj || ! apply_filters( 'woosc_product_visible', $product_obj->is_visible(), $product_obj, 'table' ) ) {
								continue;
							}

							if ( $product_obj->is_type( 'variation' ) && ( $parent_product_id = $product_obj->get_parent_id() ) ) {
								$parent_product = wc_get_product( $parent_product_id );
							}

							$products_data[ $product_id ]['id'] = $product_id;

							$product_name = apply_filters( 'woosc_product_name', $product_obj->get_name() );

							if ( $link !== 'no' ) {
								$products_data[ $product_id ]['name'] = apply_filters( 'woosc_product_name', '<a ' . ( $link === 'yes_popup' ? 'class="woosq-link" data-id="' . $product_id . '" data-context="woosc"' : '' ) . ' href="' . $product_obj->get_permalink() . '" draggable="false" ' . ( $link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . wp_strip_all_tags( $product_name ) . '</a>', $product_obj );
							} else {
								$products_data[ $product_id ]['name'] = apply_filters( 'woosc_product_name', wp_strip_all_tags( $product_name ), $product_obj );
							}

							if ( $remove && ! $is_share ) {
								$products_data[ $product_id ]['name'] .= ' <span class="woosc-remove" data-id="' . $product_id . '">' . self::localization( 'table_remove', esc_html__( 'remove', 'woo-smart-compare' ) ) . '</span>';
							}

							if ( $filter !== 'no' ) {
								$product_filter = [];

								if ( $product_terms = get_the_terms( $product_id, $filter ) ) {
									foreach ( $product_terms as $term ) {
										$product_filter[] = $term->slug;
									}
								}

								$products_data[ $product_id ]['filter'] = apply_filters( 'woosc_product_filter', $product_filter, $product_obj );
							}

							foreach ( $fields as $key => $field ) {
								$field      = array_merge( [
									'type'  => '',
									'name'  => '',
									'label' => ''
								], $field );
								$field_type = $field['type'];
								$field_name = $field['name'];

								if ( $field_type === 'default' ) {
									// default fields
									switch ( $field_name ) {
										case 'image':
											$image = $product_obj->get_image( self::get_setting( 'image_size', 'woosc-large' ), [
												'draggable' => 'false',
												'loading'   => self::get_setting( 'bar_print', 'yes' ) === 'yes' ? false : 'lazy'
											] );

											if ( $link !== 'no' ) {
												$products_data[ $product_id ]['image'] = apply_filters( 'woosc_product_image', '<a ' . ( $link === 'yes_popup' ? 'class="woosq-link" data-id="' . $product_id . '" data-context="woosc"' : '' ) . ' href="' . $product_obj->get_permalink() . '" draggable="false" ' . ( $link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $image . '</a>', $product_obj );
											} else {
												$products_data[ $product_id ]['image'] = apply_filters( 'woosc_product_image', $image, $product_obj );
											}

											break;
										case 'sku':
											$products_data[ $product_id ]['sku'] = apply_filters( 'woosc_product_sku', $product_obj->get_sku(), $product_obj );
											break;
										case 'price':
											$products_data[ $product_id ]['price'] = apply_filters( 'woosc_product_price', $product_obj->get_price_html(), $product_obj );
											break;
										case 'stock':
											$products_data[ $product_id ]['stock'] = apply_filters( 'woosc_product_stock', wc_get_stock_html( $product_obj ), $product_obj );
											break;
										case 'add_to_cart':
											$products_data[ $product_id ]['add_to_cart'] = apply_filters( 'woosc_product_add_to_cart', do_shortcode( '[add_to_cart style="" show_price="false" id="' . $product_id . '"]' ), $product_obj );
											break;
										case 'description':
											$description = $product_obj->get_short_description();

											if ( $product_obj->is_type( 'variation' ) ) {
												$description = $product_obj->get_description();

												if ( empty( $description ) && $parent_product ) {
													$description = $parent_product->get_short_description();
												}
											}

											$products_data[ $product_id ]['description'] = apply_filters( 'woosc_product_description', $description, $product_obj );

											break;
										case 'content':
											$content = $product_obj->get_description();

											if ( $parent_product ) {
												$content = $parent_product->get_description();
											}

											$products_data[ $product_id ]['content'] = apply_filters( 'woosc_product_content', do_shortcode( $content ), $product_obj );

											break;
										case 'additional':
											ob_start();
											wc_display_product_attributes( $product_obj );
											$additional = ob_get_clean();

											$products_data[ $product_id ]['additional'] = apply_filters( 'woosc_product_additional', $additional, $product_obj );
											break;
										case 'weight':
											$products_data[ $product_id ]['weight'] = apply_filters( 'woosc_product_weight', wc_format_weight( $product_obj->get_weight() ), $product_obj );
											break;
										case 'dimensions':
											$products_data[ $product_id ]['dimensions'] = apply_filters( 'woosc_product_dimensions', wc_format_dimensions( $product_obj->get_dimensions( false ) ), $product_obj );
											break;
										case 'rating':
											$products_data[ $product_id ]['rating'] = apply_filters( 'woosc_product_rating', wc_get_rating_html( $product_obj->get_average_rating() ), $product_obj );
											break;
										case 'availability':
											$product_availability                         = $product_obj->get_availability();
											$products_data[ $product_id ]['availability'] = apply_filters( 'woosc_product_availability', $product_availability['availability'], $product_obj );
											break;
									}
								}

								if ( $field_type === 'shortcode' ) {
									$products_data[ $product_id ][ 'sc_' . $key ] = apply_filters( 'woosc_product_sc_' . $key, do_shortcode( str_replace( '{product_id}', $product_id, $field_name ) ), $product_obj );
								}
							}
						}

						wp_reset_postdata();

						$count           = count( $products_data );
						$table_class     = 'woosc_table has-' . $count;
						$minimum_columns = intval( apply_filters( 'woosc_get_table_minimum_columns', 3, $products_data ) );

						if ( $minimum_columns > $count ) {
							for ( $i = 1; $i <= ( $minimum_columns - $count ); $i ++ ) {
								$products_data[ 'p' . $i ]['name'] = '';
							}
						}

						$table .= '<table ' . ( $ajax ? 'id="woosc_table"' : '' ) . ' class="' . esc_attr( $table_class ) . '"><thead><tr>';

						$table .= '<th class="th-label">';

						// settings
						if ( ( $context === 'table' ) && $table_settings ) {
							$table .= '<a href="#settings" class="woosc-table-settings">' . self::localization( 'table_settings', esc_html__( 'Settings', 'woo-smart-compare' ) ) . '</a>';
						}

						// remove all
						if ( ( $context === 'page' ) && $remove_all && ! $is_share ) {
							$table .= '<span class="woosc-remove-all" role="button">' . esc_html( self::localization( 'bar_remove_all', esc_html__( 'Remove all', 'woo-smart-compare' ) ) ) . '</span>';
						}

						$table .= '</th>';

						foreach ( $products_data as $product_data ) {
							if ( $product_data['name'] !== '' ) {
								$table .= '<th class="' . esc_attr( ! empty( $product_data['filter'] ) ? 'col col-' . implode( ' col-', $product_data['filter'] ) : 'col' ) . '">' . $product_data['name'] . '</th>';
							} else {
								$table .= '<th class="th-placeholder"></th>';
							}
						}

						$table .= '</tr></thead><tbody>';

						if ( $context === 'table' ) {
							$cookie_fields = self::get_cookie_fields( array_keys( $fields ) );
							$fields        = array_merge( array_flip( $cookie_fields ), $fields );
						} else {
							$cookie_fields = array_keys( $fields );
						}

						// display product name for printing
						if ( self::get_setting( 'bar_print', 'yes' ) === 'yes' ) {
							$table .= apply_filters( 'woosc_print_above_name', '' );
							$table .= '<tr class="tr-name tr-print"><td class="td-label">' . self::localization( 'field_name', esc_html__( 'Name', 'woo-smart-compare' ) ) . '</td>';

							foreach ( $products_data as $product_data ) {
								if ( $product_data['name'] !== '' ) {
									$table .= '<td class="' . esc_attr( ! empty( $product_data['filter'] ) ? 'col col-' . implode( ' col-', $product_data['filter'] ) : 'col' ) . '">' . $product_data['name'] . '</td>';
								} else {
									$table .= '<td class="td-placeholder"></td>';
								}
							}

							$table .= '</tr>';
							$table .= apply_filters( 'woosc_print_below_name', '' );
						}

						$tr = 1;

						foreach ( $fields as $key => $field ) {
							$field       = array_merge( [
								'type'  => '',
								'name'  => '',
								'label' => ''
							], $field );
							$field_type  = $field['type'];
							$field_name  = $field['name'];
							$field_label = $field['label'];
							$field_key   = $field_name;

							if ( $field_type === 'default' ) {
								$field_label = self::$fields[ $field_name ];
							}

							if ( $field_type === 'attribute' ) {
								$field_label = wc_attribute_label( $field_name );
							}

							if ( $field_type === 'custom_attribute' ) {
								$field_key   = 'ca_' . sanitize_title( trim( $field_name ) );
								$field_label = ! empty( $field['label'] ) ? $field['label'] : $field_name;
							}

							if ( $field_type === 'custom_field' ) {
								$field_key   = 'cf_' . sanitize_title( trim( $field_name ) );
								$field_label = ! empty( $field['label'] ) ? $field['label'] : $field_name;
							}

							if ( $field_type === 'shortcode' ) {
								$field_key   = 'sc_' . $key;
								$field_label = ! empty( $field['label'] ) ? $field['label'] : $field_name;
							}

							$row = '<tr class="tr-default tr-' . ( $tr % 2 ? 'odd' : 'even' ) . ' tr-' . esc_attr( $key ) . ' tr-' . esc_attr( $field_key ) . ' ' . ( ! in_array( $key, $cookie_fields ) ? 'tr-hide' : '' ) . '"><td class="td-label">' . esc_html( $field_label ) . '</td>';

							foreach ( $products_data as $product_id => $product_data ) {
								if ( $product_data['name'] !== '' ) {
									if ( isset( $product_data[ $field_key ] ) ) {
										$field_value = $product_data[ $field_key ];
									} else {
										$field_value = '';
									}

									$row .= '<td class="' . esc_attr( ! empty( $product_data['filter'] ) ? 'col col-' . implode( ' col-', $product_data['filter'] ) : 'col' ) . '">' . apply_filters( 'woosc_field_value', $field_value, $field_key, $product_id, $product_data ) . '</td>';
								} else {
									$row .= '<td class="td-placeholder"></td>';
								}
							}

							$row .= '</tr>';
							$tr ++;

							if ( ! empty( $row ) ) {
								$table .= $row;
							}
						}

						$table .= '</tbody></table>';
					} else {
						$table = '<div class="woosc-no-result">' . self::localization( 'table_empty', esc_html__( 'No product is added to the comparison table.', 'woo-smart-compare' ) ) . '</div>';
					}

					return apply_filters( 'woosc_get_table', $table );
				}

				function add_by_link() {
					if ( ! isset( $_REQUEST['add-to-compare'] ) && ! isset( $_REQUEST['add_to_compare'] ) ) {
						return false;
					}

					$product_id = absint( $_REQUEST['add_to_compare'] ?? 0 );
					$product_id = absint( $_REQUEST['add-to-compare'] ?? $product_id );

					if ( ! $product_id ) {
						return false;
					}

					$products = self::get_products();

					// move product to the first
					array_unshift( $products, $product_id );
					$products = array_unique( $products );

					if ( $user_id = get_current_user_id() ) {
						// update user meta
						update_user_meta( $user_id, 'woosc_products', implode( ',', $products ) );
					}

					$cookie = 'woosc_products_' . self::get_user_key();
					setcookie( $cookie, implode( ',', $products ), time() + 604800, '/' );

					// redirect to compare page
					wp_safe_redirect( self::get_page_url() );

					return null;
				}

				function get_category_slug_by_id( $id ) {
					if ( $cat = get_term( $id, 'product_cat' ) ) {
						return $cat->slug;
					}

					return '';
				}

				function ajax_search() {
					if ( ! apply_filters( 'woosc_disable_security_check', false, 'search' ) ) {
						if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woosc-security' ) ) {
							die( 'Permissions check failed!' );
						}
					}

					$keyword        = sanitize_text_field( $_POST['keyword'] );
					$cats           = self::get_setting( 'search_cats', [] );
					$related        = [];
					$products       = self::get_products();
					$args['status'] = [ 'publish' ];
					$args['limit']  = self::get_setting( 'search_count', 10 );

					if ( empty( $cats ) ) {
						$cats = [ '0' ];
					}

					if ( is_array( $cats ) && ( count( $cats ) > 0 ) && ( $cats[0] !== '0' ) ) {
						$args['category'] = array_map( [ $this, 'get_category_slug_by_id' ], $cats );
					}

					if ( empty( $keyword ) ) {
						// default products
						if ( ! empty( $products ) ) {
							foreach ( $products as $pid ) {
								if ( $rl = wc_get_related_products( $pid ) ) {
									$related = array_merge( $related, $rl );
								}
							}
						}

						foreach ( $related as $k => $r ) {
							if ( in_array( $r, $products ) ) {
								// exclude added products
								unset( $related[ $k ] );
							}
						}

						$related = apply_filters( 'woosc_search_default_products', array_unique( $related ), $products );

						if ( ! empty( $related ) ) {
							$args['include'] = $related;
						}
					} else {
						$args['s'] = $keyword;
					}

					$prs = wc_get_products( apply_filters( 'woosc_search_args', $args ) );

					if ( ! empty( $prs ) ) {
						echo '<ul>';

						foreach ( $prs as $pr ) {
							if ( apply_filters( 'woosc_search_exclude', false, $pr, $products ) ) {
								continue;
							}

							echo '<li>';
							echo '<div class="item-inner">';
							echo '<div class="item-image">' . $pr->get_image( 'woosc-small' ) . '</div>';
							echo '<div class="item-name">' . $pr->get_name() . '</div>';
							echo '<div class="item-add woosc-item-add" data-id="' . $pr->get_id() . '"><span>+</span></div>';
							echo '</div>';
							echo '</li>';
						}

						echo '</ul>';
					} else {
						echo '<ul><span>' . sprintf( self::localization( 'bar_search_no_results', /* translators: keyword */ esc_html__( 'No results found for "%s"', 'woo-smart-compare' ) ), $keyword ) . '</span></ul>';
					}

					wp_die();
				}

				function ajax_share() {
					if ( ! apply_filters( 'woosc_disable_security_check', false, 'share' ) ) {
						if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woosc-security' ) ) {
							die( 'Permissions check failed!' );
						}
					}

					$products = self::get_products( 'string' );

					if ( ! empty( $products ) ) {
						$hash = md5( $products );

						if ( ! $key = get_option( 'woosc_hash_' . $hash ) ) {
							$key = self::generate_key();

							while ( self::exists_key( $key ) ) {
								$key = self::generate_key();
							}

							update_option( 'woosc_hash_' . $hash, $key );
							update_option( 'woosc_list_' . $key, $products );
						}

						$url = self::get_share_url( $key );

						if ( ! empty( $url ) ) {
							?>
                            <div class="woosc-share-text">
								<?php echo self::localization( 'share_description', esc_html__( 'Share link was generated! Now you can copy below link to share.', 'woo-smart-compare' ) ); ?>
                            </div>
                            <div class="woosc-share-link">
                                <label for="woosc_copy_url"></label><input type="url" id="woosc_copy_url" value="<?php echo esc_url( $url ); ?>" readonly/>
                            </div>
							<?php
							echo self::share_links( urlencode( $url ) );
						}
					} else {
						echo self::localization( 'table_empty', esc_html__( 'No product is added to the comparison table.', 'woo-smart-compare' ) );
					}

					wp_die();
				}

				function ajax_load() {
					if ( ! apply_filters( 'woosc_disable_security_check', false, 'load_data' ) ) {
						if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woosc-security' ) ) {
							die( 'Permissions check failed!' );
						}
					}

					$data = [];

					if ( isset( $_REQUEST['get_data'] ) && ( sanitize_key( $_REQUEST['get_data'] ) === 'bar' ) ) {
						$data['bar']    = self::get_bar();
						$data['filter'] = self::get_filter();
					}

					if ( isset( $_REQUEST['get_data'] ) && ( sanitize_key( $_REQUEST['get_data'] ) === 'table' ) ) {
						$data['bar']    = self::get_bar();
						$data['table']  = self::get_table( true, null, 'table' );
						$data['filter'] = self::get_filter();
					}

					if ( isset( $_REQUEST['get_data'] ) && ( sanitize_key( $_REQUEST['get_data'] ) === 'sidebar' ) ) {
						$data['sidebar'] = self::get_sidebar();
						$data['filter']  = self::get_filter();
					}

					if ( isset( $_REQUEST['get_data'] ) && ( sanitize_key( $_REQUEST['get_data'] ) === 'count' ) ) {
						$data['count'] = self::get_count();
					}

					wp_send_json( $data );
				}

				function add_button() {
					echo do_shortcode( '[woosc]' );
				}

				function shortcode_btn( $attrs ) {
					$output = $product_name = $product_image = '';

					$attrs = shortcode_atts( [
						'id'   => null,
						'type' => self::get_setting( 'button_type', 'button' )
					], $attrs );

					if ( ! $attrs['id'] ) {
						global $product;

						if ( $product && is_a( $product, 'WC_Product' ) ) {
							$attrs['id']      = $product->get_id();
							$product_name     = $product->get_name();
							$product_image_id = $product->get_image_id();
							$product_image    = wp_get_attachment_image_url( $product_image_id );
						}
					} else {
						if ( $_product = wc_get_product( $attrs['id'] ) ) {
							$product_name     = $_product->get_name();
							$product_image_id = $_product->get_image_id();
							$product_image    = wp_get_attachment_image_url( $product_image_id );
						}
					}

					if ( $attrs['id'] ) {
						// check cats
						$cats = self::get_setting( 'search_cats', [] );

						if ( ! empty( $cats ) && ( $cats[0] !== '0' ) ) {
							if ( ! has_term( $cats, 'product_cat', $attrs['id'] ) ) {
								return '';
							}
						}

						// button class
						$class = 'woosc-btn woosc-btn-' . esc_attr( $attrs['id'] ) . ' ' . self::get_setting( 'button_class' );

						// button text
						$text = self::localization( 'button', esc_html__( 'Compare', 'woo-smart-compare' ) );

						if ( ( $button_icon = self::get_setting( 'button_icon', 'no' ) ) !== 'no' ) {
							$class .= ' woosc-btn-has-icon';
							$icon  = apply_filters( 'woosc_button_normal_icon', self::get_setting( 'button_normal_icon', 'woosc-icon-1' ) );

							if ( $button_icon === 'left' ) {
								$class .= ' woosc-btn-icon-text';
								$text  = '<span class="woosc-btn-icon ' . esc_attr( $icon ) . '"></span><span class="woosc-btn-text">' . esc_html( $text ) . '</span>';
							} elseif ( $button_icon === 'right' ) {
								$class .= ' woosc-btn-text-icon';
								$text  = '<span class="woosc-btn-text">' . esc_html( $text ) . '</span><span class="woosc-btn-icon ' . esc_attr( $icon ) . '"></span>';
							} else {
								$class .= ' woosc-btn-icon-only';
								$text  = '<span class="woosc-btn-icon ' . esc_attr( $icon ) . '"></span>';
							}
						}

						if ( $attrs['type'] === 'link' ) {
							$output = '<a href="' . esc_url( '?add-to-compare=' . $attrs['id'] ) . '" class="' . esc_attr( $class ) . '" data-id="' . esc_attr( $attrs['id'] ) . '" data-product_name="' . esc_attr( $product_name ) . '" data-product_image="' . esc_attr( $product_image ) . '" rel="' . esc_attr( apply_filters( 'woosc_button_rel', 'nofollow' ) ) . '">' . $text . '</a>';
						} else {
							$output = '<button class="' . esc_attr( $class ) . '" data-id="' . esc_attr( $attrs['id'] ) . '" data-product_name="' . esc_attr( $product_name ) . '" data-product_image="' . esc_attr( $product_image ) . '">' . $text . '</button>';
						}
					}

					return apply_filters( 'woosc_button_html', $output, $attrs['id'] );
				}

				function shortcode_link() {
					$output = '<span class="woosc-link"><a href="' . esc_url( self::get_url() ) . '"><span class="woosc-link-inner" data-count="' . esc_attr( self::get_count() ) . '">' . esc_html( self::localization( 'link_label', esc_html__( 'Compare', 'woo-smart-compare' ) ) ) . '</span></a></span>';

					return apply_filters( 'woosc_link_html', $output );
				}

				function shortcode_list( $attrs ) {
					$attrs = shortcode_atts( [
						'products' => null,
					], $attrs );

					if ( $attrs['products'] ) {
						$attrs['products'] = array_map( 'absint', explode( ',', $attrs['products'] ) );
					}

					return '<div class="woosc_list woosc-list woosc_page woosc-page">' . self::get_table( false, $attrs['products'], 'page' ) . '</div>';
				}

				function footer() {
					if ( is_admin() || get_query_var( 'woosc_id' ) ) {
						return;
					}

					$class = 'woosc-area';
					$class .= ' woosc-bar-' . self::get_setting( 'bar_pos', 'bottom' ) . ' woosc-bar-' . self::get_setting( 'bar_align', 'right' ) . ' woosc-bar-click-outside-' . str_replace( '_', '-', self::get_setting( 'click_outside', 'yes' ) );

					if ( self::get_setting( 'hide_checkout', 'yes' ) === 'yes' ) {
						$class .= ' woosc-hide-checkout';
					}

					if ( self::get_setting( 'hide_empty', 'no' ) === 'yes' ) {
						$class .= ' woosc-hide-empty';
					}

					if ( ( self::get_setting( 'disable_unauthenticated', 'no' ) === 'yes' ) && ! is_user_logged_in() ) {
						$class .= ' woosc-need-login';
					}

					$bar_bg_color_default  = apply_filters( 'woosc_bar_bg_color_default', '#292a30' );
					$bar_btn_color_default = apply_filters( 'woosc_bar_btn_color_default', '#00a0d2' );

					if ( self::get_setting( 'bar_add', 'yes' ) === 'yes' ) {
						?>
                        <div class="woosc-popup woosc-search">
                            <div class="woosc-popup-inner">
                                <div class="woosc-popup-content">
                                    <div class="woosc-popup-content-inner">
                                        <div class="woosc-popup-close"></div>
                                        <div class="woosc-search-input">
                                            <label for="woosc_search_input"></label><input type="search" id="woosc_search_input" placeholder="<?php echo esc_attr( self::localization( 'bar_search_placeholder', esc_html__( 'Type any keyword to search...', 'woo-smart-compare' ) ) ); ?>"/>
                                        </div>
                                        <div class="woosc-search-result"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
					}

					if ( self::get_setting( 'table_settings', 'yes' ) === 'yes' ) {
						$default_settings = self::get_setting( 'default_settings', [] );
						$cookie_settings  = self::get_cookie_settings( $default_settings );
						?>
                        <div class="woosc-popup woosc-settings">
                            <div class="woosc-popup-inner">
                                <div class="woosc-popup-content">
                                    <div class="woosc-popup-content-inner">
                                        <div class="woosc-popup-close"></div>
                                        <ul class="woosc-settings-tools">
                                            <li>
                                                <label><input type="checkbox" class="woosc-settings-tool" value="hide_similarities" id="woosc_hide_similarities" <?php echo esc_attr( in_array( 'hide_similarities', $cookie_settings ) ? 'checked' : '' ); ?>/> <?php echo self::localization( 'hide_similarities', esc_html__( 'Hide similarities', 'woo-smart-compare' ) ); ?>
                                                </label></li>
                                            <li>
                                                <label><input type="checkbox" class="woosc-settings-tool" value="highlight_differences" id="woosc_highlight_differences" <?php echo esc_attr( in_array( 'highlight_differences', $cookie_settings ) ? 'checked' : '' ); ?>/> <?php echo self::localization( 'highlight_differences', esc_html__( 'Highlight differences', 'woo-smart-compare' ) ); ?>
                                                </label></li>
                                        </ul>
										<?php echo self::localization( 'bar_select_fields_desc', esc_html__( 'Select the fields to be shown. Others will be hidden. Drag and drop to rearrange the order.', 'woo-smart-compare' ) ); ?>
                                        <ul class="woosc-settings-fields">
											<?php
											$fields        = self::get_fields();
											$fields_keys   = array_keys( $fields );
											$cookie_fields = self::get_cookie_fields( $fields_keys );
											$fields_merge  = array_unique( array_merge( $cookie_fields, $fields_keys ), SORT_REGULAR );

											foreach ( $fields_merge as $field_key ) {
												if ( isset( $fields[ $field_key ] ) ) {
													$field       = array_merge( [
														'type'  => '',
														'name'  => '',
														'label' => ''
													], $fields[ $field_key ] );
													$field_type  = $field['type'];
													$field_name  = $field['name'];
													$field_label = $field['label'];

													if ( $field_type === 'default' ) {
														$field_label = self::$fields[ $field_name ];
													}

													if ( $field_type === 'attribute' ) {
														$field_label = wc_attribute_label( $field_name );
													}

													if ( $field_type === 'custom_attribute' || $field_type === 'custom_field' || $field_type === 'shortcode' ) {
														$field_label = ! empty( $field['label'] ) ? $field['label'] : $field_name;
													}

													echo '<li class="woosc-settings-field-li"><input type="checkbox" class="woosc-settings-field" value="' . esc_attr( $field_key ) . '" ' . ( in_array( $field_key, $cookie_fields ) ? 'checked' : '' ) . '/><span class="move">' . esc_html( $field_label ) . '</span></li>';
												}
											}
											?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php }

					if ( self::get_setting( 'bar_share', 'yes' ) === 'yes' ) {
						?>
                        <div class="woosc-popup woosc-share">
                            <div class="woosc-popup-inner">
                                <div class="woosc-popup-content">
                                    <div class="woosc-popup-content-inner">
                                        <div class="woosc-popup-close"></div>
                                        <div class="woosc-share-content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php }
					?>
                    <div id="woosc-area" class="<?php echo esc_attr( apply_filters( 'woosc_area_class', $class ) ); ?>" data-bg-color="<?php echo esc_attr( apply_filters( 'woosc_bar_bg_color', self::get_setting( 'bar_bg_color', $bar_bg_color_default ) ) ); ?>" data-btn-color="<?php echo esc_attr( apply_filters( 'woosc_bar_btn_color', self::get_setting( 'bar_btn_color', $bar_btn_color_default ) ) ); ?>">
                        <div class="woosc-inner">
                            <div class="woosc-table">
                                <div class="woosc-table-inner">
									<?php if ( 'yes' === self::get_setting( 'close_button', 'yes' ) ) { ?>
                                        <a href="#close" id="woosc-table-close" class="woosc-table-close hint--left" aria-label="<?php echo esc_attr( self::localization( 'table_close', esc_html__( 'Close', 'woo-smart-compare' ) ) ); ?>"><span class="woosc-table-close-icon"></span></a>
									<?php } ?>
                                    <div class="woosc-table-items"></div>
                                </div>
                            </div>

                            <div class="<?php echo esc_attr( self::get_setting( 'bar_bubble', 'no' ) === 'yes' ? 'woosc-bar woosc-bar-bubble' : 'woosc-bar' ); ?>">
								<?php if ( self::get_setting( 'click_outside', 'yes' ) !== 'no' && self::get_setting( 'bar_bubble', 'no' ) !== 'yes' ) { ?>
                                    <div class="woosc-bar-notice">
										<?php echo self::localization( 'bar_click_outside', esc_html__( 'Click outside to hide the comparison bar', 'woo-smart-compare' ) ); ?>
                                    </div>
								<?php }

								if ( self::get_setting( 'bar_print', 'yes' ) === 'yes' ) { ?>
                                    <a href="#print" class="woosc-bar-print hint--top" aria-label="<?php echo esc_attr( self::localization( 'bar_print', esc_html__( 'Print', 'woo-smart-compare' ) ) ); ?>"></a>
								<?php }

								if ( self::get_setting( 'bar_share', 'yes' ) === 'yes' ) { ?>
                                    <a href="#share" class="woosc-bar-share hint--top" aria-label="<?php echo esc_attr( self::localization( 'bar_share', esc_html__( 'Share', 'woo-smart-compare' ) ) ); ?>"></a>
								<?php }

								if ( self::get_setting( 'bar_add', 'yes' ) === 'yes' ) { ?>
                                    <a href="#search" class="woosc-bar-search hint--top" aria-label="<?php echo esc_attr( self::localization( 'bar_add', esc_html__( 'Add product', 'woo-smart-compare' ) ) ); ?>"></a>
								<?php }

								if ( self::get_setting( 'bar_filter', 'no' ) !== 'no' ) {
									echo '<span class="woosc-bar-filter hint--top" aria-label="' . esc_attr( self::localization( 'bar_filter', esc_html__( 'Filter', 'woo-smart-compare' ) ) ) . '">' . self::get_filter() . '</span>';
								}

								echo '<div class="woosc-bar-items"></div>';

								if ( self::get_setting( 'bar_remove', 'no' ) === 'yes' ) { ?>
                                    <div class="woosc-bar-remove hint--top" role="button" aria-label="<?php echo esc_attr( self::localization( 'bar_remove_all', esc_html__( 'Remove all', 'woo-smart-compare' ) ) ); ?>"></div>
								<?php } ?>

                                <div class="woosc-bar-btn woosc-bar-btn-text">
                                    <div class="woosc-bar-btn-icon-wrapper">
                                        <div class="woosc-bar-btn-icon-inner"><span></span><span></span><span></span>
                                        </div>
                                    </div>
									<?php echo apply_filters( 'woosc_bar_btn_text', self::localization( 'bar_button', esc_html__( 'Compare', 'woo-smart-compare' ) ) ); ?>
                                </div>
                            </div>

							<?php if ( self::get_setting( 'button_action', 'show_table' ) === 'show_sidebar' || self::get_setting( 'menu_action', 'open_popup' ) === 'open_sidebar' || self::get_setting( 'open_button_action', 'open_popup' ) === 'open_sidebar' ) { ?>
                                <div class="<?php echo esc_attr( 'woosc-sidebar woosc-sidebar-position-' . self::get_setting( 'sidebar_position', 'right' ) ); ?>">
                                    <div class="woosc-sidebar-top">
                                        <span class="woosc-sidebar-heading"><?php echo self::localization( 'sidebar_heading', esc_html__( 'Compare', 'woo-smart-compare' ) ); ?></span>
                                        <span class="woosc-sidebar-count"></span>
                                        <span class="woosc-sidebar-close hint--left" role="button" aria-label="<?php echo esc_attr( self::localization( 'sidebar_close', esc_html__( 'Close', 'woo-smart-compare' ) ) ); ?>"> &times; </span>
                                    </div>
                                    <div class="woosc-sidebar-items"></div>
                                    <div class="woosc-sidebar-bot">
                                        <span class="woosc-sidebar-btn"><?php echo self::localization( 'sidebar_button', esc_html__( 'Let\'s Compare!', 'woo-smart-compare' ) ); ?></span>
                                        <span class="woosc-sidebar-continue"><span><?php echo self::localization( 'sidebar_continue', esc_html__( 'Continue shopping', 'woo-smart-compare' ) ); ?></span></span>
                                    </div>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function get_cookie_fields( $saved_fields ) {
					$hash          = self::get_setting( 'hash', '6' );
					$cookie_fields = 'woosc_fields_' . $hash . '_' . self::get_user_key();

					if ( ! empty( $_COOKIE[ $cookie_fields ] ) ) {
						$fields = explode( ',', sanitize_text_field( $_COOKIE[ $cookie_fields ] ) );
					} else {
						$fields = $saved_fields;
					}

					return $fields;
				}

				function get_cookie_settings( $saved_settings ) {
					$hash            = self::get_setting( 'hash', '6' );
					$cookie_settings = 'woosc_settings_' . $hash . '_' . self::get_user_key();

					if ( isset( $_COOKIE[ $cookie_settings ] ) ) {
						$settings = explode( ',', sanitize_text_field( $_COOKIE[ $cookie_settings ] ) );
					} else {
						$settings = $saved_settings;
					}

					return $settings;
				}

				function exists_key( $key ) {
					if ( get_option( 'woosc_list_' . $key ) ) {
						return true;
					}

					return false;
				}

				function nav_menu_items( $items, $args ) {
					$selected    = false;
					$saved_menus = self::get_setting( 'menus', [] );

					if ( ! is_array( $saved_menus ) || empty( $saved_menus ) || ! property_exists( $args, 'menu' ) ) {
						return $items;
					}

					if ( $args->menu instanceof WP_Term ) {
						// menu object
						if ( in_array( $args->menu->term_id, $saved_menus ) ) {
							$selected = true;
						}
					} elseif ( is_numeric( $args->menu ) ) {
						// menu id
						if ( in_array( $args->menu, $saved_menus ) ) {
							$selected = true;
						}
					} elseif ( is_string( $args->menu ) ) {
						// menu slug or name
						$menu = get_term_by( 'name', $args->menu, 'nav_menu' );

						if ( ! $menu ) {
							$menu = get_term_by( 'slug', $args->menu, 'nav_menu' );
						}

						if ( $menu && in_array( $menu->term_id, $saved_menus ) ) {
							$selected = true;
						}
					}

					if ( $selected ) {
						$menu_item = '<li class="' . apply_filters( 'woosc_menu_item_class', 'menu-item woosc-menu-item menu-item-type-woosc' ) . '"><a href="' . self::get_page_url() . '"><span class="woosc-menu-item-inner" data-count="' . self::get_count() . '">' . apply_filters( 'woosc_menu_item_label', self::localization( 'menu', esc_html__( 'Compare', 'woo-smart-compare' ) ) ) . '</span></a></li>';
						$items     .= apply_filters( 'woosc_menu_item', $menu_item );
					}

					return $items;
				}

				function account_items( $items ) {
					if ( isset( $items['customer-logout'] ) ) {
						$logout = $items['customer-logout'];
						unset( $items['customer-logout'] );
					} else {
						$logout = '';
					}

					if ( ! isset( $items['compare'] ) ) {
						$items['compare'] = apply_filters( 'woosc_myaccount_compare_label', esc_html__( 'Compare', 'woo-smart-compare' ) );
					}

					if ( $logout ) {
						$items['customer-logout'] = $logout;
					}

					return $items;
				}

				function account_endpoint() {
					echo apply_filters( 'woosc_myaccount_compare_content', do_shortcode( '[woosc_list]' ) );
				}

				function show_quick_table() {
					echo do_shortcode( '[woosc_quick_table]' );
				}

				function shortcode_quick_table() {
					global $product;

					if ( ! $product ) {
						return '';
					}

					$product_id = $product->get_id();
					$related    = wc_get_related_products( $product_id );

					if ( empty( $related ) ) {
						return '';
					}

					array_unshift( $related, $product_id );

					$quick_table_class = 'woosc-quick-table label-column-' . self::get_setting( 'quick_table_label', 'no' );
					ob_start();
					?>
                    <section class="<?php echo esc_attr( apply_filters( 'woosc_quick_table_class', $quick_table_class ) ); ?>">
						<?php
						do_action( 'woosc_before_quick_table', $product );
						echo apply_filters( 'woosc_quick_table_heading', '<h2>' . self::localization( 'quick_table_heading', esc_html__( 'Quick Comparison', 'woo-smart-compare' ) ) . '</h2>' );
						?>
                        <div class="woosc-quick-table-products">
							<?php
							do_action( 'woosc_before_quick_table_products', $product );
							echo self::get_table( false, $related, 'quick_table' );
							do_action( 'woosc_after_quick_table_products', $product );
							?>
                        </div>
						<?php do_action( 'woosc_after_quick_table', $product ); ?>
                    </section>
					<?php
					return ob_get_clean();
				}

				function wcml_multi_currency( $ajax_actions ) {
					$ajax_actions[] = 'woosc_load';

					return $ajax_actions;
				}

				function wpcsm_locations( $locations ) {
					$locations['WPC Smart Compare'] = [
						'woosc_before_quick_table'          => esc_html__( 'Before quick table', 'woo-smart-compare' ),
						'woosc_after_quick_table'           => esc_html__( 'After quick table', 'woo-smart-compare' ),
						'woosc_before_quick_table_products' => esc_html__( 'Before quick table products', 'woo-smart-compare' ),
						'woosc_after_quick_table_products'  => esc_html__( 'After quick table products', 'woo-smart-compare' ),
					];

					return $locations;
				}

				function get_image_sizes() {
					global $_wp_additional_image_sizes;
					$sizes = [];

					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, [ 'thumbnail', 'medium', 'medium_large', 'large' ] ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = [
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							];
						}
					}

					return $sizes;
				}

				function ajax_add_field() {
					$type    = sanitize_key( $_POST['type'] ?? '' );
					$field   = sanitize_text_field( urldecode( $_POST['field'] ?? '' ) );
					$setting = sanitize_key( $_POST['setting'] ?? '' );

					if ( ! empty( $type ) && ! empty( $field ) && ! empty( $setting ) ) {
						if ( ( $type === 'attribute' ) && ( $field === 'all' ) ) {
							// all attributes
							if ( $taxonomies = get_object_taxonomies( 'product', 'objects' ) ) {
								foreach ( $taxonomies as $taxonomy ) {
									if ( str_starts_with( $taxonomy->name, 'pa_' ) ) {
										$key = self::generate_key( 4, true );
										self::field_html( $key, $setting, $type, $taxonomy->name, wc_attribute_label( $taxonomy->name ) );
									}
								}
							}
						} else {
							$key   = self::generate_key( 4, true );
							$title = '';

							switch ( $type ) {
								case 'default':
									if ( isset( self::$fields[ $field ] ) ) {
										$title = self::$fields[ $field ];
									}

									break;
								case 'attribute':
									$title = wc_attribute_label( $field );

									break;
								case 'custom_attribute':
									$title = esc_html__( 'Custom attribute', 'woo-smart-compare' );

									break;
								case 'custom_field':
									$title = esc_html__( 'Custom field', 'woo-smart-compare' );

									break;
								case 'shortcode':
									$title = esc_html__( 'Custom text/shortcode', 'woo-smart-compare' );

									break;
							}

							self::field_html( $key, $setting, $type, $field, $title );
						}
					}

					wp_die();
				}

				function field_html( $key, $setting, $type, $field, $title, $label = '' ) {
					echo '<div class="woosc-field woosc-field-' . $key . ' woosc-field-type-' . $type . '">';
					echo '<span class="move">' . esc_html__( 'move', 'woo-smart-compare' ) . '</span>';
					echo '<span class="info">';
					echo '<span class="title">' . esc_html( $title ) . '</span>';
					echo '<input class="woosc-field-type" type="hidden" name="woosc_settings[' . $setting . '][' . $key . '][type]" value="' . esc_attr( $type ) . '"/>';

					if ( ( $type === 'custom_field' ) && ( $meta_keys = self::get_meta_keys() ) ) {
						echo '<select class="woosc-field-name" name="woosc_settings[' . $setting . '][' . $key . '][name]">';

						foreach ( $meta_keys as $meta_key ) {
							echo '<option value="' . esc_attr( $meta_key ) . '" ' . selected( $field, $meta_key, false ) . '>' . esc_html( $meta_key ) . '</option>';
						}

						echo '</select>';
					} else {
						echo '<input class="woosc-field-name" type="text" name="woosc_settings[' . $setting . '][' . $key . '][name]" value="' . esc_attr( $field ) . '" placeholder="' . esc_attr__( 'name', 'woo-smart-compare' ) . '"/>';
					}

					echo '<input class="woosc-field-label" type="text" name="woosc_settings[' . $setting . '][' . $key . '][label]" value="' . esc_attr( $label ) . '" placeholder="' . esc_attr__( 'label', 'woo-smart-compare' ) . '"/>';
					echo '</span>';
					echo '<span class="remove">&times;</span>';
					echo '</div>';
				}

				function get_page_id() {
					if ( self::get_setting( 'page_id' ) ) {
						return absint( self::get_setting( 'page_id' ) );
					}

					return false;
				}

				function get_share_url( $key ) {
					$url = home_url( '/' );

					if ( $page_id = self::get_page_id() ) {
						if ( get_option( 'permalink_structure' ) !== '' ) {
							$url = trailingslashit( get_permalink( $page_id ) ) . $key;
						} else {
							$url = get_permalink( $page_id ) . '&woosc_id=' . $key;
						}
					}

					return apply_filters( 'woosc_get_share_url', $url );
				}

				function share_links( $url ) {
					$share_links = '';
					$facebook    = esc_html__( 'Facebook', 'woo-smart-compare' );
					$twitter     = esc_html__( 'Twitter', 'woo-smart-compare' );
					$pinterest   = esc_html__( 'Pinterest', 'woo-smart-compare' );
					$mail        = esc_html__( 'Mail', 'woo-smart-compare' );
					$links       = [ 'facebook', 'twitter', 'pinterest', 'mail' ];

					if ( ! empty( $links ) ) {
						$share_links .= '<div class="woosc-share-links">';
						$share_links .= '<span class="woosc-share-label">' . self::localization( 'share_on', esc_html__( 'Share on:', 'woo-smart-compare' ) ) . '</span>';
						$share_links .= ( in_array( 'facebook', $links ) ) ? '<a class="woosc-share-facebook" href="https://www.facebook.com/sharer.php?u=' . $url . '" target="_blank">' . $facebook . '</a>' : '';
						$share_links .= ( in_array( 'twitter', $links ) ) ? '<a class="woosc-share-twitter" href="https://twitter.com/share?url=' . $url . '" target="_blank">' . $twitter . '</a>' : '';
						$share_links .= ( in_array( 'pinterest', $links ) ) ? '<a class="woosc-share-pinterest" href="https://pinterest.com/pin/create/button/?url=' . $url . '" target="_blank">' . $pinterest . '</a>' : '';
						$share_links .= ( in_array( 'mail', $links ) ) ? '<a class="woosc-share-mail" href="mailto:?body=' . $url . '" target="_blank">' . $mail . '</a>' : '';
						$share_links .= '</div>';
					}

					return apply_filters( 'woosc_share_links', $share_links, $url );
				}

				function dropdown_cats_multiple( $output, $r ) {
					if ( isset( $r['multiple'] ) && $r['multiple'] ) {
						$output = preg_replace( '/^<select/i', '<select multiple', $output );
						$output = str_replace( "name='{$r['name']}'", "name='{$r['name']}[]'", $output );

						foreach ( array_map( 'trim', explode( ',', $r['selected'] ) ) as $value ) {
							$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
						}
					}

					return $output;
				}

				function save_post( $post_id, $post ) {
					if ( $post->post_type === 'product' ) {
						delete_transient( 'woosc_get_product_meta_keys' );
					}
				}

				public static function woosc_get_page_url() {
					$page_id  = self::get_setting( 'page_id' );
					$page_url = ! empty( $page_id ) ? get_permalink( $page_id ) : '#';

					return apply_filters( 'woosc_get_page_url', esc_url( $page_url ) );
				}

				public static function get_page_url() {
					return self::woosc_get_page_url();
				}

				public static function get_url() {
					return self::woosc_get_page_url();
				}

				public static function get_user_key( $user_id = 0 ) {
					if ( ! $user_id ) {
						$user_id = get_current_user_id();
					}

					return apply_filters( 'woosc_get_user_key', md5( 'woosc' . $user_id ), $user_id );
				}

				public static function get_products( $type = 'array' ) {
					$products = $type === 'string' ? '' : [];
					$cookie   = 'woosc_products_' . self::get_user_key();

					if ( ! empty( $_COOKIE[ $cookie ] ) ) {
						if ( $type === 'string' ) {
							$products = sanitize_text_field( $_COOKIE[ $cookie ] );
						} else {
							$products = explode( ',', sanitize_text_field( $_COOKIE[ $cookie ] ) );
						}
					}

					return apply_filters( 'woosc_get_products', $products, $type );
				}

				public static function woosc_get_count() {
					$products = self::get_products();

					return apply_filters( 'woosc_get_count', count( $products ) );
				}

				public static function get_count() {
					return self::woosc_get_count();
				}

				public static function get_settings() {
					return apply_filters( 'woosc_get_settings', self::$settings );
				}

				public static function get_fields( $context = '' ) {
					if ( $context === 'quick_table' ) {
						$saved_fields6 = self::get_setting( 'quick_fields6', [] );

						if ( empty( $saved_fields6 ) ) {
							// get old data - before 6.0

							if ( is_array( self::get_setting( 'quick_fields' ) ) ) {
								$saved_fields = self::get_setting( 'quick_fields' );
							} else {
								$saved_fields = array_keys( self::$fields );
							}

							foreach ( $saved_fields as $saved_field ) {
								$sk = self::generate_key( 4, true );

								if ( $saved_field === 'attributes' ) {
									if ( ( $saved_attributes = self::get_setting( 'quick_attributes' ) ) && is_array( $saved_attributes ) && ! empty( $saved_attributes ) ) {
										foreach ( $saved_attributes as $saved_attribute ) {
											$sk_a = self::generate_key( 4, true );

											$saved_fields6[ $sk_a ] = [
												'type' => 'attribute',
												'name' => 'pa_' . $saved_attribute,
											];
										}
									}
								} elseif ( $saved_field === 'custom_attributes' ) {
									if ( ( $custom_attributes = explode( ',', self::get_setting( 'quick_custom_attributes' ) ) ) && is_array( $custom_attributes ) && ! empty( $custom_attributes ) ) {
										foreach ( $custom_attributes as $custom_attribute ) {
											if ( ! empty( $custom_attribute ) ) {
												$sk_ca = self::generate_key( 4, true );

												$saved_fields6[ $sk_ca ] = [
													'type'  => 'custom_attribute',
													'name'  => $custom_attribute,
													'label' => $custom_attribute,
												];
											}
										}
									}
								} elseif ( $saved_field === 'custom_fields' ) {
									if ( ( $custom_fields = explode( ',', self::get_setting( 'quick_custom_fields' ) ) ) && is_array( $custom_fields ) && ! empty( $custom_fields ) ) {
										foreach ( $custom_fields as $custom_field ) {
											if ( ! empty( $custom_field ) ) {
												$custom_field_arr   = explode( '|', $custom_field );
												$custom_field_name  = trim( $custom_field_arr[0] ?? '' );
												$custom_field_label = trim( $custom_field_arr[1] ?? $custom_field_name );

												if ( ! empty( $custom_field_name ) ) {
													$sk_cf = self::generate_key( 4, true );

													$saved_fields6[ $sk_cf ] = [
														'type'  => 'custom_field',
														'name'  => $custom_field_name,
														'label' => $custom_field_label,
													];
												}
											}
										}
									}
								} else {
									$saved_fields6[ $sk ] = [
										'type' => 'default',
										'name' => $saved_field
									];
								}
							}
						}
					} else {
						$saved_fields6 = self::get_setting( 'fields6', [] );

						if ( empty( $saved_fields6 ) ) {
							// get old data - before 6.0

							if ( is_array( self::get_setting( 'fields' ) ) ) {
								$saved_fields = self::get_setting( 'fields' );
							} else {
								$saved_fields = array_keys( self::$fields );
							}

							foreach ( $saved_fields as $saved_field ) {
								$sk = self::generate_key( 4, true );

								if ( $saved_field === 'attributes' ) {
									if ( ( $saved_attributes = self::get_setting( 'attributes' ) ) && is_array( $saved_attributes ) && ! empty( $saved_attributes ) ) {
										foreach ( $saved_attributes as $saved_attribute ) {
											$sk_a = self::generate_key( 4, true );

											$saved_fields6[ $sk_a ] = [
												'type' => 'attribute',
												'name' => 'pa_' . $saved_attribute
											];
										}
									}
								} elseif ( $saved_field === 'custom_attributes' ) {
									if ( ( $custom_attributes = explode( ',', self::get_setting( 'custom_attributes' ) ) ) && is_array( $custom_attributes ) && ! empty( $custom_attributes ) ) {
										foreach ( $custom_attributes as $custom_attribute ) {
											if ( ! empty( $custom_attribute ) ) {
												$sk_ca = self::generate_key( 4, true );

												$saved_fields6[ $sk_ca ] = [
													'type'  => 'custom_attribute',
													'name'  => $custom_attribute,
													'label' => $custom_attribute,
												];
											}
										}
									}
								} elseif ( $saved_field === 'custom_fields' ) {
									if ( ( $custom_fields = explode( ',', self::get_setting( 'custom_fields' ) ) ) && is_array( $custom_fields ) && ! empty( $custom_fields ) ) {
										foreach ( $custom_fields as $custom_field ) {
											if ( ! empty( $custom_field ) ) {
												$custom_field_arr   = explode( '|', $custom_field );
												$custom_field_name  = trim( $custom_field_arr[0] ?? '' );
												$custom_field_label = trim( $custom_field_arr[1] ?? $custom_field_name );

												if ( ! empty( $custom_field_name ) ) {
													$sk_cf = self::generate_key( 4, true );

													$saved_fields6[ $sk_cf ] = [
														'type'  => 'custom_field',
														'name'  => $custom_field_name,
														'label' => $custom_field_label,
													];
												}
											}
										}
									}
								} else {
									$saved_fields6[ $sk ] = [
										'type' => 'default',
										'name' => $saved_field
									];
								}
							}
						}
					}

					return apply_filters( 'woosc_get_fields', $saved_fields6, $context );
				}

				function get_meta_keys() {
					global $wpdb;
					$transient_key = 'woosc_get_product_meta_keys';
					$get_meta_keys = get_transient( $transient_key );

					if ( true === (bool) $get_meta_keys ) {
						return $get_meta_keys;
					}

					global $wp_post_types;

					if ( ! isset( $wp_post_types['product'] ) ) {
						return false;
					}

					$get_meta_keys = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT pm.meta_key FROM {$wpdb->postmeta} pm 
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id 
        WHERE p.post_type = %s", 'product' ) );

					set_transient( $transient_key, $get_meta_keys, DAY_IN_SECONDS );

					return $get_meta_keys;
				}

				public static function get_setting( $name, $default = false ) {
					$settings = self::get_settings();

					if ( ! empty( $settings ) ) {
						if ( isset( $settings[ $name ] ) ) {
							$setting = $settings[ $name ];
						} else {
							$setting = $default;
						}
					} else {
						$setting = get_option( 'woosc_' . $name, $default );
					}

					return apply_filters( 'woosc_get_setting', $setting, $name, $default );
				}

				public static function localization( $key = '', $default = '' ) {
					$str = '';

					if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
						$str = self::$localization[ $key ];
					} elseif ( ! empty( $default ) ) {
						$str = $default;
					}

					return apply_filters( 'woosc_localization_' . $key, $str );
				}

				public static function generate_key( $length = 6, $lower = false ) {
					$key         = '';
					$key_str     = apply_filters( 'woosc_key_characters', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' );
					$key_str_len = strlen( $key_str );

					for ( $i = 0; $i < apply_filters( 'woosc_key_length', $length ); $i ++ ) {
						$key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
					}

					if ( is_numeric( $key ) ) {
						$key = self::generate_key();
					}

					if ( $lower ) {
						$key = strtolower( $key );
					}

					return apply_filters( 'woosc_generate_key', $key );
				}
			}

			return WPCleverWoosc::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'woosc_notice_wc' ) ) {
	function woosc_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Smart Compare</strong> require WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
