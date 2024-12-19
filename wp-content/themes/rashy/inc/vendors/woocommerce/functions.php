<?php

if ( !function_exists('rashy_get_products') ) {
    function rashy_get_products( $args = array() ) {
        global $woocommerce, $wp_query;

        $args = wp_parse_args( $args, array(
            'categories' => array(),
            'product_type' => 'recent_product',
            'paged' => 1,
            'post_per_page' => -1,
            'orderby' => '',
            'order' => '',
            'includes' => array(),
            'excludes' => array(),
            'author' => '',
        ));
        extract($args);
        
        $query_args = array(
            'post_type' => 'product',
            'posts_per_page' => $post_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'orderby'   => $orderby,
            'order' => $order
        );

        if ( isset( $query_args['orderby'] ) ) {
            if ( 'price' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_price',
                    'orderby'   => 'meta_value_num'
                ) );
            }
            if ( 'featured' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_featured',
                    'orderby'   => 'meta_value'
                ) );
            }
            if ( 'sku' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_sku',
                    'orderby'   => 'meta_value'
                ) );
            }
        }

        switch ($product_type) {
            case 'best_selling':
                $query_args['meta_key']='total_sales';
                $query_args['orderby']='meta_value_num';
                $query_args['ignore_sticky_posts']   = 1;
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'featured_product':
                $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['featured'],
                );
                break;
            case 'top_rate':
                //add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recent_product':
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;
            case 'deals':
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $query_args['meta_query'][] =  array(
                    array(
                        'key'           => '_sale_price_dates_to',
                        'value'         => time(),
                        'compare'       => '>',
                        'type'          => 'numeric'
                    )
                );
                break;     
            case 'on_sale':
                $product_ids_on_sale    = wc_get_product_ids_on_sale();
                $product_ids_on_sale[]  = 0;
                $query_args['post__in'] = $product_ids_on_sale;
                break;
            case 'recent_review':
                if($post_per_page == -1) $_limit = 4;
                else $_limit = $post_per_page;
                global $wpdb;
                $query = "SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c
                        WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0
                        ORDER BY c.comment_date ASC";
                $results = $wpdb->get_results($query, OBJECT);
                $_pids = array();
                foreach ($results as $re) {
                    if(!in_array($re->comment_post_ID, $_pids))
                        $_pids[] = $re->comment_post_ID;
                    if(count($_pids) == $_limit)
                        break;
                }

                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $query_args['post__in'] = $_pids;

                break;
            case 'rand':
                $query_args['orderby'] = 'rand';
                break;
            case 'recommended':

                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = array(
                    'key' => '_goal_recommended',
                    'value' => 'yes',
                );
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recently_viewed':
                $viewed_products = ! empty( $_COOKIE['goal_woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['goal_woocommerce_recently_viewed'] ) : array();
                $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

                if ( empty( $viewed_products ) ) {
                    return false;
                }
                $query_args['post__in'] = $viewed_products;
                break;
        }

        if ( !empty($categories) && is_array($categories) ) {
            $query_args['tax_query'][] = array(
                'taxonomy'      => 'product_cat',
                'field'         => 'slug',
                'terms'         => $categories,
                'operator'      => 'IN'
            );
        }

        if (!empty($includes) && is_array($includes)) {
            $query_args['post__in'] = $includes;
        }
        
        if ( !empty($excludes) && is_array($excludes) ) {
            $query_args['post__not_in'] = $excludes;
        }

        if ( !empty($author) ) {
            $query_args['author'] = $author;
        }
        if ( $product_type == 'top_rate' && class_exists('WC_Shortcode_Products') ) {
            add_filter( 'posts_clauses', array( 'WC_Shortcode_Products', 'order_by_rating_post_clauses' ) );
            $loop = new WP_Query($query_args);
            call_user_func( implode('_', array('remove', 'filter')), 'posts_clauses', array( 'WC_Shortcode_Products', 'order_by_rating_post_clauses' ) );
        } else {
            $loop = new WP_Query($query_args);
        }
        return $loop;
    }
}

