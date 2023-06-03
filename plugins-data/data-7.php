<?php

function djafunction_update_password() {
    global $wpdb;
    $table_name = $wpdb->prefix . "users";

    $id_login = wp_get_current_user()->ID;
    
    // FROM INPUT
    $user_id 	= $_POST['datanya'][0];
    $user_pass_new 	= $_POST['datanya'][1];

    if($id_login==$user_id){
 
	    $hash = wp_hash_password( $user_pass_new );
	    $wpdb->update(
	        $table_name,
	        array(
	            'user_pass'           => $hash
	        ),
	        array( 'ID' => $user_id )
	    );
	 		
	 	clean_user_cache( $user_id );

	 	echo 'success';
	}

	wp_die();

}
add_action( 'wp_ajax_djafunction_update_password', 'djafunction_update_password' );
add_action( 'wp_ajax_nopriv_djafunction_update_password', 'djafunction_update_password' ); 



function djafunction_update_password_user() {
    global $wpdb;
    $table_name = $wpdb->prefix . "users";

    
    // FROM INPUT
    $user_id 	= $_POST['datanya'][0];
    $user_pass_new 	= $_POST['datanya'][1];

 
    $hash = wp_hash_password( $user_pass_new );
    $wpdb->update(
        $table_name,
        array(
            'user_pass'           => $hash
        ),
        array( 'ID' => $user_id )
    );

 	echo 'success';
	

	wp_die();

}
add_action( 'wp_ajax_djafunction_update_password_user', 'djafunction_update_password_user' );
add_action( 'wp_ajax_nopriv_djafunction_update_password_user', 'djafunction_update_password_user' ); 


function josh_table_slip() {
	global $wpdb;

	$table = $wpdb->prefix.'josh_slip';

	$draw		= $_POST['draw'];
	$start		= $_POST['start'];
	$length		= $_POST['length'];
	$search		= $_POST['search']['value'];
	$order_col	= $_POST['order']['0']['column'];
	$order_dir	= $_POST['order']['0']['dir'];
	$date_start = $_POST['date_start'];
	$date_end	= $_POST['date_end'];
	$filter		= $_POST['filter'];
	
	if( $date_start == '') {
		$date_start = date('Y-m-01') . ' 00:00:00';
		$date_end	= date('Y-m-d') . ' 23:59:59';
	} else {
		$date_start = $date_start . ' 00:00:00';
		$date_end = $date_end . ' 23:59:59';
	}

	if( $order_col == 0) $order_colName = 'id';
	elseif( $order_col == 1) $order_colName = 'relawan';
	elseif( $order_col == 2) $order_colName = 'given_date';
	elseif( $order_col == 3) $order_colName = 'program';
	elseif( $order_col == 4) $order_colName = 'type';
	elseif( $order_col == 5) $order_colName = 'platform';
	elseif( $order_col == 7) $order_colName = 'nominal';
	elseif( $order_col == 8) $order_colName = 'bank';
	elseif( $order_col == 9) $order_colName = 'transfer_date';
	elseif( $order_col == 10) $order_colName = 'whatsapp';

	/**
	 * Filter Init
	 * Convert from JSON format to Object and Array!
	 */
	$filter = stripcslashes($filter);
	if ($filter != '') {
		$filter = json_decode($filter);
	} else {
		$filter = '';
	}
	
	/**
	 * FILTER GENERATOR
	 * 
	 * 1. Date Filter
	 */
		$date_sql = " (given_date BETWEEN '".$date_start."' AND '".$date_end."') ";

	/**
	 * 2. Transfer Date
	 */
		if( $filter->transferDate->from != ''){
			// $countTFDateFilter = count((array)$filter->transferDate);
			$TFDateFilter = $filter->transferDate;

			$TFDate_sql = " AND (transfer_date BETWEEN '" . $filter->transferDate->from . "' AND '" . $filter->transferDate->end . "')";
		} else {
			$TFDate_sql = '';
		}

	/**
	 * 3. Program
	 */
	 	if( $filter->program[0] != '') {
			$countProgramFilter = count($filter->program);
			$programFilter = $filter->program;

			$program_sql = ' AND (program=';
			for( $i = 0; $i <= $countProgramFilter - 1; $i++) {
				$program_sql = $program_sql . "'" . $programFilter[$i]->text . "'";

				if( $i < $countProgramFilter - 1) {
					$program_sql = $program_sql . ' or program=';
				}
			}

			$program_sql = $program_sql . ') ';
		} else {
			$program_sql = '';
		}

	/**
	 * 4. Platform
	 */
		if( $filter->platform[0] != '') {
			$countPlatformFilter = count($filter->platform);
			$platformFilter = $filter->platform;

			$platform_sql = ' AND (platform=';
			for( $i = 0; $i <= $countPlatformFilter - 1; $i++) {
				$platform_sql = $platform_sql . "'" . $platformFilter[$i]->text . "'";

				if( $i < $countPlatformFilter - 1) {
					$platform_sql = $platform_sql . ' or platform=';
				}
			}

			$platform_sql = $platform_sql . ') ';
		} else {
			$platform_sql = '';
		}
	
	/**
	 * 5. Type
	 */
		if( $filter->type[0] != '') {
			$countTypeFilter = count($filter->type);
			$typeFilter = $filter->type;

			$type_sql = ' AND (type=';
			for( $i = 0; $i <= $countTypeFilter - 1; $i++) {
				$type_sql = $type_sql . "'" . $typeFilter[$i]->text . "'";

				if( $i < $countTypeFilter -1) {
					$type_sql = $type_sql . ' or type=';
				}
			}

			$type_sql = $type_sql . ') ';
		} else {
			$type_sql = '';
		}

	/**
	 * 6. Relawan
	 */
	 	if( $filter->relawan[0] != '') {
			$countRelawanFilter = count($filter->relawan);
			$relawanFilter = $filter->relawan;
	
			$relawan_sql = ' AND (relawan=';
			for( $i = 0; $i <= $countRelawanFilter - 1; $i++) {
				$relawan_sql = $relawan_sql . "'" . $relawanFilter[$i]->text . "'";
				
				if( $i < $countRelawanFilter - 1) {
					$relawan_sql = $relawan_sql . " or relawan=";
				}
			}

			$relawan_sql = $relawan_sql . ') ';
		} else {
			$relawan_sql = '';
		}


	/**
	 * 7. Bank
	 */
		if( $filter->bank[0] != '') {
			$countBankFilter = count($filter->bank);
			$bankFilter = $filter->bank;

			$bank_sql = ' AND (bank=';
			for( $i = 0; $i <= $countBankFilter - 1; $i++) {
				$bank_sql = $bank_sql . "'" . $bankFilter[$i]->text . "'";

				if( $i < $countBankFilter - 1) {
					$bank_sql = $bank_sql . ' or bank =';
				}
			}

			$bank_sql = $bank_sql . ') ';
		} else {
			$bank_sql = '';
		}

	/**
	 * 8. Search variable
	 */
		if( $search != '') {
			$search_sql = " AND (whatsapp LIKE '%".$search."%' or "."relawan LIKE '%".$search."%' or "."given_date LIKE '%".$search."%' or "."program LIKE '%".$search."%' or "."type LIKE '%".$search."%' or "."platform LIKE '%".$search."%' or "."nominal LIKE '%".$search."%' or "."bank LIKE '%".$search."%' or "."transfer_date LIKE '%".$search."%') ";
		} else {
			$search_sql = '';
		}
	
	/**
	 * Before Filter (recordsTotal)
	 */
	$sql = " SELECT id FROM ".$table." WHERE ".$date_sql;
	$row_before = $wpdb->get_results($sql);
	// $row_before = '';
	
	/**
	 * After Filter (recordsFiltered)
	 */
	$sql = " SELECT id FROM ".$table." WHERE ".$date_sql.$TFDate_sql.$program_sql.$platform_sql.$type_sql.$relawan_sql.$bank_sql.$search_sql;
	$row_filter = $wpdb->get_results($sql);
	// $row_filter = '';

	
	/**
	 * TO PRESENT (with Limit)
	 */
	$sql = " SELECT * FROM ".$table." WHERE ".$date_sql.$TFDate_sql.$program_sql.$platform_sql.$type_sql.$relawan_sql.$bank_sql.$search_sql." ORDER BY ".$order_colName." ".$order_dir." LIMIT ".$start.",".$length;
	$row_present = $wpdb->get_results($sql);
	// $row_present = '';

	/**
	 * GET VALUE DONATIONS
	 * and also formatting to currency
	 */
	if( $row_present != null ) {
		$sql = " SELECT SUM(nominal) as val_donation FROM ".$table." WHERE ".$date_sql.$TFDate_sql.$program_sql.$platform_sql.$type_sql.$relawan_sql.$bank_sql.$search_sql." ORDER BY ".$order_colName." ".$order_dir." LIMIT ".$start.",".$length;
		$value_donation = intval($wpdb->get_results($sql)[0]->val_donation);

		$value_donation = 'Rp ' . number_format( $value_donation, 0, ',', '.' );
	} else {
		$value_donation = 'Rp -';
	}



	/**
	 * Count of array
	 */
	$i = 1;
	$count_before = count($row_before);
	$count_filter = count($row_filter);
	$count_present = count($row_present);
	$noSq = ( $order_dir === 'asc' ) ? $start : $count_filter + 1 - $start;

	/**
	 * 
	 */
	header('content-type: application/json');
	

	$payload = array(
		"draw"				=> $draw,
		"recordsTotal"		=> $count_before,
		"recordsFiltered"	=> $count_filter,
		"order_col"			=> $order_col,
		"order_dir"			=> $order_dir,
		"order_name"		=> $order_colName,
		"date_start"		=> $date_start,
		"date_end"			=> $date_end,
		"filter"			=> $filter->program[0]->text,
		"value_donation"	=> $value_donation,
		"data"				=> array()
	);
		foreach ($row_present as $data) {
			/**
			 * Data Processing
			 */
			( $order_dir == 'asc' ) ? $noSq++ : $noSq--;
			$given_date = "<span>".$data->given_date."</span>";
			if ( $data->relawan == '' ) {
				$relawan = "<span>-</span>";
			} else {
				$relawan = "<span>".$data->relawan."</span>";
			}
			$given_date_f = strtotime($data->given_date);
			$given_date_f = date('m/d/Y', $given_date_f);
			$program	  = "<span>".str_replace(' ', '&nbsp;', $data->program)."</span>";
			$type		  = "<span>".$data->type."</span>";
			$platform	  = "<span>".$data->platform."</span>";
			$amount		  = "Rp".number_format($data->nominal, 0, ',', '.');
			$amount		  = "<span>".$amount."</span>";
			$bank		  = "<span>".str_replace(' ', '&nbsp;', $data->bank)."</span>";
			$transfer_date = "<span>".$data->transfer_date."</span>";
			$transfer_date_f = strtotime($data->transfer_date);
			$transfer_date_f = date('m/d/Y', $transfer_date_f);
			$whatsapp	  = str_replace('+62','0',$data->whatsapp);
			$whatsapp	  = str_replace(' ','',$whatsapp);
			$whatsapp	  = "<span>".$whatsapp."</span>";
			// $given_date_f = date_format(getDate(strtotime($data->given_date)), "Y/m/d H:i:s");
			$buktiSrc	  = explode( '.', $data->slip_address );
			// $tes = $buktiSrc[count($buktiSrc) - 2] . '-abithumbnail.' . $buktiSrc[count($buktiSrc) - 1];
			
			for( $i=0; $i < count( $buktiSrc ); $i++ ) {
				
				if( $i === count( $buktiSrc ) - 1 ) {
					$tes = $tes . '-abithumbnail.';
				} elseif( $i === 0 ) {
					$tes = $buktiSrc[$i];
				} else {
					$tes = $tes . '.' . $buktiSrc[$i];
				}
			}
			$tes = $tes . $buktiSrc[count($buktiSrc)-1];

			$bukti_tf = "<div class='slip-tf-box'><img id='img_$data->id' class='slip-tf img-fluid shadow-sm mx-auto d-block' src='$tes' style='cursor: pointer;' abi-data='$data->slip_address'></div>";

			$remove = '<span>Delete</span>';

			
			$payload['data'][] = array(
				"no"			=> $data->id,
				"no_sq"			=> $noSq,
				"relawan"		=> $relawan,
				"given_date"	=> $given_date,
				"given_date_f"	=> $given_date_f,
				"program"		=> $program,
				"type"			=> $type,
				"platform"		=> $platform,
				"bukti_tf"		=> $bukti_tf,
				"amount"		=> $amount,
				"bank"			=> $bank,
				"transfer_date"	=> $transfer_date,
				"transfer_date_f"=> $transfer_date_f,
				"whatsapp"		=> $whatsapp,
				"dolar_i"		=> $i,
				"img_addr"		=> $data->slip_address,
				"another_img"	=> $tes,
				"remove"		=> $remove
			);
		}
		// echo ']}';

		$ready_send = json_encode( $payload );

		echo $ready_send;


	wp_die();
}
add_action( 'wp_ajax_josh_table_slip', 'josh_table_slip' );
add_action( 'wp_ajax_nopriv_josh_table_slip', 'josh_table_slip' );


