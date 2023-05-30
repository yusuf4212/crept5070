class CardSelectMonth {
    constructor(c) {

        try {
            this.target = c.target;
            this.option = c.option;
        } catch (error) {
            console.log('An error occured:', error);
        }

        this.singlePick = ( 'singlePick' in c ) ? c.singlePick : false;

        this.customSelect = document.createElement("div");
        this.customSelect.classList.add("jh-card-select");

        this.option.forEach((o) => {
            const itemElement = document.createElement("div");

            itemElement.classList.add("select__item");
            itemElement.classList.add("select__item_"+o.id);
            itemElement.textContent = o.text;
            this.customSelect.appendChild(itemElement);

            if (o.primary) {
                this.selectStart = o
                this._select(itemElement);
            }

            itemElement.addEventListener("click", () => {
                if (
                    // this.singlePick &&
                    // ! this.selectEnd == undefined &&
                    itemElement.classList.contains("select__item--selected")
                ) {
                    this.selectStart = undefined;
                    this.selectEnd = undefined;
                    this._deselect(itemElement, o);
                } else {
                    this._select(itemElement, o);
                }
            });
        });

        this.target.appendChild(this.customSelect);
    }

    _select(itemElement, o) {

        if( this.selectEnd != undefined ) {
            this.customSelect.querySelectorAll('.select__item').forEach((el) => {
                el.classList.remove('select__item--selected');
                el.classList.remove('item_in_range');
            });
            this.selectStart = o;
            itemElement.classList.add('select__item--selected');            
            this.selectEnd = undefined;
        } else {
            this.selectEnd = o;
            itemElement.classList.add("select__item--selected");
            if(
                this.selectStart != undefined && this.selectEnd != undefined
            ) {
                if(this.selectStart.id < this.selectEnd.id) {
                    for(let i=this.selectStart.id+1; i < this.selectEnd.id; i++) {
                        var itemE = this.customSelect.querySelector(".select__item_"+i);
                        itemE.classList.add('item_in_range');
                    }
                } else if(this.selectStart.id > this.selectEnd.id) {
                    for(let i=this.selectStart.id-1; i > this.selectEnd.id; i--) {
                        var itemE = this.customSelect.querySelector('.select__item_'+i);
                        itemE.classList.add('item_in_range');
                    }
                }
            }
        }

    }

    _deselect(itemElement, option) {

        itemElement.classList.remove("select__item--selected");

    }

    _getLastDay(yearMonth) {
        const [year, month] = yearMonth.split('-');
        const lastDay = new Date(year, month, 0).getDate();
        return yearMonth+'-'+lastDay;
    }

    getResult() {
        if(this.selectEnd == undefined) {
            return {
                'start':this.selectStart.data+'-01',
                'end':this._getLastDay(this.selectStart.data)
            }
        } else {
            if(this.selectStart.id < this.selectEnd.id) {
                return {
                    'start':this.selectEnd.data+'-01',
                    'end':this._getLastDay(this.selectStart.data)
                }
            } else {
                return {
                    'start': this.selectStart.data+'-01',
                    'end': this._getLastDay(this.selectEnd.data)
                }
            }
        }
    }
}