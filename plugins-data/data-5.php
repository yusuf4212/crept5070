<?php

function djafunction_update_csrotator() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_settings";
    
    // FROM INPUT
    $campaign_id 	= $_POST['datanya'][0];
    $cs_rotator  = str_replace('\\', '', $_POST['datanya'][1]);

    $a = $wpdb->update(
	            $table_name, //table
	            array(
		            'cs_rotator' => $cs_rotator
		        ),
	            array('campaign_id' => $campaign_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );

	if($a === FALSE){
		echo 'failed_'.$campaign_id;
	}else{
		echo 'success_'.$campaign_id;
	}

	// }else{
	// 	echo 'failed_0';
	// }

    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_csrotator', 'djafunction_update_csrotator' );
add_action( 'wp_ajax_nopriv_djafunction_update_csrotator', 'djafunction_update_csrotator' );



function djafunction_update_campaign() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_settings";
    
    // FROM INPUT
    $campaign_id 	= $_POST['datanya'][0];
    $title 			= $_POST['datanya'][1];
    $slug 			= $_POST['datanya'][2];
    $image_url 		= $_POST['datanya'][3];
    $information 	= $_POST['datanya'][4];
    $target 		= $_POST['datanya'][5];
    $end_date 		= $_POST['datanya'][6];
    $location_name 	= $_POST['datanya'][7];
    $location_gmaps = $_POST['datanya'][8];
    $category_id 	= $_POST['datanya'][9];
    $publish_status = $_POST['datanya'][10];
    $id 			= $_POST['datanya'][11];
    $form_base 		= $_POST['datanya'][12];
    $form_type 		= $_POST['datanya'][13];
    $packaged 		= $_POST['datanya'][14];
    $packaged_title = $_POST['datanya'][15];
    $act 			= $_POST['datanya'][16];
    $payment_status = $_POST['datanya'][17];
    $bank_account 	= str_replace('\\', '', $_POST['datanya'][18]);
    $form_status 	= $_POST['datanya'][19];
    $form_text 		= str_replace('\\', '', $_POST['datanya'][20]);
    $unique_number_setting  = $_POST['datanya'][21];
    $unique_number_value  	= str_replace('\\', '', $_POST['datanya'][22]);
    $method_status  		= str_replace('\\', '', $_POST['datanya'][23]);
    $notification_status 	= $_POST['datanya'][24];
    $wanotif_message 		= str_replace('\\', '', $_POST['datanya'][25]);
    $pixel_status			= $_POST['datanya'][26];
    $fb_pixel				= $_POST['datanya'][27];
    $fb_event				= str_replace('\\', '', $_POST['datanya'][28]);
    $pengeluaran_setting 	= $_POST['datanya'][29];
    $pengeluaran_title 		= $_POST['datanya'][30];
    $gtm_status 			= $_POST['datanya'][31];
    $gtm_id 				= $_POST['datanya'][32];
    $socialproof 			= $_POST['datanya'][33];
    $socialproof_text 		= $_POST['datanya'][34];
    $socialproof_position 	= $_POST['datanya'][35];
    $tiktok_status 			= $_POST['datanya'][36];
    $tiktok_pixel 			= $_POST['datanya'][37];
    $zakat_setting 			= $_POST['datanya'][38];
    $zakat_percent 			= $_POST['datanya'][39];
    $fundraiser_setting 	= $_POST['datanya'][40];
    $fundraiser_on 			= $_POST['datanya'][41];
    $fundraiser_text 		= $_POST['datanya'][42];
    $fundraiser_button 		= $_POST['datanya'][43];
    $fundraiser_commission_on 		= $_POST['datanya'][44];
    $fundraiser_commission_type 	= $_POST['datanya'][45];
    $fundraiser_commission_percent 	= $_POST['datanya'][46];
    $fundraiser_commission_fixed   	= $_POST['datanya'][47];
    $additional_info				= str_replace('\\', '', $_POST['datanya'][48]);
    $additional_formula				= str_replace('\\', '', $_POST['datanya'][49]);
    $additional_field				= str_replace('\\', '', $_POST['datanya'][50]);
    $custom_field_setting			= str_replace('\\', '', $_POST['datanya'][51]);
    $general_status					= str_replace('\\', '', $_POST['datanya'][52]);
    $allocation_title				= str_replace('\\', '', $_POST['datanya'][53]);
    $allocation_others_title		= str_replace('\\', '', $_POST['datanya'][54]);
    $donatur_name					= str_replace('\\', '', $_POST['datanya'][55]);
    $donatur_others_name			= str_replace('\\', '', $_POST['datanya'][56]);
    $home_icon_url   		= $_POST['datanya'][57];
    $back_icon_url   		= $_POST['datanya'][58];
    $opt_nominal   			= str_replace('\\', '', $_POST['datanya'][59]);
    $minimum_donate   		= $_POST['datanya'][60];
    $cs_rotator   			= str_replace('\\', '', $_POST['datanya'][61]);
    $wanotif_device   		= $_POST['datanya'][62];


    $title = str_replace("'", "&#39;", $title); // petik 1
    $title = str_replace('"', "&#34;", $title); // petik 2
    $title = str_replace('\\', '', $title); // backslash

    $slug = str_replace('?', '', $slug); // backslash

    // $information = str_replace("'", "&#39;", $information); // petik 1
    // $information = str_replace('"', "&#34;", $information); // petik 2
    $information = str_replace('\\', '', $information); // backslash
    // $information = str_replace('../wp-content', home_url().'/wp-content', $information); // backslash 
    // $information = 'oke';

    // Settingsdjafunction_update_info
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="form_setting" ORDER BY id ASC');
    $form_setting = $query_settings[0]->data;

    // role
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

    // get data campaign to check user cannot modify the campaign id
    $row = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'" and id="'.$id.'"');

    if($row!=null){

    	// publish status form donatur, status can not change from page editor. set default from data campaign
	    if($role=='donatur'){
	    	$publish_status = $row[0]->publish_status;
	    }

	    // DEFAULT
	    $slug = d_formatUri($slug);

	    if($slug==''){
	    	$slug = d_formatUri($title);
	    }

	    if(strpos($image_url, 'donasiaja-cover.jpg') !== false) {
		    $image_url = null;
		}
		if($end_date==''){
			$end_date = null;
		}

	    // cek slug
    	$jumlah_slug = $wpdb->get_results("SELECT id from $table_name where slug='$slug' ");
    	if($jumlah_slug==null){
	    	$slug = $slug;
    	}else{

    		if(count($jumlah_slug)==1){
    			$jumlah_slug_bawaan = $wpdb->get_results("SELECT id from $table_name where slug='$slug' and campaign_id='$campaign_id' ");
    			if($jumlah_slug_bawaan!=null){
    				$slug = $slug;
    			}else{
    				$slug = $slug.'-'.d_randomString(3);
    			}
    		}else{
    			$count = count($jumlah_slug)+1;
		    	$slug = $slug.'-'.d_randomString(3);
    		}
    	}

    	// action status
    	if($act=='draft'){
    		$publish_status = 0;
    	}

		
		if($role=='donatur'){
    		
    		// tidak boleh setting form
    		if($form_setting!='1'){
    			$form_type = '1';
    			$packaged = '0';
    			$packaged_title = '';
    		}
    		if($act=='publish'){
	    		$publish_status = 2;
	    	}

	    	// ACTION Update TO DB tanpa form_type dan packaged
		 	$a = $wpdb->update(
	            $table_name, //table
	            array(
		            'campaign_id' 	=> $campaign_id,
		            'title' 		=> $title,
		            'slug' 			=> $slug,
		            'target' 		=> $target,
		            'image_url' 	=> $image_url,
		            'information' 	=> $information,
		            'location_name' => $location_name,
		            'location_gmaps'=> $location_gmaps,
		            'publish_status'=> $publish_status,
		            'category_id'	=> $category_id,
		            'end_date' 		=> $end_date,
		            'form_base' 	=> $form_base,
		            'form_type' 	=> $form_type,
		            'packaged' 		=> $packaged,
		            'packaged_title'=> $packaged_title,
		        ),
	            array('campaign_id' => $campaign_id), //where
	            array('%s'), //data format
	            array('%s') //where format
	        );
			
    	}else{
		
		    // ACTION Update TO DB
			$a = $wpdb->update(
	            $table_name, //table
	            array(
		            'campaign_id' 	=> $campaign_id,
		            'title' 		=> $title,
		            'slug' 			=> $slug,
		            'target' 		=> $target,
		            'image_url' 	=> $image_url,
		            'information' 	=> $information,
		            'location_name' => $location_name,
		            'location_gmaps'=> $location_gmaps,
		            'publish_status'=> $publish_status,
		            'category_id'	=> $category_id,
		            'end_date' 		=> $end_date,
		            'form_base' 	=> $form_base,
		            'form_type' 	=> $form_type,
		            'packaged' 		=> $packaged,
		            'packaged_title'=> $packaged_title,
		            'payment_status'=> $payment_status,
		            'method_status' => $method_status,
		            'bank_account'	=> $bank_account,
		            'form_status'	=> $form_status,
		            'form_text'		=> $form_text,
		            'unique_number_setting'	=> $unique_number_setting,
		            'unique_number_value'	=> $unique_number_value,
		            'notification_status'	=> $notification_status,
		            'wanotif_message'		=> $wanotif_message,
		            'pixel_status'			=> $pixel_status,
		            'fb_pixel'				=> $fb_pixel,
		            'fb_event'				=> $fb_event,
		            'pengeluaran_setting'	=> $pengeluaran_setting,
		            'pengeluaran_title'		=> $pengeluaran_title,
		            'gtm_status'			=> $gtm_status,
		            'gtm_id'				=> $gtm_id,
		            'socialproof'			=> $socialproof,
		            'socialproof_text'		=> $socialproof_text,
		            'socialproof_position'	=> $socialproof_position,
		            'tiktok_status'			=> $tiktok_status,
		            'tiktok_pixel'			=> $tiktok_pixel,
		            'zakat_setting'			=> $zakat_setting,
		            'zakat_percent'			=> $zakat_percent,
		            'fundraiser_setting'	=> $fundraiser_setting,
		            'fundraiser_on'			=> $fundraiser_on,
		            'fundraiser_text'		=> $fundraiser_text,
		            'fundraiser_button'		=> $fundraiser_button,
		            'fundraiser_commission_on'		=> $fundraiser_commission_on,
		            'fundraiser_commission_type'	=> $fundraiser_commission_type,
		            'fundraiser_commission_percent'	=> $fundraiser_commission_percent,
		            'fundraiser_commission_fixed'	=> $fundraiser_commission_fixed,
		            'additional_info'				=> $additional_info,
		            'additional_formula'			=> $additional_formula,
		            'additional_field'				=> $additional_field,
		            'custom_field_setting'			=> $custom_field_setting,
		            'general_status'				=> $general_status,
		            'allocation_title'				=> $allocation_title,
		            'allocation_others_title'		=> $allocation_others_title,
		            'donatur_name'					=> $donatur_name,
		            'donatur_others_name'			=> $donatur_others_name,
		            'home_icon_url'					=> $home_icon_url,
		            'back_icon_url'					=> $back_icon_url,
		            'minimum_donate'				=> $minimum_donate,
		            'opt_nominal'					=> $opt_nominal,
		            'cs_rotator'					=> $cs_rotator,
		            'wanotif_device'				=> $wanotif_device,
		        ),
	            array('campaign_id' => $campaign_id), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );

		 }

		if($a === FALSE){
			echo 'failed_'.$campaign_id;
		}else{
			echo 'success_'.$campaign_id;
		}

	}else{
		echo 'failed_0';
	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_campaign', 'djafunction_update_campaign' );
add_action( 'wp_ajax_nopriv_djafunction_update_campaign', 'djafunction_update_campaign' );



function djafunction_update_info() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign_update";
    
    // FROM INPUT
    $id 			= $_POST['datanya'][0];
    $campaign_id 	= $_POST['datanya'][1];
    $title 			= $_POST['datanya'][2];
    $information 	= $_POST['datanya'][3];

    $title = str_replace("'", "&#39;", $title); // petik 1
    $title = str_replace('"', "&#34;", $title); // petik 2
    $title = str_replace('\\', '', $title);

    // $information = str_replace("'", "&#39;", $information); // petik 1
    // $information = str_replace('"', "&#34;", $information); // petik 2
    $information = str_replace('\\', '', $information);


    // role
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

    $id_login = wp_get_current_user()->ID;


	if($role=='donatur'){

		// cek dulu sama gak dengan id si donatur
		$row = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'" and id="'.$id.'" and user_id="'.$id_login.'" ');

		if($row!=null){
			// ACTION Update TO DB tanpa form_type dan packaged
		    $wpdb->update(
	            $table_name, //table
	            array(
		            'title' 		=> $title,
		            'information' 	=> $information
		        ),
	            array('id' => $id, 'campaign_id' => $campaign_id, 'user_id' => $id_login), //where
	            array('%s'), //data format
	            array('%s') //where format    
	        );
	        echo $id;
		}else{
			echo 'failed';
		}
    	
	}else{
	    // ACTION Update TO DB
	    $wpdb->update(
            $table_name, //table
            array(
	            'title' 		=> $title,
	            'information' 	=> $information
	        ),
            array('id' => $id, 'campaign_id' => $campaign_id), //where
            array('%s'), //data format
            array('%s') //where format    
        );
        echo $id;
	}
	
    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_info', 'djafunction_update_info' );
