<?php

function josh_cs() { ?>
    
    <style>
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
    
    <table class="table">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>WhatsApp</th>
            <th>Invoice</th>
            <th>Action Action</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Yusuf</td>
            <td>081910086792</td>
            <td>INV-120132</td>
            <td>
                <a class="link button button-primary" href="#">Edit</a>
                
                <a class="link button button-primary" href="#">Hapus</a>
            </td>
        </tr>
    </table>
    
    
  <?php  
}

?>

