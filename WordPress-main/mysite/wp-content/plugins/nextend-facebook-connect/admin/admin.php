<?php

use NSL\Notices;

define('NSL_ADMIN_PATH', __FILE__);

require_once dirname(__FILE__) . '/upgrader.php';

class NextendSocialLoginAdmin {

    public static function init() {
        add_action('admin_menu', 'NextendSocialLoginAdmin::admin_menu', 1);
        add_action('admin_init', 'NextendSocialLoginAdmin::admin_init');

        add_filter('plugin_action_links', 'NextendSocialLoginAdmin::plugin_action_links', 10, 2);

        add_filter('nsl_update_settings_validate_nextend_social_login', 'NextendSocialLoginAdmin::validateSettings', 10, 2);

        add_action('wp_ajax_nsl_save_review_state', 'NextendSocialLoginAdmin::save_review_state');
    }

    public static function getAdminUrl($view = 'providers') {

        return add_query_arg(array(
            'page' => 'nextend-social-login',
            'view' => $view
        ), admin_url('options-general.php'));
    }

    public static function getAdminSettingsUrl($subview = 'general') {

        return add_query_arg(array(
            'page'    => 'nextend-social-login',
            'view'    => 'global-settings',
            'subview' => $subview
        ), admin_url('options-general.php'));
    }

    public static function admin_menu() {
        $menu = add_options_page('Nextend Social Login', 'Nextend Social Login', NextendSocialLogin::getRequiredCapability(), 'nextend-social-login', array(
            'NextendSocialLoginAdmin',
            'display_admin'
        ));

        add_action('admin_print_styles-' . $menu, 'NextendSocialLoginAdmin::admin_css');
    }

    public static function admin_css() {
        wp_enqueue_style('nsl-admin-stylesheet', plugins_url('/style.css?nsl-ver=' . urlencode(NextendSocialLogin::$version), NSL_ADMIN_PATH));
    }

    public static function display_admin() {
        $view = !empty($_REQUEST['view']) ? $_REQUEST['view'] : '';

        if (substr($view, 0, 9) == 'provider-') {
            $providerID = substr($view, 9);
            if (isset(NextendSocialLogin::$providers[$providerID])) {
                self::display_admin_area('provider', $providerID);

                return;
            }
        }
        switch ($view) {
            case 'fix-redirect-uri':
                self::display_admin_area('fix-redirect-uri');
                break;
            case 'debug':
                self::display_admin_area('debug');
                break;
            case 'test-connection':
                self::display_admin_area('test-connection');
                break;
            case 'global-settings':
                self::display_admin_area('global-settings');
                break;
            case 'pro-addon':
                self::display_admin_area('pro-addon');
                break;
            case 'install-pro':
                if (check_admin_referer('nextend-social-login')) {
                    self::display_admin_area('install-pro');
                } else {
                    self::display_admin_area('providers');
                }
                break;
            default:
                self::display_admin_area('providers');
                break;
        }
    }

    /**
     * @param string $view
     * @param string $currentProvider
     */
    private static function display_admin_area($view, $currentProvider = '') {
        if (empty($currentProvider)) {
            include(dirname(__FILE__) . '/templates/header.php');
            include(dirname(__FILE__) . '/templates/menu.php');

            Notices::displayNotices();

            /** @var string $view */
            include(dirname(__FILE__) . '/templates/' . $view . '.php');
            include(dirname(__FILE__) . '/templates/footer.php');
        } else {
            include(dirname(__FILE__) . '/templates/' . $view . '.php');
        }
    }

    public static function renderProSettings() {
        include(dirname(__FILE__) . '/templates/global-settings-pro.php');
    }

