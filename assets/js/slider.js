const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

let current = 0;

// Actualiza las clases activas del carrusel para la diapositiva indicada.
function showSlide(index) {
    slides.forEach(slide => slide.classList.remove("active"));
    dots.forEach(dot => dot.classList.remove("active"));

    slides[index].classList.add("active");
    dots[index].classList.add("active");

    current = index;
}

// Reutiliza el avance para los controles manuales y el cambio automático.
function avanzarSlide() {
    showSlide((current + 1) % slides.length);
}

document.querySelector(".next").addEventListener("click", avanzarSlide);
document.querySelector(".prev").addEventListener("click", () => {
    showSlide((current - 1 + slides.length) % slides.length);
});

dots.forEach((dot, index) => {
    dot.addEventListener("click", () => showSlide(index));
});

setInterval(avanzarSlide, 5000);