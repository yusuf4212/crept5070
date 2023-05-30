<?php
class Josh_WABA_Endpoint {
    /**
     * 
     */
    private $verify_token = 'HAPPY';
    private $whatsapp_token = 'EAAB4PRi3wKEBAAZAZABGgXFfj0Nwl3zkfh2mZCXZBpeZB7CKm3gsIT3GdHem3TLhoZA7a9nUrVCBt88EKbMXGv70FfmlGzuQxoBAzPpneYxUqCj6alLbGi0ZBOabaPn1SegljR18LPym58weQINc2CcCmdhYhN6EZCkpSO0dg2MbncTFWfA5ImQkwhLoWRTO9tZBKIxBtq9ZBE0gZDZD'; // permanent
    // const $whatsapp_token = 'EAAB4PRi3wKEBAOoEYsKz0WZAlfMqX4VckCO46hHDmS294JkO1T8vPHIYcxxSHUV0IZB8X7EImuK7jF0uZBZAmDRp5oFWtTQVQ97ZAPdPQQh1yCdNzZCb2qce0NRYEJxXmui8sEL58LPujVcEewIjc3BqSYpOfsq9HIMSnZBxVjMfHNA2bTq8nJDcyE3PwNWmdClZBWIHJ5jCGZAvaeatffslZB4AhZA0USFNVsZD';     // temporary
    private $table_recieve;
    private $table_recieve_error;
    private $table_incoming;
    private $table_sent;
    private $table_donate;

