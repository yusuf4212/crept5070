<?php

global $wpdb;
$table_name = $wpdb->prefix.'dja_donate';

$invoice = $_GET['inv'];

if ($invoice != null) {

    $sql = "SELECT * FROM " . $table_name . " WHERE invoice_id='" . $invoice . "'";
    $row = $wpdb->get_results($sql);
    $img = $row[0]->img_confirmation_url;
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Slip Transfer</title>
        <style>
            img {
                width: 360px;
            }
            @media screen and (min-width: 500px) {
                img {
                width: 500px;
                }
            }
        </style>
    </head>
    <body>
        <?php
        $josh_content = '
        <div style = "display: flex; justify-content: center;" >
            <img src="'.$img.'">
            </div>
            ';
            
            echo $josh_content;
            
            $wpdb->update( 'ympb2020_dja_donate',     //table
                    array(
                        'img_confirmation_status' => intval('1')),
                        array('invoice_id' => $invoice));
                
                
        } else {
            echo '<pre>';
            echo 'this page is not for public';
            echo '</pre>';
                
        }
        ?>
            
    </body>
    </html>