<?php

use NSL\Notices;
use NSL\Persistent\Persistent;

require_once(NSL_PATH . '/includes/exceptions.php');

require_once dirname(__FILE__) . '/NSL/Persistent/Persistent.php';
require_once dirname(__FILE__) . '/NSL/Notices.php';
require_once dirname(__FILE__) . '/NSL/REST.php';

require_once dirname(__FILE__) . '/NSL/GDPR.php';

require_once(NSL_PATH . '/class-settings.php');
require_once(NSL_PATH . '/includes/provider-oauth.php');
require_once(NSL_PATH . '/includes/provider-openid.php');
require_once(NSL_PATH . '/admin/admin.php');

require_once(NSL_PATH . '/compat.php');

class NextendSocialLogin {

    public static $version = '3.1.13';

    public static $nslPROMinVersion = '3.1.13';

    public static $proxyPage = false;

    public static function getRequiredCapability() {
        return apply_filters('nsl_required_capability', 'manage_options');
    }

    public static function checkVersion() {

        if (version_compare(self::$version, NextendSocialLoginPRO::$nslMinVersion, '<')) {
            if (did_action('init')) {
                NextendSocialLogin::noticeUpdateFree();
            } else {
                add_action('init', 'NextendSocialLogin::noticeUpdateFree');
            }

            return false;
        }
        if (version_compare(NextendSocialLoginPRO::$version, self::$nslPROMinVersion, '<')) {
            if (did_action('init')) {
                NextendSocialLogin::noticeUpdatePro();
            } else {
                add_action('init', 'NextendSocialLogin::noticeUpdatePro');
            }

            return false;
        }

        return true;
    }


    public static function noticeUpdateFree() {
        $showNotice = true;
        if (isset($_GET['page']) && $_GET['page'] === 'nextend-social-login' && isset($_GET['view']) && $_GET['view'] === 'pro-addon') {
            $showNotice = false;
        }

        if (is_admin() && current_user_can(NextendSocialLogin::getRequiredCapability()) && $showNotice) {
            $file = 'nextend-facebook-connect/nextend-facebook-connect.php';
            Notices::addError(sprintf(__('Please update %1$s to version %2$s or newer.', 'nextend-facebook-connect'), "Nextend Social Login", NextendSocialLoginPRO::$nslMinVersion) . ' <a href="' . esc_url(wp_nonce_url(admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file)) . '">' . __('Update now!', 'nextend-facebook-connect') . '</a>');
        }
    }

    public static function noticeUpdatePro() {
        $showNotice = true;
        if (isset($_GET['page']) && $_GET['page'] === 'nextend-social-login' && isset($_GET['view']) && $_GET['view'] === 'pro-addon') {
            $showNotice = false;
        }

        if (is_admin() && current_user_can(NextendSocialLogin::getRequiredCapability()) && $showNotice) {
            $file = 'nextend-social-login-pro/nextend-social-login-pro.php';
            Notices::addError(sprintf(__('Please update %1$s to version %2$s or newer.', 'nextend-facebook-connect'), "Nextend Social Login Pro Addon", self::$nslPROMinVersion) . ' <a href="' . esc_url(wp_nonce_url(admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file)) . '">' . __('Update now!', 'nextend-facebook-connect') . '</a>');
        }
    }

    /** @var NextendSocialLoginSettings */
    public static $settings;

    private static $styles = array(
        'fullwidth' => array(
            'container' => 'nsl-container-block-fullwidth',
            'align'     => array()
        ),
        'default'   => array(
            'container' => 'nsl-container-block',
            'align'     => array(
                'left',
                'right',
                'center',
            )
        ),
        'icon'      => array(
            'container' => 'nsl-container-inline',
            'align'     => array(
                'left',
                'right',
                'center',
            )
        ),
        'grid'      => array(
            'container' => 'nsl-container-grid',
            'align'     => array(
                'left',
                'right',
                'center',
                'space-around',
                'space-between',
            )
        )
    );

    public static $providersPath;

    /**
     * @var NextendSocialProviderDummy[]
     */
    public static $providers = array();

    /**
     * @var NextendSocialProvider[]
     */
    public static $allowedProviders = array();

    /**
     * @var NextendSocialProvider[]
     */
    public static $enabledProviders = array();

    private static $ordering = array();

    private static $loginHeadAdded = false;
    private static $loginMainButtonsAdded = false;
    public static $counter = 1;

    public static $WPLoginCurrentView = '';

    public static $WPLoginCurrentFlow = 'login';

    private static $allowedPostStates = array(
        'classic-editor-plugin',
        'elementor'
    );

    public static function init() {
        add_action('plugins_loaded', 'NextendSocialLogin::plugins_loaded');
        register_activation_hook(NSL_PATH_FILE, 'NextendSocialLogin::install');

        add_action('delete_user', 'NextendSocialLogin::delete_user');

        add_action('itsec_initialized', 'NextendSocialLogin::disable_better_wp_security_block_long_urls', -1);
    }