    public static function admin_init() {

        if (current_user_can(NextendSocialLogin::getRequiredCapability())) {

            if (!isset($_GET['page']) || $_GET['page'] != 'nextend-social-login' || !isset($_GET['view']) || $_GET['view'] != 'fix-redirect-uri') {
                add_action('admin_notices', 'NextendSocialLoginAdmin::show_oauth_uri_notice');
            }

            if (!self::isPro() && NextendSocialLogin::$settings->get('woocommerce_dismissed') == 0 && class_exists('woocommerce', false) && count(NextendSocialLogin::$enabledProviders)) {
                add_action('admin_notices', 'NextendSocialLoginAdmin::show_woocommerce_notice');
            }

            if (defined('THEME_MY_LOGIN_VERSION') && version_compare(THEME_MY_LOGIN_VERSION, '7.0.0', '>=')) {
                if (!NextendSocialLogin::getRegisterFlowPage() || !NextendSocialLogin::getProxyPage()) {
                    add_action('admin_notices', 'NextendSocialLoginAdmin::show_theme_my_login_notice');
                }
            }
        }

        if (isset($_GET['page']) && $_GET['page'] == 'nextend-social-login') {
            if (!empty($_GET['view'])) {
                switch ($_GET['view']) {
                    case 'enable':
                    case 'sub-enable':
                        if (!empty($_GET['provider'])) {
                            if (check_admin_referer('nextend-social-login_enable_' . $_GET['provider'])) {
                                NextendSocialLogin::enableProvider($_GET['provider']);
                            }
                            if ($_GET['view'] == 'sub-enable') {
                                wp_redirect(NextendSocialLogin::$providers[$_GET['provider']]->getAdmin()
                                                                                             ->getUrl('settings'));
                                exit;
                            }

                            wp_redirect(self::getAdminUrl());
                            exit;
                        }
                        break;
                    case 'disable':
                    case 'sub-disable':
                        if (!empty($_GET['provider'])) {
                            if (check_admin_referer('nextend-social-login_disable_' . $_GET['provider'])) {
                                NextendSocialLogin::disableProvider($_GET['provider']);
                            }
                            if ($_GET['view'] == 'sub-disable') {
                                wp_redirect(NextendSocialLogin::$providers[$_GET['provider']]->getAdmin()
                                                                                             ->getUrl('settings'));
                                exit;
                            }

                            wp_redirect(self::getAdminUrl());
                            exit;
                        }
                        break;
                    case 'update_oauth_redirect_url':
                        if (check_admin_referer('nextend-social-login_update_oauth_redirect_url')) {
                            foreach (NextendSocialLogin::$enabledProviders as $provider) {
                                $provider->updateAuthRedirectUrl();
                            }
                        }

                        wp_redirect(self::getAdminUrl());
                        exit;

                    case 'dismiss_woocommerce':
                        if (check_admin_referer('nsl_dismiss_woocommerce')) {
                            NextendSocialLogin::$settings->update(array(
                                'woocommerce_dismissed' => 1
                            ));

                            if (!empty($_REQUEST['redirect_to'])) {
                                wp_safe_redirect($_REQUEST['redirect_to']);
                                exit;
                            }
                        }

                        wp_redirect(self::getAdminUrl());
                        break;
                }
            }
        }
        add_action('admin_post_nextend-social-login', 'NextendSocialLoginAdmin::save_form_data');
        add_action('wp_ajax_nextend-social-login', 'NextendSocialLoginAdmin::ajax_save_form_data');


        add_action('admin_enqueue_scripts', 'NextendSocialLoginAdmin::admin_enqueue_scripts');

        if (!function_exists('json_decode')) {
            add_settings_error('nextend-social', 'settings_updated', printf(__('%s needs json_decode function.', 'nextend-facebook-connect'), 'Nextend Social Login') . ' ' . __('Please contact your server administrator and ask for solution!', 'nextend-facebook-connect'), 'error');
        }

        add_action('show_user_profile', array(
            'NextendSocialLoginAdmin',
            'showUserFields'
        ));
        add_action('edit_user_profile', array(
            'NextendSocialLoginAdmin',
            'showUserFields'
        ));

        add_filter('display_post_states', array(
            'NextendSocialLoginAdmin',
            'display_post_states'
        ), 10, 2);

        if (defined('WPML_PLUGIN_BASENAME')) {
            add_action('nsl_getting_started_warnings', array(
                'NextendSocialLoginAdmin',
                'show_WPML_warning'
            ));
            add_filter('nsl_redirect_uri_override', array(
                'NextendSocialLoginAdmin',
                'WPML_override_provider_redirect_uris'
            ), 10, 2);

        };

        add_action('nsl_getting_started_warnings', array(
            'NextendSocialLoginAdmin',
            'show_getting_started_warning'
        ), 100, 2);
    }

    public static function save_form_data() {
        if (current_user_can(NextendSocialLogin::getRequiredCapability()) && check_admin_referer('nextend-social-login')) {
            foreach ($_POST as $k => $v) {
                if (is_string($v)) {
                    $_POST[$k] = stripslashes($v);
                }
            }

            $view = !empty($_REQUEST['view']) ? $_REQUEST['view'] : '';

            if ($view == 'global-settings') {

                NextendSocialLogin::$settings->update($_POST);

                Notices::addSuccess(__('Settings saved.'));

                wp_redirect(self::getAdminSettingsUrl(!empty($_REQUEST['subview']) ? $_REQUEST['subview'] : ''));
                exit;
            } else if ($view == 'pro-addon') {

                NextendSocialLogin::$settings->update($_POST);

                if (NextendSocialLogin::hasLicense()) {
                    Notices::addSuccess(__('The activation was successful', 'nextend-facebook-connect'));
                }

                wp_redirect(self::getAdminUrl($view));
                exit;
            } else if ($view == 'pro-addon-deauthorize') {

                NextendSocialLogin::$settings->update(array(
                    'license_key' => ''
                ));

                Notices::addSuccess(__('Deactivate completed.', 'nextend-facebook-connect'));

                wp_redirect(self::getAdminUrl('pro-addon'));
                exit;

            } else if (substr($view, 0, 9) == 'provider-') {
                $providerID = substr($view, 9);
                if (isset(NextendSocialLogin::$providers[$providerID])) {

                    if (NextendSocialLogin::$providers[$providerID]->settings->update($_POST)) {
                        Notices::addSuccess(__('Settings saved.'));
                    }

                    wp_redirect(NextendSocialLogin::$providers[$providerID]->getAdmin()
                                                                           ->getUrl(isset($_POST['subview']) ? $_POST['subview'] : ''));
                    exit;
                }
            }
        }

        wp_redirect(self::getAdminUrl());
        exit;
    }

