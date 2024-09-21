<?php

class NextendSocialProviderPaypal extends NextendSocialProviderDummy {

    protected $color = '#014ea0';

    public function __construct() {
        $this->id    = 'paypal';
        $this->label = 'PayPal';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderPaypal());