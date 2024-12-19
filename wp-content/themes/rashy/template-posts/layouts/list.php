<?php
	$columns = rashy_get_config('blog_columns', 1);
	$bcol = floor( 12 / $columns );
?>
<div class="layout-posts-list">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'template-posts/loop/inner-list' ); ?>
    <?php endwhile; ?>
</div>