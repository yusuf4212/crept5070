<?php
function donasiaja_readtime($time) { 

	$timestamp = strtotime($time);	
   
    $strTime = array("detik", "menit", "jam", "hari", "bulan", "tahun");
    $length = array("60","60","24","30","12","10");

    $currentTime = time();
    if($currentTime >= $timestamp) {
    	
    	$diff     = time()- $timestamp + (60*60);
		
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
		$diff = $diff / $length[$i];
		}

		$diff = round($diff);
		return $diff . " " . $strTime[$i] . " yang lalu";
    }

}


function donasiaja_load_campaign() {
	
    global $wpdb;
	$table_name = $wpdb->prefix . "dja_campaign";
	$table_name2 = $wpdb->prefix . "dja_donate";
	$table_name3 = $wpdb->prefix . "dja_users";
	$table_name4 = $wpdb->prefix . "dja_shortcode";
	$table_name5 = $wpdb->prefix . "dja_settings";
    
    $id = $_POST['datanya'][0];
    $load_count = $_POST['datanya'][1];
    $uid = $_POST['datanya'][2];

    $firstTwoCharacters = substr($id, 0, 2);

  	$check_campaign = $wpdb->get_results('SELECT * from '.$table_name4.' where s_id="'.$id.'"');

	if($firstTwoCharacters=='u_'){
		$style = 'list';
		$show  = 3;
		$category_id  = 0;
		$loadmore  = 3;
	}elseif($check_campaign==null){
		wp_die();
	}else{
		$style = $check_campaign[0]->s_style;
		$show  = $check_campaign[0]->s_show;
		$category_id  = $check_campaign[0]->s_category;
		$loadmore  = $check_campaign[0]->s_loadmore;
		$button_on  = $check_campaign[0]->s_button_on;
		$button_text  = $check_campaign[0]->s_button_text;
	}

	$start = ($load_count*$show)-$show;
    $limit = $loadmore;

	if($category_id==null || $category_id=='0'){
		if($uid!=''){
			$campaign = $wpdb->get_results("SELECT * FROM $table_name where publish_status='1' and user_id='$uid' ORDER BY id DESC LIMIT $start,$limit ");
		}else{
			$campaign = $wpdb->get_results("SELECT * FROM $table_name where publish_status='1' ORDER BY id DESC LIMIT $start,$limit ");
		}
	}else{
		// example OR : WHERE (category_id='1' and publish_status='1') or (category_id='4' and publish_status='1')
		if( strpos($category_id, ',') !== false ) {

            // ada koma, maka loop. berarti banyak category
            $variable = $category_id;
            $var=explode(',',$variable);
            $count_categoty = count($var);
            $no_category = 1;
            $wherenya = '';
            foreach($var as $value_idnya)
            {
                $wherenya .= "(category_id='$value_idnya' and publish_status='1')";
                if($no_category<$count_categoty){
                    $wherenya .= ' or ';
                }
                $no_category++;
            }

            $campaign = $wpdb->get_results("SELECT * FROM $table_name where $wherenya ORDER BY id DESC LIMIT $start,$limit ");

        }else{

            // gak ada koma, berarti single
            $campaign = $wpdb->get_results("SELECT * FROM $table_name where publish_status='1' and category_id='$category_id' ORDER BY id DESC LIMIT $start,$limit ");
        }
	}

	// Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="theme_color" ORDER BY id ASC');
    $general_theme_color    = json_decode($query_settings[0]->data, true);
    $theme_color 			= $general_theme_color['color'][0];
	$progressbar_color  	= $general_theme_color['color'][1];
	$button_color 			= $general_theme_color['color'][2];
	$button_color2 			= $general_theme_color['color'][3];

    if($theme_color==''){
    	$theme_color = '#009F61';
    }
    if($progressbar_color==''){
    	$progressbar_color = '#009F61';
    }



  	$hasil_list = '';	
  	$hasil_slider = '';
  	$hasil_grid = '';


	if($style=='list' || $style=='slider' || $style=='grid'){

		foreach ($campaign as $value) {

			// Waktu Berakhir
		    $date_now = date('Y-m-d');
		    $datetime1 = new DateTime($date_now);
		    $datetime2 = new DateTime($value->end_date);
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

		    if($value->end_date==null){
		    	$sisa_waktu = '∞';
		    }
		    if($hasil->invert==true){
		    	$sisa_waktu = '<span style="color:#ff6b24;font-style:italic;">sudah&nbsp;berakhir</span>';
		    }


			$total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name2 where campaign_id='$value->campaign_id' and status='1' ")[0];

			if($value->target==0){
				// $persen = 100;
				$persen_width = 100;
			}else{
				// $persen = ($total_donasi->total/$row->target)*100;
				$persen_width = ($total_donasi->total/$value->target)*100;
				if($persen_width>100){
					$persen_width = 100;
				}
			}

			$data_donasinya = '';
            if($total_donasi==null){
                $total_terkumpul = 0;
                $jumlah_donasi = 0;
                $data_donasi = 0;
            }else{
                $total_terkumpul = $total_donasi->total;
                $jumlah_donasi = $total_donasi->jumlah;

                $data_donasi = $wpdb->get_results("SELECT user_id, name FROM $table_name2 where campaign_id='$value->campaign_id' and status='1' ORDER BY id ASC LIMIT 0,3 ");

                // GET INISAL DONATUR / PP URL
                foreach ($data_donasi as $data) {
                	if($data->user_id==null || $data->user_id==0){
                		$first_char = strtoupper(mb_substr($data->name, 0, 1));
                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
                	}else{
                		$first_char = strtoupper(mb_substr($data->name, 0, 1));
                		$user_profile = $wpdb->get_results('SELECT user_pp_img as photo from '.$table_name3.' where user_id="'.$data->user_id.'"');
						if(isset($user_profile[0])){
							if($user_profile==null){
								$first_char = strtoupper(mb_substr($data->name, 0, 1));
		                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
							}else{
								$url_photo = $user_profile[0]->photo;
								if($url_photo==''){
									$first_char = strtoupper(mb_substr($data->name, 0, 1));
			                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
								}else{
									$data_donasinya .= '<img src="'.$url_photo.'" alt="User Image" class="u_image">';
								}
							}
						}else{
							$first_char = strtoupper(mb_substr($data->name, 0, 1));
	                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
						}
                	}
                }
            }

			if($value->image_url==null){
                $image_url = DJA_PLUGIN_URL.'assets/images/cover_donasiaja.jpg';
            }else{
                $image_url = $value->image_url;
                // $image_url = str_replace('localhost', '192.168.0.102', $image_url);
            }

            if($jumlah_donasi==0){
            	$icon_donasi = '
            	<div class="u_campaign u_icon">
				  <div class="campaign_days no_donation" style="padding-top:5px;">'.$sisa_waktu.'</div>
				</div>
				';
            }else{
            	$donasi_plusmore = '';
            	if($jumlah_donasi>=4){
	            	
            		$total_min_three = $jumlah_donasi-3;
            		$donasi_totalnya = shortDisplayNumber($total_min_three).'+';

	            	if(strlen($donasi_totalnya)>=4){
	            		$donasi_totalnya = '<span style="font-size:7px;">'.$donasi_totalnya.'</span>';
	            	}elseif(strlen($donasi_totalnya)==3){
	            		$donasi_totalnya = '<span style="font-size:8px;">'.$donasi_totalnya.'</span>';
	            	}else{
	            		$donasi_totalnya = $donasi_totalnya;
	            	}

	            	// >= 4 char = 7px
	            	// 3 char = 8px
	            	// 2 char = 9px

	            	$donasi_plusmore = '<span class="u_image u_inisial" style="background:'.$theme_color.'">'.$donasi_totalnya.'</span>';
            	}


				$icon_donasi = '
            	<div class="u_campaign u_donasi">
				  <div class="u_photo">
				  	'.$data_donasinya.'
				  	'.$donasi_plusmore.'
				  </div>
				  <div class="campaign_days" style="margin-top:5px;font-size:11px;">'.$sisa_waktu.'</div>
				</div>
				';
            }

            if($value->publish_status=='1'){
                $campaign_url = get_site_url().'/campaign/'.$value->slug;
            }else{
                $campaign_url = get_site_url().'/preview/'.$value->slug;
            }


            // GET PROFILE PICTURE CAMPAIGNER
			$profile = $wpdb->get_results('SELECT user_pp_img as photo, user_verification from '.$table_name3.' where user_id="'.$value->user_id.'"');
			if(isset($profile[0])){
				if($profile==null){
					$profile_photo = DJA_PLUGIN_URL . "assets/images/pp.jpg";
				}else{
					$profile_photo = $profile[0]->photo;
				}
			}else{
				$profile_photo = DJA_PLUGIN_URL . "assets/images/pp.jpg";
			}

			// GET USER NAME
			$args2 = array( 'blog_id' => 0, 'search' => $value->user_id, 'search_columns' => array( 'ID' ) );
            $get_name = get_users( $args2 );

            if($get_name==null){
                $campaigner_name = '-';
            }else{
                $nama_user = str_replace("'", "", $get_name[0]->user_firstname.' '.$get_name[0]->user_lastname);
                $campaigner_name = $nama_user; // nama asli
            }
            // user_firstname

            if($profile[0]->user_verification=='1'){
            	$verified_user = '<div class="verified_checklist"><img alt="Image" src="'.DJA_PLUGIN_URL.'assets/images/check.png"></div>';
            }else{
            	$verified_user = '';
            }

            $button_donasi = '';
            if($button_on=='1'){

            	$text_button = 'Donate Now';
            	if($button_text!=''){
            		$text_button = $button_text;
            	}

            	if($style=='list'){
            		$button_donasi = '
            		<div class="btn_donasi_box">
			        	<div class="btn_donasi">
				       	<button style="background-color:'.$button_color2.';">'.$text_button.'</button>
				       	</div>
				    </div>
				    ';
            	}
            	if($style=='slider'){
            		$button_donasi = '
            		<div class="btn_donasi_box btn_slider">
			        	<div class="btn_donasi">
				       	<button style="background-color:'.$button_color2.';">'.$text_button.'</button>
				       	</div>
				    </div>
				    ';
            	}

            	if($style=='grid'){
            		$button_donasi = '
            		<div class="btn_donasi_box btn_grid">
			        	<div class="btn_donasi">
				       	<button style="background-color:'.$button_color2.';">'.$text_button.'</button>
				       	</div>
				    </div>
				    ';
            	}

            }

            if($style=='list'){
	            $hasil_list .= '
	            <li class="cards__item">
				  <a href="'.$campaign_url.'">
			      <div class="card__">
			        <div class="card__image"><img src="'.$image_url.'"></div>
			        <div class="card__content content_1">
			          <div class="card__title">'.$value->title.'</div>
			        </div>
			        <div class="card__content content_2">
			          <div class="card__text campaigner_name">'.$campaigner_name.$verified_user.'</div>
			          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
			          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
			          '.$icon_donasi.'
			        </div>
			        '.$button_donasi.'
			      </div>
			      </a>
			    </li>
			    ';
			}

			if($style=='slider'){
			    $hasil_slider .= '
				<div class="cards__item">
				  <a href="'.$campaign_url.'">
			      <div class="card__">
			        <div class="card__image"><img src="'.$image_url.'"></div>
			        <div class="card__content content_1">
			          <div class="card__title">'.$value->title.'</div>
			        </div>
			        <div class="card__content content_2">
			          <div class="card__text campaigner_name">'.$campaigner_name.$verified_user.'</div>
			          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
			          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
			          '.$icon_donasi.'
			        </div>
			        '.$button_donasi.'
			      </div>
			      </a>
			    </div>
				';
			}

			if($style=='grid'){
				$hasil_grid .= '
				<li class="cards__item josh">
				  <a href="'.$campaign_url.$pass_param.'" class="btn-shortcode" afia-params="link-target">
			      <div class="card__">
			        <div class="card__image"><img src="'.$image_url.'"></div>
			        <div class="card__content content_1">
			          <div class="card__title">'.$value->title.'</div>
			        </div>
			        <div class="card__content content_2">
			          <div class="card__text campaigner_name">'.$campaigner_name.$verified_user.'</div>
			          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
			          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
			          '.$icon_donasi.'
			        </div>
			        '.$button_donasi.'
			      </div>
			      </a>
			    </li>
				';
			}

		}

	}

	if($style=='list'){
		echo $hasil_list;
	}
	if($style=='slider'){
		echo $hasil_slider;
	}
	if($style=='grid'){
		echo $hasil_grid;
	}


    wp_die();

} 
add_action( 'wp_ajax_donasiaja_load_campaign', 'donasiaja_load_campaign' );
add_action( 'wp_ajax_nopriv_donasiaja_load_campaign', 'donasiaja_load_campaign' );


