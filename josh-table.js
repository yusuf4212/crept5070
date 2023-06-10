$(document).ready(function () {

    /**
     * Ajax Error Handler
     */
    $(document).ajaxError(function( event, request, settings) {
        //convert url to json
        var myUrl = JSON.parse('{"' + decodeURI(settings.data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
        bootbox.alert({
            title: 'Error!',
            message: "Terjadi gangguan pada internal server.",
            callback: () => {  }
        });
    });

    class FilterApply {
        relawan; program; type; platform; bank; transferDate;
        payload;

        run() {
            this.init();

            var check = this.check();

            if( check == true) {
                this.execute();
                this.finish();
            }
        }

        init() {
            // console.log('init phase!');

            this.relawan = $('#select-relawan').selectivity('data');
            this.program = $('#select-program').selectivity('data');
            this.type = $('#select-type').selectivity('data');
            this.platform = $('#select-platform').selectivity('data');
            this.bank = $('#select-bank').selectivity('data');

            // this.relawan = { ...this.relawan};
            // console.log(this);
        }
        
        check() {
            // console.log('check phase!');
            if( this.relawan != '' || this.program != '' || this.type != '' || this.platform != '' || this.bank != '' || this.transferDate != '') {
                this.payload={};

                this.payload.relawan = this.relawan;
                this.payload.program = this.program;
                this.payload.type = this.type;
                this.payload.platform = this.platform;
                this.payload.bank = this.bank;
                this.payload.transferDate = this.transferDate;
    
                // console.log(this);
                return true;
            }
        }

        execute() {
            // console.log('execute phase!');
            // console.log(this.payload);

            this.payload = JSON.stringify(this.payload);
            // console.log(this.payload);
            // console.log(this);
            // console.log(typeof this.payload);

            table.ajax.reload( null, true);
            
            // this.payload=[];
        }

        finish() {
            this.relawan=undefined; this.program=undefined; this.type=undefined; this.platform=undefined; this.bank=undefined;
        }
    }

    var FilterApply1 = new FilterApply();

    class changeDB {
        idBefore; oldData; newData; field = 'data'; ajaxName; responseAjax;

        run() {
            if ( changeDBMeta1.wait == false) {
                changeDBMeta1.wait = true;
                if ( this.oldData == this.newData ) {
                    // console.log('still same '+ this.field +'!');
                    
                    this.clearProp();
                    // console.log(this);
                    let myreturn = { status: false, hint: 'sameData' };

                    return myreturn;
                } else {
                    // console.log(this);
                    let myreturn = { status: true};

                    return myreturn;
                }
            } else {
                let myreturn1 = { status: false, hint: 'busy'};

                return myreturn1;
            }
        };

        execute() {
            if ( this.ajaxName != undefined) {
                let field = this.field;

                var data = {
                    "action"    : this.ajaxName,
                    "data"      : {
                        id  : this.idBefore,
                        [field] : this.newData,
                        field : field
                    }
                }
                // console.log(data);
    
                $.post("admin-ajax.php", data,
                    function (data, textStatus, jqXHR) {
                        data = data.replace("\n","");
                        data = JSON.parse(data);
                        // console.log(data); console.log(textStatus);
                        let response = {
                            data: data,
                            textStatus: textStatus,
                            jqXHR: jqXHR
                        }
                        changeDBMeta1.init( response);
                    }
                )

                changeDBMeta1.field = field;
            } else {
                this.clearProp();
                changeDBMeta1.failed( 'Ups, internal script error!', 'ajaxName not defined!');
            }
        }

        evaluate() {
            let data = this.responseAjax.data;
            let textStatus = this.responseAjax.textStatus;
            let jqXHR = this.responseAjax.jqXHR;

            if (textStatus == 'success' && data.dbStat == 1) {
                table.ajax.reload( null, false );   //not reset paging
            } else {
                this.clearProp();
                changeDBMeta1.failed( 'Failure in DataBase!', 'Perubahan '+this.field+' gagal! Terjadi error pada database!');
            }

            changeDBMeta1.field=undefined; changeDBMeta1.responseAjax=undefined; changeDBMeta1.wait=false;
        }

        clearProp() {
            this.idBefore = undefined;
            this.oldData = undefined;
            this.newData = undefined;
            this.responseAjax = undefined;
        }
    };

    var changeDBvar = new changeDB();

    class changeDBMeta {
        field; responseAjax; wait=false;

        init( response) {
            this.responseAjax = response;

            if( this.field == changeRelawan1.field){
                changeRelawan1.evaluate(this.responseAjax);
            } else if( this.field == changeGivenDate1.field) {
                changeGivenDate1.evaluate(this.responseAjax);
            } else if( this.field == changeProgram1.field) {
                changeProgram1.evaluate( this.responseAjax);
            } else if(this.field == changeType1.field) {
                changeType1.evaluate( this.responseAjax);
            } else if( this.field == changePlatform1.field) {
                changePlatform1.evaluate( this.responseAjax);
            } else if( this.field == changeBank1.field) {
                changeBank1.evaluate( this.responseAjax);
            } else if( this.field == changeAmount1.field) {
                changeAmount1.evaluate( this.responseAjax);
            } else if( this.field == changeWhatsApp1.field) {
                changeWhatsApp1.evaluate( this.responseAjax);
            } else if( this.field == changeTFDate1.field) {
                changeTFDate1.evaluate( this.responseAjax);
            } else {
                //provide all field for clearence
                changeRelawan1.clearProp();

                changeDBMeta1.failed( 'DB response unknown!', 'Identitas response database tidak dikenali.');
            }
        }

        failedInit( hint, field) {
            if( hint == 'sameData') {
                changeDBMeta1.failed( 'Tidak terjadi perubahan', field+' yang kamu pilih sama seperti sebelumnya. Harap pastikan kembali.');
            } else if ( hint == 'busy') {
                changeDBMeta1.failed( 'Please wait', 'Sepertinya sedang terjadi antrean pada request ke database! Pastikan koneksi internet stabil.');
            }
        }

        failed( title, message) {
            bootbox.alert({
                title: title,
                message: message,
                callback: () => {}
            })
        }

        clearProp() {
            changeDBMeta1.field=undefined; changeDBMeta1.responseAjax=undefined;changeDBMeta1.wait=false;
        }
    }

    var changeDBMeta1 = new changeDBMeta();

    class changeRelawan extends changeDB {
        field='relawan';
        ajaxName='joshfunction_table_change';

        run() {
            // this.field='relawan';
            // console.log(this);

            // console.log('.wait:');
            // console.log(changeDBMeta1.wait);
            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);
            
            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate(responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeRelawan1 = new changeRelawan();

    class changeGivenDate extends changeDB {
        field = 'given_date';
        ajaxName = 'joshfunction_table_change';

        run() {
            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeGivenDate1 = new changeGivenDate();

    class changeProgram extends changeDB {
        field = 'program';
        ajaxName = 'joshfunction_table_change';

        run() {
            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if ( superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeProgram1 = new changeProgram();

    class changeType extends changeDB {
        field='type';
        ajaxName='joshfunction_table_change';

        run() {
            // console.log(this);

            // console.log('.wait:');
            // console.log(changeDBMeta1.wait);

            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeType1 = new changeType();

    class changePlatform extends changeDB {
        field='platform';
        ajaxName='joshfunction_table_change';

        run() {
            // console.log(this);

            // console.log('.wait:');
            // console.log(changeDBMeta1.wait);

            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changePlatform1 = new changePlatform();

    class changeBank extends changeDB {
        field='bank';
        ajaxName='joshfunction_table_change';

        run() {
            // console.log(this);

            // console.log('.wait:');
            // console.log(changeDBMeta1.wait);

            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeBank1 = new changeBank();

    class changeAmount extends changeDB {
        field='nominal';
        ajaxName='joshfunction_table_change';

        run() {
            // console.log(this);

            // console.log('.wait:');
            // console.log(changeDBMeta1.wait);

            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeAmount1 = new changeAmount();

    class changeWhatsApp extends changeDB {
        field='whatsapp';
        ajaxName='joshfunction_table_change';

        run() {
            // console.log(this);

            // console.log('.wait:');
            // console.log(changeDBMeta1.wait);

            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeWhatsApp1 = new changeWhatsApp();

    class changeTFDate extends changeDB {
        field='transfer_date';
        ajaxName='joshfunction_table_change';

        run() {
            // console.log(this);

            // console.log('.wait:');
            // console.log(changeDBMeta1.wait);

            let superRun = super.run();
            // console.log('superRun var: ');
            // console.log(superRun);

            if (superRun.status != false) {
                super.execute();
            } else {
                this.clearProp();

                changeDBMeta1.failedInit( superRun.hint, this.field);
            }
        }

        evaluate( responseAjax) {
            this.responseAjax = responseAjax;
            // console.log(this.responseAjax);
            super.evaluate();
        }

        clearProp() {
            changeDBMeta1.clearProp();
            super.clearProp();
        }
    }
    var changeTFDate1 = new changeTFDate();


    var heartLoading = $('.model-body')[0];
    heartLoading = heartLoading.innerHTML;
    var floatRelawan = $('#float-relawan')[0];
    var listRelawan  = floatRelawan.children[0].children[1].children;

    var floatProgram = $('#float-program')[0];
    var listProgram = floatProgram.children[0].children[1].children;

    var floatType = $('#float-type')[0];
    var listType = floatType.children[0].children[1].children;

    var floatPlatform = $('#float-platform')[0];
    var listPlatform = floatPlatform.children[0].children[1].children;

    var floatBank = $('#float-bank')[0];
    var listBank = floatBank.children[0].children[1].children;

    var floatAmount = $('#float-amount')[0];

    var floatWhatsApp = $('#float-phone')[0];

    // const myModal = new bootstrap.Modal('#myModal', {'keyboard': false});
    // console.log(myModal);


    var table = $('#datatables').DataTable({
        "dom": "<'d-flex justify-content-start'l>"+
        "<'d-flex justify-content-start text-start'i>"+
        "<'table-responsive my-2't>"+
        "rp",
        "serverSide": true,
        "processing": true,
        "pagingType": "numbers",
        "columnDefs": [
            {
                "targets": 0,
                "className": "text-center"
            },
            {
                "targets": 6,
                "searchable": false,
                "orderable": false
            }
        ],
        "select": {
            "selector": 'td:first-child',
            "blurable": true
        },
        "buttons": [
            'colvis',
            'excel',
            'print'
        ],
        "ajax" : {
            "url"   : "admin-ajax.php",
            "type"  : "POST",
            "dataSrc": "data",
            "data"  : {
                action  : 'josh_table_slip',
                date_start : () => { return dateStart },
                date_end : () => { return dateEnd },
                filter: () => { return FilterApply1.payload }
            }
        },
        "columns"   : [
            { "data" : "no_sq" },
            { "data" : "relawan" },
            { "data" : "given_date" },
            { "data" : "program" },
            { "data" : "type" },
            { "data" : "platform" },
            { "data" : "bukti_tf" },
            { "data" : "amount" },
            { "data" : "bank" },
            { "data" : "transfer_date" },
            { "data" : "whatsapp" },
            { "data" : "remove" }
        ],
        "lengthMenu": [
            [ 10, 25, 50, 100, -1 ],
            [ '10', '25', '50', '100', 'All' ]
        ],
        "searchDelay" : 2000,
        "buttons" : [ 'copy', 'csv', 'excel'],
        "createdRow": function( row, data, dataIndex, cells ) {
            /**
             * ROW
             */
            $(row).attr('db-id', data.no);
            
            /**
             * Field 0 : ID
             */
            $(cells[0]).attr('cell-name', 'no');

            /**
             * Field 1 : Relawan
             */
            $(cells[1]).attr('field', 'relawan');
            $(cells[1]).addClass('abi-click');

            $(cells[1].children[0]).click(function (e) { 
                let bottom = e.target.getBoundingClientRect();

                changeRelawan1.oldData = e.target.innerText;
                changeRelawan1.idBefore = $(cells[1]).parent()[0].attributes[0].value;
                
                $(floatRelawan).css('top', window.scrollY + bottom.bottom - 15);
                $(floatRelawan).css('left', bottom.left - 65);
                $(floatRelawan).css('display', 'block');
                e.stopPropagation();
                // if( $('body').hasClass('folded') ) {
                //     $(floatRelawan).css('top', window.scrollY + bottom.bottom - 45);
                //     $(floatRelawan).css('left', bottom.left - 65);
                //     $(floatRelawan).css('display', 'block');
                //     e.stopPropagation();
                // } else {
                //     $(floatRelawan).css('top', window.scrollY + bottom.bottom - 45);
                //     $(floatRelawan).css('left', bottom.left - 190);
                //     $(floatRelawan).css('display', 'block');
                //     e.stopPropagation();
                // }
            }, );

            /**
             * Field 2 : Given Date
             */
            $(cells[2]).addClass('abi-click');
            $(cells[2]).attr('field', 'given_date');

            $(cells[2].children[0]).daterangepicker({
                "singleDatePicker": true,
                "startDate": data.given_date_f
            }, function(start, end, label) {

                changeGivenDate1.idBefore = $(cells[2]).parent()[0].attributes[0].value;
                changeGivenDate1.oldData = cells[2].innerText;
                changeGivenDate1.newData = start.format('YYYY-MM-DD');
                changeGivenDate1.run();
            });

            /**
             * Field 3 : Program
             */
            $(cells[3]).addClass('abi-click');
            $(cells[3]).attr('field', 'program');

            $(cells[3].children[0]).click(function (e) { 
                let bottom = e.target.getBoundingClientRect();

                changeProgram1.oldData = e.target.innerText;
                changeProgram1.idBefore = $(cells[1]).parent()[0].attributes[0].value;
                
                $(floatProgram).css('top', window.scrollY + bottom.bottom - 15);
                $(floatProgram).css('left', bottom.left - 65);
                $(floatProgram).css('display', 'block');
                e.stopPropagation();
            }, );

            /**
             * Field 4 : Type
             */
            $(cells[4]).addClass('abi-click');
            $(cells[4]).attr('field', 'type');

            $(cells[4].children[0]).click(function (e) { 
                let bottom = e.target.getBoundingClientRect();
                // console.log(e.target.attributes[0]);    //---field or column
                // console.log(e.target.attributes[1]);    //---db-id
                // console.log($(e.target).parent()[0].attributes[0].value);    //parent db-id!
                // console.log(e.target.innerText);        //---value

                changeType1.oldData = e.target.innerText;
                changeType1.idBefore = $(cells[1]).parent()[0].attributes[0].value;
                
                $(floatType).css('top', window.scrollY + bottom.bottom - 15);
                $(floatType).css('left', bottom.left - 65);
                $(floatType).css('display', 'block');
                e.stopPropagation();
            }, );


            /**
             * Field 5 : Platform
             */
            $(cells[5]).addClass('abi-click');
            $(cells[5]).attr('field', 'platform');

            $(cells[5].children[0]).click(function (e) { 
                let bottom = e.target.getBoundingClientRect();

                changePlatform1.oldData = e.target.innerText;
                changePlatform1.idBefore = $(cells[1]).parent()[0].attributes[0].value;
                
                $(floatPlatform).css('top', window.scrollY + bottom.bottom - 15);
                $(floatPlatform).css('left', bottom.left - 65);
                $(floatPlatform).css('display', 'block');
                e.stopPropagation();
            }, );

            /**
             * Field 6 : Image
             */
            $(cells[6]).addClass('image');
            $(cells[6]).attr('cell-name', 'bukti_tf');
            

            /**
             * Field 7 : Amount
             */
            $(cells[7]).addClass('abi-click');
            $(cells[7]).attr('field', 'amount');

            $(cells[7].children[0]).click(function (e) { 
                let bottom = e.target.getBoundingClientRect();

                changeAmount1.oldData = e.target.innerText;
                changeAmount1.idBefore = $(cells[1]).parent()[0].attributes[0].value;
                $('#float-amount-input').val(e.target.innerText);
                $('#float-amount-input').focus();
                changeAmount1.newData = e.target.innerText;
                
                $(floatAmount).css('top', window.scrollY + bottom.bottom - 15);
                $(floatAmount).css('left', bottom.left - 65);
                $(floatAmount).css('display', 'block');
                e.stopPropagation();
            }, );
            

            /**
             * Field 8 : Bank Option
             */
            $(cells[8]).addClass('abi-click');
            $(cells[8]).attr('field', 'bank');

            $(cells[8].children[0]).click(function (e) { 
                let bottom = e.target.getBoundingClientRect();

                changeBank1.oldData = e.target.innerText;
                changeBank1.idBefore = $(cells[1]).parent()[0].attributes[0].value;
                
                $(floatBank).css('top', window.scrollY + bottom.bottom - 15);
                $(floatBank).css('left', bottom.left - 65);
                $(floatBank).css('display', 'block');
                e.stopPropagation();
            }, );

            /**
             * Field 9 : Transfer Date
             */
            $(cells[9]).addClass('abi-click');
            $(cells[9]).attr('field', 'transfer_date');

            $(cells[9].children[0]).daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "startDate": data.transfer_date_f
            }, function(start, end, label) {

                changeTFDate1.idBefore = $(cells[2]).parent()[0].attributes[0].value;
                changeTFDate1.oldData = cells[2].innerText;
                changeTFDate1.newData = start.format('YYYY-MM-DD');
                changeTFDate1.run();
            });
            

            /**
             * WhatsApp
             */
            $(cells[10]).addClass('abi-click');
            $(cells[10]).attr('field', 'whatsapp');

            $(cells[10].children[0]).click(function (e) { 
                let bottom = e.target.getBoundingClientRect();
                
                changeWhatsApp1.oldData = e.target.innerText;
                changeWhatsApp1.idBefore = $(cells[1]).parent()[0].attributes[0].value;
                $('#float-phone-input').val(e.target.innerText);
                $('#float-phone-input').focus();
                changeWhatsApp1.newData = e.target.innerText;
                
                $(floatWhatsApp).css('top', window.scrollY + bottom.bottom - 15);
                $(floatWhatsApp).css('left', bottom.left - 65);
                $(floatWhatsApp).css('display', 'block');
                e.stopPropagation();
            }, );


            /**
             * Action Delete
             * @since 31 Mar 2023
             */
            $(cells[11]).addClass('abi-click');
            var span = cells[11].querySelector('span');

            span.style.cursor = 'pointer';
            span.style.textDecoration = 'none';

            span.classList.add('badge', 'rounded-pill', 'text-bg-danger');
            $(span).click(function (e) { 
                e.preventDefault();
                let id = $(e.target.parentElement.parentElement).attr('db-id')
                let img1 = $(e.target.parentElement.parentElement.querySelector('.slip-tf')).attr('src')
                let img2 = $(e.target.parentElement.parentElement.querySelector('.slip-tf')).attr('abi-data')

                let data = {
                    action: 'joshfunction_table_delete',
                    id: id,
                    img1: img1,
                    img2: img2
                }
                
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Hal ini tidak dapat di undo atau kembalikan.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya Hapus',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {

                        return $.post('admin-ajax.php', data, (data, textStatus, jqXHR) => {
                            if( data.response === 'success' ) {
                                // console.log('sucess!')
                                table.ajax.reload( null, false )
                                return 'josh_success'
                            } else {
                                Swal.showValidationMessage(`Delete failed: ${data.message}`)
                                return 'not success'
                            }
                        })
                    }
                })
                .then((result) => {
                    // console.log(result)
                    if( result.value.response === 'success' ) {
                        Swal.fire({
                            title: 'Success',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true
                        })
                    }
                })
            });


        }
    }).on('xhr.dt', (e, settings, json, xhr ) => {
        console.log(json)

        $('#donation-value').text(json.value_donation);
        $('#donors-total').text(json.recordsFiltered);
    })

    /**
     * Click confirm image to perform open modal
     */
    $(document).on('click', '.slip-tf', function(e) { 
        
        var div2 = document.createElement('div');
        div2.id = 'img-popup-box';
        var img2 = document.createElement("img");
        img2.src = e.target.attributes["abi-data"].textContent;
        img2.id = 'img-popup';
        img2.classList.add('img-fluid', 'mx-auto', 'd-block', 'shadow');
        div2.appendChild(img2);

        bootbox.dialog({
            message: div2,
            size: 'large',
            centerVertical: true,
            scrollable: false,
            onHidden: function(e) {
                // console.log('it is closed!');
            }
        });
    })

    /**
     * Close button from modal
     */
    $('.model-header .icon').click(function close_button(e) { 
        e.preventDefault();
        $('.model').removeClass('active');
        $('.model-body').html('');
        $('.model-body').append(heartLoading);
        // $('#img-popup').remove();
        $('#heart-box').removeClass('hidden');
        $('html, body').css('overflow', 'auto');
    });

    /**
     * Click anywhere to close popup
     */
    $('body').click(function(event) {
        let target = $(event.target);
        var floatRelawanDisplay = $('#float-relawan')[0].style.display;
        var floatProgramDisplay = $('#float-program')[0].style.display;
        var floatTypeDisplay = $('#float-type')[0].style.display;
        var floatPlatformDisplay = $('#float-platform')[0].style.display;
        var floatBankDisplay = $('#float-bank')[0].style.display;
        var floatAmountDisplay = $('#float-amount')[0].style.display;
        var floatWhatsAppDisplay = $('#float-phone')[0].style.display;

        if( !target.closest(floatRelawan).length && !$(target).is(floatRelawan) && floatRelawanDisplay == 'block') {
            // changeRelawan1.idBefore = undefined;
            // changeRelawan1.oldData = undefined;
            changeRelawan1.clearProp();
            // console.log(changeRelawan1);
            $(floatRelawan).css('display', 'none');
        }

        else if ( !target.closest(floatProgram).length && !$(target).is(floatProgram) && floatProgramDisplay == 'block') {
            changeProgram1.clearProp();
            // console.log(changeProgram1);
            $(floatProgram).css('display', 'none');
        }

        else if ( !target.closest(floatType).length && !$(target).is(floatType) && floatTypeDisplay == 'block') {
            changeType1.clearProp();
            // console.log(changeType1);
            $(floatType).css('display', 'none');
        }

        else if ( !target.closest(floatPlatform).length && !$(target).is(floatPlatform) && floatPlatformDisplay == 'block') {
            changePlatform1.clearProp();
            // console.log(changePlatform1);
            $(floatPlatform).css('display', 'none');
        }
        
        else if ( !target.closest(floatBank).length && !$(target).is(floatBank) && floatBankDisplay == 'block') {
            changeBank1.clearProp();
            // console.log(changeBank1);
            $(floatBank).css('display', 'none');
        }
        
        else if ( !target.closest(floatAmount).length && !$(target).is(floatAmount) && floatAmountDisplay == 'block') {
            changeAmount1.clearProp();
            // console.log(changeAmount1);
            $(floatAmount).css('display', 'none');
        }
        
        else if ( !target.closest(floatWhatsApp).length && !$(target).is(floatWhatsApp) && floatWhatsAppDisplay == 'block') {
            changeWhatsApp1.clearProp();
            // console.log(changeWhatsApp1);
            $(floatWhatsApp).css('display', 'none');
        }

    });

    $('#amount-cancel').click(function (e) { 
        e.preventDefault();
        changeAmount1.clearProp();
        // console.log(changeAmount1);
        $(floatAmount).css('display', 'none');
    });
    
    $('#phone-cancel').click(function (e) { 
        e.preventDefault();
        changeWhatsApp1.clearProp();
        // console.log(changeWhatsApp1);
        $(floatWhatsApp).css('display', 'none');
    });


    /**
     * Date Picker
     */
    $('#date-picker').daterangepicker({
        "autoApply": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "linkedCalendars": true,
        "showCustomRangeLabel": false,
        "autoUpdateInput": false,
        "alwaysShowCalendars": true,
        "startDate": moment().startOf('month'),
        "endDate": moment(),
        "opens": "left"
    }, function(start, end, label) {
        
        if( label == null) {
            var label1 = start.format('DD MMM YYYY') + ' - ' + end.format('DD MMMM YYYY');
        } else { label1 = label;}
        $('#date-text').text(label1);

        dateStart = start.format('YYYY-MM-DD');
        dateEnd = end.format('YYYY-MM-DD');

        table.ajax.reload( null, false );   //not reset paging
    });

    $('#date-picker').on('show.daterangepicker', (ev, picker) => {
        $('.icon-drop').addClass('active');
    })

    $('#date-picker').on('hide.daterangepicker', (ev, picker) => {
        $('.icon-drop').removeClass('active');
    })


    /**
     * Relawan Pop-up Click Listener
     */
    for (let index = 0; index < listRelawan.length; index++) {
        const element = listRelawan[index];

        $(element).click(function (e) { 
            e.preventDefault();
            changeRelawan1.newData = e.target.innerText;
            changeRelawan1.run();
            $(floatRelawan).css('display', 'none');
        });
    }

    /**
     * Program Pop-up Click Listener
     */
    for ( let index = 0; index < listProgram.length; index++) {
        const element = listProgram[index];

        $(element).click(function (e) { 
            e.preventDefault();
            changeProgram1.newData = e.target.innerText;
            changeProgram1.run();
            $(floatProgram).css('display', 'none');
        });
    }

    /**
     * Type Pop-up Click Listener
     */
    for ( let index = 0; index < listType.length; index++) {
        const element = listType[index];

        $(element).click(function (e) { 
            e.preventDefault();
            changeType1.newData = e.target.innerText;
            changeType1.run();
            $(floatType).css('display', 'none');
        });
    }

    /**
     * Platform Pop-up Click Listener
     */
    for ( let index = 0; index < listPlatform.length; index++) {
        const element = listPlatform[index];

        $(element).click(function (e) { 
            e.preventDefault();
            changePlatform1.newData = e.target.innerText;
            changePlatform1.run();
            $(floatPlatform).css('display', 'none');
        });
    }
    
    /**
     * Bank Pop-up Click Listener
     */
    for ( let index = 0; index < listBank.length; index++) {
        const element = listBank[index];

        $(element).click(function (e) { 
            e.preventDefault();
            changeBank1.newData = e.target.innerText;
            changeBank1.run();
            $(floatBank).css('display', 'none');
        });
    }

    /**
     * Input Rupiah Amount Pop-up
     */
    var formatted = new Intl.NumberFormat("id-ID", {
        style: 'decimal',
    });

    $('#float-amount-input').keyup(function (e) { 
        let value = e.target.value;
        let valueReg = value.replace(/\D+/g,'');
        valueReg = parseInt(valueReg);

        if ( valueReg <= 100000000) {
            let clean = formatted.format(valueReg);
            $('#float-amount-input').val('Rp '+clean);
        } else {
            $('#float-amount-input').val(0);
        }

        changeAmount1.newData = valueReg;
    });

    /**
     * Apply Button on Amount Float Listener
     */
    $('#amount-apply').click(function (e) { 
        e.preventDefault();
        changeAmount1.run();
        $(floatAmount).css('display', 'none');
    });
    
    $('#float-amount-input').keypress( (e) => {
        if( e.keyCode == 13) {
            e.preventDefault();
            changeAmount1.run();
            $(floatAmount).css('display', 'none');
        }
    })

    /**
     * Input Phone Pop-up
     */
    $('#float-phone-input').keyup(function (e) { 
        let value = e.target.value;
        let valueReg = value.replace(/\D/g, '');

        // replace first 62
        if( valueReg.startsWith('62') ) {
            valueReg = '0' + valueReg.substring(2)
        }

        if ( valueReg <= 99999999999999) {
            $('#float-phone-input').val(valueReg);
        } else {
            $('#float-phone-input').val(0);
        }

        changeWhatsApp1.newData = valueReg;
    });

    /**
     * Apply Button on Phone Float Listener
     */
    $('#phone-apply').click(function (e) { 
        e.preventDefault();
        changeWhatsApp1.run();
        $(floatWhatsApp).css('display', 'none');
    });
    
    $('#float-phone-input').keypress( (e) => {
        if( e.keyCode == 13) {
            e.preventDefault();
            changeWhatsApp1.run();
            $(floatWhatsApp).css('display', 'none');
        }
    })


    /**
     * Custom Input for Search The Table
     */
    var searchBefore = '';
    var intervalId = window.setInterval( () => {
        if( table.search() != searchBefore) {
            searchBefore = table.search();
            table.ajax.reload( null, false);    //not reset paging
            // console.log('reloaded!');
        }
    }, 2000);
    
    $('#search-table').on('keyup', (e)=> {
        table.search(e.target.value);
    })

    
    /**
     * Relawan Filter (Inside Filter Dialog)
    */
   $('#select-relawan').selectivity({
        items: jsonReady.cs,
        inputType: 'Multiple',
        tokenSeparators: [' ']
    });
    // console.log(jsonReady.cs)
    /**
     * Program Filter (Inside Filter)
     */
    $('#select-program').selectivity({
         items: jsonReady.program,
         inputType: 'Multiple',
         tokenSeparators: [' ']
     });
 
    /**
     * Type Filter (Inside Filter)
     */
    $('#select-type').selectivity({
        items: jsonReady.type,
        inputType: 'Multiple',
        tokenSeparators: [' ']
    });

    /**
     * Platform Filter (Inside Filter)
     */
    $('#select-platform').selectivity({
        items: jsonReady.platform,
        inputType: 'Multiple',
        tokenSeparators: [' ']
    });

    /**
     * Bank Filter (Inside Filter)
     */
    $('#select-bank').selectivity({
        items: jsonReady.bank,
        inputType: 'Multiple',
        tokenSeparators: [' ']
    });

    /**
     * Transfer Date Filter (Inside Filter)
     */
    $('#date-picker-tf').daterangepicker({
        "linkedCalendars": true,
        "showCustomRangeLabel": false,
        "autoUpdateInput": false,
        "alwaysShowCalendars": true,
        "startDate": moment().startOf('month'),
        "endDate": moment(),
        "maxDate": moment(),
        "opens": "center"
    }, function(start, end, label) {
        
        if( label == null) {
            var label1 = start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY');
        } else { label1 = label;}
        $('#date-picker-tf-text').text(label1);

        let dateStart = start.format('YYYY-MM-DD');
        let dateEnd = end.format('YYYY-MM-DD');

        let cleanDate = {'from': dateStart, 'end': dateEnd};

        FilterApply1.transferDate = cleanDate;

    });

    $('#date-picker').on('show.daterangepicker', (ev, picker) => {
        $('.icon-drop').addClass('active');
    })

    $('#date-picker').on('hide.daterangepicker', (ev, picker) => {
        $('.icon-drop').removeClass('active');
    })


    /**
     * Filter Dialog Pop-up
     */
    $('#btn-filter').click(function (e) { 
        e.preventDefault();
        $('#filter-dialog').dialog('open');
        $('#filter-dialog').css('display', 'block');
    });    

    
    $('#filter-dialog').dialog({
        autoOpen: false,
        modal: true,
        draggable: false,
        resizable: false,
        minWidth: 350,
        maxWidth: 600,
        closeText: 'hide',
        show: {
            effect: "fade", duration: 100
        },
        hide: {
            effect: "fade", duration: 100
        },
        buttons: [
            {
                text: 'Ok',
                icon: 'ui-icon-heart',
                click: () => {
                    

                    $('#filter-dialog').dialog('close');
                    $('#filter-dialog').css('display', 'none');
                    // table.ajax.reload();
                    FilterApply1.run();
                }
            }
        ]
    })
});
var dateStart = '';
var dateEnd = '';