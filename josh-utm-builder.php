<?php
header('Cache-Control: no-store, no-cache, must-revalidate');

global $wpdb;
$table_duta = $wpdb->prefix . 'josh_duta';
$table_link = $wpdb->prefix . 'josh_link_target';

{
    $query = "SELECT id, name, code FROM $table_duta";
    $rows = $wpdb->get_results($query);

    $duta = array();
    foreach($rows as $data) {
        $duta[] = array(
            'id'        => intval($data->id),
            'text'      => $data->name,
            'value'     => $data->code
        );
    }

}

{
    $query = "SELECT id, title, slug FROM $table_link";
    $rows = $wpdb->get_results($query);

    $target = array();
    foreach($rows as $data) {
        $target[] = array(
            'id'        => intval($data->id),
            'text'      => $data->title,
            'value'     => $data->slug
        );
    }
}

/**
 * versioning control
 */
$jsVer = '1.0.2';
$cssVer = '1.0.4';
?>

<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Builder YMPB</title>

    <link rel="shortcut icon" href="https://ympb.or.id/wp-content/uploads/2023/03/Logo-ympb-768x782-1.webp" type="image/x-icon">

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>

    <!-- swal -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    
    <!-- custom css -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'assets/asset-j/josh-url-builder.css?v='. $cssVer; ?>">

    <!-- selectivity -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'selectivity-jquery.css?v=1.0.1'; ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script defer src="<?php echo plugin_dir_url( __FILE__ ); ?>selectivity-jquery.js"></script>


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

                    <div class="row title">URL Builder YMPB</div>

                    <div class="app-version">
                        <div>Version info:</div>
                        <div>CSS : <?php echo $cssVer; ?></div>
                        <div>JS : <?php echo $jsVer; ?></div>
                    </div>

                    <div class="row input box">
                        <div class="row-input row-input-section">
                            <div class="col radio-1">
                                <input type="radio" value="duta" name="owner" id="duta" checked>
                                <label for="duta">duta</label>
                            </div>
                            
                            <div class="col radio-2">
                                <input type="radio" value="cs" name="owner" id="cs">
                                <label for="cs">cs dfr</label>
                            </div>
                            
                            <div class="col radio-3">
                                <input type="radio" value="cc" name="owner" id="cc">
                                <label for="cc">cc</label>
                            </div>
                        </div>

                        <div class="row section duta-section" id="duta-section">
                            <div class="row-input row-input-1">
                                <div class="col duta">
                                    <div class="label duta">Duta</div>
    
                                    <div id="duta-name" class="input"></div>
    
                                    <div class="notice-error type" style="display: none;">pilih salah satu</div>
                                </div>
                            </div>
                            
                            <div class="row-input row-input-2">
                                <div class="col target">
                                    <div class="label target">Halaman Target</div>
    
                                    <div id="target-page-duta" class="input"></div>
    
                                    <div class="notice-error type" style="display: none;">pilih salah satu</div>
                                </div>
                            </div>
                        </div>

                        <div class="row section cs-section" id="cs-section" style="display: none;">
                            <div class="row-input row-input-1">
                                <div class="col duta">
                                    <div class="label duta">CS</div>
    
                                    <div id="cs-name" class="input"></div>
    
                                    <div class="notice-error type" style="display: none;">pilih salah satu</div>
                                </div>
                            </div>
                            
                            <div class="row-input row-input-2">
                                <div class="col target">
                                    <div class="label target">Halaman Target</div>
    
                                    <div id="target-page-cs" class="input"></div>
    
                                    <div class="notice-error type" style="display: none;">pilih salah satu</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row section cc-section" id="cc-section" style="display: none;">
                            <div class="row-input row-input-1">
                                <div class="col duta">
                                    <div class="label duta">CC</div>
    
                                    <div id="cc-name" class="input"></div>
    
                                    <div class="notice-error type" style="display: none;">pilih salah satu</div>
                                </div>
                            </div>
                            
                            <div class="row-input row-input-2">
                                <div class="col target">
                                    <div class="label target">Halaman Target</div>
    
                                    <div id="target-page-cc" class="input"></div>
    
                                    <div class="notice-error type" style="display: none;">pilih salah satu</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row result">
                        <div class="title">Hasil</div>

                        <div class="box-url">
                            <div class="text" id="result-copy"></div>

                            <div class="copy-btn" id="copy-btn">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                        <path d="M16.4574 0H4.88298C4.73908 0 4.60108 0.0537992 4.49934 0.149562C4.39759 0.245326 4.34043 0.375209 4.34043 0.510638V4.08511H0.542553C0.398659 4.08511 0.260659 4.13891 0.15891 4.23467C0.0571616 4.33043 0 4.46031 0 4.59574V15.4894C0 15.6248 0.0571616 15.7547 0.15891 15.8504C0.260659 15.9462 0.398659 16 0.542553 16H12.117C12.2609 16 12.3989 15.9462 12.5007 15.8504C12.6024 15.7547 12.6596 15.6248 12.6596 15.4894V11.9149H16.4574C16.6013 11.9149 16.7393 11.8611 16.8411 11.7653C16.9428 11.6696 17 11.5397 17 11.4043V0.510638C17 0.375209 16.9428 0.245326 16.8411 0.149562C16.7393 0.0537992 16.6013 0 16.4574 0ZM11.5745 14.9787H1.08511V5.10638H11.5745V14.9787ZM15.9149 10.8936H12.6596V4.59574C12.6596 4.46031 12.6024 4.33043 12.5007 4.23467C12.3989 4.13891 12.2609 4.08511 12.117 4.08511H5.42553V1.02128H15.9149V10.8936Z" fill="black"/>
                                    </svg>
                                </div>

                                <div class="text">copy</div>
                            </div>
                        </div>

                        <div class="copied-success" id="copied-success" style="display: none;">Sudah Tersalin</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const duta = JSON.parse('<?php echo json_encode($duta); ?>');
        const target = JSON.parse('<?php echo json_encode($target); ?>');
        var josh_ajax = {
            ajaxurl : "<?php echo admin_url( 'admin-ajax.php'); ?>",
            security : "<?php echo wp_create_nonce( 'ajax_josh_s'); ?>"
        };
        var userId = <?php echo get_current_user_id(); ?>;
		var jsonReady = {
            program: '',
            platform: '',
            cs: '',
            type: '',
            bank: ''
        };

	</script>
    <script defer src="<?php echo plugin_dir_url( __FILE__ ).'assets/asset-j/josh-url-builder.js?v='.$jsVer; ?>"></script>
</body>
</html>