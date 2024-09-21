<?php

class NextendSocialProviderSpotify extends NextendSocialProviderDummy {

    protected $color = '#1DB954';

    public function __construct() {
        $this->id    = 'spotify';
        $this->label = 'Spotify';
        $this->path  = dirname(__FILE__);
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderSpotify());