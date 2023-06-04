<?php

function josh_dashboard_duta() {
    ?>
    <?php 
        global $wpdb;
        $table_name = $wpdb->prefix . "dja_campaign";
        $table_name2 = $wpdb->prefix . "dja_category";
        $table_name3 = $wpdb->prefix . "dja_campaign_update";
        $table_name4 = $wpdb->prefix . "dja_donate";
        $table_name5 = $wpdb->prefix . "dja_settings";
        $table_name6 = $wpdb->prefix . "dja_users";

        date_default_timezone_set('Asia/jakarta');

        donasiaja_global_vars();
        $plugin_license = strtoupper($GLOBALS['donasiaja_vars']['plugin_license']);

        // ROLE
        $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
        $roles = array_keys((array)$cap);
        $role = $roles[0];

        $id_login = wp_get_current_user()->ID;

        $akses = 1;
        if($role=='donatur'){
            $usernya = $wpdb->get_results('SELECT * from '.$table_name6.' where user_id="'.$id_login.'"')[0];

            if($usernya->user_verification=='1'){
                $akses = 1;
            }else{
                $akses = 0;
            }
        }


        if(isset($_GET['action'])){
            if($_GET['action']=="settings"){
                $settings = true;
            }else{
                $settings = false;
            }
        }else{
            $settings = false;
        }

        // category
        $row2 = $wpdb->get_results('SELECT * from '.$table_name2.' ');     

        // Settings
        $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="form_setting" or type="btn_followup" or type="text_f1" or type="text_f2" or type="text_f3" or type="text_f4" or type="text_f5" or type="text_received" or type="text_received_status" or type="app_name" or type="wanotif_on_dashboard"  ORDER BY id ASC');
        $form_setting = $query_settings[0]->data;
        $btn_followup = $query_settings[1]->data;
        $text_f1 = $query_settings[2]->data;
        $text_f2 = $query_settings[3]->data;
        $text_f3 = $query_settings[4]->data;
        $text_f4 = $query_settings[5]->data;
        $text_f5 = $query_settings[6]->data;
        $text_received = $query_settings[7]->data;
        $text_received_status = $query_settings[8]->data;
        $app_name = $query_settings[9]->data;
        $wanotif_on_dashboard = $query_settings[10]->data;

        $user_info = get_userdata($id_login);
        $first_name = $user_info->first_name;
        $last_name = $user_info->last_name;
        $fullname = $first_name.' '.$last_name;
        
    ?>


    <!-- DataTables -->
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" /> 

    <!-- App css -->
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
    
    <!-- jQuery -->
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery-ui.min.js"></script>


    <?php 

    // wp_enqueue_script('jquery');
    // This will enqueue the Media Uploader script
    wp_enqueue_media();
        
    ?>

    <style>
    .row [class^="col"] {
        margin:0;
    }
    .notice, #message, #dolly {
        display:none;
    }
    #swal2-content {
        text-align: center;
    }
    .text-truncate {
        text-align: center;
    }
    .media:hover {
        background: none !important;
    }
    .dropdown-item.active {
        color: #4956FF !important;
        background: #fff !important;
    }
    .dropdown-item:hover {
        color: #4956FF !important;
    }
    .date_donasi {
        font-size: 11px;
    }
    .edit_table {
        font-size: 21px;position: absolute;right: 0;margin-right: 45px;top: 35px;color: #7c94b3;
    }
    i.edit_table:hover {
        cursor:pointer;
        color: #7680FF;
    }
    body {
        background: #f6faff;
    }
    .update-nag, .error, #setting-error-tgmpa {
        display:none;
    }
    .set_payment.received span {
        color:#36BD47;
    }
    .set_payment.waiting span {
        color:#E1345E;
    }
    #box-section {
        margin: 0 auto;
        margin-top: 20px;
        max-width: 540px;
    }
    button.detail_donasi i {
        color: #91a2b0 !important;
        margin-right: 3px;
    }
    button.detail_donasi.img_confirmation i {
        color: #16e630 !important;
        margin-right: 3px;
        font-size: 11px !important;
        margin-top: -.15em;
        float: left;
    }
    button.detail_donasi span, button.detail_donasi.img_confirmation span {
        font-size:10px;
        color:#91a2b0;
    }

    button.detail_donasi:hover span, button.detail_donasi:hover i {
        color: #36BD47 !important;
        transition: 0.3s;
    }
    .img_confirmation {
        border:1px solid #58708c;border-radius:12px;padding:2px 7px 2px 5px;/*position: absolute;*/margin-top:7px;background:#58708c;color:#fff !important;
    }
    .img_confirmation.status_check {
        border:1px solid #ccd5df;border-radius:12px;padding:2px 7px 2px 5px;/*position: absolute;*/margin-top:7px;background:#dae2ec;color:#fff !important;
    }
    .img_confirmation i.mdi {
        color:#fff !important;
        /* position: absolute; */
    }
    button.detail_donasi.img_confirmation:hover div, button.detail_donasi.img_confirmation:hover i {
        color: #0fe82a !important;
        transition: 0.3s;
    }
    button.detail_donasi.img_confirmation.status_check div, button.detail_donasi.img_confirmation.status_check i {
        color: #8191a4 !important; /* #65768a !important; */
    }
    button.detail_donasi.img_confirmation.status_check:hover div, button.detail_donasi.img_confirmation.status_check:hover i {
        color: #65768a !important;
        transition: 0.3s;
    }
    .swal2-cancel.swal2-styled {
        height: 39px;
        font-size: 13px !important;
    }
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
    .btn-followup {
        background:#D8204C;
        border-color: #D8204C;
        padding: 2px 7px;
        margin-bottom: 3px;
    }
    .btn-followup:hover {
        background:#FD003C;
        border-color: #FD003C;
    }
    .btn-followup.sent {
        background:#36BD47;
        border-color: #36BD47;
    }
    .btn-followup.sent:hover {
        background:#1ACE31;
        border-color: #1ACE31;
    }
    .btn-followup .spinner-border {
        width: 11px;
        height: 11px;
    }
    .target_tak_hingga {
        font-size: 16px;position: absolute;margin-top: -2px;margin-left: 3px;
    }
    .field_required {
        color: #ff3b3b;
    }
    .media:hover {
        background: #f6faff;
    }
    .f_edit, .f_delete {
        cursor: pointer;
        float: right;
    }
    .f_delete {
        margin-left: 5px;
    }
    .campaign-title {
        font-size: 14px;
        font-weight: bold;
        color: #384d64;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }
    .campaign-title a:hover {
        /*color: #7680FF;*/
        /*color: #2196f3;*/
        color: #52649b;
    }    
    input.set_red, input.form-control.set_red, img.set_red, .mce-edit-area.set_red {
        border: 2px solid #ED8181 !important;
    }
    .wp-core-ui select, div.dataTables_wrapper div.dataTables_filter input {
        border-color: #e5eaf0;
    }
    div.dataTables_wrapper div.dataTables_filter input:visited, div.dataTables_wrapper div.dataTables_filter input:active, div.dataTables_wrapper div.dataTables_filter input:focus {
        border-color: #e5eaf0;
    }
    .error.landingpress-message{
        display: none;
    }
    .page-content-tab {
        margin: 0 !important;
        width: auto;
    }
    img.thumb-cover {
        height: 60px;
        border-radius: 4px;
    }
    .active-status {
        /*background: #1CB65D;*/
        background: #36BD47;
        color: #fff;
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 9px;
    }
    table.dataTable td {
        font-size: 12px;
        vertical-align: top;
        padding-top: 15px;
        color: #384d64;
    }
    table.dataTable td img {
        margin-top: 3px;
    }
    button.no-border {
        border: 0;
        background: #f6f9ff;
    }
    button.no-border:hover {
        background: #9eb5ca;
        color: #ffffff;
    }
    button.no-border.delete_campaign:hover {
        background: #F05860;
        color: #ffffff;
    }
    .btn-group button.btn {
        padding: .175rem .75rem;
    }
    
    a:active, a:focus, a:visited {
      box-shadow: none !important;
      outline: none;
      box-shadow: 0 4px 15px 0 rgba(0,0,0,.1);
    }
    input.form-control {
        border: 1px solid #e8ebf3 !important;
        font-size: 14px;
    }
    input.form-control:active, input.form-control:visited {
      border: 1px solid #7680FF !important;
      box-shadow: none !important;
      outline: none;
    }
    .mce-menubar, .mce-branding {
        display: none;
    }
    #cover_image img {
        border-radius: 4px;
    }
    .fro-profile_main-pic-change {
        cursor: pointer;
        background-color: #7680ff;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        -webkit-box-shadow: 0px 0px 20px 0px rgba(252, 252, 253, 0.05);
        box-shadow: 0px 0px 20px 0px rgba(252, 252, 253, 0.05);
        position: absolute;
        right: 44%;
        top: 82%;
        transition: all .35s ease;
    }

    .fro-profile_main-pic-change:hover {
        background-color: #505DFF;
    }
    .fro-profile_main-pic-change i {
        color: #fff;
    }

    .form-group input {
        height: 45px;
    }

    .target .currency {
        position: absolute;
        margin-top: -37px;
        margin-left: 15px;
        font-weight: bold;
        font-size: 18px;
        color: #719eca;
    }
    #packaged .currency {
        position: absolute;
        margin-top: -27px;
        margin-left: 10px;
        font-weight: bold;
        font-size: 14px;
        color: #719eca;
    }
    #packaged input {
        text-align: right;
    }
    .opt_packaged {
        display: none;
    }
    .opt_packaged.show {
        display: inline;
    }
    .target input {
        text-align: right;
        font-size: 24px;
        font-weight: bold;
        color: #23374d;
    }
    .box-slugnya {
        background: #e3eaf2;
        padding: 1px 4px;
        border-radius: 2px;
    }
    .box-slugnya[contenteditable="true"] {
        border: 1px solid #7680ff;
        background: #fff;
        padding: 1px 6px;
    }
    .copylink {
        font-size: 16px;
        margin-right: 5px;
        padding-top: 3px;
        cursor: pointer;
    }
    .copylink:hover {
        color:#505DFF;
    }
    .edit-slug, .edit-status, .edit-visibility {
        font-size: 16px;
        margin-left: 5px;
        padding-top: 3px;
        cursor: pointer;
    }
    .edit-slug:hover, .edit-status:hover, .edit-visibility:hover  {
        color:#505DFF !important;
    }
    #publish_status {
        display: none;
        margin-bottom: 5px;
    }

    #publish-section select {
        height: 30px !important;font-size: 13px;margin-top: 5px;
    }
    .page-title-box {
        padding-bottom: 0; 
    }

    .button-hide {
        visibility: hidden;
    }
    .swal2-confirm.swal2-styled {
        font-size:14px !important;
    }
    .inv.edit_detail, .inv.print_invoice {
        position: absolute; right: 0; margin-top: -30px; margin-right: 10px; cursor: pointer;
    }
    .inv.edit_detail:hover, .inv.print_invoice:hover {
        background: #ebf0fb;
    }
    .edit_donasi {
        cursor: pointer;
    }
    .edit_donasi:hover {
        color: #8daaf4;
    }
    .swal2-popup.swal2-modal{
        border-radius:12px;
        padding: 40px 40px 50px 40px;
        background: url('<?php echo plugin_dir_url( __FILE__ ).'../assets/images/bg4.png'; ?>') no-repeat, #fff;
    }
    .swal2-popup .swal2-title {
        margin-top: 10px;
    }
    .swal2-actions {
        padding-bottom: 10px;
    }
    button.swal2-close {
        color:#fff;
    }
    button.swal2-close.del_conf:hover {
        color:#fff;
        background:#ff003e !important;
        transition: 0.3s;
    }

    .btn-outline-info {
        color: #7887b5;
        border-color: #7887b5;
    }
    @keyframes redblink {
        0% {
               background-color: rgba(255,0,0,1)
        }
        50% {
               background-color: rgba(255,0,0,0.5)
        }
        100% {
               background-color: rgba(255,0,0,1)
        }
    }
    @-webkit-keyframes redblink {
        0% {
               background-color: rgba(255,0,0,1)
        }
        50% {
               background-color: rgba(255,0,0,0.5)
        }
        100% {
               background-color: rgba(255,0,0,1)
        }
    }
    .detected {
        padding: 15px 15px 15px 15px;
        -moz-transition:all 0.5s ease-in-out;
        -webkit-transition:all 0.5s ease-in-out;
        -o-transition:all 0.5s ease-in-out;
        -ms-transition:all 0.5s ease-in-out;
        transition:all 0.5s ease-in-out;
        -moz-animation:redblink normal 1.5s infinite ease-in-out;
        -webkit-animation:redblink normal 1.5s infinite ease-in-out;
        -ms-animation:redblink normal 1.5s infinite ease-in-out;
        animation:redblink normal 1.5s infinite ease-in-out;
    }
    


    /*==================================
        Alert container
    ====================================*/
    #lala-alert-container {
        position: fixed;
        height: auto;
        max-width: 350px;
        width: 100%;
        top: 18px;
        right: 5px;
        z-index: 9999;
    }

    #lala-alert-wrapper {
        height: auto;
        padding: 15px;
    }

    /*==================================
        Alerts
    ====================================*/

    .lala-alert {
        position: relative;
        padding: 25px 30px 20px;
        font-size: 15px;
        margin-top: 15px;
        opacity: 1;
        line-height: 1.4;
        border-radius: 3px;
        border: 1px solid transparent;
        cursor: default;
        transition: all 0.5s ease-in-out;   /* Edit for fadeout time */
        -webkit-user-select: none;  /* Chrome all / Safari all */
        -moz-user-select: none;     /* Firefox all */
        -ms-user-select: none;      /* IE 10+ */
        user-select: none;          /* Likely future */
    }

    .lala-alert span {
        opacity: 0.7;
        transition: all 0.25s ease-in-out;   /* Edit for fadeout time */
    }

    .lala-alert span:hover {
        opacity: 1.0;
    }

    .alert-success {
        color: #ffffff;
        background-color: #37c1aa;
    }

    .alert-success > span {
        color: #0b6f5e;
    }

    .alert-info {
        color: #ffffff;
        background-color: #3473c1;
    }

    .alert-info > span {
        color: #1e4567;
    }

    .alert-warning {
        color: #6b7117;
        background-color: #ffee9e;
    }

    .alert-warning > span {
        color: #8a6d3b;
    }

    .alert-danger {
        color: #ffffff;
        background-color: #d64f62;
    }

    .alert-danger > span {
        color: #6f1414;
    }

    .close-alert-x {
        position: absolute;
        float: right;
        top: 10px;
        right: 10px;
        cursor: pointer;
        outline: none;
    }

    .fade-out {
        opacity: 0;
    }

    /*==================================
        Alert Animation
    ====================================*/
    .animation-target {
      animation: animation 1500ms linear both;
    }

    /* Generated with Bounce.js. Edit at http://goo.gl/BKCT19 */

    @keyframes animation {
      0% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 250, 0, 0, 1); }
      3.14% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 160.737, 0, 0, 1); }
      4.3% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 132.565, 0, 0, 1); }
      6.27% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 91.357, 0, 0, 1); }
      8.61% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 51.939, 0, 0, 1); }
      9.41% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 40.599, 0, 0, 1); }
      12.48% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 6.498, 0, 0, 1); }
      12.91% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 2.807, 0, 0, 1); }
      16.22% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -17.027, 0, 0, 1); }
      17.22% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -20.404, 0, 0, 1); }
      19.95% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -24.473, 0, 0, 1); }
      23.69% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -21.178, 0, 0, 1); }
      27.36% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -13.259, 0, 0, 1); }
      28.33% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -11.027, 0, 0, 1); }
      34.77% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0.142, 0, 0, 1); }
      39.44% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 2.725, 0, 0, 1); }
      42.18% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 2.675, 0, 0, 1); }
      56.99% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -0.202, 0, 0, 1); }
      61.66% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -0.223, 0, 0, 1); }
      66.67% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, -0.104, 0, 0, 1); }
      83.98% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0.01, 0, 0, 1); }
      100% { transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1); }
    }

    .breadcrumb-item.active {
        color: #a1b3ca;
    }
    .img_payment_code {
        margin-top: -2px;
    }
    #box_stat_cs {
    background: #fff !important;padding-left: 10px;padding-right: 10px;border-radius: 8px;padding-bottom: 25px;margin-left: 10px;margin-right: 10px;margin-bottom: 10px;margin-top: 10px;padding-top: 30px !important;
    }

    @media only screen and (max-width:767px) {
        .page-title-box .breadcrumb {
            display: inline-flex !important;
            width: 100% !important;
        }
    }


    @media only screen and (max-width:480px) {

        .font-weight-semibold {
            padding-top: 25px;
        }
        #box_stat_cs {
            padding-top: 10px !important;
            padding-left: 10px !important;
        }
        .dja_label {
            width: auto;
        }
        .page-content-tab, .container-fluid {
            padding: 0;
        }
        .container-fluid .col-lg-4 {
            padding-right: 0;
        }
        .row .col-12 {
            padding-right: 0;
        }
        #update_text_followup {
            width: 100%;
        }
        #josh_cust1 {
            margin-top: 140px;
        }
        #josh_cust2 {
            float: none !important;
        }

        
        
        .page-title {
            padding-right: 0 !important;
        }
        .select2.select2-container.select2-container--default{
            position: absolute !important;
            left:  0 !important;
            margin-top: 70px;
            padding-left: 10px;
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
          background-color: #fff;
          height: 40px !important;
          border: 1px solid #c8d0e4;
          padding: 0;
          font-size: 13px;
          border-radius: 4px;
          padding-left: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-top: 5px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            margin-top: 5px;
        }
        .page-title-box .float-right.justify-content-between {
            position: absolute;
            /* left: 0; */
            margin-top: 125px;
            margin-left: 8px;
        }
        .col-total-donasi {
            margin-top: 130px;
        }
        .col-total-donasi .card, .col-jumlah-donasi .card {
            padding-bottom: 0px;
            margin-bottom: 5px;
        }
        #edit_data_donasi .row .col-sm-3 {
            float: left;
            width: 25%;
        }
        #edit_data_donasi .row .col-sm-9 {
            width: 75%;
        }
        #edit_data_donasi .select2.select2-container.select2-container--default {
            margin-top: 0;
            padding-right: 15px;
        }
        #edit_data_donasi .input-group-append.icon_pencil {
            width: 100%;padding-left: 87%;
        }
        #edit_data_donasi .input-group-append.icon_pencil button {
            height: 45px;
        }
        #edit_data_donasi .select2-selection__rendered{
            margin-top: -5px;
        }
        
        
    }

    
    </style>

    <?php check_license(); ?>

    <?php 
        if($role=='cs'){
        if($plugin_license!='ULTIMATE') { ?>
            <div class="body-nya" style="margin-top:20px;margin-right:20px;">
                <!-- Page Content-->
                <div class="page-content-tab">
                    <div class="container-fluid">
                        <!-- end page title end breadcrumb -->
                        <div class="row" style="padding: 0px 0 15px 0;margin-top:40px;">
                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                    <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php wp_die(); } } ?>

    <?php if($settings==true){ ?>

        <?php check_verified_dashboard($akses); ?>

        <!-- css -->
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate-4.1.1.min.css" rel="stylesheet" type="text/css">

        <!--Wysiwig js-->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/donasiaja-admin.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/tinymce/tinymce.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script type="text/javascript">

        jQuery(document).ready(function($){

            setTimeout(function() {
                $('#donasiaja-alert').slideUp('fast');
            }, 4000);

            $('#update_text_followup').on("click", function(e) {
                
                $('.update_text_followup_loading').show();

                var jumlah_button = $('#button_followup').find(":selected").val();
                var text_f1 = $('#text_f1').val();
                var text_f2 = $('#text_f2').val();
                var text_f3 = $('#text_f3').val();
                var text_f4 = $('#text_f4').val();
                var text_f5 = $('#text_f5').val();
                var text_received = $('#text_received').val();
                var text_received_status = $("input#text_received_status:checked").val();
                var wanotif_on_dashboard = $("input#wanotif_on_dashboard:checked").val();

                var data_nya = [
                    jumlah_button,
                    text_f1,
                    text_f2,
                    text_f3,
                    text_f4,
                    text_f5,
                    text_received,
                    text_received_status,
                    wanotif_on_dashboard
                ];

                // alert(data_nya);

                // return false;

                var data = {
                    "action": "djafunction_update_text_followup",
                    "datanya": data_nya
                };
                
                jQuery.post(ajaxurl, data, function(response) {
                    swal.fire(
                      'Success!',
                      'Update Button & Text Followup success.',
                      'success'
                    );
                    $('.update_text_followup_loading').hide();
                
                });

            });


            $('.checkbox1').change(function() {
                var id = $(this).data('id');

                if(this.checked) {
                    $('#checkbox'+id+' span').text('Active');
                }else{
                    $('#checkbox'+id+' span').text('Not Active');
                }

            });

            $('.checkbox2').change(function() {
                var id = $(this).data('id');

                if(this.checked) {
                    $('#checkbox'+id+' span').text('Active');
                }else{
                    $('#checkbox'+id+' span').text('Not Active');
                }

            });


            

        });
        </script>

        <div class="body-nya" style="margin-top:20px;margin-right:20px;">
            <div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>
            
            <!-- Page Content-->
            <div class="page-content-tab">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="float-right">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="<?php echo admin_url('admin.php?page=donasiaja_dashboard') ?>">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Settings</li>
                                    </ol>
                                </div>
                                <!-- <h4 class="page-title">Dashboard Settings</h4> -->
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>

                    <!-- end page title end breadcrumb -->
                    <div class="row">
                <div class="col-12">
                    <div class="card col-6" id="box-section">
                        <div class="card-body">
                            

                            <div class="met-profile">
                                <?php

                                if($text_received_status=='1'){
                                    $status_text1 = '<span>Active</span>';
                                    $checked1 = 'checked=""';
                                }else{
                                    $status_text1 = '<span>Not Active</span>';
                                    $checked1 = '';
                                }

                                if($wanotif_on_dashboard=='1'){
                                    $status_text2 = '<span>Active</span>';
                                    $checked2 = 'checked=""';
                                }else{
                                    $status_text2 = '<span>Not Active</span>';
                                    $checked2 = '';
                                }

                                ?>
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="margin-top: 0px;padding: 0;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body">
                                                <h4 class="card-title mt-0" style="position:absolute; margin-top: -30px !important;">Dashboard Settings</h4>
                                                <hr>
                                                <div id="data_followup" style="margin-bottom: 30px;margin-top: 30px;">
                                                    <h5 class="card-title mt-0">Followup with Wanotif<span></span></h5>
                                                        <br>

                                                    <div class="form-group">
                                                        <label style="cursor: text;">Wanotif</label>
                                                        <p class="card-text text-muted">Aktifkan jika ingin memfollowup menggunakan Wanotif. Pastikan Wanotif anda sudah di-setup atau dalam kondisi aktif agar pesan bisa terkirim. (<a href="<?php echo admin_url('admin.php?page=donasiaja_settings&action=notification');?>" target="_blank">Setting Wanotif</a>)</p>
                                                        <div class="custom-control custom-switch" id="checkbox2" style="margin-bottom: 20px;">
                                                            <input type="checkbox" class="custom-control-input checkbox2" id="wanotif_on_dashboard" data-id="2" <?php echo $checked2; ?> >
                                                            <label class="custom-control-label" for="wanotif_on_dashboard"><?php echo $status_text2; ?></label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <hr>
                                                <div id="data_followup" style="margin-bottom: 30px;margin-top: 30px;">
                                                    <h5 class="card-title mt-0">Payment Received Followup<span></span></h5>
                                                        <br>

                                                    <div class="form-group">
                                                        <label style="cursor: text;">Automatic Followup :</label>
                                                        
                                                        <div class="custom-control custom-switch" id="checkbox1" style="margin-bottom: 10px;">
                                                            <input type="checkbox" class="custom-control-input checkbox1" id="text_received_status" data-id="1" <?php echo $checked1; ?> >
                                                            <label class="custom-control-label" for="text_received_status"><?php echo $status_text1; ?></label>
                                                        </div>
                                                        <p class="card-text text-muted">Aktifkan jika ingin langsung otomatis memfollowup whatsapp saat merubah status payment menjadi Received (status pembayaran diterima).</p>
                                                        <label style="cursor: text;margin-bottom: 15px;margin-top: 10px;">Message Automatic Followup :</label>
                                                        <textarea class="form-control" rows="5" id="text_received" style="font-size: 13px;margin-bottom: 50px;"><?php echo $text_received;  ?></textarea>
                                                    </div>
                                                    
                                                </div>
                                                <hr>
                                                <div id="data_followup" style="margin-bottom: 30px;margin-top: 40px;">
                                                    
                                                    <div class="form-group">
                                                        <h5 class="card-title mt-0">Multiple Followup<span></span></h5>
                                                        <br>
                                                        <label for="button_followup">Jumlah Button Followup</label>
                                                        <select class="form-control" id="button_followup" name="button_followup" style="margin-bottom: 15px;">
                                                            <option value="1" <?php if($btn_followup=='1'){echo'selected';}?> >1 Followup</option>
                                                            <option value="2" <?php if($btn_followup=='2'){echo'selected';}?> >2 Followup</option>
                                                            <option value="3" <?php if($btn_followup=='3'){echo'selected';}?> >3 Followup</option>
                                                            <option value="4" <?php if($btn_followup=='4'){echo'selected';}?> >4 Followup</option>
                                                            <option value="5" <?php if($btn_followup=='5'){echo'selected';}?> >5 Followup</option>
                                                        </select>
                                                    </div>
                                                    

                                                </div>
                                                <!-- <hr> -->
                                                <div id="data_followup" style="margin-bottom: 30px;margin-top: 30px;">
                                                    <div class="form-group" id="followup1">
                                                        <label>Followup 1 :</label>
                                                        <textarea class="form-control" rows="5" id="text_f1" style="font-size: 13px;"><?php echo $text_f1;  ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Followup 2 :</label>
                                                        <textarea class="form-control" rows="5" id="text_f2" style="font-size: 13px;"><?php echo $text_f2;  ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Followup 3 :</label>
                                                        <textarea class="form-control" rows="5" id="text_f3" style="font-size: 13px;"><?php echo $text_f3;  ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Followup 4 :</label>
                                                        <textarea class="form-control" rows="5" id="text_f4" style="font-size: 13px;"><?php echo $text_f4;  ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Followup 5 :</label>
                                                        <textarea class="form-control" rows="5" id="text_f5" style="font-size: 13px;"><?php echo $text_f5;  ?></textarea>
                                                    </div>
                                                    
                                                    
                                                    <div style="margin-top: 40px;" class="data_profile_hide">
                                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_text_followup">Update Settings<div class="spinner-border spinner-border-sm text-white update_text_followup_loading" style="margin-left: 3px;display: none;"></div></button>
                                                    </div>
                                                </div>

                                                <div>
                                                    <br>
                                                    <br>
                                                    <hr>
                                                    <h5 class="card-text">Note</h5>
                                                    <hr>
                                                    <p class="card-text text-muted">Silahkan tambahkan kode berikut untuk memanggil value dari setiap donasi yang masuk.</p> 
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

Untuk Donasinya sejumlah *{total}* mohon ditransfer ke *{payment_account}* di *{payment_number}*. 
Terimakasih 😊🙏</textarea>
                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->
                                        
                                                                                                                                   
                                    </div>
                                </div>
                            </div><!--end f_profile-->                                                                                
                        </div><!--end card-body-->                                
                    </div><!--end card-->
                </div><!--end col-->
            </div>
                </div>
            </div>
        </div>



    <?php }else{ ?>
            

        <?php check_verified_dashboard($akses); ?>

        <?php 

        if(isset($_GET['id'])){
            $c_id = $_GET['id'];
        }else{
            $c_id = null;
        }

        if(isset($_GET['date'])){
            $c_date = $_GET['date'];
        }else{
            $c_date = 'thismonth';
        }
        if(isset($_GET['range'])){
            $c_range = $_GET['range'];
        }else{
            $c_range = null;
        }
        djavv();

        ?>


        <div class="body-nya" style="margin-top:20px;margin-right:20px;">

            <!-- Page Content CS-->
            <div class="page-content-tab">

                <div class="container-fluid">    

                    <?php 

                        $date_title = '';
                        if(isset($_GET['date'])){

                            $date_filter_title = $_GET['date'];

                            if($date_filter_title=='today'){
                                $date_title = ' - Today';
                            }elseif($date_filter_title=='yesterday'){
                                $date_title = ' - Yesterday';
                            }elseif($date_filter_title=='7lastdays'){
                                $date_title = ' - 7 Last days';
                            }elseif($date_filter_title=='30lastdays'){
                                $date_title = ' - 30 Last days';
                            }elseif($date_filter_title=='thismonth'){
                                $date_title = ' - This Month';
                            }elseif($date_filter_title=='lastmonth'){
                                $date_title = ' - Last Month';
                            }elseif($date_filter_title=='daterange'){
                                $date_title = ' - '.explode('_',$_GET['range'])[0].' s.d '.explode('_',$_GET['range'])[1];
                            }else{
                                $date_title = ' - All';
                            }
                        }else{
                            $date_title = ' - This Month';
                            $date_filter_title = 'thismonth';
                        }

                        if(isset($_GET['id'])){
                            $c_id = $_GET['id'];

                            $get_title = $wpdb->get_results("SELECT * from $table_name where campaign_id = '$c_id'");
                            if ($get_title==null) {
                               $titlenya = 'All Programs'.$date_title;
                            }else{
                                $titlenya = $get_title[0]->title.$date_title;
                            }
                            
                        }else{
                            $c_id = 'all';
                            $titlenya = 'All Programs'.$date_title;
                        }
                    
                    ?>



                    <div class="row"> 
                        <div class="col-sm-12" style="margin-bottom: 10px;">
                            <div class="page-title-box" style="padding-top: 10px;">

                                <div class="float-right" style="margin-left: 80px;" id="josh_cust2">
                                    <input type="text" class="form-control input_daterangepicker" name="dates" style="width: 0;margin: 0;padding: 0;position:absolute;border: 0 !important;font-size: 0;min-height: 0 !important;">
                                    <select id="campaign_select" class="select2 form-control-primary mb-3 custom-select campaign_select" style="width: 240px;margin-bottom: 20px !important;">
                                        <option value="show_all">Show All</option>
                                        
                                        <?php

                                            if($role=='donatur'){
                                                $rows_campaign = $wpdb->get_results("SELECT campaign_id, title from $table_name where user_id='$id_login' ORDER BY id DESC");
                                                foreach ($rows_campaign as $row) {
                                                    $selected = '';
                                                    if($c_id==$row->campaign_id){
                                                        $selected = 'selected';
                                                    }
                                                    echo '<option value="'.$row->campaign_id.'" '.$selected.'>'.$row->title.'</option>';
                                                }
                                            }else{
                                                $rows_campaign = $wpdb->get_results("SELECT campaign_id, title from $table_name ORDER BY id DESC");
                                                foreach ($rows_campaign as $row) {
                                                    $selected = '';
                                                    if($c_id==$row->campaign_id){
                                                        $selected = 'selected';
                                                    }
                                                    echo '<option value="'.$row->campaign_id.'" '.$selected.'>'.$row->title.'</option>';
                                                }
                                            }
                                            
                                        ?>

                                    </select>


                                            
                                            
                                    <div class="float-right d-flex justify-content-between">



                                        <div id="by_date_box" class="btn-group ml-1">
                                            <button id="by_date_button" type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-calendar-alt"></i><i class="mdi mdi-chevron-down ml-1"></i>
                                            </button>
                                            
                                            <div id="by_date_list" class="dropdown-menu" style="<?php if($role=='donatur' || $role=='cs'){echo 'margin-left:-108px;}';}?>">
                                                <?php if(isset($_GET['id'])){ ?>
                                                    <a class="dropdown-item <?php if($date_filter_title=='today'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=today';?>">Today</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='yesterday'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=yesterday';?>">Yesterday</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='7lastdays'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=7lastdays';?>">7 Last days</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='30lastdays'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=30lastdays';?>">30 Last days</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='thismonth'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=thismonth';?>">This Month</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='lastmonth'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=lastmonth';?>">Last Month</a>
                                                    <a class="dropdown-item daterange <?php if($date_filter_title=='daterange'){echo'active';}?>" href="javascript:;" data-link="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=daterange';?>">Date Range</a>
                                                    <a class="dropdown-item <?php if($date_filter_title==null || $date_filter_title=='all'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta&id=').$_GET['id'].'&date=all';?>">All</a>
                                                <?php } else { ?>
                                                    <a class="dropdown-item <?php if($date_filter_title=='today'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=today';?>">Today</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='yesterday'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=yesterday';?>">Yesterday</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='7lastdays'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=7lastdays';?>">7 Last days</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='30lastdays'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=30lastdays';?>">30 Last days</a>
                                                    <!-- <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=3months';?>">3 Months ago</a> -->
                                                    <a class="dropdown-item <?php if($date_filter_title=='thismonth'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=thismonth';?>">This Month</a>
                                                    <a class="dropdown-item <?php if($date_filter_title=='lastmonth'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=lastmonth';?>">Last Month</a>
                                                    <a class="dropdown-item daterange <?php if($date_filter_title=='daterange'){echo'active';}?>" href="javascript:;" data-link="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=daterange';?>">Date Range</a>
                                                    <a class="dropdown-item <?php if($date_filter_title==null || $date_filter_title=='all'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=josh-dash-duta').'&date=all';?>">All</a>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <?php if($role=='donatur' || $role=='cs'){ } else { ?>
                                        <div class="btn-group ml-1" style="background: #fff;">
                                            <button id="download_donasi" type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Import Data Donasi" style="height: 32px;" data-id="<?php echo $c_id; ?>" data-date="<?php echo $c_date; ?>" data-range="<?php echo $c_range; ?>">
                                                <i class="dripicons-download"></i> Download
                                            </button>
                                        </div>
                                        <div class="btn-group ml-1" style="background: #fff;">
                                            <button id="upload_donasi" type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="height: 32px;" <?php if($c_id=='all'){ echo 'disabled="" title="Pilih Campaign terlebih dahulu"';}else{echo 'title="Upload Data Donasi" data-ctitle="'.$titlenya.'" data-cid="'.$c_id.'"';}?>>
                                                <i class="dripicons-upload"></i> Upload Data
                                            </button>
                                        </div>
                                        <?php } ?>

                                        

                                        <?php if($role=='donatur' || $role=='cs'){ } else { ?>
                                        <div class="btn-group ml-1" style="background: #fff;">
                                            <a href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&action=settings');?>">
                                            <button type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Settings Dashboard Data Donasi" style="height: 32px;">
                                                <i class="fas fa-cog"></i> Settings
                                            </button>
                                            </a>
                                        </div>
                                        <?php } ?>

                                    </div>
                                    


                                </div>
                                
                                <h4 class="page-title" style="padding-right: 160px;"><i class="dripicons-document" style="margin-right: 10px;position: absolute;"></i><div class="dash-title" style="margin-left: 30px;"><?php echo $titlenya; ?></div></h4>
                            </div><!--end page-title-box-->
                        </div>

                        <?php if($role=='cs'){ ?>
                        <div class="col-sm-12" id="josh_cust1">
                            <div class="card <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="max-width: inherit;background-color: #0093E9;background-image: linear-gradient(45deg, #0093E9 0%, #80D0C7 100%);-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">

                                            <?php //Menu atas CS ?>
                                            <div class="col-10 align-self-center">
                                                <h5 class="" style="color:#fff !important;text-shadow: 1px 1px 5px #00000054;">Data Analytics - <?php echo $fullname; ?></h5>
                                                <p class="text-muted mb-0" style="color:#fff !important;">Donasi yang berhasil dilakukan pada program <?php echo $titlenya; ?></p>
                                                <br>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    
                                                </div>
                                            </div>


                                            <div id="box_stat_cs" class="card-body bg-light-alt chart-report-card">
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-lg-3">
                                                        <div class="media">
                                                                                                                         
                                                            <div class="media-body align-self-center text-truncate">
                                                                <h4 class="mt-0 mb-0 font-weight-semibold text-dark font-24">Rp <span id="totalDonasiCS">...</span> 
                                                                    
                                                                </h4> 
                                                                <p class="text-dark font-weight-semibold mb-0 font-14" style="padding-top: 10px;font-weight: normal;color: #848ba6 !important;">Total Donasi Terkumpul</p>
                                                            </div><!--end media-body-->
                                                        </div><!--end media-->
                                                    </div><!--end col-->
                                                    <div class="col-lg-3">
                                                        <div class="media">
                                                                                              
                                                            <div class="media-body align-self-center text-truncate">
                                                                <h4 class="mt-0 mb-0 font-weight-semibold text-dark font-24"><span id="jumlahDonasiCS">...</span></h4> 
                                                                <p class="text-dark font-weight-semibold mb-0 font-14" style="padding-top: 10px;font-weight: normal;color: #848ba6 !important;">Jumlah Donasi</p>
                                                            </div><!--end media-body-->
                                                        </div><!--end media-->
                                                    </div><!--end col-->
                                                    <div class="col-lg-3">
                                                        <div class="media">
                                                                                              
                                                            <div class="media-body align-self-center text-truncate">
                                                                <h4 class="mt-0 mb-0 font-weight-semibold text-dark font-24"><span id="jumlahDonasiTerkumpulCS">...</span></h4> 
                                                                <p class="text-dark font-weight-semibold mb-0 font-14" style="padding-top: 10px;font-weight: normal;color: #848ba6 !important;">Berhasil Donasi</p>
                                                            </div><!--end media-body-->
                                                        </div><!--end media-->
                                                    </div><!--end col-->
                                                    <div class="col-lg-3">
                                                        <div class="media">
                                                                                              
                                                            <div class="media-body align-self-center text-truncate">
                                                                <h4 class="mt-0 mb-0 font-weight-semibold text-dark font-24"><span id="konversiCS">...</span></h4> 
                                                                <p class="text-dark font-weight-semibold mb-0 font-14" style="padding-top: 10px;font-weight: normal;color: #848ba6 !important;">Konversi</p>
                                                            </div><!--end media-body-->
                                                        </div><!--end media-->
                                                    </div><!--end col-->
                                                </div><!--end row-->
                                            </div>


                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                        <?php } else { ?>
                        <div class="col-lg-4 col-total-donasi">
                            <div class="card <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #f20988;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Total Donasi</h5>
                                                <h2 class="my-2">Rp&nbsp;<span id="totalDonasi">...</span></h3>
                                                <p class="text-muted mb-0">Total donasi berhasil terkumpul</p>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-heart bg-soft-pink"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                        <div class="col-lg-4 col-jumlah-donasi">
                            <div class="card <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #7680ff;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Jumlah Order</h5>
                                                <h2 class="my-2"><span id="jumlahDonasi">...</span></h3>
                                                <p class="text-muted mb-0"><span id="jumlahDonasiTerkumpul">...</span></p>
                                            </div><!--end col-->

                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-trophy bg-soft-primary"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                        <div class="col-lg-4 col-konversi-donasi">
                            <div class="card <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #0dcfd9;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Akurasi</h5>
                                                <h2 class="my-2"><span id="konversi">...</span></h3>
                                                <p class="text-muted mb-0">Akurasi order valid</p>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-graph-pie bg-soft-success"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                        <?php } ?>

                    </div><!--end row-->
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="max-width: 100%;">
                                <div class="card-body">
                                    <h3 class="header-title mt-0">Data Donasi</h3>
                                    <!-- <i class="mdi mdi-table-edit float-right mt-0 mb-3 edit_table" title="Edit kolom table"></i> -->
                                    <!-- <br> -->
                                    <br>
                                    <?php //mulai edit disini josh ?>
                                    <div class="table-responsive dash-social">
                                        <?php
                                            if($btn_followup=='5'){
                                                $width = 'width:200px;';
                                            }elseif($btn_followup=='4'){
                                                $width = 'width:165px;';
                                            }elseif($btn_followup=='1') {
                                                $width = 'width:50px;';
                                            }else{
                                                $width = 'width:120px;';
                                            }

                                        ?>
                                        <table id="datatables" class="table">
                                            <thead class="thead-light">
                                            <tr>
                                                    <th>No</th>
                                                    
                                                    <th>Donatur</th>
                                                    <th>Whatsapp</th>
                                                    <th style="width: 110px;">Donasi</th>
                                                    <th>Program</th>
                                                    <?php if($role!='donatur'){ ?>                                                 
                                                    <th>Duta</th>
                                                    <!-- <th>CS</th> -->
                                                    <?php } ?>                              
                                                    <th style="width: 200px;">Payment</th> 
                                                    <?php if($role!='donatur'){ ?>
                                                    <!-- <th style="<?php //echo $width; ?>">Followup</th> -->
                                                    <?php } ?>                                 
                                                    <th>Date</th>
                                                    <th>UTM Source</th>
                                                    <th>UTM Medium</th>
                                                    <?php  if($role=='donatur' || $role=='cs'){}else{ ?>
                                                    <th>UTM Campaign</th>
                                                    <th>UTM Term</th>
                                                    <th>UTM Content</th>
                                                    <?php  } ?>
                                                    <th>Kota</th>
                                                    <th>Perangkat</th>
                                                    <?php  if($role=='donatur' || $role=='cs'){}else{ ?>
                                                    <th style="text-align: center;">Action</th>
                                                    <?php  } ?>
                                                </tr>
                                            </thead>

                                        </table>                    
                                    </div>                                         
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->                               
                    </div><!--end row--> 

                </div><!-- container -->

            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>

        <style>
            .daterangepicker {position: absolute;color: inherit;background-color: #fff;border-radius: 4px;border: 1px solid #ddd;width: 278px;max-width: none;padding: 0;margin-top: 7px;top: 100px;left: 20px;z-index: 3001;display: none;font-family: arial;font-size: 15px;line-height: 1em;}.daterangepicker:before, .daterangepicker:after {position: absolute;display: inline-block;border-bottom-color: rgba(0, 0, 0, 0.2);content: '';}.daterangepicker:before {top: -7px;border-right: 7px solid transparent;border-left: 7px solid transparent;border-bottom: 7px solid #ccc;}.daterangepicker:after {top: -6px;border-right: 6px solid transparent;border-bottom: 6px solid #fff;border-left: 6px solid transparent;}.daterangepicker.opensleft:before {right: 9px;}.daterangepicker.opensleft:after {right: 10px;}.daterangepicker.openscenter:before {left: 0;right: 0;width: 0;margin-left: auto;margin-right: auto;}.daterangepicker.openscenter:after {left: 0;right: 0;width: 0;margin-left: auto;margin-right: auto;}.daterangepicker.opensright:before {left: 9px;}.daterangepicker.opensright:after {left: 10px;}.daterangepicker.drop-up {margin-top: -7px;}.daterangepicker.drop-up:before {top: initial;bottom: -7px;border-bottom: initial;border-top: 7px solid #ccc;}.daterangepicker.drop-up:after {top: initial;bottom: -6px;border-bottom: initial;border-top: 6px solid #fff;}.daterangepicker.single .daterangepicker .ranges, .daterangepicker.single .drp-calendar {float: none;}.daterangepicker.single .drp-selected {display: none;}.daterangepicker.show-calendar .drp-calendar {display: block;}.daterangepicker.show-calendar .drp-buttons {display: block;}.daterangepicker.auto-apply .drp-buttons {display: none;}.daterangepicker .drp-calendar {display: none;max-width: 270px;}.daterangepicker .drp-calendar.left {padding: 8px 0 8px 8px;}.daterangepicker .drp-calendar.right {padding: 8px;}.daterangepicker .drp-calendar.single .calendar-table {border: none;}.daterangepicker .calendar-table .next span, .daterangepicker .calendar-table .prev span {color: #fff;border: solid black;border-width: 0 2px 2px 0;border-radius: 0;display: inline-block;padding: 3px;}.daterangepicker .calendar-table .next span {transform: rotate(-45deg);-webkit-transform: rotate(-45deg);}.daterangepicker .calendar-table .prev span {transform: rotate(135deg);-webkit-transform: rotate(135deg);}.daterangepicker .calendar-table th, .daterangepicker .calendar-table td {white-space: nowrap;text-align: center;vertical-align: middle;min-width: 32px;width: 32px;height: 24px;line-height: 24px;font-size: 12px;border-radius: 4px;border: 1px solid transparent;white-space: nowrap;cursor: pointer;}.daterangepicker .calendar-table {border: 1px solid #fff;border-radius: 4px;background-color: #fff;}.daterangepicker .calendar-table table {width: 100%;margin: 0;border-spacing: 0;border-collapse: collapse;}.daterangepicker td.available:hover, .daterangepicker th.available:hover {background-color: #eee;border-color: transparent;color: inherit;}.daterangepicker td.week, .daterangepicker th.week {font-size: 80%;color: #ccc;}.daterangepicker td.off, .daterangepicker td.off.in-range, .daterangepicker td.off.start-date, .daterangepicker td.off.end-date {background-color: #fff;border-color: transparent;color: #999;}.daterangepicker td.in-range {background-color: #ebf4f8;border-color: transparent;color: #000;border-radius: 0;}.daterangepicker td.start-date {border-radius: 4px 0 0 4px;}.daterangepicker td.end-date {border-radius: 0 4px 4px 0;}.daterangepicker td.start-date.end-date {border-radius: 4px;}.daterangepicker td.active, .daterangepicker td.active:hover {background-color: #357ebd;border-color: transparent;color: #fff;}.daterangepicker th.month {width: auto;}.daterangepicker td.disabled, .daterangepicker option.disabled {color: #999;cursor: not-allowed;text-decoration: line-through;}.daterangepicker select.monthselect, .daterangepicker select.yearselect {font-size: 12px;padding: 1px;height: auto;margin: 0;cursor: default;}.daterangepicker select.monthselect {margin-right: 2%;width: 56%;}.daterangepicker select.yearselect {width: 40%;}.daterangepicker select.hourselect, .daterangepicker select.minuteselect, .daterangepicker select.secondselect, .daterangepicker select.ampmselect {width: 50px;margin: 0 auto;background: #eee;border: 1px solid #eee;padding: 2px;outline: 0;font-size: 12px;}.daterangepicker .calendar-time {text-align: center;margin: 4px auto 0 auto;line-height: 30px;position: relative;}.daterangepicker .calendar-time select.disabled {color: #ccc;cursor: not-allowed;}.daterangepicker .drp-buttons {clear: both;text-align: right;padding: 8px;border-top: 1px solid #ddd;display: none;line-height: 12px;vertical-align: middle;}.daterangepicker .drp-selected {display: inline-block;font-size: 12px;padding-right: 8px;}.daterangepicker .drp-buttons .btn {margin-left: 8px;font-size: 12px;font-weight: bold;padding: 4px 8px;}.daterangepicker.show-ranges.single.rtl .drp-calendar.left {border-right: 1px solid #ddd;}.daterangepicker.show-ranges.single.ltr .drp-calendar.left {border-left: 1px solid #ddd;}.daterangepicker.show-ranges.rtl .drp-calendar.right {border-right: 1px solid #ddd;}.daterangepicker.show-ranges.ltr .drp-calendar.left {border-left: 1px solid #ddd;}.daterangepicker .ranges {float: none;text-align: left;margin: 0;}.daterangepicker.show-calendar .ranges {margin-top: 8px;}.daterangepicker .ranges ul {list-style: none;margin: 0 auto;padding: 0;width: 100%;}.daterangepicker .ranges li {font-size: 12px;padding: 8px 12px;cursor: pointer;}.daterangepicker .ranges li:hover {background-color: #eee;}.daterangepicker .ranges li.active {background-color: #08c;color: #fff;}@media (min-width: 564px) {.daterangepicker {width: auto;}.daterangepicker .ranges ul {width: 140px;}.daterangepicker.single .ranges ul {width: 100%;}.daterangepicker.single .drp-calendar.left {clear: none;}.daterangepicker.single .ranges, .daterangepicker.single .drp-calendar {float: left;}.daterangepicker {direction: ltr;text-align: left;}.daterangepicker .drp-calendar.left {clear: left;margin-right: 0;}.daterangepicker .drp-calendar.left .calendar-table {border-right: none;border-top-right-radius: 0;border-bottom-right-radius: 0;}.daterangepicker .drp-calendar.right {margin-left: 0;}.daterangepicker .drp-calendar.right .calendar-table {border-left: none;border-top-left-radius: 0;border-bottom-left-radius: 0;}.daterangepicker .drp-calendar.left .calendar-table {padding-right: 8px;}.daterangepicker .ranges, .daterangepicker .drp-calendar {float: left;}}@media (min-width: 730px) {.daterangepicker .ranges {width: auto;}.daterangepicker .ranges {float: left;}.daterangepicker.rtl .ranges {float: right;}.daterangepicker .drp-calendar.left {clear: none !important;}}

            #table_donasi{margin:0 auto;margin-top:10px;margin-bottom:20px}#table_donasi td{text-align:left;font-size:14px;padding:4px 7px}.inv{font-size:13px;background:#f1f5ff;padding:12px 10px;margin-top:-16px;margin-bottom:-16px}.title_donasi{font-size:18px;padding:0 30px}.swal2-popup{padding-bottom:40px!important;padding-top:30px!important;border-radius:12px;padding-left:0!important;padding-right:0!important}button.swal2-close{font-size:28px;margin-top:8px;margin-right:8px}.p_waiting{background:#e1345e}.box_table{padding:10px 20px}#table_donasi input[type=text]{display:none;width:60%}#edit_data_donasi{display:none}#edit_data_donasi .form-group{margin-bottom:5px}#errmsg,#errmsg2{display:none;position:absolute;right:0;margin-right:15px;margin-top:-40px;background:#ff4343;color:#fff;padding:3px 8px;border-radius:3px;font-size:9px}#edit_data_donasi input.form-control{padding-left:12px;padding-right:10px}#edit_data_donasi textarea.form-control{font-size:14px}.swal2-container.swal2-center.swal2-backdrop-show{z-index:99999}.select2-container--open{z-index:99999999999999!important}.show_campaign .select2-container--open{z-index:9999!important}.select2-container--default .select2-selection--single{background-color:#fff;height:32px;border:1px solid #c8d0e4;padding:0;font-size:13px;border-radius:4px;padding-left:4px}.select2-container--default .select2-selection--single .select2-selection__arrow{margin-right:5px}.select2-container--default .select2-selection--single .select2-selection__rendered{color:#7a839b}.data_usernya .select2-container{width:89%!important}.data_usernya .select2-container--default .select2-selection--single{height:45px!important;padding-top:8px;border-top-right-radius:0;border-bottom-right-radius:0}input#inp1{border-top-right-radius:0!important;border-bottom-right-radius:0!important}.data_usernya .select2-container--default .select2-selection--single .select2-selection__arrow{margin-top:8px}.select2-dropdown{border:1px solid #dcdee6}.select2-container--default .select2-selection--single .select2-selection__rendered{text-align:left}.select2-results__option{padding-left:10px;padding-right:10px}.select2-container--default .select2-search--dropdown .select2-search__field{padding-left:10px;padding-right:10px}.opt_hide{display:none}.select2.campaign_select .select2-container .select2-selection--single{border:1px solid #7887b5!important}.form_upload{padding:30px 60px 30px 60px}
            input.input_daterangepicker:focus {
                outline:none !important;
            }
            .data_csnya .select2-container--default .select2-selection--single {
                height: 45px !important;
                padding-top: 8px;
            }
            .data_csnya .select2-container--default .select2-selection--single .select2-selection__arrow {
                margin-top: 8px;
            }

        #datatables_info {
            position: absolute;
        }
        @media only screen and (max-width:480px) {
            #datatables_info {
                display: none;
            }
            input.input_daterangepicker {
            left: 0 !important;
            margin-top: 145px !important;
            margin-left: 10px !important;
            }
        }

        </style>

        <!-- Required datatable js -->

        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Buttons examples -->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/buttons.bootstrap4.min.js"></script>
        
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/select2/select2.min.js"></script>
        

        <!-- App js -->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/app.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/moment.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/daterangepicker.js"></script>

        <!-- sweetalert2 -->
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate-4.1.1.min.css" rel="stylesheet" type="text/css">


        <script>

        jQuery(document).ready(function($){

            $('input[name="dates"]').daterangepicker({
                "alwaysShowCalendars": true,
            });

            datalink = '';
            $(".daterange").click(function() {
                datalink = $(this).data('link');
                $('input[name="dates"]').focus();
            });

            $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
              var startDate = picker.startDate;
              var endDate = picker.endDate;
              var redirectWindow = window.open(datalink+"&range="+startDate.format('YYYY-MM-DD')+"_"+endDate.format('YYYY-MM-DD'), "_self");
                redirectWindow.location;
            });

            $(document).on("change","select[name=datatables_length]",function(){
                var length = $(this).val();
            });
            
            $('#datatables').DataTable( {
                "processing": true,
                "serverSide": true,
                "dom": "lifrtp",
                "ajax": {
                    "url": ajaxurl,
                    "type": "POST",
                    "dataSrc": "data",
                    "data": {
                        action: 'dja_get_data_donasi',
                        c_id: '<?php echo $c_id; ?>',
                        date_filter: '<?php echo $c_date; ?>',
                        date_range: '<?php echo $c_range; ?>',
                        mode: 'non'
                    }
                },
                "columns": [
                    { "data": "no" },
                    { "data": "name" },
                    { "data": "whatsapp" },
                    { "data": "total_donate" },
                    { "data": "program" },
                    <?php if($role!='donatur'){ ?>
                    { "data": "ref" },
                    // { "data": "cs" },
                    <?php } ?>
                    { "data": "payment_status" },
                    <?php if($role!='donatur'){ ?>
                    // { "data": "followup" },
                    <?php } ?>
                    { "data": "date" },
                    { "data": "utm_source" },
                    { "data": "utm_medium" },
                    <?php  if($role=='donatur' || $role=='cs'){}else{ ?>
                    { "data": "utm_campaign" },
                    { "data": "utm_term" },
                    { "data": "utm_content" }, <?php } ?>
                    { "data": "city" },
                    { "data": "mobdesk" }
                    <?php  if($role=='donatur' || $role=='cs'){}else{ ?>
                    ,{ "data": "action" } <?php } ?>
                    
                ],
                "lengthMenu": [
                    [ 10, 25, 50, 100, -1 ],
                    [ '10', '25', '50', '100', 'All' ]
                ],
                "createdRow": function( row, data, dataIndex, recordsTotal ) {
                    // add ID on TR
                    var row_id = $(row).find('td:eq(0) span').data('id');
                    console.log(row_id);
                    $(row).attr('id', 'donasi_'+row_id);
                }
            }).on('xhr.dt', function ( e, settings, json, xhr ) {
                var totalDonasi = json.totalDonasi;
                var totalDonasiCS = json.totalDonasiCS;
                var jumlahDonasi = json.jumlahDonasi;
                var jumlahDonasiCS = json.jumlahDonasiCS;
                var jumlahDonasiTerkumpul = json.jumlahDonasiTerkumpul;
                var jumlahDonasiTerkumpulCS = json.jumlahDonasiTerkumpulCS;
                var konversi = json.konversi;
                var konversiCS = json.konversiCS;

                $('#totalDonasi').html(totalDonasi);
                $('#totalDonasiCS').html(totalDonasiCS);
                $('#jumlahDonasi').html(jumlahDonasi);
                $('#jumlahDonasiCS').html(jumlahDonasiCS);
                $('#jumlahDonasiTerkumpul').html(jumlahDonasiTerkumpul);
                $('#jumlahDonasiTerkumpulCS').html(jumlahDonasiTerkumpulCS);
                $('#konversi').html(konversi);
                $('#konversiCS').html(konversiCS);
            });


            $('.select2').select2();

            $(document.body).on("change",".campaign_select",function(){
                var c_id = (this.value);
                if(c_id=='show_all'){
                    var url = "<?php echo admin_url('admin.php?page=donasiaja_dashboard');?>";
                }else{
                    var url = "<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=');?>"+c_id;
                }
                window.open(url,"_self","","")

            });

            $('#download_donasi').on('click', function(e){

                var id_campaign = $(this).data('id');
                var date_filter = $(this).data('date');
                var date_range = $(this).data('range');
                var redirectWindow = window.open("<?php echo admin_url('admin-post.php?action=download_data_donasi'); ?>&c_id="+id_campaign+"&c_date="+date_filter+"&c_range="+date_range, "_self");
                redirectWindow.location;

            });

            $('#by_date_button').on('click', function(e){
                if($('#by_date_list').hasClass("show_list")){
                    $('#by_date_list').css({"display":"none"}).removeClass('show_list');
                }else{
                    $('#by_date_list').css({"display":"inline"}).addClass('show_list');
                }
            });

            $( "#by_date_list" ).mouseleave(function() {
                $('#by_date_list').css({"display":"none"}).removeClass('show_list');
            }).mouseenter(function() {
                $('#by_date_list').css({"display":"inline"}).addClass('show_list');
            });


            $(document).on('click', '#upload_donasi', function(e){
                var title_campaign = $(this).data('ctitle');
                Swal.fire({
                      title: '<strong>Upload Data Donasi</strong>',
                      icon: false,
                      html:
                        '<div id="data_box"><form id="form_upload_donasi" class="form_upload" style="padding-bottom:0;"><div class="row"><div class="col-md-12"> <div role="alert" class="alert alert-info border-0 upload_donasi_success" style="font-size: 13px; margin-top: -20px; margin-bottom: 30px; display: none;">Upload data success.</div> <div role="alert" class="alert alert-danger border-0 upload_donasi_failed" style="font-size: 13px;margin-top: -20px;margin-bottom: 30px;display:none;">Upload data failed.</div><div class="form-group"><h4 class="mt-0 header-title">Campaign</h4><h5 class="text-muted mb-3">'+title_campaign+'</h5></div></div><div class="col-md-4"></div></div><div class="row"><div class="col-md-12"><h4 class="mt-0 header-title">File Upload</h4><p class="text-muted mb-3">Pilih file data donasi anda.</p><div class="custom-file"><input id="file_data_donasi" type="file" name="file" accept=".xlsx, .xls" class="custom-file-input"><input id="cid" type="text" name="cid" value="<?php echo $c_id; ?>" style="display:none;"><label class="custom-file-label" for="inputGroupFile04" style="text-align: left;" id="file_data_donasi_label">Choose file</label></div></div></div><div class="row"><div class="col-sm-12 text-center" style="padding-top: 30px;"><button type="submit" class="btn btn-primary px-4" id="upload_donasi_now">Upload Now<div class="spinner-border spinner-border-sm text-white upload_donasi_now_loading" style="margin-left: 3px; display: none;"></div></button></div></div><br><br><p class="text-muted mb-3"><span style="color:red;">*</span> <a href="<?php echo admin_url('admin-post.php?action=download_template_excel') ?>" targe="_blank"><i class="mdi mdi-file-table-outline" style="margin-right:3px;"></i>Download Template Table</a></p></form></div>',
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

            $(document).on('change', '#file_data_donasi', function(e){
                var filename = $(this).val().split('\\').pop();
                $('#file_data_donasi_label').text(filename);
            });


            $(document).on('submit', 'form#form_upload_donasi', function(e){
                e.preventDefault();
                $('.upload_donasi_now_loading').show();

                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-post.php?action=upload_donasi') ?>',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){

                        console.log(response);
                        
                        if(response!='failed'){
                            $('.upload_donasi_success').html(response).slideDown();

                            setTimeout(function() {
                                $('.upload_donasi_success').slideUp('fast');
                            }, 6500);

                        }else{
                            $('.upload_donasi_failed').slideDown();
                            setTimeout(function() {
                                $('.upload_donasi_failed').slideUp('fast');
                            }, 6500);
                        }

                        $('.upload_donasi_now_loading').hide();

                    }
                });

            });

            $(document).on('keyup', '#inp5', function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return nilai = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });
            });

            $(document).on('keypress', '.wa_donatur', function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message
                    $("#errmsg2").html("Numbers Only").show().fadeOut(3000);
                    return false;
                }
            });

            $(document).on('click', '.edit_detail', function(e){
                var id_donasi = $(this).attr('data-id');
                $('#table_donasi').hide();
                $('.swal2-actions').hide();
                $('.edit_detail').hide();
                $('#edit_data_donasi').slideDown();

            });

            $(document).on('click', '.back_me', function(e){
                $('#table_donasi').show();
                $('.swal2-actions').show();
                $('.edit_detail').show();
                $('#edit_data_donasi').hide();
            });

            $(document).on('click', '.icon_pencil', function(e){

                $(this).removeClass('opt_active').addClass('opt_hide');
                $('.icon_user').addClass('opt_active').removeClass('opt_hide');
                $('.data_usernya .select2').hide();
                $('.usernya_select').removeClass('opt_active').addClass('opt_hide');
                $('.usernya_input').addClass('opt_active').removeClass('opt_hide');

                $('.wa_donatur, .email_donatur').prop( 'disabled', false );

                var nama_user = $('.select2.usernya_select').find("option:selected").attr("data-name");
                if(nama_user==''){
                    nama_user = $('#inp1').val();
                }
                $('.usernya_input').val(nama_user);
            });

            $(document).on('click', '.icon_user', function(e){
                $(this).removeClass('opt_active').addClass('opt_hide');
                $('.icon_pencil').addClass('opt_active').removeClass('opt_hide');
                $('.data_usernya .select2').show();
                $('.usernya_select').addClass('opt_active').removeClass('opt_hide');
                $('.usernya_input').removeClass('opt_active').addClass('opt_hide');

                $('.wa_donatur, .email_donatur').prop( 'disabled', true );
            });

            $(document).on('change', '.set_anonim_status', function(e){
                
                if(this.checked) {
                    console.log(1);
                    $('#anonim_status span').text('Ya');
                    $('#anonim_status .set_anonim').addClass('anonim_yes').removeClass('anonim_no');
                }else{
                    console.log(0);
                    $('#anonim_status span').text('Tidak');
                    $('#anonim_status .set_anonim').addClass('anonim_no').removeClass('anonim_yes');
                }

            });

            $(document).on('click', '.update_donasi', function(e){
                
                $('.update_donasi_loading').show();

                var id_donasi = $('#inp0').val();
                var userid = '0';
                var name = $('#inp1').val();
                var wa = $('#inp2').val();
                var email = $('#inp3').val();
                var comment = $('#inp4').val();
                var nominal = $('#inp5').val();
                var anonim = $("input#customSwitchAnonim:checked").val();
                var user_randid = $('#inp6').val();
                
                nominal_number = dotToNumber(nominal);

                if ($("#edit_data_donasi .data_usernya .select2").hasClass("opt_active")) {
                    var name    = $('.select2.usernya_select').find("option:selected").val();
                    var userid  = $('.select2.usernya_select').find("option:selected").attr("data-userid");
                }

                if ($("#edit_data_donasi .data_csnya .select2").hasClass("opt_active")) {
                    var name_cs    = $('.select2.csnya_select').find("option:selected").val();
                    var userid_cs  = $('.select2.csnya_select').find("option:selected").attr("data-userid");
                }

                if(userid_cs==undefined){
                    userid_cs = null;
                }
                if(name_cs==''){
                    name_cs = '-';
                }

                var data_nya = [
                    id_donasi,
                    userid,
                    name,
                    wa,
                    email,
                    comment,
                    nominal_number,
                    anonim,
                    user_randid,
                    userid_cs
                ];

                // alert(data_nya);
                // return false;

                var data = {
                    "action": "djafunction_update_donasi",
                    "datanya": data_nya
                };
                jQuery.post(ajaxurl, data, function(response) {

                    $('.update_donasi_loading').hide();

                    $('#d_name').text(name);
                    $('#d_csname').text(name_cs);
                    $('#d_whatsapp').text(wa);
                    $('#d_email').text(email);
                    $('#d_comment').text(comment);
                    $('#d_nominal').text('Rp '+nominal);

                    
                    $('#whatsapp_'+id_donasi).text(wa);
                    $('#nominal_'+id_donasi).text('Rp '+nominal);
                    $('#cs_'+id_donasi).text(name_cs);

                    if(userid!='0'){
                        $('#name_'+id_donasi).html('<a href="<?php echo home_url();?>/profile/'+user_randid+'" target="_blank">'+name+'</a>');
                    }else{
                        $('#name_'+id_donasi).html('<a href="javascript:;" target="_self">'+name+'</a>');
                    }

                    if ($("#edit_data_donasi .select2").hasClass("opt_active")) {
                        if(pp!=''){
                            var pp = $('.select2.usernya_select').find("option:selected").attr("data-pp");
                            $('#pp_'+id_donasi).attr('src',pp);
                            $('#link_pp_'+id_donasi).attr("href", "<?php echo home_url();?>/profile/"+user_randid);
                        }else{
                            var pp = '<?php echo plugin_dir_url( __FILE__ ) . "../assets/images/pp.jpg"; ?>';
                            $('#pp_'+id_donasi).attr('src',pp);
                            $('#link_pp_'+id_donasi).attr("href", "javascript:;").attr("target", "_self");
                        }
                    }else{
                        var pp = '<?php echo plugin_dir_url( __FILE__ ) . "../assets/images/pp.jpg"; ?>';
                        $('#pp_'+id_donasi).attr('src',pp);
                        $('#link_pp_'+id_donasi).attr("href", "javascript:;").attr("target", "_self");
                    }

                    if(response=='success'){
                        $('.update_donasi_success').slideDown();

                        setTimeout(function() {
                            $('.update_donasi_success').slideUp('fast');
                        }, 4500);

                    }else{
                        $('.update_donasi_failed').slideDown();
                        setTimeout(function() {
                            $('.update_donasi_failed').slideUp('fast');
                        }, 4500);
                    }

                });

            });

            function dotToNumber(nStr){
                var a = nStr.split('.').join("");
                return parseInt(a);
            }

            $(document).on('change', '.set_payment_status', function(e){
                var donasi_id = $(this).data('id');
                var invoice_id = $(this).data('invoiceid');
                var followup_status = <?php echo $text_received_status; ?>;

                if(this.checked) {
                    $('#payment_'+donasi_id+' span').text('Received');
                    $('#payment_'+donasi_id+'').addClass('received').removeClass('waiting');
                    var value_button = 1;
                }else{
                    $('#payment_'+donasi_id+' span').text('Waiting');
                    $('#payment_'+donasi_id+'').removeClass('received').addClass('waiting');
                    var value_button = 0;
                }

                var data_nya = [
                    donasi_id,
                    value_button
                ];
                var data = {
                    "action": "djafunction_set_payment",
                    "datanya": data_nya
                };
                jQuery.post(ajaxurl, data, function(response) {

                    var response_text = response.split("_");
                    response_info = response_text[0];
                    response_followup = response_text[1];

                    if(response_info=='success'){
                        if(value_button=='0'){
                            additional_text = 'ke status waiting payment';
                        }else{
                            additional_text = 'ke status received';
                        }
                        var message = "<b>"+invoice_id+"</b><br>Status payment berhasil diupdate "+additional_text+"!";
                        var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
                        var timeout = 4000;     /* 5000 here means the alert message disappears after 5 seconds. */
                        createAlert(message, status, timeout);
                        if(followup_status=='1' && value_button=='1'){
                            // redirect WA
                            var redirectWindow = window.open(response_followup, "_blank");
                            redirectWindow.location;
                        }
                    }else if(response_info=='wanotif'){
                        var message = "<b>"+invoice_id+"</b><br>Status payment berhasil diupdate dan pesan sudah dikirimkan melalui wanotif!";
                        var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
                        var timeout = 4000;     /* 5000 here means the alert message disappears after 5 seconds. */
                        createAlert(message, status, timeout);
                    }else if(response_info=='wanotiffalse'){
                        var message = "<b>"+invoice_id+"</b><br>Status payment berhasil diupdate namun pesan gagal dikirimkan melalui wanotif! Cek kembali no whatsapp donatur, tidak boleh kosong.";
                        var status = "danger";    /* There are 4 statuses: success, info, warning, danger  */
                        var timeout = 4000;     /* 5000 here means the alert message disappears after 5 seconds. */
                        createAlert(message, status, timeout);
                    }else{

                        
                    }
                });

            });


            // Initialize the plugin
            $(document).on('click', '.detail_donasi', function(e){

                if($(this).hasClass("img_confirmation")){
                    return false;
                }
                
                Swal.fire({
                      title: '<strong id="popup_title">Data Detail</strong>',
                      icon: false,
                      html:
                        '<div id="data_box"><div class="spinner-border text-primary" role="status"></div></div>',
                      showCloseButton: true,
                      showClass: {
                        popup: 'animate__animated animate__zoomIn animate__faster'
                      },
                      hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                      }
                    })

                var donasi_id = $(this).data('id');
                var data_nya = [
                    donasi_id
                ];

                var data = {
                    "action": "djafunction_get_donasi",
                    "datanya": data_nya
                };
                
                jQuery.post(ajaxurl, data, function(response) {
                    $('#data_box').html(response);
                    // $('.select2').show();
                });
            });

            // Initialize the plugin
            $(document).on('click', '.detail_donasi', function(e){

                if($(this).hasClass("img_confirmation")){
                    // Swal.fire({
                    //       title: '<strong id="popup_title">Data Konfirmasi</strong>',
                    //       icon: false,
                    //       html:
                    //         '<div id="data_box"><div class="spinner-border text-primary" role="status"></div></div>',
                    //       showCloseButton: true,
                    //       showClass: {
                    //         popup: 'animate__animated animate__zoomIn animate__faster'
                    //       },
                    //       hideClass: {
                    //         popup: 'animate__animated animate__fadeOutUp animate__faster'
                    //       }
                    //     });

                    var donasi_id = $(this).data('id');
                    console.log($(this));
                    console.log(donasi_id);
                    var data_nya = [
                        donasi_id
                    ];

                    var data = {
                        "action": "djafunction_get_donasi_confirmation",
                        "datanya": data_nya
                    };
                    
                    jQuery.post(ajaxurl, data, function(response) {
                    //     $('#data_box').html(response);
                    //     $('.select2').show();
                        window.open('https://ympb.or.id/s/?inv='+response);
                    });
                }
            });

            // Initialize the plugin
            $(document).on('click', '.del_conf', function(e){
                var donasi_id = $(this).attr('data-id');

                swal.fire({
                  title: 'Anda ingin menghapus konfirmasi ini?',
                  text: "Data tidak bisa dikembalikan jika sudah dihapus!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Ya, Hapus sekarang!',
                  cancelButtonText: 'Cancel',
                  reverseButtons: true
                }).then(function(result) {
                  if (result.value) {
                    
                    var data_nya = [
                        donasi_id
                    ];

                    var data = {
                        "action": "djafunction_del_confirmation",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){
                            $('#donasi_'+donasi_id+' .img_confirmation').slideUp();

                            swal.fire(
                              'Delete success!',
                              'Data konfirmasi berhasil dihapus.',
                              'success'
                            );

                        }else{
                            swal.fire(
                              'Delete Failed!',
                              '',
                              'warning'
                            );

                        }
                        
                    });
                  }
                })
            });
            

        });



        $(document).on("click", ".del_donasi", function(e) {
            
            var donasi_id = $(this).attr('data-id');

            swal.fire({
              title: 'Anda yakin ingin menghapus donasi ini?',
              text: "Data tidak bisa dikembalikan jika sudah dihapus!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Ya, Hapus sekarang!',
              cancelButtonText: 'Cancel',
              reverseButtons: true
            }).then(function(result) {
              if (result.value) {
                
                var data_nya = [
                    donasi_id
                ];

                var data = {
                    "action": "djafunction_del_donasi",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='success'){
                        $('#donasi_'+donasi_id).slideUp();

                        swal.fire(
                          'Deleted!',
                          'Data donasi berhasil didelete.',
                          'success'
                        );

                    }else{
                        swal.fire(
                          'Delete Failed!',
                          '',
                          'warning'
                        );

                    }
                    
                });
              }
            })

        });

        $(function() {
            $('tbody tr').hover(function() { 
                $(this).find('.box-button').removeClass("button-hide"); 
            }, function() { 
                $(this).find('.box-button').addClass("button-hide");
            });
        });

        $(document).on("click", ".img_confirmation", function(e) {
            $(this).addClass('status_check');
        });


        $(document).on("click", ".btn-followup", function(e) {

            var donasi_id = $(this).data('id');
            var followup_number = $(this).data('followup');

            $('.followup'+followup_number).find(".spinner-border").remove();
            $(this).find("span").before('<div class="spinner-border spinner-border-sm" role="status"></div>');
            $(this).find("i").hide();
            $(this).addClass('sent');

            var data_nya = [
                donasi_id,
                followup_number
            ];

            var data = {
                "action": "djafunction_send_wa",
                "datanya": data_nya
            };
            
            jQuery.post(ajaxurl, data, function(response) {
                
                if(response.length<=7){
                    if(response=='success'){
                        // alert('success');

                        var message = "Followup "+followup_number+" - Message was sent to whatsapp.";
                        var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
                        var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
                        createAlert(message, status, timeout);

                    }else{
                        var message = "Send message failed.";
                        var status = "danger";    /* There are 4 statuses: success, info, warning, danger  */
                        var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
                        createAlert(message, status, timeout);
                    }
                    $('.followup'+followup_number).find("i").show();
                    $('.followup'+followup_number).find(".spinner-border").remove();
                }else{
                    // redirect WA
                    var redirectWindow = window.open(response, "_blank");
                    redirectWindow.location;
                }
                

            });

        });

        function createAlert(e,t,n){var a,o=document.createElement("div");o.className+="animation-target lala-alert ";var r="alert-"+t+" ";o.className+=r;var l=document.createElement("span");l.className+=" close-alert-x glyphicon glyphicon-remove",l.addEventListener("click",function(){var e=this.parentNode;e.parentNode.removeChild(e)}),o.addEventListener("mouseover",function(){this.classList.remove("fade-out"),clearTimeout(a)}),o.addEventListener("mouseout",function(){a=setTimeout(function(){o.parent;o.className+=" fade-out",o.parentNode&&(a=setTimeout(function(){o.parentNode.removeChild(o)},500))},3e3)}),o.innerHTML=e,o.appendChild(l);var d=document.getElementById("lala-alert-wrapper");d.insertBefore(o,d.children[0]),a=setTimeout(function(){var e=o;e.className+=" fade-out",e.parentNode&&(a=setTimeout(function(){e.parentNode.removeChild(e)},500))},n)}window.onload=function(){document.getElementById("lala-alert-wrapper"),document.getElementById("alert-success"),document.getElementById("alert-info"),document.getElementById("alert-warning"),document.getElementById("alert-danger")};


    </script>


    <?php } // end of opt data-order ?> 

    <?php
}