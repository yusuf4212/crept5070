<?php
    $table_settings = $wpdb->prefix . 'dja_settings';

    $query = "SELECT data
    FROM $table_settings
    WHERE type='fb_graphapi_token' or type='fb_graphapi_version' or type='waba_phone' or type='run_waba'";

    $rows = $wpdb->get_results($query);

    $fb_graphapi_token = $rows[0]->data;
    $fb_graphapi_version = $rows[1]->data;
    $waba_phone = $rows[2]->data;
    $run_waba = $rows[3]->data;

    $run_waba_chk = ($run_waba === '1') ? 'checked' : '';
    $run_waba_text = ($run_waba === '1') ? 'Active' : 'Not Active';
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

                <div class="row" style="padding: 0px 0 30px 0;">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <h5 class="card-title mt-0">Run WABA</h5>
                        <div class="form-group">
                            <div class="custom-control custom-switch" id="checkbox-waba-box">
                                <input type="checkbox" class="custom-control-input checkbox1" id="checkbox-waba" data-id="1" <?= $run_waba_chk; ?>>
                                <label class="custom-control-label" for="checkbox-waba"><span><?= $run_waba_text; ?></span></label>
                            </div>
                        </div>
                        <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan untuk menjalankan fitur WABA.</p> 
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <h4 class="mt-0 header-title">Facebook Graph API and WABA Setup</h4>
                        <p class="card-text text-muted">...</p>  
                    </div>

                    <div class="col">
                        <label for="input-graphapi-token">Authorization Token</label>
                        <input type="text" class="form-control" id="input-graphapi-token" value="<?php echo $fb_graphapi_token; ?>" required>
                    </div>
                </div>
                
                <div class="row form-group">
                    <div class="col">
                        <label for="input-graphapi-version">Graph API Version</label>
                        <input type="text" class="form-control" id="input-graphapi-version" value="<?php echo $fb_graphapi_version; ?>" required>
                    </div>

                    <div class="col">
                        <label for="input-waba-number">Phone Number ID</label>
                        <input type="text" class="form-control" id="input-waba-number" value="<?php echo $waba_phone; ?>" required>
                    </div>

                    <div class="col-md-12">
                        <br>
                        <button type="button" class="btn btn-primary px-5 py-2" id="btn-update-waba">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" id="btn-update-waba-spinner" style="margin-left: 3px;display: none;"></div></button>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 30px;">
                        <hr>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <h4 class="mt-0 header-title">WABA Test</h4>
                        <p class="card-text text-muted">...</p>  
                    </div>

                    <div class="col-8">
                        <label for="input-test-number">Nomor Tujuan</label>
                        <input type="text" class="form-control" id="input-test-number" placeholder="628xxx">
                        <div id="input-test-number-error" class="mx-1 text-danger" style="color: #b30909; display: none;">Harap isi dengan benar.</div>
                    </div>

                    <div class="col-12 mt-2">
                        <button type="button" class="btn btn-primary" id="btn-test">Test Now <div class="spinner-border spinner-border-sm text-white update_general_loading" id="btn-test-spinner" style="margin-left: 3px;display: none;"></div></button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_general" style="">

                                    <div class="row" style="margin-top: 10px;">
                                        <!-- <div class="col-md-12">
                                            <hr>
                                            <br>
                                            <button type="button" class="btn btn-primary px-5 py-2" id="btn-update-waba">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" id="btn-update-waba-spinner" style="margin-left: 3px;display: none;"></div></button>
                                        </div> -->
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

    $('#checkbox-waba').change(function (e) { 
        e.preventDefault();
        
        if(e.delegateTarget.checked) {
            $('span', e.delegateTarget.parentElement).text('Active');
        } else {
            $('span', e.delegateTarget.parentElement).text('Not Active');
        }
    });

    function waba_submit() {
        let data_ = {
            action: 'jh_settings_waba',
            graphapi_token: () => {return $('#input-graphapi-token').val();},
            graphapi_version: () => {return $('#input-graphapi-version').val();},
            waba_number: () => {return $('#input-waba-number').val();},
            waba_status: () => {return $('#checkbox-waba:checked').val();}
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

    $('#btn-test').click(function (e) { 
        e.preventDefault();

        let number_ = $('#input-test-number').val();

        if(number_ == '') {
            $('#input-test-number-error').slideDown('fast');

            setTimeout(() => {
                $('#input-test-number-error').slideUp();
            }, 2500);
        } else {
            $('#btn-test-spinner').show();
            
            let data_ = {
                action: 'jh_testing_waba',
                number: number_
            };
    
            $.post("admin-ajax.php", data_,
                function (data, textStatus, jqXHR) {
                    if(data.status === 'success') {
                        Swal.fire({
                            title: 'Success',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Failed!',
                            text: data.messages,
                            icon: 'danger'
                        })
                    }
                    
                    $('#btn-test-spinner').hide();
                }
            );
        }

    });
</script>