<?php

class NextendSocialProviderApple extends NextendSocialProviderDummy {

    protected $color = '#000000';

    public function __construct() {
        $this->id    = 'apple';
        $this->label = 'Apple';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderApple());