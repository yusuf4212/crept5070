<?php
// header('Content-Type: application/json');

// global $wpdb;
// $table_capi_wait = $wpdb->prefix.'josh_capi_wait';

// $sql = "SELECT id FROM $table_capi_wait WHERE status='0' ORDER BY id LIMIT 150";
// $rows = $wpdb->get_results( $sql );

// foreach( $rows as $val ) {

//     $update = $wpdb->update(
//         $table_capi_wait,
//         array( 'status' => 4 ),
//         array( 'id' => $val->id )
//         // array( '%d' ),
//         // array( '$d' )
//     );

//     echo '<pre>'; var_dump( $update ); echo '</pre>';
// }

// $get_schedules = wp_get_schedules();
// echo '<pre>'; var_dump( $get_schedules ); echo '</pre>';

$cron_jobs = wp_get_ready_cron_jobs();
echo '<pre>'; var_dump( $cron_jobs ); echo '</pre>';

// foreach ( $cron_jobs as $timestamp => $cron ) {
//     foreach ( $cron as $hook => $details ) {
//         $function_name = $details['callback']['function'];
//         echo "Function '$function_name' is scheduled to run at $timestamp with hook '$hook'.\n";
//     }
// }