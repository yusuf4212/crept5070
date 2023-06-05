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

        $('#cs-name').selectivity('clear');
        $('#target-page-cs').selectivity('clear');
        $('#cc-name').selectivity('clear');
        $('#target-page-cc').selectivity('clear');
    }
    else if(section === 'cs') {
        $('#duta-section').hide();
        $('#cc-section').hide();
        $('#cs-section').show();

        $('#duta-name').selectivity('clear');
        $('#target-page-duta').selectivity('clear');
        $('#cc-name').selectivity('clear');
        $('#target-page-cc').selectivity('clear');
    }
    else if(section === 'cc') {
        $('#duta-section').hide();
        $('#cs-section').hide();
        $('#cc-section').show();

        $('#duta-name').selectivity('clear');
        $('#target-page-duta').selectivity('clear');
        $('#cs-name').selectivity('clear');
        $('#target-page-cs').selectivity('clear');
    }
});

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
    allowClear: true,
    placeholder: 'Halaman Tujuan'
});

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
    allowClear: true,
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
    items: [{id: 1, text: 'bla'}],
    allowClear: true,
    placeholder: 'ympb.me/'
});

// CC UTM Source
$('#cc-usource').selectivity({
    // items: [
    //     {id: 1, text: 'TikTok', value: 'cc_tiktok'},
    //     {id: 2, text: 'IG', value: 'cc_ig'},
    //     {id: 3, text: 'FB', value: 'cc_fb'},
    //     {id: 4, text: 'YT', value: 'cc_yt'},
    // ],
    items: uSource,
    allowClear: true,
    placeholder: 'TikTok, IG, FB, YT'
});

// Add new UTM Content
$('#add-new-usource').click(function (e) { 
    e.preventDefault();
    
    Swal.fire({
        title: 'Create New UTM Source',
        input: 'text',
        showCancelButton: true,
        confirmButtonText: 'Create',
        showLoaderOnConfirm: true,
        preConfirm: (text) => {
            let data_ = {action: 'jh_new_utm', field: 'usource'};
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
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        //
    });
});

// $('#cc-usource').change(function (e) { 
//     e.preventDefault();
    
//     urlBuilder.cc('cc', e.delegateTarget.selectivity._data);
// });

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
    $('#cc-ucontent').selectivity('data', {id: 5, text: 'Feed'});
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

// target page
$('#target-page-cc').selectivity({
    items: target,
    allowClear: true,
    placeholder: 'Halaman Tujuan'
});

$('#target-page-cc').change(function (e) { 
    e.preventDefault();
    
    urlBuilder.cc('target', e.delegateTarget.selectivity._data);
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
