class RSheet {
    /**
     * 
     * @param {string} e hold a class of right class element (without .)
     * @param {string} f hold a table id element (without #)
     * @param {object} g hold a list of element with jh-loading class (allData, chart, table)
     * @param {object} h hold an array of allData id element (with #) pair with the object property from AJAX Request
     */
    constructor(e, f, g, h) {
        this.element = document.getElementsByClassName(e);
        this.tableId = f;
        this.loadingElement = g;
        this.allDataIds = h;
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
                "dom": 't',
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
                    { "data" : "action", "render" : (data, type, row, meta) => {
                        return '<span class="jh-action">' + data + '</span>';
                    } }
                ],
                "responsive": true,
            }).on('xhr.dt', (e, settings, json, xhr ) => {
                console.log(json);
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
                    console.log(data);
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
        for(let i=0; i < this.loadingElement.allData.length; i++) {
            this.loadingElement.allData[i].classList.remove('jh-loading');
        }
        $('.owner-contact').removeClass('jh-loading');
    }

    _loadAllData() {
        for(let i=0; i < this.loadingElement.allData.length; i++) {
            this.loadingElement.allData[i].classList.add('jh-loading');
        }
        $('.owner-contact').addClass('jh-loading');

        for(let prop in this.allDataIds) {
            $(prop).text('');
        }

        $('.summary-metric > .jh-row.title').hide();
        $('.summary-metric').addClass('jh-loading');
        this.chart.destroy();

        $('.history .jh-body').addClass('jh-loading jh-loading-medium');
        $('.history .table-wrapper').hide();
    }

    /**
     * 
     * @param {object} d hold the donor identifier
     */
    open(d) {
        $(this.element).css('width', '1100px');
        $('html, body').css('overflow', 'hidden');
        this.donorId = d;
        this._chart();
        this._table();
        this._donors_data();
    }

    close() {
        this._loadAllData();
        this.donorId = undefined;
        $(this.element).css('width', '0px');
        $('html, body').css('overflow', 'auto');
    }
}