function joshfunction_table_change() {
	$id			= $_POST['data']['id'];
	$field		= $_POST['data']['field'];
	$newData	= $_POST['data'][$field];

	global $wpdb;

	$table_slip = $wpdb->prefix.'josh_slip';

	$update = $wpdb->update(
		$table_slip,
		array(
			$field => $newData
		),
		array(
			'id' => $id
		)
	);

	$result = array(
		'dbStat' => $update
	);
	$result = json_encode($result);

	echo $result;

	wp_die();
}
add_action( 'wp_ajax_joshfunction_table_change', 'joshfunction_table_change' );
add_action( 'wp_ajax_nopriv_joshfunction_table_change', 'joshfunction_table_change' );


function joshfunction_table_delete() {
	header( 'Content-Type: application/json');
	global $wpdb;
	$table_slip = $wpdb->prefix.'josh_slip';
	$id		= $_POST['id'];
	$img1	= $_POST['img1'];	// abithumbnail
	$img2	= $_POST['img2'];	// non thumbnail

	// $response = function( $response ) {
	// 	// echo json_encode(
	// 	// 	array("response"	=> "success", "img1"	=> $img1_r, 'exist' => $exist)
	// 	// );
	
	// 	wp_die();
	// };


	/**
	 * Remove data from database
	 */
	$delete = $wpdb->delete(
		$table_slip,
		array( 'id' => $id),
		array( '%d')
	);

	if( $delete === false ) {

		echo json_encode( array(
			'response' => 'failed', 'message' => 'database error'
		) );

		wp_die();

	}

	/**
	 * Remove image file
	 */
	$img1_r = str_replace( 'https://ympb.or.id/', get_home_path(), $img1 );
	$img2_r = str_replace( 'https://ympb.or.id/', get_home_path(), $img2 );

	if( file_exists($img1_r) ) {
		$exist1 = true;

		unlink( $img1_r );
	} else {
		$exist1 = false;
	}

	if( file_exists($img2_r) ) {
		$exist2 = true;

		unlink( $img2_r );
	} else {
		$exist2 = false;
	}

	
	/**
	 * Keep sending response if there is fatal error
	 */
	echo json_encode( array(
		'response' => 'success', 'img1' => $img1_r, 'img2' => $img2_r, 'exist1' => $exist1, 'exist2' => $exist2
		)
	);
	
	wp_die();
}
add_action( 'wp_ajax_joshfunction_table_delete', 'joshfunction_table_delete' );
add_action( 'wp_ajax_nopriv_joshfunction_table_delete', 'joshfunction_table_delete' );


