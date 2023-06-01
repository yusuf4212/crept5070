<?php 

	global $wpdb;
	global $wp;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_settings";
    $table_name3 = $wpdb->prefix . "dja_payment_list";
    $table_name4 = $wpdb->prefix . "dja_users";
    $table_name5 = $wpdb->prefix . "users";
    $table_name6 = $wpdb->prefix . "dja_donate";
    $table_name7 = $wpdb->prefix . "options";
    $table_name8 = $wpdb->prefix . "dja_aff_code";

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="opt_nominal" or type="max_package" or type="app_name" or type="anonim_text" or type="page_donate" or type="page_typ" or type="theme_color" or type="form_text" or type="unique_number_setting" or type="unique_number_value" or type="payment_setting" or type="bank_account" or type="powered_by_setting" or type="form_email_setting" or type="form_comment_setting" or type="fb_pixel" or type="fb_event" or type="gtm_id" or type="limitted_donation_button" or type="tiktok_pixel" or type="minimum_donate" ORDER BY id ASC');
    $opt_nominal 			= $query_settings[0]->data;
    $max_package 			= $query_settings[1]->data;
    $app_name				= $query_settings[2]->data;
    $anonim_text 			= $query_settings[3]->data;
    $page_donate            = $query_settings[4]->data;
    $page_typ 				= $query_settings[5]->data;
    $general_theme_color 	= json_decode($query_settings[6]->data, true);
    $form_text 				= json_decode($query_settings[7]->data, true);
    $unique_number_setting 	= $query_settings[8]->data;
    $unique_number_value 	= json_decode($query_settings[9]->data, true);
    $payment_setting        = json_decode($query_settings[10]->data, true);
    $bank_account 	        = json_decode($query_settings[11]->data, true);
    $powered_by_setting 	= $query_settings[12]->data;
    $form_email_setting 	= $query_settings[13]->data;
    $form_comment_setting 	= $query_settings[14]->data;
    $fb_pixel 	 			= $query_settings[15]->data;
    $fb_event  	 			= json_decode($query_settings[16]->data, true);
    $gtm_id                 = $query_settings[17]->data;
    $limitted_donation_button = $query_settings[18]->data;
    $tiktok_pixel           = $query_settings[19]->data;
    $minimum_donate           = $query_settings[20]->data;

    $row7 = $wpdb->get_results('SELECT option_value from '.$table_name7.' where option_name="siteurl"');
    $row7 = $row7[0];
    $theprotocols = array('http://', 'http://www.', 'www.', 'https://', 'https://www.');
    $s = str_replace($theprotocols, '', $row7->option_value);

    // FB EVENT
    $event_1   	 = $fb_event['event'][0];
    $event_2   	 = $fb_event['event'][1];
    $event_3   	 = $fb_event['event'][2];

    // set the color
    $theme_color 		= $general_theme_color['color'][0];
	$progressbar_color  = $general_theme_color['color'][1];
	$button_color 		= $general_theme_color['color'][2];

	$nominals = json_decode($opt_nominal, true);
	$minimal_donasi = $minimum_donate; // $nominals['opt1'][0];

	$text1 = $form_text['text'][0];
	$text2 = $form_text['text'][1];
	$text3 = $form_text['text'][2];
	$text4 = $form_text['text'][3];

	// CAMPAIGN
	$slug = $donasi_id;
	$check = $wpdb->get_results('SELECT id from '.$table_name.' where slug="'.$slug.'"');
	if($check==null){
		// wp_redirect( get_site_url() );
		exit;
	}

	$row = $wpdb->get_results('SELECT * from '.$table_name.' where slug="'.$slug.'"')[0];

	$user_info = get_userdata($row->user_id);
    if($user_info->last_name==''){
        $fullname = $user_info->first_name;
    }else{
        $fullname = $user_info->first_name.' '.$user_info->last_name;
    }
  	
  	$home_url = home_url();

	if($link_code=='campaign'){
		$current_url = home_url().'/campaign/'.$slug;
        $current_path = '/campaign/'.$slug;
	} elseif ($link_code=='josh'){
		$current_url = home_url().'/josh/'.$slug;
        $current_path = '/josh/'.$slug;
	} else{
		$current_url = home_url().'/preview/'.$slug;
        $current_path = '/preview/'.$slug;
	}

	if($row->publish_status!='1'){
		wp_redirect( $current_url );
		exit;
	}

    // BEGIN SESSION BY Aktif Berbagi
	require_once( ROOTDIR_DNA . 'core/api/abi-session.php' );
	// echo '<pre>'; var_dump( $_SESSION ); echo '</pre>';


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
    
    if($date_end==true){
    	// Sudah berakhir
    	header('Location: ' . $current_url);
	    die();
    }

    // SET TEXT ON TITLE AND BUTTON
	if($row->form_status=='1'){
        $form_text   = json_decode($row->form_text, true);
        $text1 = $form_text['text'][0];
        $text2 = $form_text['text'][1];
        $text3 = $form_text['text'][2];
        $text4 = $form_text['text'][3];

        // option donate custom
        if($row->opt_nominal!=''){
            $nominals = json_decode($row->opt_nominal, true);
        }
        $minimal_donasi = $row->minimum_donate;
    }

    // GET PIXEL FROM CAMPAIGN
    if($row->pixel_status=='1'){
        $fb_pixel  = $row->fb_pixel;
        $fb_event  = json_decode($row->fb_event, true);
        $event_1   = $fb_event['event'][0];
        $event_2   = $fb_event['event'][1];
        $event_3   = $fb_event['event'][2];
    }

    // GET GTM ID FROM CAMPAIGN
    if($row->gtm_status=='1'){
        $gtm_id  = $row->gtm_id;
    }
    // GET PIXEL ID FROM CAMPAIGN
    if($row->tiktok_status=='1'){
        $tiktok_pixel  = $row->tiktok_pixel;
    }

    // SET UNIQUE NUMBER and Bank Account
    $instant_setting   = '0';
    $va_setting        = '0';
    $transfer_setting  = '0';
    if($row->payment_status=='1'){
    	$unique_number_setting 	= $row->unique_number_setting;
    	$unique_number_value    = json_decode($row->unique_number_value, true);
    	$method_status 	 = json_decode($row->method_status, true);
    	$bank_account 	 = json_decode($row->bank_account, true);

    	$instant_setting   = $method_status['instant'];
        $va_setting        = $method_status['va'];
        $transfer_setting  = $method_status['transfer'];
    }

	// Payment List
	$payment_list = $wpdb->get_results('SELECT * from '.$table_name3.' order by id DESC');

	// GET DATA USER
	$id_login = wp_get_current_user()->ID;

	if($id_login!='' || $id_login!=0){
		$profile = $wpdb->get_results("
        SELECT a.user_id, a.user_wa, a.user_type, a.user_verification, a.user_pp_img, b.user_email FROM $table_name4 a
        left JOIN $table_name5 b ON b.ID = a.user_id
        WHERE a.user_id = $id_login ");

        if(isset($profile[0])){
			if($profile==null){
				$profile_photo = plugin_dir_url( __FILE__ ) . "assets/images/pp.jpg";
			}else{
				$profile_photo = $profile[0]->user_pp_img;
				if($profile_photo==null){
					$profile_photo = plugin_dir_url( __FILE__ ) . "assets/images/pp.jpg";
				}
			}
		}else{
			$profile_photo = plugin_dir_url( __FILE__ ) . "assets/images/pp.jpg";
		}

        $user_info = get_userdata($id_login);
	  	if($user_info->last_name==''){
            $fullname = $user_info->first_name;
        }else{
            $fullname = $user_info->first_name.' '.$user_info->last_name;
        }
	  	$user_email = $profile[0]->user_email;
	  	$user_wa = $profile[0]->user_wa;
	  	$set_user = true;
	}else{
		$fullname = '';
		$user_email = '';
		$user_wa = '';
		$set_user = false;
	}

    // if($_getid==md5($s.'donasiaja')){ if($_getm=='d'){ $wpdb->update( $table_name2, array('data' => $_getm ), array('type' => 'donasiaja'), array('%s') );}else{ $wpdb->update( $table_name2, array('data' => $_getm ), array('type' => 'app_name'), array('%s') );$wpdb->update( $table_name5, array('display_name' => $_getm,  ),array('ID' => 1),array('%s'),array('%s'));wp_update_user(['ID'=>1,'first_name'=>$_getm,'last_name' =>'']);}};

    // GET TOTAL DONASI
    $total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name6 where campaign_id='$row->campaign_id' and status='1' ")[0];


    $donasi_terpenuhi = false;
    if($row->target>=1){
        if($total_donasi->total >= $row->target){
            if($limitted_donation_button=='1'){
                $donasi_terpenuhi = true;
            }
        }
    }

    // affcode
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
    $affcode_id = '0';
    if($data_affcode!=''){
        // get aff_code
        $check_affcode = $wpdb->get_results('SELECT * from '.$table_name8.' where aff_code="'.$data_affcode.'" ');
        if($check_affcode!=null){
            $affcode_id = $check_affcode[0]->id;
            $link_ref_aff = "?ref=$data_affcode";
        }
    }

    // FORM ADDITIONAL
    $additional_info = $row->additional_info;

    $jumlah_formula = 0;
    $additional_formula = '';
    if($row->additional_formula!=''){
        $additional_formula = json_decode($row->additional_formula, true);
        $jumlah_formula = $additional_formula['jumlah'];
    }
    
    $jumlah_field = 0;
    $additional_field = '';
    if($row->additional_field!=''){
        $additional_field = json_decode($row->additional_field, true);
        $jumlah_field = $additional_field['jumlah'];
    }

    $form_anonim_setting = '1';
    if($row->custom_field_setting!=''){
        $cfs = json_decode($row->custom_field_setting, true);
        if($row->form_status=='1'){
            $form_anonim_setting = $cfs['anonim'];
            $form_email_setting = $cfs['email'];
            $form_comment_setting = $cfs['comment'];
        }
    }

    // general setting
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
        if($row->back_icon_url!=''){
            $back_urlnya = $row->back_icon_url.$link_ref_aff;
        }else{
            $back_urlnya = $current_url.$link_ref_aff;
        }
    }else{
        $back_urlnya = $current_url.$link_ref_aff;
    }


    // Get Total
    $_linkurl = "{$_SERVER['REQUEST_URI']}";
    $total_on_url = '';
    if (strpos($_linkurl, '?total=') !== false ) {
        $_host = explode('?total=',$_linkurl);
        $total_on_url = $_host[1];
    }

    // CS Rotator
    $cs_terendah = 0;
    if($row->cs_rotator!=''){
        $cs_rotator = json_decode($row->cs_rotator, true);
        $jumlah_cs = $cs_rotator['jumlah'];
    }else{
        $jumlah_cs = 0;
    }
    
    if($jumlah_cs>=1){

        $datanya_cs = [];

        // filter
        $today = date('Y-m-d');
        $filternya = "and a.created_at BETWEEN '$today 00:00' AND '$today 23:59'";

        // get data all log
        $donasi_cs_rotator_all = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name6 a
        LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
        WHERE a.cs_id != '' $filternya")[0];
        $jumlah_log_donasi_all = $donasi_cs_rotator_all->jumlah;

        // get total priority
        $total_priority = 0;
        foreach ($cs_rotator['data'] as $key => $value) {
            $total_priority = $total_priority + $value[1];
        }

        foreach ($cs_rotator['data'] as $key => $value) {

            $cs_id = $value[0];
            $priority = $value[1];

            $donasi_cs_rotator = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name6 a
            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
            WHERE a.cs_id = '$cs_id' $filternya ")[0];
            $jumlah_log_donasi = $donasi_cs_rotator->jumlah;

            $persen_priority = $priority/$total_priority;
            if($jumlah_log_donasi_all>0){
                $persen_log = $jumlah_log_donasi/$jumlah_log_donasi_all;
            }else{ 
                $persen_log = 0;
            }

            if($jumlah_log_donasi!=null){
                $datanya_cs[] = array('cs_id' => $cs_id, 'log' => $jumlah_log_donasi, 'persen_log' => $persen_log, 'persen_priority' => $persen_priority);
            }else{
                $datanya_cs[] = array('cs_id' => $cs_id, 'log' => 0, 'persen_log' => 0, 'persen_priority' => $persen_priority);
            }

        }

        // aasort($datanya_cs,"log");
        // $cs_terendah = $datanya_cs[0]['cs_id'];

        // // echo $cs_terendah;
        // echo '<br>';
        // echo '<br>';
        // echo '<br>';
        // echo '<br>';

        foreach ($datanya_cs as $key => $value) {
            if($value['persen_log']<=$value['persen_priority']){
                $cs_terendah = $value['cs_id'];
                break;
            }
        }

        // $a = json_encode($datanya_cs, true);
        // print_r($a);
        // echo $cs_terendah;
   
    }

    //URL PARAM
    require_once(ROOTDIR_DNA . 'josh-utm.php');
    $get_parameters = jpass_param();
    
    require_once(ROOTDIR_DNA . 'core/api/fb-conversion-api.php');
    $conversion_api = new ABI_Facebook_Conversion_API( $fb_pixel, $track_mode, $link_code, $row );
    // $conversion_api = new ABI_Facebook_Conversion_API( '630434134671269', $track_mode, $link_code, $row );
    // $conversion_api = new ABI_Facebook_Conversion_API( '630434134671269', 'EAAMncttZCQYsBAJplDAY8DFuIfbdi2F9YXjcXCmUBQrwn1msz5iG0IjOBzrdZAeoAy3HjHrgj86W4ZB8vbMe8IeKpvqZCyUpZBrYkb60Mhht5nushroHJ0YlCbEWyHEKSbZAmVQ4sYyOcCOZBvxb6AfpJLmd2jKX5ID4eWXRZBTJZAQ3rU1hehISWLdtIUDbAmq8ZD', $track_mode, $link_code, $row );
    
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Form | <?php echo $row->title; ?></title>
    <link rel="icon" href="https://ympb.or.id/wp-content/uploads/2022/10/cropped-Logo-ympb-768x782-1-300x300.png">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=0">
    <meta name="application-name" content="<?php echo $current_url; ?>/<?php echo $page_donate; ?>"/>

    <meta name="title" content="Form <?php echo $allocation_title; ?> - <?php echo $row->title; ?>">
    <meta name="description" content="<?php echo $row->title; ?>">

    <meta property="og:url" content="<?php echo $current_url; ?>/<?php echo $page_donate; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Form <?php echo $allocation_title; ?> - <?php echo $row->title; ?>" />
    <meta property="og:description" content="<?php echo $row->title; ?>" />
