<?php

global $wpdb;

$table_donate = $wpdb->prefix . 'dja_donate';
$table_donors = $wpdb->prefix . 'josh_donors';
$table_slip = $wpdb->prefix . 'josh_slip';

/**
 * FROM TABLE DONATE
 */
// // get donors data from donate table
// // $query = "SELECT id, whatsapp, name, cs_id, ref FROM $table_donate WHERE (donors IS NULL) GROUP BY whatsapp ORDER BY id ASC LIMIT 3000, 10";
// $query = "SELECT id, whatsapp, name, cs_id, ref FROM $table_donate WHERE (donors IS NULL) GROUP BY whatsapp ORDER BY id ASC";
// $donors = $wpdb->get_results( $query );

// echo '<pre>'; var_dump( $donors ); echo '</pre>';

// $cs_array = array( 3 => 'husna', 9 => 'fadhilah', 10 => 'meisya', 11 => 'safina');

// // insert it into donors table
// foreach( $donors as $data ) {
//     // owned by
//     if($data->cs_id != '0') {
//         $owned_by = $cs_array[$data->cs_id];
//     } else {
//         $owned_by = $data->ref;
//     }

//     // since
//     $query = "SELECT created_at FROM $table_donate WHERE whatsapp='$data->whatsapp' ORDER BY id ASC";
//     $since = $wpdb->get_row( $query )->created_at;

//     // city
//     $query = "SELECT city FROM $table_donate WHERE whatsapp='$data->whatsapp' ORDER BY id DESC";
//     $city = $wpdb->get_row( $query )->city;
    
//     echo '==============';
//     echo "<pre>$data->whatsapp<br>$owned_by<br>$since<br>$city<br></pre>";

//     $insert = $wpdb->insert(
//         $table_donors,
//         array(
//             'whatsapp'      => $data->whatsapp,
//             'owned_by'      => $owned_by,
//             'since'         => $since,
//             'add_reason'    => 'web_donate',
//             'city'          => $city
//         )
//     );

//     echo '<pre>'; var_dump( $insert ); echo '</pre>';
//     if( $insert === false ) {
//         echo "<pre>$wpdb->last_error</pre>";
//     }
// }


/**
 * FROM TABLE SLIP
 */
// // $query = "SELECT id, whatsapp, relawan FROM $table_slip GROUP BY whatsapp ORDER BY id ASC LIMIT 100, 3";
// $query = "SELECT id, whatsapp, relawan FROM $table_slip GROUP BY whatsapp ORDER BY id ASC";
// $donors = $wpdb->get_results( $query );

// echo '<pre>'; var_dump( $donors ); echo '</pre>';

// $cs_array = array( 'Husna' => 'husna', 'Tisna' => 'fadhilah', 'Meisya' => 'meisya', 'Safina' => 'safina');

// // insert it into donors table
// foreach( $donors as $data ) {
//     // owned by
//     $owned_by = $cs_array[$data->relawan];

//     // since
//     $query = "SELECT given_date FROM $table_slip WHERE whatsapp='$data->whatsapp' ORDER BY id ASC";
//     $since = $wpdb->get_row( $query )->given_date;

    
//     echo '==============';
//     echo "<pre>$data->whatsapp<br>$owned_by<br>$since<br></pre>";

//     $insert = $wpdb->insert(
//         $table_donors,
//         array(
//             'whatsapp'      => $data->whatsapp,
//             'owned_by'      => $owned_by,
//             'since'         => $since,
//             'add_reason'    => 'non_web',
//             'city'          => null
//         )
//     );

//     echo '<pre>'; var_dump( $insert ); echo '</pre>';
//     if( $insert === false ) {
//         echo "<pre>$wpdb->last_error</pre>";
//     }
// }


/**
 * Update name
 */
// $query = "SELECT id, whatsapp FROM $table_donors WHERE (name IS NULL) ORDER BY id DESC LIMIT 500";
// $rows = $wpdb->get_results( $query );

// echo '<pre>'; var_dump( $rows ); echo '</pre>';

// foreach( $rows as $data ) {

//     $query = "SELECT name FROM $table_donate WHERE (whatsapp='{$data->whatsapp}') ORDER BY id ASC";
//     $name = $wpdb->get_row( $query )->name;

//     // check if the name are not more than 250 char
//     if( strlen( $name ) > 250 ) {
//         $name = substr( $name, 0, 250 );
//     }
//     else if( $name == null ) {
//         $name = 'Hamba Allah';
//     }

//     echo '<pre>'; var_dump( $name ); echo '</pre>';

//     $update = $wpdb->update(
//         $table_donors,
//         array(
//             'name'      => $name
//         ),
//         array(
//             'id'        => $data->id
//         )
//     );

//     echo '<pre>'; var_dump( $update ); echo '</pre>';
// }