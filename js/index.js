const nahoru = document.querySelector('#nahoru');
nahoru.addEventListener("click", (event) => {
    window.scrollTo({
        left: 0,
        top: 0,
        behavior: "smooth"
    });
});

const header = document.querySelector('header');

window.addEventListener("scroll", (event) => {
    // console.log(window.scrollY);
    const poziceHeaderu = header.getBoundingClientRect(); // vrati parametry kde je umisteny    
    // console.log(poziceHeaderu);  
    if (window.scrollY > poziceHeaderu.bottom) {
        nahoru.classList.add("zobrazit");
    } else {
        nahoru.classList.remove('zobrazit');
    }
});

const navContainer = document.querySelector("#nav_container");
const burgerBtn = document.querySelector(".menu__btn");
const burgerBtnImg = burgerBtn.querySelector("img");

burgerBtn.addEventListener("click", (e) => {
    if (navContainer.classList.contains('opened')) {
        navContainer.classList.remove('opened');
        burgerBtnImg.src = "fontawesome/svgs/solid/bars.svg";
        burgerBtnImg.ariaLabel = "otevřít menu";
    } else {
        navContainer.classList.add('opened');
        burgerBtnImg.src = "fontawesome/svgs/solid/x.svg";
        burgerBtnImg.ariaLabel = "zavřít menu";
    }
})