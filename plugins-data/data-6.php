<?php

function djafunction_reset_pass() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_password_reset";
    $table_name2 = $wpdb->prefix . "users";

    // FROM INPUT
    $new_password 	= $_POST['datanya'][0];
    $reset_email 	= $_POST['datanya'][1];
    $reset_code 	= $_POST['datanya'][2];

    $check = $wpdb->get_results('SELECT * from '.$table_name.' where reset_code="'.$reset_code.'" and  reset_email="'.$reset_email.'" ');
	if($check==null){
		echo 'reset_failed';
    	wp_die();
	}

    $user = get_user_by( 'email', $reset_email );
    if ( $user ) {
        $user_id = $user->ID;
    } else {
        $user_id = false;
    }

	$pattern = "/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";
    if (!(preg_match($pattern, $new_password))) {
        echo 'password_failed';
    	wp_die();
    }else{
    	// UPDATE
    	$hash = wp_hash_password( $new_password );
	    $wpdb->update(
	        $table_name2,
	        array(
	            'user_pass'           => $hash
	        ),
	        array( 'ID' => $user_id )
	    );
	    $wpdb->update(
	        $table_name,
	        array(
	            'reset_status' => 1
	        ),
	        array( 'reset_code' => $reset_code )
	    );

    	echo 'success';

		wp_die();

    }
    
} 
add_action( 'wp_ajax_djafunction_reset_pass', 'djafunction_reset_pass' );
add_action( 'wp_ajax_nopriv_djafunction_reset_pass', 'djafunction_reset_pass' );



function djafunction_add_user() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "usermeta";
    $table_name3 = $wpdb->prefix . "dja_users";
    $table_name4 = $wpdb->prefix . "dja_verification_details";    

    // FROM INPUT
    $user_firstname = $_POST['datanya'][0];
    $user_lastname 	= $_POST['datanya'][1];
    $user_wa 		= $_POST['datanya'][2];
    $user_email 	= $_POST['datanya'][3];
    $user_bio 		= $_POST['datanya'][4];
    $user_provinsi 	= $_POST['datanya'][5];
    $user_kabkota 	= $_POST['datanya'][6];
    $user_kecamatan 	= $_POST['datanya'][7];
    $user_provinsi_id 	= $_POST['datanya'][8];
    $user_kabkota_id 	= $_POST['datanya'][9];
    $user_kecamatan_id 	= $_POST['datanya'][10];
    $user_alamat 	= $_POST['datanya'][11];
    $user_bank_name = $_POST['datanya'][12];
    $user_bank_no 	= $_POST['datanya'][13];
    $user_bank_an 	= $_POST['datanya'][14];
    $user_role 		= $_POST['datanya'][15];
    $user_type 		= $_POST['datanya'][16];
    $user_verification = $_POST['datanya'][17];
    $user_pass 		= $_POST['datanya'][18];

    // strip out all whitespace
	$name_clean = preg_replace('/\s*/', '', $user_firstname.$user_lastname);
	$name_clean_fix = strtolower($name_clean);

    // check mail
    $user = get_user_by( 'email', $user_email );
    if ( $user ) {
        $user_id = $user->ID;
    } else {
        $user_id = false;
    }

    // check username
    $user2 = get_user_by( 'login', $name_clean_fix );
    if ( $user2 ) {
        $user_id2 = $user2->ID;
    } else {
        $user_id2 = false;
    }

    // add 3 char
    $rand_3char = d_randomString(3);
    if($user_id2!=null){
    	$name_clean_fix = $name_clean_fix.$rand_3char;
    }

    if($user_id!=null){
    	echo 'email_terdaftar';
    	wp_die();
    }else{

    	$user_firstname_update = str_replace('\\', '', $user_firstname);
		$user_lastname_update = str_replace('\\', '', $user_lastname);

    	$hash = wp_hash_password( $user_pass );
    	$WP_array = array (
	        'user_login'    =>  $name_clean_fix,
	        'user_email'    =>  $user_email,
	        'user_pass'     =>  $user_pass,
	        'user_url'      =>  '',
	        'first_name'    =>  $user_firstname_update,
	        'last_name'     =>  $user_lastname_update,
	        'nickname'      =>  $name_clean_fix,
	        'display_name'  =>  $user_firstname_update.' '.$user_lastname_update,
	        'description'   =>  '',
	    ) ;

	    $user_id = wp_insert_user( $WP_array ) ;
	    wp_update_user( array ('ID' => $user_id, 'role' => $user_role) ) ;

	    $randid = 'u_'.d_randomString(8);
        // insert data to table user
        $wpdb->insert(
            $table_name3, //table
            array(
                'user_id'           => $user_id,
                'user_randid'       => $randid,
                'user_type'         => $user_type,
                'user_verification' => $user_verification,
                'user_bio'          => $user_bio,
                'user_wa'           => $user_wa,
                'user_provinsi' 	=> $user_provinsi,
	            'user_kabkota' 		=> $user_kabkota,
	            'user_kecamatan' 	=> $user_kecamatan,
	            'user_provinsi_id' 	=> $user_provinsi_id,
	            'user_kabkota_id' 	=> $user_kabkota_id,
	            'user_kecamatan_id' => $user_kecamatan_id,
	            'user_alamat' 		=> $user_alamat,
	            'user_bank_name' 	=> $user_bank_name,
	            'user_bank_no' 	 	=> $user_bank_no,
	            'user_bank_an' 	 	=> $user_bank_an,
                'user_pp_img'       => null,
                'user_cover_img'    => null,
                'created_at'    	=> date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );

        // insert data to table verifications
        $wpdb->insert(
            $table_name4, //table
            array(
                'u_id'                  => $user_id,
                'u_nama_lengkap'        => null,
                'u_email'               => null,
                'u_whatsapp'            => null,
                'u_ktp'                 => null,
                'u_ktp_selfie'          => null,
                'u_jabatan'             => null,
                'u_nama_ketua'          => null,
                'u_alamat_lengkap'      => null,
                'u_program_unggulan'    => null,
                'u_profile'             => null,
                'u_legalitas'           => null,
                'created_at'            => date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );
        
	    echo $user_id;

    	wp_die();
    }
    
} 
add_action( 'wp_ajax_djafunction_add_user', 'djafunction_add_user' );
add_action( 'wp_ajax_nopriv_djafunction_add_user', 'djafunction_add_user' );


function djafunction_delete_usernya() {

	global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "dja_users";
    $table_name3 = $wpdb->prefix . "dja_user_logs";
    $table_name4 = $wpdb->prefix . "dja_verification_details";

    // FROM INPUT
    $user_id 	    = $_POST['datanya'][0];

    $user_info = get_userdata($user_id);
    $fullname = $user_info->first_name.' '.$user_info->last_name;
    $user_login = $user_info->user_login;
    $user_email = $user_info->user_email;


    // echo $fullname.' - '.$user_login.' - '.$user_email;

    if($user_id==1){
    	echo 'not allowed.';
    	wp_die();
    }

    $user = $wpdb->get_results('SELECT * from '.$table_name2.' where user_id="'.$user_id.'"');
	if($user!=null){

		$wpdb->insert(
            $table_name3, //table
            array(
                'del_by'        	=> $user_id,
                'user_login'        => $user_login,
                'user_email'        => $user_email,
                'user_id'           => $user_id,
                'user_fullname'     => $fullname,
                'user_randid'       => $user[0]->user_randid,
                'user_type' 		=> $user[0]->user_type,
		        'user_verification' => $user[0]->user_verification,
		        'user_bio' 			=> $user[0]->user_bio,
		        'user_wa' 			=> $user[0]->user_wa,
		        'user_provinsi' 	=> $user[0]->user_provinsi,
		        'user_kabkota' 		=> $user[0]->user_kabkota,
		        'user_kecamatan' 	=> $user[0]->user_kecamatan,
		        'user_provinsi_id' 	=> $user[0]->user_provinsi_id,
		        'user_kabkota_id' 	=> $user[0]->user_kabkota_id,
		        'user_kecamatan_id' => $user[0]->user_kecamatan_id,
		        'user_alamat' 		=> $user[0]->user_alamat,
		        'user_bank_name' 	=> $user[0]->user_bank_name,
		        'user_bank_no' 	 	=> $user[0]->user_bank_no,
		        'user_bank_an' 	 	=> $user[0]->user_bank_an,
                'user_pp_img'       => $user[0]->user_pp_img,
                'user_cover_img'    => $user[0]->user_cover_img,
                'created_at'    	=> date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );


        if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name2.' WHERE user_id = %d', $user_id ) ) ) {
			// then delete
	        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name2.' WHERE user_id = "'.$user_id.'" ' ) );
	    }

        if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name4.' WHERE u_id = %d', $user_id ) ) ) {
			// then delete
	        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name4.' WHERE u_id = "'.$user_id.'" ' ) );
	    }

	    if (wp_delete_user($user_id)){
	        echo 'success';
	    }
	    wp_die();


	}else{
		echo 'not allowed.';
		wp_die();
	}
	
    
} 
add_action( 'wp_ajax_djafunction_delete_usernya', 'djafunction_delete_usernya' );
add_action( 'wp_ajax_nopriv_djafunction_delete_usernya', 'djafunction_delete_usernya' );


