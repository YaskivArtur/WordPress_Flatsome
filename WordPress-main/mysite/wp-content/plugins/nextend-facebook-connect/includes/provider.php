<?php

use NSL\Notices;
use NSL\Persistent\Persistent;

require_once dirname(__FILE__) . '/provider-admin.php';
require_once dirname(__FILE__) . '/provider-dummy.php';
require_once dirname(__FILE__) . '/user.php';

abstract class NextendSocialProvider extends NextendSocialProviderDummy {

    protected $dbID;
    protected $optionKey;

    protected $enabled = false;

    /** @var NextendSocialAuth */
    protected $client;

    protected $authUserData = array();

    protected $requiredFields = array();

    protected $svg = '';

    protected $sync_fields = array();

    /**
     * NextendSocialProvider constructor.
     *
     * @param $defaultSettings
     */
    public function __construct($defaultSettings) {

        if (empty($this->dbID)) {
            $this->dbID = $this->id;
        }

        $this->optionKey = 'nsl_' . $this->id;

        do_action('nsl_provider_init', $this);

        $this->sync_fields = apply_filters('nsl_' . $this->getId() . '_sync_fields', $this->sync_fields);

        $extraSettings = apply_filters('nsl_' . $this->getId() . '_extra_settings', array(
            'ask_email'      => 'when-empty',
            'ask_user'       => 'never',
            'ask_password'   => 'never',
            'auto_link'      => 'email',
            'disabled_roles' => array(),
            'register_roles' => array(
                'default'
            )
        ));

        foreach ($this->getSyncFields() as $field_name => $fieldData) {

            $extraSettings['sync_fields/fields/' . $field_name . '/enabled']  = 0;
            $extraSettings['sync_fields/fields/' . $field_name . '/meta_key'] = $this->id . '_' . $field_name;
        }

        $this->settings = new NextendSocialLoginSettings($this->optionKey, array_merge(array(
            'settings_saved'        => '0',
            'tested'                => '0',
            'custom_default_button' => '',
            'custom_icon_button'    => '',
            'login_label'           => '',
            'register_label'        => '',
            'link_label'            => '',
            'unlink_label'          => '',
            'user_prefix'           => '',
            'user_fallback'         => '',
            'oauth_redirect_url'    => '',
            'terms'                 => '',

            'sync_profile/register' => 1,
            'sync_profile/login'    => 1,
            'sync_profile/link'     => 1,

            'sync_fields/link'  => 0,
            'sync_fields/login' => 0
        ), $extraSettings, $defaultSettings));

        $this->admin = new NextendSocialProviderAdmin($this);

    }

    public function needPro() {
        return false;
    }

    /**
     * @return string
     */
    public function getDbID() {
        return $this->dbID;
    }

    public function getOptionKey() {
        return $this->optionKey;
    }

    public function getRawDefaultButton() {

        return '<div class="nsl-button nsl-button-default nsl-button-' . $this->id . '" style="background-color:' . $this->color . ';"><div class="nsl-button-svg-container">' . $this->svg . '</div><div class="nsl-button-label-container">{{label}}</div></div>';
    }

    public function getRawIconButton() {
        return '<div class="nsl-button nsl-button-icon nsl-button-' . $this->id . '" style="background-color:' . $this->color . ';"><div class="nsl-button-svg-container">' . $this->svg . '</div></div>';
    }

    public function getDefaultButton($label) {
        $button = $this->settings->get('custom_default_button');
        if (!empty($button)) {
            return str_replace('{{label}}', __($label, 'nextend-facebook-connect'), $button);
        }

        return str_replace('{{label}}', __($label, 'nextend-facebook-connect'), $this->getRawDefaultButton());
    }

    public function getIconButton() {
        $button = $this->settings->get('custom_icon_button');
        if (!empty($button)) {
            return $button;
        }

        return $this->getRawIconButton();
    }

    public function getLoginUrl() {
        $args = array('loginSocial' => $this->getId());

        if (isset($_REQUEST['interim-login'])) {
            $args['interim-login'] = 1;
        }

        return add_query_arg($args, NextendSocialLogin::getLoginUrl());
    }

    /**
     * Returns the url where the Provider App should redirect during the OAuth/OpenID flow.
     *
     * @return string
     */
    public abstract function getRedirectUriForAuthFlow();


    /**
     * Should return a single redirect URL that:
     * - we us as default redirect uri suggestion in the Getting Started and Fixed redirect uri pages.
     * - we store to detect the redirect url changes
     *
     * @return string
     */
    public abstract function getBaseRedirectUriForAppCreation();