add_action( 'wp_ajax_nopriv_djafunction_update_info', 'djafunction_update_info' );



function djafunction_delete_campaign() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_campaign_update";
    $table_name3 = $wpdb->prefix . "dja_donate";
    $table_name4 = $wpdb->prefix . "dja_love";
    $table_name5 = $wpdb->prefix . "dja_settings";

    
    // FROM INPUT
    $id 			= $_POST['datanya'][0];
    $campaign_id 	= $_POST['datanya'][1];

    // GET INFO DONATE
    $info_donate = $wpdb->get_results('SELECT COUNT(id) as jumlah from '.$table_name3.' where campaign_id="'.$campaign_id.'" ')[0];
    $jumlah_donate = $info_donate->jumlah;

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="del_campaign_setting" ORDER BY id ASC');
    $del_campaign_setting = $query_settings[0]->data;

    // role
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

    $id_login = wp_get_current_user()->ID;

    
	if($role=='donatur'){

		// cek dulu sama gak dengan id si donatur
		$row = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'" and id="'.$id.'" and user_id="'.$id_login.'" ');

		// jika data donasi sudah ada yang donasi, maka gak boleh hapus
		if($jumlah_donate!=0){

			if($del_campaign_setting!='1'){
				echo 'not allowed';
			}else{
				if($row!=null){
					// ACTION Update TO DB

				    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {
					// then delete
				        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );
				    }
				    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name2.' WHERE campaign_id = %d', $campaign_id ) ) ) {
						// then delete campaign update
				        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name2.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
				    }
				    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name3.' WHERE campaign_id = %d', $campaign_id ) ) ) {
						// then delete donate
				       $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name3.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
				    }
				    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name4.' WHERE campaign_id = %d', $campaign_id ) ) ) {
						// then delete love
				        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name4.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
				    }

				    echo 'success';

				}else{
					echo 'failed';
				}
			}
			
		}else{

			if($row!=null){
				// ACTION Update TO DB

			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {
					// then delete
			        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );
			    }
			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name2.' WHERE campaign_id = %d', $campaign_id ) ) ) {
					// then delete campaign update
			        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name2.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
			    }
			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name3.' WHERE campaign_id = %d', $campaign_id ) ) ) {
					// then delete donate
			       $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name3.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
			    }
			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name4.' WHERE campaign_id = %d', $campaign_id ) ) ) {
					// then delete love
			        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name4.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
			    }

			    echo 'success';

			}else{
				echo 'failed';
			}
			
		}

		
    	
	}else{

		if($jumlah_donate!=0){

			if($del_campaign_setting!='1'){
			    echo 'sudah_ada_donasi';
			}else{
				// ACTION Update TO DB
			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {
					// then delete
			        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );
			    }
			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name2.' WHERE campaign_id = %d', $campaign_id ) ) ) {
					// then delete campaign update
			        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name2.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
			    }
			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name3.' WHERE campaign_id = %d', $campaign_id ) ) ) {
					// then delete donate
			       $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name3.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
			    }
			    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name4.' WHERE campaign_id = %d', $campaign_id ) ) ) {
					// then delete love
			        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name4.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
			    }
			    echo 'success';
			}

		}else{

		    // ACTION Update TO DB
		    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {
				// then delete
		        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );
		    }
		    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name2.' WHERE campaign_id = %d', $campaign_id ) ) ) {
				// then delete campaign update
		        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name2.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
		    }
		    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name3.' WHERE campaign_id = %d', $campaign_id ) ) ) {
				// then delete donate
		       $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name3.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
		    }
		    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT campaign_id FROM '.$table_name4.' WHERE campaign_id = %d', $campaign_id ) ) ) {
				// then delete love
		        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name4.' WHERE campaign_id = "'.$campaign_id.'" ' ) );
		    }

	        echo 'success';
		}
		
	    
	}
	

	// echo $jumlah_info_update;
	
    wp_die();

} 
add_action( 'wp_ajax_djafunction_delete_campaign', 'djafunction_delete_campaign' );
add_action( 'wp_ajax_nopriv_djafunction_delete_campaign', 'djafunction_delete_campaign' );


