<?php

function josh_cs() { ?>
    
    <style>
        form {
            display: flex;
        }
        .form1 {
            padding-bottom: 20px;
        }
        #wpcontent {
            background:white;
        }
        .table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid black;
        }
        
        th {
            border: 2px solid black;
            padding: 5px;
            text-align: center;
        }
        
        td {
            border: 2px solid black;
            padding: 5px 5px 5px 10px;
            text-align: center;
        }

        .joshclass {
        text-align: center;
        }
    </style>
    
    <div class="joshclass";><h2>Hei, this is Josh</h2>
    </div>

    <pre>
        <?php print_r( $_POST ); ?>
    </pre>

    <?php
        global $wpdb;

        $table_name = $wpdb->prefix.'josh_cs';
        $table_name2 = $wpdb->prefix.'dja_donate';
        $table_name3 = $wpdb->prefix.'josh_cs_meta';

        $get_nama = isset($_POST['nama']) ? $_POST['nama'] : '';
        $get_wa = isset($_POST['whatsapp']) ? $_POST['whatsapp'] : '';
        $get_relawan = isset($_POST['relawan']) ? $_POST['relawan'] : '';

        // echo '<pre>';
        // echo 'hei';
        // print_r ($get_nama);
        // echo '</pre>';

        // if($get_nama != "") {
        //     $wpdb->insert($table_name, array(
        //         'nama' => $get_nama,
        //         'whatsapp' => $get_wa
        //     ));
        // }
    ?>

    <h2>Search</h2>
    <form class = "form1" action="" method="POST">
        <div class="josh-whatsapp">
            <label for="whatsapp" class="formbuilder-number-label">No WA</label>
            <input type="number" class="form-control" name="whatsapp" access="false" id="whatsapp">
        </div>
        <div class="josh-submit">
            <button type="submit" name="search" value="search" class="btn-default btn" access="false" id="submit-button">Cari</button>
        </div>
    </form>

    <h2>donasi masuk</h2>
    <form class = "form2" action="" method="POST">
    <div class="josh-nama">
            <label for="nama" class="formbuilder-text-label">Nama<span class="formbuilder-required">*</span></label>
            <input type="text" placeholder="Andri" class="form-control" name="nama" access="false" id="nama" required="required" aria-required="true">
        </div>
        <div class="josh-whatsapp">
            <label for="whatsapp" class="formbuilder-number-label">No WA</label>
            <input type="number" class="form-control" name="whatsapp" access="false" id="whatsapp">
        </div>
        <div class="josh-submit">
            <button type="submit" name="donasi" value="donasi" class="btn-default btn" access="false" id="submit-button">donasi</button>
        </div>
    </form>

    <h2>Add</h2>
    <form class = "form3" action="" method="POST">
        <div class="josh-nama">
            <label for="nama" class="formbuilder-text-label">Nama<span class="formbuilder-required">*</span></label>
            <input type="text" placeholder="Andri" class="form-control" name="nama" access="false" id="nama" required="required" aria-required="true">
        </div>
        <div class="josh-whatsapp">
            <label for="whatsapp" class="formbuilder-number-label">No WA</label>
            <input type="number" class="form-control" name="whatsapp" access="false" id="whatsapp">
        </div>
        <div class="josh-relawan">
            <label for="relawan" >Relawan</label>
            <input type="text" name="relawan" access="false" id="relawan">
        </div>
        <div class="josh-submit">
            <button type="submit" name="add" value="add" class="btn-default btn" access="false" id="submit-button">Tambah</button>
        </div>
    </form>

    <h2>See All</h2>
    <form class = "form4" action="" method="POST">
        <div class="josh-submit">
            <button type="submit" name="all" value="all" class="btn-default btn" access="false" id="submit-button">Tambah</button>
        </div>
    </form>    

    <?php
        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['add']) ? $_POST['add'] : '' == "add") {
            
            if($get_nama != "") {
                $wpdb->insert($table_name, array(
                    'nama' => $get_nama,
                    'whatsapp' => $get_wa,
                    'relawan' => $get_relawan
                ));
                $sql_condition = " ";
            }
            
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['search']) ? $_POST['search'] : '' == "search") {
            
                if($get_wa != "") {
                    $sql_condition = " WHERE whatsapp='" . $get_wa . "'";
                    $select = '*';
                    $table_now = $table_name;
                }
            
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['donasi']) ? $_POST['donasi'] : '' == "donasi") {
            //$donasi_normalize = $wpdb->get_results("SELECT cs_id FROM $table_name2 WHERE whatsapp='$get_wa'");
            $sql_condition = " WHERE whatsapp='" . $get_wa . "'";
            $select = '*';
            $table_now = $table_name2;

        } elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['all']) ? $_POST['all'] : '' == "all") {
            $sql_condition = " ";
            $select = '*';
            $table_now = $table_name;
        }
    ?>

    <table class="table">
        <tr>
            <th>No</th>
            
            <th>Nama</th>
            <th>WhatsApp</th>
            <th>relawan</th>
            <th>Action Action 123</th>
        </tr>

        <?php
        // tampilkan semua data dari table
        //$sql = "SELECT nama,whatsapp,relawan FROM " . $table_name . $sql_condition;
        
        $sql = "SELECT " . $select .  "FROM " . $table_now . $sql_condition;
        $query = $wpdb->get_results($sql);
        $no = 1;

        

        foreach ( $query as $data ) :
        
        ?>

        <tr>
            <td><?php echo $no++; ?></td>
            
            <td><?php echo $data->name; ?></td>
            <td><?php echo $data->whatsapp; ?></td>
            <td><?php echo $data->relawan; ?></td>
            <td>
                <a class="link button button-primary" href="#">Edit</a>
                
                <a class="link button button-primary" href="#">Hapus</a>
            </td>
        </tr>
        <?php 
            
            endforeach;
             ?>
    </table>
    
    <?php 
        //UPDATE value!
    //    $runsql = "UPDATE $table_name3 SET `value` = '2' WHERE $table_name3.`id` = 1";
    //    $wpdb->get_results($runsql);
    ?>

    <pre>
         <?php //print_r ($query);
         //print_r (count($query)); ?>
    </pre>
    <script>
        //window.location.replace(https://ympb.or.id/wakaf-quran/);
    </script>
  <?php
        echo 'hello!';
        echo '<br>'; 
        echo '<pre>';var_dump(wp_upload_dir());echo '</pre>';
        $currentUploadDir = ABSPATH . 'wp-content/uploads' . wp_upload_dir()['subdir'];
        $uploadDir = ABSPATH . 'wp-content/uploads';
        // echo $currentUploadDir; echo '<br>';
        // echo $uploadDir; echo '<br>';

        $sampleImgUrl = 'https://ympb.or.id/wp-content/uploads/2023/02/sample-file.pp';
        echo $sampleImgUrl . '<br>';
        if( substr( $sampleImgUrl, -1) == '/') {
            echo 'last is /!' . '<br>';
        } else {
            $exploded = explode('/', $sampleImgUrl);
            echo '<pre>'; var_dump($exploded); echo '</pre>';
            $explodedLast = count($exploded) - 1;
            echo 'file name is: ' . $exploded[$explodedLast] . '<br>';
        }

        $image = $uploadDir . '/2022/josh/sampe.jpeg';
        $imageEditor = wp_get_image_editor( $image);
        echo '<pre>'; var_dump($imageEditor); echo '</pre>';

        // if( !is_wp_error( $image)) {
        //     // $imageEditor->rotate( 90 );
        //     $imageEditor->resize( null, '30');
        //     echo '<pre>';
        //     $fileName = $imageEditor->generate_filename( 'abismaller');
        //     echo $fileName . '<br>';
        //     echo $imageEditor->get_suffix() . '<br>';
        //     var_dump($imageEditor->save( $fileName));
        //     echo '</pre>';
        // }

    }
?>

