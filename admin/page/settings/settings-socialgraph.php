<?php
    $table_capi_pixel = $wpdb->prefix . 'josh_capi_pixel';
    $table_settings = $wpdb->prefix . 'dja_settings';

    // get pixel data
    $query = "SELECT *
    FROM $table_capi_pixel";

    $pixels = $wpdb->get_results($query);


    $query = "SELECT data
    FROM $table_settings
    WHERE type='ad_account_id' or type='social_graph_token'";

    $rows = $wpdb->get_results($query);
    $ad_account_id      = $rows[0]->data;
    $social_graph_token = $rows[1]->data;

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
                        <h5 class="boxcard-title">Social Graph</h5>
                        <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan anda.</p>  
                        <hr>           
                    </div><!--end col-->
                </div><!--end row-->
                
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
