<?php
    $count = 1;
    $args['inner_item'] = !isset($args['inner_item']) ? 'sticky' : $args['inner_item'];
?>
<div class="layout-blog">
    <div class="row row-blog">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="post-sticky">
                <?php get_template_part( 'template-posts/loop/inner',$args['inner_item'] ); ?>
            </div>
        <?php $count++; endwhile; ?>
    </div>
</div>