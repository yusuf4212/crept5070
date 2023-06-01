<?php 

if($fundraiser_on=='1'){
    $status_text1 = '<span>Active</span>';
    $checked1 = 'checked=""';
}else{
    $status_text1 = '<span>Not Active</span>';
    $checked1 = '';
}

if($fundraiser_commission_on=='1'){
    $status_text2 = '<span>Active</span>';
    $checked2 = 'checked=""';
}else{
    $status_text2 = '<span>Not Active</span>';
    $checked2 = '';
}

if($fundraiser_wa_on=='1'){
    $status_text3 = '<span>Active</span>';
    $checked3 = 'checked=""';
}else{
    $status_text3 = '<span>Not Active</span>';
    $checked3 = '';
}

if($fundraiser_email_on=='1'){
    $status_text4 = '<span>Active</span>';
    $checked4 = 'checked=""';
}else{
    $status_text4 = '<span>Not Active</span>';
    $checked4 = '';
}

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
                    <h5 class="boxcard-title">Fundraising</h5>  
                    <p class="card-text text-muted">Silahkan diatur fitur Fundraising sesuai kebutuhan anda.</p>  
                    <hr> 
                </div><!--end col-->
            </div><!--end row-->

            <?php if($license=='ULTIMATE') { ?>

            <div class="row">
                <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: 0px;">
                    <div class="card card-border" style="border: 0;padding: 0;">
                        <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                            <div id="data_fundraising" style="">

                                <div class="row" style="padding: 0px 0 20px 0;margin-top: -20px;">
                                    <div class="col-md-12">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Fundraising Mode<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan fundraising mode ini agar anda & member (donatur) anda bisa menggunakan/ mengakses fitur Fundraising.</p> 
                                        <div class="form-group" style="margin-top: -5px;">
                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_on">
                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_on" data-id="1" <?php echo $checked1; ?> >
                                                <label class="custom-control-label" for="fundraiser_on"><?php echo $status_text1; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Text Description<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan deksripsi singkat untuk keterangan Fundraiser yang akan Join.</p>
                                        <div class="form-group" style="margin-top: -5px;">
                                            
                                            <textarea class="form-control" rows="3" id="fundraiser_text" style="font-size: 13px;"><?php echo $fundraiser_text; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Button<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan text pada Button Fundraiser.</p> 
                                        <div class="form-group" style="margin-top: -5px;">
                                            <input type="text" class="form-control" id="fundraiser_button" required="" placeholder="Description" value="<?php echo $fundraiser_button; ?>" style="font-size: 13px;padding-left: 12px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="padding: 10px 0 20px 0;">
                                    <div class="col-md-12">
                                        <hr>
                                        <br>
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Commission / Komisi<span></span></h5>
                                    </div>

                                        <div class="col-lg-6">
                                            <div class="card" style="padding-top: 20px;">
                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . "images/commission_active.png"; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 1px;" data-action="zoom">
                                                <p style="text-align: center;padding-top: 10px;margin: 0;color: #77818e;font-size:11px;">Preview - Commission Active</p>
                                            </div><!--end card-->
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card" style="padding-top: 20px;">
                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . "images/commission_notactive.png"; ?>" alt="" class="" height="80" style="border: 1px solid #dde4ec;border-radius: 1px;" data-action="zoom">
                                                <p style="text-align: center;padding-top: 10px;margin: 0;color: #77818e;font-size:11px;">Preview - Commission Not Active</p>
                                            </div><!--end card-->
                                        </div>
                                    
                                    <div class="col-md-12">
                                        <p class="card-text text-muted" style="margin-top:0px;">Fitur ini harus aktif agar anda bisa menggunakan fitur komisi fundraising. Jika tidak aktif, maka tampilan di menu Fundraising akan menampilkan list fundraising saja tanpa ada menu komisi dan semua komisi akan diberi 0 jika ada fundraising yang masuk dari link Fundraiser.</p> 
                                        <div class="form-group" style="margin-top: -5px;">
                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_commission_on">
                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_commission_on" data-id="1" <?php echo $checked2; ?> >
                                                <label class="custom-control-label" for="fundraiser_commission_on"><?php echo $status_text2; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="padding: 0px 0 0px 0;">
                                    <div class="col-md-12">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Commission Type<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Pilih tipe komisi yang ingin anda berikan pada Fundraiser.</p> 
                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                        
                                        
                                            <div class="form-group mb-0 row">
                                                <div class="col-md-12" style="padding-bottom: 5px;padding-left: 0;">
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="0" id="customRadio15" name="fundraiser_commission_type" class="custom-control-input" <?php if($fundraiser_commission_type=='0') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio15">Percent</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check-inline my-1">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" id="customRadio16" name="fundraiser_commission_type" class="custom-control-input" <?php if($fundraiser_commission_type=='1') { echo 'checked=""';}?> >
                                                            <label class="custom-control-label" for="customRadio16">Fixed</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5 fundraiser_commission_percent" style="padding-left:0;margin-bottom:0;margin-top:15px;<?php if($fundraiser_commission_type=='0') {}else{echo 'display:none;';}?>" id="">
                                                    <div class="form-group">
                                                        <label class="">Percent : </label>
                                                        <input type="text" class="form-control" id="fundraiser_commission_percent" required="" placeholder="Contoh: 10" value="<?php echo $fundraiser_commission_percent; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="5">
                                                        <p class="card-text text-muted" style="margin-top:8px;">Range 0-100
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-5 fundraiser_commission_fixed" style="padding-left:0;margin-bottom:0;margin-top:15px;<?php if($fundraiser_commission_type=='1') {}else{echo 'display:none;';}?>" id="">
                                                    <div class="form-group">
                                                        <label class="">Fixed :</label>
                                                        <input type="text" class="form-control" id="fundraiser_commission_fixed" required="" placeholder="Contoh: 2000" value="<?php echo $fundraiser_commission_fixed; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="10">
                                                        <p class="card-text text-muted" style="margin-top:8px;">Tuliskan 2000 jika anda ingin memberikan setiap komisi 2000.
                                                        </p>
                                                    </div>
                                                </div>

                                                


                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>


                                <div class="row" style="padding: 15px 0 0px 0;">
                                    <div class="col-md-12">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Minimal saldo pencairan<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan jika anda ingin memberikan adanya minimal saldo pencairan, jika saldo belum mencukupi maka Fundraiser tidak dapat mencairkan.</p> 
                                        <div class="col-md-12" style="margin-bottom: 10px;padding-left:0;">

                                            <?php
                                            if($min_payout_setting=='1'){
                                                $status_text6 = '<span>Active</span>';
                                                $checked6 = 'checked=""';
                                            }else{
                                                $status_text6 = '<span>Not Active</span>';
                                                $checked6 = '';
                                            }
                                            ?>

                                            <div class="form-group" style="margin-top: -5px;">
                                                <div class="custom-control custom-switch" id="checkbox_min_payout_setting_on">
                                                    <input type="checkbox" class="custom-control-input checkbox1" id="min_payout_setting_on" data-id="1" <?php echo $checked6; ?> >
                                                    <label class="custom-control-label" for="min_payout_setting_on"><?php echo $status_text6; ?></label>
                                                </div>
                                            </div>

                                            
                                        
                                        </div>
                                        <div class="col-md-5" style="padding-left:0;<?php if($min_payout_setting=='1'){}else{echo'display:none;';}?>" id="min_saldo">
                                            <div class="form-group">
                                                <label class="">Min Saldo : </label>
                                                <input type="text" class="form-control" id="min_payout" required="" placeholder="Contoh: 50000" value="<?php echo $min_payout; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row" style="padding: 0px 0 20px 0;">
                                    <div class="col-md-12">
                                        <hr>
                                        <br>
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Send Notif WA<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan jika anda ingin mengirimkan pesan whatsapp kepada Fundraiser pada saat mengupdate Status Pencairan Komisi. Pastikan settingan <a href="<?php echo admin_url('admin.php?page=donasiaja_settings&action=notification') ?>" style="color:#505DFF;">Wanotif</a> anda sudah aktif juga.</p>
                                        <div class="form-group" style="margin-top: -5px;">
                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_wa_on">
                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_wa_on" data-id="1" <?php echo $checked3; ?> >
                                                <label class="custom-control-label" for="fundraiser_wa_on"><?php echo $status_text3; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">WA Message<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan pesan whatsapp yang ingin anda kirimkan. Gunakan shortcode yang anda untuk memudahkan pesan tersampaikan kepada fundraiser.</p>
                                        <div class="form-group" style="margin-top: 15px;">
                                            
                                            <textarea class="form-control" rows="6" id="fundraiser_wa_text" style="font-size: 13px;"><?php echo $fundraiser_wa_text; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="padding: 40px 0 20px 0;">
                                    <div class="col-md-12">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Send Notif Email<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Aktifkan jika anda ingin mengirimkan pesan email kepada Fundraiser pada saat mengupdate Status Pencairan Komisi. Pastikan settingan <a href="<?php echo admin_url('admin.php?page=donasiaja_settings&action=notification') ?>" style="color:#505DFF;">Email</a> anda sudah aktif juga.</p>
                                        <div class="form-group" style="margin-top: -5px;">
                                            <div class="custom-control custom-switch" id="checkbox_fundraiser_email_on">
                                                <input type="checkbox" class="custom-control-input checkbox1" id="fundraiser_email_on" data-id="1" <?php echo $checked4; ?> >
                                                <label class="custom-control-label" for="fundraiser_email_on"><?php echo $status_text4; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="padding: 0px 0 20px 10px;">
                                    <div class="col-md-12" style="padding-left: 0;">
                                        <h5 class="card-title mt-0" style="padding-top: 0px;">Email Message<span></span></h5>
                                        <p class="card-text text-muted" style="margin-top:0px;">Tuliskan pesan email yang ingin anda kirimkan. Gunakan shortcode yang anda untuk memudahkan pesan tersampaikan kepada fundraiser.</p>
                                        <!-- <div class="form-group" style="margin-top: 15px;">
                                               <textarea id="fundraiser_email_text" name="area">
                                                   <?php echo $fundraiser_email_text; ?>
                                               </textarea>
                                        </div> -->
                                    </div>


                                    <div id="box_email_5" class="col-md-12 box_email_message show_box2" style="margin-top: 20px;">
                                        <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email Message</h5>
                                        <hr>

                                        <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                            <button data-id="5" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                            <button data-id="5" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                        </div>

                                        <div class="form-group" style="margin-top: 35px;">
                                            <label title="Wajib di isi alamat email">Send to* :</label>
                                            <input type="text" class="form-control send_to_5"  required="" placeholder="{email}" value="<?php echo $f_send_to; ?>" style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                        </div>
                                        <div class="form-group">
                                            <label title="Wajib di isi subject email">Subject* :</label>
                                            <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="<?php echo $f_subject_email; ?>">
                                        </div>
                                        <div class="form-group email_cc" style="margin: 0;<?php if($f_emailnyacc==''){echo'display:none;';}?>" >
                                            <label style="margin-top: 0px;">CC :</label> 
                                            <textarea id="myTagsEmailCC_f" class="tagit tagitemailcc"></textarea>
                                        </div>
                                        <div class="form-group email_bcc" style="margin: 0;<?php if($f_emailnyabcc==''){echo'display:none;';}?>">
                                            <label style="margin-top: 0px;">BCC :</label> 
                                            <textarea id="myTagsEmailBCC_f" class="tagit tagitemailbcc"></textarea>
                                        </div>
                                        
                                        <label title="Wajib di isi message email">Message* :</label>
                                        <div class="form-group">
                                           <textarea id="f_message_email" name="area">
                                               <?php echo $f_message_email; ?>
                                           </textarea> 

                                        </div>
                                    </div>


                                </div>


                                <h5 class="card-text">Note :</h5>
                                <p class="card-text text-muted">Gunakan shortcode berikut untuk memanggil value dan bisa dipanggil pada wa message dan juga email message.</p> 
                                <ul>
                                    <li>{name} : Nama fundraiser</li>
                                    <li>{email} : Email fundraiser</li>
                                    <li>{payout_number} : ID Payout</li>
                                    <li>{nominal} : Jumlah nominal komisi yang dicairkan</li>
                                </ul>

                               
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-12">
                                        <hr>
                                        <br>
                                        <button type="button" class="btn btn-primary px-5 py-2" id="update_fundraising">Update <div class="spinner-border spinner-border-sm text-white update_fundraising_loading" style="margin-left: 3px;display: none;"></div></button>
                                    </div>
                                </div>
                                 
                            </div>

                        </div><!--end card -body-->
                    </div><!--end card-->                                                               
                </div>
            </div>   

            <?php }else{ ?>

            <div class="row" style="padding: 0px 0 15px 0;">
                <div class="col-md-12" style="margin-bottom: 10px;">
                    <div class="alert alert-secondary border-0" role="alert" style="background: #ffe5a6;color: #b36f21;">
                        <strong>Maaf!</strong> Fitur ini tidak tersedia pada license anda, silahkan upgrade untuk menikmati kemudahan fitur ini.
                    </div>
                </div>
            </div>

            <?php } ?>

        </div><!--end card-body-->                                
    </div><!--end card-->
</div><!--end col-->
</div>
