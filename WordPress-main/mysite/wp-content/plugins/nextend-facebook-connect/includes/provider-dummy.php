<?php

abstract class NextendSocialProviderDummy {

    protected $id;
    protected $label;
    protected $path;

    /**
     * Defines the way the OAuth redirect is handled
     *
     * default_redirect: both the App and the Authorization requests accepts GET parameters in the redirect uri
     *
     * default_redirect_but_app_has_restriction: the App doesn't allow redirect URLs with GET parameters, but the
     * Authorization requests accepts it.
     *
     * rest_redirect: the App doesn't allow redirect URLs with GET parameters, and neither the Authorization
     * requests. In these cases we use the REST Endpoint of the provider e.g:
     * https://example.com/wp-json/nextend-social-login/v1/{{providerID}}/redirect_uri
     * that passes the state and code to the login endpoint of the provider.
     *
     * @var string
     */
    public $authRedirectBehavior = "default";

    protected $color = '#fff';

    protected $popupWidth = 600;

    protected $popupHeight = 600;

    /** @var NextendSocialLoginSettings */
    public $settings;

    /** @var NextendSocialProviderAdmin */
    protected $admin = null;

    public function needPro() {
        return true;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    public function enable() {
        return false;
    }

    public function isEnabled() {
        return false;
    }

    public function isTested() {
        return false;
    }

    public function isTest() {
        return false;
    }

    public function connect() {

    }

    public function getState() {
        return 'pro-only';
    }

    public function getIcon() {
        return plugins_url('/providers/' . $this->id . '/' . $this->id . '.png', NSL_PATH_FILE);
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @return int
     */
    public function getPopupWidth() {
        return $this->popupWidth;
    }

    /**
     * @return int
     */
    public function getPopupHeight() {
        return $this->popupHeight;
    }

    /**
     * @return mixed
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return NextendSocialProviderAdmin
     */
    public function getAdmin() {
        return $this->admin;
    }

    /**
     * @param string $subview
     *
     * @return bool
     */
    public function adminDisplaySubView($subview) {

        return false;
    }

}