<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Testimonials extends Widget_Base {

    public function get_name() {
        return 'rashy_testimonials';
    }

    public function get_title() {
        return esc_html__( 'Goal Testimonials', 'rashy' );
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
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Choose Image', 'rashy' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Brand Image', 'rashy' ),
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => esc_html__( 'Name', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );


        $repeater->add_control(
            'content', [
                'label' => esc_html__( 'Content', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA
            ]
        );

        $repeater->add_control(
            'job',
            [
                'label' => esc_html__( 'Job', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'star',
            [
                'label' => esc_html__( 'Star Scores', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link To', 'rashy' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'Enter your social link here', 'rashy' ),
                'placeholder' => esc_html__( 'https://your-link.com', 'rashy' ),
            ]
        );


        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__( 'Testimonials', 'rashy' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
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
                'default' => 1,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__( 'Slides to Scroll', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'rashy' ),
                'options' => $columns,
                'frontend_available' => true,
                'default' => 1,
            ]
        );


        $this->add_control(
            'show_nav',
            [
                'label' => esc_html__( 'Show Nav', 'rashy' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'rashy' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
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
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'rashy' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'rashy'),
                    'style2' => esc_html__('Style 2', 'rashy'),
                ),
                'default' => 'style1'
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
                    '{{WRAPPER}} .widget.widget-testimonials' => 'text-align: {{VALUE}};',
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

        $this->start_controls_section(
            'section_box_style',
            [
                'label' => esc_html__( 'Style Box', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_background_color',
            [
                'label' => esc_html__( 'Box Background Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-testimonials .testimonials-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border_box',
                'label' => esc_html__( 'Border Box', 'rashy' ),
                'selector' => '{{WRAPPER}} [class*="testimonials-content"]',

            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rashy' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .widget-testimonials .testimonials-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style Info', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

       


        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}} !important;',
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
            'test_title_color',
            [
                'label' => esc_html__( 'Name Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .name-client' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .name-client a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Name Typography', 'rashy' ),
                'name' => 'test_title_typography',
                'selector' => '{{WRAPPER}} .name-client',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Content Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Content Typography', 'rashy' ),
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .description',
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label' => esc_html__( 'Job Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Job Typography', 'rashy' ),
                'name' => 'job_typography',
                'selector' => '{{WRAPPER}} .job',
            ]
        );
        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dots_style',
            [
                'label' => esc_html__( 'Style Dots', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => esc_html__( 'Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-carousel .slick-dots li button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => esc_html__( 'Color Active', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-carousel .slick-dots .slick-active button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        // $columns = !empty($columns) ? $columns : 1;
        // $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 1;
        // $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;
        
        if ( !empty($testimonials) ) {
            $columns = !empty($columns) ? $columns : 3;
            $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 3;
            $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;

            $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
            $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
            $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;
            ?>
            <div class="widget widget-testimonials <?php echo esc_attr($el_class.' '.$layout_type); ?> <?php echo esc_attr($show_nav ? 'show_nav' : ''); ?> ">
            <?php if($layout_type == 'style2') { ?>
            <div class="slick-carousel testimonial-main" 
                            data-items="<?php echo esc_attr($columns); ?>"
                            data-smallmedium="<?php echo esc_attr( $columns_tablet ); ?>"
                            data-extrasmall="<?php echo esc_attr($columns_mobile); ?>"
                            data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>" 
                            data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" 
                            data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>"
                            data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>"
                            data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>" 
                            data-slidestoscroll_smallmedium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>" 
                            data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>">

                        <?php foreach ($testimonials as $item) { ?>
                        <div class="item">

                            <div class="testimonials-item testimonials-item2 testimonials-content">
                                <div class="clearfix">
                                    <div class="info-testimonials"> 
                                        <div class="top-info">
                                            <?php if ( !empty($item['star']) ) { ?>
                                                <div class="star d-flex align-items-center">
                                                    <!-- <span class="text"><?php echo number_format($item['star'], 1, '.', ''); ?> </span> -->
                                                    <div class="inner">
                                                        <div class="w-percent" style="width: <?php echo trim($item['star']*20)?>%;"></div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ( !empty($item['title']) ) { ?>
                                                <h4 class="title"><?php echo trim($item['title']); ?></h4>
                                            <?php } ?> 
                                        </div>

                                        <?php if ( !empty($item['content']) ) { ?>
                                            <div class="description">
                                                <?php echo trim($item['content']); ?>
                                            </div>
                                        <?php } ?>

                                        <div class="bottom-info flex-middle">
                                            <?php
                                                $img_src = ( isset( $item['img_src']['id'] ) && $item['img_src']['id'] != 0 ) ? wp_get_attachment_url( $item['img_src']['id'] ) : '';
                                                if ( $img_src ) {
                                                ?>
                                                    <div class="avarta">
                                                        <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr(!empty($item['name']) ? $item['name'] : ''); ?>">
                                                    </div>
                                            <?php } ?>
                                            <div class="bottom-info-right">
                                                <div class="info-testimonials">
                                                     <?php if ( !empty($item['name']) ) {
                                                        $title = '<h3 class="name-client">'.$item['name'].'</h3>';
                                                        if ( ! empty( $item['link']['url'] ) ) {
                                                            $title = sprintf( '<h3 class="name-client"><a href="'.esc_url($item['link']['url']).'" target="'.esc_attr($item['link']['is_external'] ? '_blank' : '_self').'" '.($item['link']['nofollow'] ? 'rel="nofollow"' : '').'>%1$s</a></h3>', $item['name'] );
                                                        }
                                                        echo trim($title);
                                                    ?>
                                                    <?php } ?>
                                                    <?php if ( !empty($item['job']) ) { ?>
                                                        <div class="job"><?php echo esc_html($item['job']); ?></div>
                                                    <?php } ?>  
                                                </div>

                                                
                                             </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php } ?>
                    </div>
                       

                <?php } elseif($layout_type == 'style1' ) { ?>

                    <div class="slick-carousel testimonial-main" 
                            data-items="<?php echo esc_attr($columns); ?>"
                            data-smallmedium="<?php echo esc_attr( $columns_tablet ); ?>"
                            data-extrasmall="<?php echo esc_attr($columns_mobile); ?>"
                            data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>" 
                            data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" 
                            data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>"
                            data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>">
                        <?php foreach ($testimonials as $item) { ?>
                        <div class="item">

                            <div class="testimonials-item testimonials-item1 testimonials-content">
                                <div class="clearfix">
                                    <div class="info-testimonials"> 
                                        <div class="top-info flex-middle">
                                            <?php
                                                $img_src = ( isset( $item['img_src']['id'] ) && $item['img_src']['id'] != 0 ) ? wp_get_attachment_url( $item['img_src']['id'] ) : '';
                                                if ( $img_src ) {
                                                ?>
                                                    <div class="avarta">
                                                        <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr(!empty($item['name']) ? $item['name'] : ''); ?>">
                                                    </div>
                                            <?php } ?>
                                            <div class="top-info-right">
                                                <div class="info-testimonials">
                                                     <?php if ( !empty($item['name']) ) {
                                                        $title = '<h3 class="name-client">'.$item['name'].'</h3>';
                                                        if ( ! empty( $item['link']['url'] ) ) {
                                                            $title = sprintf( '<h3 class="name-client"><a href="'.esc_url($item['link']['url']).'" target="'.esc_attr($item['link']['is_external'] ? '_blank' : '_self').'" '.($item['link']['nofollow'] ? 'rel="nofollow"' : '').'>%1$s</a></h3>', $item['name'] );
                                                        }
                                                        echo trim($title);
                                                    ?>
                                                    <?php } ?>
                                                    <?php if ( !empty($item['job']) ) { ?>
                                                        <div class="job"><?php echo esc_html($item['job']); ?></div>
                                                    <?php } ?>  
                                                </div>

                                                
                                             </div>
                                        </div>
                                        
                                        <div class="bottom-info">
                                            <div class="clearfix">
                                                <?php if ( !empty($item['star']) ) { ?>
                                                    <div class="star d-flex align-items-center">
                                                        <!-- <span class="text"><?php echo number_format($item['star'], 1, '.', ''); ?> </span> -->
                                                        <div class="inner">
                                                            <div class="w-percent" style="width: <?php echo trim($item['star']*20)?>%;"></div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php if ( !empty($item['title']) ) { ?>
                                                <h4 class="title"><?php echo trim($item['title']); ?></h4>
                                            <?php } ?> 
                                        </div>

                                        <?php if ( !empty($item['content']) ) { ?>
                                            <div class="description">
                                                <?php echo trim($item['content']); ?>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php } ?>
                    </div>
                
                
                <?php } ?>
            </div>
            <?php
        }
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Testimonials );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Testimonials );
}
