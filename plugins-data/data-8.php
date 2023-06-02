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

	// echo '<pre>'; var_dump($path); echo '</pre>';
	// if(isset($query)) {
	// 	echo '<pre>'; var_dump($query); echo '</pre>';
	// }

	/**
	 * Use of url handler
	 */
	if(isset($path[2])) {
		$action_id = $path[2];
		$donasi_id = $path[1];
		$link_code = $path[0];
		$affcode = '';

		if($path[0] === 'preview' && $path[2] == $page_donate) {
			if(isset($path[3])) {
				$nominal = (is_numeric($path[3]) ? $path[3] : null);
			}
	
			require_once ROOTDIR_DNA . 'donasiaja-form4.php';
			die;
		}
		else if($path[0] === 'campaign' && $path[2] == $page_donate) {
			$track_mode = 'form';
	
			if(isset($path[3])) {
				$nominal = (is_numeric($path[3])) ? $path[3] : null;
			}
	
			require_once ROOTDIR_DNA . 'donasiaja-form4.php';
			die;
		}
		else if($path[0] === 'josh' && $path[2] == $page_donate) {
			$track_mode = 'form';

			if(isset($path[3])) {
				$nominal = (is_numeric($path[3])) ? $path[3] : null;
			}

			require_once ROOTDIR_DNA . 'donasiaja_form4.php';
			die;
		}
		else if($path[0] === 'campaign' && $path[2] == $page_typ) {
			$track_mode = 'thankyoupage';
			
			$invoice_id = $path[3];
			
			require_once ROOTDIR_DNA . 'donasiaja-typ.php';
			die;
		}
		else if($path[0] === 'josh' && $path[2] == $page_typ) {
			$track_mode = 'thankyoupage';

			$invoice_id = $path[3];

			require_once ROOTDIR_DNA . 'donasiaja-typ.php';
			die;
		}
	} // end of isset($path[2])
	else if(isset($path[0])) {
		$program_quick = ['rumah-tahfizh', 'rumah-tahfidz', 'wakaf-quran', 'sedekah-subuh', 'jumat-berkah', 'tanggap-cianjur', 'bantuan-lebak', 'zakat', 'fidyah'];
		$donasi_id = (isset($path[1])) ? $path[1] : null;
		$link_code = $path[0];
		$affcode = '';

		if(in_array($path[0], $program_quick)) {
			$track_mode = 'landingpage';
			require_once ROOTDIR_DNA . 'josh-redirect.php';
			die;
		}
		else if($path[0] === 'webhook') {
			require_once ROOTDIR_DNA . 'josh-waba-endpoint.php';
			die;
		}
		else if($path[0] === 'josh') {
			$track_mode = 'landingpage';

			require_once ROOTDIR_DNA . 'donasiaja-campaign.php';
			die;
		}
		else if($path[0] === 'f') {
			require_once ROOTDIR_DNA . 'josh-followup.php';
			die;
		}
		else if($path[0] === '_f') {
			require_once ROOTDIR_DNA . 'josh-followup-2.php';
			die;
		}
		else if($path[0] === 'slip-input') {
			require_once ROOTDIR_DNA . 'josh-input-slip.php';
			die;
		}
		else if($path[0] === 's') {
			require_once ROOTDIR_DNA . 'josh-slip-view.php';
			die;
		}
		else if($path[0] === '__glogin_crm') {
			$direct_to = 'crm';
			require_once ROOTDIR_DNA . 'jh-login-google.php';
			die;
		}
		else if($path[0] === 'url-builder') {
			require_once ROOTDIR_DNA . 'josh-utm-builder.php';
			die;
		}
		else if($path[0] === 'input-leads') {
			wp_die('<h2>Ooops..</h2><br><p>Halo! Demi terciptanya data yang <i>terintegrasi</i> dengan baik, leads saat ini langsung di Dashboard CRM ya!</p><p>Terimakasih!! :)</p><p><i>Mengintegrasikan data dari sumber yang banyak itu tidak mudah lho :) terimakasih atas pengertiannya.</i></p><br><br>- Developer DFR YMPB.');
		}
		else if($path[0] === 'preview') {
			require_once ROOTDIR_DNA . 'donasiaja-campaign.php';
			die;
		}
		else if($path[0] === 'campaign') {
			$track_mode = 'landingpage';
			require_once ROOTDIR_DNA . 'donasiaja-campaign.php';
			die;
		}
		else if($path[0] === 'search_campaign'){
			require_once(ROOTDIR_DNA . 'donasiaja-search.php');
			die;
		}
		else if($path[0] === 'profile'){
			require_once(ROOTDIR_DNA . 'donasiaja-profile.php');
			die;
		}
		else if($path[0] === $page_login){
			require_once(ROOTDIR_DNA . 'donasiaja-login.php');
			die;
		}
		else if($path[0] === $page_register){
			require_once(ROOTDIR_DNA . 'donasiaja-register.php');
			die;
		}
		else if($path[0] === 'changepass'){
			require_once(ROOTDIR_DNA . 'donasiaja-changepass.php');
			die;
		}
		else if($path[0] === 'resetpass'){
			require_once(ROOTDIR_DNA . 'donasiaja-resetpass.php');
			die;
		}
		else if($path[0] === 'ekuitansi'){
			require_once(ROOTDIR_DNA . 'admin/f_print_kuitansi.php');
			die;
		}

	}
}