function donasiaja_load_campaign_search() {
	
    global $wpdb;
	$table_name = $wpdb->prefix . "dja_campaign";
	$table_name2 = $wpdb->prefix . "dja_donate";
	$table_name3 = $wpdb->prefix . "dja_users";
	$table_name4 = $wpdb->prefix . "dja_shortcode";
	$table_name5 = $wpdb->prefix . "dja_settings";
    
    $s = $_POST['datanya'][0];
    $load_count = $_POST['datanya'][1];

    $show  = 5;
    $start = ($load_count*$show)-$show;
    $limit = 5;
    $campaign = $wpdb->get_results("SELECT * FROM $table_name where title like '%$s%' or information like '%$s%' ORDER BY id ASC LIMIT $start,$limit ");

	// Settings
 //    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="theme_color" ORDER BY id ASC');
 //    $general_theme_color    = json_decode($query_settings[0]->data, true);
 //    $theme_color 			= $general_theme_color['color'][0];
	// $progressbar_color  	= $general_theme_color['color'][1];
	// $button_color 			= $general_theme_color['color'][2];


	$hasil_list = '';
	foreach ($campaign as $value) {

            if($value->publish_status=='1'){
                $campaign_url = get_site_url().'/campaign/'.$value->slug;
            }else{
                $campaign_url = get_site_url().'/preview/'.$value->slug;
            }

            if($value->image_url==null){
                $image_url = DJA_PLUGIN_URL.'assets/images/cover_donasiaja.jpg';
            }else{
                $image_url = $value->image_url;
            }

            // GET PROFILE PICTURE CAMPAIGNER
			$profile = $wpdb->get_results('SELECT user_pp_img as photo, user_verification from '.$table_name3.' where user_id="'.$value->user_id.'"');
            if($profile[0]->user_verification=='1'){
            	$verified_user = '<span class="verified_checklist"><img alt="Image" src="'.DJA_PLUGIN_URL.'assets/images/check.png" style="height: 10px;margin-left: 5px;"></span>';
            }else{
            	$verified_user = '';
            }

            // GET USER NAME
			$args2 = array( 'blog_id' => 0, 'search' => $value->user_id, 'search_columns' => array( 'ID' ) );
            $get_name = get_users( $args2 );

            if($get_name==null){
                $campaigner_name = '-';
            }else{
                $nama_user = str_replace("'", "", $get_name[0]->user_firstname.' '.$get_name[0]->user_lastname);
                $campaigner_name = $nama_user; // nama asli
            }

            $hasil_list .= '
            <li class="cards__item">
			  <a href="'.$campaign_url.'">
		      <div class="card__">
		        <div class="card__image"><img src="'.$image_url.'"></div>
		        <div class="card__content content_1">
		          <div class="card__title"><span style="font-size:13px;">'.$value->title.'<br><span style="font-size: 12px;font-weight: normal;">'.$campaigner_name.$verified_user.'</span></span></div>
		          <br>
		        </div>
		      </div>
		      </a>
		    </li>
		    ';
			
		}

	echo $hasil_list;


    wp_die();

} 
add_action( 'wp_ajax_donasiaja_load_campaign_search', 'donasiaja_load_campaign_search' );
add_action( 'wp_ajax_nopriv_donasiaja_load_campaign_search', 'donasiaja_load_campaign_search' );