function josh_imgupload() {
	class Josh_Upload_Slip_Input {

		private $uploadReturn;
		
		private $warning;

		private $dateCreate;
		
		public function __construct() {

			$this->nonce();

		}

		private function nonce() {
			$nonce = check_ajax_referer('ajax_josh_s', 'security', false);

			if( $nonce == false ) {

				$this->error( 1, 'Kode Keamanan Gagal! #1', "Ulangi session Anda dan coba lagi. error-sub".$nonce);

			}

			$this->imgFormat();

		}

		private function imgFormat() {

			$arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');

			if( in_array($_FILES['file']['type'], $arr_img_ext)) {

				$upload = wp_upload_bits($_FILES['file']['name'], null, file_get_contents($_FILES['file']['tmp_name']));

				$this->upload( $upload );

			} else {

				$this->error( 2, 'Format Gambar Tidak Mendukung #2', 'Format gambar yang di upload tidak didukung oleh server' );

			}

		}

		private function upload( array $uploadStat ) {

			if( $uploadStat['error'] != false ) {

				$this->error( 3, 'Upload gagal! #3', 'Kesalahan teknis sewaktu menyimpan gambar pada server, silahkan coba lagi.');
				
			} else {
				$this->uploadReturn = $uploadStat;

				$this->dataValidation();

			}
		}

		private function dataValidation() {
			/**
			 * No WhatsApp
			 */
			if( substr( $_REQUEST['noWa'], 0, 2) === '62') {
				$_REQUEST['noWa'] = '0' . substr($_REQUEST['noWa'],2 );
			}


			/**
			 * Finish all continue
			 */
			$this->insertDb();
		}

		private function insertDb() {

			global $wpdb;
			$table_slip = $wpdb->prefix . 'josh_slip';

			$upload = $this->uploadReturn;
			$dateCreate = date("Y-m-d G:i:s");

			$this->dateCreate = $dateCreate;

			/**
			 * Insert for donors data
			 * @since 27 Apr 2023
			 */
			$table_donors = $wpdb->prefix . 'josh_donors';

			$cs_array = array( 'Husna' => 'husna', 'Tisna' => 'fadhilah', 'Meisya' => 'meisya', 'Safina' => 'safina');
			$owned_by = $cs_array[$_REQUEST['relawan']];

			$insert_donors = $wpdb->insert(
				$table_donors,
				array(
					'whatsapp'      => $_REQUEST['noWa'],
					'name'			=> 'Hamba Allah',
					'owned_by'      => $owned_by,
					'since'         => $_REQUEST['givenDate'],
					'add_reason'    => 'non_web',
				)
			);

			

		
			$insert = $wpdb->insert(
				$table_slip,
				array(
					'whatsapp' 		=> $_REQUEST['noWa'],
					'relawan'		=> $_REQUEST['relawan'],
					'given_date'	=> $_REQUEST['givenDate'],
					'program'		=> $_REQUEST['program'],
					'type'			=> $_REQUEST['type'],
					'platform'		=> $_REQUEST['platform'],
					'slip_address'	=> $upload['url'],
					'nominal'		=> $_REQUEST['amount'],
					'bank'			=> $_REQUEST['bank'],
					'transfer_date'	=> $_REQUEST['transferDate'],
					'user_id'		=> intval($_REQUEST['userId']),
					'created_date'	=> $dateCreate
				)
			);

			if( $insert == false ) {

				$this->error( 4, 'Submit Database Gagal! #4', 'Terjadi kesalahan ketika insert ke database.');

			} else {

				$this->convert();

			}

		}

		private function convert() {

			$upload = $this->uploadReturn;

			$imageDir = $upload['file'];
			$imageEditor = wp_get_image_editor( $imageDir);
			// with !
			if( ! is_wp_error( $imageEditor )) {

				$resize = $imageEditor->resize( null, '30');
				// non !
				if( is_wp_error( $resize ) ) {

					$this->warning['resize'] = array( 'Proses resize gagal' => 'terjadi kesalahan ketika mengecilkan ukuran gambar. Thumbnail tidak tersimpan pada server!');

				} else {

					$fileName = $imageEditor->generate_filename( 'abithumbnail');

					$imageEditStat = $imageEditor->save( $fileName );
					// non !
					if( is_wp_error($imageEditStat) ) {

						$this->warning['save'] = array( 'Gagal menyimpan gambar thumbnail' => 'thumbnail gagal telah gagal tersimpan pada server.' );

					}

				}

				$this->warning();

			}

			$this->warning['editor'] = array( 'Kesalahan pada Image Editor', 'Gambar resolusi asli sudah tersimpan, namun proses thumbnailer tidak berfungsi (ImageEditor). Thumbnail tidak tersedia!');
			
			$this->warning();

		}

		/**
		 * 
		 */
		private function error( int $code, string $title, string $message ) {

			echo '{"status":"error","desc":{"title":"'.$title.'","message":"'.$message.'"}}';

			wp_die();
		}

		private function warning() {
			global $wpdb;
			$table = $wpdb->prefix.'josh_table_log';
			$warning = $this->warning;
			$dateCreate = $this->dateCreate;

			if( $warning == null ) {

				$this->success();

			} else {

				$warning = json_encode( $warning );

				$logMenu = array(
					'date_create'	=> $dateCreate,
					'warning_data'	=> $warning
				);

				$logMenu = json_encode( $logMenu );

				$wpdb->insert(
					$table,
					array(
						'log_menu' => 'warning',
						'value' => $logMenu
					)
				);

				$this->success();

			}
		}

		/**
		 * 
		 */
		private function success( ) {

			$upload = $this->uploadReturn;
			$warning = $this->warning;

			// $warning != null ? json_encode( $warning ) : '';
			if( $warning != null ) {
				$warning = json_encode((array) $warning );
			} else {
				$warning = '';
			}

			echo '{"status":"success","desc":{"url":"'.$upload['url'].'"}}';

			wp_die();
		}
	}
	$slipInput = new Josh_Upload_Slip_Input();

	// $nonceVar = '';
	// if( $nonceVar == false ) {
	// 		// $insert_db = josh_insertDB_inputSlip( $_REQUEST, $upload);
	// 		// $josh_action = josh_insertDB_inputSlip();
	// 		if( $upload['error'] != '' || $upload['error'] == null) {
	// 			echo '{"url" : "' . $upload['url'] . '", "error": "' . $upload['error'] . '", "relawan" : "'.$_REQUEST['relawan'].'", "dbStat" : "'.$insert.'", "tableName" : "'.$table_slip.'", "imageDir" : "'.$imageDir.'", "editStat" : "'.$imageEditStat.'"}';
	// 			wp_die();
	// 		} else { // Upload not error
	// 			// echo '{"url" : "' . $upload['url'] . '", "error": "' . $upload['error'] . '", "relawan" : "'.$_REQUEST['relawan'].'", "dbStat" : "'.$insert_db.'"}';
	// 			echo '{"url" : "' . $upload['url'] . '", "error": "' . $upload['error'] . '", "relawan" : "'.$_REQUEST['relawan'].'"}';
	// 			wp_die();
	// 		} // Upload Error
	// } // end nonceVar

}
add_action('wp_ajax_josh_imgupload', 'josh_imgupload');
add_action('wp_ajax_nopriv_josh_imgupload', 'josh_imgupload');


require_once ROOTDIR_DNA . 'core/class/Josh_CRM/main/Josh_CRM.php'; // JOSH_CRM() class
require_once ROOTDIR_DNA . 'core/class/Josh_CRM/right-sheet/Josh_CRM_RSheet.php'; // JOSH_CRM_RSHEET() class
// require_once ROOTDIR_DNA . 'core/class/Josh_CRM.php'; // JOSH_CRM() class
// require_once ROOTDIR_DNA . 'core/class/Josh_CRM_RSheet.php'; // JOSH_CRM_RSHEET() class

function josh_crm_table() {

	$class = new JOSH_CRM();
	// $payload = $class->output();

	header('Content-Type: application/json');
	// echo json_encode( $payload );
	echo json_encode( $class->output() );

	die;
}
add_action( 'wp_ajax_josh_crm_table_1', 'josh_crm_table' );

function josh_crm_table_action() {

	$class = new JOSH_CRM();
	// $payload = $class->output();

	header('Content-Type: application/json');

	echo json_encode( $class->output_action() );

	die;
}
add_action( 'wp_ajax_josh_crm_table_act', 'josh_crm_table_action' );


function josh_crm_table_2() {

	$class = new JOSH_CRM_RSHEET('table');

	header('Content-Type: application/json');

	echo json_encode( $class->t_output() );

	die;
}
add_action( 'wp_ajax_josh_crm_table_2', 'josh_crm_table_2' );


function josh_crm_chart() {

	$class = new JOSH_CRM_RSHEET('chart');

	header( 'Content-Type: application/json' );

	echo json_encode($class->c_output());

	die;
}
add_action( 'wp_ajax_josh_crm_chart', 'josh_crm_chart' );

function josh_crm_donors() {

	$class = new JOSH_CRM_RSHEET('allData');

	header( 'Content-Type: application/json' );

	// echo json_encode(array(
	// 	'status'	=> 'success',
	// 	'data'	=> array(
	// 		'chk'			=> 'chk',
	// 		'nama'			=> 'Muhammad Asrofi',
	// 		'phone'			=> '081235487',
	// 		'category'		=> 'Donatur Tetap',
	// 		'program'		=> 'SS, WMAQ, JB, 2+',
	// 		'email'			=> 'asrofi@testmail.com',
	// 		'user'			=> 'Tidak',
	// 		'payment'		=> 'Bank Transfer',
	// 		'ltv'			=> 'Rp 150.000',
	// 		'adv'			=> 'Rp 25.000',
	// 		'dvol'			=> '5',
	// 		'dibuat'		=> '25 Desember 2023 ',
	// 		'dibuat_'		=> '4 bulan lalu',
	// 		'kota'			=> 'Surabaya',
	// 		'oName'			=> 'Husna',
	// 		'oEmail'		=> '(husna@ympb.or.id)'
	// 	)
	// ));

	echo json_encode($class->a_output());

	die;
}
add_action( 'wp_ajax_josh_crm_donors', 'josh_crm_donors' );

