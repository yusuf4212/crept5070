<?php
class Send_API {
    private $queue;
    static function begin() {
        new Send_API();
    }

    public function __construct() {
        $this->get_data();
    }

    private function get_data() {
        global $wpdb;

        $table_wait = $wpdb->prefix . 'josh_capi_wait';
        $table_pixel = $wpdb->prefix . 'josh_capi_pixel';

        $query = "SELECT pixel_id FROM $table_wait WHERE status ='0' GROUP BY pixel_id ORDER BY id ASC";
        $rows = $wpdb->get_results( $query );
        
        // echo '<pre>'; var_dump( $rows ); echo '</pre>';
        // echo '<pre>';
        if( ! count( $rows ) > 0 ) {
            return;
        } else {
            
            foreach( $rows as $val ) {
                $query = "SELECT access_token FROM $table_pixel WHERE pixel_id='$val->pixel_id'";
                $get_access_token = $wpdb->get_row( $query );
                
                $pixel_id = $val->pixel_id;
                $access_token = $get_access_token->access_token;
                // $access_token = $val->access_token;
    
                $query = "SELECT * FROM $table_wait WHERE status ='0' and pixel_id='$pixel_id' LIMIT 50 OFFSET 0";
                $rows = $wpdb->get_results( $query );
                
                if( $rows != null ) {
        
                    $this->queue = $rows;
        
                    $this->init( $pixel_id, $access_token );
        
                } else {
        
                    /**
                     * @todo Need to add something if there is not queue capi, send log if the system are keep listening!
                     */
                }
            }
            // echo '</pre>';

        }
    }

    private function init( $pixel_id, $access_token ) {
        $capi = $this->queue;
        $payload = array( "data" => array() );
        $id = array();
        // echo '<pre>'; var_dump( $capi ); echo "</pre>";

        foreach( $capi as $row ) {

            // $fbp = $row->fbp;
            // $fbc = $row->fbc;
            // $campaign_data = $row->campaign_data;
            // $test_code = $row->test_code;
            // $event_time = $row->event_time;
            // $action_source = $row->action_source;
            // $event_source_url = $row->event_source_url;

            // Depricated, move this logic to beginning (fb-conversion-api.php)
            // if( $row->link_code == 'josh' ) {
            //     $this->update_wait_list( $row->id, 2 );
            //     continue;
            // }

            $id[] = $row->id;
            $track_mode = $row->track_mode;
            $test_code = $row->test_code;
            $donate_data = json_decode( $row->donate_data );
    
            if( $donate_data != null ) {
                
                $event_id = $donate_data->invoice_id;
    
            } else {
    
                $event_id = $row->session_identifier;
    
            }
    
            $opt_out = '';
    
            $event_name = $this->event_name( $track_mode );
    
            // $payload['data'][] = $this->assembling(
            //     $event_name, $event_time, $action_source, $event_source_url, $event_id, $test_code,
            //     $donate_data, $campaign_data, $fbp, $fbc
            // );
            $payload['data'][] = $this->assembling( $row, $event_name, $event_id );
            
        }
        
        if( count($payload['data']) > 0 ) {
            
            if( $test_code != null ) {
                $payload['test_event_code'] = $test_code;
            }
            
            $payload = json_encode( $payload );
            
            // echo '<pre>'; var_dump( $link_code ); echo '</pre>';
            // echo '<pre>'; var_dump( $pixel_id ); echo '</pre>';
            // echo '<pre>'; var_dump( $access_token ); echo '</pre>';
            echo '<pre>'; var_dump( $payload ); echo '</pre>';
            // echo '<pre>'; var_dump( $this->fbc ); echo '</pre>';
    
            $this->sending( $payload, $pixel_id, $access_token, $id );

        }

    }

    private function event_name( $track_mode ) {
        if( isset( $track_mode ) ) {
            // $track_mode = $this->track_mode;

            if( $track_mode == 'landingpage' ) {

                return 'ViewContent';

            } else if( $track_mode == 'form' ) {

                return 'InitiateCheckout';

            } else if( $track_mode == 'thankyoupage' ) {

                return 'Purchase';

            } else {

                return 'PageView'; // last option

            }
        }
    }

    // private function assembling(
    //     $event_name, $event_time, $action_source, $event_source_url, $event_id, $test_code,
    //     $donate_data, $campaign_data, $fbp, $fbc
    //     ) {
    private function assembling( $row, $event_name, $event_id ) {

        $user_data = $this->assembling_user_data( $row );

        $custom_data = $this->assembling_custom_data( $row->donate_data, $row->campaign_data );

        $assembler = array(
            "event_name"    => $event_name,
            "event_time"    => strval( strtotime( $row->event_time ) ),
            "action_source" => $row->action_source,
            "event_source_url" => $row->event_source_url,
            "event_id"      => $event_id,
            "user_data"     => $user_data,
            "custom_data"   => $custom_data
            
        );

        return $assembler;

    }

