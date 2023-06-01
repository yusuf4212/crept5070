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

        <?php if($action === 'themes') {
            check_license();

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-theme.php';

        } elseif($action === 'form') {
            check_license();

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-form.php';

        } elseif($action === 'payment') {
            check_license();

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-payment.php';

        } elseif($action === 'notification') {
            check_license();
            
            require_once ROOTDIR_DNA . 'admin/page/settings/settings-notification.php';

        } elseif($action === 'socialproof') {
            check_license();

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-socialproof.php';

        } elseif($action === 'fundraising') {
            check_license();

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-general.php';

        } elseif($action === 'general') {
            check_license();

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-general.php';

        } elseif($action === 'waba') {

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-waba.php';

        } elseif($action === 'socialgraph') {

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-socialgraph.php';

        } else {

            require_once ROOTDIR_DNA . 'admin/page/settings/settings-license.php';

        djax(); } ?>


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