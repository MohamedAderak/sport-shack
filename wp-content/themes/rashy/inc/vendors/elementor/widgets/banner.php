<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Banner extends Widget_Base {

    public function get_name() {
        return 'rashy_banner';
    }

    public function get_title() {
        return esc_html__( 'Goal Banner', 'rashy' );
    }
    
    public function get_categories() {
        return [ 'rashy-elements' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Banner', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'rashy' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-banner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sub_title',
            [
                'label' => esc_html__( 'Sub Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your Sub title here', 'rashy' ),
            ]
        );

        $this->add_control(
            'title1',
            [
                'label' => esc_html__( 'Title 1', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'rashy' ),
            ]
        );

        $this->add_control(
            'title2',
            [
                'label' => esc_html__( 'Title 2', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'rashy' ),
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
            'link',
            [
                'label' => esc_html__( 'URL', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Button Link here', 'rashy' ),
            ]
        );

        $this->add_responsive_control(
            'banner_align',
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
                    '{{WRAPPER}} .widget-banner' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'rashy'),
                    'style2' => esc_html__('Style 2', 'rashy'),
                    'style3' => esc_html__('Style 3', 'rashy'),
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
            'section_box',
            [
                'label' => esc_html__( 'Box', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'rashy' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .content-banner',
            ]
        );

        $this->add_responsive_control(
            'padding-box',
            [
                'label' => esc_html__( 'Padding', 'rashy' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rashy' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .widget-banner.style2 .wrapper-banner .content-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .widget-banner.style1 .wrapper-banner .content-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .widget-banner.style3 .wrapper-banner ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section1_title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'title1_color',
                [
                    'label' => esc_html__( 'Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .title1 ' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'title1_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-banner:hover .title1 ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'title1_typography',
                    'selector' => '{{WRAPPER}} .title1',
                ]
            );

            $this->add_responsive_control(
                'margin-title1',
                [
                    'label' => esc_html__( 'Margin', 'rashy' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .title1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'padding-title1',
                [
                    'label' => esc_html__( 'Padding', 'rashy' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .title1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title2',
            [
                'label' => esc_html__( 'Title 2', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'title2_color',
                [
                    'label' => esc_html__( 'Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .title2 ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'title2strong_color',
                [
                    'label' => esc_html__( 'Color (strong, span)', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .title2 span ' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .title2 strong ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'title2_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .widget-banner:hover .title2 ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'title2_typography',
                    'selector' => '{{WRAPPER}} .title2',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_sub_title',
            [
                'label' => esc_html__( 'Sub Title', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'sub_title_color',
                [
                    'label' => esc_html__( 'Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .sub-title ' => 'color: {{VALUE}};',
                        
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'sub_title_typography',
                    'selector' => '{{WRAPPER}} .sub-title',
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
                        '{{WRAPPER}} .link-bottom' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .link-bottom .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .btn-theme ' => 'background-image: linear-gradient(to right, {{VALUE}} 0%, {{VALUE}} 100%);',
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
                        '{{WRAPPER}} .widget-banner .btn-theme ' => 'background-color: {{VALUE}};',
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
                        '{{WRAPPER}} .btn-theme ' => 'color: {{VALUE}};',
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
                        '{{WRAPPER}} .widget-banner:hover .btn-theme ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'button_typography',
                    'selector' => '{{WRAPPER}} .btn-theme',
                ]
            );

            $this->add_control(
                'arrow_color',
                [
                    'label' => esc_html__( 'Arrow Color', 'rashy' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .btn-theme i' => 'color: {{VALUE}};',
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
                        '{{WRAPPER}} .widget-banner:hover .btn-theme i' => 'color: {{VALUE}};',
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
                        '{{WRAPPER}} .btn-theme i ' => 'background-color: {{VALUE}} ;',
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
                        '{{WRAPPER}} .widget-banner:hover .btn-theme i ' => 'background-color: {{VALUE}} ;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Typography', 'rashy' ),
                    'name' => 'arrow_typography',
                    'selector' => '{{WRAPPER}} .btn-theme i',
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <?php if($style == 'style3') { ?>
            <div class="widget-banner <?php echo esc_attr($el_class); ?> <?php echo esc_attr($style); ?>">
                <?php if ( !empty($link) ) { ?>
                    <a href="<?php echo esc_url($link); ?>">
                <?php } ?>
                    <div class="wrapper-banner ">
                        <div class="content-banner">
                        </div>
                         <div class="inner">
                            <?php if ( $sub_title ) { ?>
                                <span class="sub-title"><?php echo trim($sub_title); ?></span> 
                            <?php } ?>

                            <?php if ( $title1 ) { ?>
                                <h2 class="title1"><?php echo trim($title1); ?></h2>
                            <?php } ?>

                            <?php if ( $title2 ) { ?>
                                <p class="title2"><?php echo trim($title2); ?></p>
                            <?php } ?>

                            <?php if ( !empty($btn_text) ) { ?>
                                <div class="link-bottom">
                                    <span class="btn btn-theme"><?php echo trim($btn_text); ?><i class="icon-arrow-right" aria-hidden="true"></i></span>
                                </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                   
                <?php if ( !empty($link) ) { ?>
                    </a>
                <?php } ?>
            </div>
        <?php } elseif($style == 'style1' ) { ?>    
            <div class="widget-banner <?php echo esc_attr($el_class); ?> <?php echo esc_attr($style); ?>">
                <?php if ( !empty($link) ) { ?>
                    <a href="<?php echo esc_url($link); ?>">
                <?php } ?>
                    <div class="wrapper-banner ">
                        <div class="content-banner">
                        </div>
                        <div class="inner">
                            <?php if ( $sub_title ) { ?>
                                <span class="sub-title"><?php echo trim($sub_title); ?></span> 
                            <?php } ?>

                            <?php if ( $title1 ) { ?>
                                <h2 class="title1"><?php echo trim($title1); ?></h2>
                            <?php } ?>

                            <?php if ( $title2 ) { ?>
                                <p class="title2"><?php echo trim($title2); ?></p>
                            <?php } ?>

                            <?php if ( !empty($btn_text) ) { ?>
                                <div class="link-bottom">
                                    <span class="btn btn-theme"><?php echo trim($btn_text); ?><i class="icon-arrow-right" aria-hidden="true"></i></span>
                                </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                    
                <?php if ( !empty($link) ) { ?>
                    </a>
                <?php } ?>
            </div>
        <?php } elseif($style == 'style2' ) { ?>    
            <div class="widget-banner <?php echo esc_attr($el_class); ?> <?php echo esc_attr($style); ?>">
                <?php if ( !empty($link) ) { ?>
                    <a href="<?php echo esc_url($link); ?>">
                <?php } ?>
                    <div class="wrapper-banner ">
                        <div class="content-banner">
                        </div>
                         <div class="inner">
                            <?php if ( $sub_title ) { ?>
                                <span class="sub-title"><?php echo trim($sub_title); ?></span> 
                            <?php } ?>

                            <?php if ( $title1 ) { ?>
                                <h2 class="title1"><?php echo trim($title1); ?></h2>
                            <?php } ?>

                            <?php if ( $title2 ) { ?>
                                <p class="title2"><?php echo trim($title2); ?></p>
                            <?php } ?>

                            <?php if ( !empty($btn_text) ) { ?>
                                <div class="link-bottom">
                                    <span class="btn btn-theme"><?php echo trim($btn_text); ?><i class="icon-arrow-right" aria-hidden="true"></i></span>
                                </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                   
                <?php if ( !empty($link) ) { ?>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
        <?php
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Banner );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Banner );
}