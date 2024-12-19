<?php
if ( !function_exists ('rashy_custom_styles') ) {
	function rashy_custom_styles() {
		global $post;	
		
		ob_start();	
		?>
		    

			<?php
				$main_font = rashy_get_config('main_font');
				$main_font = isset($main_font['font-family']) ? $main_font['font-family'] : 'Inter Tight';
			?>
			<?php if ( $main_font ): ?>
				/* Main Font */
				body,  .widget .widget-title p.sub-text, .widget .widget-title p, .widget .widgettitle p.sub-text, .widget .widgettitle p, .widget .widget-heading p.sub-text, .widget .widget-heading p,  .product-block.grid .product-cat,span.price , .name
				{
					font-family: <?php echo trim($main_font); ?> !important;
				}
			<?php endif; ?>
			
			<?php
				$heading_font = rashy_get_config('heading_font');
				$heading_font = isset($heading_font['font-family']) ? $heading_font['font-family'] : 'Zen Dots';
			?>
			<?php if ( $heading_font ): ?>
				/* Heading Font */
				.btn, .button,.megamenu > li > a, .posts-list .entry-title a, .post-layout .post-info .readmore,.post-navigation .post-title, .woocommerce #respond input#submit, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6,.product-block.grid .name a,.woocommerce ul.product_list_widget .product-title a, .product-block-list .name a, .post-layout .entry-title a, .widget-title,.widgettitle,.details-product .information .compare, .details-product .information .woosc-btn,.details-product .information .woosw-btn,
				.tabs-v1 .nav-tabs > li > a,.tabs-v3 .head-tab, .woocommerce table.shop_table tbody .product-name , .woocommerce div.product form.cart .group_table label
				{
					
					font-family: <?php echo trim($heading_font); ?> !important;
				}
			<?php endif; ?>

            
			<?php if ( rashy_get_config('main_color') != "" ) : ?>
				/* seting background main */
				.woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a::before ,
				.goal-checkout-step li.active,
				.details-product .goal-woocommerce-product-gallery-thumbs.vertical .slick-arrow:hover i, .details-product .goal-woocommerce-product-gallery-thumbs.vertical .slick-arrow:focus i,
				.product-block-list .quickview:hover, .product-block-list .quickview:focus,
				.goal-pagination .page-numbers li > span:hover, .goal-pagination .page-numbers li > span.current, .goal-pagination .page-numbers li > a:hover, .goal-pagination .page-numbers li > a.current, .goal-pagination .pagination li > span:hover, .goal-pagination .pagination li > span.current, .goal-pagination .pagination li > a:hover, .goal-pagination .pagination li > a.current,
				.wishlist-icon .count, .mini-cart .count,
				.woocommerce .widget_price_filter .price_slider_amount .button,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
				.widget-countdown.style3 .title::before,
				.slick-carousel .slick-arrow:hover, .slick-carousel .slick-arrow:active, .slick-carousel .slick-arrow:focus,
				
				.add-fix-top,
				.widget .widget-title::after, .widget .widgettitle::after, .widget .widget-heading::after,
				.slick-carousel .slick-dots li.slick-active button,
				.bg-theme,
				.vertical-wrapper .title-vertical, table.variations .tawcvs-swatches .swatch-label.selected, .widget-social .social a:hover, .widget-social .social a:focus,
				.goal-pagination > span:hover, .goal-pagination > span.current, .goal-pagination > a:hover, .goal-pagination > a.current,
				.woocommerce .percent-sale, .woocommerce span.onsale, .goal-topcart .offcanvas-content .title-cart-canvas, 
				.widget-team .top-image .social a
				{
					background-color: <?php echo esc_html( rashy_get_config('main_color') ) ?> ;
				}
			
				
				/* setting color*/
				.header-mobile .mobile-vertical-menu-title:hover, .header-mobile .mobile-vertical-menu-title.active,
				.dokan-store-menu #cat-drop-stack > ul a:hover, .dokan-store-menu #cat-drop-stack > ul:focus,
				.shopping_cart_content .cart_list .quantity,
				#order_review .order-total .amount, #order_review .cart-subtotal .amount,
				.woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link.is-active > a, .woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link:hover > a, .woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link:active > a,
				.woocommerce table.shop_table tbody .product-subtotal,
				.woocommerce div.product p.price,
				.goal-breadscrumb .breadcrumb a:hover, .goal-breadscrumb .breadcrumb a:active,
				.details-product .title-cat-wishlist-wrapper .yith-wcwl-add-to-wishlist a:focus, .details-product .title-cat-wishlist-wrapper .yith-wcwl-add-to-wishlist a:hover,
				.details-product .title-cat-wishlist-wrapper .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				.details-product .product_meta a,
				.product-block-list .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				.product-block-list .yith-wcwl-add-to-wishlist a:hover, .product-block-list .yith-wcwl-add-to-wishlist a:focus,
				.goal-filter .change-view:hover, .goal-filter .change-view.active,
				.woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item > a:hover, .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item > a:active,
				.mobile-sidebar-btn,
				.btn-readmore:hover,
				.goal-countdown .times > div > span,
				.btn-link,
				.goal-vertical-menu > li > a > i, .goal-vertical-menu > li > a > img,
				.megamenu .dropdown-menu li > a:hover, .megamenu .dropdown-menu li > a:active,
				.goal-footer a:hover, .goal-footer a:focus, .goal-footer a:active, .megamenu .dropdown-menu li.current-menu-item > a, .megamenu .dropdown-menu li.open > a, .megamenu .dropdown-menu li.active > a, .comment-list .comment-reply-link, .comment-list .comment-edit-link, .product-categories li.current-cat-parent > a, .product-categories li.current-cat > a, .product-categories li:hover > a,.detail-post .top-info i, .detail-post .entry-content-detail .list-categories:before,.post-layout .top-info i,.post-layout .list-categories:before,.megamenu > li:hover > a, .megamenu > li.active > a, .goal-breadscrumb .breadcrumb .active,.post-layout .top-info a, .post-layout .top-info span, #recentcomments > li:before, .wp-block-latest-comments > li:before,.tabs-v1 .nav-tabs > li.active > a,.add-to-cart-bottom-wrapper .woocommerce-Price-amount,.woocommerce ul.product_list_widget .woocommerce-Price-amount, .product-categories li.current-cat-parent > .count, .product-categories li.current-cat > .count, .product-categories li:hover > .count,.woocommerce .star-rating span:before
				{
					color: <?php echo esc_html( rashy_get_config('main_color') ) ?> !important;
				}
				/* setting border color*/
				.goal-checkout-step li.active::after,
				.details-product .goal-woocommerce-product-gallery-thumbs .slick-slide:hover .thumbs-inner, .details-product .goal-woocommerce-product-gallery-thumbs .slick-slide:active .thumbs-inner, .details-product .goal-woocommerce-product-gallery-thumbs .slick-slide.slick-current .thumbs-inner,
				.product-block-list:hover,
				.border-theme, .widget-social .social a:hover, .widget-social .social a:focus, .post .entry-description .wp-block-quote,
				.tabs-v1 .nav-tabs > li.active{
					border-color: <?php echo esc_html( rashy_get_config('main_color') ) ?> !important;
				}

				.details-product .information .price,
				.product-block-list .price,
				.text-theme{
					color: <?php echo esc_html( rashy_get_config('main_color') ) ?> !important;
				}
				.goal-checkout-step li.active .inner::after {
					border-color: #fff <?php echo esc_html( rashy_get_config('main_color') ) ?>;
				}

				.goal-loader-inner:before{
					border-color: <?php echo esc_html( rashy_get_config('main_color') ) ?>;
				}

				.sidebar > .widget.widget_block h1:before, .sidebar > .widget.widget_block h2:before, .sidebar > .widget.widget_block h3:before, .sidebar > .widget.widget_block h4:before, .sidebar > .widget.widget_block h5:before, .sidebar > .widget.widget_block h6:before, .sidebar > .widget.widget_block .h1:before, .sidebar > .widget.widget_block .h2:before, .sidebar > .widget.widget_block .h3:before, .sidebar > .widget.widget_block .h4:before, .sidebar > .widget.widget_block .h5:before, .sidebar > .widget.widget_block .h6:before, .sidebar > .widget.widget_block label:before, .goal-sidebar > .widget.widget_block h1:before, .goal-sidebar > .widget.widget_block h2:before, .goal-sidebar > .widget.widget_block h3:before, .goal-sidebar > .widget.widget_block h4:before, .goal-sidebar > .widget.widget_block h5:before, .goal-sidebar > .widget.widget_block h6:before, .goal-sidebar > .widget.widget_block .h1:before, .goal-sidebar > .widget.widget_block .h2:before, .goal-sidebar > .widget.widget_block .h3:before, .goal-sidebar > .widget.widget_block .h4:before, .goal-sidebar > .widget.widget_block .h5:before, .goal-sidebar > .widget.widget_block .h6:before, .goal-sidebar > .widget.widget_block label:before,.sidebar > .widget .widget-title:before, .sidebar > .widget .widgettitle:before, .sidebar > .widget .widget-heading:before, .goal-sidebar > .widget .widget-title:before, .goal-sidebar > .widget .widgettitle:before, .goal-sidebar > .widget .widget-heading:before {
					background-color : <?php echo esc_html( rashy_get_config('main_color') ) ?>;
				}
				
			<?php endif; ?>

			<?php if ( rashy_get_config('text_color') != "" ) : ?>
			/* setting text color*/
			body,.posts-list .top-info a, .post .entry-description .wp-block-quote{
				color: <?php echo esc_html( rashy_get_config('text_color') ) ?>;
			}
			.tagcloud a, .wp-block-tag-cloud a {
				border-color: <?php echo esc_html( rashy_get_config('text_color') ) ?>;
				background-color: <?php echo esc_html( rashy_get_config('text_color') ) ?> ;
			}
			<?php endif; ?>

			<?php if ( rashy_get_config('link_color') != "" ) : ?>
			/* setting link color*/
			a, .show-search-header,.post-layout .top-info a, .post-layout .top-info span, .detail-post .top-info a, .detail-post .top-info span,.post-navigation .nav-links .post-title{
				color: <?php echo esc_html( rashy_get_config('link_color') ) ?> ;
			}
			.goal-loader-inner:after{
					border-color: <?php echo esc_html( rashy_get_config('link_color') ) ?>;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('hover_color') != "" ) : ?>
			/* setting hover color*/
			a:hover, a:focus, .details-product .information .woosw-btn:hover, .details-product .information .woosw-btn:focus, .details-product .information .woosw-btn.woosw-added,.details-product .information .compare:hover, .details-product .information .compare:focus, .details-product .information .compare.woosc-added, .details-product .information .woosc-btn:hover, .details-product .information .woosc-btn:focus, .details-product .information .woosc-btn.woosc-added,.post-navigation .nav-links .post-title:hover{
				color: <?php echo esc_html( rashy_get_config('hover_color') ) ?> !important;
			}
			.woocommerce .quantity .minus:hover, .woocommerce-page .quantity .minus:hover, .woocommerce .quantity .plus:hover, .woocommerce-page .quantity .plus:hover, .tagcloud a:hover, .tagcloud a:focus, .tagcloud a.active {
				background-color: <?php echo esc_html( rashy_get_config('hover_color') ) ?> !important;
				border-color: <?php echo esc_html( rashy_get_config('hover_color') ) ?> !important; 
			}
			.tagcloud a:hover, .tagcloud a:focus, .tagcloud a.active{
				color:#fff !important;
			}
			.wp-block-tag-cloud a:hover, .wp-block-tag-cloud a:focus, .wp-block-tag-cloud a.active {
				background-color: <?php echo esc_html( rashy_get_config('hover_color') ) ?>;
				border-color: <?php echo esc_html( rashy_get_config('hover_color') ) ?> ; 
			}
			<?php endif; ?>

			<?php if ( rashy_get_config('heading_color') != "" ) : ?>
			/* setting heading color*/
			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6,.widget .widget-title, .widget .widgettitle, .widget .widget-heading{
				color: <?php echo esc_html( rashy_get_config('heading_color') ) ?> !important;
			}
			<?php endif; ?>

			<?php if ( rashy_get_config('price_color') != "" ) : ?>
			/* setting price color*/
			.woocommerce div.product p.price, 
			.woocommerce div.product span.price, 
			.woocommerce ul.product_list_widget .woocommerce-Price-amount, 
			.shopping_cart_content .cart_list .quantity, 
			.add-to-cart-bottom-wrapper .woocommerce-Price-amount,
			.woocommerce table.shop_table tbody .product-subtotal,
			.woocommerce .cart_totals table.shop_table th .woocommerce-Price-amount, 
			.woocommerce .cart_totals table.shop_table td .woocommerce-Price-amount,
			#order_review .order-total .amount, #order_review .cart-subtotal .amount{
				color: <?php echo esc_html( rashy_get_config('price_color') ) ?> !important;
			}
			.product-block .sale-perc,
			.woocommerce .percent-sale, .woocommerce span.onsale {
				background-color: <?php echo esc_html( rashy_get_config('price_color') ) ?> !important;
			}
			<?php endif; ?>


			

			<?php if ( rashy_get_config('button_color') != "" ) : ?>
				/* seting background main */
				.btn,
				.btn-theme-second:hover,.btn-block,
				.slick-carousel .slick-arrow,
				.product-block-list .compare:hover,
				.widget-mailchimp.default .btn, .widget-mailchimp.default .viewmore-products-btn,
				.viewmore-products-btn, .woocommerce .wishlist_table td.product-add-to-cart a, .woocommerce .return-to-shop .button, .woocommerce .track_order .button, .woocommerce #respond input#submit,
				.woocommerce div.product form.cart .button, .woocommerce div.product form.cart .added_to_cart,
				.add-to-cart-bottom-wrapper .cart .added_to_cart, .add-to-cart-bottom-wrapper .cart button.single_add_to_cart_button,.goal-loadmore-btn:hover, .goal-loadmore-btn:active,.woocommerce .checkout_coupon .button,.woocommerce .widget_price_filter .price_slider_amount .button, 
				.goal-topcart .buttons .btn.btn-primary
				{
					color: <?php echo esc_html( rashy_get_config('button_color') ) ?> !important;
				}
				.woocommerce div.product form.cart .button,
				.product-block-list .add-cart .added_to_cart,
				.btn-theme.btn-outline{
					border-color: <?php echo esc_html( rashy_get_config('button_color') ) ?> ;
				}

				  .woocommerce #respond input#submit,  .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce .wishlist_table .product-add-to-cart a, .viewmore-products-btn {
					color: <?php echo esc_html( rashy_get_config('button_color') ) ?> !important;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('button_hover_color') != "" ) : ?>
				/* seting background main */
				.btn,
				.btn-theme-second,btn-block:hover,
				.goal-loadmore-btn,
				.slick-carousel .slick-arrow:hover,
				.product-block-list .compare,
				.widget-mailchimp.default .btn:hover, .widget-mailchimp.default .viewmore-products-btn:hover,
				.viewmore-products-btn:hover, .woocommerce .wishlist_table td.product-add-to-cart a:hover, .woocommerce .return-to-shop .button:hover, .woocommerce .track_order .button:hover, .woocommerce #respond input#submit:hover,
				.woocommerce div.product form.cart .button:hover, .woocommerce div.product form.cart .button:focus,
				.details-product .information .compare:hover, .details-product .information .compare:focus,
				
				.product-block.grid .yith-wcwl-add-to-wishlist:hover:not(.add_to_wishlist) a,
				
				.product-block.grid .yith-compare .compare:hover,
				.product-block-list .compare.added, .product-block-list .compare:hover, .product-block-list .compare:focus,
				 
				.btn-theme.btn-outline:hover,
				.btn-theme.btn-outline:focus,
				.add-to-cart-bottom-wrapper .cart button.single_add_to_cart_button:hover, .add-to-cart-bottom-wrapper .cart button.single_add_to_cart_button:focus,.woocommerce .checkout_coupon .button:hover, .woocommerce .widget_price_filter .price_slider_amount .button:hover,.goal-topcart .buttons .btn.btn-primary:hover, .goal-topcart .buttons .btn.checkout,
				.widget-search .btn, .widget-search .viewmore-products-btn{
					color: <?php echo esc_html( rashy_get_config('button_hover_color') ) ?> !important;
				}
				
			<?php endif; ?>

			<?php if ( rashy_get_config('button_bg_color') != "" ) : ?>
				.btn,
				.btn-theme-second:hover,.btn-block,
				.slick-carousel .slick-arrow,
				.product-block-list .compare:hover,
				.widget-mailchimp.default .btn, .widget-mailchimp.default .viewmore-products-btn,
				.viewmore-products-btn, .woocommerce .wishlist_table td.product-add-to-cart a, .woocommerce .return-to-shop .button, .woocommerce .track_order .button, .woocommerce #respond input#submit,
				.woocommerce div.product form.cart .button, .woocommerce div.product form.cart .added_to_cart,
				.add-to-cart-bottom-wrapper .cart .added_to_cart, .add-to-cart-bottom-wrapper .cart button.single_add_to_cart_button,.goal-loadmore-btn:hover, .goal-loadmore-btn:active,.woocommerce .checkout_coupon .button,.woocommerce .widget_price_filter .price_slider_amount .button, .goal-topcart .buttons .btn.btn-primary,
				.woocommerce .woocommerce-error .button, .woocommerce .woocommerce-message .button, .woocommerce .checkout_coupon .button, .woocommerce table.shop_table input.button:disabled, .woocommerce table.shop_table input.button,.woocommerce .cart_totals .wc-proceed-to-checkout .btn,.woocommerce input.button, .btn-theme
				{
					
					background-image: linear-gradient(to right, <?php echo esc_html( rashy_get_config('button_bg_color') ) ?> 0%, <?php echo esc_html( rashy_get_config('button_bg_color') ) ?> 100%) !important;
				}

				.goal-topcart .buttons .btn.checkout {
					background-color: <?php echo esc_html( rashy_get_config('button_bg_color') ) ?> !important;
				}

			<?php endif; ?>

			<?php if ( rashy_get_config('button_bg_hover_color') != "" ) : ?>
				.btn,
				.btn-theme-second:hover,.btn-block,
				.slick-carousel .slick-arrow,
				.product-block-list .compare:hover,
				.widget-mailchimp.default .btn, .widget-mailchimp.default .viewmore-products-btn,
				.viewmore-products-btn, .woocommerce .wishlist_table td.product-add-to-cart a, .woocommerce .return-to-shop .button, .woocommerce .track_order .button, .woocommerce #respond input#submit,
				.woocommerce div.product form.cart .button, .woocommerce div.product form.cart .added_to_cart,
				.add-to-cart-bottom-wrapper .cart .added_to_cart, .add-to-cart-bottom-wrapper .cart button.single_add_to_cart_button,.goal-loadmore-btn:hover, .goal-loadmore-btn:active,.woocommerce .checkout_coupon .button,.woocommerce .widget_price_filter .price_slider_amount .button, .goal-topcart .buttons .btn.btn-primary, 
				.woocommerce .woocommerce-error .button, .woocommerce .woocommerce-message .button, .woocommerce .checkout_coupon .button, .woocommerce table.shop_table input.button:disabled, .woocommerce table.shop_table input.button,.woocommerce .cart_totals .wc-proceed-to-checkout .btn,.woocommerce input.button:hover, .btn-theme
				{
					
					background-color: <?php echo esc_html( rashy_get_config('button_bg_hover_color') ) ?> !important;
				}
                .goal-topcart .buttons .btn.checkout {
					background-image: linear-gradient(to right, <?php echo esc_html( rashy_get_config('button_bg_hover_color') ) ?> 0%, <?php echo esc_html( rashy_get_config('button_bg_hover_color') ) ?> 100%) !important;
				}
			<?php endif; ?>


			<?php if ( rashy_get_config('button_arrow_color') != "" ) : ?>
				.btn-theme i {
					color: <?php echo esc_html( rashy_get_config('button_arrow_color') ) ?> !important;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('button_arrow_bg_color') != "" ) : ?>
				.btn-theme i {
					background-color: <?php echo esc_html( rashy_get_config('button_arrow_bg_color') ) ?> !important;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('button_arrow_hv_color') != "" ) : ?>
				.btn-theme:hover i {
					color: <?php echo esc_html( rashy_get_config('button_arrow_hv_color') ) ?> !important;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('button_arrow_bg_hover_color') != "" ) : ?>
				.btn-theme:hover i {
					background-color: <?php echo esc_html( rashy_get_config('button_arrow_bg_hover_color') ) ?> !important;
				}
			<?php endif; ?>

			
			/* seting button cart color */
			<?php if ( rashy_get_config('button_cart_color') != "" ) : ?>
				.product-block.grid .add-cart > .added_to_cart, 
				.product-block.grid .add-cart > .button,
				.post-layout .post-info a.readmore {
					color: <?php echo esc_html( rashy_get_config('button_cart_color') ) ?>;
				}
			<?php endif; ?>

            /* seting button arrow cart color */
			<?php if ( rashy_get_config('button_arrow_cart_color') != "" ) : ?>
				.product-block.grid .add-cart > .added_to_cart:not(.loading)::before, 
				.product-block.grid .add-cart > .button:not(.loading)::before,
				.product-block-list .add-cart a.button:not(.loading)::before, 
				.product-block-list .add-cart .added_to_cart:not(.loading)::before {
					color: <?php echo esc_html( rashy_get_config('button_arrow_cart_color') ) ?>;
				}
			<?php endif; ?>

			/* seting button cart background color */
			<?php if ( rashy_get_config('button_cart_bg_color') != "" ) : ?>
				.product-block.grid .add-cart > .button,
				.product-block-list .add-cart a.button,
				.product-block.grid .add-cart > .added_to_cart,
				.product-block-list .add-cart .added_to_cart,
				.product-block-list .add-cart a.added_to_cart,
				.post-layout .post-info a.readmore,
				.btn-theme {
					background-image: linear-gradient(to right, <?php echo esc_html( rashy_get_config('button_cart_bg_color') ) ?> 0%, <?php echo esc_html( rashy_get_config('button_cart_bg_color') ) ?> 100%);

				}
				
			<?php endif; ?>

			/* seting button arrow cart background color */
			<?php if ( rashy_get_config('button_arrow_cart_bg_color') != "" ) : ?>
				.product-block.grid .add-cart > .added_to_cart:not(.loading)::before, 
				.product-block.grid .add-cart > .button:not(.loading)::before,
				.product-block-list .add-cart a.button:not(.loading)::before, 
				.product-block-list .add-cart .added_to_cart:not(.loading)::before,
				.post-layout .post-info .readmore i {
					background-color: <?php echo esc_html( rashy_get_config('button_arrow_cart_bg_color') ) ?>;
				}
				
			<?php endif; ?>

            /* seting button cart hover color */
			<?php if ( rashy_get_config('button_cart_hv_color') != "" ) : ?>
				.product-block.grid .add-cart a.button:hover,
				.product-block.grid .add-cart .added_to_cart,
				.product-block-list .add-cart .added_to_cart,
				.product-block-list .add-cart .added_to_cart:hover, 
				.product-block-list .add-cart a.button:hover,
				.post-layout .post-info .readmore:hover{
					color: <?php echo esc_html( rashy_get_config('button_cart_hv_color') ) ?> ;
				}
			<?php endif; ?>



            /* seting button arrow cart hover color */
			<?php if ( rashy_get_config('button_arrow_cart_hv_color') != "" ) : ?>
				.product-block.grid .add-cart > .added_to_cart:hover:not(.loading)::before, 
				.product-block.grid .add-cart > .added_to_cart:active:not(.loading)::before,
				.product-block.grid .add-cart > .button:hover:not(.loading)::before, 
				.product-block.grid .add-cart > .button:active:not(.loading)::before,
				.product-block-list .add-cart a.button:hover:not(.loading)::before,
				.product-block-list .add-cart .added_to_cart:hover:not(.loading)::before,
				.post-layout .post-info .readmore i {
					color: <?php echo esc_html( rashy_get_config('button_arrow_cart_hv_color') ) ?>;
				}
			<?php endif; ?>

			
            /* seting button cart background hover color */
			<?php if ( rashy_get_config('button_cart_bg_hover_color') != "" ) : ?>
				.product-block.grid .add-cart a.button:hover,
				.product-block.grid .add-cart .added_to_cart,
				.product-block-list .add-cart .added_to_cart,
				.product-block-list .add-cart .added_to_cart:hover, 
				.product-block-list .add-cart a.button:hover,
				.post-layout .post-info .readmore:hover {
					background-color: <?php echo esc_html( rashy_get_config('button_cart_bg_hover_color') ) ?> ;
				}
				.product-block.grid .add-cart > .button,
				.product-block-list .add-cart a.button,
				.post-layout .post-info .readmore,
				.btn-theme {
					background-color: <?php echo esc_html( rashy_get_config('button_cart_bg_hover_color') ) ?> ;
					
				}
				
			<?php endif; ?>


			 /* seting button arrow cart hover background color */
			<?php if ( rashy_get_config('button_arrow_cart_bg_hover_color') != "" ) : ?>
				.product-block.grid .add-cart > .added_to_cart:hover:not(.loading)::before, 
				.product-block.grid .add-cart > .added_to_cart:active:not(.loading)::before,
				.product-block.grid .add-cart > .button:hover:not(.loading)::before, 
				.product-block.grid .add-cart > .button:active:not(.loading)::before,
				.product-block-list .add-cart a.button:hover:not(.loading)::before,
				.product-block-list .add-cart .added_to_cart:hover:not(.loading)::before,
				.post-layout .post-info .readmore:hover i {
					background-color: <?php echo esc_html( rashy_get_config('button_arrow_cart_bg_hover_color') ) ?>;
				}
			<?php endif; ?>


			<?php if ( rashy_get_config('info_button_color') != "" ) : ?>
				.product-block.grid .add-cart a.button, 
				.product-block.grid .add-cart a.added_to_cart,
				.product-block.grid .add-cart > .added_to_cart:not(.loading)::before, .product-block.grid .add-cart > .button:not(.loading)::before,
				.woocommerce a.button,
				.product-block.grid .woosw-btn, 
				.product-block.grid .woosc-btn, 
				.product-block-list .woosw-btn:before,
				.product-block-list .woosc-btn:before,
				.product-block.grid .view .quickview,
				.product-block-list .view .quickview{
					color: <?php echo esc_html( rashy_get_config('info_button_color') ) ?> ;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('info_button_hv_color') != "" ) : ?>
				.product-block.grid .woosw-btn:hover, 
				.product-block.grid .woosw-btn.woosw-added:hover, 
				.product-block.grid .woosc-btn:hover, 
				.product-block-list .woosw-btn:hover:before,
				.product-block-list .woosc-btn:hover:before,
				.product-block.grid .woosc-btn.woosc-added:hover,
				.product-block.grid .view .quickview:active, 
				.product-block.grid .view .quickview:hover i,
				.product-block-list .view .quickview:hover i,
				.product-block.grid .add-cart .added_to_cart:hover:before,
				.product-block.grid .add-cart .button:hover:before {
					color: <?php echo esc_html( rashy_get_config('info_button_hv_color') ) ?> ;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('info_button_active_color') != "" ) : ?>
				 .product-block.grid .woosw-btn.woosw-added, 
				 .product-block.grid .woosc-btn.woosc-added,
				 .product-block.grid .add-cart .added_to_cart:before {
					color: <?php echo esc_html( rashy_get_config('info_button_active_color') ) ?> ;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('info_button_bg_color') != "" ) : ?>
				.product-block.grid .woosw-btn, 
				.product-block.grid .woosc-btn, 
				.product-block.grid .view .quickview,
				.product-block-list .view .quickview,
				.product-block-list .woosw-btn, 
				.product-block-list .woosc-btn,
				.product-block.grid .add-cart .added_to_cart,
				.product-block.grid .add-cart .button,
				.product-block.grid .add-cart .added_to_cart:before  {
					background-color: <?php echo esc_html( rashy_get_config('info_button_bg_color') ) ?> ;
				}
			<?php endif; ?>

			<?php if ( rashy_get_config('info_button_hv_bg_color') != "" ) : ?>
				.product-block.grid .woosw-btn:hover,
				.product-block.grid .woosc-btn:hover, 
				.product-block.grid .view .quickview:hover,
				.product-block-list .view .quickview:hover,
				.product-block-list .woosw-btn:hover, 
				.product-block-list .woosc-btn:hover,
				.product-block.grid .add-cart .button:hover,
				.product-block.grid .add-cart .added_to_cart:hover:before,
				.product-block.grid .add-cart .button:hover:before {
					background-color: <?php echo esc_html( rashy_get_config('info_button_hv_bg_color') ) ?> ;
				}
			<?php endif; ?>
	<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}
		
		return implode($new_lines);
	}
}