function action_get_the_user_attachments( $query ) {

    // If we are not seeing the backend we quit.
    if ( !is_admin() ) {
        return;
    }

    /**
     * If it's not a main query type we quit.
     *
     * @link    https://codex.wordpress.org/Function_Reference/is_main_query
     */
    if ( !$query->is_main_query() ) {
        return;
    }

    $current_screen = get_current_screen();

    $current_screen_id = $current_screen->id;

    // If it's not the upload page we quit.
    if ( $current_screen_id != 'upload' ) {
        return;
    }

    $current_user = wp_get_current_user();

    $current_user_id = $current_user->ID;

    $author__in = array(
        $current_user_id
    );

    $query->set( 'author__in', $author__in );

}

add_action( 'pre_get_posts', 'action_get_the_user_attachments', 10 );


// ajax media file
function filter_get_the_user_attachments( $query ) {

    $current_user = wp_get_current_user();

    if ( !$current_user ) {
        return;
    }

    $current_user_id = $current_user->ID;

    $query['author__in'] = array(
        $current_user_id
    );

    return $query;

};

add_filter( 'ajax_query_attachments_args', 'filter_get_the_user_attachments', 10 );


function djafunction_publish_campaign() {
	
    global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";

    // role
    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];
    
    // FROM INPUT
    $title 			= $_POST['datanya'][0];
    $image_url 		= $_POST['datanya'][1];
    $information 	= $_POST['datanya'][2];
    $target 		= $_POST['datanya'][3];
    $end_date 		= $_POST['datanya'][4];
    $location_name 	= $_POST['datanya'][5];
    $location_gmaps = $_POST['datanya'][6];
    $category_id	= $_POST['datanya'][7];
    $form_base		= $_POST['datanya'][8];
    $form_type		= $_POST['datanya'][9];
    $packaged		= $_POST['datanya'][10];
    $packaged_title	= $_POST['datanya'][11];
    $act			= $_POST['datanya'][12];
    $pengeluaran_setting = $_POST['datanya'][13];
    $pengeluaran_title	 = $_POST['datanya'][14];
    $zakat_setting	 = $_POST['datanya'][15];
    $zakat_percent	 = $_POST['datanya'][16];

    $title = str_replace("'", "&#39;", $title); // petik 1
    $title = str_replace('"', "&#34;", $title); // petik 2
    $title = str_replace('\\', '', $title); // backslash

    // $information = str_replace("'", "&#39;", $information); // petik 1
    // $information = str_replace('"', "&#34;", $information); // petik 2
    $information = str_replace('\\', '', $information); // backslash

    // DEFAULT
    $campaign_id = 'dja'.d_randomString(8);
    $slug = d_formatUri($title);
    $publish_status = 1;
    $reached_status = 0;
    $currency = 'IDR';
    $user_id = wp_get_current_user()->ID;
    $fundraiser_id = null;
    $method_status = '{"instant":"0","va":"0","transfer":"0"}';


    if($end_date==''){
    	$end_date = null;
    }else{
    	$end_date = date($_POST['datanya'][4].' 23:59:00');
    }

    // cek slug
    $slug = str_replace('?', '', $slug); // delete ? on slug
    $jumlah_slug = count($wpdb->get_results('SELECT id from '.$table_name.' where slug="'.$slug.'"'));
    if($jumlah_slug>1){
    	$count = $jumlah_slug+1;
    	$slug = $slug.'-'.d_randomString(3);
    }

    // publish status
    if($role=='donatur'){
    	$publish_status = 2; // pending review
    	$form_type = 1; // form type : card
    	$form_base = 0;
    	$pengeluaran_setting = 0;
    	$pengeluaran_title = null;
    }

    if($act=='draft'){
    	$publish_status = 0;
    }

    if(strpos($image_url, 'donasiaja-cover.jpg') !== false) {
	    $image_url = null;
	}

	if($zakat_setting=='0'){
		$zakat_percent = null;
	}

    // ACTION INSERT TO DB
    $wpdb->insert(
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
	            'reached_status'=> $reached_status,
	            'end_date' 		=> $end_date,
	            'form_base'		=> $form_base,
	            'form_type'		=> $form_type,
	            'packaged'		=> $packaged,
	            'packaged_title'=> $packaged_title,
	            'currency' 		=> $currency,
	            'category_id'	=> $category_id,
	            'user_id' 		=> $user_id,
	            'fundraiser_id' => $fundraiser_id,
	            'payment_status'=> 0,
	            'method_status' => $method_status,
	            'bank_account'  => null,
	            'form_status'	=> 0,
	            'form_text'  	=> null,
	            'pengeluaran_setting' => $pengeluaran_setting,
	            'pengeluaran_title'   => $pengeluaran_title,
	            'zakat_setting'   => $zakat_setting,
	            'zakat_percent'   => $zakat_percent,
	            'created_at' 	=> date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );
	
	echo $campaign_id;

    wp_die();

} 
add_action( 'wp_ajax_djafunction_publish_campaign', 'djafunction_publish_campaign' );
add_action( 'wp_ajax_nopriv_djafunction_publish_campaign', 'djafunction_publish_campaign' );


