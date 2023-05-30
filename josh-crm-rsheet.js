class RSheet {
    constructor(e) {
        this.element = document.getElementsByClassName(e);
    }

    open() {
        $(this.element).css('width', '1100px');
        $('html, body').css('overflow', 'hidden');
    }

    close() {
        $(this.element).css('width', '0px');
        $('html, body').css('overflow', 'auto');
    }
}