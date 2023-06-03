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
                        <h5 class="boxcard-title">Form</h5>  
                        <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan form anda.</p>  
                        <hr>           
                    </div><!--end col-->
                </div><!--end row-->
                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_themes" style="">

                                    <?php

                                    if($form_setting=='1'){
                                        $status_text1 = '<span>Active</span>';
                                        $checked1 = 'checked=""';
                                    }else{
                                        $status_text1 = '<span>Not Active</span>';
                                        $checked1 = '';
                                    }

                                    $form_text   = json_decode($form_text, true);
                                    $text1 = $form_text['text'][0];
                                    $text2 = $form_text['text'][1];
                                    $text3 = $form_text['text'][2];
                                    $text4 = $form_text['text'][3];

                                    if($form_email_setting=='1'){
                                        $status_text2 = '<span>Show</span>';
                                        $checked2 = 'checked=""';
                                    }else{
                                        $status_text2 = '<span>Hide</span>';
                                        $checked2 = '';
                                    }

                                    if($form_comment_setting=='1'){
                                        $status_text3 = '<span>Show</span>';
                                        $checked3 = 'checked=""';
                                    }else{
                                        $status_text3 = '<span>Hide</span>';
                                        $checked3 = '';
                                    }

                                    if($limitted_donation_button=='1'){
                                        $status_text4 = '<span>Active</span>';
                                        $checked4 = 'checked=""';
                                    }else{
                                        $status_text4 = '<span>Not Active</span>';
                                        $checked4 = '';
                                    }

                                    ?>

                                    <div class="row" style="margin-top: 0px;">
                                        <div class="col-md-9">
                                            <h5 class="card-title mt-0">Donatur dapat memilih Tipe Form</h5>
                                            <p class="card-text text-muted">Aktifkan jika donatur boleh memilih tipe form pada saat pembuatan campaign (Form Card, Typing dan Packaged).</p>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_form_setting" style="margin-bottom: 20px;">
                                                    <input type="checkbox" class="custom-control-input checkbox1" id="form_setting" data-id="1" <?php echo $checked1; ?> >
                                                    <label class="custom-control-label" for="form_setting"><?php echo $status_text1; ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 20px 0 0px 0;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Minimum Donasi</h5>
                                            <p class="card-text text-muted">Tulis minimum donasi yang diperbolehkan ketika donatur mengetik donasi pada form.</p> 
                                        </div>
                                        <div class="col-md-3" style="margin-top: 15px;">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="minimum_donate" required="" placeholder="Cth: 10000" value="<?php echo $minimum_donate; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 30px 0 20px 0;">
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Pilihan Nominal Donasi</h5>
                                            <p class="card-text text-muted">Tulis dari nominal terendah ke tinggi.</p> 
                                        </div>
                                    <?php $i=1; foreach ($opt_nominal as $key => $value) { ?>
                                        <div class="row box_nominal_donasi" style="padding-left: 10px;">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control nominalnya" id="opt_number<?php echo $i; ?>" required="" placeholder="Nominal <?php echo $i; ?>" style="font-size: 13px;padding-left: 12px;" value="<?php echo $value[0]; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control labelnya" id="opt_label<?php echo $i; ?>" required="" placeholder="Label <?php echo $i; ?>" style="font-size: 13px;padding-left: 12px;" value="<?php echo $value[1]; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio seringnya" style="padding-top: 12px;padding-left: 30px;">
                                                    <input type="radio" value="<?php echo $i; ?>" id="sering_dipilih<?php echo $i; ?>" name="sering_dipilih" class="custom-control-input" <?php if($value[2]=='1'){echo 'checked=""';}?>>
                                                    <label class="custom-control-label" for="sering_dipilih<?php echo $i; ?>">Sering di Pilih</label>
                                                </div>
                                            </div>
                                        </div>

                                    <?php $i++; } ?>

                                    </div>


                                    <div class="row" style="padding: 0 0 20px 0;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Maksimal jumlah paket</h5>
                                            <p class="card-text text-muted">Tulis maksimal jumlah paket yang ada pada form paket</p> 
                                        </div>
                                        <div class="col-md-6" style="margin-top: 15px;">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="max_package" required="" placeholder="Jumlah Paket" value="<?php echo $max_package; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row" style="padding: 0px 0 10px 0;">
                                        
                                        <div class="col-md-6" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Anonim</h5>
                                            <p class="card-text text-muted">Contoh: Orang Baik, Hamba Allah</p>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="anonim_text" required="" placeholder="Anonim" value="<?php echo $anonim_text; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Email</h5>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_form_email_setting">
                                                    <input type="checkbox" class="custom-control-input checkbox31" id="form_email_setting" data-id="1" <?php echo $checked2; ?> >
                                                    <label class="custom-control-label" for="form_email_setting"><?php echo $status_text2; ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Comment</h5>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_form_comment_setting">
                                                    <input type="checkbox" class="custom-control-input checkbox32" id="form_comment_setting" data-id="1" <?php echo $checked3; ?> >
                                                    <label class="custom-control-label" for="form_comment_setting"><?php echo $status_text3; ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="row set_line" style="padding: 20 0 30px 0;margin-top: 10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Form Pagename</h5>
                                            <p class="card-text text-muted">Tulis nama halaman form anda, contoh: donasi, donasi-sekarang, donate-now.<br>Note: tanpa spasi dan huruf kecil semua.</p>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 15px;">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="page_donate" required="" placeholder="Pagename" value="<?php echo $page_donate; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 0px;">
                                            <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/campaign/title-campaign/<span class="set_page_donate"><?php echo $page_donate; ?></span></p> 
                                        </div>

                                    </div>


                                    <div class="row" style="margin-top: 40px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">Thankyou Page</h5>
                                            <p class="card-text text-muted">Tulis nama halaman thankyou page anda, contoh: terimakasih, thankyou-page, typ, atau invoice. Note: tanpa spasi dan huruf kecil semua.</p> 
                                        </div>
                                        <div class="col-md-6" style="margin-top: 15px;">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="page_typ" required="" placeholder="Pagename" value="<?php echo $page_typ; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 0px;">
                                            <p class="card-text text-muted" style="margin-top: -10px;">Link : <?php echo $home_url; ?>/campaign/title-campaign/<span class="set_page_typ"><?php echo $page_typ; ?></span>/invoiceid</p> 
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 50px 0 10px 0;">
                                        <div class="col-md-10">
                                            <h5 class="card-title mt-0">Form Konfirmasi</h5>
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-9">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="formKonfirmasi1" name="form_confirmation_setting" class="custom-control-input" <?php if($form_confirmation_setting=='1') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="formKonfirmasi1">Aktifkan untuk semua menu pembayaran</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="2" id="formKonfirmasi2" name="form_confirmation_setting" class="custom-control-input" <?php if($form_confirmation_setting=='2') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="formKonfirmasi2">Aktifkan hanya pada menu Transfer</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="formKonfirmasi3" name="form_confirmation_setting" class="custom-control-input" <?php if($form_confirmation_setting=='0' || $form_confirmation_setting=='') { echo 'checked=""';}?>>
                                                            <label class="custom-control-label" for="formKonfirmasi3">Non Aktifkan</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="form_text" style="margin-top: 40px;">

                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h4 class="mt-0 header-title">Form Text</h4>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="text1">Button 1</label>
                                                <input type="text" class="form-control" id="text1" required="" value="<?php echo $text1; ?>">
                                                <div class="form-text text-muted">Button on Campaign</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="text2">Button 2</label>
                                                <input type="text" class="form-control" id="text2" required="" value="<?php echo $text2; ?>">
                                                <div class="form-text text-muted">Button on Form Donate</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="text3">Small Title Campaign</label>
                                                <input type="text" class="form-control" id="text3" required="" value="<?php echo $text3; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="text4">Small Title Donate</label>
                                                <input type="text" class="form-control" id="text4" required="" value="<?php echo $text4; ?>">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" style="padding: 30px 0 10px 0;">
                                        
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                            <h5 class="card-title mt-0">Limited Donation - Button</h5>
                                            <p class="card-text text-muted">Aktifkan jika ingin tombol donasi menjadi Disabled ketika donasi sudah terpenuhi.</p> 
                                            <div class="form-group">
                                                <div class="custom-control custom-switch" id="checkbox_limitted_donation_button">
                                                    <input type="checkbox" class="custom-control-input checkbox42" id="limitted_donation_button" data-id="1" <?php echo $checked4; ?> >
                                                    <label class="custom-control-label" for="limitted_donation_button"><?php echo $status_text4; ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <hr>
                                            <br>
                                            <button type="button" class="btn btn-primary px-5 py-2" id="update_form">Update <div class="spinner-border spinner-border-sm text-white update_form_loading" style="margin-left: 3px;display: none;"></div></button>
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
