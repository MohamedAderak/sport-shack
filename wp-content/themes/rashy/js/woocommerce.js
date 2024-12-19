(function($) {
    "use strict";
    
    $.extend($.goalThemeCore, {
        /**
         *  Initialize scripts
         */
        woo_init: function() {
            var self = this;

            self.loginRegister();

            self.cartOffcanvas();

            self.addToCartAction();

            self.getProductAjax();

            self.searchProduct();

            self.productDetail();
            
            self.initQuickview();

            self.initSwatches();

            self.wishlistInit();

            self.initFilter();

            self.initSearchVendor();

            self.searchHeader();

            $( 'body' ).on( 'found_variation', function( event, variation ) {
                self.variationsImageUpdate(variation);
            });

            $( 'body' ).on( 'reset_image', function( event, variation ) {
                self.variationsImageUpdate(variation);
            });
            if ( $.isFunction( $.fn.select2 ) ) {
                $('.goal-search-form .select-category select').select2();
            }
            $(document).on('click', '.goal-topcart a.mini-cart', function(){
                $('.goal-topcart .cart_list').perfectScrollbar();
            });

            $(document).on('click', '.filter-btn', function(){
                $(this).closest('.filter-btn-wrapper').find('.shop-filter-sidebar-wrapper').addClass('active');
                $(this).closest('.filter-btn-wrapper').find('.shop-filter-sidebar-overlay').addClass('active');
                $("body").css("overflow-y", "hidden");
            });
            $(document).on('click', '.close-filter', function(){
                $(this).closest('.filter-btn-wrapper').find('.shop-filter-sidebar-wrapper').removeClass('active');
                $(this).closest('.filter-btn-wrapper').find('.shop-filter-sidebar-overlay').removeClass('active');
                $("body").css("overflow-y", "initial");
            });
            $('.shop-filter-sidebar-overlay').on('click', function(){
                $(this).closest('.filter-btn-wrapper').find('.shop-filter-sidebar-wrapper').removeClass('active');
                $(this).removeClass('active');
                $("body").css("overflow-y", "initial");
            });

            setTimeout(function(){
                $('.top-categories-inner .list-category-products').perfectScrollbar();
            }, 100);

            // categories
            $('.widget_product_categories ul li.cat-item').each(function(){
                if ($(this).find('ul.children').length > 0) {
                    $(this).addClass('noactive');
                    $(this).prepend('<span class="closed">+</span>');
                }
                $(this).find('ul.children').hide();
            });
            $( "body" ).on( "click", '.widget_product_categories ul li.cat-item.noactive', function(){
                $(this).find('ul.children').first().slideDown();
                $(this).find('span').remove(".closed");
                $(this).prepend('<span class="opened">-</span>');
                $(this).addClass('active');
            });
            $( "body" ).on( "click", '.widget_product_categories ul li.cat-item.active', function(){
                $(this).find('ul.children').first().slideUp();
                $(this).find('span').remove(".opened");
                $(this).prepend('<span class="closed">+</span>');
                $(this).removeClass('active');
                $(this).addClass('noactive');
            });

            $('.wp-block-woocommerce-product-categories ul li.wc-block-product-categories-list-item').each(function(){
                if ($(this).find('ul.wc-block-product-categories-list').length > 0) {
                    $(this).addClass('noactive');
                    $(this).prepend('<span class="closed">+</span>');
                }
                $(this).find('ul.wc-block-product-categories-list').hide();
            });
            $( "body" ).on( "click", '.wp-block-woocommerce-product-categories ul li.wc-block-product-categories-list-item.noactive', function(){
                $(this).find('ul.wc-block-product-categories-list').first().slideDown();
                $(this).find('span').remove(".closed");
                $(this).prepend('<span class="opened">-</span>');
                $(this).addClass('active');
            });
            $( "body" ).on( "click", '.wp-block-woocommerce-product-categories ul li.wc-block-product-categories-list-item.active', function(){
                $(this).find('ul.wc-block-product-categories-list').first().slideUp();
                $(this).find('span').remove(".opened");
                $(this).prepend('<span class="closed">+</span>');
                $(this).removeClass('active');
                $(this).addClass('noactive'); 
            });

            $('.widget_categories ul li.cat-item').each(function(){
                if ($(this).find('ul.children').length > 0) {
                    $(this).addClass('noactive');
                    $(this).prepend('<span class="closed">+</span>');
                }
                $(this).find('ul.children').hide();
            });
            $( "body" ).on( "click", '.widget_categories ul li.cat-item.noactive', function(){
                $(this).find('ul.children').first().slideDown();
                $(this).find('span').remove(".closed");
                $(this).prepend('<span class="opened">-</span>');
                $(this).addClass('active');
            });
            $( "body" ).on( "click", '.widget_categories ul li.cat-item.active', function(){
                $(this).find('ul.children').first().slideUp();
                $(this).find('span').remove(".opened");
                $(this).prepend('<span class="closed">+</span>');
                $(this).removeClass('active');
                $(this).addClass('noactive');
            });

            $('.widget_nav_menu  ul.menu li.menu-item').each(function(){
                if ($(this).find('ul.sub-menu').length > 0) {
                    $(this).addClass('noactive');
                    $(this).prepend('<span class="closed">+</span>');
                }
                $(this).find('ul.sub-menu').hide();
            });
            $( "body" ).on( "click", '.widget_nav_menu  ul.menu li.menu-item.noactive', function(){
                $(this).find('ul.sub-menu').first().slideDown();
                $(this).find('span').remove(".closed");
                $(this).prepend('<span class="opened">-</span>');
                $(this).addClass('active');
            });
            $( "body" ).on( "click", '.widget_nav_menu  ul.menu li.menu-item.active', function(){
                $(this).find('ul.sub-menu').first().slideUp();
                $(this).find('span').remove(".opened");
                $(this).prepend('<span class="closed">+</span>');
                $(this).removeClass('active');
                $(this).addClass('noactive');
            });

             $('.wp-block-group__inner-container ul li.cat-item').each(function(){
                if ($(this).find('ul.children').length > 0) {
                    $(this).addClass('noactive');
                    $(this).prepend('<span class="closed">+</span>');
                }
                $(this).find('ul.children').hide();
            });
            $( "body" ).on( "click", '.wp-block-group__inner-container ul li.cat-item.noactive', function(){
                $(this).find('ul.children').first().slideDown();
                $(this).find('span').remove(".closed");
                $(this).prepend('<span class="opened">-</span>');
                $(this).addClass('active');
            });
            $( "body" ).on( "click", '.wp-block-group__inner-container ul li.cat-item.active', function(){
                $(this).find('ul.children').first().slideUp();
                $(this).find('span').remove(".opened");
                $(this).prepend('<span class="closed">+</span>');
                $(this).removeClass('active');
                $(this).addClass('noactive');
            });


            
           
        },
        wishlistInit: function() {
            $( 'body' ).on( 'added_to_wishlist', function( event, variation ) {
                $('.wishlist-icon .count').each(function(){
                    var count = $(this).text();
                    count = parseInt(count) + 1;
                    $(this).text(count);
                });
                    
            });
            $('body').on('removed_from_wishlist', function( event, variation ) {
                if ( $('.wishlist-icon .count').length > 0 ) {
                    $('.wishlist-icon .count').each(function(){
                        var count = $(this).text();
                        count = parseInt(count) - 1;
                        $(this).text(count);
                    });
                }
            });
            $('body').on('woosw_change_count', function( event, variation ) {
                if ( $('.count.woosw-custom-menu-item').length > 0 ) {
                    $('.count.woosw-custom-menu-item').each(function(){
                        $(this).text(variation);
                    });
                }
            });
        },

        searchHeader: function() {
            $('.goal-search-form').each(function(){
                var $form_container = $(this);
                $form_container.find('.show-search-header').on('click', function(e){
                    e.preventDefault();
                    
                    if ( $form_container.find('.goal-search-form-inner').hasClass('active') ) {
                        $form_container.find('.goal-search-form-inner').removeClass('active');
                        $form_container.find('.overlay-search-header').removeClass('active');
                        $("body").removeClass('show-header-static');
                    } else {
                        $form_container.find('.goal-search-form-inner').addClass('active');
                        $form_container.find('.overlay-search-header').addClass('active');
                        $("body").addClass('show-header-static');
                    }
                    $(this).find('i').toggleClass("ti-search ti-close");
                });

                $form_container.find('.overlay-search-header').on('click', function(e){
                    $form_container.find('.goal-search-form-inner').removeClass('active');
                    $form_container.find('.overlay-search-header').removeClass('active');
                    $("body").toggleClass('show-header-static');
                    $form_container.find('.show-search-header i').toggleClass("ti-search ti-close");
                });
            });
        },

        cartOffcanvas: function() {
            $('.mini-cart.offcanvas').on('click', function(e){
                e.preventDefault();
                if ( $('.offcanvas-content').hasClass('active') ) {
                    $('.offcanvas-content').removeClass('active');
                    $('.overlay-offcanvas-content').removeClass('active');
                } else {
                    $('.offcanvas-content').addClass('active');
                    $('.overlay-offcanvas-content').addClass('active');
                }
            });
            $('.overlay-offcanvas-content, .close-cart, .widget_shopping_cart_heading').on('click', function(){
                $('.offcanvas-content').removeClass('active');
                $('.overlay-offcanvas-content').removeClass('active');
            });
        },
        addToCartAction: function() {
            jQuery('body').bind('added_to_cart', function( fragments, cart_hash ){
                $('.offcanvas-content').addClass('active');
                $('.overlay-offcanvas-content').addClass('active');
            });
        },
         getProductAjax: function() {
            var self = this;
            $('[data-load="ajax"] a').on('click', function(e){
                e.preventDefault();
                var $href = $(this).attr('href');

                $(this).parent().parent().find('li').removeClass('active');
                $(this).parent().addClass('active');

                var main = $($href);
                if ( main.length > 0 ) {
                    if ( main.data('loaded') == false ) {
                        main.parent().addClass('loading');
                        main.data('loaded', 'true');

                        $.ajax({
                            url: rashy_woo_opts.ajaxurl.toString().replace( '%%endpoint%%', 'rashy_ajax_get_products' ),
                            type:'POST',
                            dataType: 'html',
                            data:  {
                                settings: main.data('settings'),
                                tab: main.data('tab')
                            }
                        }).done(function(reponse) {
                            main.find('.tab-content-products').html( reponse );
                            main.parent().removeClass('loading');
                            main.parent().find('.tab-pane').removeClass('active');
                            main.addClass('active');

                            main.find('[data-time="timmer"]').each(function(index, el) {
                                var $this = $(this);
                                var $date = $this.data('date').split("-");
                                var $format = "<div class=\"times\"><div class=\"day\">%%D%% "+ rashy_countdown_opts.days +"</div><div class=\"hours\">%%H%% "+ rashy_countdown_opts.hours +"</div><div class=\"minutes\">%%M%% "+ rashy_countdown_opts.mins +"</div><div class=\"seconds\">%%S%% "+ rashy_countdown_opts.secs +"</div></div>";
                                if ( $(this).data('format')) {
                                    $format = $(this).data('format');
                                }
                                $this.goalCountDown({
                                    TargetDate:$date[0]+"/"+$date[1]+"/"+$date[2]+" "+$date[3]+":"+$date[4]+":"+$date[5],
                                    DisplayFormat: $format,
                                    FinishMessage: "",
                                });
                            });

                            if ( main.find('.slick-carousel') ) {
                                self.initSlick(main.find('.slick-carousel'));
                            }
                            self.layzyLoadImage();
                        });
                        return true;
                    } else {
                        main.parent().removeClass('loading');
                        main.parent().find('.tab-pane').removeClass('active');
                        main.addClass('active');

                        var $slick = $("[data-carousel=slick]", main);
                        if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                            $slick.slick('refresh');
                        }
                        self.layzyLoadImage();
                    }
                }
            });
        },
        loginRegister: function(){
            var self = this;
            $('body').on( 'click', '.register-login-action', function(e){
                e.preventDefault();
                var href = $(this).attr('href');
                $(this).closest('.user').find('.register_login_wrapper').removeClass('active');
                $(href).addClass('active');

                if ( $(this).hasClass('creat-account') ) {
                    var $cookie_val = '#customer_register';
                } else {
                    var $cookie_val = '#customer_login';
                }
                self.setCookie('rashy_login_register', $cookie_val, 1);
            } );
        },
        searchProduct: function(){
            if ( $('.goal-autocompleate-input').length ) {
                $('.goal-autocompleate-input').typeahead({
                        hint: true,
                        highlight: true,
                        minLength : 3,
                    }, {
                        limit: 10,
                        name: 'search',
                        source: function (query, processSync, processAsync) {
                            processSync([rashy_woo_opts.empty_msg]);
                            $('.twitter-typeahead').addClass('loading');
                            return $.ajax({
                                url: rashy_woo_opts.ajaxurl.toString().replace( '%%endpoint%%', 'rashy_autocomplete_search' ),
                                type: 'GET',
                                data: {
                                    's': query,
                                    'category': $('.goal-search-form .dropdown_product_cat').val(),
                                    'security': rashy_woo_opts.ajax_nonce
                                },
                                dataType: 'json',
                                success: function (json) {
                                    $('.twitter-typeahead').removeClass('loading');
                                    return processAsync(json);
                                }
                            });
                        },
                        templates: {
                            empty : [
                                '<div class="empty-message">',
                                rashy_woo_opts.empty_msg,
                                '</div>'
                            ].join('\n'),
                            suggestion: function(data) {
                                return '<div class="autocomplete-list-item"><a href="'+ data.url +'" class="media autocompleate-media"><div class="media-left media-middle"><img src="'+ data.image +'" class="media-object" height="100" width="100"></div><div class="media-body media-middle"><div class="product-cat">'+ data.category +'</div><h3 class="name-product">'+ data.title +'</h3><div class="price">'+ data.price +'</div></div></a></div>';
                            }
                        },
                    }
                );
                $('.goal-autocompleate-input').on('typeahead:selected', function (e, data) {
                    e.preventDefault();
                    setTimeout(function(){
                        $('.goal-autocompleate-input').val(data.title);    
                    }, 5);
                    
                    return false;
                });
            }
        },
        productDetail: function(){
            // review click link
            $('.woocommerce-review-link').on('click', function(){
                $('.woocommerce-tabs a[href="#tabs-list-reviews"]').trigger('click');
                $('html, body').animate({
                    scrollTop: $("#reviews").offset().top
                }, 1000);
                return false;
            });

            $( document.body )
            .off( 'click', '.woocommerce-tabs.tabs-v2 .tab-item a.tab-header-title' )
            .on( 'click', '.woocommerce-tabs.tabs-v2 .tab-item a.tab-header-title', function( event ) {
                event.preventDefault();

                $(this).closest('.tab-item').find('.tabs-content-wrapper').addClass('active');
                $(this).closest('.woocommerce-tabs').find('.overlay-tabs').addClass('active');
            } );

            // $('.woocommerce-tabs.tabs-v2 .tab-item a.tab-header-title').on('click', function(){
            //     $(this).closest('.tab-item').find('.tabs-content-wrapper').addClass('active');
            //     $(this).closest('.woocommerce-tabs').find('.overlay-tabs').addClass('active');
            // });
            $('.overlay-tabs, .close-tab').on('click', function(){
                $('.woocommerce-tabs.tabs-v2 .tabs-content-wrapper').removeClass('active');
                $('.overlay-tabs').removeClass('active');
            });


            // Remove active tab
            $( 'body' ).on( 'init', '.goal-wc-tabs', function() {
                var hash  = window.location.hash;
                var url   = window.location.href;
                var $tabs = $( this );

                if ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' || hash === '#tab-reviews' ) {
                    $tabs.find( '.reviews_tab a' ).trigger('click');
                } else if ( url.indexOf( 'comment-page-' ) > 0 || url.indexOf( 'cpage=' ) > 0 ) {
                    $tabs.find( '.reviews_tab a' ).trigger('click');
                } else if ( hash === '#tab-additional_information' ) {
                    $tabs.find( '.additional_information_tab a' ).trigger('click');
                }
            } );


            $('.delivery-shipping-info .item .item-btn').magnificPopup({
                mainClass: 'goal-mfp-zoom-in login-popup',
                type:'inline',
                midClick: true
            });

            var main_sticky = $('.add-to-cart-bottom-wrapper');
            if ( main_sticky.length > 0 && $('.details-product form.cart').length > 0 ){
                setTimeout(function(){
                    var height = main_sticky.outerHeight();
                    $('body.sticky-add-to-cart').css({
                        'margin-bottom': height + 'px'
                    });
                    var Goal_Add_To_Cart_Fixed = function(){
                        "use strict";
                        var fromBottom = $('.details-product form.cart').offset().top + $('.details-product form.cart').outerHeight();
                        if( $(document).scrollTop() > fromBottom ){
                            main_sticky.addClass('sticky');
                        } else {
                            main_sticky.removeClass('sticky');
                        }
                    }
                    if ($(window).width() > 991) {
                        $(window).scroll(function(event) {
                            Goal_Add_To_Cart_Fixed();
                        });
                        Goal_Add_To_Cart_Fixed();
                    }
                    
                }, 100);
            }

            if ($('.details-product .sticky-this').length > 0 ) {
                if ($(window).width() > 991) {
                    $('.details-product .sticky-this').stick_in_parent({
                        parent: ".product-v-wrapper"
                    });
                }
            }

        },
        initSearchVendor: function(){
            $('.btn-showserach-dokan').on('click', function(){
                $('.dokan-seller-search-form').toggleClass('active');
            });
            $('.tabs-v1 .tabs-list').perfectScrollbar();
        },
        initQuickview: function(){
            var self = this;
            $('body').on('click', 'a.quickview', function (e) {
                e.preventDefault();
                var $self = $(this);
                $self.addClass('loading');
                var product_id = $(this).data('product_id');
                var url = rashy_woo_opts.ajaxurl.toString().replace( '%%endpoint%%', 'rashy_quickview_product' ) + '&product_id=' + product_id;
                
                $.get(url,function(data,status){
                    $.magnificPopup.open({
                        mainClass: 'goal-mfp-zoom-in goal-quickview',
                        items : {
                            src : data,
                            type: 'inline'
                        },
                        callbacks: {
                            open: function() {
                                // variation
                                
                                if ( $('.goal-quickview').find('.slick-carousel').length ) {
                                    var $slick = $('.goal-quickview').find('.slick-carousel');
                                    if ( $slick.hasClass('slick-initialized')) {
                                        $slick.slick('refresh');
                                    } else {
                                        self.initSlick($slick);
                                    }
                                }
                                setTimeout(function(){
                                    self.layzyLoadImage();

                                    if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
                                        $( '.variations_form' ).each( function() {
                                            $( this ).wc_variation_form().find('.variations select:eq(0)').trigger('change');
                                        });
                                    }
                                    if ( $.isFunction( $.fn.tawcvs_variation_swatches_form ) ) {
                                        $( '.variations_form' ).tawcvs_variation_swatches_form();
                                    }
                                }, 200);
                                
                                self.refresh_quantity_increments();

                                // setTimeout(function(){
                                //     var $max_heigh = $('.goal-mfp-zoom-in.goal-quickview .gallery-wrapper').outerHeight();
                                //     $('.goal-mfp-zoom-in.goal-quickview .information').css({'height': $max_heigh});
                                //     $('.goal-mfp-zoom-in.goal-quickview .information').perfectScrollbar();
                                // }, 100);
                            }
                        }
                    });
                    
                    $self.removeClass('loading');
                });
            });
        },
        
        initSwatches: function() {
            $( 'body' ).on( 'click', '.swatches-wrapper li a', function() {
                var $parent = $(this).closest('.product-block');
                var $image = $parent.find('.image .product-image img');
                
                $('.swatches-wrapper li a').removeClass('active');
                if ( $(this).attr( 'data-image_src' ) ) {
                    $image.attr('src', $(this).attr( 'data-image_src' ) );
                    $(this).addClass('active');
                }
                if ( $(this).attr( 'data-image_srcset' ) ) {
                    $image.attr('srcset', $(this).attr( 'data-image_srcset' ) );
                }
                if ( $(this).attr( 'data-image_sizes') ) {
                    $image.attr('sizes', $(this).attr( 'data-image_sizes' ) );
                }
            });
        },
        variationsImageUpdate: function( variation ) {
            var $form             = $('.variations_form'),
                $product          = $form.closest( '.product' ),
                $product_gallery  = $product.find( '.goal-woocommerce-product-gallery-wrapper' ),
                $gallery_img      = $product.find( '.goal-woocommerce-product-gallery-thumbs img:eq(0)' ),
                $product_img_wrap = $product_gallery.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' ).eq( 0 ),
                $product_img      = $product_img_wrap.find( '.wp-post-image' ),
                $product_link     = $product_img_wrap.find( 'a' ).eq( 0 );


            if ( variation && variation.image && variation.image.src && variation.image.src.length > 1 ) {
                
                if ( $( '.goal-woocommerce-product-gallery-thumbs img[src="' + variation.image.thumb_src + '"]' ).length > 0 ) {
                    $( '.goal-woocommerce-product-gallery-thumbs img[src="' + variation.image.thumb_src + '"]' ).trigger( 'click' );
                    $form.attr( 'current-image', variation.image_id );
                    return;
                } else {
                    $product_img.wc_set_variation_attr( 'src', variation.image.src );
                    $product_img.wc_set_variation_attr( 'height', variation.image.src_h );
                    $product_img.wc_set_variation_attr( 'width', variation.image.src_w );
                    $product_img.wc_set_variation_attr( 'srcset', variation.image.srcset );
                    $product_img.wc_set_variation_attr( 'sizes', variation.image.sizes );
                    $product_img.wc_set_variation_attr( 'title', variation.image.title );
                    $product_img.wc_set_variation_attr( 'alt', variation.image.alt );
                    $product_img.wc_set_variation_attr( 'data-src', variation.image.full_src );
                    $product_img.wc_set_variation_attr( 'data-large_image', variation.image.full_src );
                    $product_img.wc_set_variation_attr( 'data-large_image_width', variation.image.full_src_w );
                    $product_img.wc_set_variation_attr( 'data-large_image_height', variation.image.full_src_h );
                    $product_img_wrap.wc_set_variation_attr( 'data-thumb', variation.image.src );
                    $gallery_img.wc_set_variation_attr( 'src', variation.image.thumb_src );
                    $gallery_img.wc_set_variation_attr( 'srcset', variation.image.thumb_srcset );

                    $product_link.wc_set_variation_attr( 'href', variation.image.full_src );
                    $gallery_img.removeAttr('srcset');
                    if ( $('.goal-woocommerce-product-gallery').hasClass('slick-carousel') ) {
                        $('.goal-woocommerce-product-gallery').slick('slickGoTo', 0);
                    }
                }
            } else {
                $product_img.wc_reset_variation_attr( 'src' );
                $product_img.wc_reset_variation_attr( 'width' );
                $product_img.wc_reset_variation_attr( 'height' );
                $product_img.wc_reset_variation_attr( 'srcset' );
                $product_img.wc_reset_variation_attr( 'sizes' );
                $product_img.wc_reset_variation_attr( 'title' );
                $product_img.wc_reset_variation_attr( 'alt' );
                $product_img.wc_reset_variation_attr( 'data-src' );
                $product_img.wc_reset_variation_attr( 'data-large_image' );
                $product_img.wc_reset_variation_attr( 'data-large_image_width' );
                $product_img.wc_reset_variation_attr( 'data-large_image_height' );
                $product_img_wrap.wc_reset_variation_attr( 'data-thumb' );
                $gallery_img.wc_reset_variation_attr( 'src' );
                $product_link.wc_reset_variation_attr( 'href' );
            }

            window.setTimeout( function() {
                $( window ).trigger( 'resize' );
                $form.wc_maybe_trigger_slide_position_reset( variation );
                $product_gallery.trigger( 'woocommerce_gallery_init_zoom' );
            }, 20 );
        },
        initFilter: function() {
            var self = this;

            $('body').on('click', '.show-filter', function(e){
                e.preventDefault();
                $(".shop-top-sidebar-wrapper").toggle(300);
            });

            self.filterScrollbarsInit();
            $('body').on('click', '.shop-top-categories a', function(e) {
                e.preventDefault();
                self.shopGetPage($(this).attr('href'));
            });

            $('body').on('click', '.widget_product_categories a', function(e) {
                e.preventDefault();
                self.shopGetPage($(this).attr('href'));
            });

           

            $('body').on('click', '.woocommerce-widget-layered-nav-list a', function(e) {
                e.preventDefault();
                self.shopGetPage($(this).attr('href'));
            });
            $('body').on('click', '.goal-price-filter a', function(e) {
                e.preventDefault();
                self.shopGetPage($(this).attr('href'));
            });
            $('body').on('click', '.goal-product-sorting a', function(e) {
                e.preventDefault();
                self.shopGetPage($(this).attr('href'));
            });
            $('body').on('click', '.widget_orderby a', function(e) {
                e.preventDefault();
                self.shopGetPage($(this).attr('href'), false, true);
            });
            $('body').on('click', '.widget_product_tag_cloud a', function(e) {
                e.preventDefault();
                self.shopGetPage($(this).attr('href'), false, true);
            });

            $('body').on('change', '.orderby-wrapper select', function(){
                $('.orderby-wrapper form.woocommerce-ordering').trigger('submit');
            });

            $('body').on('submit', '.orderby-wrapper form.woocommerce-ordering', function (e) {
                e.preventDefault();
                var url = $(this).attr('action');

                var formData = $(this).find(":input").filter(function(index, element) {
                        return $(element).val() != '';
                    }).serialize();

                if( url.indexOf('?') != -1 ) {
                    url = url + '&' + formData;
                } else{
                    url = url + '?' + formData;
                }
                
                self.shopGetPage( url );
                return false;
            });

            $('body').on('click', '.shop-filter-top-wrapper aside .widget-title', function(){
                $(this).closest('aside').find(' .widget-title ').toggleClass('active');
                $(this).closest('aside').find(' .widget-title + * ').slideToggle();
            });


            // ajax pagination
            if ( $('.ajax-pagination').length ) {
                self.ajaxPaginationLoad();
            }
        },
        shopGetPage: function(pageUrl, isBackButton, isProductTag){
            var self = this;
            if (self.shopAjax) { return false; }
            
            if (pageUrl) {
                // Remove any visible shop notices
                //self.shopRemoveNotices();                                             
                
                // Set current shop URL (used to reset search and product-tag AJAX results)
                self.shopSetCurrentUrl(isProductTag);
                
                // Show 'loader' overlay
                self.shopShowLoader();
                
                // Make sure the URL has a trailing-slash before query args (301 redirect fix)
                pageUrl = pageUrl.replace(/\/?(\?|#|$)/, '/$1');
                
                // Set browser history "pushState" (if not back button "popstate" event)
                if (!isBackButton) {
                    self.setPushState(pageUrl);
                }
                
                self.shopAjax = $.ajax({
                    url: pageUrl,
                    data: {
                        'load_type': 'full',
                        '_preset': rashy_woo_opts._preset
                    },
                    dataType: 'html',
                    cache: false,
                    headers: {'cache-control': 'no-cache'},
                    
                    method: 'POST', // Note: Using "POST" method for the Ajax request to avoid "load_type" query-string in pagination links
                    
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Goal: AJAX error - shopGetPage() - ' + errorThrown);
                        
                        // Hide 'loader' overlay (after scroll animation)
                        self.shopHideLoader();
                        
                        self.shopAjax = false;
                    },
                    success: function(response) {
                        // Update shop content
                        self.shopUpdateContent(response);
                        
                        self.shopAjax = false;
                    }
                });
                
            }
        },
        shopHideLoader: function(){
            $('body').find('#goal-shop-products-wrapper').removeClass('loading');
        },
        shopShowLoader: function(){
            $('body').find('#goal-shop-products-wrapper').addClass('loading');
        },
        setPushState: function(pageUrl) {
            window.history.pushState({goalShop: true}, '', pageUrl);
        },
        shopSetCurrentUrl: function(isProductTag) {
            var self = this;
            
            // Exclude product-tag page URL's
            if (!self.isProductTagUrl) {
                // Set current page URL
                self.searchAndTagsResetURL = window.location.href;
            }
            
            // Is the current URL a product-tag URL?
            self.isProductTagUrl = (isProductTag) ? true : false;
        },
        /**
         *  Shop: Update shop content with AJAX HTML
         */
        shopUpdateContent: function(ajaxHTML) {
            var self = this,
                $ajaxHTML = $('<div>' + ajaxHTML + '</div>'); // Wrap the returned HTML string in a dummy 'div' element we can get the elements
            
            // Page title - wp_title()
            var wpTitle = $ajaxHTML.find('#goal-wp-title').text();
            if (wpTitle.length) {
                // Update document/page title
                document.title = wpTitle;
            }
            
            // Extract elements
            var $categories = $ajaxHTML.find('.shop-top-categories'),
                $sidebar = $ajaxHTML.find('.shop-top-sidebar-wrapper'),
                $sidebar_left = $ajaxHTML.find('.shop-sidebar-left-wrapper'),
                $sidebar_right = $ajaxHTML.find('.shop-sidebar-right-wrapper'),
                $shop = $ajaxHTML.find('#goal-shop-products-wrapper');

            // Prepare/replace categories
            if ($categories.length) { 
                var $shopCategories = $('.shop-top-categories');
                
                $shopCategories.replaceWith($categories); 
            }

            // Prepare/replace sidebar filters
            if ($sidebar_left.length) {
                var $shopSidebar = $('.shop-sidebar-left-wrapper');
                $shopSidebar.replaceWith($sidebar_left);
                self.filterScrollbarsInit();
            }

            if ($sidebar_right.length) {
                var $shopSidebar = $('.shop-sidebar-right-wrapper');
                $shopSidebar.replaceWith($sidebar_right);
                self.filterScrollbarsInit();
            }
            
            // Replace shop
            if ($shop.length) {
                $('#goal-shop-products-wrapper').replaceWith($shop);
            }

            $("body").css("overflow-y", "initial");
            
            // Load images (init Unveil)
            self.layzyLoadImage();
            // Isoto Load
            self.initIsotope();
            // paging
            self.ajaxPaginationLoad();

            setTimeout(function() {
                // Hide 'loader' overlay (after scroll animation)
                self.shopHideLoader();
            }, 100);
        },
        filterScrollbarsInit: function() {
            $('.goal-woocommerce-widget-layered-nav .wrapper-limit').perfectScrollbar();
            $('.goal-widget_price_filter .wrapper-limit').perfectScrollbar();
            $('.goal_widget_product_sorting .wrapper-limit').perfectScrollbar();
            $('.widget_product_tag_cloud .tagcloud').perfectScrollbar();
        },
        /**
         *  Shop: Initialize infinite load
         */
        ajaxPaginationLoad: function() {
            var self = this,
                $infloadControls = $('.ajax-pagination'),                   
                nextPageUrl;
            
            // Used to check if "infload" needs to be initialized after Ajax page load
            self.shopInfLoadBound = true;
            
            
            self.infloadScroll = ($infloadControls.hasClass('infinite-action')) ? true : false;
            
            if (self.infloadScroll) {
                self.infscrollLock = false;
                
                var pxFromWindowBottomToBottom,
                    pxFromMenuToBottom = Math.round($(document).height() - $infloadControls.offset().top);
                    //bufferPx = 0;
                
                /* Bind: Window resize event to re-calculate the 'pxFromMenuToBottom' value (so the items load at the correct scroll-position) */
                var to = null;
                $(window).resize(function() {
                    if (to) { clearTimeout(to); }
                    to = setTimeout(function() {
                        pxFromMenuToBottom = Math.round($(document).height() - $infloadControls.offset().top);
                    }, 100);
                });
                
                $(window).scroll(function(){
                    if (self.infscrollLock) {
                        return;
                    }
                    
                    pxFromWindowBottomToBottom = 0 + $(document).height() - ($(window).scrollTop()) - $(window).height();
                    
                    // If distance remaining in the scroll (including buffer) is less than the pagination element to bottom:
                    if ((pxFromWindowBottomToBottom/* - bufferPx*/) < pxFromMenuToBottom) {
                        self.ajaxPaginationGet();
                    }
                });
            } else {
                var $productsWrap = $('body');
                
                /* Bind: "Load" button */
                $productsWrap.on('click', '#goal-shop-products-wrapper .goal-loadmore-btn', function(e) {
                    e.preventDefault();
                    self.ajaxPaginationGet();
                });
                
            }
            
            if (self.infloadScroll) {
                $(window).trigger('scroll'); // Trigger scroll in case the pagination element (+buffer) is above the window bottom
            }
        },
        /**
         *  Shop: AJAX load next page
         */
        ajaxPaginationGet: function() {
            var self = this;
            
            if (self.shopAjax) return false;
            
            // Remove any visible shop notices
            //self.shopRemoveNotices();
            
            // Get elements (these can be replaced with AJAX, don't pre-cache)
            var $nextPageLink = $('.goal-pagination-next-link').find('a'),
                $infloadControls = $('.ajax-pagination'),
                nextPageUrl = $nextPageLink.attr('href');
            
            if (nextPageUrl) {
                //nextPageUrl = self.updateUrlParameter(nextPageUrl, 'load_type', 'products');
                
                // Show 'loader'
                $infloadControls.addClass('goal-loader');
                
                self.shopAjax = $.ajax({
                    url: nextPageUrl,
                    data: {
                        load_type: 'products',
                        '_preset': rashy_woo_opts._preset
                    },
                    dataType: 'html',
                    cache: false,
                    headers: {'cache-control': 'no-cache'},
                    method: 'GET',
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('GOAL: AJAX error - ajaxPaginationGet() - ' + errorThrown);
                    },
                    complete: function() {
                        // Hide 'loader'
                        $infloadControls.removeClass('goal-loader');
                    },
                    success: function(response) {
                        var $response = $('<div>' + response + '</div>'), $moreProducts = $response.children('.goal-products');
                        // add new products
                        $('.goal-shop-products-wrapper .products .goal-products-wrapper').append($moreProducts.html());
                        
                        // Load images (init Unveil)
                        self.layzyLoadImage();
                        
                        // Get the 'next page' URL
                        nextPageUrl = $response.find('.goal-pagination-next-link').children('a').attr('href');
                        
                        if (nextPageUrl) {
                            $nextPageLink.attr('href', nextPageUrl);
                        } else {
                            $('.goal-shop-products-wrapper').addClass('all-products-loaded');
                            
                            if (self.infloadScroll) {
                                self.infscrollLock = true; // "Lock" scroll (no more products/pages)
                            }
                            $infloadControls.find('.goal-loadmore-btn').addClass('hidden');
                            $nextPageLink.removeAttr('href');
                        }
                        
                        self.shopAjax = false;
                        
                        if (self.infloadScroll) {
                            $(window).trigger('scroll'); // Trigger 'scroll' in case the pagination element (+buffer) is still above the window bottom
                        }
                    }
                });
            } else {
                if (self.infloadScroll) {
                    self.infscrollLock = true; // "Lock" scroll (no more products/pages)
                }
            }
        }
    });

    $.goalThemeExtensions.shop = $.goalThemeCore.woo_init;


    // gallery

    var GoalProductGallery = function( $target, args ) {
        var self = this;
        this.$target = $target;
        this.$images = $( '.woocommerce-product-gallery__image', $target );

        // No images? Abort.
        if ( 0 === this.$images.length ) {
            this.$target.css( 'opacity', 1 );
            return;
        }

        // Make this object available.
        $target.data( 'product_gallery', this );

        // Pick functionality to initialize...
        this.zoom_enabled       = $.isFunction( $.fn.zoom ) && wc_single_product_params.zoom_enabled;
        this.photoswipe_enabled = typeof PhotoSwipe !== 'undefined' && wc_single_product_params.photoswipe_enabled;

        // ...also taking args into account.
        if ( args ) {
            this.zoom_enabled       = false === args.zoom_enabled ? false : this.zoom_enabled;
            this.photoswipe_enabled = false === args.photoswipe_enabled ? false : this.photoswipe_enabled;
        }

        

        // Bind functions to this.
        this.initZoom             = this.initZoom.bind( this );
        this.initZoomForTarget    = this.initZoomForTarget.bind( this );
        this.initPhotoswipe       = this.initPhotoswipe.bind( this );
        this.getGalleryItems      = this.getGalleryItems.bind( this );
        this.openPhotoswipe       = this.openPhotoswipe.bind( this );

            this.$target.css( 'opacity', 1 );

        if ( this.zoom_enabled ) {
            this.initZoom();
            $target.on( 'woocommerce_gallery_init_zoom', this.initZoom );
        }

        if ( this.photoswipe_enabled ) {
            this.initPhotoswipe();
        }

        $('.goal-woocommerce-product-gallery').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            self.initZoomForTarget( self.$images.eq(nextSlide) );
        });
    };


    /**
     * Init zoom.
     */
    GoalProductGallery.prototype.initZoom = function() {
        this.initZoomForTarget( this.$images.first() );
    };

    /**
     * Init zoom.
     */
    GoalProductGallery.prototype.initZoomForTarget = function( zoomTarget ) {
        if ( ! this.zoom_enabled ) {
            return false;
        }

        var galleryWidth = this.$target.width(),
            zoomEnabled  = false;

        $( zoomTarget ).each( function( index, target ) {
            var image = $( target ).find( 'img' );

            if ( image.data( 'large_image_width' ) > galleryWidth ) {
                zoomEnabled = true;
                return false;
            }
        } );

        // But only zoom if the img is larger than its container.
        if ( zoomEnabled ) {
            var zoom_options = {
                touch: false
            };

            if ( 'ontouchstart' in window ) {
                zoom_options.on = 'click';
            }

            zoomTarget.trigger( 'zoom.destroy' );
            zoomTarget.zoom( zoom_options );
        }
    };

    /**
     * Init PhotoSwipe.
     */
    GoalProductGallery.prototype.initPhotoswipe = function() {
        if ( this.zoom_enabled && this.$images.length > 0 ) {
            this.$target.prepend( '<a href="#" class="woocommerce-product-gallery__trigger"><i class="fa fa-search-plus" aria-hidden="true"></i></a>' );
            this.$target.on( 'click', '.woocommerce-product-gallery__trigger', this.openPhotoswipe );
        }
        this.$target.on( 'click', '.woocommerce-product-gallery__image a', this.openPhotoswipe );
    };

    /**
     * Get product gallery image items.
     */
    GoalProductGallery.prototype.getGalleryItems = function() {
        var $slides = this.$images,
            items   = [];

        if ( $slides.length > 0 ) {
            $slides.each( function( i, el ) {
                var img = $( el ).find( 'img' ),
                    large_image_src = img.attr( 'data-large_image' ),
                    large_image_w   = img.attr( 'data-large_image_width' ),
                    large_image_h   = img.attr( 'data-large_image_height' ),
                    item            = {
                        src  : large_image_src,
                        w    : large_image_w,
                        h    : large_image_h,
                        title: img.attr( 'data-caption' ) ? img.attr( 'data-caption' ) : img.attr( 'title' )
                    };
                items.push( item );
            } );
        }

        return items;
    };

    /**
     * Open photoswipe modal.
     */
    GoalProductGallery.prototype.openPhotoswipe = function( e ) {
        e.preventDefault();

        var pswpElement = $( '.pswp' )[0],
            items       = this.getGalleryItems(),
            eventTarget = $( e.target ),
            clicked;

        if ( this.$target.find( '.woocommerce-product-gallery__image.slick-current' ).length > 0 ) {
            clicked = this.$target.find( '.woocommerce-product-gallery__image.slick-current' );
        } else {
            clicked = eventTarget.closest( '.woocommerce-product-gallery__image' );
        }
        var options = $.extend( {
            index: $( clicked ).index()
        }, wc_single_product_params.photoswipe_options );

        // Initializes and opens PhotoSwipe.
        var photoswipe = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
        photoswipe.init();
    };

    /**
     * Function to call wc_product_gallery on jquery selector.
     */
    $.fn.goal_wc_product_gallery = function( args ) {
        new GoalProductGallery( this, args );
        return this;
    };

    /*
     * Initialize all galleries on page.
     */
    $( '.goal-woocommerce-product-gallery-wrapper' ).each( function() {
        $( this ).goal_wc_product_gallery();
    } );

    
})(jQuery);