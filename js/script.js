window.onscroll = function() {
    scroll = document.documentElement.scrollTop;
    header = document.getElementById("header");

    if (scroll > 20) {
        header.classList.add('nav_mod');
    } else if (scroll < 20) {
        header.classList.remove('nav_mod');
    }
}

document.getElementById("btn_menu").addEventListener("click", mostrar_menu);

menu = document.getElementById("header");
nav = document.getElementById("nav");

// Updated function to close the mobile menu when a link is clicked
function mostrar_menu() {
    nav.classList.toggle('move_nav');
}

// Close the mobile menu when a link is clicked
nav.addEventListener("click", function() {
    nav.classList.remove('move_nav');
});

window.addEventListener("resize", function() {
    if (window.innerWidth > 760) {
        nav.classList.remove('move_nav');
    }
});

 // Detectar cuando la sección esté en el área visible
 const nosotrosSection = document.querySelector('.nosotros');
 const observer = new IntersectionObserver(entries => {
     entries.forEach(entry => {
         if (entry.isIntersecting) {
             // Si la sección está en el área visible, agregar la clase de animación
             entry.target.querySelector('h1').classList.add('animate__backInLeft');
         } else {
             // Si la sección no está en el área visible, quitar la clase de animación
             entry.target.querySelector('h1').classList.remove('animate__backInLeft');
         }
     });
 });

 // Observar la sección
 observer.observe(nosotrosSection);

 // Detectar cuando la sección esté en el área visible
 const infoSection = document.querySelector('.info');
 const observerInfo = new IntersectionObserver(entries => {
     entries.forEach(entry => {
         if (entry.isIntersecting) {
             // Si la sección está en el área visible, agregar la clase de animación
             entry.target.querySelector('h1').classList.add('animate__backInLeft');
         } else {
             // Si la sección no está en el área visible, quitar la clase de animación
             entry.target.querySelector('h1').classList.remove('animate__backInLeft');
         }
     });
 });

 // Observar la sección
 observerInfo.observe(infoSection);