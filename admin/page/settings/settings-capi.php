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
    WHERE type='capi_access_token'";

    $access_token = $wpdb->get_row($query)->data;
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
                        <div class="row form-group mb-3">
                            <div class="col-5">
                                <label for="input-pixel-name">Nama Pixel</label>
                                <input type="text" class="form-control " id="<?= $id_name; ?>" value="<?= $data->pixel_name; ?>">
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
                                            <button type="button" class="btn btn-primary px-5 py-2" id="update_waba">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" style="margin-left: 3px;display: none;"></div></button>
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

    /**
     * @param {string}
     */
    function _remove_pixel(id) {
        if(id != null) {
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
    }
</script>