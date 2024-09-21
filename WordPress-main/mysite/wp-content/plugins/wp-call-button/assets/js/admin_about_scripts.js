jQuery(document).ready(function($){
    $( '.am-plugins-holder .plugin-item .details' ).matchHeight();
    /*
     * Install/Active the plugins.
     */
    $( document ).on( 'click', '.am-plugins-holder .plugin-item .action-button .button.perform-action', function( e ) {
      e.preventDefault();

      var $btn = $( this );

      if ( $btn.hasClass( 'disabled' ) || $btn.hasClass( 'loading' ) ) {
        return false;
      }

      var $plugin = $btn.closest( '.plugin-item' ),
        plugin = $btn.attr( 'data-plugin' ),
        task,
        cssClass,
        statusText,
        buttonText,
        errorText,
        successText;

      $btn.prop( 'disabled', true ).addClass( 'loading' );
      $btn.text( wpcallbtn_about_ajaxvars.plugin_processing );

      if ( $btn.hasClass( 'status-inactive' ) ) {
        // Activate.
        task       = 'about_plugin_activate';
        cssClass   = 'status-active button button-secondary disabled';
        statusText = wpcallbtn_about_ajaxvars.plugin_active;
        buttonText = wpcallbtn_about_ajaxvars.plugin_activated;
        errorText  = wpcallbtn_about_ajaxvars.plugin_activate;

      } else if ( $btn.hasClass( 'status-download' ) ) {
        // Install & Activate.
        task       = 'about_plugin_install';
        cssClass   = 'status-active button disabled';
        statusText = wpcallbtn_about_ajaxvars.plugin_active;
        buttonText = wpcallbtn_about_ajaxvars.plugin_activated;
        errorText  = wpcallbtn_about_ajaxvars.plugin_activate;

      } else {
        return;
      }

      // Setup ajax POST data.
      var data = {
        action: 'wp_call_button_about_ajax',
        task: task,
        nonce : wpcallbtn_about_ajaxvars.nonce,
        plugin: plugin
      };

      $.post( wpcallbtn_about_ajaxvars.ajax_url, data, function( res ) {

        if ( res.success ) {
          if ( 'about_plugin_install' === task ) {
            $btn.attr( 'data-plugin', res.data.basename );
            successText = res.data.msg;
            if ( ! res.data.is_activated ) {
              cssClass = 'button';
              statusText = wpcallbtn_about_ajaxvars.plugin_inactive;
              buttonText = wpcallbtn_about_ajaxvars.plugin_activate;
            }
          } else {
            successText = res.data;
          }
          $plugin.find( '.actions' ).append( '<div class="msg success">'+successText+'</div>' );
          $plugin.find( 'span.status-label' )
              .removeClass( 'status-active status-inactive status-download' )
              .addClass( cssClass )
              .removeClass( 'button button-primary button-secondary disabled' )
              .text( statusText );
          $btn
            .removeClass( 'status-active status-inactive status-download' )
            .removeClass( 'button button-primary button-secondary disabled' )
            .addClass( cssClass ).html( buttonText );
        } else {
          if (
            res.hasOwnProperty('data') &&
            res.data.hasOwnProperty(0) &&
            res.data[0].hasOwnProperty('code') &&
            res.data[0].code === 'download_failed'
          ) {
            // Specific server-returned error.
            $plugin.find( '.actions' ).append( '<div class="msg error">'+wpcallbtn_about_ajaxvars.plugin_install_error+'</div>' );
          } else {
            // Generic error.
            $plugin.find( '.actions' ).append( '<div class="msg error">'+ ( res.data || res ) +'</div>' );
          }
          $btn.html( errorText );
        }

        $btn.prop( 'disabled', false ).removeClass( 'loading' );

        // Automatically clear plugin messages after 3 seconds.
        setTimeout( function() {
          $( '.plugin-item .msg' ).remove();
        }, 3000 );

      }).fail( function( xhr ) {
        console.log( xhr.responseText );
      });
    });
});