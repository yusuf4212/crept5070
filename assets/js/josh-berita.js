var x = document.getElementById("josh-readmore");
var y = x.clientHeight;
let height2 = '600px'
x.style.height = height2;

var b = document.getElementById("btn-readmore-josh");
var c = document.getElementById("i-readmore-josh");
var d = document.getElementById("spn-readmore-josh");
var e = document.getElementById("container-readmore-josh");
var f = document.getElementById("spn-readmore-josh");

b.addEventListener('click', function handleClick() {
    const initialText = 'Baca Selengkapnya';

    if (d.textContent.toLowerCase().includes(initialText.toLowerCase())) {
        x.style.height = '100%';
        d.textContent = 'Sembunyikan';
        c.style.rotate = '180deg';
        f.style.animation = 'none';
        e.classList.add("active");

    } else {
        x.style.height = height2;
        d.textContent = initialText;
        c.style.rotate = '0deg';
        f.style.animation = 'josh 1.5s infinite';
        location.hash = '#tab-josh';
        e.classList.remove("active");
    }
});