    public static function plugins_loaded() {

        self::$settings = new NextendSocialLoginSettings('nextend_social_login', array(
            'enabled'                          => array(),
            'register-flow-page'               => '',
            'proxy-page'                       => '',
            'ordering'                         => array(
                'facebook',
                'google',
                'twitter'
            ),
            'licenses'                         => array(),
            'terms_show'                       => 0,
            'terms'                            => __('By clicking Register, you accept our <a href="#privacy_policy_url" target="_blank">Privacy Policy</a>', 'nextend-facebook-connect'),
            'store_name'                       => 1,
            'store_email'                      => 1,
            'avatar_store'                     => 1,
            'store_access_token'               => 0,
            'redirect_prevent_external'        => 0,
            'redirect'                         => '',
            'redirect_reg'                     => '',
            'default_redirect'                 => '',
            'default_redirect_reg'             => '',
            'blacklisted_urls'                 => '',
            'redirect_overlay'                 => 'overlay-with-spinner-and-message',
            'unsupported_webview_behavior'     => '',
            'target'                           => 'prefer-popup',
            'allow_register'                   => -1,
            'allow_unlink'                     => 1,
            'show_login_form'                  => 'show',
            'login_form_button_align'          => 'left',
            'show_registration_form'           => 'show',
            'login_form_button_style'          => 'default',
            'login_form_layout'                => 'below',
            'show_embedded_login_form'         => 'show',
            'embedded_login_form_button_align' => 'left',
            'embedded_login_form_button_style' => 'default',
            'embedded_login_form_layout'       => 'below',

            'custom_actions'               => '',
            'custom_actions_button_style'  => 'default',
            'custom_actions_button_layout' => 'default',
            'custom_actions_button_align'  => 'left',

            'comment_login_button' => 'show',
            'comment_button_align' => 'left',
            'comment_button_style' => 'default',

            'buddypress_register_button'          => 'bp_before_account_details_fields',
            'buddypress_register_button_align'    => 'left',
            'buddypress_register_button_style'    => 'default',
            'buddypress_register_form_layout'     => 'default',
            'buddypress_login'                    => 'show',
            'buddypress_login_form_layout'        => 'default',
            'buddypress_login_button_style'       => 'default',
            'buddypress_sidebar_login'            => 'show',
            'buddypress_social_accounts_tab'      => 'show',
            'buddypress_registration_integration' => '0',

            'woocommerce_login'                => 'after',
            'woocommerce_login_form_layout'    => 'default',
            'woocommerce_register'             => 'after',
            'woocommerce_register_form_layout' => 'default',
            'woocommerce_billing'              => 'before',
            'woocommerce_billing_form_layout'  => 'default',
            'woocoommerce_form_button_style'   => 'default',
            'woocoommerce_form_button_align'   => 'left',
            'woocommerce_account_details'      => 'before',

            'memberpress_login'                        => 'before',
            'memberpress_form_button_align'            => 'left',
            'memberpress_login_form_button_style'      => 'default',
            'memberpress_login_form_layout'            => 'below-separator',
            'memberpress_signup'                       => 'before',
            'memberpress_signup_form_button_style'     => 'default',
            'memberpress_signup_form_layout'           => 'below-separator',
            'memberpress_account_details'              => 'after',
            'registration_notification_notify'         => '0',
            'debug'                                    => '0',
            'bypass_cache'                             => '1',
            'show_linked_providers'                    => '0',
            'login_restriction'                        => '0',
            'avatars_in_all_media'                     => '0',
            'custom_register_label'                    => '0',
            'review_state'                             => -1,
            'woocommerce_dismissed'                    => 0,
            'woocoommerce_registration_email_template' => 'woocommerce',

            'userpro_show_login_form'            => 'show',
            'userpro_show_register_form'         => 'show',
            'userpro_login_form_button_style'    => 'default',
            'userpro_login_form_layout'          => 'below',
            'userpro_register_form_button_style' => 'default',
            'userpro_register_form_layout'       => 'below',
            'userpro_form_button_align'          => 'left',

            'ultimatemember_login'                      => 'after',
            'ultimatemember_login_form_button_style'    => 'default',
            'ultimatemember_login_form_layout'          => 'below-separator',
            'ultimatemember_register'                   => 'after',
            'ultimatemember_register_form_button_style' => 'default',
            'ultimatemember_register_form_layout'       => 'below-separator',
            'ultimatemember_account_details'            => 'after',
            'ultimatemember_form_button_align'          => 'left',

            'edd_login'                      => 'after',
            'edd_login_form_button_style'    => 'default',
            'edd_login_form_layout'          => 'default',
            'edd_register'                   => 'after',
            'edd_register_form_button_style' => 'default',
            'edd_register_form_layout'       => 'default',
            'edd_checkout'                   => 'form_after',
            'edd_checkout_form_button_style' => 'default',
            'edd_checkout_form_layout'       => 'default',
            'edd_form_button_align'          => 'left',

            'admin_bar_roles' => array(),
        ));

        NextendSocialLoginAdmin::init();
        NextendSocialUpgrader::init();

        $lastVersion = get_option('nsl-version');
        if ($lastVersion != self::$version) {
            NextendSocialLogin::install();

            if (empty($lastVersion) || version_compare($lastVersion, '3.0.14', '<=')) {
                $old_license_status = NextendSocialLogin::$settings->get('license_key_ok');

                if ($old_license_status) {
                    $domain = NextendSocialLogin::$settings->get('authorized_domain');
                    if (empty($domain)) {
                        $domain = self::getDomain();
                    }
                    NextendSocialLogin::$settings->set('licenses', array(
                        array(
                            'license_key' => NextendSocialLogin::$settings->get('license_key'),
                            'domain'      => $domain
                        )
                    ));
                }
            }

            update_option('nsl-version', self::$version, true);
            if (!empty($_SERVER['HTTP_HOST'])) {
                wp_redirect(set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
            } else {
                wp_redirect(admin_url());
            }
            exit;
        } else if (isset($_REQUEST['repairnsl']) && current_user_can(NextendSocialLogin::getRequiredCapability()) && check_admin_referer('repairnsl')) {
            self::install();
            wp_redirect(admin_url('admin.php?page=nextend-social-login'));
            exit;
        }

        do_action('nsl_start');

        load_plugin_textdomain('nextend-facebook-connect', false, basename(dirname(__FILE__)) . '/languages/');

        Notices::init();

        self::$providersPath = NSL_PATH . '/providers/';

        $providers = array_diff(scandir(self::$providersPath), array(
            '..',
            '.'
        ));

        foreach ($providers as $provider) {
            if (file_exists(self::$providersPath . $provider . '/' . $provider . '.php')) {
                require_once(self::$providersPath . $provider . '/' . $provider . '.php');
            }
        }

        do_action('nsl_add_providers');

        self::$ordering = array_flip(self::$settings->get('ordering'));
        uksort(self::$providers, 'NextendSocialLogin::sortProviders');
        uksort(self::$allowedProviders, 'NextendSocialLogin::sortProviders');
        uksort(self::$enabledProviders, 'NextendSocialLogin::sortProviders');

        do_action('nsl_providers_loaded');

        if (NextendSocialLogin::$settings->get('allow_register') != 1) {
            add_filter('nsl_is_register_allowed', 'NextendSocialLogin::is_register_allowed');
        }

        add_action('login_form_login', 'NextendSocialLogin::login_form_login');

        /**
         * We need smaller priority, as some plugins like Ultimate Member may trigger a redirect before us.
         */
        add_action('login_form_register', 'NextendSocialLogin::login_form_register', 9);
        add_action('login_form_link', 'NextendSocialLogin::login_form_link');
        add_action('bp_core_screen_signup', 'NextendSocialLogin::bp_login_form_register');

        add_action('login_form_unlink', 'NextendSocialLogin::login_form_unlink');


        add_action('template_redirect', 'NextendSocialLogin::alternate_login_page_template_redirect');

        add_action('parse_request', 'NextendSocialLogin::editProfileRedirect');

        //check if DOM is ready
        add_action('wp_print_scripts', 'NextendSocialLogin::nslDOMReady');

        if (count(self::$enabledProviders) > 0) {

            if (self::$settings->get('show_login_form') == 'hide') {
                add_action('login_form_login', 'NextendSocialLogin::removeLoginFormAssets');
            } else {
                add_action('login_form', 'NextendSocialLogin::addLoginFormButtons');
            }

            if (NextendSocialLogin::$settings->get('show_registration_form') == 'hide') {
                add_action('login_form_register', 'NextendSocialLogin::removeLoginFormAssets');
            } else {
                add_action('register_form', 'NextendSocialLogin::addRegisterFormButtons');
            }

            if (NextendSocialLogin::$settings->get('show_embedded_login_form') != 'hide') {
                add_filter('login_form_bottom', 'NextendSocialLogin::filterAddEmbeddedLoginFormButtons');
            }

            //some themes trigger both the bp_sidebar_login_form action and the login_form action.
            switch (NextendSocialLogin::$settings->get('buddypress_sidebar_login')) {
                case 'show':
                    add_action('bp_sidebar_login_form', 'NextendSocialLogin::addLoginButtons');
                    break;
            }

            switch (NextendSocialLogin::$settings->get('buddypress_social_accounts_tab')) {
                case 'show':
                    add_action('bp_settings_setup_nav', 'NextendSocialLogin::bp_settings_setup_nav', 999999);
                    break;
            }

            add_action('profile_personal_options', 'NextendSocialLogin::addLinkAndUnlinkButtons');


            /*
             * Shopkeeper theme fix. Remove normal login form hooks while WooCommerce registration/login form rendering
             */
            add_action('woocommerce_login_form_start', 'NextendSocialLogin::remove_action_login_form_buttons');
            add_action('woocommerce_login_form_end', 'NextendSocialLogin::add_action_login_form_buttons');

            add_action('woocommerce_register_form_start', 'NextendSocialLogin::remove_action_login_form_buttons');
            add_action('woocommerce_register_form_end', 'NextendSocialLogin::add_action_login_form_buttons');
            /* End of fix */


            add_action('wp_head', 'NextendSocialLogin::styles', 100);

            /**
             * AMP integration:
             *
             * NSL_DISABLE_IN_AMP_REQUESTS constant can be used for disabling our social buttons in the AMP requests.
             * If the constant is defined, then we shouldn't force the loading of our styles either.
             */
            if (!defined('NSL_DISABLE_IN_AMP_REQUESTS')) {
                /**
                 * We need to call in our styles on the AMP pages using this action, since:
                 * -the "AMP" plugin does not call wp_head in Reader mode.
                 * -the "AMP for WP" plugin does not call wp_head in AMP view at all.
                 * -AMP plugins only allow adding custom CSS in the unique <style> tag with the attribute "amp-custom". Callbacks are only allowed to output bare CSS on this action.
                 */
                add_action('amp_post_template_css', 'NextendSocialLogin::stylesWithoutTag');
            } else {
                /**
                 * If the NSL_DISABLE_IN_AMP_REQUESTS constant is defined, then our social buttons won't be rendered, so we shouldn't load our assets either.
                 */
                add_action('wp', function () {
                    if (NextendSocialLogin::isAMPRequest()) {
                        NextendSocialLogin::removeFrontendAssets();
                    }
                });
            }


            add_action('admin_head', 'NextendSocialLogin::styles', 100);
            add_action('login_head', 'NextendSocialLogin::loginHead', 100);

            add_action('wp_print_footer_scripts', 'NextendSocialLogin::scripts', 100);
            add_action('login_footer', 'NextendSocialLogin::scripts', 100);

            require_once dirname(__FILE__) . '/includes/avatar.php';

            add_shortcode('nextend_social_login', 'NextendSocialLogin::shortcode');
        }

        add_action('admin_print_footer_scripts', 'NextendSocialLogin::scripts', 100);

        require_once(NSL_PATH . '/widget.php');

        do_action('nsl_init');

        /**
         * Fix for Hide my WP plugin @see https://codecanyon.net/item/hide-my-wp-amazing-security-plugin-for-wordpress/4177158
         */
        if (class_exists('HideMyWP', false)) {
            if (!empty($_REQUEST['loginSocial'])) {
                global $HideMyWP;
                $loginPath = '/wp-login.php';
                if (is_object($HideMyWP) && !empty($_SERVER['PHP_SELF']) && substr($_SERVER['PHP_SELF'], -1 * strlen($loginPath))) {
                    $login_query = $HideMyWP->opt('login_query');
                    if (!$login_query) {
                        $login_query = 'hide_my_wp';
                    }
                    $_GET[$login_query] = $HideMyWP->opt('admin_key');
                }
            }
        }

        if (!empty($_REQUEST['loginSocial'])) {

            // Fix for all-in-one-wp-security-and-firewall
            if (empty($_GET['action'])) {
                $_GET['action'] = 'nsl-login';
            }

            // Fix for wps-hide-login
            if (empty($_REQUEST['action'])) {
                $_REQUEST['action'] = 'nsl-login';
            }

            // Fix for Social Rabbit as it catch our code response from Facebook
            if (class_exists('\SR\Utils\Scheduled', true)) {
                add_action('init', 'NextendSocialLogin::fixSocialRabbit', 0);
            }

            // Fix for Dokan https://wedevs.com/dokan/
            if (function_exists('dokan_redirect_to_register')) {
                remove_action('login_init', 'dokan_redirect_to_register', 10);
            }

            // Fix for Jetpack SSO
            add_filter('jetpack_sso_bypass_login_forward_wpcom', '__return_false');

            /**
             * Fix: our autologin after the registration prevents WooRewards (MyRewards) and Ultimate Affiliate Pro plugins from awarding the points for the registration
             * so we need to make our autologin happen after they have already awarded the points. They use 999999 and 99 priority respectively.
             * @url https://plugins.longwatchstudio.com/product/woorewards/
             * @url2 https://affiliate.wpindeed.com/
             */
            if (class_exists('LWS_WooRewards') || class_exists('UAP_Main')) {
                add_filter('nsl_autologin_priority', function () {
                    return 10000000;
                });
            }

            /**
             * Fix: Jetpack Boost - Defer Non-Essential JavaScript opens an output buffer and stops our authentication with a blank page, when the OAuth redirect uri proxy page is used.
             *
             * @see NSLDEV-426
             */
            if (defined('JETPACK_BOOST_VERSION')) {
                add_filter('jetpack_boost_should_defer_js', '__return_false');
            }

            /*
             * Fix: WP 2FA
             * @url https://wordpress.org/plugins/wp-2fa/
             *
             * It breaks our login with an empty form when 2FA is enforced and the user doesn't have any 2FA method selected yet.
             * E.g. "Grace period" was set to "Users have to configure 2FA straight away."
             *
             * If our Support Login Restrictions feature is enabled and the user has any authentication method configured, then we should still allow the restriction.
             */
            if (defined('WP_2FA_VERSION')) {
                if (self::$settings->get('login_restriction')) {
                    add_filter('wp_2fa_skip_2fa_login_form', function ($skip, $user) {
                        if (class_exists('WP2FA\Admin\Helpers\User_Helper', false) && method_exists(WP2FA\Admin\Helpers\User_Helper::class, 'is_user_using_two_factor')) {
                            $is_user_using_two_factor = WP2FA\Admin\Helpers\User_Helper::is_user_using_two_factor($user->ID);
                            if ($is_user_using_two_factor) {
                                return $skip;
                            }
                        }

                        return true;
                    }, 10, 2);
                } else {
                    add_filter('wp_2fa_skip_2fa_login_form', '__return_true');
                }

            }

        }
    }

    public static function fixSocialRabbit() {
        remove_action('init', '\SR\Utils\Scheduled::init', 10);
    }

    public static function removeFrontendAssets() {
        remove_action('wp_head', 'NextendSocialLogin::styles', 100);
        remove_action('wp_print_footer_scripts', 'NextendSocialLogin::scripts', 100);
        remove_action('wp_print_scripts', 'NextendSocialLogin::nslDOMReady');
    }

    public static function removeLoginFormAssets() {
        remove_action('login_head', 'NextendSocialLogin::loginHead', 100);
        remove_action('wp_print_footer_scripts', 'NextendSocialLogin::scripts', 100);
        remove_action('login_footer', 'NextendSocialLogin::scripts', 100);
    }

    public static function styles() {

        $stylesheet = self::get_template_part('style.css');
        if (!empty($stylesheet) && file_exists($stylesheet)) {
            echo '<style type="text/css">' . file_get_contents($stylesheet) . '</style>';
        }

        self::noticeStyles();
    }

    public static function stylesWithoutTag() {

        $stylesheet = self::get_template_part('style.css');
        if (!empty($stylesheet) && file_exists($stylesheet)) {
            echo file_get_contents($stylesheet);
        }

        self::noticeStylesWithoutTag();
    }

    public static function noticeStyles() {
        static $noticeStylesOnce = null;
        if ($noticeStylesOnce === null) {
            $noticeStylesheet = self::get_template_part('notice-styles.css');
            if (!empty($noticeStylesheet) && file_exists($noticeStylesheet)) {
                echo '<style type="text/css">' . file_get_contents($noticeStylesheet) . '</style>';
            }

            $noticeStylesOnce = true;
        }
    }

    public static function noticeStylesWithoutTag() {
        static $noticeStylesOnce = null;
        if ($noticeStylesOnce === null) {
            $noticeStylesheet = self::get_template_part('notice-styles.css');
            if (!empty($noticeStylesheet) && file_exists($noticeStylesheet)) {
                echo file_get_contents($noticeStylesheet);
            }

            $noticeStylesOnce = true;
        }
    }

    public static function nslDOMReady() {
        echo '<script type="text/javascript">
            window._nslDOMReady = function (callback) {
                if ( document.readyState === "complete" || document.readyState === "interactive" ) {
                    callback();
                } else {
                    document.addEventListener( "DOMContentLoaded", callback );
                }
            };
            </script>';
    }

    public static function loginHead() {
        self::styles();

        self::$loginHeadAdded = true;
    }

    public static function scripts() {
        static $once = null;
        if ($once === null) {
            $scripts = NSL_PATH . '/js/nsl.js';
            if (file_exists($scripts)) {
                $localizedStrings = array(
                    'redirect_overlay_title'    => __('Hold On', 'nextend-facebook-connect'),
                    'redirect_overlay_text'     => __('You are being redirected to another page,<br>it may take a few seconds.', 'nextend-facebook-connect'),
                    'webview_notification_text' => __('The selected provider doesn\'t support embedded browsers!', 'nextend-facebook-connect'),
                );

                $scriptOptions = array(
                    '_localizedStrings'           => $localizedStrings,
                    '_targetWindow'               => self::$settings->get('target'),
                    '_redirectOverlay'            => self::$settings->get('redirect_overlay'),
                    '_unsupportedWebviewBehavior' => self::$settings->get('unsupported_webview_behavior'),
                );

                echo '<script type="text/javascript">(function (undefined) {let scriptOptions=' . wp_json_encode($scriptOptions) . ";\n" . file_get_contents($scripts) . '})();</script>';
            }
            $once = true;
        }
    }

    public static function install() {
        /** @var $wpdb WPDB */ global $wpdb;
        $table_name      = $wpdb->prefix . "social_users";
        $charset_collate = $wpdb->get_charset_collate();

        $lastVersion = get_option('nsl-version');

        /*
         * We should run these codes only if our database table already exists.
         */
        if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") === $table_name) {
            /**
             * In 3.0.27 we added a new column to the social_users table as autoincrement and primary key.
             * This causes an SQL error for the dbDelta() function so we need to add it beforehand.
             */
            if (version_compare($lastVersion, '3.0.26', '<=')) {
                $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . $table_name . "' AND COLUMN_NAME = 'social_users_id';");
                if (!$row) {
                    $alterQuery = "ALTER TABLE " . $table_name . " ADD `social_users_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY;";
                    $wpdb->query($alterQuery);
                }
            }

            if (version_compare($lastVersion, '3.0.27', '<=')) {
                /*
                 * In version 3.0.21 we started storing the register_date, login_date and link_date with '0000-00-00 00:00:00' as default value.
                 * That value returned an invalid value error on databases where 'sql_mode' has 'NO_ZERO_DATE, NO_ZERO_IN_DATE' modes, so it prevented us from modifying our database structure.
                 */
                $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . $table_name . "' AND COLUMN_NAME = 'register_date';");
                if (!empty($row)) {
                    $alterQuery = "ALTER TABLE " . $table_name . " CHANGE `register_date` `register_date` datetime DEFAULT NULL, CHANGE `login_date` `login_date` datetime DEFAULT NULL, CHANGE `link_date` `link_date` datetime DEFAULT NULL;";
                    $result     = $wpdb->query($alterQuery);

                    if ($result) {
                        $wpdb->update($table_name, array('register_date' => NULL,), array(
                            'register_date' => '0000-00-00 00:00:00'
                        ));
                        $wpdb->update($table_name, array('login_date' => NULL,), array(
                            'login_date' => '0000-00-00 00:00:00'
                        ));
                        $wpdb->update($table_name, array('link_date' => NULL,), array(
                            'link_date' => '0000-00-00 00:00:00'
                        ));
                    }
                }
            }
        }


        $sql = "CREATE TABLE " . $table_name . " (
        `social_users_id` int NOT NULL AUTO_INCREMENT,
        `ID` int NOT NULL,
        `type` varchar(20) NOT NULL,
        `identifier` varchar(100) NOT NULL,
        `register_date` datetime default NULL,
        `login_date` datetime default NULL,
        `link_date` datetime default NULL,
        PRIMARY KEY  (social_users_id),
        KEY `ID` (`ID`,`type`),
        KEY `identifier` (`identifier`)
        ) " . $charset_collate . ";";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function sortProviders($a, $b) {
        if (isset(self::$ordering[$a]) && isset(self::$ordering[$b])) {
            if (self::$ordering[$a] < self::$ordering[$b]) {
                return -1;
            }

            return 1;
        }
        if (isset(self::$ordering[$a])) {
            return -1;
        }

        return 1;
    }

    /**
     * @param $provider NextendSocialProviderDummy
     */
    public static function addProvider($provider) {
        if (in_array($provider->getId(), self::$settings->get('enabled'))) {
            if ($provider->isTested() && $provider->enable()) {
                self::$enabledProviders[$provider->getId()] = $provider;
            }
        }
        self::$providers[$provider->getId()] = $provider;

        if ($provider instanceof NextendSocialProvider) {
            self::$allowedProviders[$provider->getId()] = $provider;
        }
    }

    public static function enableProvider($providerID) {
        if (isset(self::$providers[$providerID])) {
            $enabled   = self::$settings->get('enabled');
            $enabled[] = self::$providers[$providerID]->getId();
            $enabled   = array_unique($enabled);

            self::$settings->update(array(
                'enabled' => $enabled
            ));
        }
    }

    public static function disableProvider($providerID) {
        if (isset(self::$providers[$providerID])) {

            $enabled = array_diff(self::$settings->get('enabled'), array(self::$providers[$providerID]->getId()));

            self::$settings->update(array(
                'enabled' => $enabled
            ));
        }
    }

    public static function isProviderEnabled($providerID) {
        return isset(self::$enabledProviders[$providerID]);
    }

    public static function alternate_login_page_template_redirect() {

        $isAlternatePage = ((self::getProxyPage() !== false && (is_page(self::getProxyPage()) || get_permalink() === get_permalink(self::getProxyPage()))) || (self::getRegisterFlowPage() !== false && (is_page(self::getRegisterFlowPage()) || get_permalink() === get_permalink(self::getRegisterFlowPage()))));
        if ($isAlternatePage) {
            nocache_headers();

            if (!empty($_REQUEST['loginSocial']) || (isset($_GET['interim_login']) && $_GET['interim_login'] === 'nsl')) {

                $action = isset($_GET['action']) ? $_GET['action'] : 'login';
                if (!in_array($action, array(
                    'login',
                    'register',
                    'link',
                    'unlink'
                ))) {
                    $action = 'login';
                }
                switch ($action) {
                    case 'login':
                        NextendSocialLogin::login_form_login();
                        break;
                    case 'register':
                        NextendSocialLogin::login_form_register();
                        break;
                    case 'link':
                        NextendSocialLogin::login_form_link();
                        break;
                    case 'unlink':
                        NextendSocialLogin::login_form_unlink();
                        break;
                }
            } else {
                if (!is_front_page() && !is_home()) {
                    if (Notices::hasErrors()) {
                        wp_redirect(NextendSocialLogin::enableNoticeForUrl(home_url()));
                        exit;
                    }

                    wp_redirect(home_url());
                    exit;
                }
            }
        }
    }

    public static function login_form_login() {
        self::$WPLoginCurrentView = 'login';
        self::login_init();
    }

    public static function login_form_register() {
        self::$WPLoginCurrentView = 'register';
        self::login_init();
    }

    public static function bp_login_form_register() {
        self::$WPLoginCurrentView = 'register-bp';
        self::login_init();
    }

    public static function login_form_link() {
        self::$WPLoginCurrentView = 'link';
        self::login_init();
    }

    public static function login_form_unlink() {
        self::$WPLoginCurrentView = 'unlink';
        self::login_init();
    }

    public static function login_init() {

        add_filter('wp_login_errors', 'NextendSocialLogin::wp_login_errors');

        if (isset($_GET['interim_login']) && $_GET['interim_login'] === 'nsl' && is_user_logged_in()) {
            self::onInterimLoginSuccess();

        }

        if (isset($_REQUEST['loginFacebook']) && $_REQUEST['loginFacebook'] == '1') {
            $_REQUEST['loginSocial'] = 'facebook';
        }
        if (isset($_REQUEST['loginGoogle']) && $_REQUEST['loginGoogle'] == '1') {
            $_REQUEST['loginSocial'] = 'google';
        }
        if (isset($_REQUEST['loginTwitter']) && $_REQUEST['loginTwitter'] == '1') {
            $_REQUEST['loginTwitter'] = 'twitter';
        }

        if (isset($_REQUEST['loginSocial']) && is_string($_REQUEST['loginSocial']) && isset(self::$providers[$_REQUEST['loginSocial']]) && (self::$providers[$_REQUEST['loginSocial']]->isEnabled() || self::$providers[$_REQUEST['loginSocial']]->isTest())) {

            nocache_headers();

            self::$providers[$_REQUEST['loginSocial']]->connect();
        }

    }

    private static function onInterimLoginSuccess() {
        require_once(NSL_PATH . '/admin/interim.php');
    }

    public static function wp_login_errors($errors) {

        if (empty($errors)) {
            $errors = new WP_Error();
        }

        $errorMessages = Notices::getErrors();
        if ($errorMessages !== false) {
            foreach ($errorMessages as $errorMessage) {
                $errors->add('error', $errorMessage);
            }
        }

        return $errors;
    }

    public static function editProfileRedirect() {
        global $wp;

        if (isset($wp->query_vars['editProfileRedirect'])) {
            if (function_exists('bp_loggedin_user_domain')) {
                header('LOCATION: ' . bp_loggedin_user_domain() . 'profile/edit/group/1/');
            } else {
                header('LOCATION: ' . self_admin_url('profile.php'));
            }
            exit;
        }
    }

    public static function filterAddEmbeddedLoginFormButtons($ret) {

        return $ret . self::getEmbeddedLoginForm();
    }

    private static function getEmbeddedLoginForm($labelType = 'login') {
        ob_start();
        self::styles();

        $index = self::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . self::renderButtonsWithContainer(self::$settings->get('embedded_login_form_button_style'), false, false, false, self::$settings->get('embedded_login_form_button_align'), $labelType) . '</div>';

        $template = self::get_template_part('embedded-login/' . sanitize_file_name(self::$settings->get('embedded_login_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }

        return ob_get_clean();
    }

    public static function addLoginFormButtons() {
        echo self::getRenderedLoginButtons();
    }

    public static function addLoginButtons() {
        echo self::getRenderedLoginButtons();
    }

    public static function addRegisterFormButtons() {
        echo self::getRenderedLoginButtons('register');
    }

    public static function remove_action_login_form_buttons() {
        remove_action('login_form', 'NextendSocialLogin::addLoginFormButtons');
        remove_action('register_form', 'NextendSocialLogin::addRegisterFormButtons');
    }

    public static function add_action_login_form_buttons() {
        add_action('login_form', 'NextendSocialLogin::addLoginFormButtons');
        add_action('register_form', 'NextendSocialLogin::addRegisterFormButtons');
    }

    private static function getRenderedLoginButtons($labelType = 'login') {
        if (!self::$loginHeadAdded || self::$loginMainButtonsAdded) {

            return self::getEmbeddedLoginForm($labelType);
        }

        self::$loginMainButtonsAdded = true;
        ob_start();

        $ret = '<div id="nsl-custom-login-form-main">';
        $ret .= self::renderButtonsWithContainer(self::$settings->get('login_form_button_style'), false, false, false, self::$settings->get('login_form_button_align'), $labelType);
        $ret .= '</div>';
        echo $ret;

        $template = self::get_template_part('login/' . sanitize_file_name(self::$settings->get('login_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }


        return ob_get_clean();
    }

    public static function addLinkAndUnlinkButtons() {
        echo self::renderLinkAndUnlinkButtons();
    }

    /**
     * @param bool|false|string $heading
     * @param bool              $link
     * @param bool              $unlink
     * @param string            $align
     * @param array|string      $providers
     * @param string            $style
     *
     * @return string
     */
    public static function renderLinkAndUnlinkButtons($heading = '', $link = true, $unlink = true, $align = "left", $providers = false, $style = "default") {
        if (count(self::$enabledProviders) && is_user_logged_in()) {

            /**
             * If the NSL_DISABLE_IN_AMP_REQUESTS constant is defined, we shouldn't display the link/unlink buttons on the AMP pages.
             */
            if (defined('NSL_DISABLE_IN_AMP_REQUESTS') && NextendSocialLogin::isAMPRequest()) {

                return '';
            }

            /**
             * We shouldn't allow the icon style for Link and Unlink buttons
             */
            if ($style === 'icon') {
                $style = 'default';
            }

            $buttons = '';
            if ($heading !== false) {
                if (empty($heading)) {
                    $heading = __('Social Login', 'nextend-facebook-connect');
                }
                $buttons = '<h2>' . $heading . '</h2>';
            }


            if ($unlink) {
                //Filter to disable unlinking social accounts
                $isUnlinkAllowed = apply_filters('nsl_allow_unlink', true);
                if (!$isUnlinkAllowed) {
                    $unlink = false;
                }
            }


            $enabledProviders = false;
            if (is_array($providers)) {
                $enabledProviders = array();
                foreach ($providers as $provider) {
                    if ($provider && isset(self::$enabledProviders[$provider->getId()])) {
                        $enabledProviders[$provider->getId()] = $provider;
                    }
                }
            }
            if ($enabledProviders === false) {
                $enabledProviders = self::$enabledProviders;
            }

            if (count($enabledProviders)) {
                $buttons = '';
                foreach ($enabledProviders as $provider) {
                    if ($provider->isCurrentUserConnected()) {
                        if ($unlink) {
                            $buttons .= $provider->getUnLinkButton();
                        }
                    } else {
                        if ($link) {
                            $buttons .= $provider->getLinkButton();
                        }
                    }
                }

                $buttons = '<div class="nsl-container-buttons">' . $buttons . '</div>';

                return '<div class="nsl-container ' . self::$styles[$style]['container'] . '"' . ($style !== 'fullwidth' ? ' data-align="' . esc_attr($align) . '"' : '') . '>' . $buttons . '</div>';

            }
        }

        return '';
    }

    /**
     * @param $user_id
     *
     * @return bool
     * @deprecated
     *
     */
    public static function getAvatar($user_id) {
        foreach (self::$enabledProviders as $provider) {
            $avatar = $provider->getAvatar($user_id);
            if ($avatar !== false) {
                return $avatar;
            }
        }

        return false;
    }

    public static function shortcode($atts) {
        if (!is_array($atts)) {
            $atts = array();
        }

        $atts = array_merge(array(
            'style'    => 'default',
            'provider' => false,
            'login'    => 1,
            'link'     => 0,
            'unlink'   => 0,
            'heading'  => false,
            'align'    => 'left'
        ), $atts);

        $providers  = false;
        $providerID = $atts['provider'] === false ? false : $atts['provider'];
        if ($providerID !== false && isset(self::$enabledProviders[$providerID])) {
            $providers = array(self::$enabledProviders[$providerID]);
        }

        if (!is_user_logged_in()) {

            if (filter_var($atts['login'], FILTER_VALIDATE_BOOLEAN) === false) {
                return '';
            }

            $atts = array_merge(array(
                'redirect'    => false,
                'trackerdata' => false,
                'labeltype'   => 'login',
                'customlabel' => false
            ), $atts);

            return self::renderButtonsWithContainerAndTitle($atts['heading'], $atts['style'], $providers, $atts['redirect'], $atts['trackerdata'], $atts['align'], $atts['labeltype'], $atts['customlabel']);
        }

        $link   = filter_var($atts['link'], FILTER_VALIDATE_BOOLEAN);
        $unlink = filter_var($atts['unlink'], FILTER_VALIDATE_BOOLEAN);

        if ($link || $unlink) {
            return self::renderLinkAndUnlinkButtons($atts['heading'], $link, $unlink, $atts['align'], $providers, $atts['style']);
        }

        return '';
    }

    /**
     * @param string                       $style
     * @param bool|NextendSocialProvider[] $providers
     * @param bool|string                  $redirect_to
     * @param bool                         $trackerData
     * @param string                       $align
     * @param string                       $labelType
     *
     * @return string
     */
    public static function renderButtonsWithContainer($style = 'default', $providers = false, $redirect_to = false, $trackerData = false, $align = 'left', $labelType = 'login') {
        return self::renderButtonsWithContainerAndTitle(false, $style, $providers, $redirect_to, $trackerData, $align, $labelType);
    }

    private static function renderButtonsWithContainerAndTitle($heading = false, $style = 'default', $providers = false, $redirect_to = false, $trackerData = false, $align = 'left', $labelType = 'login', $customLabel = false) {
        if (!is_user_logged_in()) {

            /**
             * If the NSL_DISABLE_IN_AMP_REQUESTS constant is defined, we shouldn't display the social buttons in the AMP requests.
             */
            if (defined('NSL_DISABLE_IN_AMP_REQUESTS') && NextendSocialLogin::isAMPRequest()) {
                return '';
            }

            if (!isset(self::$styles[$style])) {
                $style = 'default';
            }

            if (!in_array($align, self::$styles[$style]['align'])) {
                $align = 'left';
            }


            $enabledProviders = false;
            if (is_array($providers)) {
                $enabledProviders = array();
                foreach ($providers as $provider) {
                    if ($provider && isset(self::$enabledProviders[$provider->getId()])) {
                        $enabledProviders[$provider->getId()] = $provider;
                    }
                }
            }
            if ($enabledProviders === false) {
                $enabledProviders = self::$enabledProviders;
            }

            if (count($enabledProviders)) {
                $buttons = '';
                foreach ($enabledProviders as $provider) {
                    $buttons .= $provider->getConnectButton($style, $redirect_to, $trackerData, $labelType, $customLabel);
                }

                if (!empty($heading)) {
                    $heading = '<h2>' . $heading . '</h2>';
                } else {
                    $heading = '';
                }

                $buttons = '<div class="nsl-container-buttons">' . $buttons . '</div>';

                $ret = '<div class="nsl-container ' . self::$styles[$style]['container'] . '"' . ($style !== 'fullwidth' ? ' data-align="' . esc_attr($align) . '"' : '') . '>' . $heading . $buttons . '</div>';
                if (defined('DOING_AJAX') && DOING_AJAX) {
                    $id  = md5(uniqid('nsl-ajax-'));
                    $ret = '<div id="' . $id . '">' . $ret . '</div>';
                    if (!$redirect_to) {
                        $ret .= '<script>window._nslDOMReady(function(){var socialButtonContainer=document.getElementById("' . $id . '");if(socialButtonContainer){var socialButtons=socialButtonContainer.querySelectorAll("a");socialButtons.forEach(function(el,i){var href=el.getAttribute("href");if(href.indexOf("?")===-1){href+="?"}else{href+="&"}
el.setAttribute("href",href+"redirect="+encodeURIComponent(window.location.href))})}});</script>';
                    }
                }

                return $ret;
            }
        }

        return '';
    }


    public static function getCurrentPageURL() {

        if ((defined('DOING_AJAX') && DOING_AJAX) || empty($_SERVER['HTTP_HOST']) || empty($_SERVER['REQUEST_URI'])) {
            return false;
        }

        $currentUrl = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        if (!self::isAllowedRedirectUrl($currentUrl)) {
            return false;
        }

        return $currentUrl;
    }

    public static function getLoginUrl($scheme = 'login') {
        static $alternateLoginPage = null;
        if ($alternateLoginPage === null) {
            $proxyPage = self::getProxyPage();
            if ($proxyPage !== false) {
                $alternateLoginPage = get_permalink($proxyPage);
            }
            if (empty($alternateLoginPage)) {
                $alternateLoginPage = false;
            }
        }

        if ($alternateLoginPage !== false) {

            return $alternateLoginPage;
        }

        return site_url('wp-login.php', $scheme);
    }

    public static function getRegisterUrl() {

        return wp_registration_url();
    }

    public static function isAllowedRedirectUrl($url) {
        $loginUrl = self::getLoginUrl();

        // If the currentUrl is the loginUrl, then we should not return it for redirects
        if (strpos($url, $loginUrl) === 0) {
            return false;
        }

        $loginUrl2 = site_url('wp-login.php');

        // If the currentUrl is the loginUrl, then we should not return it for redirects
        if ($loginUrl2 !== $loginUrl && strpos($url, $loginUrl2) === 0) {
            return false;
        }

        $registerUrl = wp_registration_url();
        // If the currentUrl is the registerUrl, then we should not return it for redirects
        if (strpos($url, $registerUrl) === 0) {
            return false;
        }

        $blacklistedUrls = NextendSocialLogin::$settings->get('blacklisted_urls');
        if (!empty($blacklistedUrls)) {
            $blackListedUrlArray = preg_split('/\r\n|\r|\n/', $blacklistedUrls);
            // If the currentUrl is blacklisted, then we should not return it for redirects
            foreach ($blackListedUrlArray as $blackListedUrl) {
                //If the url contains the blackListedUrl returns false
                if (strpos($url, $blackListedUrl) !== false) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function get_template_part($file_name, $name = null) {
        // Execute code for this part
        do_action('get_template_part_' . $file_name, $file_name, $name);

        // Setup possible parts
        $templates   = array();
        $templates[] = $file_name;

        // Allow template parts to be filtered
        $templates = apply_filters('nsl_get_template_part', $templates, $file_name, $name);

        // Return the part that is found
        return self::locate_template($templates);
    }

    public static function locate_template($template_names) {
        // No file found yet
        $located = false;

        // Try to find a template file
        foreach ((array)$template_names as $template_name) {

            // Continue if template is empty
            if (empty($template_name)) {
                continue;
            }

            // Trim off any slashes from the template name
            $template_name = ltrim($template_name, '/');
            // Check child theme first
            if (file_exists(trailingslashit(get_stylesheet_directory()) . 'nsl/' . $template_name)) {
                $located = trailingslashit(get_stylesheet_directory()) . 'nsl/' . $template_name;
                break;

                // Check parent theme next
            } else if (file_exists(trailingslashit(get_template_directory()) . 'nsl/' . $template_name)) {
                $located = trailingslashit(get_template_directory()) . 'nsl/' . $template_name;
                break;

                // Check theme compatibility last
            } else if (file_exists(trailingslashit(self::get_templates_dir()) . $template_name)) {
                $located = trailingslashit(self::get_templates_dir()) . $template_name;
                break;
            } else if (defined('NSL_PRO_PATH') && file_exists(trailingslashit(NSL_PRO_PATH) . 'template-parts/' . $template_name)) {
                $located = trailingslashit(NSL_PRO_PATH) . 'template-parts/' . $template_name;
                break;
            }
        }

        return $located;
    }

    public static function get_templates_dir() {
        return NSL_PATH . '/template-parts';
    }

    public static function delete_user($user_id) {
        /** @var $wpdb WPDB */ global $wpdb, $blog_id;

        $wpdb->delete($wpdb->prefix . 'social_users', array(
            'ID' => $user_id
        ), array(
            '%d'
        ));

        $attachment_id = get_user_meta($user_id, $wpdb->get_blog_prefix($blog_id) . 'user_avatar', true);
        if (wp_attachment_is_image($attachment_id)) {
            wp_delete_attachment($attachment_id, true);
        }

    }

    public static function disable_better_wp_security_block_long_urls() {
        if (class_exists('ITSEC_System_Tweaks', false)) {
            remove_action('itsec_initialized', array(
                ITSEC_System_Tweaks::get_instance(),
                'block_long_urls'
            ));
        }
    }

    public static function bp_settings_setup_nav() {

        if (!bp_is_active('settings') || !bp_is_active('members')) {
            return;
        }

        // Determine user to use.
        if (bp_loggedin_user_domain()) {
            $user_domain = bp_loggedin_user_domain();
        } else {
            return;
        }

        // Get the settings slug.
        $settings_slug = bp_get_settings_slug();

        $subnav_slug = apply_filters('nsl_bp_social_accounts_tab_slug', 'social');
        if (buddypress()->members->nav->get($settings_slug . '/' . $subnav_slug)) {
            /**
             * If there is a sub-nav item with the used slug, then we should use "nsl-social" as a fallback.
             */
            $subnav_slug = 'nsl-social';
        }

        bp_core_new_subnav_item(array(
            'name'            => __('Social Accounts', 'nextend-facebook-connect'),
            'slug'            => $subnav_slug,
            'parent_url'      => trailingslashit($user_domain . $settings_slug),
            'parent_slug'     => $settings_slug,
            'screen_function' => 'NextendSocialLogin::bp_display_account_link',
            'position'        => 29,
            'user_has_access' => bp_core_can_edit_settings()
        ), 'members');

    }

    public static function bp_display_account_link() {

        add_action('bp_template_title', 'NextendSocialLogin::bp_template_title');
        add_action('bp_template_content', 'NextendSocialLogin::bp_template_content');
        bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
    }

    public static function bp_template_title() {
        _e('Social Login', 'nextend-facebook-connect');
    }

    public static function bp_template_content() {
        echo self::renderLinkAndUnlinkButtons(false, true, true, NextendSocialLogin::$settings->get('buddypress_register_button_align'), false, NextendSocialLogin::$settings->get('buddypress_login_button_style'));
    }

    public static function getTrackerData() {
        return Persistent::get('trackerdata');
    }

    public static function getDomain() {
        return preg_replace('/^www\./', '', parse_url(site_url(), PHP_URL_HOST));
    }

    public static function getRegisterFlowPage() {
        static $registerFlowPage = null;
        if ($registerFlowPage === null) {
            $registerFlowPage = intval(self::$settings->get('register-flow-page'));
            if (empty($registerFlowPage) || get_post($registerFlowPage) === null || get_post_status($registerFlowPage) !== 'publish') {
                $registerFlowPage = false;
            }
        }

        return $registerFlowPage;
    }

    public static function getProxyPage() {
        static $proxyPage = null;
        if ($proxyPage === null) {
            $proxyPage = intval(self::$settings->get('proxy-page'));
            if (empty($proxyPage) || get_post($proxyPage) === null || get_post_status($proxyPage) !== 'publish') {
                $proxyPage = false;
            }
        }

        return $proxyPage;
    }

    public static function getFreePagesForRegisterFlow($pages) {

        $availablePages = array();
        foreach ($pages as $page) {
            $post_states = array();
            $post_states = apply_filters('display_post_states', $post_states, $page);
            if (NextendSocialLogin::getRegisterFlowPage() === $page->ID || !$post_states || (count($post_states) === 1 && array_intersect(self::$allowedPostStates, array_keys($post_states)))) {
                $availablePages[] = $page;
            }
        }

        return $availablePages;
    }

    public static function getFreePagesForOauthProxyPage($pages) {

        $availablePages = array();
        foreach ($pages as $page) {
            $post_states = array();
            $post_states = apply_filters('display_post_states', $post_states, $page);
            if (NextendSocialLogin::getProxyPage() === $page->ID || !$post_states || (count($post_states) === 1 && array_intersect(self::$allowedPostStates, array_keys($post_states)))) {
                $availablePages[] = $page;
            }
        }

        return $availablePages;
    }

    public static function is_register_allowed($isAllowed) {
        $allow_register = NextendSocialLogin::$settings->get('allow_register');
        switch ($allow_register) {
            //WordPress default membership
            case -1:
                if (get_option('users_can_register')) {
                    return true;
                }
                break;
        }

        return false;
    }

    public static function hasLicense($strict = true) {
        return self::getLicense($strict) !== false;
    }

    public static function getLicense($strict = true) {
        $licenses            = NextendSocialLogin::$settings->get('licenses');
        $currentDomain       = '.' . NextendSocialLogin::getDomain();
        $currentDomainLength = strlen($currentDomain);

        for ($i = 0; $i < count($licenses); $i++) {
            $authorizedDomain       = '.' . preg_replace('/^www\./', '', $licenses[$i]['domain']);
            $authorizedDomainLength = strlen($authorizedDomain);

            if ($authorizedDomain === $currentDomain || strrpos($currentDomain, $authorizedDomain) === $currentDomainLength - $authorizedDomainLength) {
                return $licenses[$i];
            }

            if (strrpos($currentDomain, $authorizedDomain) === $currentDomainLength - $authorizedDomainLength) {
                return $licenses[$i];
            }

            if (strrpos($authorizedDomain, $currentDomain) === $authorizedDomainLength - $currentDomainLength) {
                return $licenses[$i];
            }
        }

        if (!$strict && !empty($licenses)) {
            return $licenses[0];
        }

        return false;
    }

    public static function hasConfigurationWithNoEnabledProviders() {
        if (count(NextendSocialLogin::$enabledProviders) === 0) {
            foreach (NextendSocialLogin::$providers as $provider) {
                $state = $provider->getState();
                // Has providers configured, but none of them are enabled
                if ($state === 'disabled') {
                    return true;
                }
            }
        }

        return false;
    }

    public static function enableNoticeForUrl($url) {
        return add_query_arg(array('nsl-notice' => 1), $url);
    }

    public static function getUserIDByIdOrEmail($id_or_email) {
        $id = 0;

        /**
         * Get the user id depending on the $id_or_email, it can be the user id, email and object.
         */
        if (is_numeric($id_or_email)) {
            $id = $id_or_email;
        } else if (is_string($id_or_email)) {
            $user = get_user_by('email', $id_or_email);
            if ($user) {
                $id = $user->ID;
            }
        } else if (is_object($id_or_email)) {
            if (!empty($id_or_email->comment_author_email)) {
                $user = get_user_by('email', $id_or_email->comment_author_email);
                if ($user) {
                    $id = $user->ID;
                }
            } else if (!empty($id_or_email->user_id)) {
                $id = $id_or_email->user_id;
            }
        }

        return $id;
    }

    /**
     * For logged in users, this function might add the 'nsl_bypass_cache' GET parameter to a URL with a unique value
     * to bypass the cache.
     *
     * @param $url
     *
     * @return string
     */
    public static function maybeAddBypassCacheArgToUrl($url) {
        if ($url && self::$settings->get('bypass_cache')) {
            if (is_user_logged_in()) {
                if (has_filter('nsl_bypass_cache_url')) {
                    $url = apply_filters('nsl_bypass_cache_url', $url);
                } else {
                    $url = add_query_arg(array('nsl_bypass_cache' => wp_hash(get_current_user_id() . current_time('timestamp'))), $url);
                }
            }
        }

        return $url;
    }

    public static function isLostPasswordRequest() {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

        return $action === 'lostpassword';
    }

    /**
     * @param String $providerID
     *
     * @return bool|NextendSocialProvider
     */
    public static function getProviderByProviderID($providerID) {
        if (NextendSocialLogin::isProviderEnabled($providerID)) {
            return NextendSocialLogin::$enabledProviders[$providerID];
        }

        return false;
    }

    /**
     * This action should be used only at places where the "wp" action has been already fired!
     *
     * @return bool
     */
    public static function isAMPRequest() {
        /**
         * Both the AMP and the AMP For WordPress plugins need to have access to the WP_Query object, so we need to run these checks after the object has been already set up.
         */
        if (did_action('wp')) {

            /*
             * AMP plugin
             */
            if (class_exists('AMP_Theme_Support') && function_exists('amp_is_request') && amp_is_request()) {
                return true;
            }

            /*
             * AMP for WP plugin
             */
            if ((defined('AMPFORWP_VERSION') && function_exists('ampforwp_is_amp_endpoint') && ampforwp_is_amp_endpoint()) || (function_exists('amp_activate') && function_exists('is_amp_endpoint') && is_amp_endpoint())) {
                return true;
            }

        }

        return false;
    }

}

NextendSocialLogin::init();
