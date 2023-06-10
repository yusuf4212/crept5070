<?php
/**
 * @param array $time_range should be associative array. 'dateRange' => 'value'. Example 'date_preset' => 'last_month'
 * @return bool|array|string false if error, array associative if connection ok.
 */
function jh_get_spent_($time_range) {
    global $wpdb;

    $table_settings = $wpdb->prefix . 'dja_settings';

    if(! is_array($time_range)) {
        return false;
    }

    
    $key_ = 'date_preset';
    switch ($time_range['date_filter']) {
        case 'today':
            $val_ = 'today';
            break;

        case 'yesterday':
            $val_ = 'yesterday';
            break;
            
        case '7lastdays':
            $val_ = 'last_7d';
            break;
        
        case '30lastdays':
            $val_ = 'last_30d';
            break;
        
        case 'thismonth':
            $val_ = 'this_month';
            break;

        case 'lastmonth':
            $val_ = 'last_month';
            break;

        case 'all':
            $val_ = 'maximum';
            break;
            
        default:
            $key_ = 'time_range';

            $_date = explode('_', $time_range['date_range']);
            $__date = [
                'since' => $_date[0],
                'until' => $_date[1]
            ];

            $val_ = json_encode($__date);

            break;
    }
    // $val_ = $time_range['date_filter'];

    $query = "SELECT data
    FROM $table_settings
    WHERE type='ad_account_id' or type='fb_graphapi_token' or type='fb_graphapi_version'
    ORDER BY id";
    $rows = $wpdb->get_results($query);

    $ad_account_id          = $rows[0]->data;
    $fb_graphapi_token      = $rows[1]->data;
    $fb_graphapi_version    = $rows[2]->data;

    $data = [
        'access_token'  => $fb_graphapi_token,
        $key_           => $val_
    ];

    $url = "https://graph.facebook.com/$fb_graphapi_version/$ad_account_id/insights";

    $query = '?' . http_build_query($data);

    $ch = curl_init($url . $query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);

        // Handle cURL error
        return 'cURL Error: ' . $error;
    } else {
        // Process the response
        $data = json_decode($response, true);
        
        // Use the $data variable for further processing
        curl_close($ch);
        return $data;
    }

}