    public static function ajax_save_form_data() {
        check_ajax_referer('nextend-social-login');
        if (current_user_can(NextendSocialLogin::getRequiredCapability())) {
            $view = !empty($_POST['view']) ? $_POST['view'] : '';
            switch ($view) {
                case 'orderProviders':
                    if (!empty($_POST['ordering'])) {
                        NextendSocialLogin::$settings->update(array(
                            'ordering' => $_POST['ordering']
                        ));
                    }
                    break;
                case 'newsletterSubscribe':
                    $user_info = wp_get_current_user();
                    update_user_meta($user_info->ID, 'nsl_newsletter_subscription', 1);
                    break;
            }
        }
    }

    public static function validateSettings($newData, $postedData) {

        if (isset($postedData['redirect'])) {
            if (isset($postedData['custom_redirect_enabled']) && $postedData['custom_redirect_enabled'] == '1') {
                $newData['redirect'] = trim(sanitize_text_field($postedData['redirect']));
            } else {
                $newData['redirect'] = '';
            }
        }

        if (isset($postedData['redirect_reg'])) {
            if (isset($postedData['custom_redirect_reg_enabled']) && $postedData['custom_redirect_reg_enabled'] == '1') {
                $newData['redirect_reg'] = trim(sanitize_text_field($postedData['redirect_reg']));
            } else {
                $newData['redirect_reg'] = '';
            }
        }

        if (isset($postedData['default_redirect'])) {
            if (isset($postedData['default_redirect_enabled']) && $postedData['default_redirect_enabled'] == '1') {
                $newData['default_redirect'] = trim(sanitize_text_field($postedData['default_redirect']));
            } else {
                $newData['default_redirect'] = '';
            }
        }

        if (isset($postedData['default_redirect_reg'])) {
            if (isset($postedData['default_redirect_reg_enabled']) && $postedData['default_redirect_reg_enabled'] == '1') {
                $newData['default_redirect_reg'] = trim(sanitize_text_field($postedData['default_redirect_reg']));
            } else {
                $newData['default_redirect_reg'] = '';
            }
        }

        foreach ($postedData as $key => $value) {
            switch ($key) {
                case 'debug':
                case 'bypass_cache':
                case 'login_restriction':
                case 'avatars_in_all_media':
                case 'custom_register_label':
                case 'terms_show':
                case 'store_name':
                case 'store_email':
                case 'avatar_store':
                case 'store_access_token':
                case 'redirect_prevent_external':
                    if ($value == 1) {
                        $newData[$key] = 1;
                    } else {
                        $newData[$key] = 0;
                    }
                    break;
                case 'terms':
                    $newData[$key] = wp_kses_post($value);
                    break;
                case 'blacklisted_urls':
                    $newData[$key] = sanitize_textarea_field($postedData[$key]);
                    break;
                case 'show_login_form':
                case 'login_form_button_align':
                case 'show_registration_form':
                case 'show_embedded_login_form':
                case 'embedded_login_form_button_align':
                case 'redirect_overlay':
                case 'unsupported_webview_behavior':
                    $newData[$key] = sanitize_text_field($value);
                    break;
                case 'enabled':
                    if (is_array($value)) {
                        $newData[$key] = $value;
                    }
                    break;
                case 'ordering':
                    if (is_array($value)) {
                        $newData[$key] = $value;
                    }
                    break;
                case 'license_key':
                    Notices::clear();

                    $value = trim(sanitize_text_field($value));

                    if (!empty($value)) {
                        try {
                            $response = self::apiCall('test-license', array('license_key' => $value));
                            if ($response === 'OK') {
                                $newData['licenses'] = array(
                                    array(
                                        'license_key' => $value,
                                        'domain'      => NextendSocialLogin::getDomain()
                                    )
                                );
                                wp_clean_plugins_cache();
                            }
                        } catch (Exception $e) {
                            Notices::addError($e->getMessage());
                        }
                    } else {
                        wp_clean_plugins_cache();
                        $newData['licenses'] = array();
                    }
                    break;
                case 'review_state':
                case 'woocommerce_dismissed':
                    $newData[$key] = intval($value);
                    break;
                case 'register-flow-page':
                case 'proxy-page':
                    if (get_post($value) !== null) {
                        $newData[$key] = $value;
                    } else {
                        $newData[$key] = '';
                    }
                    break;

                case 'allow_register':
                    if ($value == '0') {
                        $newData[$key] = 0;
                    } else if ($value == '1') {
                        $newData[$key] = 1;
                    } else {
                        $newData[$key] = -1;
                    }
                    break;


            }
        }

        return $newData;
    }