    /**
     * This function should return an array of URLs generated from getRedirectUri().
     *
     * We display the generated results in the Getting Started section and the Fixed redirect uri pages.
     * Also we use these for the OAuth redirect uri change checking.
     *
     * @return array
     */
    public function getAllRedirectUrisForAppCreation() {
        /**
         * Parameters:
         * 1: Array with an URL that should be added to the App by default.
         *
         * 2: The provider instance
         */
        return apply_filters('nsl_redirect_uri_override', array($this->getBaseRedirectUriForAppCreation()), $this);
    }

    /**
     * Enable the selected provider.
     *
     * @return bool
     */
    public function enable() {
        $this->enabled = true;

        do_action('nsl_' . $this->getId() . '_enabled');

        return true;
    }

    /**
     * Check if provider is enabled.
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * Check if provider is verified.
     *
     * @return bool
     */
    public function isTested() {
        return !!$this->settings->get('tested');
    }


    public abstract function checkAuthRedirectUrl();

    public function updateAuthRedirectUrl() {
        $this->settings->update(array(
            'oauth_redirect_url' => $this->getBaseRedirectUriForAppCreation()
        ));
    }

    /**
     * @return array
     */
    public function getRequiredFields() {
        return $this->requiredFields;
    }

    /**
     * Get the current state of a Provider.
     *
     * @return string
     */
    public function getState() {
        foreach ($this->requiredFields as $name => $label) {
            $value = $this->settings->get($name);
            if (empty($value)) {
                return 'not-configured';
            }
        }
        if (!$this->isTested()) {
            return 'not-tested';
        }

        if (!$this->isEnabled()) {
            return 'disabled';
        }

        return 'enabled';
    }

    /**
     * Authenticate and connect with the provider.
     */
    public function connect() {
        try {
            $this->doAuthenticate();
        } catch (NSLContinuePageRenderException $e) {
            // This is not an error. We allow the page to continue the normal display flow and later we inject our things.
            // Used by Theme my login function where we override the shortcode and we display our email request.
        } catch (Exception $e) {
            $this->onError($e);
        }
    }

    /**
     * @return NextendSocialAuth
     */
    protected abstract function getClient();

    public function getTestUrl() {
        return $this->getClient()
                    ->getTestUrl();
    }

    /**
     * @throws NSLContinuePageRenderException
     */
    protected function doAuthenticate() {

        if (!headers_sent()) {
            //All In One WP Security sets a LOCATION header, so we need to remove it to do a successful test.
            if (function_exists('header_remove')) {
                header_remove("LOCATION");
            } else {
                header('LOCATION:', true); //Under PHP 5.3
            }
        }

        //If it is a real login action, add the actions for the connection.
        if (!$this->isTest()) {
            add_action($this->id . '_login_action_before', array(
                $this,
                'liveConnectBefore'
            ));
            add_action($this->id . '_login_action_redirect', array(
                $this,
                'liveConnectRedirect'
            ));
            add_action($this->id . '_login_action_get_user_profile', array(
                $this,
                'liveConnectGetUserProfile'
            ));

            $interim_login = isset($_REQUEST['interim-login']);
            if ($interim_login) {
                Persistent::set($this->id . '_interim_login', 1);
            }
            /**
             * Store the settings for the provider login.
             */
            $display = isset($_REQUEST['display']);
            if ($display && $_REQUEST['display'] == 'popup') {
                Persistent::set($this->id . '_display', 'popup');
            }

        } else { //This is just to verify the settings.
            add_action($this->id . '_login_action_get_user_profile', array(
                $this,
                'testConnectGetUserProfile'
            ));
        }

        // Redirect if the login is blocked by another Plugin like Cerber.
        if (function_exists('cerber_is_allowed')) {
            $allowed = cerber_is_allowed();
            if (!$allowed) {
                global $wp_cerber;
                $error = $wp_cerber->getErrorMsg();
                Notices::addError($error);

                $loginDisabledRedirectURL = apply_filters('nsl_disabled_login_redirect_url', NextendSocialLogin::getLoginUrl());
                $this->redirectWithAuthenticationError($loginDisabledRedirectURL);
            }
        }

        do_action($this->id . '_login_action_before', $this);

        $this->doAuthProtocolSpecificFlow();

    }

