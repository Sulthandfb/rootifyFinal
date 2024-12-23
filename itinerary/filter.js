document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tripForm');
    const steps = Array.from(document.querySelectorAll('.form-step'));
    const nextButtons = Array.from(document.querySelectorAll('.next-btn'));
    const prevButtons = Array.from(document.querySelectorAll('.prev-btn'));
    const progressSteps = Array.from(document.querySelectorAll('.progress-bar .step'));

    let currentStep = 0;

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
        });
        progressSteps.forEach((step, index) => {
            step.classList.toggle('active', index <= stepIndex);
        });
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentStep++;
            showStep(currentStep);
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentStep--;
            showStep(currentStep);
        });
    });
});

document.addEventListener('scroll', function () {
    const nav = document.querySelector('nav');
    if (window.scrollY > 0) { // Sesuaikan nilai ini dengan kebutuhan Anda
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
});

const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", () => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

navLinks.addEventListener("click", () => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

const scrollRevealOption = {
  distance: "50px",
  origin: "bottom",
  duration: 1000,
};

// header container
ScrollReveal().reveal(".header__container p", {
  ...scrollRevealOption,
});

ScrollReveal().reveal(".header__container h1", {
  ...scrollRevealOption,
  delay: 500,
});

// about container
ScrollReveal().reveal(".about__image img", {
  ...scrollRevealOption,
  origin: "left",
});

ScrollReveal().reveal(".about__content .section__subheader", {
  ...scrollRevealOption,
  delay: 500,
});

ScrollReveal().reveal(".about__content .section__header", {
  ...scrollRevealOption,
  delay: 1000,
});

ScrollReveal().reveal(".about__content .section__description", {
  ...scrollRevealOption,
  delay: 1500,
});

ScrollReveal().reveal(".about__btn", {
  ...scrollRevealOption,
  delay: 2000,
});

  
 