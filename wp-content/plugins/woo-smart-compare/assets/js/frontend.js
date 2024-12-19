'use strict';

(function($) {
  var woosc_timer = 0;

  // ready
  $(function() {
    woosc_color();
    woosc_change_count('first');
    woosc_check_buttons();
    woosc_hide_empty();
    woosc_hide_similarities();
    woosc_highlight_differences();

    if (woosc_vars.reload_count === 'yes') {
      // reload the count
      woosc_load_data('count');
    }

    if (woosc_vars.open_bar === 'yes') {
      woosc_load_data('bar');
      woosc_open_bar();
    }

    $('.woosc-settings-fields').sortable({
      handle: '.move', update: function(event, ui) {
        woosc_save_settings();
      },
    });

    if (woosc_vars.button_action === 'show_message') {
      $.notiny.addTheme('woosc', {
        notification_class: 'notiny-theme-woosc',
      });
    }

    woosc_quick_table();
  });

  // resize
  $(window).on('resize', function() {
    woosc_quick_table();
  });

  // quick view
  $(document).
      on('click touch', '.woosc_table .woosq-btn, .woosc_table .woosq-link',
          function(e) {
            e.preventDefault();
            woosc_close();
          });

  $(document).
      on('click touch', '.woosc-sidebar .woosq-btn, .woosc-sidebar .woosq-link',
          function(e) {
            e.preventDefault();
            woosc_close_sidebar();
          });

  // settings
  $(document).on('click touch', '.woosc-table-settings', function(e) {
    e.preventDefault();
    $('.woosc-settings').toggleClass('open');
  });

  $(document).on('click touch', '.woosc-bar-print', function(e) {
    e.preventDefault();
    $.print('#woosc-area');
  });

  $(document).on('change', '#woosc-filter', function(e) {
    e.preventDefault();
    woosc_filter();
  });

  $(document).on('keydown', function(e) {
    if ((e.ctrlKey == true || e.metaKey == true) && (e.which == '80')) {
      //ctrl+p or command+p
      if ($('.woosc-area').hasClass('woosc-area-open-table')) {
        e.preventDefault();
        $.print('#woosc-area');
      }
    }
  });

  $(document).on('click touch', '.woosc-bar-share', function(e) {
    e.preventDefault();
    $('.woosc-share').toggleClass('open');
    $('.woosc-share-content').addClass('woosc-loading');

    var data = {
      action: 'woosc_share', nonce: woosc_vars.nonce,
    };

    jQuery.post(woosc_vars.wc_ajax_url.toString().
        replace('%%endpoint%%', 'woosc_share'), data, function(response) {
      $('.woosc-share-content').html(response).removeClass('woosc-loading');
    });
  });

  // copy link
  $(document).
      on('click touch', '#woosc_copy_url, #woosc_copy_btn', function(e) {
        e.preventDefault();
        let $link = $('#woosc_copy_url');
        let link = $link.val();

        navigator.clipboard.writeText(link).then(function() {
          alert(woosc_vars.copied_text.replace('%s', link));
        }, function() {
          alert('Failure to copy!');
        });

        $link.select();
      });

  // search
  $(document).on('click touch', '.woosc-bar-search', function(e) {
    e.preventDefault();
    $('.woosc-search').toggleClass('open');

    if ($('.woosc-search-result').text() === '') {
      if (woosc_timer != null) {
        clearTimeout(woosc_timer);
      }

      woosc_timer = setTimeout(woosc_search, 300);
      return false;
    }
  });

  // popup
  $(document).on('click touch', '.woosc-popup', function(e) {
    if ($(e.target).closest('.woosc-popup-content').length === 0) {
      $(this).toggleClass('open');
    }
  });

  $(document).on('keyup', '#woosc_search_input', function() {
    if ($('#woosc_search_input').val() !== '') {
      if (woosc_timer != null) {
        clearTimeout(woosc_timer);
      }

      woosc_timer = setTimeout(woosc_search, 300);
      return false;
    }
  });

  $(document).on('click touch', '.woosc-item-add', function(e) {
    e.preventDefault();
    var product_id = $(this).attr('data-id');

    $('.woosc-search').toggleClass('open');
    woosc_add_product(product_id);
    woosc_load_data('table');
    woosc_open_table();
  });

  $(document).on('click touch', '.woosc-popup-close', function(e) {
    e.preventDefault();
    $(this).closest('.woosc-popup').toggleClass('open');
  });

  // woovr
  $(document).on('woovr_selected', function(e, selected) {
    if (woosc_vars.variations === 'yes') {
      var id = selected.attr('data-id');
      var pid = selected.attr('data-pid');

      if (id > 0) {
        $('.woosc-btn-' + pid).
            removeClass('woosc-btn-added woosc-added').
            attr('data-id', id);
      } else {
        $('.woosc-btn-' + pid).
            removeClass('woosc-btn-added woosc-added').
            attr('data-id', pid);
      }
    }
  });

  // found variation
  $(document).on('found_variation', function(e, t) {
    if (woosc_vars.variations === 'yes') {
      var product_id = $(e['target']).attr('data-product_id');

      $('.woosc-btn-' + product_id).
          removeClass('woosc-btn-added woosc-added').
          attr('data-id', t.variation_id);

      $('.woosc-btn-' + product_id + ':not(.woosc-btn-has-icon)').
          html(woosc_vars.button_text);
      $('.woosc-btn-has-icon.woosc-btn-' + product_id).
          find('.woosc-btn-icon').
          removeClass(woosc_vars.button_added_icon).
          addClass(woosc_vars.button_normal_icon);
      $('.woosc-btn-has-icon.woosc-btn-' + product_id).
          find('.woosc-btn-text').
          html(woosc_vars.button_text);
    }
  });

  // reset data
  $(document).on('reset_data', function(e) {
    if (woosc_vars.variations === 'yes') {
      var product_id = $(e['target']).attr('data-product_id');

      $('.woosc-btn-' + product_id).
          removeClass('woosc-btn-added woosc-added').
          attr('data-id', product_id);

      $('.woosc-btn-' + product_id + ':not(.woosc-btn-has-icon)').
          html(woosc_vars.button_text);
      $('.woosc-btn-has-icon.woosc-btn-' + product_id).
          find('.woosc-btn-icon').
          removeClass(woosc_vars.button_added_icon).
          addClass(woosc_vars.button_normal_icon);
      $('.woosc-btn-has-icon.woosc-btn-' + product_id).
          find('.woosc-btn-text').
          html(woosc_vars.button_text);
    }
  });

  // remove all
  $(document).on('click touch', '.woosc-bar-remove', function(e) {
    e.preventDefault();
    var r = confirm(woosc_vars.remove_all);

    if (r == true) {
      woosc_remove_product('all');
      woosc_load_data('table');
    }
  });

  // add
  $(document).on('click touch', '.woosc-btn', function(e) {
    e.preventDefault();
    var $this = $(this);
    var id = $this.attr('data-id');
    var pid = $this.attr('data-pid');
    var product_id = $this.attr('data-product_id');
    var product_name = $this.attr('data-product_name');
    var product_image = $this.attr('data-product_image');

    if (typeof pid !== typeof undefined && pid !== false) {
      id = pid;
    }

    if (typeof product_id !== typeof undefined && product_id !== false) {
      id = product_id;
    }

    $this.addClass('');

    if ($this.hasClass('woosc-btn-added woosc-added')) {
      if (woosc_vars.click_again === 'yes') {
        // remove
        woosc_remove_product(id);

        if (woosc_vars.button_action === 'show_message') {
          $.notiny({
            theme: 'woosc',
            position: woosc_vars.message_position,
            image: product_image,
            text: woosc_vars.message_removed.replace('{name}',
                '<strong>' + product_name + '</strong>'),
          });
        }
      } else {
        if (woosc_vars.button_action === 'show_message') {
          $.notiny({
            theme: 'woosc',
            position: woosc_vars.message_position,
            image: product_image,
            text: woosc_vars.message_exists.replace('{name}',
                '<strong>' + product_name + '</strong>'),
          });
        }
      }
    } else {
      $this.addClass('woosc-btn-adding woosc-adding');
      woosc_add_product(id);

      if (woosc_vars.button_action === 'show_message') {
        $.notiny({
          theme: 'woosc',
          position: woosc_vars.message_position,
          image: product_image,
          text: woosc_vars.message_added.replace('{name}',
              '<strong>' + product_name + '</strong>'),
        });
      }
    }

    if (woosc_vars.button_action === 'show_bar') {
      // show bar only
      woosc_load_data('bar');
      woosc_open_bar();
    }

    if (woosc_vars.button_action === 'show_table') {
      // show bar & table
      woosc_load_data('table');
      woosc_open_bar();
      woosc_open_table();
    }

    if ((woosc_vars.button_action === 'show_message') ||
        (woosc_vars.button_action === 'none')) {
      // load bar again
      if ($('.woosc-bar').hasClass('woosc-bar-open')) {
        woosc_load_data('bar');
      }
    }

    if (woosc_vars.button_action === 'show_sidebar') {
      woosc_load_data('sidebar');
      woosc_open_sidebar();
    }
  });

  // remove on popup
  $(document).
      on('click touch',
          '#woosc-area .woosc-bar-item-remove, #woosc-area .woosc-remove',
          function(e) {
            e.preventDefault();
            var product_id = $(this).attr('data-id');

            $(this).parent().addClass('removing');
            woosc_remove_product(product_id);
            woosc_load_data('table');
            woosc_check_buttons();
          });

  // remove on page
  $(document).
      on('click touch', '.woosc-page .woosc-remove', function(e) {
        e.preventDefault();
        var product_id = $(this).attr('data-id');

        woosc_remove_product(product_id);
        location.reload();
      });

  // remove all on page
  $(document).
      on('click touch', '.woosc-page .woosc-remove-all', function(e) {
        e.preventDefault();
        var r = confirm(woosc_vars.remove_all);

        if (r == true) {
          woosc_remove_product('all');
        }

        location.reload();
      });

  // remove on sidebar
  $(document).on('click touch', '.woosc-sidebar-item-remove', function(e) {
    e.preventDefault();
    var product_id = $(this).closest('.woosc-sidebar-item').attr('data-id');

    woosc_remove_product(product_id);
    $(this).closest('.woosc-sidebar-item').slideUp();
    woosc_check_buttons();
  });

  // bar button
  $(document).on('click touch', '.woosc-bar-btn', function(e) {
    e.preventDefault();

    if (!$('.woosc-table-items').hasClass('woosc-table-items-loaded')) {
      woosc_load_data('table');
    }

    woosc_toggle_table();
  });

  // close
  $(document).on('click touch', function(e) {
    if (((woosc_vars.click_outside === 'yes') ||
            ((woosc_vars.click_outside === 'yes_empty') &&
                (parseInt($('.woosc-bar').attr('data-count')) === 0))) &&
        ($(e.target).closest('.wpc_compare_count').length === 0) &&
        ($(e.target).closest('.woosc-popup').length === 0) &&
        ($(e.target).closest('.woosc-btn').length === 0) &&
        ($(e.target).closest('.woosc-table').length === 0) &&
        ($(e.target).closest('.woosc-bar').length === 0) &&
        ($(e.target).closest('.woosc-menu-item a').length === 0) &&
        ($(e.target).closest('.woosc-link a').length === 0) &&
        ($(e.target).closest('.woosc-menu a').length === 0) &&
        ($(e.target).closest('.woosc-sidebar-btn').length === 0) &&
        ((woosc_vars.open_button === '') ||
            ($(e.target).closest(woosc_vars.open_button).length === 0))) {
      woosc_close();
    }
  });

  // close sidebar
  $(document).on('click touch', '.woosc-area-open-sidebar', function(e) {
    if (($(e.target).closest('.woosc-sidebar').length === 0)) {
      woosc_close_sidebar();
    }
  });

  $(document).
      on('click touch', '.woosc-sidebar-close, .woosc-sidebar-continue',
          function(e) {
            woosc_close_sidebar();
          });

  // let compare
  $(document).on('click touch', '.woosc-sidebar-btn', function(e) {
    e.preventDefault();
    woosc_close_sidebar();
    woosc_toggle();
  });

  // close
  $(document).on('click touch', '#woosc-table-close', function(e) {
    e.preventDefault();
    woosc_close_table();
  });

  // change settings
  $(document).
      on('change', '.woosc-settings-field, .woosc-settings-tool', function() {
        woosc_save_settings();
      });

  // open button
  if (woosc_vars.open_button !== '') {
    $(document).on('click touch', woosc_vars.open_button, function(e) {
      e.preventDefault();

      if (woosc_vars.open_button_action === 'open_page') {
        // open page
        if ((woosc_vars.page_url !== '') && (woosc_vars.page_url !== '#')) {
          window.location.href = woosc_vars.page_url;
        }
      } else {
        if (woosc_vars.open_button_action === 'open_popup') {
          // open compare popup
          if (!$('.woosc-table-items').hasClass('woosc-table-items-loaded')) {
            woosc_load_data('table');
          }

          woosc_open_bar();
          woosc_open_table();
        }

        if (woosc_vars.open_button_action === 'open_sidebar') {
          // open sidebar
          if (!$('.woosc-sidebar-items').
              hasClass('woosc-sidebar-items-loaded')) {
            woosc_load_data('sidebar');
          }

          woosc_open_sidebar();
        }
      }
    });
  }

  // menu item
  $(document).
      on('click touch', '.woosc-menu-item a, .woosc-menu a, .woosc-link a',
          function(e) {
            if (woosc_vars.menu_action === 'open_popup') {
              e.preventDefault();

              // open compare popup
              if (!$('.woosc-table-items').
                  hasClass('woosc-table-items-loaded')) {
                woosc_load_data('table');
              }

              woosc_open_bar();
              woosc_open_table();
            }

            if (woosc_vars.menu_action === 'open_sidebar') {
              e.preventDefault();

              // open sidebar
              if (!$('.woosc-sidebar-items').
                  hasClass('woosc-sidebar-items-loaded')) {
                woosc_load_data('sidebar');
              }

              woosc_open_sidebar();
            }
          });

  function woosc_search() {
    $('.woosc-search-result').html('').addClass('woosc-loading');
    // ajax search product
    woosc_timer = null;

    var data = {
      action: 'woosc_search',
      keyword: $('#woosc_search_input').val(),
      nonce: woosc_vars.nonce,
    };

    $.post(woosc_vars.wc_ajax_url.toString().
        replace('%%endpoint%%', 'woosc_search'), data, function(response) {
      $('.woosc-search-result').
          html(response).
          removeClass('woosc-loading');
    });
  }

  function woosc_set_cookie(cname, cvalue, exdays) {
    var d = new Date();

    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));

    var expires = 'expires=' + d.toUTCString();

    document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
  }

  function woosc_get_cookie(cname) {
    var name = cname + '=';
    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];

      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }

      if (c.indexOf(name) == 0) {
        return decodeURIComponent(c.substring(name.length, c.length));
      }
    }

    return '';
  }

  function woosc_get_products() {
    var cookie_products = woosc_get_cookie_products();

    if (woosc_get_cookie(cookie_products) != '') {
      return woosc_get_cookie(cookie_products);
    } else {
      return '';
    }
  }

  function woosc_save_products() {
    var cookie_products = woosc_get_cookie_products();
    var products = [];

    $('.woosc-bar-item').each(function() {
      var pid = $(this).attr('data-id');

      if (pid !== '') {
        products.push(pid);
      }
    });

    var products_str = products.join();

    woosc_set_cookie(cookie_products, products_str, 7);
    woosc_load_data('table');
  }

  function woosc_save_settings() {
    var fields = [];
    var settings = [];
    var cookie_fields = 'woosc_fields_' + woosc_vars.hash;
    var cookie_settings = 'woosc_settings_' + woosc_vars.hash;

    if (woosc_vars.user_id !== '') {
      cookie_fields += '_' + woosc_vars.user_id;
      cookie_settings += '_' + woosc_vars.user_id;
    }

    // fields
    $('.woosc-settings-field').each(function() {
      var _val = $(this).val();

      if ($(this).prop('checked')) {
        fields.push(_val);
        $('.woosc_table .tr-' + _val).removeClass('tr-hide');
      } else {
        $('.woosc_table .tr-' + _val).addClass('tr-hide');
      }
    });

    // tools
    $('.woosc-settings-tool').each(function() {
      var _val = $(this).val();

      if ($(this).prop('checked')) {
        settings.push(_val);
      }
    });

    woosc_set_cookie(cookie_fields, fields.join(','), 7);
    woosc_set_cookie(cookie_settings, settings.join(','), 7);
    woosc_load_data('table');
  }

  function woosc_add_product(product_id) {
    var count;
    var limit = false;
    var limit_notice = woosc_vars.limit_notice;
    var cookie_products = woosc_get_cookie_products();

    if (woosc_get_cookie(cookie_products) !== '') {
      var products = woosc_get_cookie(cookie_products).split(',');

      if (products.length < woosc_vars.limit) {
        products = $.grep(products, function(value) {
          return value != product_id;
        });

        if (woosc_vars.adding === 'append') {
          products.push(product_id);
        } else {
          products.unshift(product_id);
        }

        var products_str = products.join();

        woosc_set_cookie(cookie_products, products_str, 7);
      } else {
        limit = true;
        limit_notice = limit_notice.replace('{limit}', woosc_vars.limit);
      }

      count = products.length;
    } else {
      woosc_set_cookie(cookie_products, product_id, 7);
      count = 1;
    }

    woosc_change_count(count);
    $(document.body).trigger('woosc_added', [count]);

    if (limit) {
      $('.woosc-btn[data-id="' + product_id + '"]').
          removeClass('woosc-btn-adding woosc-adding');
      alert(limit_notice);
    } else {
      $('.woosc-btn[data-id="' + product_id + '"]').
          removeClass('woosc-btn-adding woosc-adding').
          addClass('woosc-btn-added woosc-added');

      $('.woosc-btn[data-id="' + product_id + '"]:not(.woosc-btn-has-icon)').
          html(woosc_vars.button_text_added);
      $('.woosc-btn-has-icon[data-id="' + product_id + '"]').
          find('.woosc-btn-icon').
          removeClass(woosc_vars.button_normal_icon).
          addClass(woosc_vars.button_added_icon);
      $('.woosc-btn-has-icon[data-id="' + product_id + '"]').
          find('.woosc-btn-text').
          html(woosc_vars.button_text_added);
    }
  }

  function woosc_remove_product(product_id) {
    var count = 0;
    var cookie_products = woosc_get_cookie_products();

    if (product_id !== 'all') {
      // remove one
      if (woosc_get_cookie(cookie_products) != '') {
        var products = woosc_get_cookie(cookie_products).split(',');

        products = $.grep(products, function(value) {
          return value != product_id;
        });

        var products_str = products.join();

        woosc_set_cookie(cookie_products, products_str, 7);
        count = products.length;
      }

      $('.woosc-btn[data-id="' + product_id + '"]').
          removeClass('woosc-btn-added woosc-added');

      $('.woosc-btn[data-id="' + product_id + '"]:not(.woosc-btn-has-icon)').
          html(woosc_vars.button_text);
      $('.woosc-btn-has-icon[data-id="' + product_id + '"]').
          find('.woosc-btn-icon').
          removeClass(woosc_vars.button_added_icon).
          addClass(woosc_vars.button_normal_icon);
      $('.woosc-btn-has-icon[data-id="' + product_id + '"]').
          find('.woosc-btn-text').
          html(woosc_vars.button_text);
    } else {
      // remove all
      if (woosc_get_cookie(cookie_products) != '') {
        woosc_set_cookie(cookie_products, '', 7);
        count = 0;
      }

      $('.woosc-btn').removeClass('woosc-btn-added woosc-added');

      $('.woosc-btn:not(.woosc-btn-has-icon)').html(woosc_vars.button_text);
      $('.woosc-btn-has-icon').
          find('.woosc-btn-icon').
          removeClass(woosc_vars.button_added_icon).
          addClass(woosc_vars.button_normal_icon);
      $('.woosc-btn-has-icon').
          find('.woosc-btn-text').
          html(woosc_vars.button_text);
    }

    woosc_change_count(count);
    $(document.body).trigger('woosc_removed', [count]);
  }

  function woosc_check_buttons() {
    var cookie_products = woosc_get_cookie_products();

    if (woosc_get_cookie(cookie_products) != '') {
      var products = woosc_get_cookie(cookie_products).split(',');

      $('.woosc-btn').removeClass('woosc-btn-added woosc-added');
      $('.woosc-btn:not(.woosc-btn-has-icon)').html(woosc_vars.button_text);
      $('.woosc-btn.woosc-btn-has-icon').
          find('.woosc-btn-icon').
          removeClass(woosc_vars.button_added_icon).
          addClass(woosc_vars.button_normal_icon);
      $('.woosc-btn.woosc-btn-has-icon').
          find('.woosc-btn-text').
          html(woosc_vars.button_text);

      products.forEach(function(entry) {
        $('.woosc-btn-' + entry).addClass('woosc-btn-added woosc-added');
        $('.woosc-btn-' + entry + ':not(.woosc-btn-has-icon)').
            html(woosc_vars.button_text_added);
        $('.woosc-btn-has-icon.woosc-btn-' + entry).
            find('.woosc-btn-icon').
            removeClass(woosc_vars.button_normal_icon).
            addClass(woosc_vars.button_added_icon);
        $('.woosc-btn-has-icon.woosc-btn-' + entry).
            find('.woosc-btn-text').
            html(woosc_vars.button_text_added);
      });
    }
  }

  function woosc_load_data(get_data) {
    var data = {
      action: 'woosc_load', get_data: get_data, nonce: woosc_vars.nonce,
    };

    if (get_data === 'table') {
      $('.woosc-table-inner').addClass('woosc-loading');
    }

    if (get_data === 'sidebar') {
      $('.woosc-sidebar').addClass('woosc-loading');
    }

    $.post(woosc_vars.wc_ajax_url.toString().
        replace('%%endpoint%%', 'woosc_load'), data, function(response) {
      if (get_data === 'bar' || get_data === 'table') {
        // load bar items
        $('.woosc-bar-items').
            html(response.bar).
            addClass('woosc-bar-items-loaded');

        $(document.body).trigger('woosc_bar_loaded');
      }

      if (get_data === 'table') {
        // load table
        $('.woosc-table-items').
            html(response.table).
            addClass('woosc-table-items-loaded');

        if ($(window).width() >= 768) {
          if ((woosc_vars.freeze_column === 'yes') &&
              (woosc_vars.freeze_row === 'yes')) {
            // freeze row and column
            $('#woosc_table').tableHeadFixer({'head': true, left: 1});
          } else if (woosc_vars.freeze_column === 'yes') {
            // freeze column
            $('#woosc_table').tableHeadFixer({'head': false, left: 1});
          } else if (woosc_vars.freeze_row === 'yes') {
            // freeze row
            $('#woosc_table').tableHeadFixer({'head': true});
          }
        } else {
          if (woosc_vars.freeze_row === 'yes') {
            // freeze row
            $('#woosc_table').tableHeadFixer({'head': true});
          }
        }

        if (woosc_vars.scrollbar === 'yes') {
          $('.woosc-table-items').perfectScrollbar({theme: 'wpc'});
        }

        $('.woosc-table-inner').removeClass('woosc-loading');
        woosc_hide_empty();
        woosc_hide_similarities();
        woosc_highlight_differences();

        $(document.body).trigger('woosc_table_loaded');
      }

      if (get_data === 'sidebar') {
        // load sidebar
        $('.woosc-sidebar-items').
            html(response.sidebar).
            addClass('woosc-sidebar-items-loaded');
        $('.woosc-sidebar').removeClass('woosc-loading');

        if (woosc_vars.scrollbar === 'yes') {
          $('.woosc-sidebar-items').perfectScrollbar({theme: 'wpc'});
        }

        $(document.body).trigger('woosc_sidebar_loaded');
      }

      if (get_data === 'bar' || get_data === 'table' || get_data ===
          'sidebar') {
        // load bar filter
        $('.woosc-bar-filter').html(response.filter);
        woosc_filter();

        $(document.body).trigger('woosc_filter_loaded');
      }

      if (get_data === 'count') {
        // load count
        if (response.count != null) {
          var count = parseInt(response.count);

          woosc_change_count(count);
          $(document.body).trigger('woosc_count_loaded', [count]);
        }
      }
    });
  }

  function woosc_quick_table() {
    let ww = $(window).width();
    let fixed = JSON.parse(woosc_vars.quick_table_fixed);

    if (ww >= 1024) {
      $('.woosc-quick-table .woosc_table').
          tableHeadFixer({'head': false, left: fixed.pc});
    }

    if (ww >= 768 && ww < 1024) {
      $('.woosc-quick-table .woosc_table').
          tableHeadFixer({'head': false, left: fixed.ta});
    }

    if (ww < 768) {
      $('.woosc-quick-table .woosc_table').
          tableHeadFixer({'head': false, left: fixed.mo});
    }
  }

  function woosc_open_bar() {
    woosc_close_sidebar();

    $('#woosc-area').addClass('woosc-area-open-bar');
    $('.woosc-bar').addClass('woosc-bar-open');

    $('.woosc-bar-items').sortable({
      handle: 'img', update: function(event, ui) {
        woosc_save_products();
      },
    });

    $(document.body).trigger('woosc_bar_open');
  }

  function woosc_close_bar() {
    $('#woosc-area').removeClass('woosc-area-open-bar');
    $('.woosc-bar').removeClass('woosc-bar-open');

    $(document.body).trigger('woosc_bar_close');
  }

  function woosc_open_sidebar() {
    woosc_close_bar();
    woosc_close_table();

    $('#woosc-area').addClass('woosc-area-open-sidebar');
    $('.woosc-sidebar').addClass('woosc-sidebar-open');

    $(document.body).trigger('woosc_sidebar_open');
  }

  function woosc_close_sidebar() {
    $('#woosc-area').removeClass('woosc-area-open-sidebar');
    $('.woosc-sidebar').removeClass('woosc-sidebar-open');

    $(document.body).trigger('woosc_sidebar_close');
  }

  function woosc_open_table() {
    woosc_close_sidebar();

    $('#woosc-area').addClass('woosc-area-open-table');
    $('.woosc-table').addClass('woosc-table-open');
    $('.woosc-bar-btn').addClass('woosc-bar-btn-open');

    if (woosc_vars.bar_bubble === 'yes') {
      $('.woosc-bar').removeClass('woosc-bar-bubble');
    }

    $(document.body).trigger('woosc_table_open');
  }

  function woosc_close_table() {
    $('#woosc-area').removeClass('woosc-area-open woosc-area-open-table');
    $('.woosc-table').removeClass('woosc-table-open');
    $('.woosc-bar-btn').removeClass('woosc-bar-btn-open');

    if (woosc_vars.bar_bubble === 'yes') {
      $('.woosc-bar').addClass('woosc-bar-bubble');
    }

    $(document.body).trigger('woosc_table_close');
  }

  function woosc_toggle_table() {
    if ($('.woosc-table').hasClass('woosc-table-open')) {
      woosc_close_table();
    } else {
      woosc_open_table();
    }
  }

  function woosc_open() {
    $('#woosc-area').addClass('woosc-area-open');
    woosc_load_data('table');
    woosc_open_bar();
    woosc_open_table();

    $(document.body).trigger('woosc_open');
  }

  function woosc_close() {
    $('#woosc-area').removeClass('woosc-area-open');
    woosc_close_bar();
    woosc_close_table();

    $(document.body).trigger('woosc_close');
  }

  function woosc_toggle() {
    if ($('#woosc-area').hasClass('woosc-area-open')) {
      woosc_close();
    } else {
      woosc_open();
    }

    $(document.body).trigger('woosc_toggle');
  }

  function woosc_color() {
    var bg_color = $('#woosc-area').attr('data-bg-color');
    var btn_color = $('#woosc-area').attr('data-btn-color');

    $('.woosc-table').css('background-color', bg_color);
    $('.woosc-bar').css('background-color', bg_color);
    $('.woosc-bar-btn').css('background-color', btn_color);
    $('.woosc-sidebar-btn').css('background-color', btn_color);
  }

  function woosc_filter() {
    if (woosc_vars.bar_filter !== 'no' && $('#woosc-filter').length) {
      var filter = $('#woosc-filter').val().toLowerCase();

      if (filter !== '_all_') {
        $('.woosc_table .col').addClass('col-hide').hide();
        $('.woosc_table .col-' + filter).removeClass('col-hide').show();

        $('.woosc-bar-item').addClass('woosc-bar-item-hidden');
        $('.woosc-bar-item-' + filter).removeClass('woosc-bar-item-hidden');
      } else {
        $('.woosc_table .col').removeClass('col-hide').show();
        $('.woosc-bar-item').removeClass('woosc-bar-item-hidden');
      }

      if (woosc_vars.hide_empty_row === 'yes') {
        $('.woosc_table > tbody > tr:not(.tr-print)').each(function() {
          var $tr = $(this);
          var _td = 0;
          var _empty = true;

          $tr.removeClass('tr-empty').show();

          $tr.children('.col:not(.col-hide)').each(function() {
            if ($(this).html().length > 0) {
              _empty = false;
              return false;
            }
          });

          if (_empty) {
            $tr.addClass('tr-empty').hide();
          }
        });
      }
    }
  }

  function woosc_change_count(count) {
    if (count === 'first') {
      var products = woosc_get_products();

      if (products != '') {
        var products_arr = products.split(',');

        count = products_arr.length;
      } else {
        count = 0;
      }
    }

    $('.woosc-menu-item').each(function() {
      if ($(this).hasClass('menu-item-type-woosc')) {
        $(this).find('.woosc-menu-item-inner').attr('data-count', count);
      } else {
        $(this).
            addClass('menu-item-type-woosc').
            find('a').
            wrapInner(
                '<span class="woosc-menu-item-inner" data-count="' + count +
                '"></span>');
      }
    });

    $('#woosc-area').attr('data-count', count);
    $('.woosc-bar').attr('data-count', count);
    $('.woosc-sidebar-count').html(' (' + count + ')');
    $('.woosc-link .woosc-link-inner').attr('data-count', count);

    $('.woosc-bar-items').removeClass('woosc-bar-items-loaded');
    $('.woosc-sidebar-items').removeClass('woosc-sidebar-items-loaded');
    $('.woosc-table-items').removeClass('woosc-table-items-loaded');

    $(document.body).trigger('woosc_change_count', [count]);
  }

  function woosc_hide_empty() {
    if (woosc_vars.hide_empty_row === 'yes') {
      $('.woosc_table > tbody > tr').each(function() {
        var $tr = $(this);
        var _td = 0;
        var _empty = true;

        $tr.children('td').each(function() {
          if ((_td > 0) && ($(this).html().length > 0)) {
            _empty = false;
            return false;
          }

          _td++;
        });

        if (_empty) {
          $tr.addClass('tr-empty').remove();
        }
      });
    }
  }

  function woosc_highlight_differences() {
    if ($('#woosc_highlight_differences').prop('checked')) {
      $('.woosc_table > tbody > tr').each(function() {
        var $tr = $(this);
        var _td = 0;
        var _val = $(this).children('td').eq(1).html();
        var _differences = false;

        $tr.children('td:not(.td-placeholder)').each(function() {
          if ((_td > 1) && ($(this).html() !== _val)) {
            _differences = true;
            return false;
          }
          _td++;
        });

        if (_differences) {
          $tr.addClass('tr-highlight');
        }
      });
    } else {
      $('.woosc_table tr').removeClass('tr-highlight');
    }
  }

  function woosc_hide_similarities() {
    if ($('#woosc_hide_similarities').prop('checked')) {
      $('.woosc_table > tbody > tr').each(function() {
        var $tr = $(this);
        var _td = 0;
        var _val = $(this).children('td').eq(1).html();
        var _similarities = true;

        $tr.children('td:not(.td-placeholder)').each(function() {
          if ((_td > 1) && ($(this).html() !== _val)) {
            _similarities = false;
            return false;
          }
          _td++;
        });

        if (_similarities) {
          $tr.addClass('tr-similar');
        }
      });
    } else {
      $('.woosc_table tr').removeClass('tr-similar');
    }
  }

  function woosc_get_cookie_products() {
    return woosc_vars.user_id !== ''
        ? 'woosc_products_' + woosc_vars.user_id
        : 'woosc_products';
  }
})(jQuery);