    protected function handlePopupRedirectAfterAuthentication() {
        /**
         * if the login display was in popup window,
         * in the source window the user is redirected to the login url.
         * and the popup window must be closed
         */
        if (Persistent::get($this->id . '_display') == 'popup') {
            Persistent::delete($this->id . '_display');
            ?>
            <!doctype html>
            <html lang=en>
            <head>
                <meta charset=utf-8>
                <title><?php _e('Authentication successful', 'nextend-facebook-connect'); ?></title>
                <script type="text/javascript">
                    try {
                        if (window.opener !== null && window.opener !== window) {
                            var sameOrigin = true;
                            try {
                                var currentOrigin = window.location.protocol + '//' + window.location.hostname;
                                if (window.opener.location.href.substring(0, currentOrigin.length) !== currentOrigin) {
                                    sameOrigin = false;
                                }

                            } catch (e) {
                                /**
                                 * Blocked cross origin
                                 */
                                sameOrigin = false;
                            }
                            if (sameOrigin) {
                                var url = <?php echo wp_json_encode($this->getLoginUrl()); ?>;
                                if (typeof window.opener.nslRedirect === 'function') {
                                    window.opener.nslRedirect(url);
                                } else {
                                    window.opener.location = url;
                                }
                                window.close();
                            } else {
                                window.location.reload(true);
                            }
                        } else {
                            if (window.opener === null) {
                                /**
                                 * Cross-Origin-Opener-Policy blocked the access to the opener
                                 */
                                if (typeof BroadcastChannel === "function") {
                                    const _nslLoginBroadCastChannel = new BroadcastChannel('nsl_login_broadcast_channel');
                                    _nslLoginBroadCastChannel.postMessage({
                                        action: 'redirect',
                                        href:<?php echo wp_json_encode($this->getLoginUrl()); ?>});
                                    _nslLoginBroadCastChannel.close();
                                    window.close();
                                } else {
                                    window.location.reload(true);
                                }
                            } else {
                                window.location.reload(true);
                            }
                        }
                    } catch (e) {
                        window.location.reload(true);
                    }
                </script>
            </head>
            <body><a href="<?php echo esc_url($this->getLoginUrl()); ?>"><?php echo 'Continue...'; ?></a></body>
            </html>
            <?php
            exit;
        }
    }

    protected abstract function doAuthProtocolSpecificFlow();

    /**
     * @param $data
     * Connect with the selected provider.
     * After a successful login, we no longer need the previous persistent data.
     */
    public function liveConnectGetUserProfile($data) {

        $socialUser = new NextendSocialUser($this, $data);
        $socialUser->liveConnectGetUserProfile();

        $this->deleteLoginPersistentData();
        $this->redirectToLastLocationOther(true);
    }

    /**
     * @param $user_id
     * @param $providerIdentifier
     * @param $isRegister
     * Insert the userid into the wp_social_users table,
     * in this way a link is created between user accounts and the providers.
     *
     * @return bool
     */
    public function linkUserToProviderIdentifier($user_id, $providerIdentifier, $isRegister = false) {
        /** @var $wpdb WPDB */ global $wpdb;

        $connectedProviderID = $this->getProviderIdentifierByUserID($user_id);

        if ($connectedProviderID !== null) {
            if ($connectedProviderID == $providerIdentifier) {
                // This provider already linked to this user
                return true;
            }

            // User already have this provider attached to his account with different provider id.
            return false;
        }

        if ($isRegister) {
            /**
             * This is a register action.
             */
            $wpdb->insert($wpdb->prefix . 'social_users', array(
                'ID'            => $user_id,
                'type'          => $this->dbID,
                'identifier'    => $providerIdentifier,
                'register_date' => current_time('mysql'),
                'link_date'     => current_time('mysql'),
            ), array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s'
            ));
        } else {
            /**
             * This is a link action.
             */
            $wpdb->insert($wpdb->prefix . 'social_users', array(
                'ID'         => $user_id,
                'type'       => $this->dbID,
                'identifier' => $providerIdentifier,
                'link_date'  => current_time('mysql'),
            ), array(
                '%d',
                '%s',
                '%s',
                '%s'
            ));
        }

        do_action('nsl_' . $this->getId() . '_link_user', $user_id, $this->getId(), $isRegister);

