'use strict';

(function($) {
  $(function() {
    // load plugins
    if ($('.wpclever_essential_kit_wrapper').length) {
      $.ajax({
        url: ajaxurl, method: 'POST', data: {
          action: 'wpc_get_essential_kit',
          security: wpc_kit_vars.nonce,
        }, dataType: 'html', beforeSend: function() {
          $('.wpclever_essential_kit_wrapper').
              addClass('wpclever_essential_kit_loading');
        }, complete: function() {
          $('.wpclever_essential_kit_wrapper').
              removeClass('wpclever_essential_kit_loading');
        }, success: function(response) {
          $('.wpclever_essential_kit_wrapper').html(response);
        },
      });
    }
  });

  $('body').on('click', '.install-now', function(e) {
    var _this = $(this);
    var _href = _this.attr('href');

    _this.addClass('updating-message').html('Installing...');

    $.get(_href, function(data) {
      location.reload();
    });

    e.preventDefault();
  });
})(jQuery);
