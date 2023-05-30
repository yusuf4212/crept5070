<?php
    function joshnotif() {
        echo 'hello world'; echo '<br>';

        $token = '5687720702:AAHECL_LOrQpazd4FDeoyVqTm-9segST7iU';
        // $telegram_send_to = '[{"channel":"@joshprac","message":"ORDER {new_repeat_c}\n**{campaign_title}**\n\nOrder ID : {invoice_id}\nNama : {name}\nNo. WhatsApp : {whatsapp_s}\nTotal : {total}\nEmail : {email}\nCS : {cs_name}\nJenis : {new_repeat}\nTanggal Order : {date}\nDoa : {comment}"}]';
        // $telegram_send_to = json_decode($telegram_send_to);

        // foreach($telegram_send_to as $key => $value) {

            // $message_tele = $value->message;
            // $message_tele = strtr($message_tele, $data_field);
            // $channel = $value->channel;
            $message_tele = 'tes tes';
            $channel = '@joshprac';
            
            if (strpos($channel, ',') !== false ) {
                $array_channel  = (explode(",", $channel));
                foreach ($array_channel as $values){
                    $channel_id = $values;
                    $send = donasiaja_send2tg($token, $channel_id, $message_tele);
                    // echo $send;
                }
            }else{
                $channel_id = $channel;
                $send2 = donasiaja_send2tg($token, $channel_id, $message_tele);
            }
        // }
        echo $token; echo '<br>';
        echo $channel_id; echo '<br>';
        echo $message_tele; echo '<br>';
        echo $send2;

    }
?>