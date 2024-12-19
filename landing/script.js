document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    navToggle.addEventListener('click', function() {
        navMenu.classList.toggle('active');
    });

    const wrapper = document.querySelector('.destinations-wrapper');
    const cards = document.querySelectorAll('.destination-card');
    let currentIndex = 0;
    const totalCards = cards.length;
    let isAnimating = false;

    function updateCardPositions() {
        cards.forEach((card, index) => {
            let position = index - currentIndex;
            if (position < -2) position += totalCards;
            if (position > 2) position -= totalCards;

            const xPos = position * 280;
            card.style.transform = `translateX(${xPos}px)`;

            card.classList.remove('active', 'semi-active', 'quarter-active');

            if (position === 0) {
                card.classList.add('active');
            } else if (Math.abs(position) === 1) {
                card.classList.add('semi-active');
            } else if (Math.abs(position) === 2) {
                card.classList.add('quarter-active');
            }

            if (Math.abs(position) <= 2) {
                card.style.opacity = '1';
                card.style.visibility = 'visible';
            } else {
                card.style.opacity = '0';
                card.style.visibility = 'hidden';
            }
        });
    }

    function rotateCards(direction) {
        if (isAnimating) return;
        isAnimating = true;

        if (direction === 'right') {
            currentIndex = (currentIndex + 1) % totalCards;
        } else {
            currentIndex = (currentIndex - 1 + totalCards) % totalCards;
        }

        updateCardPositions();

        setTimeout(() => {
            isAnimating = false;
        }, 500);
    }

    let lastScrollTime = 0;
    const scrollCooldown = 250; // milliseconds between scroll events

    wrapper.addEventListener('wheel', (e) => {
        e.preventDefault();
        
        const currentTime = new Date().getTime();
        if (currentTime - lastScrollTime < scrollCooldown) return;
        
        lastScrollTime = currentTime;

        // Check horizontal scroll (deltaX) first, then vertical scroll (deltaY)
        if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
            // Horizontal scroll
            if (e.deltaX > 0) {
                rotateCards('right');
            } else {
                rotateCards('left');
            }
        } else {
            // Vertical scroll
            if (e.deltaY > 0) {
                rotateCards('right');
            } else {
                rotateCards('left');
            }
        }
    }, { passive: false });

    let startX;
    let startTime;
    const threshold = 50;
    const maxSwipeTime = 300;

    wrapper.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startTime = new Date().getTime();
    });

    wrapper.addEventListener('touchmove', (e) => {
        e.preventDefault();
    }, { passive: false });

    wrapper.addEventListener('touchend', (e) => {
        const endX = e.changedTouches[0].clientX;
        const endTime = new Date().getTime();
        const diffX = startX - endX;
        const diffTime = endTime - startTime;

        if (diffTime < maxSwipeTime && Math.abs(diffX) > threshold) {
            if (diffX > 0)
                rotateCards('right');
            else
                rotateCards('left');
        }
    });

    updateCardPositions();

    // Menambahkan event listener untuk scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        const brandText = document.querySelector('.brand-text');

        if (window.scrollY > 0) {
            navbar.classList.add('scrolled');
            brandText.style.color = '#000'; // Ubah warna teks menjadi hitam
        } else {
            navbar.classList.remove('scrolled');
            brandText.style.color = '#fff'; // Ubah warna teks menjadi putih
        }
    });
});