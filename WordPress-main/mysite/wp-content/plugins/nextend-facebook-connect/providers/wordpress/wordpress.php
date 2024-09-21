<?php

class NextendSocialProviderWordpress extends NextendSocialProviderDummy {

    protected $color = '#3499cd';

    public function __construct() {
        $this->id    = 'wordpress';
        $this->label = 'WordPress.com';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderWordpress());