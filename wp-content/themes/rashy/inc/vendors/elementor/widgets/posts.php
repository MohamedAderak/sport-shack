<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rashy_Elementor_Posts extends Elementor\Widget_Base {

	public function get_name() {
        return 'rashy_posts';
    }

	public function get_title() {
        return esc_html__( 'Goal Posts', 'rashy' );
    }
    
	public function get_categories() {
        return [ 'rashy-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Posts', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'rashy' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'rashy' ),
            ]
        );

        $this->add_control(
            'sub_title', [
                'label' => esc_html__( 'Widget Sub Title', 'rashy' ),
                'type' => Elementor\Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'sub_text', [
                'label' => esc_html__( 'Sub Text', 'rashy' ),
                'type' => Elementor\Controls_Manager::TEXTAREA
            ]
        );

        $this->add_control(
            'title_type',
            [
                'label' => esc_html__( 'Position Title', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'left' => esc_html__('Left', 'rashy'),
                    'center' => esc_html__('Center', 'rashy'),
                ),
                'default' => 'center'
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => esc_html__( 'Number', 'rashy' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'input_type' => 'number',
                'description' => esc_html__( 'Number posts to display', 'rashy' ),
                'default' => 4
            ]
        );
        
        $this->add_control(
            'order_by',
            [
                'label' => esc_html__( 'Order by', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'rashy'),
                    'date' => esc_html__('Date', 'rashy'),
                    'ID' => esc_html__('ID', 'rashy'),
                    'author' => esc_html__('Author', 'rashy'),
                    'title' => esc_html__('Title', 'rashy'),
                    'modified' => esc_html__('Modified', 'rashy'),
                    'rand' => esc_html__('Random', 'rashy'),
                    'comment_count' => esc_html__('Comment count', 'rashy'),
                    'menu_order' => esc_html__('Menu order', 'rashy'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Sort order', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'rashy'),
                    'ASC' => esc_html__('Ascending', 'rashy'),
                    'DESC' => esc_html__('Descending', 'rashy'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'slugs',
            [
                'label' => esc_html__( 'Categories Slug', 'rashy' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter slug spearate by comma(,)', 'rashy' ),
            ]
        );

        $columns = range( 1, 12 );
        $columns = array_combine( $columns, $columns );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'frontend_available' => true,
                'default' => 3,
                'condition' => [
                    'layout_type' => ['grid', 'carousel'],
                ],
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'rashy'),
                    'carousel' => esc_html__('Carousel', 'rashy'),
                    'sticky' => esc_html__('Sticky', 'rashy')
                ),
                'default' => 'grid'
            ]
        );

        $this->add_control(
            'item_type',
            [
                'label' => esc_html__( 'Item Style', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid-home' => esc_html__('Grid', 'rashy'),
                    'list-home' => esc_html__('List', 'rashy'),
                ),
                'default' => 'grid'
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__( 'Slides to Scroll', 'rashy' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'rashy' ),
                'options' => $columns,
                'condition' => [
                    'columns!' => '1',
                    'layout_type' => 'carousel',
                ],
                'frontend_available' => true,
                'default' => 3,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'rashy' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your rows number here', 'rashy' ),
                'default' => 1,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label' => esc_html__( 'Show Nav', 'rashy' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'rashy' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'rashy' ),
                'label_off' => esc_html__( 'Show', 'rashy' ),
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'rashy' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
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
                'type'          => Elementor\Controls_Manager::SWITCHER,
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
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'rashy' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'rashy' ),
            ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
            'section_widget_title_style',
            [
                'label' => esc_html__( 'Widget Title Style', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-blogs-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__( 'Sub Title Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .sub-widget-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sub_text_color',
            [
                'label' => esc_html__( 'Sub Text Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .sub-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'rashy' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .widget-title h3',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_post_style',
            [
                'label' => esc_html__( 'Post Style', 'rashy' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'post_title_color',
            [
                'label' => esc_html__( 'Post Title Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'post_title_hv_color',
            [
                'label' => esc_html__( 'Post Title Hover Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .entry-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Title Typography', 'rashy' ),
                'name' => 'post_title_typography',
                'selector' => '{{WRAPPER}} .post-layout .entry-title a',
            ]
        );

        $this->add_control(
            'post_excerpt_color',
            [
                'label' => esc_html__( 'Post Excerpt Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Excerpt Typography', 'rashy' ),
                'name' => 'post_excerpt_typography',
                'selector' => '{{WRAPPER}} .post .description',
            ]
        );

        $this->add_control(
            'post_top_info_color',
            [
                'label' => esc_html__( 'Post Top Info Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .top-info a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .post-layout .top-info span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'post_top_info_hv_color',
            [
                'label' => esc_html__( 'Post Top Info Hover Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .top-info a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Top Info Typography', 'rashy' ),
                'name' => 'post_top_info_typography',
                'selector' => '{{WRAPPER}} .post-layout .top-info a',
            ]
        );


        $this->add_control(
            'post_readmore_color',
            [
                'label' => esc_html__( 'Post Read More Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'post_readmore_bg_color',
            [
                'label' => esc_html__( 'Post Read More Background Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore' => 'background-image: linear-gradient(to right, {{VALUE}} 0%, {{VALUE}} 100%);',
                ],
            ]
        );

        $this->add_control(
            'post_readmore_hv_color',
            [
                'label' => esc_html__( 'Post Read More Hover Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore:hover' => 'color: {{VALUE}};',
                ],
            ]
        );


         $this->add_control(
            'post_readmore_bg_hv_color',
            [
                'label' => esc_html__( 'Post Read More Background Hover Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Read More Typography', 'rashy' ),
                'name' => 'post_readmore_typography',
                'selector' => '{{WRAPPER}} .post .readmore',
            ]
        ); 

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__( 'Post Read More Arrow Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => esc_html__( 'Post Read More Arrow Hover Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => esc_html__( 'Post Read More Arrow Background Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore i ' => 'background-color: {{VALUE}} ;',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => esc_html__( 'Post Read More Arrow Hover Background Color', 'rashy' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post-layout .post-info .readmore:hover i ' => 'background-color: {{VALUE}} ;',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'rashy' ),
                'name' => 'arrow_typography',
                'selector' => '{{WRAPPER}} .post-layout .post-info .readmore i',
            ]
        );

        

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $number,
            'orderby' => $order_by,
            'order' => $order,
        );

        $slugs = !empty($slugs) ? array_map('trim', explode(',', $slugs)) : array();
        if ( !empty($slugs) && is_array($slugs) ) {
            $args['tax_query'][] = array(
                'taxonomy'      => 'category',
                'field'         => 'slug',
                'terms'         => $slugs,
                'operator'      => 'IN'
            );
        }

        $loop = new WP_Query($args);
        if ( $loop->have_posts() ) {
            if ( $image_size == 'custom' ) {
                
                if ( $image_custom_dimension['width'] && $image_custom_dimension['height'] ) {
                    $thumbsize = $image_custom_dimension['width'].'x'.$image_custom_dimension['height'];
                } else {
                    $thumbsize = 'full';
                }
            } else {
                $thumbsize = $image_size;
            }
            set_query_var( 'thumbsize', $thumbsize );
            $rows = isset($rows) ? $rows : 1;
            $columns = !empty($columns) ? $columns : 3;
            $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 3;
            $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 2;

            $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
            $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
            $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;
            ?>
            <div class="widget widget-blogs widget-<?php echo esc_attr($layout_type); ?> <?php echo esc_attr($el_class); ?>">
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
                <div class="widget-content">
                    
                    <?php if ( $layout_type == 'carousel' ): ?>
                        <div class="slick-carousel <?php echo esc_attr($columns < $loop->post_count?'':'hidden-dots'); ?>"
                                data-rows="<?php echo esc_attr( $rows ); ?>"
                                data-items="<?php echo esc_attr($columns); ?>"
                                data-smallmedium="<?php echo esc_attr( $columns_tablet ); ?>"
                                data-extrasmall="<?php echo esc_attr($columns_mobile); ?>"

                                data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
                                data-slidestoscroll_smallmedium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                                data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

                                data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>" data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>">
                            <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
                                <div class="item">
                                    <?php get_template_part( 'template-posts/loop/inner',$item_type); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php elseif ( $layout_type == 'sticky' ): ?>

                        <div class="layout-blog  style-sticky">
                             <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
                                <div class="post-sticky">
                                    <?php get_template_part( 'template-posts/loop/inner',$item_type); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>    
                    <?php else: ?>
                        <?php $bcol = 12/$columns; ?>
                        <div class="layout-blog  style-grid">
                            <div class="row">
                                <?php
                                    $mdcol = 12/$columns;
                                    $smcol = 12/$columns_tablet;
                                    $xscol = 12/$columns_mobile;
                                    while ( $loop->have_posts() ) : $loop->the_post();
                                ?>
                                    <div class="col-md-<?php echo esc_attr($mdcol); ?> col-sm-<?php echo esc_attr($smcol); ?> col-xs-<?php echo esc_attr($xscol); ?>">
                                        <?php get_template_part( 'template-posts/loop/inner',$item_type); ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
            <?php
        }

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Rashy_Elementor_Posts );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Rashy_Elementor_Posts );
}