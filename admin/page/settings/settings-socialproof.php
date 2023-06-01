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
                        <h5 class="boxcard-title">Social Proof</h5>  
                        <p class="card-text text-muted">Silahkan diatur social proof sesuai kebutuhan anda.</p>  
                        <hr>   

                        <?php


                        $socialproof_setting  = json_decode($socialproof_settings, true);
                        $popup_style    = $socialproof_setting['settings'][0];
                        $position       = $socialproof_setting['settings'][1];
                        $time_set       = $socialproof_setting['settings'][2];
                        $delay          = $socialproof_setting['settings'][3];
                        $data_load      = $socialproof_setting['settings'][4];

                        if($popup_style=='rounded'){
                            $set_style = 's-rounded';
                        }elseif($popup_style=='flying_boxed'){
                            $set_style = 's-flying';
                        }elseif($popup_style=='flying_rounded'){
                            $set_style = 's-rounded s-flying';
                        }else{
                            $set_style = '';
                        }

                        ?>

                        <style>
                        .donasiaja-socialproof{ -webkit-transition: all 0.5s ease-in-out;     -moz-transition: all 0.5s ease-in-out;     -o-transition: all 0.5s ease-in-out;     -ms-transition: all 0.5s ease-in-out;     transition: all 0.5s ease-in-out;line-height: 1.5;border-radius:6px;max-width:360px;height:75px;padding-right:30px!important;z-index:1 !important;background:#fff!important;box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.09);}.donasiaja-socialproof .toast-close{position:absolute;right:0;color:#fff;margin-top:-16px!important;background:#0000004f;width:25px!important;height:25px!important;font-size:13px!important;text-align:center!important;padding:2px!important;opacity:1;top:10px}.dsproof-avatar{border-radius:4px;width:50px;height:50px;text-align:center;position:absolute;margin-left:-7px;margin-top:0px;font-size:32px;font-weight:700;color:#fffc;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-avatar img{width:50px;height:50px;border-radius:4px;}.dsproof-content{margin-left:54px;color:#888;font-size:11px;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-name{font-size:13px;font-weight:700;color:#35363c;position:absolute;margin-top:-3px}.dsproof-title{color:#656577;padding-top:16px;padding-bottom:2px}.dsproof-verified{font-size:10px;color:#b0b0c6;margin-bottom:2px;}.dsproof-verified span{padding-left:15px}.dsproof-verified img{width:12px;position:absolute;margin-top:2px}.toastify{padding:12px 20px;padding-top:12px!important;color:#fff;display:inline-block;box-shadow:0 3px 6px -1px rgba(0,0,0,.12),0 10px 36px -4px rgba(77,96,232,.3);background:-webkit-linear-gradient(315deg,#73a5ff,#5477f5);background:linear-gradient(135deg,#73a5ff,#5477f5);opacity:0;transition:all .4s cubic-bezier(.215,.61,.355,1);cursor:pointer;text-decoration:none;z-index:2147483647}.toastify.on{opacity:1}.toast-close{opacity:.4;padding:0 5px}.toastify-right{right:15px}.toastify-left{left:15px}.toastify-top{top:-150px}.toastify-bottom{bottom:-150px}.toastify-rounded{}.toastify-avatar{width:1.5em;height:1.5em;margin:-7px 5px;border-radius:2px}.toastify-center{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content;max-width:-moz-fit-content}@media only screen and (max-width:360px){.toastify-left,.toastify-right{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content}} .donasiaja-socialproof.s-rounded .dsproof-avatar{border-radius: 50px;} .donasiaja-socialproof.s-rounded .dsproof-avatar img{border-radius: 50px;}.donasiaja-socialproof.s-rounded {min-height:75px !important;}.donasiaja-socialproof.s-rounded .dsproof-avatar {margin-top:0px;}.donasiaja-socialproof.s-flying{background:transparent!important;box-shadow:none}.donasiaja-socialproof.s-flying .dsproof-avatar{box-shadow:0 3px 6px -1px rgba(0,0,0,.06),0 10px 36px -4px rgba(77,96,232,.09)}.donasiaja-socialproof.s-flying .dsproof-content{background:#fff;padding:10px 20px 10px 16px;border-radius:4px;box-shadow:0 3px 6px -1px rgba(0,0,0,.06),0 10px 36px -4px rgba(77,96,232,.09)}#box-socialproof-setting{background:#e9eef4;padding:30px 15px;min-height:220px;border-radius:4px;margin:0}#time_set_preview{padding-left:0}.top_left{top:0;left:0;margin-top:105px;margin-left:25px}.top_right{top:0;right:0;margin-top:105px;margin-right:25px}.top_right.s-flying{margin-top:105px;margin-right:5px}.bottom_left{bottom:0;left:0;margin-bottom:70px;margin-left:25px}.bottom_right{bottom:0;right:0;margin-bottom:70px;margin-right:25px}.bottom_right.s-flying{margin-bottom:70px;margin-right:5px}
                            </style>

                            <div class="row" id="box-socialproof-setting">

                                <div class="toastify on donasiaja-socialproof <?php echo $set_style; ?> <?php echo $position; ?>" style="background: rgba(0, 0, 0, 0) linear-gradient(to right, rgb(255, 255, 255), rgb(255, 255, 255)) repeat scroll 0% 0%; transform: translate(0px);position: absolute;"><div class="dsproof-container" id="dja0tp32owt"><div class="dsproof-avatar" style="background:#4FC0E8;">D</div><div class="dsproof-avatar" style="display:none;"><img src=""></div><div class="dsproof-content"><div class="dsproof-name">DonasiAja</div><div class="dsproof-title" id="dsproof-title"><?php echo $socialproof_text; ?></div><div class="dsproof-verified"><img src="<?php echo plugin_dir_url( __FILE__ ).'../assets/images/check.png';?>"><span>Verified <span id="time_set_preview" <?php if($time_set=='hide'){echo'style="display:none;"';}?>>- 10 menit yang lalu</span></span></div><div></div></div></div></div>

                            </div>

                            <div style="background: #f0f6fb;padding: 10px 20px;border-radius: 4px;margin-top: -5px;font-weight: bold;font-size: 15px;color: #35363c;">
                                <span>[donasiaja_socialproof]</span>
                                <div class="copy-shortcode-socialproof" data-salin="[donasiaja_socialproof]" style="position: absolute;right: 0;margin-right: 30px;margin-top: -20px;font-size: 11px;color: #96a9c1;font-weight: normal;cursor: pointer;">Copy Shortcode</div>
                            </div>


                    </div><!--end col-->
                </div><!--end row-->
                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: 0px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_general" style="">

                                    <div class="row" style="padding: 20px 0 20px 0;margin-top: -10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Text Description</h5>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="socialproof_text" required="" placeholder="Description" value="<?php echo $socialproof_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>

                                            <ul class="text-muted" style="margin-top: -10px;">
                                                <li>{campaign_title} : Judul campaign</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div id="row-style" class="row" style="padding: 10px 0 20px 0;">
                                        <div class="col-md-6">
                                            <h5 class="card-title mt-0">Popup Style</h5> 
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <!-- <label for="event_1">Page Campaign</label> -->
                                                        <select class="form-control" id="popup_style" name="popup_style" style="height: 45px;font-size: 13px;" title="Event">
                                                            <option value="boxed" <?php if($popup_style=='boxed'){echo 'selected';}?>>Boxed</option>
                                                            <option value="rounded" <?php if($popup_style=='rounded'){echo 'selected';}?>>Rounded</option>
                                                            <option value="flying_boxed" <?php if($popup_style=='flying_boxed'){echo 'selected';}?>>Flying Boxed</option>
                                                            <option value="flying_rounded" <?php if($popup_style=='flying_rounded'){echo 'selected';}?>>Flying Rounded</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title mt-0">Position</h5> 
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <select class="form-control" id="position" name="position" style="height: 45px;font-size: 13px;" title="Event">
                                                            <option value="top_left" <?php if($position=='top_left'){echo 'selected';}?>>Top - Left</option>
                                                            <option value="top_right" <?php if($position=='top_right'){echo 'selected';}?>>Top - Right</option>
                                                            <option value="bottom_left" <?php if($position=='bottom_left'){echo 'selected';}?>>Bottom - Left</option>
                                                            <option value="bottom_right" <?php if($position=='bottom_right'){echo 'selected';}?>>Bottom - Right</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="row-time" class="row" style="padding: 0px 0 20px 0;">
                                        <div class="col-md-6">
                                            <h5 class="card-title mt-0">Time</h5> 
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-9">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="hide" id="customRadio9" name="time_set" class="custom-control-input" <?php if($time_set=='hide') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="customRadio9">Hide</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="show" id="customRadio8" name="time_set" class="custom-control-input" <?php if($time_set=='show') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="customRadio8">Show</label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title mt-0">Delay</h5> 
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <select class="form-control select_event" id="delay" name="delay" style="height: 45px;font-size: 13px;" title="Event">
                                                            <option value="8" <?php if($delay=='8'){echo 'selected';}?>>8 detik</option>
                                                            <option value="10" <?php if($delay=='10'){echo 'selected';}?>>10 detik</option>
                                                            <option value="15" <?php if($delay=='15'){echo 'selected';}?>>15 detik</option>
                                                            <option value="20" <?php if($delay=='20'){echo 'selected';}?>>20 detik</option>
                                                            <option value="30" <?php if($delay=='30'){echo 'selected';}?>>30 detik</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                            <div class="col-md-6">
                                            <h5 class="card-title mt-0">Data Load</h5> 
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <select class="form-control select_event" id="data_load" name="data_load" style="height: 45px;font-size: 13px;" title="Event">
                                                            <option value="10" <?php if($data_load=='10'){echo 'selected';}?>>10 data</option>
                                                            <option value="15" <?php if($data_load=='15'){echo 'selected';}?>>15 data</option>
                                                            <option value="30" <?php if($data_load=='30'){echo 'selected';}?>>30 data</option>
                                                            <option value="45" <?php if($data_load=='45'){echo 'selected';}?>>45 data</option>
                                                            <option value="60" <?php if($data_load=='60'){echo 'selected';}?>>60 data</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <hr>
                                            <br>
                                            <button type="button" class="btn btn-primary px-5 py-2" id="update_socialproof">Update <div class="spinner-border spinner-border-sm text-white update_socialproof_loading" style="margin-left: 3px;display: none;"></div></button>
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
