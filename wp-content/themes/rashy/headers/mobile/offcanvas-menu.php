<div id="goal-mobile-menu" class="goal-offcanvas hidden-lg"> 
    <div class="goal-offcanvas-body">

        <div class="header-offcanvas">
            <div class="container">
                <div class="row flex-middle">
                    <div class="col-xs-3">
                        <a class="btn-toggle-canvas" data-toggle="offcanvas">
                            <i class="ti-close"></i>
                        </a>
                    </div>

                    <div class="text-center col-xs-6">
                        <?php
                            $logo = rashy_get_config('media-mobile-logo');
                            $logo_url = '';
                            if ( !empty($logo['id']) ) {
                                $img = wp_get_attachment_image_src($logo['id'], 'full');
                                if ( !empty($img[0]) ) {
                                    $logo_url = $img[0];
                                }
                            }
                        ?>
                        <?php if( isset($logo['url']) && !empty($logo['url']) ): ?>
                            <div class="logo">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                    <img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="logo logo-theme">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                    <img src="<?php echo esc_url_raw( get_template_directory_uri().'/images/logo.svg'); ?>" alt="<?php bloginfo( 'name' ); ?>">
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ( defined('RASHY_WOOCOMMERCE_ACTIVED') && rashy_get_config('show_cartbtn') && !rashy_get_config( 'enable_shop_catalog' ) ): ?>
                        <div class="col-xs-3">
                            <div class="pull-right">
                                <!-- Setting -->
                                <div class="top-cart">
                                    <?php global $woocommerce; ?>
                                    <div class="goal-topcart">
                                        <div class="cart">
                                            <a class="mini-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e('View your shopping cart', 'rashy'); ?>">
                                                <i class="icon-shopping-bag"></i>
                                                <span class="count"><?php echo trim($woocommerce->cart->cart_contents_count); ?></span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <div class="middle-offcanvas">

            <?php
                if ( defined('RASHY_WOOCOMMERCE_ACTIVED') && rashy_get_config('show_searchform', true) ) {
                    get_template_part('template-parts/productsearchform-nocategory');
                }
            ?>

            <nav id="menu-main-menu-navbar" class="navbar navbar-offcanvas" role="navigation">
                <?php
                    $mobile_menu = 'primary';
                    $menus = get_nav_menu_locations();
                    if( !empty($menus['mobile-primary']) && wp_get_nav_menu_items($menus['mobile-primary'])) {
                        $mobile_menu = 'mobile-primary';
                    }
                    $args = array(
                        'theme_location' => $mobile_menu,
                        'container_class' => '',
                        'menu_class' => '',
                        'fallback_cb' => '',
                        'menu_id' => '',
                        'container' => 'div',
                        'container_id' => 'mobile-menu-container',
                        'walker' => new Rashy_Mobile_Menu()
                    );
                    wp_nav_menu($args);

                ?>

                <?php if ( rashy_get_config('show_login_register', true) ) { ?>
                    <a class="my-account" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>">
                        <?php if ( is_user_logged_in() ) { ?>
                            <?php esc_html_e('MY ACCOUNT', 'rashy'); ?>
                        <?php }else{ ?>
                            <?php esc_html_e('LOGIN & REGISTER', 'rashy'); ?>
                        <?php } ?>
                    </a>
                <?php } ?>
            </nav>
        </div>
        <?php if ( is_active_sidebar( 'header-mobile-bottom' ) || rashy_get_config('show_login_register', true) ) { ?>
            <div class="header-mobile-bottom">
                
            
                <?php if ( is_active_sidebar( 'header-mobile-bottom' ) ){ ?>
                    <?php dynamic_sidebar( 'header-mobile-bottom' ); ?>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</div>
<div class="over-dark"></div>