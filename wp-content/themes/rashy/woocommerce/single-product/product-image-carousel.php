<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

$nb_columns = 5;
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );

$thumbs_pos = rashy_get_config('product_thumbs_position', 'thumbnails-bottom');

$attachment_ids = $product->get_gallery_image_ids();
$count_thumbs = (!empty($attachment_ids) && has_post_thumbnail()) ? count($attachment_ids) + 1 : 1;
?>
<div class="goal-woocommerce-product-gallery-wrapper <?php echo esc_attr(($attachment_ids && has_post_thumbnail())?'':'full-width'); ?>">
    <?php
      $video = get_post_meta( $post->ID, 'goal_product_review_video', true );

      if (!empty($video)) {
        ?>
        <div class="video">
          <a href="<?php echo esc_url($video); ?>" class="popup-video">
            <i class="fa fa-play"></i>
            <span class="text-theme"><?php echo esc_html__('Watch video', 'rashy'); ?></span>
          </a>
        </div>
        <?php
      }
    ?>

	<div class="slick-carousel goal-woocommerce-product-gallery" data-carousel="slick" data-items="1" data-smallmedium="1" data-extrasmall="1" data-pagination="false" data-nav="false" data-slickparent="true">
		<?php
		
		if ( has_post_thumbnail() ) {
			$html  = rashy_wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_attr__( 'Awaiting product image', 'rashy' ) );
			$html .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
		 		$html  = rashy_wc_get_gallery_image_html( $attachment_id, true );
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
			}
		}

		?>
	</div>
</div>
<?php if ( $attachment_ids && has_post_thumbnail() ) { ?>
	<div class="wrapper-thumbs <?php echo esc_attr($count_thumbs <= $nb_columns ? '' : ''); ?>">
		<div class="slick-carousel goal-woocommerce-product-gallery-thumbs" data-carousel="slick" data-items="<?php echo esc_attr($nb_columns); ?>" data-smallmedium="<?php echo esc_attr($nb_columns); ?>" data-extrasmall="<?php echo esc_attr($nb_columns); ?>" data-smallest="<?php echo esc_attr($nb_columns); ?>" data-pagination="false" data-nav="true" data-asnavfor=".goal-woocommerce-product-gallery" data-slidestoscroll="1" data-focusonselect="true" data-centermode="true">
			<?php

			if ( has_post_thumbnail() ) {
				$html  = '<div class="woocommerce-product-gallery__image"><div class="thumbs-inner">';
				$html .= rashy_get_attachment_thumbnail( $post_thumbnail_id, 'woocommerce_gallery_thumbnail' );
				$html .= '</div></div>';
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder"><div class="thumbs-inner">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_attr__( 'Awaiting product image', 'rashy' ) );
				$html .= '</div></div>';
			}

			echo apply_filters( 'rashy_woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

			
			foreach ( $attachment_ids as $attachment_id ) {
				$html  = '<div class="woocommerce-product-gallery__image"><div class="thumbs-inner">';
				$html .= rashy_get_attachment_thumbnail( $attachment_id, 'woocommerce_gallery_thumbnail' );
		 		$html .= '</div></div>';

				echo apply_filters( 'rashy_woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
			}

			?>
		</div>
	</div>
<?php } ?>