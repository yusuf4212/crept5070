<?php

?>
<div class="row">
    <div class="col-12">
        <div class="card col-7" id="box-section">
            <div class="card-body" style="padding-bottom: 0;">                                
                <div class="button-items mb-4">
                    <a href="<?php echo admin_url('admin.php?page=donasiaja_settings') ?>"><button type="button" class="btn btn-primary waves-light">License</button></a>
                    <?php
                    foreach($menu_arr as $key => $val) {
                        $class = 'btn btn-outline-light';

                        echo '<a href="' . admin_url("admin.php?page=donasiaja_settings&action=") . $key . '"><button type="button" class="' . $class . '">' . $val . '</button></a>';
                    }
                    ?>
                </div>
            </div>
            <div class="card-body" style="margin-top: -10px;">
                <div class="row">
                    <div class="col-lg-12 align-self-center mb-3 mb-lg-0">
                        <h5 class="boxcard-title">DonasiAja - <?php echo $GLOBALS['donasiaja_vars']['plugin_version']; ?></h5>  
                        <p class="card-text text-muted">Masukkan lisensi DonasiAja agar anda bisa menggunakan fitur-fitur terbaik yang ada.</p>   
                        <p style="background:#ffd5d5;padding: 20px 20px; border-radius: 2px;display: none;">Perhatian, jika ada perubahan terkait struktur code pada plugin DonasiAja yang dilakukan secara mandiri, maka anda sudah siap dengan konsekuensi jika ada error dan menjadi tanggung jawab pribadi. Harap menjadi perhatian, Terimakasih.</p>
                        <hr>
                        <p class="card-text">
                        <?php
                        if (_check_is_curl_installed()) {
                        } else {
                            echo "<span style=\"color:red\">cURL belum terinstall</span> di server. Silahkan kontak provider hosting anda untuk mengaktifkan cURL-nya agar bisa segera melakukan aktivasi DonasiAja dengan lancar.";
                        }
                        ?>
                        </p>      
                    </div><!--end col-->
                </div><!--end row-->
                <div class="row">
                    <div class="col-md-12 col-xl-12" style="padding: 0;margin-top: -20px;">
                        <div class="card card-border" style="border: 0;padding: 0;">
                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                <div id="data_license" style="margin-bottom: 30px; background: #f5f9ff;border-radius: 8px;padding: 20px 25px;border: 1px solid #ebf1f9;">

                                    

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">LICENSE</h5>
                                            <div class="form-group" style="height: 18px;">
                                                <H4 style="color: #36BD47;"><?php if($license!=''){echo $license.' License';}else{echo'-';}; ?></H4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 5px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">STATUS</h5>
                                            <div class="form-group" style="height: 18px;">
                                                <?php if($status=='valid' && $plugin=='allowed'){ ?>
                                                <p class="card-text" style="background: #36BD47;color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 9px;position: absolute;">Active</p>
                                                <p style="position: absolute;margin-left: 55px;font-style: italic;">Aktif sampai dengan <?php echo date("d M Y - H:i:s",$time);?></p>
                                                <?php } elseif($status=='valid' && $plugin=='expired'){ ?>
                                                <p class="card-text" style="background: #E1345E;color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 9px;position: absolute;">Expired</p>
                                                <p style="position: absolute;margin-left: 60px;font-style: italic;">Aktif sampai dengan <?php echo date("d mY h:i:s",$time);?></p>
                                                <?php } else { ?>
                                                <p class="card-text" style="background: #E1345E;color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 9px;position: absolute;">Not Active</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <hr>
                                            <h5 class="card-title mt-0" style="padding-top: 10px;">API Key DonasiAja<span></span></h5>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="apikey" required="" placeholder="Your API Key" value="<?php echo $apikey; ?>" style="font-size: 13px;padding-left: 12px;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button id="deactivate_apikey" type="button" class="btn btn-outline-primary waves-effect waves-light" style="height: 40px;">&nbsp;&nbsp;Deactivate&nbsp;&nbsp;<div class="spinner-border spinner-border-sm deactivate_apikey_loading" style="margin-left: 3px;display: none;"></button>

                                                <button id="activate_apikey" type="button" class="btn btn-primary waves-effect waves-light" style="height: 40px;">&nbsp;&nbsp;Activate&nbsp;&nbsp;<div class="spinner-border spinner-border-sm activate_apikey_loading" style="margin-left: 3px;display: none;"></button>
                                            </div>
                                            <br>
                                        </div>
                                    </div>

                                    
                                </div>

                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <p class="card-text text-muted"><b>Note:</b><br>Jika saat aktivasi apikey tertulis "Your Activation is Full", klik Deactivate terlebih dahulu setelah itu silahkan Activate kembali. Begitu juga jika ingin menghapus license APIKey, klik tombol Deactivate.</p> 
                                        </div>
                                    </div>

                            </div><!--end card -body-->

                            <div class="card-body" style="padding-left: 10px;padding-right: 10px;">
                                    <div id="data_system" style="">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <h5 class="card-title mt-0">SYSTEM REQUIREMENT</h5>
                                        </div>

                                        <table class="table mb-0" style="margin-top: 10px;margin-left: 10px;margin-right: 10px;">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="border-top-0"></th>
                                                    <th class="border-top-0">Requirement</th>
                                                    <th class="border-top-0">Current</th>
                                                </tr><!--end tr-->
                                            </thead>
                                            <tbody>
                                                <?php 

                                                $phpversion = phpversion(); 
                                                $a = version_compare($phpversion, '7.2', '>=') ?: "false";
                                                $b = version_compare($phpversion, '7.4.3', '<=') ?: "false";
                                                if($a==true && $b==true){
                                                    $requirement = 'color: #36BD47;';
                                                }else{
                                                    $requirement = 'color: #E61515;';
                                                }
                                                

                                                ?>
                                                <tr>                                                        
                                                    <td>PHP Version</td>
                                                    <td><span style="color: #36374c;">7.2 - 7.4.3</span></td>               
                                                    <td><span style="<?php echo $requirement; ?>"><?php  echo phpversion(); ?></span></td>
                                                </tr><!--end tr-->     
                                                <tr>                                                        
                                                    <td>Curl</td>
                                                    <td><span style="color: #36374c;">Enabled</span></td>
                                                    <td><?php if(function_exists('curl_version')==true){echo'<span style="color: #36BD47;">Enabled</span>';}else{echo'<span style="color: #E61515;">Not Enabled</span>';} ?></td>
                                                </tr><!--end tr-->
                                                <tr>                                                        
                                                    <td>Zip</td>
                                                    <td><span style="color: #36374c;">Enabled</span></td>       
                                                    <td><?php if(extension_loaded('zip')==true){echo'<span style="color: #36BD47;">Enabled</span>';}else{echo'<span style="color: #E61515;">Not Enabled</span>';} ?></td>
                                                </tr><!--end tr-->  
                                                <tr>                                                        
                                                    <td>allow_url_fopen</td>
                                                    <td><span style="color: #36374c;">Enabled</span></td>       
                                                    <td><?php if(ini_get("allow_url_fopen")==true){echo'<span style="color: #36BD47;">Enabled</span>';}else{echo'<span style="color: #E61515;">Not Enabled</span>';} ?></td>
                                                </tr><!--end tr-->                                
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>

                        

                        </div><!--end card-->                                                               
                    </div>
                </div>                                                                             
            </div><!--end card-body-->                                
        </div><!--end card-->
    </div><!--end col-->
</div>
