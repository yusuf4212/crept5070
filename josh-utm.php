<?php
    function josh_phpparam() {
    
    // Initialize URL to the variable
    $url = '/bla-pgae/sec?placement=rightcolumn&campaign=abc&adset=eddr';
    $url = '/bla-pgae/sec?hub.challenge=cha&hub.verify_token=tokenn';
    $link1 = $_SERVER['REQUEST_URI'];
    $link2 = $_SERVER;
    // $link1 = $_SERVER;

    // Use parse_url() function to parse the URL
    // and return an associative array which
    // contains its various components
    $url_components = parse_url($url);

    // Use parse_str() function to parse the
    // string passed via URL
    parse_str($url_components['query'], $params);

    // Display result
    echo '<pre>';
    var_dump($link2);
    if($url_components['query']!=null) {
        $pass_q = '?' . $url_components['query'];
        echo 'pass this : ' . $pass_q; echo '<br>';
        var_dump($pass_q); echo '<br>';
    } else {
        echo 'empty, will not pass query anything!';
    }
    // var_dump($params);
    // echo 'Hi ' . $params['name']; echo '<br>';
    // var_dump($link1);
    echo '</pre>';

    }

    // function jpass_param() {
    //     $request_uri = $_SERVER['REQUEST_URI'];
    //     $url_components = parse_url($request_uri);
        
    //     if($url_components['query']!=null) {
    //         $pass_q = '?' . $url_components['query'];
    //         parse_str($url_components['query'], $params);
    //         $params['jpass'] = $pass_q;
    //         return $params;
    //     } else {
    //         echo '';
    //     }
    // }

    /**
     * @return array|string
     * 
     * @since 5 April 2023
     */
    function jpass_param() {
        /**
         * Get and parsing url component
         */
        $request_uri = $_SERVER['REQUEST_URI'];
        $url_components = parse_url($request_uri);
        
        if(isset($url_components['query'])) {
        // if($url_components['query']!=null) {
            /**
             * Parsing query to array associative (key => value)
             * 
             * Unset (remove) fbclid pair and jfbclid pair (debug)
             * 
             * Build query parameter after removing fbclid pair
             */
            parse_str( $url_components['query'], $query_params );

            unset( $query_params['fbclid'] );
            unset( $query_params['jfbclid'] );

            $query_string = http_build_query( $query_params );
            
            /**
             * Add '?' at first query string
             * 
             */
            $pass_q = '?' . $query_string;
            // $pass_q = '?' . $url_components['query'];
            // parse_str($url_components['query'], $params);
            $query_params['jpass'] = $pass_q;
            // $params['__jpass'] = $pass_q;

            return $query_params;

        } else {

            return '';

        }
    }
?>