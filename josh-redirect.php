<?php
    if($jlink == 'rumah-tahfidz') {
        $jlink = 'rumah-tahfizh';
    }
    $donasi_id = $jlink;
    $link_code = 'campaign';
    require_once(ROOTDIR_DNA . 'donasiaja-campaign.php');
?>