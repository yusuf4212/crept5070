<?php

function djafunction_submit_donasi(){
	global $wpdb;
    $table_name 	= $wpdb->prefix . "dja_donate";
    $table_name2 	= $wpdb->prefix . "dja_settings";
    $table_name3 	= $wpdb->prefix . "dja_campaign";
    $table_name4 	= $wpdb->prefix . "dja_payment_log";
    $table_name5 	= $wpdb->prefix . "dja_aff_submit";
    $table_name6 	= $wpdb->prefix . "dja_aff_code";

    // FROM INPUT
    $campaign_id 	= $_POST['datanya'][0];
    $name 			= $_POST['datanya'][1];
    $whatsapp 		= $_POST['datanya'][2];
    $email 			= $_POST['datanya'][3];
    $anonim 		= $_POST['datanya'][4];
    $comment 		= str_replace('\\', '', $_POST['datanya'][5]);
    $nominal 		= $_POST['datanya'][6];
    $payment_method = '';
    $payment_code 	= '';
    $payment_number = '';
    $payment_account = '';
    $unique_number   = $_POST['datanya'][7];
    $title_campaign  = $_POST['datanya'][8];
    $affcode_id 	 = $_POST['datanya'][9];
    $main_donate 	 = $_POST['datanya'][10];
    $info_donate 	 = str_replace('\\', '', $_POST['datanya'][11]);
    $cs_id 	 		 = $_POST['datanya'][12];
	$debug_mode      = $_POST['datanya'][13];
	$utm_source		 = $_POST['datanya'][14];
	$utm_medium		 = $_POST['datanya'][15];
	$utm_campaign	 = $_POST['datanya'][16];
	$utm_term		 = $_POST['datanya'][17];
	$utm_content	 = $_POST['datanya'][18];
	$linkReference	 = $_POST['datanya'][19];

	/**
	 * Filter whatsapp filter only accept digit value
	 * @since 19 Apr 2023
	 * 
	 * change if the first two digit was 62, replace with 0
	 */
	$whatsapp = preg_replace("/[^0-9]/", '', $whatsapp);

	if( substr($whatsapp, 0, 2) === '62' ) {
		$whatsapp = '0' . substr($whatsapp, 2);
	}


	/**
	 * Check if ref query exist
	 */
    if( $linkReference == '' ) {
        $linkReference = null;
    }

	/**
	 * BEGIN
	 * Project: ADS EXCEPTION WITH OTHER DUTA
	 * @since 30 Mar 23
	 */
	$dutaAdsWA = false;
	if( $linkReference !== null ) {

		// Please manually syncron with this reference: donasiaja-typ.php
		$exceptionDuta = array( 'meisya', 'tisna', 'fadhilah', 'fina', 'safina', 'yusuf', 'husna' );
		if( in_array( $linkReference, $exceptionDuta ) === true ) {
			$dutaAdsWA = $linkReference;	// used cs rotator order logic
			$linkReference = null;
			
			$exceptionDutaID = array( 'meisya' => 10, 'tisna' => 9, 'fadhilah' => 9, 'fina' => 11, 'safina' => 11, 'yusuf' => 2, 'husna' => 3 );
			$dutaAdsWA = $exceptionDutaID[$dutaAdsWA];
		}

	}


    // handling char
    $name = substr($name, 0, 120);
    $whatsapp = substr($whatsapp, 0, 15);
    $email = substr($email, 0, 60);

    // General Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="text_f1" or type="app_name" or type="currency" or type="ipaymu_mode" or type="ipaymu_va" or type="ipaymu_apikey" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_message" or type="telegram_bot_token" or type="telegram_send_to" or type="telegram_on" or type="wanotif_on" or type="wanotif_followup1_on" or type="tripay_mode" or type="tripay_apikey" or type="tripay_privatekey" or type="tripay_merchant" or type="tripay_apikey_sandbox" or type="tripay_privatekey_sandbox" or type="tripay_merchant_sandbox" or type="midtrans_mode" or type="midtrans_serverkey" or type="midtrans_clientkey" or type="midtrans_merchant" or type="midtrans_serverkey_sandbox" or type="midtrans_clientkey_sandbox" or type="midtrans_merchant_sandbox" or type="email_send_to" or type="email_on" or type="fundraiser_on" or type="fundraiser_commission_on" or type="fundraiser_commission_type" or type="fundraiser_commission_percent" or type="fundraiser_commission_fixed" or type="tripay_qris" or type="wanotif_apikey_cs" or type="telegram_send_to_duta" ORDER BY id ASC');
    $text_f1 			= $query_settings[0]->data;
    $app_name 			= $query_settings[1]->data;

    $currency 			= $query_settings[2]->data;
    $ipaymu_mode 		= $query_settings[3]->data;
    $ipaymu_va 			= $query_settings[4]->data;
    $ipaymu_apikey 		= $query_settings[5]->data;
    
    $wanotif_url 		= $query_settings[6]->data;
    $wanotif_apikey 	= $query_settings[7]->data;
    $wanotif_message 	= $query_settings[8]->data;
    $telegram_bot_token	= $query_settings[9]->data;
    $telegram_send_to	= $query_settings[10]->data;
    $telegram_on 		= $query_settings[11]->data;
    $wanotif_on			= $query_settings[12]->data;
    $wanotif_followup1_on	= $query_settings[13]->data;

    $tripay_mode 		= $query_settings[14]->data;
    $tripay_apikey 		= $query_settings[15]->data;
    $tripay_privatekey 	= $query_settings[16]->data;
    $tripay_merchant 	= $query_settings[17]->data;
    $tripay_apikey_sandbox 		= $query_settings[18]->data;
    $tripay_privatekey_sandbox 	= $query_settings[19]->data;
    $tripay_merchant_sandbox 	= $query_settings[20]->data;

    $midtrans_mode 		= $query_settings[21]->data;
    $midtrans_serverkey = $query_settings[22]->data;
    $midtrans_clientkey = $query_settings[23]->data;
    $midtrans_merchant 	= $query_settings[24]->data;
    $midtrans_serverkey_sandbox = $query_settings[25]->data;
    $midtrans_clientkey_sandbox = $query_settings[26]->data;
    $midtrans_merchant_sandbox 	= $query_settings[27]->data;

    $email_send_to	= $query_settings[28]->data;
    $email_on 		= $query_settings[29]->data;

    $fundraiser_on 	= $query_settings[30]->data;
    $fundraiser_commission_on 	= $query_settings[31]->data;
    $fundraiser_commission_type = $query_settings[32]->data;
    $fundraiser_commission_percent = $query_settings[33]->data;
    $fundraiser_commission_fixed = $query_settings[34]->data;

    $tripay_qris = $query_settings[35]->data;
    $wanotif_apikey_cs = $query_settings[36]->data;
	
    $telegram_send_to_duta = $query_settings[37]->data;

    // Campaign Settings
    $campaign_setting = $wpdb->get_results('SELECT notification_status, wanotif_message, fundraiser_setting, fundraiser_on,  fundraiser_commission_on, fundraiser_commission_type, fundraiser_commission_percent, fundraiser_commission_fixed, general_status, allocation_title, allocation_others_title, cs_rotator, wanotif_device from '.$table_name3.' where campaign_id="'.$campaign_id.'" ')[0];
    $notification_status 		= $campaign_setting->notification_status;
    $wanotif_message_on_form 	= $campaign_setting->wanotif_message;
    $c_fundraiser_setting 		= $campaign_setting->fundraiser_setting;
    $c_fundraiser_on 				 = $campaign_setting->fundraiser_on;
    $c_fundraiser_commission_on 	 = $campaign_setting->fundraiser_commission_on;
    $c_fundraiser_commission_type    = $campaign_setting->fundraiser_commission_type;
    $c_fundraiser_commission_percent = $campaign_setting->fundraiser_commission_percent;
    $c_fundraiser_commission_fixed   = $campaign_setting->fundraiser_commission_fixed;
    $c_general_status 				 = $campaign_setting->general_status;
    $allocation_title 				 = $campaign_setting->allocation_title;
    $allocation_others_title 		 = $campaign_setting->allocation_others_title;
    $cs_rotator   					 = $campaign_setting->cs_rotator;
    $wanotif_device   				 = $campaign_setting->wanotif_device;
    
    if($c_general_status=='1'){     //1 untuk donasi, 0 untuk zakat
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


    // ========== HIDE Normalize cs rotator di campaign ================
    // $today_cs = date('Y-m-d');
    // $filternya_cs = "and a.created_at BETWEEN '$today_cs 00:00' AND '$today_cs 23:59'";
    // $donasi_normalize = $wpdb->get_results("SELECT a.cs_id FROM $table_name a
    // WHERE a.campaign_id='$campaign_id' and a.whatsapp='$whatsapp' and a.cs_id != '' $filternya_cs ");
    // if($donasi_normalize!=null){
    	
    // 	$cs_id_normalize = $donasi_normalize[0]->cs_id;
    // 	if($cs_rotator!=''){
    //         $data_cs = json_decode($cs_rotator, true);
    //         $jumlah_cs = $data_cs['jumlah'];
    //     }else{
    //         $jumlah_cs = 0;
    //     }
    //     if($jumlah_cs>=1){
    //     	foreach ($data_cs['data'] as $key => $value) {
    //         	if($cs_id_normalize==$value[0]){
    //         		$cs_id = $value[0];
    //         		break;
    //         	}
    //         }
    //     }
    // }
	

	/**
	 * ANTI-REPEAT ORDER BEFORE 1 DAY
	 * Updated on Friday, 20 Jan 2023
	 * 
	 * Return TRUE apabila sdh order lebih dari sehari
	 * 		  FALSE apabila blm order lebih dari sehari
	 */
	$j_row2 = $wpdb->get_results(" SELECT invoice_id, created_at FROM `ympb2020_dja_donate` WHERE whatsapp='".$whatsapp."' AND campaign_id='".$campaign_id."' ORDER BY `ympb2020_dja_donate`.`created_at` DESC ")[0];
	$thistime = $j_row2->created_at;
	if ( $thistime != '' ) {
		$timeint = new TimeInterval();	//declare object
		$antiRepeat = $timeint->jsh_timeInterval($thistime);
	} else {
		$antiRepeat = true;
	}

	if ( $antiRepeat == true ) {

	if ($debug_mode!='josh') {

        if( ! isset( $linkReference ) ) { // null value considered as not set

            //============= CS ROTATOR BY JOSH ===============
        
            $table_now = $table_name;
            $sql_condition = " WHERE whatsapp='" . $whatsapp . "'";
        
            $j_row = "SELECT repeat_no, cs_id FROM $table_now $sql_condition ORDER BY `ympb2020_dja_donate`.`id` DESC ";
            $j_query = $wpdb->get_row($j_row);
                
    
            /**
             * cek sudah pernah donasi atau belum
             * 
             * BELUM PERNAH DONASI SEBELUMNYA
             */
            if($j_query==null) {

				if( $dutaAdsWA !== false ) {

					$cs_id = $dutaAdsWA;
					$repeat_or_new = 'Baru (WA)';

				} else {

					$get_cs = $wpdb->get_results("SELECT j_value FROM `ympb2020_josh_cs_meta` WHERE property='last_cs_rotate'");
							
					$last_cs_rotate = $get_cs[0]->j_value;           //last cs rotate ready
					$take_cs = 'cs_id_' . $last_cs_rotate;
							
			
					$req1 = "SELECT `j_value` FROM `ympb2020_josh_cs_meta` WHERE property='" . $take_cs . "' ";
					$get_cs2 = $wpdb->get_results($req1);
					$user_id_cs = $get_cs2[0]->j_value;   //id cs siap
			
					$cs_id = $user_id_cs;   //clear rotator cs!! == RESULT ==
					$repeat_or_new = 'Baru';

					$repeat_no_now = 1;	// used to insert donate db
					$db_repeat = 'new';		// used to insert donate db
			
					//ambil data diri cs
					// $cs_identity = get_userdata($cs_id);
					// $cs_completename = $cs_identity->first_name . " " . $cs_identity->last_name; //nama cs readable
			
			
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
							
				}
        
            // SUDAH PERNAH ORDER SEBELUMNYA!
            } else {

				$cs_id_exist = $j_query->cs_id;
				$repeat_no_now = intval($j_query->repeat_no) + 1;	// used to insert donate db
				$db_repeat = 'repeat';	// used to insert donate db

				if( $dutaAdsWA !== false ) {

					$cs_id = $dutaAdsWA;
					$repeat_or_new = 'Ulang (WA)';

				} else {

					$cs_id = $cs_id_exist;
					$repeat_or_new = 'Ulang';

				}
            }
            
            
            //misal program diatas tidak bekerja
            if($cs_id==null) {
                $cs_id = 3;				//always Husna
            }
        
        
            // set cs_name
            $cs_name = '';
            if($cs_id>=1){
                $user_info_cs = get_userdata($cs_id);
                if($user_info_cs!=null){
                    if($user_info_cs->last_name==''){
                        $cs_name = $user_info_cs->first_name;
                    }else{
                        $cs_name = $user_info_cs->first_name.' '.$user_info_cs->last_name;
                    }
                }
            }

        } else { // using a ref params (referral)
			$j_row = "SELECT repeat_no FROM $table_name WHERE whatsapp='$whatsapp' ORDER BY id DESC";
            $j_query = $wpdb->get_row($j_row);

			if( $j_query === null ) {
				$repeat_no_now = 1; // used to insert donate db
				$db_repeat = 'new'; // used to insert donate db
			} else {
				$repeat_no_now = intval($j_query->repeat_no) + 1; // used to insert donate db
				$db_repeat = 'repeat'; // used to insert donate db
			}

		}
		
	}



    $ddate = date('y').date('m').date('d');     //generate invoice
    $rand_char = $ddate.d_randomBigString(5);
    $invoice_id = 'INV-'.$rand_char;
    $url_wp = home_url($wp->request);
    $link_notify = $url_wp.'/notify-ipaymu';
    
    $TransactionId = null;
    $payment_qrcode = null;

    $protocols = array('http://', 'http://www.', 'www.', 'https://', 'https://www.');
	$base_url = str_replace($protocols, '', $url_wp);
	// if($email==''){
	// 	$email = 'nomail_'.$rand_char.'@'.$base_url;
	// }

    $HasQRLink = strpos($payment_number, 'http') !== false || strpos($payment_number, 'www.') !== false;
	if($HasQRLink){
		$payment_qrcode = $payment_number;
	}

	// Set PG
	$pg_code = $payment_account;

    if($pg_code=='ipaymu'){
    	
    	// langsung eksekusi pakai ipaymu payment gateway
    	// CHECK METHOD AND CHANNEL

    	// ****************************
		// Payment channel of the payment method
		// ****************************

		// Virtual Account (va) : method
		// -------------------
		// BAG => 'bag'
		// BNI => 'bni'
		// Cimb Niaga => 'cimb'
		// Mandiri => 'mandiri'
		// Muamalat => 'bmi'
		// BRI => 'bri'

		// Bank Transfer (banktransfer) : method
		// -------------------
		// BCA => 'bca'

		// QRIS (qris) : method
		// -------------------
		// linkaja => 'linkaja' : channel

    	$payment_gateway = false;

    	if($payment_method=='instant'){
    		if($payment_number=='qris' || $payment_number=='gopay' || $payment_number=='ovo' || $payment_number=='dana' || $payment_number=='linkaja' || $payment_number=='shopeepay'){
	    		$p_method = 'qris';
	    		$p_channel = 'linkaja';
	    		$payment_gateway = true;
	    	}
    	}

    	if($payment_method=='va'){
    		if($payment_number=='mandiri' || $payment_number=='bni' || $payment_number=='cimb' || $payment_number=='bag' || $payment_number=='bmi' || $payment_number=='bri' || $payment_number=='bsi' || $payment_number=='bca' || $payment_number=='permata'){
    			$p_method = 'va';
	    		if($payment_number=='mandiri'){ $p_channel = 'mandiri';$payment_gateway = true;}
	    		if($payment_number=='bni'){ $p_channel = 'bni';$payment_gateway = true;}
	    		if($payment_number=='cimb'){ $p_channel = 'cimb';$payment_gateway = true;}
	    		if($payment_number=='bag'){ $p_channel = 'bag';$payment_gateway = true;}
	    		if($payment_number=='bmi'){ $p_channel = 'bmi';$payment_gateway = true;}
	    		if($payment_number=='bri'){ $p_channel = 'bri';$payment_gateway = true;}
	    		if($payment_number=='bsi'){ $p_channel = 'bsi';$payment_gateway = true;}
	    		if($payment_number=='bca'){ $p_channel = 'bca';$payment_gateway = true;}
	    		if($payment_number=='permata'){ $p_channel = 'permata';$payment_gateway = true;}
	    	}
    	}

    	if($payment_method=='transfer'){
    		if($payment_number=='bca'){
	    		$p_method = 'banktransfer';
	    		$p_channel = 'bca';
	    		$payment_gateway = true;
	    	}
	    	if($payment_number=='alfamart' || $payment_number=='indomaret'){
	    		if($payment_number=='alfamart'){ $p_channel = 'alfamart';$payment_gateway = true;}
	    		if($payment_number=='indomaret'){ $p_channel = 'indomaret';$payment_gateway = true;}
	    		$p_method = 'cstore';
	    	}
    	}


    	// LAST VALIDATION
    	if($payment_gateway == true){
    		
    		$va      = $ipaymu_va;
		    $secret  = $ipaymu_apikey;

		    if($ipaymu_mode=='1'){
		    	$url = 'https://my.ipaymu.com/api/v2/payment/direct';
		    }else{
		    	$url = 'https://sandbox.ipaymu.com/api/v2/payment/direct';
		    }
		    
		    if($email==''){
				$emailnya = 'nomail_'.$ddate.date('His').'@gmail.com';
			}else{
				$emailnya = $email;
			}

			$check_title = substr("$title_campaign", -1);
			if($check_title==' '){
			    $title_campaign = $title_campaign.'.'; // add last char with .
			}

			$check_name = substr("$name", -1);
			if($check_name==' '){
			    $name = substr_replace($name ,"",-1); // remove last char
			}

			$method = 'POST'; //method

		    // Request Body//
		    $body['name']   = $name;
		    $body['phone']  = $whatsapp;
		    $body['email']  = $emailnya;
		    $body['amount'] = $nominal;
		    $body['notifyUrl']  = $link_notify;
		    $body['expired']    = '24';
		    $body['expiredType']    = 'hours';
		    $body['referenceId']    = $invoice_id;
		    $body['paymentMethod']  = $p_method;
		    $body['paymentChannel'] = $p_channel;
		    $body['comments']    	= $title_campaign;
		    // End Request Body//

		    //Generate Signature
		    // *Don't change this
		    $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
		    $requestBody  = strtolower(hash('sha256', $jsonBody));
		    $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
		    $signature    = hash_hmac('sha256', $stringToSign, $secret);
		    $timestamp    = Date('YmdHis');
		    //End Generate Signature

		    $ch = curl_init($url);
		    $headers = array(
		        'Accept: application/json',
		        'Content-Type: application/json',
		        'va: ' . $va,
		        'signature: ' . $signature,
		        'timestamp: ' . $timestamp
		    );

		    curl_setopt($ch, CURLOPT_HEADER, false);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch, CURLOPT_POST, count($body));
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		    $err = curl_error($ch);
		    $response = curl_exec($ch);
		    curl_close($ch);
		    if($err) {
		        // print_r($err);
		        $response = json_decode( $err, true );
		        $response_payment = $response;

		    } else {
		    	// print_r($response);
		    	$response = json_decode( $response, true );
		    	$response_payment = $response;
		    }

			if(strtolower($response['Message'])=='success'){

				$TransactionId = $response['Data']['TransactionId'];
				$PaymentNo = $response['Data']['PaymentNo'];
				$PaymentName = $response['Data']['PaymentName'];
				$QrImage = $response['Data']['QrImage'];

				// QrValue
				// set payment_number and payment_account
				if(isset($response['Data']['QrImage'])){
					// INSTANT PAYMENT
					$payment_number = $QrImage;
					$payment_account = strtoupper($payment_code).' - '.$PaymentName;
				}else{
					$payment_number = $PaymentNo;
					$payment_account = $PaymentName;

					if($p_method=='va'){
						$payment_account = 'VA '.strtoupper($payment_code).' - '.$PaymentName;
					}
				}
			}
    	}

    	// Handling ipaymu have qrcode or not
	    $paymentHasLink = strpos($payment_number, 'http') !== false || strpos($payment_number, 'www.') !== false;
		if($paymentHasLink){
			$payment_qrcode = $payment_number;
			$payment_number = $payment_number;
		}

    }

   
	// TRIPAY
	$deeplink_urlnya = '';
	if($pg_code=='tripay'){

		if($payment_number=='mandiri' || $payment_number=='bni' || $payment_number=='bri' || $payment_number=='cimb' || $payment_number=='maybank' || $payment_number=='bmi' || $payment_number=='sampoerna' || $payment_number=='sinarmas' || $payment_number=='permata' || $payment_number=='bca' || $payment_number=='bsi' || $payment_number=='danamon'){
			
			if($payment_number=='bmi'){
				$payment_number = 'muamalat';
			}
			if($payment_number=='sinarmas'){
				$payment_number = 'sms';
			}
			if($payment_number=='maybank'){
				$payment_number = 'myb';
			}

			$tripay_method = strtoupper($payment_number).'VA'; // BCAVA, BNIVA, dll

		}

		if($payment_number=='alfamart' || $payment_number=='alfamidi' || $payment_number=='indomaret'){
			$tripay_method = strtoupper($payment_number); // ALFAMART, ALFAMIDI, INDOMARET
		}

		if($tripay_qris==''){$tripay_qris='QRISC';}

		if($payment_number=='qris' || $payment_number=='gopay' || $payment_number=='linkaja'){
    		$tripay_method = $tripay_qris; // QRISC - QRIS Customizable
    	}

		if($payment_number=='shopeepay'){
    		$tripay_method = 'SHOPEEPAY'; // QRIS Shopeepay - jump apps
    	}

		if($payment_number=='ovo'){
    		$tripay_method = 'OVO'; // OVO
    	}

		if($payment_number=='dana'){
    		$tripay_method = 'QRIS2'; //  QRIS2 - QRIS Dana
    	}

    	if($payment_number=='cc'){
    		$tripay_method = strtoupper($payment_number); // CC
    	}

	    if($tripay_mode=='1'){
	    	$tripay_api_url = 'api';
	    	$apiKey = $tripay_apikey;
			$privateKey = $tripay_privatekey;
			$merchantCode = $tripay_merchant;
			$link_callback_tripay = $url_wp.'/callback_tripay';
	    }else{
	    	$tripay_api_url = 'api-sandbox';
	    	$apiKey = $tripay_apikey_sandbox;
			$privateKey = $tripay_privatekey_sandbox;
			$merchantCode = $tripay_merchant_sandbox;
			$link_callback_tripay = $url_wp.'/callback_tripay_sandbox';
	    }


		$merchantRef = $invoice_id;
		$amount = $nominal;
		$method = $tripay_method; //$payment_list['bcava']['code'];

		if($email==''){
			$emailnya = 'nomail_'.$ddate.date('His').'@gmail.com';
		}else{
			$emailnya = $email;
		}

		$dataPayment = [
		  'method'            => $method,
		  'merchant_ref'      => $merchantRef,
		  'amount'            => $amount,
		  'customer_name'     => $name,
		  'customer_email'    => $emailnya,
		  'customer_phone'    => $whatsapp,
		  'order_items'       => [
		    [
		      'sku'       => home_url(),
		      'name'      => $title_campaign,
		      'price'     => $amount,
		      'quantity'  => 1
		    ]
		  ],
		  'callback_url'      => $link_callback_tripay,
		  'return_url'        => null,
		  'expired_time'      => (time()+(24*60*60)), // 24 jam
		  'signature'         => hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privateKey)
		];

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_FRESH_CONNECT     => true,
		  CURLOPT_URL               => "https://tripay.co.id/$tripay_api_url/transaction/create",
		  CURLOPT_RETURNTRANSFER    => true,
		  CURLOPT_HEADER            => false,
		  CURLOPT_HTTPHEADER        => array(
		    "Authorization: Bearer ".$apiKey
		  ),
		  CURLOPT_FAILONERROR       => false,
		  CURLOPT_POST              => true,
		  CURLOPT_POSTFIELDS        => http_build_query($dataPayment)
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		// print_r($response);
		// wp_die();

		$response = json_decode( $response, true );		

		if($response['success']==true){
			$TransactionId = $response['data']['reference'];
			$payment_number = $response['data']['pay_code'];
			$payment_account = $response['data']['payment_name'];

			// Handling TRIPAY have qrcode or not
			if($response['data']['qr_url']!=null){
				$payment_qrcode = $response['data']['qr_url'];
				$payment_number = $response['data']['qr_url'];
			}

			// Handling OVO redirect URL
			if($response['data']['payment_method']=='OVO'){
				$deeplink_urlnya = $response['data']['checkout_url'];
			}

			// Handling SHOPEEPAY redirect URL
			if($response['data']['payment_method']=='SHOPEEPAY'){
				$deeplink_urlnya = $response['data']['checkout_url'];
			}
		}

	}

	$deeplink_url = '';
	if($deeplink_urlnya!=''){
		$deeplink_url = $deeplink_urlnya;
	}


	if($pg_code=='ipaymu' || $pg_code=='tripay' || $pg_code=='midtrans' || $pg_code=='xendit'){
		// nothing
	}else{
		$pg_code = null;
	}

	// MIDTRANS
	if($pg_code=='midtrans'){

		// Configurations
		require_once dirname(__FILE__) . '/library/Midtrans/Config.php';

		// Midtrans API Resources
		require_once dirname(__FILE__) . '/library/Midtrans/Transaction.php';

		// Plumbing
		require_once dirname(__FILE__) . '/library/Midtrans/ApiRequestor.php';
		require_once dirname(__FILE__) . '/library/Midtrans/Notification.php';
		require_once dirname(__FILE__) . '/library/Midtrans/CoreApi.php';
		require_once dirname(__FILE__) . '/library/Midtrans/Snap.php';

		// Sanitization
		require_once dirname(__FILE__) . '/library/Midtrans/Sanitizer.php';

		if($midtrans_mode=='1'){
	    	Midtrans\Config::$isProduction = true;
	    	Midtrans\Config::$serverKey = $midtrans_serverkey;
	    	Midtrans\Config::$isSanitized = true;
	    }else{
	    	Midtrans\Config::$isProduction = false;
	    	Midtrans\Config::$serverKey = $midtrans_serverkey_sandbox;
	    	Midtrans\Config::$isSanitized = true;
	    }

	    // Midtrans\Config::$isSanitized = true;

		$amount = $nominal;
		$nominal_fix = $amount-$unique_number;
		    
		$transaction_details = array(
		    'order_id'    	=> $invoice_id,
		    'gross_amount'  => $amount
		);

		// Populate items
		$items = array(
		    array(
		        'id'       => 'item1',
		        'price'    => $nominal_fix,
		        'quantity' => 1,
		        'name'     => 'Jumlah '.$allocation_title.''
		    ),
		    array(
		        'id'       => 'item2',
		        'price'    => $unique_number,
		        'quantity' => 1,
		        'name'     => 'Kode Unik'
		    ));


		// Populate customer's info
		$customer_details = array(
		    'first_name'       => $name,
		    'last_name'        => "",
		    'email'            => $email,
		    'phone'            => $whatsapp
		);

		$any_error = false;

		if($payment_number=='bri'){

			$payment_account = strtoupper($payment_number). ' VA';
			$payment_type = 'bank_transfer';
			$payment_code = $payment_number;

			// Transaction data to be sent
			$transaction_data = array(
			    'payment_type' => $payment_type,
			    'transaction_details' => $transaction_details,
			    'item_details'        => $items,
			    'customer_details'    => $customer_details,
			    'bank_transfer' => array(
			         'bank' => $payment_code
			    )
			);

			try {
				$response = Midtrans\CoreApi::charge($transaction_data);
				
				if($response->status_code=='201'){
					$TransactionId = $response->transaction_id;

					$reponse_json = json_encode($response);
				    $reponse_array = json_decode($reponse_json, TRUE);
					$payment_number = $reponse_array['va_numbers'][0]['va_number'];

				}
			} catch (Exception $e) {
				// echo 'Caught exception: ',  $e->getMessage(), "\n";
				$any_error = true;
				$message_error = $e->getMessage();
			}

		}

		if($payment_number=='bca' || $payment_number=='bni'){

			$payment_account = strtoupper($payment_number). ' VA';
			$payment_type = 'bank_transfer';
			$payment_code = $payment_number;

			// Transaction data to be sent
			$transaction_data = array(
			    'payment_type' => $payment_type,
			    'transaction_details' => $transaction_details,
			    'item_details'        => $items,
			    'customer_details'    => $customer_details,
			    'bank_transfer' => array(
			         'bank' => $payment_code
			    )
			);

			try { 
				$response = Midtrans\CoreApi::charge($transaction_data);
				
				if($response->status_code=='201'){
					$TransactionId = $response->transaction_id;

					$reponse_json = json_encode($response);
				    $reponse_array = json_decode($reponse_json, TRUE);
					$payment_number = $reponse_array['va_numbers'][0]['va_number'];

				}
			} catch (Exception $e) {
				$any_error = true;
				$message_error = $e->getMessage();
			}

		}

		if($payment_number=='permata'){

			$payment_account = strtoupper($payment_number). ' VA';
			$payment_type = 'permata';
			
			// Transaction data to be sent
			$transaction_data = array(
			    'payment_type' => $payment_type,
			    'transaction_details' => $transaction_details,
			    'item_details'        => $items,
			    'customer_details'    => $customer_details
			);

			try {
				$response = Midtrans\CoreApi::charge($transaction_data);

				if($response->status_code=='201'){
					$TransactionId = $response->transaction_id;
					$payment_number = $response->permata_va_number;
				}

			} catch (Exception $e) {
				$any_error = true;
				$message_error = $e->getMessage();
			}


		}

		if($payment_number=='mandiri'){

			$payment_account = strtoupper($payment_number). ' VA';
			$payment_type = 'echannel';
			
			// Transaction data to be sent
			$transaction_data = array(
			    'payment_type' => $payment_type,
			    'transaction_details' => $transaction_details,
			    'item_details'        => $items,
			    'customer_details'    => $customer_details,
			    'echannel' => array(
			        'bill_info1' => 'Online payment',
				    'bill_info2' => $title_campaign,
			    )
			);

			try {
				$response = Midtrans\CoreApi::charge($transaction_data);

				if($response->status_code=='201'){
					$TransactionId = $response->transaction_id;
					$payment_number = $response->bill_key;
				}

			} catch (Exception $e) {
				$any_error = true;
				$message_error = $e->getMessage();
			}


		}

		if($payment_number=='gopay' || $payment_number=='qris' || $payment_number=='ovo' || $payment_number=='dana' || $payment_number=='linkaja' || $payment_number=='shopeepay'){

			$payment_account = strtoupper($payment_number);
			$payment_type = 'gopay';

			$transaction_data = array(
			    'payment_type' => $payment_type,
			    'transaction_details' => $transaction_details,
			    'item_details'        => $items,
			    'customer_details'    => $customer_details,
			    'gopay' => array(
			        'enable_callback' => true,
			        'callback_url' => 'someapps://callback'
			    )
			);

			try {
				$response = Midtrans\CoreApi::charge($transaction_data);

				if($response->status_code=='201'){
					$TransactionId = $response->transaction_id;

					$reponse_json = json_encode($response);
				    $reponse_array = json_decode($reponse_json, TRUE);
					$qr_code_url = $reponse_array['actions'][0]['url'];
					
					if($payment_number=='gopay'){
						$deeplink_url = $reponse_array['actions'][1]['url'];
					}
					// set value qrcode link
					$payment_qrcode = $qr_code_url;
					$payment_number = $qr_code_url;

				}
			} catch (Exception $e) {
				$any_error = true;
				$message_error = $e->getMessage();
			}
			
		}

		if($payment_number=='alfamart' || $payment_number=='indomaret' ){

			$payment_account = strtoupper($payment_number);
			$payment_type = 'cstore';

			if($payment_number=='alfamart'){
				$transaction_data = array(
				    'payment_type' => $payment_type,
				    'transaction_details' => $transaction_details,
				    'item_details'        => $items,
				    'customer_details'    => $customer_details,
				    'cstore' => array(
				        'store' => 'alfamart',
				        'message' => 'Alfamart - '.$title_campaign,
					    'alfamart_free_text_1' => '',
					    'alfamart_free_text_2' => '',
					    'alfamart_free_text_3' => ''
				    )
				);
			}

			if($payment_number=='indomaret'){
				$transaction_data = array(
				    'payment_type' => $payment_type,
				    'transaction_details' => $transaction_details,
				    'item_details'        => $items,
				    'customer_details'    => $customer_details,
				    'cstore' => array(
				        'store' => 'indomaret',
					    'message' => 'Indomaret - '.$title_campaign
				    )
				);
			}
			
			try {
				$response = Midtrans\CoreApi::charge($transaction_data);

				if($response->status_code=='201'){
					$TransactionId = $response->transaction_id;
					$payment_number = $response->payment_code;
				}
			} catch (Exception $e) {
				$any_error = true;
				$message_error = $e->getMessage();
			}
			
		}

		$transaction_datanya = json_encode($transaction_data);
		$responsenya = json_encode($response);

	}



	// XENDIT

	/**
	 * Set Another Information
	 */
	$user_agent	= $_SERVER['HTTP_USER_AGENT'];
	$country	= $_SERVER['GEOIP_CITY_COUNTRY_CODE'];
	$city		= $_SERVER['GEOIP_CITY'];
	$provider	= $_SERVER['GEOIP_ORGANIZATION'];
	$j_os = donasiaja_getOS();
	$j_ip = donasiaja_getIP();
	$j_browser = donasiaja_getBrowser();
	$j_mobdesk = donasiaja_getMobDesktop();


	// SET TEXT FORMAT
	$date_created = date("Y-m-d H:i:s");

	$data_field = array();
    $data_field[ '{name}' ] 	= $name;
    $data_field[ '{cs_name}' ] 	= $cs_name;
    $data_field[ '{new_repeat}' ] = $repeat_or_new;
    $data_field[ '{new_repeat_c}' ] = strtoupper($repeat_or_new);
    $data_field[ '{whatsapp}' ] = $whatsapp;
	$length_j = strlen($whatsapp);
	if($length_j >= 6) {
		$star_l = 4;
		$real_l = $length_j - $star_l;

		$number_j = substr($whatsapp, 0, $real_l);
		$whatsapp_s = $number_j . "----";
	} else {
		$number_j = substr($whatsapp, 0, 3);
		$whatsapp_s = $number_j . "--";
	}
	$data_field[ '{whatsapp_s}' ] = $whatsapp_s;
    $data_field[ '{email}' ] 	= $email;
    $data_field[ '{comment}' ] 	= $comment;
    $data_field[ '{payment_number}' ] 	= $payment_number;
    $data_field[ '{payment_code}' ] 	= paymentCode($payment_code);
    $data_field[ '{payment_account}' ] 	= $payment_account;
    $data_field[ '{campaign_title}' ] 	= $title_campaign;
    $data_field[ '{invoice_id}' ] 		= $invoice_id;
    $data_field[ '{date}' ] 			= $datenya = date("j F Y - H:i:s",strtotime($date_created));
    $data_field[ '{link_ekuitansi}' ] 	= home_url().'/ekuitansi/'.$invoice_id;
    if($currency=='IDR'){
    	$data_field[ '{total}' ] 	= 'Rp '.number_format($nominal,0,",",".");
    }else{
    	$data_field[ '{total}' ] 	= $nominal;
    }
    $fundraiser_name = '-';
    if($affcode_id!='0'){
		$query_donation = $wpdb->get_results("SELECT b.user_id as fundraiser_id FROM $table_name5 a
	    LEFT JOIN $table_name6 b ON b.id = a.affcode_id 
	    where a.affcode_id='$affcode_id' ORDER BY a.id DESC ")[0];

	    if($query_donation->fundraiser_id!=''){
	        $user_info = get_userdata($query_donation->fundraiser_id);
	        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
	    }
	    $data_field[ '{fundraiser}' ] = $fundraiser_name;
	}else{
		$data_field[ '{fundraiser}' ] = '-';
	}
	$data_field[ '{utm_source}' ]		= $utm_source;
	$data_field[ '{utm_medium}' ]		= $utm_medium;
	$data_field[ '{utm_campaign}' ]		= $utm_campaign;
	$data_field[ '{city}' ]				= $city;
	$data_field[ '{provider}' ]			= $provider;
	$data_field[ '{ref}' ]			    = $linkReference;



	if ($debug_mode=='josh') {
		$j_table = $wpdb->prefix . "dja_donate_debug";
	} else {
		$j_table = $wpdb->prefix . "dja_donate";
	}

	/**
	 * Project: ADS EXCEPTION WITH OTHER DUTA
	 */
	if( $dutaAdsWA !== false ) {

		$refVal = $_POST['datanya'][19];

	} else {

		$refVal = $linkReference;

	}

	/**
	 * Insert for donors data
	 * @since 27 Apr 2023
	 */
	$add_reason = ( $refVal != null ) ? 'organic' : 'web_donate';
	$add_reason = ( $utm_source == 'ig' || $utm_source == 'fb' || $utm_source == 'an' || $utm_source == 'google' ) ? 'ads' : $add_reason;
	$table_donors = $wpdb->prefix . 'josh_donors';

	if( $refVal != null ) {
		$owned_by = $refVal;
	} else {
		$cs_array = array( '3' => 'husna', '9' => 'fadhilah', '10' => 'meisya', '11' => 'safina');
		if( strval($cs_id) != '0' ) {
			$owned_by = $cs_array[strval($cs_id)];
		} else {
			$owned_by = '0';
		}
	}

	$insert_donors = $wpdb->insert(
		$table_donors,
        array(
            'whatsapp'      => $whatsapp,
			'name'			=> $name,
            'owned_by'      => $owned_by,
            'since'         => $date_created,
            'add_reason'    => $add_reason,
            'city'          => $city
        )
	);


	// CREATE DONASI
    $id_login = wp_get_current_user()->ID;
	$submit_db = $wpdb->insert(
        $j_table, //table
        array(
            'campaign_id' 	=> substr($campaign_id, 0, 15),
            'invoice_id' 	=> substr($invoice_id, 0, 32),
            'user_id' 		=> $id_login,
            'name' 			=> substr($name, 0, 120),
            'whatsapp' 		=> substr($whatsapp, 0, 15),
			'repeat_sts'	=> substr($db_repeat, 0, 50),
			'repeat_no'		=> $repeat_no_now,
            'email' 		=> substr($email, 0, 60),
            'comment' 		=> $comment,
            'anonim' 		=> $anonim,
            // 'payment_method'=> null,
            // 'payment_code'	=> null,
            // 'payment_number'=> null,
            // 'payment_qrcode'=> null,
            // 'payment_account'=> null,
            'unique_number' => $unique_number,
            'nominal' 		=> $nominal,
            'main_donate' 	=> $main_donate,
            'info_donate' 	=> $info_donate,
            'status' 		=> 0,
            'cs_id' 		=> $cs_id,
            // 'f0' 			=> null,
            // 'f1' 			=> null,
            // 'f2' 			=> null,
            // 'f3' 			=> null,
            // 'f4' 			=> null,
            // 'f5' 			=> null,
            'payment_trx_id'=> $TransactionId,
            // 'payment_at'	=> null,
            'deeplink_url'	=> $deeplink_url,
            'payment_gateway'=> $pg_code,
            'created_at' 	=> $date_created,
            'ref'           => $refVal,
			'utm_source'	=> substr($utm_source, 0, 100),
			'utm_medium'	=> substr($utm_medium, 0, 100),
			'utm_campaign'  => substr($utm_campaign, 0, 100),
			'utm_term'		=> substr($utm_term, 0, 100),
			'utm_content'   => substr($utm_content, 0, 100),
			'user_agent'	=> $user_agent,
			'country'		=> substr($country, 0, 30),
			'city'			=> substr($city, 0, 50),
			'provider'		=> substr($provider, 0, 100),
			'operating_system' => substr($j_os, 0, 50),
			'ip_address'	=> substr($j_ip, 0, 30),
			'browser'		=> substr($j_browser, 0, 200),
			'mobdesk'		=> substr($j_mobdesk, 0, 50)
		)
    );

    $donasi_id = $wpdb->insert_id;


	//Jika Gagal Insert!
	if ($submit_db == false) {
		$mytable = $wpdb->prefix. 'josh_faildonate';
		$datainsert = array(
            'campaign_id' 	=> $campaign_id,
            'invoice_id' 	=> $invoice_id,
            'user_id' 		=> $id_login,
            'name' 			=> $name,
            'whatsapp' 		=> $whatsapp,
            'email' 		=> $email,
            'comment' 		=> $comment,
            'anonim' 		=> $anonim,
            'unique_number' => $unique_number,
            'nominal' 		=> $nominal,
            'main_donate' 	=> $main_donate,
            'info_donate' 	=> $info_donate,
            'status' 		=> 0,
            'cs_id' 		=> $cs_id,
            'payment_trx_id'=> $TransactionId,
            'deeplink_url'	=> $deeplink_url,
            'payment_gateway'=> $pg_code,
            'created_at' 	=> $date_created,
            'ref'           => $linkReference,
			'utm_source'	=> $utm_source,
			'utm_medium'	=> $utm_medium,
			'utm_campaign'  => $utm_campaign,
			'utm_term'		=> $utm_term,
			'utm_content'   => $utm_content,
			'user_agent'	=> $user_agent,
			'country'		=> $country,
			'city'			=> $city,
			'provider'		=> $provider,
			'operating_system' => $j_os,
			'ip_address'	=> $j_ip,
			'browser'		=> $j_browser,
			'mobdesk'		=> $j_mobdesk
		);
		$datainsert2 = json_encode($datainsert);

		$wpdb->insert(
			$mytable,
			array(
				'error_time'	=> date("Y-m-d H:i:s"),
				'data'			=> $datainsert2
			)
		);
		// $answer = '{"inv":"' . $invoice_id . '","statDb":"false"}';
		$answer = array('inv'=>$invoice_id, 'statDb'=>'false');

	} elseif ($submit_db == 1) {
		// $answer = '{"inv":"' . $invoice_id . '","statDb":"true"}';
		$answer = array('inv'=>$invoice_id, 'statDb'=>'true');
	} else {
		// $answer = '{"inv":"' . $invoice_id . '","statDb":"unknown"}';
		$answer = array('inv'=>$invoice_id, 'statDb'=>'unknown');
	}

    if($affcode_id!='0'){
    	// check mode fundraising
    	if($fundraiser_on=='1'){
			if($c_fundraiser_setting=='0' || $c_fundraiser_setting==null){
				
				$add_commission = true;
				// ikut default settingan utama
				if($fundraiser_commission_on=='1'){
		    		if($fundraiser_commission_type=='0'){
		    			if($fundraiser_commission_percent=='' || $fundraiser_commission_percent==null){
		    				$fundraiser_commission_percent = 0;
		    			}
		    			$nominal_commission = round($nominal*($fundraiser_commission_percent/100));
		    		}else{
		    			if($fundraiser_commission_fixed=='' || $fundraiser_commission_fixed==null){
		    				$fundraiser_commission_fixed = 0;
		    			}
		    			$nominal_commission = round($fundraiser_commission_fixed);
		    		}
		    	}else{
		    		$nominal_commission = 0;
		    	}

			}else if($c_fundraiser_setting=='1'){

				if($c_fundraiser_on=='1'){

					$add_commission = true;
					if($c_fundraiser_commission_on=='1'){

						// ikut settingan di campaign
						if($c_fundraiser_commission_on=='1'){
				    		if($c_fundraiser_commission_type=='0'){
				    			$nominal_commission = round($nominal*($c_fundraiser_commission_percent/100));
				    		}else{
				    			$nominal_commission = round($c_fundraiser_commission_fixed);
				    		}
				    	}else{
				    		$nominal_commission = 0;
				    	}

					}else{
						$nominal_commission = 0;
					}
					
				}else{
					$add_commission = false;
				}
			}else{
				$add_commission = false;
			}
		}else{
			$add_commission = false;
		}
		
		if($add_commission==true){

	    	$wpdb->insert( $table_name5,
		        array(
		            'donate_id' 	=> $donasi_id,
		            'campaign_id' 	=> $campaign_id,
		            'affcode_id' 	=> $affcode_id,
		            'payout_status' => 0,
		            'nominal_commission' => $nominal_commission,
		            'created_at' 	=> date("Y-m-d H:i:s"),
		            'updated_at' 	=> date("Y-m-d H:i:s")),
		        array('%s', '%s') //data format         
		    );

		}
    	
    }

    // CREATE LOG Payment
    if($pg_code=='midtrans'){
		
		$data_log = $responsenya;
		
    	if($response->status_code=='201'){
			$data_status = 1;
			$data_message = 'success';
		}else{
			if($any_error==false){
				$data_status = 0;
				$data_message = $response->status_message;
			}else{
				$data_status = 0;
				$data_message = 'failed';
				$data_log = $message_error;
			}
		}

	    $wpdb->insert(
	        $table_name4, //table
	        array(
	            'id_donate' 	=> $donasi_id,
	            'hit'			=> $transaction_datanya,
	            'status' 		=> $data_status,
	            'message' 		=> $data_message,
	            'log' 			=> $data_log,
	            'created_at' 	=> date("Y-m-d H:i:s")
	        ),
	        array('%s', '%s') //data format         
	    );
    }

    if($pg_code=='ipaymu'){

		$data_status  = $response_payment['Status'];
    	$data_message = $response_payment['Message'];
    	$data_log = json_encode($response_payment);
    	$data_log = str_replace('\/', '/', $data_log);

	    $wpdb->insert(
	        $table_name4, //table
	        array(
	            'id_donate' 	=> $donasi_id,
	            'hit'			=> $jsonBody,
	            'status' 		=> $data_status,
	            'message' 		=> $data_message,
	            'log' 			=> $data_log,
	            'created_at' 	=> date("Y-m-d H:i:s")
	        ),
	        array('%s', '%s') //data format         
	    );
    }

    if($pg_code=='tripay'){

    	if($response['success']==true){
			$data_status = 1;
			$data_message = 'success';
		}else{
			$data_status = 0;
			$data_message = $response['message'];
		}

		$dataPayment = json_encode($dataPayment);
		$dataPayment = str_replace('\/', '/', $dataPayment);
		$data_log = json_encode($response);
		$data_log = str_replace('\/', '/', $data_log);

    	$wpdb->insert(
	        $table_name4, //table
	        array(
	            'id_donate' 	=> $donasi_id,
	            'hit'			=> $dataPayment,
	            'status' 		=> $data_status,
	            'message' 		=> $data_message,
	            'log' 			=> $data_log,
	            'created_at' 	=> date("Y-m-d H:i:s")
	        ),
	        array('%s', '%s') //data format         
	    );
    }
    
    
    // WANOTIF
	if($wanotif_apikey!='' && $wanotif_on=='1'){
		// SET PHONE
		if($whatsapp!=''){

			$phone = djaPhoneFormat($whatsapp);
		
			if($notification_status=='1'){
				// message diambil dari settingan campaign
				$messagenya = $wanotif_message_on_form;

				// device diambil dari settingan campaign
				if($wanotif_device=='1'){
					if($wanotif_apikey_cs!=''){
                        $data_cs = json_decode($wanotif_apikey_cs, true);
                        $jumlah_cs = $data_cs['jumlah'];
                    }else{
                        $jumlah_cs = 0;
                    }
                    
                    if($jumlah_cs>=1){
                    	foreach ($data_cs['data'] as $key => $value) {
	                    	if($cs_id==$value[0]){
	                    		$wanotif_apikey = $value[1];
	                    		break;
	                    	}
	                    }
                    }
				}

			}else{
				if($wanotif_followup1_on=='1'){
					
					// UPDATE FOLLOWUP 1 STATUS = 1
				    $wpdb->update( $table_name, array( 'f1' => 1 ), array('id' => $donasi_id), array('%s'), array('%s'));

				    // pakai message followup 1
					$messagenya = $text_f1;
				}else{
					// pakai settingan default wanotif
					$messagenya = $wanotif_message;
				}
			}


			// Update data message dari data sumbit donasi
			$messagenya = strtr($messagenya, $data_field);
			$messagenya = str_replace('*'.$payment_qrcode.'*', '', $messagenya);
			$messagenya = str_replace($payment_qrcode, '', $messagenya);

			$bHasLink = strpos($payment_qrcode, 'http') !== false || strpos($payment_qrcode, 'www.') !== false;
			$bHasIPaymu = strpos($payment_qrcode, 'ipaymu.com') !== false;

			if($bHasLink){

				$content = file_get_contents($payment_qrcode);

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

			}else{

				$url = $wanotif_url.'/send';

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

	}

	// WhatsApp Business Platform

	
	// $debug_mode = 'josh';
	if ($debug_mode!='josh') {

		if( $linkReference != null ) {
			$telegram_send_to = $telegram_send_to_duta;
		}

        // if( ! isset($linkReference) ) { // jika bukan dari link duta
            
            // TELEGRAM
            if($telegram_on=='1'){
                $token = $telegram_bot_token;
                $telegram_send_to = json_decode($telegram_send_to);
        
                foreach($telegram_send_to as $key => $value) {
        
                    $message_tele = $value->message;
                    $message_tele = strtr($message_tele, $data_field);
                    $channel = $value->channel;
                    
                    if (strpos($channel, ',') !== false ) {
                        $array_channel  = (explode(",", $channel));
                        foreach ($array_channel as $values){
                            $channel_id = $values;
                            $send = donasiaja_send2tg($token, $channel_id, $message_tele);//----------> AKTIFKAN
                            // echo $send;
                        }
                    }else{
                        $channel_id = $channel;
                        donasiaja_send2tg($token, $channel_id, $message_tele);//----------> AKTIFKAN
                    }
                }
            }
        
            // EMAIL
            if($email_on=='1'){
                $send_email_status = josh_send_email($data_field, $email_send_to, $user_info_cs, $invoice_id);//----------> AKTIFKAN
            }

        // }
		$waba ='';

	}
	// else {

	// }

	/**
	 * WABA Notification
	 * @since 10 April 2023
	 * 
	 * modified to keep send if ref params exist
	 * both for dutaAds or dutanonAds
	 * @since 19 Apr 2023
	 */
	$data = array();
	if( $cs_id > 0 ) { // determine phone to
		$phone_number = get_the_author_meta( 'phone_number', $cs_id ); // example phone number
		if ( substr( $phone_number, 0, 1 ) === '0' ) {
			$phone_number = '62' . substr( $phone_number, 1 );
		}
	} elseif ($dutaAdsWA != null) { // for dutaAds
		$table_duta = $wpdb->prefix . 'josh_duta';

		$query = "SELECT whatsapp FROM $table_duta WHERE code='$dutaAdsWA'";
		$phone_number = $wpdb->get_row( $query )->whatsapp;

		if( substr( $phone_number, 0, 1 ) === '0' ) {
			$phone_number = '62' . substr( $phone_number, 1 );
		}
	} else if($linkReference != null) { // for dutanonAds
		$table_duta = $wpdb->prefix . 'josh_duta';

		$query = "SELECT whatsapp FROM $table_duta WHERE code='$linkReference'";
		$phone_number = $wpdb->get_row( $query )->whatsapp;

		if( substr( $phone_number, 0, 1 ) === '0' ) {
			$phone_number = '62' . substr( $phone_number, 1 );
		}
	} else {
		$phone_number = '';
	}

	$data['phone_to'] = $phone_number;
	$data['new_repeat_c'] = ($repeat_or_new == '') ? ' ' : strtoupper($repeat_or_new);
	$data['program'] = $title_campaign;
	$data['invoice'] = $invoice_id;
	$data['name'] = $name;
	$data['cs_name'] = ($cs_name === null) ? '-' : $cs_name;
	$data['datetime'] = date('j F Y - H:i:s', strtotime($date_created));
	$waba_comment = $comment === '' ? '-' : preg_replace('/\r\n|\r|\n/', '\n', $comment);//$comment;
	$data['doa'] = $waba_comment;
	$data['utm_source'] = ($utm_source == '') ? '-' : $utm_source;

	$waba = joshfunction_waba_order_baru( $data ); // -----------> AKTIFKAN!
	$answer['waba'] = $waba;


	// IF from ANTI-REPEAT ORDER search term '$timeint = new jsh_TimeInterval()'
	} else {
		// $answer = '{"inv":"'.$j_row2->invoice_id.'", "statDb":"true"}';
		$answer = array('inv'=>$j_row2->invoice_id, 'statDb'=>'true');
		// $answer = '{"inv":"'.$invoice_id.'", "antiRepeat":"true"}';
	}

	header( 'Content-Type: application/json' );
	echo json_encode($answer);

    wp_die();
}
add_action( 'wp_ajax_djafunction_submit_donasi', 'djafunction_submit_donasi' );
add_action( 'wp_ajax_nopriv_djafunction_submit_donasi', 'djafunction_submit_donasi' );

function joshfunction_waba_order_baru( $data ) {
	global $wpdb;
	$table_log_fail = $wpdb->prefix . 'josh_waba_send_fail';
	$table_donate = $wpdb->prefix . 'dja_donate';

	$version = 'v16.0';
	$phone_from = '106104445787569';
	$header = array(
		'Content-Type: application/json',
		'Authorization: Bearer EAAB4PRi3wKEBAAZAZABGgXFfj0Nwl3zkfh2mZCXZBpeZB7CKm3gsIT3GdHem3TLhoZA7a9nUrVCBt88EKbMXGv70FfmlGzuQxoBAzPpneYxUqCj6alLbGi0ZBOabaPn1SegljR18LPym58weQINc2CcCmdhYhN6EZCkpSO0dg2MbncTFWfA5ImQkwhLoWRTO9tZBKIxBtq9ZBE0gZDZD'
	);
	$postBody = array(
		'messaging_product' => 'whatsapp',
		'recipient_type' => 'individual',
		'to' => $data['phone_to'],
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
							'text' => $data['new_repeat_c']
						)
					)
				),
				array(
					'type' => 'body',
					'parameters' => array(
						array(
							'type' => 'text',
							'text' => $data['program']
						),
						array(
							'type' => 'text',
							'text' => $data['invoice']
						),
						array(
							'type' => 'text',
							'text' => $data['name']
						),
						array(
							'type' => 'text',
							'text' => $data['cs_name']
						),
						array(
							'type' => 'text',
							'text' => strval($data['datetime'])
						),
						array(
							'type' => 'text',
							'text' => $data['doa']
						),
						array(
							'type' => 'text',
							'text' => $data['utm_source']
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
							'text' => $data['invoice']
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

	// send log to db if send fail
	if( ! isset( $response_decode->contacts) ) {
		$wpdb->insert(
			$table_log_fail,
			array(
				'msg_fail' => $response,
				'type'	   => 'order_baru'
			)
		);
	} else {
		$wpdb->update(
			$table_donate,
			array(
				'wam_id_order'	=> $response_decode->messages[0]->id
			),
			array(
				'invoice_id' => $data['invoice']
			)
		);
	}

	return $response_decode;
	// return $postBody;
}


function joshfunction_manual_submitdonasi() {
	global $wpdb;
	$table_donate = $wpdb->prefix.'dja_donate';
	$table_settings = $wpdb->prefix.'dja_settings';
	$table_campaign = $wpdb->prefix.'dja_campaign';


	
	
	$myarray = array();
	foreach ($_POST['datanya'] as $k => $v) {
		if($v == '') {
			$myarray[$k] = null;
		} else {
			$myarray[$k] = $v;
		}
	}
	
	$campaign_id = $myarray['campaign_id'];
	$invoice_id = $myarray['invoice_id'];
	$repeat_new = $myarray['repeatnew'];
	$user_id = $myarray['user_id'];
	$name = $myarray['name'];
	$whatsapp = $myarray['whatsapp'];
	$email = $myarray['email'];
	$comment = $myarray['comment'];
	$anonim = $myarray['anonim'];
	$unique_number = $myarray['unique_number'];
	$nominal = $myarray['nominal'];
	$main_donate = $myarray['main_donate'];
	$info_donate = $myarray['info_donate'];
	$cs_id = $myarray['cs_id'];
	$created_at = $myarray['created_at'];
	$utm_source = $myarray['utm_source'];
	$utm_medium = $myarray['utm_medium'];
	$utm_campaign = $myarray['utm_campaign'];
	$utm_term = $myarray['utm_term'];
	$utm_content = $myarray['utm_content'];
	$user_agent = $myarray['user_agent'];
	$country = $myarray['country'];
	$city = $myarray['city'];
	$provider = $myarray['provider'];
	$operating_system = $myarray['operating_system'];
	$ip_address = $myarray['ip_address'];
	$browser = $myarray['browser'];
	$mobdesk = $myarray['mobdesk'];



	//Insert Database
	$insert_row = $wpdb->insert(
		$table_donate,
		array(
			'campaign_id'		=> $campaign_id,
			'invoice_id'		=> $invoice_id,
			'user_id'			=> $user_id,
			'name'				=> $name,
			'whatsapp'			=> $whatsapp,
			'email'				=> $email,
			'comment'			=> $comment,
			'anonim'			=> $anonim,
			'unique_number'		=> $unique_number,
			'nominal'			=> $nominal,
			'main_donate'		=> $main_donate,
			'info_donate'		=> $info_donate,
			'cs_id' 			=> $cs_id,
			'created_at'		=> $created_at,
			'utm_source'		=> $utm_source,
			'utm_medium'		=> $utm_medium,
			'utm_campaign'		=> $utm_campaign,
			'utm_term'			=> $utm_term,
			'utm_content'		=> $utm_content,
			'user_agent'		=> $user_agent,
			'country'			=> $country,
			'city'				=> $city,
			'provider'			=> $provider,
			'operating_system'	=> $operating_system,
			'ip_address'		=> $ip_address,
			'browser'			=> $browser,
			'mobdesk'			=> $mobdesk
			)
		);
		
			
	//Get CS Data
	$cs_name = '';
	if($cs_id>=1){
		$user_info_cs = get_userdata($cs_id);
		if($user_info_cs!=null){
			if($user_info_cs->last_name==''){
				$cs_name = $user_info_cs->first_name;
			}else{
				$cs_name = $user_info_cs->first_name.' '.$user_info_cs->last_name;
			}
		}
	}
			
			
			
	//Get Data From Databases
	$row_campaign = $wpdb->get_results("SELECT title FROM ".$table_campaign." WHERE campaign_id='".$campaign_id."'")[0];
	$row_settings = $wpdb->get_results('SELECT data FROM '.$table_settings.' where type="text_f1" or type="app_name" or type="currency" or type="ipaymu_mode" or type="ipaymu_va" or type="ipaymu_apikey" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_message" or type="telegram_bot_token" or type="telegram_send_to" or type="telegram_on" or type="wanotif_on" or type="wanotif_followup1_on" or type="tripay_mode" or type="tripay_apikey" or type="tripay_privatekey" or type="tripay_merchant" or type="tripay_apikey_sandbox" or type="tripay_privatekey_sandbox" or type="tripay_merchant_sandbox" or type="midtrans_mode" or type="midtrans_serverkey" or type="midtrans_clientkey" or type="midtrans_merchant" or type="midtrans_serverkey_sandbox" or type="midtrans_clientkey_sandbox" or type="midtrans_merchant_sandbox" or type="email_send_to" or type="email_on" or type="fundraiser_on" or type="fundraiser_commission_on" or type="fundraiser_commission_type" or type="fundraiser_commission_percent" or type="fundraiser_commission_fixed" or type="tripay_qris" or type="wanotif_apikey_cs" ORDER BY id ASC');
	
	
	$title_campaign = $row_campaign->title;
	$email_send_to = $row_settings[28]->data;
	$email_on = $row_settings[29]->data;
	$telegram_on = $row_settings[11]->data;
	$telegram_bot_token = $row_settings[9]->data;
	$telegram_send_to = $row_settings[10]->data;


	//Set Text Format
	$data_field = array();
	$data_field[ '{name}' ] 	= $name;
	$data_field[ '{cs_name}' ] 	= $cs_name;
	$data_field[ '{new_repeat}' ] = $repeat_new;
	$data_field[ '{new_repeat_c}' ] = strtoupper($repeat_new);
	$data_field[ '{whatsapp}' ] = $whatsapp;
	$length_j = strlen($whatsapp);
	if($length_j >= 6) {
		$star_l = 4;
		$real_l = $length_j - $star_l;
		$number_j = substr($whatsapp, 0, $real_l);
		$whatsapp_s = $number_j . "----";
	} else {
		$number_j = substr($whatsapp, 0, 3);
		$whatsapp_s = $number_j . "--";
	}
	$data_field[ '{whatsapp_s}' ] = $whatsapp_s;
	$data_field[ '{email}' ] 	= $email;
	$data_field[ '{comment}' ] 	= $comment;
	$data_field[ '{campaign_title}' ] 	= $title_campaign;
	$data_field[ '{invoice_id}' ] 		= $invoice_id;
	$data_field[ '{date}' ] 			= $created_at;
	$data_field[ '{link_ekuitansi}' ] 	= home_url().'/ekuitansi/'.$invoice_id;
	$data_field[ '{total}' ] 	= 'Rp '.number_format($nominal,0,",",".");
		
			
		
	//Send Notifications
	if ($insert_row == 1) {

		if($telegram_on=='1'){
			$token = $telegram_bot_token;
			$telegram_send_to = json_decode($telegram_send_to);
	
			foreach($telegram_send_to as $key => $value) {
	
				$message_tele = $value->message;
				$message_tele = strtr($message_tele, $data_field);
				$channel = $value->channel;
				
				if (strpos($channel, ',') !== false ) {
					$array_channel  = (explode(",", $channel));
					foreach ($array_channel as $values){
						$channel_id = $values;
						$sendtele = donasiaja_send2tg($token, $channel_id, $message_tele);
						// echo $send;
					}
				}else{
					$channel_id = $channel;
					$sendtele = donasiaja_send2tg($token, $channel_id, $message_tele);
				}
			}
		}

		if($email_on=='1') {
			$sendemail = josh_send_email($data_field, $email_send_to, $user_info_cs, $invoice_id);		//email
		}
	}


	$feedback_data = json_encode($data_field);
	$myarray_json = json_encode($myarray);
	$post_data = json_encode($_POST['datanya']);
	$final = '{"statusDb": "'.$insert_row.'", "statusTele": "'.$telegram_on.'", "statusEmail": "'.$sendemail.'"}';
	echo $final;
	wp_die();
}
add_action( 'wp_ajax_joshfunction_manual_submitdonasi', 'joshfunction_manual_submitdonasi' );
add_action( 'wp_ajax_nopriv_joshfunction_manual_submitdonasi', 'joshfunction_manual_submitdonasi' );


require_once ROOTDIR_DNA . 'core/class/Input-leads.php';

function joshfunction_input_leads() {

    $leads = new Input_Leads('get');

	header('Content-Type: application/json');

    echo json_encode($leads->get_output());

	wp_die();
}
add_action( 'wp_ajax_joshfunction_input_leads', 'joshfunction_input_leads' );
// add_action( 'wp_ajax_nopriv_joshfunction_input_leads', 'joshfunction_input_leads' );


function joshfunction_submit_leads() {

    $leads = new Input_Leads('submit');

	// $date = $_POST['date'];
	// $noWa = $_POST['phone'];
	// // $user_id = wp_get_current_user()->ID;
	// $user_id = 10;
	
	// global $wpdb;
	// $table_leads_log = $wpdb->prefix. 'josh_leads_log';
	// $table_donate = $wpdb->prefix . 'dja_donate';

	// $cs_name = get_user_meta( $user_id, 'first_name', true);

	// // set the output type to json
	// header('Content-Type: application/json');

	// if( $noWa != null ) {
	// 	$noWa = stripslashes($noWa);
	// 	$noWa_ar = json_decode( $noWa );
	// } else {
	// 	$noWa_ar = array();
	// }

	// /**
	//  * Submit to leads_log
	//  */
	// $insert_leads = $wpdb->insert(
	// 	$table_leads_log,
	// 	array(
	// 		'cs_id'		=> $user_id,
	// 		'cs_name'	=> $cs_name,
	// 		'no_leads'  => $noWa,
	// 		'date'		=> $date
	// 	)
	// );

	// if( $insert_leads === false ) {
	// 	$error_db = $wpdb->last_error;
	// 	echo json_encode(array('status'=>'error', 'message'=>"insert database failed! $error_db"));
	// 	// var_dump($noWa);
	// 	// var_dump($noWa_ar);
	// 	// var_dump(json_decode(stripslashes($noWa))[0]->id);
	// 	// var_dump(json_last_error_msg());
	// 	wp_die();
	// }

	// /**
	//  * Updating data in donate db
	//  */
	// /**
	//  * convert all no_leads id into an array
	//  */
	// $no_leads = [];
	// foreach( $noWa_ar as $val ) {
	// 	$no_leads[] = $val->id;
	// }

	// /**
	//  * Get all new order in this day and current cs
	//  */
	// $date_start = $date . ' 00:00:00';
	// $date_end = $date . ' 23:59:59';
	// $query = "SELECT id, whatsapp FROM $table_donate WHERE (created_at BETWEEN '$date_start' AND '$date_end') AND (cs_id='$user_id') AND (repeat_sts='new') ORDER BY id ASC";
	// $rows = $wpdb->get_results( $query );

	// for($i=0; $i < count($rows); $i++) {

	// 	if( in_array( $rows[$i]->id, $no_leads ) ) {
	// 		$leads = 0; // for number not leads
	// 	} else {
	// 		$leads = 1; // for number as leads
	// 	}

		
	// 	$update = $wpdb->update(
	// 		$table_donate,
	// 		array(
	// 			'leads'	=> $leads
	// 		),
	// 		array(
	// 			'id'	=> $rows[$i]->id
	// 		)
	// 	);

	// 	if( $update === false ) {
	// 		$error_db = $wpdb->last_error;
	// 		echo json_encode(array('status'=>'error', 'message'=>"updating database failed! $error_db"));
	// 		wp_die();
	// 	}
	// }
	// // if( $noWa != null ) {


	// // }

    header('Content-Type: application/json');

	// echo json_encode(array('status'=>'success'));
    echo json_encode($leads->submit_output());

	wp_die();
}
add_action( 'wp_ajax_joshfunction_submit_leads', 'joshfunction_submit_leads' );
// add_action( 'wp_ajax_nopriv_joshfunction_submit_leads', 'joshfunction_submit_leads' );


function djafunction_regaff_fundraiser(){

	global $wpdb;
    $table_name = $wpdb->prefix . "dja_aff_code";
    $table_name2 = $wpdb->prefix . "dja_campaign";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $campaign_id 	= $_POST['datanya'][0];
    $aff_code = d_randomString(5);


    if($id_login==null){
    	echo 'loginfirst_0';
    	wp_die();
    }

    $check = $wpdb->get_results('SELECT * from '.$table_name.' where campaign_id="'.$campaign_id.'" and user_id="'.$id_login.'" ');
	if($check==null){
		// create aff_code
		// check dl aff_code sudah ada belum
		$check2 = $wpdb->get_results('SELECT * from '.$table_name.' where aff_code="'.$aff_code.'" ');
		if($check2==null){
			$wpdb->insert(
	            $table_name, //table
	            array(
		            'campaign_id' 	=> $campaign_id,
		            'user_id' 		=> $id_login,
		            'aff_code' 		=> $aff_code,
		            'created_at' 	=> date("Y-m-d H:i:s")
		        ),
	            array('%s', '%s') //data format         
	        );
	        $the_code = 'success_'.$aff_code;
		}else{
			$aff_code2 = d_randomString(6);
			$check3 = $wpdb->get_results('SELECT * from '.$table_name.' where aff_code="'.$aff_code2.'" ');
			if($check3==null){
				$wpdb->insert(
		            $table_name, //table
		            array(
			            'campaign_id' 	=> $campaign_id,
			            'user_id' 		=> $id_login,
			            'aff_code' 		=> $aff_code2,
			            'created_at' 	=> date("Y-m-d H:i:s")
			        ),
		            array('%s', '%s') //data format         
		        );
		        $the_code = 'success_'.$aff_code2;
			}else{
				$aff_code3 = d_randomString(7);
				$check4 = $wpdb->get_results('SELECT * from '.$table_name.' where aff_code="'.$aff_code3.'" ');
				if($check4==null){
					$wpdb->insert(
			            $table_name, //table
			            array(
				            'campaign_id' 	=> $campaign_id,
				            'user_id' 		=> $id_login,
				            'aff_code' 		=> $aff_code3,
				            'created_at' 	=> date("Y-m-d H:i:s")
				        ),
			            array('%s', '%s') //data format         
			        );
			        $the_code = 'success_'.$aff_code3;
				}
			}
		}
		
	}else{
		// tampilkan saja
		$the_code = 'show_'.$check[0]->aff_code;
	}

	echo $the_code;

    wp_die();

}
add_action( 'wp_ajax_djafunction_regaff_fundraiser', 'djafunction_regaff_fundraiser' );
add_action( 'wp_ajax_nopriv_djafunction_regaff_fundraiser', 'djafunction_regaff_fundraiser' );


function donasiaja_send2tg($token, $chatID, $message_tele) {
	
	$urlweb = 'https://api.telegram.org/bot'.$token.'/sendMessage?text='.rawurlencode($message_tele).'&chat_id='.$chatID.'&parse_mode=Markdown';
	// $urlweb = 'https://api.telegram.org/bot'.$token.'/sendMessage?text='.$message_tele.'&chat_id='.$chatID.'&parse_mode=Markdown';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlweb);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	$siteData = curl_exec($ch);
	curl_close($ch);

	return $siteData;
	
}


function josh_send_email($data_field, $email_send_to, $user_info_cs, $invoice_id) {

	$email_send_to = json_decode($email_send_to);
	
	foreach($email_send_to as $key => $value) {

		$message = $value->message;
		$message = strtr($message, $data_field);
		$message = str_replace('<p>linebreak</p>', '', $message);
		$message = str_replace('linebreak', '', $message);
		
		$subject = $value->subject;
		$subject = strtr($subject, $data_field);
		$subject = str_replace("'","",$subject);
		
		//pelu ganti email disini
		//$emailnya = $value->email;
		$emailnya = $user_info_cs->user_email;
		
		$emailnyacc = $value->emailcc;
		$emailnyabcc = $value->emailbcc;

		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;"><h1 style="margin-top:0;margin-bottom:16px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'</h1>'
		.$message.'
		<p style="text-align:center; margin-bottom:20px"><span style="text-align:center;display:block;margin-top:20px; margin-bottom:5px"><a href="https://ympb.or.id/f/?inv='.$invoice_id.'" style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;color:#fff;text-decoration:none;line-height:2em;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background-color:#22cd3f;margin:0;border-color:#22cd3f;border-style:solid;border-width:10px 20px" target="_blank">Follow Up via WhatsApp</a></span>*silahkan digunakan, laporkan jika ada error</p></td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';

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
			$send_status = wp_mail( $emailnya, $subject, $body, $headers );
		}	
	}
	
	return $send_status;
}


function djafunction_send_test_telegram() {
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    // FROM INPUT
    $channel_tele 	= $_POST['datanya'][0];
    $message_tele 	= $_POST['datanya'][1];

    // General Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="telegram_bot_token" or type="telegram_send_to" ORDER BY id ASC');
    $telegram_bot_token	= $query_settings[0]->data;
    $telegram_send_to	= $query_settings[1]->data;

	$token = $telegram_bot_token;
	$send = donasiaja_send2tg($token, $channel_tele, $message_tele);

	$array = json_decode( $send, true );
    $data = $array['ok'];

	if($data==true){
		echo 'success';
	}else{
		echo 'failed';
	}

    wp_die();

} 
add_action( 'wp_ajax_djafunction_send_test_telegram', 'djafunction_send_test_telegram' );
add_action( 'wp_ajax_nopriv_djafunction_send_test_telegram', 'djafunction_send_test_telegram' );


function djafunction_send_test_email(){
	global $wpdb;

    // FROM INPUT
    $to 		= $_POST['datanya'][0];
    $subject 	= $_POST['datanya'][1];
    $message 	= $_POST['datanya'][2];
    $headers = array('Content-Type: text/html; charset=UTF-8');

    $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #F1F7FB; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr><tr><td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]--> <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;"> <tr><td style="padding:30px;background-color:#ffffff;">'.$message.'</td></tr><tr><td style="padding:30px;background-color:#E7ECF0;"></td></tr></table><!--[if mso]> </td></tr></table><![endif]--> </td></tr></table> </div></body></html>';

    wp_mail( $to, $subject, $body, $headers );
	
	echo 'success';

    wp_die();

} 
add_action( 'wp_ajax_djafunction_send_test_email', 'djafunction_send_test_email' );
add_action( 'wp_ajax_nopriv_djafunction_send_test_email', 'djafunction_send_test_email' );



function djafunction_send_test_wanotif(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    // FROM INPUT
    $apikey 		= $_POST['datanya'][0];
    $no_wa 			= $_POST['datanya'][1];
    $message_wa 	= $_POST['datanya'][2];

    // General Settings
    // GET SETTINGS
	$query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="wanotif_url" or type="wanotif_apikey" ORDER BY id ASC');
    $wanotif_url 		  = $query_settings[0]->data;
    $wanotif_apikey 	  = $query_settings[1]->data;   

	$phone = djaPhoneFormat($no_wa);
	$url = $wanotif_url.'/send';

	$messagenya = $message_wa;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_TIMEOUT,30);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, array(
	    'Apikey'    => $apikey,
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

    wp_die();

} 
add_action( 'wp_ajax_djafunction_send_test_wanotif', 'djafunction_send_test_wanotif' );
add_action( 'wp_ajax_nopriv_djafunction_send_test_wanotif', 'djafunction_send_test_wanotif' );


function djafunction_status_wanotif(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $check_status 			= $_POST['datanya'][0];

    if($check_status==true){
    	// GET SETTINGS
		$query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="wanotif_url" or type="wanotif_apikey" ORDER BY id ASC');
	    $wanotif_url 		  = $query_settings[0]->data;
	    $wanotif_apikey 	  = $query_settings[1]->data;   

		$url = $wanotif_url.'/auth';

		$curl = curl_init();
		curl_setopt_array($curl, [
		    CURLOPT_URL => $url,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_ENCODING => "",
		    CURLOPT_MAXREDIRS => 10,
		    CURLOPT_TIMEOUT => 30,
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_CUSTOMREQUEST => "GET",
		    CURLOPT_SSL_VERIFYHOST => 0,
		    CURLOPT_SSL_VERIFYPEER => 0,
		    CURLOPT_HTTPHEADER => [
		      "Accept: application/json",
		      "Apikey: $wanotif_apikey",
		    ],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

	    curl_close($curl);

	    if ($err) {
	      echo "cURL Error #:" . $err;
	    }else{
	    	$array = json_decode( $response, true );
			$data_wanotif_status = $array['wanotif']['status'];

			if($data_wanotif_status=='connected'){
			    $status_wanotif = '<span style="color:#36BD47;">Connected</span>';
			}elseif($data_wanotif_status=='scan'){
			    $status_wanotif =  '<a href="https://app.wanotif.id/device" class="link_href" target="_blank">Sqan QR-Code</a>';
			}else{
				if($wanotif_apikey==''){
					$status_wanotif = '-';
				}else{
					$status_wanotif = '<a href="https://app.wanotif.id/device" class="link_href" target="_blank">Check your connection on Wanotif</a>';
				}
			}
			echo $status_wanotif;
	    }
		wp_die();
    }

} 
add_action( 'wp_ajax_djafunction_status_wanotif', 'djafunction_status_wanotif' );
add_action( 'wp_ajax_nopriv_djafunction_status_wanotif', 'djafunction_status_wanotif' );