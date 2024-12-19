<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Testimonials_Special extends Widget_Base {

    public function get_name() {
        return 'rashy_testimonials_special';
    }

    public function get_title() {
        return esc_html__( 'Goal Testimonials Special', 'rashy' );
    }

    public function get_icon() {
        return 'eicon-testimonial';
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

        $repeater = new Repeater();
        

        $repeater->add_control(
            'description', [
                'label' => esc_html__( 'Description', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Description' , 'rashy' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'info',
            [
                'label' => esc_html__( 'Info', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'testimonials_special',
            [
                'label' => esc_html__( 'Testimonials Special', 'rashy' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'left' => esc_html__('Style 1', 'rashy'),
                    'right' => esc_html__('Style 2', 'rashy'),
                ),
                'default' => 'right'
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
                'label' => esc_html__( 'Style', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'test_description_color',
            [
                'label' => esc_html__( 'Description Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Description Typography', 'rashy' ),
                'name' => 'test_description_typography',
                'selector' => '{{WRAPPER}} .description',
            ]
        );

        $this->add_control(
            'test_info_color',
            [
                'label' => esc_html__( 'Info Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .name-client' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .widget-testimonials-special .item .name-client::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Info Typography', 'rashy' ),
                'name' => 'test_info_typography',
                'selector' => '{{WRAPPER}} .name-client',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($testimonials_special) ) {?>
            <div class="widget-testimonials-special anim-loop-<?php echo esc_attr($style); ?> <?php echo esc_attr($el_class); ?>">
                <?php foreach ($testimonials_special as $item) { ?>
                    <div class="item">
                        <?php if ( !empty($item['description']) ) { ?>
                            <h5 class="description"><?php echo trim($item['description']); ?></h5>
                        <?php } ?>
                        <?php if ( !empty($item['info']) ) { ?>
                            <div class="name-client"><?php echo trim($item['info']); ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Testimonials_Special );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Testimonials_Special );
}