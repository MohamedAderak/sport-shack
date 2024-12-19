
<div class="popupnewsletter hidden">
  <!-- Modal -->
  <button title="<?php echo esc_html('Close (Esc)', 'rashy'); ?>" type="button" class="mfp-close goal-mfp-close"> <span class="ti-close"></span> </button>
  <div class="modal-content" >
        <div class="row flex-middle">
          <div class="col-sm-6 hidden-xs">
            <?php if ( isset($image) && $image ) : ?>
            <img src="<?php echo esc_attr( $image ); ?>" alt="<?php esc_attr_e('Image', 'rashy'); ?>">
            <?php endif; ?> 
          </div>
          <div class="col-sm-6 col-xs-12 text-center">
            <div class="popupnewsletter-widget">
            <?php if(!empty($title)){ ?>
                <h3>
                    <span><?php echo esc_html( $title ); ?></span>
                </h3>
            <?php } ?>
            
            <?php if(!empty($description)){ ?>
                <p class="description">
                    <?php echo trim( $description ); ?>
                </p>
            <?php } ?>      
            <?php mc4wp_show_form(''); ?>

            <a href="javascript:void(0)" class="close-dont-show"><?php esc_html_e('Don\'t show this popup again', 'rashy'); ?></a>
            </div>
          </div>
        </div>
      
  </div>
   
</div>