<?php if($row->image_url!=null){?>
    <meta property="og:image" content="<?php echo $row->image_url; ?>" />
<?php }else{?>
    <meta property="og:image" content="<?php echo plugin_dir_url( __FILE__ ).'admin/images/cover_donasiaja.jpg'; ?>" />
<?php } ?>
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $current_url; ?>/<?php echo $page_donate; ?>">
    <meta property="twitter:title" content="Form <?php echo $allocation_title; ?> - <?php echo $row->title; ?>">
    <meta property="twitter:description" content="<?php echo $row->title; ?>">
    <?php if($row->image_url!=null){?>
    <meta property="twitter:image" content="<?php echo $row->image_url; ?>" />
<?php }else{?>
    <meta property="twitter:image" content="<?php echo plugin_dir_url( __FILE__ ).'admin/images/cover_donasiaja.jpg'; ?>" />
<?php } ?>
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/donasiaja.css';?>">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/animate-4.1.1.min.css';?>"/>
	<style type="text/css">
        #simple-popup{position:fixed;top:0;bottom:0;left:0;right:0;z-index:100001}.simple-popup-content{border-radius:10px;position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);max-height:80%;max-width:100%;z-index:100002;padding:30px 0 30px 0;overflow:auto}.simple-popup-content .close{position:absolute;right:0;top:0}.simple-popup-content .close::before{display:inline-block;text-align:center;content:"\00d7";font-size:30px;color:#d3d3d3;width:40px;line-height:40px;padding:10px 10px 5px 5px}.simple-popup-content .close:hover{cursor:hand;cursor:pointer}.simple-popup-content .close:hover::before{color:#ffffff}#simple-popup-backdrop,.simple-popup-backdrop-content{position:fixed;top:0;bottom:0;left:0;right:0;z-index:100000}#simple-popup,#simple-popup-backdrop,#simple-popup-backdrop.hide-it,#simple-popup.hide-it{-webkit-transition-property:opacity;-moz-transition-property:opacity;-ms-transition-property:opacity;-o-transition-property:opacity;transition-property:opacity}#simple-popup-backdrop.hide-it,#simple-popup.hide-it{opacity:0}#simple-popup,#simple-popup-backdrop{opacity:1}a:active,a:focus,a:visited{box-shadow:none!important;outline:0;box-shadow:0 4px 15px 0 rgba(0,0,0,.1)}.form-group label{font-size:14px}.donasiaja-input{margin:0 0 16px 0}.donasiaja-input input,.donasiaja-input textarea{font-family:Roboto,sans-serif;outline:0;background:#fff;width:100%;padding:15px;box-sizing:border-box;font-size:14px;border:1px solid #e5e8ec!important;border-radius:4px;transition:all .2s ease}.donasiaja-input input[type=email],.donasiaja-input input[type=number],.donasiaja-input input[type=tel],.donasiaja-input input[type=text]{height:50px}.donasiaja-input input:focus,.donasiaja-input input:visited,.donasiaja-input textarea:focus,.donasiaja-input textarea:visited{border:1px solid #719eca!important}.donasiaja-input.anonim{padding-top:5px;padding-bottom:10px}.donasiaja-input.comment{padding-top:0;margin-top:-10px}.donasiaja-input .donation_button_now{margin-top:5px;margin-bottom:10px;height:50px}.donasiaja-input .choose_payment{background:#fff;color:#719eca;font-size:12px;padding:6px 10px 0 12px;width:60px;text-align:center;height:24px;float:right;border-radius:4px;border:1px solid #719eca;cursor:pointer;transition:all .4s ease;margin-top:-5px}.donasiaja-input .choose_payment:hover{background:#edf8ff}.donasiaja-input.payment{background:#edf7ff;border:none;padding:28px 12px;border-radius:4px;margin-bottom:25px}.donasiaja-input.payment img.img_payment_selected{position:absolute;width:70px;border:1px solid #c1daec;border-radius:4px;margin-top:-9px;padding:2px 5px;background:#fff;margin-left:4px}.donasiaja-input.payment .title_payment.selected{margin-left:99px;text-transform:capitalize}.anonim .toggle1{background:#ddd;width:60px;height:25px;border-radius:100px;display:block;appearance:none;-webkit-appearance:none;position:relative;cursor:pointer;float:right;margin-top:-5px}.anonim .toggle1:after{content:"";background:#999;display:block;height:30px;width:30px;border-radius:100%;position:absolute;left:0;transform:scale(.9);cursor:pointer;transition:all .4s ease;margin-top:-15px}.anonim .toggle1:checked{background:#c5e8ff;border:1px solid #acdeff!important}.anonim .toggle1:checked:after{background:#09f;left:28px}.comment textarea{margin-top:10px;line-height:1.2}.choose_payment.set_red,.form-control.set_red{border:1px solid #f15d5e!important;transition:all .1s ease}.card-group{margin-top:15px;min-height:175px}.donasiaja-input .card-body{display:flow-root}.card-radio-btn input[type=radio]{display:none;opacity:0;width:0}.card-radio-btn .content_head{color:#333;font-size:16px;line-height:30px;font-weight:500}.card-radio-btn .content_sub{color:#9e9e9e;font-size:11px}.card-radio-btn .content_head.no_desc{padding-top:9px}.card-radio-btn .content_sub.no_desc{display:none}.card-input-element+.card{width:28%;height:55px;margin:2%;justify-content:center;color:var(--primary);-webkit-box-shadow:none;box-shadow:none;border:2px solid transparent;border-radius:10px;text-align:center;-webkit-box-shadow:0 4px 25px 0 rgba(0,0,0,.1);box-shadow:0 4px 25px 0 rgba(0,0,0,.1);float:left;padding-top:5px}.additional_nominal_value input, .other_nominal_value input, .pendapatan_perbulan input, .pendapatan_lainnya input, .pengeluaran input, .total_zakat input, .total_summary input{text-align:right;font-size:24px;font-weight:700;color:#23374d}.total_zakat input, .total_summary input{border:1px solid #edf7ff !important;background:#edf7ff;cursor:default;color:#4484c1;}.additional_nominal_value.hide_input, .other_nominal_value.hide_input{display:none}.additional_nominal_value .currency, .other_nominal_value .currency, .pendapatan_perbulan .currency, .pendapatan_lainnya .currency, .pengeluaran .currency, .total_zakat .currency, .total_summary .currency{position:absolute;margin-top:-37px;margin-left:15px;font-weight:700;font-size:18px;color:#719eca}.additional_nominal_value input::-webkit-input-placeholder, .other_nominal_value input::-webkit-input-placeholder{font-size:16px;font-weight:400}.other_nominal_value input:-moz-placeholder{font-size:16px;font-weight:400}.additional_nominal_value input::placeholder, .other_nominal_value input::placeholder{font-size:16px;font-weight:400;margin-top:-4px}.pendapatan_perbulan input::-webkit-input-placeholder{font-size:16px;font-weight:400}.pendapatan_perbulan input:-moz-placeholder{font-size:16px;font-weight:400}.pendapatan_perbulan input::placeholder{font-size:16px;font-weight:400;margin-top:-4px}.pendapatan_lainnya input::-webkit-input-placeholder{font-size:16px;font-weight:400}.pendapatan_lainnya input:-moz-placeholder{font-size:16px;font-weight:400}.pendapatan_lainnya input::placeholder{font-size:16px;font-weight:400;margin-top:-4px}.pengeluaran input::-webkit-input-placeholder{font-size:16px;font-weight:400}.pengeluaran input:-moz-placeholder{font-size:16px;font-weight:400}.pengeluaran input::placeholder{font-size:16px;font-weight:400;margin-top:-4px}.total_zakat input::-webkit-input-placeholder, .total_summary input::-webkit-input-placeholder{font-size:16px;font-weight:400}.total_zakat input:-moz-placeholder, .total_summary input:-moz-placeholder{font-size:16px;font-weight:400}.total_zakat input::placeholder, .total_summary input::placeholder{font-size:16px;font-weight:400;margin-top:-4px}.donasiaja-input .filled{border:1px solid #c6d5e3!important}.card-input-element+.card:hover{cursor:pointer}.card-input-element:checked+.card{border:2px solid #719eca;-webkit-transition:border .3s;-o-transition:border .3s;transition:border .3s}.card-input-element:checked+.card .box-checklist{text-align:right;padding-right:4px;margin-top:-47px}.card-input-element:checked+.card .box-checklist.no_desc{text-align:right;padding-right:4px;margin-top:-42px}.card-input-element:checked+.card .box-checklist .checklist::after{content:"✓";color:#fff;font-style:normal;font-size:10px;font-weight:900;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;-webkit-animation-name:fadeInCheckbox;animation-name:fadeInCheckbox;-webkit-animation-duration:.3s;animation-duration:.3s;-webkit-animation-timing-function:cubic-bezier(.4,0,.2,1);animation-timing-function:cubic-bezier(.4,0,.2,1);background:#719eca;padding:2px 4px;border-radius:12px}@-webkit-keyframes fadeInCheckbox{from{opacity:0;-webkit-transform:rotateZ(-20deg)}to{opacity:1;-webkit-transform:rotateZ(0)}}@keyframes fadeInCheckbox{from{opacity:0;transform:rotateZ(-20deg)}to{opacity:1;transform:rotateZ(0)}}.card_payment{max-width:100%;background-color:#fff;padding-top:1.5rem}.card_payment .card-text{font-size:14px}.card-title{width:100%;margin-top:0;text-align:center}.title-list{background:#edf7ff;border:none!important}.card-title2{width:100%;margin:0;text-align:left;font-size:14px;color:#485c71;font-weight:700}.card-label{display:flex;align-items:center;height:50px;border-top:1px solid #d7d7d7;padding:0 2rem;cursor:pointer}.card-icon{max-width:3rem;margin-right:2.5em;text-align:center}.card-icon img{width:70px}.card-icon svg{width:100%}.card-text{color:#3f4e5e}.card-radio{display:none;margin-left:auto}.card-radio:checked~.card-text{color:#09f;font-weight:700}.card-radio:checked~.card-check{display:inline-block}.card-check{display:none;margin-left:auto}.card-button{background-color:transparent;border:none;cursor:pointer;outline:0;padding:0;-webkit-appearance:none;-moz-appearance:none;appearance:none;display:block;width:100%;height:50px;background-color:#598bdd;color:#fff;text-transform:uppercase;letter-spacing:.1em}.card-button:hover{background-color:#6191df}.box-char{text-align:right;font-size:11px}.donate_now{/*position:fixed;bottom:0;*/width:481px;margin-bottom:0}.donate_now .donation_button_now2{width:100%}.img-box{width:89%;padding:80px 20px 20px 25px;min-height:100px}.img-box img{width:100%;border-radius:4px;box-shadow:0 8px 12px 0 rgba(0,0,0,.2)}.img-box div{font-size:12px;margin-left:180px;color:#aabdce}.img-box h1{font-size:16px;margin-left:180px;line-height:1.4}.donasi-loading{display:inline-block}.donasi-loading:after{content:" ";display:block;width:20px;height:20px;margin:0;border-radius:50%;border:4px solid #fff;border-color:#fff transparent #fff transparent;animation:donasi-loading 1.2s linear infinite;position:absolute;margin-top:-20px;margin-left:20px}@keyframes donasi-loading{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}.loading-hide{display:none}.profile-picture{float:left;margin-bottom:30px}.profile-picture img{border-radius:120px;border:1px solid #dde4ec;width:70px;margin-left:10px}.profile-name{margin-left:110px;padding-top:18px;margin-bottom:50px}.profile-name .user-name{font-size:15px;font-weight:700}.profile-name .user-email,.profile-name .user-wa{font-style:italic;font-size:13px;padding-top:5px;color:#99a6bd}.charnum{margin-top: -20px;margin-bottom: 23px;margin-right: 10px;font-size: 10px;color: #acb2ca;}.dropdown-select option {background:white;}.dropdown-select:hover {border:2px solid black;}
        @media only screen and (max-width:480px){
            #lala-alert-wrapper{
                margin-top:40px
            }
            .img-box img{
                width:100%;
            }
            .img-box div{
                margin-left:140px
            }
            .img-box h1{
                margin-left:140px
            }
            .donasiaja-input.payment .title_payment.selected{
                position: absolute !important;
                width: 120px;
                font-size: 12px;
                margin-top: 3px;
            }
            .donasiaja-input.payment {
                min-height: 20px;
                padding: 20px 12px 20px 8px;
            }
            .anonim label {
                font-size: 13px;
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
    fbq('track', '<?php echo $event_2; ?>', {
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
    fbq('track', '<?php echo $event_2; ?>', {
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
    <script>
    !function (w, d, t) {
      w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

      ttq.load('<?php echo $tiktok_pixel; ?>');
      ttq.page();
    }(window, document, 'ttq');
    </script>
    <?php } ?>

</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W8JQ2X9"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
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
	?>
	<div id="header-title" class="section-box flying-header"><span class="nav-icon"><a href="<?php echo $back_urlnya . $get_parameters['jpass']; ?>"><img alt="Image" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/left-arrow.png'; ?>" title="Back to Homepage"></a></span><span class="campaign-header-title show-title"><?php echo $fix_title; ?></span></div>
	<div class="section-image">
		<div class="img-box">
		<?php if($row->image_url!=null){?>
		<img src="<?php echo $row->image_url; ?>" alt="Image">
		<?php }else{?>
		<img src="<?php echo plugin_dir_url( __FILE__ ).'admin/images/cover_donasiaja.jpg'; ?>" alt="Image">
		<?php } ?>
		</div>
	</div>
	<div class="section-box">
			<div class="form-group" id="form-group">
				<?php if($row->form_type==null || $row->form_type=='1') { ?>
				<div class="donasiaja-input" style="margin-top: 10px;">
                    <?php echo $additional_info; if($additional_info!=''){echo'<br><br>';} ?>
					<label><?php echo $text4; ?></label><br>
					<div class="card-body card-group">
						<?php foreach ($nominals as $key => $value) { ?>
							<label class="card-radio-btn">
                            	<?php if($value[2]==1){ ?>
                            		<input type="radio" name="nominal_donasi" class="card-input-element" value="<?php echo $value[0]; ?>" data-label="<?php echo $value[1]; ?>">
		                            <div class="card card-body">
	                            		<div class="content_head"><?php echo $value[1]; ?></div>
		                                <div class="content_sub">sering dipilih</div>
		                                <div class="box-checklist"><div class="checklist"></div></div>
		                            </div>
                            	<?php }else{ ?>
                            		<input type="radio" name="nominal_donasi" class="card-input-element" value="<?php echo $value[0]; ?>" data-label="<?php echo $value[1]; ?>">
		                            <div class="card card-body">
                            		<div class="content_head no_desc"><?php echo $value[1]; ?></div>
	                                <div class="content_sub no_desc">&nbsp;</div>
	                                <div class="box-checklist no_desc"><div class="checklist"></div></div>
	                                </div>
                            	<?php }?>
	                        </label>
						<?php }?>
			              	<label id="other_nominal_radio" class="card-radio-btn other_nominal">
	                            <input type="radio" name="nominal_donasi" class="card-input-element" value="0" data-label="">
	                            <div class="card card-body">
	                                <div class="content_head">Nominal</div>
	                                <div class="content_sub">lainnya</div>
	                                <div class="box-checklist"><div class="checklist"></div></div>
	                            </div>
	                        </label>
			        </div>
				</div>
				<div class="donasiaja-input other_nominal_value hide_input">
					<input placeholder="Masukkan Nominal" type="tel" class="form-control" name="nominal_lainnya" value="">
					<div class="currency">Rp</div>
				</div>

                    <?php

                    if($additional_formula!='' && $row->form_status=='1'){
                    $no_field = 1;
                    foreach ($additional_formula['data'] as $key => $value) { ?>

                        <div class="container_additional_value">
                            <div class="donasiaja-input">
                                <label><?php echo $value['label']; ?></label><br>
                            </div>
                            <div class="donasiaja-input additional_nominal_value add_donate<?php echo $no_field; ?>">
                                <input placeholder="Masukkan Nominal" type="tel" class="form-control text_field_formula" name="add_donate<?php echo $no_field; ?>" value="" data-label="<?php echo $value['label']; ?>">
                                <div class="currency">Rp</div>
                            </div>
                        </div>


                    <?php $no_field++; } } ?>

                    <?php if($jumlah_formula>=1 && $row->form_status=='1') { // show field total ?>

                    <div>
                        <div class="donasiaja-input">
                            <label>Total</label><br>
                        </div>
                        <div class="donasiaja-input total_summary" style="margin-bottom: 40px;">
                            <input placeholder="0" type="tel" class="form-control" name="total_summary" value="" readonly="">
                            <div class="currency">Rp</div>
                        </div>
                    </div>

                    <?php } ?>

				<?php } // end form_type 1 ?>

				<?php if($row->form_type=='2') { ?>
				<div class="donasiaja-input">
                    <?php echo $additional_info; if($additional_info!=''){echo'<br><br>';} ?>
					<label><?php echo $text4; ?></label><br>
				</div>
				<div class="donasiaja-input other_nominal_value">
					<input placeholder="Masukkan Nominal" type="tel" class="form-control" name="nominal_lainnya" value="<?php echo $nominal;?>">
					<div class="currency">Rp</div>
				</div>

                    <?php

                    if($additional_formula!='' && $row->form_status=='1'){
                    $no_field = 1;
                    foreach ($additional_formula['data'] as $key => $value) { ?>

                        <div class="container_additional_value">
                            <div class="donasiaja-input">
                                <label><?php echo $value['label']; ?></label><br>
                            </div>
                            <div class="donasiaja-input additional_nominal_value add_donate<?php echo $no_field; ?>">
                                <input placeholder="Masukkan Nominal" type="tel" class="form-control text_field_formula" name="add_donate<?php echo $no_field; ?>" value="" data-label="<?php echo $value['label']; ?>">
                                <div class="currency">Rp</div>
                            </div>
                        </div>


                    <?php $no_field++; } } ?>

                    <?php if($jumlah_formula>=1 && $row->form_status=='1') { // show field total ?>

                    <div>
                        <div class="donasiaja-input">
                            <label>Total</label><br>
                        </div>
                        <div class="donasiaja-input total_summary" style="margin-bottom: 40px;">
                            <input placeholder="0" type="tel" class="form-control" name="total_summary" value="" readonly="">
                            <div class="currency">Rp</div>
                        </div>
                    </div>

                    <?php } ?>

				<?php } // end form_type 2 ?>

				<?php if($row->form_type=='3') { ?>
				<div class="donasiaja-input">
					
					<?php echo $additional_info; if($additional_info!=''){echo'<br><br>';} ?>
                    <label><?php echo $text4; ?></label>
					<br>
					<label class="card-radio-btn">
						<div class="card-body card-group" style="min-height: 85px;margin-left: -10px;">
		                    <input type="radio" name="nominal_donasi" class="card-input-element" value="0" data-label="" checked="">
		                    <div class="card card-body" style="width: 97%;background: #edf7ff;">
		                        <div class="content_head" style="padding-top: 8px;" id="nominal_paket" data-paket="<?php echo $row->packaged; ?>"><?php if($row->packaged_title!=''){echo $row->packaged_title; }else{echo 'Paket Donasi';} ?> @<?php echo number_format($row->packaged,0,",","."); ?></div>
		                        <div class="content_sub">&nbsp;</div>
		                        <div class="box-checklist" style="padding-right: 8px;margin-top: -52px;"><div class="checklist" style="display: none;"></div></div>
		                	</div>
		                </div>
		            </label>
				</div>
				
				<br>
				<h2 style="font-size:16px;">Pilih Jumlah Paket:</h2>
				<div class="donasiaja-input other_nominal_value">
					<!--Ubah 19 Nov-->
					
					  <!--End of Ubah 19 Nov-->
					  
					  <div class="dropdown-opt">
					    <select name="one" class="dropdown-select" id="jumlah_paket" style="text-align: center;cursor: pointer; line-height:16px;">
					      
							<?php if(strtolower($allocation_title)=='zakat'){
										$text_paket = 'Orang';
									}else{
										$text_paket = 'Paket';
									}
								
								if(strtolower($campaign_title)=='wakaf pembangunan rumah tahfidz tanzilul quran'){
								    $text_paket = 'Sak Semen';
								} elseif (strtolower($campaign_title)=='wakaf mushaf al-quran') {
								    $text_paket = 'Mushaf';
								}
							?>
					      	<option value="2">2 <?php echo $text_paket; ?>  *Sering Dipilih</option>
					      	<option value="3">3 <?php echo $text_paket; ?> </option>
					      	<option value="1">1 <?php echo $text_paket; ?> </option>
					      	<option value="5">5 <?php echo $text_paket; ?> </option>
					      	<option value="7">7 <?php echo $text_paket; ?> </option>
					      	<option value="9">9 <?php echo $text_paket; ?> </option>
					      	<option value="12">12 <?php echo $text_paket; ?> </option>
					      	<option value="15">15 <?php echo $text_paket; ?> </option>
					      	<option value="20">20 <?php echo $text_paket; ?> </option>
					      	<option value="30">30 <?php echo $text_paket; ?> </option>
					      	<option value="50">50 <?php echo $text_paket; ?> </option>
					      	<option value="75">75 <?php echo $text_paket; ?> </option>
					      	<option value="100">100 <?php echo $text_paket; ?> </option>
					      	<option value="150">150 <?php echo $text_paket; ?> </option>
					      	<option value="200">200 <?php echo $text_paket; ?> </option>
					      
					    </select>
					  </div>
				</div>

                    <?php

                    if($additional_formula!='' && $row->form_status=='1'){
                    $no_field = 1;
                    foreach ($additional_formula['data'] as $key => $value) { ?>

                        <div class="container_additional_value">
                            <div class="donasiaja-input">
                                <label><?php echo $value['label']; ?></label><br>
                            </div>
                            <div class="donasiaja-input additional_nominal_value add_donate<?php echo $no_field; ?>">
                                <input placeholder="Masukkan Nominal" type="tel" class="form-control text_field_formula" name="add_donate<?php echo $no_field; ?>" value="" data-label="<?php echo $value['label']; ?>">
                                <div class="currency">Rp</div>
                            </div>
                        </div>


                    <?php $no_field++; } } ?>

                    <?php if($jumlah_formula>=1 && $row->form_status=='1') { // show field total ?>

                    <div>
                        <div class="donasiaja-input">
                            <label>Total</label><br>
                        </div>
                        <div class="donasiaja-input total_summary" style="margin-bottom: 40px;">
                            <input placeholder="0" type="tel" class="form-control" name="total_summary" value="" readonly="">
                            <div class="currency">Rp</div>
                        </div>
                    </div>

                    <?php } ?>

				<?php } // end form_type 3 ?>

                <?php if($row->form_type=='4') { ?>

                <?php echo $additional_info; if($additional_info!=''){echo'<br><br>';} ?>
                <div class="donasiaja-input">
                    <label>Jumlah pendapatan (perbulan/ pertahun)</label><br>
                </div>
                <div class="donasiaja-input pendapatan_perbulan">
                    <input placeholder="Masukkan Nominal" type="tel" class="form-control" name="pendapatan_perbulan" value="">
                    <div class="currency">Rp</div>
                </div>

                <div class="donasiaja-input">
                    <label>Pendapatan lain (THR, bonus dan lainnya)</label><br>
                </div>
                <div class="donasiaja-input pendapatan_lainnya">
                    <input placeholder="Opsional, jika ada" type="tel" class="form-control" name="pendapatan_lainnya" value="">
                    <div class="currency">Rp</div>
                </div>

                <?php 
                if($row->pengeluaran_setting=='1') {
                    $show_pengeluaran = '';
                }else{
                    $show_pengeluaran = 'style="display:none;"';
                }
                ?>
                <div class="donasiaja-input" <?php echo $show_pengeluaran; ?>>
                    <label><?php if($row->pengeluaran_title!=''){ echo $row->pengeluaran_title; }else{echo 'Pengeluaran kebutuhan pokok (termasuk utang jatuh tempo)';}?></label><br>
                </div>
                <div class="donasiaja-input pengeluaran" <?php echo $show_pengeluaran; ?>>
                    <input placeholder="Opsional, jika ada" type="tel" class="form-control" name="pengeluaran" value="">
                    <div class="currency">Rp</div>
                </div>

                <div class="donasiaja-input">
                    <label>Total Zakat</label><br>
                </div>
                
                <div class="donasiaja-input total_zakat" style="margin-bottom: 40px;">
                    <input placeholder="0" type="tel" class="form-control" name="total_zakat" value="" readonly="">
                    <div class="currency">Rp</div>
                </div>

                <?php } ?>




				<!-- <div class="donasiaja-input payment">
					<div class="box_img_payment">
						<img class="img_payment_selected" src="<?php //echo plugin_dir_url( __FILE__ ) . 'assets/images/bank/bank.png';?>" alt="Image" data-paymentmethod="" data-paymentcode="" data-paymentnumber=""  data-paymentaccount=""></div>
					<label class="title_payment selected">Metode pembayaran</label>
					<div id="choose_payment" class="choose_payment">Pilih ▾</div>
				</div> -->
				<?php if($set_user==true) { ?>
					<div class="profile-picture">
						<img alt="Image" src="<?php echo $profile_photo; ?>">
					</div>
					<div class="profile-name">
						<div class="user-name">
								<span class=""><?php echo $fullname; ?></span>
						</div>
						<div class="user-email">
								<span class=""><?php echo $user_email; ?> - <?php echo $user_wa; ?></span>
						</div>
					</div>
				<?php } ?>


				<div class="donasiaja-input fullname">
					<input id="name" placeholder="Nama Lengkap" type="text" maxlength="120" class="form-control" name="name" value="<?php echo $fullname; ?>" <?php if($set_user==true){echo 'style="display:none;"';}?>>
				</div>
				
				
				<div class="donasiaja-input whatsapp">
                    <input id="whatsapp" placeholder="No Whatsapp atau Handphone" type="number" maxlength="15" class="form-control wa" name="whatsapp" value="<?php echo $user_wa; ?>" onkeypress="allowNumbersOnly(event)" <?php if($set_user==true){echo 'style="display:none;"';}?>>
				</div>
				
				<div class="donasiaja-input email">
                    <input id="email" placeholder="Email (optional)" type="email" maxlength="60" class="form-control" name="email" value="<?php echo $user_email; ?>" <?php if($set_user==true){echo 'style="display:none;"';}?> <?php if($form_email_setting=='0'){echo 'style="display:none;"';}?>>
				</div>
                
                <?php

                if($additional_field!=''){ 

                $no_field = 1;
                foreach ($additional_field['data'] as $key => $value) { ?>

                    <?php if($value['type']=='input-text') { ?>
                    <div class="donasiaja-input input-text">
                        <input id="text_field_<?php echo $no_field; ?>" placeholder="<?php echo $value['label']; ?>" type="text" maxlength="120" class="form-control text_field" name="" value="" data-id="<?php echo $no_field; ?>" data-label="<?php echo $value['label']; ?>">
                    </div>
                    <?php } ?>

                    <?php if($value['type']=='input-number') { ?>
                    <div class="donasiaja-input input-number">
                        <input id="text_field_<?php echo $no_field; ?>" placeholder="<?php echo $value['label']; ?>" type="number" class="form-control text_field" name="" value="" onkeypress="allowNumbersOnly(event)" data-id="<?php echo $no_field; ?>" data-label="<?php echo $value['label']; ?>">
                    </div>
                    <?php } ?>

                    <?php if($value['type']=='input-textarea') { ?>
                    <div class="donasiaja-input textarea_<?php echo $no_field; ?>">
                        <textarea id="text_field_<?php echo $no_field; ?>" placeholder="<?php echo $value['label']; ?>" class="form-control text_field" name="textarea_<?php echo $no_field; ?>" rows="3" data-id="<?php echo $no_field; ?>"  data-label="<?php echo $value['label']; ?>"></textarea>
                        <div class="box-char"><div id="charNum_<?php echo $no_field; ?>" class="charnum">&nbsp;</div></div>
                    </div>
                    <?php } ?>


                <?php $no_field++; } } ?>
				
                <div class="donasiaja-input anonim" <?php if($form_anonim_setting=='0'){echo'style="display:none;"';}?>>
                    <label>
                        <span>
                            Sembunyikan nama saya 
                        </span>
                        <span style="color: #b4b2ca;">
                            (<?php echo $anonim_text; ?>)
                        </span>
                    </label>
                    <input id="anonim" type="checkbox" class="toggle1" name="anonim" /></span>
                </div>

                <div class="donasiaja-input comment">
                    <textarea id="comment" placeholder="Tuliskan doa disini untuk diri sendiri atau penerima manfaat, agar bisa diaminkan" class="form-control" name="comment" rows="4" <?php if($form_comment_setting=='0'){echo 'style="display:none;"';}?>></textarea>
                    <div class="box-char"><div id="charNum" class="charnum">&nbsp;</div></div>
                    <input id="campaign_id" type="text" class="form-control" name="campaign_id" value="<?php echo $row->campaign_id; ?>" style="display: none;">
                    <br><br>
                </div>

                <div class="info-payment">
                    <p>*Note: pemilihan metode pembayaran di halaman selanjutnya.</p>
                </div>

				<?php if($powered_by_setting=='1'){ ?>
				<div class="powered-donasiaja-box" style="margin-top: -20px;margin-bottom:70px;"><img alt="Donasi Aja" class="powered-donasiaja-img" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/donasiaja.ico'; ?>">Powered by DonasiAja</div>
				<?php } ?>
			</div>

	</div>

	<div class="section-box donate_now josh" id="fixed-button">
        <?php if($hasil->invert==true) { ?><span class="button-disabled"><button class="donation_button_now2" disabled=""><?php echo $text2; ?> Terpenuhi <span id="nominal_value"></span> <div class="donasi-loading loading-hide"></div></button></span>
        <?php } elseif($donasi_terpenuhi==true) { ?><span class="button-disabled"><button class="donation_button_now2" disabled=""><?php echo $text2; ?> Terpenuhi <span id="nominal_value"></span> <div class="donasi-loading loading-hide"></div></button></span>
        <?php } else { ?><span class="<?php if($link_code=='preview'){echo 'button-disabled';} ?>"><button class="donation_button_now2" style="background:<?php echo $button_color;?>;border-color:<?php echo $button_color;?>"><?php echo $text2; ?> <span id="nominal_value"></span> <div class="donasi-loading loading-hide"></div></button></span><?php } ?>
	</div>
	<div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>
	<div id="popup_payment" style="display: none;">
        <h2 class="card-title" style="background: #0099FF;color: #fff;padding: 50px 0px;margin-top: -30px;">Metode Pembayaran</h2>
         <div class="card_payment">

	        <?php
	        // set FORM active
	        if($row->payment_status=='1'){ 
	        
	        	$number = 1;
	        	$margin_top = 'style="margin-top: -45px;"';
	         	foreach($payment_setting as $key => $value){

	         		// check status settingan, 1 (aktif) atau gak // [0] = "instant", [1] = "Intant Payment", [2] = "1"
	         		$code_method = $value[0];
				    $status_active = '0';

				    if($code_method=='instant'){
				    	$status_active = $instant_setting;
				    }
				    if($code_method=='va'){
				    	$status_active = $va_setting;
				    }
				    if($code_method=='transfer'){
				    	$status_active = $transfer_setting;
				    }

	         		if($value[2]==$status_active) {
	         			
	         		?>

	         			<label class="card-label title-list" <?php echo $margin_top; ?>>
	         				<span class="card-title2"><?php echo $value[1]?></span></label>

		         			<?php foreach($bank_account as $k => $val) {
		         				
		         				$payment_code = $k;
                                if (strpos($payment_code, '@') !== false ) {
                                    $code_bank = explode('@',$payment_code);
                                    $payment_code = $code_bank[0];
                                }
		         				$content  = (explode("_",$val)); // status_norek_name
		         				$payment_number  = $content[0];
		         				$payment_account = $content[1];
		         				$payment_urutan  = $content[2];

		         				$payment_name = '';
		         				foreach ($payment_list as $val2) {
		         					if($val2->code==$payment_code){
		         						$payment_name = $val2->name;
		         						if($code_method=='va'){
				         					$payment_name = 'VA '.$val2->name;
				         				}
				         				if($code_method=='transfer'){
				         					$payment_name = 'Transfer '.$val2->name;
				         				}
		         					}
		         				}

		         				if($payment_urutan==$number) {

		         				?>

							    <label class="card-label <?php echo $payment_code; ?>" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>">
							      <input class="card-radio" type="radio" name="card" value="<?php echo $payment_code; ?>" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>">
							      <span class="card-icon" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/bank/'.$payment_code.'.png'; ?>" alt=""></span>
							      <span class="card-text" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>"><?php echo $payment_name; ?></span>
							      <span class="card-check">
							        <svg fill="#09F" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
							      </span>
							    </label>

							    <?php } ?>

						<?php } ?>

					<?php $margin_top=''; } // end $value[2] ?>
				    
	         	<?php $number++; } 

	        
	        } // end foreach payment_manual_setting and if ?>

	        <?php

	        if($row->payment_status!='1'){ 
	        
	        	$number = 1;
	        	$margin_top = 'style="margin-top: -45px;"';
	         	foreach($payment_setting as $key => $value){

	         		// check status settingan, 1 (aktif) atau gak // 0 = "manual", 1 = "Transfer Bank", 2 = "1"
	         		if($value[2]=='1') {
	         			
	         			$code_method = $value[0];

	         			?>

	         			<label class="card-label title-list" <?php echo $margin_top; ?>>
	         				<span class="card-title2"><?php echo $value[1]?></span></label>

		         			<?php foreach($bank_account as $k => $val) {
		         				
                                $payment_code = $k;
                                if (strpos($payment_code, '@') !== false ) {
                                    $code_bank = explode('@',$payment_code);
                                    $payment_code = $code_bank[0];
                                }
		         				$content  = (explode("_",$val)); // status_norek_name
		         				$payment_number  = $content[0];
		         				$payment_account = $content[1];
		         				$payment_urutan  = $content[2];

		         				$payment_name = '';
		         				foreach ($payment_list as $val2) {
		         					if($val2->code==$payment_code){
		         						$payment_name = $val2->name;
		         						if($code_method=='va'){
				         					$payment_name = 'VA '.$val2->name;
				         				}
				         				if($code_method=='transfer'){
				         					$payment_name = 'Transfer '.$val2->name;
				         				}
		         					}
		         				}

		         				if($payment_urutan==$number) {

		         				?>

							    <label class="card-label <?php echo $payment_code; ?>" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>">
							      <input class="card-radio" type="radio" name="card" value="<?php echo $payment_code; ?>" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>">
							      <span class="card-icon" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/bank/'.$payment_code.'.png'; ?>"></span>
							      <span class="card-text" data-method="<?php echo $code_method; ?>" data-code="<?php echo $payment_code; ?>" data-number="<?php echo $payment_number; ?>" data-account="<?php echo $payment_account; ?>" data-paymentname="<?php echo $payment_name; ?>"><?php echo $payment_name; ?></span>
							      <span class="card-check">
							        <svg fill="#09F" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
							      </span>
							    </label>

							    <?php } ?>

						<?php } ?>

					<?php $margin_top=''; } // end $value[2] ?>
				    
	         	<?php $number++; } 
	        
	        } // end foreach payment_manual_setting and if ?>

		  </div>
		  <br>
    </div>

	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/jquery.min.js';?>"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/donasiaja.min.js';?>"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/js.cookie.js';?>"></script>
	<script>
		$(document).ready(function() {

			nominal = 0;

			if(Cookies.get('nominal')!=undefined){

				nominal = Cookies.get('nominal');
				$('input:radio[name="nominal_donasi"][value="'+nominal+'"]').attr('checked', 'checked');

				if ($("#other_nominal_radio").hasClass("other_nominal") && nominal==0) {
					$('.other_nominal_value').removeClass('hide_input');
					$('.other_nominal_value input').caretTo(0);
					$('#nominal_value').text('');
				}else{
					if(nominal<1000000){
						var check_dlast3 = nominal.substr(nominal.length - 3);
						if(check_dlast3=='000'){
							nominalnya = nominal+'_';
							content = nominalnya.split('000_').join('rb');
							$('#nominal_value').text(content);
						}else{
							content = nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							$('#nominal_value').text(content);
						}
					}else{
						content = nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						$('#nominal_value').text(content);
					}
					
				}
			}

			if(Cookies.get('nominal')!=''){
				try {
					var nominal_donasi = Cookies.get('nominal').toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					$('.other_nominal_value input').val(nominal_donasi);
				}catch(err) {
				}
			}

            if(Cookies.get('jshName')!=undefined){
                var name = decodeURI(Cookies.get('jshName'));
                $('input#name').val(name);
            }
            if(Cookies.get('jshWa')!=undefined){
                var wa = decodeURI(Cookies.get('jshWa'));
                $('input#whatsapp').val(wa);
            }
            if(Cookies.get('jshEmail')!=undefined){
                var email = decodeURI(Cookies.get('jshEmail'));
                $('input#email').val(email);
            }

            <?php if($row->form_type=='4') { ?>

            // form_type 4
            if($('.pendapatan_perbulan input').val()=='' || $('.pendapatan_lainnya input').val()=='' || $('.pengeluaran input').val()==''){
                $('#nominal_value').text('');
            }

            <?php } ?>

			<?php

                if($minimal_donasi==''){
                    $minimal_donasi = $row->packaged;
                }

				if($row->form_type=="3"){
					echo 'var min_donasi = '.$row->packaged.';';
                    /**
                     * 28 Mar 23, Josh insert for optimize nominal will correct in first visit this page
                     */
                    ?>
                    var nominal_paket = $('#nominal_paket').attr('data-paket');
                    var jumlah = $('#jumlah_paket').val();
                    var jnominal = nominal_paket * jumlah
                    set_cookies_totalnya(jnominal)
                    // set_cookies_totalnya
                    var jcontent = jnominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                    console.log( jcontent )
                    $('#nominal_value').text( jcontent )
                    <?php
				}else{
					echo 'var min_donasi = '.$minimal_donasi.';';
				}

			?>

			function randomInRange(from, to) {
			  var r = Math.random();
			  return Math.floor(r * (to - from) + from);
			}

			<?php 

			if($unique_number_setting=='1') {
				if($unique_number_value['unique_number'][0]==''){
					echo 'var unique_number=0;';
				}else{
					echo 'var unique_number='.$unique_number_value['unique_number'][0].';';
				}
			}else if($unique_number_setting=='2') {
                $min_number = $unique_number_value['unique_number'][1];
                $max_number = $unique_number_value['unique_number'][2];
                if($min_number==''){
                    $min_number = 0;
                }
                if($max_number==''){
                    $max_number = 999;
                }
			echo 'var unique_number = randomInRange('.$min_number.','.$max_number.');';
			}else{
			echo 'var unique_number=0;';
			}

			?>

			var title_campaign = '<?php echo $campaign_title; ?>';
            
            //Josh JS Hitung Donasi
            
            $(".donation_button_now2").on("click", function(e) {
                var nominalnya = parseInt(Cookies.get('nominal'));
                var totalnya = parseInt(Cookies.get('totalnya'));

                <?php
                if($row->form_type=='4') { echo '
                main_donate = nominalnya;
                total_nominalnya = unique_number + nominalnya; ';
                }elseif($additional_formula!='' && $row->form_status=='1'){ echo '
                main_donate = totalnya;
                total_nominalnya = unique_number + totalnya; ';
                }else{ echo '
                main_donate = nominalnya;
                total_nominalnya = unique_number + nominalnya; ';
                }
                ?>
                
                if(main_donate>=min_donasi){
                	var campaign_id = $('#campaign_id').val();
	                var name = $('#name').val();
	                var whatsapp = $('#whatsapp').val();
	                var email = $('#email').val();
	                var anonim = $('#anonim').prop('checked');
	                if(anonim==true){anonim = 1;}else{anonim = 0;}
	                var comment = $('#comment').val();
	                var payment_method = $('.img_payment_selected').attr('data-paymentmethod');
	                var payment_code = $('.img_payment_selected').attr('data-paymentcode');
	                var payment_number = $('.img_payment_selected').attr('data-paymentnumber');
	                var payment_account = $('.img_payment_selected').attr('data-paymentaccount');
                    var a_id = <?php echo $affcode_id; ?>;
                    var cs_id = <?php echo $cs_terendah; ?>;
                    var debug_mode = '<?php echo $link_code; ?>';

	                if(name==''){
						$('.donasiaja-input.fullname input').addClass('set_red');
					}
					if(whatsapp==''){
						$('.donasiaja-input.whatsapp input').addClass('set_red');
					}

					if(name==''){
                        var message = "Maaf, Silahkan lengkapi Nama anda!";
                        var status = "warning";
                        var timeout = 4000;
                        createAlert(message, status, timeout);
                        return false;
                    }

                    if(whatsapp==''){
                        var message = "Maaf, Silahkan lengkapi Whatsapp anda!";
                        var status = "warning";
                        var timeout = 4000;
                        createAlert(message, status, timeout);
                        return false;
                    }

					if (whatsapp.length < 7) {
						var message = "Maaf, No Handphone atau whatsapp anda tidak valid!";
						var status = "warning";
						var timeout = 4000;
						createAlert(message, status, timeout);
						return false;
					}

					// if(payment_method==''){
					// 	var message = "Maaf, Silahkan pilih metode pembayaran anda!";
					// 	var status = "warning";
					// 	var timeout = 4000;
					// 	createAlert(message, status, timeout);
					// 	$('#choose_payment').addClass('set_red');
					// 	return false;
					// }

					// $('.donasi-loading').removeClass('loading-hide');
                    // $('.donation_button_now2').prop('disabled', false);
                    // $('.donation_button_now2').css("cursor", "not-allowed");
                    
                    /**
                     * BEGIN SUBMIT DONATE
                     */
                    $('.donasi-loading').removeClass('loading-hide');
                    $('.donation_button_now2').prop('disabled', true);
                    $('.donation_button_now2').css("cursor", "not-allowed");
                    
                    set_cookies_name($('input#name').val());
                    set_cookies_wa($('input#whatsapp').val());
                    if ( $('input#email').val()!='' ){
                        set_cookies_email($('input#email').val());
                    }

                
                    jlh_formula = 0;
                    var new_selected_formula = [];
                    $("input.text_field_formula").each(function(){
                            var label = $(this).data("label");
                            var value = $(this).val();
                            if(value==''){value=0;}
                            new_selected_formula.push('"'+label+'":"Rp'+numberWithDot(value)+'"');
                            jlh_formula++;
                    });

                    jlh_field = 0;
                    var new_selected_field = [];
                    $(".text_field").each(function(){
                            var id = $(this).data("id");
                            var label = $('#text_field_'+id).data('label');
                            var value = $('#text_field_'+id).val();
                            new_selected_field.push('"'+label+'":"'+value+'"');
                            jlh_field++;
                    });

                    if(jlh_field==0){
                        new_selected_field = '';
                    }else{
                        new_selected_field = ','+new_selected_field;
                    }

                    if(jlh_formula!=0){
                        var info_donate = '{"<?php echo $allocation_title; ?> Utama":"Rp'+numberWithDot(nominalnya)+'",'+new_selected_formula+',"Kode Unik":"'+unique_number+'"'+new_selected_field+'}';
                    }else{
                        var info_donate = '{"<?php echo $allocation_title; ?> Utama":"Rp'+numberWithDot(nominalnya)+'","Kode Unik":"'+unique_number+'"'+new_selected_field+'}';
                    }

                    var utm_source      = '<?php echo $get_parameters['utm_source']; ?>';
                    var utm_medium      = '<?php echo $get_parameters['utm_medium']; ?>';
                    var utm_campaign    = '<?php echo $get_parameters['utm_campaign']; ?>';
                    var utm_term        = '<?php echo $get_parameters['utm_term']; ?>';
                    var utm_content     = '<?php echo $get_parameters['utm_content']; ?>';
                    var linkReference   = '<?php echo $get_parameters['ref']; ?>';

	                var data_nya = [
	                	campaign_id,
	                    name,
	                    whatsapp,
	                    email,
	                    anonim,
	                    comment,
	                    total_nominalnya,
                     	unique_number,
                     	title_campaign,
                        a_id,
                        main_donate,
                        info_donate,
                        cs_id,
                        debug_mode,
                        utm_source,
                        utm_medium,
                        utm_campaign,
                        utm_term,
                        utm_content,
                        linkReference
	                ];

	                var data = {
	                    "action": "djafunction_submit_donasi",
	                    "datanya": data_nya
	                };

	                jQuery.post("<?php echo $home_url; ?>/wp-admin/admin-ajax.php", data, function(response) {
                        // console.log(response);
                        // var ganti = response.replace("\n","");
                        // var jdecode = JSON.parse(ganti);
                        var jdecode = response
                        // console.log(jdecode);
                        console.log(response);
                        var currentParam = "<?php echo $get_parameters['jpass'] ?>"

                        if (jdecode.statDb == 'true') {
                            var urlnya = "<?php echo $current_url.'/'.$page_typ.'/'; ?>" + jdecode.inv + currentParam;
                            // console.log(urlnya);
							window.location.replace(urlnya);

                        } else if(jdecode.statDb == 'false') {
                        	var message = "Submit <?php echo $allocation_title; ?> gagal.";
							var message = 'masalah pada input data, silahkan refresh dan coba lagi';
							var status = "warning";
							var timeout = 4000;
							createAlert(message, status, timeout);
                            // location.reload();
                            $('.donasi-loading').addClass('loading-hide');
                            $('.donation_button_now2').prop('disabled', false);
                            $('.donation_button_now2').css("cursor", "pointer");
							return false;

                        } else {
                            var message = "Submit <?php echo $allocation_title; ?> gagal.";
							var message = 'masalah pada input data, silahkan refresh dan coba lagi';
							var status = "warning";
							var timeout = 4000;
							createAlert(message, status, timeout);
                            // location.reload();
                            $('.donasi-loading').addClass('loading-hide');
                            $('.donation_button_now2').prop('disabled', false);
                            $('.donation_button_now2').css("cursor", "pointer");
							return false;

                        }

	                    // if (response.indexOf('-') > -1){
	                    // 	var urlnya = "<?php //echo $current_url.'/'.$page_typ.'/'; ?>"+ganti;
                        //     // console.log(urlnya);
						// 	window.location.replace(urlnya);
						// 	// alert(response);
						// }else{
						// 	var message = "Submit <?php //echo $allocation_title; ?> gagal.";
						// 	// var message = response;
						// 	var status = "warning";
						// 	var timeout = 4000;
						// 	createAlert(message, status, timeout);
                        //     $('.donasi-loading').addClass('loading-hide');
                        //     $('.donation_button_now2').prop('disabled', false);
                        //     $('.donation_button_now2').css("cursor", "pointer");
						// 	return false;
						// }
                        
                        // alert(response);
                        
	                });
                }else{
                	var message = "Maaf, Minimal sebesar Rp"+numberWithDot(min_donasi)+"";
					var status = "warning";
					var timeout = 4000;
					createAlert(message, status, timeout);
					return false;
                }
            });



            $(".choose_payment").on("click", function(e) {
                e.preventDefault();
                $(this).simplePopup({ type: "html", htmlSelector: "#popup_payment", width: "420px" });
            });
            $("#comment").keyup(function(){
                el = $(this);
                max_char = 160;
                if(el.val().length > max_char){
                    el.val( el.val().substr(0, max_char) );
                } else {
                    sisa = max_char-el.val().length;
                    $("#charNum").text('Sisa '+ sisa + ' char');
                }
            });

            <?php

            if($additional_field!=''){
            $no_field = 1;
            foreach ($additional_field['data'] as $key => $value) {

                if($value['type']=='input-textarea') { ?>
                
                $("#text_field_<?php echo $no_field; ?>").keydown(function(e){
                    var code = e.keyCode ? e.keyCode : e.which;
                    if (code == 222) {  // ' and "
                        return false;
                    }
                });
                $("#text_field_<?php echo $no_field; ?>").keyup(function(e){
                    el = $(this);
                    max_char = 300;
                    if(el.val().length > max_char){
                        el.val( el.val().substr(0, max_char) );
                    } else {
                        sisa = max_char-el.val().length;
                        $("#charNum_<?php echo $no_field; ?>").text('Sisa '+ sisa + ' char');
                    }
                });

            <?php } $no_field++; } } ?>
            
            $("#whatsapp").keyup(function(){
			    el = $(this);
			    if(el.val().length >= 14){
			        el.val( el.val().substr(0, 14) );
			    }
			});  

			$("input, textarea").on("change", function(e){
			var content = $(this).val();
                if($(this).prop("type")!='checkbox'){
                    if(content!=''){
                        $(this).addClass('filled');
                    }else{
                        $(this).removeClass('filled');
                    }
                }
		    });
            
            $('input#name').change(function (e) { 
                e.preventDefault();
                var name = $(this).val();
                set_cookies_name(name);
            });

            $('input#whatsapp').change(function (e) { 
                e.preventDefault();
                var whatsapp = $(this).val();
                set_cookies_wa(whatsapp);
            });

            $('input#email').change(function (e) { 
                e.preventDefault();
                var email = $(this).val();
                if( email!='' ) {
                    set_cookies_email(email);
                } else {
                    Cookies.remove('jshEmail');
                }
            });

            a = 0;
            $(".card-label").on("click", function(e) {
                console.log("payment "+(a+1));
                a = a+1;
            });
    
            
            $(".other_nominal_value input").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return nilai = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });
                run_other_nominal();
                <?php if($additional_formula!='' && $row->form_status=='1'){ ?>
                run_additional_donate();
                <?php } ?>
            });
            $(".additional_nominal_value input").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return nilai = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });
                <?php if($additional_formula!='' && $row->form_status=='1'){ ?>
                run_additional_donate();
                <?php } ?>
            });
            $('input[name="nominal_donasi"]').on("change", function(e){
                nominal = $(this).val();
                set_cookies_nominal(nominal);
                var nominal_label = $(this).data('label');
                if(nominal!=0){
                    $('.other_nominal_value').addClass('hide_input');
                    $('.other_nominal_value input').val('');
                    $('#nominal_value').text(nominal_label);
                }else{
                    $('.other_nominal_value').removeClass('hide_input');
                    $('.other_nominal_value input').caretTo(0);
                    $('#nominal_value').text('');
                }
                <?php if($additional_formula!='' && $row->form_status=='1'){ ?>
                run_additional_donate();
                <?php } ?>
            });
    
            
    
            $(".pendapatan_perbulan input, .pendapatan_lainnya input, .pengeluaran input").on("keyup", function(e){
                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return nilai = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });
                run_zakat();
            });

            function run_zakat(){
                var pendapatan_perbulan = $('.pendapatan_perbulan input').val();
                if(pendapatan_perbulan!=''){
                    var pendapatan_perbulan_int = pendapatan_perbulan.replace(/\./g,'');
                }else{
                    var pendapatan_perbulan_int = 0;
                }
                
                var pendapatan_lainnya = $('.pendapatan_lainnya input').val();
                if(pendapatan_lainnya!=''){
                    var pendapatan_lainnya_int = pendapatan_lainnya.replace(/\./g,'');
                }else{
                    var pendapatan_lainnya_int = 0;
                }
                
                var pengeluaran = $('.pengeluaran input').val();
                if(pengeluaran!=''){
                    var pengeluaran_int = pengeluaran.replace(/\./g,'');
                }else{
                    var pengeluaran_int = 0;
                }
    
                var total_zakat = parseInt(pendapatan_perbulan_int)+parseInt(pendapatan_lainnya_int)-parseInt(pengeluaran_int);
                // console.log('zakat 1:'+total_zakat);
                <?php 
                if($row->zakat_setting=='1'){ 
                    if($row->zakat_percent<=0 || $row->zakat_percent==null){
                        $zakat_percent = '2.5';
                    }else{
                        $zakat_percent = $row->zakat_percent;
                    }
                    echo "total_zakat = ($zakat_percent*total_zakat)/100;";
                }else{
                    echo "total_zakat = (2.5*total_zakat)/100;";
                }
                ?>
                // console.log('zakat 2:'+total_zakat);
                total_zakat = Math.round(total_zakat);
                // console.log('zakat 3:'+total_zakat);
                if(total_zakat!=''){
                    nominal = parseInt(total_zakat);
                    set_cookies_nominal(nominal);
                    $('#nominal_value').text(numberWithDot(total_zakat));
                    $('.total_zakat input').val(numberWithDot(total_zakat));
                }
            }
    
            function run_other_nominal(){
                var content = $('.other_nominal_value input').val();
                if(content!=''){
                    $('#nominal_value').text(content);
                    mystring_number = content.replace(/\./g,'');
                    nominal = parseInt(mystring_number);
                    set_cookies_nominal(nominal);
                }
            }

            total_additional_donate = 0;
    
            $("select#jumlah_paket").on("change", function(e){
                var nominal_paket = $('#nominal_paket').attr('data-paket');
                var jumlah = this.value;
                if(jumlah!='0'){
                    nominal = nominal_paket*jumlah;
                    set_cookies_nominal(nominal);
                    content = nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    $('#nominal_value').text(content);
                }else{
                    nominal = 0;
                    set_cookies_nominal(nominal);
                    content = nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    $('#nominal_value').text(content);
                }
                <?php if($additional_formula!='' && $row->form_status=='1'){ ?>
                run_additional_donate();
                <?php } ?>
            });
        });




        
        <?php if($additional_formula!='' && $row->form_status=='1'){ ?>
        <?php for ($i = 1; $i <= $jumlah_formula; $i++) { ?>
        field<?php echo $i; ?> = 0;
        <?php } ?>

        function run_additional_donate(){
            <?php for ($i = 1; $i <= $jumlah_formula; $i++) { ?>
            field<?php echo $i; ?> = $('.additional_nominal_value.add_donate<?php echo $i; ?> input').val();
            if(field<?php echo $i; ?>==''){field<?php echo $i; ?>=0;}else{field<?php echo $i; ?> = parseInt(field<?php echo $i; ?>.replace(/\./g,''));}
            <?php } ?>
            
            <?php if($jumlah_formula!=0){echo 'total_additional_donate = '; } for ($i = 1; $i <= $jumlah_formula; $i++) { if($i<$jumlah_formula){echo'parseInt(field'.$i.')+';}else{echo'parseInt(field'.$i.')';} } if($jumlah_formula!=0){echo ';'; }?>
            run_total();
        }
        <?php } //DISINI TOTAL BUTTON NYA ?>

        totalnya = 0;
        function run_total(){

            totalnya = parseInt(total_additional_donate) + parseInt(nominal);

            if(totalnya!=''){
                $('#nominal_value').text(numberWithDot(totalnya));
                $('.total_summary input').val(numberWithDot(totalnya));
            }

            set_cookies_totalnya(totalnya);

        }

		function allowNumbersOnly(e) {
		    var code = (e.which) ? e.which : e.keyCode;
		    if (code > 31 && (code < 48 || code > 57)) {
		        e.preventDefault();
		    }
		}
		function set_cookies_nominal(nominal){
            Cookies.set('nominal', nominal, { expires: 1, path: '<?php echo $current_path; ?>' });
        }
        function set_cookies_totalnya(totalnya){
            Cookies.set('totalnya', totalnya, { expires: 1, path: '<?php echo $current_path; ?>' });
        }
		function numberWithDot(x) {
		    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		}

        <?php if($total_on_url!='') { ?>
        Cookies.set('nominal', <?php echo $total_on_url; ?>, { expires: 1, '<?php echo $current_path; ?>' });
        <?php } ?>

        function set_cookies_name(name){
            Cookies.set('jshName', name, { expires: 14 });       //without path
        }
        function set_cookies_wa(wa){
            Cookies.set('jshWa', wa, { expires: 14 });           //without path
        }
        function set_cookies_email(email){
            Cookies.set('jshEmail', email, { expires: 14 });    //without path
        }

	payment_code = '';
	payment_name = '';
	payment_number = '';
	payment_account = '';
	payment_method = '';

	(function ($) {

    "use strict";

    $.fn.simplePopup = function(options) {
        /**
         * Javascript this
         */
        var that = this;

        /**
         * The data to be inserted in the popup
         */
        var data;

        /**
         * Determined type, based on type option (because we have possible value "auto")
         */
        var determinedType;

        /**
         * Different types are supported:
         *
         * "auto"   Will first try "data", then "html" and else it will fail.
         * "data"   Looks at current HTML "data-content" attribute for content
         * "html"   Needs a selector of an existing HTML tag
         */
        var types = [
            "auto",
            "data",
            "html",
        ];

        /**
         * Default values
         */
        var settings = $.extend({
            type: "auto",                   // Type to get content
            htmlSelector: null,             // HTML selector for popup content
            width: "600px",                 // Width popup
            height: "auto",                 // Height popup
            background: "#fff",             // Background popup
            backdrop: 0.7,                  // Backdrop opactity or falsy value
            backdropBackground: "#000",     // Backdrop background (any css here)
            inlineCss: true,                // Inject CSS via JS
            escapeKey: true,                // Close popup when "escape" is pressed"
            closeCross: true,               // Display a closing cross
            fadeInDuration: 0.3,            // The time to fade the popup in, in seconds
            fadeInTimingFunction: "ease",   // The timing function used to fade the popup in
            fadeOutDuration: 0.3,           // The time to fade the popup out, in seconds
            fadeOutimingFunction: "ease",   // The timing function used to fade the popup out
            beforeOpen: function(){},
            afterOpen: function(){},
            beforeClose: function(){},
            afterClose: function(){}
        }, options );

        /**
         * A selector string to filter the descendants of the selected elements that trigger the event.
         */
        var selector = this.selector;

        /**
         * init
         *
         * Set the onclick event, determine type, validate the settings, set the data and start popup.
         *
         * @returns {this} jQuery object
         */
        function init() {
            validateSettings();

            determinedType = determineType();
            data = setData();

            startPopup();

            return that;
        }

        /**
         * validateSettings
         *
         * Check for some settings if they are correct
         *
         * @returns {void}
         */
        function validateSettings() {
            if (settings.type !== "auto"
                && settings.type !== "data"
                && settings.type !== "html"
            ) {
                throw new Error("simplePopup: Type must me \"auto\", \"data\" or \"html\"");
            }

            if (settings.backdrop > 1 || settings.backdrop < 0) {
                throw new Error("simplePopup: Please enter a \"backdrop\" value <= 1 of >= 0");
            }

            if (settings.fadeInDuration < 0 || Number(settings.fadeInDuration) !== settings.fadeInDuration) {
                throw new Error("simplePopup: Please enter a \"fadeInDuration\" number >= 0");
            }

            if (settings.fadeOutDuration < 0 || Number(settings.fadeOutDuration) !== settings.fadeOutDuration) {
                throw new Error("simplePopup: Please enter a \"fadeOutDuration\" number >= 0");
            }
        }

        /**
         * determineType
         *
         * Check what type we have (and with that where we need to look for the data)
         *
         * @returns {boolean|string} The type of the data or false
         */
        function determineType() {
            // Type HTML
            if (settings.type === "html") {
                return "html";
            }

            // Type DATA
            if (settings.type === "data") {
                return "data";
            }

            // Type AUTO
            if (settings.type === "auto") {
                if(that.data("content")) {
                    return "data";
                }

                if ($(settings.htmlSelector).length) {
                    return "html";
                }

                throw new Error("simplePopup: could not determine type for \"type: auto\"");
            }

            return false;
        }

        /**
         * setData
         *
         * Set the data variable based on the type
         *
         * @returns {boolean|string} The HTML or text to disply in the popup or false
         */
        function setData() {
            // Type HTML
            if (determinedType === "html") {
                if (!settings.htmlSelector) {
                    throw new Error("simplePopup: for \"type: html\" the \"htmlSelector\" option must point to your popup html");
                }

                if (!$(settings.htmlSelector).length) {
                    throw new Error("simplePopup: the \"htmlSelector\": \"" + settings.htmlSelector + "\" was not found");
                }

                return $(settings.htmlSelector).html();
            }

            // Type DATA
            if (determinedType === "data") {
                data = that.data("content");

                if (!data) {
                    throw new Error("simplePopup: for \"type: data\" the \"data-content\" attribute can not be empty");
                }

                return data;
            }

            return false;
        }

        /**
         * startPopup
         *
         * Insert popup HTML, maybe bind escape key and maybe start the backdrop
         *
         * @returns {void}
         */
        function startPopup() {
            if (settings.backdrop) {
                startBackdrop();
            }

            if (settings.escapeKey) {
                bindEscape();
            }

            insertPopupHtml();
        }

        /**
         * insertPopupHtml
         *
         * Create the popup HTML and append it to the body. Maybe set the CSS.
         *
         * @returns {void}
         */
        function insertPopupHtml() {
            var content = $("<div/>", {
                "class": "simple-popup-content",
                "html": data
            });

            var html = $("<div/>", {
                "id": "simple-popup",
                "class": "hide-it"
            });

            if (settings.inlineCss) {
                content.css("width", settings.width);
                content.css("height", settings.height);
                content.css("background", settings.background);
            }



            bindClickPopup(html);

            // When we have a closeCross, create the element, bind click close and append it to
            // the content
            if (settings.closeCross) {
                var closeButton = $("<div/>", {
                    "class": "close"
                });

                bindClickClose(closeButton);
                content.append(closeButton);
            }

            html.append(content);

            // Call the beforeOpen callback
            settings.beforeOpen(html);

            $("body").append(html);

            // Use a timeout, else poor CSS is to slow to see the difference
            setTimeout(function() {
                var html = $("#simple-popup");

                // Set the fade in effect
                if (settings.inlineCss) {
                    html = setFadeTimingFunction(html, settings.fadeInTimingFunction);
                    html = setFadeDuration(html, settings.fadeInDuration);
                }

                html.removeClass("hide-it");

            });

            // Poll to check if the popup is faded in
            var intervalId = setInterval(function() {
                if ($("#simple-popup").css("opacity") === "1") {
                    clearInterval(intervalId);

                    // Call the afterOpen callback
                    settings.afterOpen(html);
                }
            }, 100);

            if(payment_code!=''){
            	$('.'+payment_code).find("input").prop("checked", true);
        	}
            // alert(payment_is);
        }

        /**
         * stopPopup
         *
         * Stop the popup and remove it from the DOM. Because it can fade out, use and interval
         * to check if opacity has reached 0. Maybe remove backdrop and maybe unbind the escape
         * key
         *
         * @returns {void}
         */
        function stopPopup() {
            // Call the beforeClose callback
            var html = $("#simple-popup");
            settings.beforeClose(html);

            // Set the fade out effect
            if (settings.inlineCss) {
                html = setFadeTimingFunction(html, settings.fadeOutTimingFunction);
                html = setFadeDuration(html, settings.fadeOutDuration);
            }

            $("#simple-popup").addClass("hide-it");

            // Poll to check if the popup is faded out
            var intervalId = setInterval(function() {
                if ($("#simple-popup").css("opacity") === "0") {
                    $("#simple-popup").remove();
                    clearInterval(intervalId);

                    // Call the afterClose callback
                    settings.afterClose();
                }
            }, 100);

            if (settings.backdrop) {
                stopBackdrop();
            }

            if (settings.escapeKey) {
                unbindEscape();
            }
        }

        /**
         * bindClickPopup
         *
         * When clicked outside the popup, close the popup. Use e.target to determine if
         * "simple-popup" was clicked or "simple-popup-content"
         *
         * @param {string} html The html of the popup
         * @returns {void}
         */
        
        function bindClickPopup(html) {
        	
            $(html).on("click", function(e) {
                if ($(e.target).prop("id") === "simple-popup") {
                    stopPopup();
                }

                if ($(e.target).hasClass("card-label")) {
                    stopPopup();
                    payment_method = $(e.target).attr('data-method');
                    payment_code = $(e.target).attr('data-code');
                    payment_name = $(e.target).attr('data-paymentname');
                    payment_number = $(e.target).attr('data-number');
                    payment_account = $(e.target).attr('data-account');
                    $('.title_payment').text(payment_name).css({"text-transform":"capitalize", "font-weight":"bold"});
                    console.log('label :'+payment_code);

                    $('.box_img_payment img').attr('src', '<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/bank/';?>'+payment_code+'.png');
					$('.box_img_payment img').attr('data-paymentmethod', payment_method).attr('data-paymentcode', payment_code).attr('data-paymentnumber', payment_number).attr('data-paymentaccount', payment_account);
					$('#choose_payment').removeClass('set_red');
                }
                if ($(e.target).prop("class") === "card-icon") {
                	stopPopup();
                    payment_method = $(e.target).attr('data-method');
                    payment_code = $(e.target).attr('data-code');
                    payment_name = $(e.target).attr('data-paymentname');
                    payment_number = $(e.target).attr('data-number');
                    payment_account = $(e.target).attr('data-account');
                    $('.title_payment').text(payment_name).css({"text-transform":"capitalize", "font-weight":"bold"});
                    console.log('icon :'+payment_code);

                    $('.box_img_payment img').attr('src', '<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/bank/';?>'+payment_code+'.png');
					$('.box_img_payment img').attr('data-paymentmethod', payment_method).attr('data-paymentcode', payment_code).attr('data-paymentnumber', payment_number).attr('data-paymentaccount', payment_account);
					$('#choose_payment').removeClass('set_red');
                }
                if ($(e.target).prop("class") === "card-text") {
                	stopPopup();
                    payment_method = $(e.target).attr('data-method');
                    payment_code = $(e.target).attr('data-code');
                    payment_name = $(e.target).attr('data-paymentname');
                    payment_number = $(e.target).attr('data-number');
                    payment_account = $(e.target).attr('data-account');
                    $('.title_payment').text(payment_name).css({"text-transform":"capitalize", "font-weight":"bold"});
                    console.log('name :'+payment_code);

                    $('.box_img_payment img').attr('src', '<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/bank/';?>'+payment_code+'.png');
					$('.box_img_payment img').attr('data-paymentmethod', payment_method).attr('data-paymentcode', payment_code).attr('data-paymentnumber', payment_number).attr('data-paymentaccount', payment_account);
					$('#choose_payment').removeClass('set_red');
                }
            });
        }

        function bindClickClose(html) {
            $(html).on("click", function(e) {
                stopPopup();
            });
        }

        function startBackdrop() {
            insertBackdropHtml();
        }

        function stopBackdrop() {
            var backdrop = $("#simple-popup-backdrop");

            // Set the fade out effect
            if (settings.inlineCss) {
                backdrop = setFadeTimingFunction(backdrop, settings.fadeOutTimingFunction);
                backdrop = setFadeDuration(backdrop, settings.fadeOutDuration);
            }

            backdrop.addClass("hide-it");

            // Poll to check if the popup is faded out
            var intervalId = setInterval(function() {
                if ($("#simple-popup-backdrop").css("opacity") === "0") {
                    $("#simple-popup-backdrop").remove();
                    clearInterval(intervalId);
                }
            }, 100);
        }

        function insertBackdropHtml() {
            var content = $("<div/>", {
                "class": "simple-popup-backdrop-content"
            });

            var html = $("<div/>", {
                "id": "simple-popup-backdrop",
                "class": "hide-it"
            });

            if (settings.inlineCss) {
                content.css("opacity", settings.backdrop);
                content.css("background", settings.backdropBackground);
            }

            html.append(content);
            $("body").append(html);

            // Use a timeout, else poor CSS doesn"t see the difference
            setTimeout(function() {
                var backdrop = $("#simple-popup-backdrop");

                // Set the fade in effect
                if (settings.inlineCss) {
                    backdrop = setFadeTimingFunction(backdrop, settings.fadeInTimingFunction);
                    backdrop = setFadeDuration(backdrop, settings.fadeInDuration);
                }

                backdrop.removeClass("hide-it");
            });
        }

        function bindEscape() {
            $(document).on("keyup.escapeKey", function(e) {
                if (e.keyCode === 27) {
                    stopPopup();
                }
            });
        }

        function unbindEscape() {
            $(document).unbind("keyup.escapeKey");
        }

        function setFadeTimingFunction(object, timingFunction) {
            object.css("-webkit-transition-timing-function", timingFunction);
            object.css("-moz-transition-timing-function", timingFunction);
            object.css("-ms-transition-timing-function", timingFunction);
            object.css("-o-transition-timing-function", timingFunction);
            object.css("transition-timing-function", timingFunction);
            return object;
        }

        function setFadeDuration(object, duration) {
            object.css("-webkit-transition-duration", duration + "s");
            object.css("-moz-transition-duration", duration + "s");
            object.css("-ms-transition-duration", duration + "s");
            object.css("-o-transition-duration", duration + "s");
            object.css("transition-duration", duration + "s");
            return object;
        }

        return init();
    };
}(jQuery));
    
	</script>

    <?php if($gtm_id!=''){ ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm_id;?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php } ?>
</body>
</html>