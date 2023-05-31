<?php

function donasiaja_data_fundraising() { ?>
    <?php 
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_category";
    $table_name3 = $wpdb->prefix . "dja_campaign_update";
    $table_name4 = $wpdb->prefix . "dja_donate";
    $table_name5 = $wpdb->prefix . "dja_settings";
    $table_name6 = $wpdb->prefix . "dja_users";
    $table_name7 = $wpdb->prefix . "dja_payment_list";
    $table_name8 = $wpdb->prefix . "dja_aff_code";
    $table_name9 = $wpdb->prefix . "dja_aff_submit";
    $table_name10 = $wpdb->prefix . "dja_aff_click";
    $table_name11 = $wpdb->prefix . "dja_aff_payout";

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

    if(isset($_GET['as'])){
        if($_GET['as']=="user"){
            $as_user = true;
        }else{
            $as_user = false;
        }
    }else{
        $as_user = false;
    }

    // category
    $row2 = $wpdb->get_results('SELECT * from '.$table_name2.' ');     

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="form_setting" or type="app_name" or type="page_donate" or type="fundraiser_on"  or type="fundraiser_commission_on" or type="min_payout_setting" or type="min_payout"ORDER BY id ASC');
    $form_setting   = $query_settings[0]->data;
    $app_name       = $query_settings[1]->data;
    $page_donate    = $query_settings[2]->data;
    $fundraiser_on  = $query_settings[3]->data;
    $fundraiser_commission_on    = $query_settings[4]->data;
    $min_payout_setting = $query_settings[5]->data;
    $min_payout      = $query_settings[6]->data;
    
    
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

    <!-- jQuery  -->
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/zoom/medium-zoom.min.js"></script>

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
    }body{background:#f6faff}.update-nag{display:none}img[data-action="zoom"]{cursor:pointer;cursor:-webkit-zoom-in;cursor:-moz-zoom-in}.zoom-img,.zoom-img-wrap{position:relative;z-index:666;-webkit-transition:all 300ms;-o-transition:all 300ms;transition:all 300ms}img.zoom-img{cursor:pointer;cursor:-webkit-zoom-out;cursor:-moz-zoom-out}.zoom-overlay{z-index:420;background:rgba(34,40,45,.9);position:fixed;top:0;left:0;right:0;bottom:0;pointer-events:none;filter:"alpha(opacity=0)";opacity:0;-webkit-transition:opacity 300ms;-o-transition:opacity 300ms;transition:opacity 300ms}.zoom-overlay-open.zoom-overlay{filter:"alpha(opacity=100)";opacity:1}.zoom-overlay-open,.zoom-overlay-transitioning{cursor:default}.target_tak_hingga{font-size:16px;position:absolute;margin-top:-2px;margin-left:3px}.field_required{color:#ff3b3b}.media:hover{background:#f6faff}.f_edit,.f_delete{cursor:pointer;float:right}.f_delete{margin-left:5px}.campaign-title{font-size:14px;font-weight:700;color:#384d64;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif}.campaign-title a:hover{color:#52649b}input.set_red,input.form-control.set_red,img.set_red,.mce-edit-area.set_red{border:2px solid #ED8181!important}.wp-core-ui select,div.dataTables_wrapper div.dataTables_filter input{border-color:#e5eaf0}div.dataTables_wrapper div.dataTables_filter input:visited,div.dataTables_wrapper div.dataTables_filter input:active,div.dataTables_wrapper div.dataTables_filter input:focus{border-color:#e5eaf0}.error.landingpress-message{display:none}.page-content-tab{margin:0!important;width:auto}img.thumb-cover{height:60px;border-radius:4px}.active-status{background:#1CB65D;color:#fff;border-radius:4px;padding:2px 8px;font-size:9px}table.dataTable td{font-size:12px;vertical-align:top;padding-top:15px;color:#384d64}table.dataTable td img{margin-top:3px}button.no-border{border:0;background:#f6f9ff}button.no-border:hover{color:#505DFF}button.no-border.delete_payout:hover{background:#F05860;color:#fff}.btn-group button.btn{padding:.175rem.75rem}a:active,a:focus,a:visited{box-shadow:none!important;outline:none;box-shadow:0 4px 15px 0 rgba(0,0,0,.1)}input.form-control{border:1px solid #e8ebf3!important;font-size:14px}input.form-control:active,input.form-control:visited{border:1px solid #7680FF!important;box-shadow:none!important;outline:none}input.form-control.packaged_title,input.form-control.pengeluaran_title{font-size:13px}.mce-menubar,.mce-branding{display:none}#cover_image img{border-radius:4px}.fro-profile_main-pic-change{background-color:#7680ff99;border-radius:50%;width:28px;height:28px;-webkit-box-shadow:0 0 20px 0 rgba(252,252,253,.05);box-shadow:0 0 20px 0 rgba(252,252,253,.05);transition:all.35s ease;position:absolute;right:0}#upload_cover_image{text-align:center;padding-top:3px;cursor:pointer;margin-right:20px;margin-top:10px}.fro-profile_main-pic-change:hover{background-color:#505DFF}.fro-profile_main-pic-change i{color:#fff;font-size:11px}.form-group input{height:45px;padding:0 15px}.target.currency{position:absolute;margin-top:-37px;margin-left:15px;font-weight:700;font-size:18px;color:#719eca}#packaged.currency{position:absolute;margin-top:-27px;margin-left:10px;font-weight:700;font-size:14px;color:#719eca}#packaged input{text-align:right}.opt_packaged{display:none}.opt_packaged.show{display:inline}.target input{text-align:right;font-size:24px;font-weight:700;color:#23374d}.box-slugnya{background:#e3eaf2;padding:1px 4px;border-radius:2px}.box-slugnya[contenteditable="true"]{border:1px solid #7680ff;background:#fff;padding:1px 6px}.copylink{font-size:16px;margin-right:5px;padding-top:3px;cursor:pointer}.copylink:hover{color:#505DFF}.edit-slug,.edit-status,.edit-visibility{font-size:16px;margin-left:5px;padding-top:3px;cursor:pointer}.edit-slug:hover,.edit-status:hover,.edit-visibility:hover{color:#505DFF!important}#publish_status{display:none;margin-bottom:5px}#publish-section select{height:30px!important;font-size:13px;margin-top:5px}.page-title-box{padding-bottom:0}.button-hide{visibility:hidden}.dropdown-item:hover{color:#505DFF!important}.dropdown-item.delete_payout:hover{color:#F05860!important}.show_data_donasi:hover img{-webkit-box-shadow:0 8px 16px rgba(0,0,0,.1);box-shadow:0 8px 16px rgba(0,0,0,.1);transition:border.2s linear,transform.2s linear,background-color.2s linear,box-shadow.2s linear,opacity.2s linear,-webkit-transform.2s linear,-webkit-box-shadow.2s linear;-webkit-transition:all 200ms ease-in;-webkit-transform:scale(1.02);-ms-transition:all 200ms ease-in;-ms-transform:scale(1.02);-moz-transition:all 200ms ease-in;-moz-transform:scale(1.02);transition:all 200ms ease-in;transform:scale(1.02)}@keyframes redblink{0%{background-color:rgba(255,0,0,1)}50%{background-color:rgba(255,0,0,.5)}100%{background-color:rgba(255,0,0,1)}}@-webkit-keyframes redblink{0%{background-color:rgba(255,0,0,1)}50%{background-color:rgba(255,0,0,.5)}100%{background-color:rgba(255,0,0,1)}}.detected{padding:15px 15px 15px 15px;-moz-transition:all 0.5s ease-in-out;-webkit-transition:all 0.5s ease-in-out;-o-transition:all 0.5s ease-in-out;-ms-transition:all 0.5s ease-in-out;transition:all 0.5s ease-in-out;-moz-animation:redblink normal 1.5s infinite ease-in-out;-webkit-animation:redblink normal 1.5s infinite ease-in-out;-ms-animation:redblink normal 1.5s infinite ease-in-out;animation:redblink normal 1.5s infinite ease-in-out}.toggleSwitch span span{display:none}.toggleSwitch{display:inline-block;height:18px;position:relative;overflow:visible;padding:0;cursor:pointer;width:100%;background-color:#fff;border:1px solid #c0c5d7;border-radius:6px;height:34px}.toggleSwitch*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.toggleSwitch label,.toggleSwitch>span{line-height:20px;height:20px;vertical-align:middle}.toggleSwitch input:focus~a,.toggleSwitch input:focus+label{outline:none}.toggleSwitch label{position:relative;z-index:3;display:block;width:100%}.toggleSwitch input{position:absolute;opacity:0;z-index:5}.toggleSwitch>span{position:absolute;left:0;width:calc(100%-6px);margin:0;text-align:left;white-space:nowrap;margin:0 3px}.toggleSwitch>span span{position:absolute;top:0;left:0;z-index:5;display:block;width:50%;margin-left:50px;text-align:left;font-size:.9em;width:auto;left:0;top:-1px;opacity:1;width:40%;text-align:center;line-height:34px}.toggleSwitch a{position:absolute;right:50%;z-index:4;display:block;top:3px;bottom:3px;padding:0;left:3px;width:50%;background-color:#7680FF;border-radius:4px;-webkit-transition:all 0.2s ease-out;-moz-transition:all 0.2s ease-out;transition:all 0.2s ease-out;box-shadow:0 1px 2px rgba(0,0,0,.05);border-radius:4px}.toggleSwitch>span span:first-of-type{color:#FFF;opacity:1;left:0;margin:0;width:50%}.toggleSwitch>span span:last-of-type{left:auto;right:0;color:#656d9a;margin:0;width:50%}.toggleSwitch>span:before{content:'';display:block;width:100%;height:100%;position:absolute;left:0;top:-2px;border-radius:30px;-webkit-transition:all 0.2s ease-out;-moz-transition:all 0.2s ease-out;transition:all 0.2s ease-out}.toggleSwitch input:checked~a{left:calc(50%-3px)}.toggleSwitch input:checked~span span:first-of-type{left:0;color:#656d9a}.toggleSwitch input:checked~span span:last-of-type{color:#FFF}.toggleSwitch.large{width:60px;height:27px}.toggleSwitch.large a{width:27px}.toggleSwitch.large>span{height:29px;line-height:28px}.toggleSwitch.large input:checked~a{left:41px}.toggleSwitch.large>span span{font-size:1.1em}.toggleSwitch.large>span span:first-of-type{left:50%}.toggleSwitch.xlarge{width:80px;height:36px}.toggleSwitch.xlarge a{width:36px}.toggleSwitch.xlarge>span{height:38px;line-height:37px}.toggleSwitch.xlarge input:checked~a{left:52px}.toggleSwitch.xlarge>span span{font-size:1.4em}.toggleSwitch.xlarge>span span:first-of-type{left:50%}#lala-alert-container{position:fixed;height:auto;max-width:350px;width:100%;top:18px;right:5px;z-index:9999}#lala-alert-wrapper{height:auto;padding:15px}.lala-alert{position:relative;padding:25px 30px 20px;font-size:15px;margin-top:15px;opacity:1;line-height:1.4;border-radius:3px;border:1px solid transparent;cursor:default;transition:all 0.5s ease-in-out;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.lala-alert span{opacity:.7;transition:all 0.25s ease-in-out}.lala-alert span:hover{opacity:1}.alert-success{color:#fff;background-color:#37c1aa}.alert-success>span{color:#0b6f5e}.alert-info{color:#fff;background-color:#3473c1}.alert-info>span{color:#1e4567}.alert-warning{color:#6b7117;background-color:#ffee9e}.alert-warning>span{color:#8a6d3b}.alert-danger{color:#fff;background-color:#d64f62}.alert-danger>span{color:#6f1414}.close-alert-x{position:absolute;float:right;top:10px;right:10px;cursor:pointer;outline:none}.fade-out{opacity:0}.custom-control-input:checked~.custom-control-label::before{color:#fff;border-color:#36bd47;background-color:#36bd47}.custom-control-label::before{border:#d8204c solid 1px}.custom-switch.custom-control-label::after{background-color:#d8204c}.custom-radio.custom-control-label::before{border-radius:50%;border:1px solid currentColor}.animation-target{animation:animation 1500ms linear both}@keyframes animation{0%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,250,0,0,1)}3.14%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,160.737,0,0,1)}4.3%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,132.565,0,0,1)}6.27%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,91.357,0,0,1)}8.61%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,51.939,0,0,1)}9.41%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,40.599,0,0,1)}12.48%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,6.498,0,0,1)}12.91%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,2.807,0,0,1)}16.22%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-17.027,0,0,1)}17.22%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-20.404,0,0,1)}19.95%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-24.473,0,0,1)}23.69%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-21.178,0,0,1)}27.36%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-13.259,0,0,1)}28.33%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-11.027,0,0,1)}34.77%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,.142,0,0,1)}39.44%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,2.725,0,0,1)}42.18%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,2.675,0,0,1)}56.99%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-.202,0,0,1)}61.66%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-.223,0,0,1)}66.67%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,-.104,0,0,1)}83.98%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,.01,0,0,1)}100%{transform:matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1)}}.breadcrumb-item.active{color:#a1b3ca}
    
    .img-ss {
        width:24px;
        margin-bottom:5px;
        height:auto;
        border-radius:2px;
    }

    .swal2-confirm.swal2-styled {
        font-size:14px !important;
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
    .form_upload {
      padding: 50px 20px 60px 20px;
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

    #count_status_on_process {
        position: absolute;background: #ffbf47;width: 20px;height: 20px;text-align: center;border-radius: 30px;margin-top: -10px;z-index: 2;font-size: 10px;color: #fff;padding-top: 3px;margin-left: 80px;margin-top: -22px;
    }

    button.swal2-close {
        color:#fff;
    }

    @media only screen and (max-width:767px) {
        .page-title-box .breadcrumb {
            display: inline-flex !important;
            width: 100% !important;
        }
    }

    @media only screen and (max-width:480px) {
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
      .card .card-body {
        /* padding-right: 5px;
        padding-left: 5px; */
      }
      #publish {
        width: 100%;
        margin-top: -10px;
      }
      #publish-section .col-sm-7.text-left.dja_label, #publish-section .col-sm-5.text-left.dja_label, #publish-section .col-sm-6.text-left.dja_label {
        width: 100%;
      }
      #publish-section .publish_update, #preview_campaign {
        width: 100%;
        margin-top: 20px;
      }
      .col-sm-7 .publish, .col-sm-7 .publish_update {
        width: 100%;
      }
      .col-sm-7.col-data {
        width: 65%;
      }
      .dja_label.label_status {
        padding-right: 24px !important;
      }
      #cover_image {
        position: absolute;
        margin-top: -115px;
        right: 0;
        padding-right: 44px;
      }
      #cover_image img {
          height: 75px;
      }
      #cover_image #dja_image_cover {
        right: 0 !important;
      }
      #upload_cover_image {
        margin-right: 55px;
      }
      .row-title {
        margin-top: 70px;
      }
      .label_title {
        padding-right: 30px;
      }
      .nominal_packaged, .title_packaged {
        width: 65%;
      }
      #packaged .currency {
        margin-top: -30px;
      }
      #shorturl {
        margin-left: 10px;
      }
      label.opt_zakat_penghasilan.opt_title {
        padding-right: 53px;
      }
      #c_opt_zakat_penghasilan, #t_opt_zakat_penghasilan {
        width: 60%;
      }
      .bank-col-1 {
        margin-bottom: 10px;
      }
      .bank-col-2 .form-group, .bank-col-3 .form-group, .bank-col-4 .form-group  {
        margin-bottom: 10px;
      }
      .bank-col-5 {
        margin-bottom: 30px;
        text-align: right;
      }
      .bank-col-1 .form-control, .bank-col-4 .form-control {
        font-size: 13px;
      }
      .unique_number_range.col-min, .unique_number_range.col-max {
        width: 48%;
      }
      #update_info {
        width: 100% !important;
      }
      .col-total-donasi .card, .col-jumlah-donasi .card {
            padding-bottom: 0px;
            margin-bottom: 5px;
      }

    }

    
    </style>

    <?php check_license(); ?>

    <?php // if($as_user==true){ ?>

        <!-- <h1>aloha</h1> -->

    <?php // }else{ ?>


        <?php 

            
            check_verified_campaign($akses);

            $id_login = wp_get_current_user()->ID;

            // ************************************
            if($role=='administrator'){
                if($as_user==true) {
                    $rows_aff = $wpdb->get_results("SELECT a.id, a.campaign_id, a.aff_code, a.user_id as fundraiser_id, c.user_randid, c.user_pp_img from $table_name8 a 
                    left JOIN $table_name6 c ON a.user_id = c.user_id
                    where a.user_id=$id_login
                    ORDER BY a.id DESC");
                }else{
                    $rows_aff = $wpdb->get_results("SELECT a.id, a.campaign_id, a.aff_code, a.user_id as fundraiser_id, c.user_randid, c.user_pp_img from $table_name8 a 
                    left JOIN $table_name6 c ON a.user_id = c.user_id
                    ORDER BY a.id DESC");
                }
                
            }else{
                $rows_aff = $wpdb->get_results("SELECT a.id, a.campaign_id, a.aff_code, a.user_id as fundraiser_id, c.user_randid, c.user_pp_img from $table_name8 a 
                left JOIN $table_name6 c ON a.user_id = c.user_id
                where a.user_id=$id_login
                ORDER BY a.id DESC");
            }
            

            // $rows_aff_by_user = $wpdb->get_results("SELECT a.id, a.campaign_id, a.aff_code, a.user_id as fundraiser_id, b.title, b.slug, b.image_url, b.user_id from $table_name8 a 
            //     left JOIN $table_name b ON b.campaign_id = a.campaign_id
            //     where a.user_id=$id_login ORDER BY a.id DESC");

            // ************************************

            $the_data = '';
            $no = 1;
            $total_fundraising = 0;
            $komisi_fundraising = 0;
            $belum_dicairkan = 0;
            $sudah_dicairkan = 0;
            $on_process = 0;
            $success_fundraising = 0;
            $link_fundraising = 0;
            // print_r($rows_aff);
            foreach ($rows_aff as $row) { 

                if($row->aff_code!=''){$ref='?ref='.$row->aff_code;}else{$ref='';}
                
                $data_campaign = $wpdb->get_results("SELECT b.title, b.slug, b.image_url, b.user_id from $table_name b 
                    where campaign_id='$row->campaign_id'");
                
                if($data_campaign==null){
                    $title_campaign     = '';
                    $slug_campaign      = '';
                    $image_url_campaign = '';
                    $user_id_campaign   = '';
                    $the_campaign = '';
                    
                }else{
                    $title_campaign     = $data_campaign[0]->title;
                    $slug_campaign      = $data_campaign[0]->slug;
                    $image_url_campaign = $data_campaign[0]->image_url;
                    $user_id_campaign   = $data_campaign[0]->user_id;
                    
                    if($image_url_campaign==null){
                        $image_url_campaign = plugin_dir_url( __FILE__ ).'images/cover_donasiaja.jpg';
                    }
                    
                    $the_campaign = '<img src="'.$image_url_campaign.'" alt="" class="thumb-cover" style="width:45px;position:absolute;margin-bottom: 5px;height:auto;margin-left: -65px;">
                            <a href="'.get_site_url().'/campaign/'.$slug_campaign.$ref.'" target="_blank" style="">'.$title_campaign.'</a>';
                }
                

                if($row->fundraiser_id=='0'){
                    $fundraiser = 'No Name';
                }else{
                    $user_info2 = get_userdata($row->fundraiser_id);
                    $fundraiser = '<a href="'.home_url().'/profile/'.$row->user_randid.'" target="_blank">'.$user_info2->first_name.' '.$user_info2->last_name.'</a>';

                }
                

                $count_click = $wpdb->get_results("SELECT COUNT(id) as total_click FROM $table_name10 where affcode_id='$row->id'  ");
                if($count_click==null){
                    $total_click = 0;
                }else{
                    $total_click = $count_click[0]->total_click;
                }


                $count_submit = $wpdb->get_results("SELECT COUNT(id) as total_submit FROM $table_name9 where affcode_id='$row->id'  ");
                if($count_submit==null){
                    $total_submit = 0;
                }else{
                    $total_submit = $count_submit[0]->total_submit;
                }

                $count_submit2 = $wpdb->get_results("SELECT COUNT(a.id) as total_submit, b.status, SUM(b.nominal) as total_donate, SUM(a.nominal_commission) as nominal_commission  FROM $table_name9 a 
                    left JOIN $table_name4 b ON b.id = a.donate_id
                    where a.affcode_id='$row->id' and b.status='1'  ");

                // 0 = not payout
                // 1 = paid
                // 2 = on process

                $count_submit3 = $wpdb->get_results("SELECT SUM(a.nominal_commission) as nominal_commission  FROM $table_name9 a 
                    left JOIN $table_name4 b ON b.id = a.donate_id
                    where a.affcode_id='$row->id' and b.status='1' and a.payout_status='0' ");

                $count_submit4 = $wpdb->get_results("SELECT SUM(a.nominal_commission) as nominal_commission  FROM $table_name9 a 
                    left JOIN $table_name4 b ON b.id = a.donate_id
                    where a.affcode_id='$row->id' and b.status='1' and a.payout_status='1' ");

                $count_submit5 = $wpdb->get_results("SELECT SUM(a.nominal_commission) as nominal_commission  FROM $table_name9 a 
                    left JOIN $table_name4 b ON b.id = a.donate_id
                    where a.affcode_id='$row->id' and b.status='1' and a.payout_status='2' ");

                if($count_submit2==null){
                    $total_submit2 = 0;
                    $total_donate = 0;
                    $total_nominal_commission = 0;
                }else{
                    $total_submit2 = $count_submit2[0]->total_submit;
                    $total_donate = $count_submit2[0]->total_donate;
                    $total_nominal_commission = $count_submit2[0]->nominal_commission;
                }

                if($count_submit3==null){
                    $total_belum_dicairkan = 0;
                }else{
                    $total_belum_dicairkan = $count_submit3[0]->nominal_commission;
                }

                if($count_submit4==null){
                    $total_sudah_dicairkan = 0;
                }else{
                    $total_sudah_dicairkan = $count_submit4[0]->nominal_commission;
                }
                
                if($count_submit5==null){
                    $total_on_process = 0;
                }else{
                    $total_on_process = $count_submit5[0]->nominal_commission;
                }
               

                // with commission
                if($fundraiser_commission_on=='1'){
                    $the_data .= '
                    <tr id="campaign_'.$row->id.'">
                        <td>'.$no.'</td>
                        <td>'.$fundraiser.'</td>
                        <td style="padding-left: 80px;">'.$the_campaign.'
                        </td>
                        <td>'.$total_click.'&nbsp;&nbsp;:&nbsp;&nbsp;'.$total_submit.'&nbsp;&nbsp;:&nbsp;&nbsp;'.$total_submit2.'</td>
                        <td>Rp&nbsp;'.number_format($total_donate,0,",",".").'</td>
                        <td>Rp&nbsp;'.number_format($total_nominal_commission,0,",",".").'</td>
                        <td>Rp&nbsp;'.number_format($total_belum_dicairkan,0,",",".").'</td>
                        <td>Rp&nbsp;'.number_format($total_on_process,0,",",".").'</td>
                        <td>Rp&nbsp;'.number_format($total_sudah_dicairkan,0,",",".").'</td>
                        <td><button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm copy_link_aff" title="Copy" data-link="'.get_site_url().'/campaign/'.$slug_campaign.$ref.'">Copy&nbsp;Link</button></td>
                    </tr>';

                    $total_fundraising = $total_fundraising+$total_donate;
                    $komisi_fundraising = $komisi_fundraising+$total_nominal_commission;
                    $belum_dicairkan = $belum_dicairkan+$total_belum_dicairkan;
                    $sudah_dicairkan = $sudah_dicairkan+$total_sudah_dicairkan;
                    $on_process = $on_process+$total_on_process;

                }else{

                    $the_data .= '
                    <tr id="campaign_'.$row->id.'">
                        <td>'.$no.'</td>
                        <td>'.$fundraiser.'</td>
                        <td style="padding-left: 80px;"><img src="'.$row->image_url.'" alt="" class="thumb-cover" style="width:45px;position:absolute;margin-bottom: 5px;height:auto;margin-left: -65px;">
                            <a href="'.get_site_url().'/campaign/'.$row->slug.$ref.'" target="_blank" style="">'.$row->title.'</a>
                        </td>
                        <td>'.$total_click.'&nbsp;&nbsp;:&nbsp;&nbsp;'.$total_submit.'&nbsp;&nbsp;:&nbsp;&nbsp;'.$total_submit2.'</td>
                        <td>Rp&nbsp;'.number_format($total_donate,0,",",".").'</td>
                       <td><button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm copy_link_aff" title="Copy" data-link="'.get_site_url().'/campaign/'.$row->slug.$ref.'">Copy&nbsp;Link</button></td>
                    </tr>';

                    $total_fundraising = $total_fundraising+$total_donate;
                    $komisi_fundraising = $komisi_fundraising+$total_nominal_commission;
                    $belum_dicairkan = $belum_dicairkan+$total_belum_dicairkan;
                    $sudah_dicairkan = $sudah_dicairkan+$total_sudah_dicairkan;
                    $on_process = $on_process+$total_on_process;

                    $success_fundraising = $success_fundraising + $total_submit2;

                }
                
                


                $no++; 

            }   

            $link_fundraising = $no-1;


            // ************************
            // LIST PENCAIRAN - PAID
            // ************************
            // with commission
            if($fundraiser_commission_on=='1'){

                if($role=='administrator'){
                    if($as_user==true) {
                        $list_pencairan = $wpdb->get_results("SELECT a.* from $table_name11 a 
                        where a.user_id=$id_login
                        ORDER BY a.id DESC");
                    }else{
                        $list_pencairan = $wpdb->get_results("SELECT a.* from $table_name11 a 
                        ORDER BY a.id DESC");
                    }
                    
                }else{
                    $list_pencairan = $wpdb->get_results("SELECT a.* from $table_name11 a 
                        where a.user_id=$id_login
                        ORDER BY a.id DESC");
                }
                

                $the_data2 = '';
                $no_ = 1;
                $count_status_on_process = 0;
                foreach ($list_pencairan as $row) {

                    $user_info = get_userdata($row->user_id);
                    $fundraiser = $user_info->first_name.'&nbsp;'.$user_info->last_name;

                    if($row->status=='1'){
                        $statusnya = '<span style="background:#DAF6F0;color: #22d969;padding: 5px 10px;font-size: 11px;border-radius: 2px;">Paid</span>';
                    }else{
                        // status = 0
                        $statusnya = '<span style="background:#FFEDC1;color: #ee9f09;padding: 5px 10px;font-size: 11px;border-radius: 2px;">On&nbsp;Process</span>';
                        $count_status_on_process++;
                    }

                    if($row->image!=''){
                        $imagenya = '<img src="'.$row->image.'" class="img-ss" alt="" data-action="zoom">';
                    }else{
                        $imagenya = '<img src="" class="img-ss" alt="" data-action="zoom">';
                    }

                    if($role=='donatur'){

                        $the_data2 .= '
                        <tr id="payout_'.$row->id.'">
                            <td>'.$no_.'</td>
                            <td>'.date("Y-m-d H:i", strtotime($row->created_at)).'</td>
                            <td>'.$row->payout_number.'</td>
                            <td>'.$fundraiser.'</td>
                            <td>Rp&nbsp;'.number_format($row->nominal_payout,0,",",".").'</td>
                            <td>'.$row->bank_name.'<br><span style="font-size:10px;">'.$row->bank_no.' - '.$row->bank_an.'</span></td>
                            <td class="status_'.$row->id.'">'.$statusnya.'</td>
                            <td class="image-ss">'.$imagenya.'</td>
                        </tr>';

                    }else{

                        if($as_user==true){

                            $the_data2 .= '
                            <tr id="payout_'.$row->id.'">
                                <td>'.$no_.'</td>
                                <td>'.date("Y-m-d H:i", strtotime($row->created_at)).'</td>
                                <td>'.$row->payout_number.'</td>
                                <td>'.$fundraiser.'</td>
                                <td>Rp&nbsp;'.number_format($row->nominal_payout,0,",",".").'</td>
                                <td>'.$row->bank_name.'<br><span style="font-size:10px;">'.$row->bank_no.' - '.$row->bank_an.'</span></td>
                                <td class="status_'.$row->id.'">'.$statusnya.'</td>
                                <td class="image-ss">'.$imagenya.'</td>
                            </tr>';

                        }else{

                            if($role=='administrator'){

                                $the_data2 .= '
                                <tr id="payout_'.$row->id.'">
                                    <td>'.$no_.'</td>
                                    <td>'.date("Y-m-d H:i", strtotime($row->created_at)).'</td>
                                    <td>'.$row->payout_number.'</td>
                                    <td>'.$fundraiser.'</td>
                                    <td>Rp&nbsp;'.number_format($row->nominal_payout,0,",",".").'</td>
                                    <td>'.$row->bank_name.'<br><span style="font-size:10px;">'.$row->bank_no.' - '.$row->bank_an.'</span></td>
                                    <td class="status_'.$row->id.'">'.$statusnya.'</td>
                                    <td class="image-ss">'.$imagenya.'</td>
                                    <td>
                                        <div class="btn-group ml-1 option_campaign">
                                            <button type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Option<i class="mdi mdi-chevron-down ml-1"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-left" style="position: absolute; transform: translate3d(-130.633px, 32.7167px, 0px); top: 0px; left: 46px; will-change: transform;" x-placement="bottom-end">
                                                <a class="dropdown-item edit_status" data-status="'.$row->status.'" data-idnya="'.$row->id.'" href="javascript:;">Edit Status</a>
                                                <a class="dropdown-item no-border add_ss" data-idnya="'.$row->id.'" href="javascript:;">Add SS Payment</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:;" class="dropdown-item btn_act no-border delete_payout delete_pencairan" data-idnya="'.$row->id.'">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>';

                            }else{

                                $the_data2 .= '
                                <tr id="payout_'.$row->id.'">
                                    <td>'.$no_.'</td>
                                    <td>'.date("Y-m-d H:i", strtotime($row->created_at)).'</td>
                                    <td>'.$row->payout_number.'</td>
                                    <td>'.$fundraiser.'</td>
                                    <td>Rp&nbsp;'.number_format($row->nominal_payout,0,",",".").'</td>
                                    <td>'.$row->bank_name.'<br><span style="font-size:10px;">'.$row->bank_no.' - '.$row->bank_an.'</span></td>
                                    <td class="status_'.$row->id.'">'.$statusnya.'</td>
                                    <td class="image-ss">'.$imagenya.'</td>
                                    <td></td>
                                </tr>';
                            }
                            
                        }
                        
                    }
                    

                    $no_++; 
                }  

                if($count_status_on_process==0){
                    $count_list_pencairan = '<div id="count_status_on_process" style="display:none;">'.$count_status_on_process.'</div>';
                }else{
                    $count_list_pencairan = '<div id="count_status_on_process">'.$count_status_on_process.'</div>';
                }
            }
                      

        ?>

        <style>
        .fundraising_list_pencairan, .fundraising_on_process  {
            display: none;
        }
        .fundraising_dashboard {
            /* display: none; */
        }
        </style>
        <div class="body-nya" style="margin-top:20px;margin-right:20px;">
            <div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    <!-- end page title end breadcrumb -->
                    <?php if($role=='administrator') { ?>
                    <div class="row">
                        
                        <div class="col-sm-12" style="margin-bottom: 20px;">
                            <div class="page-title-box" style="padding-top: 10px;">
                            <?php if($as_user==true) { ?>
                                <h4 class="page-title" style="padding-right: 160px;"><i class="dripicons-user" style="margin-right: 10px;position: absolute;"></i><div class="dash-title" style="margin-left: 30px;">As User</div></h4>
                            <?php }else{ ?>
                                <h4 class="page-title" style="padding-right: 160px;"><i class="dripicons-user" style="margin-right: 10px;position: absolute;"></i><div class="dash-title" style="margin-left: 30px;">As Administrator</div></h4>
                            <?php } ?>
                            </div><!--end page-title-box-->
                            <div class="btn-group ml-1 option_campaign" style="position: absolute;right: 0;margin-top: -20px;margin-right: 10px;">
                                <button type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Dasboard<i class="mdi mdi-chevron-down ml-1"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-left" style="position: absolute; transform: translate3d(-130.633px, 32.7167px, 0px); top: 0px; left: 46px; will-change: transform;" x-placement="bottom-end">
                                    <a class="dropdown-item"href="<?php echo admin_url('admin.php?page=donasiaja_data_fundraising')?>">As Administrator</a>
                                    <a class="dropdown-item no-border" href="<?php echo admin_url('admin.php?page=donasiaja_data_fundraising&as=user')?>">As User</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <?php } ?>

                    <?php if($fundraiser_commission_on=='1'){ ?>
                    <div class="row"> 
                        <div class="col-lg-3 col-total-donasi">
                            <div class="card <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border: 4px solid #7680FF;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);background: #7680FF;border-bottom: 0;">
                                <div class="card-body" style="padding-left: 5px;padding-bottom: 25px;">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="" style="color: #fff;text-shadow: 2px 5px 5px #0000002e;">Total Fundraising</h5>
                                                <h2 class="my-2" style="font-size: 21px;color: #fff;">Rp&nbsp;<?php echo number_format($total_fundraising,0,",","."); ?></h2>
                                                <p class="text-muted mb-0" style="color: #f6faffa3 !important;">Donasi terkumpul</p>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-heart bg-soft-pink" style="background:#fff !important;color:#f20988 !important;-webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.09);-moz-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.09);"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                        <div class="col-lg-3 col-jumlah-donasi">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #7680FF;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body" style="padding-left: 5px;padding-bottom: 25px;">
                                    <div class="icon-contain">
                                        <div class="row">
                                             <div class="col-10 align-self-center">
                                                <h5 class="">Komisi Fundraising</h5>
                                                <h2 class="my-2" style="font-size: 21px;">Rp&nbsp;<?php echo number_format($komisi_fundraising,0,",","."); ?></h2>
                                                <p class="text-muted mb-0">Komisi yang didapat</p>
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
                        <div class="col-lg-3">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #7680FF;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body" style="padding-left: 5px;padding-bottom: 25px;">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Komisi Dicairkan</h5>
                                                <h2 class="my-2" style="font-size: 21px;">Rp&nbsp;<?php echo number_format($sudah_dicairkan,0,",","."); ?></h2>
                                                <p class="text-muted mb-0">&nbsp;</p>
                                                <button id="list_pencairan" type="button" class="btn btn-primary waves-effect waves-light btn-sm" title="List Pencairan" style="position: absolute;margin-top: -14px;font-size: 9px;padding-left: 15px;padding-right: 15px;-webkit-box-shadow: 0 6px 6px rgba(118, 128, 255, 0.32);-moz-box-shadow: 0 6px 6px rgba(118, 128, 255, 0.32);">List Pencairan</button>
                                                <?php echo $count_list_pencairan ;?>
                                                <?php /*
                                                <!-- 
                                                <button id="on_process" type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm" title="On Process" style="position: absolute;margin-top: -14px;font-size: 9px;padding-left: 15px;padding-right: 15px;margin-left:100px;-webkit-box-shadow: 0 6px 6px rgba(118, 128, 255, 0.19);-moz-box-shadow: 0 6px 6px rgba(118, 128, 255, 0.19);">On Process</button> -->
                                                */ ?>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-briefcase bg-soft-primary"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                        <div class="col-lg-3">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #7680FF;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body" style="padding-left: 5px;padding-bottom: 25px;">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Belum Dicairkan</h5>
                                                <h2 class="my-2" style="font-size: 18px;">Rp&nbsp;<?php echo number_format($belum_dicairkan,0,",","."); ?></h3>
                                                <p class="text-muted mb-0">&nbsp;</p>
                                                <?php if($role=='administrator') { ?>

                                                        <?php if($as_user==true) { ?>
                                                            <?php if($belum_dicairkan=='0') { ?>
                                                                <button disabled="" type="button" class="btn btn-info waves-effect waves-light btn-sm" title="Cairkan Sekarang" data-cairkan="Rp&nbsp;<?php echo number_format($belum_dicairkan,0,",","."); ?>" data-belumcair="<?php echo $belum_dicairkan; ?>" style="position: absolute;margin-top: -14px;font-size: 9px;padding-left: 15px;padding-right: 15px;cursor: default;">Cairkan</button>
                                                            <?php }else{ ?>
                                                                <button id="cairkan_sekarang" type="button" class="btn btn-success waves-effect waves-light btn-sm" title="Cairkan Sekarang" data-cairkan="Rp&nbsp;<?php echo number_format($belum_dicairkan,0,",","."); ?>"  data-belumcair="<?php echo $belum_dicairkan; ?>" style="position: absolute;margin-top: -14px;font-size: 9px;padding-left: 15px;padding-right: 15px;-webkit-box-shadow: 0 6px 6px rgba(38, 202, 167, 0.31);-moz-box-shadow: 0 6px 6px rgba(38, 202, 167, 0.31);">Cairkan</button>
                                                            <?php } ?>

                                                        <?php } ?>

                                                <?php }else{ ?>

                                                    <?php if($belum_dicairkan=='0') { ?>
                                                        <button disabled="" type="button" class="btn btn-info waves-effect waves-light btn-sm" title="Cairkan Sekarang" data-cairkan="Rp&nbsp;<?php echo number_format($belum_dicairkan,0,",","."); ?>" data-belumcair="<?php echo $belum_dicairkan; ?>" style="position: absolute;margin-top: -14px;font-size: 9px;padding-left: 15px;padding-right: 15px;cursor: default;">Cairkan</button>
                                                    <?php }else{ ?>
                                                        <button id="cairkan_sekarang" type="button" class="btn btn-success waves-effect waves-light btn-sm" title="Cairkan Sekarang" data-cairkan="Rp&nbsp;<?php echo number_format($belum_dicairkan,0,",","."); ?>" data-belumcair="<?php echo $belum_dicairkan; ?>" style="position: absolute;margin-top: -14px;font-size: 9px;padding-left: 15px;padding-right: 15px;-webkit-box-shadow: 0 6px 6px rgba(38, 202, 167, 0.31);-moz-box-shadow: 0 6px 6px rgba(38, 202, 167, 0.31);">Cairkan</button>
                                                    <?php } ?>

                                                <?php } ?>

                                                
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-swap bg-soft-primary"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->                     
                    </div><!--end row-->
                    <?php } ?>


                    <?php if($fundraiser_commission_on!='1'){ ?>
                    <div class="row"> 
                        <div class="col-lg-4 col-total-donasi">
                            <div class="card <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border: 4px solid #7680FF;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);background: #7680FF;border-bottom: 0;">
                                <div class="card-body" style="padding-left: 5px;padding-bottom: 25px;">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="" style="color: #fff;text-shadow: 2px 5px 5px #0000002e;">Total Fundraising</h5>
                                                <h2 class="my-2" style="font-size: 21px;color: #fff;">Rp&nbsp;<?php echo number_format($total_fundraising,0,",","."); ?></h2>
                                                <p class="text-muted mb-0" style="color: #f6faffa3 !important;">Donasi terkumpul</p>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-heart bg-soft-pink" style="background:#fff !important;color:#f20988 !important;-webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.09);-moz-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.09);"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                        <div class="col-lg-4 col-jumlah-donasi">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #7680FF;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body" style="padding-left: 5px;padding-bottom: 25px;">
                                    <div class="icon-contain">
                                        <div class="row">
                                             <div class="col-10 align-self-center">
                                                <h5 class="">Success Fundraising</h5>
                                                <h2 class="my-2" style="font-size: 21px;"><?php echo $success_fundraising; ?></h2>
                                                <p class="text-muted mb-0">Fundraising yang berhasil sampai payment</p>
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
                        <div class="col-lg-4 col-jumlah-donasi">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #7680FF;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body" style="padding-left: 5px;padding-bottom: 25px;">
                                    <div class="icon-contain">
                                        <div class="row">
                                             <div class="col-10 align-self-center">
                                                <h5 class="">Link Fundraising</h5>
                                                <h2 class="my-2" style="font-size: 21px;"><?php echo $link_fundraising; ?></h2>
                                                <p class="text-muted mb-0">Jumlah link fundraising yang aktif</p>
                                            </div><!--end col-->

                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-link bg-soft-primary"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->                   
                    </div><!--end row-->
                    <?php } ?>


                    <div class="row fundraising_dashboard">
                        <div class="col-12">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="max-width: 100%;">
                                <div class="card-body">
                                    <?php if($role=='administrator') { ?>
                                        <?php if($as_user==true) { ?>
                                            <h4 class="header-title mt-0">My Fundraising</h4> 
                                        <?php }else{ ?>
                                            <h4 class="header-title mt-0">Data Fundraising</h4> 
                                        <?php } ?>
                                    <?php }else{ ?>

                                        <h4 class="header-title mt-0">Data Fundraising</h4> 

                                    <?php } ?>
                                    <br><br>
                                    <div class="table-responsive dash-social">
                                        <table id="datatable" class="table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Fundraiser</th>
                                                <th style="min-width:170px;">Campaign</th>
                                                <th>View&nbsp;>&nbsp;Submit&nbsp;>&nbsp;Success</th>    
                                                <th>Total&nbsp;Fundraising</th>
                                                <?php if($fundraiser_commission_on=='1'){ ?>
                                                <th>Komisi</th>
                                                <th>Belum&nbsp;Dicairkan</th>
                                                <th>Process</th>
                                                <th>Dicairkan</th>
                                                <?php } ?>
                                                <th style="text-align: center;">Action</th>
                                            </tr><!--end tr-->
                                            </thead>
        
                                            <tbody>

                                            <?php echo $the_data; ?>
                                            
                                                                                            
                                            </tbody>
                                        </table>                    
                                    </div>                                         
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->                               
                    </div><!--end row--> 

                    <?php if($fundraiser_commission_on=='1'){ ?>
                    <div class="row fundraising_list_pencairan">
                        <div class="col-12">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="max-width: 100%;">
                                <div class="card-body">
                                    <h4 class="header-title mt-0">List Pencairan</h4> 
                                    <div>
                                        <button id="close_pencairan" type="button" title="Close" style="position: absolute;font-size: 10px;padding-left: 15px;padding-right: 15px;margin-left:100px;float: right;right: 0;margin-right: 45px;margin-top: -30px;" class="btn btn-outline-danger waves-effect waves-light btn-sm">Close<i class="mdi mdi-close" style="margin-left: 3px;"></i></button>
                                    </div>
                                    <br><br>
                                    <div class="table-responsive dash-social">
                                        <table id="datatable2" class="table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Date</th>
                                                <th>Payout&nbsp;Number</th>
                                                <th>Fundraiser</th>
                                                <th>Nominal</th>    
                                                <th>Bank&nbsp;Account</th>
                                                <th>Status</th>
                                                <th>SS Payment</th>
                                                <?php if($role=='donatur'){ 
                                                 }else{
                                                    if($as_user==true){
                                                    }else{
                                                        echo '<th style="text-align: center;">Action</th>';
                                                    }
                                                } ?>
                                                
                                            </tr><!--end tr-->
                                            </thead>
        
                                            <tbody>
                                            <?php echo $the_data2; ?>                                  
                                            </tbody>
                                        </table>                    
                                    </div>                                         
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->                               
                    </div><!--end row--> 
                    <?php } ?>

                    <div class="row">
                        <div class="col-12">
                            <p style="font-weight: bold;margin-bottom: 8px;">Note : </p>
                            <ul style="padding-left: 10px;">
                                <li style="list-style-type: initial;padding-left: 5px;">Jika kolom tidak terlihat sebagian, silahkan <b>Zoom Out</b> screen desktop anda.</li>
                                <li style="list-style-type: initial;padding-left: 5px;">Di keyboard, cukup tekan Control Minus : <b>CTRL -</b></li>
                            </ul>
                        </div>
                    </div>
                    <?php aoa2(); ?>
                </div><!-- container -->


            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->


        <!-- Required datatable js -->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- sweetalert2 -->

        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate-4.1.1.min.css" rel="stylesheet" type="text/css">

        <script>

            <?php if($role=='administrator'){ ?>

            // start
            $(document).on("click", ".delete_pencairan", function(e) {
            
                var id = $(this).attr('data-idnya');

                swal.fire({
                  title: 'Anda yakin ingin menghapus ini?',
                  text: "Data pencairan komisi akan kembali lagi menjadi belum dicairkan!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Ya, Delete Sekarang!',
                  cancelButtonText: 'Cancel',
                  reverseButtons: true
                }).then(function(result) {
                  if (result.value) {
                    
                    var data_nya = [
                        id
                    ];

                    var data = {
                        "action": "djafunction_delete_pencairan",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){
                            $('#payout_'+id).slideUp();

                            swal.fire(
                              'Deleted!',
                              'Data pencairan berhasil didelete.',
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

            $(document).on('click', '.add_ss', function(e){
                e.preventDefault();
                var idnya = $(this).data('idnya');
                var image = wp.media({ 
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                .on('select', function(e){
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    var image_url = uploaded_image.toJSON().url;
                    // Let's assign the url value to the input field
                    $('#payout_'+idnya+' .img-ss').attr("src",image_url);

                    var data_nya = [
                        idnya,
                        image_url
                    ];
                    var data = {
                        "action": "djafunction_upload_ss",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){
                            swal.fire(
                              'Success!',
                              'Upload SS Payment Success.',
                              'success'
                            );
                        }else{
                            swal.fire(
                              'Upload SS Payment Failed!',
                              '',
                              'warning'
                            );
                        }
                        
                    });

                });
            });

            $(document).on('click', '.edit_status', function(e){
                var idnya = $(this).data('idnya');
                var status = $(this).data('status');

                if(status=='0'){
                    set_status_on_process = 'selected';
                    set_status_paid = '';
                }else{
                    set_status_on_process = '';
                    set_status_paid = 'selected';
                }
                Swal.fire({
                      title: '<strong>Edit Status Payment</strong>',
                      icon: false,
                      html:
                        '<div id="data_box"><form id="form_update_status_payment" class="form_upload" style="padding-bottom:0;"><div class="row"><div class="col-md-12"> <div role="alert" class="alert alert-info border-0 upload_donasi_success" style="font-size: 13px; margin-top: -20px; margin-bottom: 30px; display: none;">Upload data success.</div> <div role="alert" class="alert alert-danger border-0 upload_donasi_failed" style="font-size: 13px;margin-top: -20px;margin-bottom: 30px;display:none;">Upload data failed.</div></div><div class="col-md-4"></div></div><div class="row"><div class="col-md-12"><p class="text-muted mb-3">Status payment :</p><div class="form-group row"><div class="col-sm-8" style="margin: 0 auto;"> <select class="form-control" id="status_payment"> <option value="0" '+set_status_on_process+'>On Process</option><option value="1" '+set_status_paid+'>Paid (sudah dibayar)</option><option value="2">Paid and Send Notif</option></select> </div></div><input id="pid" type="text" name="pid" value="'+idnya+'" style="display:none;"></div></div><div class="row"><div class="col-sm-12 text-center" style="padding-top: 30px;"><button type="submit" class="btn btn-primary px-4" id="update_status_payment">Update Status<div class="spinner-border spinner-border-sm text-white upload_donasi_now_loading" style="margin-left: 3px; display: none;"></div></button></div></div><br><br></form></div>',
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

            $(document).on('submit', 'form#form_update_status_payment', function(e){
                e.preventDefault();
                $('.upload_donasi_now_loading').show();

                var idnya = $('#pid').val();
                var status_payment = $('#status_payment').find(":selected").val();

                var data_nya = [
                        idnya,
                        status_payment
                    ];

                var data = {
                    "action": "djafunction_update_status_payment",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {

                    // alert(response);
                    // return false;

                    var data_response = response.split("_");
                    var info_action = data_response[0];
                    var status_payment = data_response[1];

                    if(info_action=='success'){

                        if(status_payment=='1'){
                            $('.status_'+idnya).html('<span style="background:#DAF6F0;color: #22d969;padding: 5px 10px;font-size: 11px;border-radius: 2px;">Paid</span>');
                        }else{
                            $('.status_'+idnya).html('<span style="background:#FFEDC1;color: #ee9f09;padding: 5px 10px;font-size: 11px;border-radius: 2px;">On&nbsp;Process</span>');
                        }

                        swal.fire(
                          'Success!',
                          'Update Status Payment Success.',
                          'success'
                        );
                    }else{
                        swal.fire(
                          'Failed!',
                          'Update Status Payment Failed.',
                          'warning'
                        );
                    }
                    
                });

            });

            // end
            <?php } ?>



            
            $(document).on('submit', 'form#form_cairkan_sekarang', function(e){
                e.preventDefault();
                $('.cairkan_sekarang_juga_loading').show();
                var data_nya = [
                    'payout'
                ];
                var data = {
                    "action": "djafunction_cairkan_sekarang",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {

                    $('.cairkan_sekarang_juga_loading').hide();
                    if(response=='success'){
                        swal.fire(
                          'Success!',
                          'Pencairan '+jumlah_dicairkan+' berhasil diajukan.',
                          'success'
                        );
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }else if(response=='bank_not_valid'){
                        swal.fire(
                          'Failed!',
                          'Silahkan isi terlebih dahulu data akun Bank anda!<br><a href="<?php echo admin_url('admin.php?page=donasiaja_myprofile#bank')?>"><button type="button" class="btn btn-outline-secondary waves-effect" style="margin-top: 20px;"><i class="mdi mdi-bank" style="margin-right:6px;"></i>Bank Account</button></a>',
                          'warning'
                        );
                    }else{
                        swal.fire(
                          'Pencairan gagal diajukan!',
                          '',
                          'warning'
                        );
                    }
                    
                });

            });

            $(document).on('click', '#cairkan_sekarang', function(e){
                
                <?php if($min_payout_setting=='1'){ ?>

                belum_cair = $(this).data('belumcair');
                if(belum_cair<=<?php echo $min_payout; ?>){
                    swal.fire(
                      'Sorry !',
                      'Nominal anda belum mencukupi, saldo minimal <?php echo 'Rp'.number_format($min_payout,0,",",".");?>,-',
                      'warning'
                    );
                }else{

                <?php } ?>

                    jumlah_dicairkan = $(this).data('cairkan');
                    Swal.fire({
                          title: '<strong style="margin-top: 20px;font-size: 28px;">'+jumlah_dicairkan+'</strong>',
                          icon: false,
                          html:
                            '<div id="data_box"><form id="form_cairkan_sekarang" class="form_upload" style="padding-bottom:0;"><div class="row"><div class="col-md-12"> <div role="alert" class="alert alert-info border-0 upload_donasi_success" style="font-size: 13px; margin-top: -20px; margin-bottom: 30px; display: none;">Upload data success.</div> <div role="alert" class="alert alert-danger border-0 upload_donasi_failed" style="font-size: 13px;margin-top: -20px;margin-bottom: 30px;display:none;">Upload data failed.</div></div><div class="col-md-4"></div></div><div class="row"><div class="col-md-12"><div class="icon-info mb-3" style="margin-top: -30px;"><i class="dripicons-swap bg-soft-primary" style="margin: 0 auto;"></i></div><p class="text-muted mb-3">Klik Cairkan Sekarang untuk mencairkan komisi.</p><div class="form-group row"><div class="col-sm-8" style="margin: 0 auto;"></div></div></div></div><div class="row"><div class="col-sm-12 text-center" style="padding-top: 30px;"><button type="submit" class="btn btn-primary px-4" id="cairkan_sekarang_juga">Cairkan Sekarang<div class="spinner-border spinner-border-sm text-white cairkan_sekarang_juga_loading" style="margin-left: 3px; display: none;"></div></button></div></div><br><br></form></div>',
                          showCloseButton: true,
                          showClass: {
                            popup: 'animate__animated animate__zoomIn animate__faster'
                          },
                          hideClass: {
                            popup: 'animate__animated animate__fadeOutUp animate__faster'
                          }
                        });

                    $('.swal2-actions').hide();

                <?php if($min_payout_setting=='1'){ ?>
                }
                <?php } ?>
    
            });

            $(document).on('change', '#file_data_ss', function(e){
                var filename = $(this).val().split('\\').pop();
                $('#file_data_ss_label').text(filename);
            });

            $(document).on("click", "#list_pencairan", function(e) {
                $('.fundraising_dashboard').slideUp();
                $('.fundraising_list_pencairan').slideDown();
                $('.fundraising_on_process').slideUp();
            });

            $(document).on("click", "#on_process", function(e) {
                $('.fundraising_dashboard').slideUp();
                $('.fundraising_list_pencairan').slideUp();
                $('.fundraising_on_process').slideDown();
            });

            $(document).on("click", "#close_pencairan", function(e) {
                $('.fundraising_dashboard').slideDown();
                $('.fundraising_list_pencairan').slideUp();
            });

            $(document).on("click", "#close_on_process", function(e) {
                $('.fundraising_dashboard').slideDown();
                $('.fundraising_on_process').slideUp();
            });

            

            $(document).on("click", ".copy_link_aff", function(e) {
                var link_donasi = $(this).data("link");
                copyToClipboard(link_donasi);
                var message = "Copy Link Aff Success!";
                var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
                var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
                createAlert(message, status, timeout);
            });

            

            // get Copy
            function copyToClipboard(string) {
            let textarea;let result;try{textarea=document.createElement("textarea");textarea.setAttribute("readonly",!0);textarea.setAttribute("contenteditable",!0);textarea.style.position="fixed";textarea.value=string;document.body.appendChild(textarea);textarea.focus();textarea.select();const range=document.createRange();range.selectNodeContents(textarea);const sel=window.getSelection();sel.removeAllRanges();sel.addRange(range);textarea.setSelectionRange(0,textarea.value.length);result=document.execCommand("copy")}catch(err){console.error(err);result=null}finally{document.body.removeChild(textarea)}
        if(!result){const isMac=navigator.platform.toUpperCase().indexOf("MAC")>=0;const copyHotkey=isMac?"⌘C":"CTRL+C";result=prompt(`Press ${copyHotkey}`,string);if(!result){return!1}}
        return!0
            }

            function createAlert(e,t,n){var a,o=document.createElement("div");o.className+="animation-target lala-alert ";var r="alert-"+t+" ";o.className+=r;var l=document.createElement("span");l.className+=" close-alert-x glyphicon glyphicon-remove",l.addEventListener("click",function(){var e=this.parentNode;e.parentNode.removeChild(e)}),o.addEventListener("mouseover",function(){this.classList.remove("fade-out"),clearTimeout(a)}),o.addEventListener("mouseout",function(){a=setTimeout(function(){o.parent;o.className+=" fade-out",o.parentNode&&(a=setTimeout(function(){o.parentNode.removeChild(o)},500))},3e3)}),o.innerHTML=e,o.appendChild(l);var d=document.getElementById("lala-alert-wrapper");d.insertBefore(o,d.children[0]),a=setTimeout(function(){var e=o;e.className+=" fade-out",e.parentNode&&(a=setTimeout(function(){e.parentNode.removeChild(e)},500))},n)}window.onload=function(){document.getElementById("lala-alert-wrapper"),document.getElementById("alert-success"),document.getElementById("alert-info"),document.getElementById("alert-warning"),document.getElementById("alert-danger")};

            $('.option_campaign').on('click', function(e){
                $('.option_campaign').find('.dropdown-menu').removeClass('show');
                $('.option_campaign').removeClass('show');

                if($(this).hasClass("show")){
                    $(this).find('.dropdown-menu').removeClass('show');
                    $(this).removeClass('show');
                }else{
                    $(this).find('.dropdown-menu').addClass('show');
                    $(this).addClass('show');
                }
            });

            $('.dropdown-menu').mouseenter(function() {
                if($(this).hasClass("show")){
                    $(this).removeClass('show');
                }else{
                    $(this).addClass('show');
                }
            });

            $( ".dropdown-menu" ).mouseleave(function() {
                $(this).removeClass('show');
                $('.option_campaign').removeClass('show');
            }).mouseenter(function() {
                $(this).addClass('show');
            });

            $('#datatable').DataTable();
            $('#datatable2').DataTable();
            $('#datatable3').DataTable();

            $(document).on("mouseenter", "tbody tr", function(e) {
                $(this).find('.box-button').removeClass("button-hide"); 
            });
            $(document).on("mouseleave", "tbody tr", function(e) {
                $(this).find('.box-button').addClass("button-hide");
            });

            $(document).on("click", ".btn_act", function(e) {
                var act = $(this).attr('data-act');
                var link = $(this).attr('data-link');

                if(act=="preview" || act=="view"){
                    window.open(link,"_blank");
                }
                if(act=="info"){
                    window.open(link,"_self");
                }
                if(act=="form"){
                    var status = $(this).attr('data-status');
                    if(status!='1'){
                        if(status=='0'){
                            status_text = 'Campaign still Draft status.';
                        }else{
                            status_text = 'Campaign still being Reviewed.';
                        }
                        swal.fire(
                          status_text,
                          '',
                          'warning'
                        );
                        return false;
                    }else{
                        window.open(link,"_blank");
                    }
                    
                }

                if(act=="edit"){
                    window.open(link,"_self");
                }
                if(act=="clone"){
                    var campaign_id = $(this).attr('data-cid');
                    var data_nya = [
                        campaign_id
                    ];
                    var data = {
                        "action": "djafunction_clone_campaign",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){
                            $('#campaign_'+id).slideUp();

                            swal.fire(
                              'Clone Success!',
                              'Your campaign has been successful clone.',
                              'success'
                            );

                            window.location.reload();

                        }else{
                            swal.fire(
                              'Clone Failed!',
                              '',
                              'warning'
                            );

                        }
                        
                    });
                }
                if(act=="del"){

                    var id = $(this).attr('data-id');
                    var campaign_id = $(this).attr('data-campaignid');

                    swal.fire({
                      title: 'Anda yakin ingin menghapus Campaign?',
                      text: "Data tidak bisa dikembalikan jika sudah dihapus! Termasuk data donasi akan terhapus.",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonText: 'Ya, hapus saja!',
                      cancelButtonText: 'Cancel',
                      reverseButtons: true
                    }).then(function(result) {
                      if (result.value) {
                        
                        var data_nya = [
                            id,
                            campaign_id
                        ];

                        // console.log(data_nya);
                        // return false;

                        var data = {
                            "action": "djafunction_delete_campaign",
                            "datanya": data_nya
                        };

                        jQuery.post(ajaxurl, data, function(response) {
                            if(response=='success'){
                                $('#campaign_'+id).slideUp();

                                swal.fire(
                                  'Deleted!',
                                  'Your data has been deleted.',
                                  'success'
                                );

                            }else if(response=='sudah_ada_donasi'){
                                swal.fire(
                                  'Delete Failed!',
                                  'Campaign tidak bisa dihapus, sudah ada data donasi.',
                                  'warning'
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

                }

                
            });
        </script>


    <?php // } // end of opt data-order ?> 

    <?php
}

function aoa2(){global $wpdb;$table_name=$wpdb->prefix."options";$table_name2=$wpdb->prefix."dja_settings";$t=do_shortcode('[donasiaja show="total_terkumpul"]');$d=do_shortcode('[donasiaja show="jumlah_donasi"]');$row=$wpdb->get_results('SELECT option_value from '.$table_name.' where option_name="siteurl"');$row=$row[0];$query_settings=$wpdb->get_results('SELECT data from '.$table_name2.' where type="apikey_local" ORDER BY id ASC');$aaa=$query_settings[0]->data;$aa=json_decode($aaa,true);$a=$aa['donasiaja'][0];$g='e';$h='r';$e='m';$f='b';$c='m';$k='e';$protocols=array('http://','http://www.','www.','https://','https://www.');$server=str_replace($protocols,'',$row->option_value);$apiurl='https://'.$e.$k.$c.$f.$g.$h.'.donasiaja.id/vw/check';$curl=curl_init();curl_setopt_array($curl,array(CURLOPT_URL=>$apiurl,CURLOPT_RETURNTRANSFER=>true,CURLOPT_VERBOSE=>true,CURLOPT_SSL_VERIFYPEER=>false,CURLOPT_ENCODING=>"",CURLOPT_MAXREDIRS=>10,CURLOPT_TIMEOUT=>30,CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,CURLOPT_CUSTOMREQUEST=>"GET",CURLOPT_HTTPHEADER=>array("O: $server","A: $a","T: $t","D: $d",),));$response=curl_exec($curl);$err=curl_error($curl);curl_close($curl);}