    public function __construct() {
        global $wpdb;
        $this->table_recieve = $wpdb->prefix . 'josh_waba_recieve';
        $this->table_recieve_error = $wpdb->prefix . 'josh_waba_recieve_error';
        $this->table_incoming = $wpdb->prefix . 'josh_waba_incoming';
        $this->table_sent = $wpdb->prefix . 'josh_waba_sent';
        $this->table_donate = $wpdb->prefix . 'dja_donate';

        /**
         * Determine request method
         */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->post();

        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->get();

        } else {

            http_response_code( 405 );
            header( 'Allow: POST, GET' );
            echo 'Only POST and GET requests are allowed';
            exit();

        }
          
    }

    private function post() {
        global $wpdb;
        $json = file_get_contents( 'php://input' );
        $data = json_decode( $json );


        /**
         * check if $data in correct format
         */
        if( $data === null && json_last_error() !== JSON_ERROR_NONE ) {
            // $wpdb->insert(
            //     $this->table_recieve_error,
            //     array(
            //         'error_msg' => 'josh: json error'
            //     )
            // );

            http_response_code( 400 );
            echo 'Error decoding JSON data';
            throw new Exception( 'Error decoding JSON data: '. json_last_error_msg() );

        }


        /**
         * check if this from facebook
         * 
         */
        if( isset($data->object) ) {
            /**
             * Messages Incoming Mode
             */
            if(
                $data->entry &&
                $data->entry[0]->changes &&
                $data->entry[0]->changes[0] &&
                $data->entry[0]->changes[0]->value->messages &&
                $data->entry[0]->changes[0]->value->messages[0]
            ) {

                $phone_number_id = $data->entry[0]->changes[0]->value->metadata->phone_number_id;
                $name = $data->entry[0]->changes[0]->value->contacts[0]->profile->name;
                $from_phone = $data->entry[0]->changes[0]->value->messages[0]->from;    // phone number from
                $msg_body = $data->entry[0]->changes[0]->value->messages[0]->text->body;
                $wam_id = $data->entry[0]->changes[0]->value->messages[0]->id;
                $timestamp = $data->entry[0]->changes[0]->value->messages[0]->timestamp;
    

                /**
                 * Store information in database
                 */
                $insert = $wpdb->insert(
                    $this->table_incoming,
                    array(
                        'phone_number_id'   => $phone_number_id,
                        'name'              => $name,
                        'from_phone'        => $from_phone,
                        'msg_body'          => $msg_body,
                        'wam_id'            => $wam_id,
                        'timestamp'         => $timestamp
                    )
                );
        
                if( $insert === false ) {
                    $wpdb->insert(
                        $this->table_recieve_error,
                        array(
                            'error_msg' => $wpdb->last_error,
                            'query'     => $wpdb->last_query
                        )
                    );
                }

                $response = $this->feedback_msg( $phone_number_id, $from_phone, $msg_body );

                echo $response;
                http_response_code(200);
                exit();
            
                /**
                 * Messaging Sent & Status
                 */
            }
            else if(
                $data->entry &&
                $data->entry[0]->changes &&
                $data->entry[0]->changes[0] &&
                $data->entry[0]->changes[0]->value->statuses &&
                $data->entry[0]->changes[0]->value->statuses[0]
            ) {

                $phone_number_id = $data->entry[0]->changes[0]->value->statuses[0]->recipient_id;
                $to_phone = $data->entry[0]->changes[0]->value->statuses[0]->recipient_id;
                $status = $data->entry[0]->changes[0]->value->statuses[0]->status;
                $wam_id = $data->entry[0]->changes[0]->value->statuses[0]->id;
                $timestamp = $data->entry[0]->changes[0]->value->statuses[0]->timestamp;
                $conv_id = $data->entry[0]->changes[0]->value->statuses[0]->conversation->id;
                $conv_expired = $data->entry[0]->changes[0]->value->statuses[0]->conversation->expiration_timestamp;
                $conv_type = $data->entry[0]->changes[0]->value->statuses[0]->conversation->origin->type;
                $pricing = $data->entry[0]->changes[0]->value->statuses[0]->pricing->billable;
                $pricing_model = $data->entry[0]->changes[0]->value->statuses[0]->pricing->category;

                /**
                 * Store information in database
                 */
                $insert = $wpdb->insert(
                    $this->table_sent,
                    array(
                        'phone_number_id'   => $phone_number_id,
                        'name'              => '',
                        'to_phone'          => $to_phone,
                        'status_sent'       => $status,
                        'wam_id'            => $wam_id,
                        'timestamp'         => $timestamp,
                        'conv_id'           => $conv_id,
                        'conv_expired'      => $conv_expired,
                        'conv_type'         => $conv_type,
                        'pricing'           => $pricing,
                        'pricing_model'     => $pricing_model
                    )
                );

                /**
                 * Updating wam_id and status_sent in table donate
                 * @since 18 Apr 2023
                 */
                $update_order = $wpdb->update(
                    $this->table_donate,
                    array(
                        'status_sent_order' => $status
                    ),
                    array(
                        'wam_id_order'      => $wam_id
                    )
                );

                if( $update_order === 0 || $update_order === false ) {
                    $update_slip = $wpdb->update(
                        $this->table_donate,
                        array(
                            'status_sent_slip' => $status
                        ),
                        array(
                            'wam_id_slip'   => $wam_id
                        )
                    );
                }
                // ------
        
                if( $insert === false ) {
                    $wpdb->insert(
                        $this->table_recieve_error,
                        array(
                            'error_msg' => $wpdb->last_error,
                            'query'     => $wpdb->last_query
                        )
                    );
                }

            }
            else { // error when data not valid

                $wpdb->insert(
                    $this->table_recieve,
                    array(
                        'message' => $json
                    )
                );
                
                http_response_code(200);
                exit();

            }
 
            /**
             * Handling $data->object doesn't exist
             */
        }
        else {
            // $wpdb->insert(
            //     $this->table_recieve_error,
            //     array(
            //         'error_msg' => 'josh: body object error'
            //     )
            // );

            // Return a '404 Not Found' if event is not from a WhatsApp API
            http_response_code(404);
            exit();
        }
    }

    private function get() {

        $mode       = $_GET['hub_mode'];
        $token      = $_GET['hub_verify_token'];
        $challenge  = $_GET['hub_challenge'];
        // echo $mode;
        // echo 'get';
        // var_dump($_GET);
        // var_dump(isset($token));

        // check if a mode and token were sent
        if( isset($mode) && isset($token) ) {
            // echo 'isset';
            // var_dump($token === $this->whatsapp_token);
            // var_dump( $this->whatsapp_token );
            // check the mode and token sent are correct
            if( $mode === 'subscribe' && $token === $this->verify_token ) {
                echo $challenge;
                // echo 'HAPPY';
                http_response_code(200);
                exit();
            } else {
                http_response_code(403);
                exit();
            }
        } else {
            http_response_code(403);
            exit();
        }
    }

    private function feedback_msg( $phone_number_id, $from, $msg_body ) {
        $msg_body = 'Ack: '.$msg_body;
        $payload = array(
            'messaging_product' => 'whatsapp',
            'to' => strval($from),
            'text'  => array(
                'body' => $msg_body
            )
        );

        // create a new cURL resource
        $ch = curl_init();

        // set the cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/'.$phone_number_id.'/messages?access_token='.$this->whatsapp_token); // set the URL of the endpoint
        curl_setopt($ch, CURLOPT_POST, true); // set the request method to POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $payload )); // set the JSON body of the request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the response as a string
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // set the content type header to JSON

        // execute the cURL request and get the response
        $response = curl_exec($ch);

        // check for errors
        if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        // handle the error
        }

        // close the cURL resource
        curl_close($ch);

        // process the response data
        $data = json_decode($response, true);
        // use the decoded data
        return $response;
    }

}

new Josh_WABA_Endpoint();