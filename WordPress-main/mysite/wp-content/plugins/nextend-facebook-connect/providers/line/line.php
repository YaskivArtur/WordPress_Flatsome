<?php

class NextendSocialProviderLine extends NextendSocialProviderDummy {

    protected $color = '#06C755';

    public function __construct() {
        $this->id    = 'line';
        $this->label = 'Line';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderLine());