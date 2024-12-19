<?php $thumbsize = !isset($thumbsize) ? rashy_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;?>
<article <?php post_class('post post-layout post-grid-v3'); ?>>
    
    
    <div class="top-image image">
        <?php
            $thumb = rashy_display_post_thumb($thumbsize);
            echo trim($thumb);
        ?>
        <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
            <span class="post-sticky"><?php echo esc_html__('Featured','rashy'); ?></span>
        <?php endif; ?>
   
        <div class="content">
            <div class="post-info">
                <div class="top-info">
                    <div class="post-author">
                        <a class="post-user" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?>
                        </a>
                    </div>
                    <?php rashy_post_categories($post); ?>
                </div>
                <?php if (get_the_title()) { ?>
                    <h4 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                <?php } ?>

                <?php if (rashy_get_config('show_readmore', false)) { ?>
                <a class="btn btn-theme-second readmore radius-5x" href="<?php the_permalink(); ?>">
                    <?php esc_html_e('Read More', 'rashy'); ?>
                    <i class="icon-arrow-right" aria-hidden="true"></i>
                </a>
                <?php } ?>
            </div>
       </div>
    </div>
</article>