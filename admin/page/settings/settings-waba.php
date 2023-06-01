<?php
    $table_settings = $wpdb->prefix . 'dja_settings';

    $query = "SELECT data
    FROM $table_settings
    WHERE type='fb_graphapi_token'";

    $fb_graphapi_token = $wpdb->get_row($query)->data;
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
                        <h5 class="boxcard-title">Whatsapp Business Platform</h5>  
                        <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan anda.</p>  
                        <hr>           
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row form-group">
                    <div class="col">
                        <label for="input-waba-token">Authorization Token</label>
                        <input type="text" class="form-control" id="input-waba-token" value="<?php echo $fb_graphapi_token; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 30px;">
                        <hr>
                    </div>
                </div>

                <!-- <div class="row form-group">
                    <div class="col">
                        <h5 class="card-title mt-0">Category <div class="spinner-border spinner-border-sm text-primary set_category_loading" style="margin-left: 10px;display: none;"></div></h5>

                        <label for=""></label>
                        <input type="text" name="" id="">
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_general" style="">

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <hr>
                                            <br>
                                            <button type="button" class="btn btn-primary px-5 py-2" id="btn-update-waba">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" id="btn-update-waba-spinner" style="margin-left: 3px;display: none;"></div></button>
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
    $('#btn-update-waba').click(function (e) { 
        e.preventDefault();
        
        $('#btn-update-waba-spinner').show();
        waba_submit();
    });

    function waba_submit() {
        let data_ = {
            action: 'jh_settings_waba',
            waba_token: () => {return $('#input-waba-token').val();}
        };

        $.post("admin-ajax.php", data_,
            function (data, textStatus, jqXHR) {
                
                if(data.status === 'success') {
                    $('#btn-update-waba-spinner').hide();

                    Swal.fire('success');
                }
            }
        );
    }
</script>