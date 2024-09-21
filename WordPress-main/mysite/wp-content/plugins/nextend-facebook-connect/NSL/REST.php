<?php

namespace NSL;

use Exception;
use NextendSocialLogin;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use function add_action;
use function register_rest_route;
use NextendSocialProviderOAuth;

class REST {

    public function __construct() {
        add_action('rest_api_init', array(
            $this,
            'rest_api_init'
        ));
    }

    public function rest_api_init() {
        register_rest_route('nextend-social-login/v1', '/(?P<provider>\w[\w\s\-]*)/get_user', array(
            'args' => array(
                'provider'     => array(
                    'required'          => true,
                    'validate_callback' => array(
                        $this,
                        'validate_provider'
                    )
                ),
                'access_token' => array(
                    'required' => true,
                ),
            ),
            array(
                'methods'             => 'POST',
                'callback'            => array(
                    $this,
                    'get_user'
                ),
                'permission_callback' => '__return_true'
            ),
        ));

    }

    public function validate_provider($providerID) {
        if (NextendSocialLogin::isProviderEnabled($providerID)) {
            if (NextendSocialLogin::$enabledProviders[$providerID] instanceof NextendSocialProviderOAuth) {
                return true;
            } else {
                /*
                 * OpenID providers don't have a secure Access Token, but just a simple ID that is usually easy to guess.
                 * For this reason we shouldn't return the WordPress user ID over the REST API of providers based on OpenID authentication.
                 */
                return new WP_Error('error', __('This provider doesn\'t support REST API calls!', 'nextend-facebook-connect'));
            }
        }

        return false;
    }

    /**
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_user($request) {

        $provider = NextendSocialLogin::$enabledProviders[$request['provider']];
        try {
            $user = $provider->findUserByAccessToken($request['access_token']);
        } catch (Exception $e) {
            return new WP_Error('error', $e->getMessage());
        }

        return $user;
    }

}

new REST();

