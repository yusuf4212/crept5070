<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
$current_user = wp_get_current_user();
if( $current_user->id == 0) {
    $loginStatus = 'BELUM LOGIN';
    header('Location: '.wp_login_url());
    die;
}

global $wpdb;
    
$table_settings = $wpdb->prefix.'josh_table_settings';

$query = " SELECT menu, value FROM $table_settings ";
$row = $wpdb->get_results( $query );

$program    = $row[0]->value;
$platform   = $row[1]->value;
$cs         = $row[2]->value;
// $type       = $row[3]->value;
$bank       = $row[4]->value;

/**
 * versioning control
 */
$jsVer = '5.0.6';
$cssVer = '2.0.3';
?>

<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Slip Transfer Admin YMPB</title>

    <link rel="shortcut icon" href="https://ympb.or.id/wp-content/uploads/2023/03/Logo-ympb-768x782-1.webp" type="image/x-icon">

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>

    <!-- swal -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- include bootstrap -->
    <link rel="stylesheet" href="<?php //echo plugin_dir_url( __FILE__ ).'assets/asset-j/bootstrap-reboot.css'; ?>">
    <link rel="stylesheet" href="<?php //echo plugin_dir_url( __FILE__ ) . 'assets/asset-j/bootstrap-utilities.css'; ?>">
    <script defer src="<?php //echo plugin_dir_url( __FILE__ ).'assets/asset-j/bootstrap.bundle.js'; ?>"></script>
    
    <!-- custom css -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'assets/asset-j/josh-input-slip.css?v='. $cssVer; ?>">

    <!-- selectivity -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'selectivity-jquery.css?v=1.0.1'; ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script defer src="<?php echo plugin_dir_url( __FILE__ ); ?>selectivity-jquery.js"></script>

    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


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

                    <div class="row title">Form Input Slip TF</div>

                    <div class="app-version">
                        <div>Version info:</div>
                        <div>CSS : <?php echo $cssVer; ?></div>
                        <div>JS : <?php echo $jsVer; ?></div>
                    </div>

                    <div class="row input box">
                        <div class="row-input row-input-1">
                            <div class="col no-wa">
                                <div class="label no-wa">No WA</div>
                                <input type="text" name="no-wa" id="no-wa" placeholder="0812xxxx">
                                <div class="notice-error no-wa-2" style="display: none;">masukkan sesuai format (08xxx)</div>
                                <div class="notice-error no-wa" style="display: none;">masukkan nomor valid (minimal 5 digit)</div>
                            </div>
                        </div>

                        <div class="row-input row-input-2">
                            <div class="col given-date">
                                <div class="label given-date">Tanggal Diberikan CS</div>
                                <div id="given-date" class="input">Today</div>
                            </div>

                            <div class="col program">
                                <div class="label program">Program</div>
                                <div id="program" class="input"></div>
                                <div class="notice-error program" style="display: none;">pilih salah satu</div>
                            </div>
                        </div>

                        <div class="row-input row-input-3">
                            <div class="col platform">
                                <div class="label platform">Platform</div>
                                <div class="input" id="platform"></div>
                                <div class="notice-error platform" style="display: none;">pilih salah satu</div>
                            </div>

                            <div class="col relawan">
                                <div class="label relawan">Relawan</div>
                                <div id="relawan" class="input"></div>
                                <div class="notice-error relawan" style="display: none;">pilih salah satu</div>
                            </div>
                        </div>

                        <div class="row-input row-input-4">
                            <div class="col amount">
                                <div class="label amount">Jumlah Transfer</div>
                                <input type="text" name="amount" id="amount" value="Rp ">
                                <div class="notice-error amount" style="display: none;">masukkan nominal</div>
                            </div>
                        </div>

                        <div class="row-input row-input-5">
                            <!-- <div class="col type">
                                <div class="label type">Jenis</div>
                                <div id="type" class="input"></div>
                                <div class="notice-error type" style="display: none;">pilih salah satu</div>
                            </div> -->

                            <div class="col bank">
                                <div class="label bank">Bank</div>
                                <div id="bank" class="input"></div>
                                <div class="notice-error bank" style="display: none;">pilih salah satu</div>
                            </div>
                        </div>

                        <div class="row-input row-input-6">
                            <div class="col transfer-date">
                                <div class="label transfer-date">Tanggal Transfer</div>
                                <div id="transfer-date" class="input">Today</div>
                            </div>
                        </div>

                        <div class="row-input row-input-7">
                            <div class="col upload">
                                <div class="label upload">Bukti Transfer</div>
                                <div class="input-upload" id="upload-box">
                                    <!-- <div class="col-1 text">Drag and drop picture here</div>    
                                    <div class="col-2 button">
                                        <button>Upload</button>
                                    </div> -->

                                    <form class="fileUpload" enctype="multipart/form-data">
                                        <input type="file" name="upload" id="upload" accept="image/*" />
                                        <a class="remove-uploaded" title="Remove file" style="display: none;">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    </form>
                                </div>
                                <div class="notice-error upload" style="display: none;">upload bukti transfer</div>
                            </div>
                        </div>

                        <div class="row-input row-input-8">
                            <div class="col submit">
                                <button class="btn btn-primary" id="submit">Kirim</button>
                            </div>
                        </div>

                        <div class="image">
                            <img src="" alt="" id="preview">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var josh_ajax = {
            ajaxurl : "<?php echo admin_url( 'admin-ajax.php'); ?>",
            security : "<?php echo wp_create_nonce( 'ajax_josh_s'); ?>"
        };
        var userId = <?php echo get_current_user_id(); ?>;
		var jsonReady = {
            program: <?php echo $program; ?>,
            platform: <?php echo $platform; ?>,
            cs: <?php echo $cs; ?>,
            // type: <?php //echo $type; ?>,
            bank: <?php echo $bank; ?>
        };

	</script>
    <script defer src="<?php echo plugin_dir_url( __FILE__ ).'assets/asset-j/josh-input-slip.js?v='.$jsVer; ?>"></script>
</body>
</html>