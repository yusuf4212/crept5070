<?php

class donasiaja_readtime {

	public function time_donation($time) { 

		global $wpdb;

		date_default_timezone_set('Asia/jakarta');

		$timestamp = strtotime($time);	
	   
	    $strTime = array("detik", "menit", "jam", "hari", "bulan", "tahun");
	    $length = array("60","60","24","30","12","10");

	    $currentTime = time();
	    if($currentTime >= $timestamp) {
	    	// if($utc_status==1){
	    	// 	$diff     = time()- $timestamp + ((60*60)*($utc_value));
	    	// }else{
	    		$diff     = time() - $timestamp;// + ((60*60)*7);
	    	// }
			
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
				$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . " yang lalu ";
	    }else{
	    	return '';
	    }
	}


}


class humanReadtime {

	public function dja_human_time($time) { 

	    global $wpdb;

		date_default_timezone_set('Asia/jakarta');

		$timestamp = strtotime($time);	
	   
	    $strTime = array("detik", "menit", "jam", "hari", "bulan", "tahun");
	    $length = array("60","60","24","30","12","10");

	    $currentTime = time();
	    if($currentTime >= $timestamp) {
	    	$diff     = time() - $timestamp;
		
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
				$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . " yang lalu ";
	    }else{
	    	return '';
	    }
	}

}


class TimeInterval {
    const EpochOneDay = 18000;		//Interval 5 Jam
     
    public function jsh_timeInterval($time) {
        date_default_timezone_set('Asia/jakarta');
        $cur_time = time();
        $req_time = strtotime($time);

        $calc = $cur_time - $req_time;
        if ( $calc > self::EpochOneDay ) {
            // SUDAH 5 JAM
            return true;
        } else {
            // BELUM 5 JAM
            return false;
        }
        // ERROR KASIH TRUE
        return true;
    }
}


function redirect_logged_in_user() {

    $id_login = wp_get_current_user()->ID;

    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="login_setting" or type="page_login" ORDER BY id ASC');
    $login_setting 	= $query_settings[0]->data;
    $page_login 	= $query_settings[1]->data;

    wp_redirect( home_url().'/'.$page_login.'/' );

}



function angka_terbilang($x) {
    $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
  
    if ($x < 12){
      return " " . $angka[$x];
    }elseif ($x < 20){
      return angka_terbilang($x - 10) . " belas";
    }elseif ($x < 100){
      return angka_terbilang($x / 10) . " puluh" . angka_terbilang($x % 10);
    }elseif ($x < 200){
      return "seratus" . angka_terbilang($x - 100);
    }elseif ($x < 1000){
      return angka_terbilang($x / 100) . " ratus" . angka_terbilang($x % 100);
    }elseif ($x < 2000){
      return "seribu" . angka_terbilang($x - 1000);
    }elseif ($x < 1000000){
      return angka_terbilang($x / 1000) . " ribu" . angka_terbilang($x % 1000);
    }elseif ($x < 1000000000){
      return angka_terbilang($x / 1000000) . " juta" . angka_terbilang($x % 1000000);
    }elseif ($x < 1000000000000){
      return angka_terbilang($x / 1000000000) . " milyar" . angka_terbilang($x % 1000000000);
    }
  }
  
function donasiaja_url_handler_() {
	global $wpdb;
	global $pagenow;

	$table_settings = $wpdb->prefix . 'dja_settings';
	$table_donate = $wpdb->prefix . 'dja_donate';
	$table_campaign = $wpdb->prefix . 'dja_campaign';
	$table_aff_submit = $wpdb->prefix . 'dja_aff_submit';
	$table_aff_code = $wpdb->prefix . 'dja_aff_code';

	$query = "SELECT data
	FROM $table_settings
	WHERE type='login_setting' or type='login_text' or type='register_setting' or type='register_text' or type='page_login' or type='page_register' or type='page_donate' or type='page_typ'
	ORDER BY id ASC";

	$rows = $wpdb->get_results($query);

	$login_setting 		= $rows[0]->data;
    $login_text 		= $rows[1]->data;
    $register_setting 	= $rows[2]->data;
    $register_text 		= $rows[3]->data;
    $page_login 		= $rows[4]->data;
    $page_register 		= $rows[5]->data;
    $page_donate		= $rows[6]->data;
    $page_typ 			= $rows[7]->data;

	/**
	 * @todo Find out this..
	 */
	if($login_setting=='1'){
		if( $pagenow == 'wp-login.php' ){
			redirect_logged_in_user();
		}
	}

	$host = $_SERVER['HTTP_HOST'];

	$request_uri = parse_url($_SERVER['REQUEST_URI']);

	$path = $request_uri['path'];
	$query = (isset($request_uri['query'])) ? $request_uri['query'] : null;

	// remove the last "/" if there any
	$path = (str_ends_with($path, '/')) ? substr_replace($path, '', -1, 1) : $path;

	$path = explode('/', $path);

	// remove the first array element
	if(count($path) >1) {
		array_shift($path);
	}

	// detect if run in local or dummy web
	if($host === 'localhost') {
		array_shift($path);
	}

	echo '<pre>'; var_dump($path); echo '</pre>';
	if(isset($query)) {
		echo '<pre>'; var_dump($query); echo '</pre>';
	}
}
donasiaja_url_handler_();

