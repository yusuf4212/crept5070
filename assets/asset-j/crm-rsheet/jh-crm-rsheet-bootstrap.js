class RSheet {
    /**
     * 
     * @param {string} e hold a id of right element (without #)
     * @param {string} f hold a table id element (without #)
     * @param {string} i modal id (with #)
     * @param {object} g hold a list of element with jh-loading class (allData, chart, table)
     * @param {object} h hold an array of allData id element (with #) pair with the object property from AJAX Request
     */
    constructor(e, f, i, g, h) {
        // let e_ = document.getElementById(e);
        this.element = new bootstrap.Offcanvas('#'+e, {keyboard: false});
        // this.modal = new bootstrap.Modal(i);
        this.tableId = f;
        this.loadingElement = g;
        this.allDataIds = h;
        this.placeholder = document.querySelectorAll('#offcanvasRight .placeholder');
    }

    /**
     * 
     */
    _chart() {
        const reqChart = {action: 'josh_crm_chart', donorData: this.donorId};

        this.__chart(reqChart, 'graph', 'line');
    }

    /**
     * initialize chart
     * @param {string} d ajax request body
     * @param {string} t target canvas id (without #)
     * @param {string} c chart type (line, pie, etc)
     */
    __chart(d, t, c) {
        $.post("https://ympb.or.id/wp-admin/admin-ajax.php", d,
                function (data, textStatus, jqXHR) {
                    // console.log(data)

                    let chartX = []
                    let chartY = []
                    let chartY2 = []

                    data.data.map( data => {
                        chartX.push(data.month)
                        chartY.push(data.donate)
                        chartY2.push(data.volume)
                    })
                    
                    const chartData = {
                        labels: chartX,
                        datasets: [
                            {
                                label: 'Donasi',
                                data: chartY,
                                backgroundColor: ['#9cc9f3'],
                                borderColor: ['#5BACF6'],
                                borderWidth: 3,
                                tension: 0.2,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Nilai Donasi',
                                data: chartY2,
                                backgroundColor: ['#ffc4c4'],
                                borderColor: ['#ff6767'],
                                borderWidth: 3,
                                type: 'bar',
                                tension: 0.2,
                                yAxisID: 'y1'
                            }
                        ]
                    }

                    const ctx = document.getElementById(t).getContext('2d')

                    const config = {
                        type: c,
                        data: chartData,
                        options: {
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Riwayat Donasi'
                                }
                            },
                            scales: {
                                y: {
                                    id: 'left-y-axis',
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    title: {
                                        display: true,
                                        text: 'Total Donasi',
                                        color: '#646464'
                                    }
                                },
                                y1: {
                                    id: 'right-y-axis',
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    // grid line settings
                                    grid: {
                                        drawOnChartArea: false // only want the grid lines for one axis to show up
                                    },
                                    title: {
                                        display: true,
                                        text: 'Nilai Donasi (Rp)',
                                        color: '#646464'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Bulan',
                                        color: '#646464'
                                    }
                                }
                            },
                        },
                        plugins: [{
                            beforeInit: (chart) => {
                                $('.summary-metric').removeClass('jh-loading');
                                $('.summary-metric .jh-row.title').show();
                            }
                        }]
                    }

                    RightSheet.chart = new Chart(ctx, config);
                },
                "json"
            );
    }
    
    /**
     * 
     * @param {*} d hold data for identifier donors
     */
    _table() {
        if(this.table == undefined) {
            this.table = $(this.tableId).DataTable({
                "dom": "<'table-responsive my-2't>",
                "serverSide": true,
                "processing": true,
                "ordering": false,
                "ajax" : {
                    "url"   : "/wp-admin/admin-ajax.php",
                    "type"  : "POST",
                    "dataSrc": "data",
                    "data": {action: 'josh_crm_table_2', donorData: () => {return RightSheet.donorId}},
                },
                "columns"   : [
                    { "data" : "tgl", "width": "10%" },
                    { "data" : "news", "width": "90%" },
                    { "data" : "action" }
                ],
                // "responsive": true,
                "createdRow": (row, data, dataIndex, cells) => {
                    /**
                     * Action column
                     */
                    {
                        $('td:eq(2)', row).addClass('jh-action');
                        let _receipt = undefined;

                        if(data.source === 'order') {
                            _receipt = (data.receipt == null) ? true : false;
                        } else if(data.source === 'slip') {
                            _receipt = (data.slipAddress == null) ? true : false;
                        }

                        let detail = '<svg xmlns="http://www.w3.org/2000/svg" class="jh-detail" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>';

                        let receipt = (_receipt) ? '' : '<svg xmlns="http://www.w3.org/2000/svg" class="jh-receipt" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16"><path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/><path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/></svg>';

                        let justify = (_receipt) ? 'justify-content-center' : 'justify-content-between';

                        $('.jh-action', row).html('<div class="d-flex '+justify+'" style="cursor: pointer;">'+
                        detail +
                        receipt +
                        '</div>');

                        $('.jh-action .jh-detail', row).click(function (e) { 
                            e.preventDefault();
                            RightSheet.openModal(data, 'detail');
                        });

                        $('.jh-action .jh-receipt', row).click(function (e) { 
                            e.preventDefault();
                            RightSheet.openModal(data, 'receipt');
                        });
                    }
                }
            }).on('xhr.dt', (e, settings, json, xhr ) => {
                $('.history .jh-body').removeClass('jh-loading jh-loading-medium');
                $('.history .table-wrapper').show();
            });
        } else {
            this.table.ajax.reload();
        }
    }

    /**
     * 
     */
    _donors_data() {
        const req = {action : 'josh_crm_donors', donorData: this.donorId};

        $.post("/wp-admin/admin-ajax.php", req,
            function (data, textStatus, jqXHR) {
                if(data.status == 'success') {
                    RightSheet._hideLoadAllData();

                    const myObj = RightSheet.allDataIds;
                    for(let prop in myObj) {
                        const key = myObj[prop];
                        $(prop).text(data.data[key]);
                    }
                }
            },
            "json"
        );
    }

    _hideLoadAllData() {
        for(let i=0; i < this.placeholder.length; i++) {
            this.placeholder[i].classList.remove('placeholder');
        }
    }

    _loadAllData() {
        this.chart.destroy();
        for(let i=0; i < this.placeholder.length; i++) {
            this.placeholder[i].classList.add('placeholder');
        }

        for(let prop in this.allDataIds) {
            $(prop).text('');
        }

        $('.summary-metric > .jh-row.title').hide();
        $('.summary-metric').addClass('jh-loading');

        $('.history .jh-body').addClass('jh-loading jh-loading-medium');
        $('.history .table-wrapper').hide();
    }

    /**
     * 
     * @param {object} d hold the donor identifier
     */
    open(d) {
        this.element.show();
        this.donorId = d;
        this._chart();
        this._table();
        this._donors_data();
    }

    close() {
        this._loadAllData();
        this.donorId = undefined;
    }

    /**
     * 
     * @param {object} d one row data, from AJAX
     * @param {string} e mode, 'detail' or 'receipt'
     */
    openModal(d, e) {
        // RightSheet.modal.show();
        // let title = (d.source === 'order') ? 'Detail Order' : 'Detail Input Slip';
        let title = '';
        let html = '';

        if(d.source === 'order' && e === 'detail') {
            title = 'Detail Order';
            html = '<div>'+
            '<table class="table" style="font-size: 15px;">'+
            '<tbody style="text-align: start;">'+
            '<tr><td style="width: 35%;">Invoice</td><td>'+d.invoice+'</td></tr>'+
            '<tr><td style="width: 35%;">Nama</td><td>'+d.name+'</td></tr>'+
            '<tr><td style="width: 35%;">Whatsapp</td><td>'+d.whatsapp+'</td></tr>'+
            '<tr><td style="width: 35%;">Program</td><td>'+d.program+'</td></tr>'+
            '<tr><td style="width: 35%;">Nominal</td><td>Rp'+d.nominal+'</td></tr>'+
            '<tr><td style="width: 35%;">Ulang?</td><td>'+d.ulang+'</td></tr>'+
            '<tr><td style="width: 35%;">Email</td><td>'+d.email+'</td></tr>'+
            '<tr><td style="width: 35%;">Doa</td><td>'+d.doa+'</td></tr>'+
            '<tr><td style="width: 35%;">Waktu Order</td><td>'+d.waktu+'</td></tr>'+
            '<tr><td style="width: 35%;">Anonim</td><td>'+d.anonim+'</td></tr>'+
            '<tr><td style="width: 35%;">CS</td><td>'+d.cs+'</td></tr>'+
            '<tr><td style="width: 35%;" title="Waktu FollowUp">Waktu Followup</td><td>'+d.fwp+'</td></tr>'+
            '<tr><td style="width: 35%;">WABA Status</td><td>'+d.waba+'</td></tr>'+
            '<tr><td style="width: 35%;">Ref</td><td>'+d.ref+'</td></tr>'+
            '<tr><td style="width: 35%;">UTM Source</td><td>'+d.utmSource+'</td></tr>'+
            '<tr><td style="width: 35%;">UTM Medium</td><td>'+d.utmMedium+'</td></tr>'+
            '<tr><td style="width: 35%;">UTM Campaign</td><td>'+d.utmCampaign+'</td></tr>'+
            '<tr><td style="width: 35%;">UTM Term</td><td>'+d.utmTerm+'</td></tr>'+
            '<tr><td style="width: 35%;">UTM Content</td><td>'+d.utmContent+'</td></tr>'+
            '<tr><td style="width: 35%;">City</td><td>'+d.city+'</td></tr>'+
            '<tr><td style="width: 35%;">Provider</td><td>'+d.provider+'</td></tr>'+
            '<tr><td style="width: 35%;">OS</td><td>'+d.os+'</td></tr>'+
            '<tr><td style="width: 35%;">IP Address</td><td>'+d.ipAddress+'</td></tr>'+
            '<tr><td style="width: 35%;">Browser</td><td>'+d.browser+'</td></tr>'+
            '<tr><td style="width: 35%;">Mobile?</td><td>'+d.mobile+'</td></tr>'+
            '</tbody>'+
            '</table>'+
            '</div>';
        } else if(d.source === 'order' && e === 'receipt') {
            title = 'Bukti TF';
            html = '<div class="d-flex justify-content-center">'+
            '<img src="'+d.receipt+'" class="img-fluid">'+
            '</div>';
        } else if(d.source === 'slip' && e === 'detail') {
            title = 'Detail Slip';
            html = '<div>'+
            '<table class="table" style="font-size: 15px;">'+
            '<tbody style="text-align: start;">'+
            '<tr><td style="width: 35%;">Nama</td><td>'+d._name+'</td></tr>'+
            '<tr><td style="width: 35%;">Whatsapp</td><td>'+d.whatsapp+'</td></tr>'+
            '<tr><td style="width: 35%;">CS</td><td>'+d.relawan+'</td></tr>'+
            '<tr><td style="width: 35%;">Tgl Dilaporkan CS</td><td>'+d.givenDate+'</td></tr>'+
            '<tr><td style="width: 35%;">Tgl Transfer</td><td>'+d.tfDate+'</td></tr>'+
            '<tr><td style="width: 35%;">Program</td><td>'+d.program+'</td></tr>'+
            '<tr><td style="width: 35%;">Platform</td><td>'+d.platform+'</td></tr>'+
            '<tr><td style="width: 35%;">Nominal</td><td>Rp'+d.nominal+'</td></tr>'+
            '<tr><td style="width: 35%;">Bank</td><td>'+d.bank+'</td></tr>'+
            '<tr><td style="width: 35%;">Oleh</td><td>'+d.inputBy+'</td></tr>'+
            '<tr><td style="width: 35%;">Tgl Input</td><td>'+d.createdDate+'</td></tr>'+
            '</tbody>'+
            '</table>'+
            '</div>';
        } else if(d.source === 'slip' && e === 'receipt') {
            title = 'Bukti TF input Admin';
            html = '<div class="d-flex justify-content-center">'+
            '<img src="'+d.slipAddress+'" class="img-fluid">'+
            '</div>';
        }

        Swal.fire({
            title: title,
            html: html
        })
    }

    closeModal() {

    }
}