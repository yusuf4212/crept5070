<?php
global $wpdb;
$table_donate = $wpdb->prefix.'dja_donate';
$table_campaign = $wpdb->prefix . 'dja_campaign';
$table_settings = $wpdb->prefix . 'dja_settings';
$table_followup = $wpdb->prefix . 'josh_cs_f';

if(isset($_GET['inv'])) {
    $gett = $_GET['inv'];
    $gett = preg_replace('/[^a-zA-Z0-9\-_]/', '', $gett);
    echo '<pre>'; var_dump($gett); echo '</pre>';
    
    $query = "SELECT *
    FROM $table_donate as d
    LEFT JOIN $table_campaign as c
    ON d.campaign_id = c.campaign_id
    WHERE d.invoice_id='$gett'";

    $get_order = $wpdb->get_row($query);

    if(isset($get_order)) {
        $campaign_title     = $get_order->title;
        $total              = 'Rp '.number_format($get_order->nominal,0,",",".");
        $whatsapp           = $get_order->whatsapp;
        $name               = $get_order->name;
        $phone              = $get_order->whatsapp;
        $ref                = $get_order->ref;
        
        $query = "SELECT data
        FROM $table_settings
        WHERE type='bank_account' or type='bank_account_ref'";
    
        $db_bank = $wpdb->get_results($query);
        $db_bank_       = json_decode($db_bank[0]->data, true);
        $db_bank_ref    = json_decode($db_bank[1]->data, true);
    
        /**
         * @return bool true when it dfr, else if not (duta)
         */
        function bank_chooser($data) {
            if(isset($data->ref)) {
                $cs_dfr_code = ['husna', 'fadhilah', 'meisya', 'safina'];
    
                if(in_array($data->ref, $cs_dfr_code)) {
                    return true;
                } else {
                    return false;
                }
            }
    
            $cs_ids = [3, 9, 10, 11];
    
            if(in_array($data->cs_id, $cs_ids)) {
                return true;
            } else {
                return false;
            }
        }
    
        $bank_chooser_ = bank_chooser($get_order);
        $bank = ($bank_chooser_) ? $db_bank_ : $db_bank_ref;
    
        $bank_text = '';
        foreach($bank as $key => $val) {
            $bank_code = explode('@', $key)[0];
            $bank_code = strtoupper($bank_code);
    
            $bank_number = explode('_', $val)[0];
    
            $bank_text = "$bank_text\n\n$bank_code\nNo. Rek: $bank_number";
        }
    
        $bank_text = "$bank_text\n\nAtas Nama: *Yayasan Mulia Peduli Bangsa*";
        $bank_text = ($bank_chooser_) ? $bank_text . " atau *Rumah Tahfizh Tahzilul Qur'an*" : $bank_text;
        $nominal = 'Rp'.number_format(intval($get_order->nominal), 0, ',', '.');
    
        // $name = 'Maulana Isra';
        $text_followup2 = "Assalamualaikum Kak $name \xF0\x9F\x98\x8A\n\nKami sudah menerima niat baik nya.\n\n*$get_order->title*\nTotal: $nominal\n\nTerimakasih sudah berniat baik semoga Allah tambahkan rezeki nya seluas samudra.. Untuk melengkapi niat baik kakak, silahkan transfer senilai *$nominal*, ke salah satu rekening dibawah ini:$bank_text\n\n\n\nMohon kirim bukti jika sudah di tunaikan, untuk membantu proses administrasi Kami.\n_syuqran katsiran_ \xF0\x9F\x99\x8F \xF0\x9F\x98\x8A	";
    
        $text_followup = urlencode($text_followup2);
    
        /**
         * cek sudah pernah di follow belum
         */
        $query = "SELECT *
        FROM $table_followup
        WHERE invoice_id='$gett'";
    
        $get_follow = $wpdb->get_results($query);

        if($get_follow == null) {
            $wpdb->insert( $table_followup,
            array(
            'invoice_id' 	=> $gett,
            'date_fol_up' 	=> date("Y-m-d H:i:s")));
    
            $wpdb->update( $table_donate,
            array(
            'f1' 	=> intval('1')),
            array('invoice_id' => $gett));
        } else {
            $fol2 = $get_follow[0]->date_fol_up2;
            $fol3 = $get_follow[0]->date_fol_up3;
            $fol4 = $get_follow[0]->date_fol_up4;
            $fol5 = $get_follow[0]->date_fol_up5;

            if($fol2 == null) {
                $wpdb->update( $table_followup,
                array(
                'date_fol_up2' 	=> date("Y-m-d H:i:s")),
                array('invoice_id' => $gett));
    
            } elseif ($fol3 == null) {
                $wpdb->update( $table_followup,
                array(
                'date_fol_up3' 	=> date("Y-m-d H:i:s")),
                array('invoice_id' => $gett));
            } elseif ($fol4 == null) {
                $wpdb->update( $table_followup,
                array(
                'date_fol_up4' 	=> date("Y-m-d H:i:s")),
                array('invoice_id' => $gett));
            } elseif ($fol5 == null) {
                $wpdb->update( $table_followup,
                array(
                'date_fol_up5' 	=> date("Y-m-d H:i:s")),
                array('invoice_id' => $gett));
            }
        }
    

        $phone = substr_replace($whatsapp,"62",0,1);
        $full_link = "https://api.whatsapp.com/send?phone=$phone&text=$text_followup";
    
        header('location: ' . $full_link);
    } else {
        wp_die('Maaf data order tidak ditemukan!', 'Ooops!');
    }
    
} else {
    wp_die('Data invoice tidak terdeteksi.', 'Not Valid');
}