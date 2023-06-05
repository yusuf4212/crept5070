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
        // console.log(d, e);
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

        $(this.target).text(this.results);
    }
}
