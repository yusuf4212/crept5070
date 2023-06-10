/**
 * Dropdown date end filter
 */
let a = document.querySelectorAll('.dropdown .dropdown-item');
let b = document.querySelector('#btn-month-select');

for(let i=0; i < a.length; i++) {
    $(a[i]).click(function (e) { 
        e.preventDefault();

        $(b).html($(a[i]).html());

        for(let j=0; j < a.length; j++) {
            $(a[j]).removeClass('bg-primary text-light');
        }
        $(e.delegateTarget).addClass('bg-primary text-light');

        let currentUrl = window.location.href;
        let url = new URL(currentUrl);

        url.searchParams.set('date', e.delegateTarget.attributes[2].value);
        url.searchParams.set('date_', e.delegateTarget.textContent);
        let modifiedUrl = url.toString();
        window.history.pushState(null, null, modifiedUrl);

        monthDate = e.delegateTarget.attributes[2].value;

        mainTable.ajax.reload();
    });
}

/**
 * Quick search by name and phone
 */
$('#btn-search').click(function (e) { 
    e.preventDefault();

    mainTable.ajax.reload();
});

/**
 * Quick search by name and phone. Enter listener
 */
$('#search-text').keydown(function (e) { 
    if(e.keyCode === 13) {
        mainTable.ajax.reload();
    }
});

/**
 * Function to sanitize
 */
function removeSpecialCharacters(str) {
    var cleanStr = str.replace(/[^a-zA-Z0-9 ]/g, '');
    return cleanStr;
}

/**
 * Function to get search text
 */
function getSearchText() {
    let searchText = $('#search-text').val();

    let searchTextS = removeSpecialCharacters(searchText);

    if(searchTextS === '') {
        searchTextS = null;
    }
    return searchTextS;
}

/**
 * Select Initiator
 */
// let selectElement = document.querySelector('.card-select');
// let cardMonth = new CardSelectMonth({
//     'target': selectElement,
//     option: optionMonth
// })

/**
 * Filter CS
 */
$('#filter-cs').selectivity({
    items: optionCS,
    inputType: 'Multiple',
    allowClear: true,
    placeholder: 'Filter CS',
    tokenSeparators: [' ']
});

$('#filter-cs').change(function (e) { 
    e.preventDefault();
    
    mainTable.ajax.reload();
});

/**
 * Refresh button, mainTable
 */
$('#btn-refresh-table').click(function (e) { 
    e.preventDefault();
    
    mainTable.ajax.reload();
});

/**
 * Filter
 */
// const modalFilter = new bootstrap.Modal('#modalFilter');

// $('.custom-filter').click(function (e) { 
//     e.preventDefault();
//     modalFilter.show();
// });

const modalFilter = document.querySelector('#filter-modal');
$('#filter-modal').remove();
$(modalFilter).css('display', 'block');

let filterData = null;

$('#custom-filter').click(function (e) { 
    e.preventDefault();
    Swal.fire({
        title: 'Filter',
        html: modalFilter
    }).then((e) => {
        $('#radio-filter-clear').off('click');

        if(e.isConfirmed) {
            let radio = $('input[name="filter-main-table"]:checked').val();
            filterData = (radio == undefined) ? null : radio;

            if(filterData == null) {
                $('#filter-badges').addClass('d-none');
            } else {
                $('#filter-badges').removeClass('d-none');
            }
            mainTable.ajax.reload();
        }
    });
    
    $('#radio-filter-clear').click(function (e) { 
        e.preventDefault();
        $('input[name="filter-main-table"]').prop('checked', false);
    });
});



var dateStart = ''; var dateEnd = '';
// $( "#filter-bulan" ).dialog({
//     autoOpen: false,
//     modal: true,
//     draggable: false,
//     resizable: false,
//     width: 600,
//     maxWidth: 600,
//     closeText: 'hide',
//     show: {
//         effect: "fade", duration: 100
//     },
//     hide: {
//         effect: "fade", duration: 100
//     },
//     buttons: [
//         {
//             text: 'Ok',
//             click: () => {
//                 $('#filter-bulan').dialog('close');
//                 $('#filter-bulan').css('display', 'none');
//                 let cardMonthR = cardMonth.getResult();
//                 dateStart = cardMonthR.start;
//                 dateEnd = cardMonthR.end;
//                 console.log(cardMonthR);

//                 mainTable.ajax.reload();
//             }
//         }
//     ]
// });

// $('#date-picker').click(function (e) { 
//     e.preventDefault();
    
