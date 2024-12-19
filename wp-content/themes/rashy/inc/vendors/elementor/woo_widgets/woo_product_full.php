<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Woo_Product_Full extends Widget_Base {

	public function get_name() {
        return 'rashy_element_woo_product_full';
    }

	public function get_title() {
        return esc_html__( 'Goal Product Full', 'rashy' );
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
            'product_id', [
                'label' => esc_html__( 'Product ID', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your product id here', 'rashy' ),
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
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Widget Title Typography', 'rashy' ),
                'name' => 'widget_title_typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );


        $this->add_control(
            'product_title_color',
            [
                'label' => esc_html__( 'Product Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .woocommerce div.product .product_title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Product Title Typography', 'rashy' ),
                'name' => 'product_title_typography',
                'selector' => '{{WRAPPER}} .woocommerce div.product .product_title',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Price Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .details-product .information .price' => 'color: {{VALUE}}; ',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Price Typography', 'rashy' ),
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .details-product .information .price !important;',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce div.product p.price del' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce div.product span.price del' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .variations label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Description Typography', 'rashy' ),
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .woocommerce-product-details__short-description',
            ]
        );

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        $product_id = !empty($product_id) ? $product_id : '';

        if ( $product_id && ($post_object = get_post( $product_id )) ) {
            
        ?>
            <div class="woocommerce widget-product-full <?php echo esc_attr($el_class); ?>">
                <?php if ( !empty($title) ): ?>
                    <h3 class="widget-title">
                        <?php echo esc_attr( $title ); ?>
                    </h3>
                <?php endif; ?>
                <div class="widget-content">
                    
                    <?php
                    
                    setup_postdata( $GLOBALS['post'] =& $post_object );

                    ?>
                        <div <?php wc_product_class( '', $post_object ); ?>>
                            <?php wc_get_template_part( 'item-product/inner-full' ); ?>
                        </div>
                    <?php

                    wp_reset_postdata();
                    ?>

                </div>
            </div>
            <?php
        }
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Woo_Product_Full );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Woo_Product_Full );
}