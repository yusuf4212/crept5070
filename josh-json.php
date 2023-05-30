<?php
    function josh_json() {

        global $wpdb;
        // $table = $wpdb->prefix . 'dja_settings';
        // $bank_acc = $wpdb->get_results("SELECT * FROM " . $table . " WHERE type='bank_account'")[0];
        
        // $data = $bank_acc->data;

        $rawdata = '{"inv":"some inv","statusDb":"gagale"}';
        $arraydata = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");
        $decode = json_decode($rawdata);
        $encode = json_encode($arraydata);
        $arraydata2 = json_decode($encode);

        // foreach ($decode as $k => $v) {
        //     $code_bank[] = explode('@', $k);
		//     $name_bank[] = explode('_', $v);
        // }
        global $wpdb;
        $mytable = $wpdb->prefix . 'dja_donate_debug';

        // $dbinsert = $wpdb->insert(
        //     $mytable,
        //     array(
        //         'campaign_id'   => '33323abc',
        //         'invoice_id'    => 'abcdeffghi',
        //         'name'          => 'tes fandi',
        //     ),
        //     array('%s', '%s')
        // );

        // $mytable = $wpdb->prefix. 'josh_faildonate';
		// $datainsert = array(
        //     'campaign_id' 	=> $campaign_id,
        //     'invoice_id' 	=> $invoice_id,
        //     'user_id' 		=> $id_login,
        //     'name' 			=> $name,
        //     'whatsapp' 		=> $whatsapp,
        //     'email' 		=> $email,
        //     'comment' 		=> $comment,
        //     'anonim' 		=> $anonim,
        //     'unique_number' => $unique_number,
        //     'nominal' 		=> $nominal,
        //     'main_donate' 	=> $main_donate,
        //     'info_donate' 	=> $info_donate,
        //     'status' 		=> 0,
        //     'cs_id' 		=> $cs_id,
        //     'payment_trx_id'=> $TransactionId,
        //     'deeplink_url'	=> $deeplink_url,
        //     'payment_gateway'=> $pg_code,
        //     'created_at' 	=> $date_created,
		// 	'utm_source'	=> $utm_source,
		// 	'utm_medium'	=> $utm_medium,
		// 	'utm_campaign'  => $utm_campaign,
		// 	'utm_term'		=> $utm_term,
		// 	'utm_content'   => $utm_content,
		// 	'user_agent'	=> $user_agent,
		// 	'country'		=> $country,
		// 	'city'			=> $city,
		// 	'provider'		=> $provider,
		// 	'operating_system' => $j_os,
		// 	'ip_address'	=> $j_ip,
		// 	'browser'		=> $j_browser,
		// 	'mobdesk'		=> $j_mobdesk
		// );
		// $datainsert2 = json_encode($datainsert);

		// $thisdb = $wpdb->insert(
		// 	$mytable,
		// 	array(
		// 		'error_time'	=> date("Y-m-d H:i:s"),
		// 		'data'			=> $datainsert2
		// 	)
		// );
        
        $json2 = '{"relawan":[{"id":3,"text":"Husna"},{"id":9,"text":"Tisna"}],"program":[],"type":[],"platform":[],"bank":[],"transferDate":{"from":"2023-02-01","end":"2023-02-03"}}';
        $json3 = json_decode($json2);
        
        echo '<pre>';
        // echo 'status db: '; var_dump($thisdb); echo '<br>';
        // var_dump($datainsert2);
        var_dump($json3);
        $relawan = $json3->relawan;
        var_dump($relawan);
        $crelawan = count($relawan);
        var_dump($crelawan);

        $i = 0;
        if ( $crelawan > 0) {
            $sql_text = " WHERE relawan=";
            // foreach ($relawan as $value) {
            //     $sql_text = $sql_text . $value . "'";
                
            //     if( $crelawan < $key) {
            //         $sql_text = $sql_text . ' or ';
            //     }
            // }
            for ($i=0; $i <= $crelawan-1; $i++) { 
                $sql_text = $sql_text . "'" . $relawan[$i]->text . "'";

                if ( $i < $crelawan-1) {
                    $sql_text = $sql_text . ' or ';
                }
            }

            foreach ($json3->transferDate as $key => $value) {
                echo $key . ' => ' . $value;
            }
            echo '<br>';
        }
        echo count((array)$json3->transferDate); echo '<br>';
        echo $sql_text; echo '<br>';
        // echo $json3->relawan[0]->text;
        echo '<br>'.'======= USER INFO ======='.'<br>';
        // var_dump(wp_get_current_user());
        var_dump(wp_upload_dir('2023/01'));
        var_dump(wp_create_nonce( $action = -1 ));
        var_dump(wp_create_nonce( 'file_upload' ));
        var_dump(get_stylesheet_directory_uri());
        echo '</pre>';
        ?>
        <!-- <script>
            let myjson = '<?php //echo '{"inv":"1234","statDb":"false"}' ?>';
            // let myjson = '<?php //echo $rawdata; ?>';
            var clean = myjson.replace("\n","");
            myjson = JSON.parse(clean);

            if (myjson.statDb == 'false') {
                console.log('insert gagal!');
            } else if(myjson.statDb == 'true') {
                console.log('insert berhasil!');
                var nextlink = 'ympb.or.id/' + myjson.inv;
                console.log(nextlink);
            }
        </script> -->

<?php
    }
?>