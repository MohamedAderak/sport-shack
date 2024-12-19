<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Woo_Product_Tabs_Special extends Widget_Base {

	public function get_name() {
        return 'rashy_element_woo_product_tabs_special';
    }

	public function get_title() {
        return esc_html__( 'Goal Product Tabs Special', 'rashy' );
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

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Tab Title', 'rashy' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'rashy' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-star',
            ]
        );

        $repeater->add_control(
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

        $repeater->add_control(
            'slugs',
            [
                'label' => esc_html__( 'Category Slug', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter slug spearate by comma(,)', 'rashy' ),
            ]
        );

        // Special
        $repeater->add_control(
            'product_id', [
                'label' => esc_html__( 'Special Product ID', 'rashy' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_control(
            'end_date', [
                'label' => esc_html__( 'End Date', 'rashy' ),
                'type' => Controls_Manager::DATE_TIME,
                'picker_options' => [
                    'enableTime' => false
                ]
            ]
        );
        $repeater->add_control(
            'available', [
                'label' => esc_html__( 'Available', 'rashy' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 5
            ]
        );
        $repeater->add_control(
            'already_sold', [
                'label' => esc_html__( 'Already Sold', 'rashy' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 10
            ]
        );

        $repeater->add_control(
            'product_item',
            [
                'label' => esc_html__( 'Special Product Style', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'inner' => esc_html__('Item 1', 'rashy'),
                    'inner-v2' => esc_html__('Item 2', 'rashy'),
                    'inner-v3' => esc_html__('Item 3', 'rashy'),
                    'inner-deal' => esc_html__('Item Deal', 'rashy'),
                ),
                'default' => 'inner-deal',
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
            'tabs',
            [
                'label' => esc_html__( 'Tabs', 'rashy' ),
                'type' => Controls_Manager::REPEATER,
                'placeholder' => esc_html__( 'Enter your product tabs here', 'rashy' ),
                'fields' => $repeater->get_controls(),
            ]
        );
        
        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Limit', 'rashy' ),
                'type' => Controls_Manager::NUMBER,
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
                    'inner' => esc_html__('Item 1', 'rashy'),
                    'inner-v2' => esc_html__('Item 2', 'rashy'),
                    'inner-v3' => esc_html__('Item 3', 'rashy'),
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
            'tab_style',
            [
                'label' => esc_html__( 'Style Tab', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'st_1' => esc_html__('Style 1', 'rashy'),
                    'st_2' => esc_html__('Style 2', 'rashy'),
                    'st_3' => esc_html__('Style 3', 'rashy'),
                ),
                'default' => 'st_1'
            ]
        );


        $this->add_control(
            'tab_type',
            [
                'label' => esc_html__( 'Position Tab', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'left' => esc_html__('Left', 'rashy'),
                    'right' => esc_html__('Right', 'rashy'),
                    'center' => esc_html__('Center', 'rashy'),
                ),
                'default' => 'center'
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


        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Widget Style', 'rashy' ),
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

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab_style',
            [
                'label' => esc_html__( 'Tabs Style', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => esc_html__( 'Tab Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav-tabs > li > a, 
                    {{WRAPPER}} .nav.tabs-product.st_3 > li:hover > a' => 'color: {{VALUE}};',
                    
                    
                ],
            ]
        );

        $this->add_control(
            'tab_hover_color',
            [
                'label' => esc_html__( 'Tab Hover/Active Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav-tabs > li.active > a, {{WRAPPER}} .nav-tabs > li.active > a:hover, {{WRAPPER}} .nav-tabs > li.active > a:focus, {{WRAPPER}} .nav-tabs > li > a:hover, {{WRAPPER}} .nav-tabs > li > a:focus, {{WRAPPER}} .nav.tabs-product.st_1 > li:hover > a, {{WRAPPER}} .nav.tabs-product.st_1 > li:hover > a:hover' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'bg_tab_color',
            [
                'label' => esc_html__( 'Background Tab Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li > a' => 'background-color: {{VALUE}};',
                    
                ],
            ]
        );

        $this->add_control(
            'bg_tab_hover_color',
            [
                'label' => esc_html__( 'Background Tab Hover/Active Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li.active > a' => 'background-color: {{VALUE}};', 
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li.active > a:hover'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li.active > a:focus'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li > a:hover'  => 'background-color: {{VALUE}};', 
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li > a:focus' => 'background-color: {{VALUE}};',
                    
                   
                ],
            ]
        );

        $this->add_control(
            'border_tab_color',
            [
                'label' => esc_html__( 'Border Tab Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li > a' => 'border-color: {{VALUE}};',
                    
                ],
            ]
        );

        $this->add_control(
            'border_tab_hover_color',
            [
                'label' => esc_html__( 'Border Tab Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li.active > a ' => 'border-color: {{VALUE}};', 
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li.active > a:hover ' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li.active > a:focus' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li > a:hover' => 'border-color: {{VALUE}};', 
                    '{{WRAPPER}} .nav.tabs-product.st_1 > li > a:focus' => 'border-color: {{VALUE}};',
                    
                ],
            ]
        );

        $this->add_control(
            'icon_tab_color',
            [
                'label' => esc_html__( 'Icon Tab Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product.st_3 > li > a i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_tab_hover_color',
            [
                'label' => esc_html__( 'Icon Tab Hover/Icon Active Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product.st_3 > li.active > a i, 
                    {{WRAPPER}} .nav.tabs-product.st_3 > li.active > a:hover i, 
                    {{WRAPPER}} .nav.tabs-product.st_3 > li.active > a:focus i, 
                    {{WRAPPER}} .nav.tabs-product.st_3 > li:hover > a i, 
                    {{WRAPPER}} .nav.tabs-product.st_3 > li:hover > a:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_active_color',
            [
                'label' => esc_html__( 'Border Active Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .nav.tabs-product > li > a::before, {{WRAPPER}} .widget-title:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Tab Typography', 'rashy' ),
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .nav-tabs > li > a',
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => esc_html__( 'Dot Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .slick-dots li button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label' => esc_html__( 'Dot Active Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'section_box_style',
            [
                'label' => esc_html__( 'Box Style', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__( 'Box Primary Border Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .primary .product-block' => 'border-color: {{VALUE}} !important;',
                ],
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
            'line_color',
            [
                'label' => esc_html__( 'Countdown Line Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .special-progress .progress .progress-bar' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .goal-countdown-dark .times > div' => 'background-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .product-block.grid .woosw-btn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block .view .quickview:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .woosc-btn' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-block.grid .add-cart a.button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart a.added_to_cart' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-block.grid .add-cart a.button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product-block.grid .add-cart a.added_to_cart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .added_to_cart' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .added_to_cart:hover' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .added_to_cart:before' => 'background-color: {{VALUE}} ;', 
                    '{{WRAPPER}} .product .product-block.grid.grid-v3 .button:before' => 'background-color: {{VALUE}};', 
                    '{{WRAPPER}} .product-block.grid.grid-v3 .view .quickview' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid.grid-v3 .view .quickview:hover' => 'color: {{VALUE}} ;',
                    '{{WRAPPER}} .product-block.grid.grid-v3 .view:before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-block.grid .add-cart a.button, {{WRAPPER}} .product-block.grid .add-cart a.added_to_cart' => 'background-color: {{VALUE}};',
                   
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
                    '{{WRAPPER}} .product-block.grid .add-cart a.button:hover, {{WRAPPER}} .product-block.grid .add-cart a.added_to_cart:hover' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($tabs) ) {
            $_id = rashy_random_key();
            ?>
            <div class="widget woocommerce widget-products-tabs widget-products-tabs-special <?php echo esc_attr($el_class); ?>">
                
                <div class="widget-content <?php echo esc_attr($layout_type); ?>">
                    <div class="top-info-tabs <?php echo esc_attr($tab_type); ?>">
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

                        <ul role="tablist" class="nav nav-tabs tabs-product <?php echo esc_attr($tab_style); ?>" data-load="ajax">
                            <?php $i = 0; foreach ($tabs as $tab) : ?>
                                <li class="<?php echo esc_attr($i == 0 ? 'active' : '');?>">
                                    <a href="#tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>">
                                        <?php if ( !empty($tab['icon']) ) { ?>
                                            <i class="<?php echo esc_attr($tab['icon']); ?>"></i>
                                        <?php } ?>
                                        <?php if ( !empty($tab['title']) ) { ?>
                                            <span>
                                                <?php echo trim($tab['title']); ?>
                                            </span>
                                        <?php } ?>
                                    </a>
                                </li>
                            <?php $i++; endforeach; ?>
                        </ul>

                    </div>
                    <div class="widget-inner">
                        <div class="tab-content">
                            <?php $i = 0; foreach ($tabs as $tab) :
                                $settings['woo_product_tabs_special'] = 'woo_product_tabs_special';
                                $encoded_atts = json_encode( $settings );
                                $encoded_tab = json_encode( $tab );
                            ?>
                                <div id="tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>" class="tab-pane <?php if (rashy_get_config('colection_gutter', false)) { ?> colection_gutter <?php } ?>  <?php echo esc_attr($i == 0 ? 'active' : ''); ?>" data-loaded="<?php echo esc_attr($i == 0 ? 'true' : 'false'); ?>" data-settings="<?php echo esc_attr($encoded_atts); ?>" data-tab="<?php echo esc_attr($encoded_tab); ?>">

                                    <div class="tab-content-products ">
                                        <?php if ( $i == 0 ): ?>
                                            <div class="row">
                                                <?php
                                                $col_class_second = 12;
                                                $excludes = array();
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
                                                <div class="col-md-<?php echo esc_attr($col_class_second); ?> col-xs-12 second">
                                                    <?php
                                                        $slugs = !empty($tab['slugs']) ? array_map('trim', explode(',', $tab['slugs'])) : array();
                                                        $type = isset($tab['type']) ? $tab['type'] : 'recent_product';
                                                        $args = array(
                                                            'categories' => $slugs,
                                                            'product_type' => $type,
                                                            'post_per_page' => $limit,
                                                            'excludes' => $excludes,
                                                        );

                                                        $loop = rashy_get_products( $args );
                                                    ?>

                                                    <?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array(
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
                                                        'elementor_element' => true,
                                                    ) ); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php $i++; endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Woo_Product_Tabs_Special );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Woo_Product_Tabs_Special );
}