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
                        <h5 class="boxcard-title">Notification</h5>  
                        <p class="card-text text-muted">Silahkan diatur notifikasi sesuai kebutuhan anda.</p>
                    </div><!--end col-->
                </div><!--end row-->
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        
                        <?php 

                            if($wanotif_followup1_on=='1'){
                                $status_text1 = '<span>Active</span>';
                                $checked1 = 'checked=""';
                            }else{
                                $status_text1 = '<span>Not Active</span>';
                                $checked1 = '';
                            }

                            if($wanotif_on=='1'){
                                $status_text2 = '<span>Active</span>';
                                $checked2 = 'checked=""';
                            }else{
                                $status_text2 = '<span>Not Active</span>';
                                $checked2 = '';
                            }

                            if($telegram_on=='1'){
                                $status_text3 = '<span>Active</span>';
                                $checked3 = 'checked=""';
                            }else{
                                $status_text3 = '<span>Not Active</span>';
                                $checked3 = '';
                            }

                            if($email_on=='1'){
                                $status_text4 = '<span>Active</span>';
                                $checked4 = 'checked=""';
                            }else{
                                $status_text4 = '<span>Not Active</span>';
                                $checked4 = '';
                            }



                            


                        ?>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#wanotif" role="tab" aria-selected="true">Wanotif</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#telegram" role="tab" aria-selected="false">Telegram</a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#email" role="tab" aria-selected="false">Email</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane p-3 active" id="wanotif" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_wanotif" style="">

                                                    <div class="row" style="padding: 0px 0 20px 0;">
                                                        <div class="col-md-12">
                                                            <p class="card-text text-muted">Wanotif memudahkan kita dalam mengirimkan pesan whatsapp otomatis ke Donatur. Semakin cepat donatur mendapatkan notifikasi transaksi donasi, semakin cepat donatur akan menyelesaikan proses pembayaran. Lembaga Donasi juga lebih terlihat Profesional.</p>
                                                            <br>
                                                            <h5 class="card-title mt-0" style="padding-top: 0px;">Wanotif<span></span></h5>
                                                            <p class="card-text text-muted" style="margin-top:0px;">Aktifkan agar Wanotif bisa mengirimkan whatsapp ke no donatur.</p> 
                                                            <div class="form-group" style="margin-top: -5px;margin-bottom: 38px;">
                                                                <div class="custom-control custom-switch" id="checkbox_wanotif_on">
                                                                    <input type="checkbox" class="custom-control-input checkbox1" id="wanotif_on" data-id="1" <?php echo $checked2; ?> >
                                                                    <label class="custom-control-label" for="wanotif_on"><?php echo $status_text2; ?></label>
                                                                </div>
                                                            </div>

                                                            <h5 class="card-title mt-0">Wanotif Apikey - Default</h5>
                                                            <p class="card-text text-muted">Dapatkan Apikey Wanotif <a href="https://wanotif.id/" target="_blank" class="link_href">disini.</a></p> 
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="wanotif_apikey" required="" placeholder="Wanotif Apikey" value="<?php echo $wanotif_apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                                            </div>
                                                            <p class="card-text text-muted" style="margin-top: -10px;margin-bottom: 20px;">Status : <span id="status_wanotif"></span></p> 
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding: 0px 0 20px 0;">
                                                        <div class="col-md-12">
                                                            <h5 class="card-title mt-0">Wanotif Apikey - CS Rotator</h5>
                                                            <p class="card-text text-muted" style="margin-bottom: 10px;">Tambahkan Apikey Wanotif pada masing-masing CS agar pengiriman langsung dari device Whatsapp CS anda (CS Rotator). Abaikan jika anda menggunakan 1 device pada pengiriman notifikasi whatsapp, karena otomatis akan menggunakan wanotif apikey default.</p> 
                                                        </div>
                                                        <div id="box_cs_apikey">
                                                            <?php  

                                                            if($jumlah_wanotif_cs>=1){

                                                            $wanotif_apikey_cs = json_decode($wanotif_apikey_cs, true);
                                                            foreach ($wanotif_apikey_cs['data'] as $key => $value) { 
                                                                $rand3 = d_randomString(3);
                                                            ?>

                                                            <div class="form-group row container_cs_box" style="padding: 8px 10px 0 10px;margin-bottom:5px;" id="container_cs_<?php echo $rand3;?>" data-id="<?php echo $rand3;?>">
                                                                <div class="col-lg-5 mo-b-15">
                                                                    <select class="form-control select_cs" id="select_cs_<?php echo $rand3;?>" data-randid="uj7" name="select_cs" style="height: 45px;" title="CS">
                                                                    <option value="0">Choose CS</option>
                                                                    <?php 
                                                                    $nama_csnya = '';
                                                                    foreach ( $data_userwp as $user ) {
                                                                        $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
                                                                        $roles_user = array_keys((array)$cap_user);
                                                                        $rolenya_user = $roles_user[0];

                                                                        if($rolenya_user=='cs'){
                                                                            $selected = '';
                                                                            if($user->ID==$value[0]){
                                                                                $selected = 'selected';
                                                                                $nama_csnya = $user->display_name;
                                                                            }
                                                                            echo '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';

                                                                            
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    </select>                                   
                                                                </div> 
                                                                <div class="col-lg-6" style="padding-left: 0;">
                                                                    <input class="form-control" type="text" id="apikey_cs_<?php echo $rand3;?>" value="<?php echo $value[1];?>" placeholder="Wanotif Apikey">
                                                                </div>              
                                                                <div class="col-lg-1">
                                                                    <button type="button" class="btn btn-danger del_apikey" title="Delete" data-randid="<?php echo $rand3;?>" style="margin-top: 5px;"><i class="fas fa-minus"></i></button>
                                                                </div>                                                   
                                                            </div>
                                                            <?php } } ?>
                                                        </div>
                                                        <div class="col-md-12" style="margin-top: 15px;">
                                                            <button type="button" class="btn btn-outline-primary add_apikey" data-randid=""><i class="fas fa-plus" style="font-size:9px;"></i>&nbsp;&nbsp;Add Apikey</button>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding: 40px 0 20px 0;">
                                                        <div class="col-md-12">
                                                            <h5 class="card-title mt-0">Followup Message</h5>
                                                            <p class="card-text text-muted">Pesan yang akan dikirimkan pertama kali ke Donatur setelah berhasil submit donasi.</a></p> 
                                                            <div class="form-group" style="border-radius: 6px;background: #f6faff;padding: 20px 20px;margin-bottom: 20px !important;box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);-webkit-box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);">
                                                                <textarea class="form-control" rows="8" id="wanotif_message" style="font-size: 13px;"><?php echo $wanotif_message; ?></textarea>
                                                            </div>
                                                            <div class="form-group" style="padding-top: 20px;">
                                                                <br>
                                                                <h5 class="card-title mt-0">Followup Message dengan Followup 1</h5>
                                                                <div class="custom-control custom-switch" id="wanotif_followup1_on">
                                                                    <input type="checkbox" class="custom-control-input checkbox1" id="wanotif_followup1" data-id="1" <?php echo $checked1; ?> >
                                                                    <label class="custom-control-label" for="wanotif_followup1"><?php echo $status_text1; ?></label>
                                                                </div>
                                                            </div>
                                                            <p class="card-text text-muted" style="margin-top: -10px;">Aktifkan jika ingin menggunakan text followup 1 yang ada di <a href="<?php echo admin_url('admin.php?page=donasiaja_dashboard&action=settings#followup1') ?>" class="link_href">Followup Dashboard</a>. Keuntungan mengaktfikan ini adalah ketika terjadi followup maka akan ketahuan button followup 1 akan berwarna hijau.</p> 
                                                            </div>

                                                    </div>

                                                    <div class="row" style="padding: 0px 0 20px 0;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <h5 class="card-title mt-0">Payment Success Message</h5>
                                                            <p class="card-text text-muted">Pesan yang akan dikirimkan ke Donatur setelah sistem menerima pembayaran. Notifikasi akan di trigger melalui transaksi di Moota, iPaymu, dan Tripay secara otomatis.</a></p> 
                                                            <div class="form-group" style="border-radius: 6px;background: #f6faff;padding: 20px 20px;margin-bottom: 20px !important;box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);-webkit-box-shadow: 0 6px 24px rgba(164, 192, 217, 0.35);">
                                                                <textarea class="form-control" rows="8" id="wanotif_message2" style="font-size: 13px;"><?php echo $wanotif_message2; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-md-12">
                                                            
                                                            <button type="button" class="btn btn-primary px-4 py-2" id="update_wanotif" style="margin-right: 8px;">Update Wanotif<div class="spinner-border spinner-border-sm text-white update_wanotif_loading" style="margin-left: 3px;display: none;"></div></button>
                                                            <button type="button" class="btn btn-outline-warning waves-effect waves-light px-4 py-2" id="test_wanotif">Test Wanotif<div class="spinner-border spinner-border-sm text-white" style="margin-left: 3px;display: none;"></div></button>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <br><br><br>
                                                        <h5 class="card-text">Note</h5>
                                                        <hr>
                                                        <p class="card-text text-muted">Silahkan tambahkan shortcode berikut untuk memanggil value dari setiap donasi yang masuk.</p> 
                                                        <ul>
                                                            <li>{name} : Nama donatur</li>
                                                            <li>{email} : Email donatur</li>
                                                            <li>{whatsapp} : Whatsapp donatur</li>
                                                            <li>{comment} : Pesan atau Doa dari donatur</li>
                                                            <li>{total} : Nominal donasi dari donatur</li>
                                                            <li>{payment_number} : No Rekening</li>
                                                            <li>{payment_code} : Nama Bank atau Dompet Digital</li>
                                                            <li>{payment_account} : Nama Pemilik Rekening</li>
                                                            <li>{campaign_title} : Judul program dari campaign</li>
                                                            <li>{fundraiser} : Nama Fundraiser pada campaign jika ada</li>
                                                            <li>{date} : Tanggal donasi dibuat</li>
                                                            <li>{invoice_id} : No Invoice ID donasi</li>
                                                            <li>{cs_name} : Nama CS, pastikan anda menggunakan CS Rotator</li>
                                                            <li>{link_ekuitansi} : Link untuk mendownload ekuitansi</li>

                                                        </ul>
                                                        <p class="card-text text-muted">Contoh :</p> 
                                                        <textarea class="form-control" rows="6" disabled="" style="color: #435177;">Terimakasih *Bpk/Ibu {name}* atas Donasi yang akan Anda berikan. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.

Untuk Donasinya sejumlah *{total}* mohon ditransfer ke *{payment_account}* dengan No Rek *{payment_number}*. 
Terimakasih 😊🙏</textarea>
                                                    </div>

                                                    
                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->                                                               
                                    </div>
                                </div>     
                                <!-- end content -->
                            </div>

                            <div class="tab-pane p-3" id="telegram" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_telegram" style="">
                                                    <p class="card-text text-muted">Gunakan Telegram Notif untuk memantau setiap donasi yang masuk dan notif konfirmasi manual yang dilakukan oleh donatur. Anda bisa mengirimkan notif ke Channel Telegram anda. Free, tinggal setting dan buat Bot Telegram anda untuk mengirimkan notif.</p>
                                                    <br>
                                                    <h5 class="card-title mt-0" style="padding-top: 0px;">Telegram<span></span></h5>
                                                    <p class="card-text text-muted" style="margin-top:0px;">Aktifkan agar Bot Telegram anda bisa mengirimkan notif.</p> 
                                                    <div class="form-group" style="margin-top: -5px;">
                                                        <div class="custom-control custom-switch" id="checkbox_telegram_on">
                                                            <input type="checkbox" class="custom-control-input checkbox1" id="telegram_on" data-id="1" <?php echo $checked3; ?> >
                                                            <label class="custom-control-label" for="telegram_on"><?php echo $status_text3; ?></label>
                                                        </div>
                                                    </div>
                                                    
                                                    <br>
                                                    <h5 class="card-title mt-0" style="padding-top: 0px;">Telegram Bot Token<span></span></h5>
                                                    <div class="form-group" style="padding-bottom: 20px;">
                                                        <input type="text" class="form-control" id="token" required="" placeholder="Your API Key" value="<?php echo $token; ?>" style="font-size: 13px;padding-left: 12px;">
                                                    </div>

                                                    <hr>

                                                    <div class="row" style="padding: 20px 0 15px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">Telegram Notif (New Donate)</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Notif ini untuk mendapatkan notifikasi dari setiap donasi baru yang masuk. Silahkan klik tombol <b>+Notif</b> dibawah untuk menambahkan beberapa notifikasi ke channel telegram. Anda bisa menambahkan pesan notifikasi telegram yang berbeda ke channel yang berbeda.</p>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="padding: 10px;padding-top: 20px;" id="box_notif">
                                                        
                                                        <?php 
                                                        
                                                        $no = 1;
                                                        foreach($telegram_send_to as $key => $value) {
                                                                $message_tele = $value->message;
                                                        ?>
                                                        
                                                        <div id="box_tele_<?php echo $no; ?>" class="col-md-12 box_telegram_message">
                                                            <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Notif <?php echo $no; ?></h5>
                                                            <?php if($no!=1) { ?> 
                                                            <div class="form-group" style="position: absolute;right: 0;margin-right: 20px;margin-top: -10px;">
                                                            <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-danger  waves-effect waves-light btn-xs del_notif" title="Delete Notif"><i class="mdi mdi-close"></i> Del&nbsp;</button>
                                                            </div>
                                                            <?php }?>
                                                            <hr>
                                                            <label style="margin-top: 20px;">Custom Channel :</label> 
                                                            <textarea id="myTags_<?php echo $no; ?>" class="tagit"></textarea>
                                                            <label>Message :</label>
                                                            <div class="form-group">
                                                                <textarea class="form-control textarea_text tele_message" rows="5" placeholder="Message"><?php echo $message_tele; ?></textarea>
                                                            </div>
                                                        </div>

                                                        <?php $no++; } ?>

                                                    </div>

                                                    <div class="row" style="margin-top: 15px;">
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="add_notif"><i class="mdi mdi-plus"></i> Notif&nbsp;&nbsp;</button>
                                                        </div>
                                                    </div>

                                                    <?php                  
                                                        $message_tele_mc = '';
                                                        foreach($telegram_manual_confirmation as $key => $value) {
                                                            $message_tele_mc = $value->message;
                                                        }
                                                    ?>

                                                    <div class="row" style="padding: 30px 0 15px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <hr>
                                                            <br>
                                                            <h5 class="card-title mt-0">Telegram Notif (Manual Confirmation)</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Notif ini berfungsi untuk mendapatkan notifikasi dari setiap donatur yang memberikan konfirmasi pembayaran secara manual.</p>
                                                        </div>
                                                    </div>

                                                    <div id="box_tele_99" class="col-md-12 box_telegram_message2">
                                                        <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Notif Manual Confirmation</h5>
                                                        
                                                        <hr>
                                                        <label style="margin-top: 20px;">Custom Channel :</label> 
                                                        <textarea id="myTags_manual_confirmation" class="tagit tagit2"></textarea>
                                                        <label>Message :</label>
                                                        <div class="form-group">
                                                            <textarea class="form-control textarea_text tele_message2" rows="5" placeholder="Message"><?php echo $message_tele_mc; ?></textarea>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="row" style="margin-top: 20px;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-primary px-4 py-2" id="update_telegram" style="margin-right: 10px;">Update Telegram<div class="spinner-border spinner-border-sm text-white update_telegram_loading" style="margin-left: 3px;display: none;"></div></button>
                                                            <button type="button" class="btn btn-outline-warning waves-effect waves-light px-4 py-2" id="test_telegram">Test Telegram<div class="spinner-border spinner-border-sm text-white" style="margin-left: 3px;display: none;"></div></button>
                                                        </div>
                                                    </div>

                                                        <div style="margin-top: 40px;">
                                                        <h5 class="card-text">Note</h5> 
                                                        <ul>
                                                            <li class="text-muted">Public: <span class="text_highlight">@</span><b>yourchannel</b></li>
                                                            <li class="text-muted">Private: <span class="text_highlight">-100</span><b>XXXXXXXXXX</b></li>
                                                            <li class="text-muted">Lebih lengkap cara mengetahui Channel ID bisa di <a href="https://bit.ly/donasiajanote" target="_blank" class="link_href">link berikut ini</a>.</li>
                                                    </div>

                                                    <div>
                                                        <!-- <br><br><br> -->
                                                        <!-- <h5 class="card-text">Note</h5> -->
                                                        <hr>
                                                        <p class="card-text text-muted">Silahkan tambahkan shortcode berikut untuk memanggil value dari setiap donasi yang masuk.</p> 
                                                        <ul>
                                                            <li>{name} : Nama donatur</li>
                                                            <li>{email} : Email donatur</li>
                                                            <li>{whatsapp} : Whatsapp donatur</li>
                                                            <li>{comment} : Pesan atau Doa dari donatur</li>
                                                            <li>{total} : Nominal donasi dari donatur</li>
                                                            <li>{payment_number} : No Rekening</li>
                                                            <li>{payment_code} : Nama Bank atau Dompet Digital</li>
                                                            <li>{payment_account} : Nama Pemilik Rekening</li>
                                                            <li>{campaign_title} : Judul program dari campaign</li>
                                                            <li>{fundraiser} : Nama Fundraiser pada campaign jika ada</li>
                                                            <li>{date} : Tanggal donasi dibuat</li>
                                                            <li>{invoice_id} : No Invoice ID donasi</li>
                                                            <li>{cs_name} : Nama CS, pastikan anda menggunakan CS Rotator</li>
                                                            <li>{link_ekuitansi} : Link untuk mendownload ekuitansi</li>
                                                        </ul>
                                                        <p class="card-text text-muted">Contoh :</p> 
                                                        <textarea class="form-control" rows="7" disabled="" style="color: #435177;">Alhamdulillah ada donasi baru yang masuk sebesar *{total}*,
Donatur : {name}
Whatsapp : {whatsapp}
Email : {email}
Pesan : {comment}
Program : {campaign_title}
Pengiriman ke {payment_number} - {payment_account}
</textarea>
                                                    </div>



                                                </div>

                                            </div><!--end card -body-->
                                        </div><!--end card-->                                                               
                                    </div>
                                </div>     
                                <!-- end content -->
                            </div>

                                <div class="tab-pane p-3" id="email" role="tabpanel" style="padding-left: 5px !important;">
                                <!-- content -->
                                <div class="row">
                                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                                        <div class="card card-border" style="border: 0;padding: 0;">
                                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                                <div id="data_email" style="">
                                                    <p class="card-text text-muted">Gunakan Email untuk mengirimkan pesan ke Customer/Donatur. Bisa juga anda gunakan untuk mengirimkan notifikasi email ke Owner/Pemilik Yayasan atau Organisasi.</p>
                                                    <br>
                                                    <h5 class="card-title mt-0" style="padding-top: 0px;">Email<span></span></h5>
                                                    <p class="card-text text-muted" style="margin-top:0px;">Aktifkan email agar anda bisa mengirim email.</p> 
                                                    <div class="form-group" style="margin-top: -5px;">
                                                        <div class="custom-control custom-switch" id="checkbox_email_on">
                                                            <input type="checkbox" class="custom-control-input checkbox1" id="email_on" data-id="1" <?php echo $checked4; ?> >
                                                            <label class="custom-control-label" for="email_on"><?php echo $status_text4; ?></label>
                                                        </div>
                                                    </div>
                                                    
                                                    <hr>

                                                    <div class="row" style="padding: 20px 0 15px 0;">
                                                        <div class="col-md-12" style="margin-bottom: 10px;">
                                                            <h5 class="card-title mt-0">Email Message</h5>
                                                            <p class="card-text text-muted" style="margin-top: -5px;">Silahkan klik tombol <b>+Email</b> dibawah untuk menambahkan email yang akan dikirimkan. Email ini akan dikirimkan pada saat terjadi submit donasi. Anda bisa menambahkan sampai 3 pesan email.</p> 
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="row" style="padding: 10px;padding-top: 20px;" id="box_email">
                                                        
                                                        <?php 
                                                        
                                                        $no = 1;
                                                        foreach($email_send_to as $key => $value) {

                                                                $message_email = $value->message;
                                                                $message_email = str_replace('<p>linebreak</p>', '', $message_email);
                                                                $message_email = str_replace('linebreak', '', $message_email);

                                                                if (isset($value->subject)){
                                                                    $subject_email = $value->subject;
                                                                }else{
                                                                    $subject_email = '';
                                                                }
                                                                if (isset($value->email)){
                                                                    $send_to = $value->email;
                                                                }else{
                                                                    $send_to = '';
                                                                }
                                                                if (isset($value->emailcc)){
                                                                    $emailnyacc = $value->emailcc;
                                                                }else{
                                                                    $emailnyacc = '';
                                                                }
                                                                if (isset($value->emailbcc)){
                                                                    $emailnyabcc = $value->emailbcc;
                                                                }else{
                                                                    $emailnyabcc = '';
                                                                }

                                                        ?>
                                                        
                                                        <div id="box_email_<?php echo $no; ?>" class="col-md-12 box_email_message show_box">
                                                            <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email <?php echo $no; ?></h5>
                                                            <?php if($no!=1) { ?> 
                                                            <div class="form-group" style="position: absolute;right: 0;margin-right: 20px;margin-top: -10px;">
                                                            <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-danger  waves-effect waves-light btn-xs del_notif_email" title="Delete Notif"><i class="mdi mdi-close"></i> Del&nbsp;</button>
                                                            </div>
                                                            <?php }?>
                                                            <hr>

                                                            <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                                                <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                                                <button data-id="<?php echo $no; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                                            </div>

                                                            <div class="form-group" style="margin-top: 35px;">
                                                                <label title="Wajib di isi alamat email">Send to* :</label> 
                                                                <!-- <textarea id="myTagsEmail_<?php echo $no; ?>" class="tagit tagitemail"></textarea> -->
                                                                <input type="text" class="form-control send_to_<?php echo $no; ?>"  required="" placeholder="example@gmail.com atau {email}" value="<?php echo $send_to; ?>"  style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                                            </div>
                                                            <div class="form-group">
                                                                <label title="Wajib di isi subject email">Subject* :</label>
                                                                <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="<?php echo $subject_email; ?>">
                                                            </div>
                                                            <div class="form-group email_cc" style="margin: 0;<?php if($emailnyacc==''){echo'display:none;';}?>" >
                                                                <label style="margin-top: 0px;">CC :</label> 
                                                                <textarea id="myTagsEmailCC_<?php echo $no; ?>" class="tagit tagitemailcc"></textarea>
                                                            </div>
                                                            <div class="form-group email_bcc" style="margin: 0;<?php if($emailnyabcc==''){echo'display:none;';}?>">
                                                                <label style="margin-top: 0px;">BCC :</label> 
                                                                <textarea id="myTagsEmailBCC_<?php echo $no; ?>" class="tagit tagitemailbcc"></textarea>
                                                            </div>
                                                            
                                                            <label title="Wajib di isi message email">Message* :</label>
                                                            <div class="form-group">
                                                                <textarea id="email_message_<?php echo $no; ?>" name="area">
                                                                    <?php echo $message_email; ?>
                                                                </textarea> 

                                                            </div>
                                                        </div>

                                                        <?php $no++; } ?>

                                                    <?php

                                                    $jumlah_email = count($email_send_to);
                                                    $email_tambahan = 3;

                                                    for ($i = $jumlah_email; $i <= $email_tambahan; $i++){

                                                        if($i>$jumlah_email){ ?>

                                                        <div id="box_email_<?php echo $i; ?>" class="col-md-12 box_email_message">
                                                            <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email <?php echo $i; ?></h5>
                                                            <?php if($i!=1) { ?> 
                                                            <div class="form-group" style="position: absolute;right: 0;margin-right: 20px;margin-top: -10px;">
                                                            <button data-id="<?php echo $i; ?>" type="button" class="btn btn-outline-danger  waves-effect waves-light btn-xs del_notif_email" title="Delete Notif"><i class="mdi mdi-close"></i> Del&nbsp;</button>
                                                            </div>
                                                            <?php }?>
                                                            <hr>

                                                            <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                                                <button data-id="<?php echo $i; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                                                <button data-id="<?php echo $i; ?>" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                                            </div>

                                                            <div class="form-group" style="margin-top: 0px;">
                                                                <label style="margin-top: 20px;">Send to*:</label> 
                                                                <!-- <textarea id="myTagsEmail_<?php echo $i; ?>" class="tagit tagitemail"></textarea> -->
                                                                <input type="text" class="form-control send_to_<?php echo $i; ?>"  required="" placeholder="example@gmail.com atau {email}" value="" style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Subject*:</label>
                                                                <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="">
                                                            </div>

                                                            <div class="form-group email_cc" style="margin: 0;display:none;" >
                                                                <label style="margin-top: 0px;">CC :</label> 
                                                                <textarea id="myTagsEmailCC_<?php echo $i; ?>" class="tagit tagitemailcc"></textarea>
                                                            </div>
                                                            <div class="form-group email_bcc" style="margin: 0;display:none;" >
                                                                <label style="margin-top: 0px;">BCC :</label> 
                                                                <textarea id="myTagsEmailBCC_<?php echo $i; ?>" class="tagit tagitemailbcc"></textarea>
                                                            </div>

                                                            <label>Message*:</label>
                                                            <div class="form-group">
                                                                <textarea id="email_message_<?php echo $i; ?>" name="area"></textarea> 

                                                            </div>
                                                        </div>

                                                        <?php }

                                                        
                                                        } // end for

                                                    ?>


                                                    </div>


                                                    <div class="row" style="margin-top: 15px;margin-bottom: 30px;">
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light" id="add_notif_email"><i class="mdi mdi-plus"></i> Email&nbsp;&nbsp;</button>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="padding: 0px 0 20px 10px;">
                                                        <div class="col-md-12" style="padding-left: 0;">
                                                            <hr>
                                                            <br>
                                                            <h5 class="card-title mt-0">Payment - Success Email Message</h5>
                                                            <p class="card-text text-muted">Email ini akan dikirimkan ke Donatur setelah sistem menerima pembayaran. Notifikasi akan di trigger melalui transaksi di Moota, iPaymu, dan Tripay secara otomatis dan juga update data donasi secara manual.</a></p>
                                                        </div>




                                                        <div id="box_email_4" class="col-md-12 box_email_message show_box2" style="margin-top: 20px;">
                                                            <h5 class="card-title mt-0" style="background: #7680FF;color: #fff;padding: 15px 20px;position: absolute;width: 100%;margin-left: -20px;margin-top: -20px !important;border-top-left-radius: 4px;border-top-right-radius: 4px;">Email Message</h5>
                                                            <hr>

                                                            <div class="form-group" style="margin: 0;position: absolute;right: 0;margin-right: 20px;margin-top: 13px;">
                                                                <button data-id="4" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_cc" title="Add CC (Carbon Copy)"><i class="mdi mdi-plus"></i> CC&nbsp;</button>
                                                                <button data-id="4" type="button" class="btn btn-outline-info waves-effect waves-light btn-xs add_bcc" title="Add BCC (Blind Carbon Copy)"><i class="mdi mdi-plus"></i> BCC&nbsp;</button>
                                                            </div>

                                                            <div class="form-group" style="margin-top: 35px;">
                                                                <label title="Wajib di isi alamat email">Send to* :</label>
                                                                <input type="text" class="form-control send_to_4"  required="" placeholder="{email}" value="<?php echo $s_send_to; ?>"  style="padding-left: 13px;margin-bottom: 15px;" title="Hanya satu email saja">
                                                            </div>
                                                            <div class="form-group">
                                                                <label title="Wajib di isi subject email">Subject* :</label>
                                                                <input type="text" class="form-control subject_email"  required="" placeholder="Title" value="<?php echo $s_subject_email; ?>">
                                                            </div>
                                                            <div class="form-group email_cc" style="margin: 0;<?php if($s_emailnyacc==''){echo'display:none;';}?>" >
                                                                <label style="margin-top: 0px;">CC :</label> 
                                                                <textarea id="myTagsEmailCC_s" class="tagit tagitemailcc"></textarea>
                                                            </div>
                                                            <div class="form-group email_bcc" style="margin: 0;<?php if($s_emailnyabcc==''){echo'display:none;';}?>">
                                                                <label style="margin-top: 0px;">BCC :</label> 
                                                                <textarea id="myTagsEmailBCC_s" class="tagit tagitemailbcc"></textarea>
                                                            </div>
                                                            
                                                            <label title="Wajib di isi message email">Message* :</label>
                                                            <div class="form-group">
                                                                <textarea id="s_message_email" name="area">
                                                                    <?php echo $s_message_email; ?>
                                                                </textarea> 

                                                            </div>
                                                        </div>

                                                    </div>

                                                    
                                                    <div class="row" style="margin-top: 20px;">
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-primary px-4 py-2" id="update_email" style="margin-right: 10px;">Update Email<div class="spinner-border spinner-border-sm text-white update_email_loading" style="margin-left: 3px;display: none;"></div></button>
                                                            <button type="button" class="btn btn-outline-warning waves-effect waves-light px-4 py-2" id="test_email">Test Email<div class="spinner-border spinner-border-sm text-white" style="margin-left: 3px;display: none;"></div></button>
                                                        </div>
                                                    </div>

                                                        <div style="margin-top: 40px;">
                                                        <h5 class="card-text">Note</h5> 
                                                    </div>

                                                    <div>
                                                        <!-- <br><br><br> -->
                                                        <!-- <h5 class="card-text">Note</h5> -->
                                                        <hr>
                                                        <p class="card-text text-muted">Silahkan tambahkan shortcode berikut untuk memanggil value dari setiap donasi yang masuk.</p> 
                                                        <ul>
                                                            <li>{name} : Nama donatur</li>
                                                            <li>{email} : Email donatur</li>
                                                            <li>{whatsapp} : Whatsapp donatur</li>
                                                            <li>{comment} : Pesan atau Doa dari donatur</li>
                                                            <li>{total} : Nominal donasi dari donatur</li>
                                                            <li>{payment_number} : No Rekening</li>
                                                            <li>{payment_code} : Nama Bank atau Dompet Digital</li>
                                                            <li>{payment_account} : Nama Pemilik Rekening</li>
                                                            <li>{campaign_title} : Judul program dari campaign</li>
                                                            <li>{fundraiser} : Nama Fundraiser pada campaign jika ada</li>
                                                            <li>{date} : Tanggal donasi dibuat</li>
                                                            <li>{invoice_id} : No Invoice ID donasi</li>
                                                            <li>{cs_name} : Nama CS, pastikan anda menggunakan CS Rotator</li>
                                                            <li>{link_ekuitansi} : Link untuk mendownload ekuitansi</li>
                                                        </ul>
                                                        <p class="card-text text-muted">Contoh :</p> 
                                                        <textarea class="form-control" rows="7" disabled="" style="color: #435177;">Terimakasih *{name}* atas Donasi yang akan Anda berikan pada program *{campaign_title}* sebesar *{total}*. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.

Untuk Donasinya mohon ditransfer ke *{payment_account}* dengan No Rek *{payment_number}*. 
Terimakasih 😊🙏


</textarea>
                                                    </div>



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
