<?php
// echo '<pre>'; var_dump( $track_mode ); echo '</pre>';
class ABI_Facebook_Conversion_API {
    /**
     * 
     */
    private $track_mode;
    /**
     * 
     */
    private $donate_data;
    /**
     * 
     */
    private $campaign_data;
    /**
     * 
     */
    private $test_code;
    /**
     * 
     */
    private $pixel_id;
    /**
     * 
     */
    private $access_token;


    /**
     * false if empty
     * @var string|bool
     */
    private $fbc;
    /**
     * false if empty
     * @var string|bool
     */
    private $fbp;
    /**
     * 
     */
    private $event_name;
    /**
     * 
     */
    private $user_data;
    /**
     * 
     */
    private $custom_data;
    /**
     * 
     */
    private $domain_cookie;


    /**
     * @param string $track_mode hold tracking mode value, 'landingpage' 'form' 'thankyoupage'
     * @param string|null $test_code null if this was not test event. hold test value if this mode was testing
     */
    public function __construct( $pixel_id, $track_mode, $link_code = 'campaign', $campaign_data = null, $donate_data = null, $test_code = null ) {
    // public function __construct( $pixel_id, $access_token, $track_mode, $link_code = 'campaign', $campaign_data = null, $donate_data = null, $test_code = null ) {
        global $wpdb;
        $table_wait         = $wpdb->prefix . 'josh_capi_wait';
        $table_wait_fail    = $wpdb->prefix . 'josh_capi_wait_fail';
        $table_settings     = $wpdb->prefix . 'dja_settings';
        
        /**
         * Get run_capi value
         */
        {
            $query = "SELECT data
            FROM $table_settings
            WHERE type='run_capi'";

            $run_capi = $wpdb->get_row($query)->data;
        }

        // $this->track_mode = $track_mode;
        // $this->donate_data = $donate_data;
        // $this->campaign_data = $campaign_data;
        /**
         * JANGAN LUPA COOKIE _FBC dan _FBP dibawah!!
         */
        // $test_code = 'TEST4333';    // TEST CODE
        $this->domain_cookie = '.'.$_SERVER['HTTP_HOST'];
        $event_time = time();
        $action_source = 'website';
        $event_source_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $session_identifier = $_SESSION['abi_identifier'];
        $fbp = $this->fbp();
        $fbc = $this->fbc();
        $campaign_data = ( $campaign_data == null ) ? null : json_encode( $campaign_data );
        $donate_data = ( $donate_data == null ) ? null : json_encode( $donate_data );
        $user_id = get_current_user_id();

        $opt_out = '';

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = null;
        
        $referrer_url = null;
        if( isset( $_SERVER['HTTP_REFERER'] ) ) {
            $referrer_url = $_SERVER['HTTP_REFERER'];
        }

        if($run_capi === '1') {
            $status = ( $link_code == 'josh' ) ? 2 : 0;
            $status = ( strpos( $_SERVER['HTTP_USER_AGENT'], 'WhatsApp' ) === false ) ? $status : 3;
        } else {
            $status = null;
        }

        // $this->user_data();

        // $this->custom_data();
        // $event_name = $this->event_name( $track_mode );

        // $payload = $this->assembling( $event_time, $action_source, $event_source_url, $event_id );
        // $payload = json_encode( $payload );
        
        // echo '<pre>'; var_dump( $link_code ); echo '</pre>';
        // if( $link_code=='josh' ) {
        //     echo '<pre>'; var_dump( $payload ); echo '</pre>';
            // echo '<pre>'; var_dump( $this->fbc ); echo '</pre>';
        // }

        // $this->sending( $payload );
        $city = (isset($_SERVER['GEOIP_CITY'])) ? $_SERVER['GEOIP_CITY'] : null;
        $country_code = (isset($_SERVER['GEOIP_CITY_COUNTRY_CODE'])) ? $_SERVER['GEOIP_CITY_COUNTRY_CODE'] : null;

        $insert = $wpdb->insert(
            $table_wait,
            array(
                "status"        => $status,
                "pixel_id"      => $pixel_id,
                // "access_token"  => $access_token,
                "track_mode"    => $track_mode,
                "link_code"     => $link_code,
                "user_id"       => $user_id,
                // "event_time"    => $event_time,
                "action_source" => $action_source,
                "event_source_url" => $event_source_url,
                "session_identifier" => $session_identifier,
                "fbp"           => $fbp,
                "fbc"           => $fbc,
                "referrer_url"  => $referrer_url,
                "city"          => $city,
                "country"       => $country_code,
                "ip_address"    => $ipaddress,
                "user_agent"    => $_SERVER['HTTP_USER_AGENT'],
                "campaign_data" => $campaign_data,
                "donate_data"   => $donate_data,
                "test_code"     => $test_code
            )
        );

        if( $insert == false ) {
            $wpdb->insert(
                $table_wait_fail,
                array(
                    "data" => $wpdb->last_error,
                    "query_text" => $wpdb->last_query,
                    "session_identifier" => $session_identifier,
                    "event_source_url"  => $event_source_url
                )
            );
        }

        // echo '<pre>'; var_dump( $insert ); echo '</pre>';
        // echo '<pre>'; var_dump( $event_time ); echo '</pre>';
    }

