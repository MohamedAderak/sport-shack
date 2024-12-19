<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Woo_Products_Specific extends Widget_Base {

	public function get_name() {
        return 'rashy_woo_products_specific';
    }

	public function get_title() {
        return esc_html__( 'Goal Products Specific', 'rashy' );
    }

    public function get_icon() {
        return 'ti-bag';
    }

	public function get_categories() {
        return [ 'rashy-elements' ];
    }

	protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'title', [
                'label' => esc_html__( 'Widget Title', 'rashy' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'sub_title', [
                'label' => esc_html__( 'Widget Sub Title', 'rashy' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'sub_text', [
                'label' => esc_html__( 'Sub Text', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA
            ]
        );

        $this->add_control(
            'product_ids', [
                'label' => esc_html__( 'Product IDS', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your product id here', 'rashy' ),
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'rashy'),
                    'carousel' => esc_html__('Carousel', 'rashy'),
                ),
                'default' => 'grid'
            ]
        );

        $this->add_control(
            'title_type',
            [
                'label' => esc_html__( 'Position Title', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'left' => esc_html__('Left', 'rashy'),
                    'center' => esc_html__('Center', 'rashy'),
                ),
                'default' => 'center'
            ]
        );

        $this->add_control(
            'product_item',
            [
                'label' => esc_html__( 'Product Item', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'inner' => esc_html__('Item 1', 'rashy'),
                    'inner-v2' => esc_html__('Item 2', 'rashy'),
                    'inner-v3' => esc_html__('Item 3', 'rashy'),
                    'inner-deal' => esc_html__('Item Deal', 'rashy'),
                    'inner-list' => esc_html__('Item List', 'rashy'),
                ),
                'default' => 'inner',
            ]
        );

        $columns = range( 1, 12 );
        $columns = array_combine( $columns, $columns );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => $columns,
                'frontend_available' => true,
                'default' => 3,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__( 'Slides to Scroll', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'rashy' ),
                'options' => $columns,
                'condition' => [
                    'columns!' => '1',
                    'layout_type' => 'carousel',
                ],
                'frontend_available' => true,
                'default' => 1,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your rows number here', 'rashy' ),
                'default' => 1,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label'         => esc_html__( 'Show Navigation', 'rashy' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'rashy' ),
                'label_off'     => esc_html__( 'Hide', 'rashy' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'         => esc_html__( 'Show Pagination', 'rashy' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'rashy' ),
                'label_off'     => esc_html__( 'Hide', 'rashy' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'rashy' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'rashy' ),
                'label_off'     => esc_html__( 'No', 'rashy' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'         => esc_html__( 'Infinite Loop', 'rashy' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'rashy' ),
                'label_off'     => esc_html__( 'No', 'rashy' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'carousel_style',
            [
                'label' => esc_html__( 'Carousel Style', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'rashy'),
                    'carousel_vertical' => esc_html__('Vertical', 'rashy'),
                    'carousel_vertical carousel_2' => esc_html__('Vertical 2', 'rashy'),
                    'carousel_white' => esc_html__('White', 'rashy'),
                    'carousel_circle' => esc_html__('Circle', 'rashy'),
                    'carousel_circle st_center' => esc_html__('Circle Center', 'rashy'),
                ),
                'default' => '',
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'rashy' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'rashy' ),
            ]
        );

        $this->end_controls_section();


        // Style
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Box Style', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_title_color',
            [
                'label' => esc_html__( 'Widget Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .products-tabs-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Widget Title Typography', 'rashy' ),
                'name' => 'widget_title_typography',
                'selector' => '{{WRAPPER}} .products-tabs-title',
            ]
        );

        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__( 'Sub Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .sub-widget-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .sub-widget-title:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .sub-widget-title:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Sub Title Typography', 'rashy' ),
                'name' => 'sub_title_typography',
                'selector' => '{{WRAPPER}} .sub-widget-title',
            ]
        );

        $this->add_control(
            'sub_text_color',
            [
                'label' => esc_html__( 'Sub Text Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .sub-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Sub Text Typography', 'rashy' ),
                'name' => 'sub_text_typography',
                'selector' => '{{WRAPPER}} .sub-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__( 'Border', 'rashy' ),
                'selector' => '{{WRAPPER}} .product-block.grid .grid-inner, {{WRAPPER}} .products-grid.colection_gutter .row-products-wrapper:after, {{WRAPPER}} .products-grid.colection_gutter .products-wrapper-mansory:after'

            ]
        );

        $this->add_control(
            'box_hover_border_color',
            [
                'label' => esc_html__( 'Border Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-block.grid .grid-inner:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => esc_html__( 'Box Shadow Hover', 'rashy' ),
                'selector' => '{{WRAPPER}} .product-block:hover',
            ]
        );

        $this->end_controls_section();

        
        $this->start_controls_section(
            'section_product_style',
            [
                'label' => esc_html__( 'Product Style', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} h3.name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block h3.name a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block h3.name a:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'rashy' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} h3.name a',
            ]
        );

        $this->add_control(
            'cat_color',
            [
                'label' => esc_html__( 'Category Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .product-cat a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Category Typography', 'rashy' ),
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} .product-block .product-cat a',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Price Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .price' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .product-block .price ins' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'price_old_color',
            [
                'label' => esc_html__( 'Price Old Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .price del' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Price Typography', 'rashy' ),
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .product-block .price',
            ]
        );

        $this->add_control(
            'info_color',
            [
                'label' => esc_html__( 'Info Action Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .yith-compare .compare' => 'color: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'info_hv_color',
            [
                'label' => esc_html__( 'Info Action Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist:focus a' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .yith-wcwl-add-to-wishlist a:hover' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block .yith-compare:focus .compare' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .yith-compare:hover .compare' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .yith-compare .compare.added' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview:hover' => 'color: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'info_bg_color',
            [
                'label' => esc_html__( 'Info Action Background Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block.grid .yith-wcwl-add-to-wishlist:not(.add_to_wishlist) a' => 'background-color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .yith-wcwl-add-to-wishlist a' => 'background-color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block .view .quickview' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .yith-compare .compare' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'info_bg_hv_color',
            [
                'label' => esc_html__( 'Info Action Background Color Hover', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block .view .quickview:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview:focus' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview.loading::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .yith-wcwl-add-to-wishlist a:hover' => 'background-color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .yith-compare .compare:hover' => 'background-color: {{VALUE}};',
                    
                ],
               
            ]
        );

        $this->add_control(
            'Addtocarted_color',
            [
                'label' => esc_html__( 'Addtocart Added Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .added_to_cart:hover, .product .product-block.grid.grid-v3 .button:hover' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .added_to_cart:focus, .product .product-block.grid.grid-v3 .button:focus' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .product-block.grid .add-cart a.added_to_cart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart:hover a.button' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart:focus a.button' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'add_bg_color',
            [
                'label' => esc_html__( 'Addtocart Background Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block.grid .add-cart a.button' => 'background-color: {{VALUE}};',
                   
                ],
                
            ]
        );

        $this->add_control(
            'add_bg_hv_color',
            [
                'label' => esc_html__( 'Addtocart Background Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block.grid .add-cart a.button:hover, .product-block.grid .add-cart a.added_to_cart:hover' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        $product_ids = !empty($product_ids) ? array_filter(explode(',', $product_ids)) : '';
        if ( $product_ids ) {
        ?>
            <div class="widget woocommerce widget-products-deal <?php echo esc_attr($product_item.' '.$el_class); ?> <?php if (rashy_get_config('colection_gutter', false)) { ?> colection_gutter <?php } ?>">
                <div class="top-info <?php echo esc_attr(($title_type != 'center')?'flex-middle-sm':'text-center'); ?>">
                    <?php if ( !empty($title) ): ?>
                    <div class="widget-title">
                        <?php if ( !empty($sub_title) ): ?>
                            <span class="sub-widget-title">
                                <?php echo esc_attr( $sub_title ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( !empty($title) ): ?>
                            <h3 class="products-tabs-title">
                                <?php echo esc_attr( $title ); ?>
                            </h3>
                        <?php endif; ?>
                       
                        <?php if ( !empty($sub_text) ): ?>
                            <p class="sub-text">
                                <?php echo esc_attr( $sub_text ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="widget-content col-sm-12 col-xs-12 <?php echo esc_attr($layout_type); ?>">
                    <div class="products-wrapper-grid">
                        <div class="row row-products row-products-wrapper">
                            <?php if ( $layout_type == 'carousel' ) { ?>

                                <?php
                                $product_item = isset($product_item) ? $product_item : 'inner';

                                $show_nav = isset($show_nav) ? $show_nav : false;
                                $show_pagination = isset($show_pagination) ? $show_pagination : false;

                                $rows = isset($rows) ? $rows : 1;
                                $columns = !empty($columns) ? $columns : 3;
                                if($columns > 1){
                                    $columns_tablet = 2;
                                }else{
                                    $columns_tablet = 1;
                                }
                                $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;

                                $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
                                $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
                                $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;

                                $infinite_loop = isset($infinite_loop) ? $infinite_loop : false;
                                $autoplay = isset($autoplay) ? $autoplay : false;
                                ?>
                                <div class="slick-carousel products carousel_small <?php echo trim($carousel_style); ?>"
                                    data-items="<?php echo esc_attr($columns); ?>"
                                    data-smallmedium="<?php echo esc_attr( $columns_tablet ); ?>"
                                    data-extrasmall="<?php echo esc_attr($columns_mobile); ?>"

                                    data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
                                    data-slidestoscroll_smallmedium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                                    data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

                                    data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>" data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>" data-rows="<?php echo esc_attr( $rows ); ?>">

                                    <?php
                                    foreach ($product_ids as $product_id) {
                                        if ( !empty($product_id) ) {
                                            $post_object = get_post( $product_id );
                                            if ( $post_object ) {
                                                setup_postdata( $GLOBALS['post'] =& $post_object );
                                                ?>
                                                    <div class="products-grid product">
                                                        <?php wc_get_template_part( 'item-product/'.$product_item ); ?>
                                                    </div>
                                                <?php
                                            }
                                        }
                                    }
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <?php
                                    $product_item = isset($product_item) ? $product_item : 'inner';
                                    $columns = !empty($columns) ? $columns : 3;
                                    if($columns > 1){
                                        $columns_tablet = 2;
                                    }else{
                                        $columns_tablet = 1;
                                    }
                                    $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;

                                    $mdcol = 12/$columns;
                                    $smcol = 12/$columns_tablet;
                                    $xscol = 12/$columns_mobile;

                                    if( $columns == 5 ){
                                        $mdcol = '5c';
                                    }

                                    if( $columns_tablet == 5 ){
                                        $smcol = '5c';
                                    }

                                    if( $columns_mobile == 5 ){
                                        $xscol = '5c';
                                    }

                                    $classes = array();

                                    $classes[] = 'col-md-'.$mdcol.' col-sm-'.$smcol.' col-xs-'.$xscol;

                                    $count = 0;
                                    foreach ($product_ids as $product_id) {
                                        
                                        if ( !empty($product_id) ) {
                                            $post_object = get_post( $product_id );
                                            if ( $post_object ) {
                                                setup_postdata( $GLOBALS['post'] =& $post_object );
                                                $pclasses = $classes;
                                                if ( $count%$columns == 0 ) {
                                                    $pclasses[] = 'md-clearfix lg-clearfix ';
                                                }
                                                ?>
                                                    <div <?php wc_product_class( $pclasses, $post_object ); ?>>
                                                        <?php wc_get_template_part( 'item-product/'.$product_item ); ?>
                                                    </div>
                                                <?php
                                                $count++;
                                            }
                                        }
                                    }
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Woo_Products_Specific );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Woo_Products_Specific );
}