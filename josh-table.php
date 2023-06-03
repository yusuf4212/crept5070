<?php
function josh_table() {
    global $wpdb;
    
    $table_settings_ = $wpdb->prefix . 'dja_settings';
    
    $query = "SELECT data
    FROM $table_settings_
    WHERE type='program_' or type='platform_' or type='cs_' or type='type_' or type='bank_'";
    $rows = $wpdb->get_results($query);
    
    $program    = $rows[0]->data;
    $platform   = $rows[1]->data;
    $cs         = $rows[2]->data;
    $bank       = $rows[3]->data;
    $type       = $rows[4]->data;
    ?>
    
    <!-- bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
    <!-- jQuery -->
    <!-- <script src="<?php //echo plugin_dir_url( __FILE__ ); ?>admin/assets/js/jquery.min.js"></script>
    <script src="<?php //echo plugin_dir_url( __FILE__ ); ?>admin/assets/js/jquery-ui.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'jquery-ui.css'; ?>">

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootboxjs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js" integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- DataTables -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"/> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.2/af-2.5.2/b-2.3.4/b-colvis-2.3.4/b-html5-2.3.4/b-print-2.3.4/cr-1.6.1/date-1.3.0/fc-4.2.1/fh-3.3.1/kt-2.8.1/r-2.4.0/sc-2.1.0/sl-1.6.0/sr-1.2.1/datatables.min.css"/>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.2/af-2.5.2/b-2.3.4/b-colvis-2.3.4/b-html5-2.3.4/b-print-2.3.4/cr-1.6.1/date-1.3.0/fc-4.2.1/fh-3.3.1/kt-2.8.1/r-2.4.0/sc-2.1.0/sl-1.6.0/sr-1.2.1/datatables.min.js"></script>

    <!-- selectivity -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'selectivity-jquery.css?v=1.0.1'; ?>">
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>selectivity-jquery.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- select3 -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'select3.css'; ?>">
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>select3-full.js"></script>

    <div class="model"  style="display: none;">
        <div class="model-header">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z" fill="#0F1729"/>
                </svg>
            </div>
        </div>

        <div class="model-wrapper">
            <div class="left"></div>
    
            <div class="body">
                <div class="model-body">
                    <div id="heart-box" class="loading-icon">
                        <svg id="heart" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 32 32" version="1.1">
                            <path d="M16.042 8.345c0 0-2-4.262-6.5-4.262-4.917 0-7.5 4.167-7.5 8.333 0 6.917 14 15.5 14 15.5s13.916-8.5 13.916-15.5c0-4.25-2.666-8.333-7.416-8.333s-6.5 4.262-6.5 4.262z"/>
                        </svg>
                    </div>
                </div>
            </div>
    
            <div class="right">    
            </div>
        </div>
    </div>

    <div class="container-fluid wrapper mt-2">
        <div class="row mt-2">
            <div class="d-flex justify-content-between gap-2">
                <div class="">
                    <input class="form-control form-control-sm" id="search-table" type="text" placeholder="Search Here" aria-label=".form-control-sm example">
                </div>
    
                <div class="date-picker-box" id="date-picker">
                    <div class="date-picker btn btn-outline-secondary d-flex align-items-center gap-2">
                        <div class="icon-date" style="width: 20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 1920 1920">
                                <path d="M1694.176 451.765H225.942V282.353c0-31.172 25.411-56.471 56.47-56.471h169.411v56.471c0 31.172 25.3 56.471 56.471 56.471 31.172 0 56.471-25.299 56.471-56.471v-56.471h790.589v56.471c0 31.172 25.3 56.471 56.47 56.471 31.171 0 56.47-25.299 56.47-56.471v-56.471h169.412c31.059 0 56.47 25.299 56.47 56.471v169.412Zm-338.822 790.586H1016.53v338.826H903.589v-338.826H564.765v-112.939h338.824V790.588h112.941v338.824h338.824v112.939Zm282.352-1129.41h-169.412v-56.47c0-31.172-25.299-56.471-56.47-56.471-31.17 0-56.47 25.299-56.47 56.471v56.47H564.765v-56.47C564.765 25.299 539.466 0 508.294 0c-31.171 0-56.471 25.299-56.471 56.471v56.47H282.412c-93.403 0-169.412 76.01-169.412 169.412V1920h1694.118V282.353c0-93.402-76.008-169.412-169.412-169.412Z" fill-rule="evenodd"/>
                            </svg>
                        </div>
    
                        <div class="date-text text-nowrap" id="date-text">This Month</div>
    
                        <div class="icon-drop" style="width: 20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="blue" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 12l-4-4h8l-4 4z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="d-flex justify-content-between">
                <div>
                    <button onclick="window.open('<?= home_url('slip-input'); ?>', '_blank');" type="button" class="btn btn-outline-primary btn-sm josh1" id="btn-input">
                        Input slip
                    </button>
                </div>
    
                <div class="btn btn-outline-primary btn-sm josh1" id="btn-filter">
                    <div class="icon-filter d-flex justify-content-between gap-2">
                        <div class="jh-icon" style="width: 20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="icon-filter">
                                <path d="M6 12H18M3 6H21M9 18H15" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <span>Filter</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 mx-0 summary-data gap-3">
            <div class="col-sm-4 col-12 card donation-value shadow-sm p-0">
                <div class="card-body">
                    <div class="card-title">Nilai Donasi</div>
                    <div class="value fw-bold" id="donation-value">Rp ...</div>
                </div>
            </div>
            
            <div class="col-sm-4 col-12 card donors-total shadow-sm p-0">
                <div class="card-body">
                    <div class="card-title">Total Slip</div>
                    <div class="value fw-bold" id="donors-total">...</div>
                </div>
            </div>
        </div>

        <div class="row table mt-4 mx-0 shadow rounded" style="font-size: 14px;">
            <table id="datatables" class="table table-hover align-middle">
                <thead class="table-light align-middle text-center">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">Relawan</th>
                        <th class="py-3">Given&nbsp;date</th>
                        <th class="py-3">Program</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Platform</th>
                        <th class="py-3">Bukti&nbsp;TF</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Bank</th>
                        <th class="py-3">Transfer&nbsp;date</th>
                        <th class="py-3">WhatsApp</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="float drop-down relawan" id="float-relawan" style="display: none;">
            <div class="wrapper">
                <div class="float-row-title">SELECT AN OPTION</div>
                <div class="row-list">
                    <?php
                    $cs2 = json_decode( $cs );
                    foreach( $cs2 as $v ) {
                        echo '
                        <div id="opt-1" class="option"><div>'.$v->text.'</div></div>
                        ';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="float drop-down program" id="float-program" style="display: none;">
            <div class="wrapper">
                <div class="float-row-title">SELECT AN OPTION</div>
                <div class="row-list">
                    <?php
                    $program2 = json_decode( $program );
                    foreach( $program2 as $v ) {
                        echo '
                        <div id="opt-1" class="option"><div>'.$v->text.'</div></div>
                        ';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="float drop-down type" id="float-type" style="display: none;">
            <div class="wrapper">
                <div class="float-row-title">SELECT AN OPTION</div>
                <div class="row-list">
                    <?php
                    $type2 = json_decode( $type );
                    foreach( $type2 as $v ) {
                        echo '
                        <div id="opt-1" class="option"><div>'.$v->text.'</div></div>
                        ';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="float drop-down platform" id="float-platform" style="display: none;">
            <div class="wrapper">
                <div class="float-row-title">SELECT AN OPTION</div>
                <div class="row-list">
                    <?php
                    $platform2 = json_decode( $platform );
                    foreach( $platform2 as $v ) {
                        echo '
                        <div id="opt-1" class="option"><div>'.$v->text.'</div></div>
                        ';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="float drop-down bank" id="float-bank" style="display: none;">
            <div class="wrapper">
                <div class="float-row-title">SELECT AN OPTION</div>
                <div class="row-list">
                    <?php
                    foreach( json_decode($bank) as $v ) {
                        echo '
                        <div id="opt-1" class="option"><div>'.$v->text.'</div></div>
                        ';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="float input amount" id="float-amount" style="display: none;">
            <div class="wrapper">
                <div class="float-row-title">INSERT AMOUNT</div>
                <div class="row-list">
                    <div id="opt-1" class="option">
                        <input type="text" placeholder="insert here" id="float-amount-input">
                    </div>
                    <div class="button-box">
                        <div class="drp-buttons">
                            <button class="cancelBtn btn btn-sm btn-default" type="button" id="amount-cancel">Cancel</button>
                            <button class="applyBtn btn btn-sm btn-primary" type="button" id="amount-apply">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="float input phone" id="float-phone" style="display: none;">
            <div class="wrapper">
                <div class="float-row-title">INSERT PHONE</div>
                <div class="row-list">
                    <div id="opt-1" class="option">
                        <input type="text" placeholder="insert here" id="float-phone-input">
                    </div>
                    <div class="button-box">
                        <div class="drp-buttons">
                            <button class="cancelBtn btn btn-sm btn-default" type="button" id="phone-cancel">Cancel</button>
                            <button class="applyBtn btn btn-sm btn-primary" type="button" id="phone-apply">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100" id="filter-dialog" title="Filter" style="display: none; width: 100%;">
            <div class="container-fluid p-0 m-0">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="select-relawan">relawan</div>
                        <span id="select-relawan" class="multi-input multiple-relawan form-control form-control-sm"></span>
                    </div>

                    <div class="col-sm-6 col-12">
                        <div class="select-program">program</div>
                        <span id="select-program" class="multi-input multiple-program form-control form-control-sm"></span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="select-type">type</div>
                        <span id="select-type" class="multi-input multiple-type form-control form-control-sm"></span>
                    </div>

                    <div class="col-sm-6 col-12">
                        <div class="select-platform">platform</div>
                        <span id="select-platform" class="multi-input multiple-platform form-control form-control-sm"></span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="select-bank">bank</div>
                        <span id="select-bank" class="multi-input multiple-bank form-control form-control-sm"></span>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="select-transfer-date">transfer date</div>
                            <div class="date-picker-box date-filter form-control" id="date-picker-tf" style="cursor: pointer;">
                                <div class="date-picker d-flex gap-2">
                                    <div class="icon-date" style="width: 20px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 1920 1920">
                                            <path d="M1694.176 451.765H225.942V282.353c0-31.172 25.411-56.471 56.47-56.471h169.411v56.471c0 31.172 25.3 56.471 56.471 56.471 31.172 0 56.471-25.299 56.471-56.471v-56.471h790.589v56.471c0 31.172 25.3 56.471 56.47 56.471 31.171 0 56.47-25.299 56.47-56.471v-56.471h169.412c31.059 0 56.47 25.299 56.47 56.471v169.412Zm-338.822 790.586H1016.53v338.826H903.589v-338.826H564.765v-112.939h338.824V790.588h112.941v338.824h338.824v112.939Zm282.352-1129.41h-169.412v-56.47c0-31.172-25.299-56.471-56.47-56.471-31.17 0-56.47 25.299-56.47 56.471v56.47H564.765v-56.47C564.765 25.299 539.466 0 508.294 0c-31.171 0-56.471 25.299-56.471 56.471v56.47H282.412c-93.403 0-169.412 76.01-169.412 169.412V1920h1694.118V282.353c0-93.402-76.008-169.412-169.412-169.412Z" fill-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    
                                    <div class="date-text" id="date-picker-tf-text">Select Here</div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        var jsonReady = {
            program: <?= $program; ?>,
            platform: <?= $platform; ?>,
            cs: <?= $cs; ?>,
            type: <?= $type; ?>,
            bank: <?= $bank; ?>
        };
    </script>

    <script src="<?= plugin_dir_url( __FILE__ ).'josh-table.js?v=1.0.4'; ?>"></script>
<?php
}
?>