    /**
     * Give event name based on opened page
     * landingpage, form, thankyoupage
     * PageView, InitiateCheckout, Purchase
     */
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

    private function fbp() {
        if( isset($_COOKIE['_fbp']) ) {

            setcookie( '_fbp', $_COOKIE['_fbp'], time() + (86400 * 90), '/', $this->domain_cookie);
            // setcookie( 'j_fbp', $_COOKIE['_fbp'], time() + (86400 * 90), '/', $this->domain_cookie);
            return $_COOKIE['_fbp'];

        } else {

            $newfbp = 'fb.' . '2.' . strval( floor(microtime(true) * 1000) ) . '.' . strval( rand( 1000000000, 9999999999) );
            setcookie( '_fbp', $newfbp, time() + (86400 * 90), '/', $this->domain_cookie);
            // setcookie( 'j_fbp', $newfbp, time() + (86400 * 90), '/', $this->domain_cookie);
            return $newfbp;

        }
    }
    
    /**
     * 
     */
    // private function user_data() {
    //     if( $this->event_name == 'Purchase' ) {
    //         $email = $this->donate_data->email;
    //         $phone = $this->donate_data->whatsapp;
    //         $first_name = $this->donate_data->name;
    //         $last_name = null;
    //     }

    //     $client_ip_address = $this->donate_data->ip_address;
    //     $client_user_agent = $this->donate_data->user_agent;
    //     $city = $this->donate_data->city;
    //     $country = $this->donate_data->country;


    //     $this->fbc = $this->fbc();
        
    // }

    /**
     * 
     */
    // private function custom_data() {
    //     $currency = 'IDR';
    //     $value = $this->donate_data->nominal;
    //     $content_name = $this->campaign_data->title;
    //     $content_ids = $this->campaign_data->campaign_id;
    //     $content_category = '';
    //     $num_items = '';
    //     $order_id = $this->donate_data->invoice_id;
    // }

    /**
     * 
     * @param int $event_time
     * @param string $action_source
     * @param string $event_source_url
     */
    // private function assembling( $event_time, $action_source, $event_source_url, $event_id ) {

    //     $user_data = $this->assembling_user_data();

    //     $custom_data = $this->assembling_custom_data();

    //     $assembler = array(
    //         "data" => array(
    //             array(
    //                 "event_name"    => $this->event_name,
    //                 "event_time"    => strval( $event_time ),
    //                 "action_source" => $action_source,
    //                 "event_source_url" => $event_source_url,
    //                 "event_id"      => $event_id,
    //                 "user_data"     => $user_data,
    //                 "custom_data"   => $custom_data
    //             )
    //         )
    //     );

    //     if( $this->test_code != null ) {
    //         $assembler['test_event_code'] = $this->test_code;
    //     }

    //     return $assembler;

