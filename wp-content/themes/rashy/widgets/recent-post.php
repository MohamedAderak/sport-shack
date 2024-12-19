<?php
extract( $args );
extract( $instance );
$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}
$args = array(
	'post_type' => 'post',
	'posts_per_page' => $number_post
);
$query = new WP_Query($args);
if($query->have_posts()):
?>
<div class="post-widget">
<ul class="posts-list">
<?php
	while($query->have_posts()):$query->the_post();
?>
	<li>
		<article class="post post-list">
		    <div class="entry-content flex-middle">
		    	<?php
					if(has_post_thumbnail()){
				?>
					<div class="image pull-left" >
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'thumbnail' ); ?>
						</a>
					</div>
				<?php } ?>
				<div class="content-info">
					 <div class="top-info">
	                    <a href="<?php the_permalink(); ?>"><?php the_time( get_option('date_format', 'd M, Y') ); ?></a>
	                </div>
			         <?php
			              if (get_the_title()) {
			              ?>
			                  <h4 class="entry-title">
			                      <a href="<?php the_permalink(); ?>"><?php echo rashy_substring(get_the_title(),10,''); ?></a>
			                  </h4>
			              <?php
			          }
			        ?>
			       
			    </div>
		    </div>
		</article>
	</li>
<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
</ul>
</div>
<?php endif; ?>