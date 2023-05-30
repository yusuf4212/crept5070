/**
 * Set date today
 */
$('#datepicker').text(moment().format('DD MMM YYYY'));
ajaxSend.date = moment().format('YYYY-MM-DD');
ajax.date = moment().format('YYYY-MM-DD');

/**
 * Date
 */
$('#datepicker').daterangepicker({
    "autoApply": true,
    "singleDatePicker": true,
    "showCustomRangeLabel": false,
    "autoUpdateInput": false,
    "alwaysShowCalendars": true,
    "startDate": moment(),
    "endDate": moment(),
    'minDate': '01/01/2022',
    'maxDate': max_date,
    "opens": "center"
}, function(start, end, label) {
    $('#datepicker').text(start.format('DD MMM YYYY'));

    var result = start.format('YYYY-MM-DD')
    $('#loader').show();

    ajaxSend.date = result;
    ajax.date = result;

    _ajax();
    // $.post('/wp-admin/admin-ajax.php', ajax,
    //     function (data, textStatus, jqXHR) {
    //         // console.log(data)
    //         phone_list = data.phone_list;

    //         // set 'done input' label
    //         if(data.done_leads === true) {
    //             $('.row.done').show();
    //         } else {
    //             $('.row.done').hide();
    //         }

    //         // update no_leads
    //         no_leads = data.no_leads_ar;
    //         no_leads_obj = data.no_leads_obj;

    //         // update phone list
    //         $('#new-order').html(phone_list.length);
    //         let actual_number = phone_list.length - no_leads.length
            
    //         $('#actual-number').html(actual_number);

    //         $('#picker-phone').selectivity('clear');
    //         $('#picker-phone').selectivity('rerenderSelection');
    //         $('#loader').hide();
    //     }
    // );
});


/**
 * Select Phone
 */
$('#picker-phone').selectivity({
    items: phone_list,
    inputType: 'Multiple',
    tokenSeparators: [' '],
    value: no_leads // continue to make this one
});


$('#picker-phone').change(function (e) { 
    e.preventDefault(); console.log(e);
    
    // change selectivity items
    e.currentTarget.selectivity.items = phone_list;
    e.currentTarget.selectivity._data = no_leads_obj;

    let currentLength = e.currentTarget.selectivity.items.length - e.currentTarget.selectivity._data.length;

    // change actual number
    $('#actual-number').html(currentLength);
});

$('#picker-phone').click(function (e) { 
    e.preventDefault();
    console.log(e);
});

Swal.fire({
    title: 'Harap Tunggu...',
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: false,
    didOpen: () => {
        Swal.showLoading();
    },
    willClose: () => {
        _ajax();
    }
})

function _ajax() {
    $.post('/wp-admin/admin-ajax.php', ajax,
        function (data, textStatus, jqXHR) {
            // console.log(data)
            phone_list = data.phone_list;

            // set 'done input' label
            if(data.done_leads === true) {
                $('.row.done').show();
            } else {
                $('.row.done').hide();
            }

            // update no_leads
            no_leads = data.no_leads_ar;
            no_leads_obj = data.no_leads_obj;

            // update phone list
            $('#new-order').html(phone_list.length);
            let actual_number = phone_list.length - no_leads.length
            
            $('#actual-number').html(actual_number);

            $('#picker-phone').selectivity('clear');
            $('#picker-phone').selectivity('rerenderSelection');
            $('#loader').hide();
            // Swal.hideLoading();
            Swal.close();
        }
    );
}

_ajax();

/**
 * Submit
 */
$('.sub-row.submit').click(function (e) { 
    e.preventDefault();

    let phone = $('#picker-phone').selectivity('data');
    // console.log(phone);
    if( phone.length === 0 ) {

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah kamu yakin? jumlah nomor tanpa WA: '+phone.length,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return sendAjax();
            }
        })
        // .then((e) => {
        //     console.log(e)

        //     if( e.isConfirmed === true ) {

        //         ajaxSend.phone = undefined;
        //         Swal.showLoading();
        //         sendAjax();
                
        //     }
        // })

    } else {

        ajaxSend.phone = JSON.stringify(phone);
        Swal.showLoading();
        sendAjax();

    }
    
});

function sendAjax() {

    // Swal.showLoading();

    return $.post('/wp-admin/admin-ajax.php', ajaxSend,
        function (data, textStatus, jqXHR) {

            ajaxSend.phone = undefined
            if(data.status === 'success') {
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    title: 'Failed!',
                    icon: 'error',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        }
    );

}