function djafunction_clone_campaign() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    
    // FROM INPUT
    $campaign_id 	= $_POST['datanya'][0];

    $row = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'" ')[0];

    // role
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

    $id_login = wp_get_current_user()->ID;

    $action = false;
	if($role=='donatur'){
		if($id_login==$row->user_id){
			$action = true;
		}else{
			$action = false;
		}
	}else{
		if($role=='administrator'){
			$action = true;
		}else{
			$action = false;
		}
	}

	if($action==true){

		// ACTION INSERT TO DB
		$new_campaign_id = 'dja'.d_randomString(8);
		$id_login = wp_get_current_user()->ID;

		$jumlah_slug = count($wpdb->get_results('SELECT id from '.$table_name.' where slug="'.$row->slug.'"'));
		$slug = d_formatUri($row->title).'-'.d_randomString(3);
	    if($jumlah_slug>1){
	    	$slug = $row->slug.'-'.d_randomString(3);
	    }

	    $a = $wpdb->insert(
            $table_name, //table
            array(
	            'campaign_id' 	=> $new_campaign_id,
	            'title' 		=> $row->title,
	            'slug' 			=> $slug,
	            'target' 		=> $row->target,
	            'image_url' 	=> $row->image_url,
	            'information' 	=> $row->information,
	            'location_name' => $row->location_name,
	            'location_gmaps'=> $row->location_gmaps,
	            'publish_status'=> 0,
	            'reached_status'=> $row->reached_status,
	            
	            'end_date' 		=> $row->end_date,
	            'form_base' 	=> $row->form_base,
	            'form_type' 	=> $row->form_type,
	            'packaged' 		=> $row->packaged,
	            'packaged_title'=> $row->packaged_title,
	            'currency'		=> $row->currency,
	            'category_id'	=> $row->category_id,
	            'user_id'		=> $id_login,

	            'payment_status'=> $row->payment_status,
	            'method_status' => $row->method_status,
	            'bank_account'	=> $row->bank_account,
	            'form_status'	=> $row->form_status,
	            'form_text'		=> $row->form_text,
	            'unique_number_setting'	=> $row->unique_number_setting,
	            'unique_number_value'	=> $row->unique_number_value,
	            'notification_status'	=> $row->notification_status,

	            'wanotif_message'		=> $row->wanotif_message,
	            'pixel_status'			=> $row->pixel_status,
	            'fb_pixel'				=> $row->fb_pixel,
	            'fb_event'				=> $row->fb_event,
	            'pengeluaran_setting'	=> $row->pengeluaran_setting,
	            'pengeluaran_title'		=> $row->pengeluaran_title,
	            'gtm_status'			=> $row->gtm_status,
	            'gtm_id'				=> $row->gtm_id,
	            'tiktok_status'			=> $row->tiktok_status,
			    'tiktok_pixel'			=> $row->tiktok_pixel,
	            'socialproof'			=> $row->socialproof,
	            'socialproof_text'		=> $row->socialproof_text,
	            'socialproof_position'	=> $row->socialproof_position,

	            'zakat_setting'			=> $row->zakat_setting,
				'zakat_percent'			=> $row->zakat_percent,
				'fundraiser_setting'	=> $row->fundraiser_setting,
				'fundraiser_on'			=> $row->fundraiser_on,
				'fundraiser_text'		=> $row->fundraiser_text,
				'fundraiser_button'		=> $row->fundraiser_button,
				'fundraiser_commission_on'		=> $row->fundraiser_commission_on,
				'fundraiser_commission_type'	=> $row->fundraiser_commission_type,
				'fundraiser_commission_percent'	=> $row->fundraiser_commission_percent,
				'fundraiser_commission_fixed'	=> $row->fundraiser_commission_fixed,
				'additional_info'		=> $row->additional_info,
				'additional_formula'	=> $row->additional_formula,
				'additional_field'		=> $row->additional_field,
				'custom_field_setting'	=> $row->custom_field_setting,
				'general_status'		=> $row->general_status,
				'allocation_title'		=> $row->allocation_title,
				'allocation_others_title'	=> $row->allocation_others_title,
				'donatur_name'				=> $row->donatur_name,
				'donatur_others_name'		=> $row->donatur_others_name,

	        ),
            array('%s', '%s') //data format  
        );

        echo 'success';

	}else{
		echo 'failed';
	}


    wp_die();

} 
add_action( 'wp_ajax_djafunction_clone_campaign', 'djafunction_clone_campaign' );
add_action( 'wp_ajax_nopriv_djafunction_clone_campaign', 'djafunction_clone_campaign' );



function djafunction_delete_info() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign_update";
    
    // FROM INPUT
    $id 			= $_POST['datanya'][0];
    $campaign_id 	= $_POST['datanya'][1];

    // role
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

    $id_login = wp_get_current_user()->ID;

	if($role=='donatur'){

		// cek dulu sama gak dengan id si donatur
		$row = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'" and id="'.$id.'" and user_id="'.$id_login.'" ');

		if($row!=null){
			// ACTION Update TO DB
		    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {

				// then delete
		        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );

		        echo 'success';
		    }else{
		    	echo 'not allowed';
		    }
		}else{
			echo 'failed';
		}
    	
	}else{
	    // ACTION Update TO DB
	    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {

			// then delete
	        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );

	        echo 'success';
	    }else{
	    	echo 'not allowed';
	    }
	    
	}
	
    wp_die();

} 
add_action( 'wp_ajax_djafunction_delete_info', 'djafunction_delete_info' );
add_action( 'wp_ajax_nopriv_djafunction_delete_info', 'djafunction_delete_info' );



