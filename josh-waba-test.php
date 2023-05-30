<?php
// header('Content-Type: application/json');
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
                        'text' => $data['datetime']
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

$url = "https://graph.facebook.com/$version/$phone_from/messages";
var_dump($url);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_PORT, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postBody));

// execute
$response = curl_exec($curl);

var_dump( $response);

if($response === false) {
    echo 'Error: '.curl_error($curl);
}

curl_close($curl);