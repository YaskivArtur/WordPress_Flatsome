<?php

use NSL\Notices;

class NextendSocialProviderFacebook extends NextendSocialProviderOAuth {

    protected $dbID = 'fb';

    /** @var NextendSocialProviderFacebookClient */
    protected $client;

    protected $color = '#1877F2';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1365.3 1365.3" height="24" width="24"><path d="M1365.3 682.7A682.7 682.7 0 10576 1357V880H402.7V682.7H576V532.3c0-171.1 102-265.6 257.9-265.6 74.6 0 152.8 13.3 152.8 13.3v168h-86.1c-84.8 0-111.3 52.6-111.3 106.6v128h189.4L948.4 880h-159v477a682.8 682.8 0 00576-674.3" fill="#fff"/></svg>';

    protected $svgBlue = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1365.3 1365.3" height="24" width="24"><path d="M1365.3 682.7A682.7 682.7 0 10576 1357V880H402.7V682.7H576V532.3c0-171.1 102-265.6 257.9-265.6 74.6 0 152.8 13.3 152.8 13.3v168h-86.1c-84.8 0-111.3 52.6-111.3 106.6v128h189.4L948.4 880h-159v477a682.8 682.8 0 00576-674.3" fill="#1877f2"/><path d="M948.4 880l30.3-197.3H789.3v-128c0-54 26.5-106.7 111.3-106.7h86V280s-78-13.3-152.7-13.3c-156 0-257.9 94.5-257.9 265.6v150.4H402.7V880H576v477a687.8 687.8 0 00213.3 0V880h159.1" fill="#fff"/></svg>';

    protected $svgBlack = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1365.3 1365.3" height="24" width="24"><path d="M1365.3 682.7A682.7 682.7 0 10576 1357V880H402.7V682.7H576V532.3c0-171.1 102-265.6 257.9-265.6 74.6 0 152.8 13.3 152.8 13.3v168h-86.1c-84.8 0-111.3 52.6-111.3 106.6v128h189.4L948.4 880h-159v477a682.8 682.8 0 00576-674.3" fill="#100f0d"/><path d="M948.4 880l30.3-197.3H789.3v-128c0-54 26.5-106.7 111.3-106.7h86V280s-78-13.3-152.7-13.3c-156 0-257.9 94.5-257.9 265.6v150.4H402.7V880H576v477a687.8 687.8 0 00213.3 0V880h159.1" fill="#fff"/></svg>';

    protected $popupWidth = 600;

    protected $popupHeight = 679;

    protected $sync_fields = array(
        'age_range' => array(
            'label' => 'Age range',
            'node'  => 'me',
            'scope' => 'user_age_range'
        ),
        'birthday'  => array(
            'label' => 'Birthday',
            'node'  => 'me',
            'scope' => 'user_birthday'
        ),
        'link'      => array(
            'label' => 'Profile link',
            'node'  => 'me',
            'scope' => 'user_link'
        ),
        'hometown'  => array(
            'label' => 'Hometown',
            'node'  => 'me',
            'scope' => 'user_hometown'
        ),
        'location'  => array(
            'label' => 'Location',
            'node'  => 'me',
            'scope' => 'user_location'
        ),
        'gender'    => array(
            'label' => 'Gender',
            'node'  => 'me',
            'scope' => 'user_gender'
        ),
        'quotes'    => array(
            'label' => 'Quotes',
            'node'  => 'me',
            'scope' => 'user_likes'
        )
    );

    public function __construct() {
        $this->id    = 'facebook';
        $this->label = 'Facebook';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'appid'  => 'App ID',
            'secret' => 'App Secret'
        );

        add_filter('nsl_finalize_settings_' . $this->optionKey, array(
            $this,
            'finalizeSettings'
        ));

