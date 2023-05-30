<?php

	global $wpdb;
	global $wp;
    $table_name = $wpdb->prefix . "dja_users";
    $table_name2 = $wpdb->prefix . "dja_settings";
    $table_name3 = $wpdb->prefix . "dja_donate";
    $table_name4 = $wpdb->prefix . "dja_campaign";


	$slug = $donasi_id;
	$campaign = $wpdb->get_results('SELECT * from '.$table_name4.' where slug="'.$slug.'"');
	if($campaign==null){
		wp_redirect( get_site_url() );
		exit;
	}

	// BEGIN SESSION BY Aktif Berbagi
	require_once( ROOTDIR_DNA . 'core/api/abi-session.php' );
	// echo '<pre>'; var_dump( $_SESSION ); echo '</pre>';
	

	// Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="logo_url" or type="app_name" or type="login_setting" or type="login_text" or type="register_setting" or type="register_text" or type="page_login" or type="page_register" or type="theme_color" or type="currency" or type="powered_by_setting" or type="fb_pixel" or type="fb_event" or type="gtm_id" or type="tiktok_pixel" or type="form_confirmation_setting" ORDER BY id ASC');
    $logo_url 		= $query_settings[0]->data;
    $app_name 		= $query_settings[1]->data;
    $login_setting 	= $query_settings[2]->data;
    $login_text 	= $query_settings[3]->data;
    $register_setting = $query_settings[4]->data;
    $register_text 	= $query_settings[5]->data;
    $page_login 	= $query_settings[6]->data;
    $page_register 	= $query_settings[7]->data;
    $general_theme_color = json_decode($query_settings[8]->data, true);
    $currency		= $query_settings[9]->data;
    $powered_by_setting = $query_settings[10]->data;
    $fb_pixel 	 		= $query_settings[11]->data;
    $fb_event  	 		= json_decode($query_settings[12]->data, true);
    $gtm_id             = $query_settings[13]->data;
    $tiktok_pixel       = $query_settings[14]->data;
    $form_confirmation_setting = $query_settings[15]->data;


    // FB EVENT
    $event_1   	 = $fb_event['event'][0];
    $event_2   	 = $fb_event['event'][1];
    $event_3   	 = $fb_event['event'][2];

	$home_url = home_url();
	if($link_code=='campaign'){
		$current_url = home_url().'/campaign/'.$slug;
	}elseif ($link_code=='josh'){
		$current_url = home_url().'/josh/'.$slug;
	}else{
		$current_url = home_url().'/preview/'.$slug;
	}

	if ($link_code=='josh') {
		$j_table = $wpdb->prefix . "dja_donate_debug";
	} else {
		$j_table = $wpdb->prefix . "dja_donate";
	}
	// echo '<pre>'; var_dump($invoice_id); echo '</pre>';
	$donate = $wpdb->get_results('SELECT * from '.$j_table.' where invoice_id="'.$invoice_id.'"');
	if($donate==null){
		wp_redirect( $current_url );
		exit;
	}

	// Get DATA
	$total = $donate[0]->nominal;
	$total_depan = substr($total, 0, -3);
	$total_depan = number_format($total_depan,0,",",".");
	$total_belakang = substr($total, -3);
	$bank_code = $donate[0]->payment_code;
	// $payment_code = $donate[0]->payment_code;
	$payment_account = $donate[0]->payment_account;
	$payment_number = $donate[0]->payment_number;
	$payment_qrcode = $donate[0]->payment_qrcode;
	$payment_date = $donate[0]->created_at;
	$donatur = $donate[0]->name;
	$img_confirmation_url = $donate[0]->img_confirmation_url;
	$payment_method = $donate[0]->payment_method;
	$linkReference = $donate[0]->ref;

	/**
	 * Project: ADS EXCEPTION WITH OTHER DUTA
	 * get exception for dfr's duta
	 */
	if( isset( $linkReference ) ) {
		$exceptionDuta = array( 'meisya', 'tisna', 'fadhilah', 'fina', 'safina', 'yusuf' );
		if( in_array( $linkReference, $exceptionDuta ) === true ) {
			$linkReference = null;
		}
	}

	//GET ALL MANUAL PAYMENT
	$all_payment1 = $wpdb->get_results("SELECT * FROM ".$table_name2. " WHERE type='bank_account' or type='bank_account_ref'");
	$all_payment2 = $all_payment1[0]->data;		// bank dfr
	$all_payment_ref = $all_payment1[1]->data;	// bank duta

	/**
	 * Check if this donor from Duta or Ads
	 * 
	 * @since 7 March 2023
	 */
	if( $linkReference === null || $linkReference === '' ) {
		$all_payment = json_decode($all_payment2);
	} else {
		$all_payment = json_decode($all_payment_ref);
	}

	
	foreach ($all_payment as $k => $v) {
		$code_bank[] = explode('@', $k);
		$name_bank[] = explode('_', $v);
	}

	$title = $campaign[0]->title;
	if($campaign[0]->form_status=='1'){
        $form_text   = json_decode($campaign[0]->form_text, true);
        $text1 = $form_text['text'][0];
        $text2 = $form_text['text'][1];
        $text3 = $form_text['text'][2];
        $text4 = $form_text['text'][3];

        $donasi_text = $text2;
    }else{
    	$donasi_text = 'Donasi';
    }

    // GET PIXEL FROM CAMPAIGN
    if($campaign[0]->pixel_status=='1'){
    	$fb_pixel  = $campaign[0]->fb_pixel;
        $fb_event  = json_decode($campaign[0]->fb_event, true);
        $event_1   = $fb_event['event'][0];
        $event_2   = $fb_event['event'][1];
        $event_3   = $fb_event['event'][2];
    }

    if($campaign[0]->gtm_status=='1'){
    	$gtm_id  = $campaign[0]->gtm_id;
    }
    if($campaign[0]->tiktok_status=='1'){
    	$tiktok_pixel  = $campaign[0]->tiktok_pixel;
    }

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

	$id_login = wp_get_current_user()->ID;

	$data_field = array();
    $data_field[ '{payment_account}' ] = '<span style="color:'.$button_color.'">'.$payment_account.'</span>';
    $data_field[ '{payment_number}' ] = '<span style="color:'.$button_color.'">'.$payment_number.'</span>';
    $data_field[ '{nominal}' ] = '<span style="color:'.$button_color.'">'.$total.'</span>';

	if($form_confirmation_setting=='1'){
		// buka semua
		$form_konfirmasi = true;
	}elseif($form_confirmation_setting=='2'){
		if($payment_method=='transfer'){
			// buka hanya untuk transfer
			$form_konfirmasi = true;
		}else{
			// close untuk instant dan va
			$form_konfirmasi = false;
		}
	}else{
		// close
		$form_konfirmasi = false;
	}
	$max_value = 7500000;

	//URL PARAM
	require_once(ROOTDIR_DNA . 'josh-utm.php');
	$get_parameters = jpass_param();

    require_once(ROOTDIR_DNA . 'core/api/fb-conversion-api.php');
    $conversion_api = new ABI_Facebook_Conversion_API( $fb_pixel, $track_mode, $link_code, $campaign[0], $donate[0], );
    // $conversion_api = new ABI_Facebook_Conversion_API( '630434134671269', 'EAAMncttZCQYsBAJplDAY8DFuIfbdi2F9YXjcXCmUBQrwn1msz5iG0IjOBzrdZAeoAy3HjHrgj86W4ZB8vbMe8IeKpvqZCyUpZBrYkb60Mhht5nushroHJ0YlCbEWyHEKSbZAmVQ4sYyOcCOZBvxb6AfpJLmd2jKX5ID4eWXRZBTJZAQ3rU1hehISWLdtIUDbAmq8ZD', $track_mode, $link_code, $campaign[0], $donate[0], );

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=0">
	<meta name="application-name" content="<?php echo $home_url; ?>"/>
	<meta property="og:url" content="<?php echo $home_url; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Instruksi Pembayaran - <?php echo $app_name; ?>" />
	<meta property="og:description" content="<?php echo $app_name; ?>" />
	<meta property="og:image" content="<?php echo $logo_url; ?>" />
	<title>Instruksi Penunaian | <?php echo $app_name; ?></title>
	<link rel="icon" href="https://ympb.or.id/wp-content/uploads/2022/10/cropped-Logo-ympb-768x782-1-300x300.png">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/donasiaja.css';?>">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/donasiaja-style.css';?>">
	<!-- sweetalert2 -->
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>admin/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo plugin_dir_url( __FILE__ ); ?>admin/plugins/animate/animate-4.1.1.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>admin/plugins/sweet-alert2/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
	<style type="text/css">
		.swal2-popup.swal2-modal.swal2-show {
		  padding-top: 40px;
		  padding-bottom: 40px;
		}
		.swal2-styled.swal2-cancel {
			background-color: #c6d0e3;
		}
		.swal2-styled.swal2-confirm {
			background-color: #E40D6D;
			border-left-color: rgb(228, 13, 109);
			border-right-color: rgb(228, 13, 109);
		}
		.swal2-icon-success .swal2-title {
		  color: #fff !important;
		} 
		.swal2-icon-success .swal2-content {
		  color: #ffffffab !important;
		}
		.box-card.no_rekening.josh {
			height: auto !important;
			margin-bottom: 0;
		}
		.box-copy.josh {
			display: flex;
			justify-content: center;
			float: none;
			margin: 10px 0px;
		}
		.box-img.josh img {
			float: none;
			width: 100px;
		}
		.bank-name.josh {
			text-align: center;
		}
		.bank-number.josh {
			text-align: center;
		}
		.box-img.josh {
			display: flex;
			justify-content: center;
			margin-right: 0;
		}
		.typ-rek-copy:hover {
			background: #18a870 !important;
			color: white !important;
		}
		.donasiaja-box img{
			width: 300px;
			border-radius:4px;
			margin-bottom: -43px;
			margin-top: -55px;
		}
		div.grid {
			display: grid;
			grid-template-columns: auto auto;
			column-gap: 10px;
			row-gap: 15px;
			margin-bottom: 20px;
		}
		#typ_section{border-radius:12px;padding:20px 40px 40px 40px;margin-top:30px}.loading-section{float:right;height:0;margin-top:-95px;display:none}.typ-box{margin-top:80px;border-radius:12px;padding:30px 30px;border:1px solid #c5cfdd}.typ-total{margin-top:30px;margin-bottom:30px}.typ-total-text{font-size:24px;font-weight:700}.typ-total-text .threelastd{color:#46a9fd}.typ-rek-copy,.typ-total-copy{position:absolute;padding:2px 10px;border-radius:12px;font-size:11px;margin-left:12px;cursor:pointer;color:#fff;background:#52a0fd;background:linear-gradient(to right,#52a0fd 0,#00e2fa 80%,#00e2fa 100%);box-shadow:0 4px 8px rgba(0,0,0,.1)}.typ-card .typ-total-copy{color:#2ab2f1}.animation-target.lala-alert.alert-success{background:linear-gradient(to right,#52a0fd 0,#00e2fa 80%,#00e2fa 100%);padding-top:20px;border:none;border-radius:4px;margin-top:10px;box-shadow:0 6px 24px rgba(164,192,217,.25);-webkit-box-shadow:0 6px 24px rgba(164,192,217,.25);-moz-box-shadow:0 6px 24px rgba(164,192,217,.25)}.typ-rek-copy img,.typ-total-copy img{width:10px;margin-right:3px;filter:brightness(0) invert(1);background:0 0;color:#2bbcf9}p.typ-note{background:#fff2d0;border:1px solid #ece5cc;padding:10px 12px;border-radius:4px;margin-bottom:30px;margin-top:20px;line-height:1.5;font-size:12px;margin-top:40px;box-shadow:0 8px 17px rgba(0,0,0,.1)}.typ-card{background:-webkit-linear-gradient(to left,#0067b3,#164fd7);background:linear-gradient(to left,#0067b3,#164fd7);padding:30px 25px;height:160px;border-radius:16px;box-shadow:0 8px 8px rgba(0,0,0,.1);-webkit-box-shadow:0 8px 8px rgba(0,0,0,.1);-moz-box-shadow:0 8px 8px rgba(0,0,0,.1);border:1px solid #fff}.typ-card .bank-name{text-align:left;color:#2ab2f1;margin-bottom:0;position:absolute;margin-top:10px}.typ-card .bank-number{font-size:25px;font-weight:700;margin-top:0;color:#fff;text-shadow:1px 4px 8px rgba(0,0,0,.2)}.bank-number span{font-size:14px;font-weight:300}.typ-card .bank-logo{text-align:right;margin-top:-40px}.typ-card .bank-logo img{width:120px}.box-card{border:1px solid #edeaf4;margin-bottom:20px;padding:20px 10px;background:#f3f5fb;border-radius:4px;height:40px;background:linear-gradient(to right,transparent 0,#ffffff45 80%,#fff 100%);background:#ffffff;}.box-card .box-img img{width:70px;float:left;margin-right:1em;position:relative;margin-bottom:10px;border-radius:3px}.box-card li{text-align:left;list-style-position:outside;list-style:none}.box-card ul{padding-left:0;margin:0;color:#23374d}.box-card .bank-name{margin-bottom:3px;font-weight:700}.box-card .bank-number{margin-bottom:0}.box-copy{float:right;margin-right:70px;margin-top:-58px}.box-copy img{filter:none}.box-copy .typ-rek-copy,.box-copy .typ-total-copy{box-shadow:none}p.typ_text{color:#23374d;margin-top:-8px;text-align:center}ul li.copy{display:none}ul li.copy img{width:10px;margin-right:5px;background:0 0;color:#2bbcf9}@media only screen and (max-width:480px){.typ-box{margin-left:20px;margin-right:20px;border-radius:16px;padding:30px 20px}.typ-card .bank-logo{margin-top:-25px}.typ-card .bank-logo img{width:90px}.typ-card{height:150px;font-size:21px}#typ_section{padding:0px 20px 60px 20px;margin-left:20px;margin-right:20px}.box-copy{display:none}.box-card.total_donasi{margin-bottom:50px}ul li.copy{display:inherit;padding:5px 10px;text-align:center;background:#f2f2fb;margin-top:15px;border-radius:2px;cursor:pointer}.box-card.no_rekening ul li.copy{margin-top:10px}.box-card.no_rekening{margin-bottom:60px}.donasiaja-box img{
			width: 270px;
			border-radius:4px;
			margin-bottom: -43px;
			margin-top: -35px;
		}.box-copy.josh {display: none;}div.grid {grid-template-columns: none;}}accordion-container{margin-bottom: 40px;position: relative;max-width: 500px;height: auto;margin: 10px auto;}.accordion-container > h2{text-align: center;color: #fff;padding-bottom: 5px;margin-bottom: 20px;padding-bottom: 15px;}.set{position: relative;width: 100%;height: auto;background-color: #ebeff7; margin-bottom: 8px; border-radius: 4px;}.set > a{display: block;padding: 10px 15px;text-decoration: none;color: #23374d;font-weight: 600;-webkit-transition:all 0.2s linear;-moz-transition:all 0.2s linear;transition:all 0.2s linear;font-size: 14px;}.set > a i{float: right;margin-top: 2px;}.set > a.active{border-top-left-radius: 4px; border-top-right-radius: 4px;background-color:<?php echo $button_color;?>;color: #fff;}.content{background-color: rgba(255, 255, 255, 0.63);display: none;margin-top: 0px;margin-bottom: 0;padding-bottom: 15px;padding-top: 10px;}.content li{padding: 6px 12px;margin: 0;color: #333;font-size: 13px;}.set > a:active{outline: none;}.upload-container {width: 100%;align-items: center;display: flex;justify-content: center;background-color: #fcfcfc;margin-bottom: 40px;}.upload-card {border-radius: 10px;width: 600px;height: 260px;background-color: #ffffff;}.upload-card h3 {font-size: 22px;font-weight: 600;}.upload-container {display: none;}.drop_box {margin: 10px 0;padding: 30px 30px 40px 30px;display: flex;align-items: center;justify-content: center;flex-direction: column;border: 3px dashed #ABCAFF;border-radius: 10px;background: #abcaff21;}.drop_box h4 {font-size: 16px;font-weight: 400;color: #2e2e2e;}.drop_box p {margin-top: 10px;margin-bottom: 20px;font-size: 12px;color: #a3a3a3;}.btn {text-decoration: none;background-color: #005af0;color: #ffffff;padding: 10px 20px;border: none;outline: none;transition: 0.3s;border-radius: 2px;}.btn:hover{text-decoration: none;background-color: #ffffff;color: #005af0;padding: 10px 20px;border: none;outline: 1px solid #010101;}.form input {margin: 10px 0;width: 100%;background-color: #e2e2e2;border: none;outline: none;padding: 12px 20px;border-radius: 4px;}.upload-area {position: relative;height: 9.25rem;display: flex;justify-content: center;align-items: center;flex-direction: column;border: 2px dashed var(--clr-light-blue);border-radius: 15px;cursor: pointer;transition: border-color 300ms ease-in-out;display: none;}#previewImage {height: 130px;transition: opacity 300ms ease-in-out;border-radius: 8px;margin-bottom: 30px;}.close {position: absolute;top: 0;margin: 0 auto;margin-top: 0px;margin-right: auto;background: #e40d6d;width: 25px;height: 25px;border-radius: 40px;text-align: center;color: #fff;right: 0;margin-top: -10px;margin-right: -10px;}.upload_file {display: none;background: #e40d6d;border-radius: 2px;}.btn.upload_file:hover{text-decoration: none;background-color: #ffffff;color: #e40d6d;padding: 10px 20px;border: none;outline: 1px solid #e40d6d;}.box_loading {background: transparent;width: 0;padding: 0;margin-top: 10px;}.btn.box_loading:hover{text-decoration: none;background-color: transparent;color: transparent;}.lds-ellipsis {display: none;position: absolute;margin-left: -38px;margin-top: -30px;}.lds-ellipsis div {position: absolute;top: 33px;width: 13px;height: 13px;border-radius: 50%;background: #e40d6dba;animation-timing-function: cubic-bezier(0, 1, 1, 0);}.lds-ellipsis div:nth-child(1) {left: 8px;animation: lds-ellipsis1 0.6s infinite;}.lds-ellipsis div:nth-child(2) {left: 8px;animation: lds-ellipsis2 0.6s infinite;}.lds-ellipsis div:nth-child(3) {left: 32px;animation: lds-ellipsis2 0.6s infinite;}.lds-ellipsis div:nth-child(4) {left: 56px;animation: lds-ellipsis3 0.6s infinite;}@keyframes lds-ellipsis1 {0% {transform: scale(0);}100% {transform: scale(1);}}@keyframes lds-ellipsis3 {0% {transform: scale(1);}100% {transform: scale(0);}}@keyframes lds-ellipsis2 {0% {transform: translate(0, 0);}100% {transform: translate(24px, 0);}}.swal2-success-circular-line-left, .swal2-success-fix, .swal2-success-circular-line-right, .swal2-icon-success {background-color: #209A45 !important;}.swal2-title, .swal2-content {color:#2d3237;}.swal2-popup {border-radius:16px;}.swal2-icon-success .swal2-confirm {background: #0a7129 !important;margin-bottom: 20px;}.swal2-icon-warning .swal2-title, .swal2-icon-warning #swal2-content {color: #575252;}
	</style>

	<script>
		document.purcValue = <?php echo $donate[0]->nominal; ?>;
		document.purcID	= '<?php echo $invoice_id; ?>';
		document.purcCurrency = 'IDR';
	</script>

	
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
	fbq('track', '<?php echo $event_3; ?>', {
		value: <?php echo $total; ?>,
		currency: '<?php echo $currency?>',
		content_name: '<?php echo $campaign[0]->title; ?>',
		content_ids: '<?php echo $campaign[0]->campaign_id ?>'
	}, {eventID: '<?php echo $invoice_id; ?>'});
	</script>
        
        <?php }

    }elseif($fb_pixel==''){
        $pixel_id = "";
    }else{
		if($total < $max_value) {
			$pixel_id = $fb_pixel; ?>

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
			fbq('track', '<?php echo $event_3; ?>', {
				value: <?php echo $total; ?>,
				currency: '<?php echo $currency?>',
				content_name: '<?php echo $campaign[0]->title; ?>',
				content_ids: '<?php echo $campaign[0]->campaign_id ?>'
			}, {eventID: '<?php echo $invoice_id; ?>'});
			</script>
		<?php }
        
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
	
	

		<div id="typ_section" class="section-box" style="background: url('<?php echo plugin_dir_url( __FILE__ ).'assets/images/bg3.png'; ?>') no-repeat, #fff;">
			<div class="donasiaja-box" style="margin-bottom: 10px;margin-top: 10px;">
			<img alt="Donasi Aja" class="" src="<?php //echo $logo_url; ?>https://ympb.or.id/wp-content/uploads/2022/11/Desain-tanpa-judul-7-min.png">
			</div>

				<div class="title" style="margin-bottom: 30px;">
					<p class="typ_text">Terimakasih <b><?php echo $donatur; ?></b><br>atas niat <?php echo $donasi_text; ?> yang akan dialokasikan pada program :</p>
					<h2 style="text-align: center;font-size: 16px;"><?php echo $title; ?></h2>
					<p class="typ_text">Untuk menyempurnakan <?php echo $donasi_text; ?>, silahkan transfer ke rekening :</p>
				</div>

				<?php 

				$bHasLink = strpos($payment_number, 'http') !== false || strpos($payment_number, 'www.') !== false;

				if($bHasLink){

					$ipaymuLink = strpos($payment_qrcode, 'ipaymu') !== false;
					if($ipaymuLink){
						$content=file_get_contents($payment_qrcode);
						if (preg_match("/src=[\"\'][^\'\']+[\"\']/", $content, $matches)) {
						// if (preg_match('@"([^"]+)"@', $content, $matches)) {
							$payment_qrcode = $matches[0];
						}
						$from_ipaymu = true;
					}else{
						$from_ipaymu = false;
					}

				?>

				<?php if($from_ipaymu==true) { ?>
					<div class="qrcode" style="border: 1px solid #edeaf4;border-radius: 4px;margin-bottom: 20px;text-align: center;padding-top: 20px; padding-bottom: 20px;margin-top: -20px;">
					<img <?php echo $payment_qrcode; ?> >
					</div>
				<?php }else{ ?>
					<div style="border: 1px solid #edeaf4;border-radius: 4px;margin-bottom: 20px;text-align: center;padding-top: 20px;padding-bottom: 20px;margin-top: -20px;">
					<img src="<?php echo $payment_number; ?>" style="width: 70%;">
					</div>
				<?php } ?>
				

				<?php
			    if (preg_match('~[0-9]+~', $payment_account)) { ?>
				    <p class="typ_text" style="margin-bottom: 30px;font-size: 13px;">Scan QR-Code berikut pada aplikasi atau gunakan Payment ID dibawah untuk mentransfer.</p>
				<?php }else{ ?>
					<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;">Scan QR-Code berikut untuk mentransfer<br>dengan app kesayangan anda.</p>
					
					<?php if($bank_code=='gopay') { ?>
						<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;"><a href="https://gojek.onelink.me/2351932542?af_banner=true&amp;pid=Go-Jek_Web&amp;c=WebToAppBanner&amp;af_adset=bottom-banner&amp;af_ad=%2Fsg%2F&amp;af_dp=gojek%3A%2F%2Fhome"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/app_gopay.png'; ?>" style="width: 100%;"></a></p>
					<?php }elseif($bank_code=='ovo'){?>
						<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/app_ovo.png'; ?>" style="width: 100%;"></p>
					<?php }elseif($bank_code=='dana'){?>
						<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;"><a href="https://link.dana.id" target="_blank"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/app_dana.png'; ?>" style="width: 100%;"></a></p>
					<?php }elseif($bank_code=='shopeepay'){?>
						<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/app_shopeepay.png'; ?>" style="width: 100%;"></p>
					<?php }elseif($bank_code=='linkaja'){?>
						<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/app_linkaja.png'; ?>" style="width: 100%;"></p>
					<?php }else{?>
						<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/app_qris_support.png'; ?>" style="width: 70%;"></p>
					<?php } ?>

					<p class="typ_text" style="margin-bottom: 30px;font-size: 13px;"><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/scan_qris.png'; ?>" style="width: 70%;"></p>

				<?php } ?>

				<div class="box-card no_rekening" <?php if (preg_match('~[0-9]+~', $payment_account)) {}else{ echo 'style="margin-bottom: 30px;"';} ?>>
					<div class="box-img"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/bank/'.$bank_code.'.png'; ?>"></div>
					<ul>
					    <li class="bank-name"></li>
					    <li class="bank-number" style="margin-top: 8px;padding-bottom: 10px;"><?php echo $payment_account; ?></li>
					    <?php
					    if (preg_match('~[0-9]+~', $payment_account)) { ?>
						    <li class="copy copy-rek" data-salin="<?php echo preg_replace('/\D/', '', $payment_account); ?>"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/copy.png'; ?>">Copy ID</li>
						<?php } ?>

					</ul>
					<?php
				    if (preg_match('~[0-9]+~', $payment_account)) { ?>
						    <div class="box-copy"><span class="typ-rek-copy" data-salin="<?php echo preg_replace('/\D/', '', $payment_account); ?>" style="background: transparent;color: #888095;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/copy.png'; ?>"> COPY</span></span></div>
					<?php } ?>

				</div>

				<?php }else{ ?>
				
				<div class="jrekening">
					<div class="grid">
					<?php 
                    if( $linkReference === null || $linkReference === '' ) {
                        $c_max = 3;
                    } else {
                        $c_max = 4;
                    }
                    for($i=0; $i< $c_max; $i++) : 
                    ?>
							<div class="box-card no_rekening josh" >
								<div class="box-img josh"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/bank/'.$code_bank[$i][0].'.png'; ?>"></div>
								<ul>
									<li class="bank-name josh"><?php echo preg_replace('/\D/', '', $name_bank[$i][0]); ?></li>
									<li class="bank-number josh"><?php echo $name_bank[$i][1]; ?></li>
									<li class="copy copy-rek" data-salin="<?php echo preg_replace('/\D/', '', $name_bank[$i][0]); ?>"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/copy.png'; ?>">Copy Rekening</li>
								</ul>
								<div class="box-copy josh"><span class="typ-rek-copy josh" data-salin="<?php echo preg_replace('/\D/', '', $name_bank[$i][0]); ?>" style="background: transparent;color: #888095;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/copy.png'; ?>"> COPY</span></span></div>
							</div>

						<?php endfor ?>
					</div>
				</div>

				<?php } ?>

				<div class="box-card total_donasi">
					<div class="box-img"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/donation_love.png'; ?>" style="width: 35px;margin-left: 15px;margin-right: 35px;"></div>
					<ul>
					    <li class="bank-name" style="font-size: 21px;padding-top: 7px;">Rp <?php echo $total_depan; ?>.<span style="color: #E40D6D;"><?php echo $total_belakang; ?></span></li>
					    <li class="copy copy-total" data-salin="<?php echo $total; ?>"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/copy.png'; ?>">Copy Total</li>
					</ul>
					<div class="box-copy"><span class="typ-total-copy" data-salin="<?php echo $total; ?>" style="background: transparent;color: #888095;margin-top: 5px;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/copy.png'; ?>"> COPY</span></span></div>
				</div>
				<?php if($bank_code=='jemput_donasi') { ?>
					<p class="typ_text" style="text-align: left;margin-bottom: 40px;font-size: 13px;">Dalam waktu dekat kami akan menghubungi dan menjemput Donasi Anda. Terimakasi atas kebaikannya, semoga Allah selalu senantiasa bersama anda.</p>
				<?php }else{ ?>
				<p class="typ_text" style="text-align: left;margin-bottom: 40px;font-size: 13px;">Harap transfer sesuai nominal diatas <i>(sampai 3 digit terakhir)</i> agar dapat terkonfirmasi otomatis dan kebaikan ini dapat kami teruskan.</p>

				<?php if($form_konfirmasi==true){ ?>
					<?php if($img_confirmation_url==''){ ?>
					<div class="upload-container">

					  <div class="upload-card">
					    <!-- <h3>Upload Files</h3> -->
					    <div class="drop_box">
					      <header class="title_dropbox">
					        <h4>Upload File Disini</h4>
					      </header>
					      <p class="title_dropbox">Files Supported: JPG, JPEG, PNG</p>


					      <div id="dropZoon" class="upload-area">

						      <img id="previewImage">
						      <div class="close" title="Change Image">x</div>
						      
						      
						  </div>
					      <input type="file" hidden accept=".jpg,.jpeg,.png" id="fileID" style="display:none;">
					      <button class="btn choose_file">Choose File</button><button class="btn upload_file">Upload Now</button>
					      <button class="btn box_loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></button>

					    </div>

					  </div>

					</div>
					<?php } ?>
				<?php } ?>

				<?php if($form_konfirmasi==true){ ?>
					<?php if($img_confirmation_url==''){ ?>
					<button class="donation_button_now2 confirm_payment" style="background:#23374d;border-color:#23374d;margin:0px 0 20px 0;width:100%;margin-bottom:30px;font-size:14px;">Konfirmasi Penunaian</button>
					<button class="donation_button_now2 confirm_process" style="background:#0DAC50;border-color:#109F4D;margin:0px 0 20px 0;width:100%;margin-bottom:30px;font-size:14px;display:none;cursor:default;"><i class="fa fa-check-circle" style="font-size: 16px;margin-right: 8px;"></i> Confirmation on Process</button>
					<?php }else{ ?>
					<button class="donation_button_now2 confirm_process" style="background:#0DAC50;border-color:#109F4D;margin:0px 0 20px 0;width:100%;margin-bottom:30px;font-size:14px;cursor:default;"><i class="fa fa-check-circle" style="font-size: 16px;margin-right: 8px;"></i> Confirmation on Process</button>
					<?php } ?>
				<?php } ?>
				
				<div class="box-card" style="background:#fff5e4;">
					<div class="box-img"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/timer.png'; ?>" style="width: 35px;margin-left: 18px;margin-right: 32px;"></div>
					<ul>
					    <li class="bank-number">Silahkan transfer sebelum :</li>
					    <li class="bank-name"><?php 

					    $time_plus_24 = strtotime('+24 hour',strtotime($payment_date));
					    echo date('d',$time_plus_24).' '.date('M',$time_plus_24).' '.date('Y',$time_plus_24).' - '.date('H:i',$time_plus_24); ?> WIB</li>
					</ul>
				
				</div>
				
				<p class="typ_text" style="text-align: left;margin-bottom: 40px;font-size: 13px;"><i>PENTING!</i> Bukti transfer memudahkan yayasan dalam <strong>menyalurkan <?php echo $donasi_text; ?></strong> kepada program yang tepat! Pastikan kamu <i>upload</i> bukti transfer ya! ^^</p>

				

				<br>
				
				
				</div>
				<?php } ?>
				
		</div>

	


	<div class="section-box box-powered" style="box-shadow: none;background: transparent;">
		
		<p style="color: #9aabc8;margin-top: -8px;text-align: center;padding-top: 30px;">Ingin lihat program lainnya?<br><a href="<?php echo $home_url; echo '/donasi'.$get_parameters['jpass']; ?>" target="_self" style="text-decoration: none;color: #1c75ba;">Klik disini</a></p>
		<?php if($powered_by_setting=='1'){ ?>
		<div class="powered-donasiaja-box"><a href="https://donasiaja.id" target="_blank"><img alt="Donasi Aja" class="powered-donasiaja-img" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/icons/donasiaja.ico'; ?>">Powered by DonasiAja</a></div>
		<?php } ?>
	</div>
	<div id="lala-alert-container"><div id="lala-alert-wrapper"></div></div>
	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/jquery.min.js';?>"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/donasiaja.min.js';?>"></script>
	<script>

	$(document).ready(function(){$(".set > a").on("click",function(){if($(this).hasClass("active")){$(this).removeClass("active");$(this).siblings(".content").slideUp(200);$(".set > a i").removeClass("fa-minus").addClass("fa-plus")}else{$(".set > a i").removeClass("fa-minus").addClass("fa-plus");$(this).find("i").removeClass("fa-plus").addClass("fa-minus");$(".set > a").removeClass("active");$(this).addClass("active");$(".content").slideUp(200);$(this).siblings(".content").slideDown(200)}})})

	<?php
		
		if($donate[0]->deeplink_url!=''){
		echo '
		$(function() {
		    setTimeout(loadDeeplink, 2000);
		});

		function loadDeeplink() {
		    window.location="'.$donate[0]->deeplink_url.'";
		}

		';			
		}

	?>

	$(".typ-total-copy, .copy-total").on("click",function(e){var total=$(this).attr('data-salin');copyToClipboard(total);var message="Nominal: "+total+" tersalin.";var status="success";var timeout=1500;createAlert(message,status,timeout)});$(".typ-rek-copy, .copy-rek").on("click",function(e){var rek=$(this).attr('data-salin');copyToClipboard(rek);var message="No. Rek: "+rek+" tersalin.";var status="success";var timeout=1500;createAlert(message,status,timeout)})

	function copyToClipboard(string) {
            let textarea;let result;try{textarea=document.createElement("textarea");textarea.setAttribute("readonly",!0);textarea.setAttribute("contenteditable",!0);textarea.style.position="fixed";textarea.value=string;document.body.appendChild(textarea);textarea.focus();textarea.select();const range=document.createRange();range.selectNodeContents(textarea);const sel=window.getSelection();sel.removeAllRanges();sel.addRange(range);textarea.setSelectionRange(0,textarea.value.length);result=document.execCommand("copy")}catch(err){console.error(err);result=null}finally{document.body.removeChild(textarea)}
        if(!result){const isMac=navigator.platform.toUpperCase().indexOf("MAC")>=0;const copyHotkey=isMac?"⌘C":"CTRL+C";result=prompt(`Press ${copyHotkey}`,string);if(!result){return!1}}
        return!0
            }

    <?php if($form_konfirmasi==true){ ?>
    <?php if($img_confirmation_url==''){ ?>
    
    // upload
    const dropArea = document.querySelector(".drop_box"),
	  button = dropArea.querySelector("button"),
	  dragText = dropArea.querySelector("header"),
	  input = dropArea.querySelector("input");
	let file;
	var filename;

	button.onclick = () => {
	  input.click();
	};

	input.addEventListener("change", function (e) {
	  	var file = e.target.files[0];
	    getFile(file).then((customJsonFile) => {
	         var ibase64 = 'data:'+customJsonFile['fileType']+';base64,'+customJsonFile['base64StringFile'];
	         $('#previewImage').attr('src', ibase64);
	         $('.title_dropbox').hide();
	         $('.upload-area').css({ "display": "inline"});
	         $('.choose_file').hide();
	         $('.upload_file').show();
	    });


	});

	$(".close").on("click",function(e){
		$('.choose_file').show();
	    $('.upload_file').hide();
	    $('.upload-area').css({ "display": "none"});
	    $('.title_dropbox').slideDown();
	    $('.lds-ellipsis').css({ "display": "none"});

	});

	
    function getFile(file) {
        var reader = new FileReader();
        return new Promise((resolve, reject) => {
            reader.onerror = () => { reader.abort(); reject(new Error("Error parsing file"));}
            reader.onload = function () {

                let bytes = Array.from(new Uint8Array(this.result));

                let base64StringFile = btoa(bytes.map((item) => String.fromCharCode(item)).join(""));

                resolve({ 
                    bytes: bytes,
                    base64StringFile: base64StringFile,
                    fileName: file.name, 
                    fileType: file.type
                });
            }
            reader.readAsArrayBuffer(file);
        });
    }

    $(document).ready(function() {
    	
    $(".upload_file").click(function(event) {
    	event.preventDefault();
    	swal.fire({
          title: 'Apakah data sudah sesuai?',
          text: "Hanya bisa upload data sekali, klik Lanjut jika sudah sesuai.",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Lanjut!',
          cancelButtonText: 'Batalkan',
          reverseButtons: true
        }).then(function(result) {
          	if (result.value) {
                
                var linkReference   = '<?php echo $get_parameters['ref']; ?>';
		        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		        var formData = new FormData();
		        formData.append('updoc', $('input[type = file]')[0].files[0]);
		        formData.append('action', "donasiaja_upload_confirmation");
		        $('.lds-ellipsis').css({ "display": "inline-block"});
		        $.ajax({
		            url: ajaxurl,
		            type: "POST",
		            data: formData,
		            cache: false,
		            processData: false,
		            contentType: false,
		            success: function(data) {
		                if(data!='failed'){
		                	var link_image = data;
		                	var invoice_id = "<?php echo $invoice_id;?>";

		                	var data_nya = [
			                	link_image,
			                    invoice_id
			                ];

			                var data = {
			                    "action": "djafunction_update_confirmation",
			                    "datanya": data_nya,
                                "ref" : "<?php echo $get_parameters['ref']; ?>"
			                };

			                jQuery.post("<?php echo $home_url; ?>/wp-admin/admin-ajax.php", data, function(response) {

								console.log(response)
								console.log(response.status)
								// var j_response = response.search("success");
			                	//if(response=='success'){
								// if(j_response != '-1') {
								if( response.status=='success') {
			                		var message = "Terimakasih, data konfirmasi berhasil kami terima!"
							        swal.fire(
						              'Success!',
						              message,
						              'success'
						            );
						            $('.lds-ellipsis').css({ "display": "none"});
					                $('.upload_file, .close, .upload-container').hide();
					                $('.confirm_process').show();
			                	}else{
			                		var message = "File gagal di Upload! #1"
				                	swal.fire(
						              'Failed!',
						              message,
						              'warning'
						            );
									console.log(response);
						            $('.lds-ellipsis').css({ "display": "none"});
			                	}
			                });

		                }else{
		                	var message = "File gagal di Upload! #2"
		                	swal.fire(
				              'Failed!',
				              message,
				              'warning'
				            );
		                }

		            },
		        });

          	}
      	})


	    });
	    

	    $(".confirm_payment").click(function(event) {
	    	$('.upload-container').css({'display':'inline-flex'});
	    	$(this).hide();
	    });

	    
	});

	<?php } ?>
	<?php } ?>

	</script>

	<?php if($gtm_id!=''){ ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm_id;?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php } ?>
    
</body>
</html>