function djafunction_update_user() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "usermeta";
    $table_name3 = $wpdb->prefix . "dja_verification_details";  
    $table_name4 = $wpdb->prefix . "users";
    $table_name5 = $wpdb->prefix . "dja_settings";    
    
    // FROM INPUT
    $user_id 	    = $_POST['datanya'][0];
    $user_firstname = $_POST['datanya'][1];
    $user_lastname 	= $_POST['datanya'][2];
    $user_wa 		= $_POST['datanya'][3];
    $user_email 	= $_POST['datanya'][4];
    $user_bio 		= $_POST['datanya'][5];
    $user_provinsi 	= $_POST['datanya'][6];
    $user_kabkota 	= $_POST['datanya'][7];
    $user_kecamatan 	= $_POST['datanya'][8];
    $user_provinsi_id 	= $_POST['datanya'][9];
    $user_kabkota_id 	= $_POST['datanya'][10];
    $user_kecamatan_id 	= $_POST['datanya'][11];
    $user_alamat 	= $_POST['datanya'][12];
    $user_bank_name = $_POST['datanya'][13];
    $user_bank_no 	= $_POST['datanya'][14];
    $user_bank_an 	= $_POST['datanya'][15];
    $user_role 		= $_POST['datanya'][16];
    $user_type 		= $_POST['datanya'][17];
    $user_verification = $_POST['datanya'][18];

    // get email settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="email_on" ORDER BY id ASC');
    $email_on 		  = $query_settings[0]->data;

    $check = $wpdb->get_results('SELECT id, user_verification from '.$table_name.' where user_id="'.$user_id.'"');
	if($check!=null){

	    // ACTION Update TO DB
	    $wpdb->update(
            $table_name, //table
            array(
	            'user_type' 		=> $user_type,
	            'user_verification' => $user_verification,
	            'user_bio' 			=> $user_bio,
	            'user_wa' 			=> $user_wa,
	            'user_provinsi' 	=> $user_provinsi,
	            'user_kabkota' 		=> $user_kabkota,
	            'user_kecamatan' 	=> $user_kecamatan,
	            'user_provinsi_id' 	=> $user_provinsi_id,
	            'user_kabkota_id' 	=> $user_kabkota_id,
	            'user_kecamatan_id' => $user_kecamatan_id,
	            'user_alamat' 		=> $user_alamat,
	            'user_bank_name' 	=> $user_bank_name,
	            'user_bank_no' 	 	=> $user_bank_no,
	            'user_bank_an' 	 	=> $user_bank_an,
	        ),
            array('user_id' => $user_id), //where
            array('%s'), //data format
            array('%s') //where format    
        );

	    $user_firstname_update = str_replace('\\', '', $user_firstname);
	    $user_lastname_update = str_replace('\\', '', $user_lastname);

        // ACTION Update TO DB
	    $wpdb->update(
            $table_name2, //table
            array(
	            'meta_value' => $user_firstname_update,
	        ),
            array('user_id' => $user_id, 'meta_key' => 'first_name' ), //where
            array('%s'), //data format
            array('%s') //where format    
        );
	    $wpdb->update(
            $table_name2, //table
            array(
	            'meta_value' => $user_lastname_update,
	        ),
            array('user_id' => $user_id, 'meta_key' => 'last_name' ), //where
            array('%s'), //data format
            array('%s') //where format    
        );
	    $wpdb->update(
            $table_name4, //table
            array(
	            'user_email' => $user_email,
	        ),
            array('ID' => $user_id), //where
            array('%s'), //data format
            array('%s') //where format    
        );
	    $wpdb->update(
            $table_name4, //table
            array(
	            'display_name' => $user_firstname_update.' '.$user_lastname_update,
	        ),
            array('ID' => $user_id), //where
            array('%s'), //data format
            array('%s') //where format    
        );

	    if($email_on=='1'){
	        if($check[0]->user_verification=='2' && $user_verification=='1'){

				$message = '<p>Akun anda dengan email '.$user_email.' berhasil kami verifikasi.<br>Selamat menggunakan layanan kami.</p>';
	            $subject = 'Your account is Verified';
	            $emailnya = $user_email;
	            $headers[] = 'Content-Type: text/html; charset=UTF-8';
	            $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';
	 			
				if($emailnya!=''){
					wp_mail( $emailnya, $subject, $body, $headers );
				}
			}
			if($check[0]->user_verification=='2' && $user_verification=='3'){

				$message = '<p>Akun anda dengan email '.$user_email.' belum memenuhi syarat verifikasi.<br>Silahkan lengkapi data terlebih dahulu sesuai dengan data yang kami minta agar bisa kami verifikasi. Terimakasih</p>';
	            $subject = 'Your account is Rejected';
	            $emailnya = $user_email;
	            $headers[] = 'Content-Type: text/html; charset=UTF-8';
	            $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';
	 			
				if($emailnya!=''){
					wp_mail( $emailnya, $subject, $body, $headers );
				}
			}
		}

   		// set new role
		$u = new WP_User($user_id);
		$u->set_role($user_role);

        echo 'success';

	}else{

		$randid = 'u_'.d_randomString(8);

        // insert data to table user
        $wpdb->insert(
            $table_name, //table
            array(
                'user_id'           => $user_id,
                'user_randid'       => $randid,
                'user_type' 		=> $user_type,
		        'user_verification' => $user_verification,
		        'user_bio' 			=> $user_bio,
		        'user_wa' 			=> $user_wa,
		        'user_provinsi' 	=> $user_provinsi,
		        'user_kabkota' 		=> $user_kabkota,
		        'user_kecamatan' 	=> $user_kecamatan,
		        'user_provinsi_id' 	=> $user_provinsi_id,
		        'user_kabkota_id' 	=> $user_kabkota_id,
		        'user_kecamatan_id' => $user_kecamatan_id,
		        'user_alamat' 		=> $user_alamat,
		        'user_bank_name' 	=> $user_bank_name,
		        'user_bank_no' 	 	=> $user_bank_no,
		        'user_bank_an' 	 	=> $user_bank_an,
                'user_pp_img'       => null,
                'user_cover_img'    => null,
                'created_at'    	=> date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );

        // insert data to table verifications
        $wpdb->insert(
            $table_name3, //table
            array(
                'u_id'                  => $user_id,
                'u_nama_lengkap'        => null,
                'u_email'               => null,
                'u_whatsapp'            => null,
                'u_ktp'                 => null,
                'u_ktp_selfie'          => null,
                'u_jabatan'             => null,
                'u_nama_ketua'          => null,
                'u_alamat_lengkap'      => null,
                'u_program_unggulan'    => null,
                'u_profile'             => null,
                'u_legalitas'           => null,
                'created_at'            => date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );

        $user_firstname_update = str_replace('\\', '', $user_firstname);
	    $user_lastname_update = str_replace('\\', '', $user_lastname);

        // ACTION Update TO DB
	    $wpdb->update(
            $table_name2, //table
            array(
	            'meta_value' => $user_firstname_update,
	        ),
            array('user_id' => $user_id, 'meta_key' => 'first_name' ), //where
            array('%s'), //data format
            array('%s') //where format    
        );
	    $wpdb->update(
            $table_name2, //table
            array(
	            'meta_value' => $user_lastname_update,
	        ),
            array('user_id' => $user_id, 'meta_key' => 'last_name' ), //where
            array('%s'), //data format
            array('%s') //where format    
        );

   		// set new role
		$u = new WP_User($user_id);
		$u->set_role($user_role);

        echo 'success';
	}


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_user', 'djafunction_update_user' );
add_action( 'wp_ajax_nopriv_djafunction_update_user', 'djafunction_update_user' );


function djafunction_update_profile() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "usermeta";
    $table_name3 = $wpdb->prefix . "users";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 		= $_POST['datanya'][0];
    $first_name 	= $_POST['datanya'][1];
    $last_name 		= $_POST['datanya'][2];
    $user_bio 		= $_POST['datanya'][3];
    $user_alamat 	= $_POST['datanya'][4];
    $user_provinsi 	= $_POST['datanya'][5];
    $user_kabkota 	= $_POST['datanya'][6];
    $user_kecamatan 	= $_POST['datanya'][7];
    $user_provinsi_id 	= $_POST['datanya'][8];
    $user_kabkota_id 	= $_POST['datanya'][9];
    $user_kecamatan_id 	= $_POST['datanya'][10];

    if($last_name==''){
    	$full_name = $first_name;
    }else{
    	$full_name = $first_name.' '.$last_name;
    }
    

    if($id_login==$user_id){

		    // ACTION Update TO DB
		    $wpdb->update(
	            $table_name, //table
	            array(
		            'user_bio' 			=> $user_bio,
		            'user_provinsi' 	=> $user_provinsi,
		            'user_kabkota' 		=> $user_kabkota,
		            'user_kecamatan' 	=> $user_kecamatan,
		            'user_provinsi_id' 	=> $user_provinsi_id,
		            'user_kabkota_id' 	=> $user_kabkota_id,
		            'user_kecamatan_id' => $user_kecamatan_id,
		            'user_alamat' 		=> $user_alamat
		        ),
	            array('user_id' => $user_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );

	        // ACTION Update TO DB
		    $wpdb->update(
	            $table_name2, //table
	            array(
		            'meta_value' => $first_name,
		        ),
	            array('user_id' => $user_id, 'meta_key' => 'first_name' ), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );
		    $wpdb->update(
	            $table_name2, //table
	            array(
		            'meta_value' => $last_name,
		        ),
	            array('user_id' => $user_id, 'meta_key' => 'last_name' ), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );
		    $wpdb->update(
	            $table_name3, //table
	            array(
		            'display_name' => $full_name,
		        ),
	            array('ID' => $user_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );
			
			echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_profile', 'djafunction_update_profile' );
add_action( 'wp_ajax_nopriv_djafunction_update_profile', 'djafunction_update_profile' );



function djafunction_update_akun() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "users";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 	= $_POST['datanya'][0];
    $user_wa 	= $_POST['datanya'][1];
    $user_email = $_POST['datanya'][2];
    

    if($id_login==$user_id){

		    // ACTION Update TO DB
		    $wpdb->update(
	            $table_name, //table
	            array(
		            'user_wa' 			=> $user_wa
		        ),
	            array('user_id' => $user_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );

	        // ACTION Update TO DB
		    $wpdb->update(
	            $table_name2, //table
	            array(
		            'user_email' => $user_email,
		        ),
	            array('ID' => $user_id ), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );
			
			echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_akun', 'djafunction_update_akun' );
add_action( 'wp_ajax_nopriv_djafunction_update_akun', 'djafunction_update_akun' );


function djafunction_update_akun_bank() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "users";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 	= $_POST['datanya'][0];
    $user_bank_name = $_POST['datanya'][1];
    $user_bank_no = $_POST['datanya'][2];
    $user_bank_an = $_POST['datanya'][3];
    

    if($id_login==$user_id){

		    // ACTION Update TO DB
		    $wpdb->update(
	            $table_name, //table
	            array(
		            'user_bank_name' => $user_bank_name,
		            'user_bank_no' 	 => $user_bank_no,
		            'user_bank_an' 	 => $user_bank_an,
		        ),
	            array('user_id' => $user_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );

	        echo 'success';


    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_akun_bank', 'djafunction_update_akun_bank' );
add_action( 'wp_ajax_nopriv_djafunction_update_akun_bank', 'djafunction_update_akun_bank' );


function djafunction_update_pp_img() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "users";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 	= $_POST['datanya'][0];
    $user_pp_img = $_POST['datanya'][1];

    if($id_login==$user_id){

		    // ACTION Update TO DB
		    $wpdb->update(
	            $table_name, //table
	            array(
		            'user_pp_img' => $user_pp_img
		        ),
	            array('user_id' => $user_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );

	        echo 'success';


    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_pp_img', 'djafunction_update_pp_img' );
add_action( 'wp_ajax_nopriv_djafunction_update_pp_img', 'djafunction_update_pp_img' );



function djafunction_update_pp_img_user() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "users";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 	= $_POST['datanya'][0];
    $user_pp_img = $_POST['datanya'][1];

    $check = $wpdb->get_results('SELECT id from '.$table_name.' where user_id="'.$user_id.'"');
	if($check!=null){

	    // ACTION Update TO DB
	    $wpdb->update(
            $table_name, //table
            array(
	            'user_pp_img' => $user_pp_img
	        ),
            array('user_id' => $user_id), //where
            array('%s'), //data format
            array('%s') //where format    
        );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_pp_img_user', 'djafunction_update_pp_img_user' );
add_action( 'wp_ajax_nopriv_djafunction_update_pp_img_user', 'djafunction_update_pp_img_user' );



function djafunction_upload_ktp() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "dja_verification_details";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id = $_POST['datanya'][0];
    $u_ktp 	 = $_POST['datanya'][1];

    if($id_login==$user_id){

    		$check = $wpdb->get_results('SELECT id from '.$table_name2.' where u_id="'.$user_id.'"');
			if($check!=null){

				// ACTION Update TO DB
			    $wpdb->update(
		            $table_name2, //table
		            array(
			            'u_ktp' => $u_ktp
			        ),
		            array('u_id' => $user_id), //where
		            array('%s'), //data format
		            array('%s') //where format    
		        );

			}else{
				// ACTION INSERT TO DB
			    $wpdb->insert(
		            $table_name2, //table
		            array(
			            'u_id' 					=> $user_id,
			            'u_nama_lengkap'		=> null,
			            'u_email' 				=> null,
			            'u_whatsapp' 			=> null,
			            'u_ktp' 				=> $u_ktp,
			            'u_ktp_selfie' 			=> null,
			            'u_jabatan' 			=> null,
			            'u_nama_ketua' 			=> null,
			            'u_alamat_lengkap' 		=> null,
			            'u_program_unggulan' 	=> null,
			            'u_profile' 			=> null,
			            'u_legalitas' 			=> null,
			            'created_at' 			=> date("Y-m-d H:i:s")),
		            array('%s', '%s') //data format         
		        );
			}

	        echo 'success';


    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_upload_ktp', 'djafunction_upload_ktp' );
add_action( 'wp_ajax_nopriv_djafunction_upload_ktp', 'djafunction_upload_ktp' );


function djafunction_upload_app_logo() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id  = $_POST['datanya'][0];
    $logo_url = $_POST['datanya'][1];

    if($id_login==$user_id){
		// ACTION Update TO DB
	    $wpdb->update(
            $table_name, //table
            array(
	            'data' => $logo_url
	        ),
            array('type' => 'logo_url'), //where
            array('%s'), //data format
            array('%s') //where format    
        );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_upload_app_logo', 'djafunction_upload_app_logo' );
add_action( 'wp_ajax_nopriv_djafunction_upload_app_logo', 'djafunction_upload_app_logo' );



function djafunction_update_themes_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $app_name  	 = $_POST['datanya'][0];
    $theme_color = str_replace('\\', '', $_POST['datanya'][1]);

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $app_name ), array('type' => 'app_name'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $theme_color ), array('type' => 'theme_color'), array('%s'), array('%s') );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_themes_settings', 'djafunction_update_themes_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_themes_settings', 'djafunction_update_themes_settings' );



function djafunction_update_form_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $page_donate  	= $_POST['datanya'][0];
    $form_setting 	= $_POST['datanya'][1];
    $opt_nominal 	= str_replace('\\', '', $_POST['datanya'][2]);
    $max_package 	= $_POST['datanya'][3];
    $anonim_text 	= $_POST['datanya'][4];
    $page_typ 		= $_POST['datanya'][5];
    $form_text 		= str_replace('\\', '', $_POST['datanya'][6]);
    $form_email_setting 	= $_POST['datanya'][7];
    $form_comment_setting 	= $_POST['datanya'][8];
    $limitted_donation_button 	= $_POST['datanya'][9];
    $form_confirmation_setting 	= $_POST['datanya'][10];
    $minimum_donate 	= $_POST['datanya'][11];


    if($form_setting==''){
    	$form_setting = 0;
    }else{
    	$form_setting = 1;
    }

    if($form_email_setting==''){
    	$form_email_setting = 0;
    }else{
    	$form_email_setting = 1;
    }

    if($form_comment_setting==''){
    	$form_comment_setting = 0;
    }else{
    	$form_comment_setting = 1;
    }

    if($limitted_donation_button==''){
    	$limitted_donation_button = 0;
    }else{
    	$limitted_donation_button = 1;
    }

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $page_donate ), array('type' => 'page_donate'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $form_setting ), array('type' => 'form_setting'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $opt_nominal ), array('type' => 'opt_nominal'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $max_package ), array('type' => 'max_package'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $anonim_text ), array('type' => 'anonim_text'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $page_typ ), array('type' => 'page_typ'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $form_text ), array('type' => 'form_text'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $form_email_setting ), array('type' => 'form_email_setting'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $form_comment_setting ), array('type' => 'form_comment_setting'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $limitted_donation_button ), array('type' => 'limitted_donation_button'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $form_confirmation_setting ), array('type' => 'form_confirmation_setting'), array('%s'), array('%s') );
        $wpdb->update( $table_name,array( 'data' => $minimum_donate ), array('type' => 'minimum_donate'), array('%s'), array('%s') );
        

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_form_settings', 'djafunction_update_form_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_form_settings', 'djafunction_update_form_settings' );



function djafunction_del_category(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_category";

    // FROM INPUT
    $id 	= $_POST['datanya'][0];

    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {
		// then delete
        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );
    }

	echo 'success';

    wp_die();
}
add_action( 'wp_ajax_djafunction_del_category', 'djafunction_del_category' );
add_action( 'wp_ajax_nopriv_djafunction_del_category', 'djafunction_del_category' );



function djafunction_add_new_category() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_category";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $code  		= $_POST['datanya'][0];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
		if($code=='add_new'){
			$wpdb->insert( 
				$table_name, 
				array(
					'category' => 'New',
				) 
			);
	        echo 'success';
		}
    }else{
    	echo 'Not allowed.';
    }

    wp_die();

} 
add_action( 'wp_ajax_djafunction_add_new_category', 'djafunction_add_new_category' );
add_action( 'wp_ajax_nopriv_djafunction_add_new_category', 'djafunction_add_new_category' );


function djafunction_save_category() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_category";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $id  		= $_POST['datanya'][0];
    $category  	= $_POST['datanya'][1];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'category' => $category ), array('id' => $id), array('%s'), array('%s') );
        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_save_category', 'djafunction_save_category' );
add_action( 'wp_ajax_nopriv_djafunction_save_category', 'djafunction_save_category' );



function djafunction_update_category_private() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_category";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $id  		= $_POST['datanya'][0];
    $value  	= $_POST['datanya'][1];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'private_category' => $value ), array('id' => $id), array('%s'), array('%s') );
        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_category_private', 'djafunction_update_category_private' );
add_action( 'wp_ajax_nopriv_djafunction_update_category_private', 'djafunction_update_category_private' );


function get_data_campaign($id){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";

    $data_campaign = $wpdb->get_results("SELECT id FROM $table_name where category_id='$id' ");
	return count($data_campaign);
}



function djafunction_update_socialproof() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $socialproof_text  		= $_POST['datanya'][0];
    $socialproof_settings  	= str_replace('\\', '', $_POST['datanya'][1]);

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $socialproof_text ), array('type' => 'socialproof_text'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $socialproof_settings ), array('type' => 'socialproof_settings'), array('%s'), array('%s') );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_socialproof', 'djafunction_update_socialproof' );
add_action( 'wp_ajax_nopriv_djafunction_update_socialproof', 'djafunction_update_socialproof' );



function djafunction_update_general_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $login_setting  	= $_POST['datanya'][0];
    $page_login  		= $_POST['datanya'][1];
    $login_text  		= $_POST['datanya'][2];
    $register_setting   = $_POST['datanya'][3];
    $page_register  	= $_POST['datanya'][4];
    $register_text  	= $_POST['datanya'][5];
    $campaign_setting  	= $_POST['datanya'][6];
    $del_campaign_setting  = $_POST['datanya'][7];
    $label_tab  		= $_POST['datanya'][8];
    $max_love  			= $_POST['datanya'][9];
    $max_love_custom	= $_POST['datanya'][10];
    $powered_by_setting	= $_POST['datanya'][11];
    $fb_pixel			= $_POST['datanya'][12];
    $fb_event			= str_replace('\\', '', $_POST['datanya'][13]);
    $jquery_on			= $_POST['datanya'][14];
    $gtm_id				= $_POST['datanya'][15];
    $changepass_setting	= $_POST['datanya'][16];
    $tiktok_pixel		= $_POST['datanya'][17];
    $register_checkbox_setting	= $_POST['datanya'][18];
    $register_checkbox_info		= $_POST['datanya'][19];
    $jquery_custom		= $_POST['datanya'][20];
    

    if($login_setting==''){
    	$login_setting = 0;
    }else{
    	$login_setting = 1;
    }

    if($register_setting==''){
    	$register_setting = 0;
    }else{
    	$register_setting = 1;
    }
    
    if($campaign_setting==''){
    	$campaign_setting = 0;
    }else{
    	$campaign_setting = 1;
    }
    
    if($del_campaign_setting==''){
    	$del_campaign_setting = 0;
    }else{
    	$del_campaign_setting = 1;
    }

    if($max_love!='0'){
    	$max_love = $max_love_custom;
    }

    if($powered_by_setting==''){
    	$powered_by_setting = 0;
    }else{
    	$powered_by_setting = 1;
    }

    if($jquery_on==''){
    	$jquery_on = 0;
    }

    if($changepass_setting==''){
    	$changepass_setting = 0;
    }else{
    	$changepass_setting = 1;
    }

    if($register_checkbox_setting==''){
    	$register_checkbox_setting = 0;
    }else{
    	$register_checkbox_setting = 1;
    }

    

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $login_setting ), array('type' => 'login_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $page_login ), array('type' => 'page_login'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $login_text ), array('type' => 'login_text'), array('%s'), array('%s') );

	    $wpdb->update( $table_name,array( 'data' => $register_setting ), array('type' => 'register_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $page_register ), array('type' => 'page_register'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $register_text ), array('type' => 'register_text'), array('%s'), array('%s') );
	    
	    $wpdb->update( $table_name,array( 'data' => $campaign_setting ), array('type' => 'campaign_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $del_campaign_setting ), array('type' => 'del_campaign_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $label_tab ), array('type' => 'label_tab'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $max_love ), array('type' => 'max_love'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $powered_by_setting ), array('type' => 'powered_by_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fb_pixel ), array('type' => 'fb_pixel'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fb_event ), array('type' => 'fb_event'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $jquery_on ), array('type' => 'jquery_on'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $gtm_id ), array('type' => 'gtm_id'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $changepass_setting ), array('type' => 'changepass_setting'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $tiktok_pixel ), array('type' => 'tiktok_pixel'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $register_checkbox_setting ), array('type' => 'register_checkbox_setting'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $register_checkbox_info ), array('type' => 'register_checkbox_info'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $jquery_custom ), array('type' => 'jquery_custom'), array('%s'), array('%s') );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_general_settings', 'djafunction_update_general_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_general_settings', 'djafunction_update_general_settings' );



function djafunction_update_payment_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $payment_setting = str_replace('\\', '', $_POST['datanya'][0]);
    $bank_account  	 = str_replace('\\', '', $_POST['datanya'][1]);
    $unique_number_setting  = $_POST['datanya'][2];
    $unique_number_value  	= str_replace('\\', '', $_POST['datanya'][3]);

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $payment_setting ), array('type' => 'payment_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $bank_account ), array('type' => 'bank_account'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $unique_number_setting ), array('type' => 'unique_number_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $unique_number_value ), array('type' => 'unique_number_value'), array('%s'), array('%s') );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

}

add_action( 'wp_ajax_djafunction_update_payment_settings', 'djafunction_update_payment_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_payment_settings', 'djafunction_update_payment_settings' );


function djafunction_update_ipaymu_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $ipaymu_mode 	= $_POST['datanya'][0];
    $ipaymu_va  	= $_POST['datanya'][1];
    $ipaymu_apikey  = $_POST['datanya'][2];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $ipaymu_mode ), array('type' => 'ipaymu_mode'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $ipaymu_va ), array('type' => 'ipaymu_va'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $ipaymu_apikey ), array('type' => 'ipaymu_apikey'), array('%s'), array('%s') );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_ipaymu_settings', 'djafunction_update_ipaymu_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_ipaymu_settings', 'djafunction_update_ipaymu_settings' );



