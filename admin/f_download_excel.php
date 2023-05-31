<?php

	
	require 'plugins/phpspreadsheet/autoload.php';
	use PhpOffice\PhpSpreadsheet\Helper\Sample;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;

	global $wpdb;
    $table_name = $wpdb->prefix . "dja_donate";
    $table_name2 = $wpdb->prefix . "dja_campaign";
    $table_name3 = $wpdb->prefix . "dja_aff_submit";
    $table_name4 = $wpdb->prefix . "dja_aff_code";
   	$campaign_id = $c_id;

   	// date_default_timezone_set('Asia/jakarta');
   	$date_now = 'Data Download: '.date("j F Y, H:i");
   	if($date_filter==''){$date_filter='All';}
   	if($date_filter=='daterange'){$date_filter='Date range '.$date_range_first.' s.d '.$date_range_last;}

  	if($campaign_id=='all'){
  		if($date_filter!=''){
  			$file_title = 'All Donation / '.$date_now.' / Filter: '.$date_filter;
	  		$query_donation = $wpdb->get_results("SELECT a.*, b.title, c.nominal_commission,  c.affcode_id, d.user_id as fundraiser_id FROM $table_name a
			LEFT JOIN $table_name2 b ON b.campaign_id = a.campaign_id 
			LEFT JOIN $table_name3 c ON c.donate_id = a.id 
			LEFT JOIN $table_name4 d ON d.id = c.affcode_id
			where a.campaign_id!='' $filternya ORDER BY a.id DESC ");
  		}else{
	  		$file_title = 'All Donation / '.$date_now.' / Filter: '.$date_filter;
	  		$query_donation = $wpdb->get_results("SELECT a.*, b.title, c.nominal_commission,  c.affcode_id, d.user_id as fundraiser_id FROM $table_name a
			LEFT JOIN $table_name2 b ON b.campaign_id = a.campaign_id 
			LEFT JOIN $table_name3 c ON c.donate_id = a.id 
			LEFT JOIN $table_name4 d ON d.id = c.affcode_id
			ORDER BY a.id DESC ");
  		}
  	}else{
  		$query_donation = $wpdb->get_results("SELECT a.*, b.title, c.nominal_commission, d.user_id as fundraiser_id FROM $table_name a
		LEFT JOIN $table_name2 b ON b.campaign_id = a.campaign_id 
		LEFT JOIN $table_name3 c ON c.donate_id = a.id
		LEFT JOIN $table_name4 d ON d.aff_code = c.affcode_id 
		where a.campaign_id='$campaign_id' $filternya ORDER BY a.id DESC ");
		if($query_donation!=null){
			$file_title = $query_donation[0]->title.' / '.$date_now.' - Filter: '.$date_filter;
		}else{
			$file_title = $campaign_id.' / '.$date_now.' / Filter: '.$date_filter;
		}
  	}


  	if($campaign_id=='all'){

  		if($date_filter!=''){

  			$nominal_donasi_terkumpul = 0;
			$jumlah_donasi_terkumpul = 0;
			$jumlah_donasi_semua = 0;
  			foreach ($query_donation as $value) {

  				if($value->status=='1'){

  					$nominal_donasi_terkumpul = $nominal_donasi_terkumpul + $value->nominal;
  					$jumlah_donasi_terkumpul++;
  				}

  				$jumlah_donasi_semua++;
  			}

  			if($jumlah_donasi_semua>=1){
		        $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
		    }else{
		        $konversi = 0;
		    }

		}else{
			$total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name where status='1' ")[0];
		    $nominal_donasi_terkumpul = $total_donasi->total;
		    $jumlah_donasi_terkumpul = $total_donasi->jumlah;

		    $total_donasi_semua = $wpdb->get_results("SELECT COUNT(id) as jumlah FROM $table_name ")[0];
		    $jumlah_donasi_semua = $total_donasi_semua->jumlah;

		    if($jumlah_donasi_semua>=1){
		        $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
		    }else{
		        $konversi = 0;
		    }
		}

	}else{

		$total_donasi = $wpdb->get_results("SELECT SUM(a.nominal) as total, COUNT(a.id) as jumlah FROM $table_name a
	    LEFT JOIN $table_name2 c on a.campaign_id = c.campaign_id 
	    WHERE c.campaign_id = '$campaign_id' and a.status='1' ")[0];
	    $nominal_donasi_terkumpul = $total_donasi->total;
	    $jumlah_donasi_terkumpul = $total_donasi->jumlah;

	    $total_donasi_semua = $wpdb->get_results("SELECT COUNT(a.id) as jumlah FROM $table_name a 
	    LEFT JOIN $table_name2 c on a.campaign_id = c.campaign_id 
	    WHERE c.campaign_id = '$campaign_id' ")[0];

	    $jumlah_donasi_semua = $total_donasi_semua->jumlah;

	    if($jumlah_donasi_semua>=1){
	        $konversi = $jumlah_donasi_terkumpul/$jumlah_donasi_semua;
	    }else{
	        $konversi = 0;
	    }
	}

    $konversi = $konversi*100;
    $konversi = number_format($konversi,1,",",".").' %';
	
	// print_r(json_encode($total_donasi));
	// exit();

	$helper = new Sample();
	if ($helper->isCli()) {
	    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

	    return;
	}
	// Create new Spreadsheet object
	$spreadsheet = new Spreadsheet();

	// Set document properties
	$spreadsheet->getProperties()->setCreator('DonasiAja')
	    ->setLastModifiedBy('DonasiAja')
	    ->setTitle('Office 2007 XLSX Test Document')
	    ->setSubject('Office 2007 XLSX Test Document')
	    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
	    ->setKeywords('office 2007 openxml php')
	    ->setCategory('Test result file');

	// Add some data
	$spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A1', 'Campaign : '.$file_title);

	$spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A3', 'Total Donasi : Rp '.number_format($nominal_donasi_terkumpul,0,",","."));
	$spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A4', 'Jumlah Donasi : Berhasil terkumpul '.number_format($jumlah_donasi_terkumpul,0,",",".").' dari '.number_format($jumlah_donasi_semua,0,",",".").' Donasi');
	$spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A5', 'Konversi : '.$konversi);

	$start_cell = 7;
	$spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A'.$start_cell, 'No')
	    ->setCellValue('B'.$start_cell, 'Invoice ID')
	    ->setCellValue('C'.$start_cell, 'Donatur')
	    ->setCellValue('D'.$start_cell, 'Nominal') // NumberFormat
	    ->setCellValue('E'.$start_cell, 'Kode Unik')
	    ->setCellValue('F'.$start_cell, 'Total') // NumberFormat
	    ->setCellValue('G'.$start_cell, 'Whatsapp')
	    ->setCellValue('H'.$start_cell, 'Email')
	    ->setCellValue('I'.$start_cell, 'Comment')
	    ->setCellValue('J'.$start_cell, 'Program')
	    ->setCellValue('K'.$start_cell, 'Payment Method')
	    ->setCellValue('L'.$start_cell, 'Payment Number')
	    ->setCellValue('M'.$start_cell, 'Payment Account')
	    ->setCellValue('N'.$start_cell, 'Payment Status')
	    ->setCellValue('O'.$start_cell, 'Fundraiser Commission') // NumberFormat
	    ->setCellValue('P'.$start_cell, 'Fundraiser Name')
	    ->setCellValue('Q'.$start_cell, 'CS')
	    ->setCellValue('R'.$start_cell, 'Date')
	    ->setCellValue('S'.$start_cell, 'Additional Data');

	$no = 1;
	$i = 8;
	foreach ($query_donation as $value) {
		
		$datenya = new DateTime($value->created_at, new DateTimeZone('Asia/jakarta') );
        $date_donasi = $datenya->format('M').' '.$datenya->format('d').', '.$datenya->format('Y').' - '.$datenya->format('H').':'.$datenya->format('i');

        if($value->status=='1'){
        	$payment_status = 'Received';

        	$spreadsheet
			    ->getActiveSheet()
			    ->getStyle('A'.$i.':S'.$i)
			    ->getFill()
			    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB('90EE90');

        }else{
        	$payment_status = 'Waiting';
        }

        if($value->nominal_commission==''){
        	$nominal_commission = '';
        }else{
        	$nominal_commission = $value->nominal_commission;
        }

    	if($value->fundraiser_id!=''){
        	$user_info = get_userdata($value->fundraiser_id);
        	$fundraiser_name = $user_info->first_name.' '.$user_info->last_name;
        }else{
        	$fundraiser_name = '';
        }

        if($value->main_donate==''){
        	$nominal_utama = $value->nominal-$value->unique_number;
        	if($nominal_utama<=0){
        		$nominal_utama = 0;
        	}
        }else{
        	$nominal_utama = $value->main_donate;
        }

        $cs_name = '';
	    if($value->cs_id>=1){
	    	$user_info_cs = get_userdata($value->cs_id);
		    if($user_info_cs!=null){
		    	if($user_info_cs->last_name==''){
			    	$cs_name = $user_info_cs->first_name;
			    }else{
			    	$cs_name = $user_info_cs->first_name.' '.$user_info_cs->last_name;
			    }
		    }
	    }
       

		$spreadsheet->setActiveSheetIndex(0)
		    ->setCellValue('A'.$i, $no)
		    ->setCellValue('B'.$i, $value->invoice_id)
		    ->setCellValue('C'.$i, $value->name)
		    ->setCellValue('D'.$i, $nominal_utama) // NumberFormat
		    ->setCellValue('E'.$i, $value->unique_number) 
		    ->setCellValue('F'.$i, $value->nominal) // NumberFormat
		    ->setCellValue('G'.$i, $value->whatsapp)
		    ->setCellValue('H'.$i, $value->email)
		    ->setCellValue('I'.$i, $value->comment)
		    ->setCellValue('J'.$i, $value->title)
		    ->setCellValue('K'.$i, $value->payment_method)
		    ->setCellValue('L'.$i, $value->payment_number)
		    ->setCellValue('M'.$i, $value->payment_account)
		    ->setCellValue('N'.$i, $payment_status)
		    ->setCellValue('O'.$i, $nominal_commission) // NumberFormat
		    ->setCellValue('P'.$i, $fundraiser_name)
		    ->setCellValue('Q'.$i, $cs_name)
		    ->setCellValue('R'.$i, $date_donasi)
		    ->setCellValue('S'.$i, $value->info_donate);


		$spreadsheet->getActiveSheet()
		    ->getStyle('D'.$i)
		    ->getNumberFormat()
		    ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR);

		$spreadsheet->getActiveSheet()
		    ->getStyle('F'.$i)
		    ->getNumberFormat()
		    ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR);

		$spreadsheet->getActiveSheet()
		    ->getStyle('O'.$i)
		    ->getNumberFormat()
		    ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR);

		$i++;
		$no++;
	}
	

	$powered_row = count($query_donation)+9+2;
	$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$powered_row, 'This Data Download Powered By DonasiAja');

	// Rename worksheet
	$spreadsheet->getActiveSheet()->setTitle('Donasi');
	
	// Set Bold and Merge
	$spreadsheet->getActiveSheet()->mergeCells("A1:S1")->getStyle("A1:S1")->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()->mergeCells("A3:S3")->getStyle("A3:S3")->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()->mergeCells("A4:S4")->getStyle("A4:S4")->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()->mergeCells("A5:S5")->getStyle("A5:S5")->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()->mergeCells("A".$powered_row.":S".$powered_row)->getStyle("A".$powered_row.":S".$powered_row)->getFont()->setBold(true);

	// set Bold on sub title colomn
	$spreadsheet->getActiveSheet()->getStyle("A7:S7")->getFont()->setBold(true);

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$spreadsheet->setActiveSheetIndex(0);

	// Redirect output to a client’s web browser (Xlsx)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$file_title.'.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0

	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
	exit;

