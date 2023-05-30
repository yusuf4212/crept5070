<?php

global $wpdb;

$table_slip = $wpdb->prefix . 'josh_slip';

// get
$query = "SELECT id, whatsapp FROM $table_slip WHERE (whatsapp LIKE '62%') ORDER BY id LIMIT 300";
$rows = $wpdb->get_results( $query );

foreach( $rows as $data ) {
    echo '<pre>'; var_dump($data->whatsapp); echo '<pre>';
    if( substr( $data->whatsapp, 0, 2) === '62') {
        $data->whatsapp = '0' . substr($data->whatsapp,2 );
    }
    echo '<pre>'; var_dump($data->whatsapp); echo '<pre>';

    $update = $wpdb->update($table_slip, array('whatsapp' => $data->whatsapp), array('id' => $data->id));
    echo '<pre>'; var_dump($update); echo '<pre>';
}