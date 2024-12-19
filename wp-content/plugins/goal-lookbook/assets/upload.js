jQuery(document).ready(function($){

	
	if ( $('.goal-image-settings .upload_image').val() ) {
		var attachment_url = $('.goal-image-settings .upload_image').val();
		$('.goal-image-settings .screenshot').empty().hide().prepend('<img src="' + attachment_url + '">').slideDown('fast');
	}
});