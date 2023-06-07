const a = document.querySelector('input[name="owner"]:checked');
const b = a.value;

let urlBuilder = new Url_Builder;

/**
 * Radio button Changes
 */
$('input[name="owner"]').change(function (e) { 
    e.preventDefault();
    // console.log($('input[name="owner"]:checked').val())
    const section = $('input[name="owner"]:checked').val();

    if(section === 'duta') {
        $('#cs-section').hide();
        $('#cc-section').hide();
        $('#duta-section').show();
        $('#row-result-cc').hide();
        $('#row-result').show();
        $('#row-submit-slink').hide();

        selectivityClear();
    }
    else if(section === 'cs') {
        $('#duta-section').hide();
        $('#cc-section').hide();
        $('#cs-section').show();
        $('#row-result-cc').hide();
        $('#row-result').show();
        $('#row-submit-slink').hide();

        selectivityClear();
    }
    else if(section === 'cc') {
        $('#duta-section').hide();
        $('#cs-section').hide();
        $('#cc-section').show();
        $('#row-result-cc').hide();
        $('#row-result').hide();
        $('#row-submit-slink').show();

        selectivityClear();
    }

    $('#target-page-duta').selectivity('data', target[0]);
    $('#target-page-cs').selectivity('data', target[0]);
    $('#target-page-cc').selectivity('data', target[0]);
});

function selectivityClear() {
    $('#cs-name').selectivity('clear');
    $('#target-page-cs').selectivity('clear');
    $('#duta-name').selectivity('clear');
    $('#target-page-duta').selectivity('clear');
    $('#cc-preset').selectivity('clear');
    $('#cc-usource').selectivity('clear');
    $('#cc-ucontent').selectivity('clear');
    $('#cc-ucampaign').selectivity('clear');
}

/**
 * Toast Config
 */
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    showCloseButton: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

const newUTM = Swal.mixin({
    html: '<p>Hanya menerima huruf (aA) dan angka.</p><p>Sangat disarankan menggunakan 1 atau 2 suku kata (singkat).</p>',
    input: 'text',
    showCancelButton: true,
    confirmButtonText: 'Create',
    showLoaderOnConfirm: true,
    allowOutsideClick: () => !Swal.isLoading(),
    customClass: {
        input: 'input-new-utm'
    },
    didOpen: () => {
        $('.input-new-utm').keyup(function (e) { 
            let text = $('.input-new-utm').val();
            text = text.replace(/[^a-zA-Z0-9 ]/g, '');
            text = text.slice(0, 100);
            $('.input-new-utm').val(text);
        });        
    },
    willClose: () => {
        $('.input-new-utm').unbind('keyup');
    }
});

function newUtmAjax(field_, text_) {
    let data_ = {action: 'jh_new_utm', field: field_, text: text_};

    return $.post(ajaxLink, data_,
    function (data, textStatus, jqXHR) {
        if(data.status === 'success') {
            Swal.fire({
                title: 'Create Success',
                icon: 'success',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.showValidationMessage(
                `Failed: ${data.messages}`
            )
        }
    }
).fail(() => {
    Swal.hideLoading();
    Swal.showValidationMessage('Failed! Please login first!');
});
}

// $('.input-new-utm').keyup(function (e) { 
//     console.log(e);
// });

/**
 * Duta Section
 */
// Duta Name
$('#duta-name').selectivity({
    items: duta,
    allowClear: true,
    placeholder: 'Nama Duta'
})

$('#duta-name').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.duta('duta', e.delegateTarget.selectivity._data);
});

// target page
$('#target-page-duta').selectivity({
    items: target,
    placeholder: 'Halaman Tujuan'
});

$('#target-page-duta').selectivity('data', target[0]);

$('#target-page-duta').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.duta('target', e.delegateTarget.selectivity._data);
});


/**
 * CS Section
 */
// Duta Name
$('#cs-name').selectivity({
    items: [
        {id: 1, text: 'Husna', value: 'husna'},
        {id: 2, text: 'Meisya', value: 'meisya'},
        {id: 3, text: 'Safina', value: 'safina'},
        {id: 4, text: 'Fadhilah', value: 'fadhilah'}
    ],
    allowClear: true,
    placeholder: 'Nama CS'
})

$('#cs-name').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.cs('cs', e.delegateTarget.selectivity._data);
});

// target page
$('#target-page-cs').selectivity({
    items: target,
    placeholder: 'Halaman Tujuan'
});

$('#target-page-cs').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.cs('target', e.delegateTarget.selectivity._data);
});


/**
 * CC Section
 */
// CC Preset
$('#cc-preset').selectivity({
    // items: [{id: 1, text: 'bla'}],
    items: slink,
    allowClear: true,
    placeholder: 'ympb.me/'
});