function djafunction_add_update_info() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign_update";
    $table_name2 = $wpdb->prefix . "dja_campaign";
    
    // FROM INPUT
    $user_id 		= $_POST['datanya'][0];
    $campaign_id 	= $_POST['datanya'][1];

    // role
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

    // $id_login = wp_get_current_user()->ID;


	if($role=='donatur'){

		// cek dulu sama gak dengan id si donatur
		$row = $wpdb->get_results('SELECT * from '.$table_name2.' where campaign_id="'.$campaign_id.'" and user_id="'.$user_id.'" ');

		if($row!=null){

			// ACTION Update TO DB 
		    $wpdb->insert(
	            $table_name, //table
	            array(
		            'campaign_id' 	=> $campaign_id,
		            'title' 		=> '',
		            'information' 	=> '',
		            'user_id' 		=> $user_id,
		            'created_at' 	=> date("Y-m-d H:i:s")
		        ),
	            array('%s', '%s') //data format         
	        );

	        $lastid = $wpdb->insert_id;
        	echo $lastid;

		}else{
			echo 'failed';
		}
    	
	}else{
	    // ACTION Update TO DB
	    $wpdb->insert(
            $table_name, //table
            array(
	            'campaign_id' 	=> $campaign_id,
	            'title' 		=> '',
	            'information' 	=> '',
	            'user_id' 		=> $user_id,
	            'created_at' 	=> date("Y-m-d H:i:s")
	        ),
            array('%s', '%s') //data format         
        );
        
        $lastid = $wpdb->insert_id;

        echo $lastid;
	}
	
    wp_die();

} 
add_action( 'wp_ajax_djafunction_add_update_info', 'djafunction_add_update_info' );
add_action( 'wp_ajax_nopriv_djafunction_add_update_info', 'djafunction_add_update_info' );



function djafunction_update_text_followup() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    // FROM INPUT
    $btn_followup 			= $_POST['datanya'][0];
    $text_f1 				= $_POST['datanya'][1];
    $text_f2 				= $_POST['datanya'][2];
    $text_f3 				= $_POST['datanya'][3];
    $text_f4 				= $_POST['datanya'][4];
    $text_f5 				= $_POST['datanya'][5];
    $text_received 			= $_POST['datanya'][6];
    $text_received_status 	= $_POST['datanya'][7];
    $wanotif_on_dashboard 	= $_POST['datanya'][8];

    if($text_received_status==''){
    	$text_received_status = 0;
    }else{
    	$text_received_status = 1;
    }

    if($wanotif_on_dashboard==''){
    	$wanotif_on_dashboard = 0;
    }else{
    	$wanotif_on_dashboard = 1;
    }

    $text_f1 = str_replace('\\', '', $text_f1);
    $text_f2 = str_replace('\\', '', $text_f2);
    $text_f3 = str_replace('\\', '', $text_f3);
    $text_f4 = str_replace('\\', '', $text_f4);
    $text_f5 = str_replace('\\', '', $text_f5);
    $text_received = str_replace('\\', '', $text_received);

    // ACTION Update TO DB
    $wpdb->update( $table_name, array('data' => $btn_followup), array('type' => 'btn_followup'), array('%s'), array('%s') );
    $wpdb->update( $table_name, array('data' => $text_f1), array('type' => 'text_f1'), array('%s'), array('%s') );
    $wpdb->update( $table_name, array('data' => $text_f2), array('type' => 'text_f2'), array('%s'), array('%s') );
    $wpdb->update( $table_name, array('data' => $text_f3), array('type' => 'text_f3'), array('%s'), array('%s') );
    $wpdb->update( $table_name, array('data' => $text_f4), array('type' => 'text_f4'), array('%s'), array('%s') );
    $wpdb->update( $table_name, array('data' => $text_f5), array('type' => 'text_f5'), array('%s'), array('%s') );
    $wpdb->update( $table_name, array('data' => $text_received), array('type' => 'text_received'), array('%s'), array('%s') );
    $wpdb->update( $table_name, array('data' => $text_received_status), array('type' => 'text_received_status'), array('%s'), array('%s') );
	$wpdb->update( $table_name, array('data' => $wanotif_on_dashboard), array('type' => 'wanotif_on_dashboard'), array('%s'), array('%s') );
	
	echo 'success';

    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_text_followup', 'djafunction_update_text_followup' );
add_action( 'wp_ajax_nopriv_djafunction_update_text_followup', 'djafunction_update_text_followup' );


