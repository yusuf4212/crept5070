<?php
header('Cache-Control: no-store, no-cache, must-revalidate');

global $wpdb;
$table_duta = $wpdb->prefix . 'josh_duta';
$table_link = $wpdb->prefix . 'josh_link_target';
$table_settings = $wpdb->prefix . 'dja_settings';

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
    $query = "SELECT data
    FROM $table_settings
    WHERE type='url_usource' or type='url_ucontent' or type='url_ucampaign' or type='user_can_urlbuilder_cc'";
    $rows = $wpdb->get_results($query);

    $url_usource    = $rows[0]->data;
    $url_ucontent   = $rows[1]->data;
    $url_ucampaign  = $rows[2]->data;
    $allow_user_cc  = json_decode($rows[3]->data);
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
 * Show CC only registered user
 */
{
    $user = wp_get_current_user();

    // $show_cc = (! in_array('2', $allow_user_cc)) ? ' style="display: none;"' : '';
    $show_cc = (! in_array(strval($user->ID), $allow_user_cc)) ? ' style="display: none;"' : '';

    $show_login = ($user->ID === 0) ? '' : ' display: none;';
}

/**
 * versioning control
 */
$jsVer = '1.0.7';
?>

<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Builder YMPB</title>

    <link rel="shortcut icon" href="https://ympb.or.id/wp-content/uploads/2023/03/Logo-ympb-768x782-1.webp" type="image/x-icon">

    <!-- bootstrap 5.3 -->
    <link rel="stylesheet" href="<?php echo DJA_PLUGIN_URL . 'assets/vendor/twbs/bootstrap/dist/css/bootstrap.css' ?>">
    <script src="<?php echo DJA_PLUGIN_URL . 'assets/vendor/twbs/bootstrap/dist/js/bootstrap.js' ?>"></script>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <!-- custom css -->
    <link rel="stylesheet" href="<?php //echo plugin_dir_url( __FILE__ ).'assets/asset-j/josh-url-builder.css?v='. $cssVer; ?>">

    <!-- selectivity -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'selectivity-jquery.css?v=1.0.1'; ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script defer src="<?php echo plugin_dir_url( __FILE__ ); ?>selectivity-jquery.js"></script>

    <style>
        body {
            background-color: #e9ecef;
        }
    </style>

