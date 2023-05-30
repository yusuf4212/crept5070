<?php
/**
 * Generate Random String
 * 
 * @param int $n number of character
 * @param int $mode 0 for 1aB | 1 for 1A | 2 for 1 (just number) | 3 for 1ab
 */
function random_string(int $mode = 0, int $n = 4) {
    if( $mode === 0) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    } else if( $mode === 1) { // number and caps string
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    } else if( $mode === 2) { // just number
        $characters = '0123456789';
    } else if( $mode === 3) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    }
    $randomString = '';
 
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
 
    return $randomString;
}