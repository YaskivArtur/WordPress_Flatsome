<?php

use NSL\Persistent\Persistent;

require_once dirname(__FILE__) . '/provider.php';

abstract class NextendSocialProviderOAuth extends NextendSocialProvider {

    /**
     * NextendSocialProviderOAuth constructor.
     *
     * @param $defaultSettings
     */
    public function __construct($defaultSettings) {
        parent::__construct($defaultSettings);

        add_action('rest_api_init', array(
            $this,
            'registerRedirectRESTRoute'
        ));

    }

    /**
     * Returns the url where the Provider App should redirect during the OAuth flow.
     *
     * @return string
     */
    public function getRedirectUriForAuthFlow() {
        if ($this->authRedirectBehavior === 'rest_redirect') {

            return rest_url('/nextend-social-login/v1/' . $this->id . '/redirect_uri');
        }

        $args = array('loginSocial' => $this->id);

        return add_query_arg($args, NextendSocialLogin::getLoginUrl());
    }

    /*
     * This function has been deprecated in 3.1.5!
     * Use the getRedirectUriForAuthFlow() method instead!
     */
    public function getRedirectUriForOAuthFlow() {
        _deprecated_function(__FUNCTION__, '3.1.5', 'getRedirectUriForAuthFlow()');

        return $this->getRedirectUriForAuthFlow();
    }

    /*
     * This function has been deprecated in 3.1.5!
     * Use the checkAuthRedirectUrl() method instead!
     */
    public function checkOauthRedirectUrl() {
        _deprecated_function(__FUNCTION__, '3.1.5', 'checkAuthRedirectUrl()');

        return $this->checkAuthRedirectUrl();
    }


    /*
     * This function has been deprecated in 3.1.5!
     * Use the updateAuthRedirectUrl() method instead!
     */
    public function updateOauthRedirectUrl() {
        _deprecated_function(__FUNCTION__, '3.1.5', 'updateAuthRedirectUrl()');

        return $this->updateAuthRedirectUrl();
    }

    /**
     * Returns a single redirect URL that:
     * - we us as default redirect uri suggestion in the Getting Started and Fixed redirect uri pages.
     * - we store to detect the OAuth redirect url changes
     *
     * @return string
     */
    public function getBaseRedirectUriForAppCreation() {

        $redirectUri = $this->getRedirectUriForAuthFlow();

        if ($this->authRedirectBehavior === 'default_redirect_but_app_has_restriction') {
            $parts = explode('?', $redirectUri);

            return $parts[0];
        }

        return $redirectUri;
    }

    /**
     * Check if the current redirect url of the provider matches with the one that we stored when the provider was
     * configured. Returns "false" if they are different, so a new URL needs to be added to the App.
     *
     * @return bool
     */
    public function checkAuthRedirectUrl() {
        $oauth_redirect_url = $this->settings->get('oauth_redirect_url');

        $redirectUrls = $this->getAllRedirectUrisForAppCreation();


        if (is_array($redirectUrls)) {
            /**
             * Before 3.1.2 we saved the default redirect url of the provider ( e.g.:
             * https://example.com/wp-login.php?loginSocial=twitter ) for the OAuth check. However, some providers ( e.g.
             * Microsoft ) can use the REST API URL as redirect url. In these cases if the URL of the OAuth page was changed,
             * we gave a false warning for such providers.
             *
             * We shouldn't throw warnings for users who have the redirect uri stored still with the old format.
             * For this reason we need to push the legacy redirect url into the $redirectUrls array, too!
             */
            $legacyRedirectURL = add_query_arg(array('loginSocial' => $this->getId()), NextendSocialLogin::getLoginUrl());
            if (!in_array($legacyRedirectURL, $redirectUrls)) {
                $redirectUrls[] = $legacyRedirectURL;
            }


            if (in_array($oauth_redirect_url, $redirectUrls)) {
                return true;
            }
        }

        return false;
    }

    public function doAuthProtocolSpecificFlow() {
        $client = $this->getClient();

        $accessTokenData = $this->getAnonymousAccessToken();

        $client->checkError();

        do_action($this->id . '_login_action_redirect', $this);

        /**
         * Check if we have an accessToken and a code.
         * If there is no access token and code it redirects to the Authorization Url.
         */
        if (!$accessTokenData && !$client->hasAuthenticateData()) {

            header('LOCATION: ' . $client->createAuthUrl());
            exit;

        } else {

            /**
             * If the code is OK but there is no access token, authentication is necessary.
             */
            if (!$accessTokenData) {

                $accessTokenData = $client->authenticate();

                $accessTokenData = $this->requestLongLivedToken($accessTokenData);

                /**
                 * store the access token
                 */
                $this->setAnonymousAccessToken($accessTokenData);
            } else {
                $client->setAccessTokenData($accessTokenData);
            }

            $data = array(
                "access_token_data" => $accessTokenData
            );

            $this->handlePopupRedirectAfterAuthentication();

            /**
             * Retrieves the userinfo trough the REST API and connect with the provider.
             * Redirects to the last location.
             */
            $this->authUserData = $this->getCurrentUserInfo();

            do_action($this->id . '_login_action_get_user_profile', $data);
        }
    }

