<?php
        echo '<pre>';
        echo 'this page is not for public';
        echo '</pre>';
    
        global $wpdb;
        $table_name = $wpdb->prefix.'dja_donate';
        $table_name2 = $wpdb->prefix . 'dja_campaign';

        $gett = $_GET['inv'];
    
        // var_dump($gett); echo '<br>';
    

        if($gett!=null) {
            $syn = "SELECT * FROM `ympb2020_dja_donate` WHERE invoice_id='" . $gett . "'";
            // echo 'not null'; echo '<br>';
            //echo $syn; echo '<br>';
            $get_cs = $wpdb->get_results($syn);
            $camp_id = $get_cs[0]->campaign_id;

            $syn2 = "SELECT * FROM `ympb2020_dja_campaign` WHERE campaign_id='" . $camp_id . "'";
            $get_campaign = $wpdb->get_results($syn2);

            $campaign_title = $get_campaign[0]->title;
            $nominal = $get_cs[0]->nominal;
            $total = 'Rp '.number_format($nominal,0,",",".");
            $whatsapp = $get_cs[0]->whatsapp;
            $nama = $get_cs[0]->name;

            if($get_cs!=null) {
                // echo '========== INVOICE DITEMUKAN ========'; echo '<br>';
                // echo 'nama donatur : '; echo $get_cs[0]->name; echo '<br>';
                // echo 'program : '; echo $campaign_title; echo '<br>';
                // echo 'total : '; echo $total; echo '<br>';
                // echo 'jumlah row : '; echo count($get_cs); echo '<br>';

                $text_followup = "Assalamualaikum%20Kak%20". $nama ."%20%E2%98%BA%0A%0AKami%20sudah%20terima%20niat%20baik%20nya%2C%0A%0A%2A" . $campaign_title . "%2A%0ATotal%3A%20" . $total . "%0A%0ATerimakasih%20sudah%20berniat%20baik%20semoga%20Allah%20tambahkan%20rezeki%20nya%20seluas%20samudra..%20Untuk%20melengkapi%20niat%20baik%20kakak%2C%20silahkan%20transfer%20senilai%20%2A" . $total . "%2A%2C%20ke%20salah%20satu%20rekening%20dibawah%20ini%3A%0A%0ABRI%0ANo.%20Rek%3A%20114701000389560%0A%0ABCA%0ANo.%20Rek%3A%208670708005%0A%0ABSI%0ANo.%20Rek%3A%207222759708%0A%0AAtas%20Nama%3A%20%2AYayasan%20Mulia%20Peduli%20Bangsa%2A%0A%0A%20%0A%0AMohon%20kirim%20bukti%20transfer%20jika%20sudah%20di%20tunaikan%2C%20untuk%20membantu%20proses%20administrasi%20Kami%0A_syuqran%20katsiran_%20%F0%9F%99%8F%F0%9F%98%8A";

                //cek sudah pernah di follow belum
                $syn3 = "SELECT * FROM `ympb2020_josh_cs_f` WHERE invoice_id='" . $gett . "'";
                $get_follow = $wpdb->get_results($syn3);

                $fol2 = $get_follow[0]->date_fol_up2;
                $fol3 = $get_follow[0]->date_fol_up3;
                $fol4 = $get_follow[0]->date_fol_up4;
                $fol5 = $get_follow[0]->date_fol_up5;

                if($get_follow==null) {     //belum pernah follow
                    $wpdb->insert( 'ympb2020_josh_cs_f',     //table
		            array(
		            'invoice_id' 	=> $gett,
		            'date_fol_up' 	=> date("Y-m-d H:i:s")));

                    $wpdb->update( 'ympb2020_dja_donate',     //table
		            array(
		            'f1' 	=> intval('1')),
                    array('invoice_id' => $gett));

                    //echo 'berhasil input SQL'; echo '<br>';
                } elseif ($fol2==null) {
                    $wpdb->update( 'ympb2020_josh_cs_f',     //table
		            array(
		            'date_fol_up2' 	=> date("Y-m-d H:i:s")),
                    array('invoice_id' => $gett));

                } elseif ($fol3==null) {
                    $wpdb->update( 'ympb2020_josh_cs_f',     //table
		            array(
		            'date_fol_up3' 	=> date("Y-m-d H:i:s")),
                    array('invoice_id' => $gett));
                } elseif ($fol4==null) {
                    $wpdb->update( 'ympb2020_josh_cs_f',     //table
		            array(
		            'date_fol_up4' 	=> date("Y-m-d H:i:s")),
                    array('invoice_id' => $gett));
                } elseif ($fol5==null) {
                    $wpdb->update( 'ympb2020_josh_cs_f',     //table
		            array(
		            'date_fol_up5' 	=> date("Y-m-d H:i:s")),
                    array('invoice_id' => $gett));
                } //else {
                    //echo 'tidak input SQL'; echo '<br>';
                }
                
                $phone = substr_replace($whatsapp,"62",0,1);
                $full_link = 'https://api.whatsapp.com/send?phone='. $phone .'&text=' . $text_followup;

                // echo '<pre>'; echo 'ini nomor siap : '; echo $phone; echo '<br>';
                // echo 'ini full link : '; echo $full_link; echo '<br>';
                // echo '</pre>';

                header ('location: ' . $full_link);
            } //else {
                //echo '========== INVOICE TIDAK DITEMUKAN ========';echo '<br>';
            // }
            
        // }   else {
        //     echo 'this page are not for public';
        // }

        //echo '</pre>';

    // } elseif (get_the_ID() == '2731' ) {
    //     global $wpdb;
    //     $table_name = $wpdb->prefix.'dja_donate';

    //     $invoice = $_GET['inv'];

    //     if ($invoice != null) {

    //         $sql = "SELECT * FROM `ympb2020_dja_donate` WHERE invoice_id='" . $invoice . "'";

    //         $row = $wpdb->get_results($sql);
            
    //         $img = $row[0]->img_confirmation_url;
    //         $josh_content = '
    //         <div style = "display: flex; justify-content: center;" >
    //             <img src="'.$img.'" style="width: 360px;">
    //         </div>
    //         ';

    //         $wpdb->update( 'ympb2020_dja_donate',     //table
	// 	            array(
	// 	            'img_confirmation_status' => intval('1')),
    //                 array('invoice_id' => $invoice));


    //     }    
    
?>