function djafunction_update_tripay_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $tripay_mode 		= $_POST['datanya'][0];
    $tripay_apikey  	= $_POST['datanya'][1];
    $tripay_privatekey  = $_POST['datanya'][2];
    $tripay_merchant  	= $_POST['datanya'][3];
    $tripay_apikey_sandbox  	= $_POST['datanya'][4];
    $tripay_privatekey_sandbox  = $_POST['datanya'][5];
    $tripay_merchant_sandbox  	= $_POST['datanya'][6];
    $tripay_qris  		= $_POST['datanya'][7];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $tripay_mode ), array('type' => 'tripay_mode'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $tripay_apikey ), array('type' => 'tripay_apikey'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $tripay_privatekey ), array('type' => 'tripay_privatekey'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $tripay_merchant ), array('type' => 'tripay_merchant'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $tripay_apikey_sandbox ), array('type' => 'tripay_apikey_sandbox'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $tripay_privatekey_sandbox ), array('type' => 'tripay_privatekey_sandbox'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $tripay_merchant_sandbox ), array('type' => 'tripay_merchant_sandbox'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $tripay_qris ), array('type' => 'tripay_qris'), array('%s'), array('%s') );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_tripay_settings', 'djafunction_update_tripay_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_tripay_settings', 'djafunction_update_tripay_settings' );


