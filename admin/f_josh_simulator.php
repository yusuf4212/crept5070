<?php

function josh_sim() { ?>

    <?php
        global $wpdb;
        $table_name = $wpdb->prefix.'dja_donate';
        $table_name2 = $wpdb->prefix.'dja_donate_exc';
        $table_name3 = $wpdb->prefix.'josh_cs_meta';

        $get_nama = isset($_POST['name']) ? $_POST['name'] : '';
        $get_wa = isset($_POST['whatsapp']) ? $_POST['whatsapp'] : '';

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['donasi']) ? $_POST['donasi'] : '' == "donasi") {
            $table_now = $table_name;
            $sql_condition = " WHERE whatsapp='" . $get_wa . "'";
            $select = '* ';

            $row = "SELECT" . " " . $select . " " .  "FROM" . " " . $table_now . " " . $sql_condition . " ORDER BY `ympb2020_dja_donate`.`id` ASC ";
            $query = $wpdb->get_results($row);

            $cs_id_exist = $query[0]->cs_id;
            $invoice_get = $query[0]->invoice_id;


            //cek sudah pernah donasi atau belum
            if($query==null) {
                $get_cs = $wpdb->get_results("SELECT j_value FROM `ympb2020_josh_cs_meta` WHERE property='last_cs_rotate'");
                echo '<pre>'; echo '=== BELUM PERNAH ORDER ===='; echo '<br>';
                echo 'rotator terakhir adalah : ';echo $get_cs[0]->j_value;
                
                $last_cs_rotate = $get_cs[0]->j_value;              //last cs rotate ready
                $take_cs = 'cs_id_' . $last_cs_rotate; echo '<br>';
                echo 'get cs id : '; echo $take_cs; echo '<br>';
                

                $req1 = "SELECT `j_value` FROM `ympb2020_josh_cs_meta` WHERE property='" . $take_cs . "' ";
                $get_cs2 = $wpdb->get_results($req1);
                echo 'request SQL ='; echo $req1; echo '<br>';
                $user_id_cs = $get_cs2[0]->j_value;
                echo 'kode user si cs : '; echo $user_id_cs; echo '<br>';      //id cs siap

                //ambil data diri cs
                $cs_identity = get_userdata($user_id_cs);
                $cs_completename = $cs_identity->first_name . " " . $cs_identity->last_name; //nama cs readable

                echo 'ini nama cs nya : '; echo $cs_completename; echo '<br>';
                echo 'ini email cs nya : '; echo $cs_identity->user_email; echo '<br>';


                //ubah nilai last_cs_rotate
                if($last_cs_rotate < 10) {
                    $last_cs_rotate++;
                } elseif ($last_cs_rotate==10) {
                    $last_cs_rotate = 1;
                } else {
                    $last_cs_rotate = 1;

                    $error_datetime = date('Y-m-d - H:i:s');
                    $wpdb->update(
                        'ympb2020_josh_cs_meta', //table
                    array(
                        'j_value' 	=> $error_datetime,   //insert date of error
                    ),
                    array('property' => 'cs_rotate_error') //where

                    //HARUSNYA KIRIM EMAIL
                    );
                }

                $update1 = $wpdb->update(
                    'ympb2020_josh_cs_meta', //table
                array(
                    'j_value' 	=> $last_cs_rotate,   //insert
                ),
                array('property' => 'last_cs_rotate') //where
                );

                echo 'status update last rotator : '; echo $update1; echo '<br>';
                echo 'nilai last rotate : '; echo $last_cs_rotate; echo '<br>';

                
                

                // BELUM PERNAH ORDER SEBELUMNYA!
            } else {
                echo '==== SUDAH PERNAH ORDER! ===='; echo'<br>';
                echo 'ini dia invoice nya : '; echo $invoice_get; echo '<br>';
                echo 'ini dia cs yg lama : ';
                echo $cs_id_exist; echo '<br>';
            }

            echo '</pre>';

        } elseif($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['all']) ? $_POST['all'] : '' == "all") {
            $table_now = $table_name;
            $sql_condition = ' ';
            $select = ' * ';

            $row = "SELECT" . " " . $select . " " .  "FROM" . " " . $table_now . " " . $sql_condition . " ORDER BY `ympb2020_dja_donate`.`id` ASC ";
            $query = $wpdb->get_results($row);
        }

        
    ?>
    <style>
        #wpcontent {background:white;}.joshclass {text-align: center;}.table {width: 100%;border-collapse: collapse;border: 2px solid black;}th {border: 2px solid black;padding: 5px;text-align: center;}td {border: 2px solid black;padding: 5px 5px 5px 10px;text-align: center;}form {display: flex;}.form1 {padding: 20px;}
    </style>

    <pre>
        <?php 
        $home = home_url();
        echo 'url home adalah : '; echo $home; echo '<br>';
        echo 'url saat ini adalah : '; echo get_the_ID(); echo '<br>';
        echo '==Post Query=='; echo '<br>';
        print_r($_POST); echo '<br>';
        echo '==this is row for sql=='; echo '<br>';
        echo $row; ?>
    </pre>

    <div class="joshclass";><h2>Simulator Button FollowUp</h2></div>


    <p style="text-align:center; margin-bottom:20px;"><span style="text-align:center;display:block;margin-top:20px; margin-bottom:5px"><a href="https://ympb.or.id/f/?inv=<?php echo $invoice_get?>" style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;color:#fff;text-decoration:none;line-height:2em;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background-color:#22cd3f;margin:0;border-color:#22cd3f;border-style:solid;border-width:10px 20px" target="_blank">Follow Up via WhatsApp</a></span>*silahkan digunakan, laporkan jika ada error</p>

    <div class="joshclass";><h2>Simulator CS Rotator</h2></div>
    
    <h2>Donasi Masuk</h2>
    <form class = "form0" method="POST">
    <div class="josh-nama">
            <label for="name" class="formbuilder-text-label">Nama<span class="formbuilder-required">*</span></label>
            <input type="text" placeholder="Andri" class="form-control" name="name" access="false" id="name" required="required" aria-required="true">
        </div>
        <div class="josh-whatsapp">
            <label for="whatsapp" class="formbuilder-number-label">No WA</label>
            <input type="number" class="form-control" name="whatsapp" access="false" id="whatsapp" required="required">
        </div>
        <div class="josh-submit">
            <button type="submit" name="donasi" value="donasi" class="btn-default btn" access="false" style="default" id="submit-button">Donate</button>
        </div>
    </form>

    <form class = "form1" method="POST">
    <div class="josh-submit">
            <button type="submit" name="all" value="all" class="btn-default btn" access="false" style="default" id="submit-button">Show All</button>
        </div>
    </form>

    <table class="table">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>WhatsApp</th>
            <th>Created Date</th>
            <th>CS</th>
        </tr>

        <?php 

        $sum_data = count($query);

        if($sum_data>5) {
            $max = 5;
        } else { $max = $sum_data;}

        for($i=1;$i<=$max;$i++) :
        ?>

        <tr>
        <td><?php echo $i; ?></td>
            
        <td><?php echo $query[$i-1]->name; ?></td>
        <td><?php echo $query[$i-1]->whatsapp; ?></td>
        <td><?php echo $query[$i-1]->created_at; ?></td>
        <td><?php echo $query[$i-1]->cs_id; ?></td>
        </tr>

        <?php endfor; ?>
    </table>
        
<?php } ?>