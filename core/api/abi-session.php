<?php
session_start();
if( ! isset( $_SESSION['abi_start'] ) ) {
    $_SESSION['abi_start'] = time();
}
if( !isset( $_SESSION['abi_identifier'] ) ) {
    init_session();
}

$interval_start = time() - $_SESSION['abi_start'];

if( $interval_start > 1200 ) { // 20 menit

    after_exp();

}

function init_session() {

    $session_identifier = random_string( 3, 8 );
    $_SESSION['abi_identifier'] = $session_identifier;
    
}


function after_exp() {
    
    $session_identifier = random_string( 3, 8);
    $_SESSION['abi_identifier'] = $session_identifier;
    $_SESSION['abi_start'] = time();
    
}