    public function findUserByAccessToken($access_token) {
        return $this->getUserIDByProviderIdentifier($this->findSocialIDByAccessToken($access_token));
    }

    public function findSocialIDByAccessToken($access_token) {
        $client = $this->getClient();
        $client->setAccessTokenData($access_token);
        $this->authUserData = $this->getCurrentUserInfo();

        return $this->getAuthUserData('id');
    }

    public function getAuthUserDataByAuthOptions($key, $authOptions) {
        if (empty($this->authUserData)) {
            if (!empty($authOptions['access_token_data'])) {
                $client = $this->getClient();
                $client->setAccessTokenData($authOptions['access_token_data']);
                $this->authUserData = $this->getCurrentUserInfo();
            }
        }

        if (!empty($this->authUserData)) {
            return $this->getAuthUserData($key);
        }


        return '';
    }

    /**
     * @param integer $user_id
     * @param ['access_token_data'=>String]  $authOptions
     * @param String  $action - login/link/register
     * @param boolean $shouldSyncProfile
     *                        Rest API specific integrations might need this function to store the sync data fields,
     *                        the access token, and to update the avatar on login.
     */
    public function triggerSync($user_id, $authOptions, $action = "login", $shouldSyncProfile = false) {
        if (!empty($authOptions['access_token_data'])) {
            switch ($action) {
                case "login":
                    do_action('nsl_' . $this->getId() . '_login', $user_id, $this, $authOptions);
                    break;
                case "link":
                    do_action('nsl_' . $this->getId() . '_link_user', $user_id, $this->getId());
                    break;
                case "register":
                    do_action('nsl_' . $this->getId() . '_register_new_user', $user_id, $this);
                    break;
            }

            if ($shouldSyncProfile) {
                $this->syncProfile($user_id, $this, $authOptions);
            }
        }

    }

    /**
     * @param $accessToken
     * Store the accessToken data.
     */
    protected function setAnonymousAccessToken($accessToken) {
        Persistent::set($this->id . '_at', $accessToken);
    }

    protected function getAnonymousAccessToken() {
        return Persistent::get($this->id . '_at');
    }

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();