function djafunction_update_midtrans_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $midtrans_mode 		 = $_POST['datanya'][0];
    $midtrans_serverkey  = $_POST['datanya'][1];
    $midtrans_clientkey  = $_POST['datanya'][2];
    $midtrans_merchant   = $_POST['datanya'][3];
    $midtrans_serverkey_sandbox  = $_POST['datanya'][4];
    $midtrans_clientkey_sandbox  = $_POST['datanya'][5];
    $midtrans_merchant_sandbox   = $_POST['datanya'][6];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $midtrans_mode ), array('type' => 'midtrans_mode'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $midtrans_serverkey ), array('type' => 'midtrans_serverkey'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $midtrans_clientkey ), array('type' => 'midtrans_clientkey'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $midtrans_merchant ), array('type' => 'midtrans_merchant'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $midtrans_serverkey_sandbox ), array('type' => 'midtrans_serverkey_sandbox'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $midtrans_clientkey_sandbox ), array('type' => 'midtrans_clientkey_sandbox'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $midtrans_merchant_sandbox ), array('type' => 'midtrans_merchant_sandbox'), array('%s'), array('%s') );

        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_midtrans_settings', 'djafunction_update_midtrans_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_midtrans_settings', 'djafunction_update_midtrans_settings' );


function djafunction_update_moota_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $moota_secret_token = $_POST['datanya'][0];
    $moota_range = $_POST['datanya'][1];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $moota_secret_token ), array('type' => 'moota_secret_token'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $moota_range ), array('type' => 'moota_range'), array('%s'), array('%s') );
	
        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_moota_settings', 'djafunction_update_moota_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_moota_settings', 'djafunction_update_moota_settings' );


