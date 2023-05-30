const a = document.querySelector('input[name="owner"]:checked');
console.log(a)
const b = a.value;
console.log(b)

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
 * Duta Section
 */
{
    // Duta Name
    $('#duta-name').selectivity({
        items: duta,
        allowClear: true,
        placeholder: 'nama duta'
    })

    $('#duta-name').change(function (e) { 
        e.preventDefault();
        
        urlBuilder.duta('duta', e.delegateTarget.selectivity._data);
        console.log(e.delegateTarget.selectivity._data);
    });
    
    // target page
    $('#target-page-duta').selectivity({
        items: target,
        allowClear: true,
        placeholder: 'halaman tujuan'
    });

    $('#target-page-duta').change(function (e) { 
        e.preventDefault();
        
        urlBuilder.duta('target', e.delegateTarget.selectivity._data);
    });
}

/**
 * CS Section
 */
{
    // Duta Name
    $('#cs-name').selectivity({
        items: [
            {id: 1, text: 'Husna', value: 'husna'},
            {id: 2, text: 'Meisya', value: 'meisya'},
            {id: 3, text: 'Safina', value: 'safina'},
            {id: 4, text: 'Fadhilah', value: 'fadhilah'}
        ],
        allowClear: true,
        placeholder: 'nama cs'
    })

    $('#cs-name').change(function (e) { 
        e.preventDefault();
        
        urlBuilder.cs('cs', e.delegateTarget.selectivity._data);
    });
    
    // target page
    $('#target-page-cs').selectivity({
        items: target,
        allowClear: true,
        placeholder: 'halaman tujuan'
    });

    $('#target-page-cs').change(function (e) { 
        e.preventDefault();
        
        urlBuilder.cs('target', e.delegateTarget.selectivity._data);
    });
}

/**
 * CC Section
 */
{
    // CC Name
    $('#cc-name').selectivity({
        items: [{id: 1, text: 'bla'}],
        allowClear: true,
        placeholder: 'nama cc'
    });

    $('#cc-name').change(function (e) { 
        e.preventDefault();
        
        urlBuilder.cc('cc', e.delegateTarget.selectivity._data);
    });
    
    // target page
    $('#target-page-cc').selectivity({
        items: target,
        allowClear: true,
        placeholder: 'halaman tujuan'
    });

    $('#target-page-cc').change(function (e) { 
        e.preventDefault();
        
        urlBuilder.cc('target', e.delegateTarget.selectivity._data);
    });
}

/**
 * Copy (to clipboard)
 */
$('#copy-btn').click(function (e) { 
    e.preventDefault();
    
    navigator.clipboard.writeText(urlBuilder.results).then(() => {
        $('#copied-success').show();
        setTimeout(() => {
            $('#copied-success').slideUp('slow');
        }, 1000);
    }, (err) => {
        console.error('Could not copy to clipboard: '+err)
    })
});

class Url_Builder {
    constructor() {
        this.target = document.getElementById('result-copy');
    }

    /**
     * 
     * @param {string} d MODE: 'duta' & 'target'
     * @param {object||null} e
     */
    duta(d, e) {
        if(d === 'duta') {
            this._duta(e);
        }
        else if(d === 'target') {
            this._duta_(e);
        }
    }

    _duta(e) {
        if(e == null) {
            this.valDuta = null;
        } else {
            this.valDuta = e.value;
        }

        this.generator('duta');
    }

    _duta_(e) {
        if(e == null) {
            this.valDutaTarget = null;
        } else {
            this.valDutaTarget = e.value;
        }

        this.generator('duta');
    }

    /**
     * 
     * @param {string} d MODE: 'cs' & 'target'
     * @param {object||null} e
     */
    cs(d, e) {
        if(d === 'cs') {
            this._cs(e);
        }
        else if(d === 'target') {
            this._cs_(e);
        }
    }

    _cs(e) {
        if(e == null) {
            this.valCs = null;
        } else {
            this.valCs = e.value;
        }

        this.generator('cs');
    }

    _cs_(e) {
        if(e == null) {
            this.valCsTarget = null;
        } else {
            this.valCsTarget = e.value;
        }

        this.generator('cs');
    }

    /**
     * 
     * @param {string} d MODE: 'cc' & 'target'
     * @param {object||null} e
     */
    cc(d, e) {
        console.log(d, e);
    }

    /**
     * 
     * @param {*} a 
     */
    generator(a) {
        if(a === 'duta') {
            if(this.valDuta == null) {
                this.results = '';
            } else {
                if(this.valDutaTarget == null) {
                    this.results = 'https://ympb.or.id/?ref=' + this.valDuta;
                } else {
                    this.results = 'https://ympb.or.id' + this.valDutaTarget + '?ref=' + this.valDuta;
                }
            }
        }
        else if(a === 'cs') {
            if(this.valCs == null) {
                this.results = '';
            } else {
                if(this.valCsTarget == null) {
                    this.results = 'https://ympb.or.id/?ref=' + this.valCs;
                } else {
                    this.results = 'https://ympb.or.id' + this.valCsTarget + '?ref=' + this.valCs;
                }
            }
        }

        $(this.target).text(this.results); console.log(this.valDuta)
    }
}

let urlBuilder = new Url_Builder;