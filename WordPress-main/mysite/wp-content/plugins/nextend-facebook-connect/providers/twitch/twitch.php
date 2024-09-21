<?php

class NextendSocialProviderTwitch extends NextendSocialProviderDummy {

    protected $color = '#9146FF';

    public function __construct() {
        $this->id    = 'twitch';
        $this->label = 'Twitch';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderTwitch());