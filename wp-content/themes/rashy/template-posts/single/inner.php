<?php
$post_format = get_post_format();
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="top-info-detail post-layout">

        <?php if(has_post_thumbnail()) { ?>
            <div class="entry-thumb">
                <?php
                    $thumb = rashy_post_thumbnail();
                    echo trim($thumb);
                ?>
            </div>
        <?php } ?>
        
    </div>
    <div class="entry-content-detail">
        <div class="top-info">
           
           <!--  <div class="post-author">
                <a class="post-user" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><i class="icon-user-avatar"></i><?php echo get_the_author(); ?>
                </a>
            </div> -->
            <?php rashy_post_categories($post); ?>
           
        </div>
        
        <div class="single-info info-bottom">
            <div class="entry-description">
                <?php
                    the_content();
                ?>
            </div><!-- /entry-content -->
            <?php
            wp_link_pages( array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'rashy' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'rashy' ) . ' </span>%',
                'separator'   => '',
            ) );
            ?>
            <?php  
                $posttags = get_the_tags();
            ?>
            <?php if( !empty($posttags) || rashy_get_config('show_blog_social_share', false) ){ ?>
                <div class="tag-social">
                    <?php rashy_post_tags(); ?>
                    <?php if( rashy_get_config('show_blog_social_share', false) ) {
                        get_template_part( 'template-parts/sharebox' );
                    } ?>
                </div>
            <?php } ?>
        </div>
    </div>
     <?php
        //Previous/next post navigation.
        rashy_post_nav();
    ?>
</article>