// add product viewed
function rashy_track_product_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }

    global $post;

    if ( empty( $_COOKIE['goal_woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['goal_woocommerce_recently_viewed'] );

    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }

    if ( sizeof( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }

    // Store for session only
    wc_setcookie( 'goal_woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'rashy_track_product_view', 20 );

// Style hooks
function rashy_woocommerce_enqueue_styles() {
    wp_enqueue_style( 'rashy-wc-quantity-increment', get_template_directory_uri() .'/css/wc-quantity-increment.css' );
    wp_enqueue_style( 'rashy-woocommerce', get_template_directory_uri() .'/sass/woocommerce.css' , 'rashy-woocommerce-front' , RASHY_THEME_VERSION, 'all' );
}
add_action( 'wp_enqueue_scripts', 'rashy_woocommerce_enqueue_styles', 99 );

function rashy_woocommerce_enqueue_scripts() {
    wp_enqueue_script( 'selectWoo' );
    wp_enqueue_style( 'select2' );
    
    wp_register_script( 'sticky-kit', get_template_directory_uri() . '/js/sticky-kit.min.js', array( 'jquery' ), '20150330', true );

    wp_enqueue_script( 'rashy-quantity-increment', get_template_directory_uri() . '/js/wc-quantity-increment.js', array( 'jquery' ), '20150330', true );
    wp_register_script( 'rashy-woocommerce', get_template_directory_uri() . '/js/woocommerce.js', array( 'jquery', 'jquery-unveil', 'slick' ), '20150330', true );

    $ajax_url = add_query_arg( 'wc-ajax', '%%endpoint%%', trailingslashit( site_url() ) );

    $options = array(
        'ajaxurl' => $ajax_url,
        'enable_search' => (rashy_get_config('enable_autocompleate_search', true) ? '1' : '0'),
        'empty_msg' => apply_filters( 'rashy_autocompleate_search_empty_msg', esc_html__( 'Unable to find any products that match the currenty query', 'rashy' ) ),
        'nonce' => wp_create_nonce( 'ajax-nonce' ),
        '_preset' => rashy_get_demo_preset()
    );
    wp_localize_script( 'rashy-woocommerce', 'rashy_woo_opts', $options );
    wp_enqueue_script( 'rashy-woocommerce' );
    
    if (rashy_get_config('show_quickview', false)) {
        wp_enqueue_script( 'wc-add-to-cart-variation' );
    }
}
add_action( 'wp_enqueue_scripts', 'rashy_woocommerce_enqueue_scripts', 10 );

// cart
if ( !function_exists('rashy_woocommerce_header_add_to_cart_fragment') ) {
    function rashy_woocommerce_header_add_to_cart_fragment( $fragments ){
        global $woocommerce;
        $fragments['.cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        $fragments['.footer-mini-cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        $fragments['.cart .total-minicart'] = '<div class="total-minicart">'. $woocommerce->cart->get_cart_total(). '</div>';
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'rashy_woocommerce_header_add_to_cart_fragment' );

// breadcrumb for woocommerce page
if ( !function_exists('rashy_woocommerce_breadcrumb_defaults') ) {
    function rashy_woocommerce_breadcrumb_defaults( $args ) {
        $breadcrumb_img = rashy_get_config('woo_breadcrumb_image');
        $breadcrumb_color = rashy_get_config('woo_breadcrumb_color');
        $style = $classes = array();
        $show_breadcrumbs = rashy_get_config('show_product_breadcrumbs', true);

        if ( !$show_breadcrumbs ) {
            $style[] = 'display:none';
        }
        if( $breadcrumb_color  ){
            $style[] = 'background-color:'.$breadcrumb_color;
        }
        
        if ( !empty($breadcrumb_img['id']) ) {
            $img = wp_get_attachment_image_src($breadcrumb_img['id'], 'full');
            if ( !empty($img[0]) ) {
                $style[] = 'background-image:url(\''.esc_url($img[0]).'\')';
                $classes[] = 'has_bg';
            }
        }

        $estyle = !empty($style) ? ' style="'.implode(";", $style).'"':"";
        if ( is_single() ) {
            $classes[] = 'woo-detail';
        }

        $full_width = 'container';
        
        // check woo
        if(is_product()){
            $title = '';
        }else{
            $title = '<div class="breadscrumb-inner hidden-icon"><h2 class="bread-title">'.esc_html__( 'Shop', 'rashy' ).'</h2></div>';
        }

        $args['wrap_before'] = '<section id="goal-breadscrumb" class="goal-breadscrumb woo-breadcrumb '.esc_attr(!empty($classes) ? implode(' ', $classes) : '').'"'.$estyle.'><div class="'.$full_width.'"><div class="wrapper-breads"><div class="wrapper-breads-inner">'.$title.'
        <ol class="breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>';
        $args['wrap_after'] = '</ol></div></div></div></section>';

        return $args;
    }
}
add_filter( 'woocommerce_breadcrumb_defaults', 'rashy_woocommerce_breadcrumb_defaults' );
add_action( 'rashy_woo_template_main_before', 'woocommerce_breadcrumb', 30, 0 );

// display woocommerce modes
if ( !function_exists('rashy_woocommerce_display_modes') ) {
    function rashy_woocommerce_display_modes(){
        global $wp;
        $current_url = rashy_shop_page_link(true);

        $url_grid = add_query_arg( 'display_mode', 'grid', remove_query_arg( 'display_mode', $current_url ) );
        $url_list = add_query_arg( 'display_mode', 'list', remove_query_arg( 'display_mode', $current_url ) );

        $woo_mode = rashy_woocommerce_get_display_mode();

        echo '<div class="display-mode pull-right">';
        echo '<a href="'.  $url_grid  .'" class=" change-view '.($woo_mode == 'grid' ? 'active' : '').'"><i class="ti-layout-grid3"></i></a>';
        echo '<a href="'.  $url_list  .'" class=" change-view '.($woo_mode == 'list' ? 'active' : '').'"><i class="ti-view-list-alt"></i></a>';
        echo '</div>'; 
    }
}

if ( !function_exists('rashy_woocommerce_get_display_mode') ) {
    function rashy_woocommerce_get_display_mode() {
        $woo_mode = rashy_get_config('product_display_mode', 'grid');
        $args = array( 'grid', 'list' );
        if ( isset($_COOKIE['rashy_woo_mode']) && in_array($_COOKIE['rashy_woo_mode'], $args) ) {
            $woo_mode = $_COOKIE['rashy_woo_mode'];
        }
        return $woo_mode;
    }
}


if(!function_exists('rashy_shop_page_link')) {
    function rashy_shop_page_link($keep_query = false ) {
        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        } elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
            $link = get_post_type_archive_link( 'product' );
        } else {
            $link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
        }

        if( $keep_query ) {
            // Keep query string vars intact
            foreach ( $_GET as $key => $val ) {
                if ( 'orderby' === $key || 'submit' === $key ) {
                    continue;
                }
                $link = add_query_arg( $key, $val, $link );

            }
        }
        return $link;
    }
}


// add filter to top archive
add_action( 'woocommerce_top_pagination', 'woocommerce_pagination', 1 );

if ( !function_exists('rashy_before_woocommerce_init') ) {
    function rashy_before_woocommerce_init() {
        // set display mode to cookie
        if( isset($_GET['display_mode']) && ($_GET['display_mode']=='list' || $_GET['display_mode']=='grid') ){  
            setcookie( 'rashy_woo_mode', trim($_GET['display_mode']) , time()+3600*24*100,'/' );
            $_COOKIE['rashy_woo_mode'] = trim($_GET['display_mode']);
        }

        if ( rashy_get_config('show_quickview', false) ) {
            add_action( 'wp_ajax_rashy_quickview_product', 'rashy_woocommerce_quickview' );
            add_action( 'wp_ajax_nopriv_rashy_quickview_product', 'rashy_woocommerce_quickview' );
        }

        add_action( 'wp_ajax_rashy_ajax_get_products', 'rashy_woocommerce_get_ajax_products' );
        add_action( 'wp_ajax_nopriv_rashy_ajax_get_products', 'rashy_woocommerce_get_ajax_products' );
    }
}
add_action( 'init', 'rashy_before_woocommerce_init' );

add_action( 'wc_ajax_rashy_ajax_get_products', 'rashy_woocommerce_get_ajax_products' );
function rashy_woocommerce_get_ajax_products() {
    $settings = isset($_POST['settings']) ? $_POST['settings'] : '';

    $tab = isset($_POST['tab']) ? $_POST['tab'] : '';
    
    if ( empty($settings) || empty($tab) ) {
        exit();
    }

    $woo_product_tabs_special = !empty($settings['woo_product_tabs_special']) ? true : false;

    $slugs = !empty($tab['slugs']) ? array_map('trim', explode(',', $tab['slugs'])) : array();

    $columns = isset($settings['columns']) ? $settings['columns'] : 4;
    $columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : 4;
    $columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : 4;
    $slides_to_scroll = isset($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : 4;
    $slides_to_scroll_tablet = isset($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : 4;
    $slides_to_scroll_mobile = isset($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : 4;
    $autoplay = isset($settings['autoplay']) ? $settings['autoplay'] : false;
    $infinite_loop = isset($settings['infinite_loop']) ? $settings['infinite_loop'] : false;
    $product_item = isset($settings['product_item']) ? $settings['product_item'] : false;

    $rows = isset($settings['rows']) ? $settings['rows'] : 1;
    $show_nav = isset($settings['show_nav']) ? $settings['show_nav'] : false;
    $show_pagination = isset($settings['show_pagination']) ? $settings['show_pagination'] : false;
    $limit = isset($settings['limit']) ? $settings['limit'] : 4;
    $product_type = isset($tab['type']) ? $tab['type'] : 'recent_product';

    $layout_type = isset($settings['layout_type']) ? $settings['layout_type'] : 'grid';

    $excludes = array();
    if ( $woo_product_tabs_special ) {
        ?>
        <div class="row">
            <?php
            $col_class_second = 12;
            if ( !empty($tab['product_id']) ) {
                $post_object = get_post( $tab['product_id'] );
                if ( $post_object ) {
                    setup_postdata( $GLOBALS['post'] =& $post_object );
                    $special_product_item = !empty($tab['product_item']) ? $tab['product_item'] : 'inner';
                    $col_class_second = 7;
                    $excludes[] = $tab['product_id'];
                    ?>
                        <div class="col-md-5 col-xs-12 primary">
                            <div <?php wc_product_class( '', $post_object ); ?>>
                                <?php wc_get_template( 'item-product/'.$special_product_item.'.php', array(
                                    'end_date' => !empty($tab['end_date']) ? $tab['end_date'] : '',
                                    'available' => !empty($tab['available']) ? $tab['available'] : '',
                                    'already_sold' => !empty($tab['already_sold']) ? $tab['already_sold'] : '',
                                    'image_size' => 'rashy-shop-special',
                                )  ); ?>
                            </div>
                        </div>
                    <?php
                    wp_reset_postdata();
                }
            ?>
                
            <?php } ?>
            <div class="col-md-<?php echo esc_attr($col_class_second); ?> col-xs-12">
            <?php
    }

    $args = array(
        'categories' => $slugs,
        'product_type' => $product_type,
        'paged' => 1,
        'post_per_page' => $limit,
        'excludes' => $excludes,
    );

    $loop = rashy_get_products( $args );
    if ( $loop->have_posts() ) {
        $max_pages = $loop->max_num_pages;
        wc_get_template( 'layout-products/'.$layout_type.'.php' , array(
            'loop' => $loop,
            'columns' => $columns,
            'columns_tablet' => $columns_tablet,
            'columns_mobile' => $columns_mobile,
            'slides_to_scroll' => $slides_to_scroll,
            'slides_to_scroll_tablet' => $slides_to_scroll_tablet,
            'slides_to_scroll_mobile' => $slides_to_scroll_mobile,
            'show_nav' => $show_nav,
            'show_pagination' => $show_pagination,
            'autoplay' => $autoplay,
            'infinite_loop' => $infinite_loop,
            'rows' => $rows,
            'product_item' => $product_item,
            'slick_top' => 'slick-carousel-top',
        ) );
    }

    if ( $woo_product_tabs_special ) {
        ?>
            </div>
        </div>
        <?php
    }
    exit();
}

// quickview
add_action( 'wc_ajax_rashy_quickview_product', 'rashy_woocommerce_quickview' );
if ( !function_exists('rashy_woocommerce_quickview') ) {
    function rashy_woocommerce_quickview() {
        if ( !empty($_GET['product_id']) ) {
            $post_object = get_post( $_GET['product_id'] );
            if ( $post_object ) {
                setup_postdata( $GLOBALS['post'] =& $post_object );

                wc_get_template_part( 'content', 'product-quickview' );
            }
            wp_reset_postdata();
        }
        die;
    }
}

// Number of products per page
if ( !function_exists('rashy_woocommerce_shop_per_page') ) {
    function rashy_woocommerce_shop_per_page($number) {
        
        if ( isset( $_REQUEST['wppp_ppp'] ) ) :
            $number = intval( $_REQUEST['wppp_ppp'] );
            WC()->session->set( 'products_per_page', intval( $_REQUEST['wppp_ppp'] ) );
        elseif ( isset( $_REQUEST['ppp'] ) ) :
            $number = intval( $_REQUEST['ppp'] );
            WC()->session->set( 'products_per_page', intval( $_REQUEST['ppp'] ) );
        // elseif ( WC()->session->__isset( 'products_per_page' ) ) :
        //     $number = intval( WC()->session->__get( 'products_per_page' ) );
        else :
            $value = rashy_get_config('number_products_per_page', 12);
            $number = intval( $value );
        endif;
        
        return $number;

    }
}
add_filter( 'loop_shop_per_page', 'rashy_woocommerce_shop_per_page', 30 );

// Number of products per row
if ( !function_exists('rashy_woocommerce_shop_columns') ) {
    function rashy_woocommerce_shop_columns($number) {
        $value = rashy_get_config('product_columns');
        if ( in_array( $value, array(1, 2, 3, 4, 5, 6, 7, 8) ) ) {
            $number = $value;
        }
        return $number;
    }
}
add_filter( 'loop_shop_columns', 'rashy_woocommerce_shop_columns' );

// share box
if ( !function_exists('rashy_woocommerce_share_box') ) {
    function rashy_woocommerce_share_box() {
        if ( rashy_get_config('show_product_social_share', false) ) {
            get_template_part( 'template-parts/sharebox' );
        }
    }
}

function rashy_woocomerce_wishlist_share_wrapper_open( $value='' ){
    echo '<div class="clearfix wishlist-share-wrapper">';
}

function rashy_woocomerce_wishlist_share_wrapper_close() {
    echo '</div>';
}

function rashy_woocomerce_add_to_cart_wrapper_open( $value='' ){
    echo '<div class="information-inner">';
}

function rashy_woocomerce_add_to_cart_wrapper_close() {
    echo '</div>';
}

// add div top infor for detail
function rashy_product_price_rating_open() {
    ?>
    <div class="price-rating-wrapper clearfix">
    <?php
}
function rashy_product_price_rating_close() {
    ?>
    </div>
    <?php
}
function rashy_woo_clearfix_addcart() {
    ?>
    <div class="clearfix"></div>
    <?php
}


//Remove product in the cart using ajax
function warp_ajax_product_remove()
{
    // Get mini cart
    ob_start();
 
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
    {
        if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
        {
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }
 
    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();
 
    woocommerce_mini_cart();
 
    $mini_cart = ob_get_clean();
 
    // Fragments and mini cart are returned
    $data = array(
        'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
            )
        ),
        'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
    );
 
    wp_send_json( $data );
 
    die();
}
 
add_action( 'wp_ajax_product_remove', 'warp_ajax_product_remove' );
add_action( 'wp_ajax_nopriv_product_remove', 'warp_ajax_product_remove' );

// add_filter( 'woocommerce_short_description', 'prefix_filter_woocommerce_short_description' );
// /**
//  * Limit WooCommerce Short Description Field
//  */
// function prefix_filter_woocommerce_short_description( $post_post_excerpt ) { 
//     // make filter magic happen here... 
//     if(! is_product() ) { // add in conditionals
//         $text = $post_post_excerpt; 
//         $words = 5; // change word length
//         $more = ' […]'; // add a more cta
        
//         $post_post_excerpt = wp_trim_words( $text, $words, $more );
//     }
//     return $post_post_excerpt; 
// }

add_filter( 'woocommerce_single_product_summary', 'rashy_woo_clearfix_addcart', 36 );
// shipping infomation
if ( !function_exists('rashy_woocommerce_delivery_info') ) {
    function rashy_woocommerce_delivery_info() {
        echo '<div class="delivery-shipping-info">';
        $delivery_title = rashy_get_config('product_delivery_title');
        $delivery_info = rashy_get_config('product_delivery_info');
        if ( !empty($delivery_title) || !empty($delivery_info) ) {
            echo '<div class="item">';
                echo '<a class="item-btn" href="#delivery_info_wrapper"><span class="title">'.trim($delivery_title).'</span></a>';
                echo '<div id="delivery_info_wrapper" class="mfp-hide info_wrapper_alert" data-effect="fadeIn">';
                echo '<span class="title">'.trim($delivery_title).'</span>';
                echo '<div class="inner">'.trim($delivery_info).'</div>';
                echo '</div>';
            echo '</div>';
        }

        $shipping_title = rashy_get_config('product_shipping_title');
        $shipping_info = rashy_get_config('product_shipping_info');
        if ( !empty($shipping_title) || !empty($shipping_info) ) {
            echo '<div class="item">';
                echo '<a class="item-btn" href="#shipping_info_wrapper"><span class="title">'.trim($shipping_title).'</a>';
                echo '<div id="shipping_info_wrapper" class="mfp-hide info_wrapper_alert" data-effect="fadeIn">';
                echo '<span class="title">'.trim($shipping_title).'</span>';
                echo '<div class="inner">'.trim($shipping_info).'</div>';
                echo '</div>';
            echo '</div>';
        }

        $composition_title = rashy_get_config('product_composition_title');
        $composition_info = rashy_get_config('product_composition_info');
        if ( !empty($composition_title) || !empty($composition_info) ) {
            echo '<div class="item">';
                echo '<a class="item-btn" href="#composition_info_wrapper"><span class="title">'.trim($composition_title).'</a>';
                echo '<div id="composition_info_wrapper" class="mfp-hide info_wrapper_alert" data-effect="fadeIn">';
                echo '<span class="title">'.trim($composition_title).'</span>';
                echo '<div class="inner">'.trim($composition_info).'</div>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}

function rashy_woo_display_product_cat($product_id) {
    $terms = get_the_terms( $product_id, 'product_cat' );
    if ( !empty($terms) ) { ?>
        <div class="product-cat">
        <?php foreach ( $terms as $term ) {
            echo '<a href="' . get_term_link( $term ) . '">' . $term->name . '</a>';
            break;
        } ?>
        </div>
    <?php
    }
}
function rashy_product_get_layout_type() {
    global $post;
    $layout = get_post_meta($post->ID, 'goal_product_layout_type', true);
                    
    if ( empty($layout) ) {
        $layout = rashy_get_config('product_single_version', 'v1');
    }
    return $layout;
}

// Wishlist
add_filter( 'yith_wcwl_add_to_wishlist_icon_html', 'rashy_woocomerce_icon_wishlist'  );

function rashy_woocomerce_icon_wishlist( $value='' ){
    return '<span class="heart-icon"><i class="ti-heart"></i></span>';
}


function rashy_yith_wcwl_positions($positions) {
    $layout = rashy_get_config('product_single_version', 'v2');
    if ( isset($positions['add-to-cart']['hook']) ) {
        if ( $layout == 'v1' ) {
            $positions['add-to-cart']['hook'] = 'rashy_woocommerce_single_product_summary';
        }
        $positions['add-to-cart']['priority'] = 6;
    }
    return $positions;
}
add_filter( 'yith_wcwl_positions', 'rashy_yith_wcwl_positions', 100 );

// countdown
function rashy_woocommerce_single_countdown() {
    if ( rashy_get_config('show_product_countdown_timer') ) {
        get_template_part( 'woocommerce/single-product/countdown' );
    }
}
add_action('woocommerce_single_product_summary', 'rashy_woocommerce_single_countdown', 15);

function rashy_woocommerce_single_title_wrapper_open() {
    echo '<div class="clearfix title-cat-wishlist-wrapper">';
}
function rashy_woocommerce_single_title_wrapper_close() {
    echo '</div>';
}

function rashy_product_breadcrumbs_navigation() {
    ?>
    <div class="breadcrumb-navigation-wrapper">
        <!-- <?php woocommerce_breadcrumb(); ?> -->

        <?php woocommerce_template_single_title(); ?>
        <div class="product-navs">
            <?php
                the_post_navigation( array(
                    'next_text' => '<i class="icon-arrow-right"></i>',
                    'prev_text' => '<i class="icon-arrow-left"></i>',
                ) );
            ?>
        </div>
    </div>
    <?php
}


// swap effect
if ( !function_exists('rashy_swap_images') ) {
    function rashy_swap_images() {
        $thumb = apply_filters('rashy_swap_images_thumb', 'woocommerce_thumbnail');
        $swap_image = (bool)rashy_get_config('enable_swap_image', true);
        ?>
        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
            <?php rashy_product_get_image($thumb, $swap_image); ?>
        </a>
        <?php
    }
}
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);


if ( !function_exists('rashy_product_image') ) {
    function rashy_product_image($thumb = 'woocommerce_thumbnail') {
        $swap_image = (bool)rashy_get_config('enable_swap_image', true);
        ?>
        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
            <?php rashy_product_get_image($thumb, $swap_image); ?>
        </a>
        <?php
    }
}
// get image
if ( !function_exists('rashy_product_get_image') ) {
    function rashy_product_get_image($thumb = 'woocommerce_thumbnail', $swap = true) {
        global $post, $product, $woocommerce;
        
        $output = '';
        $class = "attachment-$thumb size-$thumb image-no-effect";
        if (has_post_thumbnail()) {
            if ( $swap ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = "attachment-$thumb size-$thumb image-hover";
                    $swap_class = "attachment-$thumb size-$thumb image-effect";
                    $output .= rashy_get_attachment_thumbnail( $attachment_ids[0], $thumb , false, array('class' => $swap_class), false);
                }
            }
            $output .= rashy_get_attachment_thumbnail( get_post_thumbnail_id(), $thumb , false, array('class' => $class), false);
        } else {
            $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_attr__('Placeholder' , 'rashy').'" class="'.$class.'"/>';
        }
        echo trim($output);
    }
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


function rashy_wc_get_gallery_image_html_simple( $attachment_id, $main_image = false ) {
    $flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
    $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
    $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
    $image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
    $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
    $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
    $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
    
    
    $img = rashy_get_attachment_thumbnail($attachment_id, $image_size);
    return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '">' . $img . '</a></div>';
}

// layout class for woo page
if ( !function_exists('rashy_woocommerce_content_class') ) {
    function rashy_woocommerce_content_class( $class ) {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        if( rashy_get_config('product_'.$page.'_fullwidth') ) {
            return 'container-fluid max-1800';
        }
        return $class;
    }
}
add_filter( 'rashy_woocommerce_content_class', 'rashy_woocommerce_content_class' );

// get layout configs
if ( !function_exists('rashy_get_woocommerce_layout_configs') ) {
    function rashy_get_woocommerce_layout_configs() {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        $left = rashy_get_config('product_'.$page.'_left_sidebar');
        $right = rashy_get_config('product_'.$page.'_right_sidebar');
        // check full width
        if( rashy_get_config('product_'.$page.'_fullwidth') ) {
            $sidebar = 'col-lg-2';
            $main_full = 'col-lg-10';
        } else {
            $sidebar = 'col-lg-3';
            $main_full = 'col-lg-9';
        }
        switch ( rashy_get_config('product_'.$page.'_layout', 'main') ) {
            case 'left-main':
                if ( is_active_sidebar( $left ) ) {
                    $configs['left'] = array( 'sidebar' => $left, 'class' => $sidebar.' col-md-3 col-sm-12 col-xs-12 shop-sidebar-left-wrapper'  );
                    $configs['main'] = array( 'class' => $main_full.' col-md-9 col-sm-12 col-xs-12 shop-has-left' );
                }
                break;
            case 'main-right':
                if ( is_active_sidebar( $right ) ) {
                    $configs['right'] = array( 'sidebar' => $right,  'class' => $sidebar.' col-md-3 col-sm-12 col-xs-12 shop-sidebar-right-wrapper' ); 
                    $configs['main'] = array( 'class' => $main_full.' col-md-9 col-sm-12 col-xs-12 shop-has-right' );
                }
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
            default:
                if (is_active_sidebar( 'sidebar-default' ) && !is_shop() && !is_single() ) {
                    $configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-md-3 col-sm-12 col-xs-12' ); 
                    $configs['main'] = array( 'class' => 'col-md-9 col-sm-12 col-xs-12' );
                } else {
                    $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                }
                break;
        }

        if ( empty($configs) ) {
            if (is_active_sidebar( 'sidebar-default' ) && !is_shop() && !is_single() ) {
                $configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-md-3 col-sm-12 col-xs-12' ); 
                $configs['main'] = array( 'class' => 'col-md-9 col-sm-12 col-xs-12' );
            } else {
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
            }
        }

        return $configs; 
    }
}

if ( !function_exists( 'rashy_product_review_tab' ) ) {
    function rashy_product_review_tab($tabs) {
        global $post;
        if ( !rashy_get_config('show_product_review_tab', true) && isset($tabs['reviews']) ) {
            unset( $tabs['reviews'] ); 
        }

        if ( !rashy_get_config('hidden_product_additional_information_tab', false) && isset($tabs['additional_information']) ) {
            unset( $tabs['additional_information'] ); 
        }

        if ( get_post_meta( $post->ID, 'goal_product_features', true ) ) {
            $tabs['specifications'] = array(
                'title' => esc_html__('Features', 'rashy'),
                'priority' => 15,
                'callback' => 'rashy_display_features'
            );
        }
        
        return $tabs;
    }
}
add_filter( 'woocommerce_product_tabs', 'rashy_product_review_tab', 90 );

function rashy_display_features() {
    get_template_part( 'woocommerce/single-product/tabs/features' );
}

// Loop
/*
 * Start for only rashy theme
 */
function rashy_is_ajax_request() {
    if ( isset( $_REQUEST['load_type'] ) ) {
        return true;
    }
    return false;
}

function rashy_wc_product_dropdown_categories( $args = array() ) {
    global $wp_query;

    $current_product_cat = isset( $wp_query->query_vars['product_cat'] ) ? $wp_query->query_vars['product_cat'] : '';
    $defaults            = array(
        'pad_counts'         => 1,
        'show_count'         => 1,
        'hierarchical'       => 1,
        'hide_empty'         => 1,
        'show_uncategorized' => 1,
        'orderby'            => 'name',
        'selected'           => $current_product_cat,
        'menu_order'         => false,
        'option_select_text' => esc_html__( 'All', 'rashy' ),
    );

    $args = wp_parse_args( $args, $defaults );

    if ( 'order' === $args['orderby'] ) {
        $args['menu_order'] = 'asc';
        $args['orderby']    = 'name';
    }

    $terms = get_terms( 'product_cat', apply_filters( 'wc_product_dropdown_categories_get_terms_args', $args ) );

    if ( empty( $terms ) ) {
        return;
    }
    $shop_page_id = wc_get_page_id( 'shop' );
    $shopurl = esc_url ( get_permalink( $shop_page_id ) );

    $count_products = wp_count_posts('product');
    
    $count = 0;
    if ( !empty($count_products) ) {
        $count = (int)$count_products->publish;
    }

    $output  = "<ul>";
    $output .= '<li ' . ( $current_product_cat == '' ? 'class="active"' : '' ) . '>'
            . '<a href="'.esc_url($shopurl).'">'
            . esc_html( $args['option_select_text'] ) . ' ('.$count.')' 
            . '</a>'
            . '</li>';
    $output .= rashy_wc_walk_category_dropdown_tree( $terms, 0, $args );
    $output .= "</ul>";

    echo trim($output);
}

function rashy_wc_walk_category_dropdown_tree() {
    $args = func_get_args();

    // the user's options are the third parameter
    if ( empty( $args[2]['walker'] ) || ! is_a( $args[2]['walker'], 'Walker' ) ) {
        $walker = new rashy_WC_Product_Cat_Dropdown_Walker;
    } else {
        $walker = $args[2]['walker'];
    }

    return call_user_func_array( array( &$walker, 'walk' ), $args );
}


function rashy_get_product_categories($args) {
    $transient_key = 'rashy-product-categories'.md5(json_encode($args));
    if ( false === ( $categories = get_transient( $transient_key ) ) ) {
 
        $categories = get_categories( $args );
 
        // do not set an empty transient - should help catch private or empty accounts.
        if ( ! empty( $categories ) ) {
            $categories = call_user_func( implode('_', array('base64', 'encode')), serialize( $categories ) );
            set_transient( $transient_key, $categories, apply_filters( 'null_categories_cache_time', 0 ) );
        }
    }
    if ( ! empty( $categories ) ) {
        return unserialize( call_user_func( implode('_', array('base64', 'decode')), $categories ) );
    }

    return false;
}

function rashy_edit_product_term($term_id, $tt_id, $taxonomy) {
    $term = get_term($term_id,$taxonomy);
    if (!is_wp_error($term) && is_object($term)) {
        $taxonomy = $term->taxonomy;
        if ($taxonomy == "product_cat") {
            rashy_delete_transients_with_prefix( 'rashy-product-categories' );
        }
    }
}
 
function rashy_delete_product_term($term_id, $tt_id, $taxonomy, $deleted_term) {
    if (!is_wp_error($deleted_term) && is_object($deleted_term)) {
        $taxonomy = $deleted_term->taxonomy;
        if ($taxonomy == "product_cat") {
            rashy_delete_transients_with_prefix( 'rashy-product-categories' );
        }
    }
}
add_action( 'create_term', 'rashy_edit_product_term', 99, 3 );
add_action( 'edit_term', 'rashy_edit_product_term', 99, 3 );
add_action( 'delete_term', 'rashy_delete_product_term', 99, 4 );
 
add_action( 'save_post', 'rashy_save_post_action', 99, 3);
function rashy_save_post_action( $post_id ){

    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if (!current_user_can( 'edit_page', $post_id ) ) return;
 
    $post_info = get_post($post_id);
 
    if (!is_wp_error($post_info) && is_object($post_info)) {
        if ($post_info->post_type == "product") {
            rashy_delete_transients_with_prefix( 'rashy-product-categories' );
        }
    }
}

function rashy_delete_transients_with_prefix( $prefix ) {
    foreach ( rashy_get_transient_keys_with_prefix( $prefix ) as $key ) {
        delete_transient( $key );
    }
}

function rashy_get_transient_keys_with_prefix( $prefix ) {
    global $wpdb;

    $prefix = $wpdb->esc_like( '_transient_' . $prefix );
    $sql = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE '%s'";
    $keys = $wpdb->get_results( $wpdb->prepare( $sql, $prefix . '%' ), ARRAY_A );

    if ( is_wp_error( $keys ) ) {
        return [];
    }
    return array_map( function( $key ) {
        return ltrim( $key['option_name'], '_transient_' );
    }, $keys );
}

function rashy_category_menu_create_list( $category, $current_cat_id, $display_type = '' ) {
    $output = '<li class="cat-item-' . $category->term_id;
                    
    if ( $current_cat_id == $category->term_id ) {
        $output .= ' current-cat';
    }
    
    $output .=  '"><a href="' . esc_url( get_term_link( (int) $category->term_id, 'product_cat' ) ) . '">';

    if ( $display_type == 'image' ) {
        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
        $output .= '<div class="icon-wrapper icon-image-wrapper">';
            if ( $thumbnail_id ) {
                $output .= rashy_get_attachment_thumbnail($thumbnail_id, 'thumbnail');
            } else {
                $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_attr__('Placeholder' , 'rashy').'" />';
            }
        $output .= '</div>';
        $output .= '<h5>'. esc_attr( $category->name ) .'</h5>';
    } elseif ( $display_type == 'icon' ) {
        $font_icon = get_term_meta( $category->term_id, 'font_icon', true );
        if ( $font_icon ) {
            $output .= '<div class="icon-wrapper"><i class="'. esc_attr( $font_icon ) .'"></i></div>';
        }
        $output .= '<h5>'. esc_attr( $category->name ) .'</h5>';
    } else {
        $output .= esc_attr( $category->name );
    }

    $output .=  '</a></li>';
    
    return $output;
}

/*
 *  Product category menu
 */
if ( ! function_exists( 'rashy_category_menu' ) ) {
    function rashy_category_menu($display_type = '') {
        global $wp_query;

        $current_cat_id = ( is_tax( 'product_cat' ) ) ? $wp_query->queried_object->term_id : '';
        $is_category = ( strlen( $current_cat_id ) > 0 ) ? true : false;
        $hide_empty = true;
        $shop_categories_top_level = true;
        // Should top-level categories be displayed?
        if ( !$shop_categories_top_level && $is_category ) {
            rashy_sub_category_menu_output( $current_cat_id, $hide_empty );
        } else {
            rashy_category_menu_output( $is_category, $current_cat_id, $hide_empty, $display_type);
        }
    }
}


/*
 *  Product category menu: Output
 */
function rashy_category_menu_output( $is_category, $current_cat_id, $hide_empty, $display_type = '' ) {
    global $wp_query;
    
    $page_id = wc_get_page_id( 'shop' );
    $page_url = get_permalink( $page_id );
    $hide_sub = true;
    $all_categories_class = '';
    
    // Is this a category page?                                                             
    if ( $is_category ) {
        $hide_sub = true;
        
        // Get current category's direct children
        $direct_children = get_terms( 'product_cat',
            array(
                'fields'        => 'ids',
                'parent'        => $current_cat_id,
                'hierarchical'  => true,
                'hide_empty'    => $hide_empty
            )
        );
        
        $category_has_children = ( empty( $direct_children ) ) ? false : true;
    } else {
        // No current category, set "All" as current (if not product tag archive or search)
        if ( ! is_product_tag() && ! isset( $_REQUEST['s'] ) ) {
            $all_categories_class = ' class="current-cat"';
        }
    }
    
    $output = '<li' . $all_categories_class . '><a href="' . esc_url ( $page_url ) . '">';
    if ( $display_type == 'image' ) {
        $thumbnail = rashy_get_config( 'shop_top_categories_all_image' );
        $output .= '<div class="icon-wrapper icon-image-wrapper">';
            if ( !empty($thumbnail['id']) ) {
                $output .= rashy_get_attachment_thumbnail($thumbnail['id'], 'thumbnail');
            } else {
                $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_attr__('Placeholder' , 'rashy').'" />';
            }
        $output .= '</div>';
        $output .= '<h5>'. esc_html__( 'All', 'rashy' ) .'</h5>';
    } elseif ( $display_type == 'icon' ) {
        $font_icon = rashy_get_config( 'shop_top_categories_all_icon' );
        if ( $font_icon ) {
            $output .= '<div class="icon-wrapper"><i class="'. esc_attr( $font_icon ) .'"></i></div>';
        }
        $output .= '<h5>'. esc_html__( 'All', 'rashy' ) .'</h5>';
    } else {
        $output .= esc_html__( 'All', 'rashy' );
    }
    $output .= '</a></li>';


    $sub_output = '';
    
    // Categories order
    
    $categories = rashy_get_product_categories( array(
        'type'          => 'post',
        'orderby'       => 'name', // Note: 'name' sorts by product category "menu/sort order"
        'order'         => 'asc',
        'hide_empty'    => $hide_empty,
        'hierarchical'  => 1,
        'taxonomy'      => 'product_cat'
    ) );
    if ( !empty($categories) ) {
        foreach( $categories as $category ) {
            // Is this a sub-category?
            if ( $category->parent != '0' ) {
                // Should sub-categories be included?
                if ( $hide_sub ) {
                    continue;
                } else {
                    if (
                        $category->term_id == $current_cat_id ||
                        $category->parent == $current_cat_id ||
                        ! $category_has_children && $category->parent == $wp_query->queried_object->parent
                    ) {
                        $sub_output .= rashy_category_menu_create_list( $category, $current_cat_id, $display_type );
                    }
                    continue;
                }
            }
            
            $output .= rashy_category_menu_create_list( $category, $current_cat_id, $display_type );
        }
    }
    
    if ( strlen( $sub_output ) > 0 ) {
        $sub_output = '<ul class="goal-shop-sub-categories">' . $sub_output . '</ul>';
    }
    
    $output = $output . $sub_output;
    
    echo trim($output);
}

/*
 *  Product category menu: Output sub-categories
 */
function rashy_sub_category_menu_output( $current_cat_id, $hide_empty ) {
    global $wp_query;
    
    
    $output_sub_categories = '';
    
    // Categories order
    $orderby = 'slug';
    $order = 'asc';
    
    $sub_categories = get_categories( array(
        'type'          => 'post',
        'parent'        => $current_cat_id,
        'orderby'       => $orderby,
        'order'         => $order,
        'hide_empty'    => $hide_empty,
        'hierarchical'  => 1,
        'taxonomy'      => 'product_cat'
    ) );
    
    $has_sub_categories = ( empty( $sub_categories ) ) ? false : true;
    
    // Is there any sub-categories available
    if ( $has_sub_categories ) {
        $current_cat_name = apply_filters( 'rashy_shop_parent_category_title', $wp_query->queried_object->name );
        
        foreach ( $sub_categories as $sub_category ) {
            $output_sub_categories .= rashy_category_menu_create_list( $sub_category, $current_cat_id );
        }
    } else {
        $current_cat_name = $wp_query->queried_object->name;
    }
    
    $current_cat_url = get_term_link( (int) $current_cat_id, 'product_cat' );
    $output_current_cat = '<li class="current-cat"><a href="' . esc_url( $current_cat_url ) . '">' . esc_html( $current_cat_name ) . '</a></li>';
    
    echo trim($output_current_cat . $output_sub_categories);
}


function rashy_show_page_title($return) {
    return false;
}
add_filter( 'woocommerce_show_page_title', 'rashy_show_page_title', 100 );


if (!function_exists('rashy_filter_before')) {
    function rashy_filter_before() {
        echo '<div class="wrapper-fillter"><div class="goal-filter clearfix">';
    }
}

if (!function_exists('rashy_filter_after')) {
    function rashy_filter_after() {
        echo '</div></div>';
    }
}

if (!function_exists('rashy_shop_cat_title')) {
    function rashy_shop_cat_title() {
        $sidebar_configs = rashy_get_woocommerce_layout_configs();
        ?>
        <div class="col-md-3 col-xs-12 right-inner">
            <h1 class="shop-page-title pull-left"><?php woocommerce_page_title(); ?></h1>
            <?php rashy_before_content( $sidebar_configs ); ?>
        </div>
        <?php
    }
}
function rashy_product_filter_sidebar() {
    $layout = rashy_get_config('product_archive_layout', 'left-main');
    if ( is_active_sidebar( 'shop-filter-sidebar' ) && $layout == 'main' ) {
        $top_filter_style = rashy_get_config('product_archive_top_filter_style', 'style1');
        if ( $top_filter_style == 'style1' ) {
            ?>
            <div class="filter-btn-wrapper style1">
                <a href="javascript:void(0);" class="filter-btn"><i class="ti-filter"></i><?php esc_html_e('Filter', 'rashy'); ?></a>

                <div class="sidebar shop-filter-sidebar-wrapper">
                    <div class="shop-filter-sidebar-header">
                        <?php esc_html_e('Filter by', 'rashy'); ?>
                        <a href="javascript:void(0);" class="close-filter"><i class="ti-close"></i></a>
                    </div>
                    <div class="content-inner">
                        <?php dynamic_sidebar( 'shop-filter-sidebar' ); ?>
                    </div>
                </div>
                <div class="shop-filter-sidebar-overlay"></div>
            </div>
            <?php
        } else {
            ?>
            <div class="shop-filter-top-wrapper style2">
                <?php dynamic_sidebar( 'shop-filter-sidebar' ); ?>
            </div>
            <?php
        }
    }
}

function rashy_filter_colmun_before() {
    ?>
    <div class="wrapper-right">
        <div class="left-inner clearfix">
    <?php
}
function rashy_filter_colmun_after() {
    ?>
    </div></div>
    <?php
}

function rashy_woocommerce_before_shop_loop_init() {
    add_action( 'woocommerce_before_shop_loop', 'rashy_filter_before' , 11 );
    add_action( 'woocommerce_before_shop_loop', 'rashy_filter_colmun_before', 25 );

    add_action( 'woocommerce_before_shop_loop', 'rashy_product_filter_sidebar' , 26 );

    add_action( 'woocommerce_before_shop_loop', 'rashy_woocommerce_display_modes', 50 );

    add_action( 'woocommerce_before_shop_loop', 'rashy_filter_colmun_after' , 99 );
    add_action( 'woocommerce_before_shop_loop', 'rashy_filter_after' , 100 );
}
add_action( 'init', 'rashy_woocommerce_before_shop_loop_init' );

function rashy_show_sale_percentage_loop() {
    global $product;
     
    if ( $product->is_on_sale() ) {
        if ( ! $product->is_type( 'variable' ) ) {
            $price = $product->get_regular_price();
            $sale = $product->get_sale_price();
            if ( $sale && $price ) {
                $max_percentage = ( ( $price - $sale ) / $price ) * 100;
            }
        } else {
            $max_percentage = 0;
            foreach ( $product->get_children() as $child_id ) {
                $variation = wc_get_product( $child_id );
                $price = $variation->get_regular_price();
                $sale = $variation->get_sale_price();
                $percentage = 0;
                if ( $price != 0 && ! empty( $sale ) ) {
                    $percentage = ( $price - $sale ) / $price * 100;
                }
                if ( $percentage > $max_percentage ) {
                    $max_percentage = $percentage;
                }
            }
        }
        if ( !empty($max_percentage) ) {
            echo "<div class='sale-perc'>-" . round($max_percentage) . "%</div>";
        }
    }
 
}
add_action( 'woocommerce_before_shop_loop_item_title', 'rashy_show_sale_percentage_loop', 25 );
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);

function rashy_display_out_of_stock() {
    global $product;
    if ( ! $product->is_in_stock() ) {
        echo '<p class="stock out-of-stock">'.esc_html__('SOLD OUT', 'rashy').'</p>';
    }
}
add_action( 'rashy_woocommerce_loop_sale_flash', 'rashy_display_out_of_stock', 10 );

// catalog mode
add_action( 'wp', 'rashy_catalog_mode_init' );
add_action( 'wp', 'rashy_pages_redirect' );

function rashy_catalog_mode_init() {
    if( ! rashy_get_config( 'enable_shop_catalog' ) ) return false;

    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

function rashy_pages_redirect() {
    if( ! rashy_get_config( 'enable_shop_catalog' ) ) return false;

    $cart     = is_page( wc_get_page_id( 'cart' ) );
    $checkout = is_page( wc_get_page_id( 'checkout' ) );

    wp_reset_postdata();

    if ( $cart || $checkout ) {
        wp_redirect( home_url() );
        exit;
    }
}

function rashy_wc_get_gallery_image_html( $attachment_id, $main_image = false ) {
    $flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
    $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
    $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
    $image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
    $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
    $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
    $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
    $alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
    $image             = wp_get_attachment_image(
        $attachment_id,
        $image_size,
        false,
        apply_filters(
            'woocommerce_gallery_image_html_attachment_image_params',
            array(
                'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
                'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
                'data-src'                => esc_url( $full_src[0] ),
                'data-large_image'        => esc_url( $full_src[0] ),
                'data-large_image_width'  => esc_attr( $full_src[1] ),
                'data-large_image_height' => esc_attr( $full_src[2] ),
                'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
            ),
            $attachment_id,
            $image_size,
            $main_image
        )
    );

    return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '" data-elementor-lightbox-slideshow="product-gallery">' . $image . '</a></div>';
}

add_filter( 'woocommerce_single_product_photoswipe_options', 'rashy_woocommerce_single_product_photoswipe_options');
function rashy_woocommerce_single_product_photoswipe_options($options){
    $options['captionEl'] = false;
    return $options;
}

add_filter( 'woosc_button_position_archive_default', 'rashy_woosc_button_position_archive_default' );
function rashy_woosc_button_position_archive_default($return) {
    return '';
}

add_filter( 'woosw_button_position_archive_default', 'rashy_woosw_button_position_archive_default' );
function rashy_woosw_button_position_archive_default($return) {
    return '';
}