function djafunction_send_wa(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";
    $table_name2 = $wpdb->prefix . "dja_donate";
    $table_name3 = $wpdb->prefix . "dja_campaign";
    $table_name4 	= $wpdb->prefix . "dja_aff_submit";
    $table_name5 	= $wpdb->prefix . "dja_aff_code";

    // FROM INPUT
    $donasi_id 			= $_POST['datanya'][0];
    $followup_number 	= $_POST['datanya'][1];

    // GET SETTINGS
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="text_f1" or type="text_f2" or type="text_f3" or type="text_f4" or type="text_f5" or type="currency" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_on_dashboard" or type="wanotif_on" or type="wanotif_apikey_cs" ORDER BY id ASC');
    $text_f1 = $query_settings[0]->data;
    $text_f2 = $query_settings[1]->data;
    $text_f3 = $query_settings[2]->data;
    $text_f4 = $query_settings[3]->data;
    $text_f5 = $query_settings[4]->data;
    $currency = $query_settings[5]->data;
    $wanotif_url 		  = $query_settings[6]->data;
    $wanotif_apikey 	  = $query_settings[7]->data;
    $wanotif_on_dashboard = $query_settings[8]->data;
    $wanotif_on 		  = $query_settings[9]->data;
    $wanotif_apikey_cs 	  = $query_settings[10]->data;


    if($followup_number=='1'){
    	$text_wa = $text_f1;
    }elseif($followup_number=='2'){
    	$text_wa = $text_f2;
    }elseif($followup_number=='3'){
    	$text_wa = $text_f3;
    }elseif($followup_number=='4'){
    	$text_wa = $text_f4;
    }else{
    	$text_wa = $text_f5;
    }
	
	// GET DATA DONASI
    $row = $wpdb->get_results('SELECT a.*, b.title from '.$table_name2.' a 
    left JOIN '.$table_name3.' b ON b.campaign_id = a.campaign_id where a.id="'.$donasi_id.'" ')[0];

	$invoice_id_jsh = $row->invoice_id;

    // set cs_name
    $cs_name = '';
    if($row->cs_id>=1){
    	$user_info_cs = get_userdata($row->cs_id);
	    if($user_info_cs!=null){
	    	if($user_info_cs->last_name==''){
		    	$cs_name = $user_info_cs->first_name;
		    }else{
		    	$cs_name = $user_info_cs->first_name.' '.$user_info_cs->last_name;
		    }
	    }
    }

    if($row->payment_number==''){
    	$payment_numbernya = $row->deeplink_url;
    }else{
    	$payment_numbernya = $row->payment_number;
    }
    
    $data_field = array();
    $data_field[ '{name}' ] 	= $row->name;
    $data_field[ '{cs_name}' ] 	= $cs_name;
    $data_field[ '{whatsapp}' ] = $row->whatsapp;
    $data_field[ '{email}' ] 	= $row->email;
    $data_field[ '{comment}' ] 	= $row->comment;
    $data_field[ '{payment_number}' ] 	= $payment_numbernya;
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
    where a.donate_id='$donasi_id' ORDER BY a.id DESC ")[0];

    if($query_donation->fundraiser_id!=''){
        $user_info = get_userdata($query_donation->fundraiser_id);
        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
        $data_field[ '{fundraiser}' ] = $fundraiser_name;
    }else{
    	$data_field[ '{fundraiser}' ] = '-';
    }
	

    // set data payment_qrcode
    $payment_qrcode = $row->payment_qrcode;
    
    // UPDATE FOLLOWUP STATUS
    $wpdb->update(
        $table_name2, //table
        array(
            'f'.$followup_number => 1
        ),
        array('id' => $donasi_id), //where
        array('%s'), //data format
        array('%s') //where format    
    );

    $message = strtr($text_wa, $data_field);
	$messagenya = dja_whatsapp_format($message);
	$messagenya = str_replace("\\", '', $messagenya);
	$messagenya = strip_tags($messagenya);

    $phone = djaPhoneFormat($row->whatsapp);

    if($row->cs_id>=1){
		if($wanotif_apikey_cs!=''){
	        $data_cs = json_decode($wanotif_apikey_cs, true);
	        $jumlah_cs = $data_cs['jumlah'];
	    }else{
	        $jumlah_cs = 0;
	    }
	    
	    if($jumlah_cs>=1){
	    	foreach ($data_cs['data'] as $key => $value) {
	        	if($row->cs_id==$value[0]){
	        		$wanotif_apikey = $value[1];
	        		break;
	        	}
	        }
	    }
	}

	if($wanotif_on_dashboard=='1' && $wanotif_apikey!='' && $wanotif_on=='1'){

		$messagenya = strtr($messagenya, $data_field);

		$bHasLink = strpos($payment_qrcode, 'http') !== false || strpos($payment_qrcode, 'www.') !== false;
		$bHasIPaymu = strpos($message, 'ipaymu.com') !== false; // example qr on : https://sandbox.ipaymu.com/qr/41342

		if($bHasLink && $followup_number=='1'){

			// send with wanotif with Image QRCode
			$messagenya = $message;
			$messagenya = str_replace('*'.$payment_qrcode.'*', '', $messagenya);
			$messagenya = str_replace($payment_qrcode, '', $messagenya);

			if (preg_match('@"([^"]+)"@', $content, $matches) && $bHasIPaymu) {
				$image_base64_qrcode = $matches[0];
				$body = $image_base64_qrcode;
				$body = str_replace('"', '', $body);
			}else{	
				$imagedata = 'data:image/png;base64,'.base64_encode(file_get_contents($payment_qrcode));
				$body = $imagedata;
			}

			// Nama file beserta jenis ekstensinya
			$filename = 'QRcode.jpg';

			$url = $wanotif_url.'/sendfile';

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
			    'Body'      => $body,
			    'Caption'   => $messagenya,
			    'Filename'  => $filename
			));
			$response = curl_exec($curl);
			curl_close($curl); 

			$array = json_decode( $response, true );
			$data = $array['wanotif']['status'];

			if($data=='sent'){
				echo 'success';
			}else{
				echo 'failed';
			}

		}else{

			// send with wanotif
			$url = $wanotif_url.'/send';
			$messagenya = $message;

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

			$array = json_decode( $response, true );
			$data = $array['wanotif']['status'];

			if($data=='sent'){
				echo 'success';
			}else{
				echo 'failed';
			}

		}

    }else{
    	// send wa with URL API Whatsapp
    	// $urllink_whatsapp = 'https://api.whatsapp.com/send?phone='.$phone.'&text='.$messagenya;
    	// echo $urllink_whatsapp;

		$urllink_follow = home_url("f/?inv=$invoice_id_jsh");
    	echo $urllink_follow;
    } 

    wp_die();

} 
add_action( 'wp_ajax_djafunction_send_wa', 'djafunction_send_wa' );
add_action( 'wp_ajax_nopriv_djafunction_send_wa', 'djafunction_send_wa' );


function djafunction_set_payment() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_settings";
    $table_name3 = $wpdb->prefix . "dja_campaign";
    $table_name4 	= $wpdb->prefix . "dja_aff_submit";
    $table_name5 	= $wpdb->prefix . "dja_aff_code";

    // FROM INPUT
    $donasi_id  = $_POST['datanya'][0];
    $value_button = $_POST['datanya'][1];
	
	// GET SETTINGS
	$query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="text_received" or type="text_received_status" or type="currency" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_message" or type="wanotif_on_dashboard" or type="wanotif_on" or type="email_on" or type="email_success_message" ORDER BY id ASC');
    $text_received 		  = $query_settings[0]->data;
    $text_received_status = $query_settings[1]->data;
    $currency 			  = $query_settings[2]->data;
    $wanotif_url 		  = $query_settings[3]->data;
    $wanotif_apikey 	  = $query_settings[4]->data;
    $wanotif_message 	  = $query_settings[5]->data;
    $wanotif_on_dashboard = $query_settings[6]->data;
    $wanotif_on 		  = $query_settings[7]->data;
    $email_on 		  	  = $query_settings[8]->data;
    $email_success_message= $query_settings[9]->data;


    $row = $wpdb->get_results('SELECT a.*, b.title from '.$table_name.' a 
    left JOIN '.$table_name3.' b ON b.campaign_id = a.campaign_id where a.id="'.$donasi_id.'" ')[0];

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
    where a.donate_id='$donasi_id' ORDER BY a.id DESC ")[0];

    if($query_donation->fundraiser_id!=''){
        $user_info = get_userdata($query_donation->fundraiser_id);
        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
        $data_field[ '{fundraiser}' ] = $fundraiser_name;
    }else{
    	$data_field[ '{fundraiser}' ] = '-';
    }
    


    $phone = djaPhoneFormat($row->whatsapp);

    // $id_login = wp_get_current_user()->ID;
    // $user = get_user_by( 'ID', $id_login );
    // $display_name = $user->display_name;
    
    if($value_button=='1'){
    	$wpdb->update(
	        $table_name,
	        array(
	            'status' 	 => 1,
	            'payment_at' => date("Y-m-d H:i:s"),
	            'process_by' => 'admin'
	        ), array('id' => $donasi_id), array('%s'), array('%s')
	    );
    }else{
    	$wpdb->update(
	        $table_name,
	        array(
	            'status' 	 => 0,
	            'payment_at' => null,
	            'process_by' => null
	        ), array('id' => $donasi_id), array('%s'), array('%s')
	    );
    }
    

    if($text_received_status=='1'){
    	$text_wa = $text_received;
    }else{
    	$text_wa = '';
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

   	// With Wanotif
	if($wanotif_apikey!='' && $wanotif_on=='1' && $value_button=='1' && $text_received_status=='1' && $wanotif_on_dashboard=='1'){
		// SET PHONE
		if($row->whatsapp!=''){

			$phone = djaPhoneFormat($row->whatsapp);
			$url = $wanotif_url.'/send';

			$messagenya = $text_wa;
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

			echo 'wanotif_';

		}else{
			echo 'wanotiffalse_';
		}

	}else{

		$message = strtr($text_wa, $data_field);
		$message = dja_whatsapp_format($message);
		$message = str_replace("\\", '', $message);
		$message = strip_tags($message);

		$urllink_whatsapp = 'https://api.whatsapp.com/send?phone='.$phone.'&text='.$message;

		echo 'success_'.$urllink_whatsapp;

	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_set_payment', 'djafunction_set_payment' );
add_action( 'wp_ajax_nopriv_djafunction_set_payment', 'djafunction_set_payment' );



function djafunction_clone_shortcode(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_shortcode";

    // FROM INPUT
    $s_name 		= $_POST['datanya'][0];
    $s_category 	= $_POST['datanya'][1];
    $s_style 		= $_POST['datanya'][2];
    $s_show 		= $_POST['datanya'][3];
    $s_loadmore 	= $_POST['datanya'][4];
    $s_button_on 	= $_POST['datanya'][5];
    $s_button_text  = $_POST['datanya'][6];
    $s_data_load 	= $_POST['datanya'][7];
    $s_campaign 	= str_replace('\\', '', $_POST['datanya'][8]);

    if($s_campaign!=''){
    	$var = explode(',', $s_campaign);
	    $count_campaign = count($var);
	    $no = 1;
	    $data_campaign = '';
	    foreach($var as $value){
	    	if($no==$count_campaign){
	    		$data_campaign .= '"'.$value.'"';
	    	}else{
	    		$data_campaign .= '"'.$value.'",';
	    	}

	    	$no++;
	    }

	    $s_campaign = '{"campaign":['.$data_campaign.']}';

    }else{
    	$s_campaign = '{"campaign":[]}';
    }
    

    $id_login = wp_get_current_user()->ID;

    if($s_loadmore==''){
    	$s_loadmore = 0;
    }

    $s_id = 'ds'.d_randomString(7);

    	// create
    	$wpdb->insert(
            $table_name, //table
            array(
	            's_id' 			=> $s_id,
	            's_name' 		=> $s_name,
	            's_category' 	=> $s_category,
	            's_style' 		=> $s_style,
	            's_show' 		=> $s_show,
	            's_loadmore' 	=> $s_loadmore,
	            's_button_on' 	=> $s_button_on,
	            's_button_text' => $s_button_text,
	            's_data_load' 	=> $s_data_load,
	            's_campaign' 	=> $s_campaign,
	            'created_at' 	=> date("Y-m-d H:i:s"),
	            'updated_at' 	=> date("Y-m-d H:i:s")
	        ),
            array('%s', '%s') //data format         
        );

    	echo 'success';

    wp_die();
}
add_action( 'wp_ajax_djafunction_clone_shortcode', 'djafunction_clone_shortcode' );
add_action( 'wp_ajax_nopriv_djafunction_clone_shortcode', 'djafunction_clone_shortcode' );


function djafunction_delete_shortcode() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_shortcode";
    
    // FROM INPUT
    $id 			= $_POST['datanya'][0];

    // ACTION Update TO DB
    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {

		// then delete
		$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );

        echo 'success';
    }else{
    	echo 'not allowed';
    }
	
    wp_die();

} 
add_action( 'wp_ajax_djafunction_delete_shortcode', 'djafunction_delete_shortcode' );
add_action( 'wp_ajax_nopriv_djafunction_delete_shortcode', 'djafunction_delete_shortcode' );



function djafunction_update_shortcode() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_shortcode";
    
    // FROM INPUT
    $s_id 			= $_POST['datanya'][0];
    $s_name 		= $_POST['datanya'][1];
    $s_category 	= $_POST['datanya'][2];
    $s_style 		= $_POST['datanya'][3];
    $s_show 		= $_POST['datanya'][4];
    $s_loadmore 	= $_POST['datanya'][5];
    $s_button_on 	= $_POST['datanya'][6];
    $s_button_text 	= $_POST['datanya'][7];
    $s_data_load 	= $_POST['datanya'][8];
    $s_campaign 	= str_replace('\\', '', $_POST['datanya'][9]);
	$j_utmsource	= $_POST['datanya'][10];
	$j_utmmedium	= $_POST['datanya'][11];
	$j_utmcampaign	= $_POST['datanya'][12];
	$j_utmterm		= $_POST['datanya'][13];
	$j_utmcontent	= $_POST['datanya'][14];

    if($s_button_on==''){
    	$s_button_on = 0;
    }else{
    	$s_button_on = 1;
    }

    // get data campaign to check user cannot modify the campaign id
    $row = $wpdb->get_results('SELECT * from '.$table_name.' where s_id="'.$s_id.'" ');

    if($row!=null){

	    // ACTION Update TO DB
	    $wpdb->update(
            $table_name, //table
            array(
	            's_name' 	 	=> $s_name,
	            's_category' 	=> $s_category,
	            's_style' 	 	=> $s_style,
	            's_show' 	 	=> $s_show,
	            's_loadmore' 	=> $s_loadmore,
	            's_button_on' 	=> $s_button_on,
	            's_button_text' => $s_button_text,
	            's_data_load' 	=> $s_data_load,
	            's_campaign' 	=> $s_campaign,
				'utm_source'	=> $j_utmsource,
				'utm_medium'	=> $j_utmmedium,
				'utm_campaign'	=> $j_utmcampaign,
				'utm_term'		=> $j_utmterm,
				'utm_content'	=> $j_utmcontent
	        ),
            array('s_id' => $s_id), //where
            array('%s'), //data format
            array('%s') //where format    
        );
		
		echo $s_id;

	}else{
		echo 0;
	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_shortcode', 'djafunction_update_shortcode' );
add_action( 'wp_ajax_nopriv_djafunction_update_shortcode', 'djafunction_update_shortcode' );



function djafunction_add_shortcode() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_shortcode";
    
    // FROM INPUT
    $s_name 		= $_POST['datanya'][0];
    $s_category 	= $_POST['datanya'][1];
    $s_style 		= $_POST['datanya'][2];
    $s_show 		= $_POST['datanya'][3];
    $s_loadmore	 	= $_POST['datanya'][4];
    $s_button_on 	= $_POST['datanya'][5];
    $s_button_text 	= $_POST['datanya'][6];
    $s_data_load 	= $_POST['datanya'][7];
    $s_campaign 	= str_replace('\\', '', $_POST['datanya'][8]);
	$j_utmsource	= $_POST['datanya'][10];
	$j_utmmedium	= $_POST['datanya'][11];
	$j_utmcampaign	= $_POST['datanya'][12];
	$j_utmterm		= $_POST['datanya'][13];
	$j_utmcontent	= $_POST['datanya'][14];


    if($s_button_on==''){
    	$s_button_on = 0;
    }else{
    	$s_button_on = 1;
    }

	$s_id = 'ds'.d_randomString(7);

	// create
	$wpdb->insert(
        $table_name, //table
        array(
            's_id' 			=> $s_id,
            's_name' 		=> $s_name,
            's_category' 	=> $s_category,
            's_style' 		=> $s_style,
            's_show' 		=> $s_show,
            's_loadmore' 	=> $s_loadmore,
            's_button_on' 	=> $s_button_on,
            's_button_text' => $s_button_text,
            's_data_load' 	=> $s_data_load,
            's_campaign' 	=> $s_campaign,
            'created_at' 	=> date("Y-m-d H:i:s"),
            'updated_at' 	=> date("Y-m-d H:i:s"),
			'utm_source'	=> $j_utmsource,
			'utm_medium'	=> $j_utmmedium,
			'utm_campaign'	=> $j_utmcampaign,
			'utm_term'		=> $j_utmterm,
			'utm_content'	=> $j_utmcontent

        ),
        array('%s', '%s') //data format         
    );

	echo $s_id;


    wp_die();

} 
add_action( 'wp_ajax_djafunction_add_shortcode', 'djafunction_add_shortcode' );
add_action( 'wp_ajax_nopriv_djafunction_add_shortcode', 'djafunction_add_shortcode' );


function djafunction_login_user() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "dja_settings"; 
    $table_name3 = $wpdb->prefix . "dja_users"; 

    $query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="login_setting" ORDER BY id ASC');
    $login_setting = $query_settings[0]->data;

    if($login_setting=='1'){
    	// lanjut terus
    }else{
    	echo 'not_allowed';
    	wp_die();
    }   

    // FROM INPUT
    $e_p_username = $_POST['datanya'][0];
    $password 	  = $_POST['datanya'][1];

    if(filter_var($e_p_username, FILTER_VALIDATE_EMAIL)) {
        // valid address
        $userdata = get_user_by( 'email', $e_p_username );
	    if ( $userdata ) {

	        $user_id = $userdata->ID;
	        $result = wp_check_password($password, $userdata->user_pass, $user_id);

	        if($result!=null){
	        	$set_login = dja_auto_login_new_user($user_id);
	        	echo $set_login;
			    wp_die();
	        }else{
	        	echo 'email_failed';
			    wp_die();
	        }

	    } else {
	        echo 'email_failed';
	        wp_die();
	    }

    }else{
    	
        if (is_numeric($e_p_username)) {

        	// phone number
        	$check_wa = $wpdb->get_results('SELECT user_id from '.$table_name3.' where user_wa="'.$e_p_username.'"');
			if($check_wa!=null){
				
				$user_id = $check_wa[0]->user_id;
				$userdata = get_user_by( 'ID', $user_id );

		        $result = wp_check_password($password, $userdata->user_pass, $user_id);

		        if($result!=null){
		        	$set_login = dja_auto_login_new_user($user_id);
		        	echo $set_login;
				    wp_die();
		        }else{
		        	echo 'phone_failed';
				    wp_die();
		        }

			}else{
				echo 'phone_failed';
		        wp_die();
			}

	    } else {
	    	// username
	        $userdata = get_user_by( 'login', $e_p_username );
		    if ( $userdata ) {

		        $user_id = $userdata->ID;
		        $result = wp_check_password($password, $userdata->user_pass, $user_id);

		        if($result!=null){
		        	$set_login = dja_auto_login_new_user($user_id);
		        	echo $set_login;
				    wp_die();
		        }else{
		        	echo 'username_failed';
				    wp_die();
		        }
			    
		    } else {
		        echo 'username_failed';
		        wp_die();
		    }
	    }

    }

    

    
} 
add_action( 'wp_ajax_djafunction_login_user', 'djafunction_login_user' );
add_action( 'wp_ajax_nopriv_djafunction_login_user', 'djafunction_login_user' );



function djafunction_register_user() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "usermeta";
    $table_name3 = $wpdb->prefix . "dja_users";
    $table_name4 = $wpdb->prefix . "dja_verification_details"; 
    $table_name5 = $wpdb->prefix . "dja_settings";

    // FROM INPUT
    $user_fullname 	= $_POST['datanya'][0];
    $user_email 	= $_POST['datanya'][1];
    $user_whatsapp 	= $_POST['datanya'][2];
    $user_pass 		= $_POST['datanya'][3];

    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="register_setting" or type="email_on" ORDER BY id ASC');
    $register_setting = $query_settings[0]->data;
    $email_on 		  = $query_settings[1]->data;

    if($register_setting=='1'){
    	// lanjut terus
    }else{
    	echo 'not_allowed';
    	wp_die();
    }

    $count_name = str_word_count($user_fullname);
    if($count_name==1){
    	$user_firstname = $user_fullname;
    	$user_lastname = '';
    }elseif($count_name==2){
    	$name = explode(' ',$user_fullname);
	    $user_firstname = $name[0];
    	$user_lastname = $name[1];
    }elseif($count_name==3){
    	$name = explode(' ',$user_fullname);
	    $user_firstname = $name[0];
    	$user_lastname = $name[1].' '.$name[2];
    }elseif($count_name==4){
    	$name = explode(' ',$user_fullname);
	    $user_firstname = $name[0];
    	$user_lastname = $name[1].' '.$name[2].' '.$name[3];
    }elseif($count_name==5){
    	$name = explode(' ',$user_fullname);
	    $user_firstname = $name[0];
    	$user_lastname = $name[1].' '.$name[2].' '.$name[3].' '.$name[4];
    }else{
    	$user_firstname = $user_fullname;
    	$user_lastname = '';
    }

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

    $check_wa = $wpdb->get_results('SELECT id from '.$table_name3.' where user_wa="'.$user_whatsapp.'"');
	if($check_wa!=null){
		echo 'wa_terdaftar';
    	wp_die();
	}

	$pattern = "/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";
    if (!(preg_match($pattern, $user_pass))) {
        echo 'password_failed';
    	wp_die();
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
	    wp_update_user( array ('ID' => $user_id, 'role' => 'donatur') ) ;

	    $randid = 'u_'.d_randomString(8);
        // insert data to table user
        $wpdb->insert(
            $table_name3, //table
            array(
                'user_id'           => $user_id,
                'user_randid'       => $randid,
                'user_type'         => null,
                'user_verification' => null,
                'user_bio'          => null,
                'user_wa'           => $user_whatsapp,
                'user_provinsi' 	=> null,
	            'user_kabkota' 		=> null,
	            'user_kecamatan' 	=> null,
	            'user_provinsi_id' 	=> null,
	            'user_kabkota_id' 	=> null,
	            'user_kecamatan_id' => null,
	            'user_alamat' 		=> null,
	            'user_bank_name' 	=> null,
	            'user_bank_no' 	 	=> null,
	            'user_bank_an' 	 	=> null,
                'user_pp_img'       => null,
                'user_cover_img'    => null,
                'created_at'    => date("Y-m-d H:i:s")),
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

        if($email_on=='1'){
			
            $message = '<p><ul><li>Username: '.$name_clean_fix.'</li><li>Email: '.$user_email.'</li><li>Password: '.$user_pass.'</li></ul></p>';
            $subject = 'Berikut data Username dan Password anda';
            $emailnya = $user_email;
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';
 			
			if($emailnya!=''){
				wp_mail( $emailnya, $subject, $body, $headers );
			}

		}	
        
        $set_login = dja_auto_login_new_user($user_id);

        echo $set_login;

    	wp_die();
    }
    
} 
add_action( 'wp_ajax_djafunction_register_user', 'djafunction_register_user' );
add_action( 'wp_ajax_nopriv_djafunction_register_user', 'djafunction_register_user' );



function djafunction_send_link() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_password_reset";
    $table_name2 = $wpdb->prefix . "dja_password_reset_log";
    $table_name3 = $wpdb->prefix . "users";
    $table_name4 = $wpdb->prefix . "dja_settings";

    // FROM INPUT
    $user_email 	= $_POST['datanya'][0];

    $check_email = $wpdb->get_results('SELECT id from '.$table_name3.' where user_email="'.$user_email.'"');
	if($check_email==null){

		$user_ip 	    = donasiaja_getIP();
		$user_os        = donasiaja_getOS();
		$user_browser   = donasiaja_getBrowser();
	    
        $wpdb->insert(
            $table_name2, //table
            array(
                'reset_email'     => $user_email,
                'ip'    		  => $user_ip,
                'os'    	  	  => $user_os,
                'browser'   	  => $user_browser,
                'created_at'      => date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );

		$today		  = date('Y-m-d H:i:s');
		$date 		  = date("Y-m-d H:i:s", strtotime('-1 hours', time()));

		$count_access = $wpdb->get_results('SELECT id from '.$table_name2.' where ip="'.$user_ip.'" and os="'.$user_os.'" and  browser="'.$user_browser.'" and created_at between "'.$date.'" and "'.$today.'" ORDER BY id ASC');
		$check_count_access = count($count_access);

		if($check_count_access>=5){
			echo 'limitted_access';
		}else{
			echo 'not_valid';
		}
    	wp_die();

	}else{

		$user_ip 			= donasiaja_getIP();
		$user_os        = donasiaja_getOS();
		$user_browser   = donasiaja_getBrowser();
	    $code = d_randomString(32);
        $wpdb->insert(
            $table_name, //table
            array(
                'reset_email'     => $user_email,
                'reset_code'      => $code,
                'reset_status'    => 0,
                'ip'    		  => $user_ip,
                'os'    	  	  => $user_os,
                'browser'    	  => $user_browser,
                'created_at'      => date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );

        $today		  = date('Y-m-d H:i:s');
		$date 		  = date("Y-m-d H:i:s", strtotime('-1 hours', time()));

		$count_access = $wpdb->get_results('SELECT id from '.$table_name.' where ip="'.$user_ip.'" and os="'.$user_os.'" and  browser="'.$user_browser.'" and created_at between "'.$date.'" and "'.$today.'" ORDER BY id ASC');
		$check_count_access = count($count_access);

		if($check_count_access>=5){
			echo 'limitted_access2';
		}else{

			$user = get_user_by( 'email', $user_email );
			$display_name = '';
		    if ( $user ) {
		        $display_name = $user->display_name;
		    }

		    // Settings
		    $query_settings = $wpdb->get_results('SELECT data from '.$table_name4.' where type="app_name" ORDER BY id ASC');
		    $app_name				= $query_settings[0]->data;

			// send email please
			// $message = 'Berikut Link Password anda <a href="'.get_site_url().'/resetpass/'.$code.'">Change Password Now</a>';
			$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><title>Activation</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">a{outline:none;color:#fa5151;text-decoration:underline;}a:hover{text-decoration:none !important;}.h-u a{text-decoration:none;}.h-u a:hover{text-decoration:underline !important;}a[x-apple-data-detectors]{color:inherit !important; text-decoration:none !important;}a[href^="tel"]:hover{text-decoration:none !important;}.active-i a:hover,.active-t:hover{opacity:0.8;}.active-i a,.active-t{transition:all 0.3s ease;}a img{border:none;}th{padding:0;}table td{mso-line-height-rule:exactly;}[owa] div button{display:block; font-size:1px; line-height:1px;}[owa] .main-table div{display:block !important; font-size:1px; line-height:1px;}.l-white a{color:#fff;}@media only screen and (max-width:375px) and (min-width:374px){.gmail-fix{min-width:374px !important;}}@media only screen and (max-width:414px) and (min-width:413px){.gmail-fix{min-width:413px !important;}}@media only screen and (max-width:500px){/* default style */.flexible{width:100% !important;}.img-flex img{width:100% !important; height:auto !important;}.table-holder{display:table !important; width:100% !important;}.thead{display:table-header-group !important; width:100% !important;}.tfoot{display:table-footer-group !important; width:100% !important;}.flex{display:block !important; width:100% !important;}.hide{display:none !important; width:0 !important; height:0 !important; padding:0 !important; font-size:0 !important; line-height:0 !important;}.p-0{padding:0 !important;}.p-20{padding:20px !important;}.p-25{padding:25px !important;}.p-30{padding:30px !important;}.plr-15{padding-left:15px !important; padding-right:15px !important;}.plr-20{padding-left:20px !important; padding-right:20px !important;}.pt-20{padding-top:20px !important;}.pt-25{padding-top:25px !important;}.pb-15{padding-bottom:15px !important;}.pb-20{padding-bottom:20px !important;}.pb-25{padding-bottom:25px !important;}.fs-24{font-size:24px !important;}.lh-28{line-height:28px !important;}/* custom style */.pt-10p{padding-top:10%;}.plr-9p{padding-left:9%; padding-right:9%;}.pb-8p{padding-bottom:8%;}.social-icons img{width:85% !important;}}</style></head><body style="margin:0; padding:0; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;"><table class="gmail-fix" bgcolor="#ffffff" width="100%" style="min-width:320px;" cellspacing="0" cellpadding="0"><tr><td style="display:none; font-size:0; line-height:0;"></td></tr><tr><td><table data-module="module-1" class="main-table" width="100%" cellpadding="0" cellspacing="0"><tr><td data-bgcolor="bg-module" bgcolor="#F6FAFF"><table class="flexible" width="600" align="center" style="margin:0 auto;margin-bottom: 30px;margin-top: 20px;" cellpadding="0" cellspacing="0"></table></td></tr></table><table data-module="module-3" class="main-table" width="100%" cellpadding="0" cellspacing="0"><tr><td data-bgcolor="bg-module" bgcolor="#F6FAFF"><table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0"><tr><td data-bgcolor="bg-block-02" class="l-white p-25" style="padding:53px 110px 60px;background:#ffffff;border:1px solid #bb0ac1;border-radius: 8px;"><table width="100%" cellpadding="0" cellspacing="0"><tr><td data-color="title-with-bg" data-size="size title-with-bg" data-min="10" data-max="46" data-link-color="link title-with-bg color" data-link-style="text-decoration:underline; color:#fff;" align="center" style="padding:0 0 9px; font:700 26px/35px Arial, Helvetica, sans-serif; color:#23374D;"><span style="color: #bb0ac1;border-box;background-clip: text;">Hai '.$display_name.'</span></td></tr><tr><td data-color="text-with-bg" data-size="size text" data-min="10" data-max="46" data-link-color="link text-with-bg color" data-link-style="text-decoration:underline; color:#23374D;" align="center" style="padding:0 0 20px; font:15px/30px Arial, Helvetica, sans-serif; color:#23374D;"><span style="color:#23374D;">Baru saja anda request untuk mereset password akun anda. Ikuti link dibawah ini, Terimakasih.</span><br><br><a href="'.get_site_url().'/resetpass/'.$code.'" target="_blank" style="color:#23374D;">'.get_site_url().'/resetpass/'.$code.'</a><br><br></td></tr><tr><td><table align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0"><tr><td data-bgcolor="bg-button-01" data-size="size button" data-min="10" data-max="26" class="active-t" bgcolor="#ffffff" align="center" style="mso-padding-alt:14px 30px; font:700 14px/16px Arial, Helvetica, sans-serif; text-transform:uppercase; border-radius:22px;background: linear-gradient(to right, #b425b9 20%, #e40d6d 100%);"><a style="padding:14px 30px; color:#ffffff; text-decoration:none; display:block;" href="'.get_site_url().'/resetpass/'.$code.'" target="_blank">RESET PASSWORD</a></td></tr></table></td></tr></table></td></tr><tr><td style="text-align: center;font:12px/30px Arial, Helvetica, sans-serif; color:#ababab;padding-top: 10px;"><span>* Do not reply to this email.</span><br><br></td></tr></table></td></tr></table><table data-module="module-11" class="main-table" width="100%" cellpadding="0" cellspacing="0"><tr><td data-bgcolor="bg-module" bgcolor="#F6FAFF"><table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0"><tr><td data-bgcolor="bg-block" class="p-30 p-20" bgcolor="#F6FAFF" style="padding:10px 30px 30px;"><table width="100%" cellpadding="0" cellspacing="0"><tr><td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="text-decoration:underline; color:#fa5151;" align="center" style="font:13px/22px Arial, Helvetica, sans-serif; color:#242424;"><span style="color: #bb0ac1;">'.$app_name.'</span><br/><span style="color: #bb0ac1;">Powered by DonasiAja</span><br/><span style="color: #bb0ac1;">All rights reserved.</span></td></tr></table></td></tr></table></td></tr></table></td></tr></table></body></html>';
            $subject = 'Reset Password - '.$app_name;
            $headers = array('Content-Type: text/html; charset=UTF-8');
			
			wp_mail( $user_email, $subject, $message, $headers );
				
			echo 'success';
		}

    	wp_die();
    }
    
} 
add_action( 'wp_ajax_djafunction_send_link', 'djafunction_send_link' );
add_action( 'wp_ajax_nopriv_djafunction_send_link', 'djafunction_send_link' );

