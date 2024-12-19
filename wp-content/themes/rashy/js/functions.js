(function ($) {
    "use strict";

    if (!$.goalThemeExtensions)
        $.goalThemeExtensions = {};
    
    function GoalThemeCore() {
        var self = this;
        // self.init();
    };

    GoalThemeCore.prototype = {
        /**
         *  Initialize
         */
        init: function() {
            var self = this;
            
            self.preloadSite();

            // slick init
            self.initSlick($("[data-carousel=slick]"));

            // Unveil init
            setTimeout(function(){
                self.layzyLoadImage();
            }, 200);
            
            // isoto
            self.initIsotope();

            // Sticky Header
            self.intChangeHeaderMarginTop();
            self.initHeaderSticky();

            // back to top
            self.backToTop();

            // popup image
            self.popupImage();

            $('[data-toggle="tooltip"]').tooltip();

            self.initPopupNewsletter();
            
            self.initUserInfo();

            self.initMobileMenu(); 

            self.initVerticalMenu();

            self.mainMenuInit();

            $('.footer-search-btn').on('click', function(){
                $('.footer-search-mobile').toggleClass('active');
            });
            $('.more').on('click', function(){
                $('.wrapper-morelink').toggleClass('active');
            });
            
            // search
            $('.goal-search-form.style1 .show-search-header').on('click', function(){
                $(this).closest('.goal-search-form').toggleClass('active');
            });
            
            $(document.body).on('click', '.nav [data-toggle="dropdown"]' ,function(){
                if(  this.href && this.href != '#'){
                    window.location.href = this.href;
                }
            });

            // Ajax delete product from the cart
            $(document).on('click', '.widget_shopping_cart_content a.remove', function (e)
            {
                e.preventDefault();
             
                var product_id = $(this).attr("data-product_id"),
                    cart_item_key = $(this).attr("data-cart_item_key"),
                    product_container = $(this).parents('.mini_cart_item');
             
                // Add loader
                product_container.block({
                    message: null,
                    overlayCSS: {
                        cursor: 'none'
                    }
                });
             
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: wc_add_to_cart_params.ajax_url,
                    data: {
                        action: "product_remove",
                        product_id: product_id,
                        cart_item_key: cart_item_key
                    },
                    success: function(response) {
                        if ( ! response || response.error )
                            return;
             
                        var fragments = response.fragments;
             
                        // Replace fragments
                        if ( fragments ) {
                            $.each( fragments, function( key, value ) {
                                $( key ).replaceWith( value );
                            });
                        }
                    }
                });
            });

            self.loadExtension();
        },
        /**
         *  Extensions: Load scripts
         */
        loadExtension: function() {
            var self = this;
            
            
            
            if ($.goalThemeExtensions.quantity_increment) {
                $.goalThemeExtensions.quantity_increment.call(self);
            }

            if ($.goalThemeExtensions.shop) {
                $.goalThemeExtensions.shop.call(self);
            }
        },
        initSlick: function(element) {
            var self = this;
            element.each( function(){
                var config = {
                    infinite: false,
                    arrows: $(this).data( 'nav' ),
                    dots: $(this).data( 'pagination' ),
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    prevArrow:"<button type='button' aria-label='Previous' class='slick-arrow slick-prev pull-left'><i class='icon-arrow-left' aria-hidden='true'></i></span><span class='textnav'>"+ rashy_ajax.previous +"</span></button>",
                    nextArrow:"<button type='button' aria-label='Next' class='slick-arrow slick-next pull-right'><span class='textnav'>"+ rashy_ajax.next +"</span><i class='icon-arrow-right' aria-hidden='true'></i></button>",
                };
            
                var slick = $(this);
                if( $(this).data('items') ){
                    config.slidesToShow = $(this).data( 'items' );
                    config.slidesToScroll = $(this).data( 'items' );
                }
                if( $(this).data('infinite') ){
                    config.infinite = true;
                }
                if( $(this).data('autoplay') ){
                    config.autoplay = true;
                    config.autoplaySpeed = 2000;
                }
                if( $(this).data('swipe') ){
                    config.swipe = true;
                }
                if( $(this).data('disable_draggable') ){
                    config.touchMove = false;
                    config.draggable = false;
                    config.swipe = false;
                    config.swipeToSlide = false;
                }
                if( $(this).data('centermode') ){
                    config.centerMode = true;
                }
                if( $(this).data('centerpadding') ){
                    config.centerPadding = $(this).data( 'centerpadding' );
                }
                if( $(this).data('vertical') ){
                    config.vertical = true;
                }
                if( $(this).data('rows') ){
                    config.rows = $(this).data( 'rows' );
                }
                if( $(this).data('asnavfor') ){
                    config.asNavFor = $(this).data( 'asnavfor' );
                }
                if( $(this).data('slidestoscroll') ){
                    config.slidesToScroll = $(this).data( 'slidestoscroll' );
                }
                if( $(this).data('focusonselect') ){
                    config.focusOnSelect = $(this).data( 'focusonselect' );
                }
                if ($(this).data('large')) {
                    var desktop = $(this).data('large');
                } else {
                    var desktop = config.items;
                }
                if ($(this).data('smalldesktop')) {
                    var smalldesktop = $(this).data('smalldesktop');
                } else {
                    if ($(this).data('large')) {
                        var smalldesktop = $(this).data('large');
                    } else{
                        var smalldesktop = config.items;
                    }
                }
                if ($(this).data('medium')) {
                    var medium = $(this).data('medium');
                } else {
                    var medium = config.items;
                }
                if ($(this).data('smallmedium')) {
                    var smallmedium = $(this).data('smallmedium');
                } else {
                    var smallmedium = 3;
                }
                if ($(this).data('extrasmall')) {
                    var extrasmall = $(this).data('extrasmall');
                } else {
                    var extrasmall = 3;
                }
                if ($(this).data('smallest')) {
                    var smallest = $(this).data('smallest');
                } else {
                    var smallest = 1;
                }
                config.responsive = [
                    {
                        breakpoint: 321,
                        settings: {
                            slidesToShow: smallest,
                            slidesToScroll: smallest,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: extrasmall,
                            slidesToScroll: extrasmall,
                        }
                    },
                    {
                        breakpoint: 769,
                        settings: {
                            slidesToShow: smallmedium,
                            slidesToScroll: smallmedium
                        }
                    },
                    {
                        breakpoint: 981,
                        settings: {
                            slidesToShow: medium,
                            slidesToScroll: medium
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: smalldesktop,
                            slidesToScroll: smalldesktop
                        }
                    },
                    {
                        breakpoint: 1501,
                        settings: {
                            slidesToShow: desktop,
                            slidesToScroll: desktop
                        }
                    }
                ];
                if ( $('html').attr('dir') == 'rtl' ) {
                    config.rtl = true;
                }

                $(this).slick( config );

            } );

            // Fix owl in bootstrap tabs
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");
                var $slick = $(".slick-carousel", target);

                if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                    $slick.slick('refresh');
                }
                self.layzyLoadImage();
            });
        },
        layzyLoadImage: function() {
            $(window).off('scroll.unveil resize.unveil lookup.unveil');
            var $images = $('.image-wrapper:not(.image-loaded) .unveil-image'); // Get un-loaded images only
            if ($images.length) {
                $images.unveil(1, function() {
                    $(this).load(function() {
                        $(this).parents('.image-wrapper').first().addClass('image-loaded');
                        $(this).removeAttr('data-src');
                        $(this).removeAttr('data-srcset');
                        $(this).removeAttr('data-sizes');
                    });
                });
            }

            var $images = $('.product-image:not(.image-loaded) .unveil-image'); // Get un-loaded images only
            if ($images.length) {
                $images.unveil(1, function() {
                    $(this).load(function() {
                        $(this).parents('.product-image').first().addClass('image-loaded');
                    });
                });
            }
        },
        initIsotope: function() {
            $('.isotope-items').each(function(){  
                var $container = $(this);
                
                $container.imagesLoaded( function(){
                    $container.isotope({
                        itemSelector : '.isotope-item',
                        transformsEnabled: true,         // Important for videos
                        masonry: {
                            columnWidth: $container.data('columnwidth')
                        }
                    }); 
                });
            });

            /*---------------------------------------------- 
             *    Apply Filter        
             *----------------------------------------------*/
            $('.isotope-filter li a').on('click', function(){
               
                var parentul = $(this).parents('ul.isotope-filter').data('related-grid');
                $(this).parents('ul.isotope-filter').find('li a').removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter'); 
                $('#'+parentul).isotope({ filter: selector }, function(){ });
                
                return(false);
            });
        },
        changeHeaderMarginTop: function() {
            if ($(window).width() > 991) {
                if ( $('.main-sticky-header').length > 0 ) {
                    var header_height = $('.main-sticky-header').outerHeight();
                    $('.main-sticky-header-wrapper').css({'height': header_height});
                }
                // top for cart
                if ( $('#goal-header').length > 0 ) {
                    var header_height = $('#goal-header').outerHeight();
                    $('.search-header').css({'padding-top': header_height});
                }
            }
        },
        intChangeHeaderMarginTop: function() {
            var self = this;
            setTimeout(function(){
                self.changeHeaderMarginTop();
            }, 50);
            $(window).resize(function(){
                self.changeHeaderMarginTop();
            });
        },
        initHeaderSticky: function() {
            var self = this;
            var main_sticky = $('.main-sticky-header');
            setTimeout(function(){
                if ( main_sticky.length > 0 ){
                    if ($(window).width() > 991) {
                        var _menu_action = main_sticky.offset().top;
                        $(window).scroll(function(event) {
                            self.headerSticky(main_sticky, _menu_action);
                        });
                        self.headerSticky(main_sticky, _menu_action);
                    }
                }
            }, 50);
        },
        headerSticky: function(main_sticky, _menu_action) {
            if( $(document).scrollTop() > _menu_action ){
                main_sticky.addClass('sticky-header');
            }else{
                main_sticky.removeClass('sticky-header');
            }
        },
        backToTop: function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 400) {
                    $('#back-to-top').addClass('active');
                } else {
                    $('#back-to-top').removeClass('active');
                }
            });
            $('#back-to-top').on('click', function () {
                $('html, body').animate({scrollTop: '0px'}, 800);
                return false;
            });
        },
        popupImage: function() {
            // popup
            $(".popup-image").magnificPopup({type:'image'});
            $('.popup-video').magnificPopup({
                // disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });

            $('.widget-gallery').each(function(){
                var tagID = $(this).attr('id');
                $('#' + tagID).magnificPopup({
                    delegate: '.popup-image-gallery',
                    type: 'image',
                    tLoading: 'Loading image #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                    }
                });
            });

            $('.sizeguides-btn').magnificPopup({
                mainClass: 'goal-mfp-zoom-in zoom-sizeguides',
                type:'inline',
                midClick: true,
            });
        },
        preloadSite: function() {
            // preload page
            if ( $('body').hasClass('goal-body-loading') ) {
                $('body').removeClass('goal-body-loading');
                $('.goal-page-loading').fadeOut(100);
            }
        },
        initPopupNewsletter: function() {
            var self = this;

            if ($('.popupnewsletter').length > 0) {
                setTimeout(function(){
                    var hiddenmodal = self.getCookie('hidde_popup_newsletter');
                    if (hiddenmodal == "") {
                        var popup_content = $('.popupnewsletter').html();
                        $.magnificPopup.open({
                            mainClass: 'goal-mfp-zoom-in popupnewsletter-wrapper',
                            modal:true,
                            items    : {
                                src : popup_content,
                                type: 'inline'
                            },
                            callbacks: {
                                close: function() {
                                    var dont = $('.close-dont-show').attr('data-dont');
                                    if ( dont === 'yes' ) {
                                        self.setCookie('hidde_popup_newsletter', 1, 30);
                                    }
                                }
                            }
                        });
                    }
                }, 3000);
            }
            $('body').on('click', '.goal-mfp-close', function(e){
                e.preventDefault();
                $.magnificPopup.close();
            });
            $('body').on('click', '.close-dont-show', function(e){
                e.preventDefault();
                $(this).attr('data-dont', 'yes');
                $.magnificPopup.close();
            });
        },
        initUserInfo: function() {
            $('.login.popup').on('click', function(e) {
                e.preventDefault();
                var popup_content = $(this).parent().find('.header-customer-login-wrapper').html();
                $.magnificPopup.open({
                    mainClass: 'goal-mfp-zoom-in login-wrapper',
                    modal:true,
                    items    : {
                        src : popup_content,
                        type: 'inline'
                    }
                });
            });
        },
        initVerticalMenu: function() {
            // mobile menu
            $('.show-hover').on('click', function (e) {
                e.stopPropagation();
                $('.show-hover .content-vertical').toggle(350);           
            });
            $('body').on('click', function() {
                $('.show-hover .content-vertical').slideUp(350);
            });
            $('.content-vertical').on('click', function(e) {
                e.stopPropagation();
            });
            
            $('body:not(.home) .show-in-home').on('click', function (e) {
                e.stopPropagation();
                $('.show-in-home .content-vertical').toggle(350);           
            });
            // show vertical mobile
            $('.mobile-vertical-menu-title').click(function(){
                $('.mobile-vertical-menu').slideToggle();
                $(this).toggleClass('active');
                if ( $(this).find('i').hasClass('fa-angle-down') ) {
                    $(this).find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
                } else {
                    $(this).find('i').addClass('fa-angle-down').removeClass('fa-angle-up');
                }
            });
            $('#vertical-mobile-menu .has-submenu > .icon-toggle').on('click', function (e) {
                e.stopPropagation();
                $(this).parent().find('> .sub-menu').toggle(350);
                if ( $(this).find('i').hasClass('ti-plus') ) {
                    $(this).find('i').removeClass('ti-plus').addClass('ti-minus');
                } else {
                    $(this).find('i').removeClass('ti-minus').addClass('ti-plus');
                }
            });
        },

        mainMenuInit: function() {
            $('.goal-megamenu .megamenu').each(function(e){
                var $this = $(this);
                $this.on('mouseenter', function(){
                    $('body').addClass('show-header-static');
                }).on('mouseleave', function(){
                    $('body').removeClass('show-header-static');
                });
            });
        },

        initMobileMenu: function() {

            // mobile menu
            $('.btn-toggle-canvas,.btn-showmenu').on('click', function (e) {
                e.stopPropagation();
                $('.goal-offcanvas').toggleClass('active');           
                $('.over-dark').toggleClass('active');

                $("#mobile-menu-container").slidingMenu();
            });
            $('body').on('click', function() {
                if ($('.goal-offcanvas').hasClass('active')) {
                    $('.goal-offcanvas').toggleClass('active');
                    $('.over-dark').toggleClass('active');
                }
            });
            $('.goal-offcanvas').on('click', function(e) {
                e.stopPropagation();
            });

            // sidebar mobile
            $('.sidebar-right, .sidebar-left').perfectScrollbar();
            $('body').on('click', '.mobile-sidebar-btn', function(){
                if ( $('.sidebar-left').length > 0 ) {
                    $('.sidebar-left').toggleClass('active');
                } else if ( $('.sidebar-right').length > 0 ) {
                    $('.sidebar-right').toggleClass('active');
                }
                $('.mobile-sidebar-panel-overlay').toggleClass('active');
            });
            $('body').on('click', '.mobile-sidebar-panel-overlay, .close-sidebar-btn', function(){
                if ( $('.sidebar-left').length > 0 ) {
                    $('.sidebar-left').removeClass('active');
                } else if ( $('.sidebar-right').length > 0 ) {
                    $('.sidebar-right').removeClass('active');
                }
                $('.mobile-sidebar-panel-overlay').removeClass('active');
            });
        },
        hoverMenu: function(container) { 
            var $container = container;
            var sectionId = $container.attr('data-section-id');
            var item = this.slider = '.item_' + sectionId;
            const items = document.querySelectorAll(item);
            var zoomout = false;

            items.forEach((el) => {
                const image = el.querySelector('.img')

                el.addEventListener('mouseenter', (e) => {
                    gsap.to(image, {
                        ease: 'Expo.easeOut',
                        autoAlpha: 1,
                        scale: 1,
                        startAt: {
                            scale: zoomout ? 1.2 :0.6,
                        },
                        scale: 1
                    })
                })

                el.addEventListener('mouseleave', (e) => {
                    gsap.to(image, {
                        ease: 'Expo.easeOut',
                        autoAlpha: 0,
                        scale: zoomout ? 1.2 : 1,
                    })
                })

                el.addEventListener('mousemove', (e) => {
                    gsap.set(image, { 
                        ease: 'Expo.easeOut',
                        left: e.offsetX,
                        top: e.offsetY,
                        delay: 0,
                    })
                })
            })
        },
        setCookie: function(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires+";path=/";
        },
        getCookie: function(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        }
    }

    $.goalThemeCore = GoalThemeCore.prototype;
    
    
    $.fn.wrapStart = function(numWords){
        return this.each(function(){
            var $this = $(this);
            var node = $this.contents().filter(function(){
                return this.nodeType == 3;
            }).first(),
            text = node.text().trim(),
            first = text.split(' ', 1).join(" ");
            if (!node.length) return;
            node[0].nodeValue = text.slice(first.length);
            node.before('<b>' + first + '</b>');
        });
    };

    $(document).ready(function() {
        // Initialize script
        var goalthemecore_init = new GoalThemeCore();
        goalthemecore_init.init();

        $('.mod-heading .widget-title > span').wrapStart(1);
    });

    jQuery(window).on("elementor/frontend/init", function() {
        
        var goalthemecore_init = new GoalThemeCore();

        // General element
        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_brands.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_features_box.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_about_us.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_posts.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_testimonials.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

       

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_instagram.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_woo_products_deal.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_woo_products.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_woo_products_specific.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_woo_product_tabs.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_element_woo_product_tabs_special.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_woo_categories.default",
            function($scope) {
                goalthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/rashy_element_special_banner.default",
            function($scope) {
                if ( $scope.find('.txtcollection').length ) {
                    goalthemecore_init.hoverMenu($scope.find('.txtcollection'));
                }
            }
        );
    });

})(jQuery);

