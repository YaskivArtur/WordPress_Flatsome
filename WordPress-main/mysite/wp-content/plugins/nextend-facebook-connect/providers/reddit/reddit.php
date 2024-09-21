<?php

class NextendSocialProviderReddit extends NextendSocialProviderDummy {

    protected $color = '#FF4500';

    public function __construct() {
        $this->id    = 'reddit';
        $this->label = 'reddit';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderReddit());