<?php
function josh_crm_2() {
    global $wpdb;
    $table_settings = $wpdb->prefix . 'dja_settings';
    global $wp_roles;

    /**
     * User
     */
    $user = wp_get_current_user();
    $cs_conversion = array('husna' => 'Husna', 'tisna' => 'Tisna', 'meisya' => 'Meisya', 'safina' => 'Safina');
    if($user->roles[0] === 'cs') {
        $cs_id = $user->ID;
        $cs_text = $cs_conversion[$user->data->user_login];
        // $cs_text = $cs_conversion['meisya'];
        // echo '<pre>'.$cs_text.'</pre>';
        // echo '<pre>'; var_dump($user->data); echo '<pre>';
        // echo '<pre>'; var_dump($user->data->user_login); echo '<pre>';
        // echo '<pre>'; var_dump($cs_text); echo '<pre>';
    }

    /**
     * Month Year List
     */
    $end_date = new DateTime('2021-08-01');
    $start_date = new DateTime('now');
    // $start_date = new DateTime('2021-08-01');
    // $end_date = new DateTime('now');

    $list_month = array();
    $i = 1;
    while( $start_date >= $end_date ) {
        $pri = ( $start_date->format('M y') === date('M y') ) ? true : false;
        $list_month[] = array(
            'id'    => $i,
            'text'  => $start_date->format('M y'),
            'data'  => $start_date->format('Y-m'),
            'primary' => $pri
        );
        $start_date->modify('-1 month');
        $i++;
    }

    /**
     * CS list
     */
    $query = "SELECT data
    FROM $table_settings
    WHERE type='cs_'";
    $list_cs = $wpdb->get_row( $query )->data;

?>

<!-- bootstrap 5.3 -->
<link rel="stylesheet" href="<?php echo DJA_PLUGIN_URL . 'assets/vendor/twbs/bootstrap/dist/css/bootstrap.css' ?>">
<script src="<?php echo DJA_PLUGIN_URL . 'assets/vendor/twbs/bootstrap/dist/js/bootstrap.js' ?>"></script>

<!-- jQuery -->
<!-- <script src="<?php //echo plugin_dir_url( __FILE__ ); ?>admin/assets/js/jquery.min.js"></script>
<script src="<?php //echo plugin_dir_url( __FILE__ ); ?>admin/assets/js/jquery-ui.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>

<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'jquery-ui.css'; ?>">

<!-- Bootboxjs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js" integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'selectivity-jquery.css'; ?>">
<script src="<?php echo plugin_dir_url( __FILE__ ); ?>selectivity-jquery.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<!-- chart.js 4.2.1 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>

<!-- card select -->
<script defer src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/asset-j/card-select/card-select.js' ?>"></script>
<link href="<?php echo plugin_dir_url( __FILE__ ) . 'assets/asset-j/card-select/card-select.css' ?>" rel="stylesheet">

<!-- custom js -->
<script defer src="<?php echo DJA_PLUGIN_URL . 'assets/asset-j/crm-rsheet/jh-crm-rsheet-bootstrap.js?v1.3.4' ?>"></script>
<script defer src="<?php echo plugin_dir_url( __FILE__ ) . 'josh-crm.js?v=1.3.4' ?>"></script>

<!-- put variable -->
<script>
    optionMonth = JSON.parse('<?php echo json_encode( $list_month ); ?>');
    optionCS = JSON.parse('<?php echo $list_cs; ?>');
    <?php if($user->roles[0] === 'cs') {?>
    const cs_now = [{<?php echo "id: $cs_id, text: '$cs_text'"; ?>}];
    // const cs_now = [{id: 9, text: 'Tisna'}];
    <?php } else {echo 'const cs_now = undefined;';} ?>
</script>

<!-- custom css -->
<!-- <link id="josh-crm" rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ).'josh-crm.css'; ?>"> -->
<style>
    body {
        background-color: #f0f0f1;
    }
    .offcanvas.offcanvas-end {
        width: 1100px;
    }
    .dataTables_info {
        text-align: left !important;
    }
    input[type=checkbox], input[type=radio] {
        margin: 0 !important;
    }
    #scroll-top {
        position: fixed;
        right: 15px;
        bottom: 15px !important;
        z-index: 99;
    }
    @media screen and (max-width: 576px) {
        #graph {
            min-height: auto !important;
        }
        #scroll-top {
            right: 18px !important;
            bottom: 30px !important;
        }
    }
</style>

<div id="filter-bulan" title="Filter Periode Bulan" style="display: none;">
    <div class="selector-wrapper">
        <div class="card-select month"></div>
    </div>
</div>

