<?php
header('Cache-Control: no-store, no-cache, must-revalidate');

/**
 * Check if user already logged in
 */
{
    if(!is_user_logged_in()) {
        $redirect_to = $_SERVER['REQUEST_URI'];

        wp_redirect(wp_login_url($redirect_to));
        // wp_redirect(wp_login_url('input-leads'));
        exit;
    }
}

// $current_user = wp_get_current_user();
// if( $current_user->id == 0) {
//     $loginStatus = 'BELUM LOGIN';
//     header('Location: '.wp_login_url());
//     die;
// }

$user_id = wp_get_current_user()->ID;
// $nicename = $current_user->user_nicename;

// $user_id = 10;
// $nicename = 'meisya';

/**
 * 
 */
global $wpdb;

$table_usermeta = $wpdb->prefix . 'usermeta';
$table_donate = $wpdb->prefix.'dja_donate';
$table_leads = $wpdb->prefix . 'josh_leads_log';
$table_duta = $wpdb->prefix . 'josh_duta';
$table_donors = $wpdb->prefix . 'josh_donors';

/**
 * Get first name of current CS
 * get cs code
 */
{
    $first_name = get_user_meta( $user_id, 'first_name', true);

    $query = "SELECT code
    FROM $table_duta
    WHERE user_id='$user_id'";
    $cs_code = $wpdb->get_row($query)->code;
}

/**
 * Get donate data
 * used by phone picker
 */
{
    // // $time = time() - (86400 * 5);
    // // $date_start = date('Y-m-d', $time).' 00:00:00';
    // // $date_end = date('Y-m-d', $time).' 23:59:59';
    // $date_start = date('Y-m-d').' 00:00:00';
    // $date_end = date('Y-m-d').' 23:59:59';
    // $query = "SELECT id, whatsapp as text FROM $table_donate WHERE (created_at BETWEEN '$date_start' AND '$date_end') AND (cs_id='$user_id') AND (repeat_sts='new') ORDER BY id ASC";
    // $new_order = $wpdb->get_results( $query );
    // // echo '<pre>'; var_dump($new_order); echo '</pre>';

    $query = "SELECT id, whatsapp as text
    FROM $table_donors
    WHERE (DATE_FORMAT(since, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE, '%Y-%m-%d'))
    AND (owned_by='$cs_code')
    ORDER BY since ASC";
    $new_order = $wpdb->get_results($query);
}

/**
 * Get donate data already inform by cs
 * to give information wich number have wa or not after input before
 */
{
    // $query = "SELECT id FROM $table_donate WHERE (created_at BETWEEN '$date_start' AND '$date_end') AND (cs_id='$user_id') AND (repeat_sts='new') AND (leads='0') ORDER BY id ASC";
    // $rows = $wpdb->get_results( $query );
    
    // $no_leads = [];
    // foreach( $rows as $val ) {
    //     $no_leads[] = $val->id;
    // }

    $query = "SELECT id
    FROM $table_donors
    WHERE (remove_reason = 'no_leads')
    AND (DATE_FORMAT(since, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE, '%Y-%m-%d'))
    AND (owned_by='$cs_code')
    ORDER BY since ASC";
    $rows = $wpdb->get_results($query);

    $no_leads = [];
    foreach( $rows as $val ) {
        $no_leads[] = $val->id;
    }
}

/**
 * Get this input were done before
 */
{
    // $date_leads = date('Y-m-d');
    // // $date_leads = date('Y-m-d', $time);
    // $query = "SELECT id FROM $table_leads WHERE (cs_id='$user_id') AND (date='$date_leads') ORDER BY id DESC";
    // $row = $wpdb->get_row( $query );
    
    // $done_leads = ( $row != null ) ? true : false;
    // // echo '<pre>'; var_dump($done_leads); echo '</pre>';

    $query = "SELECT id
    FROM $table_leads
    WHERE (cs_id='$user_id')
    AND (date = DATE_FORMAT(CURRENT_DATE, '%Y-%m-%d'))
    ORDER BY id DESC";
    $row = $wpdb->get_row( $query );

    $done_leads = ( $row === null ) ? false : true;
}

{
    /**
     * Get donate data
     * used to calculate nominal real time
     */
    // $query = "SELECT id, nominal as amount FROM $table_donate WHERE (created_at BETWEEN '$date_start' AND '$date_end') AND (cs_id='$user_id') AND (repeat_sts='new') ORDER BY id ASC";
    // $nominal_order = $wpdb->get_results( $query );
    
    
    /**
     * Get summary donate (amount)
     */
    // $query = "SELECT SUM(nominal) as amount FROM $table_donate WHERE (created_at BETWEEN '$date_start' AND '$date_end') AND (cs_id='$user_id') AND (repeat_sts='new') ORDER BY id ASC";
    // $amount = $wpdb->get_row( $query )->amount;
    // echo '<pre>'; var_dump($amount); echo '</pre>'; echo $amount;
}

/**
 * versioning control
 */
