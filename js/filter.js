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
  
 