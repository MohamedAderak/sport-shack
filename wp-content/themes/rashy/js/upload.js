jQuery(document).ready(function($){
	"use strict";
	var rashy_upload;
	var rashy_selector;

	function rashy_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		rashy_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( rashy_upload ) {
			rashy_upload.open();
			return;
		} else {
			// Create the media frame.
			rashy_upload = wp.media.frames.rashy_upload =  wp.media({
				// Set the title of the modal.
				title: "Select Image",

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: "Selected",
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			rashy_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = rashy_upload.state().get('selection').first();

				rashy_upload.close();
				rashy_selector.find('.upload_image').val(attachment.attributes.url).change();
				if ( attachment.attributes.type == 'image' ) {
					rashy_selector.find('.rashy_screenshot').empty().hide().prepend('<img src="' + attachment.attributes.url + '">').slideDown('fast');
				}
			});

		}
		// Finally, open the modal.
		rashy_upload.open();
	}

	function rashy_remove_file(selector) {
		selector.find('.rashy_screenshot').slideUp('fast').next().val('').trigger('change');
	}
	
	$('body').on('click', '.rashy_upload_image_action .remove-image', function(event) {
		rashy_remove_file( $(this).parent().parent() );
	});

	$('body').on('click', '.rashy_upload_image_action .add-image', function(event) {
		rashy_add_file(event, $(this).parent().parent());
	});

});