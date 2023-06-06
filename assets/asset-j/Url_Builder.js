class Url_Builder {
    constructor() {
        this.target = document.getElementById('result-copy');
        this.target_cc = document.getElementById('result-copy-cc');
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
     * @param {string} d MODE: 'usource', 'ucontent', 'ucampaign', 'target', & 'short link'
     * @param {object||null} e
     */
    cc(d, e) {
        if(d === 'usource') {
            this.usource = (e == null) ? null : e;
        }
        else if(d === 'ucontent') {
            this.ucontent = (e == null) ? null : e;
        }
        else if(d === 'ucampaign') {
            this.ucampaign = (e == null) ? null : e;
        }
        else if(d === 'target') {
            this.target = (e == null) ? null : e;
        }
        else if(d === 'short') {
            this.short = (e == null) ? null : e;
        }
    }

    cc_sumbit() {
        if(this.usource == undefined) {
            console.error('Please fill UTM Source!');
            Toast.fire({
                title: 'Fail! Please complete first.',
                text: 'Please fill UTM Source!',
                icon: 'error'
            });

            return;
        }
        else if(this.ucontent == undefined) {
            console.error('Please fill UTM Content!');
            Toast.fire({
                title: 'Fail! Please complete first.',
                text: 'Please fill UTM Content!',
                icon: 'error'
            });

            return;
        }
        else if(this.target == undefined) {
            console.error('Please fill Target Page!');
            Toast.fire({
                title: 'Fail! Please complete first.',
                text: 'Please fill Target Page!',
                icon: 'error'
            });

            return;
        }
        
        let shortLink = $('#cc-short-link').val();
        if(shortLink == '') {
            console.error('Short link should not empty!');
            Toast.fire({
                title: 'Oops!',
                text: 'Short link should not empty!',
                icon: 'error'
            });

            return;
        }

        let summary = {
            uSource: this.usource,
            uContent: this.ucontent,
            uCampaign: (this.ucampaign == undefined) ? null : this.ucampaign,
            target: this.target,
            sLink: shortLink
        };
        // console.log(summary);

        Swal.fire({
            title: 'New Link Will be Created',
            text: 'Please review your submission, are you sure?',
            showCancelButton: true,
            confirmButtonText: 'Create',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                let data_ = {
                    action: 'jh_submit_cc_link',
                    payload: summary
                };

                return $.post(ajaxLink, data_,
                    function (data, textStatus, jqXHR) {
                        if(data.status === 'success') {
                            Swal.fire({
                                title: 'success',
                                icon: 'success',
                                timer: 2500,
                                timerProgressBar: true
                            })
                            .then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Failed!',
                                text: 'Error occured: '+data.messages,
                                icon: 'error'
                            });
                        }
                    }
                );
            }
        });
    }

    /**
     * 
     * @param {*} a 
     */
    generator(a) {
        console.log(a);
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

    generator_cc(a) {
        console.log(a)
        let parts = a.parts;
        $('#cc-usource').selectivity('data', parts.uSource);
        $('#cc-ucontent').selectivity('data', parts.uContent);
        if(parts.uCampaign == '') {
            $('#cc-ucampaign').selectivity('clear');
        } else {
            $('#cc-ucampaign').selectivity('data', parts.uCampaign);
        }
        $('#target-page-cc').selectivity('data', parts.target);

        $('#result-copy-cc').text(a.value);
        this.results_cc = a.value;
    }
}