function dja_get_data_donasi(){

	global $wpdb;
    $table_name = $wpdb->prefix . "dja_campaign";
    $table_name2 = $wpdb->prefix . "dja_category";
    $table_name3 = $wpdb->prefix . "dja_campaign_update";
    $table_name4 = $wpdb->prefix . "dja_donate";
    $table_name5 = $wpdb->prefix . "dja_settings";
    $table_name6 = $wpdb->prefix . "dja_users";
    $table_name7 = $wpdb->prefix . "users";
    $table_name8 = $wpdb->prefix . "dja_payment_log";

    date_default_timezone_set('Asia/jakarta');

    $startlist 	  = $_POST['start'];
    $length 	  = $_POST['length'];
    $draw 		  = $_POST['draw'];
    $c_id 		  = $_POST['c_id'];
    $date_filter  = $_POST['date_filter'];
    $date_range   = $_POST['date_range'];
    $search_value = $_POST['search']['value'];
    $dashMode     = $_POST['mode'];
	$adsMode	  = $_POST['ads'];

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="form_setting" or type="btn_followup" or type="text_f1" or type="text_f2" or type="text_f3" or type="text_f4" or type="text_f5" or type="text_received" or type="text_received_status" or type="wanotif_on_dashboard"  ORDER BY id ASC');
    $form_setting = $query_settings[0]->data;
    $btn_followup = $query_settings[1]->data;
    $text_f1 = $query_settings[2]->data;
    $text_f2 = $query_settings[3]->data;
    $text_f3 = $query_settings[4]->data;
    $text_f4 = $query_settings[5]->data;
    $text_f5 = $query_settings[6]->data;
    $text_received = $query_settings[7]->data;
    $text_received_status = $query_settings[8]->data;
    $wanotif_on_dashboard = $query_settings[9]->data;

    $cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];
    $id_login = wp_get_current_user()->ID;


    if($c_id=='all'){
    	$c_id = null;
    }

    $limit = '';
    if($length==-1){
		$limit = '';
	}else{
		$limit = "LIMIT $startlist,$length";
	}


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

    /**
     * DASHBOARD MODE ----
     * dfr or non
     * 
     * @since 7 March 2023
     */
    if( isset($dashMode) ) {
        if($dashMode == 'dfr' ) {
            $modeFilter = " and (ref IS NULL) ";
        }
        else if ( $dashMode == 'non' ) {
            $modeFilter = " and (ref IS NOT NULL) ";
        }
    
        if( $filternya == "" ) {
            $filternya = $modeFilter;
        } else {
            $filternya = $filternya . $modeFilter;
        }
    }

	/**
	 * ADS MODE ----
	 * from ads or not
	 * 
	 * @since 30 Mar 2023
	 */
	if( $adsMode == 'true' ) {
		$adsFilter = " and (utm_source='ig' or utm_source='fb' or utm_source='an') ";

		if( $filternya == "" ) {
			$filternya = $adsFilter;
		} else {
			$filternya = $filternya . $adsFilter;
		}
	}
	// if( isset($adsMode) ) {
	// }

    // if( $filternya == "" ) {

    // }


    $search_filter = '';
    if($search_value!=''){
    	$search_value2 = '';
    	if(strtolower($search_value)=='waiting'){
    		$search_value2 = '0';
    	}
    	if(strtolower($search_value)=='received'){
    		$search_value2 = '1';
    	}

    	if($search_value2=='1' || $search_value2=='0'){
    		$search_filter = "and (name like '%$search_value%' or whatsapp like '%$search_value%' or email like '%$search_value%' or comment like '%$search_value%' or nominal like '%$search_value%' or invoice_id like '%$search_value%' or process_by like '%$search_value%' or status like '%$search_value2%') ";
    	}else{
    		$search_filter = "and (name like '%$search_value%' or whatsapp like '%$search_value%' or email like '%$search_value%' or comment like '%$search_value%' or nominal like '%$search_value%' or invoice_id like '%$search_value%' or process_by like '%$search_value%' or utm_source like '%$search_value%' or utm_medium like '%$search_value%' or utm_campaign like '%$search_value%' or utm_term like '%$search_value%' or utm_content like '%$search_value%') ";
    	}
    	
    }
    
    $data_check = 0;
    if($role=='donatur'){ // if this is DONATUR

        if($c_id!=null){ // Campaign ID isi dan ada

            // cek c_id ada dan beneran donatur ini yang punya gak
            // klo iya lanjut, klo gak ya no data

            $total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name4 a
            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
            WHERE c.campaign_id = '$c_id' and a.status='1' and c.user_id = '$id_login' $search_filter $filternya ")[0];

            $nominal_donasi_terkumpul = $total_donasi->total;
            $jumlah_donasi_terkumpul = $total_donasi->jumlah;

            $total_donasi_semua = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name4 a 
            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
            WHERE c.campaign_id = '$c_id' and c.user_id = '$id_login' $search_filter $filternya ")[0];

            $jumlah_donasi_semua = $total_donasi_semua->jumlah;

            if($jumlah_donasi_semua>=1){
                $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
            }else{
                $konversi = 0;
            }


            // GET DATA DONASI
            $data_donasi = $wpdb->get_results("
            SELECT a.*, b.user_randid, b.user_pp_img, c.title, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
            LEFT JOIN $table_name6 b on a.user_id = b.user_id
            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id
            WHERE c.campaign_id = '$c_id' and c.user_id = '$id_login'
            $search_filter $filternya
            ORDER BY a.id DESC $limit");

            $jumlah_total = $wpdb->get_results("
            SELECT count(a.id) as idnya from $table_name4 a 
            LEFT JOIN $table_name6 b on a.user_id = b.user_id
            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
            WHERE c.campaign_id = '$c_id' and c.user_id = '$id_login'
            $search_filter $filternya ORDER BY a.id DESC")[0]->idnya;


        }else{


            $total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name4 a
            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
            WHERE a.status='1' and c.user_id = '$id_login' $search_filter $filternya ")[0];

            $nominal_donasi_terkumpul = $total_donasi->total;
            $jumlah_donasi_terkumpul = $total_donasi->jumlah;

            $total_donasi_semua = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name4 a 
            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
            WHERE c.user_id = '$id_login' $search_filter $filternya ")[0];

            $jumlah_donasi_semua = $total_donasi_semua->jumlah;

            if($jumlah_donasi_semua>=1){
                $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
            }else{
                $konversi = 0;
            }


            // GET DATA DONASI
            $data_donasi = $wpdb->get_results("
            SELECT a.user_id as uid, a.publish_status, a.slug, a.title, b.*, c.user_pp_img FROM $table_name a 
            left JOIN $table_name4 b ON b.campaign_id = a.campaign_id
            left JOIN $table_name6 c ON c.user_id = b.user_id
            WHERE a.user_id = $id_login and b.id!=''
            $search_filter $filternya
            ORDER BY a.id DESC $limit");

            $jumlah_total = $wpdb->get_results("
            SELECT count(b.id) as idnya FROM $table_name a 
            left JOIN $table_name4 b ON b.campaign_id = a.campaign_id
            left JOIN $table_name6 c ON c.user_id = b.user_id
            WHERE a.user_id = $id_login and b.id!=''
            $search_filter $filternya
            ORDER BY a.id DESC")[0]->idnya;

        }
        
        
    }else{

        if($c_id!=null){ // Campaign ID isi dan ada

            // STATISTICS - Get Data CAMPAIGN
            // $rows = $wpdb->get_results("SELECT * from $table_name where campaign_id = '$c_id' ORDER BY id DESC");

        	// GET DATA FROM CS
            if($role=='cs'){

            	$data_check = 9;

            	$total_donasi_cs = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name4 a
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE c.campaign_id = '$c_id' and a.status='1' and a.cs_id = '$id_login' $search_filter $filternya ")[0];

	            $nominal_donasi_terkumpul_cs = $total_donasi_cs->total;
	            $jumlah_donasi_terkumpul_cs = $total_donasi_cs->jumlah;

	            $total_donasi_semua_cs = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name4 a 
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE c.campaign_id = '$c_id' and a.cs_id = '$id_login' $search_filter $filternya ")[0];

	            $jumlah_donasi_semua_cs = $total_donasi_semua_cs->jumlah;

	            if($jumlah_donasi_semua_cs>=1){
	                $konversi_cs = $jumlah_donasi_terkumpul_cs/$jumlah_donasi_semua_cs;
	            }else{
	                $konversi_cs = 0;
	            }


	            // GET DATA DONASI
	            $data_donasi_cs = $wpdb->get_results("
	            SELECT a.*, b.user_randid, b.user_pp_img, c.title, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
	            LEFT JOIN $table_name6 b on a.user_id = b.user_id
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id
	            WHERE c.campaign_id = '$c_id' and a.cs_id = '$id_login'
	            $search_filter $filternya
	            ORDER BY a.id DESC $limit");

	            $jumlah_total_cs = $wpdb->get_results("
	            SELECT count(a.id) as idnya from $table_name4 a 
	            LEFT JOIN $table_name6 b on a.user_id = b.user_id
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE c.campaign_id = '$c_id' and a.cs_id = '$id_login'
	            $search_filter $filternya ORDER BY a.id DESC")[0]->idnya;

	            // Normalisasi, data di set dari data si CS
	            $data_donasi = $data_donasi_cs;
	            $jumlah_total = $jumlah_total_cs;


            }else{

            	$total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name4 a
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE c.campaign_id = '$c_id' and a.status='1' $search_filter $filternya ")[0];
	            $nominal_donasi_terkumpul = $total_donasi->total;
	            $jumlah_donasi_terkumpul = $total_donasi->jumlah;

	            $total_donasi_semua = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name4 a 
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE c.campaign_id = '$c_id' $search_filter $filternya ")[0];

	            $jumlah_donasi_semua = $total_donasi_semua->jumlah;

	            if($jumlah_donasi_semua>=1){
	                $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
	            }else{
	                $konversi = 0;
	            }


	            // GET DATA DONASI
	            $data_donasi = $wpdb->get_results("
	            SELECT a.*, d.user_randid, d.user_pp_img, c.title, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
	            LEFT JOIN $table_name7 b on a.user_id = b.id
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            LEFT JOIN $table_name6 d on b.id = d.user_id
	            WHERE c.campaign_id = '$c_id'
	            $search_filter $filternya
	            ORDER BY a.id DESC $limit");

	            $jumlah_total = $wpdb->get_results("
	            SELECT count(a.id) as idnya from $table_name4 a 
	            LEFT JOIN $table_name7 b on a.user_id = b.id
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE c.campaign_id = '$c_id'
	        	$search_filter $filternya ORDER BY a.id DESC")[0]->idnya;
            }
            


        }else{


            // GET DATA FROM CS
            if($role=='cs'){

            	// $data_check = 8;

            	$total_donasi_cs = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name4 a
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE a.status='1' and a.cs_id = '$id_login' $search_filter $filternya ")[0];

	            $nominal_donasi_terkumpul_cs = $total_donasi_cs->total;
	            $jumlah_donasi_terkumpul_cs = $total_donasi_cs->jumlah;

	            $total_donasi_semua_cs = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name4 a 
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE a.cs_id = '$id_login' $search_filter $filternya")[0];

	            $jumlah_donasi_semua_cs = $total_donasi_semua_cs->jumlah;

	            if($jumlah_donasi_semua_cs>=1){
	                $konversi_cs = $jumlah_donasi_terkumpul_cs/$jumlah_donasi_semua_cs;
	            }else{
	                $konversi_cs = 0;
	            }


	            // GET DATA DONASI
	            $data_donasi_cs = $wpdb->get_results("
	            SELECT a.*, b.user_randid, b.user_pp_img, c.title, c.abbvr, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
	            LEFT JOIN $table_name6 b on a.user_id = b.user_id
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id
	            WHERE c.campaign_id != '' and a.cs_id = '$id_login'
	            $search_filter $filternya
	            ORDER BY a.id DESC $limit");

	            $jumlah_total_cs = $wpdb->get_results("
	            SELECT count(a.id) as idnya from $table_name4 a 
	            LEFT JOIN $table_name6 b on a.user_id = b.user_id
	            LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	            WHERE c.campaign_id != '' and a.cs_id = '$id_login'
	            $search_filter $filternya ORDER BY a.id DESC")[0]->idnya;

	            // Normalisasi, data di set dari data si CS
	            $data_donasi = $data_donasi_cs;
	            $jumlah_total = $jumlah_total_cs;

            }else{

            	// STATISTICS - Get Data CAMPAIGN
	            // $rows = $wpdb->get_results("SELECT * from $table_name ORDER BY id DESC");

	            $total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(id) as jumlah FROM $table_name4 a 
	            	WHERE status='1' $search_filter $filternya ")[0];
	            $nominal_donasi_terkumpul = $total_donasi->total;
	            $jumlah_donasi_terkumpul = $total_donasi->jumlah;

	            $total_donasi_semua = $wpdb->get_results("SELECT a.*, COUNT(a.id) as jumlah FROM $table_name4 a 
	            	WHERE a.id !='' $search_filter $filternya ");
	            $jumlah_donasi_semua = $total_donasi_semua[0]->jumlah;

	            if($jumlah_donasi_semua>=1){
	                $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
	            }else{
	                $konversi = 0;
	            }

	            $data_donasi = $wpdb->get_results("
	                SELECT a.*, d.user_randid, d.user_pp_img, c.title, c.abbvr, c.campaign_id, c.slug, c.publish_status from $table_name4 a 
	                LEFT JOIN $table_name7 b on a.user_id = b.id
	                LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	                LEFT JOIN $table_name6 d on b.id = d.user_id
	                WHERE a.id != ''
	                $search_filter $filternya
	                ORDER BY a.id DESC $limit");

	            $jumlah_total = $wpdb->get_results("
	                SELECT count(a.id) as idnya from $table_name4 a 
	                LEFT JOIN $table_name7 b on a.user_id = b.id
	                LEFT JOIN $table_name c on a.campaign_id = c.campaign_id 
	                WHERE a.id != '' $search_filter $filternya ORDER BY a.id DESC")[0]->idnya;
            }


        }

    }


    // Statistic
    // Statistic from CS
    if($role=='cs'){

    	$totalDonasi = 0;
	    $jumlahDonasi = '0&nbsp;Donasi';
	    $jumlahDonasiTerkumpul = '0&nbsp;Donasi';
	    $konversi = '0&nbsp;%';

	    $totalDonasi_cs = number_format($nominal_donasi_terkumpul_cs,0,",",".");
	    $jumlahDonasi_cs = number_format($jumlah_donasi_semua_cs,0,",",".").'&nbsp;Donasi';
	    $jumlahDonasiTerkumpul_cs = number_format($jumlah_donasi_terkumpul_cs,0,",",".").'&nbsp;Donasi';
	    $konversi_cs = $konversi_cs*100;
	    $konversi_cs = number_format($konversi_cs,1,",",".").'&nbsp;%';

	}else{

		$totalDonasi = number_format($nominal_donasi_terkumpul,0,",",".");
	    // $jumlahDonasi = number_format($jumlah_donasi_semua,0,",",".").'&nbsp;Donasi';
		$jumlahDonasi = number_format($jumlah_donasi_terkumpul,0,",",".").'&nbsp;Order';
	    // $jumlahDonasiTerkumpul = 'Terkumpul&nbsp;<b style='."'".'color: #313f68;'."'".'>'.number_format($jumlah_donasi_terkumpul,0,",",".").'</b> dari <b style='."'".'color: #313f68;'."'".'>'.number_format($jumlah_donasi_semua,0,",",".").'</b>&nbsp;Donasi';

		$jumlahDonasiTerkumpul = 'Terverifikasi &nbsp;<b style='."'".'color: #313f68;'."'".'>'.number_format($jumlah_donasi_terkumpul,0,",",".").'</b> dari <b style='."'".'color: #313f68;'."'".'>'.number_format($jumlah_donasi_semua,0,",",".").'</b>&nbsp;Order';

	    $konversi = $konversi*100;
	    $konversi = number_format($konversi,1,",",".").'&nbsp;%';

		$totalDonasi_cs = 0;
	    $jumlahDonasi_cs = '0&nbsp;Donasi';
	    $jumlahDonasiTerkumpul_cs = '0&nbsp;Donasi';
	    $konversi_cs = '0&nbsp;%';
	}
    


	$no = 1+$startlist;
	$len = count($data_donasi)+$startlist;
	             
		echo '
		{
			"draw": '.$draw.',
			"recordsTotal": '.$jumlah_total.',
			"recordsFiltered": '.$jumlah_total.',
			"totalDonasi": "'.$totalDonasi.'",
			"totalDonasiCS": "'.$totalDonasi_cs.'",
			"jumlahDonasi": "'.$jumlahDonasi.'",
			"jumlahDonasiCS": "'.$jumlahDonasi_cs.'",
			"jumlahDonasiTerkumpul": "'.$jumlahDonasiTerkumpul.'",
			"jumlahDonasiTerkumpulCS": "'.$jumlahDonasiTerkumpul_cs.'",
			"konversi": "'.$konversi.'",
			"konversiCS": "'.$konversi_cs.'",
			"data": [';

			  	foreach ($data_donasi as $row) {

			  		if($row->publish_status=='1'){
                        $campaign_url = get_site_url().'/campaign/';
                    }else{
                        $campaign_url = get_site_url().'/preview/';
                    }
                    

                    $button_followupnya = '';
                    for ($i = 1; $i <= $btn_followup; $i++){
                        
                        $var = 'f'.$i;
                        if($row->$var=='1'){$$var = 'sent';}else{$$var = '';}

                        $button_followupnya .= '<div><button type='."'".'button'."'".' data-id='."'".$row->id."'".' data-followup='."'".$i."'".' class='."'".'btn btn-primary btn-followup '.$$var.' followup'.$i."'".' title='."'".'Followup '.$i."'".' style='."'".'margin-right:3px;'."'".'><i class='."'".'fab fa-whatsapp'."'".'></i><span style='."'".'margin-left: 3px;'."'".'>'.$i.'</span></button></div><div>'.$row->status_sent_order.'</div>';
                    }

                    $user_info = get_userdata($row->user_id);
                    if($user_info!=null){
                        $fullname = $user_info->first_name.' '.$user_info->last_name;
                    }

                    $time_donate = date('Y-m-d H:i:s',strtotime('+0 hour',strtotime($row->created_at)));

                    $readtime = new humanReadtime();
                    $a = $readtime->dja_human_time($time_donate);

                    // GET DATA CS
				    $user_info_cs = get_userdata($row->cs_id);
				    if($user_info_cs!=null){
				    	if($user_info_cs->last_name==''){
					    	$cs_name = '<span id='."'".'cs_'.$row->id."'".'>'.$user_info_cs->first_name.'</span>';
					    }else{
					    	$cs_name = '<span id='."'".'cs_'.$row->id."'".'>'.$user_info_cs->first_name.'&nbsp;'.$user_info_cs->last_name.'</span>';
					    }
				    }else{
				    	$cs_name = '<span id='."'".'cs_'.$row->id."'".'>-</span>';
				    }

                    // Profile Picture
                    if($row->user_id!='0') {

                    	if($row->user_pp_img=='') {
                    		$pp = '<a id='."'".'link_pp_'.$row->id."'".' href='."'".home_url().'/profile/'.$row->user_randid."'".'  target='."'".'_blank'."'".'><img alt='."''".' id='."'".'pp_'.$row->id."'".' src='."'".DJA_PLUGIN_URL.'assets/images/pp.jpg'."'".' class='."'".'rounded-circle thumb-md'."'".' style='."'".'border: 1px solid #dde4ec;height: 38px;width: 38px;margin-top: -4px;max-width: 38px'."'".'></a>';
                    	}else{
                    		$pp ='<a id='."'".'link_pp_'.$row->id."'".' href='."'".home_url().'/profile/'.$row->user_randid."'".' target='."'".'_blank'."'".'><img alt='."''".' id='."'".'pp_'.$row->id."'".' src='."'".$row->user_pp_img."'".' class='."'".'rounded-circle thumb-md'."'".' style='."'".'border: 1px solid #dde4ec;height: 38px;width: 38px;margin-top: -4px;max-width: 38px'."'".'></a>';
                    	}
                    }else{
                    	if($row->user_pp_img=='') {
                    		$pp = '<img alt='."''".' id='."'".'pp_'.$row->id."'".' src='."'".DJA_PLUGIN_URL.'assets/images/pp.jpg'."'".' class='."'".'rounded-circle thumb-md'."'".' style='."'".'border: 1px solid #dde4ec;height: 38px;width: 38px;margin-top: -4px;max-width: 38px'."'".'>';
                    	}else{
                    		$pp ='<img alt='."''".' id='."'".'pp_'.$row->id."'".' src='."'".$row->user_pp_img.'" class='."'".'rounded-circle thumb-md'."'".' style='."'".'border: 1px solid #dde4ec;height: 38px;width: 38px;margin-top: -4px;max-width: 38px'."'".'>';
                    	}
                    }


                    // Donatur Name
                    if($row->user_id!='0'){
                    	$donatur_name = '<span id='."'".'name_'.$row->id."'".'><a href='."'".home_url().'/profile/'.$row->user_randid."'".' target='."'".'_blank'."'".'>'.dja_handling_character($row->name).'</a></span>';
                    }else{
                    	$donatur_name = '<span id='."'".'name_'.$row->id."'".'>'.dja_handling_character($row->name).'</span>';
                    }

                    // Whatsapp
                    $whatsapp = '<span id='."'".'whatsapp_'.$row->id."'".'>'.dja_handling_character($row->whatsapp).'</span>';
                    
                    // Nominal Donasi
                    if($role=='donatur'){
                    	$nominal_donasi = '<span id='."'".'nominal_'.$row->id."'".'>Rp&nbsp;'.number_format($row->nominal,0,",",".").'</span>';
	                }else{
	                	if($row->payment_code=='ovo'){
	                		$margin_left = 'margin-left:-6px;';
	                	}elseif($row->payment_code=='dana' || $row->payment_code=='bca' || $row->payment_code=='bni' || $row->payment_code=='qris' || $row->payment_code=='gopay'){
	                		$margin_left = 'margin-left:-3px;';
	                	}else{
	                		$margin_left = 'margin-left:-1px;';
	                	}
	                	//$nominal_donasi = '<div class='."'img_payment_code'".'><img src='."'".DJA_PLUGIN_URL.'assets/images/bank/'.$row->payment_code.'.png'."'".' style='."'".'width:35px;'.$margin_left.'margin-right: 4px;margin-top: -1px;'."'".'></div><span id='."'".'nominal_'.$row->id."'".'>Rp&nbsp;'.number_format($row->nominal,0,",",".").'</span>';
						$nominal_donasi = '<span id='."'".'nominal_'.$row->id."'".'>Rp&nbsp;'.number_format($row->nominal,0,",",".").'</span>';
	                }

                    $get_log = $wpdb->get_results("SELECT * FROM $table_name8 where id_donate='$row->id' ");
				    if($get_log!=null){
				    	if(strtolower($get_log[0]->message)=='success'){
				    		$style_inv_color = '';
				    		$title_payment_gateway = "title='Show Detail'";
				    	}else{
				    		$style_inv_color = 'color:#EF4D56 !important;';
				    		$title_payment_gateway = "title='Payment Gateway Error'";
				    	}
				    }else{
				    	$style_inv_color = '';
				    	$title_payment_gateway = "title='Show Detail'";
				    }

                    $donasi_payment_info = '';
                    $img_confirmation = '';
                    if($role!='donatur'){

                    	// image confirmation
	                    if($row->img_confirmation_url!=''){
	                    	$status_img = '';
	                    	if($row->img_confirmation_status=='1'){
	                    		$status_img = 'status_check';
	                    	}
	                    	$img_confirmation = '<button href='."'".'javascript:;'."'".' class='."'".'detail_donasi img_confirmation '.$status_img."'".' data-id='."'".$row->id."'".' title='."'".'Check Confirmation'."'".' style='."'".''."'".'><i class='."'".'mdi mdi-checkbox-marked-circle-outline'."'".' style='."color:#36BD47;".'></i><div style='."color:#FFF;padding-left:15px;font-size:9px;".'>Confirmation</div></button></div>';
		                }

		                // info invoice
	                    $info_invoice = '<br><a href='."'".'javascript:;'."'".' class='."'".'detail_donasi'."'".' data-id='."'".$row->id."'".'><i class='."'".'mdi mdi-file-document-box'."'".' style='."'".$style_inv_color."'".'></i><span style='."'".$style_inv_color."padding-top:2px;'".' '.$title_payment_gateway.'>'.$row->invoice_id.'</span></a>';

	                    	$info_payment_confirmed = '';
	                        if($row->process_by=='moota'){
	                        	$info_payment_confirmed = '<br><p style='."'".'font-size:10px;color:#91a2b0;margin-bottom: 0;'."'".'><img src='."'".DJA_PLUGIN_URL.'admin/icons/moota.png'."'".' style='."'".'width: 10px;margin-left:1px;margin-right: 4px;margin-top: 0px;'."'".'>Moota</p>';
	                        }elseif($row->process_by=='ipaymu'){
	                        	$info_payment_confirmed = '<br><p style='."'".'font-size:10px;color:#91a2b0;margin-bottom: 0;'."'".'><img src='."'".DJA_PLUGIN_URL.'admin/icons/ipaymu.png'."'".' style='."'".'width: 10px;margin-left:1px;margin-right: 4px;margin-top: -1px;'."'".'>iPaymu</p>';
	                        }elseif($row->process_by=='tripay'){
	                        	$info_payment_confirmed = '<br><p style='."'".'font-size:10px;color:#91a2b0;margin-bottom: 0;'."'".'><img src='."'".DJA_PLUGIN_URL.'admin/icons/tripay.png'."'".' style='."'".'width: 10px;margin-left:1px;margin-right: 4px;margin-top: -1px;'."'".'>Tripay</p>';
	                        }elseif($row->process_by=='midtrans'){
	                        	$info_payment_confirmed = '<br><p style='."'".'font-size:10px;color:#91a2b0;margin-bottom: 0;'."'".'><img src='."'".DJA_PLUGIN_URL.'admin/icons/midtrans.png'."'".' style='."'".'width: 10px;margin-left:1px;margin-right: 4px;margin-top: -1px;'."'".'>Midtrans</p>';
	                        }elseif($row->process_by=='xendit'){
	                        	$info_payment_confirmed = '<br><p style='."'".'font-size:10px;color:#91a2b0;margin-bottom: 0;'."'".'><img src='."'".DJA_PLUGIN_URL.'admin/icons/xendit.png'."'".' style='."'".'width: 10px;margin-left:1px;margin-right: 4px;margin-top: -1px;'."'".'>Xendit</p>';
	                        }else{ }

	                    $donasi_payment_info = $info_invoice.$info_payment_confirmed;

                    }


                    // Title Campaign
                    //$title_campaign = '<a href='."'".$campaign_url.$row->slug."'".' title='."Show&nbsp;Program".' target='."'".'_blank'."'".'>'.dja_handling_character($row->title).'</a>';
					$title_campaign = '<span>'.dja_handling_character($row->abbvr).'</span>';

                    // Date
                    $datenya = new DateTime($row->created_at);
                    $date_donasi = '<span class=date_donasi>'.$datenya->format('d').'&nbsp;'.$datenya->format('M').',&nbsp;'.$datenya->format('Y').'&nbsp;-&nbsp;'.$datenya->format('H').':'.$datenya->format('i').':'.$datenya->format('s').'</span><br><span style='."'".'font-size:10px;color:#91a2b0;'."'".'>'.$a.'<span>';

                    // Payment Status
                    if($role=='donatur'){
                        if($row->status=='1'){
                            $status = '<span class='."'".'active-status p_received'."'".'>Received</span>';
                        }else{
                            $status = '<span class='."'".'active-status p_waiting'."'".'>Waiting</span>';
                        }
                    }else{
                        if($row->status=='1'){
                            $status = 'received';
                            $checked = 'checked='."''";
                        }else{
                            $status = 'waiting';
                            $checked = '';
                        }
                    }

                    if($role=='donatur'){
                        $payment_status = $status;
                    }else{
                    	$payment_status = '<div><div title='."Update&nbsp;Payment".' id='."'".'payment_'.$row->id."'".' class='."'".'custom-control custom-switch set_payment '.$status."'".'><input type='."'".'checkbox'."'".' class='."'".'custom-control-input set_payment_status'."'".' id='."'".'customSwitchSuccess_'.$row->id."'".' '.$checked.' data-id='."'".$row->id."'".' data-invoiceid='."'".$row->invoice_id."'".'><label class='."'".'custom-control-label'."'".' for='."'".'customSwitchSuccess_'.$row->id."'".' style='."'".'font-size: 11px;'."'".'><span>'.ucfirst($status).'</span></label></div>';

                    }

                    //Reference (Duta)
                    $refValue = $row->ref;

                    // Action
                    // $button_action = '<button type='."'".'button'."'".' class='."'".'btn btn-outline-danger waves-effect waves-light btn-sm del_donasi'."'".' data-id='."'".$row->id."'".' title='."'".'Delete Donasi'."'".'><i class='."'".'mdi mdi-trash-can mr-2'."'".' style='."'".'margin: 0 4px !important;'."'".'></i></button>';
                    $button_action = '<div class='."'".'btn-group mb-4'."'".' role='."'".'group'."'".' aria-label='."'".'Basic example'."'".'><button type='."'".'button'."'".' class='."'".'btn btn-outline-light detail_donasi'."'".' data-id='."'".$row->id."'".' title='."'".'Show Detail'."'".' style='."'".'border-top-right-radius:0;border-bottom-right-radius:0;'."'".'><i class='."'".'mdi mdi-file-document-outline  mr-2'."'".' style='."'".'margin: 0 4px 0 2px !important;color:#7680FF;'."'".'></i></button><button type='."'".'button'."'".' class='."'".'btn btn-outline-light del_donasi'."'".' data-id='."'".$row->id."'".' title='."'".'Delete'."'".'><i class='."'".'mdi mdi-trash-can mr-2'."'".' style='."'".'margin: 0 4px 0 2px !important;color:#EF4D56;'."'".'></i></button></div>';

				    echo '
				    {
				      "no": "<span data-id='."'".$row->id."'".'>'.$no.'</span>",
				      "picture": "'.$pp.'",
				      "name": "'.$donatur_name.'",
				      "whatsapp": "'.$whatsapp.'",
				      "total_donate": "'.$nominal_donasi.$donasi_payment_info.'",
				      "program": "'.$title_campaign.'",
                      "ref": "'.$refValue.'",
				      "cs": "'.$cs_name.'",';
					  if($role=='cs') {
						echo '
						"payment_status": "'.$img_confirmation.'",';
					  } else {
						echo '
						"payment_status": "'.$payment_status.$img_confirmation.'",';
					  }
				      if($role!='donatur'){echo '"followup": "'.$button_followupnya.'",';}
				      echo '
				      "date": "'.$date_donasi.'",
					  "utm_source" : "'.$row->utm_source.'",
					  "utm_medium" : "'.$row->utm_medium.'",
					  ';
				  	  if($role=='donatur' || $role=='cs'){
						echo '';
					  }else{
						echo '
							 "utm_campaign" : "'.$row->utm_campaign.'",
							 "utm_term" : "'.$row->utm_term.'",
							 "utm_content" : "'.$row->utm_content.'",
							';
					  }
					  echo '"city": "'.$row->city.'",
					  		"mobdesk" : "'.$row->mobdesk.'"
					  		';

					  if($role=='donatur' || $role=='cs') {
						echo '';
					  } else {
					  	echo ',"action": "'.$button_action.'"';
					  }
						
				    echo '}';

				    if($len==$no){echo'';}else{echo',';} // add koma
                    $no++;

				}

			echo '
			  ]
		}';

		

	wp_die();

}

add_action( 'wp_ajax_dja_get_data_donasi', 'dja_get_data_donasi' );
add_action( 'wp_ajax_nopriv_dja_get_data_donasi', 'dja_get_data_donasi' );  



function dja_handling_character($text){
	$set_petikdua = str_replace('"', '&#34;', $text);
    $set_petiksatu = str_replace("'", "&#39;", $set_petikdua);
    $set_garismiring = str_replace("\\", "", $set_petiksatu);
    $set_enter = trim(preg_replace('/\s\s+/', ' ', $set_garismiring));
	return $set_enter;
}



function dja_get_provinsi(){

	global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";

    $user_id = $_POST['datanya'][0];

  	$profile = $wpdb->get_results("SELECT * FROM $table_name where user_id='$user_id' ")[0];

    $curl = curl_init();
    $apikey = 'f8c9777c807e12be084a770f23c1a573';
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "key: $apikey"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $data_provinsi = '<option value="" data-idprovinsi="">Pilih Provinsi</option>';

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
        $array = json_decode( $response, true );
        $data = $array['rajaongkir']['results'];
        foreach($data as $d){
            if($profile->user_provinsi_id==$d['province_id']){
                $data_provinsi .= '<option value="'.$d['province'].'" data-idprovinsi="'.$d['province_id'].'" selected>'.$d['province'].'</option>';
            }else{
                $data_provinsi .= '<option value="'.$d['province'].'" data-idprovinsi="'.$d['province_id'].'">'.$d['province'].'</option>';
            }
        }
    }

    echo $data_provinsi;

    wp_die();

}

add_action( 'wp_ajax_dja_get_provinsi', 'dja_get_provinsi' );
add_action( 'wp_ajax_nopriv_dja_get_provinsi', 'dja_get_provinsi' );  



function dja_get_kabkota(){

	global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";

    $user_id = $_POST['datanya'][0];
    $id_provinsi = $_POST['datanya'][1];

  	$profile = $wpdb->get_results("SELECT * FROM $table_name where user_id='$user_id' ")[0];

  	if($id_provinsi=='0'){
  		$id_provinsi = $profile->user_provinsi_id;
  	}

  	if($profile->user_provinsi_id==null){
  		$id_provinsi = $id_provinsi;
  	}


		$curl = curl_init();
		$apikey = 'f8c9777c807e12be084a770f23c1a573';

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=$id_provinsi",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "key: $apikey"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$data_kabkota = '<option value="" data-idkabkota="">Pilih Kab. Kota</option>';

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		    $array = json_decode( $response, true );
			$data = $array['rajaongkir']['results'];
		    // $data_provinsi = '<option value="" data-idprovinsi="">--</option>';
		    
		    foreach($data as $d){
		    	if($d['type']=='Kabupaten'){
		    		$type = 'Kab. ';
		    	}else{
		    		$type = 'Kota ';
		    	}

		    	if($profile->user_kabkota_id==$d['city_id']){
		    		$data_kabkota .= '<option value="'.$type.$d['city_name'].'" data-idkabkota="'.$d['city_id'].'" selected>'.$type.$d['city_name'].'</option>';
		    	}else{
		        	$data_kabkota .= '<option value="'.$type.$d['city_name'].'" data-idkabkota="'.$d['city_id'].'">'.$type.$d['city_name'].'</option>';
		    	}
		    }
		}
	

    echo $data_kabkota;
    wp_die();

}
add_action( 'wp_ajax_dja_get_kabkota', 'dja_get_kabkota' );
add_action( 'wp_ajax_nopriv_dja_get_kabkota', 'dja_get_kabkota' );  


function dja_get_kecamatan(){

	global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $user_id = $_POST['datanya'][0];
    $id_kabkota = $_POST['datanya'][1];

  	$profile = $wpdb->get_results("SELECT * FROM $table_name where user_id='$user_id' ")[0];
  	if($id_kabkota=='0'){
  		$id_kabkota = $profile->user_kabkota_id;
  	}

  	if($profile->user_kabkota_id==null){
  		$id_kabkota = $id_kabkota;
  	}


        $curl = curl_init();
        $apikey = 'f8c9777c807e12be084a770f23c1a573';

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=$id_kabkota",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: $apikey"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $data_kecamatan = '<option value="" data-idkecamatan="">Pilih Kecamatan</option>';

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            $array = json_decode( $response, true );
            $data = $array['rajaongkir']['results'];
                
                foreach($data as $d){
                    if($profile->user_kecamatan_id==$d['subdistrict_id']){
                        $data_kecamatan .= '<option value="'.$d['subdistrict_name'].'" data-idkecamatan="'.$d['subdistrict_id'].'" selected>'.$d['subdistrict_name'].'</option>';
                    }else{
                        $data_kecamatan .= '<option value="'.$d['subdistrict_name'].'" data-idkecamatan="'.$d['subdistrict_id'].'">'.$d['subdistrict_name'].'</option>';
                    }
                    
                }

        }
    

    echo $data_kecamatan;
    wp_die();

}
add_action( 'wp_ajax_dja_get_kecamatan', 'dja_get_kecamatan' );
add_action( 'wp_ajax_nopriv_dja_get_kecamatan', 'dja_get_kecamatan' ); 



function djafunction_activate_apikey() {
	global $wpdb;

	$table_name = $wpdb->prefix . "options";
	$table_name2 = $wpdb->prefix . "dja_settings";

	// GET POST
    $apikey = $_POST['datanya'][0];

    // GET URL WEB
	$row = $wpdb->get_results('SELECT option_value from '.$table_name.' where option_name="siteurl"');
	$row = $row[0];

    $protocols = array('http://', 'http://www.', 'www.', 'https://', 'https://www.');
	$server = str_replace($protocols, '', $row->option_value);
	$apiurl = 'https://member.donasiaja.id/validateapi/donasiaja';

   	$curl = curl_init();
	curl_setopt_array($curl, array(
		  CURLOPT_URL => $apiurl,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_VERBOSE => true,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Origin: $server", // NAMA DOMAIN, NAMA ID HARDWARE
		    "Apikey: $apikey", // APIKEY
		    "Setup: activate", // ACTIVATE
		  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {

	  	$hasil = json_decode($response);
		
		if($hasil[0]->apikeystatus=='valid'){

			$apikey_local = '{"donasiaja": ["'.$apikey.'"]}';
			$apikey_server = '{"donasiaja": ["'.$hasil[0]->pluginstatus.'", "'.$hasil[0]->apikeystatus.'", "'.$hasil[0]->expired.'", "'.md5($hasil[0]->pluginstatus).'"]}';

			$apikey_local= str_replace('\\', '', $apikey_local);
			$apikey_server= str_replace('\\', '', $apikey_server);

			// ACTION Update TO DB
		    $wpdb->update( $table_name2, array('data' => $apikey_local), array('type' => 'apikey_local'), array('%s'), array('%s') );
		    $wpdb->update( $table_name2, array('data' => $apikey_server), array('type' => 'apikey_server'), array('%s'), array('%s') );
			$wpdb->update( $table_name2, array('data' => 'DonasiAja'), array('type' => 'main_menu_name'), array('%s'), array('%s') );

			echo 'success_'.$hasil[0]->value;

		}else{

			echo 'failed_'.$hasil[0]->value;
		}
		
	}

	wp_die();
}
add_action( 'wp_ajax_djafunction_activate_apikey', 'djafunction_activate_apikey' );
add_action( 'wp_ajax_nopriv_djafunction_activate_apikey', 'djafunction_activate_apikey' );



function djafunction_deactivate_apikey() {
	global $wpdb;

	$table_name = $wpdb->prefix . "options";
	$table_name2 = $wpdb->prefix . "dja_settings";

	// GET POST
    $apikey = $_POST['datanya'][0];

    // GET URL WEB
	$row = $wpdb->get_results('SELECT option_value from '.$table_name.' where option_name="siteurl"');
	$row = $row[0];

    $protocols = array('http://', 'http://www.', 'www.', 'https://', 'https://www.');
	$server = str_replace($protocols, '', $row->option_value);
	$apiurl = 'https://member.donasiaja.id/validateapi/donasiaja';

   	$curl = curl_init();
	curl_setopt_array($curl, array(
		  CURLOPT_URL => $apiurl,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_VERBOSE => true,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Origin: $server",
		    "Apikey: $apikey",
		    "Setup: deactivate",
		  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  	// echo $response;
	  	
	  	$hasil = json_decode($response);

	  	// UPDATE KE DB
		if($hasil[0]->apikeystatus=='deactivated'){

			$apikey_local = '{"donasiaja": [""]}';
			$apikey_server = '{"donasiaja": ["", "'.$hasil[0]->apikeystatus.'", "", ""]}';

			$apikey_local= str_replace('\\', '', $apikey_local);
			$apikey_server= str_replace('\\', '', $apikey_server);

			// ACTION Update TO DB
		    // $wpdb->update( $table_name2, array('data' => $apikey_local), array('type' => 'apikey_local'), array('%s'), array('%s') );
		    $wpdb->update( $table_name2, array('data' => $apikey_server), array('type' => 'apikey_server'), array('%s'), array('%s') );

			echo 'success_'.$hasil[0]->value;

		}else{
			
			echo 'failed_'.$hasil[0]->value;
		}
		
	}

	wp_die();
}
add_action( 'wp_ajax_djafunction_deactivate_apikey', 'djafunction_deactivate_apikey' );
add_action( 'wp_ajax_nopriv_djafunction_deactivate_apikey', 'djafunction_deactivate_apikey' );



function check_verified_campaign($akses){

	if($akses!='1'){

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
                                                <h5 class="">Status User</h5>
                                                <h2 class="my-2">Belum verifikasi</h3>
                                                <p class="text-muted mb-0">Agar bisa melakukan campaign, data anda harus diverikasi terlebih dahulu.</p>
                                                <a href="'.admin_url("admin.php?page=donasiaja_myprofile&action=verification").'">
                                                <button type="button" class="btn btn-primary px-5 py-2" style="margin-top: 25px;">Verifikasi sekarang</button></a>
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

function check_verified_dashboard($akses){

	if($akses!='1'){

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
                                                <h5 class="">Status User</h5>
                                                <h2 class="my-2">Belum verifikasi</h3>
                                                <p class="text-muted mb-0">Campaign Dashboard masih terkunci, data anda harus diverikasi terlebih dahulu.</p>
                                                <a href="'.admin_url("admin.php?page=donasiaja_myprofile&action=verification").'">
                                                <button type="button" class="btn btn-primary px-5 py-2" style="margin-top: 25px;">Verifikasi sekarang</button></a>
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


function dja_auto_login_new_user( $user_id ) {
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    $user = get_user_by( 'id', $user_id );
    do_action( 'wp_login', $user->user_login );//[Codex Ref.][1]
    return 'wp-admin/admin.php?page=donasiaja_myprofile';
}
add_action( 'user_register', 'dja_auto_login_new_user' );



function dja_whatsapp_format($text){
	$set_div1 = str_replace('<br></div><div>','%0A',$text);
	$set_div2 = str_replace('</div><div>','%0A',$set_div1);
    $set_nbsp = str_replace('&nbsp;','',$set_div2);
	$set_b_1 = str_replace('<b>','*',$set_nbsp);
	$set_b_2 = str_replace('</b>','*',$set_b_1);
	$set_i_1 = str_replace('<i>','_',$set_b_2);
	$set_i_2 = str_replace('</i>','_',$set_i_1);
	$set_s_1 = str_replace('<strike>','~',$set_i_2);
	$set_s_2 = str_replace('</strike>','~',$set_s_1);
	$set_enter = str_replace('<br>','%0A',$set_s_2);
	return urlencode($set_enter);
}

function d_randomString($length) {
    $keys = array_merge(range(0,9), range('a', 'z'));

    $key = "";
    for($i=0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
    }
    return $key;
}

function d_randomBigString($length) {
    $keys = array_merge(range(0,9), range('A', 'Z'));

    $key = "";
    for($i=0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
    }
    return $key;
}



function d_formatUri( $string, $separator = '-' ) {
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and', "'" => '');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}


function djaPhoneFormat($nohp) {
    $nohp = str_replace(" ","",$nohp);
    $nohp = str_replace("(","",$nohp);
    $nohp = str_replace(")","",$nohp);
    $nohp = str_replace(".","",$nohp);

    // cek apakah no hp mengandung karakter + dan 0-9
   if(!preg_match('/[^+0-9]/',trim($nohp))){
       // cek apakah no hp karakter 1-3 adalah +62
       if(substr(trim($nohp), 0, 3)=='+62'){
           $hp = substr(trim($nohp), 1);
       }
       // cek apakah no hp karakter 1 adalah 0
       elseif(substr(trim($nohp), 0, 1)=='0'){
           $hp = '62'.substr(trim($nohp), 1);
       }else{
           $hp = $nohp;
       }
   }
    return $hp;
}



function _check_is_curl_installed() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}
