<?php

class NextendSocialProviderSteam extends NextendSocialProviderDummy {

    protected $color = '#201D1D';

    public function __construct() {
        $this->id    = 'steam';
        $this->label = 'Steam';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderSteam());