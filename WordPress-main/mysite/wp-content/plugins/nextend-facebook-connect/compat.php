<?php


class NextendSocialLoginCompatibility {

    public function __construct() {
        add_action('after_setup_theme', array(
            $this,
            'after_setup_theme'
        ), 11);

        add_action('wp_head', array(
            $this,
            'wplms_hide_duplicate_buttons'
        ), 10);

    }

    public function after_setup_theme() {
        global $pagenow;

        /** Compatibility fix for Socialize theme @SEE https://themeforest.net/item/socialize-multipurpose-buddypress-theme/12897637 */
        if (function_exists('ghostpool_login_redirect')) {
            if ('wp-login.php' === $pagenow && !empty($_GET['loginSocial'])) {
                /** If the action not removed, then the wp-login.php always redirected to {siteurl}/#login/ and it break social login */
                remove_action('init', 'ghostpool_login_redirect');
            }
        }
    }

    public function wplms_hide_duplicate_buttons() {
        if (class_exists('vibe_bp_login', false)) {
            echo "<style>
                /**
                    WPLMS triggers the same hook twice in the same form -> Hide duplicated social buttons.
                 */
                div#vibe_bp_login div#nsl-custom-login-form-2{
                    display:none;
                }
            </style>
        ";
        }
    }
}

new NextendSocialLoginCompatibility();
