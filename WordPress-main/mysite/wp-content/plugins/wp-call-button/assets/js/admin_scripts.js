/* globals jQuery */
jQuery( document ).ready( function( $ ){
  // Instantiate minicolor picker.
  $( '#wpcallbtn_button_color_static, #wpcallbtn_button_color' ).minicolors();

  // Widget events.
  var widgetAdvancedLinkShowSel = '.wpcb-link-adv-show',
      widgetSettingSel = '.wpcb-widget-settings',
      $body = $('body'),
      initializeCallButtonWidgetSettings = function(widget) {
        var  widgetDom = $(widget);
        widgetDom.find( '.input_wpcallbtn_button_color' ).minicolors({
          change: function(value, opacity) {
            $(this).next('.minicolors-swatch').find('.minicolors-swatch-color').css('background', value);
            $(this).next('.minicolors-swatch').css('background', value);
          }
        });
        widgetDom.find( '.input_wpcallbtn_button_color_static' ).minicolors();
      };

  // Listen to widget events.
  $(document).on( 'widget-added widget-updated', function( e, widget ){
    // check if the call button widget was updated
    if(widget && widget[0] && widget[0].id.indexOf( 'wp-call-button' ) !== false) {
      initializeCallButtonWidgetSettings(widget[0]);
    }
  });

  // Listen to click events for the show hide.
  $body.on( 'click', widgetAdvancedLinkShowSel, function(){
    var $this = $(this);
    $this.next(widgetSettingSel).toggle( 'slow' );
    $this.text($this.text() === $this.data('show-msg') ? $this.data('hide-msg') : $this.data('show-msg'));
  });

  // Visual shortcode generator events.
  var $wpcallbtnButtonTextStatic = $( '#wpcallbtn_button_text_static' ),
      $wpcallbtnButtonColorStatic = $( '#wpcallbtn_button_color_static' ),
      $wpcallbtnButtonMobileOnly = $( '#wpcallbtn_button_mobile_only' ),
      $wpcallbtnButtonShortcode = $( '#wpcallbtn_button_shortcode' );

  // Shortcode update events.
  var wpcallbtn_shortcode_event = function ( e ) {
    $wpcallbtnButtonShortcode.val( '[wp_call_button btn_text="' + $wpcallbtnButtonTextStatic.val() + '" btn_color="' + $wpcallbtnButtonColorStatic.val() + '" hide_phone_icon="' + ( $wpcallbtnButtonMobileOnly.is( ':checked' ) ? 'yes' : 'no' ) + '"]' );
  };

  // Register shortcode events.
  $wpcallbtnButtonTextStatic.on( 'input', function ( e ) { wpcallbtn_shortcode_event( e ); });
  $wpcallbtnButtonColorStatic.on( 'input', function ( e ) { wpcallbtn_shortcode_event( e ); });
  $wpcallbtnButtonMobileOnly.change( function ( e ) { wpcallbtn_shortcode_event( e ); });

  // Populate shortcode selector & clipboard copy on page load.
  if ( $wpcallbtnButtonTextStatic.length > 0 ) {
    wpcallbtn_shortcode_event();

    // Set clipboard copy
    new ClipboardJS( '#wpcallbtn-copy-btn, #wpcallbtn_button_shortcode' );
  }

  // Instantiate the Select2 posts picker for filter field.
  $("#wpcallbtn_button_filter_ids_show,#wpcallbtn_button_filter_ids_hide").select2({
    /* containerCssClass : "wpcallbtn-show-hide", */
    ajax: {
      url: wpcallbtn_ajaxvars.ajaxurl,
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          page: params.page,
          _wp_call_btn_search_nonce: wpcallbtn_ajaxvars.nonce,
          action: 'wp-call-button_get_posts'
        };
      },
      processResults: function (data, params) {
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be used
        params.page = params.page || 1;

        return {
          results: data.data,
          pagination: {
            more: (params.page * 30) < data.total_count
          }
        };
      },
      cache: true
    },
    placeholder: wpcallbtn_ajaxvars.placeholder,
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 3,
    templateResult: formatPost,
    templateSelection: formatPostSelection,
    maximumSelectionSize: 10,
    minimumResultsForSearch: Infinity,
    multiple: true,
  });

  function formatPost (post) {
    if (post.loading) {
      return post.value;
    }

    var markup = "<div class='select2-result-repository'>" +
        "<div class='select2-result-repository__title'>" + post.text + "</div>";
    markup += "</div>";

    return markup;
  }

  function formatPostSelection (post) {
    return post.text;
  }

  // Show / hide Select2 fields based on option choices.
  var $filter_hide = $( '#wpcallbtn_button_filter_type-2' ),
      $filter_show = $( '#wpcallbtn_button_filter_type-1' ),
      $filter_none = $( '#wpcallbtn_button_filter_type-3' ),
      $filter_hide_sel2 = $( '.items-hide-only' ),
      $filter_show_sel2 = $( '.items-show-only' );
  if ( $filter_hide.length === 1 && $filter_show.length === 1 ) {
    if ( ! $filter_hide.is( ':checked' ) ) {
      $filter_hide_sel2.hide();
    }
    if ( ! $filter_show.is( ':checked' ) ) {
      $filter_show_sel2.hide();
    }
    $filter_hide.on('click', function(){
      $filter_hide_sel2.show();
      $filter_show_sel2.hide();
    });
    $filter_show.on('click', function(){
      $filter_hide_sel2.hide();
      $filter_show_sel2.show();
    });
    $filter_none.on('click', function(){
      $filter_hide_sel2.hide();
      $filter_show_sel2.hide();
    });
  }

  // Setup the smart Phone field.
  // Taken from intlTelInput Validate examples package
  var input = document.querySelector("#wpcallbtn_phone_num"),
      errorMsg = document.querySelector("#error-msg"),
      validMsg = document.querySelector("#valid-msg");

  // Register the Phone element only if dom exists.
  if ( input !== null ) {
    // here, the index maps to the error code returned from getValidationError - see readme
    var errorMap = wpcallbtn_ajaxvars.phone_validate_errors;

    // initialise plugin
    var iti = window.intlTelInput(input, {
      utilsScript: "utils.js",
      formatOnDisplay: false,
      hiddenInput: "wpcallbtn_full_phone_num",
      separateDialCode: true
    });

    var reset = function() {
      input.classList.remove("error");
      errorMsg.innerHTML = "";
      errorMsg.classList.add("hide");
      validMsg.classList.add("hide");
    };

    // on blur: validate
    input.addEventListener( 'blur', function() {
      reset();
      if (input.value.trim()) {
        if (iti.isValidNumber()) {
          validMsg.classList.remove("hide");
        } else {
          input.classList.add("error");
          var errorCode = iti.getValidationError();
          errorMsg.innerHTML = errorMap[errorCode];
          errorMsg.classList.remove("hide");
        }
      }
    });

    // on keyup / change flag: reset
    input.addEventListener( 'change', reset);
    input.addEventListener( 'keyup', reset);
  }
});
