'use strict';

(function($) {
  $(function() {
    // load plugins
    if ($('.wpclever_plugins_wrapper').length) {
      $.ajax({
        url: ajaxurl, method: 'POST', data: {
          action: 'wpc_get_plugins', security: wpc_dashboard_vars.nonce,
        }, dataType: 'html', beforeSend: function() {
          $('.wpclever_plugins_wrapper').addClass('wpclever_plugins_loading');
        }, complete: function() {
          $('.wpclever_plugins_wrapper').
              removeClass('wpclever_plugins_loading');
        }, success: function(response) {
          $('.wpclever_plugins_wrapper').html(response);
        },
      });
    }

    // load suggestion
    if ($('.wpclever_settings_page_suggestion').length) {
      $.ajax({
        url: ajaxurl, method: 'POST', data: {
          action: 'wpc_get_suggestion', security: wpc_dashboard_vars.nonce,
        }, success: function(response) {
          if (response !== '' && response !== '0') {
            $('.wpclever_settings_page_suggestion_content').html(response);
            $('.wpclever_settings_page_suggestion_content > div').hide();
            wpc_rotate_suggestion(
                $('.wpclever_settings_page_suggestion_content > div'));
          }
        },
      });
    }
  });

  $(document).on('click', '.wpclever_plugins_order_a', function(e) {
    e.preventDefault();
    var o = $(this).data('o');

    if ($(this).hasClass('wpclever_plugins_order_down')) {
      $('.wpclever_plugins_wrapper').find('.item').sort(function(a, b) {
        return $(b).data(o) - $(a).data(o);
      }).appendTo('.wpclever_plugins_wrapper');
    } else {
      $('.wpclever_plugins_wrapper').find('.item').sort(function(a, b) {
        return $(a).data(o) - $(b).data(o);
      }).appendTo('.wpclever_plugins_wrapper');
    }

    $(this).toggleClass('wpclever_plugins_order_down');
  });

  function wpc_rotate_suggestion(elems, i = -1) {
    i = i === elems.length - 1 ? 0 : i + 1;
    elems.eq(i).fadeIn().delay(7000).fadeOut(0, function() {
      wpc_rotate_suggestion(elems, i);
    });
  }
})(jQuery);