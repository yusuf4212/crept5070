<?php

function donasiaja_dashboard() {
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
        $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="form_setting" or type="btn_followup" or type="text_f1" or type="text_f2" or type="text_f3" or type="text_f4" or type="text_f5" or type="text_received" or type="text_received_status" or type="wanotif_on_dashboard"  ORDER BY id ASC');
        $form_setting = $query_settings[0]->data;
        $btn_followup = $query_settings[1]->data;
        $text_f1 = $query_settings[2]->data;
        $text_f2 = $query_settings[3]->data;
        $text_f3 = $query_settings[4]->data;
        $text_f4 = $query_settings[5]->data;
        $text_f5 = $query_settings[6]->data;
        $text_received = $query_settings[7]->data;
        $text_received_status = $query_settings[8]->data;
        $wanotif_on_dashboard = $query_settings[9]->data;
        
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
    <!-- <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
    

    <!-- jQuery -->
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery-ui.min.js"></script>
    <!-- <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/metismenu.min.js"></script> -->
    <!-- <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/waves.js"></script> -->
    <!-- <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/feather.min.js"></script> -->
    <!-- <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery.slimscroll.min.js"></script> -->
    <!-- <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/apexcharts/apexcharts.min.js"></script>  -->


    <?php 

    // wp_enqueue_script('jquery');
    // This will enqueue the Media Uploader script
    wp_enqueue_media();
        
    ?>

    <style>
    .notice, #message, #dolly {
    display:none;
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
    a.detail_donasi i {
        color: #8997BD !important;
        margin-right: 3px;
    }
    a.detail_donasi span {
        font-size:10px;
        color:#8997BD;
    }

    a.detail_donasi:hover span, a.detail_donasi:hover i {
        color: #36BD47 !important;
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
        padding: 2px 8px;
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
    .btn-outline-info {
        color: #7887b5;
        border-color: #7887b5;
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


    @media only screen and (max-width:480px) {
      .dja_label {
        width: auto;
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
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate.css" rel="stylesheet" type="text/css">

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
                                                        <li>{payment_account} : Nama Pemilik Rekening</li>
                                                        <li>{campaign_title} : Judul program dari campaign</li>
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


        ?>

        <?php 

            $id_login = wp_get_current_user()->ID;

            $filternya = "";
            if(isset($_GET['date'])){

                $date_filter = $_GET['date'];

                if($date_filter=='today' || $date_filter=='week' || $date_filter=='month' || $date_filter=='3months' || $date_filter=='all'){
                
                    // Date
                    $today = date('Y-m-d');
                    $one_week_ago = date("Y-m-d", strtotime("-7 day"));
                    $one_month_ago = date("Y-m-d", strtotime("-30 day"));
                    $three_months_ago = date("Y-m-d", strtotime("-90 day"));

                    if($date_filter=='today'){
                        $filternya = "and a.created_at BETWEEN '$today 00:00' AND '$today 23:59'";
                    }elseif($date_filter=='week'){
                        $filternya = "and a.created_at BETWEEN '$one_week_ago 00:00' AND '$today 23:59'";
                    }elseif($date_filter=='month'){
                        $filternya = "and a.created_at BETWEEN '$one_month_ago 00:00' AND '$today 23:59'";
                    }elseif($date_filter=='3months'){
                        $filternya = "and a.created_at BETWEEN '$three_months_ago 00:00' AND '$today 23:59'";
                    }else{
                        $filternya = "";
                    }
                }
            }

            if($role=='donatur'){

                if($c_id!=null){ // Campaign ID isi dan ada

                    // cek c_id ada dan beneran donatur yang punya gak
                    // klo iya lanjut, klo gak ya no data

                    // STATISTICS - Get Data CAMPAIGN
                    $rows = $wpdb->get_results("SELECT * from $table_name where campaign_id = '$c_id' and user_id = '$id_login' ORDER BY id DESC");

                    $total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name4 a
                    LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
                    WHERE c.campaign_id = '$c_id' and a.status='1' and c.user_id = '$id_login' $filternya ")[0];
                    $nominal_donasi_terkumpul = $total_donasi->total;
                    $jumlah_donasi_terkumpul = $total_donasi->jumlah;

                    $total_donasi_semua = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name4 a 
                    LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
                    WHERE c.campaign_id = '$c_id' and c.user_id = '$id_login' $filternya ")[0];

                    $jumlah_donasi_semua = $total_donasi_semua->jumlah;

                    if($jumlah_donasi_semua>=1){
                        $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
                    }else{
                        $konversi = 0;
                    }


                    // GET DATA DONASI - Normal without date
                    $data_donasi = $wpdb->get_results("
                    SELECT a.*, b.user_randid, b.user_pp_img, c.title, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
                    LEFT JOIN $table_name6 b on a.user_id = b.user_id
                    LEFT JOIN $table_name c on a.campaign_id = c.campaign_id
                    WHERE c.campaign_id = '$c_id' and c.user_id = '$id_login'
                    $filternya
                    ORDER BY a.id DESC");


                }else{

                    $rows = $wpdb->get_results("SELECT * from $table_name where user_id=$id_login ORDER BY id DESC");

                    $nominal_donasi_terkumpul = 0;
                    $jumlah_donasi_terkumpul = 0;
                    foreach ($rows as $value) {
                        $total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name4 where campaign_id='$value->campaign_id' and status='1' $filternya ")[0];
                        $nominal_donasi_terkumpul = $nominal_donasi_terkumpul + $total_donasi->total;
                        $jumlah_donasi_terkumpul = $jumlah_donasi_terkumpul + $total_donasi->jumlah;
                    }

                    $jumlah_donasi_semua = 0;
                    foreach ($rows as $value) {
                        $total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name4 where campaign_id='$value->campaign_id' $filternya ")[0];
                        $jumlah_donasi_semua = $jumlah_donasi_semua + $total_donasi->jumlah;
                    }

                    if($jumlah_donasi_semua>=1){
                        $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
                    }else{
                        $konversi = 0;
                    }


                    // GET DATA DONASI
                    $data_donasi = $wpdb->get_results("
                    SELECT a.user_id as uid, a.publish_status, a.slug, a.title, b.*, c.user_pp_img FROM $table_name a 
                    left JOIN $table_name4 b ON b.campaign_id = a.campaign_id
                    left JOIN $table_name6 c ON c.user_id = b.user_id
                    WHERE a.user_id = $id_login and b.id!=''
                    $filternya
                    ORDER BY a.id DESC");

                }
                

                // print_r($data_donasi);
                
            }else{

                if($c_id!=null){ // Campaign ID isi dan ada

                    // STATISTICS - Get Data CAMPAIGN
                    $rows = $wpdb->get_results("SELECT * from $table_name where campaign_id = '$c_id' ORDER BY id DESC");

                    $total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name4 a
                    LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
                    WHERE c.campaign_id = '$c_id' and a.status='1' $filternya ")[0];
                    $nominal_donasi_terkumpul = $total_donasi->total;
                    $jumlah_donasi_terkumpul = $total_donasi->jumlah;

                    $total_donasi_semua = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name4 a 
                    LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
                    WHERE c.campaign_id = '$c_id' $filternya ")[0];

                    $jumlah_donasi_semua = $total_donasi_semua->jumlah;

                    if($jumlah_donasi_semua>=1){
                        $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
                    }else{
                        $konversi = 0;
                    }


                    // GET DATA DONASI - Normal without date
                    $data_donasi = $wpdb->get_results("
                    SELECT a.*, b.user_randid, b.user_pp_img, c.title, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
                    LEFT JOIN $table_name6 b on a.user_id = b.user_id
                    LEFT JOIN $table_name c on a.campaign_id = c.campaign_id
                    WHERE c.campaign_id = '$c_id'
                    $filternya
                    ORDER BY a.id DESC");



                }else{

                    // STATISTICS - Get Data CAMPAIGN
                    $rows = $wpdb->get_results("SELECT * from $table_name ORDER BY id DESC");

                    $total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(id) as jumlah FROM $table_name4 a where status='1' $filternya ")[0];
                    $nominal_donasi_terkumpul = $total_donasi->total;
                    $jumlah_donasi_terkumpul = $total_donasi->jumlah;

                    $total_donasi_semua = $wpdb->get_results("SELECT a.*, COUNT(a.id) as jumlah FROM $table_name4 a WHERE a.id !='' $filternya ");
                    $jumlah_donasi_semua = $total_donasi_semua[0]->jumlah;

                    if($jumlah_donasi_semua>=1){
                        $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
                    }else{
                        $konversi = 0;
                    }

                    // GET DATA DONASI
                    $data_donasi = $wpdb->get_results("
                        SELECT a.*, b.user_randid, b.user_pp_img, c.title, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
                        LEFT JOIN $table_name6 b on a.user_id = b.user_id
                        LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
                        WHERE a.id != ''
                        $filternya
                        ORDER BY a.id DESC");


                }
                
                // print_r($rows);
                // $table_name = $wpdb->prefix . "dja_campaign";
                // $table_name2 = $wpdb->prefix . "dja_category";
                // $table_name3 = $wpdb->prefix . "dja_campaign_update";
                // $table_name4 = $wpdb->prefix . "dja_donate";
                // $table_name5 = $wpdb->prefix . "dja_settings";
                // $table_name6 = $wpdb->prefix . "dja_users";


                

            }

            $konversi = $konversi*100;
            $konversi = number_format($konversi,1,",",".").' %';


            // GET CAMPAIGN ACTIVE
            $active_campaign = 0;
            foreach ($rows as $row) {
                                                
                $date_now = date('Y-m-d');
                $datetime1 = new DateTime($date_now);
                $datetime2 = new DateTime($row->end_date);
                $hasil = $datetime1->diff($datetime2);

                if($hasil->invert==true && $row->publish_status==1){
                    
                }else{
                    if($row->publish_status==1){
                        $active_campaign++;
                    }
                }
            }

        ?>


        <div class="body-nya" style="margin-top:20px;margin-right:20px;">

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">

                    <?php /*
                        <div class="row" style="padding-bottom: 5px;">
                            <!-- end page title end breadcrumb -->
                            <div class="col-md-6 show_campaign">
                                
                            </div>

                            <div class="col-md-6">
                                
                            </div>
                        </div>
                    */ ?>

                    <!-- end col -->       

                    <?php 

                        $date_title = '';
                        if(isset($_GET['date'])){

                            $date_filter_title = $_GET['date'];

                            if($date_filter_title=='today'){
                                $date_title = ' - Today';
                            }elseif($date_filter_title=='week'){
                                $date_title = ' - A Week Ago';
                            }elseif($date_filter_title=='month'){
                                $date_title = ' - A Month Ago';
                            }elseif($date_filter_title=='3months'){
                                $date_title = ' - 3 Months Ago';
                            }else{
                                $date_title = ' - All';
                            }
                        }else{
                            $date_title = ' - All';
                        }

                        if(isset($_GET['id'])){
                            $c_id = $_GET['id'];

                            $get_title = $wpdb->get_results("SELECT * from $table_name where campaign_id = '$c_id'");
                            if ($get_title==null) {
                               $titlenya = 'Show All'.$date_title;
                            }else{
                                $titlenya = $get_title[0]->title.$date_title;
                            }
                            
                        }else{
                            $c_id = 'all';
                            $titlenya = 'Show All'.$date_title;
                        }
                    
                    ?>

                    <div class="row"> 
                        <div class="col-sm-12" style="margin-bottom: 10px;">
                            <div class="page-title-box" style="padding-top: 10px;">

                                <div class="float-right" style="margin-left: 80px;">
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
                                            <div id="by_date_list" class="dropdown-menu" style="">
                                                <?php if(isset($_GET['id'])){ ?>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=').$_GET['id'].'&date=today';?>">Today</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=').$_GET['id'].'&date=week';?>">A Week ago</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=').$_GET['id'].'&date=month';?>">A Month ago</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=').$_GET['id'].'&date=3months';?>">3 Months ago</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=').$_GET['id'].'&date=all';?>">All</a>
                                                <?php } else { ?>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard').'&date=today';?>">Today</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard').'&date=week';?>">A Week ago</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard').'&date=month';?>">A Month ago</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard').'&date=3months';?>">3 Months ago</a>
                                                    <a class="dropdown-item" href="<?php echo admin_url('admin.php?page=donasiaja_dashboard').'&date=all';?>">All</a>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <?php if($role=='donatur' || $role=='cs'){ } else { ?>
                                        <div class="btn-group ml-1" style="background: #fff;">
                                            <button id="download_donasi" type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Import Data Donasi" style="height: 32px;" data-id="<?php echo $c_id; ?>">
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
                                
                                <h4 class="page-title" style="padding-right: 160px;"><i class="dripicons-document" style="margin-right: 10px;position: absolute;"></i><div style="margin-left: 30px;"><?php echo $titlenya; ?></div></h4>
                            </div><!--end page-title-box-->
                        </div>

                        <div class="col-lg-4">
                            <div class="card" style="border-bottom: 4px solid #f20988;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Total Donasi</h5>
                                                <h2 class="my-2">Rp&nbsp;<?php echo number_format($nominal_donasi_terkumpul,0,",","."); ?></h3>
                                                <p class="text-muted mb-0">Total donasi yang berhasil terkumpul</p>
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
                        <div class="col-lg-4">
                            <div class="card" style="border-bottom: 4px solid #7680ff;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <?php /*
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Jumlah Donasi : <?php echo number_format($jumlah_donasi_semua,0,",","."); ?></h5>
                                                <h5 class="" style="margin-bottom: 25px;">Jumlah Donasi Terkumpul: <?php echo number_format($jumlah_donasi_terkumpul,0,",","."); ?></h5>
                                                <!-- <h2 class="my-2"><?php echo number_format($jumlah_donasi_terkumpul,0,",","."); ?></h3> -->
                                                <p class="text-muted mb-0">Jumlah donasi keseluruhan</p>
                                            </div><!--end col-->
                                            */ ?>
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Jumlah Donasi</h5>
                                                <h2 class="my-2"><?php echo number_format($jumlah_donasi_semua,0,",","."); ?> Donasi</h3>
                                                <p class="text-muted mb-0">Terkumpul <b style="color: #313f68;"><?php echo number_format($jumlah_donasi_terkumpul,0,",","."); ?></b> dari <b style="color: #313f68;"><?php echo number_format($jumlah_donasi_semua,0,",","."); ?></b>  Donasi </p>
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
                        <div class="col-lg-4">
                            <div class="card" style="border-bottom: 4px solid #0dcfd9;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Konversi</h5>
                                                <h2 class="my-2"><?php echo $konversi; ?></h3>
                                                <p class="text-muted mb-0">Konversi donasi yang berhasil terkumpul</p>
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
                                                         
                    </div><!--end row-->
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="max-width: 100%;">
                                <div class="card-body">
                                    <h3 class="header-title mt-0">Data Donasi</h3>
                                    <br>
                                    <br>
                                    <div class="table-responsive dash-social">
                                        <?php
                                            if($btn_followup=='5'){
                                                $width = 'width:200px;';
                                            }elseif($btn_followup=='4'){
                                                $width = 'width:165px;';
                                            }else{
                                                $width = 'width:120px;';
                                            }

                                        ?>
                                        <table id="datatable" class="table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th></th>
                                                    <th>Donatur</th>
                                                    <th>Whatsapp</th>
                                                    <th style="width: 110px;">Donasi</th>
                                                    <th>Program</th>                                                 
                                                    <th>Payment</th> 
                                                    <?php if($role!='donatur'){ ?>                                                 
                                                    <th style="<?php echo $width; ?>">Followup</th>
                                                    <?php } ?>                                 
                                                    <th>Date</th>
                                                    <?php if($role=='donatur' || $role=='cs'){ } else { ?>
                                                    <th style="text-align: center;">Action</th>
                                                    <?php } ?> 
                                                </tr>
                                            </thead>
        
                                            <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($data_donasi as $row) { ?>
                                                
                                                <?php 


                                                    if($row->publish_status=='1'){
                                                        $campaign_url = get_site_url().'/campaign/';
                                                    }else{
                                                        $campaign_url = get_site_url().'/preview/';
                                                    }
                                                    

                                                    $button_followupnya = '';
                                                    for ($i = 1; $i <= $btn_followup; $i++){
                                                        
                                                        $var = 'f'.$i;
                                                        if($row->$var=='1'){$$var = 'sent';}else{$$var = '';}

                                                        $button_followupnya .= '
                                                            <button type="button" data-id="'.$row->id.'" data-followup="'.$i.'" class="btn btn-primary btn-followup '.$$var.' followup'.$i.'" title="Followup '.$i.'"><i class="fab fa-whatsapp"></i><span style="margin-left: 3px;">'.$i.'</span>
                                                            </button>
                                                        ';
                                                    }

                                                    $user_info = get_userdata($row->user_id);
                                                    if($user_info!=null){
                                                        $fullname = $user_info->first_name.' '.$user_info->last_name;
                                                    }

                                                    $time_donate = date('Y-m-d H:i:s',strtotime('+0 hour',strtotime($row->created_at)));

                                                    $readtime = new humanReadtime();
                                                    $a = $readtime->dja_human_time($time_donate);

                                                    if($role=='donatur'){
                                                        if($row->status=='1'){
                                                            $status = '<span class="active-status p_received">Received</span>';
                                                        }else{
                                                            $status = '<span class="active-status p_waiting">Waiting</span>';
                                                        }
                                                    }else{
                                                        if($row->status=='1'){
                                                            $status = 'received';
                                                            $checked = 'checked=""';
                                                        }else{
                                                            $status = 'waiting';
                                                            $checked = '';
                                                        }
                                                    }

                                                ?>
                                                
                                                <tr id="donasi_<?php echo $row->id?>">
                                                    <td><?php echo $no; ?></td>
                                                    <td>

                                                        <?php if($row->user_id!='0') { ?>
                                                        <a id="link_pp_<?php echo $row->id?>" href="<?php echo home_url();?>/profile/<?php echo $row->user_randid;?>" target="_blank">
                                                        <?php } ?>

                                                        <?php if($row->user_pp_img=='') { ?>
                                                            <img id="pp_<?php echo $row->id?>" src="<?php echo plugin_dir_url( __FILE__ ) . "../assets/images/pp.jpg"; ?>" alt="" class="rounded-circle thumb-md" style="border: 1px solid #dde4ec;height: 38px;width: 38px;margin-top: -4px;">
                                                            </span>
                                                        <?php }else{?>
                                                            <img id="pp_<?php echo $row->id?>" src="<?php echo $row->user_pp_img; ?>" alt="" class="rounded-circle thumb-md" style="border: 1px solid #dde4ec;height: 38px;width: 38px;margin-top: -4px;">
                                                        <?php } ?>
                                                        <?php if($row->user_id!='0') { ?>
                                                        </a>
                                                        <?php } ?>

                                                    </td>
                                                    <td><span id="name_<?php echo $row->id?>">
                                                        <?php if($row->user_id!='0') { ?>
                                                        <a href="<?php echo home_url();?>/profile/<?php echo $row->user_randid;?>" target="_blank">
                                                        <?php } ?>
                                                        <?php echo $row->name; ?>
                                                        <?php if($row->user_id!='0') { ?>
                                                        </a>
                                                        <?php } ?>

                                                    </span></td>
                                                    <td><span id="whatsapp_<?php echo $row->id?>"><?php echo $row->whatsapp; ?></span></td>
                                                    <td><span id="nominal_<?php echo $row->id?>">Rp&nbsp;<?php echo number_format($row->nominal,0,",","."); ?></span>
                                                    <?php if($role!='donatur'){ ?>
                                                    <br><a href="javascript:;" class="detail_donasi" data-id="<?php echo $row->id?>"><i class="mdi mdi-file-document-box"></i><span><?php echo $row->invoice_id; ?><span></a>
                                                        <?php if($row->process_by=='moota'){ ?>
                                                        <br>
                                                        <p style="font-size:10px;color:#91a2b0;margin-bottom: 0;"><img src="<?php echo plugin_dir_url( __FILE__ ) . "/icons/moota.png"; ?>" style="width: 10px;margin-left:1px;margin-right: 4px;margin-top: 0px;">Moota</p>
                                                        <?php }elseif($row->process_by=='ipaymu'){ ?>
                                                        <br>
                                                        <p style="font-size:10px;color:#91a2b0;margin-bottom: 0;"><img src="<?php echo plugin_dir_url( __FILE__ ) . "/icons/ipaymu.png"; ?>" style="width: 10px;margin-left:1px;margin-right: 4px;margin-top: -1px;">iPaymu</p>
                                                        <?php }else{ }?>

                                                    <?php } ?>
                                                    </td>
                                                    <td><a href="<?php echo $campaign_url.$row->slug; ?>" target="_blank"><?php echo $row->title; ?></a></td>
                                                    <td>

                                                        <?php if($role=='donatur'){ ?>
                                                            <?php echo $status; ?>
                                                        <?php }else{ ?>
                                                            <div id="payment_<?php echo $row->id?>" class="custom-control custom-switch set_payment <?php echo $status; ?>">
                                                                <input type="checkbox" class="custom-control-input set_payment_status" id="customSwitchSuccess_<?php echo $row->id?>" <?php echo $checked; ?> data-id="<?php echo $row->id?>" data-invoiceid="<?php echo $row->invoice_id?>">
                                                                <label class="custom-control-label" for="customSwitchSuccess_<?php echo $row->id?>" style="font-size: 11px;"><span><?php echo ucfirst($status); ?></span></label>
                                                                
                                                            </div>

                                                        <?php } ?>

                                                    </td>
                                                    <?php if($role!='donatur'){ ?>
                                                    <td>
                                                        <?php echo $button_followupnya; ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td><?php 
                                                    // echo date("M",strtotime($row->created_at)).'&nbsp;'.date("j",strtotime($row->created_at)).',&nbsp;'.date("Y",strtotime($row->created_at)).'&nbsp;-&nbsp;'.date("H:i ",strtotime($row->created_at)).'<br><span style='."'".'font-size:10px;color:#91a2b0;'."'".'>'.$a.'<span>';

                                                    $datenya = new DateTime($row->created_at, new DateTimeZone('Asia/jakarta') );
                                                    echo $datenya->format('d').'&nbsp;'.$datenya->format('M').',&nbsp;'.$datenya->format('Y').'&nbsp;-&nbsp;'.$datenya->format('H').':'.$datenya->format('i');
                                                    echo '<br><span style='."'".'font-size:10px;color:#91a2b0;'."'".'>'.$a.'<span>';
                                                    ?></td>
                                                    
                                                    <?php if($role=='donatur' || $role=='cs'){ } else { ?>
                                                    <td>
                                                    <button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm del_donasi" data-id="<?php echo $row->id; ?>" title="Delete Donasi">
                                                        <i class="mdi mdi-trash-can mr-2" style="margin: 0 4px !important;"></i></button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php $no++; } ?>
                                            
                                                                                            
                                            </tbody>
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
            #table_donasi {
                margin: 0 auto;
                margin-top: 10px;
                margin-bottom: 20px;
            }
            #table_donasi td {
                text-align: left;
                font-size: 15px;
                padding: 4px 7px;
            }
            .inv{
                font-size: 13px;
                background: #f1f5ff;
                padding: 12px 10px;
                margin-top: -16px;
                margin-bottom: -16px;
            }
            .title_donasi {
                font-size: 18px;
                padding: 0 30px;
            }
            .swal2-popup {
                padding-bottom: 40px !important;
                padding-top: 30px !important;
                border-radius: 12px;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            button.swal2-close {
                font-size: 28px;
                margin-top: 8px;
                margin-right: 8px;
            }
            .p_waiting {
                background:#E1345E;
            }
            .box_table {
                padding: 10px 20px;
            }
            #table_donasi input[type="text"] {
                display: none;
                width: 60%;
            }
            /*.box_table input[type="text"] {
                width: 60%;
            }*/
            .swal2-actions {
                /*opacity: 0 !important;*/
            }
            #edit_data_donasi {
                display: none;
            }
            #edit_data_donasi .form-group {
                margin-bottom: 5px;
            }
            #errmsg, #errmsg2 {
                display: none;
                position: absolute;
                right: 0;
                margin-right: 15px;
                margin-top: -40px;
                background: #ff4343;
                color: #fff;
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 9px;
            }
            #edit_data_donasi input.form-control {
                padding-left: 12px;
                padding-right: 10px;
            }
            #edit_data_donasi textarea.form-control {
                font-size: 14px;
            }
            .swal2-container.swal2-center.swal2-backdrop-show{
                z-index: 99999;
            }
            .select2-container--open {
                z-index: 99999999999999 !important;
            }
            .show_campaign .select2-container--open {
                z-index: 9999 !important;
            }
            .select2-container--default .select2-selection--single {
                background-color: #fff;
                height: 32px;
                border: 1px solid #c8d0e4;
                padding: 0;
                font-size: 13px;
                border-radius: 4px;
                padding-left: 4px;

            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                /*margin-top: 7px;
                margin-right: 8px;*/
                margin-right: 5px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #7a839b;
            }
            .data_usernya .select2-container {
                width: 89% !important;
            }
            .data_usernya .select2-container--default .select2-selection--single {
                height: 45px !important;
                padding-top: 8px;
                border-top-right-radius: 0px;
                border-bottom-right-radius: 0px;
            }
            input#inp1 {
                border-top-right-radius: 0px !important;
                border-bottom-right-radius: 0px !important;
            }
            .data_usernya .select2-container--default .select2-selection--single .select2-selection__arrow {
                margin-top: 8px;
            }
            .select2-dropdown {
                border: 1px solid #dcdee6;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                text-align: left;
            }
            .select2-results__option {
                padding-left: 10px;
                padding-right: 10px;
            }
            .select2-container--default .select2-search--dropdown .select2-search__field {
                padding-left: 10px;
                padding-right: 10px;
            }
            .opt_hide {
                display: none;
            }
            .select2.campaign_select .select2-container .select2-selection--single {
                border: 1px solid #7887b5 !important;
            }
            .form_upload {
                padding:30px 60px 30px 60px;
            }


            .filepond--drop-label {
                color: #4c4e53;
            }

            .filepond--label-action {
                text-decoration-color: #babdc0;
            }

            .filepond--panel-root {
                border-radius: 2em;
                background-color: #edf0f4;
                height: 1em;
            }

            .filepond--item-panel {
                background-color: #595e68;
            }

            .filepond--drip-blob {
                background-color: #7f8a9a;
            }

        </style>

        <!-- Required datatable js -->

        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Buttons examples -->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/buttons.bootstrap4.min.js"></script>
        <!-- <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/jszip.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/pdfmake.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/vfs_fonts.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/buttons.print.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/buttons.colVis.min.js"></script> -->
        
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/select2/select2.min.js"></script>
        

        <!-- App js -->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/app.js"></script>

        <!-- sweetalert2 -->
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate-4.1.1.min.css" rel="stylesheet" type="text/css">


        <script>

            jQuery(document).ready(function($){

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
                    var redirectWindow = window.open("<?php echo admin_url('admin-post.php?action=download_data_donasi'); ?>&c_id="+id_campaign, "_self");
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

                    if ($("#edit_data_donasi .select2").hasClass("opt_active")) {
                        var name    = $('.select2.usernya_select').find("option:selected").val();
                        var userid  = $('.select2.usernya_select').find("option:selected").attr("data-userid");
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
                        user_randid
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
                        $('#d_whatsapp').text(wa);
                        $('#d_email').text(email);
                        $('#d_comment').text(comment);
                        $('#d_nominal').text('Rp '+nominal);

                        
                        $('#whatsapp_'+id_donasi).text(wa);
                        $('#nominal_'+id_donasi).text('Rp '+nominal);

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

                // Buttons examples
                var table = $('#datatable').DataTable({
                    lengthChange: false,
                    // buttons: ['excel'] // pdf, colvis, excel, copy
                });

                table.buttons().container()
                  .appendTo('#datatable_wrapper .col-md-6:eq(0)');

                $(document).on('change', '.set_payment_status', function(e){
                    var donasi_id = $(this).data('id');
                    var invoice_id = $(this).data('invoiceid');
                    var followup_status = <?php echo $text_received_status; ?>;

                    if(this.checked) {
                        $('#payment_'+donasi_id+' span').text('Received');
                        $('#payment_'+donasi_id+'').addClass('received').removeClass('waiting');
                        var value = 1;
                    }else{
                        $('#payment_'+donasi_id+' span').text('Waiting');
                        $('#payment_'+donasi_id+'').removeClass('received').addClass('waiting');
                        var value = 0;
                    }

                    var data_nya = [
                        donasi_id,
                        value
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
                            if(value=='0'){
                                additional_text = 'ke status waiting payment';
                            }else{
                                additional_text = 'ke status received';
                            }
                            var message = "<b>"+invoice_id+"</b><br>Status payment berhasil diupdate "+additional_text+"!";
                            var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
                            var timeout = 4000;     /* 5000 here means the alert message disappears after 5 seconds. */
                            createAlert(message, status, timeout);
                            if(followup_status=='1' && value=='1'){
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

                Swal.fire({
                      title: '<strong>Donasi</strong>',
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
                });
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