function donasiaja_url_handler() {

	global $wpdb;
	global $pagenow;

	// echo '<pre>'; var_dump($_SERVER); echo '</pre>';

	$table_name = $wpdb->prefix . "dja_settings";
	$table_name2 = $wpdb->prefix . "dja_donate";
	$table_name3 = $wpdb->prefix . "dja_campaign";
	$table_name4 	= $wpdb->prefix . "dja_aff_submit";
    $table_name5 	= $wpdb->prefix . "dja_aff_code";

    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="login_setting" or type="login_text" or type="register_setting" or type="register_text" or type="page_login" or type="page_register" or type="page_donate" or type="page_typ" ORDER BY id ASC');
    $login_setting 		= $query_settings[0]->data;
    $login_text 		= $query_settings[1]->data;
    $register_setting 	= $query_settings[2]->data;
    $register_text 		= $query_settings[3]->data;
    $page_login 		= $query_settings[4]->data;
    $page_register 		= $query_settings[5]->data;
    $page_donate		= $query_settings[6]->data;
    $page_typ 			= $query_settings[7]->data;
    
    if($login_setting=='1'){
		if( $pagenow == 'wp-login.php' ){
			redirect_logged_in_user();
		}
	}

	$link1 = "{$_SERVER['REQUEST_URI']}";
	$host = explode('/',$link1);

	// echo '<pre>';
	// echo '<br>';
	// echo $host[0];		//null
	// echo '<br>';
	// echo $host[1];		//campaign | campaign | ekuitansi
	// echo '<br>';
	// echo $host[2];		//slug_campaign	| slug_campaign | inv_id
	// echo '<br>';
	// echo $host[3];		//donate or invoice | |
	// echo '<br>';
	// echo $host[4];		//inv_id
	// echo '<br>';
	// echo '<br>';
	// echo '<br>';
	// echo 'ni dia';
	// var_dump($host);
	// echo '</pre>';

	

	$web = get_site_url();
	$donasi_id = '';

	if(isset($host[1])){
		// echo '<pre>'; var_dump($web); echo '</pre>';
		if (strpos($web, 'localhost') !== false ) { // klo localhost atau ip
		    
		    $code1 = $host[2];
		    $link_code = $host[2];

		    if(isset($host[3])){
		    	$donasi_id = $host[3];
		    	$affcode = '';

		    	if (strpos($host[3], '?') !== false ) {
					$slugnya = explode('?',$host[3]);
					$donasi_id = $slugnya[0];
					$affcode = $slugnya[1];
				}

		    }

		    if(isset($host[4])){

		    	$action_id = $host[4];
		    	$_getid = '';
		    	if (strpos($action_id, '?') !== false ) {
		    		$act_slug = explode('?',$host[4]);
				    $action_id = $act_slug[0];
				    $affcode = $act_slug[1];
					$_g = $act_slug[1];
					if (strpos($_g, '_') !== false ) {
						$_gs = explode('_',$_g);
					    $_getid = $_gs[0];
					    $_getm = urldecode($_gs[1]);
					}
				    
		    	}

				if($link_code=='preview' and $action_id==$page_donate){ // 'donate-now'
		    		
		    		$nominal = null;
		    		if(isset($host[5])){
		    			$nominal = $host[5];
		    			if (is_numeric($nominal)) {
		    				$nominal = $nominal;
		    			}else{
		    				$nominal = null;
		    			}
		    		}
					
					require_once(ROOTDIR_DNA . 'donasiaja-form4.php');
					die;
				}
		    	if($link_code=='campaign' and $action_id==$page_donate){ // 'donate-now'
		    		
		    		$nominal = null;
		    		if(isset($host[5])){
		    			$nominal = $host[5];
		    			if (is_numeric($nominal)) {
		    				$nominal = $nominal;
		    			}else{
		    				$nominal = null;
		    			}
		    		}
					
					require_once(ROOTDIR_DNA . 'donasiaja-form4.php');
					die;
				}

		    	if($link_code=='campaign' and $action_id==$page_typ){
		    		
		    		$invoice_id = null;
                    
		    		if(isset($host[5])){

                        $invoice_id = $host[5];

		    		}
					
					require_once(ROOTDIR_DNA . 'donasiaja-typ.php');
					die;
				}

			}
			if($link_code=='preview'){
				require_once(ROOTDIR_DNA . 'donasiaja-campaign.php');
				die;
			}
			if($link_code=='campaign'){
				require_once(ROOTDIR_DNA . 'donasiaja-campaign.php');
				die;
			}
			if($link_code=='search_campaign'){
				require_once(ROOTDIR_DNA . 'donasiaja-search.php');
				die;
			}
			if($link_code=='profile'){
				require_once(ROOTDIR_DNA . 'donasiaja-profile.php');
				die;
			}
			if($link_code==$page_login){
				require_once(ROOTDIR_DNA . 'donasiaja-login.php');
				die;
			}
			if($link_code==$page_register){
				require_once(ROOTDIR_DNA . 'donasiaja-register.php');
				die;
			}
			if($link_code=='changepass'){
				require_once(ROOTDIR_DNA . 'donasiaja-changepass.php');
				die;
			}
			if($link_code=='resetpass'){
				require_once(ROOTDIR_DNA . 'donasiaja-resetpass.php');
				die;
			}
			if($link_code=='ekuitansi'){
				require_once(ROOTDIR_DNA . 'admin/f_print_kuitansi.php');
		  		exit();
				die;
			}

			
			/*
			if($link_code=='send-tripay'){



			}
			*/

			/*
			if($link_code=='send-midtrans'){


			}
			*/

		}else{

			$code1 = $host[1];
			$link_code = $host[1];
			if(isset($host[2])){
		    	$donasi_id = $host[2];
		    	$affcode = '';

		    	if (strpos($host[2], '?') !== false ) {
					$slugnya = explode('?',$host[2]);
					$donasi_id = $slugnya[0];
					$affcode = $slugnya[1];
				}
		    }

		    if(isset($host[3])){

		    	$action_id = $host[3];
		    	$_getid = '';
		    	if (strpos($action_id, '?') !== false ) {
		    		$act_slug = explode('?',$host[3]);
				    $action_id = $act_slug[0];
				    $affcode = $act_slug[1];
				    $_g = $act_slug[1];
				    if (strpos($_g, '_') !== false ) {
						$_gs = explode('_',$_g);
					    $_getid = $_gs[0];
					    $_getm = urldecode($_gs[1]);
					}
		    	}

				if($link_code=='preview' and $action_id==$page_donate){ // 'donate-now'
		    		
		    		$nominal = null;
		    		if(isset($host[4])){
		    			$nominal = $host[4];
		    			if (is_numeric($nominal)) {
		    				$nominal = $nominal;
		    			}else{
		    				$nominal = null;
		    			}
		    		}
					
					require_once(ROOTDIR_DNA . 'donasiaja-form4.php');
					die;
				}
		    	if($link_code=='campaign' and $action_id==$page_donate){ // 'donate-now'
		    		$track_mode = 'form';
		    		$nominal = null;
		    		if(isset($host[4])){
		    			$nominal = $host[4];
		    			if (is_numeric($nominal)) {
		    				$nominal = $nominal;
		    			}else{
		    				$nominal = null;
		    			}
		    		}
					
					require_once(ROOTDIR_DNA . 'donasiaja-form4.php');
					die;
				}
				if($link_code=='josh' and $action_id==$page_donate){ // 'donate-now'
		    		$track_mode = 'form';
		    		$nominal = null;
		    		if(isset($host[4])){
		    			$nominal = $host[4];
		    			if (is_numeric($nominal)) {
		    				$nominal = $nominal;
		    			}else{
		    				$nominal = null;
		    			}
		    		}
					
					require_once(ROOTDIR_DNA . 'donasiaja-form4.php');
					die;
				}

		    	if($link_code=='campaign' and $action_id==$page_typ){
		    		$track_mode = 'thankyoupage';
		    		$invoice_id = null;
                    // echo '<br>'; var_dump($host[4]); echo '<br>';
		    		if(isset($host[4])){
                        if( strpos( $host[4], '?') !== false ) {
                            $hostExp = explode( '?', $host[4] );
                            $invoice_id = $hostExp[0];
                        } else {
                            $invoice_id = $host[4];
                        }
		    		}
					
					require_once(ROOTDIR_DNA . 'donasiaja-typ.php');
					die;
				}

				if($link_code=='josh' and $action_id==$page_typ){
		    		$track_mode = 'thankyoupage';
		    		$invoice_id = null;
		    		if(isset($host[4])){
                        if( strpos( $host[4], '?') !== false ) {
                            $hostExp = explode( '?', $host[4] );
                            $invoice_id = $hostExp[0];
                        } else {
                            $invoice_id = $host[4];
                        }
		    		}
					
					// require_once(ROOTDIR_DNA . 'josh-typ.php');
					require_once(ROOTDIR_DNA . 'donasiaja-typ.php');
					die;
				}

			}

			$realpath = explode('?',$link_code);
			
			$jlink = $realpath[0];
			// var_dump($jlink);
			// echo '</pre>';
			$jlink1 = 'rumah-tahfizh'; $jlink2 = 'rumah-tahfidz'; $jlink3 = 'wakaf-quran';
			$jlink4 = 'sedekah-subuh'; $jlink5 = 'jumat-berkah'; $jlink6 = 'tanggap-cianjur'; $jlink7 = 'bantuan-lebak'; $jlink8 = 'zakat'; $jlink9 = 'fidyah';

			if( $jlink == $jlink1 || $jlink == $jlink2 || $jlink == $jlink3 || $jlink == $jlink4 || $jlink == $jlink5 || $jlink == $jlink6 || $jlink == $jlink7 || $jlink == $jlink8 || $jlink == $jlink9){
				$track_mode = 'landingpage';
				require_once(ROOTDIR_DNA . 'josh-redirect.php');
				die;
			}
			if($jlink=='webhook') {
				require_once ROOTDIR_DNA . 'josh-waba-endpoint.php';
				die;
			}
			if($link_code=='josh') { 					//this is only for development
				$track_mode = 'landingpage';
				require_once(ROOTDIR_DNA . 'donasiaja-campaign.php');
				die;
			}
			if($link_code=='f'){
				require_once(ROOTDIR_DNA . 'josh-followup.php');
				die;
			}
			if($link_code=='_f'){
				require_once(ROOTDIR_DNA . 'josh-followup-2.php');
				die;
			}
			if($link_code == 'slip-input') {
				require_once(ROOTDIR_DNA . 'josh-input-slip.php');
				die;
			}
			if($link_code=='s'){
				require_once(ROOTDIR_DNA . 'josh-slip-view.php');
				die;
			}
			if($realpath[0]=='__glogin_crm') {
				require_once(ROOTDIR_DNA . 'jh-login-google.php');
                $direct_to = 'crm';
				die;
			}
			// if($link_code=='josh-deb') {
			// 	require_once(ROOTDIR_DNA . 'josh-debug.php');
			// 	die;
			// }
			// if($link_code=='josh-capi') {
			// 	new Send_API;
			// 	die;
			// }
			if($link_code=='url-builder') {
				require_once ROOTDIR_DNA . 'josh-utm-builder.php';
				die;
			}
			// if($link_code=='donors-table') {
			// 	require_once ROOTDIR_DNA . 'josh-donors-table.php';
			// 	die;
			// }
			// if($link_code=='correcting-slip') {
			// 	require_once ROOTDIR_DNA . 'correcting-table-slip.php';
			// 	die;
			// }
			// if($link_code=='test-waba') {
			// 	require_once ROOTDIR_DNA . 'josh-waba-test.php';
			// 	die;
			// }
            // if($link_code=='correcting-capi') {
            //     require_once ROOTDIR_DNA . 'correcting_capi_wait.php';
            //     die;
            // }
			// if($link_code=='repeatsts') {
			// 	require_once ROOTDIR_DNA . 'josh_repeatsts.php';
			// 	die;
			// }
			if($link_code=='input-leads') {
				// require_once ROOTDIR_DNA . 'josh-page/josh-input-leads.php';
				wp_die('<h2>Ooops..</h2><br><p>Halo! Demi terciptanya data yang <i>terintegrasi</i> dengan baik, leads saat ini langsung di Dashboard CRM ya!</p><p>Terimakasih!! :)</p><p><i>Mengintegrasikan data dari sumber yang banyak itu tidak mudah lho :) terimakasih atas pengertiannya.</i></p><br><br>- Developer DFR YMPB.');
			}
			if($link_code=='preview'){
				require_once(ROOTDIR_DNA . 'donasiaja-campaign.php');
				die;
			}
			if($link_code=='campaign'){
				$track_mode = 'landingpage';
				require_once(ROOTDIR_DNA . 'donasiaja-campaign.php');
				die;
			}
			if($link_code=='search_campaign'){
				require_once(ROOTDIR_DNA . 'donasiaja-search.php');
				die;
			}
			if($link_code=='profile'){
				require_once(ROOTDIR_DNA . 'donasiaja-profile.php');
				die;
			}
			if($link_code==$page_login){
				require_once(ROOTDIR_DNA . 'donasiaja-login.php');
				die;
			}
			if($link_code==$page_register){
				require_once(ROOTDIR_DNA . 'donasiaja-register.php');
				die;
			}
			if($link_code=='changepass'){
				require_once(ROOTDIR_DNA . 'donasiaja-changepass.php');
				die;
			}
			if($link_code=='resetpass'){
				require_once(ROOTDIR_DNA . 'donasiaja-resetpass.php');
				die;
			}
			if($link_code=='ekuitansi'){
				require_once(ROOTDIR_DNA . 'admin/f_print_kuitansi.php');
		  		exit();
				die;
			}
			if($link_code=='notify-ipaymu'){

				//notify pembayaran dengan iPaymu
				// {
				// 	"trx_id":121212,
				//     "sid": "C0E6D95C-05D3-4039-9105-333333",
				//     "status":"berhasil",
				//     "via":"Metode pembayaran",
				//     "no_rekening_deposit":jika pembeli memilih opsi pembayaran Transfer Bank (non-member iPaymu)
				// }

				if($_REQUEST['status'] == 'berhasil') {

					$payment_trx_id = $_REQUEST['trx_id'];

					$wpdb->update(
			            $table_name2, //table
			            array(
				            'status' 	 => 1,
				            'payment_at' => date("Y-m-d H:i:s"),
				            'process_by' => 'ipaymu'

				        ),
			            array('payment_trx_id' => $payment_trx_id), //where
			            array('%s'), //data format
			            array('%s') //where format
			        );


			        // GENERAL Settings
				    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="currency" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_message2" or type="wanotif_on" or type="email_on" or type="email_success_message" ORDER BY id ASC');
				    $currency 		  = $query_settings[0]->data;
				    $wanotif_url 	  = $query_settings[1]->data;
				    $wanotif_apikey   = $query_settings[2]->data;
				    $wanotif_message2 = $query_settings[3]->data;
				    $wanotif_on 	  = $query_settings[4]->data;
				    $email_on 	  	  = $query_settings[5]->data;
				    $email_success_message = $query_settings[6]->data;

					// GET DATA DONASI
				    $row = $wpdb->get_results('SELECT a.*, b.title from '.$table_name2.' a 
				    left JOIN '.$table_name3.' b ON b.campaign_id = a.campaign_id where a.payment_trx_id="'.$payment_trx_id.'" ')[0];
				    
				    $data_field = array();
				    $data_field[ '{name}' ] 	= $row->name;
				    $data_field[ '{whatsapp}' ] = $row->whatsapp;
				    $data_field[ '{email}' ] 	= $row->email;
				    $data_field[ '{comment}' ] 	= $row->comment;
				    $data_field[ '{payment_number}' ] 	= $row->payment_number;
				    $data_field[ '{payment_code}' ] 	= paymentCode($row->payment_code);
				    $data_field[ '{payment_account}' ] 	= $row->payment_account;
				    $data_field[ '{campaign_title}' ] 	= $row->title;
				    $data_field[ '{invoice_id}' ] 		= $row->invoice_id;
				    $data_field[ '{date}' ] 			= $datenya = date("j F Y - h:i",strtotime($row->created_at));
				    $data_field[ '{link_ekuitansi}' ] 	= home_url().'/ekuitansi/'.$row->invoice_id;
				    
				    if($currency=='IDR'){
				    	$data_field[ '{total}' ] 	= 'Rp '.number_format($row->nominal,0,",",".");
				    }else{
				    	$data_field[ '{total}' ] 	= $row->nominal;
				    }

				    $query_donation = $wpdb->get_results("SELECT b.user_id as fundraiser_id FROM $table_name4 a
				    LEFT JOIN $table_name5 b ON b.id = a.affcode_id 
				    where a.donate_id='$row->id' ORDER BY a.id DESC ")[0];

				    if($query_donation->fundraiser_id!=''){
				        $user_info = get_userdata($query_donation->fundraiser_id);
				        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
				        $data_field[ '{fundraiser}' ] = $fundraiser_name;
				    }else{
				    	$data_field[ '{fundraiser}' ] = '-';
				    }


				    // send email
				    if($email_on=='1'){

				    	$email_success_message = json_decode($email_success_message);

						foreach($email_success_message as $key => $value) {

				            $message = $value->message;
				            $message = strtr($message, $data_field);
							$message = str_replace('<p>linebreak</p>', '', $message);
							$message = str_replace('linebreak', '', $message);
							
				            $subject = $value->subject;
				            $subject = strtr($subject, $data_field);
							$subject = str_replace("'","",$subject);
							
				            $emailnya = $value->email;
							
				            $emailnyacc = $value->emailcc;
				            $emailnyabcc = $value->emailbcc;

				            $headers[] = 'Content-Type: text/html; charset=UTF-8';

				            $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';

							if (strpos($emailnyacc, ',') !== false ) {
								$array_emailcc  = (explode(",", $emailnyacc));
								foreach ($array_emailcc as $values){
									$email_cc = strtr($values, $data_field);
									$headers[] = 'Cc: '.$email_cc;
								}
							}else{
								$emailnyacc = strtr($emailnyacc, $data_field);
								$headers[] = 'Cc: '.$emailnyacc;
							}

							if (strpos($emailnyabcc, ',') !== false ) {
								$array_emailbcc  = (explode(",", $emailnyabcc));
								foreach ($array_emailbcc as $values){
									$email_bcc = strtr($values, $data_field);
									$headers[] = 'Bcc: '.$email_bcc;
								}
							}else{
								$emailnyabcc = strtr($emailnyabcc, $data_field);
								$headers[] = 'Bcc: '.$emailnyabcc;
							}
							
				 			
							if($emailnya!=''){
								$emailnya = strtr($emailnya, $data_field);
								wp_mail( $emailnya, $subject, $body, $headers );
							}
								
						}

					}

				    // send wanotif
				    $whatsapp = $row->whatsapp;
					if($wanotif_apikey!='' && $wanotif_on=='1'){
						// SET PHONE
						if($whatsapp!=''){

							$phone = djaPhoneFormat($whatsapp);
							$url = $wanotif_url.'/send';

							$messagenya = $wanotif_message2;
							$messagenya = strtr($messagenya, $data_field);

							$curl = curl_init();
							curl_setopt($curl, CURLOPT_URL, $url);
							curl_setopt($curl, CURLOPT_HEADER, 0);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
							curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($curl, CURLOPT_TIMEOUT,30);
							curl_setopt($curl, CURLOPT_POST, 1);
							curl_setopt($curl, CURLOPT_POSTFIELDS, array(
							    'Apikey'    => $wanotif_apikey,
							    'Phone'     => $phone,
							    'Message'   => $messagenya,
							));
							$response = curl_exec($curl);
							curl_close($curl);

						}

					}

			        echo 'Success Updated Invoice ID: '.$row->invoice_id.' in '.home_url($wp->request);
			        die;

				}
			}
			if($link_code=='callback_tripay' || $link_code=='callback_tripay_sandbox'){
            
            	// GENERAL Settings
				if($link_code=='callback_tripay'){
					$query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="tripay_privatekey" ORDER BY id ASC');
			    	$tripay_privatekey 		  = $query_settings[0]->data;
				}else{
					$query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="tripay_privatekey_sandbox" ORDER BY id ASC');
			    	$tripay_privatekey 		  = $query_settings[0]->data;
				}

                
                // ambil data JSON
                $json = file_get_contents("php://input");

                // ambil callback signature
                $callbackSignature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';

                // generate signature untuk dicocokkan dengan X-Callback-Signature
                $signature = hash_hmac('sha256', $json, $tripay_privatekey);

                // validasi signature
                if( $callbackSignature !== $signature ) {
                    exit("Invalid Signature"); // signature tidak valid, hentikan proses
                }

                $data = json_decode($json);
                $event = $_SERVER['HTTP_X_CALLBACK_EVENT'];
                
                if( $event == 'payment_status' )
				{

					$check_reference = $wpdb->get_results('SELECT id from '.$table_name2.' where payment_trx_id="'.$data->reference.'" and invoice_id="'.$data->merchant_ref.'"');

    				if($check_reference==null){
    					echo 'Payment reference ID (No referensi transaksi) and Invoice ID tidak ditemukan atau tidak Match';
    					die;
    				}

                	if( $data->status == 'PAID') // PAID, UNPAID, EXPIREF, FAILED
                    {
                    	$payment_trx_id = $data->reference;
                    	$invoice_id = $data->merchant_ref;
                    	// check dl apakah ada
                    	$check_payment = $wpdb->get_results('SELECT * from '.$table_name2.' where payment_trx_id="'.$payment_trx_id.'" and invoice_id="'.$invoice_id.'" and status="0" ');

						if($check_payment!=null){

							$wpdb->update(
	                            $table_name2, //table
	                            array(
	                                'status' 	 => 1,
	                                'payment_at' => date("Y-m-d H:i:s"),
	                                'process_by' => 'tripay'

	                            ),
	                            array('payment_trx_id' => $payment_trx_id, 'invoice_id' => $invoice_id), //where
	                            array('%s'), //data format
	                            array('%s') //where format
	                        );


	                        // GENERAL Settings
	                        $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="currency" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_message2" or type="wanotif_on" or type="email_on" or type="email_success_message" ORDER BY id ASC');
	                        $currency 		  = $query_settings[0]->data;
	                        $wanotif_url 	  = $query_settings[1]->data;
	                        $wanotif_apikey   = $query_settings[2]->data;
	                        $wanotif_message2 = $query_settings[3]->data;
	                        $wanotif_on 	  = $query_settings[4]->data;
	                        $email_on 		  = $query_settings[5]->data;
						    $email_success_message = $query_settings[6]->data;

	                        // GET DATA DONASI
	                        $row = $wpdb->get_results('SELECT a.*, b.title from '.$table_name2.' a 
	                        left JOIN '.$table_name3.' b ON b.campaign_id = a.campaign_id where a.payment_trx_id="'.$payment_trx_id.'" ')[0];

	                        $data_field = array();
	                        $data_field[ '{name}' ] 	= $row->name;
	                        $data_field[ '{whatsapp}' ] = $row->whatsapp;
	                        $data_field[ '{email}' ] 	= $row->email;
	                        $data_field[ '{comment}' ] 	= $row->comment;
	                        $data_field[ '{payment_number}' ] 	= $row->payment_number;
	                        $data_field[ '{payment_code}' ] 	= paymentCode($row->payment_code);
	                        $data_field[ '{payment_account}' ] 	= $row->payment_account;
	                        $data_field[ '{campaign_title}' ] 	= $row->title;
	                        $data_field[ '{invoice_id}' ] 		= $row->invoice_id;
						    $data_field[ '{date}' ] 			= $datenya = date("j F Y - h:i",strtotime($row->created_at));
						    $data_field[ '{link_ekuitansi}' ] 	= home_url().'/ekuitansi/'.$row->invoice_id;

	                        if($currency=='IDR'){
	                            $data_field[ '{total}' ] 	= 'Rp '.number_format($row->nominal,0,",",".");
	                        }else{
	                            $data_field[ '{total}' ] 	= $row->nominal;
	                        }

	                        $query_donation = $wpdb->get_results("SELECT b.user_id as fundraiser_id FROM $table_name4 a
						    LEFT JOIN $table_name5 b ON b.id = a.affcode_id 
						    where a.donate_id='$row->id' ORDER BY a.id DESC ")[0];

						    if($query_donation->fundraiser_id!=''){
						        $user_info = get_userdata($query_donation->fundraiser_id);
						        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
						        $data_field[ '{fundraiser}' ] = $fundraiser_name;
						    }else{
						    	$data_field[ '{fundraiser}' ] = '-';
						    }

	                        // send email
						    if($email_on=='1'){

						    	$email_success_message = json_decode($email_success_message);

								foreach($email_success_message as $key => $value) {

						            $message = $value->message;
						            $message = strtr($message, $data_field);
									$message = str_replace('<p>linebreak</p>', '', $message);
									$message = str_replace('linebreak', '', $message);
									
						            $subject = $value->subject;
						            $subject = strtr($subject, $data_field);
									$subject = str_replace("'","",$subject);
									
						            $emailnya = $value->email;
									
						            $emailnyacc = $value->emailcc;
						            $emailnyabcc = $value->emailbcc;

						            $headers[] = 'Content-Type: text/html; charset=UTF-8';

						            $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';

									if (strpos($emailnyacc, ',') !== false ) {
										$array_emailcc  = (explode(",", $emailnyacc));
										foreach ($array_emailcc as $values){
											$email_cc = strtr($values, $data_field);
											$headers[] = 'Cc: '.$email_cc;
										}
									}else{
										$emailnyacc = strtr($emailnyacc, $data_field);
										$headers[] = 'Cc: '.$emailnyacc;
									}

									if (strpos($emailnyabcc, ',') !== false ) {
										$array_emailbcc  = (explode(",", $emailnyabcc));
										foreach ($array_emailbcc as $values){
											$email_bcc = strtr($values, $data_field);
											$headers[] = 'Bcc: '.$email_bcc;
										}
									}else{
										$emailnyabcc = strtr($emailnyabcc, $data_field);
										$headers[] = 'Bcc: '.$emailnyabcc;
									}
									
						 			
									if($emailnya!=''){
										$emailnya = strtr($emailnya, $data_field);
										wp_mail( $emailnya, $subject, $body, $headers );
									}
										
								}

							}

	                        // send wanotif
	                        $whatsapp = $row->whatsapp;
	                        if($wanotif_apikey!='' && $wanotif_on=='1'){
	                            // SET PHONE
	                            if($whatsapp!=''){

	                                $phone = djaPhoneFormat($whatsapp);
	                                $url = $wanotif_url.'/send';

	                                $messagenya = $wanotif_message2;
	                                $messagenya = strtr($messagenya, $data_field);

	                                $curl = curl_init();
	                                curl_setopt($curl, CURLOPT_URL, $url);
	                                curl_setopt($curl, CURLOPT_HEADER, 0);
	                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	                                curl_setopt($curl, CURLOPT_TIMEOUT,30);
	                                curl_setopt($curl, CURLOPT_POST, 1);
	                                curl_setopt($curl, CURLOPT_POSTFIELDS, array(
	                                    'Apikey'    => $wanotif_apikey,
	                                    'Phone'     => $phone,
	                                    'Message'   => $messagenya,
	                                ));
	                                $response = curl_exec($curl);
	                                curl_close($curl);

	                            }

	                        }

	                        echo 'Success Updated Invoice ID: '.$row->invoice_id.' in '.home_url($wp->request);

						}else{
							echo 'Invoice ID: '.$row->invoice_id.' in '.home_url($wp->request).' sudah pernah diupdate.';
						}
                       
                    }else{
                    	echo 'Hanya status PAID yang diproses di sistem.';
                    }
                	
                }

                die;
                
			}
			if($link_code=='midtrans_handling' || $link_code=='midtrans_handling_sandbox'){

				$input_source = "php://input";
				$raw_notification = json_decode(file_get_contents($input_source), true);

				$transaction = $raw_notification['transaction_status'];
				$type = $raw_notification['payment_type'];
				$order_id = $raw_notification['order_id'];
				$fraud = $raw_notification['fraud_status'];
				$transaction_id = $raw_notification['transaction_id'];
				
				if ($transaction == 'settlement'){
					
                	// check dl apakah ada
                	$check_payment = $wpdb->get_results('SELECT * from '.$table_name2.' where payment_trx_id="'.$transaction_id.'" ');

					if($check_payment!=null){

						$wpdb->update(
                            $table_name2, //table
                            array(
                                'status' 	 => 1,
                                'payment_at' => date("Y-m-d H:i:s"),
                                'process_by' => 'midtrans'

                            ),
                            array('payment_trx_id' => $transaction_id), //where
                            array('%s'), //data format
                            array('%s') //where format
                        );

                        // GENERAL Settings
                        $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="currency" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_message2" or type="wanotif_on" or type="email_on" or type="email_success_message" ORDER BY id ASC');
                        $currency 		  = $query_settings[0]->data;
                        $wanotif_url 	  = $query_settings[1]->data;
                        $wanotif_apikey   = $query_settings[2]->data;
                        $wanotif_message2 = $query_settings[3]->data;
                        $wanotif_on 	  = $query_settings[4]->data;
                        $email_on 		  = $query_settings[5]->data;
					    $email_success_message = $query_settings[6]->data;

                        // GET DATA DONASI
                        $row = $wpdb->get_results('SELECT a.*, b.title from '.$table_name2.' a 
                        left JOIN '.$table_name3.' b ON b.campaign_id = a.campaign_id where a.payment_trx_id="'.$transaction_id.'" ')[0];

                        $data_field = array();
                        $data_field[ '{name}' ] 	= $row->name;
                        $data_field[ '{whatsapp}' ] = $row->whatsapp;
                        $data_field[ '{email}' ] 	= $row->email;
                        $data_field[ '{comment}' ] 	= $row->comment;
                        $data_field[ '{payment_number}' ] 	= $row->payment_number;
                        $data_field[ '{payment_code}' ] 	= paymentCode($row->payment_code);
                        $data_field[ '{payment_account}' ] 	= $row->payment_account;
                        $data_field[ '{campaign_title}' ] 	= $row->title;
                        $data_field[ '{invoice_id}' ] 		= $row->invoice_id;
					    $data_field[ '{date}' ] 			= $datenya = date("j F Y - h:i",strtotime($row->created_at));
					    $data_field[ '{link_ekuitansi}' ] 	= home_url().'/ekuitansi/'.$row->invoice_id;

                        if($currency=='IDR'){
                            $data_field[ '{total}' ] 	= 'Rp '.number_format($row->nominal,0,",",".");
                        }else{
                            $data_field[ '{total}' ] 	= $row->nominal;
                        }

                        $query_donation = $wpdb->get_results("SELECT b.user_id as fundraiser_id FROM $table_name4 a
					    LEFT JOIN $table_name5 b ON b.id = a.affcode_id 
					    where a.donate_id='$row->id' ORDER BY a.id DESC ")[0];

					    if($query_donation->fundraiser_id!=''){
					        $user_info = get_userdata($query_donation->fundraiser_id);
					        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
					        $data_field[ '{fundraiser}' ] = $fundraiser_name;
					    }else{
					    	$data_field[ '{fundraiser}' ] = '-';
					    }

                        // send email
					    if($email_on=='1'){

					    	$email_success_message = json_decode($email_success_message);

							foreach($email_success_message as $key => $value) {

					            $message = $value->message;
					            $message = strtr($message, $data_field);
								$message = str_replace('<p>linebreak</p>', '', $message);
								$message = str_replace('linebreak', '', $message);
								
					            $subject = $value->subject;
					            $subject = strtr($subject, $data_field);
								$subject = str_replace("'","",$subject);
								
					            $emailnya = $value->email;
								
					            $emailnyacc = $value->emailcc;
					            $emailnyabcc = $value->emailbcc;

					            $headers[] = 'Content-Type: text/html; charset=UTF-8';

					            $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';

								if (strpos($emailnyacc, ',') !== false ) {
									$array_emailcc  = (explode(",", $emailnyacc));
									foreach ($array_emailcc as $values){
										$email_cc = strtr($values, $data_field);
										$headers[] = 'Cc: '.$email_cc;
									}
								}else{
									$emailnyacc = strtr($emailnyacc, $data_field);
									$headers[] = 'Cc: '.$emailnyacc;
								}

								if (strpos($emailnyabcc, ',') !== false ) {
									$array_emailbcc  = (explode(",", $emailnyabcc));
									foreach ($array_emailbcc as $values){
										$email_bcc = strtr($values, $data_field);
										$headers[] = 'Bcc: '.$email_bcc;
									}
								}else{
									$emailnyabcc = strtr($emailnyabcc, $data_field);
									$headers[] = 'Bcc: '.$emailnyabcc;
								}
								
					 			
								if($emailnya!=''){
									$emailnya = strtr($emailnya, $data_field);
									wp_mail( $emailnya, $subject, $body, $headers );
								}
									
							}

						}

                        // send wanotif
                        $whatsapp = $row->whatsapp;
                        if($wanotif_apikey!='' && $wanotif_on=='1'){
                            // SET PHONE
                            if($whatsapp!=''){

                                $phone = djaPhoneFormat($whatsapp);
                                $url = $wanotif_url.'/send';

                                $messagenya = $wanotif_message2;
                                $messagenya = strtr($messagenya, $data_field);

                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $url);
                                curl_setopt($curl, CURLOPT_HEADER, 0);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($curl, CURLOPT_TIMEOUT,30);
                                curl_setopt($curl, CURLOPT_POST, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                                    'Apikey'    => $wanotif_apikey,
                                    'Phone'     => $phone,
                                    'Message'   => $messagenya,
                                ));
                                $response = curl_exec($curl);
                                curl_close($curl);

                            }

                        }

                        echo 'Success Updated Invoice ID: '.$row->invoice_id.' in '.home_url($wp->request);

					}


				}else{
					echo "No data.";
				}

				die;

			}
			if($link_code=='push_moota'){
			   
                // GENERAL SETTINGS
				$query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="currency" or type="moota_secret_token" or type="moota_range" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_message2" or type="wanotif_on" or type="email_on" or type="email_success_message" ORDER BY id ASC');
				$currency 			= $query_settings[0]->data;
			    $moota_secret_token = $query_settings[1]->data;
			    $moota_range 		= $query_settings[2]->data;
			    $wanotif_url 		= $query_settings[3]->data;
			    $wanotif_apikey 	= $query_settings[4]->data;
			    $wanotif_message2 	= $query_settings[5]->data;
			    $wanotif_on 		= $query_settings[6]->data;
			    $email_on 			= $query_settings[7]->data;
			    $email_success_message = $query_settings[8]->data;


			    // GET DATA MOOTA
			    $headers = apache_request_headers();
                $signature = '';
                foreach ($headers as $header => $value) {
                   if($header=='Signature'){
                       $signature = $value;
                   }
                }

                $payload_json_from_response=file_get_contents('php://input');
                $data=json_decode(file_get_contents('php://input'),1);
				$datanum = count($data); // COUNT THE DATA
				
				$signature2 = hash_hmac('sha256', $payload_json_from_response, $moota_secret_token); // local signature
				
				$b = '';
				
				if($signature == $signature2){
				
    				date_default_timezone_set('Asia/jakarta');
    				$filter_datestart_now = date('Y-m-d H:i');
    				$filter_datestart_before = date('Y-m-d H:i', strtotime($filter_datestart_now. ' - '.$moota_range.' days'));


                    if($datanum >= 1){
                        foreach ($data as $key => $value) {
                            
                            $amount = $value['amount'];
                            
    						// Query transfer amount into form entry db for order id
    						$row = $wpdb->get_results("SELECT id from $table_name2 where nominal='$amount' and status='0' and created_at BETWEEN '$filter_datestart_before' AND '$filter_datestart_now' ");
    	                   
    						if($row!=null){

    							// UPDATE DATA
    							$wpdb->update(
    						        $table_name2, //table
    						        array(
    						            'status' 	 	  => 1,
    						            'payment_at'	  => date("Y-m-d H:i:s"),
    						            'process_by'	  => 'moota'
    						        ),
    						        array('id' => $row[0]->id), //where
    						        array('%s'), //data format
    						        array('%s') //where format    
    						    );

								// GET DATA DONASI
							    $row2 = $wpdb->get_results('SELECT a.*, b.title from '.$table_name2.' a 
							    left JOIN '.$table_name3.' b ON b.campaign_id = a.campaign_id where a.id="'.$row[0]->id.'" ')[0];
							    
							    $data_field = array();
							    $data_field[ '{name}' ] 	= $row2->name;
							    $data_field[ '{whatsapp}' ] = $row2->whatsapp;
							    $data_field[ '{email}' ] 	= $row2->email;
							    $data_field[ '{comment}' ] 	= $row2->comment;
							    $data_field[ '{payment_number}' ] 	= $row2->payment_number;
							    $data_field[ '{payment_code}' ] 	= paymentCode($row2->payment_code);
							    $data_field[ '{payment_account}' ] 	= $row2->payment_account;
							    $data_field[ '{campaign_title}' ] 	= $row2->title;
							    $data_field[ '{invoice_id}' ] 		= $row2->invoice_id;
							    $data_field[ '{date}' ] 			= $datenya = date("j F Y - h:i",strtotime($row2->created_at));
							    $data_field[ '{link_ekuitansi}' ] 	= home_url().'/ekuitansi/'.$row2->invoice_id;
							    
							    if($currency=='IDR'){
							    	$data_field[ '{total}' ] 	= 'Rp '.number_format($row2->nominal,0,",",".");
							    }else{
							    	$data_field[ '{total}' ] 	= $row2->nominal;
							    }

							    $query_donation = $wpdb->get_results("SELECT b.user_id as fundraiser_id FROM $table_name4 a
							    LEFT JOIN $table_name5 b ON b.id = a.affcode_id 
							    where a.donate_id='$row->id' ORDER BY a.id DESC ")[0];

							    if($query_donation->fundraiser_id!=''){
							        $user_info = get_userdata($query_donation->fundraiser_id);
							        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
							        $data_field[ '{fundraiser}' ] = $fundraiser_name;
							    }else{
							    	$data_field[ '{fundraiser}' ] = '-';
							    }

							    // send email
							    if($email_on=='1'){

							    	$email_success_message = json_decode($email_success_message);

									foreach($email_success_message as $key => $value) {

							            $message = $value->message;
							            $message = strtr($message, $data_field);
										$message = str_replace('<p>linebreak</p>', '', $message);
										$message = str_replace('linebreak', '', $message);
										
							            $subject = $value->subject;
							            $subject = strtr($subject, $data_field);
										$subject = str_replace("'","",$subject);
										
							            $emailnya = $value->email;
										
							            $emailnyacc = $value->emailcc;
							            $emailnyabcc = $value->emailbcc;

							            $headers[] = 'Content-Type: text/html; charset=UTF-8';

							            $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';

										if (strpos($emailnyacc, ',') !== false ) {
											$array_emailcc  = (explode(",", $emailnyacc));
											foreach ($array_emailcc as $values){
												$email_cc = strtr($values, $data_field);
												$headers[] = 'Cc: '.$email_cc;
											}
										}else{
											$emailnyacc = strtr($emailnyacc, $data_field);
											$headers[] = 'Cc: '.$emailnyacc;
										}

										if (strpos($emailnyabcc, ',') !== false ) {
											$array_emailbcc  = (explode(",", $emailnyabcc));
											foreach ($array_emailbcc as $values){
												$email_bcc = strtr($values, $data_field);
												$headers[] = 'Bcc: '.$email_bcc;
											}
										}else{
											$emailnyabcc = strtr($emailnyabcc, $data_field);
											$headers[] = 'Bcc: '.$emailnyabcc;
										}
										
							 			
										if($emailnya!=''){
											$emailnya = strtr($emailnya, $data_field);
											wp_mail( $emailnya, $subject, $body, $headers );
										}
											
									}

								}

							    // send wanotif
							    $whatsapp = $row2->whatsapp;
								if($wanotif_apikey!='' && $wanotif_on=='1'){
									// SET PHONE
									if($whatsapp!=''){

										$phone = djaPhoneFormat($whatsapp);
										$url = $wanotif_url.'/send';

										$messagenya = $wanotif_message2;
										$messagenya = strtr($messagenya, $data_field);

										$curl = curl_init();
										curl_setopt($curl, CURLOPT_URL, $url);
										curl_setopt($curl, CURLOPT_HEADER, 0);
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
										curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
										curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
										curl_setopt($curl, CURLOPT_TIMEOUT,30);
										curl_setopt($curl, CURLOPT_POST, 1);
										curl_setopt($curl, CURLOPT_POSTFIELDS, array(
										    'Apikey'    => $wanotif_apikey,
										    'Phone'     => $phone,
										    'Message'   => $messagenya,
										));
										$response = curl_exec($curl);
										curl_close($curl);

									}

								}
    							
    						    $b .= $amount.' | ';
    						}
                        }
                    }
				}
				
				if($signature!=''){
    				$reply = array('Signature Moota' => $signature, 'Signature Server' => $signature2, 'Nominal' => $b, 'jumlah' => $datanum, 'data' => json_decode($data));
    				echo json_encode($reply);
    				die;
				}else{
					$reply = array('data' => 'Signature not found');
    				echo json_encode($reply);
    				die;
				}
			
			}

		}
	}


// });
}

add_action('parse_request', 'donasiaja_url_handler');


// Run the function on admin_init
function donasiaja_remove_profile_menu() {
  
	global $wpdb;
	$cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
	$roles = array_keys((array)$cap);
	$role = $roles[0];

	if($role=='cs' || $role=='donatur'){
		remove_submenu_page('upload.php', 'media-new.php');
	    remove_menu_page('upload.php');

	    remove_submenu_page('users.php', 'profile.php');
	    remove_menu_page('profile.php');

	    remove_submenu_page('index.php', 'index.php');
	    remove_menu_page('index.php');
	}
}
add_action('admin_init', 'donasiaja_remove_profile_menu');


function donasiaja_admin_default_page() {

	return 'wp-admin/admin.php?page=donasiaja_myprofile';

}
// add_filter('login_redirect', 'donasiaja_admin_default_page');



function check_license(){
	
	donasiaja_global_vars();
    $plugin_version = $GLOBALS['donasiaja_vars']['plugin_version'];
    $activate = $GLOBALS['donasiaja_vars']['activate'];
    $plugin_check_info = $GLOBALS['donasiaja_vars']['plugin_check_info'];
    $expired = $GLOBALS['donasiaja_vars']['expired'];
    $plugin_license = $GLOBALS['donasiaja_vars']['plugin_license'];
    
    $plugin_license = strtoupper($plugin_license);

    if($activate==false){

    	echo '
		<div class="body-nya" style="margin-top:20px;margin-right:20px;">

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    <!-- end page title end breadcrumb -->
                    <div class="row"> 
                        <div class="col-lg-4">
                        </div><!--end col-->  
                        <div class="col-lg-4">
                            <div class="card" style="border-bottom: 4px solid #f20988;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);margin-top: 20px;">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class=""></h5>
                                                <h2 class="my-2">Belum diaktivasi</h2>
                                                <p class="text-muted mb-0" style="padding-top:10px;padding-bottom:10px;">Silahkan aktivasi dengan API Key DonasiAja dan nikmati kemudahan dalam melakukan penggalangan dana.</p>
                                                <a href="'.admin_url("admin.php?page=donasiaja_settings").'">
                                                <button type="button" class="btn btn-primary px-5 py-2" style="margin-top: 25px;">Aktivasi sekarang</button></a>
                                                <br>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-lock-open bg-soft-pink"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->     
                        <div class="col-lg-4">
                            
                        </div><!--end col-->   
                                                         
                    </div><!--end row-->

                </div><!-- container -->


            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->
        ';
        // return false;
        wp_die();
    }

    if($plugin_check_info==false){

    	echo '
		<div class="body-nya" style="margin-top:20px;margin-right:20px;">

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    <!-- end page title end breadcrumb -->
                    <div class="row"> 
                        <div class="col-lg-4">
                        </div><!--end col-->  
                        <div class="col-lg-4">
                            <div class="card" style="border-bottom: 4px solid #f20988;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);margin-top: 20px;">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class=""></h5>
                                                <h2 class="my-2">Terjadi Kesalahan</h2>
                                                <p class="text-muted mb-0" style="padding-top:10px;padding-bottom:10px;">Periksa kembali API Key anda atau ulangi kembali aktivasi. Pastikan anda tidak mengubah settingan atau codingan pada DonasiAja yang bisa mengakibatkan error seperti ini.</p>
                                                <a href="'.admin_url("admin.php?page=donasiaja_settings").'">
                                                <button type="button" class="btn btn-primary px-5 py-2" style="margin-top: 25px;">Aktivasi sekarang</button></a>
                                                <br>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-pulse bg-soft-pink"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->     
                       <div class="col-lg-4">
                            
                    </div><!--end col-->   
                                                         
                    </div><!--end row-->

                </div><!-- container -->


            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->
        ';
        // return false;
        wp_die();
    }

    if($expired==true){

    	echo '
		<div class="body-nya" style="margin-top:20px;margin-right:20px;">

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    <!-- end page title end breadcrumb -->
                    <div class="row"> 
                        <div class="col-lg-4">
                        </div><!--end col-->  
                        <div class="col-lg-4">
                            <div class="card" style="border-bottom: 4px solid #f20988;-webkit-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);-moz-box-shadow: 0 6px 12px rgba(164, 192, 217, 0.3);margin-top: 20px;">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-10 align-self-center">
                                                <h5 class=""></h5>
                                                <h2 class="my-2">Plugin Expired</h2>
                                                <p class="text-muted mb-0" style="padding-top:10px;padding-bottom:10px;">Silahkan perpanjang license anda dan aktivasi kembali agar tetap bisa menggunakan DonasiAja.</p>
                                                <a href="https://member.donasiaja.id/my-products" target="_blank">
                                                <button type="button" class="btn btn-primary px-5 py-2" style="margin-top: 25px;">Perpanjang sekarang</button></a>
                                                <br>
                                            </div><!--end col-->
                                            <div class="col-2 align-self-center">
                                                <div class="">
                                                    <div class="icon-info mb-3">
                                                        <i class="dripicons-user bg-soft-pink"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  <!--end row-->                                                      
                                    </div><!--end icon-contain-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->     
                        <div class="col-lg-4">
                            
                        </div><!--end col-->   
                                                         
                    </div><!--end row-->

                </div><!-- container -->


            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->
        ';
        // return false;
        wp_die();
    }

}


function donasiaja_global_vars() {

	global $wpdb;
	global $donasiaja_vars;
	$table_name = $wpdb->prefix . 'dja_settings';

	$query_settings=$wpdb->get_results('SELECT data from '.$table_name.' where type="main_menu_name" or type="apikey_local" or type="apikey_server" or type="wanotif_url" ORDER BY id ASC');
	
	$main_menu_name=$query_settings[0]->data;
	$apikey_local=$query_settings[1]->data;
	$apikey_server=$query_settings[2]->data;
	$wanotif_url=$query_settings[3]->data;
	$apikey_local=json_decode($apikey_local,true);
	$apikey_server=json_decode($apikey_server,true);
	
	$apikey=$apikey_local['donasiaja'][0];
	$license=$apikey_server['donasiaja'][0];

	$status=$apikey_server['donasiaja'][1];
	$time=$apikey_server['donasiaja'][2];
	$code=$apikey_server['donasiaja'][3];
	if($time==null||$time==''){
		$time=0;
	}
	
	date_default_timezone_set('Asia/jakarta');
	$now=strtotime(date("Y-m-d h:i:s"));
	$time_check=floatval($time)-$now;$expired=false;
	if($time!='0'){
		if($time_check<=0){
			$expired=true;
		} else{
			$expired=false;
		}
	}
	
	$plugin_check_info=true;
	if($code!=''&&$license!=''){
		if(md5($license)!=$code){
			$plugin_check_info=false;
		}
	}
	
	if(md5($main_menu_name)!='f08cf9b423e4b46ed3025ddd3dc63b65'){$plugin_check_info=false;}
	
	if($apikey==''){$activate=false;
	} else{
		if($status!='valid'){
			$activate=false;
		} else{
			$activate=true;
		}
	}

	$donasiaja_vars=array('expired'=>$expired,'date_expired'=>$time,'plugin_name'=>'DonasiAja','plugin_version'=>'1.6.0.1','plugin_license'=>$license,'apikey'=>$apikey,'apikey_status'=>$status,'plugin_check_info'=>$plugin_check_info,'activate'=>$activate,);

}
add_action( 'parse_query', 'donasiaja_global_vars' );