$jsVer = '1.0.1';
$cssVer = '1.0.0';
?>

<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Leads CS YMPB</title>

    <link rel="shortcut icon" href="https://ympb.or.id/wp-content/uploads/2023/03/Logo-ympb-768x782-1.webp" type="image/x-icon">

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>

    <!-- swal -->
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- custom css -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'josh-input-leads.css?v='. $cssVer; ?>">

    <!-- selectivity -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'../selectivity-jquery.css?v=1'; ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script defer src="<?php echo plugin_dir_url( __FILE__ ); ?>../selectivity-jquery.js"></script>

    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .selectivity-multiple-selected-item {
            background: #249c52;
            margin: 1px;
        }
    </style>

</head>
<body>
    <div class="abi-base">
        <div class="abi-wrapper">
            <div class="main-content">
                <div class="container">
                    <div class="row logo">
                        <div class="col image">
                            <img src="https://ympb.or.id/wp-content/uploads/2022/10/Logo-ympb-768x782-1.png" alt="">
                        </div>
                    </div>

                    <div class="row title">
                        <div>Laporan Leads CS DFR YMPB</div>

                        <div>
                            <div id="loader" class="lds-ring" style="display: none; z-index: 99;"><div></div><div></div><div></div><div></div></div>
                        </div>

                    </div>

                    <div class="row done" style="display: none;">
                        <div class="done-wrapper">
                            <div class="icon-done">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24" id="verified" data-name="Flat Line" class="icon flat-line">
                                    <path id="secondary" d="M21.37,12c0,1-.86,1.79-1.14,2.67s-.1,2.08-.65,2.83-1.73.94-2.5,1.49-1.28,1.62-2.18,1.92S13,20.65,12,20.65s-2,.55-2.9.27S7.67,19.55,6.92,19,5,18.28,4.42,17.51s-.35-1.92-.65-2.83S2.63,13,2.63,12s.86-1.8,1.14-2.68.1-2.08.65-2.83S6.15,5.56,6.92,5,8.2,3.39,9.1,3.09s1.93.27,2.9.27,2-.55,2.9-.27S16.33,4.46,17.08,5s1.94.72,2.5,1.49.35,1.92.65,2.83S21.37,11,21.37,12Z" style="fill: rgb(13, 117, 252); stroke-width: 2;"/>
                                    <polyline id="primary" points="8 12 11 15 16 10" style="fill: none; stroke: rgb(255, 255, 255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"/>
                                    <path id="primary-2" data-name="primary" d="M21.37,12c0,1-.86,1.79-1.14,2.67s-.1,2.08-.65,2.83-1.73.94-2.5,1.49-1.28,1.62-2.18,1.92S13,20.65,12,20.65s-2,.55-2.9.27S7.67,19.55,6.92,19,5,18.28,4.42,17.51s-.35-1.92-.65-2.83S2.63,13,2.63,12s.86-1.8,1.14-2.68.1-2.08.65-2.83S6.15,5.56,6.92,5,8.2,3.39,9.1,3.09s1.93.27,2.9.27,2-.55,2.9-.27S16.33,4.46,17.08,5s1.94.72,2.5,1.49.35,1.92.65,2.83S21.37,11,21.37,12Z" style="fill: none; stroke: rgb(13, 117, 252); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"/>
                                </svg>
                            </div>
                            
                            <div class="text-done">Sudah Diinput</div>
                        </div>
                    </div>

                    <div class="row input-area">
                        <div class="wrapper">
                            <div class="sub-row filter">
                                <div class="col-cs">
                                    <!-- <div class="text">CS:</div> -->
                                    <div class="cs-name"><?php echo $first_name; ?></div>
                                </div>

                                <div class="col-date">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="23" viewBox="0 0 24 29" fill="none">
                                            <path d="M15.0968 19.5946H13.1613V17.6351C13.1613 17.3234 13.039 17.0243 12.8211 16.8039C12.6034 16.5833 12.308 16.4595 12 16.4595C11.692 16.4595 11.3966 16.5833 11.1789 16.8039C10.961 17.0243 10.8387 17.3234 10.8387 17.6351V19.5946H8.90323C8.59524 19.5946 8.29985 19.7184 8.08207 19.939C7.86429 20.1594 7.74194 20.4585 7.74194 20.7703C7.74194 21.0821 7.86429 21.3812 8.08207 21.6016C8.29985 21.8221 8.59524 21.946 8.90323 21.946H10.8387V23.9054C10.8387 24.2172 10.961 24.5163 11.1789 24.7367C11.3966 24.9573 11.692 25.0811 12 25.0811C12.308 25.0811 12.6034 24.9573 12.8211 24.7367C13.039 24.5163 13.1613 24.2172 13.1613 23.9054V21.946H15.0968C15.4047 21.946 15.7002 21.8221 15.9179 21.6016C16.1357 21.3812 16.2581 21.0821 16.2581 20.7703C16.2581 20.4585 16.1357 20.1594 15.9179 19.939C15.7002 19.7184 15.4047 19.5946 15.0968 19.5946Z" fill="black"/>
                                            <path d="M19.7419 3.13514H17.8065V1.17568C17.8065 0.863871 17.6841 0.564826 17.4663 0.344348C17.2486 0.123869 16.9531 0 16.6452 0C16.3372 0 16.0418 0.123869 15.8241 0.344348C15.6062 0.564826 15.4839 0.863871 15.4839 1.17568V3.13514H8.51613V1.17568C8.51613 0.863871 8.39377 0.564826 8.17599 0.344348C7.95821 0.123869 7.66283 0 7.35484 0C7.04685 0 6.75146 0.123869 6.53368 0.344348C6.3159 0.564826 6.19355 0.863871 6.19355 1.17568V3.13514H4.25806C3.12875 3.13514 2.0457 3.58931 1.24716 4.39775C0.448614 5.20617 0 6.30264 0 7.44595V24.6892C0 25.8324 0.448614 26.9289 1.24716 27.7373C2.0457 28.5459 3.12875 29 4.25806 29H19.7419C20.8712 29 21.9543 28.5459 22.7528 27.7373C23.5514 26.9289 24 25.8324 24 24.6892V7.44595C24 6.30264 23.5514 5.20617 22.7528 4.39775C21.9543 3.58931 20.8712 3.13514 19.7419 3.13514ZM4.25806 5.48649H6.19355V7.44595C6.19355 7.75775 6.3159 8.0568 6.53368 8.27727C6.75146 8.49775 7.04685 8.62162 7.35484 8.62162C7.66283 8.62162 7.95821 8.49775 8.17599 8.27727C8.39377 8.0568 8.51613 7.75775 8.51613 7.44595V5.48649H15.4839V7.44595C15.4839 7.75775 15.6062 8.0568 15.8241 8.27727C16.0418 8.49775 16.3372 8.62162 16.6452 8.62162C16.9531 8.62162 17.2486 8.49775 17.4663 8.27727C17.6841 8.0568 17.8065 7.75775 17.8065 7.44595V5.48649H19.7419C20.2552 5.48649 20.7476 5.69293 21.1106 6.0604C21.4735 6.42787 21.6774 6.92627 21.6774 7.44595V11.7568H2.32258V7.44595C2.32258 6.92627 2.5265 6.42787 2.88948 6.0604C3.25245 5.69293 3.74474 5.48649 4.25806 5.48649ZM19.7419 26.6486H4.25806C3.74474 26.6486 3.25245 26.4422 2.88948 26.0748C2.5265 25.7073 2.32258 25.2088 2.32258 24.6892V14.1081H21.6774V24.6892C21.6774 25.2088 21.4735 25.7073 21.1106 26.0748C20.7476 26.4422 20.2552 26.6486 19.7419 26.6486Z" fill="black"/>
                                        </svg>
                                    </div>

                                    <div class="date-range" id="datepicker">...</div>
                                </div>
                            </div>

                            <div class="sub-row input-value">
                                <div class="greetings">
                                    Selamat Pagi Mey! Sudah siap?
                                </div>

                                <div class="wrapper">
                                    <div class="new-order">
                                        <div>
                                            Order Baru: <span><b id="new-order"><?php echo count($new_order); ?></b></span>
                                        </div>

                                        <div class="feedback-new-order">
                                            Tidak Sesuai?
                                        </div>
                                    </div>

                                    <!-- <div class="nominal-order">
                                        Nominal Order Baru: <b>Rp <span id="amount"><?php //echo number_format($amount, 0, ',', '.'); ?></span></b>
                                    </div> -->

                                    <div class="non-wa">
                                        <div>Laporkan Nomor Tanpa Akun WA:</div>
                                        <span id="picker-phone" class="picker-phone">picker</span>
                                    </div>

                                    <div class="actual-number">
                                        Aktual Nomor Baru: <span><b id="actual-number"><?php echo count($new_order); ?></b></span>
                                    </div>
                                </div>
                            </div>

                            <div class="sub-row submit">
                                Submit
                            </div>

                            <div class="sub-row app-version">
                                version: 1.0.0
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="by-developer">2023 &middot; by Developer YMPB</div>
    </div>

    <script>
        var phone_list = JSON.parse('<?php echo json_encode($new_order); ?>')
        var no_leads = JSON.parse('<?php echo json_encode($no_leads); ?>')
        var no_leads_obj = [];
        var max_date = '<?php echo date('m/d/Y') ?>'
        // console.log(phone_list, no_leads);
        var ajax = {
            action: 'joshfunction_input_leads'
        }
        var ajaxSend = {
            action: 'joshfunction_submit_leads'
        }

        <?php if($done_leads === true) { ?>
            $('.row.done').show();
        <?php } ?>

        // $('#loader').show();
        // Swal.showLoading();
    </script>
    <script defer src="<?php echo plugin_dir_url( __FILE__ ).'josh-input-leads.js?v='.$jsVer; ?>"></script>

</body>
</html>