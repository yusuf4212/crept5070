<?php

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
                        <h5 class="boxcard-title">Themes</h5>  
                        <p class="card-text text-muted">Silahkan diatur sesuai tema anda.</p>  
                        <hr>           
                    </div><!--end col-->
                </div><!--end row-->
                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_themes" style="">

                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="card-title mt-0">App Logo<span></span></h5>

                                                    <span class="fro-profile_main-pic-change" id="upload_app_logo" title="Upload App Logo">
                                                        <i class="fas fa-camera"></i>
                                                    </span>

                                            <div class="met-profile-main">
                                                <div class="met-profile-main-pic" id="app_logo" style="height: 100px;">
                                                <?php if($logo_url=='') { ?>
                                                    <img src="<?php echo plugin_dir_url( __FILE__ ) . "../assets/images/pp.jpg"; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 4px;">
                                                <?php }else{?>
                                                    <img src="<?php echo $logo_url; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 4px;" data-action="zoom">
                                                <?php } ?>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">App Name<span></span></h5>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="app_name" required="" placeholder="App Name" value="<?php echo $app_name; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 5px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Theme Color<span></span></h5>
                                            <div class="form-group">
                                                <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][0]; ?>" id="theme_color" data-control="hue" style="height: 45px;width: auto !important;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Progress Bar<span></span></h5>
                                            <div class="form-group">
                                                <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][1]; ?>" id="progressbar_color" data-control="hue" style="height: 45px;width: auto !important;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Donation Button on Page Campaign<span></span></h5>
                                            <div class="form-group">
                                                <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][2]; ?>" id="button_color" data-control="hue" style="height: 45px;width: auto !important;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Donation Button on Shortcode List Campaign<span></span></h5>
                                            <div class="form-group">
                                                <input class="form-control coloring" type="text" value="<?php echo $theme_color['color'][3]; ?>" id="button_color2" data-control="hue" style="height: 45px;width: auto !important;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <hr>
                                            <br>
                                            <button type="button" class="btn btn-primary px-5 py-2" id="update_themes">Update <div class="spinner-border spinner-border-sm text-white update_themes_loading" style="margin-left: 3px;display: none;"></div></button>
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