function djafunction_update_wanotif_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $wanotif_apikey = $_POST['datanya'][0];
    $wanotif_message = str_replace('\\', '', $_POST['datanya'][1]);
    $wanotif_message2 = str_replace('\\', '', $_POST['datanya'][2]);
    $wanotif_followup1_on = $_POST['datanya'][3];
    $wanotif_on = $_POST['datanya'][4];
    $wanotif_apikey_cs = str_replace('\\', '', $_POST['datanya'][5]);

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $wanotif_apikey ), array('type' => 'wanotif_apikey'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $wanotif_message ), array('type' => 'wanotif_message'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $wanotif_message2 ), array('type' => 'wanotif_message2'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $wanotif_followup1_on ), array('type' => 'wanotif_followup1_on'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $wanotif_on ), array('type' => 'wanotif_on'), array('%s'), array('%s') );
		$wpdb->update( $table_name,array( 'data' => $wanotif_apikey_cs ), array('type' => 'wanotif_apikey_cs'), array('%s'), array('%s') );
	
        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_wanotif_settings', 'djafunction_update_wanotif_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_wanotif_settings', 'djafunction_update_wanotif_settings' );



function djafunction_update_telegram_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $telegram_send_to = str_replace('\\', '', $_POST['datanya'][0]);
    $telegram_send_to = str_replace('linebreak', '\n', $telegram_send_to);
    $telegram_on 		= $_POST['datanya'][1];
    $telegram_bot_token = $_POST['datanya'][2];
    $telegram_manual_confirmation = str_replace('\\', '', $_POST['datanya'][3]);
    $telegram_manual_confirmation = str_replace('linebreak', '\n', $telegram_manual_confirmation);

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $a = $wpdb->update( $table_name,array( 'data' => $telegram_send_to ), array('type' => 'telegram_send_to'), array('%s'), array('%s') );
	    $b = $wpdb->update( $table_name,array( 'data' => $telegram_on ), array('type' => 'telegram_on'), array('%s'), array('%s') );
	    $c = $wpdb->update( $table_name,array( 'data' => $telegram_bot_token ), array('type' => 'telegram_bot_token'), array('%s'), array('%s') );
	    $d = $wpdb->update( $table_name,array( 'data' => $telegram_manual_confirmation ), array('type' => 'telegram_manual_confirmation'), array('%s'), array('%s') );
	
        if($a === FALSE){
			echo 'failed';
		}else{
			echo 'success';
		}

    }else{
    	echo 'Not allowed.';
    }

    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_telegram_settings', 'djafunction_update_telegram_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_telegram_settings', 'djafunction_update_telegram_settings' );



