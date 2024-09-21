<?php

namespace WpCallButton\Notices;

/**
 * The Review class adds a review prompt to the WordPress plugin.
 *
 * This class adds two actions to WordPress: `admin_notices` and `wp_ajax_wpcb_dismiss_review_prompt`.
 * The `admin_notices` action displays a notice in the WordPress admin area asking the user to review the plugin.
 * The `wp_ajax_wpcb_dismiss_review_prompt` action dismisses the review prompt when an AJAX request is made.
 *
 * @since 1.0.0
 */
class Review
{
    /**
     * This code adds two actions to WordPress:
     *
     * 1. admin_notices action that calls the review_notice method of the current object.
     * 2. wp_ajax_wpcb_dismiss_review_prompt action that calls the dismiss_review_prompt method of the current object when an Ajax request is made.
     *
     * @since 1.4.0
     */
    public function load_hooks()
    {
        add_action('admin_notices', array( $this, 'review_notice' ));
        add_action('wp_ajax_wpcb_dismiss_review_prompt', array( $this, 'dismiss_review_prompt' ));
    }

    /**
     * Dismisses the review prompt for the WordPress plugin.
     *
     * It is called when an Ajax request is made with the
     * wp_ajax_wpcb_dismiss_review_prompt action. This function updates the user
     * meta data to indicate that the review prompt has been dismissed and
     * returns a JSON response with a success message.
     *
     * @since 1.4.0
     */
    public function dismiss_review_prompt()
    {

        if (empty($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'wpcb_dismiss_review_prompt')) {
            die('Failed');
        }

        if (! empty($_POST['type'])) {
            if ('remove' === $_POST['type']) {
                update_option('wpcb_review_prompt_removed', true);
                wp_send_json_success(
                    array(
                        'status' => 'removed',
                    )
                );
            } elseif ('delay' === $_POST['type']) {
                update_option(
                    'wpcb_review_prompt_delay',
                    array(
                        'delayed_until' => time() + WEEK_IN_SECONDS,
                    )
                );
                wp_send_json_success(
                    array(
                        'status' => 'delayed',
                    )
                );
            }
        }
    }

    /**
     * Displays a notice in the WordPress admin area asking the user to review the plugin.
     *
     * This function is hooked to the `admin_notices` action and is called when the user visits the WordPress admin area.
     * It checks if the user has dismissed the review prompt before and if not, displays a notice asking the user to review the plugin.
     *
     * @since 1.4.0
     */
    public function review_notice()
    {

        // Only show to admins.
        if (! current_user_can('manage_options')) {
            return;
        }

        // Notice has been delayed.
        $delayed_option = get_option('wpcb_review_prompt_delay');
        if (! empty($delayed_option['delayed_until']) && time() < $delayed_option['delayed_until']) {
            return;
        }

        // Notice has been removed.
        if (get_option('wpcb_review_prompt_removed')) {
            return;
        }

        // Backwards compat.
        if (get_transient('wpcb_review_prompt_delay')) {
            return;
        }
        ?>
        <div class="notice notice-info is-dismissible wpcb-review-notice" id="wpcb_review_notice">
            <div id="wpcb_review_intro">
                <p><?php _e('Are you enjoying WP Call Button?', 'wp-call-button'); ?></p>
                <p><a data-review-selection="yes" class="wpcb-review-selection" href="#">Yes, I love it.</a> | <a data-review-selection="no" class="wpcb-review-selection" href="#">Not really.</a></p>
            </div>
            <div id="wpcb_review_yes" style="display: none;">
                <p><?php _e('Awesome! Could you please do me a big favor and give it a 5-star rating on WordPress to help us spread the word?', 'wp-call-button'); ?></p>
                <p style="font-weight: bold;">~ Syed Balkhi<br>Founder of WP Call Button</p>
                <p>
                <a style="display: inline-block; margin-right: 10px;" href="https://wordpress.org/support/plugin/wp-call-button/reviews/?filter=5" onclick="delayReviewPrompt(event, 'remove', true, true)" target="_blank"><?php esc_html_e('Okay, you deserve it', 'wp-call-button'); ?></a>
                <a style="display: inline-block; margin-right: 10px;" href="#" onclick="delayReviewPrompt(event, 'delay', true, false)"><?php esc_html_e('Nope, maybe later', 'wp-call-button'); ?></a>
                <a href="#" onclick="delayReviewPrompt(event, 'remove', true, false)"><?php esc_html_e('I already did', 'wp-call-button'); ?></a>
                </p>
            </div>
            <div id="wpcb_review_no" style="display: none;">
                <p>
                    <?php _e('We\'re sorry to hear you aren\'t enjoying WP Call Button. We would love a chance to improve. Could you take a minute and let us know what we can do better?', 'wp-call-button'); ?>
                </p>
                <p>
                    <a style="display: inline-block; margin-right: 10px;" href="https://wordpress.org/support/plugin/wp-call-button/reviews/" onclick="delayReviewPrompt(event, 'remove', true, true)" target="_blank"><?php esc_html_e('Give Feedback', 'wp-call-button'); ?></a>
                    <a href="#" onclick="delayReviewPrompt(event, 'remove', true, false)"><?php esc_html_e('No thanks', 'wp-call-button'); ?></a>
                </p>
            </div>
        </div>
    <script type="text/javascript">

      function delayReviewPrompt(event, type, triggerClick = true, openLink = false) {
        event.preventDefault();
        if ( triggerClick ) {
          jQuery('#wpcb_review_notice').fadeOut();
        }
        if ( openLink ) {
          var href = event.target.href;
          window.open(href, '_blank');
        }
        jQuery.ajax({
          url: ajaxurl,
          type: 'POST',
          data: {
            action: 'wpcb_dismiss_review_prompt',
            nonce: "<?php echo wp_create_nonce('wpcb_dismiss_review_prompt'); ?>",
            type: type
          },
        });
      }

      jQuery(document).ready(function($) {
        $('.wpcb-review-selection').click(function(event) {
          event.preventDefault();
          var $this = $(this);
          var selection = $this.data('review-selection');
          $('#wpcb_review_intro').hide();
          $('#wpcb_review_' + selection).show();
        });
        $('body').on('click', '#wpcb_review_notice .notice-dismiss', function(event) {
          delayReviewPrompt(event, 'delay', false);
        });
      });
    </script>
        <?php
    }
}
