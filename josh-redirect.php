<?php
    if($path[0] == 'rumah-tahfidz') {
        $path[0] = 'rumah-tahfizh';
    }
    $donasi_id = $path[0];
    $link_code = 'campaign';
    require_once(ROOTDIR_DNA . 'donasiaja-campaign.php');
?>