</head>
<body>
    <div class="mx-auto px-2" style="margin-top: 100px; margin-bottom: 200px; max-width: 450px;">
        <div class="container-fluid bg-white rounded shadow px-4" style="padding-bottom: 40px;">
            <div class="row logo">
                <div class="col d-flex justify-content-center">
                    <img class="img-fluid" style="max-width: 80px; margin-top: -40px;" src="https://ympb.or.id/wp-content/uploads/2022/10/Logo-ympb-768x782-1.png" alt="">
                </div>

                <span style="position: fixed; margin-top: 30px;<?= $show_login; ?>"><a href="<?= wp_login_url('url-builder'); ?>" class="text-dark" style=" text-decoration: underline dashed 1px; font-size: 14px;">login</a></span>
            </div>
        
            <div class="row title text-center" style="margin-top: 35px;">
                <h2>URL Builder YMPB</h2>
            </div>
        
            <div class="row app-version mt-2" style="font-size: 12px;">
                <div>Version info: <?= $jsVer; ?></div>
            </div>
        
            <!-- Radio Button Mode -->
            <div class="row mt-2 row-input row-input-section">
                <div class="col radio-1">
                    <input type="radio" value="duta" name="owner" id="duta" checked>
                    <label for="duta">Duta</label>
                </div>
                
                <div class="col radio-2">
                    <input type="radio" value="cs" name="owner" id="cs">
                    <label for="cs">CS DFR</label>
                </div>
                
                <div class="col radio-3" <?= $show_cc; ?>>
                    <input type="radio" value="cc" name="owner" id="cc">
                    <label for="cc">CC</label>
                </div>
            </div>

            <!-- Duta Section -->
            <div class="row input box mt-4">
                <div class="col-12 section duta-section" id="duta-section">
                    <div class="row row-input row-input-1">
                        <div class="col duta">
                            <div class="label duta">Duta</div>
        
                            <div id="duta-name" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>
                    
                    <div class="row row-input row-input-2 mt-3">
                        <div class="col target">
                            <div class="label target">Halaman Target</div>
        
                            <div id="target-page-duta" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>
                </div>
        
                <!-- CS Section -->
                <div class="col-12 section cs-section" id="cs-section" style="display: none;">
                    <div class="row row-input row-input-1">
                        <div class="col duta">
                            <div class="label duta">CS</div>
        
                            <div id="cs-name" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>
                    
                    <div class="row row-input row-input-2 mt-3">
                        <div class="col target">
                            <div class="label target">Halaman Target</div>
        
                            <div id="target-page-cs" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>
                </div>
                
                <!-- CC Section -->
                <div class="col-12 section cc-section" id="cc-section" style="display: none;">
                    <div class="row">
                        <div class="col preset">
                            <div class="label duta">Preset:<span class="text-body-tertiary" style="font-size: 12px;"> (kosongkan untuk buat baru)</span></div>
        
                            <div id="cc-preset" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>

                    <div class="row my-2" style="margin-left: -35px; margin-right: -35px;">
                        <div class="col">
                            <hr>
                        </div>
                    </div>

                    <div class="row row-input row-input-1 mt-4">
                        <div class="col">
                            <div class="d-flex justify-content-between">
                                <div class="label">UTM Source<span class="text-danger">*</span></div>

                                <div class="text-primary fw-bold" id="add-new-usource" style="cursor: pointer; font-size: 14px;">+ Add New</div>
                            </div>
        
                            <div id="cc-usource" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>

                    <div class="row row-input row-input-1 mt-4">
                        <div class="col duta">
                            <div class="d-flex justify-content-between">
                                <div class="label duta">UTM Content<span class="text-danger">*</span></div>

                                <div class="text-primary fw-bold" id="add-new-ucontent" style="cursor: pointer; font-size: 14px;">+ Add New</div>
                            </div>
        
                            <div id="cc-ucontent" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>

                    <div class="row row-input row-input-1 mt-4">
                        <div class="col">
                            <div class="d-flex justify-content-between">
                                <div class="label">UTM Campaign<span class="text-danger">*</span></div>

                                <div class="text-primary fw-bold" id="add-new-ucampaign" style="cursor: pointer; font-size: 14px;">+ Add New</div>
                            </div>
        
                            <div id="cc-ucampaign" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>
                    
                    <div class="row row-input row-input-2 mt-4">
                        <div class="col target">
                            <div class="label target">Halaman Target<span class="text-body-tertiary" style="font-size: 12px;"> (opsional)</span></div>
        
                            <div id="target-page-cc" class="input border-bottom"></div>
        
                            <div class="notice-error type" style="display: none;">pilih salah satu</div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <label for="exampleFormControlInput1" class="form-label">Short Link (ympb.me)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="rumah-tahfizh-ig-post">
                        </div>

                        <div class="col-12">
                            <span class="text-primary fw-bold mt-1 ps-2" id="submit-short-link" style="cursor: pointer; font-size: 14px;">Submit Preset</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-2" style="margin-left: -35px; margin-right: -35px;">
                <div class="col">
                    <hr>
                </div>
            </div>
        
            <div class="row result mt-4">
                <div class="col-12">
                    <div class="title">Hasil</div>
                </div>

                <div class="col-9">
                    <div class="text" id="result-copy" style="font-size: 14px;"></div>
                </div>
                
                <div class="col-3 d-flex align-items-center">
                    <div class="btn btn-primary btn-sm copy-btn d-flex gap-2 shadow" id="copy-btn" style="cursor: pointer;">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="#ffffff">
                                <path d="M16.4574 0H4.88298C4.73908 0 4.60108 0.0537992 4.49934 0.149562C4.39759 0.245326 4.34043 0.375209 4.34043 0.510638V4.08511H0.542553C0.398659 4.08511 0.260659 4.13891 0.15891 4.23467C0.0571616 4.33043 0 4.46031 0 4.59574V15.4894C0 15.6248 0.0571616 15.7547 0.15891 15.8504C0.260659 15.9462 0.398659 16 0.542553 16H12.117C12.2609 16 12.3989 15.9462 12.5007 15.8504C12.6024 15.7547 12.6596 15.6248 12.6596 15.4894V11.9149H16.4574C16.6013 11.9149 16.7393 11.8611 16.8411 11.7653C16.9428 11.6696 17 11.5397 17 11.4043V0.510638C17 0.375209 16.9428 0.245326 16.8411 0.149562C16.7393 0.0537992 16.6013 0 16.4574 0ZM11.5745 14.9787H1.08511V5.10638H11.5745V14.9787ZM15.9149 10.8936H12.6596V4.59574C12.6596 4.46031 12.6024 4.33043 12.5007 4.23467C12.3989 4.13891 12.2609 4.08511 12.117 4.08511H5.42553V1.02128H15.9149V10.8936Z" fill="#ffffff"/>
                            </svg>
                        </div>
        
                        <div class="text">Copy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ajaxLink  = '<?= home_url('wp-admin/admin-ajax.php'); ?>';
        const duta      = JSON.parse('<?php echo json_encode($duta); ?>');
        const target    = JSON.parse('<?php echo json_encode($target); ?>');
        const uSource   = JSON.parse('<?= $url_usource; ?>'); console.log(uSource)
        const uContent  = JSON.parse('<?= $url_ucontent; ?>');
        const uCampaign = JSON.parse('<?= $url_ucampaign; ?>');
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
    <script src="<?php echo plugin_dir_url( __FILE__ ).'assets/asset-j/Url_Builder.js?v='.$jsVer; ?>"></script>
    <script defer src="<?php echo plugin_dir_url( __FILE__ ).'assets/asset-j/josh-url-builder.js?v='.$jsVer; ?>"></script>
</body>
</html>