add_action('parse_request', 'donasiaja_url_handler_');


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


function jh_settings_waba() {
	header('Content-Type: application/json');

	$graphapi_token 	= $_POST['graphapi_token'];
	$graphapi_version 	= $_POST['graphapi_version'];
	$waba_number		= $_POST['waba_number'];

	$updater = [
		'fb_graphapi_token' 	=> $graphapi_token,
		'fb_graphapi_version'	=> $graphapi_version,
		'waba_phone'			=> $waba_number
	];

	global $wpdb;
	$table_settings = $wpdb->prefix . 'dja_settings';

	foreach($updater as $key => $val) {
		$update = $wpdb->update(
			$table_settings,
			[
				'data'		=> $val
			],
			[
				'type'		=> $key
			]
		);

		if($update === false) {
			break;
		}
	}

	$status = ($update === false) ? 'failed' : 'success';
	$messages = ($update === false) ? $wpdb->last_error : '';

	echo json_encode(
		[
			'status'	=> $status,
			'messages'	=> $messages
		]
	);

	die;
}

add_action('wp_ajax_jh_settings_waba', 'jh_settings_waba');

function jh_testing_waba() {
	header('Content-Type: application/json');
	global $wpdb;

	$number = $_POST['number'];
	$table_settings = $wpdb->prefix . 'dja_settings';

	/**
	 * Setting Up WABA
	 */
	{
		$query = "SELECT data
		FROM $table_settings
		WHERE type='fb_graphapi_token' or type='fb_graphapi_version' or type='waba_phone'";
		$rows = $wpdb->get_results($query);

		$fb_graphapi_token 		= $rows[0]->data;
		$fb_graphapi_version 	= $rows[1]->data;
		$waba_phone 			= $rows[2]->data;

		$authorization = "Authorization: Bearer $fb_graphapi_token";

		$version = $fb_graphapi_version;
		$phone_from = $waba_phone;

		$header = array(
			'Content-Type: application/json',
			$authorization
		);

		$postBody = array(
		'messaging_product' => 'whatsapp',
		'recipient_type' => 'individual',
		'to' => $number,
		'type' => 'template',
		'template' => array(
			'name' => 'order_baru',
			'language' => array(
				'code' => 'id'
			),
			'components' => array(
				array(
					'type' => 'header',
					'parameters' => array(
						array(
							'type' => 'text',
							'text' => 'TEST'
						)
					)
				),
				array(
					'type' => 'body',
					'parameters' => array(
						array(
							'type' => 'text',
							'text' => 'Program Test'
						),
						array(
							'type' => 'text',
							'text' => 'Invoice Test'
						),
						array(
							'type' => 'text',
							'text' => 'Name Test'
						),
						array(
							'type' => 'text',
							'text' => 'CS Test'
						),
						array(
							'type' => 'text',
							'text' => 'Test Tanggal'
						),
						array(
							'type' => 'text',
							'text' => '-'
						),
						array(
							'type' => 'text',
							'text' => 'Test UTM'
						)
					)
				),
				array(
					'type' => 'button',
					'sub_type' => 'url',
					'index' => '0',
					'parameters' => array(
						array(
							'type' => 'text',
							'text' => 'INV-123'
						)
					)
				)
				)
			)
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://graph.facebook.com/$version/$phone_from/messages");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postBody));

		// execute
		$response = curl_exec($curl);

		if( $response === false ) {
			$response = curl_error( $curl );
		}
		$response_decode = json_decode($response);

		curl_close($curl);

		/**
		 * 
		 */
		if( isset( $response_decode->contacts ) ) {
			$status = 'success';
			$messages = '';
		} else {
			$status = 'failed';
			$messages = $response_decode->error->message;
		}
	}
	
	echo json_encode(
		[
			'status'	=> $status,
			'messages'	=> $messages
		]
	);

	die;
}

add_action('wp_ajax_jh_testing_waba', 'jh_testing_waba');

function jh_remove_pixel() {
	header('Content-Type: application/json');

	$id = $_POST['id'];

	global $wpdb;
	$table_capi_pixel = $wpdb->prefix . 'josh_capi_pixel';

	$delete = $wpdb->delete(
		$table_capi_pixel,
		[
			'id'	=> $id
		]
	);

	if($delete === false) {
		$status = 'failed';
		$messages = $wpdb->last_error;
	} else {
		$status = 'success';
		$messages = '';
	}

	echo json_encode(
		[
			'status'	=> $status,
			'messages'	=> $messages
		]
	);

	die;
}

add_action('wp_ajax_jh_remove_pixel', 'jh_remove_pixel');

function jh_add_pixel() {
	header('Content-Type: application/json');

	global $wpdb;
	$table_capi_pixel = $wpdb->prefix . 'josh_capi_pixel';

	$insert = $wpdb->insert(
		$table_capi_pixel,
		[
			'pixel_name'	=> 'new pixel',
			'pixel_id'		=> '123',
			'access_token'	=> null
		]
	);

	if($insert === false) {
		$status = 'failed';
		$messages = $wpdb->last_error;
	} else {
		$status = 'success';
		$messages = '';
	}

	echo json_encode(
		[
			'status'	=> $status,
			'messages'	=> $messages
		]
	);

	die;
}

add_action('wp_ajax_jh_add_pixel', 'jh_add_pixel');


function jh_update_pixel() {
	header('Content-Type: application/json');

	$data = $_POST['data'];

	global $wpdb;
	$table_settings = $wpdb->prefix . 'dja_settings';
	$table_pixels = $wpdb->prefix . 'josh_capi_pixel';

	/**
	 * Capi Status
	 */
	{
		$statusCapi = ($data['statusCapi'] == 'true') ? '1' : '0';

		$update = $wpdb->update(
			$table_settings,
			[
				'data'	=> $statusCapi
			],
			[
				'type'	=> 'run_capi'
			]
		);
	}

	if($update === false) {
		$messages = $wpdb->last_error;

		output_fail($messages, $data);
	} else {
		
		$update = $wpdb->update(
			$table_settings,
			[
				'data'	=> $data['token']
			],
			[
				'type'	=> 'capi_access_token'
			]
		);

		if($update === false) {
			$messages = $wpdb->last_error;

			output_fail($messages, $data);
		} else {
			
			foreach($data['pixels'] as $data) {
				$update = $wpdb->update(
					$table_pixels,
					[
						'pixel_name'	=> $data['pixelName'],
						'pixel_id'		=> $data['pixelId']
					],
					[
						'id'			=> $data['id']
					]
				);

				if($update === false) {
					$messages = $wpdb->last_error;

					output_fail($messages, $data);
					break;
				}
			}

		}
	}

	function output_fail($messages, $data) {
		echo json_encode([
			'status'	=> 'failed',
			'messages'	=> $messages,
			'dbg'		=> $data
		]);

		die;
	}

	echo json_encode([
		'status'	=> 'success',
		'messages'	=> '',
		'dbg'		=> $data
	]);

	die;
}

add_action('wp_ajax_jh_update_pixel', 'jh_update_pixel');