function djafunction_update_email_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
    $email_send_to = str_replace('\\', '', $_POST['datanya'][0]);
    $email_on 	   = $_POST['datanya'][1];
    $email_success_message = str_replace('\\', '', $_POST['datanya'][2]);

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $a = $wpdb->update( $table_name,array( 'data' => $email_send_to ), array('type' => 'email_send_to'), array('%s'), array('%s') );
	    $b = $wpdb->update( $table_name,array( 'data' => $email_on ), array('type' => 'email_on'), array('%s'), array('%s') );
		$c = $wpdb->update( $table_name,array( 'data' => $email_success_message ), array('type' => 'email_success_message'), array('%s'), array('%s') );
	
        if($a === FALSE){
			echo 'failed';
		}else{
			echo 'success';
		}

    }else{
    	echo 'Not allowed.';
    }

    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_email_settings', 'djafunction_update_email_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_email_settings', 'djafunction_update_email_settings' );



function djafunction_upload_ktp_selfie() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "dja_verification_details";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 		= $_POST['datanya'][0];
    $u_ktp_selfie 	= $_POST['datanya'][1];

    if($id_login==$user_id){

    		$check = $wpdb->get_results('SELECT id from '.$table_name2.' where u_id="'.$user_id.'"');
			if($check!=null){

				// ACTION Update TO DB
			    $wpdb->update(
		            $table_name2, //table
		            array(
			            'u_ktp_selfie' => $u_ktp_selfie
			        ),
		            array('u_id' => $user_id), //where
		            array('%s'), //data format
		            array('%s') //where format    
		        );

			}else{
				// ACTION INSERT TO DB
			    $wpdb->insert(
		            $table_name2, //table
		            array(
			            'u_id' 					=> $user_id,
			            'u_nama_lengkap'		=> null,
			            'u_email' 				=> null,
			            'u_whatsapp' 			=> null,
			            'u_ktp' 				=> null,
			            'u_ktp_selfie' 			=> $u_ktp_selfie,
			            'u_jabatan' 			=> null,
			            'u_nama_ketua' 			=> null,
			            'u_alamat_lengkap' 		=> null,
			            'u_program_unggulan' 	=> null,
			            'u_profile' 			=> null,
			            'u_legalitas' 			=> null,
			            'created_at' 			=> date("Y-m-d H:i:s")),
		            array('%s', '%s') //data format         
		        );
			}

	        echo 'success';


    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_upload_ktp_selfie', 'djafunction_upload_ktp_selfie' );
add_action( 'wp_ajax_nopriv_djafunction_upload_ktp_selfie', 'djafunction_upload_ktp_selfie' );



function djafunction_upload_legalitas() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "dja_verification_details";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 		= $_POST['datanya'][0];
    $u_legalitas 	= $_POST['datanya'][1];

    if($id_login==$user_id){

    		$check = $wpdb->get_results('SELECT id from '.$table_name2.' where u_id="'.$user_id.'"');
			if($check!=null){

				// ACTION Update TO DB
			    $wpdb->update(
		            $table_name2, //table
		            array(
			            'u_legalitas' => $u_legalitas
			        ),
		            array('u_id' => $user_id), //where
		            array('%s'), //data format
		            array('%s') //where format    
		        );

			}else{
				// ACTION INSERT TO DB
			    $wpdb->insert(
		            $table_name2, //table
		            array(
			            'u_id' 					=> $user_id,
			            'u_nama_lengkap'		=> null,
			            'u_email' 				=> null,
			            'u_whatsapp' 			=> null,
			            'u_ktp' 				=> null,
			            'u_ktp_selfie' 			=> null,
			            'u_jabatan' 			=> null,
			            'u_nama_ketua' 			=> null,
			            'u_alamat_lengkap' 		=> null,
			            'u_program_unggulan' 	=> null,
			            'u_profile' 			=> null,
			            'u_legalitas' 			=> $u_legalitas,
			            'created_at' 			=> date("Y-m-d H:i:s")),
		            array('%s', '%s') //data format         
		        );
			}

	        echo 'success';


    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_upload_legalitas', 'djafunction_upload_legalitas' );
add_action( 'wp_ajax_nopriv_djafunction_upload_legalitas', 'djafunction_upload_legalitas' );



function djafunction_submit_verification() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "dja_verification_details";
    $table_name3 = $wpdb->prefix . "dja_settings";    

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 		= $_POST['datanya'][0];
    $action 		= $_POST['datanya'][1];
    
    $u_nama_lengkap = $_POST['datanya'][2];
    $u_email 		= $_POST['datanya'][3];
    $u_whatsapp 	= $_POST['datanya'][4];
    $u_ktp 			= $_POST['datanya'][5];
    $u_ktp_selfie 	= $_POST['datanya'][6];
    $u_jabatan 		= $_POST['datanya'][7];

    $u_nama_ketua 	= $_POST['datanya'][8];
    $u_alamat_lengkap 	= $_POST['datanya'][9];
    $u_program_unggulan = $_POST['datanya'][10];
    $u_profile 		= $_POST['datanya'][11];
    $u_legalitas 	= $_POST['datanya'][12];

    $akun_user = $_POST['datanya'][13];

    $u_nama_lengkap = str_replace('\\', '', $u_nama_lengkap);

    // get email settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name3.' where type="email_on" ORDER BY id ASC');
    $email_on 		  = $query_settings[0]->data;

    if($id_login==$user_id){

    		$check = $wpdb->get_results('SELECT id from '.$table_name2.' where u_id="'.$user_id.'"');
			if($check!=null){

				if($akun_user=='personal'){
					// ACTION Update TO DB
				    $wpdb->update(
			            $table_name2, //table
			            array(
				            'u_nama_lengkap'		=> $u_nama_lengkap,
				            'u_email' 				=> $u_email,
				            'u_whatsapp' 			=> $u_whatsapp
				        ),
			            array('u_id' => $user_id), //where
			            array('%s'), //data format
			            array('%s') //where format    
			        );
				}else{
					// ORGANISASI
					// ACTION Update TO DB
				    $wpdb->update(
			            $table_name2, //table
			            array(
				            'u_nama_lengkap'		=> $u_nama_lengkap,
				            'u_email' 				=> $u_email,
				            'u_whatsapp' 			=> $u_whatsapp,
				            'u_jabatan' 			=> $u_jabatan,
				            'u_nama_ketua' 			=> $u_nama_ketua,
				            'u_alamat_lengkap' 		=> $u_alamat_lengkap,
				            'u_program_unggulan' 	=> $u_program_unggulan,
				            'u_profile' 			=> $u_profile
				        ),
			            array('u_id' => $user_id), //where
			            array('%s'), //data format
			            array('%s') //where format    
			        );
				}

				if($action=='submit'){

					// Update type user and verification 2 = on Review
				    $wpdb->update(
			            $table_name, //table
			            array(
				            'user_type'	=> $akun_user,
				            'user_verification'	=> 2,

				        ),
			            array('user_id' => $user_id), //where
			            array('%s'), //data format
			            array('%s') //where format    
			        );

			        if($email_on=='1'){
				    	$message = '<p>Terimakasih telah menginput data verifikasi, akun anda masih dalam proses Review.</p>';
				        $subject = 'Your account is on Review';
				        $emailnya = $u_email;
				        $headers[] = 'Content-Type: text/html; charset=UTF-8';
				        $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';
							
						if($emailnya!=''){
							wp_mail( $emailnya, $subject, $body, $headers );
						}
				    }

					echo 'success_to_submit';

				}else{
					// Only Update type user
				    $wpdb->update(
			            $table_name, //table
			            array(
				            'user_type'	=> $akun_user
				        ),
			            array('user_id' => $user_id), //where
			            array('%s'), //data format
			            array('%s') //where format    
			        );

					echo 'success_to_draft';
				}
					
			}else{
				echo 'failed.';
			}


    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_submit_verification', 'djafunction_submit_verification' );
add_action( 'wp_ajax_nopriv_djafunction_submit_verification', 'djafunction_submit_verification' );



function djafunction_update_cover_img() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "users";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 	= $_POST['datanya'][0];
    $user_cover_img = $_POST['datanya'][1];
    
    if($id_login==$user_id){

		    // ACTION Update TO DB
		    $wpdb->update(
	            $table_name, //table
	            array(
		            'user_cover_img' => $user_cover_img
		        ),
	            array('user_id' => $user_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );

	        echo 'success';


    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_cover_img', 'djafunction_update_cover_img' );
add_action( 'wp_ajax_nopriv_djafunction_update_cover_img', 'djafunction_update_cover_img' );



// Load Data Donatur Only
function djafunction_load_data_donatur() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_love";
    $table_name3 = $wpdb->prefix . "dja_campaign";
    
    $id = $_POST['datanya'][0];
    $campaign_id = $_POST['datanya'][1];
    $load_count = $_POST['datanya'][2];
    $anonim_text = $_POST['datanya'][3];
    $fullanonim = $_POST['datanya'][4];
    $start = ($load_count-1)*5;
    $limit = 5;

    $value = $wpdb->get_results("SELECT * FROM $table_name3 where campaign_id='$campaign_id' ")[0];
    $general_status = $value->general_status;
    $allocation_title = $value->allocation_title;
    $allocation_others_title = $value->allocation_others_title;

    if($general_status=='1'){
        if($allocation_title=='1'){
            $allocation_title = 'Donasi';
        }elseif($allocation_title=='2'){
            $allocation_title = 'Zakat';
        }else{
            $allocation_title = $allocation_others_title;
        }
    }else{
        $allocation_title = 'Donasi';
    }

    if($campaign_id!=''){
  		
		$donation_paid = $wpdb->get_results("SELECT * FROM $table_name where campaign_id='$campaign_id' and status='1' ORDER BY id DESC LIMIT $start,$limit ");

		// $num = 1;
		$jumlah_arr = count($donation_paid);
		$the_data = '';
		foreach ($donation_paid as $value) {
			

			$donatur_name = $value->name;
			$anonim = 'Orang Baik';
			if($value->anonim=='1'){
				$donatur_name = $anonim_text;
			}

			// time
			$readtime = new donasiaja_readtime();
			$fix_time = $readtime->time_donation($value->created_at);

			// nominal
			$nominal = 'Rp '.number_format($value->nominal,0,",",".");

			// strtolower($allocation_title)

        	$the_data .= '
	        <div class="donation_inner_box" style="background:rgb(250, 252, 255);">
	            <div class="donation_name">'.$donatur_name.'<span class="donation_time"><span class="dashicons dashicons-clock"></span>'.$fix_time.'</span>
	            </div>
	            <div class="donation_total">Ber'.strtolower($allocation_title).' sebesar <b>'.$nominal.'</b></div>    	
	        </div>
	        ';
			

		}

		echo $the_data;

  	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_load_data_donatur', 'djafunction_load_data_donatur' );
add_action( 'wp_ajax_nopriv_djafunction_load_data_donatur', 'djafunction_load_data_donatur' );



// Load Data Donatur and Comment
function djafunction_load_list_donatur() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_love";
    $table_name3 = $wpdb->prefix . "dja_campaign";

    $id = $_POST['datanya'][0];
    $campaign_id = $_POST['datanya'][1];
    $load_count = $_POST['datanya'][2];
    $anonim_text = $_POST['datanya'][3];
    $fullanonim = $_POST['datanya'][4];
    $start = ($load_count-1)*5;
    $limit = 5;

    // Campaign Settings
	$value = $wpdb->get_results("SELECT * FROM $table_name3 where campaign_id='$campaign_id' ")[0];
    

    if($campaign_id!=''){
  		
		$donation_paid = $wpdb->get_results("SELECT * FROM $table_name where campaign_id='$campaign_id' and status='1' ORDER BY id DESC LIMIT $start,$limit ");

		// $num = 1;
		$jumlah_arr = count($donation_paid);
		$the_data = '';

		foreach ($donation_paid as $data) {

			$readtime = new donasiaja_readtime();
			$donation_time = $readtime->time_donation($data->created_at);

			$general_status = $value->general_status;
		    $allocation_title = $value->allocation_title;
		    $allocation_others_title = $value->allocation_others_title;

		    if($general_status=='1'){
		        if($allocation_title=='1'){
		            $allocation_title = 'Donasi';
		        }elseif($allocation_title=='2'){
		            $allocation_title = 'Zakat';
		        }else{
		            $allocation_title = $allocation_others_title;
		        }
		    }else{
		        $allocation_title = 'Donasi';
		    }

		    $donatur_name = $data->name;
			$anonim = 'Orang Baik';
			if($data->anonim=='1'){
				$donatur_name = $anonim_text;
			}

			$the_data .= '

			        <div class="donation_inner_box" style="background:#ffffff;">
			            <div class="donation_name">'.$donatur_name.'<span class="donation_time"><span class="dashicons dashicons-clock"></span>'.$donation_time.'</span>
			            </div>
			            <div class="donation_total" style="color: #23374d;font-weight:normal;">'.$allocation_title.' <b>Rp '.number_format($data->nominal,0,",",".").'</b></div>
			            <div class="donation_comment">'.$data->comment.'</div>
			        </div>
				        
				
			';
		}

		echo $the_data;

  	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_load_list_donatur', 'djafunction_load_list_donatur' );
add_action( 'wp_ajax_nopriv_djafunction_load_list_donatur', 'djafunction_load_list_donatur' );



// LOAD FUNDRAISER
function djafunction_load_fundraiser() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_aff_code";
    $table_name3 = $wpdb->prefix . "dja_aff_submit";
    $table_name4 = $wpdb->prefix . "users";
    $table_name5 = $wpdb->prefix . "dja_settings";
    
    $id = $_POST['datanya'][0];
    $campaign_id = $_POST['datanya'][1];
    $load_count = $_POST['datanya'][2];
    $anonim_text = $_POST['datanya'][3];
    $fullanonim = $_POST['datanya'][4];
    $start = ($load_count-1)*5;
    $limit = 5;

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="theme_color" ORDER BY id ASC');
    $general_theme_color = json_decode($query_settings[0]->data, true);

    // set the color
    $theme_color 		= $general_theme_color['color'][0];
	$progressbar_color  = $general_theme_color['color'][1];
	$button_color 		= $general_theme_color['color'][2];

	if($button_color==''){
		$button_color = '#dc2f6a';
	}

	$hex = $button_color;
	list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
	$colornya = 'rgba('.$r.','.$g.','.$b.', 0.05)';
	$color_hovernya = 'rgba('.$r.','.$g.','.$b.', 0.15)';

    if($campaign_id!=''){
  		
    	$get_fundraiser = $wpdb->get_results("SELECT a.campaign_id, c.user_id as fundraiser_id, count(a.id) as jumlah_donatur, sum(b.nominal) as total
	FROM $table_name3  a 
	LEFT JOIN $table_name2 c on c.id = a.affcode_id 
	LEFT JOIN $table_name b on b.id = a.donate_id 
	where a.campaign_id='$campaign_id' and b.status = '1'
	GROUP BY fundraiser_id ORDER BY total DESC limit $start,$limit ");

		foreach ($get_fundraiser as $value) {
	    	$user_info = get_userdata($value->fundraiser_id);
		    $fullname = $user_info->first_name.' '.$user_info->last_name;

	    	
        	$the_data .= '
	       	<div class="donation_inner_box" style="background:rgb(250, 252, 255);line-height:1.6;">
	            <div class="donation_name" style="color:'.$button_color.'">'.$fullname.'</div>
	            <div class="donation_comment" style="margin:0;">Berhasil mengajak '.$value->jumlah_donatur.' orang untuk berdonasi.<br></div>
	            <div class="donation_name">Rp&nbsp;'.number_format($value->total,0,",",".").'</div>
	        </div>
	        ';

		}

		echo $the_data;

  	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_load_fundraiser', 'djafunction_load_fundraiser' );
add_action( 'wp_ajax_nopriv_djafunction_load_fundraiser', 'djafunction_load_fundraiser' );



// LOAD DOA DONATUR
function djafunction_load_doa_donatur() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_love";
    
    $id = $_POST['datanya'][0];
    $campaign_id = $_POST['datanya'][1];
    $load_count = $_POST['datanya'][2];
    $anonim_text = $_POST['datanya'][3];
    $fullanonim = $_POST['datanya'][4];
    $start = ($load_count-1)*5;
    $limit = 5;

    if($campaign_id!=''){
  		
  		$donation_paid = $wpdb->get_results("SELECT * FROM $table_name where campaign_id='$campaign_id' and status='1' and comment!='' ORDER BY id DESC limit $start,$limit ");

		// $donation_paid = $wpdb->get_results("SELECT * FROM $table_name where status='1' ORDER BY id DESC LIMIT $start,$limit ");

		// $num = 1;
		$jumlah_arr = count($donation_paid);
		$the_data = '';
		foreach ($donation_paid as $value) {
			

			$donatur_name_pre = $value->name;
			if(strlen($donatur_name_pre) > 15) {
				$donatur_name = "";
				$words = explode(" ", "$donatur_name_pre");
				
				if(strpos($donatur_name_pre, " ") > 10) {
					$donatur_name = $words[0] . ' .' . $words[1][0];
				} else {
					$donatur_name = $words[0] . ' ' . $words[1] . ' .' . $words[2][0];
				}
			} else {
				$donatur_name = $donatur_name_pre;
			}
			$anonim = 'Orang Baik';
			if($value->anonim=='1'){
				$donatur_name = $anonim_text;
			}

			// time
			$readtime = new donasiaja_readtime();
			$fix_time = $readtime->time_donation($value->created_at);

			// nominal
			$nominal = 'Rp '.number_format($value->nominal,0,",",".");

			$id_login = wp_get_current_user()->ID;

			$a = donasiaja_getIP();
		    $b = donasiaja_getOS();
		    $c = donasiaja_getBrowser();
		    $d = donasiaja_getMobDesktop();
			if($id_login!='0'){
				// cek berdasarkan user_agent
				$row = $wpdb->get_results('SELECT * from '.$table_name2.' where ip="'.$a.'" and os="'.$b.'" and browser="'.$c.'" and mobdesktop="'.$d.'" and donate_id="'.$value->id.'"');
		    	if($row!=null){
		    		$left_span = '<span>';
		    		$right_span = '</span>';
		    		$icon_love = DJA_PLUGIN_URL . 'assets/icons/praying-color3.png';
		    	}else{
		    		$left_span = '';
		    		$right_span = '';
		    		$icon_love = DJA_PLUGIN_URL . 'assets/icons/praying-gray.png';
		    	}
			}else{
				// cek berdasarkan user_id
				$row = $wpdb->get_results('SELECT * from '.$table_name2.' where user_id="'.$id_login.'" ');
		    	if($row!=null){
		    		$left_span = '<span>';
		    		$right_span = '</span>';
		    		$icon_love = DJA_PLUGIN_URL . 'assets/icons/praying-color3.png';
		    	}else{
		    		$left_span = '';
		    		$right_span = '';
		    		$icon_love = DJA_PLUGIN_URL . 'assets/icons/praying-gray.png';
		    	}
			}


			$total_love = $wpdb->get_results("SELECT SUM(love) as jumlah FROM $table_name2 where donate_id='$value->id' ")[0];
			$love = $total_love->jumlah;
			if($love==0){
				$love = 'Aaminn-kan doa ini';
			}else{
				if($love>=2){
					$love = $left_span.$love.' Aaminn'.$right_span;
				}else{
					$love = $left_span.$love.' Aaminn'.$right_span;
				}
			}


        	$the_data .= '
	        <div class="donation_inner_box">
	            <div class="donation_name">'.$donatur_name.'<span class="donation_time"><span class="dashicons dashicons-clock"></span>'.$fix_time.'</span>
	            </div>
	            <div class="donation_comment">'.str_replace('\\', '', $value->comment).'<br></div>
	            <div class="donation_love" id="love_'.$value->id.'" data-donateid="'.$value->id.'" data-campaignid="'.$value->campaign_id.'">
	            	
	            	<div class="fancy-button">
					  <div class="left-frills frills"></div>
					  <div class="box_love">
					  	<img alt="Image" src="'.$icon_love.'">
					  	<div class="total_love">'.$love.'</div>
					  	<div class="plus1">+1</div>
					  </div>
					  <div class="right-frills frills"></div>
					</div>

				</div>
	            	
	        </div>
	        ';

		}

		echo $the_data;

  	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_load_doa_donatur', 'djafunction_load_doa_donatur' );
add_action( 'wp_ajax_nopriv_djafunction_load_doa_donatur', 'djafunction_load_doa_donatur' );


