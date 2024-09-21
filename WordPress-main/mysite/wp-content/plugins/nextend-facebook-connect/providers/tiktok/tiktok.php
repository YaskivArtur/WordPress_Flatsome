<?php

class NextendSocialProviderTiktok extends NextendSocialProviderDummy {

    protected $color = '#000000';

    public function __construct() {
        $this->id    = 'tiktok';
        $this->label = 'TikTok';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderTiktok());