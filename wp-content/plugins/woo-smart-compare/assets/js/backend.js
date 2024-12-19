'use strict';

(function($) {
  $(function() {
    woosc_button_icon();
    woosc_button_action();
    woosc_init_fields();

    $('.woosc_color_picker').wpColorPicker();
    $('.woosc_icon_picker').fontIconPicker();

    $('.woosc-fields').sortable({
      handle: '.move', placeholder: 'woosc-field',
    });

    $('#woosc_settings_cats').selectWoo();
  });

  $(document).on('change', 'select.woosc_button_action', function() {
    woosc_button_action();
  });

  $(document).on('change', 'select.woosc_button_icon', function() {
    woosc_button_icon();
  });

  $(document).on('click touch', '.woosc-field .remove', function(e) {
    $(this).closest('.woosc-field').remove();
  });

  $(document).on('click touch', '.woosc-field-add', function(e) {
    e.preventDefault();

    var $this = $(this);
    var $wrapper = $this.closest('.woosc-fields-wrapper');
    var $fields = $wrapper.find('.woosc-fields');
    var $types = $wrapper.find('select.woosc-field-types');
    var field = $types.val();
    var type = $types.find('option:selected').data('type');
    var setting = $this.data('setting');

    var data = {
      action: 'woosc_add_field', type: type, field: field, setting: setting,
    };

    $wrapper.addClass('woosc-fields-wrapper-loading');

    $.post(ajaxurl, data, function(response) {
      $fields.append(response);
      woosc_init_fields();
      $wrapper.removeClass('woosc-fields-wrapper-loading');
    });
  });

  function woosc_button_icon() {
    var button_icon = $('select.woosc_button_icon').val();

    if (button_icon !== 'no') {
      $('.woosc-show-if-button-icon').show();
    } else {
      $('.woosc-show-if-button-icon').hide();
    }
  }

  function woosc_button_action() {
    var action = $('select.woosc_button_action').val();

    $('.woosc_button_action_hide').hide();
    $('.woosc_button_action_' + action).show();
  }

  function woosc_init_fields() {
    $('.woosc-field-type-custom_field .woosc-field-name:not(.woosc-field-name-initialized)').
        selectWoo().
        addClass('woosc-field-name-initialized');
  }
})(jQuery);