    public static function plugin_action_links($links, $file) {

        if ($file != NSL_PLUGIN_BASENAME) {
            return $links;
        }
        $settings_link   = '<a href="' . esc_url(menu_page_url('nextend-social-login', false)) . '">' . __('Settings') . '</a>';
        $reactivate_link = sprintf('<a href="%s">%s</a>', wp_nonce_url(admin_url('admin.php?page=nextend-social-login&repairnsl=1'), 'repairnsl'), 'Analyze & Repair');
        array_unshift($links, $settings_link, $reactivate_link);

        return $links;
    }

    public static function admin_enqueue_scripts() {
        if ('settings_page_nextend-social-login' === get_current_screen()->id) {

            // Since WordPress 4.9
            if (function_exists('wp_enqueue_code_editor')) {
                // Enqueue code editor and settings for manipulating HTML.
                $settings = wp_enqueue_code_editor(array('type' => 'text/html'));

                // Bail if user disabled CodeMirror.
                if (false === $settings) {
                    return;
                }

                wp_add_inline_script('code-editor', sprintf('jQuery( function() { var settings = %s; jQuery(".nextend-html-editor").each(function(i, el){wp.codeEditor.initialize( el, settings);}); } );', wp_json_encode($settings)));

                $settings['codemirror']['readOnly'] = 'nocursor';

                wp_add_inline_script('code-editor', sprintf('jQuery( function() { var settings = %s; jQuery(".nextend-html-editor-readonly").each(function(i, el){wp.codeEditor.initialize( el, settings);}); } );', wp_json_encode($settings)));
            }

            if (isset($_GET['view']) && $_GET['view'] == 'pro-addon') {
                wp_enqueue_script('plugin-install');
                wp_enqueue_script('updates');
            }
        }
    }

    private static $endpoint = 'https://api.nextendweb.com/v2/nextend-api/v2/';

    public static function getEndpoint($action = '') {
        return self::$endpoint . 'product/nsl/' . urlencode($action);
    }

    /**
     * @param       $action
     * @param array $args
     *
     * @return bool|mixed
     * @throws Exception
     */
    public static function apiCall($action, $args = array()) {

        $body = array(
            'platform' => 'wordpress',
            'domain'   => NextendSocialLogin::getDomain()
        );

        $activation_data = NextendSocialLogin::getLicense();
        if ($activation_data !== false) {
            $body['license_key'] = $activation_data['license_key'];
        } else {
            $body['license_key'] = '';
        }

        $http_args = array(
            'timeout'    => 15,
            'user-agent' => 'WordPress',
            'body'       => array_merge($body, $args)
        );

        $request = wp_remote_get(self::getEndpoint($action), $http_args);

        if (is_wp_error($request)) {

            throw new NSLSanitizedRequestErrorMessageException($request->get_error_message());
        } else if (wp_remote_retrieve_response_code($request) !== 200) {

            $response = json_decode(wp_remote_retrieve_body($request), true);
            if (isset($response['message'])) {
                $message = 'Nextend Social Login Pro Addon: ' . $response['message'];

                Notices::addError($message);

                return new WP_Error('error', $message);
            }

            throw new NSLSanitizedRequestErrorMessageException(sprintf(__('Unexpected response: %s', 'nextend-facebook-connect'), wp_remote_retrieve_body($request)));
        }

        $response = json_decode(wp_remote_retrieve_body($request), true);

        return $response;
    }

    public static function showProBox() {
        if (!self::isPro()) {
            include(dirname(__FILE__) . '/templates/pro.php');
        }
    }

    public static function getProState() {

        if (NextendSocialLogin::hasLicense()) {
            if (self::isPro()) {
                return 'activated';
            } else if (!current_user_can('install_plugins')) {
                return 'no-capability';
            } else if (class_exists('NextendSocialLoginPRO', false) && version_compare(NextendSocialLogin::$version, NextendSocialLoginPRO::$nslMinVersion, '<')) {
                return 'free-not-compatible';
            } else if (class_exists('NextendSocialLoginPRO', false) && version_compare(NextendSocialLoginPRO::$version, NextendSocialLogin::$nslPROMinVersion, '<')) {
                return 'pro-not-compatible';
            } else {
                if (file_exists(WP_PLUGIN_DIR . '/nextend-social-login-pro/nextend-social-login-pro.php')) {
                    return 'installed';
                } else {
                    return 'not-installed';
                }
            }
        }

        return 'no-license';
    }

