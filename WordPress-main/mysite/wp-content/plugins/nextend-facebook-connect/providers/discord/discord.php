<?php

class NextendSocialProviderDiscord extends NextendSocialProviderDummy {

    protected $color = '#5865F2';

    public function __construct() {
        $this->id    = 'discord';
        $this->label = 'Discord';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderDiscord());