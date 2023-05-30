$(document).ready(function () {
    /**
     * Input WhatsApp
     */
    $('#no-wa').keyup(function (e) {
        let value = e.target.value;
        let valueReg = value.replace(/\D/g, '');

        // replace first 62
        if( valueReg.startsWith('62')) {
            valueReg = '0' + valueReg.substring(2)
        }
        // console.log(valueReg);
        // valueReg = parseInt(valueReg);

        if ( valueReg <= 99999999999999) {
            // let clean = formatted.format(valueReg);
            // console.log(e);
            // console.log(e.target.value);
            // console.log(clean);
            // console.log('regex: '+value.replace(/^\D+/g, ''));
            $('#no-wa').val(valueReg);
        } else {
            $('#no-wa').val(0);
        }

    });

    /**
     * Given Date
     */
    $('#given-date').daterangepicker({
        // "autoApply": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '2 Days Ago': [moment().subtract(2, 'days'), moment().subtract(2, 'days')],
            '3 Days Ago': [moment().subtract(3, 'days'), moment().subtract(3, 'days')],
            '4 Days Ago': [moment().subtract(4, 'days'), moment().subtract(4, 'days')],
        },
        "singleDatePicker": true,
        "showCustomRangeLabel": false,
        "autoUpdateInput": false,
        "alwaysShowCalendars": true,
        "startDate": moment(),
        "endDate": moment(),
        "opens": "center"
    }, function(start, end, label) {
        if( label == null) {
            var label1 = start.format('DD MMM YYYY');
        } else { label1 = label;}
        $('#given-date').text(label1);

        let dateStart = start.format('YYYY-MM-DD');

        givenDate = dateStart;
        // console.log(givenDate);
    });
    
     /**
      * Program
      */
     $('#program').selectivity({
        items: jsonReady.program,
        allowClear: true,
        placeholder: 'pilih program'
     });

    /**
     * Platform
     */
    $('#platform').selectivity({
        items: jsonReady.platform,
        allowClear: true,
        placeholder: 'pilih platform'
     });
     
    /**
     * Relawan
     */
    $('#relawan').selectivity({
        items: jsonReady.cs,
        allowClear: true,
        placeholder: 'pilih relawan'
     });
 
    /**
     * Amount
     */
    var formatted = new Intl.NumberFormat("id-ID", {
        style: 'decimal',
    });

    $('#amount').keyup(function (e) { 
        let value = e.target.value;
        let valueReg = value.replace(/\D+/g,'');
        valueReg = parseInt(valueReg);

        if ( valueReg <= 100000000) {
            let clean = formatted.format(valueReg);
            // console.log(e);
            // console.log(e.target.value);
            // console.log(clean);
            // console.log('regex: '+value.replace(/^\D+/g, ''));
            $('#amount').val('Rp '+clean);
        } else {
            $('#amount').val(0);
        }

    });

    // /**
    //  * Type
    //  */
    // $('#type').selectivity({
    //     items: jsonReady.type,
    //     allowClear: true,
    //     placeholder: 'pilih type'
    //  }); 

    /**
     * Bank
     */
    $('#bank').selectivity({
        items: jsonReady.bank,
        allowClear: true,
        placeholder: 'pilih bank'
     });

    /**
     * Transfer Date
     */
    $('#transfer-date').daterangepicker({
        // "autoApply": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '2 Days Ago': [moment().subtract(2, 'days'), moment().subtract(2, 'days')],
            '3 Days Ago': [moment().subtract(3, 'days'), moment().subtract(3, 'days')],
            '4 Days Ago': [moment().subtract(4, 'days'), moment().subtract(4, 'days')],
        },
        "singleDatePicker": true,
        "showCustomRangeLabel": false,
        "autoUpdateInput": false,
        "alwaysShowCalendars": true,
        "startDate": moment(),
        "endDate": moment(),
        "opens": "center"
    }, function(start, end, label) {
        if( label == null) {
            var label1 = start.format('DD MMM YYYY');
        } else { label1 = label;}
        $('#transfer-date').text(label1);

        let dateStart = start.format('YYYY-MM-DD');
        let dateEnd = end.format('YYYY-MM-DD');

        // let cleanDate = {'from': dateStart, 'end': dateEnd};
        let cleanDate = start.format('YYYY-MM-DD');
        // console.log(cleanDate);
        transferDate = cleanDate;
        // console.log(transferDate);

    });

    /**
     * FILE UPLOAD
    */
    $('#upload').change(function (e) { 
        e.preventDefault();
        let file_data = $('#upload')[0].files[0];
        let imgPreview = $('#preview')[0];
        
        // console.log($('#upload')[0].files);
        // console.log(file_data);
        imgPreview.src = URL.createObjectURL(file_data);
        $('.remove-uploaded').show();
        // console.log(imgPreview.src);
    });

    /**
     * File uplode remover
     */
    $('.remove-uploaded').click(function (e) { 
        e.preventDefault();
        let imgPreview = $('#preview')[0];

        $('#upload').val('');
        imgPreview.src = '';
        $('.remove-uploaded').hide();
    });

    /**
     * SUBMIT DATA
     */
    var givenDate = moment().format('YYYY-MM-DD');
    var transferDate = moment().format('YYYY-MM-DD');

    $('#submit').click(function (e) { 
        e.preventDefault();

        var noWa = $('#no-wa').val();
        var program = $('#program').selectivity('data');
        var platform = $('#platform').selectivity('data');
        var relawan = $('#relawan').selectivity('data');
        var amount = $('#amount').val();
        let amountReg = amount.replace(/\D/g, '');
        var type = $('#type').selectivity('data');
        var bank = $('#bank').selectivity('data');
        let file_data = $('#upload')[0].files[0];

        /**
         * Form Checking
         */
        let validationStat = false;
        if( noWa == '' || noWa == null || noWa.length < 5){
            $('.notice-error.no-wa').show('fast');
            validationStat = true;
        } else {
            $('.notice-error.no-wa').hide();

            if( ! noWa.startsWith('08') ) {
                swal({
                    title: 'WhatsApp Tidak Sesuai',
                    text: 'Nomor WhatsApp tidak sesuai format (08xxx), perbaiki dulu?',
                    icon: 'info',
                    buttons: ['Perbaiki', 'Lanjutkan Submit']
                })
                .then((e) => {
                    if (! e) {
                        validationStat = true;
                        $('.notice-error.no-wa-2').show('fast');
                    }
                })
            }
        }
        if( program == '' || program == null) {
            $('.notice-error.program').show('fast');
            validationStat = true;
        } else {
            $('.notice-error.program').hide();
        }
        if( platform == '' || platform == null) {
            $('.notice-error.platform').show('fast');
            validationStat = true;
        } else {
            $('.notice-error.platform').hide();
        }
        if( relawan == '' || relawan == null) {
            $('.notice-error.relawan').show('fast');
            validationStat = true;
        } else {
            $('.notice-error.relawan').hide();
        }
        if( amountReg == '' || amountReg == null) {
            $('.notice-error.amount').show('fast');
            validationStat = true;
        } else {
            $('.notice-error.amount').hide();
        }
        // if( type == '' || type == null) {
        //     $('.notice-error.type').show('fast');
        //     validationStat = true;
        // } else {
        //     $('.notice-error.type').hide();
        // }
        if( bank == '' || bank == null) {
            $('.notice-error.bank').show('fast');
            validationStat = true;
        } else {
            $('.notice-error.bank').hide();
        }
        if( file_data == undefined) {
            $('.notice-error.upload').show('fast');
            validationStat = true;
        } else {
            $('.notice-error.upload').hide();
        }

        if( validationStat == true) {
            return;
        }
        // console.log('validation succeed!');

        /**
         * Disabling button
         */
        $('#submit').prop('disabled', true)
        $('#submit').css('cursor', 'not-allowed')


        var form_data = new FormData();
        
        // console.log(Object.entries(relawan));
        form_data.append('file', file_data);
        form_data.append('action', 'josh_imgupload');
        form_data.append('security', josh_ajax.security);
        form_data.append('noWa', noWa);
        form_data.append('givenDate', givenDate);
        form_data.append('program', Object.values(program)[1]);
        form_data.append('platform', Object.values(platform)[1]);
        form_data.append('relawan', Object.values(relawan)[1]);
        form_data.append('amount', amountReg);
        // form_data.append('type', Object.values(type)[1]);
        form_data.append('bank', Object.values(bank)[1]);
        form_data.append('transferDate', transferDate);
        form_data.append('userId', userId);
        // console.log(file_data);
        // console.log(form_data);
        // console.log(...form_data);
        // console.log(josh_ajax);

        
        var jqXHR = $.ajax({
                        type: "POST",
                        url: josh_ajax.ajaxurl,
                        data: form_data,
                        contentType: false,
                        processData: false
                        })
                        .done( (data, textStatus, jqXHR) => {
                        // console.log('success');
                        // console.log( data, textStatus, jqXHR);
                        
                        data = data.replace("\n","")
                        data = JSON.parse(data)
                        // console.log(data);

                        if( data.status == 'success') {
                            let text = "Data berhasil disubmit!\n\n"+data.desc.url+"\n"+"lanjut isi lagi?";
                            swal({
                                title: "Success!",
                                text: text,
                                icon: "success",
                                buttons: ['Tidak', 'Ya Ulangi']
                              })
                              .then((e) => {
                                if (e) {
                                    // console.log('yes ulangi form');
                                    resetForm();
                                } else {
                                //   console.log('tidak, selesai');
                                    resetForm();
                                }

                                $('#submit').prop('disabled', false)
                                $('#submit').css('cursor', 'pointer')
                            });
                            } else {
                                $('#submit').prop('disabled', false)
                                $('#submit').css('cursor', 'pointer')

                                let title = "Gagal! "+data.desc.title;
                                let text = data.desc.message;
                                swal({
                                    title: title,
                                    text: text,
                                    icon: "error",
                                    buttons: ['Ok!']
                                });
                            }
                        })
                        .fail( (jqXHR, textStatus, errorThrown) => {
                        // console.log('fail');
                        // console.log( jqXHR, textStatus, errorThrown);

                        swal({
                            title: "Fail!",
                            text: "Telah terjadi kesalahan!"+'error: '+errorThrown,
                            icon: "error",
                            buttons: ['Ok!']
                          });
                        });

        
            function resetForm() {
                let imgPreview = $('#preview')[0];

                $('#no-wa').val('');
                $('#program').selectivity('clear');
                $('#platform').selectivity('clear');
                $('#relawan').selectivity('clear');
                $('#amount').val('');
                // $('#type').selectivity('clear');
                $('#bank').selectivity('clear');
                $('#upload').val('');
                imgPreview.src = '';
                $('.remove-uploaded').hide();

                $('#no-wa').focus();
            }
    });

});