        Persistent::delete($this->id . '_at');
    }

    public function getAccessToken($user_id) {
        return $this->getUserData($user_id, 'access_token');
    }

    protected function requestLongLivedToken($accessTokenData) {
        return $accessTokenData;
    }

    protected function storeAccessToken($userID, $accessToken) {
        if (NextendSocialLogin::$settings->get('store_access_token') == 1) {
            $this->saveUserData($userID, 'access_token', $accessToken);
        }
    }

    public function registerRedirectRESTRoute() {
        if ($this->authRedirectBehavior === 'rest_redirect') {
            register_rest_route('nextend-social-login/v1', $this->id . '/redirect_uri', array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array(
                    $this,
                    'redirectToProviderEndpointWithStateAndCode'
                ),
                'args'                => array(
                    'state' => array(
                        'required' => true,
                    ),
                    'code'  => array(
                        'required' => true,
                    )
                ),
                'permission_callback' => '__return_true',
            ));
        }
    }

    /**
     * @param WP_REST_Request $request Full details about the request.
     *
     * Registers a REST API endpoints for a provider. This endpoint handles the redirect to the login endpoint of the
     * currently used provider. The state and code GET parameters will be added to the login URL, so we can imitate as
     * if the provider would already returned the state and code parameters to the original login url.
     *
     * @return WP_Error|WP_REST_Response
     */
    public function redirectToProviderEndpointWithStateAndCode($request) {
        $params       = $request->get_params();
        $errorMessage = '';

        if (!empty($params['state']) && !empty($params['code'])) {

            $provider = NextendSocialLogin::$allowedProviders[$this->id];

            try {
                $providerEndpoint = $provider->getLoginUrl();

                if (defined('WPML_PLUGIN_BASENAME')) {
                    $providerEndpoint = $provider->getTranslatedLoginURLForRestRedirect();
                }

                $providerEndpointWithStateAndCode = add_query_arg(array(
                    'state' => $params['state'],
                    'code'  => $params['code']
                ), $providerEndpoint);
                wp_safe_redirect($providerEndpointWithStateAndCode);
                exit;

            } catch (Exception $e) {
                $errorMessage = $e->getMessage();
            }
        } else {
            if (empty($params['state']) && empty($params['code'])) {
                $errorMessage = 'The code and state parameters are empty!';
            } else if (empty($params['state'])) {
                $errorMessage = 'The state parameter is empty!';
            } else {
                $errorMessage = 'The code parameter is empty!';
            }
        }

        return new WP_Error('error', $errorMessage);
    }

    /**
     * Generates a single translated login URL where the REST /redirect_uri endpoint of the currently used provider
     * should redirect to instead of the original login url.
     *
     * @return string
     */
    public function getTranslatedLoginURLForRestRedirect() {
        $originalLoginUrl = $this->getLoginUrl();

        /**
         * We should attempt to generate translated login URLs only if WPML is active and there is a language code defined.
         */
        if (defined('WPML_PLUGIN_BASENAME') && defined('ICL_LANGUAGE_CODE')) {

            global $sitepress;

            $languageCode = ICL_LANGUAGE_CODE;


            if ($sitepress && method_exists($sitepress, 'get_active_languages') && $languageCode) {

                $WPML_active_languages = $sitepress->get_active_languages();

                if (count($WPML_active_languages) > 1) {
                    /**
                     * Fix:
                     * When WPML has the language URL format set to "Language name added as a parameter",
                     * we can not pass that parameter in the Authorization request in some cases ( e.g.: Microsoft ).
                     * In these cases the user will end up redirected to the redirect URL without language parameter,
                     * so after the login we won't be able to redirect them to registration flow page of the corresponding language.
                     * In these cases we need to use the language code according to the url where we should redirect after the login.
                     */
                    $WPML_language_url_format = false;
                    if (method_exists($sitepress, 'get_setting')) {
                        $WPML_language_url_format = $sitepress->get_setting('language_negotiation_type');
                    }
                    if ($WPML_language_url_format && $WPML_language_url_format == 3) {
                        $persistentRedirect = Persistent::get('redirect');
                        if ($persistentRedirect) {
                            $persistentRedirectQueryParams = array();
                            $persistentRedirectQueryString = parse_url($persistentRedirect, PHP_URL_QUERY);
                            parse_str($persistentRedirectQueryString, $persistentRedirectQueryParams);
                            if (isset($persistentRedirectQueryParams['lang']) && !empty($persistentRedirectQueryParams['lang'])) {
                                $languageParam = sanitize_text_field($persistentRedirectQueryParams['lang']);
                                if (in_array($languageParam, array_keys($WPML_active_languages))) {
                                    /**
                                     * The language code that we got from the persistent redirect url is a valid language code for WPML,
                                     * so we can use this code.
                                     */
                                    $languageCode = $languageParam;
                                }
                            }
                        }
                    }


                    $args      = array('loginSocial' => $this->getId());
                    $proxyPage = NextendSocialLogin::getProxyPage();

                    if ($proxyPage) {
                        //OAuth flow handled over OAuth redirect uri proxy page
                        $convertedURL = get_permalink(apply_filters('wpml_object_id', $proxyPage, 'page', false, $languageCode));
                        if ($convertedURL) {
                            $convertedURL = add_query_arg($args, $convertedURL);

                            return $convertedURL;
                        }

                    } else {
                        //OAuth flow handled over wp-login.php

                        if ($WPML_language_url_format && $WPML_language_url_format == 3 && (!class_exists('\WPML\UrlHandling\WPLoginUrlConverter') || (class_exists('\WPML\UrlHandling\WPLoginUrlConverter') && (!get_option(\WPML\UrlHandling\WPLoginUrlConverter::SETTINGS_KEY, false))))) {
                            /**
                             * We need to display the original redirect url when the
                             * Language URL format is set to "Language name added as a parameter and:
                             * -when the WPLoginUrlConverter class doesn't exists, since that case it is an old WPML version that can not translate the /wp-login.php page
                             * -if "Login and registration pages - Allow translating the login and registration pages" is disabled
                             */
                            return $originalLoginUrl;
                        } else {
                            global $wpml_url_converter;
                            /**
                             * When the language URL format is set to "Different languages in directories" or "A different domain per language", then the Redirect URI will be different for each languages
                             * Also when the language URL format is set to "Language name added as a parameter" and the "Login and registration pages - Allow translating the login and registration pages" setting is enabled, the urls will be different.
                             */
                            if ($wpml_url_converter && method_exists($wpml_url_converter, 'convert_url')) {

                                $convertedURL = $wpml_url_converter->convert_url(site_url('wp-login.php'), $languageCode);

                                $convertedURL = add_query_arg($args, $convertedURL);


                                return $convertedURL;

                            }
                        }
                    }
                }
            }
        }

        return $originalLoginUrl;
    }


    /**
     * @param       $userID
     * @param array $data
     *
     * @return array
     */
    public function extendExportedPersonalData($userID, $data) {
        $accessToken = $this->getAccessToken($userID);
        if (!empty($accessToken)) {
            $data[] = array(
                'name'  => $this->getLabel() . ' ' . __('Access token', 'nextend-facebook-connect'),
                'value' => $accessToken,
            );
        }

        return $data;
    }


    public function deleteTokenPersistentData() {
        Persistent::delete($this->id . '_at');
        Persistent::delete($this->id . '_state');
    }
}