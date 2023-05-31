<?php function josh_noduplicate() { ?>
    
    <?php
        global $wpdb;
        $table_name = $wpdb->prefix.'dja_donate';
        $table_name2 = $wpdb->prefix . 'dja_campaign';
        $invoice_id = 'INV-230102SNLC6';

        $sql = "SELECT a.*, b.title from `" . $table_name . "` a left JOIN `" .$table_name2. "` b ON b.campaign_id = a.campaign_id where a.invoice_id='" . $invoice_id . "'";
        $row = $wpdb->get_results($sql)[0];

        $program = $row->title;
        $confirm_date = $row->img_confirmation_date;
        $invoice_id = $row->invoice_id;
        $cs_id = $row->cs_id;
        $cs = get_userdata($cs_id);
        $cs2 = $cs->display_name;
        $name = $row->name;
        $whatsapp = $row->whatsapp;
        $total = 'Rp '.number_format($row->nominal,0,",",".");
        $order_date = date("j F Y - h:i", strtotime($row->created_at));
        $slip_date = date("j F Y - h:i", strtotime($row->img_confirmation_date));

        // echo "<pre>";
        // echo "command nya : " . $sql . "<br>";
        // var_dump($row);
        // echo "</pre>";

    ?>
    
    <style>
        .title {
            width: 135px; font-weight: bold;
        }
        .colon {
            width: 10px;
        }
        .value {
            width: 250px;
        }
        td {
            padding-bottom: 8px;
            font-size: 15px;
        }
    </style>

    <div class="wrapper" style="max-width: 480px;display: block;background-color: #fff;margin-right: auto;margin-left: auto; margin-top: 20px; margin-bottom: 20px;">
        <div class="container" style="display: flex;flex-direction: column;align-items: center;padding: 30px;">
            <h2 style="padding-bottom: 26px; font-size: 21px; line-height: 25px;">Bukti Slip Transfer untuk <?php echo $program; ?> diterima pada tanggal <?php echo $order_date . ' - ' . $invoice_id; ?></h2>
    
            <div class="data;" style="margin-right: auto;">
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td class="title">CS</td>
                            <td class="colon">:</td>
                            <td class="value" style="background-color: yellow;"><?php echo $cs2; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Invoice ID</td>
                            <td class="colon">:</td>
                            <td class="value"><? echo $invoice_id; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Nama</td>
                            <td class="colon">:</td>
                            <td class="value"><? echo $name; ?></td>
                        </tr>
                        <tr>
                            <td class="title">WhatsApp</td>
                            <td class="colon">:</td>
                            <td class="value"><? echo $whatsapp; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Program</td>
                            <td class="colon">:</td>
                            <td class="value"><? echo $program; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Total</td>
                            <td class="colon">:</td>
                            <td class="value"><? echo $total; ?></td>
                        </tr>
                        <tr>
                            <td class="title">Tanggal Order</td>
                            <td class="colon">:</td>
                            <td class="value"><? echo $order_date; ?></td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
    
            <span style="text-align:center;display:block;margin-top:20px; margin-bottom:5px"><a href="https://ympb.or.id/" style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;color:#fff;text-decoration:none;line-height:2em;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background-color:#22cd3f;margin:0;border-color:#22cd3f;border-style:solid;border-width:10px 20px" target="_blank">Lihat Bukti Slip</a></span>

            <form method="POST">
                <button type="submit" name="email" value="email">
                    Kirim Email
                </button>
                
            </form>
    
        </div>

    </div>

    <?php
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['email']) ? $_POST['email'] : '' == "email") {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $subject = "Bukti Slip Transfer untuk " . $program . " diterima pada tanggal " . $order_date . ' - ' . $invoice_id;
        $body = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="x-apple-disable-message-reformatting">
        <title></title>
        <!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]-->
        <style>
        table, td, div, h1, p{
            font-family: Arial, sans-serif;
        }
        .title {
            width: 135px; font-weight: bold;
        }
        .colon {
            width: 10px;
        }
        .value {
            width: 250px;
        }
        td {
            padding-bottom: 8px;
            font-size: 15px;
        }
        @media screen and (max-width: 530px){
            .unsub{
                display: block;
                padding: 8px;
                margin-top: 14px;
                border-radius: 6px;
                background-color: #F1F7FB;
                text-decoration: none !important;
                font-weight: bold;
            }
            .col-lge{
                max-width: 100% !important;
            }
        }
        @media screen and (min-width: 531px){
            .col-sml{
                max-width: 27% !important;
            }
            .col-lge{
                max-width: 73% !important;
            }
        }
        </style>
        </head>
        <body style="margin:0;padding:0;word-spacing:normal;background-color:#E7ECF0;">
            <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#E7ECF0;">
                <table role="presentation" style="width:100%;border:none;border-spacing:0;">
                    <tr>
                        <td style="padding:30px;background-color:#E7ECF0;">
                        </td>
                    </tr>
                    <tr>
                        <td align=center style="padding:0;">
                            <!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]-->
                            <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:15px;line-height:22px;color:#363636;">
                                <tr>
                                    <td style="padding:30px;background-color:#ffffff;">
                                        <h1 style="margin-top:0;margin-bottom:25px;font-size:21px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">'.$subject.'
                                        </h1>
    
                                        <table style="width: 100%">
                                            <tbody>
                                                <tr>
                                                    <td class="title">CS</td>
                                                    <td class="colon">:</td>
                                                    <td class="value" style="background-color: yellow;">' . $cs2 .'</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">Invoice ID</td>
                                                    <td class="colon">:</td>
                                                    <td class="value">' . $invoice_id . '</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">Nama</td>
                                                    <td class="colon">:</td>
                                                    <td class="value">' . $name . '</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">WhatsApp</td>
                                                    <td class="colon">:</td>
                                                    <td class="value">' . $whatsapp . '</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">Program</td>
                                                    <td class="colon">:</td>
                                                    <td class="value">' . $program .'</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">Total</td>
                                                    <td class="colon">:</td>
                                                    <td class="value">'. $total .'</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">Tanggal Order</td>
                                                    <td class="colon">:</td>
                                                    <td class="value">'. $order_date .'</td>
                                                </tr>
                                                <tr>
                                                    <td class="title">Tanggal Slip</td>
                                                    <td class="colon">:</td>
                                                    <td class="value">'. $slip_date .'</td>
                                                </tr>
                                            </tbody>
                                        </table>
    
                                        <p style="text-align:center; margin-bottom:20px">
                                            <span style="text-align:center;display:block;margin-top:20px; margin-bottom:5px">
                                                <a href="https://ympb.or.id/s/?inv='.$invoice_id.'" style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;color:#fff;text-decoration:none;line-height:2em;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background-color:#22cd3f;margin:0;border-color:#22cd3f;border-style:solid;border-width:10px 20px" target="_blank">Lihat Slip TF</a>
                                            </span>*silahkan digunakan, laporkan jika ada error
                                        </p>
    
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:30px;background-color:#E7ECF0;"></td>
                                </tr>
                            </table>
                            <!--[if mso]> </td></tr></table><![endif]--> 
                        </td>
                    </tr>
                </table>
            </div>
        </body>
    </html>';
        $body_backup = '<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="utf-8"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title>
        <style>
        .title {
            width: 135px; font-weight: bold;
        }
        .colon {
            width: 10px;
        }
        .value {
            width: 250px;
        }
        td {
            padding-bottom: 8px;
            font-size: 15px;
        }
        </style>
    </head><body>
        
    <div class="wrapper" style="max-width: 480px;display: block;background-color: #fff;margin-right: auto;margin-left: auto; margin-top: 20px; margin-bottom: 20px;">
        <div class="container" style="display: flex;flex-direction: column;align-items: center;padding: 30px;">
            <h2 style="padding-bottom: 26px; font-size: 21px; line-height: 25px;">Bukti Slip Transfer untuk program diterima pada tanggal sekian sekian</h2>
    
            <div class="data;" style="margin-right: auto;">
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td class="title">CS</td>
                            <td class="colon">:</td>
                            <td class="value" style="background-color: yellow;">cs2</td>
                        </tr>
                        <tr>
                            <td class="title">Invoice ID</td>
                            <td class="colon">:</td>
                            <td class="value">invoice id</td>
                        </tr>
                        <tr>
                            <td class="title">Nama</td>
                            <td class="colon">:</td>
                            <td class="value">name</td>
                        </tr>
                        <tr>
                            <td class="title">WhatsApp</td>
                            <td class="colon">:</td>
                            <td class="value">whatsapp</td>
                        </tr>
                        <tr>
                            <td class="title">Program</td>
                            <td class="colon">:</td>
                            <td class="value">program</td>
                        </tr>
                        <tr>
                            <td class="title">Total</td>
                            <td class="colon">:</td>
                            <td class="value">total</td>
                        </tr>
                        <tr>
                            <td class="title">Tanggal Order</td>
                            <td class="colon">:</td>
                            <td class="value">order date</td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
    
            <span style="text-align:center;display:block;margin-top:20px; margin-bottom:5px"><a href="https://ympb.or.id/" style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;color:#fff;text-decoration:none;line-height:2em;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background-color:#22cd3f;margin:0;border-color:#22cd3f;border-style:solid;border-width:10px 20px" target="_blank">Lihat Bukti Slip</a></span>
    
        </div>
    
    </div>
    </body>
    </html>';

        $time = date("H - i - s");
        $mail_subject = 'tes email ' . $time;
        $attach_mail = array('https://ympb.or.id/wp-content/uploads/2023/01/inbound8015131282713624403-scaled.jpg');

        if (wp_mail( 'yusuf@ympb.or.id', $mail_subject, $body, array('Content-Type: text/html; charset=UTF-8'), array('https://ympb.or.id/wp-content/uploads/2023/01/inbound8015131282713624403-scaled.jpg'))) {
            echo '<script>
                console.log("berhasil terkirim");
            </script>';
        } else {
            echo '<script>
                console.log("gagal terkirim");
            </script>';
        }

    }
    ?>
    <?php $img =  'https://ympb.or.id/wp-content/uploads/2023/01/inbound320256964362080170.jpg';?>
    <img src= '<?php echo $img; ?>'></img>
    <script>console.log('<?php echo $img; ?>')</script>
<?php } ?>