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

                

                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                        <h5 class="boxcard-title">Payment</h5>  
                        <p class="card-text text-muted">Silahkan diatur sesuai kebutuhan pembayaran anda.</p>  
                        
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#general" role="tab" aria-selected="true">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#ipaymu" role="tab" aria-selected="false">iPaymu</a>
                            </li>  
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tripay" role="tab" aria-selected="false">Tripay</a>
                            </li>  
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#midtrans" role="tab" aria-selected="false">Midtrans</a>
                            </li>                                      
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#moota" role="tab" aria-selected="false">Moota</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane p-3 active" id="general" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_payment" style="">
                                                    <div class="row" style="padding: 0px 0 15px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">Unique Number / Kode Unik</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Gunakan sesuai kebutuhan, pilih <i>None</i> jika tidak ingin ada tambahan kode unik pada total.</p> 
                                                            <div class="form-group mb-0 row">
                                                                <div class="col-md-12" style="padding-bottom: 5px;">
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="0" id="customRadio14" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='0') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadio14">None</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="1" id="customRadio13" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='1') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadio13">Fixed</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="2" id="customRadio12" name="unique_number_setting" class="custom-control-input" <?php if($unique_number_setting=='2') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadio12">Range</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 unique_number_fixed" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='1') {}else{echo 'display:none;';}?>" id="">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="unique_number_fixed" required="" placeholder="Contoh: 57" value="<?php echo $unique_number_value['unique_number'][0]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-2 unique_number_range" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="unique_number_range1" required="" placeholder="Min" value="<?php echo $unique_number_value['unique_number'][1]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                                    </div>
                                                                </div>

                                                                <div class="unique_number_range titik_dua" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                                    <div class="form-group" style="text-align: center;padding-top: 12px;">
                                                                        <p>:</p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-2 unique_number_range" style="margin-bottom:0;margin-top:15px;<?php if($unique_number_setting=='2') {}else{echo 'display:none;';}?>" id="">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="unique_number_range2" required="" placeholder="Max" value="<?php echo $unique_number_value['unique_number'][2]; ?>" style="font-size: 13px;padding-left: 12px;text-align: center;" maxlength="3">
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            
                                                        </div>
                                                    </div>

                                                    <?php

                                                    // payment_setting = {"method1": ["instant", "Instant Payment", "1"]}

                                                    $instant_setting        = $payment_setting['method1'][2];
                                                    $instant_setting_title  = $payment_setting['method1'][1];
                                                    $va_setting             = $payment_setting['method2'][2];
                                                    $va_setting_title       = $payment_setting['method2'][1];
                                                    $transfer_setting       = $payment_setting['method3'][2];
                                                    $transfer_setting_title = $payment_setting['method3'][1];

                                                    if($instant_setting=='1'){
                                                        $status_text1 = '<span>Active</span>';
                                                        $checked1 = 'checked=""';
                                                    }else{
                                                        $status_text1 = '<span>Not Active</span>';
                                                        $checked1 = '';
                                                    }

                                                    if($va_setting=='1'){
                                                        $status_text2 = '<span>Active</span>';
                                                        $checked2 = 'checked=""';
                                                    }else{
                                                        $status_text2 = '<span>Not Active</span>';
                                                        $checked2 = '';
                                                    }

                                                    if($transfer_setting=='1'){
                                                        $status_text3 = '<span>Active</span>';
                                                        $checked3 = 'checked=""';
                                                    }else{
                                                        $status_text3 = '<span>Not Active</span>';
                                                        $checked3 = '';
                                                    }
                                                    

                                                    ?>

                                                    <div class="row" style="padding: 10px 0 10px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">Payment Method</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Aktifkan sesuai kebutuhan agar tampil pada list metode pembayaran.</p>
                                                        </div>
                                                    </div>



                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                        <div class="col-md-4">
                                                            <label for="subject"><b>Instant</b></label>
                                                            <div class="form-group" style="margin-top: 10px;">
                                                                <div class="custom-control custom-switch" id="checkbox_instant_setting">
                                                                    <input type="checkbox" class="custom-control-input checkbox1" id="instant_setting" data-id="1" <?php echo $checked1; ?> >
                                                                    <label class="custom-control-label" for="instant_setting"><?php echo $status_text1; ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label for="subject">Title</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="instant_title" required="" placeholder="Title" value="<?php echo $instant_setting_title; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                        <div class="col-md-4">
                                                            <label for="subject"><b>Virtual Account</b></label>
                                                            <div class="form-group" style="margin-top: 10px;">
                                                                <div class="custom-control custom-switch" id="checkbox_va_setting">
                                                                    <input type="checkbox" class="custom-control-input checkbox1" id="va_setting" data-id="1" <?php echo $checked2; ?> >
                                                                    <label class="custom-control-label" for="va_setting"><?php echo $status_text2; ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label for="subject">Title</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="va_title" required="" placeholder="Title" value="<?php echo $va_setting_title; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                        <div class="col-md-4">
                                                            <label for="subject"><b>Transfer</b></label>
                                                            <div class="form-group" style="margin-top: 10px;">
                                                                <div class="custom-control custom-switch" id="checkbox_transfer_setting">
                                                                    <input type="checkbox" class="custom-control-input checkbox1" id="transfer_setting" data-id="1" <?php echo $checked3; ?> >
                                                                    <label class="custom-control-label" for="transfer_setting"><?php echo $status_text3; ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label for="subject">Title</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="transfer_title" required="" placeholder="Title" value="<?php echo $transfer_setting_title; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="padding: 40px 0 15px 0;">
                                                        <div class="col-md-12">
                                                            <h5 class="card-title mt-0">Bank Account</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding: 0px 0 20px 0;margin-top: -10px;" id="data_bank">
                                                        <?php 

                                                        if($bank_account==null || $bank_account==''){

                                                        }else{

                                                        foreach ($bank_account as $key => $value) {

                                                            $data_rekening = explode('_',$value);
                                                            $no_rekening = $data_rekening[0];
                                                            $an_rekening = $data_rekening[1];
                                                            $payment_method = $data_rekening[2];
                                                            $randid = d_randomString(3);
                                                        ?>
                                                        <div class="col-md-3 bank_opt_<?php echo $randid; ?> bank-col-1">
                                                        <select class="form-control select_bank" id="" data-randid="<?php echo $randid; ?>" name="select_bank" style="height: 45px;" title="Bank">
                                                            <option value="0">Pilih Bank</option>
                                                            <?php foreach ($get_bank as $key2 => $value2) {
                                                                if($value2->code=='mandiri_syariah' || $value2->code=='bni_syariah' || $value2->code=='bri_syariah'){}else{

                                                                if (strpos($key, '@') !== false ) {
                                                                    $code_bank = explode('@',$key);
                                                                    $key = $code_bank[0];
                                                                }
                                                                ?>
                                                                <option value="<?php echo $value2->code; ?>" <?php if($value2->code==$key){echo'selected';}?>><?php echo $value2->name; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                        </div>
                                                        <div class="col-md-2 bank_opt_<?php echo $randid; ?> bank-col-2">
                                                            <div class="form-group">
                                                                <input type="text" value="<?php echo $no_rekening; ?>" class="form-control label_norek" id="opt_label_norek_<?php echo $randid; ?>" required="" placeholder="No Rekening" style="font-size: 13px;padding-left: 12px;" value="" title="No Rekening">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 bank_opt_<?php echo $randid; ?> bank-col-3">
                                                            <div class="form-group">
                                                                <input type="text" value="<?php echo $an_rekening; ?>"  class="form-control label_an" id="opt_label_an_<?php echo $randid; ?>" required="" placeholder="Rek Atas Nama" style="font-size: 13px;padding-left: 12px;" value="" title="Rek Atas Nama">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 bank_opt_<?php echo $randid; ?> bank-col-4">
                                                            <div class="form-group">
                                                                <select class="form-control" id="select_method_<?php echo $randid; ?>" data-randid="<?php echo $randid; ?>" name="select_method" style="height: 45px;" title="Payment Method">
                                                                    <option value="0">Pilih Method</option>
                                                                    <option value="1" <?php if($payment_method=='1'){echo'selected';}?>>Instant</option>
                                                                    <option value="2" <?php if($payment_method=='2'){echo'selected';}?>>VA</option>
                                                                    <option value="3" <?php if($payment_method=='3'){echo'selected';}?>>Transfer</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 bank_opt_<?php echo $randid; ?> bank-col-5">
                                                            <button type="button" class="btn btn-danger del_bank" title="Delete" data-randid="<?php echo $randid; ?>" style="margin-top: 5px;">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                        <?php } } ?>


                                                    </div>
                                                    <div class="row" style="margin-top: -15px;">
                                                        <div class="col-md-12" style="padding-bottom: 20px;">
                                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="add_bank">+ Add Bank</button>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-primary px-5 py-2" id="update_payment">Update <div class="spinner-border spinner-border-sm text-white update_payment_loading" style="margin-left: 3px;display: none;"></div></button>
                                                        </div>
                                                    </div>


                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->                                                               
                                    </div>
                                </div>     
                                <!-- end content -->
                            </div>
                            <div class="tab-pane p-3" id="ipaymu" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_ipaymu" style="">

                                                    <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;">Bagi yang belum memiliki akun iPaymu, bisa mendaftar melalui link berikut: <br><a href="http://bit.ly/daftar-akun-ipaymu" target="_blank" style="color: #7680ff;text-decoration: underline;line-height: 1.3;">Daftar akun iPaymu sekarang</a></h5>
                                                    <!-- https://my.ipaymu.com/register/ -->
                                                    <br>

                                                    <?php if($plugin_license=='ULTIMATE'){ ?>
                                                    <div class="row" style="padding: 0px 0 15px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">iPaymu Mode</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Gunakan mode Sandbox untuk uji coba dan LIVE jika sistem sudah berjalan.</p> 
                                                            <div class="form-group mb-0 row">
                                                                <div class="col-md-12" style="padding-bottom: 5px;">
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="0" id="customRadioMode1" name="ipaymu_mode" class="custom-control-input" <?php if($ipaymu_mode=='0') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioMode1">Sandbox</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="1" id="customRadioMode2" name="ipaymu_mode" class="custom-control-input" <?php if($ipaymu_mode=='1') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioMode2">LIVE (Production)</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="padding: 10px 0 10px 0;">
                                                        <div class="col-md-8">
                                                            <h5 class="card-title mt-0">iPaymu Virtual Account</h5>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="ipaymu_va" required="" placeholder="Virtual Account iPaymu" value="<?php echo $ipaymu_va; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                        <div class="col-md-8">
                                                            <h5 class="card-title mt-0">iPaymu API Key</h5>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="ipaymu_apikey" required="" placeholder="API Key iPaymu" value="<?php echo $ipaymu_apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" style="background: #f6faff;padding: 20px 10px;border-radius: 8px;border: 1px solid #e8ebf3;margin-left: 0px;">
                                                        
                                                        <div class="col-md-12" style="padding-bottom: 20px;">
                                                            <h5 class="card-title mt-0">iPaymu Code</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Perhatikan pada bagian "code" dan gunakan pada saat mensetting payment dengan iPaymu.</p> 
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Instant</b></label>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant1">QRIS</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: qris</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant2" data-parsley-multiple="groups" data-parsley-mincheck="2"  checked="">
                                                                    <label class="custom-control-label" for="instant2">Gopay</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: gopay</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant3">Ovo</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: ovo</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant4">Dana</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: dana</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant5" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant5">Linkaja</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: linkaja</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant6" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant6">Shopeepay</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: shopeepay</p> 
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Virtual Account (VA)</b></label>

                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va1">VA BCA</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va1">VA Mandiri</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: mandiri</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA BNI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bni</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA BRI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bri</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA BSI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bsi</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA Muamalat</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bmi</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va3">VA Cimb Niaga</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: cimb</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va4">VA Artha Graha</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bag</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va4">VA Permata</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: permata</p> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Transfer</b></label>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Transfer BCA</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Alfamart</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamart</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Indomaret</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: indomaret</p> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 0px;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-primary px-4 py-2" id="update_ipaymu">Update iPaymu<div class="spinner-border spinner-border-sm text-white update_ipaymu_loading" style="margin-left: 3px;display: none;"></div></button>
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

                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->                                                               
                                    </div>
                                </div>     
                                <!-- end content -->
                            </div>
                            <div class="tab-pane p-3" id="tripay" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_tripay" style="">

                                                    <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;">Bagi yang belum memiliki akun Tripay, bisa mendaftar melalui link berikut: <br><a href="http://bit.ly/daftar-akun-tripay" target="_blank" style="color: #7680ff;text-decoration: underline;line-height: 1.3;">Daftar akun Tripay sekarang</a></h5>
                                                    <br>

                                                    <?php if($plugin_license=='ULTIMATE'){ ?>
                                                    <div class="row" style="padding: 0px 0 15px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">Tripay Mode</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Gunakan mode Sandbox untuk uji coba dan LIVE jika sistem sudah berjalan.<br>Masukkan API Key, Private Key, dan Kode Merchant sesuai dengan Mode-nya.</p> 
                                                            <div class="form-group mb-0 row">
                                                                <div class="col-md-12" style="padding-bottom: 5px;">
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="0" id="customRadioModeTripay1" name="tripay_mode" class="custom-control-input" <?php if($tripay_mode=='0') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioModeTripay1">Sandbox</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="1" id="customRadioModeTripay2" name="tripay_mode" class="custom-control-input" <?php if($tripay_mode=='1') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioModeTripay2">LIVE (Production)</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="tripay_production" style="<?php if($tripay_mode=='1'){}else{echo'display: none;';}?>">
                                                        <div class="row" style="padding: 10px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">Tripay API Key - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="tripay_apikey" required="" placeholder="API Key" value="<?php echo $tripay_apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">Tripay Private Key - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="tripay_privatekey" required="" placeholder="Private Key" value="<?php echo $tripay_privatekey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">Kode Merchant - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="tripay_merchant" required="" placeholder="Kode Merchant" value="<?php echo $tripay_merchant; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">URL Callback - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/callback_tripay/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div id="tripay_sandbox" style="<?php if($tripay_mode=='0'){}else{echo'display: none;';}?>">
                                                        <div class="row" style="padding: 10px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">Tripay API Key - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="tripay_apikey_sandbox" required="" placeholder="API Key" value="<?php echo $tripay_apikey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">Tripay Private Key - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="tripay_privatekey_sandbox" required="" placeholder="Private Key" value="<?php echo $tripay_privatekey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">Kode Merchant - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="tripay_merchant_sandbox" required="" placeholder="Kode Merchant" value="<?php echo $tripay_merchant_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">URL Callback - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/callback_tripay_sandbox/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="padding: 0px 0 35px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">Tripay QRIS</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Silahkan gunakan salah satu settingan QRIS dibawah jika mengalami kendala pada QRIS, Gopay, dan LinkAja tidak muncul.</p> 
                                                            <div class="form-group mb-0 row">
                                                                <div class="col-md-12" style="padding-bottom: 5px;">
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="QRISC" id="customRadioQris1" name="tripay_qris" class="custom-control-input" <?php if($tripay_qris=='QRISC' || $tripay_qris=='') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioQris1">QRIS (Customable)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="QRIS2" id="customRadioQris2" name="tripay_qris" class="custom-control-input" <?php if($tripay_qris=='QRIS2') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioQris2">QRIS (DANA)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="QRIS" id="customRadioQris3" name="tripay_qris" class="custom-control-input" <?php if($tripay_qris=='QRIS') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioQris3">QRIS (ShopeePay)</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" style="background: #f6faff;padding: 20px 10px;border-radius: 8px;border: 1px solid #e8ebf3;margin-left: 0px;">
                                                        
                                                        <div class="col-md-12" style="padding-bottom: 20px;">
                                                            <h5 class="card-title mt-0">Tripay Code</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Perhatikan pada bagian "code" dan gunakan pada saat mensetting payment dengan Tripay.</p> 
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Instant</b></label>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant1">QRIS</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: qris</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant2" data-parsley-multiple="groups" data-parsley-mincheck="2"  checked="">
                                                                    <label class="custom-control-label" for="instant2">Gopay</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: gopay</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant3">Ovo</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: ovo</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant4">Dana</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: dana</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant5" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant5">Linkaja</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: linkaja</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant6" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant6">Shopeepay</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: shopeepay</p> 
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Virtual Account (VA)</b></label>

                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va1">VA BSI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bsi</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va1">VA Mandiri</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: mandiri</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA BNI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bni</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA BRI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bri</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va1">VA BCA</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA Muamalat</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bmi</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA Danamon</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: danamon</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA Permata</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: permata</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va3">VA Cimb Niaga</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: cimb</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va4">VA Maybank</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: maybank</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va4">VA Sinarmas</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: sinarmas</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va4">VA Sahabat Sampoerna</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: sampoerna</p> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Transfer</b></label>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Alfamart</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamart</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Alfamidi</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamidi</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Indomaret</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: indomaret</p> 
                                                                </div>
                                                            </div>
                                                            <!--
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Credit Card</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: cc</p> 
                                                                </div>
                                                            </div>
                                                            -->
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 0px;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-primary px-4 py-2" id="update_tripay">Update Tripay<div class="spinner-border spinner-border-sm text-white update_tripay_loading" style="margin-left: 3px;display: none;"></div></button>
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

                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->                                                               
                                    </div>
                                </div>     
                                <!-- end content -->
                            </div>
                            <div class="tab-pane p-3" id="midtrans" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_tripay" style="">

                                                    <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;">Bagi yang belum memiliki akun Midtrans, bisa mendaftar melalui link berikut: <br><a href="http://bit.ly/daftar-akun-midtrans" target="_blank" style="color: #7680ff;text-decoration: underline;line-height: 1.3;">Daftar akun Midtrans sekarang</a></h5>
                                                    <br>

                                                    <?php if($plugin_license=='ULTIMATE'){ ?>
                                                    <div class="row" style="padding: 0px 0 15px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">Midtrans Mode</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Gunakan mode Sandbox untuk uji coba dan LIVE jika sistem sudah berjalan.<br>Masukkan Server Key, Client Key, dan Merchant ID sesuai dengan Mode-nya.</p> 
                                                            <div class="form-group mb-0 row">
                                                                <div class="col-md-12" style="padding-bottom: 5px;">
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="0" id="customRadioModeMidtrans1" name="midtrans_mode" class="custom-control-input" <?php if($midtrans_mode=='0') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioModeMidtrans1">Sandbox</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-check-inline my-1">
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" value="1" id="customRadioModeMidtrans2" name="midtrans_mode" class="custom-control-input" <?php if($midtrans_mode=='1') { echo 'checked=""';}?> >
                                                                            <label class="custom-control-label" for="customRadioModeMidtrans2">LIVE (Production)</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="midtrans_production" style="<?php if($midtrans_mode=='1'){}else{ echo 'display:none';}?>">
                                                        <div class="row" style="padding: 10px 0 10px 0;">
                                                            <div class="col-md-8">
                                                                <h5 class="card-title mt-0">Midtrans Server Key - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="midtrans_serverkey" required="" placeholder="Server Key" value="<?php echo $midtrans_serverkey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-8">
                                                                <h5 class="card-title mt-0">Midtrans Client Key - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="midtrans_clientkey" required="" placeholder="Client Key" value="<?php echo $midtrans_clientkey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-8">
                                                                <h5 class="card-title mt-0">Merchant ID - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="midtrans_merchant" required="" placeholder="Merchant ID" value="<?php echo $midtrans_merchant; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">URL Handling - Production</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/midtrans_handling/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="midtrans_sandbox" style="<?php if($midtrans_mode=='0'){}else{ echo 'display:none';}?>">
                                                        <div class="row" style="padding: 10px 0 10px 0;">
                                                            <div class="col-md-8">
                                                                <h5 class="card-title mt-0">Midtrans Server Key - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="midtrans_serverkey_sandbox" required="" placeholder="Server Key" value="<?php echo $midtrans_serverkey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-8">
                                                                <h5 class="card-title mt-0">Midtrans Client Key - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="midtrans_clientkey_sandbox" required="" placeholder="Client Key" value="<?php echo $midtrans_clientkey_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-8">
                                                                <h5 class="card-title mt-0">Merchant ID - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="midtrans_merchant_sandbox" required="" placeholder="Merchant ID" value="<?php echo $midtrans_merchant_sandbox; ?>" style="font-size: 13px;padding-left: 12px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                            <div class="col-md-9">
                                                                <h5 class="card-title mt-0">URL Handling - Sandbox</h5>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/midtrans_handling_sandbox/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" style="background: #f6faff;padding: 20px 10px;border-radius: 8px;border: 1px solid #e8ebf3;margin-left: 0px;">
                                                        
                                                        <div class="col-md-12" style="padding-bottom: 20px;">
                                                            <h5 class="card-title mt-0">Midtrans Code</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Perhatikan pada bagian "code" dan gunakan pada saat mensetting payment dengan Midtrans.</p> 
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Instant</b></label>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant1">QRIS</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: qris</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant2" data-parsley-multiple="groups" data-parsley-mincheck="2"  checked="">
                                                                    <label class="custom-control-label" for="instant2">Gopay</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: gopay</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant3" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant3">Ovo</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: ovo</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant4">Dana</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: dana</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant5" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant5">Linkaja</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: linkaja</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="instant6" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="instant6">Shopeepay</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: shopeepay</p> 
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Virtual Account (VA)</b></label>

                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va1">VA Mandiri</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: mandiri</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va1">VA Permata</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: permata</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va4" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va4">VA BCA</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bca</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA BNI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bni</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="va2" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="va2">VA BRI</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: bri</p> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="control-label"><b>Transfer</b></label>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Alfamart</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: alfamart</p> 
                                                                </div>
                                                            </div>
                                                            <div class="checkbox my-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="transfer1" data-parsley-multiple="groups" data-parsley-mincheck="2" checked="">
                                                                    <label class="custom-control-label" for="transfer1">Indomaret</label>
                                                                    <p class="card-text text-muted" style="padding-left: 8px;">Code: indomaret</p> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 0px;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-primary px-4 py-2" id="update_midtrans">Update Midtrans<div class="spinner-border spinner-border-sm text-white update_midtrans_loading" style="margin-left: 3px;display: none;"></div></button>
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

                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->                                                               
                                    </div>
                                </div>     
                                <!-- end content -->
                            </div>                                           
                            <div class="tab-pane p-3" id="moota" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_ipaymu" style="">

                                                    <?php if($license=='PRO' || $license=='ULTIMATE') { ?>

                                                    <h5 class="card-text" style="margin-top: -5px;font-size:13px;font-weight: normal;line-height: 1.3;">Bagi yang belum memiliki akun Moota, bisa mendaftar melalui link berikut: <br><a href="https://app.moota.co?ref=wanessQp4" target="_blank" style="color: #7680ff;text-decoration: underline;">Daftar akun Moota sekarang</a></h5> 
                                                        
                                                    <br>

                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                        <div class="col-md-8">
                                                            <h5 class="card-title mt-0">URL Endpoint</h5>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" required="" placeholder="URL Endpoint" value="<?php echo $home_url; ?>/push_moota/" style="font-size: 13px;padding-left: 12px;" disabled="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding: 0px 0 10px 0;">
                                                        <div class="col-md-8">
                                                            <h5 class="card-title mt-0">Moota Secret Token</h5>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="moota_secret_token" required="" placeholder="Moota Secret Token" value="<?php echo $moota_secret_token; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row" style="padding: 0px 0 10px 0;">
                                                        <div class="col-md-4">
                                                            <h5 class="card-title mt-0">Moota Date Range</h5>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control" id="moota_range" required="" placeholder="Moota Range" value="<?php echo $moota_range; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 0px;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-primary px-4 py-2" id="update_moota">Update Moota<div class="spinner-border spinner-border-sm text-white update_moota_loading" style="margin-left: 3px;display: none;"></div></button>
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
                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->                                                               
                                    </div>
                                </div>     
                                <!-- end content -->
                            </div>
                        </div>
                        
                    </div>
                </div>


            </div><!--end card-body-->                                
        </div><!--end card-->
    </div><!--end col-->
</div>
