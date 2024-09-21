<?php

use NSL\Notices;
use NSL\Persistent\Persistent;

class NextendSocialProviderTwitter extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderTwitterClient|NextendSocialProviderTwitterV2Client */
    protected $client;

    protected $color = '#000000';

    protected $svg = '<svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)"><path d="M13.712 10.622 20.413 3h-1.587l-5.819 6.618L8.36 3H3l7.027 10.007L3 21h1.588l6.144-6.989L15.64 21H21l-7.288-10.378Zm-2.175 2.474-.712-.997L5.16 4.17H7.6l4.571 6.4.712.996 5.943 8.319h-2.439l-4.85-6.788Z" fill="#fff"/></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h24v24H0z"/></clipPath></defs></svg>';

    protected $svgLegacy = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path fill="#fff" d="M16.327 3.007A5.07 5.07 0 0 1 20.22 4.53a8.207 8.207 0 0 0 2.52-.84l.612-.324a4.78 4.78 0 0 1-1.597 2.268 2.356 2.356 0 0 1-.54.384v.012A9.545 9.545 0 0 0 24 5.287v.012a7.766 7.766 0 0 1-1.67 1.884l-.768.612a13.896 13.896 0 0 1-9.874 13.848c-2.269.635-4.655.73-6.967.276a16.56 16.56 0 0 1-2.895-.936 10.25 10.25 0 0 1-1.394-.708L0 20.023a8.44 8.44 0 0 0 1.573.06c.48-.084.96-.06 1.405-.156a10.127 10.127 0 0 0 2.956-1.056 5.41 5.41 0 0 0 1.333-.852 4.44 4.44 0 0 1-1.465-.264 4.9 4.9 0 0 1-3.12-3.108c.73.134 1.482.1 2.198-.096a3.457 3.457 0 0 1-1.609-.636A4.651 4.651 0 0 1 .953 9.763c.168.072.336.156.504.24.334.127.68.22 1.033.276.216.074.447.095.673.06H3.14c-.248-.288-.653-.468-.901-.78a4.91 4.91 0 0 1-1.105-4.404 5.62 5.62 0 0 1 .528-1.26c.008 0 .017.012.024.012.13.182.28.351.445.504a8.88 8.88 0 0 0 1.465 1.38 14.43 14.43 0 0 0 6.018 2.868 9.065 9.065 0 0 0 2.21.288 4.448 4.448 0 0 1 .025-2.28 4.771 4.771 0 0 1 2.786-3.252 5.9 5.9 0 0 1 1.093-.336l.6-.072z"/></svg>';

    const requiredApi1 = 'V1.1';
    const requiredApi2 = 'V2';

    private $v1RequiredFields = array(
        'api_version'     => 'API Version',
        'consumer_key'    => 'API Key',
        'consumer_secret' => 'API Key Secret'
    );

    private $v2RequiredFields = array(
        'api_version'   => 'API Version',
        'client_id'     => 'Client ID',
        'client_secret' => 'Client Secret'
    );

    protected $sync_fields = array(
        'created_at'      => array(
            'label' => 'Register date',
            'node'  => array(
                'me',
                'mev2'
            )
        ),
        'description'     => array(
            'label' => 'Bio',
            'node'  => array(
                'me',
                'mev2'
            )
        ),
        'entities'        => array(
            'label'       => 'Bio entities',
            'node'        => 'mev2',
            'description' => self::requiredApi2
        ),
        'lang'            => array(
            'label'       => 'Language',
            'node'        => 'me',
            'description' => self::requiredApi1
        ),
        'location'        => array(
            'label' => 'Location',
            'node'  => array(
                'me',
                'mev2'
            )
        ),
        'pinned_tweet_id' => array(
            'label'       => 'Pinned Tweet ID',
            'node'        => 'mev2',
            'description' => self::requiredApi2
        ),
        'profile_url'     => array(
            'label'       => 'Profile URL',
            'node'        => 'me',
            'description' => self::requiredApi1
        ),
        'protected'       => array(
            'label'       => 'Tweet protection status',
            'node'        => 'mev2',
            'description' => self::requiredApi2
        ),
        'public_metrics'  => array(
            'label'       => 'Public metrics',
            'node'        => 'mev2',
            'description' => self::requiredApi2
        ),
        'screen_name'     => array(
            'label'       => 'Screen name',
            'node'        => 'me',
            'description' => self::requiredApi1
        ),
        'url'             => array(
            'label' => 'Owned website',
            'node'  => array(
                'me',
                'mev2'
            )
        ),
        'username'        => array(
            'label'       => 'Screen name',
            'node'        => 'mev2',
            'description' => self::requiredApi2
        ),
        'verified'        => array(
            'label'       => 'Is verified',
            'node'        => 'mev2',
            'description' => self::requiredApi2
        )
    );

    public function __construct() {
        $this->id    = 'twitter';
        $this->label = 'X (formerly Twitter)';

        $this->path = dirname(__FILE__);

        parent::__construct(array(
            'consumer_key'       => '',
            'consumer_secret'    => '',
            'skin'               => 'x',
            'login_label'        => 'Continue with <b>X</b>',
            'register_label'     => 'Sign up with <b>X</b>',
            'link_label'         => 'Link account with <b>X</b>',
            'unlink_label'       => 'Unlink account from <b>X</b>',
            'profile_image_size' => 'normal',
            'api_version'        => '1.1',
            'client_id'          => '',
            'client_secret'      => ''
        ));


        if ($this->isV2Api()) {
            $this->requiredFields = $this->v2RequiredFields;

        } else {
            $this->authRedirectBehavior = 'default_redirect_but_app_has_restriction';

            $this->requiredFields = $this->v1RequiredFields;
        }
    }

    protected function forTranslation() {
        __('Continue with <b>X</b>', 'nextend-facebook-connect');
        __('Sign up with <b>X</b>', 'nextend-facebook-connect');
        __('Link account with <b>X</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>X</b>', 'nextend-facebook-connect');
    }


    public function getRawDefaultButton() {
        $skin = $this->settings->get('skin');
        switch ($skin) {
            case 'legacy':
                $color = '#4ab3f4';
                $svg   = $this->svgLegacy;
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
            case 'legacy':
                $color = '#4ab3f4';
                $svg   = $this->svgLegacy;
                break;
            default:
                $color = $this->color;
                $svg   = $this->svg;
        }

        return '<div class="nsl-button nsl-button-icon nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div></div>';
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
                case 'api_version':
                case 'consumer_key':
                case 'consumer_secret':
                case 'client_id':
                case 'client_secret':
                    if ($this->settings->get('api_version') !== $postedData['api_version']) {
                        if ($this->isV2Api($postedData['api_version'])) {
                            $this->requiredFields = $this->v2RequiredFields;
                        } else {
                            $this->requiredFields = $this->v1RequiredFields;
                        }
                    }

                    $newData[$key] = trim(sanitize_text_field($value));
                    if ($this->settings->get($key) !== $newData[$key]) {
                        $newData['tested'] = 0;
                    }

                    if (isset($this->requiredFields[$key]) && empty($newData[$key])) {
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
     * @return NextendSocialProviderTwitterClient
     */
    public function getClient() {
        if ($this->client === null) {

            if ($this->isV2Api()) {
                require_once dirname(__FILE__) . '/twitterv2-client.php';
                $this->client = new NextendSocialProviderTwitterV2Client($this->id);

                $this->client->setClientId($this->settings->get('client_id'));
                $this->client->setClientSecret($this->settings->get('client_secret'));
            } else {
                require_once dirname(__FILE__) . '/twitter-client.php';
                $this->client = new NextendSocialProviderTwitterClient($this->id, $this->settings->get('consumer_key'), $this->settings->get('consumer_secret'));
            }

            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());
        }

        return $this->client;
    }

    /**
     * @return array|mixed|object
     * @throws Exception
     */
    protected function getCurrentUserInfo() {
        if ($this->isV2Api()) {
            return $this->getCurrentUserInfoV2();
        } else {
            return $this->getCurrentUserInfoV1();
        }
    }

    /**
     * getCurrentUserInfo() for API version 1.1
     *
     * @return array|mixed|object
     * @throws Exception
     */
    private function getCurrentUserInfoV1() {
        $response = $this->getClient()
                         ->get('account/verify_credentials', array(
                             'include_email'    => 'true',
                             'include_entities' => 'false',
                             'skip_status'      => 'true'
                         ));

        if (isset($response['id']) && isset($response['id_str'])) {
            // On 32bit and Windows server, we must copy id_str to id as the id int representation won't be OK
            $response['id'] = $response['id_str'];
        }

        return $response;
    }

    /**
     * For API version 2
     *
     * @return array
     * @throws Exception
     */
    private function getCurrentUserInfoV2() {
        $fields          = array(
            'id',
            'name',
            'profile_image_url',
        );
        $extra_me_fields = apply_filters('nsl_twitter_sync_node_fields', array(), 'mev2');

        $response = $this->getClient()
                         ->get('users/me?user.fields=' . implode(',', array_merge($fields, $extra_me_fields)));

        if (!empty($response['data'])) {
            return $response['data'];
        }

        throw new NSLSanitizedRequestErrorMessageException(sprintf(__('Unexpected response: %s', 'nextend-facebook-connect'), json_encode($response)));
    }


    public function getMe() {
        return $this->authUserData;
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getAuthUserData($key) {
        if ($this->isV2Api()) {
            return $this->getAuthUserDataV2($key);
        } else {
            return $this->getAuthUserDataV1($key);
        }
    }

    /**
     * getAuthUserData() for API version 1.1
     *
     * @param $key
     *
     * @return mixed|string
     */
    private function getAuthUserDataV1($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return $this->authUserData['name'];
            case 'username':
                return $this->authUserData['screen_name'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                $profile_image_size = $this->settings->get('profile_image_size');
                $profile_image      = $this->authUserData['profile_image_url_https'];
                $avatar_url         = '';
                if (!empty($profile_image)) {
                    switch ($profile_image_size) {
                        case 'mini':
                            $avatar_url = str_replace('_normal.', '_' . $profile_image_size . '.', $profile_image);
                            break;
                        case 'normal':
                            $avatar_url = $profile_image;
                            break;
                        case 'bigger':
                            $avatar_url = str_replace('_normal.', '_' . $profile_image_size . '.', $profile_image);
                            break;
                        case 'original':
                            $avatar_url = str_replace('_normal.', '.', $profile_image);
                            break;

                    }
                }

                return $avatar_url;
        }

        return parent::getAuthUserData($key);
    }

    /**
     * getAuthUserData() for API version 2
     *
     * @param $key
     *
     * @return mixed|string
     */
    private function getAuthUserDataV2($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return '';
            case 'name':
                return $this->authUserData['name'];
            case 'username':
                return $this->authUserData['username'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                $profile_image_size = $this->settings->get('profile_image_size');
                $profile_image      = $this->authUserData['profile_image_url'];
                $avatar_url         = '';
                if (!empty($profile_image)) {
                    switch ($profile_image_size) {
                        case 'mini':
                            $avatar_url = str_replace('_normal.', '_' . $profile_image_size . '.', $profile_image);
                            break;
                        case 'normal':
                            $avatar_url = $profile_image;
                            break;
                        case 'bigger':
                            $avatar_url = str_replace('_normal.', '_' . $profile_image_size . '.', $profile_image);
                            break;
                        case 'original':
                            $avatar_url = str_replace('_normal.', '.', $profile_image);
                            break;

                    }
                }

                return $avatar_url;
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

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();

        if ($this->client !== null) {
            $this->client->deleteLoginPersistentData();
        }
    }

    public function getAvatar($user_id) {

        if (!$this->isUserConnected($user_id)) {
            return false;
        }

        $picture = $this->getUserData($user_id, 'profile_picture');
        if (!$picture || $picture == '') {
            return false;
        }

        return $picture;
    }

    /**
     * @param $api_version
     *
     * @return bool
     */
    public function isV2Api($api_version = false) {
        $api_version = $api_version ? $api_version : $this->settings->get('api_version');

        return $api_version === '2';
    }

    public function deleteTokenPersistentData() {
        parent::deleteTokenPersistentData();
        Persistent::delete($this->id . '_code_verifier');
    }

    public function getSyncDataFieldDescription($fieldName) {
        if (isset($this->sync_fields[$fieldName]['description'])) {
            return sprintf(__('Required API: %1$s', 'nextend-facebook-connect'), $this->sync_fields[$fieldName]['description']);
        }

        return parent::getSyncDataFieldDescription($fieldName);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderTwitter);