<?php
	
	date_default_timezone_set('Asia/jakarta');

	global $wpdb;
	global $wp;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_donate";
    $table_name3 = $wpdb->prefix . "dja_users";
    $table_name4 = $wpdb->prefix . "dja_love";
    $table_name5 = $wpdb->prefix . "dja_settings";
    $table_name6 = $wpdb->prefix . "dja_campaign_update";
    $table_name7 = $wpdb->prefix . "dja_aff_code";
    $table_name8 = $wpdb->prefix . "dja_aff_submit";
    $table_name9 = $wpdb->prefix . "users";
    $table_name10 = $wpdb->prefix . "dja_aff_click";

    donasiaja_global_vars();
    $plugin_version = $GLOBALS['donasiaja_vars']['plugin_version'];
    
    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="label_tab" or type="max_love" or type="app_name" or type="login_setting" or type="page_login" or type="anonim_text" or type="page_donate" or type="theme_color" or type="form_text" or type="powered_by_setting" or type="fb_pixel" or type="fb_event" or type="gtm_id" or type="limitted_donation_button" or type="tiktok_pixel" or type="fundraiser_on" or type="fundraiser_text" or type="fundraiser_button" ORDER BY id ASC');
    $label_tab 	 = $query_settings[0]->data;
    $max_love 	 = $query_settings[1]->data;
    $app_name	 = $query_settings[2]->data;
    $login_setting 	= $query_settings[3]->data;
    $page_login 	= $query_settings[4]->data;
    $anonim_text = $query_settings[5]->data;
    $page_donate = $query_settings[6]->data;
    $general_theme_color = json_decode($query_settings[7]->data, true);
    $form_text 	 = json_decode($query_settings[8]->data, true);
    $powered_by_setting = $query_settings[9]->data;
    $fb_pixel 	 = $query_settings[10]->data;
    $fb_event  	 = json_decode($query_settings[11]->data, true);
    $event_1   	 = $fb_event['event'][0];
    $event_2   	 = $fb_event['event'][1];
    $event_3   	 = $fb_event['event'][2];
    $gtm_id 	 = $query_settings[12]->data;
    $limitted_donation_button = $query_settings[13]->data;
    $tiktok_pixel = $query_settings[14]->data;
    $fundraiser_on 		= $query_settings[15]->data;
    $fundraiser_text 	= $query_settings[16]->data;
    $fundraiser_button 	= $query_settings[17]->data;

    // set the color
    $theme_color 		= $general_theme_color['color'][0];
	$progressbar_color  = $general_theme_color['color'][1];
	$button_color 		= $general_theme_color['color'][2];

	if($button_color==''){
		$button_color = '#dc2f6a';
	}

	$text1 = $form_text['text'][0];
	$text2 = $form_text['text'][1];
	$text3 = $form_text['text'][2];
	$text4 = $form_text['text'][3];

	$slug = $donasi_id;
	$check = $wpdb->get_results('SELECT id from '.$table_name.' where slug="'.$slug.'"');
	if($check==null){
		$check2 = $wpdb->get_results('SELECT id, slug from '.$table_name.' where campaign_id="'.$slug.'"');
		$slug = $check2[0]->slug;
		if($check2==null){
			wp_redirect( get_site_url() );
			exit;
		}
	}

	// GET DATA CAMPAIGN
	$row = $wpdb->get_results('SELECT * from '.$table_name.' where slug="'.$slug.'"')[0];

	if($row->form_status=='1'){
        $form_text   = json_decode($row->form_text, true);
        $text1 = $form_text['text'][0];
        $text2 = $form_text['text'][1];
        $text3 = $form_text['text'][2];
        $text4 = $form_text['text'][3];
    }

    if($row->pixel_status=='1'){
    	$fb_pixel  = $row->fb_pixel;
        $fb_event  = json_decode($row->fb_event, true);
        $event_1   = $fb_event['event'][0];
        $event_2   = $fb_event['event'][1];
        $event_3   = $fb_event['event'][2];
    }

    if($row->gtm_status=='1'){
    	$gtm_id  = $row->gtm_id;
    }
    if($row->tiktok_status=='1'){
    	$tiktok_pixel  = $row->tiktok_pixel;
    }

    $general_status = $row->general_status;
    $allocation_title = $row->allocation_title;
    $allocation_others_title = $row->allocation_others_title;
    if($general_status=='1'){
        if($allocation_title=='1' || $allocation_title=='0'){
            $allocation_title = 'Donasi';
        }elseif($allocation_title=='2'){
            $allocation_title = 'Zakat';
        }else{
            $allocation_title = $allocation_others_title;
        }
    }else{
        $allocation_title = 'Donasi';
    }

    if($general_status=='1'){
	    $donatur_name = $row->donatur_name;
	    $donatur_others_name = $row->donatur_others_name;
	    if($donatur_name==1 || $donatur_name==0){
	        $donatur_title = "Donatur";
	    }elseif($donatur_name==2){
	        $donatur_title = "Muzakki";
	    }else{
	        $donatur_title = $donatur_others_name;
	    }
	}else{
		$donatur_title = "Donatur";
	}

	if($general_status=='1'){
		if($row->home_icon_url!=''){
			$home_urlnya = $row->home_icon_url;
		}else{
			$home_urlnya = get_site_url();
		}
	}else{
		$home_urlnya = get_site_url();
	}

	// print_r($row);

	// check campaign is published or not
	if($link_code=='campaign'){
		if($row->publish_status!='1'){
			wp_redirect( get_site_url() );
			exit;
		}
	}

	// BEGIN SESSION BY Aktif Berbagi
	require_once( ROOTDIR_DNA . 'core/api/abi-session.php' );
	// echo '<pre>'; var_dump( $_SESSION ); echo '</pre>';


	// GET CAMPAIGN UPDATE
	$campaign_update = $wpdb->get_results("SELECT * FROM $table_name6 where campaign_id='$row->campaign_id' ORDER BY created_at DESC");

	// GET TOTAL DONASI
	$total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name2 where campaign_id='$row->campaign_id' and status='1' ")[0];

	// target : 0 = unlimitted target
	if($row->target==0){
		$persen = 100;
		$persen_width = 100;
	}else{
		$persen = ($total_donasi->total/$row->target)*100;
		$persen_width = ($total_donasi->total/$row->target)*100;
		if($persen_width>100){
			$persen_width = 100;
		}
	}

	// GET INFORMATION
	$information = str_replace("'", "&#39;", $row->information); // petik 1
    // $information = str_replace('"', "&#34;", $information); // petik 2
    $information = str_replace('../wp-content', home_url().'/wp-content', $information);
    $information = str_replace('"', '', $information);
	$information_for_head = substr($information, 0, 450);

	// GER ORANG YANG DONASI
	$donasi = $wpdb->get_results("SELECT * FROM $table_name2 where campaign_id='$row->campaign_id' and status='1' ORDER BY id DESC limit 0,5 ");

	// GER ORANG YANG DONASI ADA KOMENNYA
	$donasi_comment = $wpdb->get_results("SELECT * FROM $table_name2 where campaign_id='$row->campaign_id' and status='1' and comment!='' ORDER BY id DESC limit 0,5 ");

	$jumlah_donasi = $wpdb->get_results("SELECT COUNT(id) as jumlah FROM $table_name2 where campaign_id='$row->campaign_id' and status='1'")[0];

	$data_comment = $wpdb->get_results("SELECT COUNT(id) as jumlah FROM $table_name2 where campaign_id='$row->campaign_id' and status='1' and comment!=''  ")[0];

	// GET DATA USER
	$user_info = get_userdata($row->user_id);
  	$fullname = $user_info->first_name.' '.$user_info->last_name;

  	// GET CURRENT URL
  	$home_url = home_url();
  	if($link_code=='campaign'){
		$current_url = home_url().'/campaign/'.$slug;
	} elseif ($link_code=='josh'){
		$current_url = home_url().'/josh/'.$slug;
	} else{
		$current_url = home_url().'/preview/'.$slug;
	}

	// GET PROFILE PICTURE
	$profile = $wpdb->get_results('SELECT user_pp_img as photo, user_type, user_verification, user_randid  from '.$table_name3.' where user_id="'.$row->user_id.'"');
	if(isset($profile[0])){
		if($profile==null){
			$profile_photo = plugin_dir_url( __FILE__ ) . "assets/images/pp.jpg";
		}else{
			$profile_photo = $profile[0]->photo;
			if($profile_photo==null){
				$profile_photo = plugin_dir_url( __FILE__ ) . "assets/images/pp.jpg";
			}
		}
	}else{
		$profile_photo = plugin_dir_url( __FILE__ ) . "assets/images/pp.jpg";
	}

	// print_r($profile);

	// Waktu Berakhir
    $date_now = date('Y-m-d');
    $datetime1 = new DateTime($date_now);
    $datetime2 = new DateTime($row->end_date);
    $hasil = $datetime1->diff($datetime2);
    
    $year = $hasil->y;
    $month = $hasil->m;
    $day = $hasil->d;

    // Date
    $date_end = false;
    if($year!=0){
        if($day>7){
    		$sisa_waktu = $year.'&nbsp;tahun,&nbsp;' .($month+1).'&nbsp;bulan&nbsp;lagi';
    	}else{
    		$sisa_waktu = $year.'&nbsp;tahun,&nbsp;' .$month.'&nbsp;bulan&nbsp;lagi';
    	}
    }else{
        if($month!=0){
            $sisa_waktu = $month.'&nbsp;bulan,&nbsp;' .$day.'&nbsp;hari&nbsp;lagi';
        }else{
            if($day==0 && $hasil->days==0){
                $sisa_waktu = 'hari&nbsp;ini';
            }else{
                if($hasil->invert==true){
                    $sisa_waktu = '<span style="color:#ff6b24;font-style:italic;">sudah&nbsp;berakhir</span>';
                    $date_end = true;
                }else{
                    $sisa_waktu = $day.'&nbsp;hari&nbsp;lagi';
                }
                
            }
        }
    }

    if($row->end_date==null){
    	$sisa_waktu = '∞&nbsp;hari&nbsp;lagi';
    }

    if($hasil->invert==true){
    	$sisa_waktu = '<span style="color:#ff6b24;font-style:italic;">sudah&nbsp;berakhir</span>';
    }

    // Settings Socialproof
    $query_settings2 = $wpdb->get_results('SELECT data from '.$table_name5.' where type="anonim_text" or type="socialproof_text" or type="socialproof_settings" ORDER BY id ASC');
    $anonim_text    	  = $query_settings2[0]->data;
    $socialproof_text     = $query_settings2[1]->data;
    $socialproof_settings = $query_settings2[2]->data;

    $socialproof_setting  = json_decode($socialproof_settings, true);
	$popup_style ='rounded';
	$delay = 8;
	$data_load = 10;
	$time_set = 1;
    if($socialproof_setting!=''){
		$popup_style    = $socialproof_setting['settings'][0];
		$position       = $socialproof_setting['settings'][1];
		$time_set       = $socialproof_setting['settings'][2];
		$delay          = $socialproof_setting['settings'][3];
		$data_load      = $socialproof_setting['settings'][4];
	}
    

    // close
    $close = 'false';

    // popup_style
    if($popup_style=='rounded'){
        $set_style = ' s-rounded';
    }elseif($popup_style=='flying_boxed'){
        $set_style = ' s-flying';
    }elseif($popup_style=='flying_rounded'){
        $set_style = ' s-rounded s-flying';
    }else{
        $set_style = '';
    }

    // delay
    $delay = $delay*1000;

    // data_load
    $total = $data_load;

    // time
    $time = $time_set;

    // title
    $title = $socialproof_text;

    // position
    $p_gravity = 'top';
    $p_position = 'left';
    if($socialproof_setting!=''){
	    $position_data = explode('_', $position);
		$p_gravity  = $position_data[0];
		$p_position = $position_data[1];
	}

	// set custom campaign socialproof
	if($row->socialproof_text!=''){
		$title = $row->socialproof_text;
	}

	if($row->socialproof_position!=''){
		$position_data_new = explode('_', $row->socialproof_position);
		$p_gravity  = $position_data_new[0];
		$p_position = $position_data_new[1];
	}

	// campaign
	$data_donasi = $wpdb->get_results("SELECT a.id, a.campaign_id, a.user_id, a.invoice_id, a.name, a.anonim, a.created_at, b.title, c.user_pp_img FROM $table_name2 a
	left JOIN $table_name b ON b.campaign_id = a.campaign_id
	left JOIN $table_name3 c ON c.user_id = a.user_id
	where a.status='1' and a.campaign_id='$row->campaign_id' ORDER BY id DESC LIMIT 0,$total ");

	$data_donasinya = '';
	foreach ($data_donasi as $value) {
		
		$donatur_name = $value->name;
		if($value->anonim=='1'){
			$donatur_name = $anonim_text;
		}

		if(strpos($title, '{campaign_title}') !== false) {
		    $title = str_replace("{campaign_title}",$value->title,$title);
		}

		$pp = '';
		if($value->user_pp_img!=''){
			$pp = $value->user_pp_img;
		}

		$the_time = donasiaja_readtime($value->created_at);

		$donatur_name = str_replace("'",'',$donatur_name);
		$donatur_name = str_replace('"','',$donatur_name);

		$title_campaign = str_replace("'",'',$title);
		$title_campaign = str_replace('"','',$title_campaign);
		
		$data_donasinya .= '{"content": ["'.$donatur_name.'", "'.$the_time.'", "'.$title_campaign.'", "'.$pp.'", "'.$value->campaign_id.'"]},';
	}

	$id_login = wp_get_current_user()->ID;

	$aff_code = '';
	if($id_login!=null){
		$rows_aff = $wpdb->get_results("SELECT aff_code from $table_name7 where campaign_id='$row->campaign_id' and user_id='$id_login' ORDER BY id DESC");
		if($rows_aff!=null){
			$aff_code = $rows_aff[0]->aff_code;
		}
	}


	$get_fundraiser = $wpdb->get_results("SELECT a.campaign_id, c.user_id as fundraiser_id, count(a.id) as jumlah_donatur, sum(b.nominal) as total
	FROM $table_name8 a 
	LEFT JOIN $table_name7 c on c.id = a.affcode_id 
	LEFT JOIN $table_name2 b on b.id = a.donate_id 
	where a.campaign_id='$row->campaign_id' and b.status = '1'
	GROUP BY fundraiser_id ORDER BY total DESC limit 0,5");

	$all_fundraiser = $wpdb->get_results("SELECT a.campaign_id, c.user_id as fundraiser_id, count(a.id) as jumlah_donatur, sum(b.nominal) as total
	FROM $table_name8 a 
	LEFT JOIN $table_name7 c on c.id = a.affcode_id 
	LEFT JOIN $table_name2 b on b.id = a.donate_id 
	where a.campaign_id='$row->campaign_id' and b.status = '1'
	GROUP BY fundraiser_id ORDER BY total DESC");

	$hex = $button_color;
	list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
	$colornya = 'rgba('.$r.','.$g.','.$b.', 0.15)';
	$color_hovernya = 'rgba('.$r.','.$g.','.$b.', 0.25)';


	// affcode
	$aff_ip = donasiaja_getIP();
    $aff_os = donasiaja_getOS();
    $aff_browser = donasiaja_getBrowser();

    if (strpos($affcode, '&') !== false ) {
		$get_affcode = explode('&',$affcode);
		$get_affcode = $get_affcode[0];
	}else{
		$get_affcode = $affcode;
	}

	if (strpos($get_affcode, 'ref=') !== false ) {
		$data_affcode = explode('ref=',$get_affcode);
		$data_affcode = $data_affcode[1];
	}else{
		$data_affcode = '';
	}

	$link_ref_aff = '';
	$affcode_id = '';
    if($data_affcode!=''){
    	// get aff_code
    	$check_affcode = $wpdb->get_results('SELECT * from '.$table_name7.' where aff_code="'.$data_affcode.'" ');
    	if($check_affcode!=null){
    		$affcode_id = $check_affcode[0]->id;
    		$link_ref_aff = "?ref=$data_affcode";
    		
    		list ( $y, $m, $d ) = explode('.', date('Y.m.d'));
    		$today_start = mktime(0, 0, 0, $m, $d, $y);
			$today_end   = mktime(23, 59, 59, $m, $d, $y);
    		$check_log = $wpdb->get_results("SELECT * from $table_name10 where campaign_id='$row->campaign_id' and affcode_id='$affcode_id' and ip='$aff_ip' and os='$aff_os' and browser='$aff_browser' and created_at >= CURDATE()");
    		if($check_log==null){
    			// insert log
    			$wpdb->insert( $table_name10,
		            array(
		                'campaign_id'   => $row->campaign_id,
		                'affcode_id'    => $affcode_id,
		                'ip'    		=> $aff_ip,
		                'os'    		=> $aff_os,
		                'browser'    	=> $aff_browser,
		                'created_at'    => date("Y-m-d H:i:s")),
		            array('%s', '%s')       
		        );
    		}
    	}
    }

    //URL PARAM
    require_once(ROOTDIR_DNA . 'josh-utm.php');
	$get_parameters = jpass_param();

	/**
	 * Setting ref mode, if there is ref query true, false if not
	 */
	$ref_mode = ( isset( $get_parameters['ref'] ) ) ? true : false;

    require_once(ROOTDIR_DNA . 'core/api/fb-conversion-api.php');
	$conversion_api = new ABI_Facebook_Conversion_API( $fb_pixel, $track_mode, $link_code, $row );
	// $conversion_api = new ABI_Facebook_Conversion_API( '630434134671269', $track_mode, $link_code, $row );
	// $conversion_api = new ABI_Facebook_Conversion_API( '630434134671269', 'EAAMncttZCQYsBAJplDAY8DFuIfbdi2F9YXjcXCmUBQrwn1msz5iG0IjOBzrdZAeoAy3HjHrgj86W4ZB8vbMe8IeKpvqZCyUpZBrYkb60Mhht5nushroHJ0YlCbEWyHEKSbZAmVQ4sYyOcCOZBvxb6AfpJLmd2jKX5ID4eWXRZBTJZAQ3rU1hehISWLdtIUDbAmq8ZD', $track_mode, $link_code, $row );

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<title><?php echo $row->title.' | '.$app_name; ?></title>
	<link rel="icon" href="https://ympb.or.id/wp-content/uploads/2022/10/cropped-Logo-ympb-768x782-1-300x300.png">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=0">
	<meta name="application-name" content="<?php echo $current_url; ?>"/>
	<meta name="title" content="<?php echo $row->title; ?>">
	<meta name="description" content="<?php echo strip_tags($information_for_head); ?>">
	<meta property="og:url" content="<?php echo $current_url; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo $row->title; ?>" />
	<meta property="og:description" content="<?php echo strip_tags($information_for_head); ?>" />
	<?php if($row->image_url!=null){?>
	<meta property="og:image" content="<?php echo $row->image_url; ?>" />
	<?php }else{?>
	<meta property="og:image" content="<?php echo plugin_dir_url( __FILE__ ).'admin/images/cover_donasiaja.jpg'; ?>" />
	<?php } ?>
	<meta property="twitter:card" content="summary_large_image">
	<meta property="twitter:url" content="<?php echo $current_url; ?>">
	<meta property="twitter:title" content="<?php echo $row->title; ?>">
	<meta property="twitter:description" content="<?php echo strip_tags($information_for_head); ?>">
	<?php if($row->image_url!=null){?>
	<meta property="twitter:image" content="<?php echo $row->image_url; ?>" />
	<?php }else{?>
	<meta property="twitter:image" content="<?php echo plugin_dir_url( __FILE__ ).'admin/images/cover_donasiaja.jpg'; ?>" />
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/donasiaja.css';?>">
	<script src="https://www.youtube.com/iframe_api"></script>
	<style type="text/css">
		a:active,a:focus,a:visited{box-shadow:none!important;outline:none;box-shadow:0 4px 15px 0 rgba(0,0,0,.1)}.loc_name{margin-top: -20px;padding-left: 25px;font-size: 13px;color: #a3aab0;}.d_map:hover .loc_name{color:#2196F3!important;transition:all 0.25s ease-in-out}.fancy-button{margin:auto;position:relative}.frills,.frills:after,.frills:before{position:absolute;background:#eb1f48;border-radius:4px;height:4px}.frills:after,.frills:before{content:"";display:block}.frills:before{bottom:15px}.frills:after{top:15px}.left-frills{right:180px;top:0}.active .left-frills{-webkit-animation:move-left 0.38s ease-out,width-to-zero 0.38s ease-out;animation:move-left 0.38s ease-out,width-to-zero 0.38s ease-out}.left-frills:before,.left-frills:after{left:15px}.active .left-frills:before{-webkit-animation:width-to-zero 0.38s ease-out,move-up 0.38s ease-out;animation:width-to-zero 0.38s ease-out,move-up 0.38s ease-out}.active .left-frills:after{-webkit-animation:width-to-zero 0.38s ease-out,move-down 0.38s ease-out;animation:width-to-zero 0.38s ease-out,move-down 0.38s ease-out}.right-frills{left:40px;top:0}.active .right-frills{-webkit-animation:move-right 0.38s ease-out,width-to-zero 0.38s ease-out;animation:move-right 0.38s ease-out,width-to-zero 0.38s ease-out}.right-frills:before,.right-frills:after{right:15px}.active .right-frills:before{-webkit-animation:width-to-zero 0.38s ease-out,move-up 0.38s ease-out;animation:width-to-zero 0.38s ease-out,move-up 0.38s ease-out}.active .right-frills:after{-webkit-animation:width-to-zero 0.38s ease-out,move-down 0.38s ease-out;animation:width-to-zero 0.38s ease-out,move-down 0.38s ease-out}.left-frills:before,.right-frills:after{transform:rotate(34deg)}.left-frills:after,.right-frills:before{transform:rotate(-34deg)}.total_love span{color:#F43756}.plus1{font-size:11px;margin-left:5px;position:absolute;top:0;color:#F43756;display:none}.plus1.show{display:inline}@-webkit-keyframes move-left{0%{transform:none}65%{transform:translateX(-30px)}100%{transform:translateX(-30px)}}@keyframes move-left{0%{transform:none}65%{transform:translateX(-80px)}100%{transform:translateX(-80px)}}@-webkit-keyframes move-right{0%{transform:none}65%{transform:translateX(80px)}100%{transform:translateX(80px)}}@keyframes move-right{0%{transform:none}65%{transform:translateX(80px)}100%{transform:translateX(80px)}}@-webkit-keyframes width-to-zero{0%{width:18px}100%{width:8px}}@keyframes width-to-zero{0%{width:18px}100%{width:8px}}@-webkit-keyframes move-up{100%{bottom:69.75px}}@keyframes move-up{100%{bottom:69.75px}}@-webkit-keyframes move-down{100%{top:69.75px}}@keyframes move-down{100%{top:69.75px}}
		.video-container { position: relative; padding-bottom: 56.25%; padding-top: 30px; height: 0; overflow: hidden; }
		.video-container iframe, .video-container object, .video-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
			.donation_button_fundraiser{display:inline-block;border:0 none;font-weight:700;line-height:normal;text-align:center;vertical-align:middle;cursor:pointer;transition:all .35s ease 0s;text-decoration:none;border-radius:4px;width:100%;padding:12px 45px;font-size:16px;background-color:#dc3264;color:#fff;border:2px solid #dc3264;height:47px}.donation_button_fundraiser{margin-top:20px;width:50%;margin-right:2%;color:#fff;background:#e6f4ff;border:2px solid #1c7bce;color:#1c7bce;padding:5px 45px 17px 45px;box-shadow:0 10px 12px 0 rgba(0,0,0,.1)!important}.donation_button_fundraiser img{position:absolute;width:24px;margin-left:-75px;margin-top:3px}.donation_button_fundraiser .text-fundraiser{padding-top:8px;padding-left:28px;font-size:13px;font-weight:700}
    		.donation_button_fundraiser:hover {
      			background: <?php echo $color_hovernya; ?> !important;
      			box-shadow: 0px 18px 15px 0 rgba(0,0,0,.1) !important;
    		}
    		.copy_link_aff img { width: 20px; margin-top: 6px; margin-left: -65px; }
    		.fundraiser-loading{display:inline-block}.fundraiser-loading:after{content:" ";display:block;width:10px;height:10px;margin:0;border-radius:50%;border:3px solid #fff;border-color:<?php echo $button_color; ?> transparent <?php echo $button_color; ?> transparent;animation:fundraiser-loading 1.2s linear infinite;position:absolute;margin-top:-13px;margin-left:10px}@keyframes fundraiser-loading{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
    		.fundraiser-hide{display:none}
    		@media only screen and (max-width:480px) {
	    		.donation_button_fundraiser {
	    			width: 100%;
	    		}
    		}

	</style>

	<?php 
    if (strpos($fb_pixel, ',') !== false ) {

        $array_pixel  = (explode(",", $fb_pixel));
        $count = count($array_pixel);
        $i = 1;
        foreach ($array_pixel as $values){
        	$pixel_id = $values;
        	?>  
    <script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?php echo $pixel_id; ?>');
	fbq('track', '<?php //echo $event_1; ?>ViewContent', {
		"content_name":"<?php echo $row->title; ?>",
		"content_ids":"<?php echo $row->campaign_id; ?>"
	}, {eventID: '<?php echo $_SESSION['abi_identifier']; ?>'});
	</script>
        
        <?php }

    }elseif($fb_pixel==''){
        $pixel_id = "";
    }else{
        $pixel_id = $fb_pixel;
        ?>
    <script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?php echo $pixel_id; ?>');
	fbq('track', '<?php //echo $event_1; ?>ViewContent', {
		"content_name":"<?php echo $row->title; ?>",
		"content_ids":"<?php echo $row->campaign_id; ?>"
	}, {eventID: '<?php echo $_SESSION['abi_identifier']; ?>'});
	</script>

        <?php
    }
    ?>
    <?php if($gtm_id!=''){ ?>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo $gtm_id;?>');</script>
    <!-- End Google Tag Manager -->
    <?php } ?>

    <?php if($tiktok_pixel!=''){ ?>
    <!-- <script>
	!function (w, d, t) {
	  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

	  ttq.load('<?php //echo $tiktok_pixel; ?>');
	  ttq.page();
	}(window, document, 'ttq');
	</script> -->
	<?php } ?>

	<!-- <script src="https://kit.fontawesome.com/87fad1fece.js" crossorigin="anonymous"></script> -->
</head>
<body>
    <div style="display: none;"><?php echo $get_parameters['jpass']; ?></div>
	<?php
	function isMobile() {
	    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	$campaign_title = $row->title;
	if(strlen($campaign_title)>40){
		if(isMobile()){
		    $fix_title = substr($campaign_title, 0, 41).'...';
		}
		else {
		    $fix_title = substr($campaign_title, 0, 50).'...';
		}
	}else{
		$fix_title = $campaign_title;
	}

	$cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

    $set_location = '';
    if($row->location_name!=null){
    	if($row->location_gmaps!=null){
    		$set_location = '<a href="'.$row->location_gmaps.'" target="_blank" style="text-decoration:none;"><span class="d_map"><img alt="Image" src="'.plugin_dir_url( __FILE__ ) . "assets/images/maps.png".'"><div class="loc_name">'.$row->location_name.'</div></span></a>';
    	}else{
    		$set_location = '<span class="d_map"><img alt="Image" src="'.plugin_dir_url( __FILE__ ) . 'assets/images/maps.png"><div class="loc_name">'.$row->location_name.'</div></span>';
    	}
    }

    // cek dia itu target >= 1
    // cek lagi apakah yang didapat >= target
    // $total_donasi->total >= $row->target

    $donasi_terpenuhi = false;
    if($row->target>=1){
    	if($total_donasi->total >= $row->target){
    		if($limitted_donation_button=='1'){
    			$donasi_terpenuhi = true;
    		}
    	}
    }

	?>
	
	<?php if($row->image_url!=null){?>
	<div class="section-image"><img alt="Image" src="<?php echo $row->image_url; ?>"></div>
	<?php }else{?>
	<div class="section-image"><img alt="Imagenya" src="<?php echo plugin_dir_url( __FILE__ ).'admin/images/cover_donasiaja.jpg'; ?>"></div>
	<?php } ?>

	
	<div class="section-box">
		<?php $campaign_show = ( $row->title_2 === null ) ? $campaign_title : $row->title_2 ; ?>
		<div class="title"><h1><?php echo $campaign_show; ?></h1></div>
		<?php if($row->publish_status=='0'){?>
		<p style="font-size: 13px;color: #ababab;">Campaign on Drafts</p>
		<?php } elseif($row->publish_status=='2'){?>
		<p style="font-size: 13px;color: #C8D0DD;">Campaign on Status Review</p>
		<?php } ?>
		<?php echo $set_location; ?>

		<div class="penggalang-dana" style="padding-top: 15px;">
			<div class="profile-picture">
				<img alt="Image" src="https://ympb.or.id/wp-content/uploads/2022/12/Logo-RTTQ.webp" style="border-radius: 120px;border: 1px solid #dde4ec;">
			</div>
			<div class="profile-name">
				<?php if($profile[0]->user_type=='org' && $profile[0]->user_verification=='1') { ?>
					<div class="user-link" style="color: #03a9f4;">
						<span class="">Rumah Tahfizh Tanzilul Qur'an</span>
					</div>
					<div class="verified_checklist"><img alt="Image" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/check-org2.png'; ?>" style="width:42px;"></div><div class="user-verified" style="margin-left: 48px;font-style: italic;color: #a2b0ca;">Verified Organization</div>
				<?php } elseif($profile[0]->user_type=='personal' && $profile[0]->user_verification=='1') { ?>
					<div class="user-link" style="color: #03a9f4;">
						<span class=""><?php echo $fullname; ?></span>
					</div>
					<div class="verified_checklist"><img alt="Image" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/check.png'; ?>"></div><div class="user-verified" style="font-style: italic;color: #a2b0ca;">Verified User</div>
				<?php } else { ?>
					<div class="user-link" style="padding-top: 10px; color: #03a9f4;">
						<span class=""><?php echo $fullname; ?></span>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php
		if( $ref_mode === false ) {
		?>
		<div class="donation_box2">
			
			<?php if($row->target==0){?>
				<span class="d_total">Rp <?php echo number_format($total_donasi->total,0,",","."); ?></span>
				<span class="d_target">
					<span class="d_target_text">dan masih terus dikumpulkan</span>
				</span>
			<?php }else { ?>
				<span class="d_total">Rp <?php echo number_format($total_donasi->total,0,",","."); ?></span>
				<span class="d_target">
					<span class="d_target_text">terkumpul&nbsp;dari&nbsp;<b>Rp&nbsp;<?php echo number_format($row->target,0,",","."); ?></b></span>
				</span>
			<?php } ?>
			<div class="donation_progress">
				<div class="donation_progress_bar full_green" style="background:<?php echo $progressbar_color; ?>;width:<?php echo $persen_width; ?>%"></div>
			
			<?php if($row->target==0){ ?>
				<span class="d_target_graph"><b><?php echo $total_donasi->jumlah;?></b> <?php echo $donatur_title; ?></span></span>
			<?php } else { ?>

				<span class="d_target_graph"><b><?php echo $total_donasi->jumlah;?></b> Donasi</span>
			<?php } ?>

			<span class="d_date"><span><?php echo $sisa_waktu; ?></span></span>
			</div>
		</div>

		<?php
		}
		?>

		<?php if($hasil->invert==true){ ?>
		<div class="section-button button-disabled"><a href="javascript:;"><button class="donation_button_now" id="btn-primary-josh"><?php echo $text1; ?></button></a></div>

		<?php } elseif($donasi_terpenuhi==true){ ?>
		<div class="section-button button-disabled"><a href="javascript:;"><button class="donation_button_now" id="btn-primary-josh"><?php echo $text2; ?> Terpenuhi</button></a></div>

		<?php }else{ ?>
		<div class="section-button"><a href="<?php echo $current_url;?>/<?php echo $page_donate; ?><?php echo $link_ref_aff; ?><?php echo (isset($get_parameters['jpass'])) ? $get_parameters['jpass'] : ''; ?>"><button class="donation_button_now" style="background:<?php echo $button_color;?>;border-color:<?php echo $button_color;?>"  id="btn-primary-josh"><?php echo $text1; ?></button></a></div>
		<?php } ?>
	</div>
	

	<?php if($label_tab=="tab") { ?>
	<div class="section-box news" id="tab-josh">
		<h3>Latar Belakang Program</h3>
		<div class="container--tabs" id="info-update">
			<div id="tab-1" class="tab-pane active" style="padding-bottom: 0;"> 
				<div class="col-md-10">
					<div class="donasiaja-readmore-josh" id="josh-readmore" style="overflow: hidden;">
					<?php echo $information; ?>
					</div>
				</div>
				<div class="section-readmore-josh" id="container-readmore-josh">
					<button id= "btn-readmore-josh" onclick="joshreadmore()" style="border-radius: 40px;border: 1px solid #dbe0e5;color: #fff;padding: 0 2rem !important;margin-top: 5px;background: #3ea6ff !important;font-size: 12px !important;letter-spacing: .2px;box-shadow: 0px 5px 5px 0 rgb(0 0 0 / 10%) !important;height: 34px !important;"><span id="spn-readmore-josh">Baca Selengkapnya</span><span style="padding-left: 5px;">
					<i class="fa-solid fa-arrow-down" id="i-readmore-josh"></i>
					</span></button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="section-box update">
		<h3>Penyaluran Program</h3>
		<div id="tab-2" class="tab-pane">
			<div class="col-md-10">
				<?php
		
				$dt = new DateTime($row->created_at);
				$m = $dt->format('F');
		
				if (strpos($m, 'January') !== false ) { $m = 'Januari'; }
				elseif(strpos($m, 'February') !== false ) { $m = 'Februari'; }
		
				$dt_published = $m.', '.$dt->format('j Y');
		
				?>
		
				<?php if($campaign_update==null){ ?>
		
				<div id="kabar-terbaru-donasi">
					  <ul class="timeline" style="margin-top: 50px;">
						<li class="timeline-milestone is-start" style="height: 50px;">
						  <div class="timeline-action">
							  
							<h3 class="title">Campaign is published</h3>
						</div>
						</li>
					  </ul>
				</div>
		
				<?php }else{?>
				<div id="kabar-terbaru-donasi">
					  
					<ul class="timeline" style="margin-top: 50px;">
						<?php 
						  foreach ($campaign_update as $value) { 
		
							  $readtime = new donasiaja_readtime();
							$time_update = $readtime->time_donation($value->created_at);
		
							$dt = new DateTime($value->created_at);
							$m = $dt->format('F');
		
							if (strpos($m, 'January') !== false ) { $m = 'Januari'; }
							elseif(strpos($m, 'February') !== false ) { $m = 'Februari'; }
							
							$dt = $m.', '.$dt->format('j Y');
		
						  ?>
							<li class="timeline-milestone is-current">
							  <div class="timeline-action is-expandable expanded">
								  <span class="date"><?php echo $dt; ?></span>
								  <h3 class="title"><?php echo $value->title; ?></h3>
								  <div class="content">
									  <?php
										$information_update = str_replace("'", "&#39;", $value->information); // petik 1
										// $information_update = str_replace('"', "&#34;", $information_update); // petik 2
										$information_update = str_replace('../wp-content', home_url().'/wp-content', $information_update);
										
										echo $information_update; 
										?>
									</div>
								</div>
							</li>
							<?php } ?>
							<li class="timeline-milestone is-start" style="height: 50px;">
								<div class="timeline-action">
									
									<h3 class="title">Campaign is published</h3>
								</div>
							</li>
						</ul>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<div class="section-box donors">
			<h3>Orang-Orang Baik <?php if($jumlah_donasi->jumlah!=0){echo '<span class = "doa-box" style="font-size: 14px; color: white; font-weight: 500; background: #f43756; padding: 5px 10px 5px 10px; border-radius: 20px; margin-left: 6px;">' . $jumlah_donasi->jumlah . '</span>';} ?></h3>
			<div id="tab-3" class="tab-pane">
				<div class="col-md-10">
				<!-- donation -->
				<?php
	
				$set_rand = d_randomString(4);
	
				?>
				<div class="donation_box black" style="background: #ffffff;">
					<div id="box_<?php echo $set_rand; ?>">
						<?php
						foreach ($donasi as $value) {
							$readtime = new donasiaja_readtime();
							$donation_time = $readtime->time_donation($value->created_at);
							
							//$donatur_name = $value->name;
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
							
							echo '
							<div class="donation_inner_box" style="background:rgb(255, 255, 255);">
								<div class="donation_name">'.$donatur_name.'<span class="donation_time"><span class="dashicons dashicons-clock"></span>'.$donation_time.'</span>
								</div>
								<div class="donation_total">Ber'.strtolower($allocation_title).' sebesar <b>Rp '.number_format($value->nominal,0,",",".").'</b></div>
							</div>
							';
						}
						?>
					</div>
					<div id="box_btn_<?php echo $set_rand; ?>" class="donation_button">
						<?php if($donasi==null){ ?>
							<p style="text-align: center;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/givelove.png'; ?>" style="width: 120px;"></p>
						<p style="color: #a3aab7;font-weight: 400;">Belum ada donasi untuk penggalangan dana ini</p>
						<?php }else{?>
							<div class="loadmore_info"></div>
						<button id="<?php echo $set_rand; ?>" class="load_data_donatur" data-id="<?php echo $row->campaign_id;?>" data-count="2" data-fullanonim="true" data-anonim="<?php echo $anonim_text; ?>">Tampilkan Lagi</button>
						<?php } ?>
					</div>
				</div>
				<!-- end donation -->
			</div>
		</div>

	</div>

	<?php }else{ ?>
	<div class="section-box"><h3>Keterangan</h3>
		<div id="keterangan-donasi" class="donasiaja-readmore">
		<?php echo $information; ?>
		</div>
	</div>
	<div class="section-box" id="info-update"><h3>Kabar Terbaru</h3>
		<?php

		$dt = new DateTime($row->created_at);
	    $m = $dt->format('F');

	    if (strpos($m, 'January') !== false ) { $m = 'Januari'; }
	    elseif(strpos($m, 'February') !== false ) { $m = 'Februari'; }

	    $dt_published = $m.', '.$dt->format('j Y');
	    ?>

		<?php if($campaign_update==null){ ?>

    	<div id="kabar-terbaru-donasi">
			  <ul class="timeline" style="margin-top: 50px;">
				<li class="timeline-milestone is-start" style="height: 50px;">
			      <div class="timeline-action">
			      	
			        <h3 class="title">Campaign is published</h3>
			      </div>
			    </li>
			  </ul>
    	</div>

    	<?php }else{?>
    	<div id="kabar-terbaru-donasi">
			  
			  <ul class="timeline" style="margin-top: 50px;">
			  	<?php 
			  	foreach ($campaign_update as $value) { 

				  	$readtime = new donasiaja_readtime();
					$time_update = $readtime->time_donation($value->created_at);

					$dt = new DateTime($value->created_at);
				    $m = $dt->format('F');

				    if (strpos($m, 'January') !== false ) { $m = 'Januari'; }
				    elseif(strpos($m, 'February') !== false ) { $m = 'Februari'; }

				    $dt = $m.', '.$dt->format('j Y');

			  	?>
				    <li class="timeline-milestone is-current">
				      <div class="timeline-action is-expandable expanded">
							<span class="date"><?php echo $dt; ?></span>
					        <h3 class="title"><?php echo $value->title; ?></h3>
					        <div class="content">
					        	<?php
						        	$information_update = str_replace("'", "&#39;", $value->information); // petik 1
									// $information_update = str_replace('"', "&#34;", $information_update); // petik 2
									$information_update = str_replace('../wp-content', home_url().'/wp-content', $information_update);
						        	echo $information_update; 
						        ?>
					        </div>
				      </div>
				    </li>
				<?php } ?>
				<li class="timeline-milestone is-start" style="height: 50px;">
			      <div class="timeline-action">
			      	
			        <h3 class="title">Campaign is published</h3>
			      </div>
			    </li>
			  </ul>
    	</div>
    	<?php } ?>
		
	</div>

	<div class="section-box"><h3><?php echo $donatur_title; ?> <?php if($total_donasi->jumlah!=0){echo "($total_donasi->jumlah)";} ?></h3>
		<?php
		$set_rand = d_randomString(4);
		?>
		<div class="donation_box black" style="<?php if($donasi==null){ echo 'background:#ffffff;padding-top:10px;';} ?>background: #fff;">
		    <div id="box_<?php echo $set_rand; ?>">
		        <?php
		        foreach ($donasi as $value) {
		        	$readtime = new donasiaja_readtime();
					$donation_time = $readtime->time_donation($value->created_at);

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

					// cek apakah user sudah pernah love
					$a = donasiaja_getIP();
				    $b = donasiaja_getOS();
				    $c = donasiaja_getBrowser();
				    $d = donasiaja_getMobDesktop();
					if($id_login!='0'){
						// cek berdasarkan user_agent
						$data = $wpdb->get_results('SELECT * from '.$table_name4.' where ip="'.$a.'" and os="'.$b.'" and browser="'.$c.'" and mobdesktop="'.$d.'" and donate_id="'.$value->id.'"');
				    	if($data!=null){
				    		$left_span = '<span>';
				    		$right_span = '</span>';
				    		$icon_love = plugin_dir_url( __FILE__ ) . 'assets/icons/praying-color3.png';
				    	}else{
				    		$left_span = '';
				    		$right_span = '';
				    		$icon_love = plugin_dir_url( __FILE__ ) . 'assets/icons/praying-gray.png';
				    	}
					}else{
						// cek berdasarkan user_id
						$data = $wpdb->get_results('SELECT * from '.$table_name4.' where user_id="'.$id_login.'" ');
				    	if($data!=null){
				    		$left_span = '<span>';
				    		$right_span = '</span>';
				    		$icon_love = plugin_dir_url( __FILE__ ) . 'assets/icons/praying-color3.png';
				    	}else{
				    		$left_span = '';
				    		$right_span = '';
				    		$icon_love = plugin_dir_url( __FILE__ ) . 'assets/icons/praying-gray.png';
				    	}
					}


					$total_love = $wpdb->get_results("SELECT SUM(love) as jumlah FROM $table_name4 where donate_id='$value->id' ")[0];
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


		        	echo '
			        <div class="donation_inner_box" style="background: rgb(250, 252, 255);">
			            <div class="donation_name">'.$donatur_name.'<span class="donation_time"><span class="dashicons dashicons-clock"></span>'.$donation_time.'</span>
			            </div>
			            <div class="donation_total">Ber'.strtolower($allocation_title).' sebesar <b>Rp '.number_format($value->nominal,0,",",".").'</b></div>
			        </div>
			        ';
		        }
		        ?>
		    </div>
		    <div id="box_btn_<?php echo $set_rand; ?>" class="donation_button">
		    	<?php if($donasi==null){ ?>
		    	<p style="text-align: center;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/givelove.png'; ?>" style="width: 120px;"></p>
		    	<p style="color: #a3aab7;font-weight: 400;">Belum ada donasi untuk penggalangan dana ini</p>
		    	<?php }else{?>
		        <div class="loadmore_info"></div>
		        <button id="<?php echo $set_rand; ?>" class="load_data_donatur" data-id="<?php echo $row->campaign_id;?>" data-count="2" data-fullanonim="true" data-anonim="<?php echo $anonim_text; ?>">Tampilkan Lagi</button>
			    <?php } ?>
		    </div>
		</div>
	</div>

	<?php } ?>

	<?php
		if($fundraiser_on=='1'){
			if($row->fundraiser_setting=='0' || $row->fundraiser_setting==null){
				$fundraiser_show = true;
			}else if($row->fundraiser_setting=='1'){
				if($row->fundraiser_on=='1'){
					$fundraiser_show = true;
					if($row->fundraiser_button==''){
						$fundraiser_button = 'Jadi Fundraiser';
					}else{
						$fundraiser_button = $row->fundraiser_button;
					}
					$fundraiser_text = $row->fundraiser_text;
				}else{
					$fundraiser_show = false;
				}
			}else{
				$fundraiser_show = false;
			}
		}else{
			$fundraiser_show = false;
		}

	?>

	<?php if($fundraiser_show==true) { ?>

		<div class="section-box"><h3>Fundraiser <?php if(count($all_fundraiser)!=0){echo '('.count($all_fundraiser).')';} ?></h3>
			<?php
			$set_rand = d_randomString(4);
			?>
			<div class="donation_box black" style="background:#ffffff">
			    <div id="box_<?php echo $set_rand; ?>">
			        <?php

			        foreach ($get_fundraiser as $value) {
			        	$user_info = get_userdata($value->fundraiser_id);
					    $fullname = $user_info->first_name.' '.$user_info->last_name;

			        	echo '
				        <div class="donation_inner_box" style="background:rgb(250, 252, 255);line-height:1.6;">
				            <div class="donation_name" style="color:'.$button_color.'">'.$fullname.'</div>
				            <div class="donation_comment" style="margin:0;">Berhasil mengajak '.$value->jumlah_donatur.' orang untuk berdonasi.<br></div>
				            <div class="donation_name">Rp&nbsp;'.number_format($value->total,0,",",".").'</div>
				        </div>
				        ';

			        }
			        ?>
			    </div>
			    <div id="box_btn_<?php echo $set_rand; ?>" class="donation_button">
			    	<?php if(count($all_fundraiser)==null){ ?>
			    	<p style="text-align: center;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/loudspeaker.png'; ?>" style="width: 120px;"></p>
			    	<p style="color: #a3aab7;">Belum ada Fundraiser</p>
			    	<?php }else{?>
			        <div class="loadmore_info"></div>
			        <button id="<?php echo $set_rand; ?>" class="load_fundraiser" data-id="<?php echo $row->campaign_id;?>" data-count="2" data-fullanonim="true" data-anonim="<?php echo $anonim_text; ?>">Tampilkan Lagi</button>
				    <?php } ?>
			    </div>
			</div>

			    <div style="text-align:center;padding: 30px 10px 40px 10px;">
			    	<div style="font-size: 15px;font-family: 'Lato', FontAwesome, lato, sans-serif !important;margin-bottom:10px;"><?php echo $fundraiser_text; ?></div>
			    	<?php if($aff_code==''){ ?>
			    		<div><button class="donation_button_fundraiser regaff" data-cid="<?php echo $row->campaign_id; ?>" style="background:<?php echo $colornya; ?>;border-color:<?php echo $button_color;?>;margin-top: 25px;"><div><img alt="Image" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/groups.png'; ?>"><div class="text-fundraiser" style="color:#2D3849;"><?php echo $fundraiser_button; ?><div class="fundraiser-loading fundraiser-hide"></div></div></div></button></div>
				    <?php }else{ ?>
				    	<div><button class="donation_button_fundraiser copy_link_aff" data-cid="<?php echo $row->campaign_id; ?>" style="background:<?php echo $colornya; ?>;border-color:<?php echo $button_color;?>;margin-top: 25px;" data-link="<?php echo $current_url; ?><?php if($aff_code!=''){echo '?ref=';echo $aff_code;}?>"><div><img alt="Image" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/link2.png'; ?>"><div class="text-fundraiser" style="color:#2D3849;">Copy Link Aff</div></div></button></div>
				    <?php } ?>
			    </div>
		</div>
	<?php } ?>

	<div class="section-box box-powered">
		<?php if($powered_by_setting=='1'){ ?>
		<div class="powered-donasiaja-box"><a href="https://donasiaja.id" target="_blank"><img alt="Donasi Aja" class="powered-donasiaja-img" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/donasiaja.ico'; ?>">Powered by DonasiAja</a></div>
		<?php } ?>
	</div>
	
	<div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>
	<div class="section-box" id="fixed-button">
		<button class="donation_button_share"><img alt="Image" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/share.png'; ?>"><span class="text-share">Share</span><div class="text-bagikan">Bagikan</div></button><?php if($hasil->invert==true){ ?><a href="javascript:;" class="button-disabled"><button class="donation_button_now2"><?php echo $text1; ?></button></a><?php } elseif($donasi_terpenuhi==true){ ?><a href="javascript:;" class="button-disabled"><button class="donation_button_now2"><?php echo $text2; ?> Terpenuhi</button></a><?php }else{ ?>
		<a href="<?php echo $current_url;?>/<?php echo $page_donate; ?><?php echo $link_ref_aff; ?><?php echo $get_parameters['jpass']; ?>"><button class="donation_button_now2" style="background:<?php echo $button_color;?>;border-color:<?php echo $button_color;?>"><?php echo $text1; ?></button></a>
		<?php }?>
	</div>

	<div id="fixed-share-button" class="section-box">
		<div class="share-title">Bagikan melalui:</div>
		<div class="share-close">✕ Close</div>

		<button class="donation_social_button donasiaja_copy_link" data-link="<?php echo $current_url; ?><?php if($aff_code!=''){echo'?ref='.$aff_code;}?>"><span><img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/link.png'; ?>" style="opacity: 0;margin-left: -15px;" alt="Copy Link"><div class="text-copy">Copy Link</div></span></button>

		<a class="donasiaja-share wa" href="https://api.whatsapp.com/send?&text=<?php echo $row->title; ?>%0A<?php echo $current_url; ?><?php if($aff_code!=''){echo'?ref='.$aff_code;}?>">
			<button class="donation_social_button whatsaap"><span><img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/whatsapp.png'; ?>" alt="Whatsaap"></span>
			</button>
		</a>

		<a class="donasiaja-share fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?><?php if($aff_code!=''){echo'?ref='.$aff_code;}?>">
			<button class="donation_social_button facebook"><span><img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/facebook.png'; ?>" alt="Facebook"></span>
			</button>
		</a>

		<a class="donasiaja-share twit" href="https://twitter.com/intent/tweet?text=<?php echo $current_url; ?><?php if($aff_code!=''){echo'?ref='.$aff_code;}?>">
			<button class="donation_social_button twitter"><span><img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/twitter.png'; ?>" alt="Twitter"></span>
			</button>
		</a>

		<a class="donasiaja-share tele" href="https://telegram.me/share/url?text=<?php echo $row->title; ?> &url=<?php echo $current_url; ?><?php if($aff_code!=''){echo'?ref='.$aff_code;}?>">
			<button class="donation_social_button telegram"><span><img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/telegram.png'; ?>" alt="Telegram"></span>
			</button>
		</a>
	</div>

	<?php if($row->socialproof=='1') { 

	echo '<script src="'.plugin_dir_url( __FILE__ ).'assets/js/toastify.js"></script>';

	echo '
		<script>

		Array.prototype.getRandomColor= function(cut){
		    var i= Math.floor(Math.random()*this.length);
		    if(cut && i in this){
		        return this.splice(i, 1)[0];
		    }
		    return this[i];
		}
		var avatar_colors= ["#D94452", "#FA6C51", "#F5B945", "#9ED26A", "#35BA9B", "#4FC0E8", 
		"#9579DA", "#E3B692", "#E5C3C1", "#E7CDAC"];
		splittedContent = [
				'.$data_donasinya.'
	        ];

		function loopSplit(splittedContent) {
		    for (var i = 0; i < splittedContent.length; i++) {
		        (function (i) {
		        })(i);
		    };
		}
		loopSplit(splittedContent);
				
		var d = 0, howManyTimes = splittedContent.length;

		function f_socialproof() {

		  	var name 	= splittedContent[d].content[0];
		    var time 	= " - "+splittedContent[d].content[1];
		    var title 	= splittedContent[d].content[2];
		    var pp_url 	= splittedContent[d].content[3];
		    var c_id 	= splittedContent[d].content[4];
		    show_color_avatar = "";
		    if(pp_url!=""){
		    	show_color_avatar = "display:none;";
		    }
		    show_img_avatar = "";
		    if(pp_url==""){
		    	show_img_avatar = "display:none;";
		    }
		    var show_time = "'.$time.'";
		    if(show_time=="hide"){
		    	time = "";
		    }
		    setTimeout(function() {
		       Toastify({
				  text: "<div class=dsproof-container id="+c_id+"><div class=dsproof-avatar style=background:"+avatar_colors.getRandomColor()+";"+show_color_avatar+">"+name.substring(0, 1)+"</div><div class=dsproof-avatar style="+show_img_avatar+"><img src="+pp_url+"></div><div class=dsproof-content><div class=dsproof-name>"+name+"</div><div class=dsproof-title>"+title+"</div><div class=dsproof-verified><img src='.plugin_dir_url( __FILE__ ).'assets/images/check.png'.'><span>Verified"+time+"</span></div><div></div>",
				  className: "donasiaja-socialproof'.$set_style.'",
				  escapeMarkup : false,
				  gravity: "'.$p_gravity.'",
				  position: "'.$p_position.'",
				  close: "'.$close.'",
				  duration: 5000,
				  style: {
				    background: "linear-gradient(to right, #ffffff, #ffffff)",
				  }
				}).showToast();
			}, 1000)
		  d++;
		  if (d < howManyTimes) {
		    setTimeout(f_socialproof, '.$delay.');
		  }
		}
		if(splittedContent.length>=1){
			f_socialproof();   
		}
		</script>

	';

	echo '<style>
		.donasiaja-socialproof{line-height: 1.5;border-radius:6px;max-width:360px;height:auto;padding-right:20px!important;z-index:9999;background:#fff!important;box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.09) !important;}.donasiaja-socialproof .toast-close{border-radius:20px;position:absolute;right:0;color:#fff;margin-top:-16px!important;background:#0000004f;width:25px!important;height:25px!important;font-size:13px!important;text-align:center!important;padding:2px!important;opacity:1;top:10px}.dsproof-avatar{border-radius:4px;width:50px;height:50px;text-align:center;position:absolute;margin-left:-7px;margin-top:0px;font-size:32px;font-weight:700;color:#fffc;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-avatar img{width:50px;height:50px;border-radius:4px;}.dsproof-content{margin-left:54px;color:#888;font-size:11px;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-name{font-size:13px;font-weight:700;color:#35363c;position:absolute;margin-top:-3px}.dsproof-title{color:#656577;padding-top:16px;padding-bottom:2px}.dsproof-verified{font-size:10px;color:#b0b0c6;margin-bottom:2px;}.dsproof-verified span{padding-left:15px}.dsproof-verified img{width:12px;position:absolute;margin-top:2px}.toastify{min-width:160px;padding:12px 20px;padding-top:12px!important;color:#fff;display:inline-block;box-shadow:0 3px 6px -1px rgba(0,0,0,.12),0 10px 36px -4px rgba(77,96,232,.3);background:-webkit-linear-gradient(315deg,#73a5ff,#5477f5);background:linear-gradient(135deg,#73a5ff,#5477f5);position:fixed;opacity:0;transition:all .4s cubic-bezier(.215,.61,.355,1);cursor:pointer;text-decoration:none;z-index:2147483647}.toastify.on{opacity:1}.toast-close{opacity:.4;padding:0 5px}.toastify-right{right:15px}.toastify-left{left:15px}.toastify-top{top:-150px}.toastify-bottom{bottom:-150px}.toastify-rounded{}.toastify-avatar{width:1.5em;height:1.5em;margin:-7px 5px;border-radius:2px}.toastify-center{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content;max-width:-moz-fit-content}@media only screen and (max-width:360px){.toastify-left,.toastify-right{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content}} .donasiaja-socialproof.s-rounded .dsproof-avatar{border-radius: 50px;} .donasiaja-socialproof.s-rounded .dsproof-avatar img{border-radius: 50px;}
		.donasiaja-socialproof.s-rounded {height:auto !important;}
		.donasiaja-socialproof.s-rounded .dsproof-avatar {margin-top:0px;}
		.donasiaja-socialproof.s-flying { background: transparent !important;box-shadow:none !important;}
		.donasiaja-socialproof.s-flying .dsproof-avatar { box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.04) !important;}
		.donasiaja-socialproof.s-flying .dsproof-content { background: #fff;padding: 10px 20px 10px 16px;border-radius: 4px;box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.04)}
	</style>';

	} // close socialproof ?>
	
	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/jquery.min.js';?>"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/donasiaja.min.js';?>"></script>
	<!-- <script src="<?php //echo plugin_dir_url( __FILE__ ) . 'assets/js/hello.donasiaja.js';?>"></script> -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/josh-berita.js';?>"></script>
	<script>

		window.addEventListener("load", function() {

			// store tabs variable
			var myTabs = document.querySelectorAll("ul.nav-tabs > li");

			function myTabClicks(tabClickEvent) {

				for (var i = 0; i < myTabs.length; i++) {
					myTabs[i].classList.remove("active");
				}

				var clickedTab = tabClickEvent.currentTarget; 

				clickedTab.classList.add("active");

				tabClickEvent.preventDefault();

				var myContentPanes = document.querySelectorAll(".tab-pane");

				for (i = 0; i < myContentPanes.length; i++) {
					myContentPanes[i].classList.remove("active");
				}

				var anchorReference = tabClickEvent.target;
				var activePaneId = anchorReference.getAttribute("href");
				var activePane = document.querySelector(activePaneId);

				activePane.classList.add("active");

			}

			for (i = 0; i < myTabs.length; i++) {
				myTabs[i].addEventListener("click", myTabClicks)
			}
		});

		/**
		 * YT Defer
		 * @since 15 Apr 2023
		 */
		var players = [];
		function onYouTubeIframeAPIReady() {
			var placeholderDivs = document.getElementsByClassName('youtube-target')
			for(var i=0; i < placeholderDivs.length; i++) {
				var placeholderDiv = placeholderDivs[i]

				var player = new YT.Player(placeholderDiv, {
					videoId: placeholderDiv.getAttribute('video-id'),
					playerVars: {
						'autoplay': 0,
						'controls': 1,
						'rel': 0,
						'showinfo': 0
					},
					events: {
						'onReady': onPlayerReady
					}
				});

				players.push(player)
			}
		}

		// pause video when it is first loaded
		function onPlayerReady(event) {
			event.target.pauseVideo();
		}

		$(document).ready(function() {


		  $timelineExpandableTitle = $('.timeline-action.is-expandable .title');
		  
		  $($timelineExpandableTitle).attr('tabindex', '0');
		  
		  // Give timelines ID's
		  $('.timeline').each(function(i, $timeline) {
		    var $timelineActions = $($timeline).find('.timeline-action.is-expandable');
		    
		    $($timelineActions).each(function(j, $timelineAction) {
		      var $milestoneContent = $($timelineAction).find('.content');
		      
		      $($milestoneContent).attr('id', 'timeline-' + i + '-milestone-content-' + j).attr('role', 'region');
		      $($milestoneContent).attr('aria-expanded', $($timelineAction).hasClass('expanded'));
		      
		      $($timelineAction).find('.title').attr('aria-controls', 'timeline-' + i + '-milestone-content-' + j);
		    });
		  });
		  
		  $($timelineExpandableTitle).click(function() {
		    $(this).parent().toggleClass('is-expanded');
		    $(this).siblings('.content').attr('aria-expanded', $(this).parent().hasClass('is-expanded'));
		  });
		  
		  // Expand or navigate back and forth between sections
		  $($timelineExpandableTitle).keyup(function(e) {
		    if (e.which == 13){ //Enter key pressed
		      $(this).click();
		    } else if (e.which == 37 ||e.which == 38) { // Left or Up
		      $(this).closest('.timeline-milestone').prev('.timeline-milestone').find('.timeline-action .title').focus();
		    } else if (e.which == 39 ||e.which == 40) { // Right or Down
		      $(this).closest('.timeline-milestone').next('.timeline-milestone').find('.timeline-action .title').focus();
		    }
		  });
		});                  


		$(document).ready(function() {
		    $('.donasiaja-share').click(function(e) {
		        e.preventDefault();
		        if ($(this).hasClass("wa") || $(this).hasClass("fb") || $(this).hasClass("twit") || $(this).hasClass("tele")) {
					window.open($(this).attr('href'), 'fbShareWindow', 'height=450, width=550, top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
						return false;
				}
		        
		    });
		});

		$('.donation_button_share').bind("click", function(e) {
			$('#fixed-share-button').addClass("show-button");
		});
		$('.share-close').bind("click", function(e) {
			$('#fixed-share-button').removeClass("show-button");
		});


		$('.donasiaja-readmore').readmore({
		  speed: 12,
		  moreLink: '<a href="#" class="readmore">Baca selengkapnya ▾</a>',
		  lessLink: '<a href="#" class="readmore">Baca dengan ringkas ▴</a>',
		});

		$(function() {
		    var header = $("#header-title");
		    var header2 = $('.campaign-header-title');
		    var footer = $("#fixed-button");
		    $(window).scroll(function() {
		        var scroll = $(window).scrollTop();

		        if (scroll >= 600) {
		            header.addClass("flying-header");
		            header2.addClass("show-title");
		            footer.addClass("flying-button");
		        } else {
		            header.removeClass("flying-header");
		            header2.removeClass("show-title");
		            footer.removeClass("flying-button");
		            $('#fixed-share-button').removeClass("show-button");
		        }
		    });
		});


		$(document).on("click", ".donasiaja_copy_link", function(e) {
			var link_donasi = $(this).data("link");
			copyToClipboard(link_donasi);
			var message = "Copy link donasi berhasil!";
			var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
			var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
			createAlert(message, status, timeout);
		});

		$(document).on("click", ".copy_link_aff", function(e) {
			var link_donasi = $(this).data("link");
			copyToClipboard(link_donasi);
			var message = "Copy Link Aff berhasil!";
			var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
			var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
			createAlert(message, status, timeout);
		});

		$(document).on("click", ".regaff", function(e) {
			$('.fundraiser-loading').removeClass('fundraiser-hide');
			var cid = $(this).data("cid");
			var data_nya = [cid];
		    var data = {
		        "action": "djafunction_regaff_fundraiser",
		        "datanya": data_nya
		    };

		    jQuery.post("<?php echo $home_url; ?>/wp-admin/admin-ajax.php", data, function(response) {

		    	var response_text = response.split("_");
                response_info = response_text[0];
                response_affcode = response_text[1];

                if(response_info=='loginfirst'){
			    	$('.fundraiser-loading').addClass('fundraiser-hide');

			    	var message = "Silahkan anda login terlebih dahulu.";
					var status = "warning";    /* There are 4 statuses: success, info, warning, danger  */
					var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
					createAlert(message, status, timeout);

					<?php if($login_setting=='1'){
					echo '
					setTimeout(function() {
			            var urlnya = "'.home_url().'/'.$page_login.'";
						window.location.replace(urlnya);
			        }, 1200)
					';
					}else{
					echo '
					setTimeout(function() {
			            var urlnya = "'.home_url().'/wp-login.php";
						window.location.replace(urlnya);
			        }, 1200)
					';
					} ?>

                }else{
                	var aff_url = "<?php echo $current_url; ?>"+'?ref='+response_affcode;
			    	$('.donation_button_fundraiser img').attr("src","<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/link2.png'; ?>");
			    	$('.donation_button_fundraiser').removeClass('regaff');
			    	$('.donation_button_fundraiser').addClass('copy_link_aff');
			    	$('.donation_button_fundraiser').attr('data-link', aff_url);
			    	$('.donation_button_fundraiser .text-fundraiser').text('Copy Link Aff');
			    	
			    	$('.fundraiser-loading').addClass('fundraiser-hide');

			    	copyToClipboard(aff_url);

			    	var message = "Link Aff Fundraiser berhasil didaftarkan dan di-copy. Silahkan sebarkan ke Social Media anda.";
					var status = "success";    /* There are 4 statuses: success, info, warning, danger  */
					var timeout = 3000;     /* 5000 here means the alert message disappears after 5 seconds. */
					createAlert(message, status, timeout);
                }

		    	
		    });
		});

		

		// get Copy
		function copyToClipboard(string) {
		let textarea;let result;try{textarea=document.createElement("textarea");textarea.setAttribute("readonly",!0);textarea.setAttribute("contenteditable",!0);textarea.style.position="fixed";textarea.value=string;document.body.appendChild(textarea);textarea.focus();textarea.select();const range=document.createRange();range.selectNodeContents(textarea);const sel=window.getSelection();sel.removeAllRanges();sel.addRange(range);textarea.setSelectionRange(0,textarea.value.length);result=document.execCommand("copy")}catch(err){console.error(err);result=null}finally{document.body.removeChild(textarea)}
	if(!result){const isMac=navigator.platform.toUpperCase().indexOf("MAC")>=0;const copyHotkey=isMac?"⌘C":"CTRL+C";result=prompt(`Press ${copyHotkey}`,string);if(!result){return!1}}
	return!0
		}

		function getNum(val) {
		   if (isNaN(val)) {
		     return 0;
		   }
		   return val;
		}

		$(function(){
		  $(document).on("click", ".donation_love", function(e) {
		    $(this).bind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function(){
		        $(this).removeClass('active');
		    })
		     $(this).addClass("active");
		  });
		});


		$(document).on("click", ".donation_love", function(e) {
			var id = $(this).attr('id');
			var campaign_id = $(this).attr('data-campaignid');
			var donate_id = $(this).attr('data-donateid');
			var count_love = $(this).find('.total_love').text();
			
			var thenum = parseInt(getNum(count_love.replace(/\D/g, "")));
			if(isNaN(thenum)){
				$(this).find('.total_love').html('<span>1 Aaminn</span>');
			}else{
				thenum = thenum+1;
				$(this).find('.total_love').html('<span>'+thenum+' Aaminn</span>');
			}

			$("#"+id+" img").attr("src","<?php echo plugin_dir_url( __FILE__ ).'assets/icons/praying-color3.png';?>");

			$(this).find('.plus1').addClass('show').animate({
				top: '-27px',
				opacity: '0',
			}, {
				duration : 400, 
				complete : function(){
    				set_hide(id);
    			}
    		});
			// console.log("log: "+id);
			var data_nya = [campaign_id, donate_id];
		    var data = {
		        "action": "djafunction_set_love",
		        "datanya": data_nya
		    };

		    jQuery.post("<?php echo $home_url; ?>/wp-admin/admin-ajax.php", data, function(response) {
		    	<?php if($max_love!='0'){?>

		    	if(response=='cukup'){
		    		alert('Maaf, hanya boleh <?php echo $max_love; ?> kali');
				}
				<?php } ?>


		    });
			
		});



		function set_hide(id){
			$('#'+id+' .plus1').removeClass('show').removeAttr('style');
		}

		// Load Fundariser
		$('.load_fundraiser').bind("click", function(e) {
			var id = $(this).attr('id');
			var campaign_id = $(this).attr('data-id');
			var load_count = $(this).attr('data-count');
			var anonim = $(this).attr('data-anonim');
			var fullanonim = $(this).attr('data-fullanonim');
			$('#'+id).text('Loadmore...');

			var data_nya = [id, campaign_id, load_count, anonim, fullanonim];
		    var data = {
		        "action": "djafunction_load_fundraiser",
		        "datanya": data_nya
		    };

		    jQuery.post("<?php echo $home_url; ?>/wp-admin/admin-ajax.php", data, function(response) {
		    	if(response==''){
					$('#box_btn_'+id+' .loadmore_info').html('No more data').slideDown();
			        setTimeout(function() {
			            $('#box_btn_'+id+' .loadmore_info').hide()
			        }, 5000)
				}
		        
		        load_count = parseFloat(load_count)+1;
		        $('#'+id).attr('data-count', load_count).text('Loadmore');;
				$('#box_'+id).append(response);

		    })

		});


		// Load Doa Donatur
		$('.load_doa_donatur').bind("click", function(e) {
			var id = $(this).attr('id');
			var campaign_id = $(this).attr('data-id');
			var load_count = $(this).attr('data-count');
			var anonim = $(this).attr('data-anonim');
			var fullanonim = $(this).attr('data-fullanonim');
			$('#'+id).text('Menampilkan...');

			var data_nya = [id, campaign_id, load_count, anonim, fullanonim];
		    var data = {
		        "action": "djafunction_load_doa_donatur",
		        "datanya": data_nya
		    };

		    jQuery.post("<?php echo $home_url; ?>/wp-admin/admin-ajax.php", data, function(response) {
		    	if(response==''){
					$('#box_btn_'+id+' .loadmore_info').html('No more data').slideDown();
			        setTimeout(function() {
			            $('#box_btn_'+id+' .loadmore_info').hide()
			        }, 5000)
				}
		        
		        load_count = parseFloat(load_count)+1;
		        $('#'+id).attr('data-count', load_count).text('Tampilkan Lagi');;
				$('#box_'+id).append(response);

		    })

		});

		// Load Data Donatur
		$('.load_data_donatur').bind("click", function(e) {
			var id = $(this).attr('id');
			var campaign_id = $(this).attr('data-id');
			var load_count = $(this).attr('data-count');
			var anonim = $(this).attr('data-anonim');
			var fullanonim = $(this).attr('data-fullanonim');
			$('#'+id).text('Menampilkan...');

			var data_nya = [id, campaign_id, load_count, anonim, fullanonim];
		    var data = {
		        "action": "djafunction_load_data_donatur",
		        "datanya": data_nya
		    };

		    jQuery.post("<?php echo $home_url; ?>/wp-admin/admin-ajax.php", data, function(response) {
		    	if(response==''){
					$('#box_btn_'+id+' .loadmore_info').html('No more data').slideDown();
			        setTimeout(function() {
			            $('#box_btn_'+id+' .loadmore_info').hide()
			        }, 5000)
				}
		        
		        load_count = parseFloat(load_count)+1;
		        $('#'+id).attr('data-count', load_count).text('Tampilkan Lagi');;
				$('#box_'+id).append(response);

		    })

		});

	</script>

	<?php if($gtm_id!=''){ ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm_id;?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php } ?>
</body>
</html>
