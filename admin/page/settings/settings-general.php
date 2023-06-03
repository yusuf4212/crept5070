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
                        <h5 class="boxcard-title">General</h5>  
                        <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan anda.</p>  
                        <hr>           
                    </div><!--end col-->
                </div><!--end row-->
                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_general" style="">

                                    <?php

                                    if($login_setting=='1'){
                                        $status_text1 = '<span>Active</span>';
                                        $checked1 = 'checked=""';
                                    }else{
                                        $status_text1 = '<span>Not Active</span>';
                                        $checked1 = '';
                                    }

                                    if($register_setting=='1'){
                                        $status_text2 = '<span>Active</span>';
                                        $checked2 = 'checked=""';
                                    }else{
                                        $status_text2 = '<span>Not Active</span>';
                                        $checked2 = '';
                                    }

                                    if($campaign_setting=='1'){
                                        $status_text3 = '<span>Active</span>';
                                        $checked3 = 'checked=""';
                                    }else{
                                        $status_text3 = '<span>Not Active</span>';
                                        $checked3 = '';
                                    }

                                    if($del_campaign_setting=='1'){
                                        $status_text4 = '<span>Active</span>';
                                        $checked4 = 'checked=""';
                                    }else{
                                        $status_text4 = '<span>Not Active</span>';
                                        $checked4 = '';
                                    }

                                    if($powered_by_setting=='1'){
                                        $status_text5 = '<span>Show</span>';
                                        $checked5 = 'checked=""';
                                    }else{
                                        $status_text5 = '<span>Hide</span>';
                                        $checked5 = '';
                                    }

                                    if($changepass_setting=='1'){
                                        $status_text7 = '<span>Active</span>';
                                        $checked7 = 'checked=""';
                                    }else{
                                        $status_text7 = '<span>Not Active</span>';
                                        $checked7 = '';
                                    }

                                    if($register_checkbox_setting=='1'){
                                        $status_text8 = '<span>Active</span>';
                                        $checked8 = 'checked=""';
                                    }else{
                                        $status_text8 = '<span>Not Active</span>';
                                        $checked8 = '';
                                    }

                                    ?>

                                    
                                    <div class="row" style="margin-bottom: 20px;">

                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h4 class="mt-0 header-title">Data Shortcode</h4>
                                            <p class="card-text text-muted">Gunakan shortcode berikut untuk memanggil data yang sesuai anda butuhkan.</p>  
                                        </div>

                                        <div class="col-md-8 pricingTable1 text-center">
                                            <ul class="list-unstyled pricing-content-2 text-left py-1 border-0 mb-3">
                                                <li>[donasiaja show="total_terkumpul"]</li>
                                                <li>[donasiaja show="jumlah_donasi"]</li>
                                                <li>[donasiaja show="jumlah_donatur"]</li>
                                                <li>[donasiaja show="jumlah_user"]</li>
                                                <li>[donasiaja show="jumlah_all_campaign"]</li>
                                                <li>[donasiaja show="jumlah_active_campaign"]</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom: 30px;">
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 0px 0 30px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Category <div class="spinner-border spinner-border-sm text-primary set_category_loading" style="margin-left: 10px;display: none;"></div></h5>
                                            <div class="form-group mb-0 row">
                                                <!-- box table -->
                                                <div class="table-responsive" style="padding: 5px 10px;">
                                                    <table class="table mb-0">
                                                        <thead class="thead-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Name</th>
                                                            <th style="text-align: center;">Campaign on Category</th>
                                                            <th style="text-align: center;">Private</th>
                                                            <th style="text-align: center;">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php 
                                                        $no = 1;
                                                        foreach($categories as $value){ 
                                                        $count_campaign = get_data_campaign($value->id);
                                                        ?>
                                                            <tr id="cat_<?php echo $value->id; ?>">
                                                                <th scope="row"><?php echo $no; ?></th>
                                                                <td><span id="cat_name_<?php echo $value->id; ?>" class="set_category" data-id="<?php echo $value->id; ?>"><?php echo $value->category; ?></span></td>
                                                                <td style="text-align: center;"><?php echo $count_campaign; ?></td>
                                                                <td style="text-align: center;">
                                                                    
                                                                    <div class="custom-control custom-switch" id="checkbox_category_private">
                                                                        <input type="checkbox" class="custom-control-input checkbox_private" id="category_private<?php echo $value->id; ?>" data-id="<?php echo $value->id; ?>" <?php if($value->private_category=='1'){echo'checked=""';}?>>
                                                                        <label class="custom-control-label" for="category_private<?php echo $value->id; ?>"></label>
                                                                    </div>

                                                                </td>
                                                                <td style="text-align: center;"><span class="badge badge-boxed badge-soft-secondary edit_category" style="cursor: pointer;" title="Edit Category" data-id="<?php echo $value->id; ?>" data-text="<?php echo $value->category; ?>">Edit</span><span class="badge badge-boxed badge-soft-danger del_category" style="cursor: pointer;margin-left: 8px;" data-id="<?php echo $value->id; ?>" title="Delete Category">Delete</span></td>
                                                            </tr>
                                                        <?php $no++; } ?>
                                                        </tbody>
                                                    </table><!--end /table-->
                                                </div> <!-- end box table -->
                                                <button type="button" class="btn btn-outline-primary waves-effect waves-light add_new_category" style="margin-left: 10px;margin-top: 20px;font-size: 11px;">+ Add New</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom: 30px;">
                                            <hr>
                                        </div>
                                    </div>


                                    <div class="row" style="margin-bottom: 20px;">

                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h4 class="mt-0 header-title">Facebook Pixel</h4>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="text1">Pixel ID</label>
                                                <input type="text" class="form-control" id="fb_pixel" required="" value="" placeholder="...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="event_1">Page Campaign</label>
                                                <select class="form-control select_event" id="event_1" name="select_event" style="height: 45px;font-size: 13px;" title="Event">
                                                    <option value="" <?php if($event_1==''){echo 'selected';}?>>Pilih Event</option>
                                                    <option value="ViewContent" <?php if($event_1=='ViewContent'){echo 'selected';}?>>ViewContent</option>
                                                    <option value="Lead" <?php if($event_1=='Lead'){echo 'selected';}?>>Lead</option>
                                                    <option value="AddToCart" <?php if($event_1=='AddToCart'){echo 'selected';}?>>AddToCart</option>
                                                    <option value="AddToWishlist" <?php if($event_1=='AddToWishlist'){echo 'selected';}?>>AddToWishlist</option>
                                                    <option value="InitiateCheckout" <?php if($event_1=='InitiateCheckout'){echo 'selected';}?>>InitiateCheckout</option>
                                                    <option value="AddPaymentInfo" <?php if($event_1=='AddPaymentInfo'){echo 'selected';}?>>AddPaymentInfo</option>
                                                    <option value="Purchase" <?php if($event_1=='Purchase'){echo 'selected';}?>>Purchase</option>
                                                    <option value="CompleteRegistration" <?php if($event_1=='CompleteRegistration'){echo 'selected';}?>>CompleteRegistration</option>
                                                    <option value="Donate" <?php if($event_1=='Donate'){echo 'selected';}?>>Donate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="event_2">Page Form</label>
                                                <select class="form-control select_event" id="event_2" name="select_event" style="height: 45px;font-size: 13px;" title="Event">
                                                    <option value="" <?php if($event_2==''){echo 'selected';}?>>Pilih Event</option>
                                                    <option value="ViewContent" <?php if($event_2=='ViewContent'){echo 'selected';}?>>ViewContent</option>
                                                    <option value="Lead" <?php if($event_2=='Lead'){echo 'selected';}?>>Lead</option>
                                                    <option value="AddToCart" <?php if($event_2=='AddToCart'){echo 'selected';}?>>AddToCart</option>
                                                    <option value="AddToWishlist" <?php if($event_2=='AddToWishlist'){echo 'selected';}?>>AddToWishlist</option>
                                                    <option value="InitiateCheckout" <?php if($event_2=='InitiateCheckout'){echo 'selected';}?>>InitiateCheckout</option>
                                                    <option value="AddPaymentInfo" <?php if($event_2=='AddPaymentInfo'){echo 'selected';}?>>AddPaymentInfo</option>
                                                    <option value="Purchase" <?php if($event_2=='Purchase'){echo 'selected';}?>>Purchase</option>
                                                    <option value="CompleteRegistration" <?php if($event_2=='CompleteRegistration'){echo 'selected';}?>>CompleteRegistration</option>
                                                    <option value="Donate" <?php if($event_2=='Donate'){echo 'selected';}?>>Donate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="event_3">Page Invoice</label>
                                                <select class="form-control select_event" id="event_3" name="select_event" style="height: 45px;font-size: 13px;" title="Event">
                                                    <option value="" <?php if($event_3==''){echo 'selected';}?>>Pilih Event</option>
                                                    <option value="ViewContent" <?php if($event_3=='ViewContent'){echo 'selected';}?>>ViewContent</option>
                                                    <option value="Lead" <?php if($event_3=='Lead'){echo 'selected';}?>>Lead</option>
                                                    <option value="AddToCart" <?php if($event_3=='AddToCart'){echo 'selected';}?>>AddToCart</option>
                                                    <option value="AddToWishlist" <?php if($event_3=='AddToWishlist'){echo 'selected';}?>>AddToWishlist</option>
                                                    <option value="InitiateCheckout" <?php if($event_3=='InitiateCheckout'){echo 'selected';}?>>InitiateCheckout</option>
                                                    <option value="AddPaymentInfo" <?php if($event_3=='AddPaymentInfo'){echo 'selected';}?>>AddPaymentInfo</option>
                                                    <option value="Purchase" <?php if($event_3=='Purchase'){echo 'selected';}?>>Purchase</option>
                                                    <option value="CompleteRegistration" <?php if($event_3=='CompleteRegistration'){echo 'selected';}?>>CompleteRegistration</option>
                                                    <option value="Donate" <?php if($event_3=='Donate'){echo 'selected';}?>>Donate</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top:30px;margin-bottom: 10px;">
                                            <h4 class="mt-0 header-title">Tiktok Pixel</h4>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="text1">Pixel ID</label>
                                                <input type="text" class="form-control" id="tiktok_pixel" required="" value="<?php echo $tiktok_pixel; ?>" placeholder="...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top:30px;margin-bottom: 10px;">
                                            <h4 class="mt-0 header-title">Google Tag Manager</h4>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="text1">GTM ID</label>
                                                <input type="text" class="form-control" id="gtm_id" required="" value="<?php echo $gtm_id; ?>" placeholder="GTM-XXXX">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            </div>
                                        </div>


                                        
                                        
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom: 30px;">
                                            <hr>
                                        </div>
                                    </div>



                                    <div class="row" style="padding: 0px 0 10px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Logo Powered By DonasiAja</h5>
                                            <?php if($plugin_license!='ULTIMATE') { ?>

                                                    <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                        <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                    </div>

                                            <?php } ?>

                                            <div class="form-group" <?php if($plugin_license!='ULTIMATE') { echo 'style="display:none;"'; } ?>>
                                                <div class="custom-control custom-switch" id="checkbox_powered_by_setting">
                                                    <input type="checkbox" class="custom-control-input checkbox31" id="powered_by_setting" data-id="1" <?php echo $checked5; ?> >
                                                    <label class="custom-control-label" for="powered_by_setting"><?php echo $status_text5; ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 0px 0 30px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Login</h5>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_login_setting">
                                                    <input type="checkbox" class="custom-control-input checkbox1" id="login_setting" data-id="1" <?php echo $checked1; ?> >
                                                    <label class="custom-control-label" for="login_setting"><?php echo $status_text1; ?></label>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin menggunakan login bawaan DonasiAja.</p> 
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 0px 0 20px 0;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Login URL</h5>
                                                
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="page_login" required="" placeholder="Pagename" value="<?php echo $page_login; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                            <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/<span class="set_page_login"><?php echo $page_login; ?></span>/</p> 
                                        </div>
                                    </div>
                                    <div class="row" style="padding: 20px 0 20px 0;margin-top: -10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Login Description</h5>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="login_text" required="" placeholder="Description" value="<?php echo $login_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="padding: 0px 0 20px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Register</h5>
                                            <?php if($plugin_license!='ULTIMATE'){ ?>

                                                    <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                                                        <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                                                    </div>

                                            <?php } ?>
                                            <div class="form-group" <?php if($plugin_license!='ULTIMATE') {echo'style="display:none;"';}?>>
                                                <div class="custom-control custom-switch" id="checkbox_register_setting" style="margin-bottom: 20px;">
                                                    <input type="checkbox" class="custom-control-input checkbox1" id="register_setting" data-id="1" <?php echo $checked2; ?> >
                                                    <label class="custom-control-label" for="register_setting"><?php echo $status_text2; ?></label>
                                                </div>
                                            </div>
                                            <p <?php if($plugin_license!='ULTIMATE') {echo'style="display:none;"';}?> class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin mengaktifkan menu registrasi user (donatur). Pastikan settingan di Wordpress anda sudah mengaktifkan <a href="<?php echo admin_url('options-general.php') ?>"><b>Membership > Anyone can register</b></a></p> 
                                        </div>
                                    </div>
                                    <div class="row" style="padding: 0px 0 20px 0;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Register URL</h5>
                                                
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="page_register" required="" placeholder="Pagename" value="<?php echo $page_register; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                            <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/<span class="set_page_register"><?php echo $page_register; ?></span>/</p> 
                                        </div>
                                    </div>
                                    <div class="row" style="padding: 20px 0 20px 0;margin-top: -10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Register Description</h5>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="register_text" required="" placeholder="Description" value="<?php echo $register_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="padding:0px 0 20px 0;margin-top: -10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Register Checkbox</h5>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_register_checkbox_setting" style="margin-bottom: 20px;">
                                                    <input type="checkbox" class="custom-control-input checkbox33" id="register_checkbox_setting" data-id="1" <?php echo $checked8; ?> >
                                                    <label class="custom-control-label" for="register_checkbox_setting"><?php echo $status_text8; ?></label>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin mengaktifkan checkbox register sebagai tanda user harus mematuhi peraturan yang ada di situs atau website ini.</p> 
                                        </div>
                                    </div>

                                    <div class="row" style="padding:0px 0 20px 0;margin-top: 10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Register Checkbox Info</h5>
                                            <div class="form-group">
                                                <textarea class="form-control" rows="3" id="register_checkbox_info" style="font-size: 13px;"><?php echo $register_checkbox_info; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 0px 0 20px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Page Reset Password</h5>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_changepass_setting" style="margin-bottom: 20px;">
                                                    <input type="checkbox" class="custom-control-input checkbox1" id="changepass_setting" data-id="1" <?php echo $checked7; ?> >
                                                    <label class="custom-control-label" for="changepass_setting"><?php echo $status_text7; ?></label>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin mengaktifkan menu rubah password pada halaman login.</p> 
                                        </div>
                                    </div>


                                    <div class="row" style="padding: 0px 0 20px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Donatur Create Campaign</h5>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_campaign_setting">
                                                    <input type="checkbox" class="custom-control-input checkbox1" id="campaign_setting" data-id="1" <?php echo $checked3; ?> >
                                                    <label class="custom-control-label" for="campaign_setting"><?php echo $status_text3; ?></label>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted" style="margin-top: -10px;">User Donatur diberikan akses membuat campaign.</p>
                                        </div>
                                    </div>
                                    <div class="row" style="padding: 10px 0 20px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Delete Campaign</h5> <!-- campaign_setting -->
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_del_campaign_setting">
                                                    <input type="checkbox" class="custom-control-input checkbox1" id="del_campaign_setting" data-id="1" <?php echo $checked4; ?> >
                                                    <label class="custom-control-label" for="del_campaign_setting"><?php echo $status_text4; ?></label>
                                                </div>
                                            </div>
                                            <p class="card-text">Aktif : <span class="text-muted">Campaign yang sudah ada donasinya, tetap bisa dihapus. (tidak disarankan).</span><br>Tidak Aktif : <span class="text-muted">Campaign yang sudah ada donasinya, tidak bisa dihapus. Kecuali data donasi dihapus terlebih dahulu, baru campaign bisa dihapus.</span></p>
                                            <p class="card-text">Kenapa ada ini? untuk menghindari kesalahan delete campaign dan data donasi hilang sia-sia karena kelalaian delete campaign.</p>

                                        </div>
                                    </div>

                                    <div class="row" style="padding: 10px 0 20px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">JQuery</h5>

                                            <div class="form-group mb-0 row" style="margin-bottom: 20px !important;">
                                                <div class="col-md-9">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="customRadioJ2" name="jquery_on" class="custom-control-input" <?php if($jquery_on=='0') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="customRadioJ2">Not Active</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="customRadioJ1" name="jquery_on" class="custom-control-input" <?php if($jquery_on=='1') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="customRadioJ1">Active</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="2" id="customRadioJ3" name="jquery_on" class="custom-control-input" <?php if($jquery_on=='2') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="customRadioJ3">Custom</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6" style="margin-bottom:0;margin-top: 20px;<?php if($jquery_on!='2') { echo 'display:none;';}?>" id="box_jquery_custom">
                                                    <div class="form-group" style="margin-left: -10px;margin-bottom: 30px;">
                                                        <select class="form-control" id="jquery_custom" name="jquery_custom" style="height: 45px;" title="Payment Method">
                                                            <option value="0">Pilih JQuery</option>
                                                            <option value="3.6.0" <?php if($jquery_custom=='3.6.1'){echo'selected';}?>>JQuery 3.6</option>
                                                            <option value="3.5.1" <?php if($jquery_custom=='3.5.1'){echo'selected';}?>>JQuery 3.5</option>
                                                            <option value="3.4.1" <?php if($jquery_custom=='3.4.1'){echo'selected';}?>>JQuery 3.4</option>
                                                            <option value="3.3.1" <?php if($jquery_custom=='3.3.1'){echo'selected';}?>>JQuery 3.3</option>
                                                            <option value="2.2.4" <?php if($jquery_custom=='2.2.4'){echo'selected';}?>>JQuery 2.2</option>
                                                            <option value="1.12.4" <?php if($jquery_custom=='1.12.4'){echo'selected';}?>>JQuery 1.12</option>
                                                            <option value="1.9.1" <?php if($jquery_custom=='1.9.1'){echo'selected';}?>>JQuery 1.9</option>
                                                            <option value="1.8.3" <?php if($jquery_custom=='1.8.3'){echo'selected';}?>>JQuery 1.8</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            <p class="card-text text-muted" style="margin-top: -10px;">Set Not Active jika terjadi double Jquery pada halaman website anda atau Custom lalu sesuaikan dengan Jquery yang anda gunakan pada website anda. Jika tidak, wajib aktifkan.</p>

                                        </div>
                                    </div>

                                    <div class="row" style="padding: 10px 0 20px 0;">
                                        <div class="col-md-9">
                                            <h5 class="card-title mt-0">Pilih tipe menu pada halaman Campaign</h5>
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-9">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="tab" id="customRadio8" name="label_tab" class="custom-control-input" <?php if($label_tab=='tab') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="customRadio8">Tab Menu</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="list" id="customRadio9" name="label_tab" class="custom-control-input" <?php if($label_tab=='list') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="customRadio9">List Menu</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 20px 0 10px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Batas maximal user bisa melakukan love pada doa atau komentar</h5>
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-12">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="customRadio10" name="max_love" class="custom-control-input" <?php if($max_love=='0') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio10">Unlimitted</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="customRadio11" name="max_love" class="custom-control-input" <?php if($max_love!='0') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio11">Custom</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-bottom:0;margin-top: 20px;<?php if($max_love=='0') { echo 'display:none;';}?>" id="max_love_input">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="max_love_custom" required="" placeholder="Contoh: 50" value="<?php if($max_love!='0') {echo $max_love; } ?>" style="font-size: 13px;padding-left: 12px;">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <hr>
                                            <br>
                                            <button type="button" class="btn btn-primary px-5 py-2" id="update_general">Update <div class="spinner-border spinner-border-sm text-white update_general_loading" style="margin-left: 3px;display: none;"></div></button>
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
