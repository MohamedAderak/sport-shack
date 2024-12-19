<?php
    $columns = rashy_get_config('blog_columns', 1);
    $bcol = floor( 12 / $columns );
    $count = 1;
    $args['inner_item'] = !isset($args['inner_item']) ? 'grid' : $args['inner_item'];
?>
<div class="layout-blog">
    <div class="row row-blog">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="col-sm-<?php echo esc_attr($bcol); echo esc_attr($columns >= 2?' col-xs-6 ':' col-xs-12 '); ?> <?php echo esc_attr(($count%$columns)==1?'sm-clearfix md-clearfix lg-clearfix':''); ?> <?php echo esc_attr(($count%2)==1?'xs-clearfix':''); ?>">
                <?php get_template_part( 'template-posts/loop/inner',$args['inner_item'] ); ?>
            </div>
        <?php $count++; endwhile; ?>
    </div>
</div>