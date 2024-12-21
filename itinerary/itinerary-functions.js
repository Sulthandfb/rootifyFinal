// Global variables
let currentDayIndex = 0;

// Show category selection modal
function showCategoryModal(dayIndex) {
    console.log('Opening category modal for day:', dayIndex);
    currentDayIndex = dayIndex;
    const modal = document.getElementById('categoryModal');
    if (modal) {
        modal.style.display = 'flex';
    } else {
        console.error('Category modal element not found');
    }
}

// Close category selection modal
function closeCategoryModal() {
    const modal = document.getElementById('categoryModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Show attraction sidebar for selected category
function showAttractionSidebar(category) {
    console.log('Showing attractions for category:', category);
    closeCategoryModal();
    
    const sidebar = document.getElementById('attractionSidebar');
    if (sidebar) {
        sidebar.classList.add('open');
        loadAttractionsByCategory(category);
    } else {
        console.error('Attraction sidebar element not found');
    }
}

// Close attraction sidebar
function closeAttractionSidebar() {
    const sidebar = document.getElementById('attractionSidebar');
    if (sidebar) {
        sidebar.classList.remove('open');
    }
}

// Fetch attractions by category
function loadAttractionsByCategory(category) {
    console.log('Fetching attractions for category:', category);
    
    fetch(`get_attractions_by_category.php?category=${encodeURIComponent(category)}`)
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data);
            displayAttractions(data);
        })
        .catch(error => {
            console.error('Error fetching attractions:', error);
        });
}

// Display attractions in sidebar
function displayAttractions(attractions) {
    const sidebarContent = document.querySelector('.sidebar-content');
    if (!sidebarContent) {
        console.error('Sidebar content element not found');
        return;
    }

    console.log('Displaying attractions:', attractions);

    if (Array.isArray(attractions)) {
        sidebarContent.innerHTML = attractions.map(attraction => `
            <div class="attraction-card" onclick="addAttractionToDay(${currentDayIndex}, ${attraction.id})">
                <img src="${attraction.image_url}" alt="${attraction.name}" onerror="this.src='../img/default.jpg'">
                <div class="attraction-card-content">
                    <h3>${attraction.name}</h3>
                    <div class="card-rating">
                        ${generateRatingStars(attraction.rating)}
                        <span>${attraction.rating}</span>
                    </div>
                    <div class="card-category">
                        <img src="${getCategoryIcon(attraction.category)}" alt="${attraction.category}" class="category-icon">
                        <span>${attraction.category}</span>
                    </div>
                </div>
            </div>
        `).join('');
    } else {
        console.error('Received invalid attractions data:', attractions);
        sidebarContent.innerHTML = '<p>Error loading attractions</p>';
    }
}

// Add attraction to selected day
async function addAttractionToDay(dayIndex, attractionId) {
    try {
        console.log('Adding attraction:', attractionId, 'to day:', dayIndex);
        
        const response = await fetch(`get_attraction_details.php?id=${attractionId}`);
        const attraction = await response.json();
        
        const dayContent = document.getElementById(`day-${dayIndex}`);
        if (!dayContent) {
            console.error('Day content element not found');
            return;
        }

        const newCard = document.createElement('div');
        newCard.className = 'card';
        newCard.dataset.attractionId = attraction.id;
        
        newCard.innerHTML = `
            <img src="${attraction.image_url}" alt="${attraction.name}" class="card-image" onerror="this.src='../img/default.jpg'">
            <div class="card-content">
                <h3 class="card-title">${attraction.name}</h3>
                <div class="card-rating">
                    <div class="rating-stars">
                        ${generateRatingStars(attraction.rating)}
                    </div>
                    <span>${attraction.rating}</span>
                </div>
                <div class="card-category">
                    <img src="${getCategoryIcon(attraction.category)}" alt="Category Icon" class="category-icon">
                    ${attraction.category}
                </div>
                <p class="card-description">${attraction.description}</p>
            </div>
        `;
        
        const addButton = dayContent.querySelector('.add-location-btn');
        if (addButton) {
            dayContent.insertBefore(newCard, addButton);
        } else {
            dayContent.appendChild(newCard);
        }
        
        closeAttractionSidebar();
        
        // Add marker to map
        if (typeof map !== 'undefined' && attraction.latitude && attraction.longitude) {
            addMarkerToMap(attraction);
        }
        
    } catch (error) {
        console.error('Error adding attraction:', error);
    }
}

// Helper function to generate rating stars
function generateRatingStars(rating) {
    let stars = '';
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;

    for (let i = 1; i <= 5; i++) {
        if (i <= fullStars) {
            stars += '★';
        } else if (i === fullStars + 1 && hasHalfStar) {
            stars += '⯨';
        } else {
            stars += '☆';
        }
    }
    return stars;
}

// Get category icon
function getCategoryIcon(category) {
    const icons = {
        'nature': '../icons/leaves.svg',
        'culture': '../icons/masks.svg',
        'shopping': '../icons/online-shopping.svg',
        'education': '../icons/graduation-cap.svg',
        'beach': '../icons/vacations.svg',
        'recreation': '../icons/park.svg',
        'history': '../icons/history.svg',
        'restaurant': '../icons/restaurant.svg',
        'hotel': '../icons/hotel.svg'
    };
    
    const normalizedCategory = category.toLowerCase();
    return icons[normalizedCategory] || '../icons/default.svg';
}

// Add marker to map
function addMarkerToMap(attraction) {
    if (typeof L !== 'undefined' && map) {
        L.marker([attraction.latitude, attraction.longitude])
            .addTo(map)
            .bindPopup(attraction.name);
    }
}

// Document ready event listener
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners for modals
    window.addEventListener('click', function(event) {
        const categoryModal = document.getElementById('categoryModal');
        if (event.target === categoryModal) {
            closeCategoryModal();
        }
    });

    // Initialize map if Leaflet is available
    if (typeof L !== 'undefined') {
        const map = L.map('map').setView([-7.7956, 110.3695], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    }
});