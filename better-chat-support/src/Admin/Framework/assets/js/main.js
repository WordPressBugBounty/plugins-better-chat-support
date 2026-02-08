/**
 *
 * -----------------------------------------------------------
 *
 * Codestar Framework
 * A Simple and Lightweight WordPress Option Framework
 *
 * -----------------------------------------------------------
 *
 */
; (function ($, window, document, undefined) {
  'use strict';
  // Preloader script
  $(document).ready(function () {
    $('.better-chat-support-admin').removeClass('better-chat-support-preloader');
  });

  var better_chat_support_layout_type = $(
    '.better-chat-support-field-layout_preset .better-chat-support-siblings .better-chat-support--sibling'
  )
  var better_chat_support_get_layout_value = $(
    '.better-chat-support-field-layout_preset .better-chat-support-siblings .better-chat-support--sibling.better-chat-support--active'
  )
    .find('input')
    .val()

  // Form Layout.
  if (better_chat_support_get_layout_value == 'button' || better_chat_support_get_layout_value == 'advance_button') {
    $(
      '.better-chat-support-content .floating_chat .better-chat-support-section_tab-nav a:nth-child(2)'
    ).hide()
    $(
      '.better-chat-support-content .floating_chat .better-chat-support-section_tab-nav a:nth-child(1)'
    ).trigger('click');
  } else {
    $(
      '.better-chat-support-content .floating_chat .better-chat-support-section_tab-nav a:nth-child(2)'
    ).show()
  }

  /**
   * Show/Hide tabs on changing of layout.
   */
  $(better_chat_support_layout_type).on('change', 'input', function (event) {
    var better_chat_support_get_layout_value = $(this).val();

    // Carousel Layout.
    if (better_chat_support_get_layout_value == 'button' || better_chat_support_get_layout_value == 'advance_button') {
      $(
        '.better-chat-support-content .floating_chat .better-chat-support-section_tab-nav a:nth-child(2)'
      ).hide()
      $(
        '.better-chat-support-content .floating_chat .better-chat-support-section_tab-nav a:nth-child(1)'
      ).trigger('click');
    } else {
      $(
        '.better-chat-support-content .floating_chat .better-chat-support-section_tab-nav a:nth-child(2)'
      ).show()
    }
  })

  //
  // Constants
  //
  var BetterChatSupport = BetterChatSupport || {};

  BetterChatSupport.funcs = {};

  BetterChatSupport.vars = {
    onloaded: false,
    $body: $('body'),
    $window: $(window),
    $document: $(document),
    $form_warning: null,
    is_confirm: false,
    form_modified: false,
    code_themes: [],
    is_rtl: $('body').hasClass('rtl'),
  };

  //
  // Helper Functions
  //
  BetterChatSupport.helper = {

    //
    // Generate UID
    //
    uid: function (prefix) {
      return (prefix || '') + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    //
    preg_quote: function (str) {
      return (str + '').replace(/(\[|\])/g, "\\$1");
    },

    //
    // Reneme input names
    //
    name_nested_replace: function ($selector, field_id) {

      var checks = [];
      var regex = new RegExp(BetterChatSupport.helper.preg_quote(field_id + '[\\d+]'), 'g');

      $selector.find(':radio').each(function () {
        if (this.checked || this.orginal_checked) {
          this.orginal_checked = true;
        }
      });

      $selector.each(function (index) {
        $(this).find(':input').each(function () {
          this.name = this.name.replace(regex, field_id + '[' + index + ']');
          if (this.orginal_checked) {
            this.checked = true;
          }
        });
      });

    },

    //
    // Debounce
    //
    debounce: function (callback, threshold, immediate) {
      var timeout;
      return function () {
        var context = this, args = arguments;
        var later = function () {
          timeout = null;
          if (!immediate) {
            callback.apply(context, args);
          }
        };
        var callNow = (immediate && !timeout);
        clearTimeout(timeout);
        timeout = setTimeout(later, threshold);
        if (callNow) {
          callback.apply(context, args);
        }
      };
    },

  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.BetterChatSupport_clone = function () {

    var base = $.fn.clone.apply(this, arguments),
      clone = this.find('select').add(this.filter('select')),
      cloned = base.find('select').add(base.filter('select'));

    for (var i = 0; i < clone.length; ++i) {
      for (var j = 0; j < clone[i].options.length; ++j) {

        if (clone[i].options[j].selected === true) {
          cloned[i].options[j].selected = true;
        }

      }
    }

    this.find(':radio').each(function () {
      this.orginal_checked = this.checked;
    });

    return base;

  };

  //
  // Expand All Options
  //
  $.fn.BetterChatSupport_expand_all = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        e.preventDefault();
        $('.better-chat-support-wrapper').toggleClass('better-chat-support-show-all');
        $('.better-chat-support-section').BetterChatSupport_reload_script();
        $(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

      });
    });
  };

  //
  // Options Navigation
  //
  $.fn.BetterChatSupport_nav_options = function () {
    return this.each(function () {

      var $nav = $(this),
        $window = $(window),
        $wpwrap = $('#wpwrap'),
        $links = $nav.find('a'),
        $last;

      $window.on('hashchange better-chat-support.hashchange', function () {

        var hash = window.location.hash.replace('#tab=', '');
        var slug = hash ? hash : $links.first().attr('href').replace('#tab=', '');
        var $link = $('[data-tab-id="' + slug + '"]');

        if ($link.length) {

          $link.closest('.better-chat-support-tab-item').addClass('better-chat-support-tab-expanded').siblings().removeClass('better-chat-support-tab-expanded');

          if ($link.next().is('ul')) {

            $link = $link.next().find('li').first().find('a');
            slug = $link.data('tab-id');

          }

          $links.removeClass('better-chat-support-active');
          $link.addClass('better-chat-support-active');

          if ($last) {
            $last.addClass('hidden');
          }

          var $section = $('[data-section-id="' + slug + '"]');

          $section.removeClass('hidden');
          $section.BetterChatSupport_reload_script();

          $('.better-chat-support-section-id').val($section.index() + 1);

          $last = $section;

          if ($wpwrap.hasClass('wp-responsive-open')) {
            $('html, body').animate({ scrollTop: ($section.offset().top - 50) }, 200);
            $wpwrap.removeClass('wp-responsive-open');
          }

        }

      }).trigger('better-chat-support.hashchange');

    });
  };

  //
  // Metabox Tabs
  //
  $.fn.BetterChatSupport_nav_metabox = function () {
    return this.each(function () {

      var $nav = $(this),
        $links = $nav.find('a'),
        $sections = $nav.parent().find('.better-chat-support-section'),
        $last;

      $links.each(function (index) {

        $(this).on('click', function (e) {

          e.preventDefault();

          var $link = $(this);

          $links.removeClass('better-chat-support-active');
          $link.addClass('better-chat-support-active');

          if ($last !== undefined) {
            $last.addClass('hidden');
          }

          var $section = $sections.eq(index);

          $section.removeClass('hidden');
          $section.BetterChatSupport_reload_script();

          $last = $section;

        });

      });

      $links.first().trigger('click');

    });
  };

  //
  // Metabox Page Templates Listener
  //
  $.fn.BetterChatSupport_page_templates = function () {
    if (this.length) {

      $(document).on('change', '.editor-page-attributes__template select, #page_template, .edit-post-post-status + div select', function () {

        var maybe_value = $(this).val() || 'default';

        $('.better-chat-support-page-templates').removeClass('better-chat-support-metabox-show').addClass('better-chat-support-metabox-hide');
        $('.better-chat-support-page-' + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')).removeClass('better-chat-support-metabox-hide').addClass('better-chat-support-metabox-show');

      });

    }
  };

  //
  // Metabox Post Formats Listener
  //
  $.fn.BetterChatSupport_post_formats = function () {
    if (this.length) {

      $(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function () {

        var maybe_value = $(this).val() || 'default';

        // Fallback for classic editor version
        maybe_value = (maybe_value === '0') ? 'default' : maybe_value;

        $('.better-chat-support-post-formats').removeClass('better-chat-support-metabox-show').addClass('better-chat-support-metabox-hide');
        $('.better-chat-support-post-format-' + maybe_value).removeClass('better-chat-support-metabox-hide').addClass('better-chat-support-metabox-show');

      });

    }
  };

  //
  // Search
  //
  $.fn.BetterChatSupport_search = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input');

      $input.on('change keyup', function () {

        var value = $(this).val(),
          $wrapper = $('.better-chat-support-wrapper'),
          $section = $wrapper.find('.better-chat-support-section'),
          $fields = $section.find('> .better-chat-support-field:not(.better-chat-support-depend-on)'),
          $titles = $fields.find('> .better-chat-support-title, .better-chat-support-search-tags');

        if (value.length > 3) {

          $fields.addClass('better-chat-support-metabox-hide');
          $wrapper.addClass('better-chat-support-search-all');

          $titles.each(function () {

            var $title = $(this);

            if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {

              var $field = $title.closest('.better-chat-support-field');

              $field.removeClass('better-chat-support-metabox-hide');
              $field.parent().BetterChatSupport_reload_script();

            }

          });

        } else {

          $fields.removeClass('better-chat-support-metabox-hide');
          $wrapper.removeClass('better-chat-support-search-all');

        }

      });

    });
  };

  //
  // Sticky Header
  //
  $.fn.BetterChatSupport_sticky = function () {
    return this.each(function () {

      var $this = $(this),
        $window = $(window),
        $inner = $this.find('.better-chat-support-header-inner'),
        padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
        offset = 32,
        scrollTop = 0,
        lastTop = 0,
        ticking = false,
        stickyUpdate = function () {

          var offsetTop = $this.offset().top,
            stickyTop = Math.max(offset, offsetTop - scrollTop),
            winWidth = $window.innerWidth();

          if (stickyTop <= offset && winWidth > 782) {
            $inner.css({ width: $this.outerWidth() - padding });
            $this.css({ height: $this.outerHeight() }).addClass('better-chat-support-sticky');
          } else {
            $inner.removeAttr('style');
            $this.removeAttr('style').removeClass('better-chat-support-sticky');
          }

        },
        requestTick = function () {

          if (!ticking) {
            requestAnimationFrame(function () {
              stickyUpdate();
              ticking = false;
            });
          }

          ticking = true;

        },
        onSticky = function () {

          scrollTop = $window.scrollTop();
          requestTick();

        };

      $window.on('scroll resize', onSticky);

      onSticky();

    });
  };

  //
  // Dependency System
  //
  $.fn.BetterChatSupport_dependency = function () {
    return this.each(function () {

      var $this = $(this),
        $fields = $this.children('[data-controller]');

      if ($fields.length) {

        var normal_ruleset = $.BetterChatSupport_deps.createRuleset(),
          global_ruleset = $.BetterChatSupport_deps.createRuleset(),
          normal_depends = [],
          global_depends = [];

        $fields.each(function () {

          var $field = $(this),
            controllers = $field.data('controller').split('|'),
            conditions = $field.data('condition').split('|'),
            values = $field.data('value').toString().split('|'),
            is_global = $field.data('depend-global') ? true : false,
            ruleset = (is_global) ? global_ruleset : normal_ruleset;

          $.each(controllers, function (index, depend_id) {

            var value = values[index] || '',
              condition = conditions[index] || conditions[0];

            ruleset = ruleset.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

            ruleset.include($field);

            if (is_global) {
              global_depends.push(depend_id);
            } else {
              normal_depends.push(depend_id);
            }

          });

        });

        if (normal_depends.length) {
          $.BetterChatSupport_deps.enable($this, normal_ruleset, normal_depends);
        }

        if (global_depends.length) {
          $.BetterChatSupport_deps.enable(BetterChatSupport.vars.$body, global_ruleset, global_depends);
        }

      }

    });
  };

  //
  // Field: accordion
  //
  $.fn.BetterChatSupport_field_accordion = function () {
    return this.each(function () {

      var $titles = $(this).find('.better-chat-support-accordion-title');

      $titles.on('click', function () {

        var $title = $(this),
          $icon = $title.find('.better-chat-support-accordion-icon'),
          $content = $title.next();

        if ($icon.hasClass('fa-angle-right')) {
          $icon.removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
          $icon.removeClass('fa-angle-down').addClass('fa-angle-right');
        }

        if (!$content.data('opened')) {

          $content.BetterChatSupport_reload_script();
          $content.data('opened', true);

        }

        $content.toggleClass('better-chat-support-accordion-open');

      });

    });
  };

  //
  // Field: backup
  //
  $.fn.BetterChatSupport_field_backup = function () {
    return this.each(function () {

      if (window.wp.customize === undefined) { return; }

      var base = this,
        $this = $(this),
        $body = $('body'),
        $import = $this.find('.better-chat-support-import'),
        $reset = $this.find('.better-chat-support-reset');

      base.notificationOverlay = function () {

        if (wp.customize.notifications && wp.customize.OverlayNotification) {

          // clear if there is any saved data.
          if (!wp.customize.state('saved').get()) {
            wp.customize.state('changesetStatus').set('trash');
            wp.customize.each(function (setting) { setting._dirty = false; });
            wp.customize.state('saved').set(true);
          }

          // then show a notification overlay
          wp.customize.notifications.add(new wp.customize.OverlayNotification('BetterChatSupport_field_backup_notification', {
            type: 'default',
            message: '&nbsp;',
            loading: true
          }));

        }

      };

      $reset.on('click', function (e) {

        e.preventDefault();

        if (BetterChatSupport.vars.is_confirm) {

          base.notificationOverlay();

          window.wp.ajax.post('better-chat-support-reset', {
            unique: $reset.data('unique'),
            nonce: $reset.data('nonce')
          })
            .done(function (response) {
              window.location.reload(true);
            })
            .fail(function (response) {
              alert(response.error);
              wp.customize.notifications.remove('BetterChatSupport_field_backup_notification');
            });

        }

      });

      $import.on('click', function (e) {

        e.preventDefault();

        if (BetterChatSupport.vars.is_confirm) {

          base.notificationOverlay();

          window.wp.ajax.post('better-chat-support-import', {
            unique: $import.data('unique'),
            nonce: $import.data('nonce'),
            data: $this.find('.better-chat-support-import-data').val()
          }).done(function (response) {
            window.location.reload(true);
          }).fail(function (response) {
            alert(response.error);
            wp.customize.notifications.remove('BetterChatSupport_field_backup_notification');
          });

        }

      });

    });
  };

  //
  // Field: background
  //
  $.fn.BetterChatSupport_field_background = function () {
    return this.each(function () {
      $(this).find('.better-chat-support--background-image').BetterChatSupport_reload_script();
    });
  };

  //
  // Field: code_editor
  //
  $.fn.BetterChatSupport_field_code_editor = function () {
    return this.each(function () {

      if (typeof CodeMirror !== 'function') { return; }

      var $this = $(this),
        $textarea = $this.find('textarea'),
        $inited = $this.find('.CodeMirror'),
        data_editor = $textarea.data('editor');

      if ($inited.length) {
        $inited.remove();
      }

      var interval = setInterval(function () {
        if ($this.is(':visible')) {

          var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

          // load code-mirror theme css.
          if (data_editor.theme !== 'default' && BetterChatSupport.vars.code_themes.indexOf(data_editor.theme) === -1) {

            var $cssLink = $('<link>');

            $('#better-chat-support-codemirror-css').after($cssLink);

            $cssLink.attr({
              rel: 'stylesheet',
              id: 'better-chat-support-codemirror-' + data_editor.theme + '-css',
              href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
              type: 'text/css',
              media: 'all'
            });

            BetterChatSupport.vars.code_themes.push(data_editor.theme);

          }

          CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
          CodeMirror.autoLoadMode(code_editor, data_editor.mode);

          code_editor.on('change', function (editor, event) {
            $textarea.val(code_editor.getValue()).trigger('change');
          });

          clearInterval(interval);

        }
      });

    });
  };

  //
  // Field: date
  //
  $.fn.BetterChatSupport_field_date = function () {
    return this.each(function () {

      var $this = $(this),
        $inputs = $this.find('input'),
        settings = $this.find('.better-chat-support-date-settings').data('settings'),
        wrapper = '<div class="better-chat-support-datepicker-wrapper"></div>';

      var defaults = {
        showAnim: '',
        beforeShow: function (input, inst) {
          $(inst.dpDiv).addClass('better-chat-support-datepicker-wrapper');
        },
        onClose: function (input, inst) {
          $(inst.dpDiv).removeClass('better-chat-support-datepicker-wrapper');
        },
      };

      settings = $.extend({}, settings, defaults);

      if ($inputs.length === 2) {

        settings = $.extend({}, settings, {
          onSelect: function (selectedDate) {

            var $this = $(this),
              $from = $inputs.first(),
              option = ($inputs.first().attr('id') === $(this).attr('id')) ? 'minDate' : 'maxDate',
              date = $.datepicker.parseDate(settings.dateFormat, selectedDate);

            $inputs.not(this).datepicker('option', option, date);

          }
        });

      }

      $inputs.each(function () {

        var $input = $(this);

        if ($input.hasClass('hasDatepicker')) {
          $input.removeAttr('id').removeClass('hasDatepicker');
        }

        $input.datepicker(settings);

      });

    });
  };

  //
  // Field: datetime
  //
  $.fn.BetterChatSupport_field_datetime = function () {
    return this.each(function () {

      var $this = $(this),
        $inputs = $this.find('input'),
        settings = $this.find('.better-chat-support-datetime-settings').data('settings');

      settings = $.extend({}, settings, {
        onReady: function (selectedDates, dateStr, instance) {
          $(instance.calendarContainer).addClass('better-chat-support-flatpickr');
        },
      });

      if ($inputs.length === 2) {
        settings = $.extend({}, settings, {
          onChange: function (selectedDates, dateStr, instance) {
            if ($(instance.element).data('type') === 'from') {
              $inputs.last().get(0)._flatpickr.set('minDate', selectedDates[0]);
            } else {
              $inputs.first().get(0)._flatpickr.set('maxDate', selectedDates[0]);
            }
          },
        });
      }

      $inputs.each(function () {
        $(this).flatpickr(settings);
      });

    });
  };

  //
  // Field: fieldset
  //
  $.fn.BetterChatSupport_field_fieldset = function () {
    return this.each(function () {
      $(this).find('.better-chat-support-fieldset-content').BetterChatSupport_reload_script();
    });
  };

  //
  // Field: gallery
  //
  $.fn.BetterChatSupport_field_gallery = function () {
    return this.each(function () {

      var $this = $(this),
        $edit = $this.find('.better-chat-support-edit-gallery'),
        $clear = $this.find('.better-chat-support-clear-gallery'),
        $list = $this.find('ul'),
        $input = $this.find('input'),
        $img = $this.find('img'),
        wp_media_frame;

      $this.on('click', '.better-chat-support-button, .better-chat-support-edit-gallery', function (e) {

        var $el = $(this),
          ids = $input.val(),
          what = ($el.hasClass('better-chat-support-edit-gallery')) ? 'edit' : 'add',
          state = (what === 'add' && !ids.length) ? 'gallery' : 'gallery-edit';

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) { return; }

        // Open media with state
        if (state === 'gallery') {

          wp_media_frame = window.wp.media({
            library: {
              type: 'image'
            },
            frame: 'post',
            state: 'gallery',
            multiple: true
          });

          wp_media_frame.open();

        } else {

          wp_media_frame = window.wp.media.gallery.edit('[gallery ids="' + ids + '"]');

          if (what === 'add') {
            wp_media_frame.setState('gallery-library');
          }

        }

        // Media Update
        wp_media_frame.on('update', function (selection) {

          $list.empty();

          var selectedIds = selection.models.map(function (attachment) {

            var item = attachment.toJSON();
            var thumb = (item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url) ? item.sizes.thumbnail.url : item.url;

            $list.append('<li><img src="' + thumb + '"></li>');

            return item.id;

          });

          $input.val(selectedIds.join(',')).trigger('change');
          $clear.removeClass('hidden');
          $edit.removeClass('hidden');

        });

      });

      $clear.on('click', function (e) {
        e.preventDefault();
        $list.empty();
        $input.val('').trigger('change');
        $clear.addClass('hidden');
        $edit.addClass('hidden');
      });

    });

  };

  //
  // Field: group
  //
  $.fn.BetterChatSupport_field_group = function () {
    return this.each(function () {

      var $this = $(this),
        $fieldset = $this.children('.better-chat-support-fieldset'),
        $group = $fieldset.length ? $fieldset : $this,
        $wrapper = $group.children('.better-chat-support-cloneable-wrapper'),
        $hidden = $group.children('.better-chat-support-cloneable-hidden'),
        $max = $group.children('.better-chat-support-cloneable-max'),
        $min = $group.children('.better-chat-support-cloneable-min'),
        title_by = $wrapper.data('title-by'),
        title_prefix = $wrapper.data('title-by-prefix'),
        field_id = $wrapper.data('field-id'),
        is_number = Boolean(Number($wrapper.data('title-number'))),
        max = parseInt($wrapper.data('max')),
        min = parseInt($wrapper.data('min'));

      // clear accordion arrows if multi-instance
      if ($wrapper.hasClass('ui-accordion')) {
        $wrapper.find('.ui-accordion-header-icon').remove();
      }

      var update_title_numbers = function ($selector) {
        $selector.find('.better-chat-support-cloneable-title-number').each(function (index) {
          $(this).html(($(this).closest('.better-chat-support-cloneable-item').index() + 1) + '.');
        });
      };

      $wrapper.accordion({
        header: '> .better-chat-support-cloneable-item > .better-chat-support-cloneable-title',
        collapsible: true,
        active: false,
        animate: false,
        heightStyle: 'content',
        icons: {
          'header': 'better-chat-support-cloneable-header-icon icofont-simple-right',
          'activeHeader': 'better-chat-support-cloneable-header-icon icofont-rounded-down'
        },
        activate: function (event, ui) {

          var $panel = ui.newPanel;
          var $header = ui.newHeader;

          if ($panel.length && !$panel.data('opened')) {

            var $title = $header.find('.better-chat-support-cloneable-value');
            var inputs = [];

            $.each(title_by, function (key, title_key) {
              inputs.push($panel.find('[data-depend-id="' + title_key + '"]'));
            });

            $.each(inputs, function (key, $input) {

              $input.on('change keyup better-chat-support.keyup', function () {

                var titles = [];

                $.each(inputs, function (key, $input) {

                  var input_value = $input.val();

                  if (input_value) {
                    titles.push(input_value);
                  }

                });

                if (titles.length) {
                  $title.text(titles.join(title_prefix));
                }

              }).trigger('better-chat-support.keyup');

            });

            $panel.BetterChatSupport_reload_script();
            $panel.data('opened', true);
            $panel.data('retry', false);

          } else if ($panel.data('retry')) {

            $panel.BetterChatSupport_reload_script_retry();
            $panel.data('retry', false);

          }

        }
      });

      $wrapper.sortable({
        axis: 'y',
        handle: '.better-chat-support-cloneable-title,.better-chat-support-cloneable-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        start: function (event, ui) {

          $wrapper.accordion({ active: false });
          $wrapper.sortable('refreshPositions');
          ui.item.children('.better-chat-support-cloneable-content').data('retry', true);

        },
        update: function (event, ui) {

          BetterChatSupport.helper.name_nested_replace($wrapper.children('.better-chat-support-cloneable-item'), field_id);
          $wrapper.BetterChatSupport_customizer_refresh();

          if (is_number) {
            update_title_numbers($wrapper);
          }

        },
      });

      $group.children('.better-chat-support-cloneable-add').on('click', function (e) {

        e.preventDefault();

        var count = $wrapper.children('.better-chat-support-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $cloned_item = $hidden.BetterChatSupport_clone(true);

        $cloned_item.removeClass('better-chat-support-cloneable-hidden');

        $cloned_item.find(':input[name!="_pseudo"]').each(function () {
          this.name = this.name.replace('___', '').replace(field_id + '[0]', field_id + '[' + count + ']');
        });

        $wrapper.append($cloned_item);
        $wrapper.accordion('refresh');
        $wrapper.accordion({ active: count });
        $wrapper.BetterChatSupport_customizer_refresh();
        $wrapper.BetterChatSupport_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      });

      var event_clone = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.better-chat-support-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent(),
          $cloned_helper = $parent.children('.better-chat-support-cloneable-helper').BetterChatSupport_clone(true),
          $cloned_title = $parent.children('.better-chat-support-cloneable-title').BetterChatSupport_clone(),
          $cloned_content = $parent.children('.better-chat-support-cloneable-content').BetterChatSupport_clone(),
          $cloned_item = $('<div class="better-chat-support-cloneable-item" />');

        $cloned_item.append($cloned_helper);
        $cloned_item.append($cloned_title);
        $cloned_item.append($cloned_content);

        $wrapper.children().eq($parent.index()).after($cloned_item);

        BetterChatSupport.helper.name_nested_replace($wrapper.children('.better-chat-support-cloneable-item'), field_id);

        $wrapper.accordion('refresh');
        $wrapper.BetterChatSupport_customizer_refresh();
        $wrapper.BetterChatSupport_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.better-chat-support-cloneable-item').children('.better-chat-support-cloneable-helper').on('click', '.better-chat-support-cloneable-clone', event_clone);
      $group.children('.better-chat-support-cloneable-hidden').children('.better-chat-support-cloneable-helper').on('click', '.better-chat-support-cloneable-clone', event_clone);

      var event_remove = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.better-chat-support-cloneable-item').length;

        $max.hide();
        $min.hide();

        if (min && (count - 1) < min) {
          $min.show();
          return;
        }

        $(this).closest('.better-chat-support-cloneable-item').remove();

        BetterChatSupport.helper.name_nested_replace($wrapper.children('.better-chat-support-cloneable-item'), field_id);

        $wrapper.BetterChatSupport_customizer_refresh();

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.better-chat-support-cloneable-item').children('.better-chat-support-cloneable-helper').on('click', '.better-chat-support-cloneable-remove', event_remove);
      $group.children('.better-chat-support-cloneable-hidden').children('.better-chat-support-cloneable-helper').on('click', '.better-chat-support-cloneable-remove', event_remove);

    });
  };

  //
  // Field: icon
  //
  $.fn.BetterChatSupport_field_icon = function () {
    return this.each(function () {

      var $this = $(this);

      $this.on('click', '.better-chat-support-icon-add', function (e) {

        e.preventDefault();

        var $button = $(this);
        var $modal = $('#better-chat-support-modal-icon');

        $modal.removeClass('hidden');

        BetterChatSupport.vars.$icon_target = $this;

        if (!BetterChatSupport.vars.icon_modal_loaded) {

          $modal.find('.better-chat-support-modal-loading').show();

          window.wp.ajax.post('better-chat-support-get-icons', {
            nonce: $button.data('nonce')
          }).done(function (response) {

            $modal.find('.better-chat-support-modal-loading').hide();

            BetterChatSupport.vars.icon_modal_loaded = true;

            var $load = $modal.find('.better-chat-support-modal-load').html(response.content);

            $load.on('click', 'i', function (e) {

              e.preventDefault();

              var icon = $(this).attr('title');

              BetterChatSupport.vars.$icon_target.find('.better-chat-support-icon-preview i').removeAttr('class').addClass(icon);
              BetterChatSupport.vars.$icon_target.find('.better-chat-support-icon-preview').removeClass('hidden');
              BetterChatSupport.vars.$icon_target.find('.better-chat-support-icon-remove').removeClass('hidden');
              BetterChatSupport.vars.$icon_target.find('input').val(icon).trigger('change');

              $modal.addClass('hidden');

            });

            $modal.on('change keyup', '.better-chat-support-icon-search', function () {

              var value = $(this).val(),
                $icons = $load.find('i');

              $icons.each(function () {

                var $elem = $(this);

                if ($elem.attr('title').search(new RegExp(value, 'i')) < 0) {
                  $elem.hide();
                } else {
                  $elem.show();
                }

              });

            });

            $modal.on('click', '.better-chat-support-modal-close, .better-chat-support-modal-overlay', function () {
              $modal.addClass('hidden');
            });

          }).fail(function (response) {
            $modal.find('.better-chat-support-modal-loading').hide();
            $modal.find('.better-chat-support-modal-load').html(response.error);
            $modal.on('click', function () {
              $modal.addClass('hidden');
            });
          });
        }

      });

      $this.on('click', '.better-chat-support-icon-remove', function (e) {
        e.preventDefault();
        $this.find('.better-chat-support-icon-preview').addClass('hidden');
        $this.find('input').val('').trigger('change');
        $(this).addClass('hidden');
      });

    });
  };

  //
  // Field: map
  //
  $.fn.BetterChatSupport_field_map = function () {
    return this.each(function () {

      if (typeof L === 'undefined') { return; }

      var $this = $(this),
        $map = $this.find('.better-chat-support--map-osm'),
        $search_input = $this.find('.better-chat-support--map-search input'),
        $latitude = $this.find('.better-chat-support--latitude'),
        $longitude = $this.find('.better-chat-support--longitude'),
        $zoom = $this.find('.better-chat-support--zoom'),
        map_data = $map.data('map');

      var mapInit = L.map($map.get(0), map_data);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(mapInit);

      var mapMarker = L.marker(map_data.center, { draggable: true }).addTo(mapInit);

      var update_latlng = function (data) {
        $latitude.val(data.lat);
        $longitude.val(data.lng);
        $zoom.val(mapInit.getZoom());
      };

      mapInit.on('click', function (data) {
        mapMarker.setLatLng(data.latlng);
        update_latlng(data.latlng);
      });

      mapInit.on('zoom', function () {
        update_latlng(mapMarker.getLatLng());
      });

      mapMarker.on('drag', function () {
        update_latlng(mapMarker.getLatLng());
      });

      if (!$search_input.length) {
        $search_input = $('[data-depend-id="' + $this.find('.better-chat-support--address-field').data('address-field') + '"]');
      }

      var cache = {};

      $search_input.autocomplete({
        source: function (request, response) {

          var term = request.term;

          if (term in cache) {
            response(cache[term]);
            return;
          }

          $.get('https://nominatim.openstreetmap.org/search', {
            format: 'json',
            q: term,
          }, function (results) {

            var data;

            if (results.length) {
              data = results.map(function (item) {
                return {
                  value: item.display_name,
                  label: item.display_name,
                  lat: item.lat,
                  lon: item.lon
                };
              }, 'json');
            } else {
              data = [{
                value: 'no-data',
                label: 'No Results.'
              }];
            }

            cache[term] = data;
            response(data);

          });

        },
        select: function (event, ui) {

          if (ui.item.value === 'no-data') { return false; }

          var latLng = L.latLng(ui.item.lat, ui.item.lon);

          mapInit.panTo(latLng);
          mapMarker.setLatLng(latLng);
          update_latlng(latLng);

        },
        create: function (event, ui) {
          $(this).autocomplete('widget').addClass('better-chat-support-map-ui-autocomplate');
        }
      });

      var input_update_latlng = function () {

        var latLng = L.latLng($latitude.val(), $longitude.val());

        mapInit.panTo(latLng);
        mapMarker.setLatLng(latLng);

      };

      $latitude.on('change', input_update_latlng);
      $longitude.on('change', input_update_latlng);

    });
  };

  //
  // Field: link
  //
  $.fn.BetterChatSupport_field_link = function () {
    return this.each(function () {

      var $this = $(this),
        $link = $this.find('.better-chat-support--link'),
        $add = $this.find('.better-chat-support--add'),
        $edit = $this.find('.better-chat-support--edit'),
        $remove = $this.find('.better-chat-support--remove'),
        $result = $this.find('.better-chat-support--result'),
        uniqid = BetterChatSupport.helper.uid('better-chat-support-wplink-textarea-');

      $add.on('click', function (e) {

        e.preventDefault();

        window.wpLink.open(uniqid);

      });

      $edit.on('click', function (e) {

        e.preventDefault();

        $add.trigger('click');

        $('#wp-link-url').val($this.find('.better-chat-support--url').val());
        $('#wp-link-text').val($this.find('.better-chat-support--text').val());
        $('#wp-link-target').prop('checked', ($this.find('.better-chat-support--target').val() === '_blank'));

      });

      $remove.on('click', function (e) {

        e.preventDefault();

        $this.find('.better-chat-support--url').val('').trigger('change');
        $this.find('.better-chat-support--text').val('');
        $this.find('.better-chat-support--target').val('');

        $add.removeClass('hidden');
        $edit.addClass('hidden');
        $remove.addClass('hidden');
        $result.parent().addClass('hidden');

      });

      $link.attr('id', uniqid).on('change', function () {

        var atts = window.wpLink.getAttrs(),
          href = atts.href,
          text = $('#wp-link-text').val(),
          target = (atts.target) ? atts.target : '';

        $this.find('.better-chat-support--url').val(href).trigger('change');
        $this.find('.better-chat-support--text').val(text);
        $this.find('.better-chat-support--target').val(target);

        $result.html('{url:"' + href + '", text:"' + text + '", target:"' + target + '"}');

        $add.addClass('hidden');
        $edit.removeClass('hidden');
        $remove.removeClass('hidden');
        $result.parent().removeClass('hidden');

      });

    });

  };

  //
  // Field: media
  //
  $.fn.BetterChatSupport_field_media = function () {
    return this.each(function () {

      var $this = $(this),
        $upload_button = $this.find('.better-chat-support--button'),
        $remove_button = $this.find('.better-chat-support--remove'),
        $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
        $auto_attributes = ($this.hasClass('better-chat-support-assign-field-background')) ? $this.closest('.better-chat-support-field-background').find('.better-chat-support--auto-attributes') : false,
        wp_media_frame;

      $upload_button.on('click', function (e) {

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          }
        });

        wp_media_frame.on('select', function () {

          var thumbnail;
          var attributes = wp_media_frame.state().get('selection').first().attributes;
          var preview_size = $upload_button.data('preview-size') || 'thumbnail';

          if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
            return;
          }

          $this.find('.better-chat-support--id').val(attributes.id);
          $this.find('.better-chat-support--width').val(attributes.width);
          $this.find('.better-chat-support--height').val(attributes.height);
          $this.find('.better-chat-support--alt').val(attributes.alt);
          $this.find('.better-chat-support--title').val(attributes.title);
          $this.find('.better-chat-support--description').val(attributes.description);

          if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.thumbnail !== 'undefined' && preview_size === 'thumbnail') {
            thumbnail = attributes.sizes.thumbnail.url;
          } else if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.full !== 'undefined') {
            thumbnail = attributes.sizes.full.url;
          } else if (attributes.type === 'image') {
            thumbnail = attributes.url;
          } else {
            thumbnail = attributes.icon;
          }

          if ($auto_attributes) {
            $auto_attributes.removeClass('better-chat-support--attributes-hidden');
          }

          $remove_button.removeClass('hidden');

          $this.find('.better-chat-support--preview').removeClass('hidden');
          $this.find('.better-chat-support--src').attr('src', thumbnail);
          $this.find('.better-chat-support--thumbnail').val(thumbnail);
          $this.find('.better-chat-support--url').val(attributes.url).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function (e) {

        e.preventDefault();

        if ($auto_attributes) {
          $auto_attributes.addClass('better-chat-support--attributes-hidden');
        }

        $remove_button.addClass('hidden');
        $this.find('input').val('');
        $this.find('.better-chat-support--preview').addClass('hidden');
        $this.find('.better-chat-support--url').trigger('change');

      });

    });

  };

  //
  // Field: repeater
  //
  $.fn.BetterChatSupport_field_repeater = function () {
    return this.each(function () {

      var $this = $(this),
        $fieldset = $this.children('.better-chat-support-fieldset'),
        $repeater = $fieldset.length ? $fieldset : $this,
        $wrapper = $repeater.children('.better-chat-support-repeater-wrapper'),
        $hidden = $repeater.children('.better-chat-support-repeater-hidden'),
        $max = $repeater.children('.better-chat-support-repeater-max'),
        $min = $repeater.children('.better-chat-support-repeater-min'),
        field_id = $wrapper.data('field-id'),
        max = parseInt($wrapper.data('max')),
        min = parseInt($wrapper.data('min'));

      $wrapper.children('.better-chat-support-repeater-item').children('.better-chat-support-repeater-content').BetterChatSupport_reload_script();

      $wrapper.sortable({
        axis: 'y',
        handle: '.better-chat-support-repeater-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function (event, ui) {

          BetterChatSupport.helper.name_nested_replace($wrapper.children('.better-chat-support-repeater-item'), field_id);
          $wrapper.BetterChatSupport_customizer_refresh();
          ui.item.BetterChatSupport_reload_script_retry();

        }
      });

      $repeater.children('.better-chat-support-repeater-add').on('click', function (e) {

        e.preventDefault();

        var count = $wrapper.children('.better-chat-support-repeater-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $cloned_item = $hidden.BetterChatSupport_clone(true);

        $cloned_item.removeClass('better-chat-support-repeater-hidden');

        $cloned_item.find(':input[name!="_pseudo"]').each(function () {
          this.name = this.name.replace('___', '').replace(field_id + '[0]', field_id + '[' + count + ']');
        });

        $wrapper.append($cloned_item);
        $cloned_item.children('.better-chat-support-repeater-content').BetterChatSupport_reload_script();
        $wrapper.BetterChatSupport_customizer_refresh();
        $wrapper.BetterChatSupport_customizer_listen({ closest: true });

      });

      var event_clone = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.better-chat-support-repeater-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent().parent(),
          $cloned_content = $parent.children('.better-chat-support-repeater-content').BetterChatSupport_clone(),
          $cloned_helper = $parent.children('.better-chat-support-repeater-helper').BetterChatSupport_clone(true),
          $cloned_item = $('<div class="better-chat-support-repeater-item" />');

        $cloned_item.append($cloned_content);
        $cloned_item.append($cloned_helper);

        $wrapper.children().eq($parent.index()).after($cloned_item);

        $cloned_item.children('.better-chat-support-repeater-content').BetterChatSupport_reload_script();

        BetterChatSupport.helper.name_nested_replace($wrapper.children('.better-chat-support-repeater-item'), field_id);

        $wrapper.BetterChatSupport_customizer_refresh();
        $wrapper.BetterChatSupport_customizer_listen({ closest: true });

      };

      $wrapper.children('.better-chat-support-repeater-item').children('.better-chat-support-repeater-helper').on('click', '.better-chat-support-repeater-clone', event_clone);
      $repeater.children('.better-chat-support-repeater-hidden').children('.better-chat-support-repeater-helper').on('click', '.better-chat-support-repeater-clone', event_clone);

      var event_remove = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.better-chat-support-repeater-item').length;

        $max.hide();
        $min.hide();

        if (min && (count - 1) < min) {
          $min.show();
          return;
        }

        $(this).closest('.better-chat-support-repeater-item').remove();

        BetterChatSupport.helper.name_nested_replace($wrapper.children('.better-chat-support-repeater-item'), field_id);

        $wrapper.BetterChatSupport_customizer_refresh();

      };

      $wrapper.children('.better-chat-support-repeater-item').children('.better-chat-support-repeater-helper').on('click', '.better-chat-support-repeater-remove', event_remove);
      $repeater.children('.better-chat-support-repeater-hidden').children('.better-chat-support-repeater-helper').on('click', '.better-chat-support-repeater-remove', event_remove);

    });
  };

  //
  // Field: slider
  //
  $.fn.BetterChatSupport_field_slider = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $slider = $this.find('.better-chat-support-slider-ui'),
        data = $input.data(),
        value = $input.val() || 0;

      if ($slider.hasClass('ui-slider')) {
        $slider.empty();
      }

      $slider.slider({
        range: 'min',
        value: value,
        min: data.min || 0,
        max: data.max || 100,
        step: data.step || 1,
        slide: function (e, o) {
          $input.val(o.value).trigger('change');
        }
      });

      $input.on('keyup', function () {
        $slider.slider('value', $input.val());
      });

    });
  };

  //
  // Field: sortable
  //
  $.fn.BetterChatSupport_field_sortable = function () {
    return this.each(function () {

      var $sortable = $(this).find('.better-chat-support-sortable');

      $sortable.sortable({
        axis: 'y',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function (event, ui) {
          $sortable.BetterChatSupport_customizer_refresh();
        }
      });

      $sortable.find('.better-chat-support-sortable-content').BetterChatSupport_reload_script();

    });
  };

  //
  // Field: sorter
  //
  $.fn.BetterChatSupport_field_sorter = function () {
    return this.each(function () {

      var $this = $(this),
        $enabled = $this.find('.better-chat-support-enabled'),
        $has_disabled = $this.find('.better-chat-support-disabled'),
        $disabled = ($has_disabled.length) ? $has_disabled : false;

      $enabled.sortable({
        connectWith: $disabled,
        placeholder: 'ui-sortable-placeholder',
        update: function (event, ui) {

          var $el = ui.item.find('input');

          if (ui.item.parent().hasClass('better-chat-support-enabled')) {
            $el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
          } else {
            $el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
          }

          $this.BetterChatSupport_customizer_refresh();

        }
      });

      if ($disabled) {

        $disabled.sortable({
          connectWith: $enabled,
          placeholder: 'ui-sortable-placeholder',
          update: function (event, ui) {
            $this.BetterChatSupport_customizer_refresh();
          }
        });

      }

    });
  };

  //
  // Field: spinner
  //
  $.fn.BetterChatSupport_field_spinner = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $inited = $this.find('.ui-button'),
        data = $input.data();

      if ($inited.length) {
        $inited.remove();
      }

      $input.spinner({
        min: data.min || 0,
        max: data.max || 100,
        step: data.step || 1,
        create: function (event, ui) {
          if (data.unit) {
            $input.after('<span class="ui-button better-chat-support--unit">' + data.unit + '</span>');
          }
        },
        spin: function (event, ui) {
          $input.val(ui.value).trigger('change');
        }
      });

    });
  };

  //
  // Field: switcher
  //
  $.fn.BetterChatSupport_field_switcher = function () {
    return this.each(function () {

      var $switcher = $(this).find('.better-chat-support--switcher');

      $switcher.on('click', function () {

        var value = 0;
        var $input = $switcher.find('input');

        if ($switcher.hasClass('better-chat-support--active')) {
          $switcher.removeClass('better-chat-support--active');
        } else {
          value = 1;
          $switcher.addClass('better-chat-support--active');
        }

        $input.val(value).trigger('change');

      });

    });
  };

  //
  // Field: tabbed
  //
  $.fn.BetterChatSupport_field_tabbed = function () {
    return this.each(function () {

      var $this = $(this),
        $links = $this.find('.better-chat-support-tabbed-nav a'),
        $contents = $this.find('.better-chat-support-tabbed-content');

      $contents.eq(0).BetterChatSupport_reload_script();

      $links.on('click', function (e) {

        e.preventDefault();

        var $link = $(this),
          index = $link.index(),
          $content = $contents.eq(index);

        $link.addClass('better-chat-support-tabbed-active').siblings().removeClass('better-chat-support-tabbed-active');
        $content.BetterChatSupport_reload_script();
        $content.removeClass('hidden').siblings().addClass('hidden');

      });

    });
  };
  //
  // Field: section_tab
  //
  $.fn.BetterChatSupport_field_section_tab = function () {
    return this.each(function () {

      var $this = $(this),
        $links = $this.find('.better-chat-support-section_tab-nav a'),
        $contents = $this.find('.better-chat-support-section_tab-content');

      $contents.eq(0).BetterChatSupport_reload_script();

      $links.on('click', function (e) {
        e.preventDefault();

        
        var $link = $(this),
        index = $link.index(),
        $content = $contents.eq(index);
        
        $links.removeClass('better-chat-support-section_tab-active');
        $link.addClass('better-chat-support-section_tab-active');
        
        // $link.addClass('better-chat-support-section_tab-active').siblings().removeClass('better-chat-support-section_tab-active');
        $content.BetterChatSupport_reload_script();
        $content.removeClass('hidden').siblings().addClass('hidden');

      });

    });
  };

  //
  // Field: typography
  //
  $.fn.BetterChatSupport_field_typography = function () {
    return this.each(function () {

      var base = this;
      var $this = $(this);
      var loaded_fonts = [];
      var webfonts = BetterChatSupport_typography_json.webfonts;
      var googlestyles = BetterChatSupport_typography_json.googlestyles;
      var defaultstyles = BetterChatSupport_typography_json.defaultstyles;

      //
      //
      // Sanitize google font subset
      base.sanitize_subset = function (subset) {
        subset = subset.replace('-ext', ' Extended');
        subset = subset.charAt(0).toUpperCase() + subset.slice(1);
        return subset;
      };

      //
      //
      // Sanitize google font styles (weight and style)
      base.sanitize_style = function (style) {
        return googlestyles[style] ? googlestyles[style] : style;
      };

      //
      //
      // Load google font
      base.load_google_font = function (font_family, weight, style) {

        if (font_family && typeof WebFont === 'object') {

          weight = weight ? weight.replace('normal', '') : '';
          style = style ? style.replace('normal', '') : '';

          if (weight || style) {
            font_family = font_family + ':' + weight + style;
          }

          if (loaded_fonts.indexOf(font_family) === -1) {
            WebFont.load({ google: { families: [font_family] } });
          }

          loaded_fonts.push(font_family);

        }

      };

      //
      //
      // Append select options
      base.append_select_options = function ($select, options, condition, type, is_multi) {

        $select.find('option').not(':first').remove();

        var opts = '';

        $.each(options, function (key, value) {

          var selected;
          var name = value;

          // is_multi
          if (is_multi) {
            selected = (condition && condition.indexOf(value) !== -1) ? ' selected' : '';
          } else {
            selected = (condition && condition === value) ? ' selected' : '';
          }

          if (type === 'subset') {
            name = base.sanitize_subset(value);
          } else if (type === 'style') {
            name = base.sanitize_style(value);
          }

          opts += '<option value="' + value + '"' + selected + '>' + name + '</option>';

        });

        $select.append(opts).trigger('better-chat-support.change').trigger('chosen:updated');

      };

      base.init = function () {

        //
        //
        // Constants
        var selected_styles = [];
        var $typography = $this.find('.better-chat-support--typography');
        var $type = $this.find('.better-chat-support--type');
        var $styles = $this.find('.better-chat-support--block-font-style');
        var unit = $typography.data('unit');
        var line_height_unit = $typography.data('line-height-unit');
        var exclude_fonts = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];

        //
        //
        // Chosen init
        if ($this.find('.better-chat-support--chosen').length) {

          var $chosen_selects = $this.find('select');

          $chosen_selects.each(function () {

            var $chosen_select = $(this),
              $chosen_inited = $chosen_select.parent().find('.chosen-container');

            if ($chosen_inited.length) {
              $chosen_inited.remove();
            }

            $chosen_select.chosen({
              allow_single_deselect: true,
              disable_search_threshold: 15,
              width: '100%'
            });

          });

        }

        //
        //
        // Font family select
        var $font_family_select = $this.find('.better-chat-support--font-family');
        var first_font_family = $font_family_select.val();

        // Clear default font family select options
        $font_family_select.find('option').not(':first-child').remove();

        var opts = '';

        $.each(webfonts, function (type, group) {

          // Check for exclude fonts
          if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) { return; }

          opts += '<optgroup label="' + group.label + '">';

          $.each(group.fonts, function (key, value) {

            // use key if value is object
            value = (typeof value === 'object') ? key : value;
            var selected = (value === first_font_family) ? ' selected' : '';
            opts += '<option value="' + value + '" data-type="' + type + '"' + selected + '>' + value + '</option>';

          });

          opts += '</optgroup>';

        });

        // Append google font select options
        $font_family_select.append(opts).trigger('chosen:updated');

        //
        //
        // Font style select
        var $font_style_block = $this.find('.better-chat-support--block-font-style');

        if ($font_style_block.length) {

          var $font_style_select = $this.find('.better-chat-support--font-style-select');
          var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '') : '';

          //
          // Font Style on on change listener
          $font_style_select.on('change better-chat-support.change', function (event) {

            var style_value = $font_style_select.val();

            // set a default value
            if (!style_value && selected_styles && selected_styles.indexOf('normal') === -1) {
              style_value = selected_styles[0];
            }

            // set font weight, for eg. replacing 800italic to 800
            var font_normal = (style_value && style_value !== 'italic' && style_value === 'normal') ? 'normal' : '';
            var font_weight = (style_value && style_value !== 'italic' && style_value !== 'normal') ? style_value.replace('italic', '') : font_normal;
            var font_style = (style_value && style_value.substr(-6) === 'italic') ? 'italic' : '';

            $this.find('.better-chat-support--font-weight').val(font_weight);
            $this.find('.better-chat-support--font-style').val(font_style);

          });

          //
          //
          // Extra font style select
          var $extra_font_style_block = $this.find('.better-chat-support--block-extra-styles');

          if ($extra_font_style_block.length) {
            var $extra_font_style_select = $this.find('.better-chat-support--extra-styles');
            var first_extra_style_value = $extra_font_style_select.val();
          }

        }

        //
        //
        // Subsets select
        var $subset_block = $this.find('.better-chat-support--block-subset');
        if ($subset_block.length) {
          var $subset_select = $this.find('.better-chat-support--subset');
          var first_subset_select_value = $subset_select.val();
          var subset_multi_select = $subset_select.data('multiple') || false;
        }

        //
        //
        // Backup font family
        var $backup_font_family_block = $this.find('.better-chat-support--block-backup-font-family');

        //
        //
        // Font Family on Change Listener
        $font_family_select.on('change better-chat-support.change', function (event) {

          // Hide subsets on change
          if ($subset_block.length) {
            $subset_block.addClass('hidden');
          }

          // Hide extra font style on change
          if ($extra_font_style_block.length) {
            $extra_font_style_block.addClass('hidden');
          }

          // Hide backup font family on change
          if ($backup_font_family_block.length) {
            $backup_font_family_block.addClass('hidden');
          }

          var $selected = $font_family_select.find(':selected');
          var value = $selected.val();
          var type = $selected.data('type');

          if (type && value) {

            // Show backup fonts if font type google or custom
            if ((type === 'google' || type === 'custom') && $backup_font_family_block.length) {
              $backup_font_family_block.removeClass('hidden');
            }

            // Appending font style select options
            if ($font_style_block.length) {

              // set styles for multi and normal style selectors
              var styles = defaultstyles;

              // Custom or gogle font styles
              if (type === 'google' && webfonts[type].fonts[value][0]) {
                styles = webfonts[type].fonts[value][0];
              } else if (type === 'custom' && webfonts[type].fonts[value]) {
                styles = webfonts[type].fonts[value];
              }

              selected_styles = styles;

              // Set selected style value for avoid load errors
              var set_auto_style = (styles.indexOf('normal') !== -1) ? 'normal' : styles[0];
              var set_style_value = (first_style_value && styles.indexOf(first_style_value) !== -1) ? first_style_value : set_auto_style;

              // Append style select options
              base.append_select_options($font_style_select, styles, set_style_value, 'style');

              // Clear first value
              first_style_value = false;

              // Show style select after appended
              $font_style_block.removeClass('hidden');

              // Appending extra font style select options
              if (type === 'google' && $extra_font_style_block.length && styles.length > 1) {

                // Append extra-style select options
                base.append_select_options($extra_font_style_select, styles, first_extra_style_value, 'style', true);

                // Clear first value
                first_extra_style_value = false;

                // Show style select after appended
                $extra_font_style_block.removeClass('hidden');

              }

            }

            // Appending google fonts subsets select options
            if (type === 'google' && $subset_block.length && webfonts[type].fonts[value][1]) {

              var subsets = webfonts[type].fonts[value][1];
              var set_auto_subset = (subsets.length < 2 && subsets[0] !== 'latin') ? subsets[0] : '';
              var set_subset_value = (first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1) ? first_subset_select_value : set_auto_subset;

              // check for multiple subset select
              set_subset_value = (subset_multi_select && first_subset_select_value) ? first_subset_select_value : set_subset_value;

              base.append_select_options($subset_select, subsets, set_subset_value, 'subset', subset_multi_select);

              first_subset_select_value = false;

              $subset_block.removeClass('hidden');

            }

          } else {

            // Clear Styles
            $styles.find(':input').val('');

            // Clear subsets options if type and value empty
            if ($subset_block.length) {
              $subset_select.find('option').not(':first-child').remove();
              $subset_select.trigger('chosen:updated');
            }

            // Clear font styles options if type and value empty
            if ($font_style_block.length) {
              $font_style_select.find('option').not(':first-child').remove();
              $font_style_select.trigger('chosen:updated');
            }

          }

          // Update font type input value
          $type.val(type);

        }).trigger('better-chat-support.change');

        //
        //
        // Preview
        var $preview_block = $this.find('.better-chat-support--block-preview');

        if ($preview_block.length) {

          var $preview = $this.find('.better-chat-support--preview');

          // Set preview styles on change
          $this.on('change', BetterChatSupport.helper.debounce(function (event) {

            $preview_block.removeClass('hidden');

            var font_family = $font_family_select.val(),
              font_weight = $this.find('.better-chat-support--font-weight').val(),
              font_style = $this.find('.better-chat-support--font-style').val(),
              font_size = $this.find('.better-chat-support--font-size').val(),
              font_variant = $this.find('.better-chat-support--font-variant').val(),
              line_height = $this.find('.better-chat-support--line-height').val(),
              text_align = $this.find('.better-chat-support--text-align').val(),
              text_transform = $this.find('.better-chat-support--text-transform').val(),
              text_decoration = $this.find('.better-chat-support--text-decoration').val(),
              text_color = $this.find('.better-chat-support--color').val(),
              word_spacing = $this.find('.better-chat-support--word-spacing').val(),
              letter_spacing = $this.find('.better-chat-support--letter-spacing').val(),
              custom_style = $this.find('.better-chat-support--custom-style').val(),
              type = $this.find('.better-chat-support--type').val();

            if (type === 'google') {
              base.load_google_font(font_family, font_weight, font_style);
            }

            var properties = {};

            if (font_family) { properties.fontFamily = font_family; }
            if (font_weight) { properties.fontWeight = font_weight; }
            if (font_style) { properties.fontStyle = font_style; }
            if (font_variant) { properties.fontVariant = font_variant; }
            if (font_size) { properties.fontSize = font_size + unit; }
            if (line_height) { properties.lineHeight = line_height + line_height_unit; }
            if (letter_spacing) { properties.letterSpacing = letter_spacing + unit; }
            if (word_spacing) { properties.wordSpacing = word_spacing + unit; }
            if (text_align) { properties.textAlign = text_align; }
            if (text_transform) { properties.textTransform = text_transform; }
            if (text_decoration) { properties.textDecoration = text_decoration; }
            if (text_color) { properties.color = text_color; }

            $preview.removeAttr('style');

            // Customs style attribute
            if (custom_style) { $preview.attr('style', custom_style); }

            $preview.css(properties);

          }, 100));

          // Preview black and white backgrounds trigger
          $preview_block.on('click', function () {

            $preview.toggleClass('better-chat-support--black-background');

            var $toggle = $preview_block.find('.better-chat-support--toggle');

            if ($toggle.hasClass('fa-toggle-off')) {
              $toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
            } else {
              $toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
            }

          });

          if (!$preview_block.hasClass('hidden')) {
            $this.trigger('change');
          }

        }

      };

      base.init();

    });
  };

  //
  // Field: upload
  //
  $.fn.BetterChatSupport_field_upload = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $upload_button = $this.find('.better-chat-support--button'),
        $remove_button = $this.find('.better-chat-support--remove'),
        $preview_wrap = $this.find('.better-chat-support--preview'),
        $preview_src = $this.find('.better-chat-support--src'),
        $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
        wp_media_frame;

      $upload_button.on('click', function (e) {

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          },
        });

        wp_media_frame.on('select', function () {

          var src;
          var attributes = wp_media_frame.state().get('selection').first().attributes;

          if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
            return;
          }

          $input.val(attributes.url).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function (e) {
        e.preventDefault();
        $input.val('').trigger('change');
      });

      $input.on('change', function (e) {

        var $value = $input.val();

        if ($value) {
          $remove_button.removeClass('hidden');
        } else {
          $remove_button.addClass('hidden');
        }

        if ($preview_wrap.length) {

          if ($.inArray($value.split('.').pop().toLowerCase(), ['jpg', 'jpeg', 'gif', 'png', 'svg', 'webp']) !== -1) {
            $preview_wrap.removeClass('hidden');
            $preview_src.attr('src', $value);
          } else {
            $preview_wrap.addClass('hidden');
          }

        }

      });

    });

  };

  //
  // Field: wp_editor
  //
  $.fn.BetterChatSupport_field_wp_editor = function () {
    return this.each(function () {

      if (typeof window.wp.editor === 'undefined' || typeof window.tinyMCEPreInit === 'undefined' || typeof window.tinyMCEPreInit.mceInit.BetterChatSupport_wp_editor === 'undefined') {
        return;
      }

      var $this = $(this),
        $editor = $this.find('.better-chat-support-wp-editor'),
        $textarea = $this.find('textarea');

      // If there is wp-editor remove it for avoid dupliated wp-editor conflicts.
      var $has_wp_editor = $this.find('.wp-editor-wrap').length || $this.find('.mce-container').length;

      if ($has_wp_editor) {
        $editor.empty();
        $editor.append($textarea);
        $textarea.css('display', '');
      }

      // Generate a unique id
      var uid = BetterChatSupport.helper.uid('better-chat-support-editor-');

      $textarea.attr('id', uid);

      // Get default editor settings
      var default_editor_settings = {
        tinymce: window.tinyMCEPreInit.mceInit.BetterChatSupport_wp_editor,
        quicktags: window.tinyMCEPreInit.qtInit.BetterChatSupport_wp_editor
      };

      // Get default editor settings
      var field_editor_settings = $editor.data('editor-settings');

      // Callback for old wp editor
      var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;

      if (wpEditor && wpEditor.hasOwnProperty('autop')) {
        wp.editor.autop = wpEditor.autop;
        wp.editor.removep = wpEditor.removep;
        wp.editor.initialize = wpEditor.initialize;
      }

      // Add on change event handle
      var editor_on_change = function (editor) {
        editor.on('change keyup', function () {
          var value = (field_editor_settings.wpautop) ? editor.getContent() : wp.editor.removep(editor.getContent());
          $textarea.val(value).trigger('change');
        });
      };

      // Extend editor selector and on change event handler
      default_editor_settings.tinymce = $.extend({}, default_editor_settings.tinymce, { selector: '#' + uid, setup: editor_on_change });

      // Override editor tinymce settings
      if (field_editor_settings.tinymce === false) {
        default_editor_settings.tinymce = false;
        $editor.addClass('better-chat-support-no-tinymce');
      }

      // Override editor quicktags settings
      if (field_editor_settings.quicktags === false) {
        default_editor_settings.quicktags = false;
        $editor.addClass('better-chat-support-no-quicktags');
      }

      // Wait until :visible
      var interval = setInterval(function () {
        if ($this.is(':visible')) {
          window.wp.editor.initialize(uid, default_editor_settings);
          clearInterval(interval);
        }
      });

      // Add Media buttons
      if (field_editor_settings.media_buttons && window.BetterChatSupport_media_buttons) {

        var $editor_buttons = $editor.find('.wp-media-buttons');

        if ($editor_buttons.length) {

          $editor_buttons.find('.better-chat-support-shortcode-button').data('editor-id', uid);

        } else {

          var $media_buttons = $(window.BetterChatSupport_media_buttons);

          $media_buttons.find('.better-chat-support-shortcode-button').data('editor-id', uid);

          $editor.prepend($media_buttons);

        }

      }

    });

  };

  //
  // Confirm
  //
  $.fn.BetterChatSupport_confirm = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        var confirm_text = $(this).data('confirm') || window.BetterChatSupport_vars.i18n.confirm;
        var confirm_answer = confirm(confirm_text);

        if (confirm_answer) {
          BetterChatSupport.vars.is_confirm = true;
          BetterChatSupport.vars.form_modified = false;
        } else {
          e.preventDefault();
          return false;
        }

      });
    });
  };

  $.fn.serializeObject = function () {

    var obj = {};

    $.each(this.serializeArray(), function (i, o) {
      var n = o.name,
        v = o.value;

      obj[n] = obj[n] === undefined ? v
        : $.isArray(obj[n]) ? obj[n].concat(v)
          : [obj[n], v];
    });

    return obj;

  };

  //
  // Options Save
  //
  $.fn.BetterChatSupport_save = function () {
    return this.each(function () {

      var $this = $(this),
        $buttons = $('.better-chat-support-save'),
        $panel = $('.better-chat-support-options'),
        flooding = false,
        timeout;

      $this.on('click', function (e) {

        if (!flooding) {

          var $text = $this.data('save'),
            $value = $this.val();

          $buttons.attr('value', $text);

          if ($this.hasClass('better-chat-support-save-ajax')) {

            e.preventDefault();

            $panel.addClass('better-chat-support-saving');
            $buttons.prop('disabled', true);

            window.wp.ajax.post('BetterChatSupport_' + $panel.data('unique') + '_ajax_save', {
              data: $('#better-chat-support-form').serializeJSONBetterChatSupport()
            })
              .done(function (response) {

                // clear errors
                $('.better-chat-support-error').remove();

                if (Object.keys(response.errors).length) {

                  var error_icon = '<i class="better-chat-support-label-error better-chat-support-error">!</i>';

                  $.each(response.errors, function (key, error_message) {

                    var $field = $('[data-depend-id="' + key + '"]'),
                      $link = $('a[href="#tab=' + $field.closest('.better-chat-support-section').data('section-id') + '"]'),
                      $tab = $link.closest('.better-chat-support-tab-item');

                    $field.closest('.better-chat-support-fieldset').append('<p class="better-chat-support-error better-chat-support-error-text">' + error_message + '</p>');

                    if (!$link.find('.better-chat-support-error').length) {
                      $link.append(error_icon);
                    }

                    if (!$tab.find('.better-chat-support-arrow .better-chat-support-error').length) {
                      $tab.find('.better-chat-support-arrow').append(error_icon);
                    }

                  });

                }

                $panel.removeClass('better-chat-support-saving');
                $buttons.prop('disabled', false).attr('value', $value);
                flooding = false;

                BetterChatSupport.vars.form_modified = false;
                BetterChatSupport.vars.$form_warning.hide();

                clearTimeout(timeout);

                var $result_success = $('.better-chat-support-form-success');
                $result_success.empty().append(response.notice).fadeIn('fast', function () {
                  timeout = setTimeout(function () {
                    $result_success.fadeOut('fast');
                  }, 1000);
                });

              })
              .fail(function (response) {
                alert(response.error);
              });

          } else {

            BetterChatSupport.vars.form_modified = false;

          }

        }

        flooding = true;

      });

    });
  };

  //
  // Option Framework
  //
  $.fn.BetterChatSupport_options = function () {
    return this.each(function () {

      var $this = $(this),
        $content = $this.find('.better-chat-support-content'),
        $form_success = $this.find('.better-chat-support-form-success'),
        $form_warning = $this.find('.better-chat-support-form-warning'),
        $save_button = $this.find('.better-chat-support-header .better-chat-support-save');

      BetterChatSupport.vars.$form_warning = $form_warning;

      // Shows a message white leaving theme options without saving
      if ($form_warning.length) {

        window.onbeforeunload = function () {
          return (BetterChatSupport.vars.form_modified) ? true : undefined;
        };

        $content.on('change keypress', ':input', function () {
          if (!BetterChatSupport.vars.form_modified) {
            $form_success.hide();
            $form_warning.fadeIn('fast');
            BetterChatSupport.vars.form_modified = true;
          }
        });

      }

      if ($form_success.hasClass('better-chat-support-form-show')) {
        setTimeout(function () {
          $form_success.fadeOut('fast');
        }, 1000);
      }

      $(document).keydown(function (event) {
        if ((event.ctrlKey || event.metaKey) && event.which === 83) {
          $save_button.trigger('click');
          event.preventDefault();
          return false;
        }
      });

    });
  };

  //
  // Taxonomy Framework
  //
  $.fn.BetterChatSupport_taxonomy = function () {
    return this.each(function () {

      var $this = $(this),
        $form = $this.parents('form');

      if ($form.attr('id') === 'addtag') {

        var $submit = $form.find('#submit'),
          $cloned = $this.find('.better-chat-support-field').BetterChatSupport_clone();

        $submit.on('click', function () {

          if (!$form.find('.form-required').hasClass('form-invalid')) {

            $this.data('inited', false);

            $this.empty();

            $this.html($cloned);

            $cloned = $cloned.BetterChatSupport_clone();

            $this.BetterChatSupport_reload_script();

          }

        });

      }

    });
  };

  //
  // Shortcode Framework
  //
  $.fn.BetterChatSupport_shortcode = function () {

    var base = this;

    base.shortcode_parse = function (serialize, key) {

      var shortcode = '';

      $.each(serialize, function (shortcode_key, shortcode_values) {

        key = (key) ? key : shortcode_key;

        shortcode += '[' + key;

        $.each(shortcode_values, function (shortcode_tag, shortcode_value) {

          if (shortcode_tag === 'content') {

            shortcode += ']';
            shortcode += shortcode_value;
            shortcode += '[/' + key + '';

          } else {

            shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);

          }

        });

        shortcode += ']';

      });

      return shortcode;

    };

    base.shortcode_tags = function (shortcode_tag, shortcode_value) {

      var shortcode = '';

      if (shortcode_value !== '') {

        if (typeof shortcode_value === 'object' && !$.isArray(shortcode_value)) {

          $.each(shortcode_value, function (sub_shortcode_tag, sub_shortcode_value) {

            // sanitize spesific key/value
            switch (sub_shortcode_tag) {

              case 'background-image':
                sub_shortcode_value = (sub_shortcode_value.url) ? sub_shortcode_value.url : '';
                break;

            }

            if (sub_shortcode_value !== '') {
              shortcode += ' ' + sub_shortcode_tag + '="' + sub_shortcode_value.toString() + '"';
            }

          });

        } else {

          shortcode += ' ' + shortcode_tag + '="' + shortcode_value.toString() + '"';

        }

      }

      return shortcode;

    };

    base.insertAtChars = function (_this, currentValue) {

      var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;

      if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
        obj.focus();
        return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
      } else {
        obj.focus();
        return currentValue;
      }

    };

    base.send_to_editor = function (html, editor_id) {

      var tinymce_editor;

      if (typeof tinymce !== 'undefined') {
        tinymce_editor = tinymce.get(editor_id);
      }

      if (tinymce_editor && !tinymce_editor.isHidden()) {
        tinymce_editor.execCommand('mceInsertContent', false, html);
      } else {
        var $editor = $('#' + editor_id);
        $editor.val(base.insertAtChars($editor, html)).trigger('change');
      }

    };

    return this.each(function () {

      var $modal = $(this),
        $load = $modal.find('.better-chat-support-modal-load'),
        $content = $modal.find('.better-chat-support-modal-content'),
        $insert = $modal.find('.better-chat-support-modal-insert'),
        $loading = $modal.find('.better-chat-support-modal-loading'),
        $select = $modal.find('select'),
        modal_id = $modal.data('modal-id'),
        nonce = $modal.data('nonce'),
        editor_id,
        target_id,
        gutenberg_id,
        sc_key,
        sc_name,
        sc_view,
        sc_group,
        $cloned,
        $button;

      $(document).on('click', '.better-chat-support-shortcode-button[data-modal-id="' + modal_id + '"]', function (e) {

        e.preventDefault();

        $button = $(this);
        editor_id = $button.data('editor-id') || false;
        target_id = $button.data('target-id') || false;
        gutenberg_id = $button.data('gutenberg-id') || false;

        $modal.removeClass('hidden');

        // single usage trigger first shortcode
        if ($modal.hasClass('better-chat-support-shortcode-single') && sc_name === undefined) {
          $select.trigger('change');
        }

      });

      $select.on('change', function () {

        var $option = $(this);
        var $selected = $option.find(':selected');

        sc_key = $option.val();
        sc_name = $selected.data('shortcode');
        sc_view = $selected.data('view') || 'normal';
        sc_group = $selected.data('group') || sc_name;

        $load.empty();

        if (sc_key) {

          $loading.show();

          window.wp.ajax.post('better-chat-support-get-shortcode-' + modal_id, {
            shortcode_key: sc_key,
            nonce: nonce
          })
            .done(function (response) {

              $loading.hide();

              var $appended = $(response.content).appendTo($load);

              $insert.parent().removeClass('hidden');

              $cloned = $appended.find('.better-chat-support--repeat-shortcode').BetterChatSupport_clone();

              $appended.BetterChatSupport_reload_script();
              $appended.find('.better-chat-support-fields').BetterChatSupport_reload_script();

            });

        } else {

          $insert.parent().addClass('hidden');

        }

      });

      $insert.on('click', function (e) {

        e.preventDefault();

        if ($insert.prop('disabled') || $insert.attr('disabled')) { return; }

        var shortcode = '';
        var serialize = $modal.find('.better-chat-support-field:not(.better-chat-support-depend-on)').find(':input:not(.ignore)').serializeObjectBetterChatSupport();

        switch (sc_view) {

          case 'contents':
            var contentsObj = (sc_name) ? serialize[sc_name] : serialize;
            $.each(contentsObj, function (sc_key, sc_value) {
              var sc_tag = (sc_name) ? sc_name : sc_key;
              shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']';
            });
            break;

          case 'group':

            shortcode += '[' + sc_name;
            $.each(serialize[sc_name], function (sc_key, sc_value) {
              shortcode += base.shortcode_tags(sc_key, sc_value);
            });
            shortcode += ']';
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            shortcode += '[/' + sc_name + ']';

            break;

          case 'repeater':
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            break;

          default:
            shortcode += base.shortcode_parse(serialize);
            break;

        }

        shortcode = (shortcode === '') ? '[' + sc_name + ']' : shortcode;

        if (gutenberg_id) {

          var content = window.BetterChatSupport_gutenberg_props.attributes.hasOwnProperty('shortcode') ? window.BetterChatSupport_gutenberg_props.attributes.shortcode : '';
          window.BetterChatSupport_gutenberg_props.setAttributes({ shortcode: content + shortcode });

        } else if (editor_id) {

          base.send_to_editor(shortcode, editor_id);

        } else {

          var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
          $textarea.val(base.insertAtChars($textarea, shortcode)).trigger('change');

        }

        $modal.addClass('hidden');

      });

      $modal.on('click', '.better-chat-support--repeat-button', function (e) {

        e.preventDefault();

        var $repeatable = $modal.find('.better-chat-support--repeatable');
        var $new_clone = $cloned.BetterChatSupport_clone();
        var $remove_btn = $new_clone.find('.better-chat-support-repeat-remove');

        var $appended = $new_clone.appendTo($repeatable);

        $new_clone.find('.better-chat-support-fields').BetterChatSupport_reload_script();

        BetterChatSupport.helper.name_nested_replace($modal.find('.better-chat-support--repeat-shortcode'), sc_group);

        $remove_btn.on('click', function () {

          $new_clone.remove();

          BetterChatSupport.helper.name_nested_replace($modal.find('.better-chat-support--repeat-shortcode'), sc_group);

        });

      });

      $modal.on('click', '.better-chat-support-modal-close, .better-chat-support-modal-overlay', function () {
        $modal.addClass('hidden');
      });

    });
  };

  //
  // WP Color Picker
  //
  if (typeof Color === 'function') {

    Color.prototype.toString = function () {

      if (this._alpha < 1) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt(this._color, 10).toString(16);

      if (this.error) { return ''; }

      if (hex.length < 6) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  BetterChatSupport.funcs.parse_color = function (color) {

    var value = color.replace(/\s+/g, ''),
      trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
      rgba = (trans < 100) ? true : false;

    return { value: value, transparent: trans, rgba: rgba };

  };

  $.fn.BetterChatSupport_color = function () {
    return this.each(function () {

      var $input = $(this),
        picker_color = BetterChatSupport.funcs.parse_color($input.val()),
        palette_color = window.BetterChatSupport_vars.color_palette.length ? window.BetterChatSupport_vars.color_palette : true,
        $container;

      // Destroy and Reinit
      if ($input.hasClass('wp-color-picker')) {
        $input.closest('.wp-picker-container').after($input).remove();
      }

      $input.wpColorPicker({
        palettes: palette_color,
        change: function (event, ui) {

          var ui_color_value = ui.color.toString();

          $container.removeClass('better-chat-support--transparent-active');
          $container.find('.better-chat-support--transparent-offset').css('background-color', ui_color_value);
          $input.val(ui_color_value).trigger('change');

        },
        create: function () {

          $container = $input.closest('.wp-picker-container');

          var a8cIris = $input.data('a8cIris'),
            $transparent_wrap = $('<div class="better-chat-support--transparent-wrap">' +
              '<div class="better-chat-support--transparent-slider"></div>' +
              '<div class="better-chat-support--transparent-offset"></div>' +
              '<div class="better-chat-support--transparent-text"></div>' +
              '<div class="better-chat-support--transparent-button">transparent <i class="icofont-toggle-off"></i></div>' +
              '</div>').appendTo($container.find('.wp-picker-holder')),
            $transparent_slider = $transparent_wrap.find('.better-chat-support--transparent-slider'),
            $transparent_text = $transparent_wrap.find('.better-chat-support--transparent-text'),
            $transparent_offset = $transparent_wrap.find('.better-chat-support--transparent-offset'),
            $transparent_button = $transparent_wrap.find('.better-chat-support--transparent-button');

          if ($input.val() === 'transparent') {
            $container.addClass('better-chat-support--transparent-active');
          }

          $transparent_button.on('click', function () {
            if ($input.val() !== 'transparent') {
              $input.val('transparent').trigger('change').removeClass('iris-error');
              $container.addClass('better-chat-support--transparent-active');
            } else {
              $input.val(a8cIris._color.toString()).trigger('change');
              $container.removeClass('better-chat-support--transparent-active');
            }
          });

          $transparent_slider.slider({
            value: picker_color.transparent,
            step: 1,
            min: 0,
            max: 100,
            slide: function (event, ui) {

              var slide_value = parseFloat(ui.value / 100);
              a8cIris._color._alpha = slide_value;
              $input.wpColorPicker('color', a8cIris._color.toString());
              $transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

            },
            create: function () {

              var slide_value = parseFloat(picker_color.transparent / 100),
                text_value = slide_value < 1 ? slide_value : '';

              $transparent_text.text(text_value);
              $transparent_offset.css('background-color', picker_color.value);

              $container.on('click', '.wp-picker-clear', function () {

                a8cIris._color._alpha = 1;
                $transparent_text.text('');
                $transparent_slider.slider('option', 'value', 100);
                $container.removeClass('better-chat-support--transparent-active');
                $input.trigger('change');

              });

              $container.on('click', '.wp-picker-default', function () {

                var default_color = BetterChatSupport.funcs.parse_color($input.data('default-color')),
                  default_value = parseFloat(default_color.transparent / 100),
                  default_text = default_value < 1 ? default_value : '';

                a8cIris._color._alpha = default_value;
                $transparent_text.text(default_text);
                $transparent_slider.slider('option', 'value', default_color.transparent);

                if (default_color.value === 'transparent') {
                  $input.removeClass('iris-error');
                  $container.addClass('better-chat-support--transparent-active');
                }

              });

            }
          });
        }
      });

    });
  };

  //
  // ChosenJS
  //
  $.fn.BetterChatSupport_chosen = function () {
    return this.each(function () {

      var $this = $(this),
        $inited = $this.parent().find('.chosen-container'),
        is_sortable = $this.hasClass('better-chat-support-chosen-sortable') || false,
        is_ajax = $this.hasClass('better-chat-support-chosen-ajax') || false,
        is_multiple = $this.attr('multiple') || false,
        set_width = is_multiple ? '100%' : 'auto',
        set_options = $.extend({
          allow_single_deselect: true,
          disable_search_threshold: 10,
          width: set_width,
          no_results_text: window.BetterChatSupport_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

      if ($inited.length) {
        $inited.remove();
      }

      // Chosen ajax
      if (is_ajax) {

        var set_ajax_options = $.extend({
          data: {
            type: 'post',
            nonce: '',
          },
          allow_single_deselect: true,
          disable_search_threshold: -1,
          width: '100%',
          min_length: 3,
          type_delay: 500,
          typing_text: window.BetterChatSupport_vars.i18n.typing_text,
          searching_text: window.BetterChatSupport_vars.i18n.searching_text,
          no_results_text: window.BetterChatSupport_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

        $this.BetterChatSupportAjaxChosen(set_ajax_options);

      } else {

        $this.chosen(set_options);

      }

      // Chosen keep options order
      if (is_multiple) {

        var $hidden_select = $this.parent().find('.better-chat-support-hide-select');
        var $hidden_value = $hidden_select.val() || [];

        $this.on('change', function (obj, result) {

          if (result && result.selected) {
            $hidden_select.append('<option value="' + result.selected + '" selected="selected">' + result.selected + '</option>');
          } else if (result && result.deselected) {
            $hidden_select.find('option[value="' + result.deselected + '"]').remove();
          }

          // Force customize refresh
          if (window.wp.customize !== undefined && $hidden_select.children().length === 0 && $hidden_select.data('customize-setting-link')) {
            window.wp.customize.control($hidden_select.data('customize-setting-link')).setting.set('');
          }

          $hidden_select.trigger('change');

        });

        // Chosen order abstract
        $this.BetterChatSupportChosenOrder($hidden_value, true);

      }

      // Chosen sortable
      if (is_sortable) {

        var $chosen_container = $this.parent().find('.chosen-container');
        var $chosen_choices = $chosen_container.find('.chosen-choices');

        $chosen_choices.bind('mousedown', function (event) {
          if ($(event.target).is('span')) {
            event.stopPropagation();
          }
        });

        $chosen_choices.sortable({
          items: 'li:not(.search-field)',
          helper: 'orginal',
          cursor: 'move',
          placeholder: 'search-choice-placeholder',
          start: function (e, ui) {
            ui.placeholder.width(ui.item.innerWidth());
            ui.placeholder.height(ui.item.innerHeight());
          },
          update: function (e, ui) {

            var select_options = '';
            var chosen_object = $this.data('chosen');
            var $prev_select = $this.parent().find('.better-chat-support-hide-select');

            $chosen_choices.find('.search-choice-close').each(function () {
              var option_array_index = $(this).data('option-array-index');
              $.each(chosen_object.results_data, function (index, data) {
                if (data.array_index === option_array_index) {
                  select_options += '<option value="' + data.value + '" selected>' + data.value + '</option>';
                }
              });
            });

            $prev_select.children().remove();
            $prev_select.append(select_options);
            $prev_select.trigger('change');

          }
        });

      }

    });
  };

  //
  // Helper Checkbox Checker
  //
  $.fn.BetterChatSupport_checkbox = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('.better-chat-support--input'),
        $checkbox = $this.find('.better-chat-support--checkbox');

      $checkbox.on('click', function () {
        $input.val(Number($checkbox.prop('checked'))).trigger('change');
      });

    });
  };

  //
  // Helper Check/Uncheck All
  //
  $.fn.BetterChatSupport_checkbox_all = function () {
    return this.each(function () {

      var $this = $(this);

      $this.on('click', function () {

        var $inputs = $this.closest('.better-chat-support-field-checkbox').find(':input'),
          uncheck = false;

        $inputs.each(function () {
          if (!$(this).prop('checked')) {
            uncheck = true;
          }
        });

        if (uncheck) {
          $inputs.prop('checked', 'checked');
          $inputs.attr('checked', 'checked');
        } else {
          $inputs.prop('checked', '');
          $inputs.removeAttr('checked');
        }

        $inputs.first().trigger('change');

      });

    });
  };

  //
  // Siblings
  //
  $.fn.BetterChatSupport_siblings = function () {
    return this.each(function () {

      var $this = $(this),
        $siblings = $this.find('.better-chat-support--sibling'),
        multiple = $this.data('multiple') || false;

      $siblings.on('click', function () {

        var $sibling = $(this);

        if (multiple) {

          if ($sibling.hasClass('better-chat-support--active')) {
            $sibling.removeClass('better-chat-support--active');
            $sibling.find('input').prop('checked', false).trigger('change');
          } else {
            $sibling.addClass('better-chat-support--active');
            $sibling.find('input').prop('checked', true).trigger('change');
          }

        } else {

          $this.find('input').prop('checked', false);
          $sibling.find('input').prop('checked', true).trigger('change');
          $sibling.addClass('better-chat-support--active').siblings().removeClass('better-chat-support--active');

        }

      });

    });
  };

  //
  // Help Tooltip
  //
  $.fn.BetterChatSupport_help = function() {
    return this.each( function() {

      var $this = $(this),
          $tooltip,
          offset_left;
      $this.on({
        mouseenter: function () {
          $tooltip = $('<div class="better-chat-support-tooltip"></div>').html($this.find('.better-chat-support-help-text').html()).appendTo('body');

          offset_left = (BetterChatSupport.vars.is_rtl) ? $this.offset().left - $tooltip.outerWidth() : ($this.offset().left + 32);
          var $top = $this.offset().top - (($tooltip.outerHeight() / 2) - 6);
          // This block used for support tooltip.
          if ($this.find('.better-chat-support-tooltip').length > 0) {
            $top = $this.offset().top + 46;
            offset_left = $this.offset().left - 249;
          }
          $($tooltip).addClass('better-chat-support-tooltip-hover-effect');
          $tooltip.css({
            top: $top,
            left: offset_left,
            textAlign: 'left',
          });
        },
        mouseleave: function () {
          if (!$tooltip.is(':hover')) {
            $(this).removeClass('better-chat-support-tooltip-hover-effect');
            $tooltip.addClass('better-chat-support-fade-out');
              // Wait for the transition to complete, then remove the tooltip
              $tooltip.on('transitionend', function() {
                  $tooltip.remove();
              });
        	}
        }
      });
      // Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
      $('body').on('mouseleave', '.better-chat-support-tooltip', function () {
        
        if ($tooltip !== undefined) {
          $(this).removeClass('better-chat-support-tooltip-hover-effect');
          $tooltip.addClass('better-chat-support-fade-out');
          // Wait for the transition to complete, then remove the tooltip
          $tooltip.on('transitionend', function() {
              $tooltip.remove();
          });
        }
      });
    });
  };

  //
  // Customize Refresh
  //
  $.fn.BetterChatSupport_customizer_refresh = function () {
    return this.each(function () {

      var $this = $(this),
        $complex = $this.closest('.better-chat-support-customize-complex');

      if ($complex.length) {

        var unique_id = $complex.data('unique-id');

        if (unique_id === undefined) {
          return;
        }

        var $input = $complex.find(':input'),
          option_id = $complex.data('option-id'),
          obj = $input.serializeObjectBetterChatSupport(),
          data = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '',
          control = window.wp.customize.control(unique_id + '[' + option_id + ']');

        // clear the value to force refresh.
        control.setting._value = null;

        control.setting.set(data);

      } else {

        $this.find(':input').first().trigger('change');

      }

      $(document).trigger('better-chat-support-customizer-refresh', $this);

    });
  };

  //
  // Customize Listen Form Elements
  //
  $.fn.BetterChatSupport_customizer_listen = function (options) {

    var settings = $.extend({
      closest: false,
    }, options);

    return this.each(function () {

      if (window.wp.customize === undefined) { return; }

      var $this = (settings.closest) ? $(this).closest('.better-chat-support-customize-complex') : $(this),
        $input = $this.find(':input'),
        unique_id = $this.data('unique-id'),
        option_id = $this.data('option-id');

      if (unique_id === undefined) {
        return;
      }

      $input.on('change keyup better-chat-support.change', function () {

        var obj = $this.find(':input').serializeObjectBetterChatSupport();
        var val = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '';

        window.wp.customize.control(unique_id + '[' + option_id + ']').setting.set(val);

      });

    });
  };

  //
  // Customizer Listener for Reload JS
  //
  $(document).on('expanded', '.control-section', function () {

    var $this = $(this);

    if ($this.hasClass('open') && !$this.data('inited')) {

      var $fields = $this.find('.better-chat-support-customize-field');
      var $complex = $this.find('.better-chat-support-customize-complex');

      if ($fields.length) {
        $this.BetterChatSupport_dependency();
        $fields.BetterChatSupport_reload_script({ dependency: false });
        $complex.BetterChatSupport_customizer_listen();
      }

      $this.data('inited', true);

    }

  });

  //
  // Window on resize
  //
  BetterChatSupport.vars.$window.on('resize better-chat-support.resize', BetterChatSupport.helper.debounce(function (event) {

    var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? BetterChatSupport.vars.$window.width() : window.innerWidth;

    if (window_width <= 782 && !BetterChatSupport.vars.onloaded) {
      $('.better-chat-support-section').BetterChatSupport_reload_script();
      BetterChatSupport.vars.onloaded = true;
    }

  }, 200)).trigger('better-chat-support.resize');

  //
  // Widgets Framework
  //
  $.fn.BetterChatSupport_widgets = function () {
    return this.each(function () {

      $(document).on('widget-added widget-updated', function (event, $widget) {

        var $fields = $widget.find('.better-chat-support-fields');

        if ($fields.length) {
          $fields.BetterChatSupport_reload_script();
        }

      });

      $(document).on('click', '.widget-top', function (event) {

        var $fields = $(this).parent().find('.better-chat-support-fields');

        if ($fields.length) {
          $fields.BetterChatSupport_reload_script();
        }

      });

      $('.widgets-sortables, .control-section-sidebar').on('sortstop', function (event, ui) {
        ui.item.find('.better-chat-support-fields').BetterChatSupport_reload_script_retry();
      });

    });
  };

  //
  // Nav Menu Options Framework
  //
  $.fn.BetterChatSupport_nav_menu = function () {
    return this.each(function () {

      var $navmenu = $(this);

      $navmenu.on('click', 'a.item-edit', function () {
        $(this).closest('li.menu-item').find('.better-chat-support-fields').BetterChatSupport_reload_script();
      });

      $navmenu.on('sortstop', function (event, ui) {
        ui.item.find('.better-chat-support-fields').BetterChatSupport_reload_script_retry();
      });

    });
  };

  //
  // Retry Plugins
  //
  $.fn.BetterChatSupport_reload_script_retry = function () {
    return this.each(function () {

      var $this = $(this);

      if ($this.data('inited')) {
        $this.children('.better-chat-support-field-wp_editor').BetterChatSupport_field_wp_editor();
      }

    });
  };

  //
  // Reload Plugins
  //
  $.fn.BetterChatSupport_reload_script = function (options) {

    var settings = $.extend({
      dependency: true,
    }, options);

    return this.each(function () {

      var $this = $(this);

      // Avoid for conflicts
      if (!$this.data('inited')) {

        // Field plugins
        $this.children('.better-chat-support-field-accordion').BetterChatSupport_field_accordion();
        $this.children('.better-chat-support-field-backup').BetterChatSupport_field_backup();
        $this.children('.better-chat-support-field-background').BetterChatSupport_field_background();
        $this.children('.better-chat-support-field-code_editor').BetterChatSupport_field_code_editor();
        $this.children('.better-chat-support-field-date').BetterChatSupport_field_date();
        $this.children('.better-chat-support-field-datetime').BetterChatSupport_field_datetime();
        $this.children('.better-chat-support-field-fieldset').BetterChatSupport_field_fieldset();
        $this.children('.better-chat-support-field-gallery').BetterChatSupport_field_gallery();
        $this.children('.better-chat-support-field-group').BetterChatSupport_field_group();
        $this.children('.better-chat-support-field-icon').BetterChatSupport_field_icon();
        $this.children('.better-chat-support-field-link').BetterChatSupport_field_link();
        $this.children('.better-chat-support-field-media').BetterChatSupport_field_media();
        $this.children('.better-chat-support-field-map').BetterChatSupport_field_map();
        $this.children('.better-chat-support-field-repeater').BetterChatSupport_field_repeater();
        $this.children('.better-chat-support-field-slider').BetterChatSupport_field_slider();
        $this.children('.better-chat-support-field-sortable').BetterChatSupport_field_sortable();
        $this.children('.better-chat-support-field-sorter').BetterChatSupport_field_sorter();
        $this.children('.better-chat-support-field-spinner').BetterChatSupport_field_spinner();
        $this.children('.better-chat-support-field-switcher').BetterChatSupport_field_switcher();
        $this.children('.better-chat-support-field-section_tab').BetterChatSupport_field_section_tab();
        $this.children('.better-chat-support-field-tabbed').BetterChatSupport_field_tabbed();
        $this.children('.better-chat-support-field-typography').BetterChatSupport_field_typography();
        $this.children('.better-chat-support-field-upload').BetterChatSupport_field_upload();
        $this.children('.better-chat-support-field-wp_editor').BetterChatSupport_field_wp_editor();

        // Field colors
        $this.children('.better-chat-support-field-border').find('.better-chat-support-color').BetterChatSupport_color();
        $this.children('.better-chat-support-field-background').find('.better-chat-support-color').BetterChatSupport_color();
        $this.children('.better-chat-support-field-color').find('.better-chat-support-color').BetterChatSupport_color();
        $this.children('.better-chat-support-field-color_group').find('.better-chat-support-color').BetterChatSupport_color();
        $this.children('.better-chat-support-field-link_color').find('.better-chat-support-color').BetterChatSupport_color();
        $this.children('.better-chat-support-field-typography').find('.better-chat-support-color').BetterChatSupport_color();

        // Field chosenjs
        $this.children('.better-chat-support-field-select').find('.better-chat-support-chosen').BetterChatSupport_chosen();

        // Field Checkbox
        $this.children('.better-chat-support-field-checkbox').find('.better-chat-support-checkbox').BetterChatSupport_checkbox();
        $this.children('.better-chat-support-field-checkbox').find('.better-chat-support-checkbox-all').BetterChatSupport_checkbox_all();

        // Field Siblings
        $this.children('.better-chat-support-field-button_set').find('.better-chat-support-siblings').BetterChatSupport_siblings();
        $this.children('.better-chat-support-field-image_select').find('.better-chat-support-siblings').BetterChatSupport_siblings();
        $this.children('.better-chat-support-field-layout_preset').find('.better-chat-support-siblings').BetterChatSupport_siblings();
        $this.children('.better-chat-support-field-palette').find('.better-chat-support-siblings').BetterChatSupport_siblings();

        // Help Tooptip
        $this.children('.better-chat-support-field').find('.better-chat-support-help').BetterChatSupport_help();

        if (settings.dependency) {
          $this.BetterChatSupport_dependency();
        }

        $this.data('inited', true);

        $(document).trigger('better-chat-support-reload-script', $this);

      }

    });
  };

  //
  // Document ready and run scripts
  //
  $(document).ready(function () {

    $('.better-chat-support-save').BetterChatSupport_save();
    $('.better-chat-support-options').BetterChatSupport_options();
    $('.better-chat-support-sticky-header').BetterChatSupport_sticky();
    $('.better-chat-support-nav-options').BetterChatSupport_nav_options();
    $('.better-chat-support-nav-metabox').BetterChatSupport_nav_metabox();
    $('.better-chat-support-taxonomy').BetterChatSupport_taxonomy();
    $('.better-chat-support-page-templates').BetterChatSupport_page_templates();
    $('.better-chat-support-post-formats').BetterChatSupport_post_formats();
    $('.better-chat-support-shortcode').BetterChatSupport_shortcode();
    $('.better-chat-support-search').BetterChatSupport_search();
    $('.better-chat-support-confirm').BetterChatSupport_confirm();
    $('.better-chat-support-expand-all').BetterChatSupport_expand_all();
    $('.better-chat-support-onload').BetterChatSupport_reload_script();
    $('#widgets-editor').BetterChatSupport_widgets();
    $('#widgets-right').BetterChatSupport_widgets();
    $('#menu-to-edit').BetterChatSupport_nav_menu();

    // Shortcode Copied
    $(document).on('click', '#shortcode_copy', function (e) {
      e.preventDefault();
      /* Get the text field */
      $(this).siblings('#shortcode').select();
      /* Select the text field */
      document.execCommand('copy');
      $('.shortcode_after_copy').animate(
        {
          opacity: 1,
          bottom: 25,
        },
        300
      );
      setTimeout(function () {
        jQuery('.shortcode_after_copy').animate(
          {
            opacity: 0,
          },
          200
        );
        jQuery('.shortcode_after_copy').animate(
          {
            bottom: 0,
          },
          0
        );
      }, 2000);
    });

    // Preloader
    $('.BetterChatSupport').removeClass('better-chat-support-preloader');

  });

  $.fn.getFieldInfo = function () {
    var fields = ["All Fields: {form_fields}"];
    this.find('.better-chat-support-cloneable-item').each(function (index) {
      var $item = $(this);
      var fieldType = $item.find('.field_select_items_select select').val();

      if (['recaptcha', 'checkbox', 'submit'].includes(fieldType)) {
        return;
      }

      var labelClass = '.field_select_label';
      var $labelField = $item.find(labelClass + ' input[type="text"]');

      var label = $labelField.val() || $item.find('input[type="text"]:visible').val() || 'Field Label';
      var value = fieldType;

      if (value) {
        fields.push(`${label}: {${value}_${index + 1}}`);
      }
    });

    // Append static values
    fields.push(
    "Global Vars: {siteTitle}, {siteEmail}, {currentURL}, {currentTitle}, {siteURL}, {ip}, {date}",
  "{PRODUCT_START} WooCommerce Vars: {productName}, {productSlug}, {productSku}, {productPrice}, {productRegularPrice}, {productSalePrice}, {productStockStatus} {PRODUCT_END}",
  "<b>Conditional Blocks:</b> {PRODUCT_START} ... {PRODUCT_END}, {NOT_PRODUCT_START} ... {NOT_PRODUCT_END}, {LOGGEDIN_START} ... {LOGGEDIN_END}, {NOT_LOGGEDIN_START} ... {NOT_LOGGEDIN_END}"
    );
    return fields.join('<br>');
  };

  $(document).ready(function () {
    function updateEmailVars() {
      var $groupField = $('.better-chat-support-form-fields-wrapper');
      var $messageVars = $('.message_variables');

      if ($messageVars.length && $groupField.length) {
        var fieldInfo = $groupField.find('.better-chat-support-cloneable-wrapper').getFieldInfo();
        $messageVars.html(fieldInfo);
      }
    }

    setTimeout(updateEmailVars, 100);

    $(document).on('change', '.field_select_items_select select', function () {
      setTimeout(updateEmailVars, 100);
    });

    $(document).on('keyup input change', '.better-chat-support-cloneable-item input[type="text"]', function () {
      updateEmailVars();
    });

    $('.better-chat-support-cloneable-wrapper').on('better-chat-support.field-info-update', function () {
      updateEmailVars();
    });
  });

  $(document).on('click', '.wrapper_class_form', function () {
    if ($('.better-chat-support--active.wrapper_class_form').length) {
      $('.better_chat_support_type_of_whatsapp input[value="number"]').prop('checked', true).trigger('change');
    }
  });


  $(document).on('keyup change', '#better-chat-support-form', function (e) {
    e.preventDefault();
    var $button = $(this).find('.better-chat-support-save.better-chat-support-save-ajax');
    $button.css({ "background-color": "#1a74e4", "pointer-events": "initial" }).val('Save Settings');
  });
  $(document).on('click change', '#better-chat-support-form .better-chat-support-cloneable-helper, #better-chat-support-form .better-chat-support-cloneable-helper .sp_wgs-icon-clone, #better-chat-support-form .better-chat-support-cloneable-helper .sp_wgs-icon-drag-and-drop, .better-chat-support-cloneable-add', function (e) {
    e.preventDefault();
    var $button = $('#better-chat-support-form').find('.better-chat-support-save.better-chat-support-save-ajax');
    $button.css({ "background-color": "#1a74e4", "pointer-events": "initial" }).val('Save Settings');
  });
  // If no item added Add to assign layout, trigger to add one item.
  setTimeout(() => {
    if ($('#better-chat-support-section-assign_layout .better-chat-support-cloneable-wrapper .better-chat-support-cloneable-item').length == 0 && $('#better-chat-support-section-assign_layout .better-chat-support-cloneable-add').length > 0) {
      $('#better-chat-support-section-assign_layout .better-chat-support-cloneable-add').trigger('click');
    }
  }, 500);
  $(".better-chat-support-save").on('click', function (e) {
    e.preventDefault();
    $(this).css({ "background-color": "#C5C5C6", "pointer-events": "none", "padding-left": "38px" }).val('Changes Saved');
  })  

  // Disable Fields //
  $("select option:contains((Pro))").attr("disabled", true).css("opacity", "1");
  // Disable and style the switcher element
  $(".switcher_pro_only .better-chat-support--switcher")
    .attr("disabled", "disabled")
    .addClass("only_pro_switcher")
    .css({ background: "#B0BCC4" });

  // Apply common styling to elements with the 'only_pro_switcher' class
  $(".only_pro_switcher").css({
    "pointer-events": "none",
    color: "#8796A1",
    position: "relative",
  });

  // Pro only tag.
  $(".better-chat-support-pagination-type li:nth-child(n+2) input").attr(
    "disabled",
    true
  );
  $("label:contains((Pro))").css({
    "pointer-events": "none",
    opacity: "0.8",
    "user-select": "none",
  });
  $("label:contains((Pro)) input").attr("disabled", true).css("opacity", "1");
  $("select option:contains((Pro))").attr("disabled", true).css("opacity", "1");
})(jQuery, window, document);
