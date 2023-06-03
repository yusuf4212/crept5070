<?php
    $table_capi_pixel = $wpdb->prefix . 'josh_capi_pixel';
    $table_settings = $wpdb->prefix . 'dja_settings';

    // get pixel data
    $query = "SELECT *
    FROM $table_capi_pixel";

    $pixels = $wpdb->get_results($query);

    // get access token
    $query = "SELECT data
    FROM $table_settings
    WHERE type='capi_access_token' or type='run_capi'";

    $rows = $wpdb->get_results($query);

    $access_token   = $rows[0]->data;
    $run_capi       = $rows[1]->data;

    $run_capi_chk   = ($run_capi === '1') ? 'checked' : '';
    $run_capi_text  = ($run_capi === '1') ? 'Active' : 'Not Active';
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
                        <h5 class="boxcard-title">Facebook Pixel Conversion API (CAPI)</h5>
                        <p class="card-text text-muted">.</p>  
                        <hr>           
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row" style="padding: 0px 0 30px 0;">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <h5 class="card-title mt-0">Run CAPI</h5>
                        <div class="form-group">
                            <div class="custom-control custom-switch" id="checkbox-capi-box">
                                <input type="checkbox" class="custom-control-input checkbox1" id="checkbox-capi" data-id="1" <?= $run_capi_chk; ?>>
                                <label class="custom-control-label" for="checkbox-capi"><span><?= $run_capi_text; ?></span></label>
                            </div>
                        </div>
                        <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan untuk menjalankan fitur CAPI.</p> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <h5 class="card-title mt-0">Pixel Settings</h5>
                    </div>
                </div>
                
                <?php
                    $i = 1;
                    foreach($pixels as $data) {
                        $id_name = "input-pixel-name-$i";
                        $id_id = "input-pixel-id-$i";
                    ?>
                        <div class="row form-group mb-3 input-pixel-box" jh-data="<?= $data->id; ?>">
                            <div class="col-5">
                                <label for="input-pixel-name">Nama Pixel</label>
                                <input type="text" class="form-control input-pixel-name" id="<?= $id_name; ?>" value="<?= $data->pixel_name; ?>">
                            </div>

                            <div class="col-5">
                                <label for="input-pixel-id">Pixel Id</label>
                                <input type="text" class="form-control input-pixel-id" id="<?= $id_id; ?>" value="<?= $data->pixel_id; ?>">
                            </div>

                            <div class="col-2 align-self-center">
                                <span class="badge bg-danger text-white remove-pixel" style="cursor: pointer;" data="<?= $data->id; ?>">remove</span>
                            </div>
                        </div>
                    <?php
                    $i++;
                }
                ?>

                <div class="row form-group">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="btn-add-pixel" style="margin-left: 10px;margin-top: 20px;font-size: 11px;">+ Add New</button>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <br>
                        <hr>
                        <br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <h5 class="card-title mt-0">Access Token</h5>
                    </div>
                </div>

                <div class="row form-group mb-3">
                    <div class="col">
                        <label for="input-pixel-name">Access Token</label>
                        <input type="text" class="form-control" id="input-pixel-name" value="<?= $access_token; ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_general" style="">

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <hr>
                                            <br>
                                            <button type="button" class="btn btn-primary px-5 py-2" id="update-waba">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" style="margin-left: 3px;display: none;"></div></button>
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

<script>
    $('.remove-pixel').click(function (e) { 
        e.preventDefault();
        
        _remove_pixel(e.delegateTarget.attributes[2].value)
    });

    $('#btn-add-pixel').click(function (e) { 
        e.preventDefault();
        
        _add_pixel();
    });

    $('#update-waba').click(function (e) { 
        e.preventDefault();
        
        _update_waba();
    });

    $('#checkbox-capi').change(function (e) { 
        e.preventDefault();
        
        if(e.delegateTarget.checked) {
            $('span', e.delegateTarget.parentElement).text('Active');
        } else {
            $('span', e.delegateTarget.parentElement).text('Not Active');
        }
    });

    /**
     * @param {string}
     */
    function _remove_pixel(id) {
        if(id != null) {
            Swal.fire({
                title: 'Yakin Hapus Pixel?',
                text: 'Tindakan ini tidak dapat dikembalikan.',
                showCancelButton: true
            })
            .then((e) => {
                if(e.value) {
                    Swal.fire({
                        title: 'Please wait',
                        html: '<div class="spinner-border spinner-border-sm"></div>',
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });

                    let data_ = {
                        action: 'jh_remove_pixel',
                        id: id
                    };

                    $.post("admin-ajax.php", data_,
                        function (data, textStatus, jqXHR) {
                            if(data.status === 'success') {
                                Swal.fire({
                                    title: 'Remove Success!',
                                    icon: 'success',
                                    timer: 2500,
                                    timerProgressBar: true
                                })
                                .then(() => {
                                    location.reload();
                                })
                            } else {
                                Swal.fire({
                                    title: 'Remove Failed!',
                                    icon: 'error',
                                    text: data.messages
                                })
                            }
                        }
                    );
                }
            });
        }
    }

    function _add_pixel() {
        Swal.fire({
            title: 'Please wait',
            html: '<div class="spinner-border spinner-border-sm"></div>',
            allowOutsideClick: false,
            showConfirmButton: false
        });

        $.post("admin-ajax.php", {action: 'jh_add_pixel'},
            function (data, textStatus, jqXHR) {
                if(data.status === 'success') {
                    Swal.fire({
                        title: 'Add New Success!',
                        icon: 'success',
                        timer: 2500,
                        timerProgressBar: true
                    })
                    .then(() => {
                        location.reload();
                    })
                } else {
                    Swal.fire({
                        title: 'Remove Failed!',
                        icon: 'error',
                        text: data.messages
                    })
                }
            }
        );

    }

    function _update_waba() {
        Swal.fire({
            title: 'Please wait',
            html: '<div class="spinner-border spinner-border-sm"></div>',
            allowOutsideClick: false,
            showConfirmButton: false
        });

        /**
         * 
         */
        let checkboxCapi = ($('#checkbox-capi:checked').val() === 'on') ? true : false;

        /**
         * 
         */
        let a = document.querySelectorAll('.input-pixel-box');
        let pixels = [];
        
        for(let i=0; i < a.length; i++) {
            let pixelName = $('.input-pixel-name', a[i]).val();
            let pixelId = $('.input-pixel-id', a[i]).val();
            let dataId = $(a[i]).attr('jh-data');

            pixels.push({
                id: dataId,
                pixelName: pixelName,
                pixelId: pixelId
            })
        }

        /**
         * 
         */
        let accessToken = $('#input-pixel-name').val();

        let payload = {
            statusCapi: checkboxCapi,
            pixels: pixels,
            token:accessToken
        };

        let data_ = {action: 'jh_update_pixel', data: payload};

        $.post("admin-ajax.php", data_,
            function (data, textStatus, jqXHR) {
                if(data.status === 'success') {
                    Swal.fire({
                        title: 'Update Success!',
                        icon: 'success',
                        timer: 2500,
                        timerProgressBar: true
                    })
                } else {
                    Swal.fire({
                        title: 'Update Failed!',
                        icon: 'error',
                        text: data.messages
                    })
                    .then(() => {
                        location.reload();
                    })
                }
            }
        );
    }
</script>