    public static function trackUrl($url, $source) {
        return add_query_arg(array(
            'utm_campaign' => 'nsl',
            'utm_source'   => urlencode($source),
            'utm_medium'   => 'nsl-wordpress-' . (apply_filters('nsl-pro', false) ? 'pro' : 'free')
        ), $url);
    }

    public static function save_review_state() {
        check_ajax_referer('nsl_save_review_state');
        if (isset($_POST['review_state'])) {
            $review_state = intval($_POST['review_state']);
            if ($review_state > 0) {

                NextendSocialLogin::$settings->update(array(
                    'review_state' => $review_state
                ));
            }
        }
        wp_die();
    }

    public static function show_oauth_uri_notice() {
        foreach (NextendSocialLogin::$enabledProviders as $provider) {
            if (!$provider->checkAuthRedirectUrl()) {
                echo '<div class="error">
                        <p>' . sprintf(__('%s detected that your login url changed. You must update the Oauth redirect URIs in the related social applications.', 'nextend-facebook-connect'), '<b>Nextend Social Login</b>') . '</p>
                        <p class="submit"><a href="' . NextendSocialLoginAdmin::getAdminUrl('fix-redirect-uri') . '" class="button button-primary">' . __('Fix Error', 'nextend-facebook-connect') . ' - ' . __('Oauth Redirect URI', 'nextend-facebook-connect') . '</a></p>
                    </div>';
                break;
            }
        }
    }

    public static function show_woocommerce_notice() {
        $redirectTo = array();

        $currentPageUrl = NextendSocialLogin::getCurrentPageURL();
        if ($currentPageUrl !== false) {
            $redirectTo['redirect_to'] = urlencode($currentPageUrl);
        }

        $dismissUrl = wp_nonce_url(add_query_arg($redirectTo, NextendSocialLoginAdmin::getAdminUrl('dismiss_woocommerce')), 'nsl_dismiss_woocommerce');
        echo '<div class="notice notice-info">
            <p>' . sprintf(__('%1$s detected that %2$s installed on your site. You need the Pro Addon to display Social Login buttons in %2$s login form!', 'nextend-facebook-connect'), '<b>Nextend Social Login</b>', '<b>WooCommerce</b>') . '</p>
            <p><a href="' . NextendSocialLoginAdmin::trackUrl('https://nextendweb.com/social-login/', 'woocommerce-notice') . '" target="_blank" onclick="window.location.href=\'' . esc_url($dismissUrl) . '\';" class="button button-primary">' . __('Dismiss and check Pro Addon', 'nextend-facebook-connect') . '</a> <a href="' . esc_url($dismissUrl) . '" class="button button-secondary">' . __('Dismiss', 'nextend-facebook-connect') . '</a></p>
        </div>';
    }

    public static function show_theme_my_login_notice() {
        echo '<div class="notice notice-info">
            <p>' . sprintf(__('%1$s detected that %2$s installed on your site. You must set "<b>Page for register flow</b>" and "<b>OAuth redirect uri proxy page</b>" in %1$s to work properly.', 'nextend-facebook-connect'), '<b>Nextend Social Login</b>', '<b>Theme My Login</b>') . '</p>
            <p><a href="' . NextendSocialLoginAdmin::getAdminSettingsUrl('general') . '" class="button button-primary">' . __('Fix now', 'nextend-facebook-connect') . '</a></p>
        </div>';
    }

    public static function isPro() {
        return apply_filters('nsl-pro', false);
    }

    public static function showUserFields($user) {
        include(dirname(__FILE__) . '/EditUser.php');
    }