        return true;
    }

    public function getUserIDByProviderIdentifier($identifier) {
        /** @var $wpdb WPDB */ global $wpdb;

        return $wpdb->get_var($wpdb->prepare('SELECT ID FROM `' . $wpdb->prefix . 'social_users` WHERE type = %s AND identifier = %s', array(
            $this->dbID,
            $identifier
        )));
    }

    protected function getProviderIdentifierByUserID($user_id) {
        /** @var $wpdb WPDB */ global $wpdb;

        return $wpdb->get_var($wpdb->prepare('SELECT identifier FROM `' . $wpdb->prefix . 'social_users` WHERE type = %s AND ID = %s', array(
            $this->dbID,
            $user_id
        )));
    }

    /**
     * @param $user_id
     * Delete the link between the user account and the provider.
     */
    public function removeConnectionByUserID($user_id) {
        /** @var $wpdb WPDB */ global $wpdb;

        $wpdb->query($wpdb->prepare('DELETE FROM `' . $wpdb->prefix . 'social_users` WHERE type = %s AND ID = %d', array(
            $this->dbID,
            $user_id
        )));
    }

    protected function unlinkUser() {
        //Filter to disable unlinking social accounts
        $unlinkAllowed = apply_filters('nsl_allow_unlink', true);

        if ($unlinkAllowed) {
            $user_info = wp_get_current_user();
            if ($user_info->ID) {
                $unlinkedIdentifier = $this->getProviderIdentifierByUserID($user_info->ID);
                $this->removeConnectionByUserID($user_info->ID);

                do_action('nsl_unlink_user', $user_info->ID, $this->getId(), $unlinkedIdentifier);

                return true;
            }
        }

        return false;
    }

    /**
     * If the current user has linked the account with a provider return the user identifier else false.
     *
     * @return bool|null|string
     */
    public function isCurrentUserConnected() {
        /** @var $wpdb WPDB */ global $wpdb;

        $current_user = wp_get_current_user();
        $ID           = $wpdb->get_var($wpdb->prepare('SELECT identifier FROM `' . $wpdb->prefix . 'social_users` WHERE type LIKE %s AND ID = %d', array(
            $this->dbID,
            $current_user->ID
        )));
        if ($ID === null) {
            return false;
        }

        return $ID;
    }

    /**
     * @param $user_id
     * If a user has linked the account with a provider return the user identifier else false.
     *
     * @return bool|null|string
     */
    public function isUserConnected($user_id) {
        /** @var $wpdb WPDB */ global $wpdb;

        $ID = $wpdb->get_var($wpdb->prepare('SELECT identifier FROM `' . $wpdb->prefix . 'social_users` WHERE type LIKE %s AND ID = %d', array(
            $this->dbID,
            $user_id
        )));
        if ($ID === null) {
            return false;
        }

        return $ID;
    }

    public function getConnectButton($buttonStyle = 'default', $redirectTo = null, $trackerData = false, $labelType = 'login', $customLabel = false) {
        $arg = array();
        if (!empty($redirectTo)) {
            $arg['redirect'] = urlencode($redirectTo);
        } else if (!empty($_GET['redirect_to'])) {
            $arg['redirect'] = urlencode($_GET['redirect_to']);
        } else {
            $currentPageUrl = NextendSocialLogin::getCurrentPageURL();
            if ($currentPageUrl !== false) {
                $arg['redirect'] = urlencode($currentPageUrl);
            }
        }

        if ($trackerData !== false) {
            $arg['trackerdata']      = urlencode($trackerData);
            $arg['trackerdata_hash'] = urlencode(wp_hash($trackerData));

        }

        if ($customLabel) {
            $label = str_replace('{{providerName}}', $this->getLabel(), $customLabel);
        } else {
            $label                  = $this->settings->get('login_label');
            $useCustomRegisterLabel = NextendSocialLogin::$settings->get('custom_register_label');
            if ($labelType == 'register' && $useCustomRegisterLabel) {
                $label = $this->settings->get('register_label');
            }
        }

        switch ($buttonStyle) {
            case 'icon':

                $button = $this->getIconButton();
                break;
            default:

                $button = $this->getDefaultButton($label);
                break;
        }

        $defaultLinkAttributes = [
            "href"             => esc_url(add_query_arg($arg, $this->getLoginUrl())),
            "rel"              => "nofollow",
            "aria-label"       => esc_attr__($label),
            "data-plugin"      => "nsl",
            "data-action"      => "connect",
            "data-provider"    => esc_attr($this->getId()),
            "data-popupwidth"  => $this->getPopupWidth(),
            "data-popupheight" => $this->getPopupHeight()

        ];

        $customLinkAttributes = [];
        if (defined('ELEMENTOR_PRO_VERSION')) {
            /**
             * Fix: Elementor Pro - Page Transitions shouldn't affect our button link.
             *
             * @see NSLDEV-441
             */
            $customLinkAttributes['data-e-disable-page-transition'] = true;
        }
        $customLinkAttributes = apply_filters('nsl_connect_button_custom_attributes', $customLinkAttributes, $this);
        $allLinkAttributes    = array_merge($defaultLinkAttributes, $customLinkAttributes);

        $buttonLinkOpeningTagStart = '<a';
        $buttonLinkOpeningTagEnd   = '>';
        foreach ($allLinkAttributes as $attribute => $value) {
            $buttonLinkOpeningTagStart .= ' ' . $attribute . '="' . $value . '"';
        }
        $buttonLinkClosingTag = '</a>';


        return $buttonLinkOpeningTagStart . $buttonLinkOpeningTagEnd . $button . $buttonLinkClosingTag;
    }

    public function getLinkButton() {

        $args = array(
            'action' => 'link'
        );

        $redirect = NextendSocialLogin::getCurrentPageURL();
        if ($redirect !== false) {
            $args['redirect'] = urlencode($redirect);
        }

        $defaultLinkAttributes = [
            "href"             => esc_url(add_query_arg($args, $this->getLoginUrl())),
            "rel"              => "nofollow",
            "aria-label"       => esc_attr__($this->settings->get('link_label')),
            "style"            => "text-decoration:none;display:inline-block;box-shadow:none;",
            "data-plugin"      => "nsl",
            "data-action"      => "link",
            "data-provider"    => esc_attr($this->getId()),
            "data-popupwidth"  => $this->getPopupWidth(),
            "data-popupheight" => $this->getPopupHeight()

        ];

        $customLinkAttributes = [];
        if (defined('ELEMENTOR_PRO_VERSION')) {
            /**
             * Fix: Elementor Pro - Page Transitions shouldn't affect our button link.
             *
             * @see NSLDEV-441
             */
            $customLinkAttributes['data-e-disable-page-transition'] = true;
        }
        $customLinkAttributes = apply_filters('nsl_link_button_custom_attributes', $customLinkAttributes, $this);
        $allLinkAttributes    = array_merge($defaultLinkAttributes, $customLinkAttributes);

        $buttonLinkOpeningTagStart = '<a';
        $buttonLinkOpeningTagEnd   = '>';
        foreach ($allLinkAttributes as $attribute => $value) {
            $buttonLinkOpeningTagStart .= ' ' . $attribute . '="' . $value . '"';
        }
        $buttonLinkClosingTag = '</a>';


        return $buttonLinkOpeningTagStart . $buttonLinkOpeningTagEnd . $this->getDefaultButton($this->settings->get('link_label')) . $buttonLinkClosingTag;
    }

    public function getUnLinkButton() {

        $args = array(
            'action' => 'unlink'
        );

        $redirect = NextendSocialLogin::getCurrentPageURL();
        if ($redirect !== false) {
            $args['redirect'] = urlencode($redirect);
        }

        $defaultLinkAttributes = [
            "href"          => esc_url(add_query_arg($args, $this->getLoginUrl())),
            "rel"           => "nofollow",
            "aria-label"    => esc_attr__($this->settings->get('unlink_label')),
            "style"         => "text-decoration:none;display:inline-block;box-shadow:none;",
            "data-plugin"   => "nsl",
            "data-action"   => "unlink",
            "data-provider" => esc_attr($this->getId())

        ];

        $customLinkAttributes = [];
        if (defined('ELEMENTOR_PRO_VERSION')) {
            /**
             * Fix: Elementor Pro - Page Transitions shouldn't affect our button link.
             *
             * @see NSLDEV-441
             */
            $customLinkAttributes['data-e-disable-page-transition'] = true;
        }
        $customLinkAttributes = apply_filters('nsl_unlink_button_custom_attributes', $customLinkAttributes, $this);
        $allLinkAttributes    = array_merge($defaultLinkAttributes, $customLinkAttributes);

        $buttonLinkOpeningTagStart = '<a';
        $buttonLinkOpeningTagEnd   = '>';
        foreach ($allLinkAttributes as $attribute => $value) {
            $buttonLinkOpeningTagStart .= ' ' . $attribute . '="' . $value . '"';
        }
        $buttonLinkClosingTag = '</a>';


        return $buttonLinkOpeningTagStart . $buttonLinkOpeningTagEnd . $this->getDefaultButton($this->settings->get('unlink_label')) . $buttonLinkClosingTag;
    }

    public function redirectToLoginForm() {
        $this->redirectWithAuthenticationError(NextendSocialLogin::getLoginUrl());
    }

    /**
     * @param $url
     */
    public function redirectWithAuthenticationError($url) {
        self::redirect(__('Authentication error', 'nextend-facebook-connect'), NextendSocialLogin::enableNoticeForUrl($url));
    }

    /**
     * -Allows for logged in users to unlink their account from a provider, if it was linked, and
     * redirects to the last location.
     * -During linking process, store the action as link. After the linking process is finished,
     * delete this stored info and redirects to the last location.
     */
    public function liveConnectBefore() {

        if (is_user_logged_in() && $this->isCurrentUserConnected()) {

            if (isset($_GET['action']) && $_GET['action'] == 'unlink') {
                if ($this->unlinkUser()) {
                    Notices::addSuccess(__('Unlink successful.', 'nextend-facebook-connect'));
                } else {
                    Notices::addError(__('Unlink is not allowed!', 'nextend-facebook-connect'));
                }
            }

            $this->redirectToLastLocationOther(true);
            exit;
        }

        if (isset($_GET['action']) && $_GET['action'] == 'link') {
            Persistent::set($this->id . '_action', 'link');
        }

        if (is_user_logged_in() && Persistent::get($this->id . '_action') != 'link') {
            $this->deleteLoginPersistentData();

            $this->redirectToLastLocationOther();
            exit;
        }
    }

    /**
     * Store where the user logged in.
     */
    public function liveConnectRedirect() {
        if (!empty($_GET['trackerdata']) && !empty($_GET['trackerdata_hash'])) {
            if (wp_hash($_GET['trackerdata']) === $_GET['trackerdata_hash']) {
                Persistent::set('trackerdata', sanitize_text_field($_GET['trackerdata']));
            }
        }
        if (!empty($_GET['redirect'])) {
            Persistent::set('redirect', sanitize_url($_GET['redirect']));
        }
    }

    public function redirectToLastLocation($notice = false) {
        $url = $this->getLastLocationRedirectTo();

        if (Persistent::get($this->id . '_interim_login') == 1) {
            $this->deleteLoginPersistentData();
            $args['interim_login'] = 'nsl';

            $url = add_query_arg($args, NextendSocialLogin::getLoginUrl('login'));
            if ($notice) {
                $url = NextendSocialLogin::enableNoticeForUrl($url);
            }

            self::redirect(__('Authentication successful', 'nextend-facebook-connect'), $url);

            exit;
        }

        if ($notice) {
            $url = NextendSocialLogin::enableNoticeForUrl($url);
        }
        self::redirect(__('Authentication successful', 'nextend-facebook-connect'), $url);
    }

    /**
     * @param bool $notice
     */
    protected function redirectToLastLocationOther($notice = false) {
        $this->redirectToLastLocation($notice);
    }

    protected function validateRedirect($location) {
        $location = wp_sanitize_redirect($location);

        return wp_validate_redirect($location, apply_filters('wp_safe_redirect_fallback', admin_url(), 302));
    }

    public function hasFixedRedirect() {
        if (NextendSocialLogin::$WPLoginCurrentFlow == 'register') {
            $fixedRedirect = NextendSocialLogin::$settings->get('redirect_reg');
            $fixedRedirect = apply_filters($this->id . '_register_redirect_url', $fixedRedirect, $this);
            if (!empty($fixedRedirect)) {
                return true;
            }

        } else if (NextendSocialLogin::$WPLoginCurrentFlow == 'login') {
            $fixedRedirect = NextendSocialLogin::$settings->get('redirect');
            $fixedRedirect = apply_filters($this->id . '_login_redirect_url', $fixedRedirect, $this);
            if (!empty($fixedRedirect)) {
                return true;
            }
        }

        return false;
    }

    /**
     * If fixed redirect url is set, redirect to fixed redirect url.
     * If fixed redirect url is not set, but redirect is in the url redirect to the $_GET['redirect'].
     * If fixed redirect url is not set and there is no redirect in the url, redirects to the default redirect url if it
     * is set.
     * Else redirect to the site url.
     *
     * @return mixed|void
     */
    protected function getLastLocationRedirectTo() {
        $redirect_to           = '';
        $requested_redirect_to = '';
        $fixedRedirect         = '';

        if (NextendSocialLogin::$WPLoginCurrentFlow == 'register') {

            $fixedRedirect = NextendSocialLogin::$settings->get('redirect_reg');
            $fixedRedirect = apply_filters($this->id . '_register_redirect_url', $fixedRedirect, $this);

        } else if (NextendSocialLogin::$WPLoginCurrentFlow == 'login') {

            $fixedRedirect = NextendSocialLogin::$settings->get('redirect');
            $fixedRedirect = apply_filters($this->id . '_login_redirect_url', $fixedRedirect, $this);

        }

        if (!empty($fixedRedirect)) {
            $redirect_to = $fixedRedirect;
        } else {
            $requested_redirect_to = Persistent::get('redirect');

            if (!empty($requested_redirect_to)) {
                if (empty($requested_redirect_to) || !NextendSocialLogin::isAllowedRedirectUrl($requested_redirect_to)) {
                    if (!empty($_GET['redirect']) && NextendSocialLogin::isAllowedRedirectUrl($_GET['redirect'])) {
                        $requested_redirect_to = $_GET['redirect'];
                    } else {
                        $requested_redirect_to = '';
                    }
                }

                if (empty($requested_redirect_to)) {
                    $redirect_to = site_url();
                } else {
                    $redirect_to = $requested_redirect_to;
                }
                $redirect_to = wp_sanitize_redirect($redirect_to);
                $redirect_to = wp_validate_redirect($redirect_to, site_url());

                $redirect_to = $this->validateRedirect($redirect_to);
            } else if (!empty($_GET['redirect']) && NextendSocialLogin::isAllowedRedirectUrl($_GET['redirect'])) {
                $redirect_to = $_GET['redirect'];

                $redirect_to = wp_sanitize_redirect($redirect_to);
                $redirect_to = wp_validate_redirect($redirect_to, site_url());

                $redirect_to = $this->validateRedirect($redirect_to);
            }

            if (empty($redirect_to)) {
                $defaultRedirect = '';

                if (NextendSocialLogin::$WPLoginCurrentFlow == 'register') {
                    $defaultRedirect = NextendSocialLogin::$settings->get('default_redirect_reg');
                    $defaultRedirect = apply_filters($this->id . '_default_register_redirect_url', $defaultRedirect, $this);

                } else if (NextendSocialLogin::$WPLoginCurrentFlow == 'login') {
                    $defaultRedirect = NextendSocialLogin::$settings->get('default_redirect');
                    $defaultRedirect = apply_filters($this->id . '_default_[login_redirect_url', $defaultRedirect, $this);
                }

                if ((!empty($defaultRedirect))) {
                    $redirect_to = $defaultRedirect;
                }
            }

            $redirect_to = apply_filters('nsl_' . $this->getId() . 'default_last_location_redirect', $redirect_to, $requested_redirect_to);
        }

        if ($redirect_to == '' || $redirect_to == $this->getLoginUrl()) {
            $redirect_to = site_url();
        }

        Persistent::delete('redirect');

        return apply_filters('nsl_' . $this->getId() . 'last_location_redirect', $redirect_to, $requested_redirect_to);
    }

    /**
     * @param $user_id
     * @param $provider     NextendSocialProvider
     * @param $data         array
     */
    public function syncProfile($user_id, $provider, $data) {
    }

    /**
     * @return bool
     */
    public function hasSyncableProfileFields() {
        return true;
    }

    /**
     * Check if a logged in user with manage_options capability, want to verify their provider settings.
     *
     * @return bool
     */
    public function isTest() {
        if (is_user_logged_in() && current_user_can(NextendSocialLogin::getRequiredCapability())) {
            if (isset($_REQUEST['test'])) {
                Persistent::set('test', 1);

                return true;
            } else if (Persistent::get('test') == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Make the current provider in verified mode, and update the oauth_redirect_url.
     */
    public function testConnectGetUserProfile() {

        $this->deleteLoginPersistentData();

        $this->settings->update(array(
            'tested'             => 1,
            'oauth_redirect_url' => $this->getBaseRedirectUriForAppCreation()
        ));

        Notices::addSuccess(__('The test was successful', 'nextend-facebook-connect'));

        ?>
        <!doctype html>
        <html lang=en>
        <head>
            <meta charset=utf-8>
            <title><?php _e('The test was successful', 'nextend-facebook-connect'); ?></title>
            <?php
            NextendSocialLogin::nslDOMReady();
            ?>
            <script type="text/javascript">
                if (window.opener) {
                    window.opener.location.reload(true);
                    window.close();
                } else {
                    /**
                     * Cross-Origin-Opener-Policy blocked the access to the opener
                     */
                    if (typeof BroadcastChannel === "function") {
                        const nslVerifySettingsBroadCastChannel = new BroadcastChannel("nsl_verify_settings_broadcast_channel");
                        nslVerifySettingsBroadCastChannel.postMessage({action: 'reload'});
                        nslVerifySettingsBroadCastChannel.close();
                        window.close();
                    } else {
                        window._nslDOMReady(function () {
                            document.body.innerHTML = 'Close this window and refresh the parent window!';
                        });
                    }
                }
            </script>
        </head>
        <body>
        </body>
        </html>
        <?php
        exit;
    }

    public function deleteLoginPersistentData() {
        Persistent::delete($this->id . '_interim_login');
        Persistent::delete($this->id . '_display');
        Persistent::delete($this->id . '_action');
        Persistent::delete('test');
    }

    public function deleteTokenPersistentData() {
    }

    /**
     * @param $e Exception
     */
    protected function onError($e) {
        if (NextendSocialLogin::$settings->get('debug') == 1 || $this->isTest()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $e->getMessage() . "\n";
        } else {
            //@TODO we might need to make difference between user cancelled auth and error and redirect the user based on that.
            $url = $this->getLastLocationRedirectTo();
            ?>
            <!doctype html>
            <html lang=en>
            <head>
                <meta charset=utf-8>
                <title><?php echo __('Authentication failed', 'nextend-facebook-connect'); ?></title>
                <script type="text/javascript">
                    try {
                        if (window.opener !== null && window.opener !== window) {
                            var sameOrigin = true;
                            try {
                                var currentOrigin = window.location.protocol + '//' + window.location.hostname;
                                if (window.opener.location.href.substring(0, currentOrigin.length) !== currentOrigin) {
                                    sameOrigin = false;
                                }

                            } catch (e) {
                                /**
                                 * Blocked cross origin
                                 */
                                sameOrigin = false;
                            }
                            if (sameOrigin) {
                                window.close();
                            }
                        }
                    } catch (e) {
                    }
                    window.location = <?php echo wp_json_encode($url); ?>;
                </script>
                <meta http-equiv="refresh" content="0;<?php echo esc_attr($url); ?>">
            </head>
            <body>
            </body>
            </html>
            <?php
        }
        $this->deleteLoginPersistentData();
        exit;
    }

    protected function saveUserData($user_id, $key, $data) {
        update_user_meta($user_id, $this->id . '_' . $key, $data);
    }

    protected function getUserData($user_id, $key) {
        return get_user_meta($user_id, $this->id . '_' . $key, true);
    }

    /**
     * @param $user_id
     *
     * @return bool
     * @deprecated
     *
     */
    public function getAvatar($user_id) {

        return false;
    }

    /**
     * @return array
     */
    protected function getCurrentUserInfo() {
        return array();
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getAuthUserData($key) {
        return '';
    }


    /**
     * @param $key
     * @param $authOptions
     *                        Can be used for accessing the getAuthUserData() function outside of our normal flow.
     *
     * @return mixed
     */
    public abstract function getAuthUserDataByAuthOptions($key, $authOptions);

    /**
     * @param        $user_id
     * @param        $authOptions
     * @param string $action
     * @param bool   $shouldSyncProfile
     *                                 Can be used for triggering the Sync Data storing and Avatar updating functions
     *                                 outside of our normal flow.
     *
     * @return mixed
     */
    public abstract function triggerSync($user_id, $authOptions, $action = "login", $shouldSyncProfile = false);

    /**
     * @param $title
     * @param $url
     * Redirect the source of the popup window to a specified url.
     */
    public static function redirect($title, $url) {
        $url = NextendSocialLogin::maybeAddBypassCacheArgToUrl($url);
        ?>
        <!doctype html>
        <html lang=en>
        <head>
            <meta charset=utf-8>
            <title><?php echo $title; ?></title>
            <script type="text/javascript">
                try {
                    if (window.opener !== null && window.opener !== window) {
                        var sameOrigin = true;
                        try {
                            var currentOrigin = window.location.protocol + '//' + window.location.hostname;
                            if (window.opener.location.href.substring(0, currentOrigin.length) !== currentOrigin) {
                                sameOrigin = false;
                            }

                        } catch (e) {
                            /**
                             * Blocked cross origin
                             */
                            sameOrigin = false;
                        }
                        if (sameOrigin) {
                            window.opener.location = <?php echo wp_json_encode($url); ?>;
                            window.close();
                        }
                    }
                } catch (e) {
                }
                window.location = <?php echo wp_json_encode($url); ?>;
            </script>
            <meta http-equiv="refresh" content="0;<?php echo esc_attr($url); ?>">
        </head>
        <body>
        </body>
        </html>
        <?php
        exit;
    }

    public function getSyncFields() {
        return $this->sync_fields;
    }

    public function hasSyncFields() {
        return !empty($this->sync_fields);
    }

    public function validateSettings($newData, $postedData) {

        return $newData;
    }

    protected function needUpdateAvatar($user_id) {
        return apply_filters('nsl_avatar_store', NextendSocialLogin::$settings->get('avatar_store'), $user_id, $this);
    }

    protected function updateAvatar($user_id, $url) {
        do_action('nsl_update_avatar', $this, $user_id, $url);
    }

    public function exportPersonalData($userID) {
        $data = array();

        $socialID = $this->isUserConnected($userID);
        if ($socialID !== false) {
            $data[] = array(
                'name'  => $this->getLabel() . ' ' . __('Identifier', 'nextend-facebook-connect'),
                'value' => $socialID,
            );
        }

        $profilePicture = $this->getUserData($userID, 'profile_picture');
        if (!empty($profilePicture)) {
            $data[] = array(
                'name'  => $this->getLabel() . ' ' . __('Profile Picture'),
                'value' => $profilePicture,
            );
        }

        foreach ($this->getSyncFields() as $fieldName => $fieldData) {
            $meta_key = $this->settings->get('sync_fields/fields/' . $fieldName . '/meta_key');
            if (!empty($meta_key)) {
                $value = get_user_meta($userID, $meta_key, true);
                if (!empty($value)) {
                    $data[] = array(
                        'name'  => $this->getLabel() . ' ' . $fieldData['label'],
                        'value' => $value
                    );
                }
            }
        }

        $data = $this->extendExportedPersonalData($userID, $data);

        return $data;
    }

    /**
     * @param       $userID
     *
     * @param array $data
     *
     * @return array
     */
    public function extendExportedPersonalData($userID, $data) {
        return $data;
    }


    public function getSyncDataFieldDescription($fieldName) {
        return '';
    }

    /**
     * @param $user_id
     * Update social_users table with login date of the user.
     */
    public function logLoginDate($user_id) {
        /** @var $wpdb WPDB */ global $wpdb;
        $wpdb->update($wpdb->prefix . 'social_users', array('login_date' => current_time('mysql'),), array(
            'ID'   => $user_id,
            'type' => $this->dbID
        ), array(
            '%s',
            '%s'
        ));
    }

}