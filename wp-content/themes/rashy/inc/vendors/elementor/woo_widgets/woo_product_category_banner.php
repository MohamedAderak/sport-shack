<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Woo_Category_Banner extends Widget_Base {

	public function get_name() {
        return 'rashy_woo_category_banner';
    }

	public function get_title() {
        return esc_html__( 'Goal Product Category Banner', 'rashy' );
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
                    '{{WRAPPER}} .widget-category-banner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'This is the heading', 'rashy' ),
                'placeholder' => esc_html__( 'Enter your title', 'rashy' ),
            ]
        );

        $this->add_control(
            'slug', [
                'label' => esc_html__( 'Category Slug', 'rashy' ),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'link_url',
            [
                'label' => esc_html__( 'Link', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'rashy' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'rashy' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your button text', 'rashy' ),
                'default' => 'SHOP NOW',
            ]
        );

        $this->add_control(
            'show_nb_products',
            [
                'label' => esc_html__( 'Show Number Products', 'rashy' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
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
            'show_subcategories',
            [
                'label' => esc_html__( 'Show SubCategories', 'rashy' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
                'condition' => [
                    'style' => ['style1', 'style3'],
                ],
            ]
        );

        $this->add_control(
            'number_subcategories',
            [
                'label' => esc_html__( 'Number SubCategories', 'rashy' ),
                'type' => Controls_Manager::NUMBER,
                'placeholder' => esc_html__( 'Enter number subcategories', 'rashy' ),
                'default' => 5,
                'condition' => [
                    'style' => ['style1', 'style3'],
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
            'section_title_style',
            [
                'label' => esc_html__( 'Style', 'rashy' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'rashy' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .widget-category-banner',
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

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .item-inner-categories:hover .title' => 'color: {{VALUE}};',
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
            'subtitle_color',
            [
                'label' => esc_html__( 'Sub Title Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-category-banner .subcategories li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .widget-category-banner .subcategories li a:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_hover_color',
            [
                'label' => esc_html__( 'Sub Title Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-category-banner .subcategories li a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .widget-category-banner .subcategories li a:hover:before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .widget-category-banner .subcategories li a:focus:before' => 'background-color: {{VALUE}};', 
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Sub Title Typography', 'rashy' ),
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .widget-category-banner .subcategories li a',
            ]
        );



        $this->add_control(
            'nb_color',
            [
                'label' => esc_html__( 'Number item Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .product-nb' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Number item Typography', 'rashy' ),
                'name' => 'nb_typography',
                'selector' => '{{WRAPPER}} .product-nb',
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__( 'Button Background Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn-banner ' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hover_color',
            [
                'label' => esc_html__( 'Button Background Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-category-banner:hover .btn-banner ' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_color',
            [
                'label' => esc_html__( 'Button Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn-banner ' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__( 'Button Hover Color', 'rashy' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-category-banner:hover .btn-banner ' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Button Typography', 'rashy' ),
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .btn-banner',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <?php if($style == 'style3') { ?>
            <div class="widget-category-banner <?php echo esc_attr($el_class); ?> <?php echo esc_attr($style); ?>"></div>
            <div class="widget-category-v3">
                <?php
                $html = $link = '';
                $title_html = $title;
                $term = get_term_by('slug', $slug, 'product_cat');
                
                if ( ! empty( $link_url ) ) {
                    $link = $link_url;
                }

                $html .= '<div class="category-box-content">';

                if ( ! empty( $link ) ) {
                    $html .= '<a href="'.esc_url($link).'"><h3 class="title">'.$title_html.'</h3></a>';
                } else {
                    $html .= sprintf( '<h3 class="title">%1$s</h3>', $title_html );
                }

                if ( $term ) {
                    $link = get_term_link($term);
                    if ( empty($title_html) ) {
                        $title_html = $term->name;
                    }
                    if ( $show_subcategories ) {
                        $terms_children = get_terms( 'product_cat',
                            array(
                                'parent'        => $term->term_id,
                                'hierarchical'  => true,
                                'hide_empty'    => false,
                                'number'    => $number_subcategories,
                            )
                        );
                        if ( ! empty( $terms_children ) && ! is_wp_error( $terms_children ) ) {
                            $html .= '<ul class="subcategories">';
                                foreach ($terms_children as $term_children) {
                                    $html .= '<li><a href="'.get_term_link($term_children).'">'.$term_children->name.'</li>';
                                }
                            $html .= '</ul>';
                        }
                    }
                }

                if ( $show_nb_products && $term ) {
                    $html .= '<div class="product-nb">'.sprintf(_n('%d Product', '%d Products', $term->count, 'rashy'), $term->count).'</div>';
                }

                if( !empty( $link )){
                    $html .= '<a class="text-theme link-v3" href="'.esc_url($link).'">'. $btn_text .'</a>';
                }
                $html .= '</div>';

                echo trim($html);
                ?>

            </div>
        <?php }else{ ?>
            <div class="widget-category-banner <?php echo esc_attr($el_class); ?> <?php echo esc_attr($style); ?>">
                
                <div class="item-inner">
                    <?php
                    $html = $link = '';
                    $title_html = $title;
                    $term = get_term_by('slug', $slug, 'product_cat');
                    
                    if ( ! empty( $link_url ) ) {
                        $link = $link_url;
                    }

                    $html .= '<div class="category-box-content">';

                    if ( ! empty( $link ) ) {
                        $html .= '<a href="'.esc_url($link).'"><h3 data-hover="'.$title_html.'" class="title">'.$title_html.'</h3></a>';
                    } else {
                        $html .= sprintf( '<h3 class="title">%1$s</h3>', $title_html );
                    }

                    if ( $term ) {
                        $link = get_term_link($term);
                        if ( empty($title_html) ) {
                            $title_html = $term->name;
                        }
                        if (  ($style == 'style3' || $style == 'style1') && $show_subcategories ) {
                            $terms_children = get_terms( 'product_cat',
                                array(
                                    'parent'        => $term->term_id,
                                    'hierarchical'  => true,
                                    'hide_empty'    => false,
                                    'number'    => $number_subcategories,
                                )
                            );
                            if ( ! empty( $terms_children ) && ! is_wp_error( $terms_children ) ) {
                                $html .= '<ul class="subcategories">';
                                    foreach ($terms_children as $term_children) {
                                        $html .= '<li><a href="'.get_term_link($term_children).'">'.$term_children->name.'</a></li>';
                                    }
                                $html .= '</ul>';
                            }
                        }
                    }

                    

                    if ( $show_nb_products && $term ) {
                        $html .= '<div class="product-nb">'.sprintf(_n('%d Product', '%d Products', $term->count, 'rashy'), $term->count).'</div>';
                    }

                    
                    $html .= '</div>';

                    echo trim($html);
                    ?>

                </div>
                <?php
                if ( ! empty( $link ) ) {
                ?>
                    <div class="more-categories">
                        <a href="<?php echo esc_url($link); ?>" class="btn btn-banner radius-5"><?php echo esc_html__('View More','rashy'); ?>
                            <i class="fas fa-angle-double-right" aria-hidden="true"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php
    }
}
if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Woo_Category_Banner );
} else {
    Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Woo_Category_Banner );
}