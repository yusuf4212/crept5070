<?php

function djafunction_get_donasi() {
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_campaign";
    $table_name3 = $wpdb->prefix . "users";
    $table_name4 = $wpdb->prefix . "dja_users";
    $table_name5 = $wpdb->prefix . "dja_payment_log";
    $table_name6 = $wpdb->prefix . "dja_settings";
    $table_name7 = $wpdb->prefix . "dja_aff_submit";
    $table_name8 = $wpdb->prefix . "dja_aff_code";
    

    // FROM INPUT
    $donasi_id 	= $_POST['datanya'][0];

    $row = $wpdb->get_results("SELECT a.*, b.user_randid FROM $table_name a 
    left JOIN $table_name4 b ON b.user_id = a.user_id where a.id='$donasi_id' ")[0];

    $row2 = $wpdb->get_results("SELECT title, slug, general_status, allocation_title, allocation_others_title FROM $table_name2 where campaign_id='$row->campaign_id' ")[0];

    $row3 = $wpdb->get_results("SELECT b.user_id as aff_userid from $table_name7 a 
    left JOIN $table_name8 b ON b.id = a.affcode_id where a.donate_id='$donasi_id' ");

    $query_settings = $wpdb->get_results('SELECT data from '.$table_name6.' where type="page_typ" ORDER BY id ASC');
    $page_typ = $query_settings[0]->data;

    if($row->status=='1'){
        $status = '<span class="active-status p_received" style="padding: 5px 12px;font-size: 10px;">Received</span>';
    }else{
        $status = '<span class="active-status p_waiting" style="padding: 5px 12px;font-size: 10px;">Waiting</span>';
    }

    if($row->anonim=='1'){
    	$anonim = 'Ya';
    	$anonim_text = 'Ya';
    	$anonim_status = 'anonim_yes';
    	$anonim_checked = 'checked';
    }else{
    	$anonim = 'Tidak';
    	$anonim_text = 'Tidak';
    	$anonim_status = 'anonim_no';
    	$anonim_checked = '';
    }

    if($row->comment==''){
    	$comment = '-';
    }else{
    	$comment = $row->comment;
    }

    // cek ada id user gak, klo ada, ambil data member
    if($row->user_id!='0'){
    	$select_active = 'opt_active';
    	$input_active = 'opt_hide';
    }else{
    	$select_active = 'opt_hide';
    	$input_active = 'opt_active';
    }
    	
    // $data_userwp = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
    $data_userwp = $wpdb->get_results('SELECT a.ID, a.display_name, a.user_email, b.user_wa, b.user_pp_img, b.user_randid from '.$table_name3.' a 
    left JOIN '.$table_name4.' b ON b.user_id = a.ID ');

	$data_user = '';
	foreach ( $data_userwp as $user ) {
		$selected = '';
		if($row->user_id==$user->ID){
			$selected = 'selected';
		}

	    $data_user .= '<option value="'.$user->display_name.'" data-userid="'.$user->ID.'" data-name="'.$user->display_name.'" data-wa="'.$user->user_wa.'" data-email="'.$user->user_email.'" data-userrandid="'.$user->user_randid.'" data-pp="'.$user->user_pp_img.'" '.$selected.'>'.$user->display_name.' ('.$user->user_email.')</span></option>';
	}


	$data_cs = '';
	foreach ( $data_userwp as $user ) {
        $cap_user = get_user_meta( $user->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
        $roles_user = array_keys((array)$cap_user);
        $rolenya_user = $roles_user[0];

        if($rolenya_user=='cs'){
            $selected = '';
            if($row->cs_id==$user->ID){
                $selected = 'selected';
            }
            $data_cs .= '<option value="'.$user->display_name.'" data-userid="'.$user->ID.'" data-name="'.$user->display_name.'" data-wa="'.$user->user_wa.'" data-email="'.$user->user_email.'" data-userrandid="'.$user->user_randid.'" data-pp="'.$user->user_pp_img.'" '.$selected.'>'.$user->display_name.'</span></option>';
        }
    }



	$user_data_select = '<select class="select2 form-control mb-3 custom-select usernya_select '.$select_active.'" style="width: 100%; height:36px;" >
        <option value="">Select User</option>
        '.$data_user.'
    </select>';

    $user_data_select2 = '<select class="select2 form-control mb-3 custom-select csnya_select '.$select_active.'" style="width: 100%; height:36px;" >
        <option value="">Select CS</option>
        '.$data_cs.'
    </select>';

	$user_option_select = '<span class="input-group-append icon_pencil '.$select_active.'" title="Edit Manual tanpa Korelasi User">
                            <button type="button" class="btn btn-sm btn-primary" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;"><i class="fas fa-pen" style="margin-left:3px;margin-right:3px;"></i></button>
                        </span>';


	$user_data_input = '<input type="text" class="form-control usernya_input '.$input_active.'" id="inp1" placeholder="Nama Donatur" value="'.$row->name.'" style="border-radius: 4px;">
	';
	$user_option_input = '<span class="input-group-append icon_user '.$input_active.'" title="Edit From Data User">
                            <button type="button" class="btn btn-sm btn-primary" style="height:45px;"><i class="fas fa-user" style="margin-left:4px;margin-right:4px;"></i></button>
                        </span>';
    
    // payment log
    $get_log = $wpdb->get_results("SELECT * FROM $table_name5 where id_donate='$donasi_id' ");
    if($get_log==null){
    	$log_messagenya = '';
    	$height = 'height:340px';
    }else{
    	$log_messagenya = '
    	<tr><td>Request</td><td>:</td><td><textarea readonly="readonly" rows="2" style="width: 240px;font-size: 11px;padding: 10px 10px;background: #ecf0fb;border: 1px solid #ecf0fb;color: #7484bb;">'.$get_log[0]->hit.'</textarea></td></tr>
		<tr><td>Response</td><td>:</td><td><textarea readonly="readonly" rows="2" style="width: 240px;font-size: 11px;padding: 10px 10px;background: #ecf0fb;border: 1px solid #ecf0fb;color: #7484bb;">'.$get_log[0]->log.'</textarea></td></tr>
		<tr><td>Message</td><td>:</td><td>'.$get_log[0]->message.'</td></tr>
		<tr><td colspan="3"><hr></td></tr>
    	';
    	$height = 'height:450px';
    }

    // set kosong log_messagenya for donatur
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];
    // if($role=='donatur'){
    // 	$log_messagenya = '';
    // }
    $show_data_cs = '';
    $show_edit_icon = '';
    $margin_printer = '';
    if($role=='cs'){
    	$show_data_cs = 'display:none;';
    	$show_edit_icon = 'display:none;';
    	$margin_printer = 'margin-right:5px !important;';
    }
    
    

    $general_status = $row2->general_status;
    $allocation_title = $row2->allocation_title;
    $allocation_others_title = $row2->allocation_others_title;

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


    $info_donate = json_decode($row->info_donate);
    $datane = '';
    foreach ( $info_donate as $key => $value ) {
    	$datane .= '<li>'.$key . ': ' . $value .'</li>';
    }
    if($row->info_donate==''){
    	$show_detail_donasi = 'style="display:none;"';
    }else{
    	$show_detail_donasi = '';
    }

    
    if($row3!=null){
    	$user_info_cs = get_userdata($row3[0]->aff_userid);
    	if($user_info_cs->last_name==''){
	    	$refferal_name = $user_info_cs->first_name;
	    }else{
	    	$refferal_name = $user_info_cs->first_name.'&nbsp;'.$user_info_cs->last_name;
	    }
    }else{
    	$refferal_name = '-';
    }

    $user_info_cs = get_userdata($row->cs_id);
    if($user_info_cs!=null){
    	if($user_info_cs->last_name==''){
	    	$cs_name = $user_info_cs->first_name;
	    }else{
	    	$cs_name = $user_info_cs->first_name.'&nbsp;'.$user_info_cs->last_name;
	    }
    }else{
    	$cs_name = '-';
    }


	$data_donasi = '
	<p class="title_donasi" style="color:#50608e;"><b>'.$row2->title.'</b></p>
	<hr>
	<p class="inv"><a href="'.home_url().'/campaign/'.$row2->slug.'/'.$page_typ.'/'.$row->invoice_id.'" target="_blank" style="color:#7680ff;">'.$row->invoice_id.'</a> / '.date("M",strtotime($row->created_at)).'&nbsp;'.date("j",strtotime($row->created_at)).',&nbsp;'.date("Y",strtotime($row->created_at)).'&nbsp;-&nbsp;'.date("H:i ",strtotime($row->created_at)).'</p>
	<a href="'.home_url().'/wp-admin/?donasiaja=print_kuitansi&inv='.$row->invoice_id.'" target="_blank"><p class="inv print_invoice" title="Print Kuitansi" data-id="'.$row->id.'"><i class="mdi mdi-printer mr-2" style="color:#7081B9;margin: 0 4px !important;margin-right:45px !important;'.$margin_printer.'"></i></p></a>
	<p class="inv edit_detail" title="Edit Data Donasi" data-id="'.$row->id.'" style="'.$show_edit_icon.'"><i class="mdi mdi-pencil mr-2" style="margin: 0 4px !important;"></i></p>
	<hr>
	<div class="box_table" style="'.$height.';overflow-y: scroll;">
		<table id="table_donasi" style="margin-bottom:-25px;">
			<tr><td style="width:140px;">Nama</td><td>:</td><td><span id="d_name">'.$row->name.'</span></td></tr>
			<tr><td>Anonim</td><td>:</td><td>'.$anonim.'</td></tr>
			<tr><td>Whatsapp</td><td>:</td><td><span id="d_whatsapp">'.$row->whatsapp.'</span></td></tr>
			<tr><td>Email</td><td>:</td><td><span id="d_email">'.$row->email.'</span></td></tr>
			<tr><td>Pesan</td><td>:</td><td><span id="d_comment">'.$comment.'</span></td></tr>
			<tr><td>Payment Status</td><td>:</td><td><a href="'.home_url().'/campaign/'.$row2->slug.'/'.$page_typ.'/'.$row->invoice_id.'" target="_blank">'.$status.'</a></td></tr>
			<tr><td style="padding-top: 8px;">Refferal</td><td style="padding-top: 8px;">:</td><td style="padding-top: 8px;"><span id="d_csname">'.$refferal_name.'</span></td></tr>
			<tr><td style="padding-top: 5px;">CS</td><td style="padding-top: 5px;">:</td><td style="padding-top: 5px;"><span id="d_csname">'.$cs_name.'</span></td></tr>
			<tr><td style="padding-bottom: 8px;">Total</td><td style="padding-bottom: 8px;">:</td><td style="padding-bottom: 8px;font-size:15px;"><span id="d_nominal">'.'<b>Rp '.number_format($row->nominal,0,",",".").'</b></span></td></tr>

			<tr '.$show_detail_donasi.'><td style="vertical-align:top">Detail '.$allocation_title.'</td><td style="vertical-align:top">:</td><td style="vertical-align:top;font-size: 11px;"><ul style="margin-left: px;padding-top: 2px;font-style: italic;background: #f1f5ff;padding: 10px 15px;border-radius: 4px;">'.$datane.'</ul></td></tr>
			
    		<tr><td colspan="3"><hr></td></tr>
			'.$log_messagenya.'

		</table>

		<div class="card-body" id="edit_data_donasi">
            
            <div class="general-label">
                <form>
                	<div role="alert" class="alert alert-success border-0 update_donasi_success" style="font-size: 13px;margin-top: -20px;margin-bottom: 30px;display:none;">Update Success.
                    </div>
                    <div role="alert" class="alert alert-danger border-0 update_donasi_failed" style="font-size: 13px;margin-top: -20px;margin-bottom: 30px;display:none;">Update failed.
                    </div>
                    <div class="form-group row data_usernya">
                        <label for="inp1" class="col-sm-3 col-form-label" style="font-size:14px;">Nama</label>
                        <div class="input-group col-sm-9" id="select-user">
                            <input type="text" class="form-control" id="inp0" placeholder="ID Donasi" value="'.$row->id.'" style="display:none;">
                            '.$user_data_select.'
                            '.$user_option_select.'
                            '.$user_data_input.'
                            '.$user_option_input.'
                        </div>
                    </div>
                    <div class="form-group row" id="anonim_status">
                        <label for="inp2" class="col-sm-3 col-form-label" style="font-size:14px;">Anonim</label>

                        <div class="custom-control custom-switch set_anonim '.$anonim_status.'" style="margin-left: 10px;margin-top: 10px;">
	                        <input type="checkbox" class="custom-control-input set_anonim_status" id="customSwitchAnonim" '.$anonim_checked.'>
	                        <label class="custom-control-label" for="customSwitchAnonim"><span>'.$anonim_text.'</span></label>
	                    </div>

                    </div>
                    <div class="form-group row">
                        <label for="inp2" class="col-sm-3 col-form-label" style="font-size:14px;">Whatsapp</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control wa_donatur" id="inp2" placeholder="08xxxx" value="'.$row->whatsapp.'">
                            <span id="errmsg2"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inp3" class="col-sm-3 col-form-label" style="font-size:14px;">Email</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control email_donatur" id="inp3" placeholder="Email" value="'.$row->email.'">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inp4" class="col-sm-3 col-form-label" style="font-size:14px;">Pesan</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" id="inp4">'.$row->comment.'</textarea>
                        </div>
                    </div>
                    <div class="form-group row data_csnya" style="'.$show_data_cs.'">
                        <label for="inp5" class="col-sm-3 col-form-label" style="font-size:14px;">CS</label>
                        <div class="col-sm-9">
                            '.$user_data_select2.'
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:30px;">
                        <label for="inp5" class="col-sm-3 col-form-label" style="font-size:14px;">Donasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control nominal_donasi" id="inp5" placeholder="Nominal Donasi" value="'.number_format($row->nominal,0,",",".").'">
                            <span id="errmsg"></span>
                            <input type="text" class="form-control user_randid" id="inp6" placeholder="user_randid" value="'.$row->user_randid.'" style="display:none;">
                        </div>
                    </div>
                    
                        
                    <div class="row">
                        <div class="col-sm-9 ml-auto" style="text-align:left;">
                        	<button type="button" class="btn btn-outline-primary back_me" style="padding:6px 15px 6px 12px;"><i class="mdi mdi-chevron-left"></i>Back</button>
                            <button type="button" class="btn btn-primary update_donasi" style="padding:6px 15px;">Update Donasi <div class="spinner-border spinner-border-sm text-white update_donasi_loading" style="margin-left: 3px;display: none;"></div></button>
                        	
                        </div>
                    </div> 
                </form>           
            </div>
        </div>

	</div>

	<script src="'.DJA_PLUGIN_URL.'admin/plugins/select2/select2.min.js"></script>
	<script>

    jQuery(document).ready(function($){
        $(".usernya_select").select2({
		    tags: true,
		    tokenSeparators: [",", "joss"]
		});

		$(".csnya_select").select2({
		    tags: true,
		    tokenSeparators: [",", "joss"]
		});

		var status_user = "'.$row->user_id.'";
		if(status_user!="0"){
        	$(".wa_donatur, .email_donatur").prop( "disabled", true );
        }

        if(status_user=="0"){
        	$("#select-user .select2.select2-container").hide();
    	}

        $(".select2.usernya_select").on("change", function() {
	    	var userid = $(this).find("option:selected").attr("data-userid");
	    	var name = $(this).find("option:selected").attr("data-name");
	    	var wa = $(this).find("option:selected").attr("data-wa");
	    	var email = $(this).find("option:selected").attr("data-email");
	    	var user_randid = $(this).find("option:selected").attr("data-userrandid");

	    	$(".wa_donatur").val(wa);
	    	$(".email_donatur").val(email);
	    	$(".user_randid").val(user_randid);
	    })
    });

    </script>
	';

	echo $data_donasi;


    wp_die();
}
add_action( 'wp_ajax_djafunction_get_donasi', 'djafunction_get_donasi' );
add_action( 'wp_ajax_nopriv_djafunction_get_donasi', 'djafunction_get_donasi' );



function djafunction_get_donasi_confirmation(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";

    // FROM INPUT
    $donasi_id 	= $_POST['datanya'][0];

    $row = $wpdb->get_results("SELECT * FROM $table_name where id='$donasi_id' ")[0];

    if($row!=null){
    	// $a = $wpdb->update(
	    //     $table_name, //table
	    //     array(
	    //         'img_confirmation_status' 	=> 1,
	    //     ),
	    //     array('id' => $donasi_id), //where
	    //     array('%s'), //data format
	    //     array('%s') //where format
	    // );

    	// $time_upload = date('Y-m-d H:i:s',strtotime('+0 hour',strtotime($row->img_confirmation_date)));
	    // $readtime = new humanReadtime();
        // $a = $readtime->dja_human_time($time_upload);

	    // $datenya = new DateTime($row->img_confirmation_date);
        // $date_upload = $datenya->format('d').'&nbsp;'.$datenya->format('M').',&nbsp;'.$datenya->format('Y').'&nbsp;-&nbsp;'.$datenya->format('H').':'.$datenya->format('i').'<br><span style='."'".'font-size:10px;color:#91a2b0;'."'".'>'.$a.'<span>';

	    // $data_image = '

		// <p class="title_donasi" style="color:#50608e;"><button data-id="'.$donasi_id.'" type="button" class="swal2-close del_conf" style="top: auto;right: auto;background: #ea0940;font-size: 20px;border-radius: 4px;margin-left: 180px;margin-top: 25px;display:none;" title="Delete Confirmation">×</button><img src="'.$row->img_confirmation_url.'" style="width:210px;border-radius:4px;margin-top:20px;margin-bottom:10px;"></p>';
		// $data_date = '';
		// if($row->img_confirmation_date!=''){
		// 	$data_date = '<p><i>Date upload: '.$date_upload.'</i></p>';
		// }

		// echo $data_image.$data_date;
		echo $row->invoice_id;
    }

	


    wp_die();
}
add_action( 'wp_ajax_djafunction_get_donasi_confirmation', 'djafunction_get_donasi_confirmation' );
add_action( 'wp_ajax_nopriv_djafunction_get_donasi_confirmation', 'djafunction_get_donasi_confirmation' );


function donasiaja_upload_confirmation() {
	if ( $_FILES ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		
		foreach ($_FILES as $file => $array) {

	        if($_FILES[$file]['error'] !== UPLOAD_ERR_OK){
	        	echo "failed";
	        }else{
	        	$file_handler = 'updoc';
	        	$attach_id = media_handle_upload($file_handler,$pid);
		        echo wp_get_attachment_url($attach_id); //upload file URL
	        }
	    }
	}

	// echo "success_".$attach_id;
	// echo wp_get_attachment_url($attach_id); // file url upload

	wp_die();
}
add_action( 'wp_ajax_donasiaja_upload_confirmation', 'donasiaja_upload_confirmation' );
add_action( 'wp_ajax_nopriv_donasiaja_upload_confirmation', 'donasiaja_upload_confirmation' );


function djafunction_update_confirmation() {
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_settings";
    $table_name3 = $wpdb->prefix . "dja_campaign";
    $table_name5 	= $wpdb->prefix . "dja_aff_submit";
    $table_name6 	= $wpdb->prefix . "dja_aff_code";

    // FROM INPUT
    $link_image 	= $_POST['datanya'][0];
    $invoice_id 	= $_POST['datanya'][1];
    // $linkReference  = $_POST['ref'];

    // if( $linkReference == '' ) {
    //     $linkReference = null;
    // }

    // General Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="currency" or type="telegram_bot_token" or type="telegram_send_to" or type="telegram_on" or type="telegram_manual_confirmation" or type="telegram_send_to_duta" or type="telegram_manual_confirmation_duta" ORDER BY id ASC');
    $currency 				= $query_settings[0]->data;
    $telegram_bot_token 	= $query_settings[1]->data;
    $telegram_send_to 		= $query_settings[2]->data;
    $telegram_on 			= $query_settings[3]->data;
    $telegram_manual_confirmation 	= $query_settings[4]->data;
    $telegram_send_to_duta 	= $query_settings[5]->data;
    $telegram_manual_confirmation_duta 	= $query_settings[6]->data;


    // GET DATA DONATE
    $row = $wpdb->get_results('SELECT a.*, b.title from '.$table_name.' a 
    left JOIN '.$table_name3.' b ON b.campaign_id = a.campaign_id where a.invoice_id="'.$invoice_id.'" ')[0];

    $row2 = $wpdb->get_results("SELECT * FROM $table_name5 where donate_id='$row->id' ")[0];
	//cs info
	$user_info_cs = get_userdata($row->cs_id);
    if($user_info_cs!=null){
    	if($user_info_cs->last_name==''){
	    	$cs_name = $user_info_cs->first_name;
	    }else{
	    	$cs_name = $user_info_cs->first_name.' '.$user_info_cs->last_name;
	    }
    }else{
    	$cs_name = '-';
    }

	// Data FORMAT
    $date_created = date("Y-m-d H:i:s");

	$data_field = array();
    $data_field[ '{name}' ] 	= $row->name;
	$data_field[ '{cs_name}' ] 	= $cs_name;
    $data_field[ '{whatsapp}' ] = $row->whatsapp;

	$length_j = strlen($row->whatsapp);
	if($length_j >= 6) {
		$star_l = 4;
		$real_l = $length_j - $star_l;

		$number_j = substr($row->whatsapp, 0, $real_l);
		$whatsapp_s = $number_j . "----";
	} else {
		$number_j = substr($row->whatsapp, 0, 3);
		$whatsapp_s = $number_j . "--";
	}
	$j_date = date("j F Y - H:i:s",strtotime($row->created_at));
	$j_confirm_date = date("j F Y - H:i:s");
	$j_total = 'Rp '.number_format($row->nominal,0,",",".");

	$data_field[ '{whatsapp_s}' ] 		= $whatsapp_s;
    $data_field[ '{email}' ] 			= $row->email;
    $data_field[ '{comment}' ] 			= $row->comment;
    $data_field[ '{ref}' ] 				= $row->ref;
    $data_field[ '{payment_number}' ] 	= $row->payment_number;
    $data_field[ '{payment_code}' ] 	= paymentCode($row->payment_code);
    $data_field[ '{payment_account}' ] 	= $row->payment_account;
    $data_field[ '{campaign_title}' ] 	= $row->title;
    $data_field[ '{invoice_id}' ] 		= $row->invoice_id;
    $data_field[ '{date}' ] 			= $j_date;
    $data_field[ '{link_ekuitansi}' ] 	= home_url().'/ekuitansi/'.$row->invoice_id;

    if($currency=='IDR'){
    	$data_field[ '{total}' ] 	= 'Rp '.number_format($row->nominal,0,",",".");
    }else{
    	$data_field[ '{total}' ] 	= $row->nominal;
    }

    $fundraiser_name = '-';
    if($row2!=null){
		$query_donation = $wpdb->get_results("SELECT b.user_id as fundraiser_id FROM $table_name5 a
	    LEFT JOIN $table_name6 b ON b.id = a.affcode_id 
	    where a.affcode_id='$row->affcode_id' ORDER BY a.id DESC ")[0];

	    if($query_donation->fundraiser_id!=''){
	        $user_info = get_userdata($query_donation->fundraiser_id);
	        $fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
	    }
	    $data_field[ '{fundraiser}' ] = $fundraiser_name;
	}else{
		$data_field[ '{fundraiser}' ] = '-';
	}

	$linkReference = $row->ref;

	if( $linkReference != null ) {
		$telegram_manual_confirmation = $telegram_manual_confirmation_duta;
	}

	/**
	 * Send to WABA Project
	 * @since 10 April 2023
	 * 
	 * modified to interact when linkReference exist
	 * @since 19 Apr 2023
	 */
	$data = array();
	if( $row->cs_id > 0 ) { // determine phone to
		$phone_number = get_the_author_meta( 'phone_number', $row->cs_id ); // example phone number
		if ( substr( $phone_number, 0, 1 ) === '0' ) {
			$phone_number = '62' . substr( $phone_number, 1 );
		}
	} elseif( $row->ref != null ) {
		$table_duta = $wpdb->prefix . 'josh_duta';

		$query = "SELECT whatsapp FROM $table_duta WHERE code='$linkReference'";
		$phone_number = $wpdb->get_row( $query )->whatsapp;

		if( substr( $phone_number, 0, 1 ) === '0' ) {
			$phone_number = '62' . substr( $phone_number, 1 );
		}
	} else {
		$phone_number = '';
	}
	$data['phone_to'] 		= $phone_number;
	$data['program'] 		= $row->title;
	$data['invoice'] 		= $row->invoice_id;
	$data['name'] 			= $row->name;
	$data['cs_name'] 		= ($cs_name === null) ? '-' : $cs_name;
	$data['nominal'] 		= number_format($row->nominal,0,",",".");
	$data['datetime'] 		= $j_date;

	$response_waba = joshfunction_waba_slip( $data ); // -------------> AKTIFKAN!


	// TELEGRAM
	if($telegram_on=='1'){
		$token = $telegram_bot_token;
		$telegram_manual_confirmation = json_decode($telegram_manual_confirmation);

		foreach($telegram_manual_confirmation as $key => $value) {

            $message_tele = $value->message;
            $message_tele = strtr($message_tele, $data_field);
            $channel = $value->channel;
			
			if (strpos($channel, ',') !== false ) {
				$array_channel  = (explode(",", $channel));
				foreach ($array_channel as $values){
					$channel_id = $values;
					$send = donasiaja_send2tg($token, $channel_id, $message_tele); // ----------> AKTIFKAN
				}
			}else{
				$channel_id = $channel;
				$send = donasiaja_send2tg($token, $channel_id, $message_tele); // -------------> AKTIFKAN
			}
		}
	}

	if( $linkReference == null ) {
	// EMAIL
	$j_email_to = $user_info_cs->user_email;
	$j_subject = 'Bukti Slip Transfer untuk '.$row->title.' diterima pada tanggal '.$j_confirm_date.' - '.$row->invoice_id;
	$j_headers = array("Content-Type: text/html; charset=UTF-8", "Cc: yusuf@ympb.or.id");
	//$j_headers[] = 'Content-Type: text/html; charset=UTF-8';
	// $j_headers[] = 'Cc: yusuf@ympb.or.id';
	$j_body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="x-apple-disable-message-reformatting">
	<title></title>
	<!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]-->
	<style>
	table, td, div, h1, p{
		font-family: Arial, sans-serif;
	}
	.title {
		width: 135px; font-weight: bold;
	}
	.colon {
		width: 10px;
	}
	.value {
		width: 250px;
	}
	td {
		padding-bottom: 8px;
		font-size: 15px;
	}
	@media screen and (max-width: 530px){
		.unsub{
			display: block;
			padding: 8px;
			margin-top: 14px;
			border-radius: 6px;
			background-color: #F1F7FB;
			text-decoration: none !important;
			font-weight: bold;
		}
		.col-lge{
			max-width: 100% !important;
		}
	}
	@media screen and (min-width: 531px){
		.col-sml{
			max-width: 27% !important;
		}
		.col-lge{
			max-width: 73% !important;
		}
	}
	</style>
	</head>
	<body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;">
		<div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;">
			<table role="presentation" style="width:100%;border:none;border-spacing:0;">
				<tr>
					<td style="padding:30px;background-color:#E7ECF0;">
					</td>
				</tr>
				<tr>
					<td align=center style="padding:0;">
						<!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]-->
						<table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;">
							<tr>
								<td style="padding:30px;background-color:#ffffff;">
									<h1 style="margin-top:0;margin-bottom:25px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$j_subject.'
									</h1>

									<table style="width: 100%">
										<tbody>
											<tr>
												<td class="title">CS</td>
												<td class="colon">:</td>
												<td class="value" style="background-color: yellow;">' . $cs_name .'</td>
											</tr>
											<tr>
												<td class="title">Invoice ID</td>
												<td class="colon">:</td>
												<td class="value">' . $row->invoice_id . '</td>
											</tr>
											<tr>
												<td class="title">Nama</td>
												<td class="colon">:</td>
												<td class="value">' . $row->name . '</td>
											</tr>
											<tr>
												<td class="title">WhatsApp</td>
												<td class="colon">:</td>
												<td class="value">' . $row->whatsapp . '</td>
											</tr>
											<tr>
												<td class="title">Program</td>
												<td class="colon">:</td>
												<td class="value">' . $row->title .'</td>
											</tr>
											<tr>
												<td class="title">Total</td>
												<td class="colon">:</td>
												<td class="value">'. $j_total .'</td>
											</tr>
											<tr>
												<td class="title">Tanggal Order</td>
												<td class="colon">:</td>
												<td class="value">'. $j_date .'</td>
											</tr>
											<tr>
												<td class="title">Tanggal Slip</td>
												<td class="colon">:</td>
												<td class="value">'. $j_confirm_date .'</td>
											</tr>
										</tbody>
									</table>

									<p style="text-align:center; margin-bottom:20px">
										<span style="text-align:center;display:block;margin-top:20px; margin-bottom:5px">
											<a href="https://ympb.or.id/s/?inv='.$row->invoice_id.'" style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;color:#fff;text-decoration:none;line-height:2em;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background-color:#22cd3f;margin:0;border-color:#22cd3f;border-style:solid;border-width:10px 20px" target="_blank">Lihat Slip TF</a>
										</span>*silahkan digunakan, laporkan jika ada error
									</p>

								</td>
							</tr>
							<tr>
								<td style="padding:30px;background-color:#E7ECF0;"></td>
							</tr>
						</table>
						<!--[if mso]> </td></tr></table><![endif]--> 
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>';

	wp_mail($j_email_to, $j_subject, $j_body, $j_headers); // ------------> AKTIFKAN
}
    
	// ACTION Update TO DB tanpa form_type dan packaged
 	$a = $wpdb->update(
        $table_name, //table
        array(
            'img_confirmation_url' 	=> $link_image,
            'img_confirmation_date' => date("Y-m-d H:i:s"),
        ),
        array('invoice_id' => $invoice_id), //where
        array('%s'), //data format
        array('%s') //where format
    );
	header('Content-Type: application/json');
	$answer = array('status'=>'success', 'waba'=>$response_waba);

	// echo 'success';
	echo json_encode( $answer );

    wp_die();
}
add_action( 'wp_ajax_djafunction_update_confirmation', 'djafunction_update_confirmation' );
add_action( 'wp_ajax_nopriv_djafunction_update_confirmation', 'djafunction_update_confirmation' );


function joshfunction_waba_slip( $data ) {
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
			'name' => 'slip_masuk',
			'language' => array(
				'code' => 'id'
			),
			'components' => array(
				array(
					'type' => 'body',
					'parameters' => array(
						array(
							'type' => 'text',
							'text' => $data['program']
						),
						array(
							'type' => 'text',
							'text' => $data['cs_name']
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
							'text' => $data['nominal']
						),
						array(
							'type' => 'text',
							'text' => $data['datetime']
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
	$response_decode = json_decode( $response);

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
				'wam_id_slip'	=> $response_decode->messages[0]->id
			),
			array(
				'invoice_id' => $data['invoice']
			)
		);
	}

	return $response_decode;
	// return $postBody;

}


/**
 * Add phone number field to user profile
 * @since 11 April 2023
 */
function add_phone_number_field( $user_contactmethods ) {
    // Add phone number field
    $user_contactmethods['phone_number'] = __( 'Phone Number', 'text-domain' );
    return $user_contactmethods;
}
add_filter( 'user_contactmethods', 'add_phone_number_field', 10, 1 );


function djafunction_get_mydonasi(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_campaign";
    $table_name3 = $wpdb->prefix . "users";
    $table_name4 = $wpdb->prefix . "dja_users";

    // FROM INPUT
    $donasi_id 	= $_POST['datanya'][0];

    $row = $wpdb->get_results("SELECT * FROM $table_name where id='$donasi_id' ")[0];
    $row2 = $wpdb->get_results("SELECT title FROM $table_name2 where campaign_id='$row->campaign_id' ")[0];

    if($row->status=='1'){
        $status = '<span class="active-status p_received" style="padding: 5px 12px;font-size: 10px;">Received</span>';
    }else{
        $status = '<span class="active-status p_waiting" style="padding: 5px 12px;font-size: 10px;">Waiting</span>';
    }

    if($row->anonim=='1'){
    	$anonim = 'Ya';
    	$anonim_text = 'Ya';
    	$anonim_status = 'anonim_yes';
    	$anonim_checked = 'checked';
    }else{
    	$anonim = 'Tidak';
    	$anonim_text = 'Tidak';
    	$anonim_status = 'anonim_no';
    	$anonim_checked = '';
    }

    if($row->comment==''){
    	$comment = '-';
    }else{
    	$comment = $row->comment;
    }

    // cek ada id user gak, klo ada, ambil data member
    if($row->user_id!='0'){
    	$select_active = 'opt_active';
    	$input_active = 'opt_hide';
    }else{
    	$select_active = 'opt_hide';
    	$input_active = 'opt_active';
    }
    	
    // $data_userwp = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
    $data_userwp = $wpdb->get_results('SELECT a.ID, a.display_name, a.user_email, b.user_wa, b.user_pp_img, b.user_randid from '.$table_name3.' a 
    left JOIN '.$table_name4.' b ON b.user_id = a.ID ');

	$data_user = '';
	foreach ( $data_userwp as $user ) {
		$selected = '';
		if($row->user_id==$user->ID){
			$selected = 'selected';
		}

	    $data_user .= '<option value="'.$user->display_name.'" data-userid="'.$user->ID.'" data-name="'.$user->display_name.'" data-wa="'.$user->user_wa.'" data-email="'.$user->user_email.'" data-userrandid="'.$user->user_randid.'" data-pp="'.$user->user_pp_img.'" '.$selected.'>'.$user->display_name.' ('.$user->user_email.')</span></option>';
	}



	$user_data_select = '<select class="select2 form-control mb-3 custom-select usernya_select '.$select_active.'" style="width: 100%; height:36px;" >
        <option value="">Select User</option>
        '.$data_user.'
    </select>';

	$user_option_select = '<span class="input-group-append icon_pencil '.$select_active.'" title="Edit Manual tanpa Korelasi User">
                            <button type="button" class="btn btn-sm btn-primary" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;"><i class="fas fa-pen" style="margin-left:3px;margin-right:3px;"></i></button>
                        </span>';


	$user_data_input = '<input type="text" class="form-control usernya_input '.$input_active.'" id="inp1" placeholder="Nama Donatur" value="'.$row->name.'" style="border-radius: 4px;">
	';
	$user_option_input = '<span class="input-group-append icon_user '.$input_active.'" title="Edit From Data User">
                            <button type="button" class="btn btn-sm btn-primary" style="height:45px;"><i class="fas fa-user" style="margin-left:4px;margin-right:4px;"></i></button>
                        </span>';
    
    

	$data_donasi = '
	<p class="title_donasi" style="color:#50608e;"><b>'.$row2->title.'</b></p>
	<hr>
	<p class="inv">'.$row->invoice_id.' / '.date("M",strtotime($row->created_at)).'&nbsp;'.date("j",strtotime($row->created_at)).',&nbsp;'.date("Y",strtotime($row->created_at)).'&nbsp;-&nbsp;'.date("H:i ",strtotime($row->created_at)).'</p>
	<hr>
	<div class="box_table">
		<table id="table_donasi">
			<tr><td>Nama</td><td>:</td><td><span id="d_name">'.$row->name.'</span></td></tr>
			<tr><td>Anonim</td><td>:</td><td>'.$anonim.'</td></tr>
			<tr><td>Whatsapp</td><td>:</td><td><span id="d_whatsapp">'.$row->whatsapp.'</span></td></tr>
			<tr><td>Email</td><td>:</td><td><span id="d_email">'.$row->email.'</span></td></tr>
			<tr><td>Pesan</td><td>:</td><td><span id="d_comment">'.$comment.'</span></td></tr>
			<tr><td>Donasi</td><td>:</td><td><span id="d_nominal">'.'Rp '.number_format($row->nominal,0,",",".").'</span></td></tr>
			<tr><td>Payment Method</td><td>:</td><td>'.$row->payment_method.'</td></tr>
			<tr><td>Payment Number</td><td>:</td><td>'.$row->payment_number.'</td></tr>
			<tr><td>Payment Account</td><td>:</td><td>'.$row->payment_account.'</td></tr>
			<tr><td>Payment Status</td><td>:</td><td>'.$status.'</td></tr>
		</table>


	</div>

	<script src="'.DJA_PLUGIN_URL.'admin/plugins/select2/select2.min.js"></script>
	<script>

    </script>
	';

	echo $data_donasi;


    wp_die();
}
add_action( 'wp_ajax_djafunction_get_mydonasi', 'djafunction_get_mydonasi' );
add_action( 'wp_ajax_nopriv_djafunction_get_mydonasi', 'djafunction_get_mydonasi' );



function djafunction_update_donasi(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";

    // FROM INPUT
    $id 		 = $_POST['datanya'][0];
    $user_id 	 = $_POST['datanya'][1];
    $name 		 = $_POST['datanya'][2];
    $whatsapp 	 = $_POST['datanya'][3];
    $email 		 = $_POST['datanya'][4];
    $comment 	 = $_POST['datanya'][5];
    $nominal 	 = $_POST['datanya'][6];
    $anonim 	 = $_POST['datanya'][7];
    $user_randid = $_POST['datanya'][8];
    $cs_id 		 = $_POST['datanya'][9];

    if($anonim==''){
    	$anonim = 0;
    }else{
    	$anonim = 1;
    }

    if($cs_id==''){
    	$cs_id = null;
    }

	// ACTION Update TO DB tanpa form_type dan packaged
 	$a = $wpdb->update(
        $table_name, //table
        array(
            'user_id' 	=> $user_id,
            'name' 		=> $name,
            'whatsapp' 	=> $whatsapp,
            'email' 	=> $email,
            'comment' 	=> $comment,
            'nominal' 	=> $nominal,
            'anonim' 	=> $anonim,
            'cs_id' 	=> $cs_id,
        ),
        array('id' => $id), //where
        array('%s'), //data format
        array('%s') //where format
    );

	echo 'success';

    wp_die();
}
add_action( 'wp_ajax_djafunction_update_donasi', 'djafunction_update_donasi' );
add_action( 'wp_ajax_nopriv_djafunction_update_donasi', 'djafunction_update_donasi' );



function djafunction_upload_ss() {

    global $wpdb;
    $table_name = $wpdb->prefix . "dja_aff_payout";

    $id = $_POST['datanya'][0];
    $image = $_POST['datanya'][1];

    $a = $wpdb->update(
        $table_name, //table
        array(
            'image' 	=> $image,
        ),
        array('id' => $id), //where
        array('%s'), //data format
        array('%s') //where format
    );
   	
   	if($a){
   		echo 'success';
   	}else{
   		echo 'failed';
   	}

   	wp_die();

}
add_action( 'wp_ajax_djafunction_upload_ss', 'djafunction_upload_ss' );
add_action( 'wp_ajax_nopriv_djafunction_upload_ss', 'djafunction_upload_ss' );


function djafunction_cairkan_sekarang() {

    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_category";
    $table_name3 = $wpdb->prefix . "dja_campaign_update";
    $table_name4 = $wpdb->prefix . "dja_donate";
    $table_name5 = $wpdb->prefix . "dja_settings";
    $table_name6 = $wpdb->prefix . "dja_users";
    $table_name7 = $wpdb->prefix . "dja_payment_list";
    $table_name8 = $wpdb->prefix . "dja_aff_code";
    $table_name9 = $wpdb->prefix . "dja_aff_submit";
    $table_name10 = $wpdb->prefix . "dja_aff_click";
    $table_name11 = $wpdb->prefix . "dja_aff_payout";
    
    $action = $_POST['datanya'][0];
    $id_login = wp_get_current_user()->ID;

    $check_user = $wpdb->get_results('SELECT user_bank_name, user_bank_no, user_bank_an from '.$table_name6.' where user_id="'.$id_login.'"');

	if($check_user!=null){
		$bank_name = $check_user[0]->user_bank_name;
		$bank_no = $check_user[0]->user_bank_no;
		$bank_an = $check_user[0]->user_bank_an;

		if($bank_name=='' || $bank_no=='' || $bank_an==''){
			echo 'bank_not_valid';
   			wp_die();
		}

	}

    // ************************************
    /*
    $rows_aff = $wpdb->get_results("SELECT a.id, a.campaign_id, a.aff_code, a.user_id as fundraiser_id, b.title, b.slug, b.image_url, b.user_id, c.user_randid, c.user_pp_img from $table_name8 a 
        left JOIN $table_name b ON b.campaign_id = a.campaign_id
        left JOIN $table_name6 c ON b.user_id = c.user_id
        where a.user_id=$id_login
        ORDER BY a.id DESC");
    */

   	$rows_aff = $wpdb->get_results("SELECT a.id, a.campaign_id, a.aff_code, a.user_id as fundraiser_id, c.user_randid, c.user_pp_img from $table_name8 a 
                left JOIN $table_name6 c ON a.user_id = c.user_id
                where a.user_id=$id_login
                ORDER BY a.id DESC");

    // ************************************

    $no = 1;
    $belum_dicairkan = 0;
    $data_id = '';
    // print_r($rows_aff);
    foreach ($rows_aff as $row) { 

        // 0 = not payout
        // 1 = paid
        // 2 = on process

        $count_submit3 = $wpdb->get_results("SELECT a.id, a.nominal_commission  FROM $table_name9 a 
            left JOIN $table_name4 b ON b.id = a.donate_id
            where a.affcode_id='$row->id' and b.status='1' and a.payout_status='0' ");


        if($count_submit3==null){
            $total_belum_dicairkan = 0;
        }else{
            $total_belum_dicairkan = $count_submit3[0]->nominal_commission;
            
            // Update table dja_aff_submit > payout_status : 2
            $wpdb->update(
		        $table_name9, //table
		        array(
		            'payout_status' => 2,
		        ),
		        array('id' => $count_submit3[0]->id), //where
		        array('%s'), //data format
		        array('%s') //where format
		    );

		    $data_id .= $count_submit3[0]->id.',';

        }

        $belum_dicairkan = $belum_dicairkan+$total_belum_dicairkan;

        $no++; 

    }

    $payout_number = 'INVP-'.strtoupper(d_randomString(5));

    
    // insert table dja_aff_payout
    $wpdb->insert(
        $table_name11, //table
        array(
            'payout_number' => $payout_number,
            'user_id' 		=> $id_login,
            'aff_submit_id' => $data_id,
            'nominal_payout' => $belum_dicairkan,
            'bank_name' 	=> $bank_name,
            'bank_no' 		=> $bank_no,
            'bank_an' 		=> $bank_an,
            'status' 		=> 0,
            'created_at' 	=> date("Y-m-d H:i:s"),
            'updated_at' 	=> date("Y-m-d H:i:s"),
        ),
        array('%s', '%s') //data format         
    );
    

   	echo 'success';
   	
   	wp_die();

}
add_action( 'wp_ajax_djafunction_cairkan_sekarang', 'djafunction_cairkan_sekarang' );
add_action( 'wp_ajax_nopriv_djafunction_cairkan_sekarang', 'djafunction_cairkan_sekarang' );




function djafunction_update_fundraising_settings() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_settings";

    $id_login = wp_get_current_user()->ID;

    // FROM INPUT
	$fundraiser_on = $_POST['datanya'][0];
    $fundraiser_text = $_POST['datanya'][1];
    $fundraiser_button = $_POST['datanya'][2];

    $fundraiser_commission_on = $_POST['datanya'][3];
    $fundraiser_commission_type = $_POST['datanya'][4];
    $fundraiser_commission_percent = $_POST['datanya'][5];
    $fundraiser_commission_fixed = $_POST['datanya'][6];

    $fundraiser_wa_on = $_POST['datanya'][7];
    $fundraiser_email_on = $_POST['datanya'][8];
    $fundraiser_wa_text = str_replace('\\', '', $_POST['datanya'][9]);
    $fundraiser_email_text = str_replace('\\', '', $_POST['datanya'][10]);

    $min_payout_setting = $_POST['datanya'][11];
    $min_payout = $_POST['datanya'][12];

    if($id_login!='' || $id_login!=null){
		// ACTION Update TO DB
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_on ), array('type' => 'fundraiser_on'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_text ), array('type' => 'fundraiser_text'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_button ), array('type' => 'fundraiser_button'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_commission_on ), array('type' => 'fundraiser_commission_on'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_commission_type ), array('type' => 'fundraiser_commission_type'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_commission_percent ), array('type' => 'fundraiser_commission_percent'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_commission_fixed ), array('type' => 'fundraiser_commission_fixed'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_wa_on ), array('type' => 'fundraiser_wa_on'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_email_on ), array('type' => 'fundraiser_email_on'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_wa_text ), array('type' => 'fundraiser_wa_text'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $fundraiser_email_text ), array('type' => 'fundraiser_email_text'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $min_payout_setting ), array('type' => 'min_payout_setting'), array('%s'), array('%s') );
	    $wpdb->update( $table_name,array( 'data' => $min_payout ), array('type' => 'min_payout'), array('%s'), array('%s') );
	
        echo 'success';

    }else{
    	echo 'Not allowed.';
    }


    wp_die();

} 
add_action( 'wp_ajax_djafunction_update_fundraising_settings', 'djafunction_update_fundraising_settings' );
add_action( 'wp_ajax_nopriv_djafunction_update_fundraising_settings', 'djafunction_update_fundraising_settings' );



function djafunction_delete_pencairan(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_aff_payout";
    $table_name2 = $wpdb->prefix . "dja_aff_submit";

    // FROM INPUT
    $id 	= $_POST['datanya'][0];

    // payout_status :
    // 0 = not payout
    // 1 = paid
    // 2 = on process

    $set_payout_status = 0;

    $check_data = $wpdb->get_results('SELECT * from '.$table_name.' where id="'.$id.'" ')[0];
    $data_aff_submit = $check_data->aff_submit_id;

    if(strpos($data_aff_submit, ',') !== false) {
	    $var=explode(',',$data_aff_submit);

	   	foreach($var as $idnya_aff_submit)
	    {
	       $wpdb->update(
		        $table_name2, //table
		        array(
		            'payout_status' => $set_payout_status,
		        ),
		        array('id' => $idnya_aff_submit), //where
		        array('%s'), //data format
		        array('%s') //where format
		    );
	    }

	}else{
		$idnya_aff_submit = $data_aff_submit;
		$wpdb->update(
	        $table_name2, //table
	        array(
	            'payout_status' => $set_payout_status,
	        ),
	        array('id' => $idnya_aff_submit), //where
	        array('%s'), //data format
	        array('%s') //where format
	    );
	}

    // table dja_aff_payout : 
    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $id ) ) ) {
		// then delete
        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$id.'" ' ) );
    }

	echo 'success';

    wp_die();
}
add_action( 'wp_ajax_djafunction_delete_pencairan', 'djafunction_delete_pencairan' );
add_action( 'wp_ajax_nopriv_djafunction_delete_pencairan', 'djafunction_delete_pencairan' );


