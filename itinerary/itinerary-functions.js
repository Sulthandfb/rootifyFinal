// itinerary-functions.js

let currentDayIndex = null;
let selectedAttractions = [];

// Modal functions
document.getElementById('saveTripBtn').addEventListener('click', function() {
    document.getElementById('saveTripModal').style.display = 'flex';
});

window.addEventListener('click', function(event) {
    if (event.target === document.getElementById('saveTripModal')) {
        document.getElementById('saveTripModal').style.display = 'none';
    }
});

// Day toggle function
function toggleDay(dayIndex) {
    const content = document.getElementById(`day-${dayIndex}`);
    const arrow = document.getElementById(`arrow-${dayIndex}`);
    content.classList.toggle('open');
    arrow.classList.toggle('open');
}

// Category selector functions
function openCategorySelector(dayIndex) {
    currentDayIndex = dayIndex - 1;
    document.getElementById('categorySelector').classList.add('open');
}

function closeCategorySelector() {
    document.getElementById('categorySelector').classList.remove('open');
}

function showAttractions(category) {
    fetch(`get_attractions_by_category.php?category=${category}`)
        .then(response => response.json())
        .then(attractions => {
            const attractionsList = document.getElementById('attractionsList');
            attractionsList.innerHTML = attractions.map(attraction => `
                <div class="attraction-item" onclick="toggleAttractionSelection(this, ${JSON.stringify(attraction)})">
                    <h3>${attraction.name}</h3>
                    <div class="rating">Rating: ${attraction.rating}</div>
                    <p>${attraction.description}</p>
                </div>
            `).join('');
            
            document.getElementById('categorySelector').classList.remove('open');
            document.getElementById('attractionSidebar').classList.add('open');
        });
}

// Attraction selection functions
function toggleAttractionSelection(element, attraction) {
    element.classList.toggle('selected');
    const index = selectedAttractions.findIndex(a => a.id === attraction.id);
    
    if (index === -1) {
        selectedAttractions.push(attraction);
    } else {
        selectedAttractions.splice(index, 1);
    }
}

function addSelectedAttractions() {
    const dayContent = document.getElementById(`day-${currentDayIndex}`);
    const attractionsContainer = dayContent.querySelector('.attractions-container');
    
    selectedAttractions.forEach(attraction => {
        const attractionCard = createAttractionCard(attraction);
        attractionsContainer.appendChild(attractionCard);
    });

    selectedAttractions = [];
    closeAttractionSidebar();
}

function createAttractionCard(attraction) {
    const card = document.createElement('div');
    card.className = 'card';
    card.innerHTML = `
        <img src="${attraction.image_url}" alt="${attraction.name}" class="card-image">
        <div class="card-content">
            <h3 class="card-title">${attraction.name}</h3>
            <div class="card-rating">
                <div class="rating-stars">${generateStars(attraction.rating)}</div>
                <span>${attraction.rating}</span>
            </div>
            <div class="card-category">
                <img src="${getCategoryIcon(attraction.category)}" alt="Icon" class="category-icon">
                ${attraction.category}
            </div>
            <p class="card-description">${attraction.description}</p>
        </div>
    `;
    return card;
}

function generateStars(rating) {
    return '★'.repeat(Math.floor(rating)) + '☆'.repeat(5 - Math.floor(rating));
}

function closeAttractionSidebar() {
    document.getElementById('attractionSidebar').classList.remove('open');
    selectedAttractions = [];
}

// Helper function to get category icon path
function getCategoryIcon(category) {
    const icons = {
        'nature': '../icons/leaves.svg',
        'culture': '../icons/masks.svg',
        'shopping': '../icons/online-shopping.svg',
        'education': '../icons/graduation-cap.svg',
        'beach': '../icons/vacations.svg',
        'recreation': '../icons/park.svg',
        'history': '../icons/history.svg',
        'restaurant': '../icons/restaurant.svg'
    };
    return icons[category.toLowerCase()] || '../icons/leaves.svg';
}