<?php function joshdb() {
    global $wpdb;

    $mytable = $wpdb->prefix . 'josh_faildonate';

    $row = $wpdb->get_results("SELECT data FROM " . $mytable);

    // foreach ($row as $k => $v) {
        
    // }
    $array = json_decode($row[0]->data);

    echo '<pre>';
    var_dump($array);
    echo '</pre>';
}
?>