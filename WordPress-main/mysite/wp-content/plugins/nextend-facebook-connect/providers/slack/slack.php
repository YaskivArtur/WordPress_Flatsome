<?php

class NextendSocialProviderSlack extends NextendSocialProviderDummy {

    protected $color = '#4A154B';

    public function __construct() {
        $this->id    = 'slack';
        $this->label = 'Slack';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderSlack());