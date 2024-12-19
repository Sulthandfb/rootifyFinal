document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();

    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            // Here you would typically show/hide content based on the selected tab
            console.log(`Tab ${button.dataset.tab} selected`);
        });
    });

    // Favorite button functionality
    const favoriteButton = document.querySelector('.favorite-button');
    favoriteButton.addEventListener('click', () => {
        const heartIcon = favoriteButton.querySelector('i');
        heartIcon.setAttribute('data-lucide', heartIcon.getAttribute('data-lucide') === 'heart' ? 'heart-off' : 'heart');
        lucide.createIcons();
        console.log('Favorite toggled');
    });

    // View all photos functionality
    const viewAllPhotos = document.querySelector('.view-all');
    viewAllPhotos.addEventListener('click', () => {
        console.log('View all photos clicked');
        // Here you would typically open a modal or navigate to a gallery page
    });

    // Login button functionality
    const loginButton = document.querySelector('.login-button');
    loginButton.addEventListener('click', () => {
        console.log('Login button clicked');
        // Here you would typically open a login modal or navigate to a login page
    });

    // View rooms button functionality
    const viewRoomsButton = document.querySelector('.view-rooms-button');
    viewRoomsButton.addEventListener('click', () => {
        console.log('View rooms button clicked');
        // Here you would typically navigate to a room listing page
    });
});