    public static function authorizeBox($view = 'pro-addon') {

        $args = array(
            'product'  => 'nsl',
            'domain'   => NextendSocialLogin::getDomain(),
            'platform' => 'wordpress'

        );

        $authorizeUrl = NextendSocialLoginAdmin::trackUrl('https://secure.nextendweb.com/authorize/', 'authorize');
        ?>
        <div class="nsl-box nsl-box-yellow nsl-box-padlock">
            <h2 class="title"><?php _e('Activate your Pro Addon', 'nextend-facebook-connect'); ?></h2>
            <p><?php _e('To be able to use the Pro features, you need to activate Nextend Social Login Pro Addon. You can do this by clicking on the Activate button below then select the related purchase.', 'nextend-facebook-connect'); ?></p>

            <p>
                <a href="#"
                   onclick="NSLActivate()"
                   class="button button-primary"><?php _e('Activate', 'nextend-facebook-connect'); ?></a>
            </p>
        </div>

        <script type="text/javascript">
            (function ($) {

                var args = <?php echo wp_json_encode($args); ?>;
                window.addEventListener('message', function (e) {
                    if (e.origin === 'https://secure.nextendweb.com') {
                        if (typeof window.authorizeWindow === 'undefined') {
                            if (typeof e.source !== 'undefined') {
                                window.authorizeWindow = e.source;
                            } else {
                                return false;
                            }
                        }

                        try {
                            var envelope = JSON.parse(e.data);

                            if (envelope.action) {
                                switch (envelope.action) {
                                    case 'ready':
                                        window.authorizeWindow.postMessage(JSON.stringify({
                                            'action': 'authorize',
                                            'data': args
                                        }), 'https://secure.nextendweb.com');
                                        break;
                                    case 'license':
                                        $('#nsl_license_key').val(envelope.license_key);
                                        $('#nsl_license_form').submit();
                                        break;
                                }

                            }
                        } catch (ex) {
                            console.error(ex);
                            console.log(e);
                        }
                    }
                });
            })(jQuery);

            function NSLActivate() {
                var isIE = (function detectIE() {
                    var ua = window.navigator.userAgent;

                    var msie = ua.indexOf('MSIE ');
                    if (msie > 0) {
                        // IE 10 or older => return version number
                        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
                    }

                    var trident = ua.indexOf('Trident/');
                    if (trident > 0) {
                        // IE 11 => return version number
                        var rv = ua.indexOf('rv:');
                        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
                    }

                    var edge = ua.indexOf('Edge/');
                    if (edge > 0) {
                        // Edge (IE 12+) => return version number
                        return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
                    }

                    // other browser
                    return false;
                })();

                if (isIE <= 11) {
                    /**
                     * Trick for cross origin popup postMessage in IE 11
                     * @see <https://stackoverflow.com/a/36630058/305604>
                     */

                    window.authorizeWindow = NSLPopup('/', 'authorize-window', 800, 800);
                    window.authorizeWindow.location.href = 'about:blank';
                    window.authorizeWindow.location.href = '<?php echo $authorizeUrl; ?>';
                } else {
                    window.authorizeWindow = NSLPopup('<?php echo $authorizeUrl; ?>', 'authorize-window', 800, 800);
                }
                return false;
            }
        </script>

        <form id="nsl_license_form" method="post" action="<?php echo admin_url('admin-post.php'); ?>"
              novalidate="novalidate" style="display:none;">

            <?php wp_nonce_field('nextend-social-login'); ?>
            <input type="hidden" name="action" value="nextend-social-login"/>
            <input type="hidden" name="view" value="<?php echo $view; ?>"/>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label
                                for="nsl_license_key"><?php _e('License key', 'nextend-facebook-connect'); ?></label>
                    </th>
                    <?php
                    $license_key    = '';
                    $authorizedData = NextendSocialLogin::getLicense();
                    if ($authorizedData !== false) {
                        $license_key = $authorizedData['license_key'];
                    }
                    ?>
                    <td><input name="license_key" type="text" id="nsl_license_key"
                               value="<?php echo esc_attr($license_key); ?>"
                               class="regular-text">
                    </td>
                </tr>
                </tbody>
            </table>

        </form>
        <?php
    }

    public static function display_post_states($post_states, $post) {
        if (NextendSocialLogin::getProxyPage() === $post->ID) {
            $post_states['nsl_proxy_page'] = __('OAuth proxy page') . ' — NSL';
        }
        if (NextendSocialLogin::getRegisterFlowPage() === $post->ID) {
            $post_states['nsl_proxy_page'] = __('Register flow page') . ' — NSL';
        }

        return $post_states;
    }

    public static function show_WPML_warning() {
        printf(__('<strong><u>Warning</u></strong>: You are using <b>%1$s</b>! Depending on your %1$s configuration the Redirect URI can be different. For more information please check our %2$s %1$s compatibility tutorial%3$s!', 'nextend-facebook-connect'), 'WPML', '<a href="https://nextendweb.com/nextend-social-login-docs/how-to-make-nextend-social-login-compatible-with-wpml/" target="_blank">', '</a>');
    }

