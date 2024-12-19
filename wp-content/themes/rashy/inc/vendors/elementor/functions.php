<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'Rashy_Elementor_Extensions' ) ) {
    final class Rashy_Elementor_Extensions {

        private static $_instance = null;

        
        public function __construct() {
            add_action( 'elementor/elements/categories_registered', array( $this, 'add_widget_categories' ) );
            add_action( 'init', array( $this, 'elementor_widgets' ),  100 );
            add_filter( 'rashy_generate_post_builder', array( $this, 'render_post_builder' ), 10, 2 );

            add_action( 'elementor/controls/controls_registered', array( $this, 'modify_controls' ), 10, 1 );
            add_action('elementor/editor/before_enqueue_styles', array( $this, 'style' ) );
        }

        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        public function add_widget_categories( $elements_manager ) {
            $elements_manager->add_category(
                'rashy-elements',
                [
                    'title' => esc_html__( 'Rashy Elements', 'rashy' ),
                    'icon' => 'fa fa-shopping-bag',
                ]
            );

            $elements_manager->add_category(
                'rashy-header-elements',
                [
                    'title' => esc_html__( 'Rashy Header Elements', 'rashy' ),
                    'icon' => 'fa fa-shopping-bag',
                ]
            );

        }

        public function elementor_widgets() {
            // general elements
            get_template_part( 'inc/vendors/elementor/widgets/heading' );
            get_template_part( 'inc/vendors/elementor/widgets/posts' );
            get_template_part( 'inc/vendors/elementor/widgets/call_to_action' );
            get_template_part( 'inc/vendors/elementor/widgets/features_box' );
            get_template_part( 'inc/vendors/elementor/widgets/social_links' );
            get_template_part( 'inc/vendors/elementor/widgets/testimonials' );
            get_template_part( 'inc/vendors/elementor/widgets/testimonials_special' );
            get_template_part( 'inc/vendors/elementor/widgets/brands' );
            get_template_part( 'inc/vendors/elementor/widgets/process' );
            get_template_part( 'inc/vendors/elementor/widgets/popup_video' );
            get_template_part( 'inc/vendors/elementor/widgets/banner' );
            get_template_part( 'inc/vendors/elementor/widgets/banner_shop' );
            get_template_part( 'inc/vendors/elementor/widgets/countdown' );
            get_template_part( 'inc/vendors/elementor/widgets/nav_menu' );
            get_template_part( 'inc/vendors/elementor/widgets/tabs' );
            get_template_part( 'inc/vendors/elementor/widgets/team' );
            get_template_part( 'inc/vendors/elementor/widgets/special_banners' );
            get_template_part( 'inc/vendors/elementor/widgets/list_icon' );

            // header elements
            get_template_part( 'inc/vendors/elementor/header_widgets/logo' );
            get_template_part( 'inc/vendors/elementor/header_widgets/primary_menu' );
            get_template_part( 'inc/vendors/elementor/header_widgets/primary_menu_left' );
            get_template_part( 'inc/vendors/elementor/header_widgets/primary_menu_right' );
            get_template_part( 'inc/vendors/elementor/header_widgets/vertical_menu' );
            get_template_part( 'inc/vendors/elementor/header_widgets/search_form' );
            get_template_part( 'inc/vendors/elementor/header_widgets/user_info' );

            if ( rashy_is_mailchimp_activated() ) {
                get_template_part( 'inc/vendors/elementor/widgets/mailchimp' );
            }

            if ( rashy_is_woocommerce_activated() ) {
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_product_tabs' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_product_tabs_special' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_products' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_products_specific' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_products_deal' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_product_full' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_product_category_banner' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_product_categories' );
                get_template_part( 'inc/vendors/elementor/woo_widgets/woo_lookbook' );
                get_template_part( 'inc/vendors/elementor/woo_header_widgets/woo_header_info' );
            }

            if ( rashy_is_revslider_activated() ) {
                get_template_part( 'inc/vendors/elementor/widgets/revslider' );
            }
        }

        public function style() {
            wp_enqueue_style('font-icomoon',  get_template_directory_uri() . '/css/font-icomoon.css');
            wp_enqueue_style('themify-icons',  get_template_directory_uri() . '/css/themify-icons.css');
        }

        public function modify_controls( $controls_registry ) {
            // Get existing icons
            $icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
            
            $new_icons = array_merge(array(
                'ti-volume' => 'ti-volume', 'ti-user' => 'ti-user', 'ti-unlock' => 'ti-unlock', 'ti-unlink' => 'ti-unlink', 'ti-trash' => 'ti-trash', 'ti-thought' => 'ti-thought', 'ti-target' => 'ti-target', 'ti-tag' => 'ti-tag', 'ti-tablet' => 'ti-tablet', 'ti-star' => 'ti-star', 'ti-spray' => 'ti-spray', 'ti-signal' => 'ti-signal', 'ti-shopping-cart' => 'ti-shopping-cart', 'ti-shopping-cart-full' => 'ti-shopping-cart-full', 'ti-settings' => 'ti-settings', 'ti-search' => 'ti-search', 'ti-zoom-in' => 'ti-zoom-in', 'ti-zoom-out' => 'ti-zoom-out', 'ti-cut' => 'ti-cut', 'ti-ruler' => 'ti-ruler', 'ti-ruler-pencil' => 'ti-ruler-pencil', 'ti-ruler-alt' => 'ti-ruler-alt', 'ti-bookmark' => 'ti-bookmark', 'ti-bookmark-alt' => 'ti-bookmark-alt', 'ti-reload' => 'ti-reload', 'ti-plus' => 'ti-plus', 'ti-pin' => 'ti-pin', 'ti-pencil' => 'ti-pencil', 'ti-pencil-alt' => 'ti-pencil-alt', 'ti-paint-roller' => 'ti-paint-roller', 'ti-paint-bucket' => 'ti-paint-bucket', 'ti-na' => 'ti-na', 'ti-mobile' => 'ti-mobile', 'ti-minus' => 'ti-minus', 'ti-medall' => 'ti-medall', 'ti-medall-alt' => 'ti-medall-alt', 'ti-marker' => 'ti-marker', 'ti-marker-alt' => 'ti-marker-alt', 'ti-arrow-up' => 'ti-arrow-up', 'ti-arrow-right' => 'ti-arrow-right', 'ti-arrow-left' => 'ti-arrow-left', 'ti-arrow-down' => 'ti-arrow-down', 'ti-lock' => 'ti-lock', 'ti-location-arrow' => 'ti-location-arrow', 'ti-link' => 'ti-link', 'ti-layout' => 'ti-layout', 'ti-layers' => 'ti-layers', 'ti-layers-alt' => 'ti-layers-alt', 'ti-key' => 'ti-key', 'ti-import' => 'ti-import', 'ti-image' => 'ti-image', 'ti-heart' => 'ti-heart', 'ti-heart-broken' => 'ti-heart-broken', 'ti-hand-stop' => 'ti-hand-stop', 'ti-hand-open' => 'ti-hand-open', 'ti-hand-drag' => 'ti-hand-drag', 'ti-folder' => 'ti-folder', 'ti-flag' => 'ti-flag', 'ti-flag-alt' => 'ti-flag-alt', 'ti-flag-alt-2' => 'ti-flag-alt-2', 'ti-eye' => 'ti-eye', 'ti-export' => 'ti-export', 'ti-exchange-vertical' => 'ti-exchange-vertical', 'ti-desktop' => 'ti-desktop', 'ti-cup' => 'ti-cup', 'ti-crown' => 'ti-crown', 'ti-comments' => 'ti-comments', 'ti-comment' => 'ti-comment', 'ti-comment-alt' => 'ti-comment-alt', 'ti-close' => 'ti-close', 'ti-clip' => 'ti-clip', 'ti-angle-up' => 'ti-angle-up', 'ti-angle-right' => 'ti-angle-right', 'ti-angle-left' => 'ti-angle-left', 'ti-angle-down' => 'ti-angle-down', 'ti-check' => 'ti-check', 'ti-check-box' => 'ti-check-box', 'ti-camera' => 'ti-camera', 'ti-announcement' => 'ti-announcement', 'ti-brush' => 'ti-brush', 'ti-briefcase' => 'ti-briefcase', 'ti-bolt' => 'ti-bolt', 'ti-bolt-alt' => 'ti-bolt-alt', 'ti-blackboard' => 'ti-blackboard', 'ti-bag' => 'ti-bag', 'ti-move' => 'ti-move', 'ti-arrows-vertical' => 'ti-arrows-vertical', 'ti-arrows-horizontal' => 'ti-arrows-horizontal', 'ti-fullscreen' => 'ti-fullscreen', 'ti-arrow-top-right' => 'ti-arrow-top-right', 'ti-arrow-top-left' => 'ti-arrow-top-left', 'ti-arrow-circle-up' => 'ti-arrow-circle-up', 'ti-arrow-circle-right' => 'ti-arrow-circle-right', 'ti-arrow-circle-left' => 'ti-arrow-circle-left', 'ti-arrow-circle-down' => 'ti-arrow-circle-down', 'ti-angle-double-up' => 'ti-angle-double-up', 'ti-angle-double-right' => 'ti-angle-double-right', 'ti-angle-double-left' => 'ti-angle-double-left', 'ti-angle-double-down' => 'ti-angle-double-down', 'ti-zip' => 'ti-zip', 'ti-world' => 'ti-world', 'ti-wheelchair' => 'ti-wheelchair', 'ti-view-list' => 'ti-view-list', 'ti-view-list-alt' => 'ti-view-list-alt', 'ti-view-grid' => 'ti-view-grid', 'ti-uppercase' => 'ti-uppercase', 'ti-upload' => 'ti-upload', 'ti-underline' => 'ti-underline', 'ti-truck' => 'ti-truck', 'ti-timer' => 'ti-timer', 'ti-ticket' => 'ti-ticket', 'ti-thumb-up' => 'ti-thumb-up', 'ti-thumb-down' => 'ti-thumb-down', 'ti-text' => 'ti-text', 'ti-stats-up' => 'ti-stats-up', 'ti-stats-down' => 'ti-stats-down', 'ti-split-v' => 'ti-split-v', 'ti-split-h' => 'ti-split-h', 'ti-smallcap' => 'ti-smallcap', 'ti-shine' => 'ti-shine', 'ti-shift-right' => 'ti-shift-right', 'ti-shift-left' => 'ti-shift-left', 'ti-shield' => 'ti-shield', 'ti-notepad' => 'ti-notepad', 'ti-server' => 'ti-server', 'ti-quote-right' => 'ti-quote-right', 'ti-quote-left' => 'ti-quote-left', 'ti-pulse' => 'ti-pulse', 'ti-printer' => 'ti-printer', 'ti-power-off' => 'ti-power-off', 'ti-plug' => 'ti-plug', 'ti-pie-chart' => 'ti-pie-chart', 'ti-paragraph' => 'ti-paragraph', 'ti-panel' => 'ti-panel', 'ti-package' => 'ti-package', 'ti-music' => 'ti-music', 'ti-music-alt' => 'ti-music-alt', 'ti-mouse' => 'ti-mouse', 'ti-mouse-alt' => 'ti-mouse-alt', 'ti-money' => 'ti-money', 'ti-microphone' => 'ti-microphone', 'ti-menu' => 'ti-menu', 'ti-menu-alt' => 'ti-menu-alt', 'ti-map' => 'ti-map', 'ti-map-alt' => 'ti-map-alt', 'ti-loop' => 'ti-loop', 'ti-location-pin' => 'ti-location-pin', 'ti-list' => 'ti-list', 'ti-light-bulb' => 'ti-light-bulb', 'ti-talic' => 'ti-talic', 'ti-info' => 'ti-info', 'ti-infinite' => 'ti-infinite', 'ti-id-badge' => 'ti-id-badge', 'ti-hummer' => 'ti-hummer', 'ti-home' => 'ti-home', 'ti-help' => 'ti-help', 'ti-headphone' => 'ti-headphone', 'ti-harddrives' => 'ti-harddrives', 'ti-harddrive' => 'ti-harddrive', 'ti-gift' => 'ti-gift', 'ti-game' => 'ti-game', 'ti-filter' => 'ti-filter', 'ti-files' => 'ti-files', 'ti-file' => 'ti-file', 'ti-eraser' => 'ti-eraser', 'ti-envelope' => 'ti-envelope', 'ti-download' => 'ti-download', 'ti-direction' => 'ti-direction', 'ti-direction-alt' => 'ti-direction-alt', 'ti-dashboard' => 'ti-dashboard', 'ti-control-stop' => 'ti-control-stop', 'ti-control-shuffle' => 'ti-control-shuffle', 'ti-control-play' => 'ti-control-play', 'ti-control-pause' => 'ti-control-pause', 'ti-control-forward' => 'ti-control-forward', 'ti-control-backward' => 'ti-control-backward', 'ti-cloud' => 'ti-cloud', 'ti-cloud-up' => 'ti-cloud-up', 'ti-cloud-down' => 'ti-cloud-down', 'ti-clipboard' => 'ti-clipboard', 'ti-car' => 'ti-car', 'ti-calendar' => 'ti-calendar', 'ti-book' => 'ti-book', 'ti-bell' => 'ti-bell', 'ti-basketball' => 'ti-basketball', 'ti-bar-chart' => 'ti-bar-chart', 'ti-bar-chart-alt' => 'ti-bar-chart-alt', 'ti-back-right' => 'ti-back-right', 'ti-back-left' => 'ti-back-left', 'ti-arrows-corner' => 'ti-arrows-corner', 'ti-archive' => 'ti-archive', 'ti-anchor' => 'ti-anchor', 'ti-align-right' => 'ti-align-right', 'ti-align-left' => 'ti-align-left', 'ti-align-justify' => 'ti-align-justify', 'ti-align-center' => 'ti-align-center', 'ti-alert' => 'ti-alert', 'ti-alarm-clock' => 'ti-alarm-clock', 'ti-agenda' => 'ti-agenda', 'ti-write' => 'ti-write', 'ti-window' => 'ti-window', 'ti-widgetized' => 'ti-widgetized', 'ti-widget' => 'ti-widget', 'ti-widget-alt' => 'ti-widget-alt', 'ti-wallet' => 'ti-wallet', 'ti-video-clapper' => 'ti-video-clapper', 'ti-video-camera' => 'ti-video-camera', 'ti-vector' => 'ti-vector', 'ti-themify-logo' => 'ti-themify-logo', 'ti-themify-favicon' => 'ti-themify-favicon', 'ti-themify-favicon-alt' => 'ti-themify-favicon-alt', 'ti-support' => 'ti-support', 'ti-stamp' => 'ti-stamp', 'ti-split-v-alt' => 'ti-split-v-alt', 'ti-slice' => 'ti-slice', 'ti-shortcode' => 'ti-shortcode', 'ti-shift-right-alt' => 'ti-shift-right-alt', 'ti-shift-left-alt' => 'ti-shift-left-alt', 'ti-ruler-alt-2' => 'ti-ruler-alt-2', 'ti-receipt' => 'ti-receipt', 'ti-pin2' => 'ti-pin2', 'ti-pin-alt' => 'ti-pin-alt', 'ti-pencil-alt2' => 'ti-pencil-alt2', 'ti-palette' => 'ti-palette', 'ti-more' => 'ti-more', 'ti-more-alt' => 'ti-more-alt', 'ti-microphone-alt' => 'ti-microphone-alt', 'ti-magnet' => 'ti-magnet', 'ti-line-double' => 'ti-line-double', 'ti-line-dotted' => 'ti-line-dotted', 'ti-line-dashed' => 'ti-line-dashed', 'ti-layout-width-full' => 'ti-layout-width-full', 'ti-layout-width-default' => 'ti-layout-width-default', 'ti-layout-width-default-alt' => 'ti-layout-width-default-alt', 'ti-layout-tab' => 'ti-layout-tab', 'ti-layout-tab-window' => 'ti-layout-tab-window', 'ti-layout-tab-v' => 'ti-layout-tab-v', 'ti-layout-tab-min' => 'ti-layout-tab-min', 'ti-layout-slider' => 'ti-layout-slider', 'ti-layout-slider-alt' => 'ti-layout-slider-alt', 'ti-layout-sidebar-right' => 'ti-layout-sidebar-right', 'ti-layout-sidebar-none' => 'ti-layout-sidebar-none', 'ti-layout-sidebar-left' => 'ti-layout-sidebar-left', 'ti-layout-placeholder' => 'ti-layout-placeholder', 'ti-layout-menu' => 'ti-layout-menu', 'ti-layout-menu-v' => 'ti-layout-menu-v', 'ti-layout-menu-separated' => 'ti-layout-menu-separated', 'ti-layout-menu-full' => 'ti-layout-menu-full', 'ti-layout-media-right-alt' => 'ti-layout-media-right-alt', 'ti-layout-media-right' => 'ti-layout-media-right', 'ti-layout-media-overlay' => 'ti-layout-media-overlay', 'ti-layout-media-overlay-alt' => 'ti-layout-media-overlay-alt', 'ti-layout-media-overlay-alt-2' => 'ti-layout-media-overlay-alt-2', 'ti-layout-media-left-alt' => 'ti-layout-media-left-alt', 'ti-layout-media-left' => 'ti-layout-media-left', 'ti-layout-media-center-alt' => 'ti-layout-media-center-alt', 'ti-layout-media-center' => 'ti-layout-media-center', 'ti-layout-list-thumb' => 'ti-layout-list-thumb', 'ti-layout-list-thumb-alt' => 'ti-layout-list-thumb-alt', 'ti-layout-list-post' => 'ti-layout-list-post', 'ti-layout-list-large-image' => 'ti-layout-list-large-image', 'ti-layout-line-solid' => 'ti-layout-line-solid', 'ti-layout-grid4' => 'ti-layout-grid4', 'ti-layout-grid3' => 'ti-layout-grid3', 'ti-layout-grid2' => 'ti-layout-grid2', 'ti-layout-grid2-thumb' => 'ti-layout-grid2-thumb', 'ti-layout-cta-right' => 'ti-layout-cta-right', 'ti-layout-cta-left' => 'ti-layout-cta-left', 'ti-layout-cta-center' => 'ti-layout-cta-center', 'ti-layout-cta-btn-right' => 'ti-layout-cta-btn-right', 'ti-layout-cta-btn-left' => 'ti-layout-cta-btn-left', 'ti-layout-column4' => 'ti-layout-column4', 'ti-layout-column3' => 'ti-layout-column3', 'ti-layout-column2' => 'ti-layout-column2', 'ti-layout-accordion-separated' => 'ti-layout-accordion-separated', 'ti-layout-accordion-merged' => 'ti-layout-accordion-merged', 'ti-layout-accordion-list' => 'ti-layout-accordion-list', 'ti-ink-pen' => 'ti-ink-pen', 'ti-info-alt' => 'ti-info-alt', 'ti-help-alt' => 'ti-help-alt', 'ti-headphone-alt' => 'ti-headphone-alt', 'ti-hand-point-up' => 'ti-hand-point-up', 'ti-hand-point-right' => 'ti-hand-point-right', 'ti-hand-point-left' => 'ti-hand-point-left', 'ti-hand-point-down' => 'ti-hand-point-down', 'ti-gallery' => 'ti-gallery', 'ti-face-smile' => 'ti-face-smile', 'ti-face-sad' => 'ti-face-sad', 'ti-credit-card' => 'ti-credit-card', 'ti-control-skip-forward' => 'ti-control-skip-forward', 'ti-control-skip-backward' => 'ti-control-skip-backward', 'ti-control-record' => 'ti-control-record', 'ti-control-eject' => 'ti-control-eject', 'ti-comments-smiley' => 'ti-comments-smiley', 'ti-brush-alt' => 'ti-brush-alt', 'ti-youtube' => 'ti-youtube', 'ti-vimeo' => 'ti-vimeo', 'ti-twitter' => 'ti-twitter', 'ti-time' => 'ti-time', 'ti-tumblr' => 'ti-tumblr', 'ti-skype' => 'ti-skype', 'ti-share' => 'ti-share', 'ti-share-alt' => 'ti-share-alt', 'ti-rocket' => 'ti-rocket', 'ti-pinterest' => 'ti-pinterest', 'ti-new-window' => 'ti-new-window', 'ti-microsoft' => 'ti-microsoft', 'ti-list-ol' => 'ti-list-ol', 'ti-linkedin' => 'ti-linkedin', 'ti-layout-sidebar-2' => 'ti-layout-sidebar-2', 'ti-layout-grid4-alt' => 'ti-layout-grid4-alt', 'ti-layout-grid3-alt' => 'ti-layout-grid3-alt', 'ti-layout-grid2-alt' => 'ti-layout-grid2-alt', 'ti-layout-column4-alt' => 'ti-layout-column4-alt', 'ti-layout-column3-alt' => 'ti-layout-column3-alt', 'ti-layout-column2-alt' => 'ti-layout-column2-alt', 'ti-instagram' => 'ti-instagram', 'ti-google' => 'ti-google', 'ti-github' => 'ti-github', 'ti-flickr' => 'ti-flickr', 'ti-facebook' => 'ti-facebook', 'ti-dropbox' => 'ti-dropbox', 'ti-dribbble' => 'ti-dribbble', 'ti-apple' => 'ti-apple', 'ti-android' => 'ti-android', 'ti-save' => 'ti-save', 'ti-save-alt' => 'ti-save-alt', 'ti-yahoo' => 'ti-yahoo', 'ti-wordpress' => 'ti-wordpress', 'ti-vimeo-alt' => 'ti-vimeo-alt', 'ti-twitter-alt' => 'ti-twitter-alt', 'ti-tumblr-alt' => 'ti-tumblr-alt', 'ti-trello' => 'ti-trello', 'ti-stack-overflow' => 'ti-stack-overflow', 'ti-soundcloud' => 'ti-soundcloud', 'ti-sharethis' => 'ti-sharethis', 'ti-sharethis-alt' => 'ti-sharethis-alt', 'ti-reddit' => 'ti-reddit', 'ti-pinterest-alt' => 'ti-pinterest-alt', 'ti-microsoft-alt' => 'ti-microsoft-alt', 'ti-linux' => 'ti-linux', 'ti-jsfiddle' => 'ti-jsfiddle', 'ti-joomla' => 'ti-joomla', 'ti-html5' => 'ti-html5', 'ti-flickr-alt' => 'ti-flickr-alt', 'ti-email' => 'ti-email', 'ti-drupal' => 'ti-drupal', 'ti-dropbox-alt' => 'ti-dropbox-alt', 'ti-css3' => 'ti-css3', 'ti-rss' => 'ti-rss', 'ti-rss-alt' => 'ti-rss-alt',
                    
                    'icon-yoga' => 'icon-yoga',
                    'icon-wellness' => 'icon-wellness',
                    'icon-weight' => 'icon-weight',
                    'icon-twitter' => 'icon-twitter',
                    'icon-twitter-2' => 'icon-twitter-2',
                    'icon-user' => 'icon-user',
                    'icon-swim' => 'icon-swim',
                    'icon-shoes' => 'icon-shoes',
                    'icon-shopping-bag' => 'icon-shopping-bag',
                    'icon-shopping-cart' => 'icon-shopping-cart',
                    'icon-support' => 'icon-support',
                    'icon-right' => 'icon-right',
                    'icon-run' => 'icon-run',
                    'icon-running' => 'icon-running',
                    'icon-payment' => 'icon-payment',
                    'icon-phone' => 'icon-phone',
                    'icon-play' => 'icon-play',
                    'icon-play-2' => 'icon-play-2',
                    'icon-plus' => 'icon-plus',
                    'icon-quote' => 'icon-quote',
                    'icon-headphones' => 'icon-headphones',
                    'icon-heart' => 'icon-heart',
                    'icon-installment' => 'icon-installment',
                    'icon-message' => 'icon-message',
                    'icon-cashback' => 'icon-cashback',
                    'icon-compare' => 'icon-compare',
                    'icon-gym' => 'icon-gym',
                    'icon-basketball' => 'icon-basketball',
                    'icon-basketball-2' => 'icon-basketball-2',
                    'icon-boxing' => 'icon-boxing',
                    'icon-accessories' => 'icon-accessories',
                    'icon-apparel' => 'icon-apparel',
                    'icon-arrow-left' => 'icon-arrow-left',
                    'icon-arrow-left-2' => 'icon-arrow-left-2',
                    'icon-arrow-right' => 'icon-arrow-right',
                    'icon-arrow-right-2' => 'icon-arrow-right-2',
                    
            ), $icons);

            // Then we set a new list of icons as the options of the icon control
            $controls_registry->get_control( 'icon' )->set_settings( 'options', $new_icons );
        }

        public static function render_page_content($post_id) {
            if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new Elementor\Core\Files\CSS\Post( $post_id );
                $css_file->enqueue();
            }

            return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
        }

        public function render_post_builder($html, $post) {
            if ( !empty($post) && !empty($post->ID) ) {
                return $this->render_page_content($post->ID);
            }
            return $html;
        }
    }
}



if ( did_action( 'elementor/loaded' ) ) {
    // Finally initialize code
    Rashy_Elementor_Extensions::instance();
}