<?php
echo 'login crm success!';

require_once ROOTDIR_DNA . 'core/api/JH-Google-Contact.php';

if(isset($_GET['code'])) {
    $Google = new JH_Google_Contact();
    $token = $Google->gClient->fetchAccessTokenWithAuthCode($_GET['code']);

    $oAuth = new Google_Service_Oauth2($Google->gClient);
    $userData = $oAuth->userinfo_v2_me->get();
    
    echo '<pre>';
    var_dump($userData);
    var_dump($Google->gClient->getRedirectUri());
    echo '</pre>';
}