<div class="josh-wrapper">
    <div class="row mt-2">
        <div class="d-flex flex-wrap justify-content-between title gap-3 gap-sm-0">
            <div class="col-12 col-sm text-center text-sm-start">
                <h5>Customer Relationship Management (CRM) - DFR YMPB</h5>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <div class="filter-cs-2 border px-2 rounded-2 d-flex align-items-center bg-white <?php if($user->roles[0] === 'cs') {echo 'd-none';} ?>" id="filter-cs"></div>
    
                <div class="date-picker" id="date-picker">
                    <div class="icon"></div>
    
                    <div class="btn btn-secondary btn-sm" id="date-picker-text">This Month</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row cards text-center py-3 gap-3">
        <div class="col-12">
            <div class="row card-1 gap-3 mx-2">
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-primary border-3">
                        <div class="card-title text-uppercase">Belum Transfer</div>
        
                        <div class="value fw-bolder" id="belum-tf-text">...</div>
                    </div>
                </div>
    
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-primary-subtle border-3">
                        <div class="card-title text-uppercase">Nomor Dikelola</div>
        
                        <div class="value fw-bolder" id="nomor-dikelola-text">...</div>
                    </div>
                </div>
                
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-success border-3">
                        <div class="card-title text-uppercase">Donatur Aktif</div>
        
                        <div class="value fw-bolder" id="donatur-aktif-text">...</div>
                    </div>
                </div>
    
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-success-subtle border-3">
                        <div class="card-title text-uppercase">Closing Rate</div>
        
                        <div class="value fw-bolder fs-6 text-success-subtle" id="closing-rate-text">...</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="row card-2 gap-3 mx-2">
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-primary border-3">
                        <div class="title">Donasi Ulang per Dn</div>
        
                        <div class="value" id="donasi-organik-text">Rp...</div>
                    </div>
                </div>
    
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-primary-subtle border-3">
                        <div class="title">Donasi Iklan per Dn</div>
        
                        <div class="value" id="donasi-iklan-text">Rp...</div>
                    </div>
                    
                </div>
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-success border-3">
                        <div class="title">Leads</div>
        
                        <div class="value" id="leads-text">...</div>
                    </div>
                    
                </div>
                <div class="col-12 col-sm px-0 card-summary">
                    <div class="card my-0 py-3 shadow border-top-0 border-bottom-0 border-end-0 border-success-subtle border-3">
                        <div class="title">Nilai Omset</div>
        
                        <div class="value" id="nilai-omset-text">Rp...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0 mx-0 mt-3 data-table-1" style="font-size: 14px;">
        <div class="row head-control py-2 gap-2 gap-sm-0 me-sm-2">
            <div class="col d-flex justify-content-sm-end justify-content-between gap-3">
                <div class="d-flex justify-content-sm-end justify-content-start gap-2">
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1 px-sm-2 px-3" id="btn-refresh-table">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"></path>
                            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"></path>
                        </svg>
                        <span class="d-sm-block d-none">Refresh</span>
                    </button>
    
                    <button type="button" class="btn btn-primary btn-sm position-relative" id="custom-filter">
                        Filter
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="filter-badges">
                            1
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                </div>

                <div class="input-group input-group-sm" style="max-width: 210px;">
                    <input type="text" class="form-control" id="search-text" placeholder="Nama atau Whatsapp" aria-label="Nama atau Whatsapp" aria-describedby="button-addon2">
                    <button class="btn btn-outline-primary" type="button" id="btn-search">
                        Search
                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle d-none" id="quick-search-badges">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div class="table-wrapper bg-white p-2 pt-4 mt-2 shadow rounded">
            <div class="jh-title text-center h6">Daftar Donatur (Lifetime) dan Transaksi (Bulan Ini)</div>

            <div>
                <table id="table-donors" class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Tags</th>
                            <th class="py-3">No. WA</th>
                            <th class="py-3">Pemilik</th>
                            <th class="py-3" title="Total Order">T&nbsp;Order</th>
                            <th class="py-3" title="Nilai Order">N&nbsp;Order</th>
                            <th class="py-3" title="Total Slip">T&nbsp;Slip</th>
                            <th class="py-3" title="Nilai Slip">N&nbsp;Slip</th>
                            <th class="py-3">Dibuat</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- offcanvas Right -->
    <div class="offcanvas offcanvas-end placeholder-glow" tabindex="-1" id="offcanvasRight">
        <div class="offcanvas-header mt-0 mt-sm-4 border-bottom p-2 p-sm-3">
            <!-- <button type="button" class="btn-close" aria-label="Close"></button> -->
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>

            <div class="owner-contact jh-loading text-center">
                <div class="jh-name placeholder" id="owner-name"></div>

                <div class="jh-email placeholder" id="owner-email"></div>
            </div>

            <div class="notes d-flex gap-2 align-items-center" style="cursor: pointer;">
                <div class="jh-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sticky" viewBox="0 0 16 16">
                        <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zM2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V8H9.5A1.5 1.5 0 0 0 8 9.5V14H2.5a.5.5 0 0 1-.5-.5v-11zm7 11.293V9.5a.5.5 0 0 1 .5-.5h4.293L9 13.793z"/>
                    </svg>
                </div>

                <div class="jh-text">
                    Notes
                </div>
            </div>
        </div>

        <div class="offcanvas-body bg-light">
            <div class="row justify-content-between flex-wrap-reverse flex-sm-nowrap">
                <div class="col col-sm-9 donor-var-identity mt-2 mt-sm-0">
                    <div class="summary-metric bg-white py-3">
                        <div class="jh-row title text-center" style="display: none;">Summary</div>
        
                        <div class="jh-row data-chart">
                            <div class="jh-col-1 chart">
                                <canvas id="graph" style="min-height: 270px"></canvas>
                            </div>
        
                            <!-- <div class="jh-col-2 data-selector" style="display: none">selector</div> -->
                        </div>
                    </div>
        
                    <div class="history mt-3">
                        <div class="d-flex justify-content-between px-2">
                            <div class="btn btn-secondary btn-sm">History</div>
                            
                            <div class="d-flex gap-3">
                                <div class="btn btn-secondary btn-sm">Cutom Filter</div>
        
                                <div class="date-picker btn btn-secondary btn-sm d-flex gap-2" id="date-picker-2">
                                    <div class="jh-icon">icon</div>
        
                                    <div class="" id="date-picker-2-text">This&nbsp;Month</div>
                                </div>
                            </div>
                        </div>
        
                        <div class="jh-body jh-loading jh-loading-medium bg-white p-3" style="font-size: 14px; margin-top: 5px;">
                            <div class="table-wrapper" style="display: none;">
                                <table id="table-one-donor" class="table" style="width: 100% !important;">
                                    <thead>
                                        <tr>
                                            <th>Tgl</th>
                                            <th>Berita</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="col donor-fix-identity mx-2 mx-sm-0" style="font-size: 13px; min-width: 190px;">
                    <div class="position-sticky top-0 bg-white p-3">
                        <div class="row avatar jh-loading">
                            <div class="jh-col-1">
                                <img src="" alt="">
                            </div>
        
                            <div class="jh-col-2">
                                <div class="jh-name fs-6 placeholder" id="jh-donor-name"></div>
        
                                <div class="jh-phone fs-6 placeholder" id="jh-donor-phone"></div>
                            </div>
                        </div>
        
                        <div class="row all-data mx-0 w-100">
                            <div class="row-cols-1 category mt-2">
                                <div class="title fw-semibold" >Kategori Dn</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-category"></div>
                            </div>
        
                            <div class="row program mt-2">
                                <div class="title fw-semibold">Program</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-program"></div>
                            </div>
        
                            <div class="row email mt-2">
                                <div class="title fw-semibold">Email</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-email"></div>
                            </div>
        
                            <div class="row user mt-2">
                                <div class="title fw-semibold">User YMPB</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-user"></div>
                            </div>
        
                            <div class="row payment mt-2">
                                <div class="title fw-semibold">Payment Method</div>
        
                                <div class="value fs-6 mt-2 placeholder" id="jh-donor-payment"></div>
                            </div>
                            
                            <div class="row ltv mt-2">
                                <div class="title fw-semibold">Lifetime Value (LTV)</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-ltv"></div>
                            </div>
        
                            <div class="row adv mt-2">
                                <div class="title fw-semibold">Average Donate Value (ADV)</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-adv"></div>
                            </div>
        
                            <div class="row dvol mt-2">
                                <div class="title fw-semibold">Donate Volume</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-dvol"></div>
                            </div>
        
                            <div class="row dibuat mt-2">
                                <div class="title fw-semibold">Dibuat</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-dibuat"><span id="jh-donor-dibuat-"></span></div>
                            </div>
        
                            <div class="row kota mt-2">
                                <div class="title fw-semibold">Kota</div>
        
                                <div class="value fs-6 placeholder" id="jh-donor-kota"></div>
                            </div>
        
                            <!-- continue with foreach, possible to rearrange in the future -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal Filter -->
    <div class="" id="filter-modal" style="display: none; font-size: 15px; text-align: start;">
        <div class="mb-3">
            <div class="mb-2 ms-1">
                <input type="radio" name="filter-main-table" id="rad-not-donate" value="not-donate">
                <label class="form-check-label" for="rad-not-donate">
                    Belum donasi bulan ini
                </label>
            </div>

            <div class="mb-2 ms-1">
                <input type="radio" name="filter-main-table" id="rad-removed" value="removed">
                <label class="form-check-label" for="rad-removed">
                    Donatur dihapus (no leads, diblokir, dll)
                </label>
            </div>
        </div>

        <div>
            <div class="text-primary fw-bold fw-5 ps-2" id="radio-filter-clear" style="cursor: pointer;">Clear</div>
        </div>
    </div>

    <div class="btn bg-secondary bg-opacity-75 text-white" id="scroll-top" style="display: none;">
        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-chevron-double-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M7.646 2.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 3.707 2.354 9.354a.5.5 0 1 1-.708-.708l6-6z"/>
            <path fill-rule="evenodd" d="M7.646 6.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 7.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/>
        </svg> -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" width="22" height="19" viewBox="0 0 32 32" version="1.1">
            <title>up</title>
            <path d="M11.25 15.688l-7.656 7.656-3.594-3.688 11.063-11.094 11.344 11.344-3.5 3.5z"/>
        </svg>
    </div>
</div>

<?php
} // end of function josh_crm()
?>