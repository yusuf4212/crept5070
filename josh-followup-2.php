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
    // echo '<pre>'; var_dump($get_order); echo '</pre>';
    // echo '<pre>'; var_dump($wpdb->last_error); echo '</pre>';
    
    $campaign_title     = $get_order->title;
    $total              = 'Rp '.number_format($get_order->nominal,0,",",".");
    $whatsapp           = $get_order->whatsapp;
    $name               = $get_order->name;
    $phone              = $get_order->whatsapp;
    $ref                = $get_order->ref;
    
    $query = "SELECT data
    FROM `ympb2020_dja_settings`
    WHERE type='bank_account' or type='bank_account_ref'";

    $db_bank = $wpdb->get_results($query);
    $db_bank_       = $db_bank[0]->data;
    $db_bank_ref    = $db_bank[1]->data;

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

    $bank = (bank_chooser($get_order)) ? $db_bank_ : $db_bank_ref;
    // echo '<pre>'; var_dump($get_order->cs_id); echo '</pre>';
    // echo '<pre>'; var_dump($get_order->ref); echo '</pre>';
    // echo '<pre>'; var_dump($bank); echo '</pre>';

    foreach($bank as $key => $val) {
        $bank_code = explode('@', $key)[0];
        $bank_code = strtoupper($bank_code);

        $bank_number = explode('_', $val)[0];

        $bank_text = "$bank_text\n\n$bank_code\nNo. Rek: $bank_number";
    }

    $bank_text = "$bank_text\n\nAtas Nama: *Yayasan Mulia Peduli Bangsa*";
    // $bank_text = (bank_chooser($get_order)) ? $bank_text . " atau *Rumah Tahfizh": ''

    if(! $get_order == null) {
    // if($get_cs!=null) {
        // $name = 'Maulana Isra';
        $text_followup2 = "Assalamualaikum Kak $name \xF0\x9F\x98\x8A\n\nKami sudah menerima niat baik nya.\n\n*Wakaf Rumah Tahfizh Tanzilul Qur'an*\nTotal: Rp55.668\n\nTerimakasih sudah berniat baik semoga Allah tambahkan rezeki nya seluas samudra.. Untuk melengkapi niat baik kakak, silahkan transfer senilai *Rp55.668*, ke salah satu rekening dibawah ini:$bank_text\n\n\n\nMohon kirim bukti jika sudah di tunaikan, untuk membantu proses administrasi Kami.\n_syuqran katsiran_ \xF0\x9F\x99\x8F \xF0\x9F\x98\x8A	";
    
        $text_followup = urlencode($text_followup2);
    

        //cek sudah pernah di follow belum
        $query = "SELECT *
        FROM $table_followup
        WHERE invoice_id='$gett'";

        $get_follow = $wpdb->get_results($query);

        // $fol2 = $get_follow[0]->date_fol_up2;
        // $fol3 = $get_follow[0]->date_fol_up3;
        // $fol4 = $get_follow[0]->date_fol_up4;
        // $fol5 = $get_follow[0]->date_fol_up5;

        // if($get_follow==null) {     //belum pernah follow
        //     $wpdb->insert( 'ympb2020_josh_cs_f',     //table
        //     array(
        //     'invoice_id' 	=> $gett,
        //     'date_fol_up' 	=> date("Y-m-d H:i:s")));

        //     $wpdb->update( 'ympb2020_dja_donate',     //table
        //     array(
        //     'f1' 	=> intval('1')),
        //     array('invoice_id' => $gett));

        //     //echo 'berhasil input SQL'; echo '<br>';
        // } elseif ($fol2==null) {
        //     $wpdb->update( 'ympb2020_josh_cs_f',     //table
        //     array(
        //     'date_fol_up2' 	=> date("Y-m-d H:i:s")),
        //     array('invoice_id' => $gett));

        // } elseif ($fol3==null) {
        //     $wpdb->update( 'ympb2020_josh_cs_f',     //table
        //     array(
        //     'date_fol_up3' 	=> date("Y-m-d H:i:s")),
        //     array('invoice_id' => $gett));
        // } elseif ($fol4==null) {
        //     $wpdb->update( 'ympb2020_josh_cs_f',     //table
        //     array(
        //     'date_fol_up4' 	=> date("Y-m-d H:i:s")),
        //     array('invoice_id' => $gett));
        // } elseif ($fol5==null) {
        //     $wpdb->update( 'ympb2020_josh_cs_f',     //table
        //     array(
        //     'date_fol_up5' 	=> date("Y-m-d H:i:s")),
        //     array('invoice_id' => $gett));
        // }
        
    }
        
    $phone = substr_replace($whatsapp,"62",0,1);
    // $phone = 6281210874421;
    $full_link = 'https://api.whatsapp.com/send?phone='. $phone .'&text=' . $text_followup;

    // header('location: ' . $full_link);
}

// wp_die('This page are not for public', 400);