    private function assembling_user_data( $row ) {
        $donate_data = json_decode( $row->donate_data );
        $campaign_data = json_decode( $row->campaign_data );
        $fbp = $row-> fbp;
        $fbc = $row-> fbc;
        // $donate_data = $this->donate_data;
        $user_data = array();

        if( $donate_data != null ) {

            $user_data['em']     = array( hash( 'sha256', $donate_data->email ) );
            $user_data['ph']     = array( hash( 'sha256', $donate_data->whatsapp ) );
            $user_data['fn']     = array( hash( 'sha256', $donate_data->name ) );
            
            $user_data['client_ip_address'] = $donate_data->ip_address;
            $user_data['client_user_agent'] = $donate_data->user_agent;
            if( $donate_data->city != null ) {
                $user_data['ct'] = array( strtolower( hash( 'sha256', $donate_data->city ) ) );
            }
            $user_data['country'] = array( strtolower( hash( 'sha256', $donate_data->country ) ) ); // because it is not in 2 letter code. expected 'id'
            // $user_data['country'] = array( hash( 'sha256', 'id' ) );

        } else {
            
            $user_data['client_ip_address'] = $row->ip_address;
            $user_data['client_user_agent'] = $row->user_agent;
            if( $row->city != null ) {
                $user_data['ct'] = array( strtolower( hash( 'sha256', $row->city ) ) );
            }
            $user_data['country'] = array( strtolower( hash( 'sha256', $row->country ) ) );

        }


        // if( $fbp != null ) {

        //     $user_data['fbp'] = $fbp;

        // }
        $user_data['fbp'] = ( $fbp != null ) ? $fbp : '';


        // if( $fbc != null ) {
        //     $user_data['fbc'] = $fbc;
        // }
        $user_data['fbc'] = ( $fbc != null ) ? $fbc : '';

        return $user_data;
        
    }

    /**
     * 
     */
    private function assembling_custom_data( $donate_data, $campaign_data ) {
        // $donate_data = $this->donate_data;
        // $campaign_data = $this->campaign_data;
        $donate_data = json_decode( $donate_data );
        $campaign_data = json_decode( $campaign_data );
        $custom_data = array();

        if( $donate_data != null ) {

            $custom_data['currency'] = 'IDR';
            $custom_data['value']    = $donate_data->nominal;
            $custom_data['content_name']    = $campaign_data->title;
            $custom_data['content_ids']    = $campaign_data->campaign_id;
            $custom_data['order_id']    = $donate_data->invoice_id;
            
        } else {
            
            $custom_data['content_name']    = $campaign_data->title;
            $custom_data['content_ids']    = $campaign_data->campaign_id;

        }

        return $custom_data;

    }

    private function sending( $payload, $pixel_id, $access_token, $id ) {
        global $wpdb;
        $table_log = $wpdb->prefix . 'josh_capi_log_send';
        $table_wait = $wpdb->prefix . 'josh_capi_wait';
        // echo '<pre>'; var_dump($access_token); echo '</pre>';

        $version = 'v16.0';
        // $event_id = 'juice';
        // $test_event_code = 'TEST90527';
        
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, 'https://graph.facebook.com/'.$version.'/'.$pixel_id.'/events?access_token='.$access_token );
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        
        
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json') );
        
        $output = curl_exec( $curl );
        curl_close($curl);
        echo '<pre>'; var_dump($id); echo '</pre>';
        echo '<pre>'; var_dump($output); echo '</pre>';
        // echo '<pre>'; var_dump($_COOKIE); echo '</pre>';
        // echo '<pre>'; var_dump($_SERVER['REQUEST_URI']); echo '</pre>';
        
        $wpdb->insert(
            $table_log,
            array( "response" => $output ),
            array( '%s' )
        );

        $output = json_decode( $output );
        // echo '<pre>'; var_dump($output); echo '</pre>';
        // echo '<pre>'; var_dump(isset($output->events_received)); echo '</pre>';
        if( isset($output->events_received) ) {

            // $this->update_wait_list( $id, 1 );

            foreach( $id as $val ) {
                $update = $wpdb->update(
                    $table_wait,
                    array( "status" => 1 ),
                    array( "id" => $val ),
                    array( '%d' ),
                    array( '%s' )
                );
            }

        }
    }

    /**
     * @param array|string $id
     * @param int $status
     */
    private function update_wait_list( $id, $status ) {
        global $wpdb;
        $table_wait = $wpdb->prefix . 'josh_capi_wait';

        // foreach( $id as $val ) {

        //     $update = $wpdb->update(
        //         $table_wait,
        //         array( "status" => 1 ),
        //         array( "id" => $val ),
        //         array( '%d' ),
        //         array( '%s' )
        //     );
            
        //     // echo "<pre>$update</pre>";
            
        // }
        
        // $update = $wpdb->update(
        //     $table_wait,
        //     array( "status" => $status ),
        //     array( "id" => $id ),
        //     array( '%d' ),
        //     array( '%s' )
        // );

    }

}