    //     // $assembler = '{
    //     //     "data" : [
    //     //         {
    //     //             "event_name": "",
    //     //             "event_time": "",
    //     //             "action_source": "",
    //     //             "event_source_url": "",
    //     //             "event_id": "",
    //     //             "opt_out": "",
    //     //             "user_data" : {
    //     //                 "em": [""],
    //     //                 "ph": [""],
    //     //                 "fn": [""],
    //     //                 "ln": [""],
    //     //                 "client_ip_address": "",
    //     //                 "client_user_agent": "",
    //     //                 "ct": [""],
    //     //                 "country": [""],
    //     //                 "fbp": "",
    //     //                 "fbc": ""
    //     //             },
    //     //             "custom_data": {
    //     //                 "currency": "IDR",
    //     //                 "value": 0,
    //     //                 "content_name": "",
    //     //                 "content_ids": "",
    //     //                 "content_category": "",
    //     //                 "num_items": "",
    //     //                 "order_id": ""
    //     //             }
    //     //         }
    //     //     ],
    //     //     "test_event_code" : ""
    //     // }';
    // }

    /**
     * 
     */
    // private function assembling_user_data() {
    //     $donate_data = $this->donate_data;
    //     $user_data = array();

    //     if( $donate_data != null ) {

    //         $user_data['em']     = array( hash( 'sha256', $donate_data->email ) );
    //         $user_data['ph']     = array( hash( 'sha256', $donate_data->whatsapp ) );
    //         $user_data['fn']     = array( hash( 'sha256', $donate_data->name ) );
            
    //         $user_data['client_ip_address'] = $donate_data->ip_address;
    //         $user_data['client_user_agent'] = $donate_data->user_agent;
    //         $user_data['ct'] = array( hash( 'sha256', $donate_data->city ) );
    //         // $user_data['country'] = array($donate_data->country); // because it is not in 2 letter code. expected 'id'
    //         $user_data['country'] = array( hash( 'sha256', 'id' ) );

    //     } else {
            
    //         $user_data['client_ip_address'] = $_SERVER['REMOTE_ADDR'];
    //         $user_data['client_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    //         $user_data['ct'] = array( strtolower( hash( 'sha256', $_SERVER['GEOIP_CITY'] ) ) );
    //         $user_data['country'] = array( strtolower( hash( 'sha256', $_SERVER['GEOIP_CITY_COUNTRY_CODE'] ) ) );

    //     }


    //     if( isset($_COOKIE['_fbp']) ) {

    //         $user_data['fbp'] = $_COOKIE['_fbp'];
    //         setcookie( 'j_fbp', $_COOKIE['_fbp'], time() + (86400 * 90), '/', $this->domain_cookie);

    //     } else {

    //         $newfbp = 'fb.' . '2.' . strval( floor(microtime(true) * 1000) ) . '.' . strval( rand( 1000000000, 9999999999) );
    //         $user_data['fbp'] = $newfbp;
    //         setcookie( 'j_fbp', $newfbp, time() + (86400 * 90), '/', $this->domain_cookie);

    //     }


    //     // if( $this->fbp != false ) {
    //     //     $user_data['fbp'] = $this->fbp;
    //     // }
    //     $this->fbc();

    //     if( $this->fbc != false ) {
    //         $user_data['fbc'] = $this->fbc;
    //     }

    //     return $user_data;
        
    // }

    /**
     * 
     */
    // private function assembling_custom_data() {
    //     $donate_data = $this->donate_data;
    //     $campaign_data = $this->campaign_data;
    //     $custom_data = array();

    //     if( $donate_data != null ) {

    //         $custom_data['currency'] = 'IDR';
    //         $custom_data['value']    = $donate_data->nominal;
    //         $custom_data['content_name']    = $campaign_data->title;
    //         $custom_data['content_ids']    = $campaign_data->campaign_id;
    //         $custom_data['order_id']    = $donate_data->invoice_id;
            
    //     } else {
            
    //         $custom_data['content_name']    = $campaign_data->title;
    //         $custom_data['content_ids']    = $campaign_data->campaign_id;

