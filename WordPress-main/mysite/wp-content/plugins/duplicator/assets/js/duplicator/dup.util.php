<?php
/*! ============================================================================
*  UTIL NAMESPACE: All methods at the top of the Duplicator Namespace
*  =========================================================================== */
defined("ABSPATH") or die("");
use Duplicator\Libs\Snap\SnapJson;

?>

<script>
Duplicator.Util.ajaxProgress = null;

Duplicator.Util.ajaxProgressInit = function () {
    let ajaxProgress = jQuery('#duplicator-ajax-loader');
    if (ajaxProgress.length === 0) {
        let ajaxProgress = `
            <div id="duplicator-ajax-loader" >
                <div id="duplicator-ajax-loader-img-wrapper" >
                    <img 
                        src="<?php echo esc_url(DUPLICATOR_PLUGIN_URL . '/assets/img/duplicator-logo-icon.svg'); ?>" 
                        alt="<?php _e('wait ...', 'duplicator'); ?>"
                    >
                </div>
            </div>
        `;
        jQuery('body').append(ajaxProgress);
    }

    Duplicator.Util.ajaxProgress = jQuery('#duplicator-ajax-loader');
}

Duplicator.Util.ajaxProgressShow = function () {
    if (Duplicator.Util.ajaxProgress === null) {
        Duplicator.Util.ajaxProgressInit();
    }

    Duplicator.Util.ajaxProgress
        .stop(true, true)
        .css('display', 'block')
        .delay(1000)
        .animate({
            opacity: 1
        }, 500);
}

Duplicator.Util.ajaxProgressHide = function () {
    if (Duplicator.Util.ajaxProgress === null) {
        return;
    }
    Duplicator.Util.ajaxProgress
        .stop(true, true)
        .delay(500)
        .animate({
            opacity: 0
        }, 300, function () {
            jQuery(this).css({
                'display': 'none'
            });
        });
}

Duplicator.Util.ajaxWrapper = function (ajaxData, callbackSuccess, callbackFail) {
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        dataType: "json",
        data: ajaxData,
        beforeSend: function( xhr ) {
            Duplicator.Util.ajaxProgressShow();
        },
        success: function (result, textStatus, jqXHR) {
            var message = '';
            if (result.success) {
                if (typeof callbackSuccess === "function") {
                    try {
                        message = callbackSuccess(result, result.data, result.data.funcData, textStatus, jqXHR);
                    } catch (error) {
                        console.error(error);
                        Duplicator.addAdminMessage(error.message, 'error');
                        message = '';
                    }
                } else {
                    message = '<?php _e('RESPONSE SUCCESS', 'duplicator'); ?>';
                }
                if (message != null && String(message).length) {
                    Duplicator.addAdminMessage(message, 'notice');
                }
            } else {
                if (typeof callbackFail === "function") {
                    try {
                        message = callbackFail(result, result.data, result.data.funcData, textStatus, jqXHR);
                    } catch (error) {
                        console.error(error);
                        message = error.message;
                    }
                } else {
                    message = '<?php _e('RESPONSE ERROR!', 'duplicator'); ?>' + '<br><br>' + result.data.message;
                }
                if (message != null && String(message).length) {
                    Duplicator.addAdminMessage(message, 'error');
                }
            }
        },
        error: function (result) {
            Duplicator.addAdminMessage(<?php
                echo SnapJson::jsonEncode(__('AJAX ERROR! ', 'duplicator') . '<br>' . __('Ajax request error', 'duplicator'));
            ?>, 'error');
        },
        complete: function () {
            Duplicator.Util.ajaxProgressHide();
        }
    });
};

/**
 * Get human size from bytes number.
 * Is size is -1 return unknown
 *
 * @param {size} int bytes size
 */
Duplicator.Util.humanFileSize = function(size) {
    if (size < 0) {
        return "unknown";
    }
    else if (size == 0) {
        return "0";
    } else {
        var i = Math.floor(Math.log(size) / Math.log(1024));
        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }
};

Duplicator.Util.isEmpty = function (val) {
    return (val === undefined || val == null || val.length <= 0) ? true : false;
};


</script>
