<?php
/* hai.. welcome.. */
function donasiaja_settings() {

    ?>
    <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "dja_settings";
        $table_name2 = $wpdb->prefix . "dja_users";
        $table_name3 = $wpdb->prefix . "dja_verification_details";
        $table_name4 = $wpdb->prefix . "dja_payment_list";
        $table_name5 = $wpdb->prefix . "dja_category";
        $table_name6 = $wpdb->prefix . "users";
        

        $action = (isset($_GET['action'])) ? $_GET['action'] : null;


        donasiaja_global_vars();
        $plugin_license = strtoupper($GLOBALS['donasiaja_vars']['plugin_license']);

        // ROLE
        $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
        $roles = array_keys((array)$cap);
        $role = $roles[0];

        $id_login = wp_get_current_user()->ID;

        $home_url = get_site_url();

        $query_settings2 = $wpdb->get_results('SELECT type, data from '.$table_name.' ORDER BY id ASC');
        // set data settings
        foreach ($query_settings2 as $key => $value) {
            // echo ' - '. $value->type.' => '.$value->data.'<br>';
            // echo "elseif($ value=='".$value->type."'){<br>  $ isi = '".$value->data."';<br>}";

            // GET DATA WITH AUTOMATIC VAR type on dja_settings
            $a = $value->type;
            $$a = $value->data;
        }

        $payment_setting = json_decode($payment_setting, true);
        $bank_account = json_decode($bank_account, true);
        
        $opt_nominal  = json_decode($opt_nominal, true);
        $apikey_local  = json_decode($apikey_local, true);
        $apikey_server  = json_decode($apikey_server, true);

        $apikey     = $apikey_local['donasiaja'][0];
        $license    = $apikey_server['donasiaja'][0];
        $status     = $apikey_server['donasiaja'][1];
        $time       = $apikey_server['donasiaja'][2];
        $code       = $apikey_server['donasiaja'][3];

        date_default_timezone_set('Asia/jakarta');
        $now     = strtotime(date("Y-m-d h:i:s"));
        $time_check = floatval($time)-$now;

        if($time_check<=0){
            $plugin = 'expired';
        }else{
            $plugin = 'allowed';
        }
        

        $theme_color          = json_decode($theme_color, true);
        $unique_number_value  = json_decode($unique_number_value, true);

        $get_bank = $wpdb->get_results('SELECT code, name from '.$table_name4.' ORDER BY id ASC');

        $option_bank = '<option value="0">Pilih Bank</option>';
        foreach ($get_bank as $key => $value) {
           if($value->code=='mandiri_syariah' || $value->code=='bni_syariah' || $value->code=='bri_syariah'){}else{$option_bank .= '<option value="'.$value->code.'">'.$value->name.'</option>';}
        }

        $fb_event  = json_decode($fb_event, true);
        $event_1   = $fb_event['event'][0];
        $event_2   = $fb_event['event'][1];
        $event_3   = $fb_event['event'][2];

        // Telegram Token
        $token = $telegram_bot_token;
        if($telegram_send_to!=''){
            $telegram_send_to = json_decode($telegram_send_to);
        }

        if($telegram_manual_confirmation!=''){
            $telegram_manual_confirmation = json_decode($telegram_manual_confirmation);
        }

        if($email_send_to!=''){
            $email_send_to = json_decode($email_send_to);
        }

        // print_r($fundraiser_email_text);

        if($fundraiser_email_text!=''){
            $fundraiser_email_text = json_decode($fundraiser_email_text);
        }

        foreach($fundraiser_email_text as $key => $value) {
            $f_message_email = $value->message;
            $f_message_email = str_replace('<p>linebreak</p>', '', $f_message_email);
            $f_message_email = str_replace('linebreak', '', $f_message_email);

            if (isset($value->subject)){
               $f_subject_email = $value->subject;
            }else{
               $f_subject_email = '';
            }
            if (isset($value->email)){
               $f_send_to = $value->email;
            }else{
               $f_send_to = '';
            }
            if (isset($value->emailcc)){
               $f_emailnyacc = $value->emailcc;
            }else{
               $f_emailnyacc = '';
            }
            if (isset($value->emailbcc)){
               $f_emailnyabcc = $value->emailbcc;
            }else{
               $f_emailnyabcc = '';
            }
        }


        if($email_success_message!=''){
            $email_success_message = json_decode($email_success_message);
        }

        foreach($email_success_message as $key => $value) {
            $s_message_email = $value->message;
            $s_message_email = str_replace('<p>linebreak</p>', '', $s_message_email);
            $s_message_email = str_replace('linebreak', '', $s_message_email);

            if (isset($value->subject)){
               $s_subject_email = $value->subject;
            }else{
               $s_subject_email = '';
            }
            if (isset($value->email)){
               $s_send_to = $value->email;
            }else{
               $s_send_to = '';
            }
            if (isset($value->emailcc)){
               $s_emailnyacc = $value->emailcc;
            }else{
               $s_emailnyacc = '';
            }
            if (isset($value->emailbcc)){
               $s_emailnyabcc = $value->emailbcc;
            }else{
               $s_emailnyabcc = '';
            }
        }


        // success email message
        $s_email_idcc = '';
        $s_email_idbcc = '';
        if (strpos($s_emailnyacc, ',') !== false ) {

            // echo $s_emailnyacc;
            $array_email  = (explode(",", $s_emailnyacc));
            $count = count($array_email);
            $i = 1;
            foreach ($array_email as $values){
                if($i<$count){
                    $s_email_idcc .= "'".$values."',";
                }else{
                    $s_email_idcc .= "'".$values."'";
                }
                $i++;
            }

        }elseif($s_emailnyacc==' '){
            $s_email_idcc = "";
        }else{
            $s_email_idcc = "'".$s_emailnyacc."'";
        }

        if (strpos($s_emailnyabcc, ',') !== false ) {

            $array_email  = (explode(",", $s_emailnyabcc));
            $count = count($array_email);
            $i = 1;
            foreach ($array_email as $values){
                if($i<$count){
                    $s_email_idbcc .= "'".$values."',";
                }else{
                    $s_email_idbcc .= "'".$values."'";
                }
                $i++;
            }

        }elseif($s_emailnyabcc==' '){
            $s_email_idbcc = "";
        }else{
            $s_email_idbcc = "'".$s_emailnyabcc."'";
        }

        // fundraiser_email_text
        $f_email_idcc = '';
        $f_email_idbcc = '';
        if (strpos($f_emailnyacc, ',') !== false ) {
            $array_email  = (explode(",", $f_emailnyacc));
            $count = count($array_email);
            $i = 1;
            foreach ($array_email as $values){
                if($i<$count){
                    $f_email_idcc .= "'".$values."',";
                }else{
                    $f_email_idcc .= "'".$values."'";
                }
                $i++;
            }
        }elseif($f_emailnyacc==' '){
            $f_email_idcc = "";
        }else{
            $f_email_idcc = "'".$f_emailnyacc."'";
        }

        if (strpos($f_emailnyabcc, ',') !== false ) {

            $array_email  = (explode(",", $f_emailnyabcc));
            $count = count($array_email);
            $i = 1;
            foreach ($array_email as $values){
                if($i<$count){
                    $f_email_idbcc .= "'".$values."',";
                }else{
                    $f_email_idbcc .= "'".$values."'";
                }
                $i++;
            }

        }elseif($f_emailnyabcc==' '){
            $f_email_idbcc = "";
        }else{
            $f_email_idbcc = "'".$f_emailnyabcc."'";
        }

        // fb pixel
        $pixel_id = '';
        if (strpos($fb_pixel, ',') !== false ) {

            $array_pixel  = (explode(",", $fb_pixel));
            $count = count($array_pixel);
            $i = 1;
            foreach ($array_pixel as $values){
                if($i<$count){
                    $pixel_id .= "'".$values."',";
                }else{
                    $pixel_id .= "'".$values."'";
                }
                $i++;
            }

        }elseif($fb_pixel==''){
            $pixel_id = "";
        }else{
            $pixel_id = "'".$fb_pixel."'";
        }
        
        $categories = $wpdb->get_results('SELECT * from '.$table_name5.' ORDER BY id ASC');

        $data_userwp = $wpdb->get_results('SELECT a.ID, a.display_name from '.$table_name6.' a ');
        $data_usercs = '';
        foreach ( $data_userwp as $user ) {
            $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
            $roles_user = array_keys((array)$cap_user);
            $rolenya_user = $roles_user[0];

            if($rolenya_user=='cs'){
                $data_usercs .= '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
            }
        }

        if($wanotif_apikey_cs!=''){
            $wanotif_apikey_csnya = json_decode($wanotif_apikey_cs, true);
            $jumlah_wanotif_cs = $wanotif_apikey_csnya['jumlah'];
        }else{
            $jumlah_wanotif_cs = 0;
        }
       
        $wanotif_sender = '';
        if($jumlah_wanotif_cs>=1){

        $wanotif_apikey_cs2 = json_decode($wanotif_apikey_cs, true);
        foreach ($wanotif_apikey_cs2['data'] as $key => $value) { 
            $rand3 = d_randomString(3);
       
                $nama_csnya = '';
                foreach ( $data_userwp as $user ) {
                    $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
                    $roles_user = array_keys((array)$cap_user);
                    $rolenya_user = $roles_user[0];

                    if($rolenya_user=='cs'){
                        $selected = '';
                        if($user->ID==$value[0]){
                            $selected = 'selected';
                            $nama_csnya = $user->display_name;
                        }

                        
                    }
                }
                $wanotif_sender .= '<option value="'.$value[1].'">'.$nama_csnya.' - '.substr($value[1], 0, 16).'...</option>';
            
        } }

    ?>


    <!-- App css -->
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <!-- jQuery  -->
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery-1.11.1.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/zoom/medium-zoom.min.js"></script>

    <?php 

    // wp_enqueue_script('jquery');
    // This will enqueue the Media Uploader script
    wp_enqueue_media();

    /**
     * Top Menu
     */
    $menu_arr = [
        'themes'        => 'Themes',
        'form'          => 'Form',
        'payment'       => 'Payment',
        'notification'  => 'Notification',
        'socialproof'   => 'Social&nbsp;Proof',
        'fundraising'   => 'Fundraising',
        'general'       => 'General',
        'waba'          => 'Waba',
        'socialgraph'   => 'Social&nbsp;Graph'
    ];
        
    ?>

    <style>
    .row [class^="col"] {
        margin:0;
    }
    .notice, #message, #dolly {
        display:none;
    }
    .swal2-cancel.swal2-styled, .swal2-confirm.swal2-styled {
        height: 39px;
        font-size: 13px !important;
    }
    .swal2-popup {
        padding: 3em 0px !important;
    }
    .button-items .btn {
      margin: 0 1px 8px 0;
    }
    .mce-menu-item:hover, .mce-menu-item:focus {
        background: #eef5ff !important;
    }
    .hide_box {
        display: none;
    }
    #swal2-content {
        text-align: center;
    }
    #data_bank .col-md-2, #data_bank .col-md-3 {
        padding-right: 0;
    }
    img[data-action="zoom"] {
      cursor: pointer;
      cursor: -webkit-zoom-in;
      cursor: -moz-zoom-in;
    }
    .zoom-img,
    .zoom-img-wrap {
      position: relative;
      z-index: 666;
      -webkit-transition: all 300ms;
           -o-transition: all 300ms;
              transition: all 300ms;
    }
    img.zoom-img {
      cursor: pointer;
      cursor: -webkit-zoom-out;
      cursor: -moz-zoom-out;
    }
    .zoom-overlay {
      z-index: 420;
      background: rgba(34,40,45,.9);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      pointer-events: none;
      filter: "alpha(opacity=0)";
      opacity: 0;
      -webkit-transition:      opacity 300ms;
           -o-transition:      opacity 300ms;
              transition:      opacity 300ms;
    }
    .zoom-overlay-open .zoom-overlay {
      filter: "alpha(opacity=100)";
      opacity: 1;
    }
    .zoom-overlay-open,
    .zoom-overlay-transitioning {
      cursor: default;
    }
    .other_nominal_value input::-webkit-input-placeholder{font-size:16px;font-weight:400}.other_nominal_value input:-moz-placeholder{font-size:16px;font-weight:400}.other_nominal_value input::placeholder{font-size:16px;font-weight:400;margin-top:-4px}
body{background:#f6faff}.boxcard-title{font-size:20px;font-weight:600;color:#303e67;margin-bottom:4px}.data_profile_hide,.data_akun_hide,.data_akun_bank_hide,.data_password_hide{display:none}#upload_ktp,#upload_ktp_selfie,#upload_legalitas{background-color:#40b4fa;margin-right:85px;margin-top:-45px}#upload_ktp:hover,#upload_ktp_selfie:hover,#upload_legalitas:hover{background-color:#349bd9}#user_cover_img{width:100%;border-radius:8px;margin-bottom:-20px}#verified_user .icon-dual-pink{color:#21b8f3;fill:rgba(56,219,184,.31)}#on_review .icon-dual-pink{color:#f9962c;fill:rgba(249,151,47,.31)}#data_personal,#data_organisasi{padding:30px;background:#f6faff;padding-bottom:50px;border-radius:4px}#verify_me{color:#4c5180}#box-section{margin:0 auto;margin-top:20px;}.img_display{width:250px;margin-top:10px;border-radius:4px}.text-icon{color:#abb5d2;margin-left:3px;transition:all .35s ease}.action-btn a:hover .text-icon{color:#7680FF}a.view_profile:hover p{color:#7680ff!important;transition:all .35s ease}.hide-loading{display:none}.target_tak_hingga{font-size:16px;position:absolute;margin-top:-2px;margin-left:3px}.field_required{color:#ff3b3b}.media:hover{background:#f6faff}.f_edit,.f_delete{cursor:pointer;float:right}.f_delete{margin-left:5px}.campaign-title{font-size:13px;font-weight:700;color:#384d64;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif}.campaign-title a:hover{color:#52649b}input.set_red,input.form-control.set_red,img.set_red,.mce-edit-area.set_red{border:2px solid #ED8181!important}.wp-core-ui select,div.dataTables_wrapper div.dataTables_filter input{border-color:#e5eaf0}div.dataTables_wrapper div.dataTables_filter input:visited,div.dataTables_wrapper div.dataTables_filter input:active,div.dataTables_wrapper div.dataTables_filter input:focus{border-color:#e5eaf0}.error.landingpress-message{display:none}.page-content-tab{margin:0!important;width:auto}img.thumb-cover{height:60px;border-radius:4px}.active-status{background:#1CB65D;color:#fff;border-radius:4px;padding:2px 8px;font-size:9px}table.dataTable td{font-size:12px;vertical-align:top;padding-top:15px;color:#384d64}table.dataTable td img{margin-top:3px}button.no-border{border:0;background:#f6f9ff}button.no-border:hover{background:#7680FF;color:#fff}button.no-border.delete_campaign:hover{background:#F05860;color:#fff}.btn-group button.btn{padding:.175rem .75rem}a:active,a:focus,a:visited{box-shadow:none!important;outline:none;box-shadow:0 4px 15px 0 rgba(0,0,0,.1)}input.form-control{border:1px solid #e8ebf3!important;font-size:14px}input.form-control:active,input.form-control:visited{border:1px solid #7680FF!important;box-shadow:none!important;outline:none}.mce-menubar,.mce-branding{display:none}#cover_image img{border-radius:4px}.fro-profile_main-pic-change{cursor:pointer;background-color:#7680ff;border-radius:50%;width:28px;height:28px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-flex:1;-ms-flex:1;flex:1;-webkit-box-shadow:0 0 20px 0 rgba(252,252,253,.05);box-shadow:0 0 20px 0 rgba(252,252,253,.05);transition:all .35s ease;margin-top:-10px;border:1px solid #fff;margin-top: -35px; margin-left: 80px;position: absolute;}.fro-profile_main-pic-change2{cursor:pointer;background-color:#7680ff;border-radius:50%;width:36px;height:36px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-flex:1;-ms-flex:1;flex:1;-webkit-box-shadow:0 0 20px 0 rgba(252,252,253,.05);box-shadow:0 0 20px 0 rgba(252,252,253,.05);right:0;position:absolute;margin-top:-40px;margin-right:55px;transition:all .35s ease;background-color:#7680ff9c;border:1px solid #fff}.fro-profile_main-pic-change:hover{background-color:#505DFF}.fro-profile_main-pic-change i,.fro-profile_main-pic-change2 i{color:#fff}.form-group input{height:45px}.target .currency{position:absolute;margin-top:-37px;margin-left:15px;font-weight:700;font-size:18px;color:#719eca}#packaged .currency{position:absolute;margin-top:-27px;margin-left:10px;font-weight:700;font-size:14px;color:#719eca}#packaged input{text-align:right}.opt_packaged{display:none}.opt_packaged.show{display:inline}.target input{text-align:right;font-size:24px;font-weight:700;color:#23374d}.box-slugnya{background:#e3eaf2;padding:1px 4px;border-radius:2px}.box-slugnya[contenteditable="true"]{border:1px solid #7680ff;background:#fff;padding:1px 6px}.copylink{font-size:16px;margin-right:5px;padding-top:3px;cursor:pointer}.copylink:hover{color:#505DFF}.edit-slug,.edit-status,.edit-visibility{font-size:16px;margin-left:5px;padding-top:3px;cursor:pointer}.edit-slug:hover,.edit-status:hover,.edit-visibility:hover{color:#505DFF!important}#publish_status{display:none;margin-bottom:5px}#publish-section select{height:30px!important;font-size:13px;margin-top:5px}.page-title-box{padding-bottom:0}#lala-alert-container{position:fixed;height:auto;max-width:350px;width:100%;top:18px;right:5px;z-index:9999}#lala-alert-wrapper{height:auto;padding:15px}.lala-alert{position:relative;padding:25px 30px 20px;font-size:15px;margin-top:15px;opacity:1;line-height:1.4;border-radius:3px;border:1px solid transparent;cursor:default;transition:all 0.5s ease-in-out;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.lala-alert span{opacity:.7;transition:all 0.25s ease-in-out}.lala-alert span:hover{opacity:1}.alert-success{color:#fff;background-color:#37c1aa}.alert-success>span{color:#0b6f5e}.alert-info{color:#fff;background-color:#3473c1}.alert-info>span{color:#1e4567}.alert-warning{color:#6b7117;background-color:#ffee9e}.alert-warning>span{color:#8a6d3b}.alert-danger{color:#fff;background-color:#d64f62}.alert-danger>span{color:#6f1414}.close-alert-x{position:absolute;float:right;top:10px;right:10px;cursor:pointer;outline:none}.fade-out{opacity:0}.animation-target{animation:animation 1500ms linear both}@keyframes animation{0%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,250,0,0,1)}3.14%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,160.737,0,0,1)}4.3%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,132.565,0,0,1)}6.27%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,91.357,0,0,1)}8.61%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,51.939,0,0,1)}9.41%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,40.599,0,0,1)}12.48%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,6.498,0,0,1)}12.91%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,2.807,0,0,1)}16.22%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-17.027,0,0,1)}17.22%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-20.404,0,0,1)}19.95%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-24.473,0,0,1)}23.69%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-21.178,0,0,1)}27.36%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-13.259,0,0,1)}28.33%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-11.027,0,0,1)}34.77%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,.142,0,0,1)}39.44%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,2.725,0,0,1)}42.18%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,2.675,0,0,1)}56.99%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-.202,0,0,1)}61.66%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-.223,0,0,1)}66.67%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-.104,0,0,1)}83.98%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,.01,0,0,1)}100%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1)}}@media only screen and (max-width:480px){.dja_label{width:auto}.card.col-6{max-width:100%}}
    .col-6 {
        max-width: 54%;
    }

    /* tagit */
    .tag-editor {
        list-style-type: none; padding: 10px 5px; margin: 0; overflow: hidden; border: 1px solid #eee; cursor: text;
        font: normal 14px sans-serif; color: #555; background: #fff; line-height: 20px; margin-bottom: 15px;
        border-radius: 4px;
    }
    .tag-editor li { display: block; float: left; overflow: hidden; margin: 3px 0; }
    .tag-editor div { float: left; padding: 0 4px; }
    .tag-editor .placeholder { padding: 0 8px; color: #bbb; }
    .tag-editor .tag-editor-spacer { padding: 0; width: 8px; overflow: hidden; color: transparent; background: none; }
    .tag-editor input {
        vertical-align: inherit; border: 0; outline: none; padding: 0; margin: 0; cursor: text;
        font-family: inherit; font-weight: inherit; font-size: inherit; font-style: inherit;
        box-shadow: none; background: none; color: #444;
    }
    .tag-editor-hidden-src { position: absolute !important; left: -99999px; }
    .tag-editor ::-ms-clear { display: none; }
    .tag-editor .tag-editor-tag {
        padding-left: 5px; color: #46799b; background: #e0eaf1; white-space: nowrap;
        overflow: hidden; cursor: pointer; border-radius: 2px 0 0 2px;
    }
    .tag-editor .tag-editor-delete { background: #e0eaf1; cursor: pointer; border-radius: 0 2px 2px 0; padding-left: 3px; padding-right: 4px; }
    .tag-editor .tag-editor-delete i { line-height: 18px; display: inline-block; }
    .tag-editor .tag-editor-delete i:before { font-size: 16px; color: #8ba7ba; content: "×"; font-style: normal; }
    .tag-editor .tag-editor-delete:hover i:before { color: #d65454; }
    .tag-editor .tag-editor-tag.active+.tag-editor-delete, .tag-editor .tag-editor-tag.active+.tag-editor-delete i { visibility: hidden; cursor: text; }

    .tag-editor .tag-editor-tag.active { background: none !important; padding: 0 !important; }
    .tag-editor .tag-editor-tag input {
        padding: 0px 10px !important;
        background: #F6FAFF;
        border-radius: 4px;
    }

    /* jQuery UI autocomplete - code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css */
    .ui-autocomplete { position: absolute; top: 0; left: 0; cursor: default; font-size: 14px; }
    .ui-front { z-index: 9999; }
    .ui-menu { list-style: none; padding: 1px; margin: 0; display: block; outline: none; }
    .ui-menu .ui-menu-item a { text-decoration: none; display: block; padding: 2px .4em; line-height: 1.4; min-height: 0; }
    .ui-widget-content { border: 1px solid #bbb; background: #fff; color: #555; }
    .ui-widget-content a { color: #46799b; }
    .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus { background: #e0eaf1; }
    .ui-helper-hidden-accessible { display: none; }


    .custom-control-input:checked ~ .custom-control-label::before {
        color: #fff;
        border-color: #36bd47;
        background-color: #36bd47;
    }
    .custom-control-label::before {
        border: #d8204c solid 1px;
    }
    .custom-switch .custom-control-label::after {
        background-color: #d8204c;
    }
    .custom-radio .custom-control-label::before {
        border-radius: 50%;
        border: 1px solid currentColor;
    }
    .form-group input {
        padding:0 12px;
    }
    .notice-warning {
        display: none;
    }

    .text_highlight {
        color: #7680FF;
    }
    .text_info {
        /*font-style: italic;*/
        padding-bottom: 5px;
        font-size: 11px;
        margin-top: -10px;
    }
    .input_text {
        font-size: 13px !important;
        padding-left: 12px !important;
        margin-top: -5px;
        margin-bottom: 20px;
    }
    .textarea_text {
        font-size: 13px;
    }
    .box_email_message {
        display: none;
    }
    .box_email_message.show_box {
        display: inline;
    }
    .box_email_message.show_box2 {
        display: inline;
    }
    .box_telegram_message, .box_telegram_message2, .box_email_message {
        background: #f6faff;padding: 20px 20px; border-radius: 4px; border: 1px solid #eceff5;
        margin-bottom: 20px !important;
        box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);
        -webkit-box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);
        -moz-box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);
    }
    .box_telegram_message label, .box_telegram_message2 label, .box_email_message label {
        cursor: text;
        padding-bottom: 5px;
    }
    button.swal2-close {
        font-size: 28px !important;
        margin-right: 8px !important;
        margin-top: 8px !important;
        color:#fff;
    }

    .swal2-popup.swal2-modal{
        border-radius:12px;
        padding:40px 40px 50px 40px;
        background: url('<?php echo plugin_dir_url( __FILE__ ).'../assets/images/bg4.png'; ?>') no-repeat, #fff;
        margin-top: 30px;
    }
    #message_tele, #message_wa {
        font-size: 14px;
    }
    .nav-tabs .nav-link {
        font-size: 13px;
    }
    a.link_href{
        color: #7680ff;
        text-decoration: underline;
        line-height: 1.3;
    }
    .del_notif, .del_notif_email {
        color:#fff;
        border:1px solid #fff;
    }
    .pricingTable1 .pricing-content-2 li {
        color: #596999;
        margin-bottom: 0;
    }
    .pricingTable1 .pricing-content-2 li::before {
        margin-right: 10px;
    }

@media only screen and (max-width:480px) {
    .card.col-6 {
        max-width: 100%;
    }
    .page-content-tab, .container-fluid {
        padding: 0;
    }
    .container-fluid .col-lg-4 {
        padding-right: 0;
    }
    .body-nya {
        margin-left: 0 !important;
        margin-right: 20px !important;
    }
    select.form-control {
        font-size: 13px;
    }
    .bank-col-1 {
        margin-bottom: 10px;
   }
   .bank-col-2 .form-group, .bank-col-3 .form-group, .bank-col-4 .form-group  {
        margin-bottom: 10px;
   }
   .bank-col-5 {
    margin-bottom: 20px;
    text-align: right;
   }
   #update_payment, #update_ipaymu, #update_tripay, #update_moota, #update_form, #update_socialproof, #update_general, #deactivate_apikey, #activate_apikey, #update_themes {
        width: 100%;
   }
   #deactivate_apikey {
       margin-bottom: 15px;
        margin-top: 10px;
    }
   .col-md-12.col-lg-12{
    padding-right: 0;
   }
   #unique_number_range1, #unique_number_range2 {
    /* width: 48%; */
   }
   .unique_number_range {
    width: 45%;
   }
   .unique_number_range.titik_dua {
    width: 5%;
   }
   .box_nominal_donasi {
        background: #f4f8ff;
        margin-bottom: 10px;
        border-radius: 4px;
        padding-top: 20px;
        padding-right: 10px;
   }
   .seringnya {
        padding-top: 0 !important;
        margin-bottom: 20px;
   }
   #row-style {
    padding-top: 0 !important;
   }
   #row-style, #row-time {
        padding-bottom: 0 !important;
   }
   #row-time .custom-radio {
        margin-bottom: 20px;
   }
}

