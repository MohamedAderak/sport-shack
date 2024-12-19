'use strict';

(function($) {
  $(function() {
    woosw_button_icon();
    woosw_button_action();
    $('.woosw_color_picker').wpColorPicker();
    $('.woosw_icon_picker').fontIconPicker();
    $('#woosw_settings_cats, #woosw_page_items').selectWoo();
  });

  $(document).on('click touch', '.woosw_action', function(e) {
    var uid = $(this).attr('data-uid');
    var pid = $(this).attr('data-pid');
    var key = $(this).attr('data-key');

    if ($('#woosw_popup').length < 1) {
      $('body').append('<div id=\'woosw_popup\'></div>');
    }

    $('#woosw_popup').html('Loading...');

    if (key && key != '') {
      $('#woosw_popup').dialog({
        minWidth: 460,
        title: 'Wishlist #' + key,
        modal: true,
        dialogClass: 'wpc-dialog',
        open: function() {
          $('.ui-widget-overlay').bind('click', function() {
            $('#woosw_popup').dialog('close');
          });
        },
      });

      var data = {
        action: 'wishlist_quickview',
        nonce: woosw_vars.nonce,
        key: key,
      };

      $.post(ajaxurl, data, function(response) {
        $('#woosw_popup').html(response);
      });
    }

    if (pid && pid != '') {
      $('#woosw_popup').dialog({
        minWidth: 460,
        title: 'Product ID #' + pid,
        modal: true,
        dialogClass: 'wpc-dialog',
        open: function() {
          $('.ui-widget-overlay').bind('click', function() {
            $('#woosw_popup').dialog('close');
          });
        },
      });

      var data = {
        action: 'wishlist_quickview',
        nonce: woosw_vars.nonce,
        pid: pid,
      };

      $.post(ajaxurl, data, function(response) {
        $('#woosw_popup').html(response);
      });
    }

    if (uid && uid != '') {
      $('#woosw_popup').dialog({
        minWidth: 460,
        title: 'User ID #' + uid,
        modal: true,
        dialogClass: 'wpc-dialog',
        open: function() {
          $('.ui-widget-overlay').bind('click', function() {
            $('#woosw_popup').dialog('close');
          });
        },
      });

      var data = {
        action: 'wishlist_quickview',
        nonce: woosw_vars.nonce,
        uid: uid,
      };

      $.post(ajaxurl, data, function(response) {
        $('#woosw_popup').html(response);
      });
    }

    e.preventDefault();
  });

  $(document).on('change', '.woosw_paging', function(e) {
    var page = $(this).val();
    var pid = $(this).attr('data-pid');

    if (pid && pid != '') {
      $('#woosw_popup').dialog({
        minWidth: 460,
        title: 'Product ID #' + pid,
        modal: true,
        dialogClass: 'wpc-dialog',
        open: function() {
          $('.ui-widget-overlay').bind('click', function() {
            $('#woosw_popup').dialog('close');
          });
        },
      });

      var data = {
        action: 'wishlist_quickview',
        nonce: woosw_vars.nonce,
        pid: pid,
        page: page,
      };

      $.post(ajaxurl, data, function(response) {
        $('#woosw_popup').html(response);
      });
    }
  });

  $(document).on('change', 'select.woosw_button_action', function() {
    woosw_button_action();
  });

  $(document).on('change', 'select.woosw_button_icon', function() {
    woosw_button_icon();
  });

  function woosw_button_icon() {
    var button_icon = $('select.woosw_button_icon').val();

    if (button_icon !== 'no') {
      $('.woosw-show-if-button-icon').show();
    } else {
      $('.woosw-show-if-button-icon').hide();
    }
  }

  function woosw_button_action() {
    var action = $('select.woosw_button_action').val();

    $('.woosw_button_action_hide').hide();
    $('.woosw_button_action_' + action).show();
  }
})(jQuery);