//     $('#filter-bulan').dialog('open');
// });

/**
 * Loading for the first time
 */
// Swal.fire({
//     title: 'Harap Tunggu...',
//     allowOutsideClick: false,
//     allowEscapeKey: false,
//     allowEnterKey: false,
//     didOpen: () => {
//         Swal.showLoading();
//     }
// });

/**
 * Main Table and Cards
 */
let mainTable = $('#table-donors').DataTable({
    "dom": "<'d-flex justify-content-start'l>"+
    "<'d-flex justify-content-start text-start'i>"+
    "<'table-responsive my-2't>"+
    "rp",
    "serverSide": true,
    "processing": false,
    "pagingType": "numbers",
    "columnDefs": [
        {
            "targets": 0,
            "className": "text-center"
        },
        {
            "targets": 6,
            "searchable": false,
            // "orderable": false
        }
    ],
    "select": {
        "selector": 'td:first-child',
        "blurable": true
    },
    "ordering": false,
    "ajax" : {
        "url"   : "admin-ajax.php",
        "type"  : "POST",
        "dataSrc": "data",
        "data"  : {
            action  : 'josh_crm_table_1',
            // date_start : () => { return dateStart },
            date_end : () => { return monthDate },
            filter : () => {
                let filter_cs = (cs_now == undefined) ? $('#filter-cs').selectivity('data') : cs_now;

                let quickSearch = getSearchText();


                // if(cs_now == undefined) {
                //     filter_cs = $('#filter-cs').selectivity('data');
                // } else {}
                let filter = {
                    cs: filter_cs,
                    quickSearch: quickSearch,
                    filterData : filterData
                };

                return JSON.stringify(filter);
            },
            undonate : () => {return $('input[name="undonate"]:checked').val()}
        }
    },
    "columns"   : [
        { "data" : "chk" },
        { "data" : "nama" },
        { "data" : "tags" },
        { "data" : "noWa" },
        { "data" : "pemilik" },
        { "data" : "totalOrder" },
        { "data" : "nilaiOrder" },
        { "data" : "totalSlip" },
        { "data" : "nilaiSlip" },
        { "data" : "dibuat" }
    ],
    "lengthMenu": [
        [ 25, 50, 100, -1 ],
        [ '25', '50', '100', 'All' ]
    ],
    "preDrawCallback": (settings) => {
        Swal.fire({
            title: 'Harap Tunggu...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        }); 
    },
    "createdRow": function( row, data, dataIndex, cells ) {

        // add jh-data attribute at row
        $(row).attr('jh-data', data.id);

        /**
         * Name Column
         */
        {
            let action1, badges;
            if(data.tableMode == null || data.tableMode === 'not-donate') {
                action1 = {
                    text: 'Hapus'
                };
                badges = '';
            } else if(data.tableMode === 'removed') {
                action1 = {
                    text: 'Restore'
                };
                badges = '<span class="badge rounded-pill text-bg-danger">'+data.removeReason+'</span>';
            }

            //
            $('td:eq(1)', row).html('<div class="jh-name-table" style="cursor: pointer;"><span class="me-1">'+data.nama+'</span>'+badges+'</div><div><span class="main-table-row-set remove" style="display: none; cursor: pointer;">'+action1.text+'</span>&nbsp;</div>');
    
            // open right sheet on target
            $('.jh-name-table', row).click( (e) => {
                e.preventDefault();
    
                RightSheet.open($(e.delegateTarget.parentElement.parentElement).attr('jh-data'));
            });
    
            // hover effect
            $(row).hover(function () {
                    // over
                    $('.main-table-row-set', row).show();
                }, function () {
                    // out
                    $('.main-table-row-set', row).hide();
                }
            );

            // action 1 click event
            $('.main-table-row-set', row).click(function (e) { 
                e.preventDefault();
                
                if(data.tableMode == null || data.tableMode === 'not-donate') {
                    let data = {action: 'josh_crm_table_act', crm_action: 'remove', donor_id: e.delegateTarget.parentNode.parentElement.parentElement.attributes[0].value};
                    
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Pilih alasan menghapus.',
                        input: 'select',
                        inputOptions: {
                            blokir: 'Di blokir',
                            menolak: 'Menolak dihubungi',
                            no_leads: 'Tidak ada WA (no leads)'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Ya Hapus',
                        showLoaderOnConfirm: true,
                        preConfirm: (reason) => {
                            
                            data.reason = reason;
                            return $.post('admin-ajax.php', data, (data, textStatus, jqXHR) => {
                                if( data.status === 'success' ) {
                                    // console.log('sucess!')
                                    // return 'josh_success'
                                } else {
                                    Swal.showValidationMessage(`Delete failed: ${data.message}`)
                                    // return 'not success'
                                }
                            })
                        }
                    })
                    .then((result) => {
                        console.log(result)
                        if( result.value.status === 'success' ) {
                            mainTable.ajax.reload( null, false );
    
                            Swal.fire({
                                title: 'Success',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true
                            })
                        }
                    });
                } // end of if statement
                else if(data.tableMode) {
                    let data = {action: 'josh_crm_table_act', crm_action: 'restore', donor_id: e.delegateTarget.parentNode.parentElement.parentElement.attributes[0].value};
                    
                    Swal.fire({
                        title: 'Yakin Ingin Kembalikan?',
                        text: 'Dengan pilih ya kamu akan mengeluarkan DN ini dari daftar hapus.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya Kembalikan',
                        showLoaderOnConfirm: true,
                        preConfirm: (reason) => {
                            
                            data.reason = reason;
                            return $.post('admin-ajax.php', data, (data, textStatus, jqXHR) => {
                                if( data.status === 'success' ) {
                                    // console.log('sucess!')
                                    // return 'josh_success'
                                } else {
                                    Swal.showValidationMessage(`Delete failed: ${data.message}`)
                                    // return 'not success'
                                }
                            })
                        }
                    })
                    .then((result) => {
                        console.log(result)
                        if( result.value.status === 'success' ) {
                            mainTable.ajax.reload( null, false );
    
                            Swal.fire({
                                title: 'Success',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true
                            })
                        }
                    });
                } // end of else if statement

            }); // end of action 1 click event
        }
    }
}).on('xhr.dt', (e, settings, json, xhr ) => {
    $('#belum-tf-text').text(json.belumTf);
    $('#nomor-dikelola-text').text(json.semuaNomor);
    $('#donatur-aktif-text').text(json.dnAktif);
    $('#closing-rate-text').text(json.closingRate+'%');
    $('#donasi-organik-text').text('Rp'+json.DOD);
    $('#donasi-iklan-text').text('Rp'+json.DID);
    $('#leads-text').text(json.leads);
    $('#nilai-omset-text').text('Rp'+json.omset);

    {
        let quickSearch = getSearchText();

        if(quickSearch == null) {
            $('#quick-search-badges').addClass('d-none');
            $('#search-text').val('');
        } else {
            $('#search-text').val('');
            $('#quick-search-badges').removeClass('d-none');
        }
    }

    // console.log('swal close!');
    // Swal.close();

    // $('#donation-value').text(json.value_donation);
    // $('#donors-total').text(json.recordsFiltered);
}).on('draw', () => {

    Swal.close();

})


/**
 * Initialize right sheet class and all needed
 */
const loading = {allData:[], chart:'', table: ''};
let donorTitle = document.getElementsByClassName('row avatar');
let donorAllData = document.getElementsByClassName('row all-data')[0].children;
loading.allData.push(donorTitle[0]);
for(let i=0; i < donorAllData.length; i++) {
    loading.allData.push(donorAllData[i]);
}

const allDataIds = {'#jh-donor-name':'nama','#jh-donor-phone':'phone','#jh-donor-category':'category','#jh-donor-program':'program','#jh-donor-email':'email','#jh-donor-user':'user','#jh-donor-payment':'payment','#jh-donor-ltv':'ltv','#jh-donor-adv':'adv','#jh-donor-dvol':'dvol','#jh-donor-dibuat':'dibuat','#jh-donor-dibuat-':'dibuat_','#jh-donor-kota':'kota','#owner-name':'oName','#owner-email':'oEmail'};
// console.log(loading);
const RightSheet = new RSheet('offcanvasRight', '#table-one-donor', '#modalRight', loading, allDataIds);

/**
 * offcanvas Right Listener
 */
$('#offcanvasRight').on('hide.bs.offcanvas', (e) => {
    RightSheet.close();
});

/**
 * Custom Filter (demo right sheet)
 */
$('#custom-filter').click(function (e) { 
    e.preventDefault();
    
    // RightSheet.open();
});

/**
 * 
 */
$(window).scroll(function() {
  if ($(this).scrollTop() > 80) {
    $('#scroll-top').show('fast');
  } else {
    $('#scroll-top').hide('fast');
  }
});

$("#scroll-top").click(function (){
    $('html, body').animate({
        scrollTop: '0'
        // scrollTop: $("#div1").offset().top
    });
});