        parent::__construct(array(
            'appid'              => '',
            'secret'             => '',
            'skin'               => 'dark',
            'login_label'        => 'Continue with <b>Facebook</b>',
            'register_label'     => 'Sign up with <b>Facebook</b>',
            'link_label'         => 'Link account with <b>Facebook</b>',
            'unlink_label'       => 'Unlink account from <b>Facebook</b>',
            'profile_image_size' => 'default'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Facebook</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Facebook</b>', 'nextend-facebook-connect');
        __('Link account with <b>Facebook</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Facebook</b>', 'nextend-facebook-connect');
    }

    public function getRawDefaultButton() {
        $skin = $this->settings->get('skin');
        switch ($skin) {
            case 'light':
                $color = '#fff';
                $svg   = $this->svgBlue;
                break;
            case 'black':
                $color = '#000';
                $svg   = $this->svg;
                break;
            case 'white':
                $color = '#fff';
                $svg   = $this->svgBlack;
                break;
            default:
                $color = $this->color;
                $svg   = $this->svg;
        }

        return '<div class="nsl-button nsl-button-default nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div><div class="nsl-button-label-container">{{label}}</div></div>';
    }

    public function getRawIconButton() {
        $skin = $this->settings->get('skin');
        switch ($skin) {
            case 'light':
                $color = '#fff';
                $svg   = $this->svgBlue;
                break;
            case 'black':
                $color = '#000';
                $svg   = $this->svg;
                break;
            case 'white':
                $color = '#fff';
                $svg   = $this->svgBlack;
                break;
            default:
                $color = $this->color;
                $svg   = $this->svg;
        }

        return '<div class="nsl-button nsl-button-icon nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div></div>';
    }

    public function finalizeSettings($settings) {

        if (defined('NEXTEND_FB_APP_ID')) {
            $settings['appid'] = NEXTEND_FB_APP_ID;
        }
        if (defined('NEXTEND_FB_APP_SECRET')) {
            $settings['secret'] = NEXTEND_FB_APP_SECRET;
        }

        return $settings;
    }

    /**
     * @return NextendSocialProviderFacebookClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/facebook-client.php';

            $this->client = new NextendSocialProviderFacebookClient($this->id, $this->isTest());

            $this->client->setClientId($this->settings->get('appid'));
            $this->client->setClientSecret($this->settings->get('secret'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());
        }

        return $this->client;
    }

    public function validateSettings($newData, $postedData) {
        $newData = parent::validateSettings($newData, $postedData);

        foreach ($postedData as $key => $value) {

            switch ($key) {
                case 'tested':
                    if ($postedData[$key] == '1' && (!isset($newData['tested']) || $newData['tested'] != '0')) {
                        $newData['tested'] = 1;
                    } else {
                        $newData['tested'] = 0;
                    }
                    break;
                case 'appid':
                case 'secret':
                    $newData[$key] = trim(sanitize_text_field($value));
                    if ($this->settings->get($key) !== $newData[$key]) {
                        $newData['tested'] = 0;
                    }

                    if (empty($newData[$key])) {
                        Notices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->requiredFields[$key], $this->requiredFields[$key]));
                    }
                    break;
                case 'skin':
                case 'profile_image_size':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        return $newData;
    }

    /**
     * @param $accessTokenData
     *
     * @return string
     * @throws Exception
     */
    protected function requestLongLivedToken($accessTokenData) {
        $client = $this->getClient();
        if (!$client->isAccessTokenLongLived()) {

            return $client->requestLongLivedAccessToken();
        }

        return $accessTokenData;
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    protected function getCurrentUserInfo() {
        $profile_image_size = $this->settings->get('profile_image_size');
        $profile_image_size !== "default" ? $pictureSize = 'picture.width(' . $profile_image_size . ')' : $pictureSize = 'picture.type(large)';

        $fields       = array(
            'id',
            'name',
            'email',
            'first_name',
            'last_name',
            $pictureSize
        );
        $extra_fields = apply_filters('nsl_facebook_sync_node_fields', array(), 'me');

        return $this->getClient()
                    ->get('/me?fields=' . implode(',', array_merge($fields, $extra_fields)));
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {

        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return $this->authUserData['name'];
            case 'first_name':
                return $this->authUserData['first_name'];
            case 'last_name':
                return $this->authUserData['last_name'];
            case 'picture':
                $profilePicture = $this->authUserData['picture'];
                if (!empty($profilePicture) && !empty($profilePicture['data'])) {
                    if (isset($profilePicture['data']['is_silhouette']) && !$profilePicture['data']['is_silhouette']) {
                        return $profilePicture['data']['url'];
                    }
                }

                return '';
        }

        return parent::getAuthUserData($key);
    }

    public function syncProfile($user_id, $provider, $data) {
        if ($this->needUpdateAvatar($user_id)) {

            if ($this->getAuthUserData('picture')) {
                $this->updateAvatar($user_id, $this->getAuthUserData('picture'));
            }
        }

        if (!empty($data['access_token_data'])) {
            $this->storeAccessToken($user_id, $data['access_token_data']);
        }
    }

    protected function saveUserData($user_id, $key, $data) {
        switch ($key) {
            case 'access_token':
                update_user_meta($user_id, 'fb_user_access_token', $data);
                break;
            default:
                parent::saveUserData($user_id, $key, $data);
                break;
        }
    }

    protected function getUserData($user_id, $key) {
        switch ($key) {
            case 'access_token':
                return get_user_meta($user_id, 'fb_user_access_token', true);
                break;
        }

        return parent::getUserData($user_id, $key);
    }

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();

        if ($this->client !== null) {
            $this->client->deleteLoginPersistentData();
        }
    }

    public function getSyncDataFieldDescription($fieldName) {
        if (isset($this->sync_fields[$fieldName]['scope'])) {
            return sprintf(__('Required scope: %1$s', 'nextend-facebook-connect'), $this->sync_fields[$fieldName]['scope']);
        }

        return parent::getSyncDataFieldDescription($fieldName);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderFacebook);