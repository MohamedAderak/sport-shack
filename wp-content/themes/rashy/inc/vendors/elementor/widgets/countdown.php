<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Countdown extends Widget_Base {

	public function get_name() {
        return 'rashy_countdown';
    }

	public function get_title() {
        return esc_html__( 'Goal Countdown', 'rashy' );
    }
    
	public function get_categories() {
        return [ 'rashy-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Countdown', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title here', 'rashy' ),
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => esc_html__( 'Price', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your Price here', 'rashy' ),
            ]
        );
        $this->add_control(
            'des',
            [
                'label' => esc_html__( 'Content', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your content here', 'rashy' ),
            ]
        );
        $this->add_control(
            'end_date', [
                'label' => esc_html__( 'End Date', 'rashy' ),
                'type' => Controls_Manager::DATE_TIME,
                'picker_options' => [
                    'enableTime' => false
                ]
            ]
        );
        
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'rashy' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'rashy' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'rashy' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'rashy' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'rashy' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget-countdown' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'URL', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Button Link here', 'rashy' ),
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your button text here', 'rashy' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'rashy'),
                    'style2' => esc_html__('Style 2(showdow)', 'rashy'),
                    'style3' => esc_html__('Style 3(circle)', 'rashy'),
                ),
                'default' => 'style1'
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
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'rashy' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Description Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .des' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Description Typography', 'rashy' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .des',
            ]
        );

        $this->add_control(
            'times_color',
            [
                'label' => esc_html__( 'Times Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-countdown .goal-countdown .times > div' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .goal-countdown .times > div span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'times_bg_color',
            [
                'label' => esc_html__( 'Times Background Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-countdown.style2 .goal-countdown .times > div' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .widget-countdown.style3 .goal-countdown .times > div' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Number Typography', 'rashy' ),
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .widget-countdown .goal-countdown .times > div span',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Text Typography', 'rashy' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .widget-countdown .goal-countdown .times > div',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Button', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'margin-link-bottom',
                [
                    'label' => esc_html__( 'Margin', 'rashy' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'padding-link-bottom',
                [
                    'label' => esc_html__( 'Padding', 'rashy' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );


            $this->add_control(
                'button_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn ' => 'background-image: linear-gradient(to right, {{VALUE}} 0%, {{VALUE}} 100%);',
                    ],
                ]
            );
            $this->add_control(
                'button_bg_hover_color',
                [
                    'label' => esc_html__( 'Background Hover Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn:hover ' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

           
            
            $this->add_control(
                'button_color',
                [
                    'label' => esc_html__( 'Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn ' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'button_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn:hover ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'button_typography',
                    'selector' => '{{WRAPPER}} .widget-countdown .url-bottom .btn',
                ]
            );

            $this->add_control(
                'arrow_color',
                [
                    'label' => esc_html__( 'Arrow Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'arrow_hover_color',
                [
                    'label' => esc_html__( 'Arrow Hover Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'arrow_bg_color',
                [
                    'label' => esc_html__( 'Arrow Background Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn i ' => 'background-color: {{VALUE}} ;',
                    ],
                ]
            );

            $this->add_control(
                'arrow_hover_bg_color',
                [
                    'label' => esc_html__( 'Arrow Hover Background Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-countdown .url-bottom .btn:hover i ' => 'background-color: {{VALUE}} ;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'arrow_typography',
                    'selector' => '{{WRAPPER}} .widget-countdown .url-bottom .btn i',
                ]
            );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        $end_date = !empty($end_date) ? strtotime($end_date) : '';
        if ( $end_date ) {
            ?>
            <div class="widget-countdown <?php echo esc_attr($el_class.' '.$style); ?>">
                <div class="widget-countdown-inner">
                    <?php if ( !empty($title) ) { ?>
                        <h2 class="title"><?php echo wp_kses_post($title); ?></h2>
                    <?php } ?>
                    <?php if ( !empty($price) ) { ?>
                        <div class="price"><?php echo wp_kses_post($price); ?></div>
                    <?php } ?>
                    <?php if ( !empty($des) ) { ?>
                        <div class="des"><?php echo wp_kses_post($des); ?></div>
                    <?php } ?>
                    <div class="time-wrapper clearfix">
                        <div class="goal-countdown clearfix" data-time="timmer"
                            data-date="<?php echo date('m', $end_date).'-'.date('d', $end_date).'-'.date('Y', $end_date).'-'. date('H', $end_date) . '-' . date('i', $end_date) . '-' .  date('s', $end_date) ; ?>">
                        </div>
                    </div>

                    <?php if ( !empty($btn_text) && !empty($link) ) { ?>
                        <div class="url-bottom">
                            <a href="<?php echo esc_url($link); ?>" class="btn btn-theme"><?php echo wp_kses_post($btn_text); ?><i class="icon-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <?php
        }
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Countdown );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Countdown );
}