    /**
     * @param array                 $redirectUrls
     * @param NextendSocialProvider $provider
     *
     * Used for:
     * -overriding the redirect url with the language specific redirect URLs in provider Getting Started
     * sections.
     * -generating language specific redirect urls for the OAuth check warning.
     *
     * @return array
     */
    public static function WPML_override_provider_redirect_uris($redirectUrls, $provider) {

        $addArg = true;
        if ($provider->authRedirectBehavior !== 'default') {
            /**
             * We shouldn't add any query parameters into the redirect url if:
             * -query parameters are not supported in the redirect uri
             * -or the redirect is handled over the REST /redirect_uri endpoint of the provider.
             */
            $addArg = false;
        }


        global $sitepress;
        if ($sitepress && method_exists($sitepress, 'get_active_languages')) {
            $WPML_active_languages = $sitepress->get_active_languages();
            if (count($WPML_active_languages) > 1 && defined('ICL_LANGUAGE_CODE')) {
                $originalLanguageCode      = ICL_LANGUAGE_CODE;
                $defaultLanguageCode       = self::get_default_WPML_language_code();
                $languageCodeWasOverridden = false;

                $converted_URLs = array();
                $args           = array('loginSocial' => $provider->getId());


                if ($provider->authRedirectBehavior !== 'rest_redirect') {
                    $proxyPage = NextendSocialLogin::getProxyPage();

                    if ($proxyPage) {
                        /**
                         * OAuth flow handled over OAuth redirect uri proxy page
                         * This needs to be handled differently than /wp-login.php URLs, because in these cases
                         * the slug of the translated OAuth redirect uri proxy page can be different as well!
                         */

                        foreach ($WPML_active_languages as $lang) {
                            $convertedURL = get_permalink(apply_filters('wpml_object_id', $proxyPage, 'page', false, $lang['code']));
                            if ($convertedURL) {
                                if ($addArg) {
                                    $convertedURL = add_query_arg($args, $convertedURL);
                                } else {
                                    /**
                                     * Converted URLs may contain GET parameters, so we need to remove them for the providers that don't support GET parameters in the redirect urls.
                                     */
                                    $convertedURLPieces = explode('?', $convertedURL);
                                    $convertedURL       = $convertedURLPieces[0];
                                }
                                $converted_URLs[] = $convertedURL;
                            }
                        }
                    } else {
                        //OAuth flow handled over wp-login.php

                        $WPML_language_url_format = false;
                        if (method_exists($sitepress, 'get_setting')) {
                            $WPML_language_url_format = $sitepress->get_setting('language_negotiation_type');
                        }

                        if ($WPML_language_url_format && $WPML_language_url_format == 3 && (!class_exists('\WPML\UrlHandling\WPLoginUrlConverter') || (class_exists('\WPML\UrlHandling\WPLoginUrlConverter') && (!get_option(\WPML\UrlHandling\WPLoginUrlConverter::SETTINGS_KEY, false) || (get_option(\WPML\UrlHandling\WPLoginUrlConverter::SETTINGS_KEY, false) && !$addArg))))) {
                            /**
                             * We need to display the original redirect url when the
                             * Language URL format is set to "Language name added as a parameter and:
                             * -when the WPLoginUrlConverter class doesn't exists, since that case it is an old WPML version that can not translate the /wp-login.php page
                             * -if "Login and registration pages - Allow translating the login and registration pages" is disabled
                             * -if "Login and registration pages - Allow translating the login and registration pages" is enabled, but the provider doesn't support GET parameters in the redirect URL
                             */
                            return $redirectUrls;
                        } else {
                            global $wpml_url_converter;
                            /**
                             * when the language URL format is set to "Different languages in directories" or "A different domain per language", then the Redirect URI will be different for each languages
                             * Also when the language URL format is set to "Language name added as a parameter" and the "Login and registration pages - Allow translating the login and registration pages" setting is enabled, the urls will be different.
                             */
                            if ($wpml_url_converter && method_exists($wpml_url_converter, 'convert_url')) {


                                /**
                                 * When WPML is set to a non-default language in the backend, then the $wpml_url_converter->convert_url() function won't generate language specific URL
                                 * if the provided language code is the same the the language code that the backend currently uses.
                                 */
                                if ($originalLanguageCode && $defaultLanguageCode && $originalLanguageCode !== $defaultLanguageCode) {
                                    self::change_WPML_language_code($defaultLanguageCode, false);
                                    $languageCodeWasOverridden = true;
                                }

                                foreach ($WPML_active_languages as $lang) {
                                    $convertedURL = $wpml_url_converter->convert_url(site_url('wp-login.php'), $lang['code']);
                                    if ($addArg) {
                                        $convertedURL = add_query_arg($args, $convertedURL);
                                    }
                                    $converted_URLs[] = $convertedURL;
                                }

                                if ($languageCodeWasOverridden) {
                                    /**
                                     * we need to switch back to the original language if we had to switch earlier
                                     */
                                    self::change_WPML_language_code($originalLanguageCode, true);
                                    $languageCodeWasOverridden = false;
                                }
                            }
                        }
                    }
                } else {
                    /**
                     * For providers with REST API redirect url, we should generate language specific versions from the rest route.
                     * These urls should never contain the ?loginSocial={{providerID}} parameter. Since that is the main reason of the provider prefers uses the REST API endpoint.
                     * The redirect url is not affected by the "/wp-login.php" or "OAuth redirect uri proxy page" changes in this case.
                     */

                    $WPML_language_url_format = false;
                    if (method_exists($sitepress, 'get_setting')) {
                        $WPML_language_url_format = $sitepress->get_setting('language_negotiation_type');
                    }
                    if (!$WPML_language_url_format || ($WPML_language_url_format && $WPML_language_url_format == 3)) {
                        /**
                         * We need to return the original provider REST API url when:
                         * -the Language URL format is set to "Language name added as a parameter
                         * -or if there is no Language URL format set
                         */
                        $converted_URLs = $redirectUrls;
                    } else {
                        global $wpml_url_converter;
                        if ($wpml_url_converter && method_exists($wpml_url_converter, 'convert_url')) {

                            /**
                             * When the WPML language in the backend is set to "All", then WPML will generate an invalid REST API url with this "all" string appearing in it,
                             * so we would generate wrong redirect urls.
                             * For this reason it is better if we always use the default language for the URL generation here, too.
                             */
                            if ($originalLanguageCode && $defaultLanguageCode && $originalLanguageCode !== $defaultLanguageCode) {
                                self::change_WPML_language_code($defaultLanguageCode, false);
                                $languageCodeWasOverridden = true;
                            }

                            $redirectUrl = $provider->getBaseRedirectUriForAppCreation();

                            foreach ($WPML_active_languages as $lang) {
                                $convertedURL = $wpml_url_converter->convert_url($redirectUrl, $lang['code']);
                                /**
                                 * WPML might generate URLs with trailing slash, however we use the REST redirect URL without trailing slash.
                                 */
                                $convertedURL = rtrim($convertedURL, '/');
                                if ($addArg) {
                                    $convertedURL = add_query_arg($args, $convertedURL);
                                }
                                $converted_URLs[] = $convertedURL;
                            }

                            if ($languageCodeWasOverridden) {
                                /**
                                 * we need to switch back to the original language if we had to switch earlier
                                 */
                                self::change_WPML_language_code($originalLanguageCode, true);
                                $languageCodeWasOverridden = false;
                            }
                        }
                    }
                }

                if (!empty($converted_URLs)) {
                    return $converted_URLs;
                }
            }
        }

        return $redirectUrls;
    }