function djafunction_update_status_payment() {

    global $wpdb;
    $table_name = $wpdb->prefix . "dja_aff_payout";
    $table_name2 = $wpdb->prefix . "dja_aff_submit";
    $table_name3 = $wpdb->prefix . "dja_settings";
    $table_name4 = $wpdb->prefix . "dja_users";
    $table_name5 = $wpdb->prefix . "users";
    
    $id = $_POST['datanya'][0];
    $status_payment = $_POST['datanya'][1];

    $check_data = $wpdb->get_results('SELECT a.*, b.user_wa, c.user_email from '.$table_name.' a
    left JOIN '.$table_name4.' b ON b.user_id = a.user_id
    left JOIN '.$table_name5.' c ON c.ID = a.user_id
    where a.id="'.$id.'" ');

    if($check_data!=null){
	    $data_aff_submit = $check_data[0]->aff_submit_id;
	    $user_wa = $check_data[0]->user_wa;
	    $user_email = $check_data[0]->user_email;
    }else{
    	echo 'failed_0';
    	wp_die();
    }
   

    // GET SETTINGS
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name3.' where type="currency" or type="wanotif_url" or type="wanotif_apikey" or type="wanotif_on" or type="email_on" or type="fundraiser_wa_on" or type="fundraiser_email_on" or type="fundraiser_wa_text" or type="fundraiser_email_text" ORDER BY id ASC');
    $currency 		= $query_settings[0]->data;
    $wanotif_url 	= $query_settings[1]->data;
    $wanotif_apikey	= $query_settings[2]->data;
    $wanotif_on 	= $query_settings[3]->data;
    $email_on 		= $query_settings[4]->data;
    $fundraiser_wa_on		= $query_settings[5]->data;
    $fundraiser_email_on	= $query_settings[6]->data;
    $fundraiser_wa_text		= $query_settings[7]->data;
    $fundraiser_email_text	= $query_settings[8]->data;

    $send_notif = false;
    if($status_payment=='2'){
    	$status_payment = '1';
    	$send_notif = true;
    }

    // print_r($send_notif);
    // wp_die();

    if($send_notif==true){

	    $row = $wpdb->get_results('SELECT nominal_payout as nominal, payout_number, user_id from '.$table_name.'
		    where id="'.$id.'" ')[0];

	    $user_info = get_userdata($row->user_id);
	    if($user_info->last_name==''){
	    	$fullname = $user_info->first_name;
	    	$email = $user_info->user_email;
	    }else{
	    	$fullname = $user_info->first_name.' '.$user_info->last_name;
	    	$email = $user_info->user_email;
	    }
	    
	    $data_field = array();
	    $data_field[ '{email}' ] 			= $email;
	    $data_field[ '{name}' ] 			= $fullname;
	    $data_field[ '{payout_number}' ] 	= $row->payout_number;
	    
	    if($currency=='IDR'){
	    	$data_field[ '{nominal}' ] 	= 'Rp '.number_format($row->nominal,0,",",".");
	    	$nominalnya = 'Rp '.number_format($row->nominal,0,",",".");
	    }else{
	    	$data_field[ '{nominal}' ] 	= $row->nominal;
	    	$nominalnya = $row->nominal;
	    }
	   
	    $messagenya = strtr($fundraiser_wa_text, $data_field);
		$phone = djaPhoneFormat($user_wa);

    	if($wanotif_on=='1' and $fundraiser_wa_on=='1'){

    		// send wa_notif
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

    	if($email_on=='1' and $fundraiser_email_on=='1'){

    		$fundraiser_email_text = json_decode($fundraiser_email_text);

			foreach($fundraiser_email_text as $key => $value) {

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

    }


    // table dja_aff_submit 
    // 0 => On Process =====> t_dja_aff_submit : set payout_status = 2
    // 1 dan 2 => PAID =====> t_dja_aff_submit : set payout_status = 1

    if($status_payment=='1'){
    	$set_payout_status = 1;
    }else{
    	$set_payout_status = 2;
    }

    

    if(strpos($data_aff_submit, ',') !== false) {
	    $var=explode(',',$data_aff_submit);

	   	foreach($var as $idnya_aff_submit)
	    {
	       $wpdb->update(
		        $table_name2, //table
		        array(
		            'payout_status' => $set_payout_status,
		        ),
		        array('id' => $idnya_aff_submit), //where
		        array('%s'), //data format
		        array('%s') //where format
		    );
	    }

	}else{
		$idnya_aff_submit = $data_aff_submit;
		$wpdb->update(
	        $table_name2, //table
	        array(
	            'payout_status' => $set_payout_status,
	        ),
	        array('id' => $idnya_aff_submit), //where
	        array('%s'), //data format
	        array('%s') //where format
	    );
	}


    // table dja_aff_payout : 
    $a = $wpdb->update(
        $table_name, //table
        array(
            'status' 	=> $status_payment,
        ),
        array('id' => $id), //where
        array('%s'), //data format
        array('%s') //where format
    );

   	
   	// if($a){
   		echo 'success_'.$status_payment;
   	// }else{
   	// 	echo 'failed_0';
   	// }

   	wp_die();

}
add_action( 'wp_ajax_djafunction_update_status_payment', 'djafunction_update_status_payment' );
add_action( 'wp_ajax_nopriv_djafunction_update_status_payment', 'djafunction_update_status_payment' );


function djafunction_download_donasi(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";

    // FROM INPUT
    $id_campaign 		= $_POST['datanya'][0];

	$DB_TBLName = "your_table_name"; 
	$filename = "excelfilename";  //your_file_name
	$file_ending = "xls";   //file_extention

	header("Content-Type: application/xls");    
	header("Content-Disposition: attachment; filename=$filename.'.'.$file_ending");  
	header("Pragma: no-cache"); 
	header("Expires: 0");

	$sep = "\t";

	// $sql="SELECT * FROM $DB_TBLName"; 

	$sql = $wpdb->get_results('SELECT * from '.$table_name.' ORDER BY id ASC');

	// $resultt = $con->query($sql);
	while ($property = mysqli_fetch_field($sql)) { //fetch table field name
	    echo $property->name."\t";
	}

	print("\n");    

	while($row = mysqli_fetch_row($resultt))  //fetch_table_data
	{
	    $schema_insert = "";
	    for($j=0; $j< mysqli_num_fields($resultt);$j++)
	    {
	        if(!isset($row[$j]))
	            $schema_insert .= "NULL".$sep;
	        elseif ($row[$j] != "")
	            $schema_insert .= "$row[$j]".$sep;
	        else
	            $schema_insert .= "".$sep;
	    }
	    $schema_insert = str_replace($sep."$", "", $schema_insert);
	    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
	    $schema_insert .= "\t";
	    print(trim($schema_insert));
	    print "\n";
	}


	// echo 'success';

    wp_die();
}
add_action( 'wp_ajax_djafunction_download_donasi', 'djafunction_download_donasi' );
add_action( 'wp_ajax_nopriv_djafunction_download_donasi', 'djafunction_download_donasi' );



function download_donasi() {
    if ( ! current_user_can( 'manage_options' ) )
        return;

    global $wpdb;
   
    $table_name = $wpdb->prefix . "dja_donate";
    $c_id = $_GET['c_id'];
    $date_filter = $_GET['c_date'];
    $date_range = $_GET['c_range'];

    $filternya = "";
    if($date_filter=='today' || $date_filter=='yesterday' || $date_filter=='7lastdays' || $date_filter=='30lastdays' || $date_filter=='thismonth' || $date_filter=='lastmonth' || $date_filter=='daterange' || $date_filter=='all'){
    
        // Date
        $today = date('Y-m-d');
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $one_week_ago = date("Y-m-d", strtotime("-7 day"));
        $one_month_ago = date("Y-m-d", strtotime("-30 day"));
        $three_months_ago = date("Y-m-d", strtotime("-90 day"));
        $thismonth = date("Y-m-01");
        
        $month = date('m');
        $year = date('Y');
        if($month==1){
        	$monthnya = 12;
        	$yearnya = $year-1;
        }else{
        	$monthnya = $month-1;
        	$yearnya = $year;
        }
		$lastmonth_firstrange = date($yearnya."-".$monthnya."-01");
		$lastmonth_lastrange = date($yearnya."-".$monthnya."-31");

		if($date_range!=''){
			$date_range = explode('_',$date_range);
			$date_range_first = date($date_range[0]);
			$date_range_last = date($date_range[1]);
		}else{
			$date_range_first = $today;
			$date_range_last = $today;
		}
		

        if($date_filter=='today'){
            $filternya = "and a.created_at BETWEEN '$today 00:00' AND '$today 23:59'";
        }elseif($date_filter=='yesterday'){
            $filternya = "and a.created_at BETWEEN '$yesterday 00:00' AND '$yesterday 23:59'";
        }elseif($date_filter=='7lastdays'){
            $filternya = "and a.created_at BETWEEN '$one_week_ago 00:00' AND '$today 23:59'";
        }elseif($date_filter=='30lastdays'){
            $filternya = "and a.created_at BETWEEN '$one_month_ago 00:00' AND '$today 23:59'";
        }elseif($date_filter=='3months'){
            $filternya = "and a.created_at BETWEEN '$three_months_ago 00:00' AND '$today 23:59'";
        }elseif($date_filter=='thismonth'){
            $filternya = "and a.created_at BETWEEN '$thismonth 00:00' AND '$today 23:59'";
        }elseif($date_filter=='lastmonth'){
            $filternya = "and a.created_at BETWEEN '$lastmonth_firstrange 00:00' AND '$lastmonth_lastrange 23:59'";
        }elseif($date_filter=='daterange'){
            $filternya = "and a.created_at BETWEEN '$date_range_first 00:00' AND '$date_range_last 23:59'";
        }else{
            $filternya = "";
        }

        // and a.created_at BETWEEN '2022-03-28 00:00' AND '2022-04-27 23:59'
    }

    require_once(ROOTDIR_DNA . 'admin/f_download_excel.php');

	wp_die();
	
}
add_action( 'admin_post_download_data_donasi', 'download_donasi' );
add_action( 'admin_post_nopriv_download_data_donasi', 'download_donasi' );


function upload_donasi() {
    if ( ! current_user_can( 'manage_options' ) )
        return;

    global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";

    // $uploadedfile 	= $_POST['info'][0];
    $c_id = $_POST['cid'];

    if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$uploadedfile = $_FILES['file'];
	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
	if ( $movefile ) {
	    
	    // echo "File is valid, and was successfully uploaded.\n";
	    // var_dump( $movefile);
	    require_once(ROOTDIR_DNA . 'admin/f_upload_excel.php');

	    // echo 'success';

	} else {
	    echo "failed";
	}
   
    exit();

}
add_action( 'admin_post_upload_donasi', 'upload_donasi' );
add_action( 'admin_post_nopriv_upload_donasi', 'upload_donasi' );



function download_template_excel() {
    if ( ! current_user_can( 'manage_options' ) )
        return;

    $fileurl = ROOTDIR_DNA.'admin/assets/files/template_upload_donasi.xls';

	header("Content-type:application/xls");
	header('Content-Disposition: attachment; filename=template_upload_donasi.xls');
	readfile( $fileurl );

	exit;
	
}
add_action( 'admin_post_download_template_excel', 'download_template_excel' );
add_action( 'admin_post_nopriv_download_template_excel', 'download_template_excel' );



function djafunction_del_donasi(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_love";

    // FROM INPUT
    $donasi_id 	= $_POST['datanya'][0];

    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $donasi_id ) ) ) {
		// then delete
        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$donasi_id.'" ' ) );
    }
    if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name2.' WHERE donate_id = %d', $donasi_id ) ) ) {
		// then delete campaign update
        $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name2.' WHERE donate_id = "'.$donasi_id.'" ' ) );
    }

	echo 'success';

    wp_die();
}
add_action( 'wp_ajax_djafunction_del_donasi', 'djafunction_del_donasi' );
add_action( 'wp_ajax_nopriv_djafunction_del_donasi', 'djafunction_del_donasi' );



function djafunction_del_confirmation(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_love";

    // FROM INPUT
    $donasi_id 	= $_POST['datanya'][0];

  //   if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name.' WHERE id = %d', $donasi_id ) ) ) {
		// // then delete
  //       $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name.' WHERE id = "'.$donasi_id.'" ' ) );
  //   }
  //   if ( $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM '.$table_name2.' WHERE donate_id = %d', $donasi_id ) ) ) {
		// // then delete campaign update
  //       $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$table_name2.' WHERE donate_id = "'.$donasi_id.'" ' ) );
  //   }

	echo 'success';

    wp_die();
}
add_action( 'wp_ajax_djafunction_del_confirmation', 'djafunction_del_confirmation' );
add_action( 'wp_ajax_nopriv_djafunction_del_confirmation', 'djafunction_del_confirmation' );

