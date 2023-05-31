<?php

function donasiaja_data_campaign() { ?>
    <?php 
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_category";
    $table_name3 = $wpdb->prefix . "dja_campaign_update";
    $table_name4 = $wpdb->prefix . "dja_donate";
    $table_name5 = $wpdb->prefix . "dja_settings";
    $table_name6 = $wpdb->prefix . "dja_users";
    $table_name7 = $wpdb->prefix . "dja_payment_list";
    $table_name8 = $wpdb->prefix . "users";

    // update type
    $check_row = $wpdb->get_var("SHOW COLUMNS FROM $table_name LIKE 'fundraiser_commission_percent'");
    if($check_row!=null){
        $wpdb->get_var("ALTER TABLE $table_name CHANGE `fundraiser_commission_percent` `fundraiser_commission_percent` DECIMAL(10,2) NULL DEFAULT NULL");
    }

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
        if($_GET['action']=="create"){
            $info_update = false;
            $edit = false;
            $create = true;
            $cs_statisctic = false;
        }elseif($_GET['action']=="edit"){
            // check the campaign is exist
            $check = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$_GET['id'].'"');
            if($check==null){
                $info_update = false;
                $edit = false;
                $create = false;
                $cs_statisctic = false;
            }else{

                if($role=='donatur'){
                    // check user id sama tidak dengan user_id si campaign = ngecek campaignya punya dia tau gak
                    if($id_login!=$check[0]->user_id){
                        $info_update = false;
                        $edit = false;
                        $create = false;
                        $cs_statisctic = false;
                    }else{
                        $info_update = false;
                        $edit = true;
                        $create = false;
                        $cs_statisctic = false;
                    }
                }else{
                    // admin bisa liat semua campaign dan update
                    $info_update = false;
                    $edit = true;
                    $create = false;
                    $cs_statisctic = false;
                }
            }
            
        }elseif($_GET['action']=="info_update"){
            // check the campaign is exist
            $check = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$_GET['id'].'"');
            if($check==null){
                $info_update = false;
                $edit = false;
                $create = false;
                $cs_statisctic = false;
            }else{

                if($role=='donatur'){
                    // check user id sama tidak dengan user_id si campaign = ngecek campaignya punya dia tau gak
                    if($id_login!=$check[0]->user_id){
                        $info_update = false;
                        $edit = false;
                        $create = false;
                        $cs_statisctic = false;
                    }else{
                        $info_update = true;
                        $edit = false;
                        $create = false;
                        $cs_statisctic = false;
                    }
                }else{
                    // admin bisa liat semua campaign dan update
                    $info_update = true;
                    $edit = false;
                    $create = false;
                    $cs_statisctic = false;
                }
            }
            
        }elseif($_GET['action']=="cs_statisctic"){
            // check the campaign is exist
            $check = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$_GET['id'].'"');
            if($check==null){
                $info_update = false;
                $edit = false;
                $create = false;
                $cs_statisctic = false;
            }else{

                if($role=='administrator'){
                    // hanya admin bisa liat semua cs statistic campaign
                    $info_update = false;
                    $edit = false;
                    $create = false;
                    $cs_statisctic = true;
                }else{
                    // selain itu gak bisa
                    $info_update = false;
                    $edit = false;
                    $create = false;
                    $cs_statisctic = false;
                }
            }
            
        }else{
            $info_update = false;
            $edit = false;
            $create = false;
            $cs_statisctic = false;
        }
    }else{
        $info_update = false;
        $edit = false;
        $create = false;
        $cs_statisctic = false;
    }

    // category
    $row2 = $wpdb->get_results('SELECT * from '.$table_name2.' ');     

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="form_setting" or type="app_name" or type="page_donate" ORDER BY id ASC');
    $form_setting = $query_settings[0]->data;
    $app_name = $query_settings[1]->data;
    $page_donate  = $query_settings[2]->data;

    
    
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
    }
    .swal2-cancel.swal2-styled, .swal2-confirm.swal2-styled {
        height: 39px;
        font-size: 13px !important;
    }
    .swal2-popup {
        padding: 3em 2.1em !important;
    }
    #box_cs {
        margin-bottom: 10px;
    }
    .container_cs_box {
        margin-bottom: 0;
    }
    .container_cs {
        padding-right:0;
    }
    .container_priority {
        
    }
    .container_persen {
        padding-top: 10px;
        font-size: 10px;
    }
    .container_btn_del {

    }
    body {
        background: #f6faff;
    }
    .form-group label {
        /* cursor: default; */
    }
    .update-nag {
        display: none;
    }
    .title-on-custom {
        color: #303e67;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.2;
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
        background: #1CB65D;
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
        color: #505DFF;
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
    input.form-control.packaged_title, input.form-control.pengeluaran_title {
        font-size: 13px;
    }
    .mce-menubar, .mce-branding {
        display: none;
    }
    #cover_image img {
        border-radius: 4px;
    }
    .fro-profile_main-pic-change {
        background-color: #7680ff99;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        -webkit-box-shadow: 0px 0px 20px 0px rgba(252, 252, 253, 0.05);
        box-shadow: 0px 0px 20px 0px rgba(252, 252, 253, 0.05);
        transition: all .35s ease;
        position: absolute;
        right: 0;
    }
    #upload_cover_image {
        text-align: center;
        padding-top: 3px;
        cursor: pointer;
        margin-right: 20px;
        margin-top: 10px;
    }

    .fro-profile_main-pic-change:hover {
        background-color: #505DFF;
    }
    .fro-profile_main-pic-change i {
        color: #fff;
        font-size: 11px;
    }

    .form-group input {
        height: 45px;
        padding:0 15px;
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
    .dropdown-item:hover {
        /* background: #f6faff !important; */
        color: #505DFF !important;
    }

    .dropdown-item.delete_campaign:hover {
        /* background: #F05860 !important; */
        color: #F05860 !important;
    }

    .show_data_donasi:hover img {
        -webkit-box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        transition: border .2s linear, transform .2s linear, background-color .2s linear, box-shadow .2s linear, opacity .2s linear, -webkit-transform .2s linear, -webkit-box-shadow .2s linear;
        -webkit-transition: all 200ms ease-in;
        -webkit-transform: scale(1.02);
        -ms-transition: all 200ms ease-in;
        -ms-transform: scale(1.02);   
        -moz-transition: all 200ms ease-in;
        -moz-transform: scale(1.02);
        transition: all 200ms ease-in;
        transform: scale(1.02);
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


    /*  Toggle Switch  */

    .toggleSwitch span span {
        display: none;
    }  
      
    .toggleSwitch {
        display: inline-block;
        height: 18px;
        position: relative;
        overflow: visible;
        padding: 0;
        cursor: pointer;
        width: 100%;
        background-color: #fff;
        border: 1px solid #c0c5d7;
        border-radius: 6px;
        height: 34px;
    }
    .toggleSwitch * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .toggleSwitch label,
    .toggleSwitch > span {
        line-height: 20px;
        height: 20px;
        vertical-align: middle;
    }
    .toggleSwitch input:focus ~ a,
    .toggleSwitch input:focus + label {
        outline: none;
    }
    .toggleSwitch label {
        position: relative;
        z-index: 3;
        display: block;
        width: 100%;
    }
    .toggleSwitch input {
        position: absolute;
        opacity: 0;
        z-index: 5;
    }
    .toggleSwitch > span {
        position: absolute;
        left: 0;
        width: calc(100% - 6px);
        margin: 0;
        text-align: left;
        white-space: nowrap;
      margin:0 3px;
    }
    .toggleSwitch > span span {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 5;
        display: block;
        width: 50%;
        margin-left: 50px;
        text-align: left;
        font-size: 0.9em;
        width: auto;
        left: 0;
        top: -1px;
        opacity: 1;
        width:40%;
        text-align: center;
      line-height:34px;
    }
    .toggleSwitch a {
        position: absolute;
        right: 50%;
        z-index: 4;
        display: block;
        top: 3px;
        bottom: 3px;
        padding: 0;
        left: 3px;
        width: 50%;
        background-color: #7680FF;
        border-radius: 4px;
        -webkit-transition: all 0.2s ease-out;
        -moz-transition: all 0.2s ease-out;
        transition: all 0.2s ease-out;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }
    .toggleSwitch > span span:first-of-type {
        color: #FFF;
        opacity: 1;
        left: 0;
        margin: 0;
        width: 50%;
    }
    .toggleSwitch > span span:last-of-type {
        left:auto;
        right:0;
        color: #656d9a;
        margin: 0;
        width: 50%;
    }
    .toggleSwitch > span:before {
        content: '';
        display: block;
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: -2px;
        /* background-color: #fafafa;
        border: 1px solid #ccc; */
        border-radius: 30px;
        -webkit-transition: all 0.2s ease-out;
        -moz-transition: all 0.2s ease-out;
        transition: all 0.2s ease-out;
    }
    .toggleSwitch input:checked ~ a {
        left: calc(50% - 3px);
    }
    .toggleSwitch input:checked ~ span:before {
        /* border-color: #0097D1;
        box-shadow: inset 0 0 0 30px #0097D1; */
    }
    .toggleSwitch input:checked ~ span span:first-of-type {
        left:0;
        color:#656d9a;
    }
    .toggleSwitch input:checked ~ span span:last-of-type {
        /* opacity: 1;
        color: #fff;     */
        color:#FFF;
    }
    /* Switch Sizes */
    .toggleSwitch.large {
        width: 60px;
        height: 27px;
    }
    .toggleSwitch.large a {
        width: 27px;
    }
    .toggleSwitch.large > span {
        height: 29px;
        line-height: 28px;
    }
    .toggleSwitch.large input:checked ~ a {
        left: 41px;
    }
    .toggleSwitch.large > span span {
        font-size: 1.1em;
    }
    .toggleSwitch.large > span span:first-of-type {
        left: 50%;
    }
    .toggleSwitch.xlarge {
        width: 80px;
        height: 36px;
    }
    .toggleSwitch.xlarge a {
        width: 36px;
    }
    .toggleSwitch.xlarge > span {
        height: 38px;
        line-height: 37px;
    }
    .toggleSwitch.xlarge input:checked ~ a {
        left: 52px;
    }
    .toggleSwitch.xlarge > span span {
        font-size: 1.4em;
    }
    .toggleSwitch.xlarge > span span:first-of-type {
        left: 50%;
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

    .custom-control-input:checked ~ .custom-control-label::before {
        color: #fff;
        border-color: #36bd47 !important;
        background-color: #36bd47 !important;
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

    .custom-control-label.zakat_setting_label::before {
      border: #36BD47 solid 1px;
      background: #d5ffda;
    }
    .custom-switch .custom-control-label.zakat_setting_label::after {
        background-color: #36BD47;
    }
    .hide_zakat_percent {
        display: none !important;
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

    @media only screen and (max-width:767px) {
        .page-title-box .breadcrumb {
            display: inline-flex !important;
            width: 100% !important;
        }
    }

    @media only screen and (max-width:480px) {
      .card-body {
        padding: 1rem 0.4rem 1rem 0.4rem;
      }
      .container_cs {
        padding-right: 10px;
      }
      #box_additional_field .form-group, #box_additional_formula .form-group {
          padding-bottom: 0;
          margin-bottom: 8px;
      }
      #box_additional_formula .btn.btn-danger.del_field {
        position: absolute;
        margin-top: -48px !important;
        right: 0;
        margin-right: 10px;
      }
      #box_additional_field .btn.btn-danger.del_field {
        float: right;
      }
      #box_additional_formula .form-group .text_field {
        width: 75% !important;
      }
      .container_persen {
        width: 50%;
        text-align: left;
      }
      .del_field_cs {
        position: absolute;
        margin-top: -40px !important;
        right: 10px;
      }
      .btn.btn-danger.del_field {
          margin-bottom: 15px;
      }
      .seringnya {
        padding-top: 0px !important;
        padding-bottom: 20px;
      }
      .nominalnya, .labelnya {
        padding-bottom: 0 !important;
        margin-bottom: -10px !important;
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

    <?php if($info_update==true){ ?>

        <?php check_verified_campaign($akses); ?>

        <?php 

        $campaign_id = $_GET['id'];
        $row = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'"')[0];

        $info_updatenya = $wpdb->get_results('SELECT * from '.$table_name3.' where campaign_id="'.$campaign_id.'" ORDER BY id DESC');

        if(isset($_GET['infoid'])){
            $infoid = $_GET['infoid'];
            if($role=='donatur'){
                $detail_update = $wpdb->get_results('SELECT * from '.$table_name3.' where campaign_id="'.$campaign_id.'" and id="'.$infoid.'" and user_id="'.$id_login.'" ');
            }else{
                $detail_update = $wpdb->get_results('SELECT * from '.$table_name3.' where campaign_id="'.$campaign_id.'" and id="'.$infoid.'"');
            }
            if($detail_update!=null){
                $title = $detail_update[0]->title;
                $campaignid = $detail_update[0]->campaign_id;
                $infoid = $detail_update[0]->id;
                $infonya = $detail_update[0]->information;
                $lanjut_update = true;
            }else{
                $lanjut_update = false;
            }
        }else{
            $title = '';
            $campaignid = '';
            $infoid = '';
            $infonya = '';
        }

        $infonya = str_replace("'", "&#39;", $infonya); // petik 1
        $infonya = str_replace('../wp-content', home_url().'/wp-content', $infonya);

        ?>
        <!-- css -->
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate.css" rel="stylesheet" type="text/css">

        <!--Wysiwig js-->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/donasiaja-admin.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/tinymce/tinymce.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script type="text/javascript">

        $(document).on("click", ".f_edit", function(e) {
            var link = $(this).attr('data-link');
            window.open(link,"_self");
        });

        $(document).on("click", "#view_info", function(e) {
            var link = $(this).attr('data-link');
            window.open(link,"_blank");
        });


        jQuery(document).ready(function($){

            if($("#information").length > 0){

                try {
                tinymce.init({
                      selector: "textarea#information",
                      theme: "modern",
                      height:300,
                      plugins: [
                          "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                          "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                          "save table contextmenu directionality template paste textcolor"
                      ],
                      toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignjustify | bullist numlist | link addimage | print preview media fullpage | forecolor",
                      style_formats: [
                          {title: 'Header', block: 'h2', styles: {color: '#23374d'}},
                          {title: 'Bold text', inline: 'b', styles: {color: '#23374d'}},
                          {title: 'Paragraph', inline: 'p', styles: {color: '#23374d'}},
                          {title: 'Span', inline: 'span', styles: {color: '#23374d'}},
                      ],
                      init_instance_callback:function(editor){
                        editor.setContent('<?php echo trim(preg_replace('/\s+/', ' ', $infonya)); ?>');
                      },
                      setup: function(editor) {
                          
                        function addImage(){
                            var image = wp.media({ 
                                title: 'Upload Image',
                                multiple: false
                            }).open()
                            .on('select', function(e){
                                var uploaded_image = image.state().get('selection').first();
                                var image_url = uploaded_image.toJSON().url;

                                new_image_url = image_url;

                                $.get(new_image_url)
                                .done(function() { 
                                    // Do something now you know the image exists.
                                    tinyMCE.activeEditor.insertContent('<img src="'+new_image_url+'" />');

                                }).fail(function() { 
                                    // Image doesn't exist - do something else.
                                    tinyMCE.activeEditor.insertContent('<img src="'+image_url+'" />');
                                });
                            });
                        }

                        editor.addButton('addimage', {
                          icon: 'image',
                          tooltip: "Insert/Upload Image",
                          onclick: addImage
                        });
                      }

                });
                }
                catch(err) {
                  console.log("Ada Error di editor: "+ err.message);
                }
            }


            $('#dja_title').keyup(function() {
                var id = $(this).attr('data-id');
                var title = $(this).val();
                $('#'+id).text(title);
            });


            $(document).on("click", ".f_delete", function(e) {
                var id = $(this).attr('data-id');
                var campaign_id = $(this).attr('data-campaignid');

                swal.fire({
                  title: 'Anda yakin ingin menghapus info ini?',
                  text: "Data tidak bisa dikembalikan jika sudah dihapus!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Ya, hapus saja!',
                  cancelButtonText: 'Cancel',
                  reverseButtons: true
                }).then(function(result) {
                  if (result.value) {
                    swal.fire(
                      'Deleted!',
                      'Your data has been deleted.',
                      'success'
                    );
                    
                    var data_nya = [
                        id,
                        campaign_id
                    ];

                    var data = {
                        "action": "djafunction_delete_info",
                        "datanya": data_nya
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(response=='success'){
                            $('#info-'+id).slideUp();
                        }
                    });
                  }
                })
            });

            



            $('#update_info').click(function(e) {
                
                var all_content = tinyMCE.get('information').getContent();

                var id = $('#dja_infoid').val();
                var campaign_id = $('#dja_campaignid').val();
                var title = $('#dja_title').val();
                var information = all_content;
                
                $(this).html('Update <span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');

                var data_nya = [
                    id,
                    campaign_id,
                    title,
                    information
                ];
                var data = {
                    "action": "djafunction_update_info",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='failed'){
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=info_update&id=') ?>"+campaign_id+"&infoid="+id+"&info=failed");
                    }else{
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=info_update&id=') ?>"+campaign_id+"&infoid="+response+"&info=success");
                    }
                    
                });
            });


            $('#add_new_info').click(function(e) {
                
               
                var user_id = $(this).attr("data-userid");
                var campaign_id = $(this).attr("data-campaignid");
                
                $(this).html('Add New Info <span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');

                var data_nya = [
                    user_id,
                    campaign_id
                ];
                var data = {
                    "action": "djafunction_add_update_info",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if(response=='failed'){
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=info_update&id=') ?>"+campaign_id);
                    }else{
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=info_update&id=') ?>"+campaign_id+"&infoid="+response);
                    }
                    
                });
            });

            


            setTimeout(function() {
                $('#donasiaja-alert').slideUp('fast');
            }, 4000);

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
                                        <li class="breadcrumb-item"><a href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign') ?>">Data Campaign</a></li>
                                        <li class="breadcrumb-item active">Info Update</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Info Update</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>

                    <!-- end page title end breadcrumb -->
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body" id="publish-section">
                                    <h4 class="mt-0 header-title">List Info Update</h4><br>
                                    <?php 
                                    if($row->publish_status=='1'){
                                        $campaign_url = get_site_url().'/campaign/';
                                    }else{
                                        $campaign_url = get_site_url().'/preview/';
                                    }
                                    ?>
                                    <?php
                                    foreach ($info_updatenya as $value) { 

                                    $readtime = new donasiaja_readtime();
                                    $time_update = $readtime->time_donation($value->created_at);

                                    ?>
                                        <div style="margin-bottom: 10px;" id="info-<?php echo $value->id; ?>">
                                            <div class="media" style="border: 1px solid #eaeaea;border-radius: 4px;padding: 10px 20px;padding-bottom: 15px;">
                                                <div class="media-body">
                                                    <div class="d-inline-block">
                                                        <h6 id="<?php echo $value->id; ?>" style="line-height: 1.5;"><?php echo $value->title; ?></h6>
                                                    </div>
                                                    <div>
                                                        <span><?php echo $time_update; ?></span>
                                                        <span class="f_delete" title="Delete Info" data-id="<?php echo $value->id; ?>" data-campaignid="<?php echo $campaign_id; ?>"><i class="fas fa-trash-alt text-danger"></i></span> 
                                                        <span class="f_edit" title="Edit Info" data-link="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=info_update&id=').$campaign_id.'&infoid='.$value->id ?>"><i class="fas fa-pen text-info mr-2"></i></span>
                                                    </div>
                                                </div><!-- end media-body -->
                                            </div> <!--end media-->
                                        </div>

                                    <?php }?>

                                    <div class="row" style="margin-top: 25px;margin-bottom: 20px;">
                                        
                                        <div class="col-sm-12 text-left">
                                            <button type="button" class="btn btn-outline-primary px-5 py-2 btn-block" data-link="<?php echo $campaign_url.$row->slug; ?>#info-update" id="view_info">View Info</button>
                                        </div>
                                        <div class="col-sm-12 text-left" style="margin-top: 10px;">
                                            <button type="button" class="btn btn-primary px-5 py-2 btn-block" id="add_new_info" data-userid="<?php echo $row->user_id; ?>" data-campaignid="<?php echo $campaign_id; ?>">Add New Info</button>
                                        </div>
                                    </div>

                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col--> 
                        
                        <div class="col-lg-8">
                            <div class="card" style="max-width: 100% !important;">
                                <?php if(isset($_GET['infoid']) and $lanjut_update==true){ ?>
                                    <div class="card-body">
                                        <?php 
                                        if(isset($_GET['info'])){
                                            if($_GET['info']=="success"){
                                                echo '
                                                <div id="donasiaja-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 25px;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                                    </button>
                                                    Update Info Success
                                                </div>
                                                ';
                                            }else{
                                                echo '
                                                <div id="donasiaja-alert" class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 25px;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                                    </button>
                                                    Update Info Failed!
                                                </div>
                                                ';
                                            }
                                        }
                                        ?>

                                        <h4 class="mt-0 header-title">Info</h4><br>
                                        <!-- <p class="text-muted mb-3">Custom stylr example.</p> -->
                                        
                                        <form class="">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="username">Title / Judul</label>
                                                        <input type="text" class="form-control" id="dja_title" required="" value="<?php echo $title; ?>" data-id="<?php echo $infoid; ?>">
                                                        <input type="text" class="form-control" id="dja_infoid" value="<?php echo $infoid; ?>" style="display: none;">
                                                        <input type="text" class="form-control" id="dja_campaignid" value="<?php echo $campaignid; ?>" style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">                            
                                                    <div class="form-group">
                                                        <br>
                                                        <label for="message">Information / Keterangan</label>
                                                        <textarea id="information" name="area"></textarea> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 text-left" style="margin-top: 30px;margin-bottom: 30px;">
                                                    
                                                    <button type="button" class="btn btn-primary" id="update_info" style="width: 240px;height: 45px;">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!--end card-body-->

                                <?php }else{ ?>

                                    <p style="text-align: center;margin-top: 40px;"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../assets/icons/news.png'; ?>" style="width: 120px;"><br>
                                    </p>
                                    <p style="text-align: center;margin-bottom: 40px;">Donatur anda membutuhkan info terbaru dari penggalangan anda.<br>Silahkan update info terbaru.</p>

                                <?php } ?>
                                
                            </div><!--end card-->
                        </div><!--end col-->  

                        
                    </h2>
                </div>
            </div>
        </div>

    <?php }elseif($cs_statisctic==true){ ?>

        <?php check_verified_campaign($akses); ?>

        <?php 

        $campaign_id = $_GET['id'];
        $filternya = "";
        $date_filter = $_GET['date'];
        $date_range = $_GET['range'];

        if($date_filter=='today' || $date_filter=='yesterday' || $date_filter=='7lastdays' || $date_filter=='30lastdays' || $date_filter=='thismonth' || $date_filter=='lastmonth' || $date_filter=='daterange' || $date_filter=='all'){
    
            // Date
            $today = date('Y-m-d');
            $yesterday = date("Y-m-d", strtotime("-1 day"));
            $one_week_ago = date("Y-m-d", strtotime("-7 day"));
            $one_month_ago = date("Y-m-d", strtotime("-30 day"));
            $three_months_ago = date("Y-m-d", strtotime("-90 day"));
            $thismonth = date("Y-m-01");
            
            $month = date('m');
            $year = date('Y');
            if($month==1){
                $monthnya = 12;
                $yearnya = $year-1;
            }else{
                $monthnya = $month-1;
                $yearnya = $year;
            }
            $lastmonth_firstrange = date($yearnya."-".$monthnya."-01");
            $lastmonth_lastrange = date($yearnya."-".$monthnya."-31");

            if($date_range!='0'){
                $date_range = explode('_',$date_range);
                $date_range_first = date($date_range[0]);
                $date_range_last = date($date_range[1]);
            }else{
                $date_range_first = $today;
                $date_range_last = $today;
            }
            

            if($date_filter=='today'){
                $filternya = "and created_at BETWEEN '$today 00:00' AND '$today 23:59'";
            }elseif($date_filter=='yesterday'){
                $filternya = "and created_at BETWEEN '$yesterday 00:00' AND '$yesterday 23:59'";
            }elseif($date_filter=='7lastdays'){
                $filternya = "and created_at BETWEEN '$one_week_ago 00:00' AND '$today 23:59'";
            }elseif($date_filter=='30lastdays'){
                $filternya = "and created_at BETWEEN '$one_month_ago 00:00' AND '$today 23:59'";
            }elseif($date_filter=='3months'){
                $filternya = "and created_at BETWEEN '$three_months_ago 00:00' AND '$today 23:59'";
            }elseif($date_filter=='thismonth'){
                $filternya = "and created_at BETWEEN '$thismonth 00:00' AND '$today 23:59'";
            }elseif($date_filter=='lastmonth'){
                $filternya = "and created_at BETWEEN '$lastmonth_firstrange 00:00' AND '$lastmonth_lastrange 23:59'";
            }elseif($date_filter=='daterange'){
                $filternya = "and created_at BETWEEN '$date_range_first 00:00' AND '$date_range_last 23:59'";
            }else{
                $filternya = "";
            }

            // and a.created_at BETWEEN '2022-03-28 00:00' AND '2022-04-27 23:59'
        }

        if($date_filter=='all'){
            $date_title = ' - All';
        }elseif($date_filter=='today'){
            $date_title = ' - Today';
        }elseif($date_filter=='yesterday'){
            $date_title = ' - Yesterday';
        }elseif($date_filter=='7lastdays'){
            $date_title = ' - 7 Last days';
        }elseif($date_filter=='30lastdays'){
            $date_title = ' - 30 Last days';
        }elseif($date_filter=='thismonth'){
            $date_title = ' - This Month';
        }elseif($date_filter=='lastmonth'){
            $date_title = ' - Last Month';
        }elseif($date_filter=='daterange'){
            $date_title = ' - '.explode('_',$_GET['range'])[0].' s.d '.explode('_',$_GET['range'])[1];
        }else{
            $date_title = ' - Today';
        }

        

        $row = $wpdb->get_results("SELECT * from $table_name where campaign_id='$campaign_id' ")[0];

        $title = $row->title;

        $data_cs_on_campaign = $wpdb->get_results("SELECT cs_id, count(id) as jumlah_donasi FROM $table_name4 where campaign_id = '$campaign_id' $filternya GROUP BY cs_id");
        

        if($row->cs_rotator!=''){
            $cs_rotator = json_decode($row->cs_rotator, true);
            $jumlah_cs = $cs_rotator['jumlah'];
        }else{
            $cs_rotator = null;
            $jumlah_cs = 0;
        }

        $data_cs_active = '';
        $data_donasi_active = '';
        $data_closing_active = '';
        $add_comma = '';

        $data_cs_not_active = '';
        $cs_jumlah_donasi = '';
        $cs_jumlah_donasi_closing = '';

        if($jumlah_cs>=1){

            $add_comma = ',';
            $no = 1;
            foreach ($cs_rotator['data'] as $key => $datanya) {
                $cs_id = $datanya[0];
                $user_info = get_userdata($cs_id);

                $data1 = $wpdb->get_results("SELECT count(a.id) as donasi FROM wp_dja_donate a where campaign_id = '$campaign_id' and cs_id='$cs_id' $filternya ")[0];

                $data2 = $wpdb->get_results("SELECT count(a.id) as closing FROM wp_dja_donate a where campaign_id = '$campaign_id' and cs_id='$cs_id' and status='1' $filternya ")[0];

                // Klo sudah sama dengan jumlah array, berhenti kasih koma
                if($no==$jumlah_cs){
                    if($user_info->last_name==''){
                        $data_cs_active .= "'".$user_info->first_name."'";
                    }else{
                        $data_cs_active .= "'".$user_info->first_name.' '.$user_info->last_name."'";
                    }
                    $data_donasi_active .= $data1->donasi;
                    $data_closing_active .= $data2->closing;
                }else{
                    if($user_info->last_name==''){
                        $data_cs_active .= "'".$user_info->first_name."',";
                    }else{
                        $data_cs_active .= "'".$user_info->first_name.' '.$user_info->last_name."',";
                    }
                    $data_donasi_active .= $data1->donasi.',';
                    $data_closing_active .= $data2->closing.',';
                }
                $no++;
            }

            $data_cs_array = array();
            foreach ($cs_rotator['data'] as $key => $datanya) {
                $data_cs_array[] = $datanya[0];
            }

            // print_r($data_cs_array);

            // GET DATA CS DONASI KECUALI DARI CS ROTATOR
            foreach ($data_cs_on_campaign as $key => $value) {

                $cs_id = $value->cs_id;
                $get_data_closing = $wpdb->get_results("SELECT count(a.id) as jumlah_donasi FROM wp_dja_donate a where campaign_id = '$campaign_id' and cs_id='$cs_id' and status='1' $filternya ")[0];
                $user_info = get_userdata($cs_id);

                
                if( in_array ( $cs_id, $data_cs_array ) ){
                    // nothing
                }else{
                    if($user_info->last_name==''){
                        $data_cs_not_active .= "'".$user_info->first_name."',";
                    }else{
                        $data_cs_not_active .= "'".$user_info->first_name.' '.$user_info->last_name."',";
                    }
                    $cs_jumlah_donasi .= $value->jumlah_donasi.',';
                    $cs_jumlah_donasi_closing .= $get_data_closing->jumlah_donasi.',';
                }

                
            }

        }

        // echo $data_cs_active;
        // echo '<br>';
        if($jumlah_cs>=1 && $data_cs_on_campaign!=null){
            $data_cs_not_active = ','.substr($data_cs_not_active, 0, -1);
            $cs_jumlah_donasi = ','.substr($cs_jumlah_donasi, 0, -1);
            $cs_jumlah_donasi_closing = ','.substr($cs_jumlah_donasi_closing, 0, -1);
        }else{
            $data_cs_not_active = substr($data_cs_not_active, 0, -1);
            $cs_jumlah_donasi = substr($cs_jumlah_donasi, 0, -1);
            $cs_jumlah_donasi_closing = substr($cs_jumlah_donasi_closing, 0, -1);
        }

        $data_cs_full = $data_cs_active.$data_cs_not_active;
        $data_donasi_full = $data_donasi_active.$cs_jumlah_donasi;
        $data_closing_full = $data_closing_active.$cs_jumlah_donasi_closing;


        $data_userwp = $wpdb->get_results('SELECT a.ID, a.display_name from '.$table_name8.' a ');
        $data_usercs = '';
        foreach ( $data_userwp as $user ) {
            $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
            $roles_user = array_keys((array)$cap_user);
            $rolenya_user = $roles_user[0];

            if($rolenya_user=='cs'){
                $data_usercs .= '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
            }
        }

        // echo $data_cs_not_active;

        // echo substr($cs_fullname, 0, -1);

        // echo $data_cs_active;
        // echo '<br>';
        // echo $data_donasi_active;
        // echo '<br>';
        // echo $data_closing_active;

        /*
        $cs_fullname = '';
        $cs_jumlah_donasi = '';
        $cs_jumlah_donasi_closing = '';
        $data_cs_active = '';
        if($jumlah_cs >= 1){
            
            // CSID DIAMBIL DARI DATA DONASI
            $jumlah_array_cs = count($data_cs_on_campaign);
            $no = 1;
            // $i = 1;
            foreach ($data_cs_on_campaign as $key => $value) {

                $cs_id = $value->cs_id;
                $get_data_closing = $wpdb->get_results('SELECT count(a.id) as jumlah_donasi FROM wp_dja_donate a where campaign_id = "'.$campaign_id.'" and cs_id="'.$cs_id.'" and status="1" ')[0];
                $user_info = get_userdata($cs_id);

                
                foreach ($cs_rotator['data'] as $key => $datanya) {
                    if($cs_id==$datanya[0]){
                        if($user_info->last_name==''){
                            $data_cs_active .= $user_info->first_name.",";
                        }else{
                            $data_cs_active .= $user_info->first_name.' '.$user_info->last_name.",";
                        }
                        // $i++;
                        break;
                    }
                }

                

                // klo sudah terakhir
                if($no==$jumlah_array_cs){
                    if($user_info->last_name==''){
                        $cs_fullname .= "'".$user_info->first_name."'";
                    }else{
                        $cs_fullname .= "'".$user_info->first_name.' '.$user_info->last_name."'";
                    }
                    $cs_jumlah_donasi .= $value->jumlah_donasi;
                    $cs_jumlah_donasi_closing .= $get_data_closing->jumlah_donasi;
                }else{
                    if($user_info->last_name==''){
                        $cs_fullname .= "'".$user_info->first_name."', ";
                    }else{
                        $cs_fullname .= "'".$user_info->first_name.' '.$user_info->last_name."', ";
                    }
                    $cs_jumlah_donasi .= $value->jumlah_donasi.',';
                    $cs_jumlah_donasi_closing .= $get_data_closing->jumlah_donasi.',';
                }
                $no++;
            }

            

        }else{


            // CSID DIAMBIL DARI SETTINGAN CS ROTATOR
            if($cs_rotator!=null){
                $jumlah_array_cs = count($cs_rotator['data']);
                $no = 1;
                foreach ($cs_rotator['data'] as $key => $value) {

                    $cs_id = $value[0];

                    $user_info = get_userdata($cs_id);

                    if($no==$jumlah_array_cs){
                        if($user_info->last_name==''){
                            $cs_fullname .= "'".$user_info->first_name."'";
                        }else{
                            $cs_fullname .= "'".$user_info->first_name.' '.$user_info->last_name."'";
                        }
                    }else{
                        if($user_info->last_name==''){
                            $cs_fullname .= "'".$user_info->first_name."', ";
                        }else{
                            $cs_fullname .= "'".$user_info->first_name.' '.$user_info->last_name."', ";
                        }
                    }
                    $no++;
                }
            }   
        }
        */



        // echo $data_cs_active;
        // echo $cs_jumlah_donasi;
        // echo '<br>';
        // echo $cs_jumlah_donasi_closing;

        // echo $cs_fullname;

        // $info_updatenya = $wpdb->get_results('SELECT * from '.$table_name3.' where campaign_id="'.$campaign_id.'" ORDER BY id DESC');

        // if(isset($_GET['infoid'])){
        //     $infoid = $_GET['infoid'];
        //     if($role=='donatur'){
        //         $detail_update = $wpdb->get_results('SELECT * from '.$table_name3.' where campaign_id="'.$campaign_id.'" and id="'.$infoid.'" and user_id="'.$id_login.'" ');
        //     }else{
        //         $detail_update = $wpdb->get_results('SELECT * from '.$table_name3.' where campaign_id="'.$campaign_id.'" and id="'.$infoid.'"');
        //     }
        //     if($detail_update!=null){
        //         $title = $detail_update[0]->title;
        //         $campaignid = $detail_update[0]->campaign_id;
        //         $infoid = $detail_update[0]->id;
        //         $infonya = $detail_update[0]->information;
        //         $lanjut_update = true;
        //     }else{
        //         $lanjut_update = false;
        //     }
        // }else{
        //     $title = '';
        //     $campaignid = '';
        //     $infoid = '';
        //     $infonya = '';
        // }

        // $infonya = str_replace("'", "&#39;", $infonya); // petik 1
        // $infonya = str_replace('../wp-content', home_url().'/wp-content', $infonya);

        ?>
        <!-- css -->
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate.css" rel="stylesheet" type="text/css">
        <style>
            .daterangepicker {position: absolute;color: inherit;background-color: #fff;border-radius: 4px;border: 1px solid #ddd;width: 278px;max-width: none;padding: 0;margin-top: 7px;top: 100px;left: 20px;z-index: 3001;display: none;font-family: arial;font-size: 15px;line-height: 1em;}.daterangepicker:before, .daterangepicker:after {position: absolute;display: inline-block;border-bottom-color: rgba(0, 0, 0, 0.2);content: '';}.daterangepicker:before {top: -7px;border-right: 7px solid transparent;border-left: 7px solid transparent;border-bottom: 7px solid #ccc;}.daterangepicker:after {top: -6px;border-right: 6px solid transparent;border-bottom: 6px solid #fff;border-left: 6px solid transparent;}.daterangepicker.opensleft:before {right: 9px;}.daterangepicker.opensleft:after {right: 10px;}.daterangepicker.openscenter:before {left: 0;right: 0;width: 0;margin-left: auto;margin-right: auto;}.daterangepicker.openscenter:after {left: 0;right: 0;width: 0;margin-left: auto;margin-right: auto;}.daterangepicker.opensright:before {left: 9px;}.daterangepicker.opensright:after {left: 10px;}.daterangepicker.drop-up {margin-top: -7px;}.daterangepicker.drop-up:before {top: initial;bottom: -7px;border-bottom: initial;border-top: 7px solid #ccc;}.daterangepicker.drop-up:after {top: initial;bottom: -6px;border-bottom: initial;border-top: 6px solid #fff;}.daterangepicker.single .daterangepicker .ranges, .daterangepicker.single .drp-calendar {float: none;}.daterangepicker.single .drp-selected {display: none;}.daterangepicker.show-calendar .drp-calendar {display: block;}.daterangepicker.show-calendar .drp-buttons {display: block;}.daterangepicker.auto-apply .drp-buttons {display: none;}.daterangepicker .drp-calendar {display: none;max-width: 270px;}.daterangepicker .drp-calendar.left {padding: 8px 0 8px 8px;}.daterangepicker .drp-calendar.right {padding: 8px;}.daterangepicker .drp-calendar.single .calendar-table {border: none;}.daterangepicker .calendar-table .next span, .daterangepicker .calendar-table .prev span {color: #fff;border: solid black;border-width: 0 2px 2px 0;border-radius: 0;display: inline-block;padding: 3px;}.daterangepicker .calendar-table .next span {transform: rotate(-45deg);-webkit-transform: rotate(-45deg);}.daterangepicker .calendar-table .prev span {transform: rotate(135deg);-webkit-transform: rotate(135deg);}.daterangepicker .calendar-table th, .daterangepicker .calendar-table td {white-space: nowrap;text-align: center;vertical-align: middle;min-width: 32px;width: 32px;height: 24px;line-height: 24px;font-size: 12px;border-radius: 4px;border: 1px solid transparent;white-space: nowrap;cursor: pointer;}.daterangepicker .calendar-table {border: 1px solid #fff;border-radius: 4px;background-color: #fff;}.daterangepicker .calendar-table table {width: 100%;margin: 0;border-spacing: 0;border-collapse: collapse;}.daterangepicker td.available:hover, .daterangepicker th.available:hover {background-color: #eee;border-color: transparent;color: inherit;}.daterangepicker td.week, .daterangepicker th.week {font-size: 80%;color: #ccc;}.daterangepicker td.off, .daterangepicker td.off.in-range, .daterangepicker td.off.start-date, .daterangepicker td.off.end-date {background-color: #fff;border-color: transparent;color: #999;}.daterangepicker td.in-range {background-color: #ebf4f8;border-color: transparent;color: #000;border-radius: 0;}.daterangepicker td.start-date {border-radius: 4px 0 0 4px;}.daterangepicker td.end-date {border-radius: 0 4px 4px 0;}.daterangepicker td.start-date.end-date {border-radius: 4px;}.daterangepicker td.active, .daterangepicker td.active:hover {background-color: #357ebd;border-color: transparent;color: #fff;}.daterangepicker th.month {width: auto;}.daterangepicker td.disabled, .daterangepicker option.disabled {color: #999;cursor: not-allowed;text-decoration: line-through;}.daterangepicker select.monthselect, .daterangepicker select.yearselect {font-size: 12px;padding: 1px;height: auto;margin: 0;cursor: default;}.daterangepicker select.monthselect {margin-right: 2%;width: 56%;}.daterangepicker select.yearselect {width: 40%;}.daterangepicker select.hourselect, .daterangepicker select.minuteselect, .daterangepicker select.secondselect, .daterangepicker select.ampmselect {width: 50px;margin: 0 auto;background: #eee;border: 1px solid #eee;padding: 2px;outline: 0;font-size: 12px;}.daterangepicker .calendar-time {text-align: center;margin: 4px auto 0 auto;line-height: 30px;position: relative;}.daterangepicker .calendar-time select.disabled {color: #ccc;cursor: not-allowed;}.daterangepicker .drp-buttons {clear: both;text-align: right;padding: 8px;border-top: 1px solid #ddd;display: none;line-height: 12px;vertical-align: middle;}.daterangepicker .drp-selected {display: inline-block;font-size: 12px;padding-right: 8px;}.daterangepicker .drp-buttons .btn {margin-left: 8px;font-size: 12px;font-weight: bold;padding: 4px 8px;}.daterangepicker.show-ranges.single.rtl .drp-calendar.left {border-right: 1px solid #ddd;}.daterangepicker.show-ranges.single.ltr .drp-calendar.left {border-left: 1px solid #ddd;}.daterangepicker.show-ranges.rtl .drp-calendar.right {border-right: 1px solid #ddd;}.daterangepicker.show-ranges.ltr .drp-calendar.left {border-left: 1px solid #ddd;}.daterangepicker .ranges {float: none;text-align: left;margin: 0;}.daterangepicker.show-calendar .ranges {margin-top: 8px;}.daterangepicker .ranges ul {list-style: none;margin: 0 auto;padding: 0;width: 100%;}.daterangepicker .ranges li {font-size: 12px;padding: 8px 12px;cursor: pointer;}.daterangepicker .ranges li:hover {background-color: #eee;}.daterangepicker .ranges li.active {background-color: #08c;color: #fff;}@media (min-width: 564px) {.daterangepicker {width: auto;}.daterangepicker .ranges ul {width: 140px;}.daterangepicker.single .ranges ul {width: 100%;}.daterangepicker.single .drp-calendar.left {clear: none;}.daterangepicker.single .ranges, .daterangepicker.single .drp-calendar {float: left;}.daterangepicker {direction: ltr;text-align: left;}.daterangepicker .drp-calendar.left {clear: left;margin-right: 0;}.daterangepicker .drp-calendar.left .calendar-table {border-right: none;border-top-right-radius: 0;border-bottom-right-radius: 0;}.daterangepicker .drp-calendar.right {margin-left: 0;}.daterangepicker .drp-calendar.right .calendar-table {border-left: none;border-top-left-radius: 0;border-bottom-left-radius: 0;}.daterangepicker .drp-calendar.left .calendar-table {padding-right: 8px;}.daterangepicker .ranges, .daterangepicker .drp-calendar {float: left;}}@media (min-width: 730px) {.daterangepicker .ranges {width: auto;}.daterangepicker .ranges {float: left;}.daterangepicker.rtl .ranges {float: right;}.daterangepicker .drp-calendar.left {clear: none !important;}}

            .apexcharts-legend-marker, .apexcharts-legend-text {
                opacity: 0;
                display: none;
            }
            .dropdown-item.active {
                color: #4956FF !important;
                background: #fff !important;
            }
            .dropdown-item:hover {
                color: #4956FF !important;
            }

            #add-cs select {
                height: 30px !important;font-size: 13px;margin-top: 5px;
            }

        </style>

        <?php check_license(); ?>

        <?php 
            // if($role=='cs'){
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
        <?php wp_die(); }  ?>

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
                                        <li class="breadcrumb-item"><a href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign') ?>">Data Campaign</a></li>
                                        <li class="breadcrumb-item active">CS Statisctic</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">CS Statistic<?php echo $date_title; ?></h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>

                    <!-- end page title end breadcrumb -->


                    <div class="row">

                        <div class="col-lg-8">
                            <div class="card" style="max-width: initial;">
                                <div class="card-body" id="publish-section">
                                    <h4 class="mt-0 header-title">Statistic - <?php echo $title; ?></h4><br>

                                    <input type="text" class="form-control input_daterangepicker" name="dates" style="width: 0;margin: 0;padding: 0;position:absolute;border: 0 !important;font-size: 0;min-height: 0 !important;margin-top:-50px;">

                                    <div id="by_date_box" class="btn-group ml-1" style="position: absolute;right: 0;margin-top: -52px;margin-right: 30px;">

                                            <button id="by_date_button" type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-calendar-alt"></i><i class="mdi mdi-chevron-down ml-1"></i>
                                            </button>
                                            
                                            <div id="by_date_list" class="dropdown-menu">
                                                
                                                    <a class="dropdown-item <?php if($date_filter=='today'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=today&range=0';?>">Today</a>
                                                    <a class="dropdown-item <?php if($date_filter=='yesterday'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=yesterday&range=0';?>">Yesterday</a>
                                                    <a class="dropdown-item <?php if($date_filter=='7lastdays'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=7lastdays&range=0';?>">7 Last days</a>
                                                    <a class="dropdown-item <?php if($date_filter=='30lastdays'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=30lastdays&range=0';?>">30 Last days</a>
                                                    <a class="dropdown-item <?php if($date_filter=='thismonth'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=thismonth&range=0';?>">This Month</a>
                                                    <a class="dropdown-item <?php if($date_filter=='lastmonth'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=lastmonth&range=0';?>">Last Month</a>
                                                    <a class="dropdown-item daterange <?php if($date_filter=='daterange'){echo'active';}?>" href="javascript:;" data-link="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=daterange&range=0';?>">Date Range</a>
                                                    <a class="dropdown-item <?php if($date_filter==null || $date_filter=='all'){echo'active';}?>" href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$_GET['id'].'&date=all&range=0';?>">All</a>
                                                
                                            </div>
                                        </div>
                                    <div class="">
                                        <div id="ana_dash_1" class="apex-charts"></div>
                                    </div>  

                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col--> 
                        
                        <div class="col-lg-4" id="add-cs">
                            <div class="card" style="max-width: 100% !important;">
                                <div class="card-body" id="publish-section">
                                    <h4 class="mt-0 header-title">CS Rotator</h4><br>
                                    <?php 
                                    if(isset($_GET['info'])){
                                        if($_GET['info']=="success"){
                                            echo '
                                            <div id="donasiaja-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 25px;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                                </button>
                                                Update CS Success
                                            </div>
                                            ';
                                        }else{
                                            echo '
                                            <div id="donasiaja-alert" class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 25px;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                                </button>
                                                Update CS Failed!
                                            </div>
                                            ';
                                        }
                                    }
                                    ?>

                                    <input type="text" value="<?php echo $campaign_id; ?>" id="dja_campaignid" style="display:none;">
                                        <div id="box_cs">

                                            <?php 
                                            $data_userwp = $wpdb->get_results('SELECT a.ID, a.display_name from '.$table_name8.' a ');

                                            if($row->cs_rotator!=''){
                                                $cs_rotator = json_decode($row->cs_rotator, true);
                                                $jumlah_cs = $cs_rotator['jumlah'];
                                            }else{
                                                $jumlah_cs = 0;
                                            }

                                            if($jumlah_cs>=1){

                                                $total_priority = 0;
                                                foreach ($cs_rotator['data'] as $key => $value) {
                                                    $total_priority = $total_priority + $value[1];
                                                }
                                                
                                                foreach ($cs_rotator['data'] as $key => $value) { $rand3 = d_randomString(3); ?>

                                                <div class="form-group row container_cs_box" id="container_cs_<?php echo $rand3;?>" data-id="<?php echo $rand3;?>">
                                                    <div class="col-sm-5 col-data container_cs">
                                                        <select class="form-control form-control-lg" title="CS">
                                                            <option value="0">Select CS</option>
                                                            <?php foreach ( $data_userwp as $user ) {

                                                                if($value[0]==$user->ID){
                                                                    $selected = 'selected';
                                                                }else{
                                                                    $selected = '';
                                                                }
                                                                
                                                                $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
                                                                $roles_user = array_keys((array)$cap_user);
                                                                $rolenya_user = $roles_user[0];

                                                                if($rolenya_user=='cs'){
                                                                    echo '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
                                                                }
                                                                
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 col-form-label container_priority <?php echo $rand3; ?>">
                                                        <select class="form-control form-control-lg cs_priority" id="container_priority_<?php echo $rand3; ?>" title="Priority" style="margin-top: -2px;" onclick="run_persen_cs()">
                                                            <?php for($j=1; $j<=10; $j++) { 

                                                                if($value[1]==$j){
                                                                    $selected = 'selected';
                                                                }else{
                                                                    $selected = '';
                                                                }

                                                            ?>
                                                            <option value="<?php echo $j; ?>" <?php echo $selected; ?>><?php echo $j; ?></option>
                                                            <?php } ?>
                                                        </select></div>
                                                    <div class="col-sm-2 col-form-label container_persen persen_<?php echo $rand3; ?>" data-id="<?php echo $rand3; ?>" title="Persentase"><?php 
                                                    $persen_priority = ($value[1]/$total_priority)*100;
                                                    if($persen_priority==100){
                                                        echo $persen_priority;
                                                    }else{
                                                        echo number_format($persen_priority, 1, '.', '');
                                                    }
                                                    ?>%</div>
                                                    <div class="col-sm-2 col-form-label container_btn_del">
                                                        <button type="button" class="btn btn-danger del_field_cs" title="Delete" data-randid="<?php echo $rand3; ?>" style="padding: 3px 8px;margin-top: -2px;"><i class="fas fa-minus" style="font-size: 11px;"></i></button>
                                                    </div>
                                                </div>

                                                <?php } // end foreach ?>

                                            <?php } // end ?>
                                            

                                        </div>

                                        <div class="form-group row">
                                                <button type="button" class="btn btn-outline-light btn-sm add_cs" data-randid="" style="padding: 3px 12px;font-size: 11px;margin-left: 10px;"><i class="fas fa-plus" style="font-size:9px;"></i>&nbsp;&nbsp;Add CS</button>
                                        </div>

                                        <div class="col-sm-12 text-left" style="padding-left: 0;padding-top: 25px;padding-bottom: 10px;">
                                            <button type="button" class="btn btn-outline-primary px-5 py-2 update_cs_rotator" data-act="update" id="publish_update">Update CS</button>
                                        </div>
                                </div>
                            </div><!--end card-->
                        </div><!--end col-->  

                        
                    </h2>
                </div>
            </div>
        </div>


        <!--Wysiwig js-->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/app.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/moment.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/daterangepicker.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/donasiaja-admin.js"></script>

        <script type="text/javascript">

        setTimeout(function() {
                $('#donasiaja-alert').slideUp('fast');
            }, 3000);

        function run_persen_cs(){        
            var arr_link_priority = $('select.cs_priority').map(function(){
                return this.value;
            }).get().toString();

            var str3 = arr_link_priority;
            var str3_array = str3.split(',');

            total_priority = 0;
            var len2 = str3_array.length;
            for(var i = 0; i < str3_array.length; i++) {
                nilai = parseFloat(str3_array[i]);
                total_priority = total_priority+nilai;
            }

            var new_selected = [];
            $(".container_persen").each(function(){
                    new_selected.push($(this).data('id'));
            });
            new_selected = new_selected.toString();
            var array = new_selected.split(',');

            var arrayLength = array.length;
            for (var i = 0; i < arrayLength; i++) {
                if(array[i]!=0){
                    id_ne = array[i];
                    var valuenya = $('.'+id_ne).find('option:selected').val();
                    hasil_persennya = (valuenya/total_priority)*100;
                    hasil_persennya = hasil_persennya.toFixed(1);
                    if(hasil_persennya>=100){
                        hasil_persennya = 100;
                    }
                    $('.persen_'+id_ne).text(hasil_persennya+'%')
                }
            }
        }

        function randMe(length, current) {
          current = current ? current : '';
          return length ? randMe(--length, "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz".charAt(Math.floor(Math.random() * 60)) + current) : current;
        }
        
        jQuery(document).ready(function($){
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

            $(document).on("click", ".del_field_cs", function(e) {
                var randid = $(this).attr('data-randid');
                $('#container_cs_'+randid).remove();
                run_persen_cs();
            });

            $(document).on("click", ".add_cs", function(e) {
                var randid = randMe(3);
                $('#box_cs').append('<div class="form-group row container_cs_box" id="container_cs_'+randid+'" data-id="'+randid+'"><div class="col-sm-5 col-data container_cs"><select class="form-control form-control-lg" title="CS"><option value="0">Select CS</option><?php echo $data_usercs; ?></select> </div><div class="col-sm-3 col-form-label container_priority '+randid+'"> <select class="form-control form-control-lg cs_priority" id="container_priority_'+randid+'" title="Priority" style="margin-top: -2px;" onclick="run_persen_cs()"><option value="1" selected="">1</option><option value="2">2</option> <option value="3">3</option> <option value="4">4</option> <option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> <option value="9">9</option> <option value="10">10</option> </select></div><div class="col-sm-2 col-form-label container_persen persen_'+randid+'" data-id="'+randid+'" title="Persentase"></div><div class="col-sm-2 col-form-label container_btn_del"> <button type="button" class="btn btn-danger del_field_cs" title="Delete" data-randid="'+randid+'" style="padding: 3px 8px;margin-top: -2px;"><i class="fas fa-minus" style="font-size: 11px;"></i></button> </div></div>');
                run_persen_cs();
                e.preventDefault();
            });

            $('.update_cs_rotator').click(function(e) {

                var campaign_id = $('#dja_campaignid').val();

                jlh_cs = 1;
                var new_selected_cs = [];
                $(".container_cs_box").each(function(){
                        var id = $(this).data('id');
                        var id_cs = $('#container_cs_'+id).find("option:selected").val();
                        if(id_cs=='0'){
                        }else{
                            var value_priority = $('#container_priority_'+id).find("option:selected").val();
                            new_selected_cs.push('"cs'+jlh_cs+'":["'+id_cs+'","'+value_priority+'"]');
                            jlh_cs++;
                        }
                        
                });
                new_selected_cs = '{'+new_selected_cs+'}';

                if(jlh_cs!=1){
                    jlh_cs = jlh_cs-1;
                    var new_selected_cs = '{"jumlah":'+jlh_cs+',"data":'+new_selected_cs+'}';
                }else{
                    var new_selected_cs = '{"jumlah":0,"data":{}}';
                }


                $(this).html('Update CS&nbsp;&nbsp;&nbsp;<span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');

                var data_nya = [
                    campaign_id,
                    new_selected_cs
                ];

                var data = {
                    "action": "djafunction_update_csrotator",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    var response_text = response.split("_");
                    var info = response_text[0];
                    var idnya = response_text[1];

                    if(info=='0'){
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign') ?>");
                    }else if(info=='failed'){
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=') ?>"+idnya+"&info=failed&date=all&range=0");
                    }else{
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=') ?>"+idnya+"&info=success&date=all&range=0");
                    }
                    
                });
            });



        });

        </script>

        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/apexcharts/apexcharts.min.js"></script>
        
        <script>

          var options = {
            chart: {
                height: 295,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    endingShape: 'rounded',
                    columnWidth: '25%',
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ["rgba(42, 118, 244, .18)", '#7680FF'],
            series: [{
                name: 'Donasi',
                data: [<?php echo $data_donasi_full; ?>]
            }, {
                name: 'Closing',
                data: [<?php echo $data_closing_full; ?>]
            },],
            xaxis: {
                categories: [<?php echo $data_cs_full; ?>],
                axisBorder: {
                  show: true,
                  color: '#bec7e0',
                },  
                axisTicks: {
                  show: true,
                  color: '#bec7e0',
                },    
            },
            legend: {
              offsetY: 6,
            },
            yaxis: {
                title: {
                    text: 'Jumlah Donasi',
                },
            },
            fill: {
                opacity: 1
          
            },
            // legend: {
            //     floating: true
            // },
            grid: {
                row: {
                    colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.2,           
                },
                strokeDashArray: 4,
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "" + val + ""
                    }
                }
            }
          }
          
          var chart = new ApexCharts(
            document.querySelector("#ana_dash_1"),
            options
          );
          
          chart.render();

          
            var data_chart = [];
            $(".apexcharts-xaxis-label").each(function(){
                    var id = $(this).attr('id');
                    var get_nama = $('#'+id+' tspan').text();
                    data_chart.push(id+'_'+get_nama);
            });

            // data_chart = '['+data_chart+']';
            // alert(data_chart);

            var data_csnya = [];
            data_csnya = "<?php echo $data_cs_active; ?>";
            const myArrayCSrotator = data_csnya.split(",");

            // alert(data_csnya);

            const array = data_chart;
            array.forEach(function (item, index) {
              console.log(item, index);
              const myArray = item.split("_");
              let idnya = myArray[0];
              let namanya = myArray[1];
              $('#'+idnya+' title').text(namanya+' (Not Active)');

              myArrayCSrotator.forEach(function (item, index) {  
                item = item.replace("'",'');
                item = item.replace("'",'');
                console.log(item);
                if(namanya==item){
                    $('#'+idnya+' tspan').css({'fill': '#37C1AA'});
                    $('#'+idnya+' title').text(namanya);
                }
              });
              
              // console.log(namanya);

            });

            
          
        </script>


    <?php }elseif($edit==true){ ?>

        <?php check_verified_campaign($akses); ?>

        <?php 

        $campaign_id = $_GET['id'];
        $row = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'"')[0];

        // print_r($row);
        // return false;

        if($row->form_status!='1'){
            $text1 = '';
            $text2 = '';
            $text3 = '';
            $text4 = '';
        }else{
            $form_text   = json_decode($row->form_text, true);
            $text1 = $form_text['text'][0];
            $text2 = $form_text['text'][1];
            $text3 = $form_text['text'][2];
            $text4 = $form_text['text'][3];
        }

        $fb_pixel = $row->fb_pixel;
        if($row->fb_event!=''){
            $fb_event  = json_decode($row->fb_event, true);
            $event_1   = $fb_event['event'][0];
            $event_2   = $fb_event['event'][1];
            $event_3   = $fb_event['event'][2];
        }else{
            $event_1 = '';
            $event_2 = '';
            $event_3 = '';
        }

        $gtm_status = $row->gtm_status;
        $gtm_id     = $row->gtm_id;

        $tiktok_status = $row->tiktok_status;
        $tiktok_pixel  = $row->tiktok_pixel;

        // $information = str_replace("'", "&#39;", $row->information); // petik 1
        // $information = str_replace('"', "&#34;", $information); // petik 2

        $unique_number_setting  = $row->unique_number_setting;
        if($unique_number_setting!=null){
            $unique_number_value    = json_decode($row->unique_number_value, true);
        }else{
            $json = '{"unique_number":["","",""]}';
            $unique_number_value    = json_decode($json, true);
        }

        $method_status    = json_decode($row->method_status, true);

        // print_r($method_status);

        // method_status = {"method1": ["instant", "Instant Payment", "1"]}

        $instant_setting   = $method_status['instant'];
        $va_setting        = $method_status['va'];
        $transfer_setting  = $method_status['transfer'];

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
        
        $pengeluaran_setting = $row->pengeluaran_setting;
        if($pengeluaran_setting=='1'){
            $status_text4 = '<span>Active</span>';
            $checked4 = 'checked=""';
        }else{
            $status_text4 = '<span>Not Active</span>';
            $checked4 = '';
        }
        
        $fundraiser_on = $row->fundraiser_on;
        if($fundraiser_on=='1'){
            $status_text5 = '<span>Active</span>';
            $checked5 = 'checked=""';
        }else{
            $status_text5 = '<span>Not Active</span>';
            $checked5 = '';
        }

        $fundraiser_commission_on = $row->fundraiser_commission_on;
        if($fundraiser_commission_on=='1'){
            $status_text6 = '<span>Active</span>';
            $checked6 = 'checked=""';
        }else{
            $status_text6 = '<span>Not Active</span>';
            $checked6 = '';
        }

        $form_anonim_setting = 1;
        $form_email_setting = 1;
        $form_comment_setting = 1;
        if($row->custom_field_setting!=''){
            $custom_field_setting = json_decode($row->custom_field_setting, true);
            $form_anonim_setting = $custom_field_setting['anonim'];
            $form_email_setting = $custom_field_setting['email'];
            $form_comment_setting = $custom_field_setting['comment'];
        }
        if($form_anonim_setting=='1'){
            $status_text7 = '<span>Show</span>';
            $checked7 = 'checked=""';
        }else{
            $status_text7 = '<span>Hide</span>';
            $checked7 = '';
        }

        if($form_email_setting=='1'){
            $status_text8 = '<span>Show</span>';
            $checked8 = 'checked=""';
        }else{
            $status_text8 = '<span>Hide</span>';
            $checked8 = '';
        }

        if($form_comment_setting=='1'){
            $status_text9 = '<span>Show</span>';
            $checked9 = 'checked=""';
        }else{
            $status_text9 = '<span>Hide</span>';
            $checked9 = '';
        }


        // GET BANK
        $get_bank = $wpdb->get_results('SELECT code, name from '.$table_name7.' ORDER BY id ASC');

        $option_bank = '<option value="0">Pilih Bank</option>';
        foreach ($get_bank as $key => $value) {
           $option_bank .= '<option value="'.$value->code.'">'.$value->name.'</option>';
        }

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


        $information = str_replace("'", "&#39;", $row->information); // petik 1
        $information = str_replace('../wp-content', home_url().'/wp-content', $information);

        $additional_info = str_replace("'", "&#39;", $row->additional_info); // petik 1
        $additional_info = str_replace('../wp-content', home_url().'/wp-content', $additional_info);

        $jumlah_formula = 0;
        if($row->additional_formula!=''){
            $additional_formula = json_decode($row->additional_formula, true);
            $jumlah_formula = $additional_formula['jumlah'];
        }
        
        $jumlah_field = 0;
        if($row->additional_field!=''){
            $additional_field = json_decode($row->additional_field, true);
            $jumlah_field = $additional_field['jumlah'];
        }

        // print_r($add_formula['jumlah']);
        // print_r($add_formula['data']);

        // foreach ($add_formula['data'] as $key => $value) {
        //     echo $value['label'] .' - '.$value['type'];
        //     echo '<br>';
        // }

        $data_userwp = $wpdb->get_results('SELECT a.ID, a.display_name from '.$table_name8.' a ');
        $data_usercs = '';
        foreach ( $data_userwp as $user ) {
            $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
            $roles_user = array_keys((array)$cap_user);
            $rolenya_user = $roles_user[0];

            if($rolenya_user=='cs'){
                $data_usercs .= '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
            }
        }

        ?>
        
        <!-- css -->
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate.css" rel="stylesheet" type="text/css">

        <!--Wysiwig js-->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/donasiaja-admin.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/tinymce/tinymce.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script type="text/javascript">


        function setCaret() {
            var el = document.getElementById("box-slugnya");
            var range = document.createRange();
            var sel = window.getSelection();
            range.setStart(el.childNodes[0], 0);
            range.collapse(true);
            sel.removeAllRanges();
            sel.addRange(range);
            el.focus();
        }

        function createAlert(e,t,n){var a,o=document.createElement("div");o.className+="animation-target lala-alert ";var r="alert-"+t+" ";o.className+=r;var l=document.createElement("span");l.className+=" close-alert-x glyphicon glyphicon-remove",l.addEventListener("click",function(){var e=this.parentNode;e.parentNode.removeChild(e)}),o.addEventListener("mouseover",function(){this.classList.remove("fade-out"),clearTimeout(a)}),o.addEventListener("mouseout",function(){a=setTimeout(function(){o.parent;o.className+=" fade-out",o.parentNode&&(a=setTimeout(function(){o.parentNode.removeChild(o)},500))},3e3)}),o.innerHTML=e,o.appendChild(l);var d=document.getElementById("lala-alert-wrapper");d.insertBefore(o,d.children[0]),a=setTimeout(function(){var e=o;e.className+=" fade-out",e.parentNode&&(a=setTimeout(function(){e.parentNode.removeChild(e)},500))},n)}window.onload=function(){document.getElementById("lala-alert-wrapper"),document.getElementById("alert-success"),document.getElementById("alert-info"),document.getElementById("alert-warning"),document.getElementById("alert-danger")};


        function run_persen_cs(){        
            var arr_link_priority = $('select.cs_priority').map(function(){
                return this.value;
            }).get().toString();

            var str3 = arr_link_priority;
            var str3_array = str3.split(',');

            total_priority = 0;
            var len2 = str3_array.length;
            for(var i = 0; i < str3_array.length; i++) {
                nilai = parseFloat(str3_array[i]);
                total_priority = total_priority+nilai;
            }

            var new_selected = [];
            $(".container_persen").each(function(){
                    new_selected.push($(this).data('id'));
            });
            new_selected = new_selected.toString();
            var array = new_selected.split(',');

            var arrayLength = array.length;
            for (var i = 0; i < arrayLength; i++) {
                if(array[i]!=0){
                    id_ne = array[i];
                    var valuenya = $('.'+id_ne).find('option:selected').val();
                    hasil_persennya = (valuenya/total_priority)*100;
                    hasil_persennya = hasil_persennya.toFixed(1);
                    if(hasil_persennya>=100){
                        hasil_persennya = 100;
                    }
                    $('.persen_'+id_ne).text(hasil_persennya+'%')
                }
            }
        }

        jQuery(document).ready(function($){

            $('#fb_pixel').tagEditor({
                initialTags: [<?php echo $pixel_id; ?>],
                delimiter: ', ',
                placeholder: '...'
            });

            if($("#information").length > 0){

                try {
                tinymce.init({
                      selector: "textarea#information",
                      theme: "modern",
                      height:300,
                      plugins: [
                          "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                          "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                          "save table contextmenu directionality template paste textcolor"
                      ],
                      toolbar: "oke | undo redo | styleselect | bold italic | alignleft aligncenter alignjustify | bullist numlist | link addimage | print preview media fullpage | forecolor",
                      style_formats: [
                          {title: 'Header', block: 'h2', styles: {color: '#23374d'}},
                          {title: 'Bold text', inline: 'b', styles: {color: '#23374d'}},
                          {title: 'Paragraph', inline: 'p', styles: {color: '#23374d'}},
                          {title: 'Span', inline: 'span', styles: {color: '#23374d'}},
                      ],
                      init_instance_callback:function(editor){
                        editor.setContent('<?php echo trim(preg_replace('/\s+/', ' ', $information)); ?>');
                        
                      }, 
                      setup: function(editor) {
                          
                        function addImage(){
                            var image = wp.media({ 
                                title: 'Upload Image',
                                multiple: false
                            }).open()
                            .on('select', function(e){
                                var uploaded_image = image.state().get('selection').first();
                                var image_url = uploaded_image.toJSON().url;

                                new_image_url = image_url;

                                $.get(new_image_url)
                                .done(function() { 
                                    // Do something now you know the image exists.
                                    tinyMCE.activeEditor.insertContent('<img src="'+new_image_url+'" />');

                                }).fail(function() { 
                                    // Image doesn't exist - do something else.
                                    tinyMCE.activeEditor.insertContent('<img src="'+image_url+'" />');
                                });
                            });
                        }

                        editor.addButton('addimage', {
                          icon: 'image',
                          tooltip: "Insert/Upload Image",
                          onclick: addImage
                        });
                    }


                });
                }
                catch(err) {
                  console.log("Ada Error di editor: "+ err.message);
                }
            }

            if($("#additional_info").length > 0){
                tinymce.init({
                  selector: "textarea#additional_info",
                  theme: "modern",
                  height:200,
                  plugins: [
                      "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                      "save table contextmenu directionality template paste textcolor"
                  ],
                  toolbar: "oke | undo redo | styleselect | bold italic | alignleft aligncenter alignjustify | bullist numlist | link addimage | print preview media fullpage | forecolor",
                  style_formats: [
                      {title: 'Header', block: 'h2', styles: {color: '#23374d'}},
                      {title: 'Bold text', inline: 'b', styles: {color: '#23374d'}},
                      {title: 'Paragraph', inline: 'p', styles: {color: '#23374d'}},
                      {title: 'Span', inline: 'span', styles: {color: '#23374d'}},
                  ],
                  init_instance_callback:function(editor){
                        editor.setContent('<?php echo trim(preg_replace('/\s+/', ' ', $additional_info)); ?>');
                      }, 
                  setup: function(editor) {
                          
                    function addImage(){
                        var image = wp.media({ 
                            title: 'Upload Image',
                            multiple: false
                        }).open()
                        .on('select', function(e){
                            var uploaded_image = image.state().get('selection').first();
                            var image_url = uploaded_image.toJSON().url;

                            new_image_url = image_url;

                            $.get(new_image_url)
                            .done(function() { 
                                // Do something now you know the image exists.
                                tinyMCE.activeEditor.insertContent('<img src="'+new_image_url+'" />');

                            }).fail(function() { 
                                // Image doesn't exist - do something else.
                                tinyMCE.activeEditor.insertContent('<img src="'+image_url+'" />');
                            });
                        });
                    }

                    editor.addButton('addimage', {
                      icon: 'image',
                      tooltip: "Insert/Upload Image",
                      onclick: addImage
                    });
                  }
                });

            }


            $('.edit-status').click(function(e) {
                $('#publish_status').slideDown();
            });
            
            $('#publish_status').on('change', function(e) {
                var val = $(this).find("option:selected").text();
                $('#set_publish_status').text(val);
            });

            $('#dja_end_date').on('change', function(e) {
                var d_date = $(this).val();
                if(d_date!=''){
                    var date = new Date(d_date);  // 2009-11-10
                    var month = date.toLocaleString('default', { month: 'long' });
                    var datenya = d_date.split("-");
                    var year = datenya[0];
                    var day = datenya[2];
                    var new_date = month+' '+day+', '+year;
                    $('#human_date').text(new_date);
                }else{
                    $('#human_date').text('');
                }
            });

            // $('#add_formula').on('change', function(e) {
            //     var value = $(this).val();
            //     if(value=='1'){
            //         $('.text_field1').slideDown();
            //         $('.text_field2').hide();
            //         $('.text_field3').hide();
            //     }else if(value=='2'){
            //         $('.text_field1').slideDown();
            //         $('.text_field2').slideDown();
            //         $('.text_field3').hide();
            //     }else if(value=='3'){
            //         $('.text_field1').slideDown();
            //         $('.text_field2').slideDown();
            //         $('.text_field3').slideDown();
            //     }else{
            //         $('.text_field1').hide();
            //         $('.text_field2').hide();
            //         $('.text_field3').hide();
            //     }
            // });
            

            // SET HUMAN DATE
            var d_date = $('#dja_end_date').val();
            if(d_date!=''){
                var date = new Date(d_date);  // 2009-11-10
                var month = date.toLocaleString('default', { month: 'long' });
                var datenya = d_date.split("-");
                var year = datenya[0];
                var day = datenya[2];
                var new_date = month+' '+day+', '+year;
                $('#human_date').text(new_date);
            }
            


            $(document).on("click", "#mceu_11-button", function(e) {

                

                e.preventDefault();
                    var image = wp.media({ 
                        title: 'Upload Image',
                        multiple: false
                    }).open()
                    .on('select', function(e){
                        var uploaded_image = image.state().get('selection').first();
                        var image_url = uploaded_image.toJSON().url;

                        new_image_url = image_url;

                        $.get(new_image_url)
                        .done(function() { 
                            // Do something now you know the image exists.
                            tinyMCE.activeEditor.insertContent('<img src="'+new_image_url+'" />');

                        }).fail(function() { 
                            // Image doesn't exist - do something else.
                            tinyMCE.activeEditor.insertContent('<img src="'+image_url+'" />');
                        });
                        
                    });
                // return false;
            });


            $('.edit-slug').click(function(e) {
                $('.box-slugnya').attr("contentEditable", 'true');
                setCaret();
            });

            $('#box-slugnya').on('keydown', function(e) {
                if (e.which === 13 && e.shiftKey === false) {
                    $(this).attr("contentEditable", 'false');
                    // run to save slug
                    return false;
                }
                if (event.keyCode == 32) {
                    return false;
                }
            });

            $('#upload_cover_image').click(function(e) {
                e.preventDefault();
                var image = wp.media({ 
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                .on('select', function(e){
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;


                    if(image_url.includes(".jpg")){
                        var imagenya = image_url.split(".jpg");
                        var new_image_url = imagenya[0]+"-650x350.jpg";
                    }
                    if(image_url.includes(".jpeg")){
                        var imagenya = image_url.split(".jpeg");
                        var new_image_url = imagenya[0]+"-650x350.jpeg";
                    }
                    if(image_url.includes(".png")){
                        var imagenya = image_url.split(".png");
                        var new_image_url = imagenya[0]+"-650x350.png";
                    }

                    $.get(new_image_url)
                    .done(function() { 
                        // Do something now you know the image exists.
                        $("#cover_image img").attr("src",new_image_url);

                    }).fail(function() { 
                        // Image doesn't exist - do something else.
                        $("#cover_image img").attr("src",image_url);
                    });
                    
                });
            });

            $(".target input, #packaged input").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return nilai = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });
                // run_other_nominal();
            });

            $(".text_field, .text_field_label").on("keydown", function(e){
                if (event.keyCode == 222) {
                    return false;
                }
            });

            $('input[type=radio][name=form_type]').change(function() {
                var val = this.value;
                console.log("ini"+val);
                if(val=='1' || val=='2' || val=='3' || val=='4'){
                    $('#uiform-image img').attr('src', "<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form"+val+".png");
                    if(val=='3'){
                        $(".opt_packaged").show();
                    }else{
                        $(".opt_packaged").hide();
                    }
                }
            });

            $('#preview_campaign').click(function(e) {
                var url = $('#longurl').attr('data-link');
                // window.location.href = url;
                window.open(url,"_blank","","")
            });

            $('#instant_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_instant_setting span').text('Active');
                }else{
                    $('#checkbox_instant_setting span').text('Not Active');
                }
            });

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

            $('#f_anonim').change(function() {
                if(this.checked) {
                    $('#checkbox_f_anonim span').text('Show');
                }else{
                    $('#checkbox_f_anonim span').text('Hide');
                }
            });
            $('#f_email').change(function() {
                if(this.checked) {
                    $('#checkbox_f_email span').text('Show');
                }else{
                    $('#checkbox_f_email span').text('Hide');
                }
            });
            $('#f_comment').change(function() {
                if(this.checked) {
                    $('#checkbox_f_comment span').text('Show');
                }else{
                    $('#checkbox_f_comment span').text('Hide');
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
            const subst2 =``;
            $("#fundraiser_commission_percent").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                const str=this.value;
                const result = str.replace(regex2, subst2);
                this.value=result;
            });

            $("#fundraiser_commission_fixed, #minimum_donate").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return valuenya = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "");
                });
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

            $('#pengeluaran_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_pengeluaran_setting span').text('Active');
                }else{
                    $('#checkbox_pengeluaran_setting span').text('Not Active');
                }
            });

            $('input[type=radio][name=zakat_setting]').change(function() {
                if (this.value == '0') {
                    $('.opt_zakat_percent').addClass('hide_zakat_percent');
                } else {
                    $('.opt_zakat_percent').removeClass('hide_zakat_percent');
                }
            });

            const regex = /[^\d.]|\.(?=.*\.)/g;
            const subst=``;
            $("#zakat_percent").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                const str=this.value;
                const result = str.replace(regex, subst);
                this.value=result;
            });

            $('.publish_update').click(function(e) {

                var act = $(this).attr('data-act');
                var all_content = tinyMCE.get('information').getContent();

                var campaign_id = $('#dja_campaignid').val();
                var title = $('#dja_title').val();
                var slug = $('#box-slugnya').text();
                var image_url = $('#dja_image_cover').attr('src');
                var information = all_content;
                var target = $('#dja_target').val();
                    target = target.replace(/\./g, '').replace(/\,/g, '');
                var end_date = $('#dja_end_date').val();
                var location_name = $('#dja_location_name').val();
                var location_gmaps = $('#dja_location_gmaps').val();
                var category_id = $('#category').find("option:selected").val();
                var publish_status = $('#publish_status').find("option:selected").val();
                var publish_status = $('#publish_status').find("option:selected").val();
                var form_base = $('input[name="form_base"]:checked').val();
                var form_type = $('input[name="form_type"]:checked').val();
                var packaged = $('#packaged input').val();
                    packaged = packaged.replace(/\./g, '').replace(/\,/g, '');
                var packaged_title = $('.packaged_title').val();

                var pengeluaran_setting = $('input[name="pengeluaran_setting"]:checked').val();
                var pengeluaran_title = $('#pengeluaran_title').val();

                if(pengeluaran_setting!=undefined){pengeluaran_setting = 1;}else{pengeluaran_setting = 0;}
                if(form_base!=undefined){form_base = 1;}else{form_base = 0;}

                var payment_status = $('input[name="payment_status"]:checked').val();
                if(payment_status=='1'){
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
                }else{
                    var bank_account = null;
                }

                var form_status = $('input[name="form_status"]:checked').val();
                var text1 = $('#text1').val();
                var text2 = $('#text2').val();
                var text3 = $('#text3').val();
                var text4 = $('#text4').val();
                var form_text = '{"text":["'+text1+'","'+text2+'","'+text3+'","'+text4+'"]}';

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

                var instant_setting = $("input#instant_setting:checked").val();
                if(instant_setting!=undefined){instant_setting = 1;}else{instant_setting = 0;}
                var va_setting = $("input#va_setting:checked").val();
                if(va_setting!=undefined){va_setting = 1;}else{va_setting = 0;}
                var transfer_setting = $("input#transfer_setting:checked").val();
                if(transfer_setting!=undefined){transfer_setting = 1;}else{transfer_setting = 0;}
                var method_status = '{"instant":"'+instant_setting+'","va":"'+va_setting+'","transfer":"'+transfer_setting+'"}';

                var notification_status = $('input[name="notification_status"]:checked').val();
                var wanotif_device = $('input[name="wanotif_device"]:checked').val();
                var wanotif_message = $('#wanotif_message').val();

                var pixel_status = $('input[name="pixel_status"]:checked').val();
                var fb_pixel = $("#fb_pixel").tagEditor('getTags')[0].tags.toString();
                var event_1 = $('#event_1').find(":selected").val();
                var event_2 = $('#event_2').find(":selected").val();
                var event_3 = $('#event_3').find(":selected").val();
                var fb_event = '{"event":["'+event_1+'","'+event_2+'","'+event_3+'"]}';

                var gtm_status = $('input[name="gtm_status"]:checked').val();
                var gtm_id = $("#gtm_id").val();
                var socialproof = $('input[name="socialproof"]:checked').val();
                var socialproof_text = $('#socialproof_text').val();
                var socialproof_position = $('#socialproof_position').find("option:selected").val();
                var tiktok_status = $('input[name="tiktok_status"]:checked').val();
                var tiktok_pixel = $("#tiktok_pixel").val();

                var zakat_setting = $('input[name="zakat_setting"]:checked').val();
                var zakat_percent = $('#zakat_percent').val();

                var fundraiser_setting = $('input[name="fundraiser_setting"]:checked').val();
                var fundraiser_on = $("input#fundraiser_on:checked").val();
                if(fundraiser_on!=undefined){fundraiser_on = 1;}else{fundraiser_on = 0;}
                var fundraiser_text = $("#fundraiser_text").val();
                var fundraiser_button = $("#fundraiser_button").val();
                var fundraiser_commission_on = $("input#fundraiser_commission_on:checked").val();
                if(fundraiser_commission_on!=undefined){fundraiser_commission_on = 1;}else{fundraiser_commission_on = 0;}
                var fundraiser_commission_type = $("input[type='radio'][name='fundraiser_commission_type']:checked").val();
                var fundraiser_commission_percent = $('#fundraiser_commission_percent').val();
                var fundraiser_commission_fixed = $('#fundraiser_commission_fixed').val();

                // additional

                var f_anonim = $("input#f_anonim:checked").val();
                if(f_anonim!=undefined){f_anonim = 1;}else{f_anonim = 0;}
                var f_email = $("input#f_email:checked").val();
                if(f_email!=undefined){f_email = 1;}else{f_email = 0;}
                var f_comment = $("input#f_comment:checked").val();
                if(f_comment!=undefined){f_comment = 1;}else{f_comment = 0;}
                var custom_field_setting = '{"anonim":'+f_anonim+',"email":'+f_email+',"comment":'+f_comment+'}';

                var additional_info = tinyMCE.get('additional_info').getContent();

                jlh_formula = 0;
                var new_selected_formula = [];
                $("input.text_field").each(function(){
                        var label = $(this).val();
                        new_selected_formula.push('{"label":"'+label+'","type":"input-text"}');
                        jlh_formula++;
                });

                if(jlh_formula!=0){
                    var additional_formula = '{"jumlah":'+jlh_formula+',"data":['+new_selected_formula+']}';
                }else{
                    var additional_formula = '{"jumlah":0,"data":[]}';
                }

                jlh_field = 0;
                var new_selected_field = [];
                $("input.text_field_label").each(function(){
                        var id = $(this).data('id');
                        var label = $('.field_'+id).find('input').val();
                        var type = $('.field_'+id).find("option:selected").val();
                        new_selected_field.push('{"label":"'+label+'","type":"'+type+'"}');
                        jlh_field++;
                });

                if(jlh_field!=0){
                    var additional_field = '{"jumlah":'+jlh_field+',"data":['+new_selected_field+']}';
                }else{
                    var additional_field = '{"jumlah":0,"data":[]}';
                }

                var general_status = $('input[name="general_status"]:checked').val();
                var allocation_title = $('#allocation_title').find("option:selected").val();
                var allocation_others_title = $('#allocation_others_title').val();
                var donatur_name = $('#donatur_name').find("option:selected").val();
                var donatur_others_name = $('#donatur_others_name').val();
                var home_icon_url = $('#home_icon_url').val();
                var back_icon_url = $('#back_icon_url').val();

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
                var minimum_donate = $('#minimum_donate').val();

                jlh_cs = 1;
                var new_selected_cs = [];
                $(".container_cs_box").each(function(){
                        var id = $(this).data('id');
                        var id_cs = $('#container_cs_'+id).find("option:selected").val();
                        if(id_cs=='0'){
                        }else{
                            var value_priority = $('#container_priority_'+id).find("option:selected").val();
                            new_selected_cs.push('"cs'+jlh_cs+'":["'+id_cs+'","'+value_priority+'"]');
                            jlh_cs++;
                        }

                });
                new_selected_cs = '{'+new_selected_cs+'}';

                if(jlh_cs!=1){
                    jlh_cs = jlh_cs-1;
                    var new_selected_cs = '{"jumlah":'+jlh_cs+',"data":'+new_selected_cs+'}';
                }else{
                    var new_selected_cs = '{"jumlah":0,"data":{}}';
                }

                if(act=='draft'){
                    publish_status = 0;
                    $(this).html('Save to Draft <span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');
                }else{
                    if(title==''){
                        $('#dja_title').addClass('set_red');
                    }else{
                        $('#dja_title').removeClass('set_red');
                    }

                    if(image_url.includes('donasiaja-cover.jpg')==true){
                        $('#dja_image_cover').addClass('set_red');
                    }else{
                        $('#dja_image_cover').removeClass('set_red');
                    }

                    if(information==''){
                        $('.mce-edit-area').addClass('set_red');
                    }else{
                        $('.mce-edit-area').removeClass('set_red');
                    }

                    <?php if($role!="administrator"){echo 'if(target=="" || target=="0"){$("#dja_target").addClass("set_red");}else{$("#dja_target").removeClass("set_red");}if(end_date==""){$("#dja_end_date").addClass("set_red");}else{$("#dja_end_date").removeClass("set_red");}';} ?>


                    <?php if($role!="administrator"){ echo "if(title=='' || image_url=='' || information=='' || target=='' || target=='0' || end_date==''){ Swal.fire({title: 'Warning!',text: 'Mohon lengkapi field yang bertanda merah.',imageUrl: '".plugin_dir_url( __FILE__ )."images/question-mark.png',imageWidth: 110,imageHeight: 110,confirmButtonText: 'OK',showClass: {popup: 'animated jackInTheBox faster'},hideClass: {popup: 'animated fadeOutUp faster'}});return false;}";}else{echo "if(title=='' || image_url=='' || information==''){ Swal.fire({title: 'Warning!',text: 'Mohon lengkapi field yang bertanda merah.',imageUrl: '".plugin_dir_url( __FILE__ )."images/question-mark.png',imageWidth: 110,imageHeight: 110,confirmButtonText: 'OK',showClass: {popup: 'animated jackInTheBox faster'},hideClass: {popup: 'animated fadeOutUp faster'}});return false;}";} ?>


                    if(target==''){
                        target = 0;
                    }

                    if(act=='publish'){
                        $(this).html('Publish <span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');
                    }else{
                        $(this).html('Update <span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');
                    }
                    
                }
                

                var data_nya = [
                    campaign_id,
                    title,
                    slug,
                    image_url,
                    information,
                    target,
                    end_date,
                    location_name,
                    location_gmaps,
                    category_id,
                    publish_status,
                    <?php echo $row->id; ?>,
                    form_base,
                    form_type,
                    packaged,
                    packaged_title,
                    act,
                    payment_status,
                    bank_account,
                    form_status,
                    form_text,
                    unique_number_setting,
                    unique_number_value,
                    method_status,
                    notification_status,
                    wanotif_message,
                    pixel_status,
                    fb_pixel,
                    fb_event,
                    pengeluaran_setting,
                    pengeluaran_title,
                    gtm_status,
                    gtm_id,
                    socialproof,
                    socialproof_text,
                    socialproof_position,
                    tiktok_status,
                    tiktok_pixel,
                    zakat_setting,
                    zakat_percent,
                    fundraiser_setting,
                    fundraiser_on,
                    fundraiser_text,
                    fundraiser_button,
                    fundraiser_commission_on,
                    fundraiser_commission_type,
                    fundraiser_commission_percent,
                    fundraiser_commission_fixed,
                    additional_info,
                    additional_formula,
                    additional_field,
                    custom_field_setting,
                    general_status,
                    allocation_title,
                    allocation_others_title,
                    donatur_name,
                    donatur_others_name,
                    home_icon_url,
                    back_icon_url,
                    new_selected_nominal_donasi,
                    minimum_donate,
                    new_selected_cs,
                    wanotif_device
                ];

                var data = {
                    "action": "djafunction_update_campaign",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    var response_text = response.split("_");
                    var info = response_text[0];
                    var idnya = response_text[1];

                    if(info=='0'){
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign') ?>");
                    }else if(info=='failed'){
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=edit&id=') ?>"+idnya+"&info=failed");
                    }else{
                        window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=edit&id=') ?>"+idnya+"&info=success");
                    }
                    
                });
            });

            // $(document).on("click", ".copylink", function(e) {
            $('.copylink').click(function(e) {
                var link_donasi = $(this).data("link");
                copyToClipboard(link_donasi);
                var message = "Copy link URL berhasil!";
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

            setTimeout(function() {
                $('#donasiaja-alert').slideUp('fast');
            }, 4000);

            $("#packaged_title input").keyup(function(){
                el = $(this);
                max_char = 24;
                if(el.val().length > max_char){
                    el.val( el.val().substr(0, max_char) );
                } else {
                    sisa = max_char-el.val().length;
                    $("#charNum").text('Sisa '+ sisa + ' char');
                }
            });

            $('input[type=radio][name=payment_status]').change(function() {
                if (this.value == '0') {
                    $('#data_method_status').hide();
                    $('#data_bank').hide();
                    $('#button_data_bank').hide();
                    $('#data_unique_number').hide();
                } else {
                    $('#data_method_status').show();
                    $('#data_bank').show();
                    $('#button_data_bank').show();
                    $('#data_unique_number').show();
                }
            });

            $('input[type=radio][name=form_status]').change(function() {
                if (this.value == '0') {
                    $('#form_text').hide();
                } else {
                    $('#form_text').show();
                }
            });

            $('input[type=radio][name=fundraiser_setting]').change(function() {
                if (this.value == '0') {
                    $('#fundraiser_setting').hide();
                } else {
                    $('#fundraiser_setting').show();
                }
            });

            $('input[type=radio][name=notification_status]').change(function() {
                if (this.value == '0') {
                    $('#wanotif_message_box').hide();
                } else {
                    $('#wanotif_message_box').show();
                }
            });

            $('input[type=radio][name=pixel_status]').change(function() {
                if (this.value == '0') {
                    $('#pixel_box').hide();
                } else {
                    $('#pixel_box').show();
                }
            });

            $('input[type=radio][name=gtm_status]').change(function() {
                if (this.value == '0') {
                    $('#gtm_box').hide();
                } else {
                    $('#gtm_box').show();
                }
            });

            $('input[type=radio][name=tiktok_status]').change(function() {
                if (this.value == '0') {
                    $('#tiktok_box').hide();
                } else {
                    $('#tiktok_box').show();
                }
            });

            $('input[type=radio][name=socialproof]').change(function() {
                if (this.value == '0') {
                    $('#socialproof_box').hide();
                } else {
                    $('#socialproof_box').show();
                }
            });

            $('input[type=radio][name=general_status]').change(function() {
                if (this.value == '0') {
                    $('#general_status_box').hide();
                } else {
                    $('#general_status_box').show();
                }
            });

            $(document).on("change", "#allocation_title", function(e) {
                var value = $(this).val();
                if(value==3){
                    $('.allocation_others_title').show();
                }else{
                    $('.allocation_others_title').hide();
                }
            });

            $(document).on("change", "#donatur_name", function(e) {
                var value = $(this).val();
                if(value==3){
                    $('.donatur_others_name').show();
                }else{
                    $('.donatur_others_name').hide();
                }
            });


            $(document).on("click", ".del_bank", function(e) {
                var randid = $(this).attr('data-randid');
                $('.bank_opt_'+randid).remove();
            });

            $(document).on("click", ".del_field", function(e) {
                var randid = $(this).attr('data-randid');
                $('.field_'+randid).remove();
            });

            $(document).on("click", ".del_field_cs", function(e) {
                var randid = $(this).attr('data-randid');
                $('#container_cs_'+randid).remove();
                run_persen_cs();
            });

            $(document).on("click", ".add_cs", function(e) {
                var randid = randMe(3);
                $('#box_cs').append('<div class="form-group row container_cs_box" id="container_cs_'+randid+'" data-id="'+randid+'"><div class="col-sm-5 col-data container_cs"><select class="form-control form-control-lg" title="CS"><option value="0">Select CS</option><?php echo $data_usercs; ?></select> </div><div class="col-sm-3 col-form-label container_priority '+randid+'"> <select class="form-control form-control-lg cs_priority" id="container_priority_'+randid+'" title="Priority" style="margin-top: -2px;" onclick="run_persen_cs()"><option value="1" selected="">1</option><option value="2">2</option> <option value="3">3</option> <option value="4">4</option> <option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> <option value="9">9</option> <option value="10">10</option> </select></div><div class="col-sm-2 col-form-label container_persen persen_'+randid+'" data-id="'+randid+'" title="Persentase"></div><div class="col-sm-2 col-form-label container_btn_del"> <button type="button" class="btn btn-danger del_field_cs" title="Delete" data-randid="'+randid+'" style="padding: 3px 8px;margin-top: -2px;"><i class="fas fa-minus" style="font-size: 11px;"></i></button> </div></div>');
                run_persen_cs();
                e.preventDefault();
            });

            function randMe(length, current) {
              current = current ? current : '';
              return length ? randMe(--length, "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz".charAt(Math.floor(Math.random() * 60)) + current) : current;
            }

            $('#add_bank').bind('click', function(e) {
                var randid = randMe(3);
            $('#data_bank').append('<div class="col-md-3 bank_opt_'+randid+' bank-col-1"><select class="form-control select_bank" id="" data-randid="'+randid+'" name="select_bank" style="height: 45px;" title="Bank"><?php echo $option_bank; ?></select></div><div class="col-md-2 bank_opt_'+randid+' bank-col-2"><div class="form-group"><input type="text" value="" class="form-control label_norek" id="opt_label_norek_'+randid+'" required="" placeholder="No Rekening" style="font-size: 13px;padding-left: 12px;" title="No Rekening"></div></div><div class="col-md-3 bank_opt_'+randid+' bank-col-3"><div class="form-group"><input type="text" value="" class="form-control label_an" id="opt_label_an_'+randid+'" required="" placeholder="Rek Atas Nama" style="font-size: 13px;padding-left: 12px;" title="Rek Atas Nama"></div></div><div class="col-md-3 bank_opt_'+randid+' bank-col-4"><div class="form-group"><select class="form-control" id="select_method_'+randid+'" data-randid="'+randid+'" name="select_method" style="height: 45px;" title="Payment Method"><option value="0">Pilih Method</option><option value="1">Instant</option><option value="2">VA</option><option value="3">Transfer</option></select></div></div><div class="col-md-1 bank_opt_'+randid+' bank-col-5"><button type="button" class="btn btn-danger del_bank" title="Delete" data-randid="'+randid+'" style="margin-top: 5px;"><i class="fas fa-minus"></i></button></div>'); 
            });

            $('#additional_formula').bind('click', function(e) {
                var randid = randMe(4);
            $('#box_additional_formula').append('<div class="col-md-7 field_'+randid+'"> <div class="form-group"> <input type="text" class="form-control text_field" value="" placeholder="Title"> </div></div><div class="col-md-3 field_'+randid+'"> <div class="form-group"> <button type="button" class="btn btn-danger del_field" title="Delete Field" data-randid="'+randid+'" style="margin-top: 5px;"><i class="fas fa-minus"></i></button> </div></div>'); 
            });

            $('#additional_field').bind('click', function(e) {
                var randid = randMe(4);
            $('#box_additional_field').append('<div class="col-md-7 field_'+randid+'"> <div class="form-group"> <input type="text" class="form-control text_field_label" value="" placeholder="Title" data-id="'+randid+'"> </div></div><div class="col-md-3 field_'+randid+'"> <div class="form-group"> <select class="form-control text_field_type" style="height: 45px;font-size: 13px;" title="Type"> <option value="">Pilih type</option> <option value="input-text">Text</option> <option value="input-number">Number</option> <option value="input-textarea">Textarea</option> </select> </div></div><div class="col-md-2 field_'+randid+'"> <div class="form-group"> <button type="button" class="btn btn-danger del_field" title="Delete Field" data-randid="'+randid+'" style="margin-top: 5px;"><i class="fas fa-minus"></i></button> </div></div>'); 
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

            <?php // if($row->form_base==null || $row->form_base=='0'){}else{echo '$("#uiform-image").hide();$(".opt_packaged").hide();';}?>
            
            $('.toggleSwitch').change(function() {
                // var form_type_value = <?php echo $row->form_type; ?>;
                // console.log(form_type_value);

                var form_base = $('input[name="form_base"]:checked').val();

                // var val = form_type_value;
                // console.log('disini '+val+" => "+form_base);

                if(form_base!=undefined){
                    // zakat ON
                    $('.section_zakat').show();
                    $('.section_donation').hide();
                    $('.opt_zakat_penghasilan').show();
                    $('input[name=form_type][value="4"]').prop( "checked", true );

                    // set hide option package
                    // $('#uiform-image').hide();
                    $(".opt_packaged").hide();
                    var val = 4;
                    $('#uiform-image img').attr('src', "<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form"+val+".png");

                }else{
                    // donation ON
                    $('.section_zakat').hide();
                    $('.section_donation').show();
                    $('.opt_zakat_penghasilan').hide();
                    // set value default

                    // jika bukan form zakat penghasilan
                    // if(form_type_value==1 || form_type_value==2 || form_type_value==3){
                    //     var val = form_type_value;
                    //     $('#uiform-image img').attr('src', "<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form"+val+".png");
                    //     if(val=='3'){
                    //         $(".opt_packaged").show();
                    //     }else{
                    //         $(".opt_packaged").hide();
                    //     }
                    //     $('input[name=form_type][value="' + form_type_value + '"]').prop( "checked", true );
                    //     $('#uiform-image').show();
                    // }

                    // if(form_type_value==4 || form_type_value==5){
                    //     $('input[name=form_type][value="1"]').prop( "checked", true );
                    //     $('#uiform-image').show();
                    // }

                    var val = 1;
                    $('input[name=form_type][value="' + val + '"]').prop( "checked", true );
                    $('#uiform-image img').attr('src', "<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form"+val+".png");
                     $('#uiform-image').show();

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
                                        <li class="breadcrumb-item"><a href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign') ?>">Data Campaign</a></li>
                                        <li class="breadcrumb-item active">Edit Campaign</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Campaign</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>

                    <!-- end page title end breadcrumb -->
                    <div class="row"> 
                        <div class="col-lg-8">
                            <div class="card" style="max-width: 100% !important;">
                                <div class="card-body">
                                    <?php 
                                    if(isset($_GET['info'])){
                                        if($_GET['info']=="success"){
                                            echo '
                                            <div id="donasiaja-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 25px;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                                </button>
                                                Update Campaign Success
                                            </div>
                                            ';
                                        }
                                    } ?>

                                    <h4 class="mt-0 header-title">Campaign</h4><br>
                                    
                                        <div class="row row-title">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="username">Title / Judul <span class="field_required">*</span></label>
                                                    <input type="text" class="form-control" id="dja_title" required="" value="<?php echo $row->title; ?>">
                                                    <input type="text" class="form-control" id="dja_campaignid" value="<?php echo $row->campaign_id; ?>" style="display: none;">
                                                </div>
                                            </div>
                                            <?php
                                            if($row->image_url==null){
                                                $image_url = plugin_dir_url( __FILE__ ).'images/donasiaja-cover.jpg';
                                            }else{
                                                $image_url = $row->image_url;
                                            }
                                            ?>
                                            <div class="col-md-4" id="cover_image" style="text-align: right;">
                                                <div class="fro-profile_main-pic-change" id="upload_cover_image" title="Add Image">
                                                    <i class="fas fa-camera"></i>
                                                </div>
                                                <img id="dja_image_cover" src="<?php echo $image_url; ?>" alt="" class="" height="100" title="Image Cover" data-action="zoom">
                                                                                             
                                            </div><!--end col-->
                                        </div>
                                        <div class="row">

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">                            
                                                <div class="form-group">
                                                    <br>
                                                    <label for="message">Information / Keterangan <span class="field_required">*</span></label>
                                                    <!-- <textarea class="form-control" rows="5" id="message"></textarea> -->
                                                    <textarea id="information" name="area"></textarea> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group target">
                                                    <label for="useremail">Target Donasi <span class="field_required">*</span></label>
                                                    <input type="text" class="form-control" id="dja_target" required="" placeholder="1.000.000" value="<?php echo number_format($row->target,0,",","."); ?>">
                                                    <div class="currency">Rp</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <?php 
                                                $datenya = explode(' ',$row->end_date)
                                                ?>
                                                <div class="form-group">
                                                    <label for="subject">Tanggal berakhir donasi <span class="field_required">*</span></label>
                                                    <input class="form-control" type="date" value="<?php echo $datenya[0]; ?>" id="dja_end_date">
                                                    <div id="human_date" class="form-text text-muted"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="subject">Location / Lokasi</label>
                                                    <input type="text" class="form-control" id="dja_location_name" required="" placeholder="Contoh: Bandung, Jawa Barat" value="<?php echo $row->location_name; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="username">Link Gmaps</label>
                                                    <input type="text" class="form-control" id="dja_location_gmaps" required="" value="<?php echo $row->location_gmaps; ?>">
                                                </div>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group target">
                                                    <label>Note:<br><span class="field_required">*</span> : Wajib diisi</label>
                                                </div>
                                            </div>
                                        </div>
                                    
                                </div><!--end card-body-->
                            </div><!--end card-->
                            <div class="card" style="max-width: 100% !important;padding-bottom: 40px;<?php if($role=="donatur"){echo "display:none;";}?>">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">Advanced Option</h4>
                                    <hr>
                                    <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="mt-0 header-title">Payment</h4>
                                                <br>
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-12" style="margin-top: -10px;">
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="0" id="customRadio10" name="payment_status" class="custom-control-input" <?php if($row->payment_status=='0' || $row->payment_status=='' || $row->payment_status==null) { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio10">Default</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="1" id="customRadio11" name="payment_status" class="custom-control-input" <?php if($row->payment_status=='1') { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio11">Custom</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div id="data_method_status" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->payment_status=='1') { echo '';}else{echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 0px 10px; border-top-left-radius: 8px; border-top-right-radius: 8px;background: #f6faff;">
                                            <div class="col-md-12" style="margin-bottom: 10px;">
                                                <h5 class="card-title mt-0">Payment Method</h5>
                                                <p class="card-text text-muted" style="margin-top: -5px;">Aktifkan sesuai dengan kebutuhan anda.</p> <br>
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-4">
                                                        <label for="subject">Instant Payment</label>
                                                        <div class="form-group" style="margin-top: 10px;">
                                                            <div class="custom-control custom-switch" id="checkbox_instant_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="instant_setting" data-id="1" <?php echo $checked1; ?> >
                                                                <label class="custom-control-label" for="instant_setting"><?php echo $status_text1; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="subject">VA Payment</label>
                                                        <div class="form-group" style="margin-top: 10px;">
                                                            <div class="custom-control custom-switch" id="checkbox_va_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="va_setting" data-id="1" <?php echo $checked2; ?> >
                                                                <label class="custom-control-label" for="va_setting"><?php echo $status_text2; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="subject">Transfer Payment</label>
                                                        <div class="form-group" style="margin-top: 10px;">
                                                            <div class="custom-control custom-switch" id="checkbox_transfer_setting">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="transfer_setting" data-id="1" <?php echo $checked3; ?> >
                                                                <label class="custom-control-label" for="transfer_setting"><?php echo $status_text3; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>

                                        <div class="row" id="data_bank" style="margin:0px; padding: 20px 10px 0px 10px;<?php if($row->payment_status=='1') { echo '';}else{echo 'display: none;';}?>background: #f6faff;">

                                            <div class="col-md-12" style="margin-bottom: 15px;">
                                                <h4 class="mt-0 header-title">Bank Account</h4>
                                            </div>

                                            <?php 
                                            $bank_account = json_decode($row->bank_account);

                                            if($bank_account==null || $bank_account==''){ ?>

                                            <?php }else{

                                            foreach ($bank_account as $key => $value) {

                                                $data_rekening = explode('_',$value);
                                                $no_rekening = $data_rekening[0];
                                                $an_rekening = $data_rekening[1];
                                                if(isset($data_rekening[2])){
                                                    $payment_method = $data_rekening[2];
                                                }else{
                                                    $payment_method = null;
                                                }
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

                                        <div class="row" id="button_data_bank" style="margin:0px 0px 0px 0px; padding: 0px 10px 30px 10px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;<?php if($row->payment_status=='1') { echo '';}else{echo 'display: none;';}?>background: #f6faff;">
                                            <div class="col-md-12" style="margin-top: 10px;">
                                                <button type="button" class="btn btn-outline-light waves-effect waves-light" id="add_bank">+ Add Bank</button>
                                            </div>
                                        </div>

                                        <div class="row" id="data_unique_number" style="margin:0px 0px 0px 0px;padding: 20px 10px 20px 10px;border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;<?php if($row->payment_status=='1') { echo '';}else{echo 'display: none;';}?>background: #f6faff;">
                                            <div class="col-md-12" style="margin-bottom: 10px;">
                                                <h5 class="card-title mt-0">Unique Number</h5>
                                                <p class="card-text text-muted" style="margin-top: -5px;">Gunakan sesuai kebutuhan, pilih <i>None</i> jika tidak ingin ada tambahan kode unik pada total.</p> 
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-12" style="padding-bottom: 5px;">
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="0" id="customRadio24" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='0' || $unique_number_setting==null) { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio24">None</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="1" id="customRadio23" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='1') { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio23">Fixed</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="2" id="customRadio22" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='2') { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio22">Range</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 unique_number_fixed" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='1') {}else{echo 'display:none;';}?>" id="">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="unique_number_fixed" required="" placeholder="Contoh: 57" value="<?php echo $unique_number_value['unique_number'][0]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 unique_number_range col-min" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="unique_number_range1" required="" placeholder="Min" value="<?php echo $unique_number_value['unique_number'][1]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                        </div>
                                                    </div>

                                                    <div class="unique_number_range" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                        <div class="form-group" style="text-align: center;padding-top: 12px;">
                                                            <p>:</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 unique_number_range col-max" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="unique_number_range2" required="" placeholder="Max" value="<?php echo $unique_number_value['unique_number'][2]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                        </div>
                                                    </div>


                                                </div>
                                                
                                            </div>
                                        </div>

                                    </div> <!-- end border -->

                                        

                                        <div class="row">
                                            <div class="col-md-12" style="margin-top: 15px;">
                                                <hr>
                                                <h4 class="mt-0 header-title" style="padding-top: 10px;">Form</h4>
                                                <br>
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-12" style="margin-top: -15px;">
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="0" id="customRadio12" name="form_status" class="custom-control-input" <?php if($row->form_status=='0' || $row->form_status=='' || $row->form_status==null) { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio12">Default</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="1" id="customRadio13" name="form_status" class="custom-control-input" <?php if($row->form_status=='1') { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio13">Custom</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div id="form_text" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->form_status=='1') { echo '';}else{echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">

                                            <div class="col-md-12" style="margin-bottom: 10px;margin-top:10px;">
                                                <h4 class="mt-0 header-title">Page Campaign</h4>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text1">Button 1</label>
                                                    <input type="text" class="form-control" id="text1" required="" value="<?php echo $text1; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-12" style="margin-top:5px;margin-bottom: 10px;">
                                            <hr>
                                            </div>

                                            <div class="col-md-12" style="margin-bottom: 10px;">
                                                <h4 class="mt-0 header-title">Page Form</h4>
                                            </div>

                                            
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text2">Button 2</label>
                                                    <input type="text" class="form-control" id="text2" required="" value="<?php echo $text2; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text3">Small Title Campaign</label>
                                                    <input type="text" class="form-control" id="text3" required="" value="<?php echo $text3; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="text4">Small Title Donate</label>
                                                    <input type="text" class="form-control" id="text4" required="" value="<?php echo $text4; ?>">
                                                </div>
                                            </div>

                                            

                                            <div class="col-md-12" style="margin-bottom: 10px;">
                                                <br>
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-4">
                                                        <label for="subject">Anonim</label>
                                                        <div class="form-group" style="margin-top: 10px;">
                                                            <div class="custom-control custom-switch" id="checkbox_f_anonim">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="f_anonim" data-id="1" <?php echo $checked7; ?> >
                                                                <label class="custom-control-label" for="f_anonim"><?php echo $status_text7; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="subject">Email</label>
                                                        <div class="form-group" style="margin-top: 10px;">
                                                            <div class="custom-control custom-switch" id="checkbox_f_email">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="f_email" data-id="1" <?php echo $checked8; ?> >
                                                                <label class="custom-control-label" for="f_email"><?php echo $status_text8; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="subject">Comment</label>
                                                        <div class="form-group" style="margin-top: 10px;">
                                                            <div class="custom-control custom-switch" id="checkbox_f_comment">
                                                                <input type="checkbox" class="custom-control-input checkbox1" id="f_comment" data-id="1" <?php echo $checked9; ?> >
                                                                <label class="custom-control-label" for="f_comment"><?php echo $status_text9; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>


                                            <div class="col-md-12" style="margin-top:5px;margin-bottom: 10px;">
                                            <hr>
                                            </div>


                                            <div class="col-md-12" style="margin-bottom: 10px;margin-top: 10px;">
                                                <h4 class="mt-0 header-title">Pilihan Nominal Donasi</h4>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <?php if($row->opt_nominal=='' || $row->opt_nominal=='{}'){
                                                        $opt_nominal='{"opt1":["10000","Rp 10rb","0"],"opt2":["25000","Rp 25rb","0"],"opt3":["50000","Rp 50rb","1"],"opt4":["100000","Rp 100rb","0"]}';
                                                        $opt_nominal  = json_decode($opt_nominal, true);
                                                        }else{
                                                            $opt_nominal  = json_decode($row->opt_nominal, true);
                                                        }
                                                    ?>
                                                    <?php $i=1; foreach ($opt_nominal as $key => $value) { ?>
                                                    <div class="row box_nominal_donasi" style="padding-left:0px;">
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
                                            </div>

                                            <div class="col-md-12" style="margin-bottom: 10px;">
                                                <h4 class="mt-0 header-title">Minimum Donasi</h4>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="minimum_donate" required="" value="<?php echo $row->minimum_donate; ?>">
                                                    
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group" style="margin-top: -20px;margin-bottom: 20px;">
                                                    <p class="text-muted" style="padding-top: 10px;">Nominal Donasi yang diperbolehkan ketika donatur mengetik donasi pada form.</p>
                                                </div>
                                            </div>

                                            <div class="col-md-12" style="margin-top:0px;margin-bottom: 10px;">
                                            <hr>
                                            </div>

                                            <div class="col-md-12" style="margin-bottom:0px;">
                                                <h4 class="mt-0 header-title">Additional Field Input</h4>
                                                <p class="text-muted">Anda bisa menambahkan field input dan textarea pada form donasi.</p>
                                            </div>

                                            <div class="row" id="box_additional_field" style="display:contents;">
                                                <?php 

                                                if($jumlah_field!=0){
                                                    foreach ($additional_field['data'] as $key => $value) { 

                                                    $randid = d_randomString(4);
                                                    ?>

                                                    <div class="col-md-7 field_<?php echo $randid; ?>">
                                                        <div class="form-group"> 
                                                            <input type="text" class="form-control text_field_label" value="<?php echo $value['label']; ?>" placeholder="Title" data-id="<?php echo $randid; ?>">
                                                        </div> 
                                                    </div>
                                                    <div class="col-md-3 field_<?php echo $randid; ?>">
                                                        <div class="form-group">
                                                            <select class="form-control text_field_type" style="height: 45px;font-size: 13px;" title="Type">
                                                                <option value="">Pilih type</option>
                                                                <option value="input-text" <?php if($value['type']=='input-text'){echo'selected';} ?>>Text</option>
                                                                <option value="input-number" <?php if($value['type']=='input-number'){echo'selected';} ?>>Number</option>
                                                                <option value="input-textarea" <?php if($value['type']=='input-textarea'){echo'selected';} ?>>Textarea</option>
                                                            </select>
                                                        </div> 
                                                    </div>
                                                     <div class="col-md-2 field_<?php echo $randid; ?>">
                                                        <div class="form-group"> 
                                                            <button type="button" class="btn btn-danger del_field" title="Delete Field" data-randid="<?php echo $randid; ?>" style="margin-top: 5px;"><i class="fas fa-minus"></i></button>
                                                        </div>
                                                    </div>
                                                        
                                                <?php } } ?>
                                            </div>

                                            <div class="col-md-12" style="margin-top: 5px;">
                                                <button type="button" class="btn btn-outline-light waves-effect waves-light" id="additional_field">+ Add Field</button>
                                            </div>
                                            

                                            <div class="col-md-12" style="margin-top:5px;margin-bottom: 10px;">
                                            <hr>
                                            </div>

                                            <div class="col-md-12" style="margin-bottom:0px;">
                                                <h4 class="mt-0 header-title">Additional Field Formula</h4>
                                                <p class="text-muted">Anda bisa menambahkan field input pada form yang langsung berefek pada jumlah donasi. Misal anda sedang membuat Form Zakat dan menambahkan 1 field input nominal Sedekah pada form campaign ini. Field ini hanya berlaku pada Form type Donation (Card, Typing, dan Packaged) saja.</p>
                                            </div>

                                            <div class="row" id="box_additional_formula" style="display:contents;">
                                                <?php 

                                                if($jumlah_formula!=0){
                                                foreach ($additional_formula['data'] as $key => $value) { 

                                                    $randid = d_randomString(4);
                                                    ?>

                                                    <div class="col-md-7 field_<?php echo $randid; ?>">
                                                        <div class="form-group"> 
                                                            <input type="text" class="form-control text_field" value="<?php echo $value['label']; ?>" placeholder="Title">
                                                        </div> 
                                                    </div>
                                                     <div class="col-md-3 field_<?php echo $randid; ?>">
                                                        <div class="form-group"> 
                                                            <button type="button" class="btn btn-danger del_field" title="Delete Field" data-randid="<?php echo $randid; ?>" style="margin-top: 5px;"><i class="fas fa-minus"></i></button>
                                                        </div>
                                                    </div>
                                                        
                                                <?php } } ?>
                                            </div>

                                            <div class="col-md-12" style="margin-top: 5px;">
                                                <button type="button" class="btn btn-outline-light waves-effect waves-light" id="additional_formula">+ Add Field</button>
                                            </div>
                                            

                                            

                                            <div class="col-md-12" style="margin-top:5px;margin-bottom: 10px;">
                                            <hr>
                                            </div>

                                            <div class="col-md-12" style="margin-bottom:10px;">
                                                <h4 class="mt-0 header-title">Additional Info</h4>
                                            </div>
                                            <div class="col-md-12">
                                               <div class="form-group">
                                                    <textarea id="additional_info" name="area"></textarea> 
                                                </div>
                                            </div>

                                            

                                        </div>

                                    </div> <!-- end border -->



                                    <div class="row" style="<?php if( $plugin_license=='ULTIMATE') {}else{echo'display:none;';}?>">
                                        <div class="col-md-12" style="margin-top: 15px;">
                                            <hr>
                                            <h4 class="mt-0 header-title" style="padding-top: 10px;">Fundraising</h4>
                                            <br>
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-12" style="margin-top: -15px;">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="customRadio29" name="fundraiser_setting" class="custom-control-input" <?php if($row->fundraiser_setting=='0' || $row->fundraiser_setting=='' || $row->fundraiser_setting==null) { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio29">Default</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="customRadio30" name="fundraiser_setting" class="custom-control-input" <?php if($row->fundraiser_setting=='1') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio30">Custom</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="fundraiser_setting" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->fundraiser_setting=='1') { echo '';}else{echo 'display: none;';}?><?php if( $plugin_license=='ULTIMATE') {}else{echo'display:none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">
<!-- 
                                            <div class="col-md-12" style="margin-bottom: 10px;">
                                                <h4 class="mt-0 header-title">Form Text</h4>
                                            </div> -->

                                            <div class="col-md-12">
                                                <label for="subject" class="title-on-custom">Fundraising Mode</label>
                                                <div class="form-group" style="margin-top: 10px;">
                                                    <div class="custom-control custom-switch" id="checkbox_fundraiser_on">
                                                        <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_on" data-id="1" <?php echo $checked5; ?> >
                                                        <label class="custom-control-label" for="fundraiser_on"><?php echo $status_text5; ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12" style="margin-top: -10px;margin-bottom: 30px;">
                                            <!-- <hr> -->
                                            </div>

                                            
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="text1" class="" style="margin-bottom: 15px;">Text Description</label>
                                                    <textarea class="form-control" rows="3" id="fundraiser_text" style="font-size: 13px;"><?php echo $row->fundraiser_text; ?></textarea>

                                                    <!-- <div class="form-text text-muted">Button on Form Donate</div> -->
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="text2" class="" style="margin-bottom: 15px;">Button</label>
                                                    <input type="text" class="form-control" id="fundraiser_button" required="" value="<?php echo $row->fundraiser_button; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-12" style="margin-top:5px;margin-bottom: 10px;">
                                            <hr>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="subject" class="title-on-custom">Add Commission</label>
                                                <div class="form-text text-muted">Aktifkan jika ingin memberi komisi pada Fundraiser pada campaign ini.</div>
                                                <div class="form-group" style="margin-top: 10px;">
                                                    <div class="custom-control custom-switch" id="checkbox_fundraiser_commission_on">
                                                        <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_commission_on" data-id="1" <?php echo $checked6; ?> >
                                                        <label class="custom-control-label" for="fundraiser_commission_on"><?php echo $status_text6; ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12" style="margin-top: 20px;">
                                                
                                                <div class="col-md-12" style="margin-bottom: 10px;">
                                                
                                                
                                                    <div class="form-group mb-0 row">
                                                        <div class="col-md-7" style="padding-bottom: 5px;padding-left: 0;">
                                                            <label class="card-title mt-0 title-on-custom" style="padding-top: 0px;">Commission Type<span></span></label>
                                                            <p class="card-text text-muted" style="margin-top:0px;">Pilih tipe komisi yang ingin anda berikan pada Fundraiser.</p> 
                                                            <div class="form-check-inline my-1">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" value="0" id="customRadio31" name="fundraiser_commission_type" class="custom-control-input" <?php if($row->fundraiser_commission_type=='0') { echo 'checked=""';}?> >
                                                                    <label class="custom-control-label" for="customRadio31">Percent</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-check-inline my-1">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" value="1" id="customRadio32" name="fundraiser_commission_type" class="custom-control-input" <?php if($row->fundraiser_commission_type=='1') { echo 'checked=""';}?> >
                                                                    <label class="custom-control-label" for="customRadio32">Fixed</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5 fundraiser_commission_percent" style="margin-bottom:0;<?php if($row->fundraiser_commission_type=='0') {}else{echo 'display:none;';}?>" id="">
                                                            <div class="form-group">
                                                                <label class="">Percent : </label>
                                                                <input type="text" class="form-control" id="fundraiser_commission_percent" required="" placeholder="Contoh: 10" value="<?php echo $row->fundraiser_commission_percent; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="5">
                                                                <p class="card-text text-muted" style="margin-top:8px;">Range 0-100
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5 fundraiser_commission_fixed" style="margin-bottom:0;<?php if($row->fundraiser_commission_type=='1') {}else{echo 'display:none;';}?>" id="">
                                                            <div class="form-group">
                                                                <label class="">Fixed :</label>
                                                                <input type="text" class="form-control" id="fundraiser_commission_fixed" required="" placeholder="Contoh: 2000" value="<?php echo $row->fundraiser_commission_fixed; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="10">
                                                                <p class="card-text text-muted" style="margin-top:8px;">Tuliskan 2000 jika anda ingin memberikan setiap komisi 2000.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        


                                                    </div>
                                                
                                                </div>
                                            </div>


                                        </div>

                                    </div> <!-- end border -->



                                        <div class="row">
                                            <div class="col-md-12" style="margin-top: 15px;">
                                                <hr>
                                                <h4 class="mt-0 header-title" style="padding-top: 10px;">Notification</h4>
                                                <br>
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-12" style="margin-top: -15px;">
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="0" id="customRadio14" name="notification_status" class="custom-control-input" <?php if($row->notification_status=='0' || $row->notification_status=='' || $row->notification_status==null) { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio14">Default</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="1" id="customRadio15" name="notification_status" class="custom-control-input" <?php if($row->notification_status=='1') { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio15">Custom</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div id="wanotif_message_box" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->notification_status=='1') { echo '';}else{echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">

                                            <div class="col-md-12" style="margin-bottom:0px;">
                                                <h4 class="mt-0 header-title">Wanotif Device</h4>
                                            </div>

                                            <div class="col-md-9">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" value="0" id="customRadioW1" name="wanotif_device" class="custom-control-input" <?php if($row->wanotif_device=='0' || $row->wanotif_device=='' || $row->wanotif_device==null) { echo 'checked=""';}?> >
                                                        <label class="custom-control-label" for="customRadioW1">Default</label>
                                                    </div>
                                                </div>
                                                <?php if($plugin_license=='ULTIMATE') { ?>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" value="1" id="customRadioW2" name="wanotif_device" class="custom-control-input" <?php if($row->wanotif_device=='1') { echo 'checked=""';}?>>
                                                        <label class="custom-control-label" for="customRadioW2">CS Rotator</label>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>

                                            <div class="col-md-12" style="margin-bottom: 10px;margin-top: 30px;">
                                                <h4 class="mt-0 header-title">Wanotif Message</h4>
                                            </div>

                                            
                                            <div class="col-md-12">
                                               <div class="form-group">
                                                    <textarea class="form-control" rows="6" id="wanotif_message" style="font-size: 13px;"><?php echo $row->wanotif_message; ?></textarea>
                                                    <br>
                                                    <p class="text-muted">Beberapa shortcode yang bisa digunakan: <br>{name}, {email}, {whatsapp}, {comment}, {total}, {payment_number}, {payment_code}, {payment_account}, {campaign_title}, {date}, {fundraiser}, {invoice_id} dan {cs_name} hanya untuk CS Rotator.</p>
                                                </div>
                                            </div>

                                        </div>

                                    </div> <!-- end border -->

                                        <div class="row">
                                            <div class="col-md-12" style="margin-top: 15px;">
                                                <hr>
                                                <h4 class="mt-0 header-title" style="padding-top: 10px;">Facebook Pixel</h4>
                                                <br>
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-12" style="margin-top: -15px;">
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="0" id="customRadio16" name="pixel_status" class="custom-control-input" <?php if($row->pixel_status=='0' || $row->pixel_status=='' || $row->pixel_status==null) { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio16">Default</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="1" id="customRadio17" name="pixel_status" class="custom-control-input" <?php if($row->pixel_status=='1') { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio17">Custom</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div id="pixel_box" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->pixel_status=='1') { echo '';}else{echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">
                                            
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


                                        </div>

                                    </div> <!-- end border -->

                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 15px;">
                                            <hr>
                                            <h4 class="mt-0 header-title" style="padding-top: 10px;">Tiktok Pixel</h4>
                                            <br>
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-12" style="margin-top: -15px;">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="customRadio25" name="tiktok_status" class="custom-control-input" <?php if($row->tiktok_status=='0' || $row->tiktok_status=='' || $row->tiktok_status==null) { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio25">Default</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="customRadio26" name="tiktok_status" class="custom-control-input" <?php if($row->tiktok_status=='1') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio26">Custom</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="tiktok_box" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->tiktok_status=='1') { echo '';}else{echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">

                                            
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

                                        </div>
                                    </div> <!-- end border -->

                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 15px;">
                                            <hr>
                                            <h4 class="mt-0 header-title" style="padding-top: 10px;">Google Tag Manager</h4>
                                            <br>
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-12" style="margin-top: -15px;">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="customRadio18" name="gtm_status" class="custom-control-input" <?php if($row->gtm_status=='0' || $row->gtm_status=='' || $row->gtm_status==null) { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio18">Default</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="customRadio19" name="gtm_status" class="custom-control-input" <?php if($row->gtm_status=='1') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio19">Custom</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="gtm_box" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->gtm_status=='1') { echo '';}else{echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">

                                            
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
                                    </div> <!-- end border -->

                                    
                                    <div class="row">
                                            <div class="col-md-12" style="margin-top: 15px;">
                                                <hr>
                                                <h4 class="mt-0 header-title" style="padding-top: 10px;">Social Proof</h4>
                                                <br>
                                                <div class="form-group mb-0 row">
                                                    <div class="col-md-12" style="margin-top: -15px;">
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="0" id="customRadio20" name="socialproof" class="custom-control-input" <?php if($row->socialproof=='0' || $row->socialproof=='' || $row->socialproof==null) { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio20">Hide</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check-inline my-1">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="1" id="customRadio21" name="socialproof" class="custom-control-input" <?php if($row->socialproof=='1') { echo 'checked=""';}?> >
                                                                <label class="custom-control-label" for="customRadio21">Show</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    <div id="socialproof_box" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->socialproof=='1') { echo '';}else{echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">

                                            
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="text1">Text Description</label>
                                                        <input type="text" class="form-control" id="socialproof_text" required="" value="<?php echo $row->socialproof_text; ?>" placeholder="">
                                                        <p class="col-sm-12 form-text text-muted" style="padding-left: 0;margin-top: 8px;">{campaign_title} : Judul campaign</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="event_3">Position</label>
                                                            <select class="form-control" id="socialproof_position" name="socialproof_position" style="height: 45px;font-size: 13px;" title="Event">
                                                                <option value="top_left" <?php if($row->socialproof_position=='top_left'){echo 'selected';}?>>Top - Left</option>
                                                                <option value="top_right" <?php if($row->socialproof_position=='top_right'){echo 'selected';}?>>Top - Right</option>
                                                                <option value="bottom_left" <?php if($row->socialproof_position=='bottom_left'){echo 'selected';}?>>Bottom - Left</option>
                                                                <option value="bottom_right" <?php if($row->socialproof_position=='bottom_right'){echo 'selected';}?>>Bottom - Right</option>
                                                            </select>
                                                        </div>
                                                </div>

                                        </div>
                                    </div> <!-- end border -->

                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 15px;">
                                            <hr>
                                            <h4 class="mt-0 header-title" style="padding-top: 10px;">General</h4>
                                            <br>
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-12" style="margin-top: -15px;">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="customRadio33" name="general_status" class="custom-control-input" <?php if($row->general_status=='0' || $row->general_status=='' || $row->general_status==null) { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio33">Default</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="customRadio34" name="general_status" class="custom-control-input" <?php if($row->general_status=='1') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio34">Custom</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="general_status_box" style="border: 1px solid #d6e0ec;margin:20px 0px 10px 0px;border-radius: 8px;<?php if($row->general_status!='1'){echo 'display: none;';}?>">

                                        <div class="row" style="margin: 0px 0px 0px 0px; padding: 30px 10px 30px 10px; border-radius: 8px;background: #f6faff;">

                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="text1">Home Url on Campaign</label>
                                                        <input type="text" class="form-control" id="home_icon_url" required="" value="<?php echo $row->home_icon_url; ?>" placeholder="https://">
                                                        <p class="col-sm-12 form-text text-muted" style="padding-left: 0;margin-top: 8px;">Silahkan di isi custom link, jika memiliki page tersendiri.</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="text1">Back Url on Form</label>
                                                        <input type="text" class="form-control" id="back_icon_url" required="" value="<?php echo $row->back_icon_url; ?>" placeholder="https://">
                                                        <p class="col-sm-12 form-text text-muted" style="padding-left: 0;margin-top: 8px;">Silahkan di isi custom link, jika memiliki page tersendiri.</p>
                                                        <br>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="event_3">Campaign for (title)</label>
                                                        <select class="form-control" id="allocation_title" name="allocation_title" style="height: 45px;font-size: 13px;" title="Allocation Title">
                                                            <option value="0" <?php if($row->allocation_title=='0'){echo 'selected';}?>>Pilih</option>
                                                            <option value="1" <?php if($row->allocation_title=='1'){echo 'selected';}?>>Donasi</option>
                                                            <option value="2" <?php if($row->allocation_title=='2'){echo 'selected';}?>>Zakat</option>
                                                            <option value="3" <?php if($row->allocation_title=='3'){echo 'selected';}?>>Custom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group allocation_others_title" <?php if($row->allocation_title!='3'){echo 'style="display:none;"';}?>>
                                                        <label for="text1">Custom title</label>
                                                        <input type="text" class="form-control" id="allocation_others_title" required="" value="<?php echo $row->allocation_others_title; ?>" placeholder="">
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="event_3">Donatur name</label>
                                                        <select class="form-control" id="donatur_name" name="donatur_name" style="height: 45px;font-size: 13px;" title="Allocation Title">
                                                            <option value="0" <?php if($row->donatur_name=='0'){echo 'selected';}?>>Pilih</option>
                                                            <option value="1" <?php if($row->donatur_name=='1'){echo 'selected';}?>>Donatur</option>
                                                            <option value="2" <?php if($row->donatur_name=='2'){echo 'selected';}?>>Muzakki</option>
                                                            <option value="3" <?php if($row->donatur_name=='2'){echo 'selected';}?>>Custom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 donatur_others_name" <?php if($row->donatur_others_name!='2'){echo 'style="display:none;"';}?>>
                                                    <div class="form-group">
                                                        <label for="text1">Custom name</label>
                                                        <input type="text" class="form-control" id="donatur_others_name" required="" value="<?php echo $row->donatur_others_name; ?>" placeholder="">
                                                    </div>
                                                </div>

                                        </div>
                                    </div> <!-- end border -->


                                </div>
                            </div>

                        </div><!--end col-->  

                        <?php

                        // $row->publish_status;

                        if($row->publish_status==0){
                            $pub_status = 'Draft';
                        }elseif($row->publish_status==1){
                            $pub_status = 'Published';
                        }elseif($row->publish_status==2){
                            $pub_status = 'Pending Review';
                        }else{
                            $pub_status = '';
                        }

                        $option = '<option value="0">Uncategorized</option>';
                        foreach ($row2 as $key => $value) {

                            if($role=='donatur'){
                                if($value->private_category!='1'){
                                    if($row->category_id==$value->id){
                                        $option .= '<option value="'.$value->id.'" selected>'.$value->category.'</option>';
                                    }else{
                                        $option .= '<option value="'.$value->id.'">'.$value->category.'</option>';
                                    }
                                }
                            }else{
                                if($row->category_id==$value->id){
                                    $option .= '<option value="'.$value->id.'" selected>'.$value->category.'</option>';
                                }else{
                                    $option .= '<option value="'.$value->id.'">'.$value->category.'</option>';
                                }
                            }
                            

                        }


                        $set_hide = '';
                        if($role!="administrator"){
                            $set_hide = 'style="display:none;"';
                        }


                        ?>
                        <div class="col-lg-4">
                            <div class="card" <?php if($form_setting!="1" && $role!='administrator'){echo 'style="display:none;"';}?>>
                                <div class="card-body" id="form-section">
                                    <h4 class="mt-0 header-title">Form Type</h4><br>
                                    <div class="form-group row" style="padding-left: 10px;padding-right: 10px;<?php if($role!="administrator"){echo 'display:none;';}?>">
                                        <label class="toggleSwitch nolabel">
                                            <input type="checkbox" name="form_base" value="1" <?php if($row->form_base==null || $row->form_base=='0'){ echo ''; }else{ echo 'checked=""'; }?>/>
                                            <a></a>
                                            <span>
                                                <span class="left-span">Donation</span>
                                                <span class="right-span">Zakat</span>
                                            </span>                                         
                                        </label>
                                    </div>
                                    <div class="form-group row">
                                            <div class="section_donation" <?php if($row->form_base==null || $row->form_base=='0'){}else{echo 'style="display:none;"';}?>>
                                                <div class="radio radio-primary form-check-inline" style="margin-left: 8px;">
                                                    <input type="radio" id="inlineRadio1" value="1" name="form_type" <?php if($row->form_type==null || $row->form_type=='1'){ echo 'checked=""'; }?>>
                                                    <label for="inlineRadio1"> Card </label>
                                                </div>

                                                <?php if($plugin_license=='PRO' || $plugin_license=='ULTIMATE') { ?>
                                                <div class="radio radio-primary form-check-inline">
                                                    <input type="radio" id="inlineRadio2" value="2" name="form_type" <?php if($row->form_type=='2'){ echo 'checked=""'; }?>>
                                                    <label for="inlineRadio2"> Typing </label>
                                                </div>
                                                <div class="radio radio-primary form-check-inline">
                                                    <input type="radio" id="inlineRadio3" value="3" name="form_type" <?php if($row->form_type=='3'){ echo 'checked=""'; }?>>
                                                    <label for="inlineRadio3"> Packaged </label>
                                                </div>
                                                <?php } ?>
                                            </div>

                                            <div class="section_zakat" <?php if($row->form_base=='1'){echo 'style="width:100%;"';}else{ echo 'style="display:none;width:100%;"'; }?>>
                                                <div class="radio radio-primary form-check-inline" style="margin-left: 8px;">
                                                    <input type="radio" id="inlineRadio4" value="4" name="form_type" <?php if($row->form_type=='4'){ echo 'checked=""'; }?>>
                                                    <label for="inlineRadio4"> Zakat Penghasilan </label>
                                                </div>
                                                <!-- <div class="radio radio-primary form-check-inline" style="margin-left: 8px;">
                                                    <input type="radio" id="inlineRadio5" value="5" name="form_type" <?php if($row->form_type=='5'){ echo 'checked=""'; }?>>
                                                    <label for="inlineRadio5"> Zakat Maal </label>
                                                </div> -->
                                            </div>

                                            <?php 
                                                if($row->form_type==null || $row->form_type=='1'){ 
                                                    $ui_type = '1'; 
                                                    $opt_packaged = '';
                                                }elseif($row->form_type=='2'){
                                                    $ui_type = '2'; 
                                                    $opt_packaged = '';
                                                }elseif($row->form_type=='4'){
                                                    $ui_type = '4'; 
                                                    $opt_packaged = '';
                                                }else{
                                                    $ui_type = '3';
                                                    $opt_packaged = 'display:inline;';
                                                }

                                                if($row->form_type=='4'){
                                                    $opt_pengeluaran = '';
                                                }else{
                                                    $opt_pengeluaran = 'display:none;';
                                                }

                                                if($row->zakat_setting=='1') {
                                                    $show_zakat_percent = '';
                                                }else{
                                                    $show_zakat_percent = 'hide_zakat_percent';
                                                }
                                                
                                            ?>
                                            
                                            <!-- UI Image -->
                                            <div id="uiform-image" class="col-sm-12" style="padding-top:15px;padding-bottom: 10px;">
                                                <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form<?php echo $ui_type; ?>.png" style="width: 100%;border-radius: 4px;border: 1px solid #d4d6f0;">
                                            </div>

                                            <!-- Option Type Package -->
                                            <label class="col-sm-4 col-form-label dja_label opt_packaged" style="text-align: left !important;cursor: default;margin-top: 20px;<?php echo $opt_packaged; ?>">Nominal</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_packaged" style="cursor: default;margin-top: 20px;<?php echo $opt_packaged; ?>">:</label>
                                            <div id="packaged" class="col-sm-7 opt_packaged nominal_packaged" style="margin-top: 20px;<?php echo $opt_packaged; ?>">
                                                <input type="text" name="packaged" placeholder="100.000" value="<?php echo number_format($row->packaged,0,",","."); ?>" class="form-control" style="height: 32px;">
                                                <div class="currency">Rp</div>
                                            </div>

                                            <label class="col-sm-4 col-form-label dja_label opt_packaged label_title" style="text-align: left !important;cursor: default;margin-top: 20px;<?php echo $opt_packaged; ?>">Title</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_packaged" style="cursor: default;margin-top: 20px;<?php echo $opt_packaged; ?>">:</label>
                                            <div id="packaged_title" class="col-sm-7 opt_packaged title_packaged" style="margin-top: 20px;<?php echo $opt_packaged; ?>">
                                                <input type="text" name="packaged_title" placeholder="Paket Donasi" value="<?php echo $row->packaged_title; ?>" class="form-control packaged_title" style="height: 32px;" maxlength="24">
                                                <div class="box-char"><div id="charNum" style="font-size:11px;margin-top: 3px;text-align: right;margin-right: 4px;">&nbsp;</div></div>
                                            </div>


                                            <!-- Option Zakat Penghasilan => Zakat setting -->
                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan" style="text-align: left !important;cursor: default;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">Zakat Percent</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan" style="cursor: default;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">:</label>
                                            <div class="col-sm-6 opt_zakat_penghasilan" style="margin-top: 10px;margin-left: 22px;<?php echo $opt_pengeluaran; ?>">
                                                <div class="form-check-inline my-1" style="display: inline-block;padding-top: 4px;">
                                                    <div class="custom-control custom-radio" style="display: contents;">
                                                        <input type="radio" value="0" id="customRadio27" name="zakat_setting" class="custom-control-input" <?php if($row->zakat_setting=='0' || $row->zakat_setting=='' || $row->zakat_setting==null) { echo 'checked=""';}?> >
                                                        <label class="custom-control-label" for="customRadio27" style="font-size: 12px;padding-top: 2px !important;">Default</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" value="1" id="customRadio28" name="zakat_setting" class="custom-control-input" <?php if($row->zakat_setting=='1') { echo 'checked=""';}?> >
                                                        <label class="custom-control-label" for="customRadio28" style="font-size: 12px;padding-top: 2px !important;">Custom</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan opt_title opt_zakat_percent <?php echo $show_zakat_percent; ?>" style="text-align: left !important;cursor: default;padding-right:0;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">Custom&nbsp;Percent</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan opt_zakat_percent <?php echo $show_zakat_percent; ?>" style="cursor: default;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">:</label>
                                            <div id="t_opt_zakat_penghasilan" class="col-sm-7 opt_zakat_penghasilan opt_zakat_percent <?php echo $show_zakat_percent; ?>" style="margin-top: 10px;<?php echo $opt_pengeluaran; ?>">
                                                <input type="text" name="zakat_percent" id="zakat_percent" placeholder="2.5" value="<?php echo $row->zakat_percent; ?>" class="form-control zakat_percent" style="height: 32px;" maxlength="5">
                                            </div>

                                            <div class="col-sm-12 opt_zakat_penghasilan" style="border-bottom:1px solid #e7eef7;margin: 5px 10px 5px 10px;<?php echo $opt_pengeluaran; ?>">&nbsp;</div>

                                            <!-- Pengeluaran kebutuhan pokok (termasuk utang jatuh tempo) -->
                                            <!-- Option Zakat Penghasilan -->
                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan" style="text-align: left !important;cursor: default;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">Pengeluaran</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan" style="cursor: default;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">:</label>
                                            <div id="c_opt_zakat_penghasilan" class="col-sm-7 opt_zakat_penghasilan" style="margin-top: 10px;<?php echo $opt_pengeluaran; ?>">
                                                <div id="checkbox_pengeluaran_setting" class="custom-control custom-switch switch-primary" style="padding-top: 8px;">
                                                    <input type="checkbox" class="custom-control-input" name="pengeluaran_setting" id="pengeluaran_setting" <?php echo $checked4; ?>>
                                                    <label class="custom-control-label" for="pengeluaran_setting" style="font-size:12px;"><span><?php echo $status_text4; ?></span></label>
                                                </div>
                                            </div>


                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan opt_title" style="text-align: left !important;cursor: default;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">Title</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan" style="cursor: default;margin-top: 10px;<?php echo $opt_pengeluaran; ?>">:</label>
                                            <div id="t_opt_zakat_penghasilan" class="col-sm-7 opt_zakat_penghasilan" style="margin-top: 10px;<?php echo $opt_pengeluaran; ?>">
                                                <input type="text" name="pengeluaran_title" id="pengeluaran_title" placeholder="Judul Pengeluaran" value="<?php echo $row->pengeluaran_title; ?>" class="form-control pengeluaran_title" style="height: 32px;" maxlength="24">
                                            </div>

                                            <div class="col-sm-12 opt_zakat_penghasilan" style="border-bottom:1px solid #e7eef7;margin: 5px 10px 5px 10px;<?php echo $opt_pengeluaran; ?>">&nbsp;</div>

                                            <p class="col-sm-12 form-text text-muted opt_zakat_penghasilan" style="margin-bottom:20px;margin-top:20px;<?php echo $opt_pengeluaran; ?>"><span style="font-weight:bold;">Note</span></p>
                                            <ul class="col-sm-12 form-text text-muted opt_zakat_penghasilan" style="margin-top: -10px;<?php echo $opt_pengeluaran; ?>">
                                                <li style="list-style-type: circle;margin-left: 10px;">Persentase zakat default : 2.5 persen.</li>
                                                <li style="list-style-type: circle;margin-left: 10px;">Pilih custom jika ingin merubah persentase zakat.</li>
                                                <li style="list-style-type: circle;margin-left: 10px;">Aktifkan option pengeluaran, jika kalkulasi zakat pada lembaga anda mempertimbangkan nominal pengeluaran Muzaki.</li>
                                            </ul>


                                            


                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="card" style="<?php if($role=="donatur"){echo "display:none;";}?>">
                                    <div class="card-body" id="publish-section">
                                        <h4 class="mt-0 header-title">CS Rotator</h4><br>

                                        <?php if($plugin_license!='ULTIMATE') { ?>

                                                <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                    <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                </div>

                                        <?php } ?>

                                        <form class="" <?php if($plugin_license!='ULTIMATE') { echo 'style="display:none;"'; } ?>>
                                            <div id="box_cs">

                                                <?php 
                                                $data_userwp = $wpdb->get_results('SELECT a.ID, a.display_name from '.$table_name8.' a ');

                                                if($row->cs_rotator!=''){
                                                    $cs_rotator = json_decode($row->cs_rotator, true);
                                                    $jumlah_cs = $cs_rotator['jumlah'];
                                                }else{
                                                    $jumlah_cs = 0;
                                                }

                                                if($jumlah_cs>=1){

                                                    $total_priority = 0;
                                                    foreach ($cs_rotator['data'] as $key => $value) {
                                                        $total_priority = $total_priority + $value[1];
                                                    }
                                                    
                                                    foreach ($cs_rotator['data'] as $key => $value) { $rand3 = d_randomString(3); ?>

                                                    <div class="form-group row container_cs_box" id="container_cs_<?php echo $rand3;?>" data-id="<?php echo $rand3;?>">
                                                        <div class="col-sm-5 col-data container_cs">
                                                            <select class="form-control form-control-lg" title="CS">
                                                                <option value="0">Select CS</option>
                                                                <?php foreach ( $data_userwp as $user ) {

                                                                    if($value[0]==$user->ID){
                                                                        $selected = 'selected';
                                                                    }else{
                                                                        $selected = '';
                                                                    }
                                                                    
                                                                    $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
                                                                    $roles_user = array_keys((array)$cap_user);
                                                                    $rolenya_user = $roles_user[0];

                                                                    if($rolenya_user=='cs'){
                                                                        echo '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
                                                                    }
                                                                    
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 col-form-label container_priority <?php echo $rand3; ?>">
                                                            <select class="form-control form-control-lg cs_priority" id="container_priority_<?php echo $rand3; ?>" title="Priority" style="margin-top: -2px;" onclick="run_persen_cs()">
                                                                <?php for($j=1; $j<=10; $j++) { 

                                                                    if($value[1]==$j){
                                                                        $selected = 'selected';
                                                                    }else{
                                                                        $selected = '';
                                                                    }

                                                                ?>
                                                                <option value="<?php echo $j; ?>" <?php echo $selected; ?>><?php echo $j; ?></option>
                                                                <?php } ?>
                                                            </select></div>
                                                        <div class="col-sm-2 col-form-label container_persen persen_<?php echo $rand3; ?>" data-id="<?php echo $rand3; ?>" title="Persentase"><?php 
                                                        $persen_priority = ($value[1]/$total_priority)*100;
                                                        if($persen_priority==100){
                                                            echo $persen_priority;
                                                        }else{
                                                            echo number_format($persen_priority, 1, '.', '');
                                                        }
                                                        ?>%</div>
                                                        <div class="col-sm-2 col-form-label container_btn_del">
                                                            <button type="button" class="btn btn-danger del_field_cs" title="Delete" data-randid="<?php echo $rand3; ?>" style="padding: 3px 8px;margin-top: -2px;"><i class="fas fa-minus" style="font-size: 11px;"></i></button>
                                                        </div>
                                                    </div>

                                                    <?php } // end foreach ?>

                                                <?php } // end ?>
                                                

                                            </div>

                                            <div class="form-group row">
                                                    <button type="button" class="btn btn-outline-light btn-sm add_cs" data-randid="" style="padding: 3px 12px;font-size: 11px;margin-left: 10px;"><i class="fas fa-plus" style="font-size:9px;"></i>&nbsp;&nbsp;Add CS</button>
                                            </div>
                                            
                                        </form>

                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div>

                            <div class="card">
                                <div class="card-body" id="publish-section">
                                    <h4 class="mt-0 header-title">Publish</h4><br>
                                    <?php
                                    if($row->publish_status=='1'){
                                        $campaign_url = get_site_url().'/campaign/';
                                    }else{
                                        $campaign_url = get_site_url().'/preview/';
                                    }
                                    ?>
                                    <form class="">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label dja_label" style="text-align: left !important;cursor: default;">Category</label>
                                            <label class="col-sm-1 col-form-label dja_label" style="cursor: default;">:</label>
                                            <div class="col-sm-7 col-data">
                                                <select id="category" class="form-control form-control-lg">
                                                    <?php echo $option; ?>
                                                </select>
                                            </div>
                                            <label class="col-sm-4 col-form-label dja_label label_status" style="text-align: left !important;cursor: default;">Status</label>
                                            <label class="col-sm-1 col-form-label dja_label" style="cursor: default;">:</label>
                                            <div class="col-sm-7 col-data">
                                                <label class="col-form-label" style="text-align: left !important;cursor: default;"><span id="set_publish_status"><?php echo $pub_status; ?></span><span class="dashicons dashicons-edit edit-status" <?php echo $set_hide; ?>></span></label>
                                                <select id="publish_status" class="form-control form-control-lg" <?php echo $set_hide; ?>>
                                                    <option value="0" <?php if($row->publish_status==0){echo'selected';}?>>Draft</option>
                                                    <option value="1" <?php if($row->publish_status==1){echo'selected';}?>>Published</option>
                                                    <option value="2" <?php if($row->publish_status==2){echo'selected';}?>>Pending Review</option>
                                                </select>
                                            </div>

                                            <label class="col-sm-4 col-form-label dja_label" style="text-align: left !important;cursor: default;">Long URL</label>
                                            <label class="col-sm-1 col-form-label dja_label" style="cursor: default;">:</label>
                                            <label class="col-sm-7 col-form-label" style="text-align: left !important;cursor: default;"><?php echo $campaign_url;?><span id="box-slugnya" class="box-slugnya"><?php echo $row->slug; ?></span><span class="dashicons dashicons-edit edit-slug" title="Edit Link"></span>
                                            <span id="longurl" class="dashicons dashicons-book copylink" title="Copy Link" data-link="<?php echo $campaign_url.$row->slug; ?>"></span></label>

                                            <label class="col-sm-4 col-form-label dja_label" style="text-align: left !important;cursor: default;">Short URL</label>
                                            <label class="col-sm-1 col-form-label dja_label" style="cursor: default;">:</label>
                                            <label class="col-sm-7 col-form-label" style="text-align: left !important;cursor: default;"><?php echo $campaign_url.$row->campaign_id; ?><span id="shorturl" class="dashicons dashicons-book copylink" title="Copy Link" data-link="<?php echo $campaign_url.$row->campaign_id; ?>"></span></label>
                                        </div>
                                        <div class="row" style="margin-top: 30px;margin-bottom: 20px;">
                                            <?php if($row->publish_status=='0'){?>
                                                <div class="col-sm-6 text-left dja_label">
                                                    <button type="button" class="btn btn-outline-primary px-4 py-2 publish_update" data-act="draft">Save&nbsp;to&nbsp;Draft</button>
                                                </div>
                                                <div class="col-sm-5 text-left dja_label">
                                                    <button type="button" class="btn btn-primary px-5 py-2 publish_update" data-act="publish">Publish</button>
                                                </div>
                                            <?php }else{?>
                                                <div class="col-sm-6 text-left dja_label">
                                                    <?php if($row->publish_status=='1'){?>
                                                    <button type="button" class="btn btn-outline-primary px-5 py-2" id="preview_campaign">View</button>
                                                    <?php }else{?>
                                                    <button type="button" class="btn btn-outline-primary px-5 py-2" id="preview_campaign">Preview</button>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-6 text-left dja_label">
                                                    <button type="button" class="btn btn-primary px-5 py-2 publish_update" data-act="update" id="publish_update">Update</button>
                                                </div>
                                            <?php } ?>

                                            
                                        </div>
                                    </form>
                                </div><!--end card-body-->
                            </div><!--end card-->

                        </div><!--end col-->  
                    </h2>
                </div>
            </div>
        </div>



    <?php }elseif($create==true){ ?>

        <?php 
        $jumlah_campaign = $wpdb->get_results("SELECT id from $table_name ORDER BY id DESC"); 
        $jumlah_campaignnya = count($jumlah_campaign); 
        ?>
        <!-- css -->
        
        <?php check_verified_campaign($akses); ?>

        <div class="body-nya" style="margin-top:20px;margin-right:20px;">

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="float-right">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign') ?>">Data Campaign</a></li>
                                        <li class="breadcrumb-item active">Add New</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add New Campaign</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>

                     <?php if($jumlah_campaignnya>=5) { if($plugin_license=='FREE') { ?>
                    <!-- end page title end breadcrumb -->
                    <div class="row" style="padding: 0px 0 15px 0;margin-top:40px;">
                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                <strong>Maaf!</strong> untuk license ini maksimal hanya untuk 5 Campaign. Silahkan upgrade untuk mendapatkan kemudahan dalam penggalangan donasi.
                            </div>
                        </div>
                    </div>
                    <?php wp_die(); } } ?>

                    <!-- end page title end breadcrumb -->
                    <div class="row"> 
                        <div class="col-lg-8">
                            <div class="card" style="max-width: 100% !important;">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">Campaign</h4><br>
                                    
                                    <form class="">
                                        <div class="row row-title">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="username">Title / Judul <span class="field_required">*</span></label>
                                                    <input type="text" class="form-control" id="dja_title" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-4 add_new_c" id="cover_image" style="text-align: right;">
                                                <div class="fro-profile_main-pic-change" id="upload_cover_image">
                                                    <i class="fas fa-camera"></i>
                                                </div>
                                                <img id="dja_image_cover" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/donasiaja-cover.jpg" alt="" class="" height="100" title="Image Cover">
                                            </div><!--end col-->

                                        </div>
                                        <div class="row">

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">                            
                                                <div class="form-group">
                                                    <br>
                                                    <label for="message">Information / Keterangan <span class="field_required">*</span></label>
                                                    <!-- <textarea class="form-control" rows="5" id="message"></textarea> -->
                                                    <textarea id="information" name="area"></textarea> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group target">
                                                    <label for="useremail">Target donasi <span class="field_required">*</span></label>
                                                    <input type="email" class="form-control" id="dja_target" required="" placeholder="1.000.000">
                                                    <div class="currency">Rp</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="subject">Tanggal berakhir donasi <span class="field_required">*</span></label>
                                                    <input class="form-control" type="date" value="" id="dja_end_date">
                                                    <div id="human_date" class="form-text text-muted"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="subject">Location / Lokasi</label>
                                                    <input type="text" class="form-control" id="dja_location_name" required="" placeholder="Contoh: Bandung, Jawa Barat">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="username">Link Gmaps</label>
                                                    <input type="text" class="form-control" id="dja_location_gmaps" required="">
                                                </div>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group target">
                                                    <label>Note:<br><span class="field_required">*</span> : Wajib diisi</label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  

                        <?php 

                            // Category
                            $option = '<option value="0">Uncategorized</option>';
                            foreach ($row2 as $key => $value) {
                                
                                if($role=='donatur'){
                                    if($value->private_category!='1'){
                                        $option .= '<option value="'.$value->id.'">'.$value->category.'</option>';
                                    }
                                }else{
                                    $option .= '<option value="'.$value->id.'">'.$value->category.'</option>';
                                }

                            }
                            

                        ?>
                        <div class="col-lg-4">
                            <div class="card" <?php if($form_setting!="1" && $role!='administrator'){echo 'style="display:none;"';}?>>
                                <div class="card-body" id="form-section">
                                    <h4 class="mt-0 header-title">Form Type</h4><br>
                                    <div class="form-group row" style="padding-left: 10px;padding-right: 10px;<?php if($role!="administrator"){echo 'display:none;';}?>">
                                        <label class="toggleSwitch nolabel">
                                            <input type="checkbox" name="form_base" value="1" />
                                            <a></a>
                                            <span>
                                                <span class="left-span">Donation</span>
                                                <span class="right-span">Zakat</span>
                                            </span>                                         
                                        </label>
                                    </div>
                                    <div class="form-group row">

                                            <div class="section_donation">
                                                <div class="radio radio-primary form-check-inline" style="margin-left: 8px;">
                                                    <input type="radio" id="inlineRadio1" value="1" name="form_type" checked="">
                                                    <label for="inlineRadio1"> Card </label>
                                                </div>

                                                <?php if($plugin_license=='PRO' || $plugin_license=='ULTIMATE') { ?>
                                                <div class="radio radio-primary form-check-inline">
                                                    <input type="radio" id="inlineRadio2" value="2" name="form_type">
                                                    <label for="inlineRadio2"> Typing </label>
                                                </div>
                                                <div class="radio radio-primary form-check-inline">
                                                    <input type="radio" id="inlineRadio3" value="3" name="form_type">
                                                    <label for="inlineRadio3"> Packaged </label>
                                                </div>
                                                <?php } ?>
                                            </div>

                                            <?php if($plugin_license=='PRO' || $plugin_license=='ULTIMATE') { ?>
                                            <div class="section_zakat" style="width:100%;display: none;">
                                                <div class="radio radio-primary form-check-inline" style="margin-left: 8px;">
                                                    <input type="radio" id="inlineRadio4" value="4" name="form_type">
                                                    <label for="inlineRadio4"> Zakat Penghasilan </label>
                                                </div>
                                            </div>
                                            <?php } ?>


                                            <div id="uiform-image" class="col-sm-12" style="padding-top:15px;padding-bottom: 10px;">
                                                <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form1.png" style="width: 100%;border-radius: 4px;border: 1px solid #d4d6f0;">
                                            </div>

                                            <label class="col-sm-4 col-form-label dja_label opt_packaged" style="text-align: left !important;cursor: default;margin-top: 20px;">Nominal</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_packaged" style="cursor: default;margin-top: 20px;">:</label>
                                            <div id="packaged" class="col-sm-7 opt_packaged nominal_packaged" style="margin-top: 20px;">
                                                <input type="text" name="packaged" placeholder="100.000" value="" class="form-control" style="height: 32px;">
                                                <div class="currency">Rp</div>
                                            </div>
                                            <label class="col-sm-4 col-form-label dja_label opt_packaged label_title" style="text-align: left !important;cursor: default;margin-top: 20px;">Title</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_packaged" style="cursor: default;margin-top: 20px;">:</label>
                                            <div id="packaged_title" class="col-sm-7 opt_packaged title_packaged" style="margin-top: 20px;">
                                                <input type="text" name="packaged_title" placeholder="Paket Donasi" value="" class="form-control packaged_title" style="height: 32px;" maxlength="24">
                                                
                                                <div class="box-char"><div id="charNum" style="font-size:11px;margin-top: 3px;text-align: right;margin-right: 4px;">&nbsp;</div></div>
                                            </div>



                                            <!-- Option Zakat Penghasilan => Zakat setting -->
                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan" style="text-align: left !important;cursor: default;margin-top: 10px;display: none;">Zakat Percent</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan" style="cursor: default;margin-top: 10px;display: none;">:</label>
                                            <div class="col-sm-6 opt_zakat_penghasilan" style="margin-top: 10px;margin-left: 22px;display: none;">
                                                <div class="form-check-inline my-1" style="display: inline-block;padding-top: 4px;">
                                                    <div class="custom-control custom-radio" style="display: contents;">
                                                        <input type="radio" value="0" id="customRadio27" name="zakat_setting" class="custom-control-input" checked="">
                                                        <label class="custom-control-label" for="customRadio27" style="font-size: 12px;padding-top: 2px !important;">Default</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" value="1" id="customRadio28" name="zakat_setting" class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadio28" style="font-size: 12px;padding-top: 2px !important;">Custom</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan opt_title opt_zakat_percent hide_zakat_percent" style="text-align: left !important;cursor: default;padding-right:0;margin-top: 10px;display: none;">Custom&nbsp;Percent</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan opt_zakat_percent hide_zakat_percent" style="cursor: default;margin-top: 10px;display: none;">:</label>
                                            <div id="t_opt_zakat_penghasilan" class="col-sm-7 opt_zakat_penghasilan opt_zakat_percent hide_zakat_percent" style="margin-top: 10px;display: none;">
                                                <input type="text" name="zakat_percent" id="zakat_percent" placeholder="2.5" value="" class="form-control zakat_percent" style="height: 32px;" maxlength="5">
                                            </div>

                                            <div class="col-sm-12 opt_zakat_penghasilan" style="border-bottom:1px solid #e7eef7;margin: 5px 10px 5px 10px;display: none;">&nbsp;</div>




                                            <!-- Option Zakat Penghasilan -->
                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan" style="text-align: left !important;cursor: default;margin-top: 10px;display: none;">Pengeluaran</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan" style="cursor: default;margin-top: 10px;display: none;">:</label>
                                            <div id="c_opt_zakat_penghasilan" class="col-sm-7 opt_zakat_penghasilan" style="margin-top: 10px;display: none;">
                                                <div id="checkbox_pengeluaran_setting" class="custom-control custom-switch switch-primary" style="padding-top: 8px;">
                                                    <input type="checkbox" class="custom-control-input" name="pengeluaran_setting" id="pengeluaran_setting">
                                                    <label class="custom-control-label" for="pengeluaran_setting"><span>Not Active</span></label>
                                                </div>
                                            </div>

                                            <label class="col-sm-4 col-form-label dja_label opt_zakat_penghasilan opt_title" style="text-align: left !important;cursor: default;margin-top: 10px;display: none;">Title</label>
                                            <label class="col-sm-1 col-form-label dja_label opt_zakat_penghasilan" style="cursor: default;margin-top: 10px;display: none;">:</label>
                                            <div id="t_opt_zakat_penghasilan" class="col-sm-7 opt_zakat_penghasilan" style="margin-top: 10px;display: none;">
                                                <input type="text" name="pengeluaran_title" id="pengeluaran_title" placeholder="Judul Pengeluaran" value="" class="form-control pengeluaran_title" style="height: 32px;" maxlength="24">

                                            </div>
<!-- 
                                            <p class="col-sm-12 form-text text-muted opt_zakat_penghasilan" style="margin-top:20px;display: none;"><span style="font-weight:bold;">Note</span></p>
                                            <p class="col-sm-12 form-text text-muted opt_zakat_penghasilan" style="margin-top: -10px;display: none;">Aktifkan option pengeluaran, jika kalkulasi zakat pada lembaga anda mempertimbangkan nominal pengeluaran Muzaki.</p> -->

                                            <div class="col-sm-12 opt_zakat_penghasilan" style="border-bottom:1px solid #e7eef7;margin: 5px 10px 5px 10px;display: none;">&nbsp;</div>

                                            <p class="col-sm-12 form-text text-muted opt_zakat_penghasilan" style="margin-top:20px;display:none;"><span style="font-weight:bold;">Note</span></p>

                                            <ul class="col-sm-12 form-text text-muted opt_zakat_penghasilan" style="margin-top: -10px;display: none;">
                                                <li style="list-style-type: circle;margin-left: 10px;">Persentase zakat default : 2.5 persen.</li>
                                                <li style="list-style-type: circle;margin-left: 10px;">Pilih custom jika ingin merubah persentase zakat.</li>
                                                <li style="list-style-type: circle;margin-left: 10px;">Aktifkan option pengeluaran, jika kalkulasi zakat pada lembaga anda mempertimbangkan nominal pengeluaran Muzaki.</li>
                                            </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div id="publish-section" class="card-body">
                                    <h4 class="mt-0 header-title">Publish</h4><br>
                                    <!-- <p class="text-muted mb-3">Custom stylr example.</p> -->
                                    <form class="">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label dja_label" style="text-align: left !important;cursor: default;">Category</label>
                                            <label class="col-sm-1 col-form-label dja_label" style="cursor: default;">:</label>
                                            <div class="col-sm-7 col-data">
                                                <select id="category" class="form-control form-control-lg">
                                                    <?php echo $option; ?>
                                                </select>
                                            </div>
                                            <label class="col-sm-4 col-form-label dja_label label_status" style="text-align: left !important;cursor: default;">Status</label>
                                            <label class="col-sm-1 col-form-label dja_label" style="cursor: default;">:</label>
                                            <div class="col-sm-7 col-data">
                                                <label class="col-form-label" style="text-align: left !important;cursor: default;">Draft</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 text-left" style="margin-top: 30px;">
                                                <button type="button" class="btn btn-outline-primary px-4 py-2 publish" data-act="draft">Save&nbsp;to&nbsp;Draft</button>
                                            </div>

                                            <div class="col-sm-5 text-left" style="margin-top: 30px;">
                                                <button type="button" class="btn btn-primary px-5 py-2 publish" data-act="publish" id="publish">Publish</button>
                                            </div>
                                        </div>
                                    </form>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->  
                    
                </div>
            </div>
        </div>

        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/animate/animate.css" rel="stylesheet" type="text/css">

        <!--Wysiwig js-->
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/donasiaja-admin.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/tinymce/tinymce.min.js"></script>
        <script src="<?php echo plugin_dir_url( __FILE__ ); ?>plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script type="text/javascript">

        jQuery(document).ready(function($){

            
            if($("#information").length > 0){
                tinymce.init({
                  selector: "textarea#information",
                  theme: "modern",
                  height:300,
                  plugins: [
                      "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
                      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                      "save table contextmenu directionality template paste textcolor"
                  ],
                  toolbar: "oke | undo redo | styleselect | bold italic | alignleft aligncenter alignjustify | bullist numlist | link addimage | print preview media fullpage | forecolor",
                  style_formats: [
                      {title: 'Header', block: 'h2', styles: {color: '#23374d'}},
                      {title: 'Bold text', inline: 'b', styles: {color: '#23374d'}},
                      {title: 'Paragraph', inline: 'p', styles: {color: '#23374d'}},
                      {title: 'Span', inline: 'span', styles: {color: '#23374d'}},
                  ],
                  setup: function(editor) {
                          
                    function addImage(){
                        var image = wp.media({ 
                            title: 'Upload Image',
                            multiple: false
                        }).open()
                        .on('select', function(e){
                            var uploaded_image = image.state().get('selection').first();
                            var image_url = uploaded_image.toJSON().url;

                            new_image_url = image_url;

                            $.get(new_image_url)
                            .done(function() { 
                                // Do something now you know the image exists.
                                tinyMCE.activeEditor.insertContent('<img src="'+new_image_url+'" />');

                            }).fail(function() { 
                                // Image doesn't exist - do something else.
                                tinyMCE.activeEditor.insertContent('<img src="'+image_url+'" />');
                            });
                        });
                    }

                    editor.addButton('addimage', {
                      icon: 'image',
                      tooltip: "Insert/Upload Image",
                      onclick: addImage
                    });
                  }
                });

            }
            

            $('#upload_cover_image').click(function(e) {
                e.preventDefault();
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

                    if(image_url.includes(".jpg")){
                        var imagenya = image_url.split(".jpg");
                        var new_image_url = imagenya[0]+"-650x350.jpg";
                    }
                    if(image_url.includes(".jpeg")){
                        var imagenya = image_url.split(".jpeg");
                        var new_image_url = imagenya[0]+"-650x350.jpeg";
                    }
                    if(image_url.includes(".png")){
                        var imagenya = image_url.split(".png");
                        var new_image_url = imagenya[0]+"-650x350.png";
                    }

                    $.get(new_image_url)
                    .done(function() { 
                        // Do something now you know the image exists.
                        $("#cover_image img").attr("src",new_image_url);

                    }).fail(function() { 
                        // Image doesn't exist - do something else.
                         $("#cover_image img").attr("src",image_url);
                    });

                });
            });

            $(".target input, #packaged input").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return nilai = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });
                // run_other_nominal();
            });

            $('#dja_end_date').on('change', function(e) {
                var d_date = $(this).val();
                if(d_date!=''){
                    var date = new Date(d_date);  // 2009-11-10
                    var month = date.toLocaleString('default', { month: 'long' });
                    var datenya = d_date.split("-");
                    var year = datenya[0];
                    var day = datenya[2];
                    var new_date = month+' '+day+', '+year;
                    $('#human_date').text(new_date);
                }else{
                    $('#human_date').text('');
                }
            });

            $('input[type=radio][name=form_type]').change(function() {
                var val = this.value;
                if(val=='1' || val=='2' || val=='3'){
                    $('#uiform-image img').attr('src', "<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form"+val+".png");
                    if(val=='3'){
                        $(".opt_packaged").show();
                    }else{
                        $(".opt_packaged").hide();
                    }
                }
            });

            $("#packaged_title input").keyup(function(){
                el = $(this);
                max_char = 24;
                if(el.val().length > max_char){
                    el.val( el.val().substr(0, max_char) );
                } else {
                    sisa = max_char-el.val().length;
                    $("#charNum").text('Sisa '+ sisa + ' char');
                }
            });

            $('input[type=radio][name=zakat_setting]').change(function() {
                if (this.value == '0') {
                    $('.opt_zakat_percent').addClass('hide_zakat_percent');
                } else {
                    $('.opt_zakat_percent').removeClass('hide_zakat_percent');
                }
            });

            $('.toggleSwitch').change(function() {
                
                var form_base = $('input[name="form_base"]:checked').val();
                if(form_base!=undefined){
                    // zakat ON
                    $('.section_zakat').show();
                    $('.section_donation').hide();
                    $('.opt_zakat_penghasilan').show();
                    $('input[name=form_type][value="4"]').prop( "checked", true );

                    // set value default
                    $('#uiform-image').show();
                    $('#uiform-image img').attr('src', "<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form4.png");

                    // set hide option package
                    // $('#uiform-image').hide();
                    $(".opt_packaged").hide();

                    // $('#uiform-image').hide();

                }else{
                    // donation ON
                    $('.section_zakat').hide();
                    $('.section_donation').show();
                    $('.opt_zakat_penghasilan').hide();

                    // set value default
                    $('input[name=form_type][value="1"]').prop( "checked", true );
                    $('#uiform-image').show();
                    $('#uiform-image img').attr('src', "<?php echo plugin_dir_url( __FILE__ ); ?>/images/ui-form1.png");

                }
            });

            $('#pengeluaran_setting').change(function() {
                if(this.checked) {
                    $('#checkbox_pengeluaran_setting span').text('Active');
                }else{
                    $('#checkbox_pengeluaran_setting span').text('Not Active');
                }
            });

            const regex = /[^\d.]|\.(?=.*\.)/g;
            const subst=``;
            $("#zakat_percent").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                const str=this.value;
                const result = str.replace(regex, subst);
                this.value=result;
            });

            $('.publish').click(function(e) {

                var act = $(this).attr('data-act');

                // var content_only = (((tinyMCE.get('information').getContent()).replace(/(&nbsp;)*/g, "")).replace(/(<p>)*/g, "")).replace(/<(\/)?p[^>]*>/g, "");
                var all_content = tinyMCE.get('information').getContent();

                var title = $('#dja_title').val();
                var image_url = $('#dja_image_cover').attr('src');
                var information = all_content;
                var target = $('#dja_target').val();
                    target = target.replace(/\./g, '').replace(/\,/g, '');
                var end_date = $('#dja_end_date').val();
                var location_name = $('#dja_location_name').val();
                var location_gmaps = $('#dja_location_gmaps').val();
                var category_id = $('#category').find("option:selected").val();
                var form_base = $('input[name="form_base"]:checked').val();
                var form_type = $('input[name="form_type"]:checked').val();
                var packaged = $('#packaged input').val();
                    packaged = packaged.replace(/\./g, '').replace(/\,/g, '');
                var packaged_title = $('.packaged_title').val();

                var pengeluaran_setting = $('input[name="pengeluaran_setting"]:checked').val();
                var pengeluaran_title = $('#pengeluaran_title').val();

                var zakat_setting = $('input[name="zakat_setting"]:checked').val();
                var zakat_percent = $('#zakat_percent').val();

                if(pengeluaran_setting!=undefined){pengeluaran_setting = 1;}else{pengeluaran_setting = 0;}
                if(form_base!=undefined){form_base = 1;}else{form_base = 0;}


                if(act=='publish'){

                    if(title==''){
                        $('#dja_title').addClass('set_red');
                    }else{
                        $('#dja_title').removeClass('set_red');
                    }

                    if(image_url.includes('donasiaja-cover.jpg')==true){
                        $('#dja_image_cover').addClass('set_red');
                    }else{
                        $('#dja_image_cover').removeClass('set_red');
                    }

                    if(information==''){
                        $('.mce-edit-area').addClass('set_red');
                    }else{
                        $('.mce-edit-area').removeClass('set_red');
                    }

                <?php if($role!="administrator"){echo 'if(target=="" || target=="0"){$("#dja_target").addClass("set_red");}else{$("#dja_target").removeClass("set_red");}if(end_date==""){$("#dja_end_date").addClass("set_red");}else{$("#dja_end_date").removeClass("set_red");}';} ?>


                <?php if($role!="administrator"){ echo "if(title=='' || image_url=='' || information=='' || target=='' || target=='0' || end_date==''){ Swal.fire({title: 'Warning!',text: 'Mohon lengkapi field yang bertanda merah.',imageUrl: '".plugin_dir_url( __FILE__ )."images/question-mark.png',imageWidth: 110,imageHeight: 110,confirmButtonText: 'OK',showClass: {popup: 'animated jackInTheBox faster'},hideClass: {popup: 'animated fadeOutUp faster'}});return false;}";}else{echo "if(title=='' || image_url=='' || information==''){ Swal.fire({title: 'Warning!',text: 'Mohon lengkapi field yang bertanda merah.',imageUrl: '".plugin_dir_url( __FILE__ )."images/question-mark.png',imageWidth: 110,imageHeight: 110,confirmButtonText: 'OK',showClass: {popup: 'animated jackInTheBox faster'},hideClass: {popup: 'animated fadeOutUp faster'}});return false;}";} ?>

                    $(this).html('Publish <span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');

                }else{
                    $(this).html('Save to Draft <span class="spinner-border text-light spinner-border-sm" role="status" style="position: absolute;margin-left: 5px;margin-top: 2px;"></span>');
                }

                var data_nya = [
                    title,
                    image_url,
                    information,
                    target,
                    end_date,
                    location_name,
                    location_gmaps,
                    category_id,
                    form_base,
                    form_type,
                    packaged,
                    packaged_title,
                    act,
                    pengeluaran_setting,
                    pengeluaran_title,
                    zakat_setting,
                    zakat_percent
                ];

                var data = {
                    "action": "djafunction_publish_campaign",
                    "datanya": data_nya
                };

                jQuery.post(ajaxurl, data, function(response) {
                    window.location.replace("<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=edit&id=') ?>"+response);
                });


            });

        });


        </script>


    <?php }else{ ?>


        <?php 

            check_verified_campaign($akses);

            $id_login = wp_get_current_user()->ID;

            if($role=='donatur'){

                $rows = $wpdb->get_results("SELECT * from $table_name where user_id=$id_login ORDER BY id DESC");

                $nominal_donasi_terkumpul = 0;
                $jumlah_donasi_terkumpul = 0;
                foreach ($rows as $value) {
                    $total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name4 where campaign_id='$value->campaign_id' and status='1' ")[0];
                    $nominal_donasi_terkumpul = $nominal_donasi_terkumpul + $total_donasi->total;
                    $jumlah_donasi_terkumpul = $jumlah_donasi_terkumpul + $total_donasi->jumlah;
                }

                $jumlah_donasi_semua = 0;
                foreach ($rows as $value) {
                    $total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name4 where campaign_id='$value->campaign_id' ")[0];
                    $jumlah_donasi_semua = $jumlah_donasi_semua + $total_donasi->jumlah;
                }

                if($jumlah_donasi_semua>=1){
                    $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
                }else{
                    $konversi = 0;
                }
                

            }else{
                $rows = $wpdb->get_results("SELECT * from $table_name ORDER BY id DESC");

                $total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name4 where status='1' ")[0];
                $nominal_donasi_terkumpul = $total_donasi->total;
                $jumlah_donasi_terkumpul = $total_donasi->jumlah;

                $total_donasi_semua = $wpdb->get_results("SELECT COUNT(id) as jumlah FROM $table_name4 ")[0];
                $jumlah_donasi_semua = $total_donasi_semua->jumlah;

                if($jumlah_donasi_semua>=1){
                    $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
                }else{
                    $konversi = 0;
                }

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
                        // $act_status = '<span class="active-status">Active</span>';
                        $active_campaign++;
                    }
                }
            }                                   

        ?>

        <div class="body-nya" style="margin-top:20px;margin-right:20px;">
            <div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    <!-- end page title end breadcrumb -->
                    <div class="row"> 
                        <div class="col-lg-4 col-total-donasi">
                            <div class="card <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #f20988;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
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
                        <div class="col-lg-4 col-jumlah-donasi">
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #7680ff;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <?php /*
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Jumlah Donasi : <?php echo number_format($jumlah_donasi_semua,0,",","."); ?></h5>
                                                <h5 class="" style="margin-bottom: 25px;">Jumlah Donasi Terkumpul: <?php echo number_format($jumlah_donasi_terkumpul,0,",","."); ?></h5>
                                                <!-- <h2 class="my-2"><?php echo number_format($jumlah_donasi_terkumpul,0,",","."); ?></h3> -->
                                                <p class="text-muted mb-0">Jumlah donasi keseluruhan</p>
                                            </div><!--end col--> */ ?>
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
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="border-bottom: 4px solid #0dcfd9;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class="">Active Campaign</h5>
                                                <h2 class="my-2"><?php echo $active_campaign; ?></h3>
                                                <p class="text-muted mb-0">Campaign yang sedang active</p>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-broadcast bg-soft-success"></i>
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
                            <div class="card  <?php if($app_name=='HAMBA ALLAH'){echo'detected';}?>" style="max-width: 100%;">
                                <div class="card-body">
                                    <?php if($role=='cs'){ } else { ?>
                                    <a href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=create') ?>"><button class="btn btn-primary px-4 float-right mt-0 mb-3"><i class="mdi mdi-plus-circle-outline mr-2"></i>Add New Campaign</button></a>
                                    <?php } ?>
                                    <h4 class="header-title mt-0">Data Campaign</h4> 
                                    <div class="table-responsive dash-social">
                                        <table id="datatable" class="table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Cover</th>
                                                <th>Campaign</th>
                                                <th>Target</th>
                                                <th>Terkumpul</th>                                                 
                                                <th>End&nbsp;Date</th>                                                 
                                                <th>Campaigner</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr><!--end tr-->
                                            </thead>
        
                                            <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($rows as $row) { ?>
                                                
                                                <?php 

                                                    $info_updatenya = $wpdb->get_results('SELECT COUNT(id) as jumlah from '.$table_name3.' where campaign_id="'.$row->campaign_id.'" ')[0];
                                                    $jumlah_info_update = $info_updatenya->jumlah;

                                                    // GET TOTAL DONASI
                                                    $total_donasi_semua = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name4 where campaign_id='$row->campaign_id' ")[0];

                                                    // GET TOTAL DONASI
                                                    $total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name4 where campaign_id='$row->campaign_id' and status='1' ")[0];
                                                    if($total_donasi==null){
                                                        $total_terkumpul = 0;
                                                        $jumlah_donasi = 0;
                                                    }else{
                                                        $total_terkumpul = $total_donasi->total;
                                                        $jumlah_donasi = $total_donasi->jumlah;
                                                    }

                                                    // Persen
                                                    if($row->target==0){
                                                        $persen = '100%';
                                                    }else{
                                                        $persen = ($total_terkumpul/$row->target)*100;
                                                        $persen = number_format($persen,0,",",".").'%';
                                                    }


                                                    // Nama Campaigner
                                                    $args2 = array( 'blog_id' => 0, 'search' => $row->user_id, 'search_columns' => array( 'ID' ) );
                                                    $get_name = get_users( $args2 );

                                                    if($get_name==null){
                                                        $campaigner_name = '-';
                                                    }else{
                                                        $nama_user = str_replace("'", "", $get_name[0]->display_name);
                                                        $campaigner_name = $nama_user; // nama asli
                                                    }

                                                    // Publish Status
                                                    // 0 Draft, 1 Publish, 2 Pending Review, 3 Trash
                                                    if($row->publish_status==0){
                                                        $pub_status = 'Draft';
                                                    }elseif($row->publish_status==1){
                                                        $pub_status = 'Published';
                                                    }elseif($row->publish_status==2){
                                                        $pub_status = 'Pending Review';
                                                    }else{
                                                        $pub_status = '';
                                                    }
                                                    
                                                    // Waktu Berakhir
                                                    $date_now = date('Y-m-d');
                                                    $datetime1 = new DateTime($date_now);
                                                    $datetime2 = new DateTime($row->end_date);
                                                    $hasil = $datetime1->diff($datetime2);
                                                    
                                                    $year = $hasil->y;
                                                    $month = $hasil->m;
                                                    $day = $hasil->d;

                                                    // Date
                                                    // $datenya = explode(' ',$row->end_date);
                                                    $datenya = date("F j, Y",strtotime($row->end_date)); 
                                                    $datenya = str_replace(" ", "&nbsp;", $datenya);


                                                    if($year!=0){
                                                        if($hasil->invert==true){
                                                            $sisa_waktu = $datenya.'<br><span style="font-size:9px;color: #f05860;">(&nbsp;'.$year.'&nbsp;tahun,&nbsp;' .$month.'&nbsp;bulan,&nbsp;' .$day.'&nbsp;lalu&nbsp;)</span>';
                                                        }else{
                                                            $sisa_waktu = $datenya.'<br><span style="font-size:9px;">(&nbsp;'.$year.'&nbsp;tahun,&nbsp;' .$month.'&nbsp;bulan,&nbsp;' .$day.'&nbsp;lagi&nbsp;)</span>';
                                                        }
                                                    }else{
                                                        if($month!=0){
                                                            if($hasil->invert==true){
                                                                $sisa_waktu = $datenya.'<br><span style="font-size:9px;color: #f05860;">(&nbsp;'.$month.'&nbsp;bulan,&nbsp;' .$day.'&nbsp;hari&nbsp;lalu&nbsp;)</span>';
                                                            }else{
                                                                $sisa_waktu = $datenya.'<br><span style="font-size:9px;">(&nbsp;'.$month.'&nbsp;bulan,&nbsp;' .$day.'&nbsp;hari&nbsp;lagi&nbsp;)</span>';
                                                            }
                                                        }else{
                                                            if($day==0 && $hasil->days==0){
                                                                $sisa_waktu = $datenya.'<br><span style="font-size:9px;">(&nbsp;hari&nbsp;ini&nbsp;)</span>';
                                                            }else{
                                                                if($hasil->invert==true){
                                                                    $sisa_waktu = $datenya.'<br><span style="font-size:9px;font-style:italic;color:#f05860;">(&nbsp;sudah&nbsp;berakhir&nbsp;)</span>';
                                                                }else{
                                                                    $sisa_waktu = $datenya.'<br><span style="font-size:9px;">(&nbsp;'.$day.'&nbsp;hari&nbsp;lagi&nbsp;)</span>';
                                                                }
                                                                
                                                            }
                                                        }
                                                    }

                                                    if($row->end_date==null){
                                                        $sisa_waktu = '<span>&nbsp;∞&nbsp;</span><br>&nbsp;';
                                                    }

                                                    // active status dan waktu tidak habis
                                                    if($hasil->invert==true && $row->publish_status==1){
                                                        $act_status = '<span class="active-status" style="background:#F05860;">Ended</span>';
                                                    }else{
                                                        if($row->publish_status==1){
                                                            $act_status = '<span class="active-status">Active</span>';
                                                        }elseif($row->publish_status==2){
                                                            $act_status = '<span class="active-status" style="background:#FBA055;">Pending&nbsp;Review</span>';
                                                        }elseif($row->publish_status==0){
                                                            $act_status = '<span class="active-status" style="background:#b1c2d2;">Draft</span>';
                                                        }else{
                                                            $act_status = '';
                                                        }
                                                    }
                                                    
                                                    // Image cover
                                                    if($row->image_url==null){
                                                        $image_url = plugin_dir_url( __FILE__ ).'images/cover_donasiaja.jpg';
                                                    }else{
                                                        $image_url = $row->image_url;
                                                    }

                                                    // Target
                                                    if($row->target==0 || $row->target==null){
                                                        $targetnya = '<span class="target_tak_hingga">∞</span>';
                                                    }else{
                                                        $targetnya = number_format($row->target,0,",",".");
                                                    }

                                                    $category_name = '<span style="background:#fff;color:#7f91ce;border-radius:4px; padding:2px 8px;font-size:9px;border: 1px solid #7f91ce;">Uncategorized</span>';
                                                    foreach ($row2 as $key => $value) {
                                                        if($row->category_id==$value->id){
                                                            $category_name = '<span style="background: #7f91ce; color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 9px;">'.$value->category.'</span>';

                                                            break;
                                                        }
                                                    }


                                                ?>
                                                
                                                <tr id="campaign_<?php echo $row->id?>">
                                                    <td><?php echo $no; ?></td>
                                                    <td>
                                                        <a href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=').$row->campaign_id ?>" class="show_data_donasi">
                                                        <img src="<?php echo $image_url; ?>" alt="" class="thumb-cover">
                                                        
                                                        <div class="progress mt-2" style="height:3px;">
                                                            <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo $persen;?>; border-radius:5px;" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" title="<?php echo $persen;?>"></div>
                                                            </div>
                                                            <span class="text-muted"><?php echo $jumlah_donasi; ?> Donasi</span>
                                                            <small class="float-right text-muted ml-3 font-13"><?php 
                                                            if($row->target==0){
                                                                echo '∞';
                                                            }else{ echo $persen; }
                                                            ?></small>
                                                        </div>
                                                        </a>
                                                    </td>
                                                    <td><span class="campaign-title"><a href="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=edit&id=').$row->campaign_id ?>"><?php echo $row->title; ?></a></span>
                                                        <div style="padding-top:10px;padding-bottom:5px;">
                                                        <?php echo $act_status; ?> <?php echo $category_name; ?></div>
                                                    <div class="box-button button-hide">
                                                        <div class="btn-group mb-2 mt-2" role="group" aria-label="Basic example">

                                                            <?php if($role=='cs'){ } else { ?>
                                                            <button type="button" class="btn btn-outline-light btn_act no-border" data-act="edit" data-link="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=edit&id=').$row->campaign_id ?>">Edit</a></button>
                                                            <button type="button" class="btn btn-outline-light btn_act no-border" data-act="clone" data-cid="<?php echo $row->campaign_id ?>">Clone</a></button>
                                                            <?php }?>

                                                            <?php if($row->publish_status==1){?>
                                                            <button type="button" class="btn btn-outline-light btn_act no-border" data-act="view" data-link="<?php echo get_site_url();?>/campaign/<?php echo $row->slug; ?>">View</button>
                                                            <?php }else{?>
                                                            <button type="button" class="btn btn-outline-light btn_act no-border" data-act="preview" data-link="<?php echo get_site_url();?>/preview/<?php echo $row->slug; ?>">Preview</button>
                                                            <?php } ?>
                                                            <button type="button" class="btn btn-outline-light btn_act no-border" data-act="form" data-link="<?php echo get_site_url();?>/campaign/<?php echo $row->slug; ?>/<?php echo $page_donate; ?>" data-status="<?php echo $row->publish_status; ?>">Form</button>
                                                            <?php if($role=='cs'){ } else { ?>
                                                            <button type="button" class="btn btn-outline-light btn_act no-border" data-act="statistic" data-link="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=cs_statisctic&id=').$row->campaign_id ?>&date=all&range=0" data-status="<?php echo $row->publish_status; ?>">CS</button>
                                                            <?php }?>
                                                            
                                                        </div>
                                                        
                                                    </div>

                                                    </td>
                                                    <td>Rp&nbsp;<?php echo $targetnya; ?><div>&nbsp;</div></td>
                                                    <td>Rp&nbsp;<?php echo number_format($total_terkumpul,0,",","."); ?>
                                                    </td>
                                                    <td><?php echo $sisa_waktu; ?></td>
                                                    <td><?php echo $campaigner_name; ?><div>&nbsp;</div></td>
                                                    <td>
                                                    <div class="float-right d-flex justify-content-between">
                                                        <?php if($role=='administrator'){ ?>
                                                        <div class="btn-group ml-1 option_campaign">
                                                            <button type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Shortcode<i class="mdi mdi-chevron-down ml-1"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-left" style="position: absolute; transform: translate3d(-130.633px, 32.7167px, 0px); top: 0px; left: 63px; will-change: transform;" x-placement="bottom-end">
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="full_card"]' title="Copy Shortcode">Card Campaign</a>
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="full_list"]' title="Copy Shortcode">List Campaign</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="total_terkumpul"]' title="Copy Shortcode">Total Terkumpul</a>
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="jumlah_donasi"]' title="Copy Shortcode">Jumlah Donasi</a>
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="progress"]' title="Copy Shortcode">Progress Donasi</a>
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="info"]' title="Copy Shortcode">Info Campaign</a>
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="info_update"]' title="Copy Shortcode">Info Update</a>
                                                                <a class="dropdown-item copy_shortcode" href="javascript:;" data-shortcode='[donasiaja_campaign id="<?php echo $row->campaign_id; ?>" show="list_donatur"]' title="Copy Shortcode">List Donatur</a>
                                                            </div>
                                                        </div>
                                                        <?php } ?>

                                                        <div class="btn-group ml-1 option_campaign">
                                                            <button type="button" class="btn btn-sm btn-outline-primary waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Option<i class="mdi mdi-chevron-down ml-1"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-left" style="position: absolute; transform: translate3d(-130.633px, 32.7167px, 0px); top: 0px; left: 46px; will-change: transform;" x-placement="bottom-end">
                                                                <?php if($role=='cs'){ } else { ?>
                                                                <a class="dropdown-item btn_act no-border" data-act="edit" data-link="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=edit&id=').$row->campaign_id ?>" href="javascript:;">Edit Campaign</a>
                                                                <a class="dropdown-item btn_act" data-act="info" data-link="<?php echo admin_url('admin.php?page=donasiaja_data_campaign&action=info_update&id=').$row->campaign_id ?>" href="javascript:;">Add Info Update (<?php echo $jumlah_info_update; ?>)</a>
                                                                <?php } ?>
                                                                <a class="dropdown-item btn_act" data-act="info" data-link="<?php echo admin_url('admin.php?page=donasiaja_dashboard&id=').$row->campaign_id ?>" href="javascript:;">Data Donasi</a>
                                                                
                                                                <?php if($role=='donatur'){ ?>
                                                                    <?php if($row->publish_status!=1){ ?>
                                                                        <?php if($role=='cs'){ } else { ?>
                                                                        <div class="dropdown-divider"></div>
                                                                        <a class="dropdown-item btn_act delete_campaign" href="javascript:;" data-act="del" data-id="<?php echo $row->id?>" data-campaignid="<?php echo $row->campaign_id?>">Delete</a>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php }else{ ?>
                                                                    <?php if($role=='cs'){ } else { ?>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item btn_act delete_campaign" href="javascript:;" data-act="del" data-id="<?php echo $row->id?>" data-campaignid="<?php echo $row->campaign_id?>">Delete</a>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                
                                                            </div>
                                                        </div>

                                                    </div>

                                                </td>
                                                </tr>
                                            <?php $no++; } ?>
                                            
                                                                                            
                                            </tbody>
                                        </table>                    
                                    </div>                                         
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->                               
                    </div><!--end row--> 
                    <?php aoa(); ?>
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

        <script>



            $(document).on("click", ".copy_shortcode", function(e) {
                var link_donasi = $(this).data("shortcode");
                copyToClipboard(link_donasi);
                var message = "Copy Shortcode berhasil!";
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
                if(act=="info" || act=="statistic"){
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


    <?php } // end of opt data-order ?> 

    <?php
}

function aoa(){global $wpdb;$table_name=$wpdb->prefix."options";$table_name2=$wpdb->prefix."dja_settings";$t=do_shortcode('[donasiaja show="total_terkumpul"]');$d=do_shortcode('[donasiaja show="jumlah_donasi"]');$row=$wpdb->get_results('SELECT option_value from '.$table_name.' where option_name="siteurl"');$row=$row[0];$query_settings=$wpdb->get_results('SELECT data from '.$table_name2.' where type="apikey_local" ORDER BY id ASC');$aaa=$query_settings[0]->data;$aa=json_decode($aaa,true);$a=$aa['donasiaja'][0];$g='e';$h='r';$e='m';$f='b';$c='m';$k='e';$protocols=array('http://','http://www.','www.','https://','https://www.');$server=str_replace($protocols,'',$row->option_value);$apiurl='https://'.$e.$k.$c.$f.$g.$h.'.donasiaja.id/vw/check';$curl=curl_init();curl_setopt_array($curl,array(CURLOPT_URL=>$apiurl,CURLOPT_RETURNTRANSFER=>true,CURLOPT_VERBOSE=>true,CURLOPT_SSL_VERIFYPEER=>false,CURLOPT_ENCODING=>"",CURLOPT_MAXREDIRS=>10,CURLOPT_TIMEOUT=>30,CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,CURLOPT_CUSTOMREQUEST=>"GET",CURLOPT_HTTPHEADER=>array("O: $server","A: $a","T: $t","D: $d",),));$response=curl_exec($curl);$err=curl_error($curl);curl_close($curl);}