    /**
     * Returns the default language code used by WPML.
     *
     * @return bool|string
     */
    public static function get_default_WPML_language_code() {
        global $sitepress;

        if ($sitepress) {
            return $sitepress->get_default_language();
        }

        return false;
    }

    /**
     * Thins function can be used for changing the language code that WPML use during URL conversion.
     *
     * @param string $languageCode - the language code that WPML will switch to
     * @param bool   $restore      - if true, that means we shouldn't override the language for the
     *                             get_language_from_url() function of WPML.
     */
    public static function change_WPML_language_code($languageCode, $restore) {
        global $sitepress;

        if ($sitepress) {
            $sitepress->switch_lang($languageCode, true);
            if ($restore) {
                remove_filter('wpml_get_language_from_url', 'NextendSocialLoginAdmin::get_default_WPML_language_code', 1000000000);
            } else {
                add_filter('wpml_get_language_from_url', 'NextendSocialLoginAdmin::get_default_WPML_language_code', 1000000000);
            }
        }
    }

    /**
     * @param NextendSocialProviderDummy $provider
     * @param string                     $lastUpdated
     */
    public static function show_getting_started_warning($provider, $lastUpdated) {
        if ($provider && $lastUpdated) {

            $lastUpdatedDate = date_format(date_create_from_format('Y-m-d', $lastUpdated), get_option('date_format'));

            $supportURL         = 'https://nextendweb.com/contact-us/nextend-social-login-support/';
            $version            = defined('NSL_PRO_PATH') ? 'Pro-Addon' : 'Free';
            $args               = array(
                'topic'    => 'Wrong-Steps',
                'provider' => $provider->getLabel(),
                'version'  => $version
            );
            $supportUrlWithArgs = add_query_arg($args, $supportURL);

            $documentationURL = 'https://nextendweb.com/nextend-social-login-docs/provider-' . $provider->getId() . '/';

            printf(__('<p><strong><u>Warning</u></strong>: Providers change the App setup process quite often, which means some steps below might not be accurate. If you see significant difference in the written instructions and what you see at the provider, check the guide in the %1$sonline documentation%2$s first, just in case if we are already aware of the changes hence updated the guide. Otherwise feel free to %3$sreport the changes%4$s, so we can check and update the instructions.<br><strong>Last updated:</strong> %5$s.</p>', 'nextend-facebook-connect'), '<a href="' . $documentationURL . '" target="_blank">', '</a>', '<a href="' . $supportUrlWithArgs . '" target="_blank">', '</a>', $lastUpdatedDate);
        }
    }
}