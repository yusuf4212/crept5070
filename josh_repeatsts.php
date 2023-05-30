<?php
global $wpdb;
$table_donate = $wpdb->prefix . 'dja_donate';

for($i=0; $i<350; $i++) {
    // $i += 1;
    // $i += 502;

    // take sample
    $query = "SELECT id, name, whatsapp, repeat_sts, repeat_no, created_at FROM $table_donate WHERE (repeat_sts IS NULL) ORDER BY id ASC LIMIT 1";
    $row = $wpdb->get_results( $query )[0];

    echo '<pre>SAMPLE===<br>'; var_dump( $row ); echo '</pre>';

    if( $row === null ) {
        continue;
    }
    
    // take all number same
    $query = "SELECT id, name, whatsapp, repeat_sts, repeat_no, created_at FROM $table_donate WHERE (whatsapp = '$row->whatsapp') ORDER BY id ASC";
    $rows = $wpdb->get_results( $query );
    
    echo '<pre>DATA BEFORE====<br>'; var_dump( $rows ); echo '</pre>';

    //begin process (change repeat)
    echo '<pre>DATA AFTER===<br>';
    $j = 0;
    foreach( $rows as $array ) {
        $j += 1;
        
        // $array->repeat_sts = ( $j === 1 ) ? 'new' : 'repeat';
        // $array->repeat_no = $j;
        $repeat_sts = ( $j === 1 ) ? 'new' : 'repeat';
        $repeat_no = $j;
        
        $update = $wpdb->update( // EXECUTE
            $table_donate,
            array(
                'repeat_sts'    => $repeat_sts,
                'repeat_no'      => $repeat_no
            ),
            array(
                'id'            => $array->id
            )
        );
        var_dump( $update );

        // foreach( $array as $key => $val) {
        //     echo "$val<br>";
        // }
        echo '<br>';
    }
    echo '</pre><br>=======================';
    $row =''; $query = ''; $rows = '';
}