$('#cc-preset').change(function (e) { 
    e.preventDefault();
    
    let data = $(e.delegateTarget).selectivity('data');
    //
    if(data === null) {
        if($('input[name="owner"]:checked').val() === 'cc') {
            $('#row-submit-slink').show();
            $('#row-result-cc').hide();
        }

        $('#cc-usource').selectivity('setOptions', {readOnly: false});
        $('#cc-ucontent').selectivity('setOptions', {readOnly: false});
        $('#cc-ucampaign').selectivity('setOptions', {readOnly: false});
        
        $('#cc-usource').selectivity('clear');
        $('#cc-ucontent').selectivity('clear');
        $('#cc-ucampaign').selectivity('clear');
        $('#target-page-cc').selectivity('data', target[0]);
    } else {
        if($('input[name="owner"]:checked').val() === 'cc') {
            $('#row-submit-slink').hide();
            $('#row-result-cc').show();
        }

        $('#cc-usource').selectivity('setOptions', {readOnly: true});
        $('#cc-ucontent').selectivity('setOptions', {readOnly: true});
        $('#cc-ucampaign').selectivity('setOptions', {readOnly: true});

        urlBuilder.generator_cc(data);
    }
});

// CC UTM Source
$('#cc-usource').selectivity({
    items: uSource,
    allowClear: true,
    placeholder: 'TikTok, IG, FB, YT'
});

// Add new UTM Content
$('#add-new-usource').click(function (e) { 
    e.preventDefault();
    
    newUTM.fire({
        title: 'Create New UTM Source',
        preConfirm: (text) => {
            return newUtmAjax('usource', text);
        },
    }).then((result) => {
        //
    });
});

$('#cc-usource').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.cc('usource', e.delegateTarget.selectivity._data);
});

// CC UTM Content
$('#cc-ucontent').selectivity({
    // items: [
    //     {id: 1, text: 'Shorts'},
    //     {id: 2, text: 'Reels'},
    //     {id: 3, text: 'Story'},
    //     {id: 4, text: 'Post'},
    //     {id: 5, text: 'Feed'}
    // ],
    items: uContent,
    allowClear: true,
    placeholder: 'Shorts, Reels, Story'
});

$('#add-new-ucontent').click(function (e) { 
    e.preventDefault();
    // $('#cc-ucontent').selectivity('data', {id: 5, text: 'Feed'});
    newUTM.fire({
        title: 'Create New UTM Content',
        preConfirm: (text) => {
            return newUtmAjax('ucontent', text);
        }
    });
});

$('#cc-ucontent').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.cc('ucontent', e.delegateTarget.selectivity._data);
});


// CC UTM Campaign
$('#cc-ucampaign').selectivity({
    // items: [
    //     {id: 1, text: 'Qurban 1444H', value: 'qurban_1444h'}
    // ],
    items: uCampaign,
    allowClear: true,
    placeholder: 'Campaign Name'
});

$('#add-new-ucampaign').click(function (e) { 
    e.preventDefault();
    
    newUTM.fire({
        title: 'Create New UTM Source',
        preConfirm: (text) => {
            return newUtmAjax('ucampaign', text);
        }
    });
});

$('#cc-ucampaign').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.cc('ucampaign', e.delegateTarget.selectivity._data);
});


// target page
$('#target-page-cc').selectivity({
    items: target,
    placeholder: 'Halaman Tujuan'
});

$('#target-page-cc').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.cc('target', e.delegateTarget.selectivity._data);
});

// short link
$('#cc-short-link').keyup(function (e) { 
    let text = e.delegateTarget.value;
    text = text.replace(/[ ]/g, '-');
    text = text.replace(/[^a-zA-Z0-9-_]/g, '');

    $('#cc-short-link').val(text);
    $('#preview-cc').text(text);
});

// submit preset cc
$('#submit-short-link').click(function (e) { 
    e.preventDefault();
    
    urlBuilder.cc_sumbit();
});

/**
 * Copy (to clipboard)
 */
$('#copy-btn').click(function (e) { 
    e.preventDefault();

    if(urlBuilder.results === undefined || urlBuilder.results === '') {
        Toast.fire({
            icon: 'error',
            title: 'Fail to Copy',
            text: 'Please input the Duta field'
        });
    } else {
        navigator.clipboard.writeText(urlBuilder.results).then(() => {
            Toast.fire({
                icon: 'success',
                title: 'Copied',
                text: 'Link berhasil di copy!'
            });
        }, (err) => {
            console.error('Could not copy to clipboard: '+err)
            Toast.fire({
                icon: 'error',
                title: 'Fail to Copy',
                text: err
            });
        });
    }
});

$('#copy-btn-cc').click(function (e) { 
    e.preventDefault();

    if(urlBuilder.results_cc === undefined || urlBuilder.results_cc === '') {
        Toast.fire({
            icon: 'error',
            title: 'Fail to Copy'
        });
    } else {
        navigator.clipboard.writeText(urlBuilder.results_cc).then(() => {
            Toast.fire({
                icon: 'success',
                title: 'Copied',
                text: 'Link berhasil di copy!'
            });
        }, (err) => {
            console.error('Could not copy to clipboard: '+err)
            Toast.fire({
                icon: 'error',
                title: 'Fail to Copy',
                text: err
            });
        });
    }
});
