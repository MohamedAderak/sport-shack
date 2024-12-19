<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Team extends Widget_Base {

    public function get_name() {
        return 'rashy_team';
    }

    public function get_title() {
        return esc_html__( 'Goal Teams', 'rashy' );
    }

    public function get_icon() {
        return 'ti-user';
    }

    public function get_categories() {
        return [ 'rashy-elements' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Team', 'rashy' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Social Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Social Title' , 'rashy' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Social Link', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your social link here', 'rashy' ),
            ]
        );

        $repeater->add_control(
            'social_icon',
            [
                'label' => esc_html__( 'Icon', 'rashy' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'social',
                'label_block' => true,
                'default' => [
                    'value' => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
                'recommended' => [
                    'fa-brands' => [
                        'android',
                        'apple',
                        'behance',
                        'bitbucket',
                        'codepen',
                        'delicious',
                        'deviantart',
                        'digg',
                        'dribbble',
                        'elementor',
                        'facebook',
                        'flickr',
                        'foursquare',
                        'free-code-camp',
                        'github',
                        'gitlab',
                        'globe',
                        'google-plus',
                        'houzz',
                        'instagram',
                        'jsfiddle',
                        'linkedin',
                        'medium',
                        'meetup',
                        'mixcloud',
                        'odnoklassniki',
                        'pinterest',
                        'product-hunt',
                        'reddit',
                        'shopping-cart',
                        'skype',
                        'slideshare',
                        'snapchat',
                        'soundcloud',
                        'spotify',
                        'stack-overflow',
                        'steam',
                        'stumbleupon',
                        'telegram',
                        'thumb-tack',
                        'tripadvisor',
                        'tumblr',
                        'twitch',
                        'twitter',
                        'viber',
                        'vimeo',
                        'vk',
                        'weibo',
                        'weixin',
                        'whatsapp',
                        'wordpress',
                        'xing',
                        'yelp',
                        'youtube',
                        '500px',
                    ],
                    'fa-solid' => [
                        'envelope',
                        'link',
                        'rss',
                    ],
                ],
            ]
        );

        $this->add_control(
            'name', [
                'label' => esc_html__( 'Member Name', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Member Name' , 'rashy' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'job', [
                'label' => esc_html__( 'Member Job', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Member Job' , 'rashy' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image', 'rashy' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'rashy' ),
            ]
        );

        $this->add_control(
            'description', [
                'label' => esc_html__( 'Member Description', 'rashy' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Member Description' , 'rashy' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'social_icon_list',
            [
                'label' => esc_html__( 'Social Icons', 'rashy' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'social_icon' => [
                            'value' => 'fab fa-facebook',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-twitter',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-google-plus',
                            'library' => 'fa-brands',
                        ],
                    ],
                ],
                'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
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
            'section_icon_style',
            [
                'label' => esc_html__( 'Content', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


            $this->start_controls_tabs('style_tabs');

                $this->start_controls_tab(
                    'style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'rashy' ),
                    ]
                );

                $this->add_control(
                    'name_color',
                    [
                        'label' => esc_html__( 'Name Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .widget-team .name-team' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'job_color',
                    [
                        'label' => esc_html__( 'Job Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .widget-team .job' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'icon_color',
                    [
                        'label' => esc_html__( 'Icon Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .widget-team .top-image .social a svg' => 'fill: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'icon_bg',
                    [
                        'label' => esc_html__( 'Icon Bg Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .widget-team .top-image .social a' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'rashy' ),
                    ]
                );

                $this->add_control(
                    'icon_hv_color',
                    [
                        'label' => esc_html__( 'Icon Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .widget-team .top-image .social a:hover svg' => 'fill: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'icon_hv_bg',
                    [
                        'label' => esc_html__( 'Icon Bg Color', 'rashy' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .widget-team .top-image .social a:hover' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'border-radius',
                [
                    'label' => esc_html__( 'Border Radius Icon', 'rashy' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .widget-team .top-image .social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'width',
                [
                    'label' => esc_html__( 'Width Icon', 'rashy' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .widget-team .top-image .social a svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'height',
                [
                    'label' => esc_html__( 'Height Icon', 'rashy' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .widget-team .top-image .social a svg' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Icon Typography', 'rashy' ),
                    'name' => 'typography_icon',
                    'selector' => '{{WRAPPER}} .widget-team .top-image .social a ',
                ]
            );

            $this->add_responsive_control(
                'padding_inner',
                [
                    'label' => esc_html__( 'Padding Information', 'rashy' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .widget-team .top-image .social li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

           

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'label' => esc_html__( 'Title Typography', 'rashy' ),
                    'name' => 'typography_title',
                    'selector' => '{{WRAPPER}} .widget-team .name-team',
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();

        extract( $settings );
        $migration_allowed = Icons_Manager::is_migration_allowed();

        ?>
        <div class="widget widget-team <?php echo esc_attr($el_class); ?>">
            <div class="top-image">
                <?php
                if ( !empty($settings['img_src']['id']) ) {
                ?>
                    <div class="team-image">
                        <?php echo rashy_get_attachment_thumbnail($settings['img_src']['id'], 'full'); ?>
                    </div>
                <?php } ?>
                <ul class="social">
                <?php foreach ( $settings['social_icon_list'] as $index => $item ) { ?>
                    <li>
                        <a href="<?php echo esc_url($item['link']);?>" <?php echo trim(!empty($item['title']) ? 'title="'.$item['title'].'"' : ''); ?>>
                            
                            <?php 
                                $migrated = isset( $item['__fa4_migrated']['social_icon'] );
                                $is_new = empty( $item['social'] ) && $migration_allowed;
                                $social = '';

                                // add old default
                                if ( empty( $item['social'] ) && ! $migration_allowed ) {
                                    $item['social'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-wordpress';
                                }

                                if ( ! empty( $item['social'] ) ) {
                                    $social = str_replace( 'fa fa-', '', $item['social'] );
                                }

                                if ( ( $is_new || $migrated ) && 'svg' !== $item['social_icon']['library'] ) {
                                    $social = explode( ' ', $item['social_icon']['value'], 2 );
                                    if ( empty( $social[1] ) ) {
                                        $social = '';
                                    } else {
                                        $social = str_replace( 'fa-', '', $social[1] );
                                    }
                                }
                                if ( 'svg' === $item['social_icon']['library'] ) {
                                    $social = '';
                                }
                            ?>

                            <?php
                            if ( $is_new || $migrated ) {
                                Icons_Manager::render_icon( $item['social_icon'] );
                            } else { ?>
                                <i class="<?php echo esc_attr( $item['social'] ); ?>"></i>
                            <?php } ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            </div>
            <div class="content">
                <?php if ( !empty($name) ) { ?>
                    <h3 class="name-team"><?php echo trim($name); ?></h3>
                <?php } ?>
                <?php if ( !empty($job) ) { ?>
                    <div class="job"><?php echo trim($job); ?></div>
                <?php } ?>
            </div>
            
        </div>
        <?php
    }

}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Team );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Team );
}