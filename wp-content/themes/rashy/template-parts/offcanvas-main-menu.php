
<div class="goal-offcanvas dark-menu-sidebar hidden-sm hidden-xs"> 
    <div class="offcanvas-top">
        <div class="logo-in-theme">
            <?php get_template_part( 'template-parts/logo/logo' ); ?>
        </div>
        <div class="clearfix">
            <div class="header-right pull-left">
                <?php if ( class_exists( 'YITH_WCWL' ) && rashy_get_config('show_wishlist_btn', true) ):
                    $wishlist_url = YITH_WCWL()->get_wishlist_url();
                ?>
                    <div class="pull-right">
                        <a class="wishlist-icon" href="<?php echo esc_url($wishlist_url);?>" title="<?php esc_attr_e( 'View Your Wishlist', 'rashy' ); ?>"><i class="ti-heart"></i>
                            <?php if ( function_exists('yith_wcwl_count_products') ) { ?>
                                <span class="count"><?php echo yith_wcwl_count_products(); ?></span>
                            <?php } ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ( rashy_get_config('show_login_account', true) ) { ?>
                    <div class="pull-right">
                        <?php if( is_user_logged_in() ){ ?>
                            <div class="top-wrapper-menu">
                                <a class="drop-dow" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"><i class="icon_lock_alt"></i></a>
                            </div>
                        <?php } else { ?>
                            <div class="top-wrapper-menu">
                                <a class="drop-dow" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"><i class="icon_lock_alt"></i></a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                
                <?php if ( defined('RASHY_WOOCOMMERCE_ACTIVED') && rashy_get_config('show_cartbtn') && !rashy_get_config( 'enable_shop_catalog' ) ): ?>
                    <div class="pull-right">
                        <?php get_template_part( 'woocommerce/cart/mini-cart-button' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="offcanvas-middle">
        <?php if ( has_nav_menu( 'vertical-menu' ) ): ?>
            <div class="vertical-wrapper">
                <div class="title-vertical bg-theme"><i class="fa fa-bars" aria-hidden="true"></i> <span class="text-title"><?php echo esc_html__('all Departments','rashy') ?></span> <i class="fa fa-angle-down show-down" aria-hidden="true"></i></div>
                <?php
                    $args = array(
                        'theme_location' => 'vertical-menu',
                        'container_class' => 'content-vertical',
                        'menu_class' => 'goal-vertical-menu nav navbar-nav',
                        'fallback_cb' => '',
                        'menu_id' => 'vertical-menu',
                        'walker' => new Rashy_Nav_Menu()
                    );
                    wp_nav_menu($args);
                ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="offcanvas-bottom">
        <?php if ( is_active_sidebar( 'sidebar-topbar-right' ) ) { ?>
            <div class="sidebar-topbar-left">
                <?php dynamic_sidebar( 'sidebar-topbar-right' ); ?>
            </div>
        <?php } ?>
         <?php
            $social_links = rashy_get_config('header_social_links_link');
            $social_icons = rashy_get_config('header_social_links_icon');
            if ( !empty($social_links) ) {
                ?>
                <ul class="social-top">
                    <?php foreach ($social_links as $key => $value) { ?>
                        <li class="social-item">
                            <a href="<?php echo esc_url($value); ?>">
                                <i class="<?php echo esc_attr($social_icons[$key]); ?>"></i>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <?php
            }
        ?>
    </div>
</div>
<div class="over-dark"></div>
