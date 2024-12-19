<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Woo_Products extends Widget_Base {

    public function get_name() {
        return 'rashy_woo_products';
    }

    public function get_title() {
        return esc_html__( 'Goal Products', 'rashy' );
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
            'type',
            [
                'label' => esc_html__( 'Get Products By', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'recent_product' => esc_html__('Recent Products', 'rashy' ),
                    'best_selling' => esc_html__('Best Selling', 'rashy' ),
                    'featured_product' => esc_html__('Featured Products', 'rashy' ),
                    'top_rate' => esc_html__('Top Rate', 'rashy' ),
                    'on_sale' => esc_html__('On Sale', 'rashy' ),
                    'recent_review' => esc_html__('Recent Review', 'rashy' ),
                    'recently_viewed' => esc_html__('Recent Viewed', 'rashy' ),
                ),
                'default' => 'recent_product'
            ]
        );

        $this->add_control(
            'slugs',
            [
                'label' => esc_html__( 'Categories Slug', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter slug spearate by comma(,)', 'rashy' ),
            ]
        );
        
        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Limit', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter number products to display', 'rashy' ),
                'default' => 4
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
            'product_item',
            [
                'label' => esc_html__( 'Product Item', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'inner' => esc_html__('Grid Item', 'rashy'),
                    'inner-list-normal' => esc_html__('List Item', 'rashy'),
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
            'pagination_style',
            [
                'label' => esc_html__( 'Pagination', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Normal', 'rashy'),
                    'special' => esc_html__('Special', 'rashy'),
                ),
                'default' => '',
                'condition' => [
                    'layout_type' => 'carousel',
                ]
            ]
        );

        $this->add_control(
            'arrow_position',
            [
                'label' => esc_html__( 'Navigation Position Vertical', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Normal', 'rashy'),
                    'arrow-top' => esc_html__('Top', 'rashy'),
                ),
                'condition' => [
                    'layout_type' => 'carousel',
                ],
                'default' => ''
            ]
        );

        $this->add_control(
            'navigation_position',
            [
                'label' => esc_html__( 'Navigation Position', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Normal', 'rashy'),
                    'arrow-full' => esc_html__('Outside', 'rashy'),
                    'arrow-small' => esc_html__('Inside', 'rashy'),
                ),
                'default' => 'arrow-full',
                'condition' => [
                    'layout_type' => 'carousel',
                ]
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

        $this->add_control(
            'bg-image',
            [
                'label' => esc_html__( 'Background Color Top Image', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'product_item' => 'inner-v7',
                ],
                'selectors' => [
                    '{{WRAPPER}} .block-inner' => 'background-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .product-block .image::before' => 'background: linear-gradient(to bottom, transparent 0%, {{VALUE}} 100%);',
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
                    '{{WRAPPER}} .product-block .sale-perc' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-block.grid .woosw-btn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .woosc-btn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart .added_to_cart:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart .button:before' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-block.grid .woosw-btn:focus' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .woosw-btn:hover' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .woosw-btn.woosw-added:hover' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .woosc-btn:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .woosc-btn:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .woosc-btn.woosc-added:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview:hover' => 'color: {{VALUE}};',
                    
                ],
                
            ]
        );

        $this->add_control(
            'info_active_color',
            [
                'label' => esc_html__( 'Info Action Active Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block.grid .woosw-btn.woosw-added' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .woosc-btn.woosc-added' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart .added_to_cart:before ' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-block.grid .woosw-btn' => 'background-color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block .view .quickview' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .woosc-btn' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview.loading::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart .added_to_cart ' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .product-block.grid .add-cart .button ' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .product-block.grid .add-cart .added_to_cart:before ' => 'background-color: {{VALUE}} !important;',

                    
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
                    '{{WRAPPER}} .product-block.grid .woosw-btn:hover' => 'background-color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .woosc-btn:hover' => 'background-color: {{VALUE}};',
                    

                    
                ],
               
            ]
        );

        $this->add_control(
            'Addtocart_color',
            [
                'label' => esc_html__( 'Addtocart Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block.grid .add-cart > .added_to_cart' => 'color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .add-cart > .button' => 'color: {{VALUE}} ;',
                ],
                
            ]
        );

        $this->add_control(
            'Addtocart_hover_color',
            [
                'label' => esc_html__( 'Addtocart Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-block.grid .add-cart > .button:hover' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .add-cart > .added_to_cart' => 'color: {{VALUE}};',

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
                    '{{WRAPPER}} .product-block.grid .add-cart > .button' => 'background-image: linear-gradient(to right, {{VALUE}} 0%, {{VALUE}} 100%);',
                    '{{WRAPPER}} .product-block.grid .add-cart > a.added_to_cart' => 'background-image: linear-gradient(to right, {{VALUE}} 0%, {{VALUE}} 100%);',
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
                    '{{WRAPPER}} .product-block.grid .add-cart a.button:hover, 
                    {{WRAPPER}} .product-block.grid .add-cart a.added_to_cart:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart a.button, 
                    {{WRAPPER}} .product-block.grid .add-cart a.added_to_cart' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__( 'Addtocart Arrow Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}}  .product-block.grid .add-cart > .added_to_cart:not(.loading)::before' => 'color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .add-cart > .button:not(.loading)::before' => 'color: {{VALUE}} ;', 

                ],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => esc_html__( 'Addtocart Arrow Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}}  .product-block.grid .add-cart > .added_to_cart:hover:not(.loading)::before' => 'color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .add-cart > .added_to_cart:active:not(.loading)::before' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .add-cart > .button:hover:not(.loading)::before' => 'color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .add-cart > .button:active:not(.loading)::before' => 'color: {{VALUE}} ;', 
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => esc_html__( 'Addtocart Arrow Background Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}}  .product-block.grid .add-cart > .added_to_cart:not(.loading)::before' => 'background-color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .add-cart > .button:not(.loading)::before' => 'background-color: {{VALUE}} ;', 
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => esc_html__( 'Addtocart Arrow Hover Background Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}}  .product-block.grid .add-cart > .added_to_cart:hover:not(.loading)::before' => 'background-color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .add-cart > .added_to_cart:active:not(.loading)::before' => 'background-color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid .add-cart > .button:hover:not(.loading)::before' => 'background-color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product-block.grid .add-cart > .button:active:not(.loading)::before' => 'background-color: {{VALUE}} ;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget woocommerce widget-products <?php echo esc_attr($pagination_style.' '.$el_class); ?> <?php echo esc_attr($product_item.' '.$el_class); ?>">
            <?php if ( !empty($title) ): ?>
            <div class="top-info <?php echo esc_attr(($title_type != 'center')?'flex-middle-sm':'text-center'); ?>">
                
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
                
            </div>
            <?php endif; ?>
            <div class="widget-content <?php echo esc_attr($layout_type); ?>">
                
                <?php
                    $slugs = !empty($slugs) ? array_map('trim', explode(',', $slugs)) : array();
                    $type = isset($type) ? $type : 'recent_product';
                    $args = array(
                        'categories' => $slugs,
                        'product_type' => $type,
                        'post_per_page' => $limit,
                    );
                    $loop = rashy_get_products( $args );
                    
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
                        'arrow_position' => $arrow_position,
                        'navigation_position' => $navigation_position,
                        'autoplay' => $autoplay,
                        'infinite_loop' => $infinite_loop,
                        'rows' => $rows,
                        'product_item' => $product_item,
                        'slick_top' => '',
                        'elementor_element' => true,
                    ) );
                ?>

            </div>
            
        </div>
        <?php
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Woo_Products );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Woo_Products );
}