function donasiaja_getIP(){
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];

	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}


function donasiaja_getOS() { 

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform  = "Unknown OS Platform";
    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}


function donasiaja_getBrowser() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser        = "Unknown Browser";
    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}


function donasiaja_getMobDesktop() {

	$useragent = $_SERVER['HTTP_USER_AGENT'];

    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){ 
        return "mobile";
    }else{
        return "desktop";
    }
}


function shortDisplayNumber($num) 
{
    $units = ['', 'K', 'M', 'B', 'T'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1) . $units[$i];
}



function djafunction_set_love(){
	global $wpdb;
    $table_name = $wpdb->prefix . "dja_love";
    $table_name2 = $wpdb->prefix . "dja_settings";

    // FROM INPUT
    $campaign_id 	= $_POST['datanya'][0];
    $donate_id 		= $_POST['datanya'][1];

    $a = donasiaja_getIP();
    $b = donasiaja_getOS();
    $c = donasiaja_getBrowser();
    $d = donasiaja_getMobDesktop();

    $id_login = wp_get_current_user()->ID;

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="max_love" ORDER BY id ASC');
    $max_love = $query_settings[0]->data;

    $row = $wpdb->get_results('SELECT * from '.$table_name.' where ip="'.$a.'" and os="'.$b.'" and browser="'.$c.'" and mobdesktop="'.$d.'" and donate_id="'.$donate_id.'"');
    if($row==null){
    	// create
    	$wpdb->insert(
            $table_name, //table
            array(
	            'campaign_id' 	=> $campaign_id,
	            'donate_id' 	=> $donate_id,
	            'user_id' 		=> $id_login,
	            'love' 			=> 1,
	            'ip' 			=> $a,
	            'os' 			=> $b,
	            'browser' 		=> $c,
	            'mobdesktop' 	=> $d,
	            'created_at' 	=> date("Y-m-d H:i:s")),
            array('%s', '%s') //data format         
        );

    	echo '+1';

    }else{

    	$total_love = $wpdb->get_results('SELECT love from '.$table_name.' where ip="'.$a.'" and os="'.$b.'" and browser="'.$c.'" and mobdesktop="'.$d.'" and donate_id="'.$donate_id.'"')[0];
		$love = $total_love->love;

		if($max_love=='0'){
			// update
			$love = $love+1;
	    	$wpdb->update(
		            $table_name, //table
		            array(
			            'love' 	=> $love
			        ),
		            array('campaign_id' => $campaign_id, 'donate_id' => $donate_id, 'ip' => $a, 'os' => $b, 'browser' => $c, 'mobdesktop' => $d), //where
		            array('%s'), //data format
		            array('%s') //where format    
		        );

	    	echo "+$love";

		}else{
			if($love<=$max_love){

				$love = $love+1;
		    	$wpdb->update(
			            $table_name, //table
			            array(
				            'love' 	=> $love
				        ),
			            array('campaign_id' => $campaign_id, 'donate_id' => $donate_id, 'ip' => $a, 'os' => $b, 'browser' => $c, 'mobdesktop' => $d), //where
			            array('%s'), //data format
			            array('%s') //where format    
			        );

		    	echo "+$love";

			}else{
				echo "cukup";
			}
		}
    	

    }

    wp_die();
}
add_action( 'wp_ajax_djafunction_set_love', 'djafunction_set_love' );
add_action( 'wp_ajax_nopriv_djafunction_set_love', 'djafunction_set_love' );



function getBaseUrl() 
{
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF']; 

    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
    $pathInfo = pathinfo($currentPath); 

    // output: localhost
    $hostName = $_SERVER['HTTP_HOST']; 

    // output: http://
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

    // return: http://localhost/myproject/
    return $protocol.'://'.$hostName.$pathInfo['dirname']."/";
}


function paymentCode($code) 
{
    global $wpdb;
    $table_name 	= $wpdb->prefix . "dja_payment_list";

    $get_payment_name = $wpdb->get_results('SELECT name from '.$table_name.' where code="'.$code.'" ');
    if($get_payment_name!=null){
    	return $get_payment_name[0]->name;
    }
}

