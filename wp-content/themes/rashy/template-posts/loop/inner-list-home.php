<?php 
global $post;
$thumbsize = !isset($thumbsize) ? rashy_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
$thumb = rashy_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-list-item post-list-home'); ?>>
    
    <div class="list-inner ">
       
        <div class="row <?php echo (!empty($thumb))?'flex-middle-sm':''; ?>">
            <?php if($thumb) {?>
                    <div class="image top-image col-sm-6 col-xs-12">
                        <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
                            <span class="post-sticky"><?php echo esc_html__('Featured','rashy'); ?></span>
                        <?php endif; ?>
                        <?php echo trim($thumb); ?>
                        
                    </div>
                    <div class="<?php echo (!empty($thumb))?'col-xs-12 col-sm-6':'col-sm-12 col-xs-12 no-image'; ?>">
                        <div class="post-info">
                            <div class="top-info">
                                <div class="blog-time">
                                    <a href="<?php the_permalink(); ?> " class="date">
                                        <?php the_time( get_option('date_format', 'd M, Y') ); ?>
                                    </a>
                                </div>
                                <?php rashy_post_categories($post); ?>
                            </div>
                            <?php if (get_the_title()) { ?>
                                <h4 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                            <?php } ?>

                            <div class="bottom-info">
                                <?php if (rashy_get_config('show_readmore', false)) { ?>
                                <a class="btn btn-theme-second readmore radius-5x" href="<?php the_permalink(); ?>">
                                    <?php esc_html_e('Read More', 'rashy'); ?>
                                    <i class="icon-arrow-right" aria-hidden="true"></i>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php }else{ ?>
                    <div class="list-no-image">
                        <div class="<?php echo (!empty($thumb))?'col-xs-12 col-sm-7':'col-sm-12 col-xs-12 no-image'; ?>">
                            <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
                                <span class="post-sticky"><?php echo esc_html__('Featured','rashy'); ?></span>
                            <?php endif; ?>
                            <div class="post-info">
                                <div class="top-info">
                                    <div class="blog-time">
                                        <a href="<?php the_permalink(); ?> " class="date">
                                            <?php the_time( get_option('date_format', 'd M, Y') ); ?>
                                        </a>
                                    </div>
                                    <?php rashy_post_categories($post); ?>
                                </div>
                                <?php if (get_the_title()) { ?>
                                    <h4 class="entry-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                <?php } ?>
                                
                               <div class="bottom-info">
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
                    
            <?php } ?>
            
            
        </div>
    </div>
</article>