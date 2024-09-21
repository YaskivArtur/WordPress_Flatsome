<?php

use NSL\Persistent\Persistent;

require_once dirname(__FILE__) . '/provider.php';

abstract class NextendSocialProviderOpenId extends NextendSocialProvider {

    private $openIdClaimedId = false;

    /**
     * @return bool
     */
    public function checkAuthRedirectUrl() {
        /**
         * In case of the Open ID flow the redirect url change doesn't matter.
         */
        return true;
    }

    /**
     * Returns the url that we handle the OpenID flow over.
     *
     * @return string
     */
    public function getRedirectUriForAuthFlow() {

        $args = array('loginSocial' => $this->id);

        return add_query_arg($args, NextendSocialLogin::getLoginUrl());
    }


    public function getBaseRedirectUriForAppCreation() {

        $redirectUri = $this->getRedirectUriForAuthFlow();

        return $redirectUri;
    }

    /**
     * Handles the Open ID specific Authentication flow
     */
    public function doAuthProtocolSpecificFlow() {
        $client = $this->getClient();

        $openIdClaimedId = $this->getAnonymousOpenIdClaimedId();

        $client->checkError();

        do_action($this->id . '_login_action_redirect', $this);

        /**
         * Check if we have any OpenID authentication data
         * If there is no OpenID authentication data, it redirects to the Authorization Url.
         */
        if (!$openIdClaimedId && !$client->hasAuthenticateData()) {

            header('LOCATION: ' . $client->createAuthUrl());
            exit;

        } else {
            if (!$openIdClaimedId) {
                $openIdClaimedId = $client->authenticate();
                if ($openIdClaimedId) {
                    $this->setAnonymousOpenIdClaimedId($openIdClaimedId);
                }
            }

            $this->handlePopupRedirectAfterAuthentication();

            /**
             * Retrieves the userinfo trough the REST API and connect with the provider.
             * Redirects to the last location.
             */
            $this->authUserData = $this->getCurrentUserInfo();

            do_action($this->id . '_login_action_get_user_profile', array());
        }
    }

    /**
     * @param $openIdClaimedId
     * Store the Claimed Identifier coming from an OpenID provider.
     */
    protected function setAnonymousOpenIdClaimedId($openIdClaimedId) {
        Persistent::set($this->id . '_openid_claimed_id', $openIdClaimedId);
    }

    /**
     * @return bool|string
     * Get an Claimed Identifier of an OpenID provider.
     */
    protected function getAnonymousOpenIdClaimedId() {
        return Persistent::get($this->id . '_openid_claimed_id');
    }

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();
        Persistent::delete($this->id . '_openid_claimed_id');
    }

    public function deleteTokenPersistentData() {
        Persistent::delete($this->id . '_openid_claimed_id');
    }

    /**
     * @param $openIdClaimedId
     *                        Intended use: Developers can use this for setting an openIdClaimedId outside of
     *                        our normal flow.
     */
    protected function setOpenIdClaimedId($openIdClaimedId) {
        $this->openIdClaimedId = $openIdClaimedId;
    }

    /**
     * @return mixed
     *              We should use this function only to get a fallback openIdClaimedId in getCurrentUserInfo()
     *              function, when a developer tries to get data from the API with the getAuthUserDataByAuthOptions()
     *              function.
     */
    protected function getOpenIdClaimedId() {
        return $this->openIdClaimedId;
    }

    public function getAuthUserDataByAuthOptions($key, $authOptions) {
        if (empty($this->authUserData)) {
            if (!empty($authOptions['openid_claimed_id'])) {
                $this->setOpenIdClaimedId($authOptions['openid_claimed_id']);
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
     * @param ['openid_claimed_id'=>String]  $authOptions
     * @param String  $action - login/link/register
     * @param boolean $shouldSyncProfile
     *                        Rest API specific integrations might need this function to store the sync data fields,
     *                        the access token, and to update the avatar on login.
     */
    public function triggerSync($user_id, $authOptions, $action = "login", $shouldSyncProfile = false) {
        if (!empty($authOptions['openid_claimed_id'])) {
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

}