    //     }

    //     // echo '"custom_data": {
    //     //     "currency": "IDR",
    //     //     "value": 0,
    //     //     "content_name": "",
    //     //     "content_ids": "",
    //     //     "content_category": "",
    //     //     "num_items": "",
    //     //     "order_id": ""
    //     // }';

    //     return $custom_data;

    // }

    /**
     * Responsible for fbc (facebook click id) value
     * it will fill $fbc with string or false
     */
    private function fbc() {
        $fbc_param = $this->url_param(); // check fbclid in url param

        if( $fbc_param == false ) {

            if( isset($_COOKIE['_fbc']) ) {

                setcookie( '_fbc', $_COOKIE['_fbc'], time() + (86400 * 90), '/', $this->domain_cookie );
                // setcookie( 'j_fbc', $_COOKIE['_fbc'], time() + (86400 * 90), '/', $this->domain_cookie );
                return $_COOKIE['_fbc'];

            } else {

                return null;

            }

        } else {

            setcookie( '_fbc', $fbc_param, time() + (86400 * 90), '/', $this->domain_cookie);
            // setcookie( 'j_fbc', $fbc_param, time() + (86400 * 90), '/', $this->domain_cookie);
            return $fbc_param;

        }
    }


    /**
     * @return string|bool return string|bool false if not exist, value of given facebook click id if exist
     */
    private function url_param() {
        $url = $_SERVER['REQUEST_URI'];
        $url_parse = parse_url( $url, PHP_URL_QUERY );
        parse_str( $url_parse, $query_parse );


        return ( isset($query_parse['fbclid']) ) ? 'fb.' . '2.' . strval( floor(microtime(true) * 1000) ) . '.' . $query_parse['fbclid'] : false;

    }

    // private function sending( $payload ) {
    //     $version = 'v16.0';
    //     $pixel_id = '630434134671269';
    //     $access_token = 'EAAMncttZCQYsBAJplDAY8DFuIfbdi2F9YXjcXCmUBQrwn1msz5iG0IjOBzrdZAeoAy3HjHrgj86W4ZB8vbMe8IeKpvqZCyUpZBrYkb60Mhht5nushroHJ0YlCbEWyHEKSbZAmVQ4sYyOcCOZBvxb6AfpJLmd2jKX5ID4eWXRZBTJZAQ3rU1hehISWLdtIUDbAmq8ZD';
    //     $event_id = 'juice';
    //     $test_event_code = 'TEST90527';
        
    //     $curl = curl_init();
    //     curl_setopt( $curl, CURLOPT_URL, 'https://graph.facebook.com/'.$version.'/'.$pixel_id.'/events?access_token='.$access_token );
    //     curl_setopt( $curl, CURLOPT_POST, 1 );
    //     // curl_setopt( $curl, CURLOPT_RETURNTRANSFER, false );
        
    //     // $payload = '{"data":[{"event_name":"InitiateCheckout","event_time":"'.time().'","event_id":"'.$event_id.'","event_source_url":"https://ympb.or.id/josh/jumat-berkah/donate","user_data":{"client_ip_address":"1.2.3.4","client_user_agent":"test user agent","em":["7b17fb0bd173f625b58636fb796407c22b3d16fc78302d79f0fd30c2fc2fc068"],"ph":["27d38d3380f0038d2fee0ea32b1622a59c425d8bc901626c245f3025ec517373"]},"custom_data":{"currency":"IDR","value":25000,"content_ids":"abcid123","content_name":"nama program","customJosh":"canit"}}],"test_event_code":"'.$test_event_code.'"}';
        
    //     curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
    //     curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json') );
        
    //     $output = curl_exec( $curl );
    //     curl_close($curl);
    //     // echo '<pre>'; var_dump($output); echo '</pre>';
    //     // echo '<pre>'; var_dump($_COOKIE); echo '</pre>';
    //     // echo '<pre>'; var_dump($_SERVER['REQUEST_URI']); echo '</pre>';

    // }

}