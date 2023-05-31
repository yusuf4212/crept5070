<?php

    function josh_sql() { ?>

    <?php
        
        global $wpdb;
        $table_name = $wpdb->prefix.'dja_donate';
        $table_name2 = $wpdb->prefix . 'josh_cs_f';

        $get_nama = isset($_POST['name']) ? $_POST['name'] : '';

        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['fol']) ? $_POST['fol'] : '' == "fol") {
            $select = 'invoice_id ';
            $table_now = $table_name2;
            $sql_condition = "";
            
        }

        $sql = "SELECT " . $select .  "FROM " . $table_now . $sql_condition;
        $query = $wpdb->get_results($sql);

        

    ?>

    <style>
        #wpcontent {background:white;}
    </style>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <form class = "change" method="POST">
    <div class="josh-submit">
            <button type="submit" name="fol" value="fol" class="btn-default btn" access="false" style="default" id="submit-button">Change FollowUp 1 Status</button>
        </div>
    </form>

    <div style="margin-top: 20px;">
        <form action="" method="post">
            <button id="thisbutton" type="submit" name="changecs" value="hello">
                Update CS
            </button>
        </form>
    </div>
    <pre>
        <?php 
            var_dump ($_POST); echo '<br>';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo 'iyaa ini post method nya!';
                if ($_POST['changecs'] == 'hello') {
                    echo '<br>'.'var di $_POST nya sesuai!';
                }
            } else {
                echo 'ini bukan post!';
            }
        ?>
    </pre>
    
    <?php
    // $myarray = array(
    //     array(1,1,null),
    //     array(2,9,"tisna"),
    //     array(3,10,"meisya"),
    //     array(4,9,"tisna"),
    //     array(5,11,"fina"),
    //     array(6,9,"tisna"),
    //     array(7,10,"meisya"),
    //     array(8,11,"fina"),
    //     array(9,9,"tisna"),
    //     array(10,3,"husna"),
    //     array(11,10,"meisya")
    // );
    
    // if ($_SERVER['REQUEST_METHOD']=='POST' and $_POST['changecs']=='hello') {
    //     global $wpdb;

    //     $table_cs = $wpdb->prefix.'josh_cs_meta';
    //     echo '<pre>';
    //     foreach($myarray as $k1 => $v1) {
    //         $update1 = $wpdb->update($table_cs, array('j_value' => $v1[1]), array('id' => $v1[0])); //ganti j_value
    //         $update2 = $wpdb->update($table_cs, array('comment' => $v1[2]), array('id' => $v1[0])); //ganti comment

    //         echo 'status update j_value: '.$update1.', dan status update comment: '.$update2.'<br>';
    //         // echo 'ganti j_value :'.$v1[1].', ganti comment : '.$v1[2].', dimana id nya : '.$v1[0].'<br>';
    //         // echo 'k1 nya: '.$k1.', v1 nya: '; var_dump ($v1); echo '<br>';
    //     }
    //     echo '</pre';
    //     echo 'operasi SQL berhasil!' . '<br>' . '<pre>';
    //     var_dump($status1); echo '<br>';
    //     var_dump($status2); echo '</pre>';
    // }

    
    //============== CHANGE FOLLOWUP 1 ===========
    
    // for( $i=0; $i < count($query); $i++ ) {
    //     $invoice = $query[$i] -> invoice_id;
    
    //     $wpdb->update( 'ympb2020_dja_donate',     //table
    //     array(
    //     'f1' 	=> intval('1')),
    //     array('invoice_id' => $invoice));
    // }
    

    // echo "<pre>";
    // var_dump($_POST);
    // echo 'jumlah record : '; echo count($query); echo "<br>";
    // var_dump($query);
    // echo "</pre>";





    //======== FOR CHANGE DATE FORMAT ==========
    // if($query != null) {
        // $max = count($query);
        // var_dump ($max);
        //     echo '<br>';
        // for($i=0; $i<$max; $i++) {
        //     $id = $query[$i] -> id;
        //     $img_confirmation_date_var = $query[$i] -> img_confirmation_date_var;
        //     if($img_confirmation_date_var == null) {
        //         $convert_date = null;
        //     } else {
        //         $date999 = date_create($img_confirmation_date_var);
        //         $convert_date = date_format($date999, "Y-m-d H:i:s");
        //     }
            
            
        //     var_dump ($img_confirmation_date_var);
        //     echo '<br>';
        //     var_dump ($convert_date);
        //     echo '<br>';
        //     echo $id;
        //     echo '<br>';

        //     $wpdb->update(
        //         $table_name, //table
        //         array(
        //             'img_confirmation_date' 	=> $convert_date,
        //         ),
        //         array('id' => $id) //where
        //     );
        // }
        

        // $j = 2;
        // $j--;
        // $qq = $query[$j] -> created_at_var;
        // echo '<pre>';
        // echo 'punya nya ' . $query[$j] -> name . "\n";
        // echo 'dengan id ' . $query[$j] -> id . "\n";
        //var_dump ($qq);
        // $date123 = date_create($qq);
        // $date45 = date_format($date123, "Y-m-d H:i:s");
        // var_dump ($date45);                                 //use this to update SQL
        // $date33 = strtotime($date45);
        //var_dump ($date33);
        // $date_created = date("Y-m-d H:i:s");
        //var_dump($date_created);
        // $datenya = date("j F Y - h:i",strtotime($date45));
        //var_dump($datenya);

        // var_dump(count($query));
        // var_dump($query[0] -> created_at_var);
        // echo '</pre>';

    // } 
    
    // echo '<pre>';
    //     $payment_at_var = $query[0] -> payment_at_var;
    //     if($payment_at_var==null) {
    //         echo 'this is null';
    //     } else {
    //         echo "I don't know";
    //     }
    //     // var_dump($payment_at_var);
        
    //     echo '</pre>';
    
    ?>
    
<?php } ?>