</style>

        <?php 


        ?>

        <div class="body-nya" style="margin-top:20px;margin-right:30px;margin-left: 20px;">
        <!-- Page Content-->

        <?php if($action === 'themes') { ?>

            <?php check_license(); ?>

            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Themes</h5>  
                                    <p class="card-text text-muted">Silahkan diatur sesuai tema anda.</p>  
                                    <hr>           
                                </div><!--end col-->
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_themes" style="">

                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h5 class="card-title mt-0">App Logo<span></span></h5>

                                                                <span class="fro-profile_main-pic-change" id="upload_app_logo" title="Upload App Logo">
                                                                    <i class="fas fa-camera"></i>
                                                                </span>

                                                        <div class="met-profile-main">
                                                            <div class="met-profile-main-pic" id="app_logo" style="height: 100px;">
                                                            <?php if($logo_url=='') { ?>
                                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . "../assets/images/pp.jpg"; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 4px;">
                                                            <?php }else{?>
                                                                <img src="<?php echo $logo_url; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 4px;" data-action="zoom">
                                                            <?php } ?>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">App Name<span></span></h5>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="app_name" required="" placeholder="App Name" value="<?php echo $app_name; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 5px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Theme Color<span></span></h5>
                                                        <div class="form-group">
                                                            <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][0]; ?>" id="theme_color" data-control="hue" style="height: 45px;width: auto !important;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Progress Bar<span></span></h5>
                                                        <div class="form-group">
                                                            <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][1]; ?>" id="progressbar_color" data-control="hue" style="height: 45px;width: auto !important;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Donation Button on Page Campaign<span></span></h5>
                                                        <div class="form-group">
                                                            <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][2]; ?>" id="button_color" data-control="hue" style="height: 45px;width: auto !important;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Donation Button on Shortcode List Campaign<span></span></h5>
                                                        <div class="form-group">
                                                            <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][3]; ?>" id="button_color2" data-control="hue" style="height: 45px;width: auto !important;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_themes">Update <div class="spinner-border spinner-border-sm text-white update_themes_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>
                                               
                                            </div>

                                        </div><!--end card -body-->
                                    </div><!--end card-->                                                               
                                </div>
                            </div>                                                                             
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'form') { ?>

            <?php check_license(); ?>

            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Form</h5>  
                                    <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan form anda.</p>  
                                    <hr>           
                                </div><!--end col-->
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_themes" style="">

                                                <?php

                                                if($form_setting=='1'){
                                                    $status_text1 = '<span>Active</span>';
                                                    $checked1 = 'checked=""';
                                                }else{
                                                    $status_text1 = '<span>Not Active</span>';
                                                    $checked1 = '';
                                                }

                                                $form_text   = json_decode($form_text, true);
                                                $text1 = $form_text['text'][0];
                                                $text2 = $form_text['text'][1];
                                                $text3 = $form_text['text'][2];
                                                $text4 = $form_text['text'][3];

                                                if($form_email_setting=='1'){
                                                    $status_text2 = '<span>Show</span>';
                                                    $checked2 = 'checked=""';
                                                }else{
                                                    $status_text2 = '<span>Hide</span>';
                                                    $checked2 = '';
                                                }

                                                if($form_comment_setting=='1'){
                                                    $status_text3 = '<span>Show</span>';
                                                    $checked3 = 'checked=""';
                                                }else{
                                                    $status_text3 = '<span>Hide</span>';
                                                    $checked3 = '';
                                                }

                                                if($limitted_donation_button=='1'){
                                                    $status_text4 = '<span>Active</span>';
                                                    $checked4 = 'checked=""';
                                                }else{
                                                    $status_text4 = '<span>Not Active</span>';
                                                    $checked4 = '';
                                                }

                                                ?>

                                                <div class="row" style="margin-top: 0px;">
                                                    <div class="col-md-9">
                                                        <h5 class="card-title mt-0">Donatur dapat memilih Tipe Form</h5>
                                                        <p class="card-text text-muted">Aktifkan jika donatur boleh memilih tipe form pada saat pembuatan campaign (Form Card, Typing dan Packaged).</p>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_form_setting" style="margin-bottom: 20px;">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="form_setting" data-id="1" <?php echo $checked1; ?> >
                                                                <label class="custom-control-label" for="form_setting"><?php echo $status_text1; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 20px 0 0px 0;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Minimum Donasi</h5>
                                                        <p class="card-text text-muted">Tulis minimum donasi yang diperbolehkan ketika donatur mengetik donasi pada form.</p> 
                                                    </div>
                                                    <div class="col-md-3" style="margin-top: 15px;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="minimum_donate" required="" placeholder="Cth: 10000" value="<?php echo $minimum_donate; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 30px 0 20px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Pilihan Nominal Donasi</h5>
                                                        <p class="card-text text-muted">Tulis dari nominal terendah ke tinggi.</p> 
                                                    </div>
                                                <?php $i=1; foreach ($opt_nominal as $key => $value) { ?>
                                                    <div class="row box_nominal_donasi" style="padding-left: 10px;">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control nominalnya" id="opt_number<?php echo $i; ?>" required="" placeholder="Nominal <?php echo $i; ?>" style="font-size: 13px;padding-left: 12px;" value="<?php echo $value[0]; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control labelnya" id="opt_label<?php echo $i; ?>" required="" placeholder="Label <?php echo $i; ?>" style="font-size: 13px;padding-left: 12px;" value="<?php echo $value[1]; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="custom-control custom-radio seringnya" style="padding-top: 12px;padding-left: 30px;">
                                                                <input type="radio" value="<?php echo $i; ?>" id="sering_dipilih<?php echo $i; ?>" name="sering_dipilih" class="custom-control-input" <?php if($value[2]=='1'){echo 'checked=""';}?>>
                                                                <label class="custom-control-label" for="sering_dipilih<?php echo $i; ?>">Sering di Pilih</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $i++; } ?>

                                                </div>


                                                <div class="row" style="padding: 0 0 20px 0;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Maksimal jumlah paket</h5>
                                                        <p class="card-text text-muted">Tulis maksimal jumlah paket yang ada pada form paket</p> 
                                                    </div>
                                                    <div class="col-md-6" style="margin-top: 15px;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="max_package" required="" placeholder="Jumlah Paket" value="<?php echo $max_package; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                    
                                                    <div class="col-md-6" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Anonim</h5>
                                                        <p class="card-text text-muted">Contoh: Orang Baik, Hamba Allah</p>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="anonim_text" required="" placeholder="Anonim" value="<?php echo $anonim_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Email</h5>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_form_email_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox31" id="form_email_setting" data-id="1" <?php echo $checked2; ?> >
                                                                <label class="custom-control-label" for="form_email_setting"><?php echo $status_text2; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Comment</h5>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_form_comment_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox32" id="form_comment_setting" data-id="1" <?php echo $checked3; ?> >
                                                                <label class="custom-control-label" for="form_comment_setting"><?php echo $status_text3; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>




                                                <div class="row set_line" style="padding: 20 0 30px 0;margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Form Pagename</h5>
                                                        <p class="card-text text-muted">Tulis nama halaman form anda, contoh: donasi, donasi-sekarang, donate-now.<br>Note: tanpa spasi dan huruf kecil semua.</p>
                                                    </div>
                                                    <div class="col-md-6" style="margin-top: 15px;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="page_donate" required="" placeholder="Pagename" value="<?php echo $page_donate; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" style="margin-top: 0px;">
                                                        <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/campaign/title-campaign/<span class="set_page_donate"><?php echo $page_donate; ?></span></p> 
                                                    </div>

                                                </div>


                                                <div class="row" style="margin-top: 40px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Thankyou Page</h5>
                                                        <p class="card-text text-muted">Tulis nama halaman thankyou page anda, contoh: terimakasih, thankyou-page, typ, atau invoice. Note: tanpa spasi dan huruf kecil semua.</p> 
                                                    </div>
                                                    <div class="col-md-6" style="margin-top: 15px;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="page_typ" required="" placeholder="Pagename" value="<?php echo $page_typ; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" style="margin-top: 0px;">
                                                        <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/campaign/title-campaign/<span class="set_page_typ"><?php echo $page_typ; ?></span>/invoiceid</p> 
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 50px 0 10px 0;">
                                                    <div class="col-md-10">
                                                        <h5 class="card-title mt-0">Form Konfirmasi</h5>
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-9">
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="1" id="formKonfirmasi1" name="form_confirmation_setting" class="custom-control-input" <?php if($form_confirmation_setting=='1') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="formKonfirmasi1">Aktifkan untuk semua menu pembayaran</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="2" id="formKonfirmasi2" name="form_confirmation_setting" class="custom-control-input" <?php if($form_confirmation_setting=='2') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="formKonfirmasi2">Aktifkan hanya pada menu Transfer</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="0" id="formKonfirmasi3" name="form_confirmation_setting" class="custom-control-input" <?php if($form_confirmation_setting=='0' || $form_confirmation_setting=='') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="formKonfirmasi3">Non Aktifkan</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" id="form_text" style="margin-top: 40px;">

                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h4 class="mt-0 header-title">Form Text</h4>
                                                    </div>

                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="text1">Button 1</label>
                                                            <input type="text" class="form-control" id="text1" required="" value="<?php echo $text1; ?>">
                                                            <div class="form-text text-muted">Button on Campaign</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="text2">Button 2</label>
                                                            <input type="text" class="form-control" id="text2" required="" value="<?php echo $text2; ?>">
                                                            <div class="form-text text-muted">Button on Form Donate</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="text3">Small Title Campaign</label>
                                                            <input type="text" class="form-control" id="text3" required="" value="<?php echo $text3; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="text4">Small Title Donate</label>
                                                            <input type="text" class="form-control" id="text4" required="" value="<?php echo $text4; ?>">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row" style="padding: 30px 0 10px 0;">
                                                    
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Limited Donation - Button</h5>
                                                        <p class="card-text text-muted">Aktifkan jika ingin tombol donasi menjadi Disabled ketika donasi sudah terpenuhi.</p> 
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_limitted_donation_button">
                                                                <input type="checkbox" class="custom-control-input checkbox42" id="limitted_donation_button" data-id="1" <?php echo $checked4; ?> >
                                                                <label class="custom-control-label" for="limitted_donation_button"><?php echo $status_text4; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_form">Update <div class="spinner-border spinner-border-sm text-white update_form_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div><!--end card -body-->
                                    </div><!--end card-->                                                               
                                </div>
                            </div>                                                                             
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'payment') { ?>
            <?php check_license(); ?>
            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">

                           

                            <div class="row" style="margin-bottom: 30px;">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Payment</h5>  
                                    <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan pembayaran anda.</p>  
                                    
                                </div><!--end col-->
                            </div><!--end row-->

                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#general" role="tab" aria-selected="true">General</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#ipaymu" role="tab" aria-selected="false">iPaymu</a>
                                        </li>  
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tripay" role="tab" aria-selected="false">Tripay</a>
                                        </li>  
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#midtrans" role="tab" aria-selected="false">Midtrans</a>
                                        </li>                                      
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#moota" role="tab" aria-selected="false">Moota</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane p-3 active" id="general" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_payment" style="">
                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">Unique Number / Kode Unik</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Gunakan sesuai kebutuhan, pilih <i>None</i> jika tidak ingin ada tambahan kode unik pada total.</p> 
                                                                        <div class="form-group mb-0 row">
                                                                            <div class="col-md-12" style="padding-bottom: 5px;">
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="0" id="customRadio14" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='0') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadio14">None</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="1" id="customRadio13" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='1') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadio13">Fixed</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="2" id="customRadio12" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='2') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadio12">Range</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-3 unique_number_fixed" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='1') {}else{echo 'display:none;';}?>" id="">
                                                                                <div class="form-group">
                                                                                    <input type="text" class="form-control" id="unique_number_fixed" required="" placeholder="Contoh: 57" value="<?php echo $unique_number_value['unique_number'][0]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-2 unique_number_range" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                                                <div class="form-group">
                                                                                    <input type="text" class="form-control" id="unique_number_range1" required="" placeholder="Min" value="<?php echo $unique_number_value['unique_number'][1]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                                                </div>
                                                                            </div>

                                                                            <div class="unique_number_range titik_dua" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                                                <div class="form-group" style="text-align: center;padding-top: 12px;">
                                                                                    <p>:</p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-2 unique_number_range" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                                                <div class="form-group">
                                                                                    <input type="text" class="form-control" id="unique_number_range2" required="" placeholder="Max" value="<?php echo $unique_number_value['unique_number'][2]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>

                                                                <?php

                                                                // payment_setting = {"method1": ["instant", "Instant Payment", "1"]}

                                                                $instant_setting        = $payment_setting['method1'][2];
                                                                $instant_setting_title  = $payment_setting['method1'][1];
                                                                $va_setting             = $payment_setting['method2'][2];
                                                                $va_setting_title       = $payment_setting['method2'][1];
                                                                $transfer_setting       = $payment_setting['method3'][2];
                                                                $transfer_setting_title = $payment_setting['method3'][1];

                                                                if($instant_setting=='1'){
                                                                    $status_text1 = '<span>Active</span>';
                                                                    $checked1 = 'checked=""';
                                                                }else{
                                                                    $status_text1 = '<span>Not Active</span>';
                                                                    $checked1 = '';
                                                                }

                                                                if($va_setting=='1'){
                                                                    $status_text2 = '<span>Active</span>';
                                                                    $checked2 = 'checked=""';
                                                                }else{
                                                                    $status_text2 = '<span>Not Active</span>';
                                                                    $checked2 = '';
                                                                }

                                                                if($transfer_setting=='1'){
                                                                    $status_text3 = '<span>Active</span>';
                                                                    $checked3 = 'checked=""';
                                                                }else{
                                                                    $status_text3 = '<span>Not Active</span>';
                                                                    $checked3 = '';
                                                                }
                                                                

                                                                ?>

                                                                <div class="row" style="padding: 10px 0 10px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">Payment Method</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Aktifkan sesuai kebutuhan agar tampil pada list metode pembayaran.</p>
                                                                    </div>
                                                                </div>



                                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                                    <div class="col-md-4">
                                                                        <label for="subject"><b>Instant</b></label>
                                                                        <div class="form-group" style="margin-top: 10px;">
                                                                            <div class="custom-control custom-switch" id="checkbox_instant_setting">
                                                                                <input type="checkbox" class="custom-control-input checkbox1" id="instant_setting" data-id="1" <?php echo $checked1; ?> >
                                                                                <label class="custom-control-label" for="instant_setting"><?php echo $status_text1; ?></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <label for="subject">Title</label>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="instant_title" required="" placeholder="Title" value="<?php echo $instant_setting_title; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                                    <div class="col-md-4">
                                                                        <label for="subject"><b>Virtual Account</b></label>
                                                                        <div class="form-group" style="margin-top: 10px;">
                                                                            <div class="custom-control custom-switch" id="checkbox_va_setting">
                                                                                <input type="checkbox" class="custom-control-input checkbox1" id="va_setting" data-id="1" <?php echo $checked2; ?> >
                                                                                <label class="custom-control-label" for="va_setting"><?php echo $status_text2; ?></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <label for="subject">Title</label>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="va_title" required="" placeholder="Title" value="<?php echo $va_setting_title; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                                    <div class="col-md-4">
                                                                        <label for="subject"><b>Transfer</b></label>
                                                                        <div class="form-group" style="margin-top: 10px;">
                                                                            <div class="custom-control custom-switch" id="checkbox_transfer_setting">
                                                                                <input type="checkbox" class="custom-control-input checkbox1" id="transfer_setting" data-id="1" <?php echo $checked3; ?> >
                                                                                <label class="custom-control-label" for="transfer_setting"><?php echo $status_text3; ?></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <label for="subject">Title</label>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="transfer_title" required="" placeholder="Title" value="<?php echo $transfer_setting_title; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="padding: 40px 0 15px 0;">
                                                                    <div class="col-md-12">
                                                                        <h5 class="card-title mt-0">Bank Account</h5>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="padding: 0px 0 20px 0;margin-top: -10px;" id="data_bank">
                                                                    <?php 

                                                                    if($bank_account==null || $bank_account==''){

                                                                    }else{

                                                                    foreach ($bank_account as $key => $value) {

                                                                        $data_rekening = explode('_',$value);
                                                                        $no_rekening = $data_rekening[0];
                                                                        $an_rekening = $data_rekening[1];
                                                                        $payment_method = $data_rekening[2];
                                                                        $randid = d_randomString(3);
                                                                    ?>
                                                                    <div class="col-md-3 bank_opt_<?php echo $randid; ?> bank-col-1">
                                                                    <select class="form-control select_bank" id="" data-randid="<?php echo $randid; ?>" name="select_bank" style="height: 45px;" title="Bank">
                                                                        <option value="0">Pilih Bank</option>
                                                                        <?php foreach ($get_bank as $key2 => $value2) {
                                                                            if($value2->code=='mandiri_syariah' || $value2->code=='bni_syariah' || $value2->code=='bri_syariah'){}else{

                                                                            if (strpos($key, '@') !== false ) {
                                                                                $code_bank = explode('@',$key);
                                                                                $key = $code_bank[0];
                                                                            }
                                                                          ?>
                                                                           <option value="<?php echo $value2->code; ?>" <?php if($value2->code==$key){echo'selected';}?>><?php echo $value2->name; ?></option>
                                                                        <?php } } ?>
                                                                    </select>
                                                                    </div>
                                                                    <div class="col-md-2 bank_opt_<?php echo $randid; ?> bank-col-2">
                                                                        <div class="form-group">
                                                                            <input type="text" value="<?php echo $no_rekening; ?>" class="form-control label_norek" id="opt_label_norek_<?php echo $randid; ?>" required="" placeholder="No Rekening" style="font-size: 13px;padding-left: 12px;" value="" title="No Rekening">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 bank_opt_<?php echo $randid; ?> bank-col-3">
                                                                        <div class="form-group">
                                                                            <input type="text" value="<?php echo $an_rekening; ?>"  class="form-control label_an" id="opt_label_an_<?php echo $randid; ?>" required="" placeholder="Rek Atas Nama" style="font-size: 13px;padding-left: 12px;" value="" title="Rek Atas Nama">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 bank_opt_<?php echo $randid; ?> bank-col-4">
                                                                        <div class="form-group">
                                                                            <select class="form-control" id="select_method_<?php echo $randid; ?>" data-randid="<?php echo $randid; ?>" name="select_method" style="height: 45px;" title="Payment Method">
                                                                                <option value="0">Pilih Method</option>
                                                                                <option value="1" <?php if($payment_method=='1'){echo'selected';}?>>Instant</option>
                                                                                <option value="2" <?php if($payment_method=='2'){echo'selected';}?>>VA</option>
                                                                                <option value="3" <?php if($payment_method=='3'){echo'selected';}?>>Transfer</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 bank_opt_<?php echo $randid; ?> bank-col-5">
                                                                        <button type="button" class="btn btn-danger del_bank" title="Delete" data-randid="<?php echo $randid; ?>" style="margin-top: 5px;">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <?php } } ?>


                                                                </div>
                                                                <div class="row" style="margin-top: -15px;">
                                                                    <div class="col-md-12" style="padding-bottom: 20px;">
                                                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="add_bank">+ Add Bank</button>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="margin-top: 10px;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_payment">Update <div class="spinner-border spinner-border-sm text-white update_payment_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>


                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>
                                        <div class="tab-pane p-3" id="ipaymu" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_ipaymu" style="">

                                                                <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;">Bagi yang belum memiliki akun iPaymu, bisa mendaftar melalui link berikut: <br><a href="http://bit.ly/daftar-akun-ipaymu" target="_blank" style="color: #7680ff;text-decoration: underline;line-height: 1.3;">Daftar akun iPaymu sekarang</a></h5>
                                                                <!-- https://my.ipaymu.com/register/ -->
                                                                <br>

                                                                <?php if($plugin_license=='ULTIMATE'){ ?>
                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">iPaymu Mode</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Gunakan mode Sandbox untuk uji coba dan LIVE jika sistem sudah berjalan.</p> 
                                                                        <div class="form-group mb-0 row">
                                                                            <div class="col-md-12" style="padding-bottom: 5px;">
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="0" id="customRadioMode1" name="ipaymu_mode" class="custom-control-input" <?php if($ipaymu_mode=='0') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioMode1">Sandbox</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="1" id="customRadioMode2" name="ipaymu_mode" class="custom-control-input" <?php if($ipaymu_mode=='1') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioMode2">LIVE (Production)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="padding: 10px 0 10px 0;">
                                                                    <div class="col-md-8">
                                                                        <h5 class="card-title mt-0">iPaymu Virtual Account</h5>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="ipaymu_va" required="" placeholder="Virtual Account iPaymu" value="<?php echo $ipaymu_va; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                                    <div class="col-md-8">
                                                                        <h5 class="card-title mt-0">iPaymu API Key</h5>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="ipaymu_apikey" required="" placeholder="API Key iPaymu" value="<?php echo $ipaymu_apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row" style="background: #f6faff;padding: 20px 10px;border-radius: 8px;border: 1px solid #e8ebf3;margin-left: 0px;">
                                                                    
                                                                    <div class="col-md-12" style="padding-bottom: 20px;">
                                                                        <h5 class="card-title mt-0">iPaymu Code</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Perhatikan pada bagian "code" dan gunakan pada saat mensetting payment dengan iPaymu.</p> 
                                                                    </div>


                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Instant</b></label>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant1">QRIS</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: qris</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant2" data-parsley-multiple="groups" data-parsley-mincheck="2"  checked="">
                                                                                <label class="custom-control-label" for="instant2">Gopay</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: gopay</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant3">Ovo</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: ovo</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant4">Dana</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: dana</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant5" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant5">Linkaja</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: linkaja</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant6" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant6">Shopeepay</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: shopeepay</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Virtual Account (VA)</b></label>

                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va1">VA BCA</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va1">VA Mandiri</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: mandiri</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA BNI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bni</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA BRI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bri</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA BSI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bsi</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA Muamalat</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bmi</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va3">VA Cimb Niaga</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: cimb</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va4">VA Artha Graha</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bag</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va4">VA Permata</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: permata</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Transfer</b></label>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Transfer BCA</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Alfamart</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamart</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Indomaret</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: indomaret</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: 0px;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <button type="button" class="btn btn-primary px-4 py-2" id="update_ipaymu">Update iPaymu<div class="spinner-border spinner-border-sm text-white update_ipaymu_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>
                                                                <?php }else{ ?>

                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                                            <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php } ?>

                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>
                                        <div class="tab-pane p-3" id="tripay" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_tripay" style="">

                                                                <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;">Bagi yang belum memiliki akun Tripay, bisa mendaftar melalui link berikut: <br><a href="http://bit.ly/daftar-akun-tripay" target="_blank" style="color: #7680ff;text-decoration: underline;line-height: 1.3;">Daftar akun Tripay sekarang</a></h5>
                                                                <br>

                                                                <?php if($plugin_license=='ULTIMATE'){ ?>
                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">Tripay Mode</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Gunakan mode Sandbox untuk uji coba dan LIVE jika sistem sudah berjalan.<br>Masukkan API Key, Private Key, dan Kode Merchant sesuai dengan Mode-nya.</p> 
                                                                        <div class="form-group mb-0 row">
                                                                            <div class="col-md-12" style="padding-bottom: 5px;">
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="0" id="customRadioModeTripay1" name="tripay_mode" class="custom-control-input" <?php if($tripay_mode=='0') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioModeTripay1">Sandbox</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="1" id="customRadioModeTripay2" name="tripay_mode" class="custom-control-input" <?php if($tripay_mode=='1') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioModeTripay2">LIVE (Production)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="tripay_production" style="<?php if($tripay_mode=='1'){}else{echo'display: none;';}?>">
                                                                    <div class="row" style="padding: 10px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">Tripay API Key - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="tripay_apikey" required="" placeholder="API Key" value="<?php echo $tripay_apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">Tripay Private Key - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="tripay_privatekey" required="" placeholder="Private Key" value="<?php echo $tripay_privatekey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">Kode Merchant - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="tripay_merchant" required="" placeholder="Kode Merchant" value="<?php echo $tripay_merchant; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">URL Callback - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/callback_tripay/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            
                                                                <div id="tripay_sandbox" style="<?php if($tripay_mode=='0'){}else{echo'display: none;';}?>">
                                                                    <div class="row" style="padding: 10px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">Tripay API Key - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="tripay_apikey_sandbox" required="" placeholder="API Key" value="<?php echo $tripay_apikey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">Tripay Private Key - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="tripay_privatekey_sandbox" required="" placeholder="Private Key" value="<?php echo $tripay_privatekey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">Kode Merchant - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="tripay_merchant_sandbox" required="" placeholder="Kode Merchant" value="<?php echo $tripay_merchant_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">URL Callback - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/callback_tripay_sandbox/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="padding: 0px 0 35px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">Tripay QRIS</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Silahkan gunakan salah satu settingan QRIS dibawah jika mengalami kendala pada QRIS, Gopay, dan LinkAja tidak muncul.</p> 
                                                                        <div class="form-group mb-0 row">
                                                                            <div class="col-md-12" style="padding-bottom: 5px;">
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="QRISC" id="customRadioQris1" name="tripay_qris" class="custom-control-input" <?php if($tripay_qris=='QRISC' || $tripay_qris=='') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioQris1">QRIS (Customable)</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="QRIS2" id="customRadioQris2" name="tripay_qris" class="custom-control-input" <?php if($tripay_qris=='QRIS2') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioQris2">QRIS (DANA)</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="QRIS" id="customRadioQris3" name="tripay_qris" class="custom-control-input" <?php if($tripay_qris=='QRIS') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioQris3">QRIS (ShopeePay)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row" style="background: #f6faff;padding: 20px 10px;border-radius: 8px;border: 1px solid #e8ebf3;margin-left: 0px;">
                                                                    
                                                                    <div class="col-md-12" style="padding-bottom: 20px;">
                                                                        <h5 class="card-title mt-0">Tripay Code</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Perhatikan pada bagian "code" dan gunakan pada saat mensetting payment dengan Tripay.</p> 
                                                                    </div>


                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Instant</b></label>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant1">QRIS</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: qris</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant2" data-parsley-multiple="groups" data-parsley-mincheck="2"  checked="">
                                                                                <label class="custom-control-label" for="instant2">Gopay</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: gopay</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant3">Ovo</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: ovo</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant4">Dana</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: dana</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant5" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant5">Linkaja</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: linkaja</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant6" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant6">Shopeepay</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: shopeepay</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Virtual Account (VA)</b></label>

                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va1">VA BSI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bsi</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va1">VA Mandiri</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: mandiri</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA BNI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bni</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA BRI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bri</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va1">VA BCA</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA Muamalat</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bmi</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA Danamon</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: danamon</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA Permata</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: permata</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va3">VA Cimb Niaga</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: cimb</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va4">VA Maybank</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: maybank</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va4">VA Sinarmas</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: sinarmas</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va4">VA Sahabat Sampoerna</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: sampoerna</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Transfer</b></label>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Alfamart</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamart</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Alfamidi</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamidi</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Indomaret</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: indomaret</p> 
                                                                            </div>
                                                                        </div>
                                                                        <!--
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Credit Card</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: cc</p> 
                                                                            </div>
                                                                        </div>
                                                                        -->
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: 0px;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <button type="button" class="btn btn-primary px-4 py-2" id="update_tripay">Update Tripay<div class="spinner-border spinner-border-sm text-white update_tripay_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>
                                                                <?php }else{ ?>

                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                                            <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php } ?>

                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>
                                        <div class="tab-pane p-3" id="midtrans" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_tripay" style="">

                                                                <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;">Bagi yang belum memiliki akun Midtrans, bisa mendaftar melalui link berikut: <br><a href="http://bit.ly/daftar-akun-midtrans" target="_blank" style="color: #7680ff;text-decoration: underline;line-height: 1.3;">Daftar akun Midtrans sekarang</a></h5>
                                                                <br>

                                                                <?php if($plugin_license=='ULTIMATE'){ ?>
                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">Midtrans Mode</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Gunakan mode Sandbox untuk uji coba dan LIVE jika sistem sudah berjalan.<br>Masukkan Server Key, Client Key, dan Merchant ID sesuai dengan Mode-nya.</p> 
                                                                        <div class="form-group mb-0 row">
                                                                            <div class="col-md-12" style="padding-bottom: 5px;">
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="0" id="customRadioModeMidtrans1" name="midtrans_mode" class="custom-control-input" <?php if($midtrans_mode=='0') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioModeMidtrans1">Sandbox</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check-inline my-1">
                                                                                    <div class="custom-control custom-radio">
                                                                                        <input type="radio" value="1" id="customRadioModeMidtrans2" name="midtrans_mode" class="custom-control-input" <?php if($midtrans_mode=='1') { echo 'checked=""';}?> >
                                                                                        <label class="custom-control-label" for="customRadioModeMidtrans2">LIVE (Production)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="midtrans_production" style="<?php if($midtrans_mode=='1'){}else{ echo 'display:none';}?>">
                                                                    <div class="row" style="padding: 10px 0 10px 0;">
                                                                        <div class="col-md-8">
                                                                            <h5 class="card-title mt-0">Midtrans Server Key - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="midtrans_serverkey" required="" placeholder="Server Key" value="<?php echo $midtrans_serverkey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-8">
                                                                            <h5 class="card-title mt-0">Midtrans Client Key - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="midtrans_clientkey" required="" placeholder="Client Key" value="<?php echo $midtrans_clientkey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-8">
                                                                            <h5 class="card-title mt-0">Merchant ID - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="midtrans_merchant" required="" placeholder="Merchant ID" value="<?php echo $midtrans_merchant; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">URL Handling - Production</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/midtrans_handling/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="midtrans_sandbox" style="<?php if($midtrans_mode=='0'){}else{ echo 'display:none';}?>">
                                                                    <div class="row" style="padding: 10px 0 10px 0;">
                                                                        <div class="col-md-8">
                                                                            <h5 class="card-title mt-0">Midtrans Server Key - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="midtrans_serverkey_sandbox" required="" placeholder="Server Key" value="<?php echo $midtrans_serverkey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-8">
                                                                            <h5 class="card-title mt-0">Midtrans Client Key - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="midtrans_clientkey_sandbox" required="" placeholder="Client Key" value="<?php echo $midtrans_clientkey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-8">
                                                                            <h5 class="card-title mt-0">Merchant ID - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="midtrans_merchant_sandbox" required="" placeholder="Merchant ID" value="<?php echo $midtrans_merchant_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                                        <div class="col-md-9">
                                                                            <h5 class="card-title mt-0">URL Handling - Sandbox</h5>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/midtrans_handling_sandbox/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row" style="background: #f6faff;padding: 20px 10px;border-radius: 8px;border: 1px solid #e8ebf3;margin-left: 0px;">
                                                                    
                                                                    <div class="col-md-12" style="padding-bottom: 20px;">
                                                                        <h5 class="card-title mt-0">Midtrans Code</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Perhatikan pada bagian "code" dan gunakan pada saat mensetting payment dengan Midtrans.</p> 
                                                                    </div>


                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Instant</b></label>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant1">QRIS</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: qris</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant2" data-parsley-multiple="groups" data-parsley-mincheck="2"  checked="">
                                                                                <label class="custom-control-label" for="instant2">Gopay</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: gopay</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant3">Ovo</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: ovo</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant4">Dana</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: dana</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant5" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant5">Linkaja</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: linkaja</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="instant6" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="instant6">Shopeepay</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: shopeepay</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Virtual Account (VA)</b></label>

                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va1">VA Mandiri</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: mandiri</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va1">VA Permata</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: permata</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va4">VA BCA</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA BNI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bni</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="va2">VA BRI</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: bri</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="control-label"><b>Transfer</b></label>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Alfamart</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamart</p> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="checkbox my-2">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                                <label class="custom-control-label" for="transfer1">Indomaret</label>
                                                                                <p class="card-text text-muted" style="padding-left: 8px;">Code: indomaret</p> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: 0px;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <button type="button" class="btn btn-primary px-4 py-2" id="update_midtrans">Update Midtrans<div class="spinner-border spinner-border-sm text-white update_midtrans_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>
                                                                <?php }else{ ?>

                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                                            <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php } ?>

                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>                                           
                                        <div class="tab-pane p-3" id="moota" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_ipaymu" style="">

                                                                <?php if($license=='PRO' || $license=='ULTIMATE') { ?>

                                                                <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;line-height: 1.3;">Bagi yang belum memiliki akun Moota, bisa mendaftar melalui link berikut: <br><a href="https://app.moota.co?ref=wanessQp4" target="_blank" style="color: #7680ff;text-decoration: underline;">Daftar akun Moota sekarang</a></h5> 
                                                                 
                                                                <br>

                                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                                    <div class="col-md-8">
                                                                        <h5 class="card-title mt-0">URL Endpoint</h5>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/push_moota/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                                    <div class="col-md-8">
                                                                        <h5 class="card-title mt-0">Moota Secret Token</h5>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="moota_secret_token" required="" placeholder="Moota Secret Token" value="<?php echo $moota_secret_token; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                 <div class="row" style="padding: 0px 0 10px 0;">
                                                                    <div class="col-md-4">
                                                                        <h5 class="card-title mt-0">Moota Date Range</h5>
                                                                        <div class="form-group">
                                                                            <input type="number" class="form-control" id="moota_range" required="" placeholder="Moota Range" value="<?php echo $moota_range; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: 0px;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <button type="button" class="btn btn-primary px-4 py-2" id="update_moota">Update Moota<div class="spinner-border spinner-border-sm text-white update_moota_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>
                                                                <?php }else{ ?>

                                                                <div class="row" style="padding: 0px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                                            <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php } ?>
                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>


                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'notification') { ?>
            <?php check_license(); ?>
            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row" style="margin-bottom: 30px;">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Notification</h5>  
                                    <p class="card-text text-muted">Silahkan diatur notifikasi sesuai kebutuhan anda.</p>
                                </div><!--end col-->
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    
                                    <?php 

                                        if($wanotif_followup1_on=='1'){
                                            $status_text1 = '<span>Active</span>';
                                            $checked1 = 'checked=""';
                                        }else{
                                            $status_text1 = '<span>Not Active</span>';
                                            $checked1 = '';
                                        }

                                        if($wanotif_on=='1'){
                                            $status_text2 = '<span>Active</span>';
                                            $checked2 = 'checked=""';
                                        }else{
                                            $status_text2 = '<span>Not Active</span>';
                                            $checked2 = '';
                                        }

                                        if($telegram_on=='1'){
                                            $status_text3 = '<span>Active</span>';
                                            $checked3 = 'checked=""';
                                        }else{
                                            $status_text3 = '<span>Not Active</span>';
                                            $checked3 = '';
                                        }

                                        if($email_on=='1'){
                                            $status_text4 = '<span>Active</span>';
                                            $checked4 = 'checked=""';
                                        }else{
                                            $status_text4 = '<span>Not Active</span>';
                                            $checked4 = '';
                                        }



                                        


                                    ?>

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#wanotif" role="tab" aria-selected="true">Wanotif</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#telegram" role="tab" aria-selected="false">Telegram</a>
                                        </li> 
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#email" role="tab" aria-selected="false">Email</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane p-3 active" id="wanotif" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_wanotif" style="">

                                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                                    <div class="col-md-12">
                                                                        <p class="card-text text-muted">Wanotif memudahkan kita dalam mengirimkan pesan whatsapp otomatis ke Donatur. Semakin cepat donatur mendapatkan notifikasi transaksi donasi, semakin cepat donatur akan menyelesaikan proses pembayaran. Lembaga Donasi juga lebih terlihat Profesional.</p>
                                                                        <br>
                                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Wanotif<span></span></h5>
                                                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan agar Wanotif bisa mengirimkan whatsapp ke no donatur.</p> 
                                                                        <div class="form-group" style="margin-top: -5px;margin-bottom: 38px;">
                                                                            <div class="custom-control custom-switch" id="checkbox_wanotif_on">
                                                                                <input type="checkbox" class="custom-control-input checkbox1" id="wanotif_on" data-id="1" <?php echo $checked2; ?> >
                                                                                <label class="custom-control-label" for="wanotif_on"><?php echo $status_text2; ?></label>
                                                                            </div>
                                                                        </div>

                                                                        <h5 class="card-title mt-0">Wanotif Apikey - Default</h5>
                                                                        <p class="card-text text-muted">Dapatkan Apikey Wanotif <a href="https://wanotif.id/" target="_blank" class="link_href">disini.</a></p> 
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="wanotif_apikey" required="" placeholder="Wanotif Apikey" value="<?php echo $wanotif_apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                        </div>
                                                                        <p class="card-text text-muted" style="margin-top: -10px;margin-bottom: 20px;">Status : <span id="status_wanotif"></span></p> 
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                                    <div class="col-md-12">
                                                                        <h5 class="card-title mt-0">Wanotif Apikey - CS Rotator</h5>
                                                                        <p class="card-text text-muted" style="margin-bottom: 10px;">Tambahkan Apikey Wanotif pada masing-masing CS agar pengiriman langsung dari device Whatsapp CS anda (CS Rotator). Abaikan jika anda menggunakan 1 device pada pengiriman notifikasi whatsapp, karena otomatis akan menggunakan wanotif apikey default.</p> 
                                                                    </div>
                                                                    <div id="box_cs_apikey">
                                                                        <?php  

                                                                        if($jumlah_wanotif_cs>=1){

                                                                        $wanotif_apikey_cs = json_decode($wanotif_apikey_cs, true);
                                                                        foreach ($wanotif_apikey_cs['data'] as $key => $value) { 
                                                                            $rand3 = d_randomString(3);
                                                                        ?>

                                                                        <div class="form-group row container_cs_box" style="padding: 8px 10px 0 10px;margin-bottom:5px;" id="container_cs_<?php echo $rand3;?>" data-id="<?php echo $rand3;?>">
                                                                            <div class="col-lg-5 mo-b-15">
                                                                                <select class="form-control select_cs" id="select_cs_<?php echo $rand3;?>" data-randid="uj7" name="select_cs" style="height: 45px;" title="CS">
                                                                                <option value="0">Choose CS</option>
                                                                                <?php 
                                                                                $nama_csnya = '';
                                                                                foreach ( $data_userwp as $user ) {
                                                                                    $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
                                                                                    $roles_user = array_keys((array)$cap_user);
                                                                                    $rolenya_user = $roles_user[0];

                                                                                    if($rolenya_user=='cs'){
                                                                                        $selected = '';
                                                                                        if($user->ID==$value[0]){
                                                                                            $selected = 'selected';
                                                                                            $nama_csnya = $user->display_name;
                                                                                        }
                                                                                        echo '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';

                                                                                        
                                                                                    }
                                                                                }
                                                                                
                                                                                ?>
                                                                                </select>                                   
                                                                            </div> 
                                                                            <div class="col-lg-6" style="padding-left: 0;">
                                                                                <input class="form-control" type="text" id="apikey_cs_<?php echo $rand3;?>" value="<?php echo $value[1];?>" placeholder="Wanotif Apikey">
                                                                            </div>              
                                                                            <div class="col-lg-1">
                                                                                <button type="button" class="btn btn-danger del_apikey" title="Delete" data-randid="<?php echo $rand3;?>" style="margin-top: 5px;"><i class="fas fa-minus"></i></button>
                                                                            </div>                                                   
                                                                        </div>
                                                                        <?php } } ?>
                                                                    </div>
                                                                    <div class="col-md-12" style="margin-top: 15px;">
                                                                        <button type="button" class="btn btn-outline-primary add_apikey" data-randid=""><i class="fas fa-plus" style="font-size:9px;"></i>&nbsp;&nbsp;Add Apikey</button>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="padding: 40px 0 20px 0;">
                                                                    <div class="col-md-12">
                                                                        <h5 class="card-title mt-0">Followup Message</h5>
                                                                        <p class="card-text text-muted">Pesan yang akan dikirimkan pertama kali ke Donatur setelah berhasil submit donasi.</a></p> 
                                                                        <div class="form-group" style="border-radius: 6px;background: #f6faff;padding: 20px 20px;margin-bottom: 20px !important;box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);-webkit-box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);">
                                                                            <textarea class="form-control" rows="8" id="wanotif_message" style="font-size: 13px;"><?php echo $wanotif_message; ?></textarea>
                                                                        </div>
                                                                        <div class="form-group" style="padding-top: 20px;">
                                                                            <br>
                                                                            <h5 class="card-title mt-0">Followup Message dengan Followup 1</h5>
                                                                            <div class="custom-control custom-switch" id="wanotif_followup1_on">
                                                                                <input type="checkbox" class="custom-control-input checkbox1" id="wanotif_followup1" data-id="1" <?php echo $checked1; ?> >
                                                                                <label class="custom-control-label" for="wanotif_followup1"><?php echo $status_text1; ?></label>
                                                                            </div>
                                                                        </div>
                                                                        <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin menggunakan text followup 1 yang ada di <a href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&action=settings#followup1') ?>" class="link_href">Followup Dashboard</a>. Keuntungan mengaktfikan ini adalah ketika terjadi followup maka akan ketahuan button followup 1 akan berwarna hijau.</p> 
                                                                        </div>

                                                                </div>

                                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <h5 class="card-title mt-0">Payment Success Message</h5>
                                                                        <p class="card-text text-muted">Pesan yang akan dikirimkan ke Donatur setelah sistem menerima pembayaran. Notifikasi akan di trigger melalui transaksi di Moota, iPaymu, dan Tripay secara otomatis.</a></p> 
                                                                        <div class="form-group" style="border-radius: 6px;background: #f6faff;padding: 20px 20px;margin-bottom: 20px !important;box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);-webkit-box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);">
                                                                            <textarea class="form-control" rows="8" id="wanotif_message2" style="font-size: 13px;"><?php echo $wanotif_message2; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="margin-top: 10px;">
                                                                    <div class="col-md-12">
                                                                        
                                                                        <button type="button" class="btn btn-primary px-4 py-2" id="update_wanotif" style="margin-right: 8px;">Update Wanotif<div class="spinner-border spinner-border-sm text-white update_wanotif_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                        <button type="button" class="btn btn-outline-warning waves-effect waves-light px-4 py-2" id="test_wanotif">Test Wanotif<div class="spinner-border spinner-border-sm text-white" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <br><br><br>
                                                                    <h5 class="card-text">Note</h5>
                                                                    <hr>
                                                                    <p class="card-text text-muted">Silahkan tambahkan shortcode berikut untuk memanggil value dari setiap donasi yang masuk.</p> 
                                                                    <ul>
                                                                        <li>{name} : Nama donatur</li>
                                                                        <li>{email} : Email donatur</li>
                                                                        <li>{whatsapp} : Whatsapp donatur</li>
                                                                        <li>{comment} : Pesan atau Doa dari donatur</li>
                                                                        <li>{total} : Nominal donasi dari donatur</li>
                                                                        <li>{payment_number} : No Rekening</li>
                                                                        <li>{payment_code} : Nama Bank atau Dompet Digital</li>
                                                                        <li>{payment_account} : Nama Pemilik Rekening</li>
                                                                        <li>{campaign_title} : Judul program dari campaign</li>
                                                                        <li>{fundraiser} : Nama Fundraiser pada campaign jika ada</li>
                                                                        <li>{date} : Tanggal donasi dibuat</li>
                                                                        <li>{invoice_id} : No Invoice ID donasi</li>
                                                                        <li>{cs_name} : Nama CS, pastikan anda menggunakan CS Rotator</li>
                                                                        <li>{link_ekuitansi} : Link untuk mendownload ekuitansi</li>

                                                                    </ul>
                                                                    <p class="card-text text-muted">Contoh :</p> 
                                                                    <textarea class="form-control" rows="6" disabled="" style="color: #435177;">Terimakasih *Bpk/Ibu {name}* atas Donasi yang akan Anda berikan. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.

Untuk Donasinya sejumlah *{total}* mohon ditransfer ke *{payment_account}* dengan No Rek *{payment_number}*. 
Terimakasih 😊🙏</textarea>
                                                                </div>

                                                               
                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>

                                        <div class="tab-pane p-3" id="telegram" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_telegram" style="">
                                                                <p class="card-text text-muted">Gunakan Telegram Notif untuk memantau setiap donasi yang masuk dan notif konfirmasi manual yang dilakukan oleh donatur. Anda bisa mengirimkan notif ke Channel Telegram anda. Free, tinggal setting dan buat Bot Telegram anda untuk mengirimkan notif.</p>
                                                                <br>
                                                                <h5 class="card-title mt-0" style="padding-top: 0px;">Telegram<span></span></h5>
                                                                <p class="card-text text-muted" style="margin-top:0px;">Aktifkan agar Bot Telegram anda bisa mengirimkan notif.</p> 
                                                                <div class="form-group" style="margin-top: -5px;">
                                                                    <div class="custom-control custom-switch" id="checkbox_telegram_on">
                                                                        <input type="checkbox" class="custom-control-input checkbox1" id="telegram_on" data-id="1" <?php echo $checked3; ?> >
                                                                        <label class="custom-control-label" for="telegram_on"><?php echo $status_text3; ?></label>
                                                                    </div>
                                                                </div>
                                                                
                                                                <br>
                                                                <h5 class="card-title mt-0" style="padding-top: 0px;">Telegram Bot Token<span></span></h5>
                                                                <div class="form-group" style="padding-bottom: 20px;">
                                                                    <input type="text" class="form-control" id="token" required="" placeholder="Your API Key" value="<?php echo $token; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>

                                                                <hr>

                                                                <div class="row" style="padding: 20px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">Telegram Notif (New Donate)</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Notif ini untuk mendapatkan notifikasi dari setiap donasi baru yang masuk. Silahkan klik tombol <b>+Notif</b> dibawah untuk menambahkan beberapa notifikasi ke channel telegram. Anda bisa menambahkan pesan notifikasi telegram yang berbeda ke channel yang berbeda.</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="padding: 10px;padding-top: 20px;" id="box_notif">
                                                                    
                                                                    <?php 
                                                                    
                                                                    $no = 1;
                                                                    foreach($telegram_send_to as $key => $value) {
                                                                            $message_tele = $value->message;
                                                                    ?>
                                                                    
                                                                    <div id="box_tele_<?php echo $no; ?>" class="col-md-12 box_telegram_message">
                                                                        <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Notif <?php echo $no; ?></h5>
                                                                        <?php if($no!=1) { ?> 
                                                                        <div class="form-group" style="position: absolute;right: 0;margin-right: 20px;margin-top: -10px;">
                                                                        <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-danger  waves-effect waves-light btn-xs del_notif" title="Delete Notif"><i class="mdi mdi-close"></i> Del&nbsp;</button>
                                                                        </div>
                                                                        <?php }?>
                                                                        <hr>
                                                                        <label style="margin-top: 20px;">Custom Channel :</label> 
                                                                        <textarea id="myTags_<?php echo $no; ?>" class="tagit"></textarea>
                                                                        <label>Message :</label>
                                                                        <div class="form-group">
                                                                            <textarea class="form-control textarea_text tele_message" rows="5" placeholder="Message"><?php echo $message_tele; ?></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <?php $no++; } ?>

                                                                </div>

                                                                <div class="row" style="margin-top: 15px;">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="add_notif"><i class="mdi mdi-plus"></i> Notif&nbsp;&nbsp;</button>
                                                                    </div>
                                                                </div>

                                                                <?php                  
                                                                    $message_tele_mc = '';
                                                                    foreach($telegram_manual_confirmation as $key => $value) {
                                                                        $message_tele_mc = $value->message;
                                                                    }
                                                                ?>

                                                                <div class="row" style="padding: 30px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <hr>
                                                                        <br>
                                                                        <h5 class="card-title mt-0">Telegram Notif (Manual Confirmation)</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Notif ini berfungsi untuk mendapatkan notifikasi dari setiap donatur yang memberikan konfirmasi pembayaran secara manual.</p>
                                                                    </div>
                                                                </div>

                                                                <div id="box_tele_99" class="col-md-12 box_telegram_message2">
                                                                    <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Notif Manual Confirmation</h5>
                                                                    
                                                                    <hr>
                                                                    <label style="margin-top: 20px;">Custom Channel :</label> 
                                                                    <textarea id="myTags_manual_confirmation" class="tagit tagit2"></textarea>
                                                                    <label>Message :</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control textarea_text tele_message2" rows="5" placeholder="Message"><?php echo $message_tele_mc; ?></textarea>
                                                                    </div>
                                                                </div>

                                                               
                                                                <div class="row" style="margin-top: 20px;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <button type="button" class="btn btn-primary px-4 py-2" id="update_telegram" style="margin-right: 10px;">Update Telegram<div class="spinner-border spinner-border-sm text-white update_telegram_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                        <button type="button" class="btn btn-outline-warning waves-effect waves-light px-4 py-2" id="test_telegram">Test Telegram<div class="spinner-border spinner-border-sm text-white" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>

                                                                 <div style="margin-top: 40px;">
                                                                    <h5 class="card-text">Note</h5> 
                                                                    <ul>
                                                                        <li class="text-muted">Public: <span class="text_highlight">@</span><b>yourchannel</b></li>
                                                                        <li class="text-muted">Private: <span class="text_highlight">-100</span><b>XXXXXXXXXX</b></li>
                                                                        <li class="text-muted">Lebih lengkap cara mengetahui Channel ID bisa di <a href="https://bit.ly/donasiajanote" target="_blank" class="link_href">link berikut ini</a>.</li>
                                                                </div>

                                                                <div>
                                                                    <!-- <br><br><br> -->
                                                                    <!-- <h5 class="card-text">Note</h5> -->
                                                                    <hr>
                                                                    <p class="card-text text-muted">Silahkan tambahkan shortcode berikut untuk memanggil value dari setiap donasi yang masuk.</p> 
                                                                    <ul>
                                                                        <li>{name} : Nama donatur</li>
                                                                        <li>{email} : Email donatur</li>
                                                                        <li>{whatsapp} : Whatsapp donatur</li>
                                                                        <li>{comment} : Pesan atau Doa dari donatur</li>
                                                                        <li>{total} : Nominal donasi dari donatur</li>
                                                                        <li>{payment_number} : No Rekening</li>
                                                                        <li>{payment_code} : Nama Bank atau Dompet Digital</li>
                                                                        <li>{payment_account} : Nama Pemilik Rekening</li>
                                                                        <li>{campaign_title} : Judul program dari campaign</li>
                                                                        <li>{fundraiser} : Nama Fundraiser pada campaign jika ada</li>
                                                                        <li>{date} : Tanggal donasi dibuat</li>
                                                                        <li>{invoice_id} : No Invoice ID donasi</li>
                                                                        <li>{cs_name} : Nama CS, pastikan anda menggunakan CS Rotator</li>
                                                                        <li>{link_ekuitansi} : Link untuk mendownload ekuitansi</li>
                                                                    </ul>
                                                                    <p class="card-text text-muted">Contoh :</p> 
                                                                    <textarea class="form-control" rows="7" disabled="" style="color: #435177;">Alhamdulillah ada donasi baru yang masuk sebesar *{total}*,
Donatur : {name}
Whatsapp : {whatsapp}
Email : {email}
Pesan : {comment}
Program : {campaign_title}
Pengiriman ke {payment_number} - {payment_account}
</textarea>
                                                                </div>



                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>

                                         <div class="tab-pane p-3" id="email" role="tabpanel" style="padding-left: 5px !important;">
                                            <!-- content -->
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                                    <div class="card card-border" style="border: 0;padding: 0;">
                                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                            <div id="data_email" style="">
                                                                <p class="card-text text-muted">Gunakan Email untuk mengirimkan pesan ke Customer/Donatur. Bisa juga anda gunakan untuk mengirimkan notifikasi email ke Owner/Pemilik Yayasan atau Organisasi.</p>
                                                                <br>
                                                                <h5 class="card-title mt-0" style="padding-top: 0px;">Email<span></span></h5>
                                                                <p class="card-text text-muted" style="margin-top:0px;">Aktifkan email agar anda bisa mengirim email.</p> 
                                                                <div class="form-group" style="margin-top: -5px;">
                                                                    <div class="custom-control custom-switch" id="checkbox_email_on">
                                                                        <input type="checkbox" class="custom-control-input checkbox1" id="email_on" data-id="1" <?php echo $checked4; ?> >
                                                                        <label class="custom-control-label" for="email_on"><?php echo $status_text4; ?></label>
                                                                    </div>
                                                                </div>
                                                                
                                                                <hr>

                                                                <div class="row" style="padding: 20px 0 15px 0;">
                                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                                        <h5 class="card-title mt-0">Email Message</h5>
                                                                        <p class="card-text text-muted" style="margin-top: -5px;">Silahkan klik tombol <b>+Email</b> dibawah untuk menambahkan email yang akan dikirimkan. Email ini akan dikirimkan pada saat terjadi submit donasi. Anda bisa menambahkan sampai 3 pesan email.</p> 
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="padding: 10px;padding-top: 20px;" id="box_email">
                                                                    
                                                                    <?php 
                                                                    
                                                                    $no = 1;
                                                                    foreach($email_send_to as $key => $value) {

                                                                            $message_email = $value->message;
                                                                            $message_email = str_replace('<p>linebreak</p>', '', $message_email);
                                                                            $message_email = str_replace('linebreak', '', $message_email);

                                                                            if (isset($value->subject)){
                                                                               $subject_email = $value->subject;
                                                                            }else{
                                                                               $subject_email = '';
                                                                            }
                                                                            if (isset($value->email)){
                                                                               $send_to = $value->email;
                                                                            }else{
                                                                               $send_to = '';
                                                                            }
                                                                            if (isset($value->emailcc)){
                                                                               $emailnyacc = $value->emailcc;
                                                                            }else{
                                                                               $emailnyacc = '';
                                                                            }
                                                                            if (isset($value->emailbcc)){
                                                                               $emailnyabcc = $value->emailbcc;
                                                                            }else{
                                                                               $emailnyabcc = '';
                                                                            }

                                                                    ?>
                                                                    
                                                                    <div id="box_email_<?php echo $no; ?>" class="col-md-12 box_email_message show_box">
                                                                        <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email <?php echo $no; ?></h5>
                                                                        <?php if($no!=1) { ?> 
                                                                        <div class="form-group" style="position: absolute;right: 0;margin-right: 20px;margin-top: -10px;">
                                                                        <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-danger  waves-effect waves-light btn-xs del_notif_email" title="Delete Notif"><i class="mdi mdi-close"></i> Del&nbsp;</button>
                                                                        </div>
                                                                        <?php }?>
                                                                        <hr>

                                                                        <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                                                            <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                                                            <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                                                        </div>

                                                                        <div class="form-group" style="margin-top: 35px;">
                                                                            <label title="Wajib di isi alamat email">Send to* :</label> 
                                                                            <!-- <textarea id="myTagsEmail_<?php echo $no; ?>" class="tagit tagitemail"></textarea> -->
                                                                            <input type="text" class="form-control send_to_<?php echo $no; ?>"  required="" placeholder="example@gmail.com atau {email}" value="<?php echo $send_to; ?>"  style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label title="Wajib di isi subject email">Subject* :</label>
                                                                            <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="<?php echo $subject_email; ?>">
                                                                        </div>
                                                                        <div class="form-group email_cc" style="margin: 0;<?php if($emailnyacc==''){echo'display:none;';}?>" >
                                                                            <label style="margin-top: 0px;">CC :</label> 
                                                                            <textarea id="myTagsEmailCC_<?php echo $no; ?>" class="tagit tagitemailcc"></textarea>
                                                                        </div>
                                                                        <div class="form-group email_bcc" style="margin: 0;<?php if($emailnyabcc==''){echo'display:none;';}?>">
                                                                            <label style="margin-top: 0px;">BCC :</label> 
                                                                            <textarea id="myTagsEmailBCC_<?php echo $no; ?>" class="tagit tagitemailbcc"></textarea>
                                                                        </div>
                                                                        
                                                                        <label title="Wajib di isi message email">Message* :</label>
                                                                        <div class="form-group">
                                                                           <textarea id="email_message_<?php echo $no; ?>" name="area">
                                                                               <?php echo $message_email; ?>
                                                                           </textarea> 

                                                                        </div>
                                                                    </div>

                                                                    <?php $no++; } ?>

                                                                <?php

                                                                $jumlah_email = count($email_send_to);
                                                                $email_tambahan = 3;

                                                                for ($i = $jumlah_email; $i <= $email_tambahan; $i++){

                                                                    if($i>$jumlah_email){ ?>

                                                                    <div id="box_email_<?php echo $i; ?>" class="col-md-12 box_email_message">
                                                                        <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email <?php echo $i; ?></h5>
                                                                        <?php if($i!=1) { ?> 
                                                                        <div class="form-group" style="position: absolute;right: 0;margin-right: 20px;margin-top: -10px;">
                                                                        <button data-id="<?php echo $i; ?>" type="button" class="btn btn-outline-danger  waves-effect waves-light btn-xs del_notif_email" title="Delete Notif"><i class="mdi mdi-close"></i> Del&nbsp;</button>
                                                                        </div>
                                                                        <?php }?>
                                                                        <hr>

                                                                        <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                                                            <button data-id="<?php echo $i; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                                                            <button data-id="<?php echo $i; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                                                        </div>

                                                                        <div class="form-group" style="margin-top: 0px;">
                                                                            <label style="margin-top: 20px;">Send to*:</label> 
                                                                            <!-- <textarea id="myTagsEmail_<?php echo $i; ?>" class="tagit tagitemail"></textarea> -->
                                                                            <input type="text" class="form-control send_to_<?php echo $i; ?>"  required="" placeholder="example@gmail.com atau {email}" value="" style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Subject*:</label>
                                                                            <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="">
                                                                        </div>

                                                                        <div class="form-group email_cc" style="margin: 0;display:none;" >
                                                                            <label style="margin-top: 0px;">CC :</label> 
                                                                            <textarea id="myTagsEmailCC_<?php echo $i; ?>" class="tagit tagitemailcc"></textarea>
                                                                        </div>
                                                                        <div class="form-group email_bcc" style="margin: 0;display:none;" >
                                                                            <label style="margin-top: 0px;">BCC :</label> 
                                                                            <textarea id="myTagsEmailBCC_<?php echo $i; ?>" class="tagit tagitemailbcc"></textarea>
                                                                        </div>

                                                                        <label>Message*:</label>
                                                                        <div class="form-group">
                                                                           <textarea id="email_message_<?php echo $i; ?>" name="area"></textarea> 

                                                                        </div>
                                                                    </div>

                                                                   <?php }

                                                                    
                                                                    } // end for

                                                                ?>


                                                                </div>


                                                                <div class="row" style="margin-top: 15px;margin-bottom: 30px;">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="add_notif_email"><i class="mdi mdi-plus"></i> Email&nbsp;&nbsp;</button>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="padding: 0px 0 20px 10px;">
                                                                    <div class="col-md-12" style="padding-left: 0;">
                                                                        <hr>
                                                                        <br>
                                                                        <h5 class="card-title mt-0">Payment - Success Email Message</h5>
                                                                        <p class="card-text text-muted">Email ini akan dikirimkan ke Donatur setelah sistem menerima pembayaran. Notifikasi akan di trigger melalui transaksi di Moota, iPaymu, dan Tripay secara otomatis dan juga update data donasi secara manual.</a></p>
                                                                    </div>




                                                                    <div id="box_email_4" class="col-md-12 box_email_message show_box2" style="margin-top: 20px;">
                                                                        <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email Message</h5>
                                                                        <hr>

                                                                        <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                                                            <button data-id="4" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                                                            <button data-id="4" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                                                        </div>

                                                                        <div class="form-group" style="margin-top: 35px;">
                                                                            <label title="Wajib di isi alamat email">Send to* :</label>
                                                                            <input type="text" class="form-control send_to_4"  required="" placeholder="{email}" value="<?php echo $s_send_to; ?>"  style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label title="Wajib di isi subject email">Subject* :</label>
                                                                            <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="<?php echo $s_subject_email; ?>">
                                                                        </div>
                                                                        <div class="form-group email_cc" style="margin: 0;<?php if($s_emailnyacc==''){echo'display:none;';}?>" >
                                                                            <label style="margin-top: 0px;">CC :</label> 
                                                                            <textarea id="myTagsEmailCC_s" class="tagit tagitemailcc"></textarea>
                                                                        </div>
                                                                        <div class="form-group email_bcc" style="margin: 0;<?php if($s_emailnyabcc==''){echo'display:none;';}?>">
                                                                            <label style="margin-top: 0px;">BCC :</label> 
                                                                            <textarea id="myTagsEmailBCC_s" class="tagit tagitemailbcc"></textarea>
                                                                        </div>
                                                                        
                                                                        <label title="Wajib di isi message email">Message* :</label>
                                                                        <div class="form-group">
                                                                           <textarea id="s_message_email" name="area">
                                                                               <?php echo $s_message_email; ?>
                                                                           </textarea> 

                                                                        </div>
                                                                    </div>

                                                                </div>

                                                               
                                                                <div class="row" style="margin-top: 20px;">
                                                                    <div class="col-md-12">
                                                                        <hr>
                                                                        <br>
                                                                        <button type="button" class="btn btn-primary px-4 py-2" id="update_email" style="margin-right: 10px;">Update Email<div class="spinner-border spinner-border-sm text-white update_email_loading" style="margin-left: 3px;display: none;"></div></button>
                                                                        <button type="button" class="btn btn-outline-warning waves-effect waves-light px-4 py-2" id="test_email">Test Email<div class="spinner-border spinner-border-sm text-white" style="margin-left: 3px;display: none;"></div></button>
                                                                    </div>
                                                                </div>

                                                                 <div style="margin-top: 40px;">
                                                                    <h5 class="card-text">Note</h5> 
                                                                </div>

                                                                <div>
                                                                    <!-- <br><br><br> -->
                                                                    <!-- <h5 class="card-text">Note</h5> -->
                                                                    <hr>
                                                                    <p class="card-text text-muted">Silahkan tambahkan shortcode berikut untuk memanggil value dari setiap donasi yang masuk.</p> 
                                                                    <ul>
                                                                        <li>{name} : Nama donatur</li>
                                                                        <li>{email} : Email donatur</li>
                                                                        <li>{whatsapp} : Whatsapp donatur</li>
                                                                        <li>{comment} : Pesan atau Doa dari donatur</li>
                                                                        <li>{total} : Nominal donasi dari donatur</li>
                                                                        <li>{payment_number} : No Rekening</li>
                                                                        <li>{payment_code} : Nama Bank atau Dompet Digital</li>
                                                                        <li>{payment_account} : Nama Pemilik Rekening</li>
                                                                        <li>{campaign_title} : Judul program dari campaign</li>
                                                                        <li>{fundraiser} : Nama Fundraiser pada campaign jika ada</li>
                                                                        <li>{date} : Tanggal donasi dibuat</li>
                                                                        <li>{invoice_id} : No Invoice ID donasi</li>
                                                                        <li>{cs_name} : Nama CS, pastikan anda menggunakan CS Rotator</li>
                                                                        <li>{link_ekuitansi} : Link untuk mendownload ekuitansi</li>
                                                                    </ul>
                                                                    <p class="card-text text-muted">Contoh :</p> 
                                                                    <textarea class="form-control" rows="7" disabled="" style="color: #435177;">Terimakasih *{name}* atas Donasi yang akan Anda berikan pada program *{campaign_title}* sebesar *{total}*. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.

Untuk Donasinya mohon ditransfer ke *{payment_account}* dengan No Rek *{payment_number}*. 
Terimakasih 😊🙏


</textarea>
                                                                </div>



                                                            </div>

                                                        </div><!--end card -body-->
                                                    </div><!--end card-->                                                               
                                                </div>
                                            </div>     
                                            <!-- end content -->
                                        </div>
                                        




                                    </div>
                                    
                                </div>
                            </div>                                                                          
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'socialproof') { ?>
            <?php check_license(); ?>
            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Social Proof</h5>  
                                    <p class="card-text text-muted">Silahkan diatur social proof sesuai kebutuhan anda.</p>  
                                    <hr>   

                                    <?php


                                    $socialproof_setting  = json_decode($socialproof_settings, true);
                                    $popup_style    = $socialproof_setting['settings'][0];
                                    $position       = $socialproof_setting['settings'][1];
                                    $time_set       = $socialproof_setting['settings'][2];
                                    $delay          = $socialproof_setting['settings'][3];
                                    $data_load      = $socialproof_setting['settings'][4];

                                    if($popup_style=='rounded'){
                                        $set_style = 's-rounded';
                                    }elseif($popup_style=='flying_boxed'){
                                        $set_style = 's-flying';
                                    }elseif($popup_style=='flying_rounded'){
                                        $set_style = 's-rounded s-flying';
                                    }else{
                                        $set_style = '';
                                    }

                                    ?>

                                    <style>
                                    .donasiaja-socialproof{ -webkit-transition: all 0.5s ease-in-out;     -moz-transition: all 0.5s ease-in-out;     -o-transition: all 0.5s ease-in-out;     -ms-transition: all 0.5s ease-in-out;     transition: all 0.5s ease-in-out;line-height: 1.5;border-radius:6px;max-width:360px;height:75px;padding-right:30px!important;z-index:1 !important;background:#fff!important;box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.09);}.donasiaja-socialproof .toast-close{position:absolute;right:0;color:#fff;margin-top:-16px!important;background:#0000004f;width:25px!important;height:25px!important;font-size:13px!important;text-align:center!important;padding:2px!important;opacity:1;top:10px}.dsproof-avatar{border-radius:4px;width:50px;height:50px;text-align:center;position:absolute;margin-left:-7px;margin-top:0px;font-size:32px;font-weight:700;color:#fffc;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-avatar img{width:50px;height:50px;border-radius:4px;}.dsproof-content{margin-left:54px;color:#888;font-size:11px;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-name{font-size:13px;font-weight:700;color:#35363c;position:absolute;margin-top:-3px}.dsproof-title{color:#656577;padding-top:16px;padding-bottom:2px}.dsproof-verified{font-size:10px;color:#b0b0c6;margin-bottom:2px;}.dsproof-verified span{padding-left:15px}.dsproof-verified img{width:12px;position:absolute;margin-top:2px}.toastify{padding:12px 20px;padding-top:12px!important;color:#fff;display:inline-block;box-shadow:0 3px 6px -1px rgba(0,0,0,.12),0 10px 36px -4px rgba(77,96,232,.3);background:-webkit-linear-gradient(315deg,#73a5ff,#5477f5);background:linear-gradient(135deg,#73a5ff,#5477f5);opacity:0;transition:all .4s cubic-bezier(.215,.61,.355,1);cursor:pointer;text-decoration:none;z-index:2147483647}.toastify.on{opacity:1}.toast-close{opacity:.4;padding:0 5px}.toastify-right{right:15px}.toastify-left{left:15px}.toastify-top{top:-150px}.toastify-bottom{bottom:-150px}.toastify-rounded{}.toastify-avatar{width:1.5em;height:1.5em;margin:-7px 5px;border-radius:2px}.toastify-center{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content;max-width:-moz-fit-content}@media only screen and (max-width:360px){.toastify-left,.toastify-right{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content}} .donasiaja-socialproof.s-rounded .dsproof-avatar{border-radius: 50px;} .donasiaja-socialproof.s-rounded .dsproof-avatar img{border-radius: 50px;}.donasiaja-socialproof.s-rounded {min-height:75px !important;}.donasiaja-socialproof.s-rounded .dsproof-avatar {margin-top:0px;}.donasiaja-socialproof.s-flying{background:transparent!important;box-shadow:none}.donasiaja-socialproof.s-flying .dsproof-avatar{box-shadow:0 3px 6px -1px rgba(0,0,0,.06),0 10px 36px -4px rgba(77,96,232,.09)}.donasiaja-socialproof.s-flying .dsproof-content{background:#fff;padding:10px 20px 10px 16px;border-radius:4px;box-shadow:0 3px 6px -1px rgba(0,0,0,.06),0 10px 36px -4px rgba(77,96,232,.09)}#box-socialproof-setting{background:#e9eef4;padding:30px 15px;min-height:220px;border-radius:4px;margin:0}#time_set_preview{padding-left:0}.top_left{top:0;left:0;margin-top:105px;margin-left:25px}.top_right{top:0;right:0;margin-top:105px;margin-right:25px}.top_right.s-flying{margin-top:105px;margin-right:5px}.bottom_left{bottom:0;left:0;margin-bottom:70px;margin-left:25px}.bottom_right{bottom:0;right:0;margin-bottom:70px;margin-right:25px}.bottom_right.s-flying{margin-bottom:70px;margin-right:5px}
                                        </style>

                                        <div class="row" id="box-socialproof-setting">

                                            <div class="toastify on donasiaja-socialproof <?php echo $set_style; ?> <?php echo $position; ?>" style="background: rgba(0, 0, 0, 0) linear-gradient(to right, rgb(255, 255, 255), rgb(255, 255, 255)) repeat scroll 0% 0%; transform: translate(0px);position: absolute;"><div class="dsproof-container" id="dja0tp32owt"><div class="dsproof-avatar" style="background:#4FC0E8;">D</div><div class="dsproof-avatar" style="display:none;"><img src=""></div><div class="dsproof-content"><div class="dsproof-name">DonasiAja</div><div class="dsproof-title" id="dsproof-title"><?php echo $socialproof_text; ?></div><div class="dsproof-verified"><img src="<?php echo plugin_dir_url( __FILE__ ).'../assets/images/check.png';?>"><span>Verified <span id="time_set_preview" <?php if($time_set=='hide'){echo'style="display:none;"';}?>>- 10 menit yang lalu</span></span></div><div></div></div></div></div>

                                        </div>

                                        <div style="background: #f0f6fb;padding: 10px 20px;border-radius: 4px;margin-top: -5px;font-weight: bold;font-size: 15px;color: #35363c;">
                                            <span>[donasiaja_socialproof]</span>
                                            <div class="copy-shortcode-socialproof" data-salin="[donasiaja_socialproof]" style="position: absolute;right: 0;margin-right: 30px;margin-top: -20px;font-size: 11px;color: #96a9c1;font-weight: normal;cursor: pointer;">Copy Shortcode</div>
                                        </div>


                                </div><!--end col-->
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: 0px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_general" style="">

                                                <div class="row" style="padding: 20px 0 20px 0;margin-top: -10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Text Description</h5>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="socialproof_text" required="" placeholder="Description" value="<?php echo $socialproof_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>

                                                        <ul class="text-muted" style="margin-top: -10px;">
                                                            <li>{campaign_title} : Judul campaign</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div id="row-style" class="row" style="padding: 10px 0 20px 0;">
                                                    <div class="col-md-6">
                                                        <h5 class="card-title mt-0">Popup Style</h5> 
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <!-- <label for="event_1">Page Campaign</label> -->
                                                                    <select class="form-control" id="popup_style" name="popup_style" style="height: 45px;font-size: 13px;" title="Event">
                                                                        <option value="boxed" <?php if($popup_style=='boxed'){echo 'selected';}?>>Boxed</option>
                                                                        <option value="rounded" <?php if($popup_style=='rounded'){echo 'selected';}?>>Rounded</option>
                                                                        <option value="flying_boxed" <?php if($popup_style=='flying_boxed'){echo 'selected';}?>>Flying Boxed</option>
                                                                        <option value="flying_rounded" <?php if($popup_style=='flying_rounded'){echo 'selected';}?>>Flying Rounded</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5 class="card-title mt-0">Position</h5> 
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <select class="form-control" id="position" name="position" style="height: 45px;font-size: 13px;" title="Event">
                                                                        <option value="top_left" <?php if($position=='top_left'){echo 'selected';}?>>Top - Left</option>
                                                                        <option value="top_right" <?php if($position=='top_right'){echo 'selected';}?>>Top - Right</option>
                                                                        <option value="bottom_left" <?php if($position=='bottom_left'){echo 'selected';}?>>Bottom - Left</option>
                                                                        <option value="bottom_right" <?php if($position=='bottom_right'){echo 'selected';}?>>Bottom - Right</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div id="row-time" class="row" style="padding: 0px 0 20px 0;">
                                                    <div class="col-md-6">
                                                        <h5 class="card-title mt-0">Time</h5> 
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-9">
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="hide" id="customRadio9" name="time_set" class="custom-control-input" <?php if($time_set=='hide') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="customRadio9">Hide</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="show" id="customRadio8" name="time_set" class="custom-control-input" <?php if($time_set=='show') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="customRadio8">Show</label>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5 class="card-title mt-0">Delay</h5> 
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <select class="form-control select_event" id="delay" name="delay" style="height: 45px;font-size: 13px;" title="Event">
                                                                        <option value="8" <?php if($delay=='8'){echo 'selected';}?>>8 detik</option>
                                                                        <option value="10" <?php if($delay=='10'){echo 'selected';}?>>10 detik</option>
                                                                        <option value="15" <?php if($delay=='15'){echo 'selected';}?>>15 detik</option>
                                                                        <option value="20" <?php if($delay=='20'){echo 'selected';}?>>20 detik</option>
                                                                        <option value="30" <?php if($delay=='30'){echo 'selected';}?>>30 detik</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                     <div class="col-md-6">
                                                        <h5 class="card-title mt-0">Data Load</h5> 
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <select class="form-control select_event" id="data_load" name="data_load" style="height: 45px;font-size: 13px;" title="Event">
                                                                        <option value="10" <?php if($data_load=='10'){echo 'selected';}?>>10 data</option>
                                                                        <option value="15" <?php if($data_load=='15'){echo 'selected';}?>>15 data</option>
                                                                        <option value="30" <?php if($data_load=='30'){echo 'selected';}?>>30 data</option>
                                                                        <option value="45" <?php if($data_load=='45'){echo 'selected';}?>>45 data</option>
                                                                        <option value="60" <?php if($data_load=='60'){echo 'selected';}?>>60 data</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_socialproof">Update <div class="spinner-border spinner-border-sm text-white update_socialproof_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>
                                                 
                                            </div>

                                        </div><!--end card -body-->
                                    </div><!--end card-->                                                               
                                </div>
                            </div>                                                                             
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'fundraising') { ?>
            <?php check_license(); ?>
            <?php 

                if($fundraiser_on=='1'){
                    $status_text1 = '<span>Active</span>';
                    $checked1 = 'checked=""';
                }else{
                    $status_text1 = '<span>Not Active</span>';
                    $checked1 = '';
                }

                if($fundraiser_commission_on=='1'){
                    $status_text2 = '<span>Active</span>';
                    $checked2 = 'checked=""';
                }else{
                    $status_text2 = '<span>Not Active</span>';
                    $checked2 = '';
                }

                if($fundraiser_wa_on=='1'){
                    $status_text3 = '<span>Active</span>';
                    $checked3 = 'checked=""';
                }else{
                    $status_text3 = '<span>Not Active</span>';
                    $checked3 = '';
                }

                if($fundraiser_email_on=='1'){
                    $status_text4 = '<span>Active</span>';
                    $checked4 = 'checked=""';
                }else{
                    $status_text4 = '<span>Not Active</span>';
                    $checked4 = '';
                }


                

                

            ?>
            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Fundraising</h5>  
                                    <p class="card-text text-muted">Silahkan diatur fitur Fundraising sesuai kebutuhan anda.</p>  
                                    <hr> 
                                </div><!--end col-->
                            </div><!--end row-->

                            <?php if($license=='ULTIMATE') { ?>

                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: 0px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_fundraising" style="">

                                                <div class="row" style="padding: 0px 0 20px 0;margin-top: -20px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Fundraising Mode<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan fundraising mode ini agar anda & member (donatur) anda bisa menggunakan/ mengakses fitur Fundraising.</p> 
                                                        <div class="form-group" style="margin-top: -5px;">
                                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_on">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_on" data-id="1" <?php echo $checked1; ?> >
                                                                <label class="custom-control-label" for="fundraiser_on"><?php echo $status_text1; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Text Description<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan deksripsi singkat untuk keterangan Fundraiser yang akan Join.</p>
                                                        <div class="form-group" style="margin-top: -5px;">
                                                            
                                                            <textarea class="form-control" rows="3" id="fundraiser_text" style="font-size: 13px;"><?php echo $fundraiser_text; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Button<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan text pada Button Fundraiser.</p> 
                                                        <div class="form-group" style="margin-top: -5px;">
                                                            <input type="text" class="form-control" id="fundraiser_button" required="" placeholder="Description" value="<?php echo $fundraiser_button; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 10px 0 20px 0;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Commission / Komisi<span></span></h5>
                                                    </div>

                                                        <div class="col-lg-6">
                                                            <div class="card" style="padding-top: 20px;">
                                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . "images/commission_active.png"; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 1px;" data-action="zoom">
                                                                <p style="text-align: center;padding-top: 10px;margin: 0;color: #77818e;font-size:11px;">Preview - Commission Active</p>
                                                            </div><!--end card-->
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="card" style="padding-top: 20px;">
                                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . "images/commission_notactive.png"; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 1px;" data-action="zoom">
                                                                <p style="text-align: center;padding-top: 10px;margin: 0;color: #77818e;font-size:11px;">Preview - Commission Not Active</p>
                                                            </div><!--end card-->
                                                        </div>
                                                    
                                                    <div class="col-md-12">
                                                        <p class="card-text text-muted" style="margin-top:0px;">Fitur ini harus aktif agar anda bisa menggunakan fitur komisi fundraising. Jika tidak aktif, maka tampilan di menu Fundraising akan menampilkan list fundraising saja tanpa ada menu komisi dan semua komisi akan diberi 0 jika ada fundraising yang masuk dari link Fundraiser.</p> 
                                                        <div class="form-group" style="margin-top: -5px;">
                                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_commission_on">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_commission_on" data-id="1" <?php echo $checked2; ?> >
                                                                <label class="custom-control-label" for="fundraiser_commission_on"><?php echo $status_text2; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 0px 0 0px 0;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Commission Type<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Pilih tipe komisi yang ingin anda berikan pada Fundraiser.</p> 
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                        
                                                        
                                                            <div class="form-group mb-0 row">
                                                                <div class="col-md-12" style="padding-bottom: 5px;padding-left: 0;">
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="0" id="customRadio15" name="fundraiser_commission_type" class="custom-control-input" <?php if($fundraiser_commission_type=='0') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadio15">Percent</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="1" id="customRadio16" name="fundraiser_commission_type" class="custom-control-input" <?php if($fundraiser_commission_type=='1') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadio16">Fixed</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-5 fundraiser_commission_percent" style="padding-left:0;margin-bottom:0;margin-top:15px;<?php if($fundraiser_commission_type=='0') {}else{echo 'display:none;';}?>" id="">
                                                                    <div class="form-group">
                                                                        <label class="">Percent : </label>
                                                                        <input type="text" class="form-control" id="fundraiser_commission_percent" required="" placeholder="Contoh: 10" value="<?php echo $fundraiser_commission_percent; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="5">
                                                                        <p class="card-text text-muted" style="margin-top:8px;">Range 0-100
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-5 fundraiser_commission_fixed" style="padding-left:0;margin-bottom:0;margin-top:15px;<?php if($fundraiser_commission_type=='1') {}else{echo 'display:none;';}?>" id="">
                                                                    <div class="form-group">
                                                                        <label class="">Fixed :</label>
                                                                        <input type="text" class="form-control" id="fundraiser_commission_fixed" required="" placeholder="Contoh: 2000" value="<?php echo $fundraiser_commission_fixed; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="10">
                                                                        <p class="card-text text-muted" style="margin-top:8px;">Tuliskan 2000 jika anda ingin memberikan setiap komisi 2000.
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                


                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row" style="padding: 15px 0 0px 0;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Minimal saldo pencairan<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan jika anda ingin memberikan adanya minimal saldo pencairan, jika saldo belum mencukupi maka Fundraiser tidak dapat mencairkan.</p> 
                                                        <div class="col-md-12" style="margin-bottom: 10px;padding-left:0;">

                                                            <?php
                                                            if($min_payout_setting=='1'){
                                                                $status_text6 = '<span>Active</span>';
                                                                $checked6 = 'checked=""';
                                                            }else{
                                                                $status_text6 = '<span>Not Active</span>';
                                                                $checked6 = '';
                                                            }
                                                            ?>

                                                            <div class="form-group" style="margin-top: -5px;">
                                                                <div class="custom-control custom-switch" id="checkbox_min_payout_setting_on">
                                                                    <input type="checkbox" class="custom-control-input checkbox1" id="min_payout_setting_on" data-id="1" <?php echo $checked6; ?> >
                                                                    <label class="custom-control-label" for="min_payout_setting_on"><?php echo $status_text6; ?></label>
                                                                </div>
                                                            </div>

                                                            
                                                        
                                                        </div>
                                                        <div class="col-md-5" style="padding-left:0;<?php if($min_payout_setting=='1'){}else{echo'display:none;';}?>" id="min_saldo">
                                                            <div class="form-group">
                                                                <label class="">Min Saldo : </label>
                                                                <input type="text" class="form-control" id="min_payout" required="" placeholder="Contoh: 50000" value="<?php echo $min_payout; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Send Notif WA<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan jika anda ingin mengirimkan pesan whatsapp kepada Fundraiser pada saat mengupdate Status Pencairan Komisi. Pastikan settingan <a href="<?php echo admin_url('admin.php?page=donasiaja_settings&action=notification') ?>" style="color:#505DFF;">Wanotif</a> anda sudah aktif juga.</p>
                                                        <div class="form-group" style="margin-top: -5px;">
                                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_wa_on">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_wa_on" data-id="1" <?php echo $checked3; ?> >
                                                                <label class="custom-control-label" for="fundraiser_wa_on"><?php echo $status_text3; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">WA Message<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan pesan whatsapp yang ingin anda kirimkan. Gunakan shortcode yang anda untuk memudahkan pesan tersampaikan kepada fundraiser.</p>
                                                        <div class="form-group" style="margin-top: 15px;">
                                                            
                                                            <textarea class="form-control" rows="6" id="fundraiser_wa_text" style="font-size: 13px;"><?php echo $fundraiser_wa_text; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 40px 0 20px 0;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Send Notif Email<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan jika anda ingin mengirimkan pesan email kepada Fundraiser pada saat mengupdate Status Pencairan Komisi. Pastikan settingan <a href="<?php echo admin_url('admin.php?page=donasiaja_settings&action=notification') ?>" style="color:#505DFF;">Email</a> anda sudah aktif juga.</p>
                                                        <div class="form-group" style="margin-top: -5px;">
                                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_email_on">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_email_on" data-id="1" <?php echo $checked4; ?> >
                                                                <label class="custom-control-label" for="fundraiser_email_on"><?php echo $status_text4; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 0px 0 20px 10px;">
                                                    <div class="col-md-12" style="padding-left: 0;">
                                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Email Message<span></span></h5>
                                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan pesan email yang ingin anda kirimkan. Gunakan shortcode yang anda untuk memudahkan pesan tersampaikan kepada fundraiser.</p>
                                                        <!-- <div class="form-group" style="margin-top: 15px;">
                                                               <textarea id="fundraiser_email_text" name="area">
                                                                   <?php echo $fundraiser_email_text; ?>
                                                               </textarea>
                                                        </div> -->
                                                    </div>


                                                    <div id="box_email_5" class="col-md-12 box_email_message show_box2" style="margin-top: 20px;">
                                                        <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email Message</h5>
                                                        <hr>

                                                        <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                                            <button data-id="5" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                                            <button data-id="5" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                                        </div>

                                                        <div class="form-group" style="margin-top: 35px;">
                                                            <label title="Wajib di isi alamat email">Send to* :</label>
                                                            <input type="text" class="form-control send_to_5"  required="" placeholder="{email}" value="<?php echo $f_send_to; ?>" style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                                        </div>
                                                        <div class="form-group">
                                                            <label title="Wajib di isi subject email">Subject* :</label>
                                                            <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="<?php echo $f_subject_email; ?>">
                                                        </div>
                                                        <div class="form-group email_cc" style="margin: 0;<?php if($f_emailnyacc==''){echo'display:none;';}?>" >
                                                            <label style="margin-top: 0px;">CC :</label> 
                                                            <textarea id="myTagsEmailCC_f" class="tagit tagitemailcc"></textarea>
                                                        </div>
                                                        <div class="form-group email_bcc" style="margin: 0;<?php if($f_emailnyabcc==''){echo'display:none;';}?>">
                                                            <label style="margin-top: 0px;">BCC :</label> 
                                                            <textarea id="myTagsEmailBCC_f" class="tagit tagitemailbcc"></textarea>
                                                        </div>
                                                        
                                                        <label title="Wajib di isi message email">Message* :</label>
                                                        <div class="form-group">
                                                           <textarea id="f_message_email" name="area">
                                                               <?php echo $f_message_email; ?>
                                                           </textarea> 

                                                        </div>
                                                    </div>


                                                </div>


                                                <h5 class="card-text">Note :</h5>
                                                <p class="card-text text-muted">Gunakan shortcode berikut untuk memanggil value dan bisa dipanggil pada wa message dan juga email message.</p> 
                                                <ul>
                                                    <li>{name} : Nama fundraiser</li>
                                                    <li>{email} : Email fundraiser</li>
                                                    <li>{payout_number} : ID Payout</li>
                                                    <li>{nominal} : Jumlah nominal komisi yang dicairkan</li>
                                                </ul>

                                               
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_fundraising">Update <div class="spinner-border spinner-border-sm text-white update_fundraising_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>
                                                 
                                            </div>

                                        </div><!--end card -body-->
                                    </div><!--end card-->                                                               
                                </div>
                            </div>   

                            <?php }else{ ?>

                            <div class="row" style="padding: 0px 0 15px 0;">
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                        <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                    </div>
                                </div>
                            </div>

                            <?php } ?>

                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'general') { ?>
            <?php check_license(); ?>
            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">General</h5>  
                                    <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan anda.</p>  
                                    <hr>           
                                </div><!--end col-->
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_general" style="">

                                                <?php

                                                if($login_setting=='1'){
                                                    $status_text1 = '<span>Active</span>';
                                                    $checked1 = 'checked=""';
                                                }else{
                                                    $status_text1 = '<span>Not Active</span>';
                                                    $checked1 = '';
                                                }

                                                if($register_setting=='1'){
                                                    $status_text2 = '<span>Active</span>';
                                                    $checked2 = 'checked=""';
                                                }else{
                                                    $status_text2 = '<span>Not Active</span>';
                                                    $checked2 = '';
                                                }

                                                if($campaign_setting=='1'){
                                                    $status_text3 = '<span>Active</span>';
                                                    $checked3 = 'checked=""';
                                                }else{
                                                    $status_text3 = '<span>Not Active</span>';
                                                    $checked3 = '';
                                                }

                                                if($del_campaign_setting=='1'){
                                                    $status_text4 = '<span>Active</span>';
                                                    $checked4 = 'checked=""';
                                                }else{
                                                    $status_text4 = '<span>Not Active</span>';
                                                    $checked4 = '';
                                                }

                                                if($powered_by_setting=='1'){
                                                    $status_text5 = '<span>Show</span>';
                                                    $checked5 = 'checked=""';
                                                }else{
                                                    $status_text5 = '<span>Hide</span>';
                                                    $checked5 = '';
                                                }

                                                if($changepass_setting=='1'){
                                                    $status_text7 = '<span>Active</span>';
                                                    $checked7 = 'checked=""';
                                                }else{
                                                    $status_text7 = '<span>Not Active</span>';
                                                    $checked7 = '';
                                                }

                                                if($register_checkbox_setting=='1'){
                                                    $status_text8 = '<span>Active</span>';
                                                    $checked8 = 'checked=""';
                                                }else{
                                                    $status_text8 = '<span>Not Active</span>';
                                                    $checked8 = '';
                                                }

                                                ?>

                                                
                                                <div class="row" style="margin-bottom: 20px;">

                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h4 class="mt-0 header-title">Data Shortcode</h4>
                                                        <p class="card-text text-muted">Gunakan shortcode berikut untuk memanggil data yang sesuai anda butuhkan.</p>  
                                                    </div>

                                                    <div class="col-md-8 pricingTable1 text-center">
                                                        <ul class="list-unstyled pricing-content-2 text-left py-1 border-0 mb-3">
                                                            <li>[donasiaja show="total_terkumpul"]</li>
                                                            <li>[donasiaja show="jumlah_donasi"]</li>
                                                            <li>[donasiaja show="jumlah_donatur"]</li>
                                                            <li>[donasiaja show="jumlah_user"]</li>
                                                            <li>[donasiaja show="jumlah_all_campaign"]</li>
                                                            <li>[donasiaja show="jumlah_active_campaign"]</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12" style="margin-bottom: 30px;">
                                                        <hr>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 0px 0 30px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Category <div class="spinner-border spinner-border-sm text-primary set_category_loading" style="margin-left: 10px;display: none;"></div></h5>
                                                        <div class="form-group mb-0 row">
                                                            <!-- box table -->
                                                            <div class="table-responsive" style="padding: 5px 10px;">
                                                                <table class="table mb-0">
                                                                    <thead class="thead-light">
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Name</th>
                                                                        <th style="text-align: center;">Campaign on Category</th>
                                                                        <th style="text-align: center;">Private</th>
                                                                        <th style="text-align: center;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php 
                                                                    $no = 1;
                                                                    foreach($categories as $value){ 
                                                                    $count_campaign = get_data_campaign($value->id);
                                                                    ?>
                                                                        <tr id="cat_<?php echo $value->id; ?>">
                                                                            <th scope="row"><?php echo $no; ?></th>
                                                                            <td><span id="cat_name_<?php echo $value->id; ?>" class="set_category" data-id="<?php echo $value->id; ?>"><?php echo $value->category; ?></span></td>
                                                                            <td style="text-align: center;"><?php echo $count_campaign; ?></td>
                                                                            <td style="text-align: center;">
                                                                                
                                                                                <div class="custom-control custom-switch" id="checkbox_category_private">
                                                                                    <input type="checkbox" class="custom-control-input checkbox_private" id="category_private<?php echo $value->id; ?>" data-id="<?php echo $value->id; ?>" <?php if($value->private_category=='1'){echo'checked=""';}?>>
                                                                                    <label class="custom-control-label" for="category_private<?php echo $value->id; ?>"></label>
                                                                                </div>

                                                                            </td>
                                                                            <td style="text-align: center;"><span class="badge badge-boxed badge-soft-secondary edit_category" style="cursor: pointer;" title="Edit Category" data-id="<?php echo $value->id; ?>" data-text="<?php echo $value->category; ?>">Edit</span><span class="badge badge-boxed badge-soft-danger del_category" style="cursor: pointer;margin-left: 8px;" data-id="<?php echo $value->id; ?>" title="Delete Category">Delete</span></td>
                                                                        </tr>
                                                                    <?php $no++; } ?>
                                                                    </tbody>
                                                                </table><!--end /table-->
                                                            </div> <!-- end box table -->
                                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light add_new_category" style="margin-left: 10px;margin-top: 20px;font-size: 11px;">+ Add New</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" style="margin-bottom: 30px;">
                                                        <hr>
                                                    </div>
                                                </div>


                                                <div class="row" style="margin-bottom: 20px;">

                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h4 class="mt-0 header-title">Facebook Pixel</h4>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="text1">Pixel ID</label>
                                                            <input type="text" class="form-control" id="fb_pixel" required="" value="" placeholder="...">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="event_1">Page Campaign</label>
                                                            <select class="form-control select_event" id="event_1" name="select_event" style="height: 45px;font-size: 13px;" title="Event">
                                                                <option value="" <?php if($event_1==''){echo 'selected';}?>>Pilih Event</option>
                                                                <option value="ViewContent" <?php if($event_1=='ViewContent'){echo 'selected';}?>>ViewContent</option>
                                                                <option value="Lead" <?php if($event_1=='Lead'){echo 'selected';}?>>Lead</option>
                                                                <option value="AddToCart" <?php if($event_1=='AddToCart'){echo 'selected';}?>>AddToCart</option>
                                                                <option value="AddToWishlist" <?php if($event_1=='AddToWishlist'){echo 'selected';}?>>AddToWishlist</option>
                                                                <option value="InitiateCheckout" <?php if($event_1=='InitiateCheckout'){echo 'selected';}?>>InitiateCheckout</option>
                                                                <option value="AddPaymentInfo" <?php if($event_1=='AddPaymentInfo'){echo 'selected';}?>>AddPaymentInfo</option>
                                                                <option value="Purchase" <?php if($event_1=='Purchase'){echo 'selected';}?>>Purchase</option>
                                                                <option value="CompleteRegistration" <?php if($event_1=='CompleteRegistration'){echo 'selected';}?>>CompleteRegistration</option>
                                                                <option value="Donate" <?php if($event_1=='Donate'){echo 'selected';}?>>Donate</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="event_2">Page Form</label>
                                                            <select class="form-control select_event" id="event_2" name="select_event" style="height: 45px;font-size: 13px;" title="Event">
                                                                <option value="" <?php if($event_2==''){echo 'selected';}?>>Pilih Event</option>
                                                                <option value="ViewContent" <?php if($event_2=='ViewContent'){echo 'selected';}?>>ViewContent</option>
                                                                <option value="Lead" <?php if($event_2=='Lead'){echo 'selected';}?>>Lead</option>
                                                                <option value="AddToCart" <?php if($event_2=='AddToCart'){echo 'selected';}?>>AddToCart</option>
                                                                <option value="AddToWishlist" <?php if($event_2=='AddToWishlist'){echo 'selected';}?>>AddToWishlist</option>
                                                                <option value="InitiateCheckout" <?php if($event_2=='InitiateCheckout'){echo 'selected';}?>>InitiateCheckout</option>
                                                                <option value="AddPaymentInfo" <?php if($event_2=='AddPaymentInfo'){echo 'selected';}?>>AddPaymentInfo</option>
                                                                <option value="Purchase" <?php if($event_2=='Purchase'){echo 'selected';}?>>Purchase</option>
                                                                <option value="CompleteRegistration" <?php if($event_2=='CompleteRegistration'){echo 'selected';}?>>CompleteRegistration</option>
                                                                <option value="Donate" <?php if($event_2=='Donate'){echo 'selected';}?>>Donate</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="event_3">Page Invoice</label>
                                                            <select class="form-control select_event" id="event_3" name="select_event" style="height: 45px;font-size: 13px;" title="Event">
                                                                <option value="" <?php if($event_3==''){echo 'selected';}?>>Pilih Event</option>
                                                                <option value="ViewContent" <?php if($event_3=='ViewContent'){echo 'selected';}?>>ViewContent</option>
                                                                <option value="Lead" <?php if($event_3=='Lead'){echo 'selected';}?>>Lead</option>
                                                                <option value="AddToCart" <?php if($event_3=='AddToCart'){echo 'selected';}?>>AddToCart</option>
                                                                <option value="AddToWishlist" <?php if($event_3=='AddToWishlist'){echo 'selected';}?>>AddToWishlist</option>
                                                                <option value="InitiateCheckout" <?php if($event_3=='InitiateCheckout'){echo 'selected';}?>>InitiateCheckout</option>
                                                                <option value="AddPaymentInfo" <?php if($event_3=='AddPaymentInfo'){echo 'selected';}?>>AddPaymentInfo</option>
                                                                <option value="Purchase" <?php if($event_3=='Purchase'){echo 'selected';}?>>Purchase</option>
                                                                <option value="CompleteRegistration" <?php if($event_3=='CompleteRegistration'){echo 'selected';}?>>CompleteRegistration</option>
                                                                <option value="Donate" <?php if($event_3=='Donate'){echo 'selected';}?>>Donate</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" style="margin-top:30px;margin-bottom: 10px;">
                                                        <h4 class="mt-0 header-title">Tiktok Pixel</h4>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="text1">Pixel ID</label>
                                                            <input type="text" class="form-control" id="tiktok_pixel" required="" value="<?php echo $tiktok_pixel; ?>" placeholder="...">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" style="margin-top:30px;margin-bottom: 10px;">
                                                        <h4 class="mt-0 header-title">Google Tag Manager</h4>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="text1">GTM ID</label>
                                                            <input type="text" class="form-control" id="gtm_id" required="" value="<?php echo $gtm_id; ?>" placeholder="GTM-XXXX">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                        </div>
                                                    </div>


                                                    
                                                    
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-12" style="margin-bottom: 30px;">
                                                        <hr>
                                                    </div>
                                                </div>



                                                <div class="row" style="padding: 0px 0 10px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Logo Powered By DonasiAja</h5>
                                                        <?php if($plugin_license!='ULTIMATE') { ?>

                                                                <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                                    <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                                </div>

                                                        <?php } ?>

                                                        <div class="form-group" <?php if($plugin_license!='ULTIMATE') { echo 'style="display:none;"'; } ?>>
                                                            <div class="custom-control custom-switch" id="checkbox_powered_by_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox31" id="powered_by_setting" data-id="1" <?php echo $checked5; ?> >
                                                                <label class="custom-control-label" for="powered_by_setting"><?php echo $status_text5; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 0px 0 30px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Login</h5>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_login_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="login_setting" data-id="1" <?php echo $checked1; ?> >
                                                                <label class="custom-control-label" for="login_setting"><?php echo $status_text1; ?></label>
                                                            </div>
                                                        </div>
                                                        <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin menggunakan login bawaan DonasiAja.</p> 
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Login URL</h5>
                                                         
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="page_login" required="" placeholder="Pagename" value="<?php echo $page_login; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                        <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/<span class="set_page_login"><?php echo $page_login; ?></span>/</p> 
                                                    </div>
                                                </div>
                                                <div class="row" style="padding: 20px 0 20px 0;margin-top: -10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Login Description</h5>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="login_text" required="" placeholder="Description" value="<?php echo $login_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Register</h5>
                                                        <?php if($plugin_license!='ULTIMATE'){ ?>

                                                                <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                                    <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                                </div>

                                                        <?php } ?>
                                                        <div class="form-group" <?php if($plugin_license!='ULTIMATE') {echo'style="display:none;"';}?>>
                                                            <div class="custom-control custom-switch" id="checkbox_register_setting" style="margin-bottom: 20px;">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="register_setting" data-id="1" <?php echo $checked2; ?> >
                                                                <label class="custom-control-label" for="register_setting"><?php echo $status_text2; ?></label>
                                                            </div>
                                                        </div>
                                                        <p <?php if($plugin_license!='ULTIMATE') {echo'style="display:none;"';}?> class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin mengaktifkan menu registrasi user (donatur). Pastikan settingan di Wordpress anda sudah mengaktifkan <a href="<?php echo admin_url('options-general.php') ?>"><b>Membership > Anyone can register</b></a></p> 
                                                    </div>
                                                </div>
                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Register URL</h5>
                                                         
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="page_register" required="" placeholder="Pagename" value="<?php echo $page_register; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                        <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/<span class="set_page_register"><?php echo $page_register; ?></span>/</p> 
                                                    </div>
                                                </div>
                                                <div class="row" style="padding: 20px 0 20px 0;margin-top: -10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Register Description</h5>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="register_text" required="" placeholder="Description" value="<?php echo $register_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="padding:0px 0 20px 0;margin-top: -10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Register Checkbox</h5>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_register_checkbox_setting" style="margin-bottom: 20px;">
                                                                <input type="checkbox" class="custom-control-input checkbox33" id="register_checkbox_setting" data-id="1" <?php echo $checked8; ?> >
                                                                <label class="custom-control-label" for="register_checkbox_setting"><?php echo $status_text8; ?></label>
                                                            </div>
                                                        </div>
                                                        <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin mengaktifkan checkbox register sebagai tanda user harus mematuhi peraturan yang ada di situs atau website ini.</p> 
                                                    </div>
                                                </div>

                                                <div class="row" style="padding:0px 0 20px 0;margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">Register Checkbox Info</h5>
                                                        <div class="form-group">
                                                            <textarea class="form-control" rows="3" id="register_checkbox_info" style="font-size: 13px;"><?php echo $register_checkbox_info; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Page Reset Password</h5>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_changepass_setting" style="margin-bottom: 20px;">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="changepass_setting" data-id="1" <?php echo $checked7; ?> >
                                                                <label class="custom-control-label" for="changepass_setting"><?php echo $status_text7; ?></label>
                                                            </div>
                                                        </div>
                                                        <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin mengaktifkan menu rubah password pada halaman login.</p> 
                                                    </div>
                                                </div>


                                                <div class="row" style="padding: 0px 0 20px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Donatur Create Campaign</h5>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_campaign_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="campaign_setting" data-id="1" <?php echo $checked3; ?> >
                                                                <label class="custom-control-label" for="campaign_setting"><?php echo $status_text3; ?></label>
                                                            </div>
                                                        </div>
                                                        <p class="card-text text-muted" style="margin-top: -10px;">User Donatur diberikan akses membuat campaign.</p>
                                                    </div>
                                                </div>
                                                <div class="row" style="padding: 10px 0 20px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Delete Campaign</h5> <!-- campaign_setting -->
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch" id="checkbox_del_campaign_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="del_campaign_setting" data-id="1" <?php echo $checked4; ?> >
                                                                <label class="custom-control-label" for="del_campaign_setting"><?php echo $status_text4; ?></label>
                                                            </div>
                                                        </div>
                                                        <p class="card-text">Aktif : <span class="text-muted">Campaign yang sudah ada donasinya, tetap bisa dihapus. (tidak disarankan).</span><br>Tidak Aktif : <span class="text-muted">Campaign yang sudah ada donasinya, tidak bisa dihapus. Kecuali data donasi dihapus terlebih dahulu, baru campaign bisa dihapus.</span></p>
                                                        <p class="card-text">Kenapa ada ini? untuk menghindari kesalahan delete campaign dan data donasi hilang sia-sia karena kelalaian delete campaign.</p>

                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 10px 0 20px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">JQuery</h5>

                                                        <div class="form-group mb-0 row" style="margin-bottom: 20px !important;">
                                                            <div class="col-md-9">
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="0" id="customRadioJ2" name="jquery_on" class="custom-control-input" <?php if($jquery_on=='0') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="customRadioJ2">Not Active</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="1" id="customRadioJ1" name="jquery_on" class="custom-control-input" <?php if($jquery_on=='1') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="customRadioJ1">Active</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="2" id="customRadioJ3" name="jquery_on" class="custom-control-input" <?php if($jquery_on=='2') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="customRadioJ3">Custom</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6" style="margin-bottom:0;margin-top: 20px;<?php if($jquery_on!='2') { echo 'display:none;';}?>" id="box_jquery_custom">
                                                                <div class="form-group" style="margin-left: -10px;margin-bottom: 30px;">
                                                                    <select class="form-control" id="jquery_custom" name="jquery_custom" style="height: 45px;" title="Payment Method">
                                                                        <option value="0">Pilih JQuery</option>
                                                                        <option value="3.6.0" <?php if($jquery_custom=='3.6.1'){echo'selected';}?>>JQuery 3.6</option>
                                                                        <option value="3.5.1" <?php if($jquery_custom=='3.5.1'){echo'selected';}?>>JQuery 3.5</option>
                                                                        <option value="3.4.1" <?php if($jquery_custom=='3.4.1'){echo'selected';}?>>JQuery 3.4</option>
                                                                        <option value="3.3.1" <?php if($jquery_custom=='3.3.1'){echo'selected';}?>>JQuery 3.3</option>
                                                                        <option value="2.2.4" <?php if($jquery_custom=='2.2.4'){echo'selected';}?>>JQuery 2.2</option>
                                                                        <option value="1.12.4" <?php if($jquery_custom=='1.12.4'){echo'selected';}?>>JQuery 1.12</option>
                                                                        <option value="1.9.1" <?php if($jquery_custom=='1.9.1'){echo'selected';}?>>JQuery 1.9</option>
                                                                        <option value="1.8.3" <?php if($jquery_custom=='1.8.3'){echo'selected';}?>>JQuery 1.8</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        <p class="card-text text-muted" style="margin-top: -10px;">Set Not Active jika terjadi double Jquery pada halaman website anda atau Custom lalu sesuaikan dengan Jquery yang anda gunakan pada website anda. Jika tidak, wajib aktifkan.</p>

                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 10px 0 20px 0;">
                                                    <div class="col-md-9">
                                                        <h5 class="card-title mt-0">Pilih tipe menu pada halaman Campaign</h5>
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-9">
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="tab" id="customRadio8" name="label_tab" class="custom-control-input" <?php if($label_tab=='tab') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="customRadio8">Tab Menu</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="list" id="customRadio9" name="label_tab" class="custom-control-input" <?php if($label_tab=='list') { echo 'checked=""';}?>>
                                                                        <label class="custom-control-label" for="customRadio9">List Menu</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding: 20px 0 10px 0;">
                                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                                        <h5 class="card-title mt-0">Batas maximal user bisa melakukan love pada doa atau komentar</h5>
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-md-12">
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="0" id="customRadio10" name="max_love" class="custom-control-input" <?php if($max_love=='0') { echo 'checked=""';}?> >
                                                                        <label class="custom-control-label" for="customRadio10">Unlimitted</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check-inline my-1">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" value="1" id="customRadio11" name="max_love" class="custom-control-input" <?php if($max_love!='0') { echo 'checked=""';}?> >
                                                                        <label class="custom-control-label" for="customRadio11">Custom</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" style="margin-bottom:0;margin-top: 20px;<?php if($max_love=='0') { echo 'display:none;';}?>" id="max_love_input">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="max_love_custom" required="" placeholder="Contoh: 50" value="<?php if($max_love!='0') {echo $max_love; } ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_general">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </div>

                                        </div><!--end card -body-->
                                    </div><!--end card-->                                                               
                                </div>
                            </div>                                                                             
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'waba') { ?>
            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Whatsapp Business Platform</h5>  
                                    <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan anda.</p>  
                                    <hr>           
                                </div><!--end col-->
                            </div><!--end row-->

                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_general" style="">

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_waba">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>

                                        </div><!--end card -body-->
                                    </div><!--end card-->                                                               
                                </div>
                            </div>                                                                             
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } elseif($action === 'socialgraph') { ?>
            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-outline-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = (($_GET['action'] == $key)) ? 'btn btn-primary waves-light' : 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">Social Graph</h5>
                                    <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan anda.</p>  
                                    <hr>           
                                </div><!--end col-->
                            </div><!--end row-->
                            
                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_general" style="">

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <br>
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_waba">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>

                                        </div><!--end card -body-->
                                    </div><!--end card-->                                                               
                                </div>
                            </div>                                                                             
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
        <?php } else { ?>

            <div class="row">
                <div class="col-12">
                    <div class="card col-7" id="box-section">
                        <div class="card-body" style="padding-bottom: 0;">                                
                            <div class="button-items mb-4">
                                <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-primary waves-light">License</button></a>
                                <?php
                                foreach($menu_arr as $key => $val) {
                                    $class = 'btn btn-outline-light';

                                    echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body" style="margin-top: -10px;">
                            <div class="row">
                                <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                                    <h5 class="boxcard-title">DonasiAja - <?php echo $GLOBALS['donasiaja_vars']['plugin_version']; ?></h5>  
                                    <p class="card-text text-muted">Masukkan lisensi DonasiAja agar anda bisa menggunakan fitur-fitur terbaik yang ada.</p>   
                                    <p style="background:#ffd5d5;padding: 20px 20px; border-radius: 2px;display: none;">Perhatian, jika ada perubahan terkait struktur code pada plugin DonasiAja yang dilakukan secara mandiri, maka anda sudah siap dengan konsekuensi jika ada error dan menjadi tanggung jawab pribadi. Harap menjadi perhatian, Terimakasih.</p>
                                    <hr>
                                    <p class="card-text">
                                    <?php
                                    if (_check_is_curl_installed()) {
                                    } else {
                                      echo "<span style=\"color:red\">cURL belum terinstall</span> di server. Silahkan kontak provider hosting anda untuk mengaktifkan cURL-nya agar bisa segera melakukan aktivasi DonasiAja dengan lancar.";
                                    }
                                    ?>
                                    </p>      
                                </div><!--end col-->
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                    <div class="card card-border" style="border: 0;padding: 0;">
                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                            <div id="data_license" style="margin-bottom: 30px; background: #f5f9ff;border-radius: 8px;padding: 20px 25px;border: 1px solid #ebf1f9;">

                                                

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">LICENSE</h5>
                                                        <div class="form-group" style="height: 18px;">
                                                            <H4 style="color: #36BD47;"><?php if($license!=''){echo $license.' License';}else{echo'-';}; ?></H4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 5px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">STATUS</h5>
                                                        <div class="form-group" style="height: 18px;">
                                                            <?php if($status=='valid' && $plugin=='allowed'){ ?>
                                                            <p class="card-text" style="background: #36BD47;color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 9px;position: absolute;">Active</p>
                                                            <p style="position: absolute;margin-left: 55px;font-style: italic;">Aktif sampai dengan <?php echo date("d M Y - H:i:s",$time);?></p>
                                                            <?php } elseif($status=='valid' && $plugin=='expired'){ ?>
                                                            <p class="card-text" style="background: #E1345E;color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 9px;position: absolute;">Expired</p>
                                                            <p style="position: absolute;margin-left: 60px;font-style: italic;">Aktif sampai dengan <?php echo date("d mY h:i:s",$time);?></p>
                                                            <?php } else { ?>
                                                            <p class="card-text" style="background: #E1345E;color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 9px;position: absolute;">Not Active</p>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <h5 class="card-title mt-0" style="padding-top: 10px;">API Key DonasiAja<span></span></h5>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="apikey" required="" placeholder="Your API Key" value="<?php echo $apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <button id="deactivate_apikey" type="button" class="btn btn-outline-primary waves-effect waves-light" style="height: 40px;">&nbsp;&nbsp;Deactivate&nbsp;&nbsp;<div class="spinner-border spinner-border-sm deactivate_apikey_loading" style="margin-left: 3px;display: none;"></button>

                                                            <button id="activate_apikey" type="button" class="btn btn-primary waves-effect waves-light" style="height: 40px;">&nbsp;&nbsp;Activate&nbsp;&nbsp;<div class="spinner-border spinner-border-sm activate_apikey_loading" style="margin-left: 3px;display: none;"></button>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </div>

                                                
                                            </div>

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <p class="card-text text-muted"><b>Note:</b><br>Jika saat aktivasi apikey tertulis "Your Activation is Full", klik Deactivate terlebih dahulu setelah itu silahkan Activate kembali. Begitu juga jika ingin menghapus license APIKey, klik tombol Deactivate.</p> 
                                                    </div>
                                                </div>

                                        </div><!--end card -body-->

                                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                             <div id="data_system" style="">
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-0">SYSTEM REQUIREMENT</h5>
                                                    </div>

                                                    <table class="table mb-0" style="margin-top: 10px;margin-left: 10px;margin-right: 10px;">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="border-top-0"></th>
                                                                <th class="border-top-0">Requirement</th>
                                                                <th class="border-top-0">Current</th>
                                                            </tr><!--end tr-->
                                                        </thead>
                                                        <tbody>
                                                            <?php 

                                                            $phpversion = phpversion(); 
                                                            $a = version_compare($phpversion, '7.2', '>=') ?: "false";
                                                            $b = version_compare($phpversion, '7.4.3', '<=') ?: "false";
                                                            if($a==true && $b==true){
                                                                $requirement = 'color: #36BD47;';
                                                            }else{
                                                                $requirement = 'color: #E61515;';
                                                            }
                                                            

                                                            ?>
                                                            <tr>                                                        
                                                                <td>PHP Version</td>
                                                                <td><span style="color: #36374c;">7.2 - 7.4.3</span></td>               
                                                                <td><span style="<?php echo $requirement; ?>"><?php  echo phpversion(); ?></span></td>
                                                            </tr><!--end tr-->     
                                                            <tr>                                                        
                                                                <td>Curl</td>
                                                                <td><span style="color: #36374c;">Enabled</span></td>
                                                                <td><?php if(function_exists('curl_version')==true){echo'<span style="color: #36BD47;">Enabled</span>';}else{echo'<span style="color: #E61515;">Not Enabled</span>';} ?></td>
                                                            </tr><!--end tr-->
                                                            <tr>                                                        
                                                                <td>Zip</td>
                                                                <td><span style="color: #36374c;">Enabled</span></td>       
                                                                <td><?php if(extension_loaded('zip')==true){echo'<span style="color: #36BD47;">Enabled</span>';}else{echo'<span style="color: #E61515;">Not Enabled</span>';} ?></td>
                                                            </tr><!--end tr-->  
                                                            <tr>                                                        
                                                                <td>allow_url_fopen</td>
                                                                <td><span style="color: #36374c;">Enabled</span></td>       
                                                                <td><?php if(ini_get("allow_url_fopen")==true){echo'<span style="color: #36BD47;">Enabled</span>';}else{echo'<span style="color: #E61515;">Not Enabled</span>';} ?></td>
                                                            </tr><!--end tr-->                                
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>

                                    

                                    </div><!--end card-->                                                               
                                </div>
                            </div>                                                                             
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>

        <?php djax(); } ?>


            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->
        <div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>

        <!-- sweetalert2 -->
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate-4.1.1.min.css" rel="stylesheet" type="text/css">
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/minicolors/jquery.minicolors.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/tinymce/tinymce.min.js"></script>

        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/donasiaja-admin.js"></script>

        <?php                                                 
            $channel_id = '';
            $no = 1;
            foreach($telegram_send_to as $key => $value) {

                $message_tele = $value->message;
                $channel = $value->channel;

                if (strpos($channel, ',') !== false ) {

                    $array_channel  = (explode(",", $channel));
                    $count = count($array_channel);
                    $i = 1;
                    foreach ($array_channel as $values){
                        if($i<$count){
                            $channel_id .= "'".$values."',";
                        }else{
                            $channel_id .= "'".$values."'";
                        }
                        $i++;
                    }

                }elseif($channel==' '){
                    $channel_id = "";
                }else{
                    $channel_id = "'".$channel."'";
                }

            ?>
            <script>
                $('#myTags_<?php echo $no; ?>').tagEditor({
                    initialTags: [<?php echo $channel_id; ?>],
                    delimiter: ', ',
                    placeholder: 'Enter channel ...'
                });
            </script>

        <?php $no++; } ?>




        <?php                                                 
            $channel_id_mc = '';
            foreach($telegram_manual_confirmation as $key => $value) {
                $channel_mc = $value->channel;
                if (strpos($channel_mc, ',') !== false ) {

                    $array_channel  = (explode(",", $channel_mc));
                    $count = count($array_channel);
                    $i = 1;
                    foreach ($array_channel as $values){
                        if($i<$count){
                            $channel_id_mc .= "'".$values."',";
                        }else{
                            $channel_id_mc .= "'".$values."'";
                        }
                        $i++;
                    }

                }elseif($channel==' '){
                    $channel_id_mc = "";
                }else{
                    $channel_id_mc = "'".$channel_mc."'";
                }
            }

        ?>

        <script>
            $('#myTags_manual_confirmation').tagEditor({
                initialTags: [<?php echo $channel_id_mc; ?>],
                delimiter: ', ',
                placeholder: 'Enter channel ...'
            });
        </script>


        <?php                                                 
            $email_id = '';
            $email_idcc = '';
            $email_idbcc = '';
            $nonya = 1;
            foreach($email_send_to as $key => $value) {

                $message_email = $value->message;
                if (isset($value->email)){
                   $emailnya = $value->email;
                }else{
                   $emailnya = '';
                }
                if (isset($value->emailcc)){
                   $emailnyacc = $value->emailcc;
                }else{
                   $emailnyacc = '';
                }
                if (isset($value->emailbcc)){
                   $emailnyabcc = $value->emailbcc;
                }else{
                   $emailnyabcc = '';
                }

                if (strpos($emailnya, ',') !== false ) {

                    $array_email  = (explode(",", $emailnya));
                    $count = count($array_email);
                    $i = 1;
                    foreach ($array_email as $values){
                        if($i<$count){
                            $email_id .= "'".$values."',";
                        }else{
                            $email_id .= "'".$values."'";
                        }
                        $i++;
                    }

                }elseif($emailnya==' '){
                    $email_id = "";
                }else{
                    $email_id = "'".$emailnya."'";
                }

                if (strpos($emailnyacc, ',') !== false ) {

                    $array_email  = (explode(",", $emailnyacc));
                    $count = count($array_email);
                    $i = 1;
                    foreach ($array_email as $values){
                        if($i<$count){
                            $email_idcc .= "'".$values."',";
                        }else{
                            $email_idcc .= "'".$values."'";
                        }
                        $i++;
                    }

                }elseif($emailnyacc==' '){
                    $email_idcc = "";
                }else{
                    $email_idcc = "'".$emailnyacc."'";
                }

                if (strpos($emailnyabcc, ',') !== false ) {

                    $array_email  = (explode(",", $emailnyabcc));
                    $count = count($array_email);
                    $i = 1;
                    foreach ($array_email as $values){
                        if($i<$count){
                            $email_idbcc .= "'".$values."',";
                        }else{
                            $email_idbcc .= "'".$values."'";
                        }
                        $i++;
                    }

                }elseif($emailnyabcc==' '){
                    $email_idbcc = "";
                }else{
                    $email_idbcc = "'".$emailnyabcc."'";
                }

            ?>
            <script>
                $('#myTagsEmail_<?php echo $nonya; ?>').tagEditor({
                    initialTags: [<?php echo $email_id; ?>],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com atau {email}',
                    tagLimit: 2,
                });
                $('#myTagsEmailCC_<?php echo $nonya; ?>').tagEditor({
                    initialTags: [<?php echo $email_idcc; ?>],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com'
                });
                $('#myTagsEmailBCC_<?php echo $nonya; ?>').tagEditor({
                    initialTags: [<?php echo $email_idbcc; ?>],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com'
                });
            </script>

        <?php $nonya++; } ?>

        <?php 

            $jumlah_email = count($email_send_to);
            $email_tambahan = 3;

            for ($i = $jumlah_email; $i <= $email_tambahan; $i++){

                if($i>$jumlah_email){ ?>

                    
            <script>
                $('#myTagsEmail_<?php echo $i; ?>').tagEditor({
                    initialTags: [],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com atau {email}',
                    tagLimit: 2,
                });
                $('#myTagsEmailCC_<?php echo $i; ?>').tagEditor({
                    initialTags: [],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com'
                });
                $('#myTagsEmailBCC_<?php echo $i; ?>').tagEditor({
                    initialTags: [],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com'
                });
            </script>

           <?php }
        }


        ?>


        <script>

            $('#myTagsEmailCC_s').tagEditor({
                    initialTags: [<?php echo $s_email_idcc; ?>],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com'
                });
            $('#myTagsEmailBCC_s').tagEditor({
                initialTags: [<?php echo $s_email_idbcc; ?>],
                delimiter: ', ',
                placeholder: 'example@gmail.com'
            });

            $('#myTagsEmailCC_f').tagEditor({
                    initialTags: [<?php echo $f_email_idcc; ?>],
                    delimiter: ', ',
                    placeholder: 'example@gmail.com'
                });
            $('#myTagsEmailBCC_f').tagEditor({
                initialTags: [<?php echo $f_email_idbcc; ?>],
                delimiter: ', ',
                placeholder: 'example@gmail.com'
            });

            // no spasi, no alphabet
            $("input.nominalnya, #max_package, #minimum_donate, #unique_number_fixed, #unique_number_range1, #unique_number_range2, #moota_range").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return valuenya = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "");
                });
            });

            function randMe(length, current) {
              current = current ? current : '';
              return length ? randMe(--length, "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz".charAt(Math.floor(Math.random() * 60)) + current) : current;
            }

            $(".copy-shortcode-socialproof").on("click", function(e) {
                var value = $(this).attr('data-salin');
                copyToClipboard(value);
                var message = ""+value+" berhasil dicopy.";
                var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
                var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
                createAlert(message, status, timeout);
            });

            function copyToClipboard(string) {
                let textarea;let result;try{textarea=document.createElement("textarea");textarea.setAttribute("readonly",!0);textarea.setAttribute("contenteditable",!0);textarea.style.position="fixed";textarea.value=string;document.body.appendChild(textarea);textarea.focus();textarea.select();const range=document.createRange();range.selectNodeContents(textarea);const sel=window.getSelection();sel.removeAllRanges();sel.addRange(range);textarea.setSelectionRange(0,textarea.value.length);result=document.execCommand("copy")}catch(err){console.error(err);result=null}finally{document.body.removeChild(textarea)}
            if(!result){const isMac=navigator.platform.toUpperCase().indexOf("MAC")>=0;const copyHotkey=isMac?"⌘C":"CTRL+C";result=prompt(`Press ${copyHotkey}`,string);if(!result){return!1}}
            return!0
                }

            // ************************************
            // LICENSE
            // ************************************

            $('#activate_apikey').bind('click', function() {

                var apikey = $('#apikey').val();
                $('.activate_apikey_loading').show();

                var data_nya = [
                    apikey
                ];

                var data = {
                    "action": "djafunction_activate_apikey",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    var string = response.split("_");
                    var status = string[0];
                    var info   = string[1];

                    // alert(response);

                    if(status=='success'){
                        swal.fire(
                          'Activation Success!',
                          info,
                          'success'
                        );
                        window.location.reload();
                    }else{
                        swal.fire(
                          'Activation Failed!',
                          info,
                          'warning'
                        );
                    }
                    $('.activate_apikey_loading').hide();
                });

            });
            $('#deactivate_apikey').bind('click', function() {

                var apikey = $('#apikey').val();
                $('.deactivate_apikey_loading').show();

                var data_nya = [
                    apikey
                ];

                var data = {
                    "action": "djafunction_deactivate_apikey",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    var string = response.split("_");
                    var status = string[0];
                    var info   = string[1];

                    // alert(response);

                    if(status=='success'){
                        swal.fire(
                          'Deactivation API Key Success!',
                          info,
                          'success'
                        );
                        window.location.reload();
                    }else{
                        swal.fire(
                          'Deactivation Failed!',
                          info,
                          'warning'
                        );
                    }
                    $('.deactivate_apikey_loading').hide();
                });

            });

            // ************************************
            // PAYMENT
            // ************************************

            $('#instant1, #instant2, #instant3, #instant4, #instant5, #instant6, #va1, #va2, #va3, #va4, #transfer1 ').change(function() {
                if(this.checked) {$(this).prop('checked', true);}else{$(this).prop('checked', true);}
            });

            $(document).on("click", ".del_bank", function(e) {
                var randid = $(this).attr('data-randid');
                $('.bank_opt_'+randid).remove();
            });

            $('#manual_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_manual_setting span').text('Active');
                }else{
                    $('#checkbox_manual_setting span').text('Not Active');
                }
            });


            $('#add_bank').bind('click', function(e) {
                var randid = randMe(3);
                $('#data_bank').append('<div class="col-md-3 bank_opt_'+randid+' bank-col-1"><select class="form-control select_bank" id="" data-randid="'+randid+'" name="select_bank" style="height: 45px;" title="Bank"><?php echo $option_bank; ?></select></div><div class="col-md-2 bank_opt_'+randid+' bank-col-2"><div class="form-group"><input type="text" value="" class="form-control label_norek" id="opt_label_norek_'+randid+'" required="" placeholder="No Rekening" style="font-size: 13px;padding-left: 12px;" title="No Rekening"></div></div><div class="col-md-3 bank_opt_'+randid+' bank-col-3"><div class="form-group"><input type="text" value="" class="form-control label_an" id="opt_label_an_'+randid+'" required="" placeholder="Rek Atas Nama" style="font-size: 13px;padding-left: 12px;" title="Rek Atas Nama"></div></div><div class="col-md-3 bank_opt_'+randid+' bank-col-4"><div class="form-group"><select class="form-control" id="select_method_'+randid+'" data-randid="'+randid+'" name="select_method" style="height: 45px;" title="Payment Method"><option value="0">Pilih Method</option><option value="1">Instant</option><option value="2">VA</option><option value="3">Transfer</option></select></div></div><div class="col-md-1 bank_opt_'+randid+' bank-col-5"><button type="button" class="btn btn-danger del_bank" title="Delete" data-randid="'+randid+'" style="margin-top: 5px;"><i class="fas fa-minus"></i></button></div>'); 
            });

            $('input[type=radio][name=unique_number_setting]').change(function() {
                if (this.value == '2') {
                    $('.unique_number_range').show();
                    $('.unique_number_fixed').hide();
                } else if (this.value == '1') {
                    $('.unique_number_range').hide();
                    $('.unique_number_fixed').show();
                } else {
                    $('.unique_number_range').hide();
                    $('.unique_number_fixed').hide();
                }
            });

            $('#instant_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_instant_setting span').text('Active');
                }else{
                    $('#checkbox_instant_setting span').text('Not Active');
                }
            });
            $('#va_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_va_setting span').text('Active');
                }else{
                    $('#checkbox_va_setting span').text('Not Active');
                }
            });

            $('#transfer_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_transfer_setting span').text('Active');
                }else{
                    $('#checkbox_transfer_setting span').text('Not Active');
                }
            });

            $('#wanotif_followup1').change(function() {
                if(this.checked) {
                    $('#wanotif_followup1_on span').text('Active');
                }else{
                    $('#wanotif_followup1_on span').text('Not Active');
                }
            });

            $('#telegram_on').change(function() {
                if(this.checked) {
                    $('#checkbox_telegram_on span').text('Active');
                }else{
                    $('#checkbox_telegram_on span').text('Not Active');
                }
            });

            $('#email_on').change(function() {
                if(this.checked) {
                    $('#checkbox_email_on span').text('Active');
                }else{
                    $('#checkbox_email_on span').text('Not Active');
                }
            });

            $('#wanotif_on').change(function() {
                if(this.checked) {
                    $('#checkbox_wanotif_on span').text('Active');
                }else{
                    $('#checkbox_wanotif_on span').text('Not Active');
                }
            });



            
            $("#update_payment").click(function(e) {
                
                var instant_setting = $("input#instant_setting:checked").val();
                if(instant_setting!=undefined){instant_setting = 1;}else{instant_setting = 0;}
                var va_setting = $("input#va_setting:checked").val();
                if(va_setting!=undefined){va_setting = 1;}else{va_setting = 0;}
                var transfer_setting = $("input#transfer_setting:checked").val();
                if(transfer_setting!=undefined){transfer_setting = 1;}else{transfer_setting = 0;}
                var instant_title = $('#instant_title').val();
                var va_title = $('#va_title').val();
                var transfer_title = $('#transfer_title').val();
                var payment_setting = '{"method1":["instant","'+instant_title+'","'+instant_setting+'"],"method2":["va","'+va_title+'","'+va_setting+'"],"method3":["transfer","'+transfer_title+'","'+transfer_setting+'"]}';
                
                var no = 1;
                var new_selected_bank = [];
                $(".select_bank").each(function(){
                        var randid = $(this).attr('data-randid');
                        var norek = $('#opt_label_norek_'+randid).val();
                        var atas_nama = $('#opt_label_an_'+randid).val();
                        var payment_method = $('#select_method_'+randid).find(":selected").val();
                        new_selected_bank.push('"'+$(this).find(":selected").val()+'@'+no+'":"'+norek+'_'+atas_nama+'_'+payment_method+'"');
                        no++;
                });
                var bank_account = '{'+new_selected_bank+'}';

                var unique_number_setting = $("input[type='radio'][name='unique_number_setting']:checked").val();
                var unique_number_fixed = $('#unique_number_fixed').val();
                var unique_number_range1 = $('#unique_number_range1').val();
                var unique_number_range2 = $('#unique_number_range2').val();
                var unique_number_value = '{"unique_number":["'+unique_number_fixed+'","'+unique_number_range1+'","'+unique_number_range2+'"]}';
                if (isNaN(unique_number_fixed) || isNaN(unique_number_range1) || isNaN(unique_number_range2)){
                    swal.fire(
                      '',
                      'Unique Number : Must input numbers.',
                      'warning'
                    );
                    return false;
                }

                $('.update_payment_loading').show();

                var data_nya = [
                    payment_setting,
                    bank_account,
                    unique_number_setting,
                    unique_number_value
                ];

                var data = {
                    "action": "djafunction_update_payment_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Payment setting updated.',
                          'success'
                        );
                        window.location.reload();
                    }
                });

            });

            $("#update_ipaymu").click(function(e) {
    
                var ipaymu_mode = $("input[type='radio'][name='ipaymu_mode']:checked").val();
                var ipaymu_va = $('#ipaymu_va').val();
                var ipaymu_apikey = $('#ipaymu_apikey').val();

                $('.update_ipaymu_loading').show();

                var data_nya = [
                    ipaymu_mode,
                    ipaymu_va,
                    ipaymu_apikey
                ];

                var data = {
                    "action": "djafunction_update_ipaymu_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'iPaymu setting updated.',
                          'success'
                        );
                        $('.update_ipaymu_loading').hide();
                        // window.location.reload();
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_settings&action=payment#ipaymu') ?>");
                    }
                });

            });

            $('input[type=radio][name=tripay_mode]').change(function() {
                if (this.value == '0') {
                    $('#tripay_sandbox').show();
                    $('#tripay_production').hide();
                } else {
                    $('#tripay_sandbox').hide();
                    $('#tripay_production').show();
                }
            });

            $("#update_tripay").click(function(e) {
    
                var tripay_mode         = $("input[type='radio'][name='tripay_mode']:checked").val();
                var tripay_apikey       = $('#tripay_apikey').val();
                var tripay_privatekey   = $('#tripay_privatekey').val();
                var tripay_merchant     = $('#tripay_merchant').val();
                var tripay_apikey_sandbox       = $('#tripay_apikey_sandbox').val();
                var tripay_privatekey_sandbox   = $('#tripay_privatekey_sandbox').val();
                var tripay_merchant_sandbox     = $('#tripay_merchant_sandbox').val();
                var tripay_qris         = $("input[type='radio'][name='tripay_qris']:checked").val();

                $('.update_tripay_loading').show();

                var data_nya = [
                    tripay_mode,
                    tripay_apikey,
                    tripay_privatekey,
                    tripay_merchant,
                    tripay_apikey_sandbox,
                    tripay_privatekey_sandbox,
                    tripay_merchant_sandbox,
                    tripay_qris
                ];

                var data = {
                    "action": "djafunction_update_tripay_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Tripay setting updated.',
                          'success'
                        );
                        $('.update_tripay_loading').hide();
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_settings&action=payment#tripay') ?>");
                    }
                });

            });


            $('input[type=radio][name=midtrans_mode]').change(function() {
                if (this.value == '0') {
                    $('#midtrans_sandbox').show();
                    $('#midtrans_production').hide();
                } else {
                    $('#midtrans_sandbox').hide();
                    $('#midtrans_production').show();
                }
            });

            $("#update_midtrans").click(function(e) {
    
                var midtrans_mode        = $("input[type='radio'][name='midtrans_mode']:checked").val();
                var midtrans_serverkey   = $('#midtrans_serverkey').val();
                var midtrans_clientkey   = $('#midtrans_clientkey').val();
                var midtrans_merchant   = $('#midtrans_merchant').val();
                var midtrans_serverkey_sandbox   = $('#midtrans_serverkey_sandbox').val();
                var midtrans_clientkey_sandbox   = $('#midtrans_clientkey_sandbox').val();
                var midtrans_merchant_sandbox   = $('#midtrans_merchant_sandbox').val();

                $('.update_midtrans_loading').show();

                var data_nya = [
                    midtrans_mode,
                    midtrans_serverkey,
                    midtrans_clientkey,
                    midtrans_merchant,
                    midtrans_serverkey_sandbox,
                    midtrans_clientkey_sandbox,
                    midtrans_merchant_sandbox
                ];

                var data = {
                    "action": "djafunction_update_midtrans_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Midtrans setting updated.',
                          'success'
                        );
                        $('.update_midtrans_loading').hide();
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_settings&action=payment#tripay') ?>");
                    }
                });

            });


            $("#update_moota").click(function(e) {
    
                var moota_secret_token = $('#moota_secret_token').val();
                var moota_range = $('#moota_range').val();

                $('.update_moota_loading').show();

                var data_nya = [
                    moota_secret_token,
                    moota_range
                ];

                var data = {
                    "action": "djafunction_update_moota_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Moota setting updated.',
                          'success'
                        );
                        $('.update_moota_loading').hide();
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_settings&action=payment#moota') ?>");
                    }
                });

            });

            $(document).on("click", ".add_apikey", function(e) {
                var randid = randMe(3);
                $('#box_cs_apikey').append('<div class="form-group row container_cs_box" style="padding: 8px 10px 0 10px;margin-bottom:5px;" id="container_cs_'+randid+'" data-id="'+randid+'"> <div class="col-lg-5 mo-b-15"> <select class="form-control select_cs" id="select_cs_'+randid+'" data-randid="'+randid+'" name="select_cs" style="height: 45px;" title="CS"> <option value="0">Choose CS</option><?php echo $data_usercs; ?></select> </div><div class="col-lg-6" style="padding-left: 0;"> <input class="form-control" type="text" id="apikey_cs_'+randid+'" placeholder="Wanotif Apikey"> </div><div class="col-lg-1"> <button type="button" class="btn btn-danger del_apikey" title="Delete" data-randid="'+randid+'" style="margin-top: 5px;"><i class="fas fa-minus"></i></button> </div></div>');
                e.preventDefault();
            });

            $(document).on("click", ".del_apikey", function(e) {
                var randid = $(this).attr('data-randid');
                $('#container_cs_'+randid).remove();
            });

            $("#update_wanotif").click(function(e) {
    
                var wanotif_apikey  = $('#wanotif_apikey').val();
                var wanotif_message = $('#wanotif_message').val();
                var wanotif_message2 = $('#wanotif_message2').val();
                var wanotif_followup1_on = $("input#wanotif_followup1:checked").val();
                if(wanotif_followup1_on!=undefined){wanotif_followup1_on = 1;}else{wanotif_followup1_on = 0;}
                var wanotif_on = $("input#wanotif_on:checked").val();
                if(wanotif_on!=undefined){wanotif_on = 1;}else{wanotif_on = 0;}

                jlh_apikey_cs = 1;
                var new_selected_cs = [];
                $(".container_cs_box").each(function(){
                        var id = $(this).data('id');
                        var id_cs = $('#select_cs_'+id).find("option:selected").val();
                        var apikey = $('#apikey_cs_'+id).val();
                        new_selected_cs.push('"cs'+jlh_apikey_cs+'":["'+id_cs+'","'+apikey+'"]');
                        jlh_apikey_cs++;
                });
                new_selected_cs = '{'+new_selected_cs+'}';

                if(jlh_apikey_cs!=1){
                    jlh_apikey_cs = jlh_apikey_cs-1;
                    var wanotif_apikey_cs = '{"jumlah":'+jlh_apikey_cs+',"data":'+new_selected_cs+'}';
                }else{
                    var wanotif_apikey_cs = '{"jumlah":0,"data":{}}';
                }

                $('.update_wanotif_loading').show();

                var data_nya = [
                    wanotif_apikey,
                    wanotif_message,
                    wanotif_message2,
                    wanotif_followup1_on,
                    wanotif_on,
                    wanotif_apikey_cs
                ];

                var data = {
                    "action": "djafunction_update_wanotif_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Wanotif setting updated.',
                          'success'
                        );
                        $('.update_wanotif_loading').hide();
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_settings&action=notification#wanotif') ?>");
                    }
                });

            });

            $("#add_notif").click(function(e) {

                var no = 0;
                var count_box_notif = jQuery('.box_telegram_message').map(function() {
                    no = no + 1;
                    return no;
                }).get().toString();

                no = no + 1;

                var the_data = '<div id="box_tele_'+no+'" class="col-md-12 box_telegram_message"><h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Notif '+no+'</h5><div class="form-group" style="position: absolute;right: 0;margin-right: 20px;margin-top: -10px;"> <button data-id="'+no+'" type="button" class="btn btn-outline-danger waves-effect waves-light btn-xs del_notif" title="Delete Notif"><i class="mdi mdi-close"></i> Del&nbsp;</button></div><hr> <label style="margin-top: 20px;">Custom Channel :</label><textarea id="myTags_'+no+'" class="tagit"></textarea><label>Message :</label><div class="form-group"><textarea class="form-control textarea_text tele_message" rows="5" placeholder="Message"></textarea></div></div>';
                $('#box_notif').append(the_data);

                $('#myTags_'+no).tagEditor({
                    initialTags: [],
                    delimiter: ', ',
                    placeholder: 'Enter channel ...'
                });
            
            });

            $("#add_notif_email").click(function(e) {

                var no = 0;
                var count_box_notif = jQuery('.box_email_message.show_box').map(function() {
                    no = no + 1;
                    return no;
                }).get().toString();

                no = no + 1;

                $('#box_email_'+no).addClass('show_box');

                if(no>3){
                    swal.fire(
                      'Sorry!',
                      'Only 3 email notification.',
                      'info'
                    );
                }
            
            });


            if( $('#status_wanotif').length ) {

                // console.log('check status_wanotif');
                var check_status = true;

                var data_nya = [
                    check_status
                ];

                var data = {
                    "action": "djafunction_status_wanotif",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    // console.log('status_wanotif:'+response);
                    $('#status_wanotif').html(response);
                });

            }


            $(document).on('click', '.del_notif', function(e){
                var box_no = $(this).data('id');
                $('#box_tele_'+box_no).remove();
            });

            $(document).on('click', '.del_notif_email', function(e){
                var box_no = $(this).data('id');
                $('#box_email_'+box_no).removeClass('show_box');
            });


            $("#update_telegram").click(function(e) {

                var telegram_bot_token = $('#token').val();

                var allAttributes = jQuery('.box_telegram_message').map(function() {
                    var b = $(this).find('.tagit').tagEditor('getTags')[0].tags;
                    var c = $(this).find('.tele_message').val();
                    c = c.replace(/(\r\n|\r|\n)/g, 'linebreak');
                    return '{"channel":"'+b+'","message":"'+c+'"}';
                }).get().toString();
                var telegram_send_to = '['+allAttributes+']';

                var allAttributes2 = jQuery('.box_telegram_message2').map(function() {
                    var d = $(this).find('.tagit').tagEditor('getTags')[0].tags;
                    var e = $(this).find('.tele_message2').val();
                    e = e.replace(/(\r\n|\r|\n)/g, 'linebreak');
                    return '{"channel":"'+d+'","message":"'+e+'"}';
                }).get().toString();
                var telegram_manual_confirmation = '['+allAttributes2+']';

                // alert(telegram_manual_confirmation);
                // return false;

                // [{"channel":"notif 1","message":"notif 1"},{"channel":"notif 2","message":"notif 2"},{"channel":"notif 3","message":"notif 3"},{"channel":"notif 4","message":"notif 4"}]

                var telegram_on = $("input#telegram_on:checked").val();
                if(telegram_on!=undefined){telegram_on = 1;}else{telegram_on = 0;}

                

                $('.update_telegram_loading').show();

                var data_nya = [
                    telegram_send_to,
                    telegram_on,
                    telegram_bot_token,
                    telegram_manual_confirmation
                ];

                // alert(data_nya);
                // return false;

                var data = {
                    "action": "djafunction_update_telegram_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {

                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Telegram setting updated.',
                          'success'
                        );
                        $('.update_telegram_loading').hide();
                        // window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_settings&action=notification') ?>");
                    }else{
                        swal.fire(
                          'Failed!',
                          'Telegram setting failed.',
                          'warning'
                        );
                        $('.update_telegram_loading').hide();
                    }
                    // alert(response);
                });

            });

            $('.add_cc').bind('click', function() {
                var id = $(this).data('id');
                $('#box_email_'+id+' .email_cc').slideDown();
            });
            $('.add_bcc').bind('click', function() {
                var id = $(this).data('id');
                $('#box_email_'+id+' .email_bcc').slideDown();
            });

            jQuery(document).ready(function($){
                if($("#s_message_email").length > 0){
                    tinymce.init({
                          selector: "textarea#s_message_email",
                          theme: "modern",
                          height:240,
                          plugins: [
                              "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                              "save table contextmenu directionality template paste textcolor"
                          ],
                          toolbar: "oke | undo redo | bold italic | alignleft aligncenter | bullist numlist",
                          style_formats: [
                              {title: "Header", block: "h2", styles: {color: "#23374d"}},
                              {title: "Bold text", inline: "b", styles: {color: "#23374d"}},
                              {title: "Paragraph", inline: "p", styles: {color: "#23374d"}},
                              {title: "Span", inline: "span", styles: {color: "#23374d"}},
                          ],
                    });
                }
            });
            
            $("#update_email").click(function(e) {

                var nonya = 1;
                var allAttributesEmail = jQuery('.box_email_message.show_box').map(function() {
                    // var a = $(this).find('.tagitemail').tagEditor('getTags')[0].tags;
                    var a = $(this).find('.send_to_'+nonya).val();
                    var b = $(this).find('.tagitemailcc').tagEditor('getTags')[0].tags;
                    var c = $(this).find('.tagitemailbcc').tagEditor('getTags')[0].tags;
                    var d = $(this).find('.subject_email').val();
                    var e = tinyMCE.get('email_message_'+nonya).getContent();
                    e = e.replace(/(\r\n|\r|\n)/g, 'linebreak');
                    nonya++;
                    return '{"email":"'+a+'","emailcc":"'+b+'","emailbcc":"'+c+'","subject":"'+d+'","message":"'+e+'"}';

                }).get().toString();
                var email_send_to = '['+allAttributesEmail+']';

                var email_on = $("input#email_on:checked").val();
                if(email_on!=undefined){email_on = 1;}else{email_on = 0;}

                var allAttributesEmail2 = jQuery('.box_email_message.show_box2').map(function() {
                    var a = $(this).find('.send_to_4').val();
                    var b = $(this).find('.tagitemailcc').tagEditor('getTags')[0].tags;
                    var c = $(this).find('.tagitemailbcc').tagEditor('getTags')[0].tags;
                    var d = $(this).find('.subject_email').val();
                    var e = tinyMCE.get('s_message_email').getContent();
                    e = e.replace(/(\r\n|\r|\n)/g, 'linebreak');
                    return '{"email":"'+a+'","emailcc":"'+b+'","emailbcc":"'+c+'","subject":"'+d+'","message":"'+e+'"}';
                }).get().toString();
                var email_success_message = '['+allAttributesEmail2+']';

                $('.update_email_loading').show();

                var data_nya = [
                    email_send_to,
                    email_on,
                    email_success_message
                ];

                var data = {
                    "action": "djafunction_update_email_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {

                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Email setting updated.',
                          'success'
                        );
                        $('.update_email_loading').hide();
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    }else{
                        swal.fire(
                          'Failed!',
                          'Email setting failed.',
                          'warning'
                        );
                        $('.update_email_loading').hide();
                    }
                });

            });

            $(document).on('click', '#test_telegram', function(e){

                Swal.fire({
                      title: '<strong>Test Telegram</strong>',
                      icon: false,
                      html:
                        '<div id="data_box"><form class="" style="padding: 20px 50px;"><div class="row"><div class="col-md-12"> <div role="alert" class="alert alert-success border-0 send_test_telegram_success" style="font-size: 13px; margin-top: -20px; margin-bottom: 30px; display: none;">Send Message Success.</div> <div role="alert" class="alert alert-danger border-0 send_test_telegram_failed" style="font-size: 13px;margin-top: -20px;margin-bottom: 30px;display:none;">Send Message failed.</div><div class="form-group" style="text-align: left;"> <label for="channel">Channel</label><input type="text" class="form-control" id="channel_tele" placeholder="@yourchannel atau -100xxxxxxxxxx"></div></div><div class="col-md-4"></div></div><div class="row"><div class="col-md-12"><div class="form-group" style="text-align: left;"> <label for="message">Message</label><textarea class="form-control" rows="5" id="message_tele"></textarea></div></div></div><div class="row"><div class="col-sm-12 text-right"><button type="button" class="btn btn-primary px-4" id="send_test_telegram">Send Message<div class="spinner-border spinner-border-sm text-white send_test_telegram_loading" style="margin-left: 3px; display: none;"></div></button></div></div></form></div>',
                      showCloseButton: true,
                      showClass: {
                        popup: 'animate__animated animate__zoomIn animate__faster'
                      },
                      hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                      }
                    });

                $('.swal2-actions').hide();

                
            });


            $(document).on('click', '#test_email', function(e){

                Swal.fire({
                      title: '<strong>Test Email</strong>',
                      icon: false,
                      html:
                        '<div id="data_box"><form class="" style="padding: 20px 50px;"><div class="row"><div class="col-md-12"> <div role="alert" class="alert alert-success border-0 send_test_email_success" style="font-size: 13px; margin-top: -20px; margin-bottom: 30px; display: none;">Email was sent.</div> <div role="alert" class="alert alert-danger border-0 send_test_email_failed" style="font-size: 13px;margin-top: -20px;margin-bottom: 30px;display:none;">Send Message failed.</div><div class="form-group" style="text-align: left;"> <label for="email">Send to</label><input type="text" class="form-control" id="send_to" placeholder="yourmail@gmail.com"></div><div class="form-group" style="text-align: left;"> <label for="email">Subject</label><input type="text" class="form-control" id="subject_email" placeholder="Title"></div></div><div class="col-md-4"></div></div><div class="row"><div class="col-md-12"><div class="form-group" style="text-align: left;"> <label for="message">Message</label><textarea class="form-control" rows="5" id="message_email"></textarea></div></div></div><div class="row"><div class="col-sm-12 text-right"><button type="button" class="btn btn-primary px-4" id="send_test_email">Send Email<div class="spinner-border spinner-border-sm text-white send_test_email_loading" style="margin-left: 3px; display: none;"></div></button></div></div></form></div>',
                      showCloseButton: true,
                      showClass: {
                        popup: 'animate__animated animate__zoomIn animate__faster'
                      },
                      hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                      }
                    });

                $('.swal2-actions').hide();

                
            });

            

            $(document).on('click', '#send_test_telegram', function(e){
    
                var channel_tele  = $('#channel_tele').val();
                var message_tele = $('#message_tele').val();

                $('.send_test_telegram_loading').show();

                var data_nya = [
                    channel_tele,
                    message_tele
                ];

                var data = {
                    "action": "djafunction_send_test_telegram",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {

                    if(response=='success'){
                        $('.send_test_telegram_success').slideDown();

                        setTimeout(function() {
                            $('.send_test_telegram_success').slideUp('fast');
                        }, 4500);

                    }else{
                        $('.send_test_telegram_failed').slideDown();
                        setTimeout(function() {
                            $('.send_test_telegram_failed').slideUp('fast');
                        }, 4500);
                    }

                    $('.send_test_telegram_loading').hide();

                });

            });



            $(document).on('click', '#send_test_email', function(e){
    
                var send_to       = $('#send_to').val();
                var subject_email = $('#subject_email').val();
                var message_email = $('#message_email').val();

                $('.send_test_email_loading').show();

                var data_nya = [
                    send_to,
                    subject_email,
                    message_email
                ];

                var data = {
                    "action": "djafunction_send_test_email",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {

                    if(response=='success'){
                        $('.send_test_email_success').slideDown();

                        setTimeout(function() {
                            $('.send_test_email_success').slideUp('fast');
                        }, 4500);

                    }else{
                        $('.send_test_email_failed').slideDown();
                        setTimeout(function() {
                            $('.send_test_email_failed').slideUp('fast');
                        }, 4500);
                    }

                    $('.send_test_email_loading').hide();

                });

            });

            $(document).on('click', '#test_wanotif', function(e){

                Swal.fire({
                      title: '<strong>Test Wanotif</strong>',
                      icon: false,
                      html:
                        '<div id="data_box"><form class="" style="padding:20px 50px"><div class="row"><div class="col-md-12"><div role="alert" class="alert alert-success border-0 send_test_wanotif_success" style="font-size:13px;margin-top:-20px;margin-bottom:30px;display:none">Message was sent.</div><div role="alert" class="alert alert-danger border-0 send_test_wanotif_failed" style="font-size:13px;margin-top:-20px;margin-bottom:30px;display:none">Send Message failed.</div><div class="form-group" style="text-align:left"><label for="channel">Sender</label><select class="form-control" id="select_apikey" name="select_apikey" style="height:45px;max-width:inherit;" title="CS"><option value="0">Choose Apikey</option><option value="<?php echo $wanotif_apikey; ?>">Default - <?php echo substr($wanotif_apikey, 0, 16); ?>...</option><?php echo $wanotif_sender; ?></select></div><div class="form-group" style="text-align:left"><label for="channel">No Whatsapp</label><input type="text" class="form-control" id="no_wa" placeholder="08xxxxxxxxxx"></div></div><div class="col-md-4"></div></div><div class="row"><div class="col-md-12"><div class="form-group" style="text-align:left"><label for="message">Message</label><textarea class="form-control" rows="5" id="message_wa"></textarea></div></div></div><div class="row"><div class="col-sm-12 text-right"><button type="button" class="btn btn-primary px-4" id="send_test_wanotif">Send Message<div class="spinner-border spinner-border-sm text-white send_test_wanotif_loading" style="margin-left:3px;display:none"></div></button></div></div></form></div>',
                      showCloseButton: true,
                      showClass: {
                        popup: 'animate__animated animate__zoomIn animate__faster'
                      },
                      hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                      }
                    });

                $('.swal2-actions').hide();

                
            });

            

            $(document).on('click', '#send_test_wanotif', function(e){
    
                var apikey  = $('#select_apikey').find(":selected").val();
                var no_wa  = $('#no_wa').val();
                var message_wa = $('#message_wa').val();

                $('.send_test_wanotif_loading').show();

                var data_nya = [
                    apikey,
                    no_wa,
                    message_wa
                ];

                var data = {
                    "action": "djafunction_send_test_wanotif",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {


                    if(response=='success'){
                        $('.send_test_wanotif_success').slideDown();

                        setTimeout(function() {
                            $('.send_test_wanotif_success').slideUp('fast');
                        }, 4500);

                    }else{
                        $('.send_test_wanotif_failed').slideDown();
                        setTimeout(function() {
                            $('.send_test_wanotif_failed').slideUp('fast');
                        }, 4500);
                    }

                    $('.send_test_wanotif_loading').hide();

                });

            });


            // ************************************
            // Social Proof
            // ************************************

            $('#socialproof_text').keyup(function() {
                var title = $(this).val();
                $('#dsproof-title').text(title);
            });

            $('input[type=radio][name=time_set]').change(function() {
                if (this.value == 'show') {
                    $('#time_set_preview').show();
                } else {
                    $('#time_set_preview').hide();
                }
            });

            $('#popup_style').change(function() {
                var value = $(this).val();
                // alert(value);
                if(value=='boxed'){
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('s-rounded');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('s-flying');
                }
                if(value=='rounded'){
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('s-rounded');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('s-flying');
                }
                if(value=='flying_boxed'){
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('s-rounded');
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('s-flying');
                }
                if(value=='flying_rounded'){
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('s-rounded');
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('s-flying');
                }
                
            });

            $('#position').change(function() {
                var value = $(this).val();
                // alert(value);
                if(value=='top_left'){
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('top_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('top_right');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('bottom_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('bottom_right');
                }
                if(value=='top_right'){
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('top_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('top_right');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('bottom_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('bottom_right');
                }
                if(value=='bottom_left'){
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('top_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('top_right');
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('bottom_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('bottom_right');
                }
                if(value=='bottom_right'){
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('top_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('top_right');
                    $('#box-socialproof-setting .donasiaja-socialproof').removeClass('bottom_left');
                    $('#box-socialproof-setting .donasiaja-socialproof').addClass('bottom_right');
                }
                
            });

            $("#update_socialproof").click(function(e) {
                
                var socialproof_text = $('#socialproof_text').val();

                var popup_style = $('#popup_style').find(":selected").val();
                var position    = $('#position').find(":selected").val();
                var time_set    = $("input[type='radio'][name='time_set']:checked").val();
                var delay       = $('#delay').find(":selected").val();
                var data_load   = $('#data_load').find(":selected").val();
                
                var socialproof_settings = '{"settings":["'+popup_style+'","'+position+'","'+time_set+'","'+delay+'","'+data_load+'"]}';

                $('.update_socialproof_loading').show();

                var data_nya = [
                    socialproof_text,
                    socialproof_settings
                ];

                var data = {
                    "action": "djafunction_update_socialproof",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Social Proof setting updated.',
                          'success'
                        );
                        $('.update_socialproof_loading').hide();
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                        
                    }
                });

            });

            // ************************************
            // Fundraising
            // ************************************

            $('#fundraiser_on').change(function() {
                if(this.checked) {
                    $('#checkbox_fundraiser_on span').text('Active');
                }else{
                    $('#checkbox_fundraiser_on span').text('Not Active');
                }
            });

            $('#fundraiser_commission_on').change(function() {
                if(this.checked) {
                    $('#checkbox_fundraiser_commission_on span').text('Active');
                }else{
                    $('#checkbox_fundraiser_commission_on span').text('Not Active');
                }
            });

            $('#min_payout_setting_on').change(function() {
                if(this.checked) {
                    $('#checkbox_min_payout_setting_on span').text('Active');
                    $('#min_saldo').slideDown();
                }else{
                    $('#checkbox_min_payout_setting_on span').text('Not Active');
                    $('#min_saldo').slideUp();
                }
            });

            $('#fundraiser_wa_on').change(function() {
                if(this.checked) {
                    $('#checkbox_fundraiser_wa_on span').text('Active');
                }else{
                    $('#checkbox_fundraiser_wa_on span').text('Not Active');
                }
            });

            $('#fundraiser_email_on').change(function() {
                if(this.checked) {
                    $('#checkbox_fundraiser_email_on span').text('Active');
                }else{
                    $('#checkbox_fundraiser_email_on span').text('Not Active');
                }
            });

            $('input[type=radio][name=fundraiser_commission_type]').change(function() {
                if (this.value == '1') {
                    $('.fundraiser_commission_percent').hide();
                    $('.fundraiser_commission_fixed').show();
                } else {
                    $('.fundraiser_commission_percent').show();
                    $('.fundraiser_commission_fixed').hide();
                }
            });

            const regex2 = /[^\d.]|\.(?=.*\.)/g;
            const regex3 = /[^\d]|\.(?=.*\.)/g; // tanpa titik
            const subst2 =``;
            $("#fundraiser_commission_percent").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                const str=this.value;
                const result = str.replace(regex2, subst2);
                this.value=result;
            });

            $("#fundraiser_commission_fixed").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return valuenya = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "");
                });
            });

            $("#min_payout").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                const str=this.value;
                const result = str.replace(regex3, subst2);
                this.value=result;
            });

            jQuery(document).ready(function($){
                if($("#f_message_email").length > 0){
                    tinymce.init({
                          selector: "textarea#f_message_email",
                          theme: "modern",
                          height:240,
                          plugins: [
                              "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                              "save table contextmenu directionality template paste textcolor"
                          ],
                          toolbar: "oke | undo redo | bold italic | alignleft aligncenter | bullist numlist",
                          style_formats: [
                              {title: "Header", block: "h2", styles: {color: "#23374d"}},
                              {title: "Bold text", inline: "b", styles: {color: "#23374d"}},
                              {title: "Paragraph", inline: "p", styles: {color: "#23374d"}},
                              {title: "Span", inline: "span", styles: {color: "#23374d"}},
                          ],
                    });
                }

            });

            $("#update_fundraising").click(function(e) {

                var fundraiser_on = $("input#fundraiser_on:checked").val();
                if(fundraiser_on!=undefined){fundraiser_on = 1;}else{fundraiser_on = 0;}
                var fundraiser_text = $("#fundraiser_text").val();
                var fundraiser_button = $("#fundraiser_button").val();
                var fundraiser_commission_on = $("input#fundraiser_commission_on:checked").val();
                if(fundraiser_commission_on!=undefined){fundraiser_commission_on = 1;}else{fundraiser_commission_on = 0;}
                var fundraiser_commission_type = $("input[type='radio'][name='fundraiser_commission_type']:checked").val();
                var fundraiser_commission_percent = $('#fundraiser_commission_percent').val();
                var fundraiser_commission_fixed = $('#fundraiser_commission_fixed').val();
                var fundraiser_wa_on = $("input#fundraiser_wa_on:checked").val();
                if(fundraiser_wa_on!=undefined){fundraiser_wa_on = 1;}else{fundraiser_wa_on = 0;}
                var fundraiser_email_on = $("input#fundraiser_email_on:checked").val();
                if(fundraiser_email_on!=undefined){fundraiser_email_on = 1;}else{fundraiser_email_on = 0;}
                var fundraiser_wa_text = $("#fundraiser_wa_text").val();
                var min_payout_setting_on = $("input#min_payout_setting_on:checked").val();
                if(min_payout_setting_on!=undefined){min_payout_setting_on = 1;}else{min_payout_setting_on = 0;}
                var min_payout = $("#min_payout").val();

                var allAttributesEmail = jQuery('.box_email_message.show_box2').map(function() {
                    var a = $(this).find('.send_to_5').val();
                    var b = $(this).find('.tagitemailcc').tagEditor('getTags')[0].tags;
                    var c = $(this).find('.tagitemailbcc').tagEditor('getTags')[0].tags;
                    var d = $(this).find('.subject_email').val();
                    var e = tinyMCE.get('f_message_email').getContent();
                    e = e.replace(/(\r\n|\r|\n)/g, 'linebreak');
                    return '{"email":"'+a+'","emailcc":"'+b+'","emailbcc":"'+c+'","subject":"'+d+'","message":"'+e+'"}';
                }).get().toString();
                var fundraiser_email_text = '['+allAttributesEmail+']';

                $('.update_fundraising_loading').show();

                var data_nya = [
                    fundraiser_on,
                    fundraiser_text,
                    fundraiser_button,
                    fundraiser_commission_on,
                    fundraiser_commission_type,
                    fundraiser_commission_percent,
                    fundraiser_commission_fixed,
                    fundraiser_wa_on,
                    fundraiser_email_on,
                    fundraiser_wa_text,
                    fundraiser_email_text,
                    min_payout_setting_on,
                    min_payout
                ];

                var data = {
                    "action": "djafunction_update_fundraising_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {

                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Fundraising setting updated.',
                          'success'
                        );
                        $('.update_fundraising_loading').hide();
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    }else{
                        swal.fire(
                          'Failed!',
                          'Fundraising setting failed.',
                          'warning'
                        );
                        $('.update_fundraising_loading').hide();
                    }
                });
                

            });



            // ************************************
            // GENERAL
            // ************************************

            function setCaret(theid) {
                var el = document.getElementById(theid);
                var range = document.createRange();
                var sel = window.getSelection();
                range.setStart(el.childNodes[0], 0);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
                el.focus();
            }
            
            $('.edit_category').click(function() {
                var id = $(this).attr('data-id');
                text_edit = $(this).attr('data-text');
                $('.set_category').attr('contenteditable', false);
                $('#cat_name_'+id).attr('contenteditable', true);
                var theid = 'cat_name_'+id;
                setCaret(theid);
            });

            $('.del_category').click(function() {

                var id = $(this).attr('data-id');

                swal.fire({
                  title: 'Anda yakin ingin menghapus category ini?',
                  text: "Data tidak bisa dikembalikan jika sudah dihapus!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Ya, Hapus sekarang!',
                  cancelButtonText: 'Cancel',
                  reverseButtons: true
                }).then(function(result) {
                  if (result.value) {
                    $('.set_category_loading').show();
                    var data_nya = [
                        id
                    ];

                    var data = {
                        "action": "djafunction_del_category",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){

                            $('#cat_'+id).slideUp();

                            swal.fire(
                              'Deleted!',
                              'Category berhasil didelete.',
                              'success'
                            );

                        }else{
                            swal.fire(
                              'Delete Failed!',
                              '',
                              'warning'
                            );

                        }
                        $('.set_category_loading').hide();
                        
                    });
                    

                  }
                })

                
            });
            

            $('.set_category').on('keydown', function(e) {
                if (e.which === 13 && e.shiftKey === false) {
                    $(this).attr("contentEditable", 'false');
                    // run to save slug

                    $('.set_category_loading').show();
                    var idnya = $(this).attr('data-id');
                    var valuenya = $(this).text();

                    var data_nya = [
                        idnya,
                        valuenya,
                    ];

                    var data = {
                        "action": "djafunction_save_category",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){
                            swal.fire(
                              'Success!',
                              'Category success updated.',
                              'success'
                            );
                        }
                        $('.set_category_loading').hide();
                    });
                    return false;
                }
            });

            $(".set_category").blur(function(){
                
                var idnya = $(this).attr('data-id');
                var valuenya = $(this).text();

                if(text_edit!=valuenya){
                    $('.set_category_loading').show();
                    var data_nya = [
                        idnya,
                        valuenya,
                    ];

                    var data = {
                        "action": "djafunction_save_category",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){
                            swal.fire(
                              'Success!',
                              'Category success updated.',
                              'success'
                            );
                        }
                        $('.set_category_loading').hide();
                        $('.set_category').attr('contenteditable', false);
                    });
                    return false;
                }
                
            });

            $('.add_new_category').on('click', function(e) {

                $('.set_category_loading').show();

                var data_nya = [
                    'add_new'
                ];

                var data = {
                    "action": "djafunction_add_new_category",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                            swal.fire(
                              'Success!',
                              'Category success ditambahkan.',
                              'success'
                            );
                        }
                    $('.set_category_loading').hide();
                    window.location.reload();
                });
            });


            $('.checkbox_private').change(function() {
                if(this.checked) {
                    var idnya = $(this).data('id');
                    var value = '1';
                }else{
                    var idnya = $(this).data('id');
                    var value = '0';
                }

                var data_nya = [
                    idnya,
                    value,
                ];

                var data = {
                    "action": "djafunction_update_category_private",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        if(value=='1'){
                            var valuenya = 'PRIVATE';
                        }else{
                            var valuenya = 'PUBLIC';
                        }
                        swal.fire(
                          'Success!',
                          'Updated to '+valuenya,
                          'success'
                        );
                    }
                    $('.set_category_loading').hide();
                });

            });

            


            $('#page_login').keyup(function() {
                var pagename = $(this).val();
                $('.set_page_login').text(pagename);
            });

            $('#page_register').keyup(function() {
                var pagename = $(this).val();
                $('.set_page_register').text(pagename);
            });

            $('#powered_by_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_powered_by_setting span').text('Show');
                }else{
                    $('#checkbox_powered_by_setting span').text('Hide');
                }
            });

            $('#login_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_login_setting span').text('Active');
                }else{
                    $('#checkbox_login_setting span').text('Not Active');
                }
            });

            $('#register_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_register_setting span').text('Active');
                }else{
                    $('#checkbox_register_setting span').text('Not Active');
                }
            });

            $('#changepass_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_changepass_setting span').text('Active');
                }else{
                    $('#checkbox_changepass_setting span').text('Not Active');
                }
            });

            $('#register_checkbox_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_register_checkbox_setting span').text('Active');
                }else{
                    $('#checkbox_register_checkbox_setting span').text('Not Active');
                }
            });

            $('#campaign_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_campaign_setting span').text('Active');
                }else{
                    $('#checkbox_campaign_setting span').text('Not Active');
                }
            });

            $('#del_campaign_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_del_campaign_setting span').text('Active');
                }else{
                    $('#checkbox_del_campaign_setting span').text('Not Active');
                }
            });

            // $('#jquery_on').change(function() {
            //     if(this.checked) {
            //         $('#checkbox_jquery_on span').text('Active');
            //     }else{
            //         $('#checkbox_jquery_on span').text('Not Active');
            //     }
            // });

            $('input[type=radio][name=max_love]').change(function() {

                if (this.value == '0') {
                    $('#max_love_input').hide();
                } else {
                    $('#max_love_input').show();
                }
            });

            $('input[type=radio][name=jquery_on]').change(function() {

                if (this.value == '2') {
                    $('#box_jquery_custom').show();
                } else {
                    $('#box_jquery_custom').hide();
                }
            });

            $('#fb_pixel').tagEditor({
                initialTags: [<?php echo $pixel_id; ?>],
                delimiter: ', ',
                placeholder: '...'
            });

            $("#update_general").click(function(e) {
                
                var login_setting = $("input#login_setting:checked").val();
                var page_login = $('#page_login').val();
                var login_text = $('#login_text').val();

                var register_setting = $("input#register_setting:checked").val();
                var page_register = $('#page_register').val();
                var register_text = $('#register_text').val();

                var campaign_setting = $("input#campaign_setting:checked").val();
                var del_campaign_setting = $("input#del_campaign_setting:checked").val();

                var label_tab = $("input[type='radio'][name='label_tab']:checked").val();
                var max_love = $("input[type='radio'][name='max_love']:checked").val();
                var max_love_custom = $('#max_love_custom').val();

                var powered_by_setting = $("input#powered_by_setting:checked").val();

                var fb_pixel = $("#fb_pixel").tagEditor('getTags')[0].tags.toString();
                var event_1 = $('#event_1').find(":selected").val();
                var event_2 = $('#event_2').find(":selected").val();
                var event_3 = $('#event_3').find(":selected").val();
                var fb_event = '{"event":["'+event_1+'","'+event_2+'","'+event_3+'"]}';

                var jquery_on = $("input[type='radio'][name='jquery_on']:checked").val();
                var jquery_custom = $('#jquery_custom').find(":selected").val();

                

                var gtm_id = $("#gtm_id").val();
                var changepass_setting = $("input#changepass_setting:checked").val();

                var tiktok_pixel = $("#tiktok_pixel").val();

                var register_checkbox_setting = $("input#register_checkbox_setting:checked").val();
                var register_checkbox_info = $('#register_checkbox_info').val();

                $('.update_general_loading').show();

                var data_nya = [
                    login_setting,
                    page_login,
                    login_text,
                    register_setting,
                    page_register,
                    register_text,
                    campaign_setting,
                    del_campaign_setting,
                    label_tab,
                    max_love,
                    max_love_custom,
                    powered_by_setting,
                    fb_pixel,
                    fb_event,
                    jquery_on,
                    gtm_id,
                    changepass_setting,
                    tiktok_pixel,
                    register_checkbox_setting,
                    register_checkbox_info,
                    jquery_custom
                ];

                // alert(data_nya);
                // return false;

                var data = {
                    "action": "djafunction_update_general_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'General setting updated.',
                          'success'
                        );
                        $('.update_general_loading').hide();
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                        
                    }
                });

            });

            function createAlert(e,t,n){var a,o=document.createElement("div");o.className+="animation-target lala-alert ";var r="alert-"+t+" ";o.className+=r;var l=document.createElement("span");l.className+=" close-alert-x glyphicon glyphicon-remove",l.addEventListener("click",function(){var e=this.parentNode;e.parentNode.removeChild(e)}),o.addEventListener("mouseover",function(){this.classList.remove("fade-out"),clearTimeout(a)}),o.addEventListener("mouseout",function(){a=setTimeout(function(){o.parent;o.className+=" fade-out",o.parentNode&&(a=setTimeout(function(){o.parentNode.removeChild(o)},500))},3e3)}),o.innerHTML=e,o.appendChild(l);var d=document.getElementById("lala-alert-wrapper");d.insertBefore(o,d.children[0]),a=setTimeout(function(){var e=o;e.className+=" fade-out",e.parentNode&&(a=setTimeout(function(){e.parentNode.removeChild(e)},500))},n)}window.onload=function(){document.getElementById("lala-alert-wrapper"),document.getElementById("alert-success"),document.getElementById("alert-info"),document.getElementById("alert-warning"),document.getElementById("alert-danger")};


            // ************************************
            // FORM
            // ************************************

            $('#page_donate, #page_typ, #page_login, #page_register').on('keydown', function(e) {
                if (event.keyCode == 32) {
                    return false;
                }
            });

            $('#page_donate').keyup(function(e) {
                
                var pagename = $(this).val();
                $('.set_page_donate').text(pagename);
            });

            $('#page_typ').keyup(function() {
                var pagename = $(this).val();
                $('.set_page_typ').text(pagename);
            });

            $('#form_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_form_setting span').text('Active');
                }else{
                    $('#checkbox_form_setting span').text('Not Active');
                }

            });

            $('#form_email_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_form_email_setting span').text('Show');
                }else{
                    $('#checkbox_form_email_setting span').text('Hide');
                }
            });

            $('#form_comment_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_form_comment_setting span').text('Show');
                }else{
                    $('#checkbox_form_comment_setting span').text('Hide');
                }
            });

            $('#limitted_donation_button').change(function() {
                if(this.checked) {
                    $('#checkbox_limitted_donation_button span').text('Active');
                }else{
                    $('#checkbox_limitted_donation_button span').text('Not Active');
                }
            });

            $("#update_form").click(function(e) {
                
                var page_donate = $('#page_donate').val();
                var form_setting = $("input#form_setting:checked").val();
                var max_package = $('#max_package').val();
                var anonim_text = $('#anonim_text').val();
                var page_typ = $('#page_typ').val();
                var form_email_setting = $("input#form_email_setting:checked").val();
                var form_comment_setting = $("input#form_comment_setting:checked").val();
                var limitted_donation_button = $("input#limitted_donation_button:checked").val();
                var form_confirmation_setting = $("input[type='radio'][name='form_confirmation_setting']:checked").val();

                var new_selected_nominal_donasi = [];
                var i = 1;
                $(".nominalnya").each(function(){
                        var radio = $("input[type='radio'][name='sering_dipilih']:checked").val();
                        if(radio==i){
                            radio = 1;
                        }else{
                            radio = 0;
                        }
                        var id_labelnya = 'opt_label'+i;
                        var id_seringnya = 'sering_dipilih'+i;
                        var labelnya = $('#'+id_labelnya).val();
                        var seringnya = $('#'+id_seringnya).val();
                        new_selected_nominal_donasi.push('"opt'+i+'":["'+$(this).val()+'","'+labelnya+'","'+radio+'"]');

                        i = i+1;
                });

                new_selected_nominal_donasi = '{'+new_selected_nominal_donasi+'}';

                var text1 = $('#text1').val();
                var text2 = $('#text2').val();
                var text3 = $('#text3').val();
                var text4 = $('#text4').val();
                var form_text = '{"text":["'+text1+'","'+text2+'","'+text3+'","'+text4+'"]}';

                var minimum_donate = $('#minimum_donate').val();

                $('.update_form_loading').show();

                var data_nya = [
                    page_donate,
                    form_setting,
                    new_selected_nominal_donasi,
                    max_package,
                    anonim_text,
                    page_typ,
                    form_text,
                    form_email_setting,
                    form_comment_setting,
                    limitted_donation_button,
                    form_confirmation_setting,
                    minimum_donate
                ];

                var data = {
                    "action": "djafunction_update_form_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Form setting updated.',
                          'success'
                        );
                        window.location.reload();
                    }
                });

            });


            // ************************************
            // THEMES
            // ************************************
            // Coloring
            $('.coloring').each( function() {
                $(this).minicolors({
                  control: $(this).attr('data-control') || 'hue',
                  defaultValue: $(this).attr('data-defaultValue') || '',
                  format: $(this).attr('data-format') || 'hex',
                  keywords: $(this).attr('data-keywords') || '',
                  inline: $(this).attr('data-inline') === 'true',
                  letterCase: $(this).attr('data-letterCase') || 'lowercase',
                  opacity: $(this).attr('data-opacity'),
                  position: $(this).attr('data-position') || 'bottom',
                  swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
                  change: function(hex, opacity) {
                    var log;
                    try {
                      log = hex ? hex : 'transparent';
                      if( opacity ) log += ', ' + opacity;
                      console.log(log);
                    } catch(e) {}
                  },
                  theme: 'default'
                });
            });

            $("#update_themes").click(function(e) {
                
                var app_name = $('#app_name').val();

                var theme_color = $('#theme_color').val();
                var progressbar_color = $('#progressbar_color').val();
                var button_color = $('#button_color').val();
                var button_color2 = $('#button_color2').val();

                var general_theme_color = '{"color":["'+theme_color+'","'+progressbar_color+'","'+button_color+'","'+button_color2+'"]}';
                

                $('.update_themes_loading').show();

                var data_nya = [
                    app_name,
                    general_theme_color

                ];

                var data = {
                    "action": "djafunction_update_themes_settings",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Themes setting updated.',
                          'success'
                        );
                        window.location.reload();
                    }
                });

            });


            $('#upload_app_logo').click(function(e) {
                e.preventDefault();
                var image = wp.media({ 
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                .on('select', function(e){
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;

                    if(image_url.includes(".jpg")){
                        var imagenya = image_url.split(".jpg");
                        var new_image_url = imagenya[0]+"-150x150.jpg";
                    }
                    if(image_url.includes(".jpeg")){
                        var imagenya = image_url.split(".jpeg");
                        var new_image_url = imagenya[0]+"-150x150.jpeg";
                    }
                    if(image_url.includes(".png")){
                        var imagenya = image_url.split(".png");
                        var new_image_url = imagenya[0]+"-150x150.png";
                    }

                    $.get(new_image_url)
                    .done(function() { 
                        // Do something now you know the image exists.
                        $("#app_logo img").attr("src",new_image_url);
                        $("#app_logo img").attr("data-file",new_image_url);

                        var user_id = <?php echo $id_login; ?>;
                        var user_ktp = new_image_url;

                        var data_nya = [
                            user_id,
                            user_ktp
                        ];

                        var data = {
                            "action": "djafunction_upload_app_logo",
                            "datanya": data_nya
                        };

                        jQuery.post(ajaxurl, data, function(response) {
                            if(response=='success'){
                                swal.fire(
                                  'Success!',
                                  'App Logo sukses di Upload.',
                                  'success'
                                );
                            }
                        });

                    }).fail(function() { 
                        // Image doesn't exist - do something else.
                        // $("#app_logo img").attr("src",image_url);

                        var new_image_url = image_url;
                        $("#app_logo img").attr("src",new_image_url);
                        $("#app_logo img").attr("data-file",new_image_url);

                        var user_id = <?php echo $id_login; ?>;
                        var user_ktp = new_image_url;

                        var data_nya = [
                            user_id,
                            user_ktp
                        ];

                        var data = {
                            "action": "djafunction_upload_app_logo",
                            "datanya": data_nya
                        };

                        jQuery.post(ajaxurl, data, function(response) {
                            if(response=='success'){
                                swal.fire(
                                  'Success!',
                                  'App Logo sukses di Upload.',
                                  'success'
                                );
                            }
                        });
                    });

                });
            });

            jQuery(document).ready(function($){

                // $('body#tinymce').css({"font-size":"13px"});
                // tinyMCE.init({
                // mode : "textareas",
                //     setup : function(ed)
                //     {
                //         // set the editor font size
                //         ed.onInit.add(function(ed)
                //         {
                //         ed.getBody().style.fontSize = '10px';
                //         });
                //     },
                //     });

                <?php 
                                                                    
                $no = 1;
                foreach($email_send_to as $key => $value) {

                        $message_email = $value->message;
                        if (isset($value->subject)){
                           $subject_email = $value->subject;
                        }else{
                           $subject_email = '';
                        }

                    echo '   

                    if($("#email_message_'.$no.'").length > 0){
                      tinymce.init({
                          selector: "textarea#email_message_'.$no.'",
                          theme: "modern",
                          height:240,
                          plugins: [
                              "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                              "save table contextmenu directionality template paste textcolor"
                          ],
                          toolbar: "oke | undo redo | bold italic | alignleft aligncenter | bullist numlist",
                          style_formats: [
                              {title: "Header", block: "h2", styles: {color: "#23374d"}},
                              {title: "Bold text", inline: "b", styles: {color: "#23374d"}},
                              {title: "Paragraph", inline: "p", styles: {color: "#23374d"}},
                              {title: "Span", inline: "span", styles: {color: "#23374d"}},
                          ],
                      });
                    }
                    '; 

                    $no++;

                }


                
                $jumlah_email = count($email_send_to);
                $email_tambahan = 3;

                for ($i = $jumlah_email; $i <= $email_tambahan; $i++){

                    if($i>$jumlah_email){

                    echo '   

                    if($("#email_message_'.$i.'").length > 0){
                      tinymce.init({
                          selector: "textarea#email_message_'.$i.'",
                          theme: "modern",
                          height:240,
                          plugins: [
                              "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                              "save table contextmenu directionality template paste textcolor"
                          ],
                          toolbar: "oke | undo redo | bold italic | alignleft aligncenter | bullist numlist",
                          style_formats: [
                              {title: "Header", block: "h2", styles: {color: "#23374d"}},
                              {title: "Bold text", inline: "b", styles: {color: "#23374d"}},
                              {title: "Paragraph", inline: "p", styles: {color: "#23374d"}},
                              {title: "Span", inline: "span", styles: {color: "#23374d"}},
                          ],
                          init_instance_callback:function(editor){
                             editor.setContent("");
                          },


                      });
                    }
                    '; 
                    } // end if

                } // end for




                ?>

            });


        </script>



    <?php
}

function djax() {
    global $wpdb;
    $table_name=$wpdb->prefix."options";
    $table_name2=$wpdb->prefix."dja_settings";
    $t=do_shortcode('[donasiaja show="total_terkumpul"]');
    $d=do_shortcode('[donasiaja show="jumlah_donasi"]');
    $row=$wpdb->get_results('SELECT option_value from '.$table_name.' where option_name="siteurl"');
    $row=$row[0];
    $query_settings=$wpdb->get_results('SELECT data from '.$table_name2.' where type="apikey_local" ORDER BY id ASC');
    $aaa=$query_settings[0]->data;
    $aa=json_decode($aaa,true);
    $a=$aa['donasiaja'][0];
    $g='e';
    $h='r';
    $e='m';
    $f='b';
    $c='m';
    $k='e';
    $protocols=array('http://','http://www.','www.','https://','https://www.');
    $server=str_replace($protocols,'',$row->option_value);
    $apiurl='https://'.$e.$k.$c.$f.$g.$h.'.donasiaja.id/vw/check';
    $curl=curl_init();
    curl_setopt_array($curl,array(
        CURLOPT_URL=>$apiurl,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_VERBOSE=>true,
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_ENCODING=>"",
        CURLOPT_MAXREDIRS=>10,
        CURLOPT_TIMEOUT=>30,
        CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST=>"GET",
        CURLOPT_HTTPHEADER=>array("O: $server","A: $a","T: $t","D: $d",),
    ));
    $response=curl_exec($curl);
    $err=curl_error($curl);
    curl_close($curl);
}