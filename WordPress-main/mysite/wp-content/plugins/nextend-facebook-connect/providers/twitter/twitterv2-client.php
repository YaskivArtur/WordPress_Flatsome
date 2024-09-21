<?php

use NSL\Persistent\Persistent;

require_once NSL_PATH . '/includes/oauth2.php';
require_once NSL_PATH . '/NSL/PCKE/PKCE.php';

class NextendSocialProviderTwitterv2Client extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );


    protected $endpointAuthorization = 'https://twitter.com/i/oauth2/authorize';

    protected $endpointAccessToken = 'https://api.twitter.com/2/oauth2/token';

    protected $endpointRestAPI = 'https://api.twitter.com/2/';

    protected $scopes = array(
        'users.read',
        'tweet.read'
    );

    public function createAuthUrl() {
        try {

            $codeVerifier = \NSL\PKCE\PKCE::generateCodeVerifier(128);

            $args = array(
                'response_type'         => 'code',
                'client_id'             => urlencode($this->client_id),
                'redirect_uri'          => urlencode($this->redirect_uri),
                'state'                 => urlencode($this->getState()),
                'code_challenge'        => \NSL\PKCE\PKCE::generateCodeChallenge($codeVerifier),
                'code_challenge_method' => 'S256'
            );
            Persistent::set($this->providerID . '_code_verifier', $codeVerifier);


            $scopes = apply_filters('nsl_' . $this->providerID . '_scopes', $this->scopes);
            if (count($scopes)) {
                $args['scope'] = rawurlencode($this->formatScopes($scopes));
            }

            $args = apply_filters('nsl_' . $this->providerID . '_auth_url_args', $args);

            return add_query_arg($args, $this->getEndpointAuthorization());
        } catch (Exception $e) {
            throw new NSLSanitizedRequestErrorMessageException($e->getMessage());
        }
    }


    protected function extendAuthenticateHttpArgs($http_args) {
        $http_args['headers'] = [
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret)
        ];
        $http_args['body']    = [
            'code'          => $_GET['code'],
            'grant_type'    => 'authorization_code',
            'client_id '    => $this->client_id,
            'redirect_uri'  => $this->redirect_uri,
            'code_verifier' => Persistent::get($this->providerID . '_code_verifier')
        ];

        return $http_args;
    }

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();
        Persistent::delete($this->providerID . '_code_verifier');
    }
}

