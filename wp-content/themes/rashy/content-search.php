<?php
/**
 * The template part for displaying results in search pages
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Rashy
 * @since Rashy 1.0
 */
?>
<?php 
    $thumbsize = !isset($thumbsize) ? rashy_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
    $thumb = rashy_display_post_thumb($thumbsize);
?>

<article <?php post_class('post post-layout post-list-item'); ?>>
    
    <div class="list-inner ">
        <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
            <span class="post-sticky"><?php echo esc_html__('Featured','rashy'); ?></span>
        <?php endif; ?>
        <div class="row <?php echo (!empty($thumb))?'flex-middle-sm':''; ?>">
            <?php
                if ( !empty($thumb) ) {
                    ?>
                    <div class="image top-image col-sm-6 col-xs-12">
                        <?php echo trim($thumb); ?>
                       
                    </div>
                    <?php
                }
            ?>
            <div class="<?php echo (!empty($thumb))?'col-xs-12 col-sm-6':'col-sm-12 col-xs-12 no-image'; ?>">
            
                <div class="post-info">
                    <?php if ( 'post' == get_post_type() ) : ?>
                    <div class="top-info">
                        <div class="blog-time">
                            <a href="<?php the_permalink(); ?> " class="date">
                                <?php the_time( get_option('date_format', 'd M, Y') ); ?>
                            </a>
                        </div>
                        <?php rashy_post_categories($post); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (get_the_title()) { ?>
                        <h4 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                    <?php } ?>

                    

                    <?php if (rashy_get_config('show_excerpt', false)) { ?>
                        <div class="description"><?php echo rashy_substring( get_the_excerpt(), 20, '...' ); ?></div>
                    <?php } else{ ?>
                        <div class="description"><?php echo rashy_substring( get_the_content(), 20, '...' ); ?></div>
                    <?php } ?>
                    
                    <div class="bottom-info ">
                        <?php if (rashy_get_config('show_readmore', false)) { ?>
                        <a class="btn btn-theme-second readmore radius-5x" href="<?php the_permalink(); ?>">
                            <?php esc_html_e('Read More', 'rashy'); ?>
                            <i class="icon-arrow-right" aria-hidden="true"></i>
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</article>