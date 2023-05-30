<?php

require_once ROOTDIR_DNA . 'assets/vendor/autoload.php';

class JH_Google_Contact {
    public $gClient;
    public $login_url;
    public function __construct() {
        $gClient = new Google_Client();
        $gClient->setClientId('516424759222-nusnqup79hsjnhipg7qbh4acu9pqd0af.apps.googleusercontent.com');
        $gClient->setClientSecret('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');
        $gClient->setApplicationName('DFR YMPB Login');
        $gClient->setRedirectUri('https://ympb.or.id/__glogin_crm');
        $gClient->addScope('https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email');
        
        $login_url = $gClient->createAuthUrl();

        $this->gClient = $gClient;
        $this->login_url = $login_url;
    }
}