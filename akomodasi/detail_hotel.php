<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// db_connect.php
$hostname = "dbserver"; 
$username = "root";      
$password = "rootpassword"; 
$database_name = "erd_rootify";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
    die("Koneksi database rusak: " . $db->connect_error);
}

// Get the hotel_id from the URL parameter
$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0;

// Fetch hotel details
$hotel_query = "SELECT * FROM hotels WHERE hotel_id = ?";
$stmt = $db->prepare($hotel_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$hotel_result = $stmt->get_result();
$hotel = $hotel_result->fetch_assoc();

// Fetch rooms for this hotel
$rooms_query = "SELECT * FROM rooms WHERE hotel_id = ?";
$stmt = $db->prepare($rooms_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$rooms_result = $stmt->get_result();

// Fetch facilities for this hotel
$facilities_query = "SELECT * FROM facilities WHERE hotel_id = ?";
$stmt = $db->prepare($facilities_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$facilities_result = $stmt->get_result();

// Fetch images for this hotel
$images_query = "SELECT * FROM hotel_images WHERE hotel_id = ? ORDER BY is_primary DESC, image_id ASC";
$stmt = $db->prepare($images_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$images_result = $stmt->get_result();

// Start outputting the HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($hotel['name']); ?> | Rootify</title>
    <link rel="stylesheet" href="detail_hotel.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="min-h-screen bg-background">
        <div class="photo-gallery">
            <?php
            $images = $images_result->fetch_all(MYSQLI_ASSOC);
            $main_image = $images[0]; // First image (primary)
            $gallery_images = array_slice($images, 1, 4); // Next 4 images
            ?>
            <div class="main-image">
                <img src="<?php echo htmlspecialchars($main_image['image_url']); ?>" alt="Main view of <?php echo htmlspecialchars($hotel['name']); ?>">
            </div>
            <div class="gallery-grid">
                <?php foreach ($gallery_images as $index => $image): ?>
                    <?php if ($index < 3): ?>
                        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Gallery image <?php echo $index + 1; ?>">
                    <?php else: ?>
                        <div class="view-all">
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="View all photos">
                            <span>Lihat semua foto</span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <button class="favorite-button">
                <i data-lucide="heart"></i>
            </button>
        </div>

        <div class="container">
            <div class="property-details">
                <div class="details-left">
                    <div class="rating">
                        <div class="hotel-category <?php echo strtolower($hotel['category']); ?>"><?php echo htmlspecialchars($hotel['category']); ?></div>
                        <div class="stars">
                            <?php
                            $rating = floor($hotel['rating']);
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<img src="../img/star.svg" alt="Star" class="star filled">';
                                } else {
                                    echo '<img src="../img/star.svg" alt="Star" class="star">';
                                }
                            }
                            ?>
                        </div>
                        <span class="rating-text"><?php echo number_format($hotel['rating'] * 20, 0); ?>% tamu puas nginep di sini</span>
                    </div>
                    <h1 class="property-title"><?php echo htmlspecialchars($hotel['name']); ?></h1>
                    <div class="property-meta">
                        <div class="review-score">
                            <span><?php echo number_format($hotel['rating'], 1); ?>/5</span>
                            <span>(80 review)</span>
                        </div>
                        <span class="location"><?php echo htmlspecialchars($hotel['description']); ?></span>
                    </div>
                </div>

                <div class="details-right">
                    <div class="pricing">
                        <div class="price-details">
                            <div class="price-label">Mulai dari</div>
                            <div class="price">IDR <?php echo number_format($hotel['price'], 0); ?></div>
                            <div class="price-period">/kamar/malam</div>
                        </div>
                        <button class="view-rooms-button">Lihat kamar</button>
                    </div>                    
                </div>
            </div>
            <div class="separator"></div>
        </div>

        <div class="container">
            <div class="about-section">
                <h2 class="section-title">Tentang <?php echo htmlspecialchars($hotel['name']); ?></h2>
                <div class="description">
                    <p><?php echo nl2br(htmlspecialchars($hotel['description'])); ?></p>
                </div>
                <button class="show-more">Lihat lebih sedikit</button>
                <div class="separator"></div>
            </div>

            <div class="location-section">
                <div class="section-header">
                    <h2 class="section-title">Lokasi</h2>
                    <a href="#" class="view-map">Lihat peta</a>
                </div>
                <div class="location-content">
                    <div class="map-container">
                        <?php echo $hotel['google_map_embed_code']; ?>
                    </div>
                    <div class="facilities">
                        <h3>Fasilitas Hotel</h3>
                        <div class="facilities-grid">
                            <?php while ($facility = $facilities_result->fetch_assoc()): ?>
                            <div class="facility-item">
                                <img src="<?php echo htmlspecialchars($facility['facility_icon']); ?>" alt="<?php echo htmlspecialchars($facility['facility_name']); ?>">
                                <span><?php echo htmlspecialchars($facility['facility_name']); ?></span>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator"></div>

            <div class="room-details">
                <h2 class="section-title">Kamar Tersedia</h2>
                <?php while ($room = $rooms_result->fetch_assoc()): ?>
                <!-- Di dalam room card loop, ganti form booking dengan link langsung -->
                <div class="room-card" data-room-id="<?php echo $room['room_id']; ?>">
                    <div class="room-image">
                        <img src="<?php echo htmlspecialchars($room['image_url']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="main-room-image">
                    </div>
                    <div class="room-info">
                        <h3 class="room-title"><?php echo htmlspecialchars($room['room_name']); ?></h3>
                        <p class="room-subtitle"><?php echo htmlspecialchars($room['description']); ?></p>
                        <div class="room-features">
                            <div class="feature">
                                <i data-lucide="users"></i>
                                <span><?php echo $room['capacity']; ?> Tamu</span>
                            </div>
                            <div class="feature">
                                <i data-lucide="bed"></i>
                                <span><?php echo htmlspecialchars($room['bed_type']); ?></span>
                            </div>
                            <div class="feature">
                                <i data-lucide="square"></i>
                                <span><?php echo $room['room_size']; ?>m²</span>
                            </div>
                        </div>
                        <div class="room-availability">Sisa <?php echo $room['availability']; ?> kamar lagi!</div>
                        <div class="room-price">
                            <div class="price-amount">IDR <?php echo number_format($room['price'], 0); ?></div>
                            <div class="price-info">/kamar/malam</div>
                        </div>
                        <!-- Ganti form dengan link langsung -->
                        <a href="../landing/pembayaran.php?type=hotel&id=<?php echo $room['room_id']; ?>" class="book-button">Pesan Sekarang</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        
            <!-- Add the popup structure -->
            <div id="roomPopup" class="popup">
                <div class="popup-content">
                    <span class="close-popup">&times;</span>
                    <?php
                    // Fetch room details and images
                    $room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
                    if ($room_id > 0) {
                        $room_query = "SELECT * FROM rooms WHERE room_id = ?";
                        $stmt = $db->prepare($room_query);
                        $stmt->bind_param("i", $room_id);
                        $stmt->execute();
                        $room = $stmt->get_result()->fetch_assoc();

                        $images_query = "SELECT * FROM room_images WHERE room_id = ? ORDER BY is_primary DESC";
                        $stmt = $db->prepare($images_query);
                        $stmt->bind_param("i", $room_id);
                        $stmt->execute();
                        $room_images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                        if ($room && $room_images):
                    ?>
                    <div class="popup-images">
                        <img src="<?php echo htmlspecialchars($room_images[0]['image_url']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="popup-main-image">
                        <div class="popup-thumbnails">
                            <?php foreach ($room_images as $image): ?>
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="popup-thumbnail">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="popup-details">
                        <h3 class="popup-title"><?php echo htmlspecialchars($room['room_name']); ?></h3>
                        <p class="popup-subtitle"><?php echo htmlspecialchars($room['description']); ?></p>
                        <div class="popup-features">
                            <div class="feature">
                                <i data-lucide="users"></i>
                                <span><?php echo $room['capacity']; ?> Tamu</span>
                            </div>
                            <div class="feature">
                                <i data-lucide="bed"></i>
                                <span><?php echo htmlspecialchars($room['bed_type']); ?></span>
                            </div>
                            <div class="feature">
                                <i data-lucide="square"></i>
                                <span><?php echo $room['room_size']; ?>m²</span>
                            </div>
                            <div class="feature">
                                <i data-lucide="wifi"></i>
                                <span>Wi-Fi gratis</span>
                            </div>
                            <!-- Add more features as needed -->
                        </div>
                        <div class="popup-price">
                            <div class="price-tag">Available Now</div>
                            <div class="price-amount">IDR <?php echo number_format($room['price'], 0); ?></div>
                            <div class="price-info">/kamar/malam</div>
                        </div>
                        <button class="book-button">Pesan</button>
                    </div>
                    <?php
                        else:
                            echo "<p>Room details not found.</p>";
                        endif;
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- Add this after the location section in your existing HTML -->
        <div class="container">
            <div class="policy-section">
                <h2 class="section-title">Kebijakan Akomodasi</h2>    
                <div class="policy-container">
                    <div class="policy-row">
                        <div class="policy-left">
                            <div class="policy-header">
                                <i data-lucide="clock"></i>
                                <h3>Prosedur Check-in</h3>
                            </div>
                        </div>
                        <div class="policy-right">
                            <div class="policy-item">
                                <div class="check-times">
                                    <div class="check-time">
                                        <span class="label">Check-in</span>
                                        <span class="time">14:00-23:59</span>
                                    </div>
                                    <div class="check-time">
                                        <span class="label">Check-out</span>
                                        <span class="time">12:00</span>
                                    </div>
                                </div>
                                <p>guest need to show covid test (antigen/genose/PCR) during check in.</p>
                                <button class="read-more">Selengkapnya</button>
                                <p class="note">Mau check in lebih awal? Kamu bisa isi Permintaan Khusus di halaman pemesanan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="policy-row">
                        <div class="policy-left">
                            <div class="policy-header">
                                <i data-lucide="file-text"></i>
                                <h3>Kebijakan Lainnya</h3>
                            </div>
                        </div>
                        <div class="policy-right">
                            <div class="other-policies">
                                <div class="policy-detail">
                                    <h4>Anak</h4>
                                    <p>Tamu umur berapa pun bisa menginap di sini.</p>
                                    <p>Anak-anak 6 tahun ke atas dianggap sebagai tamu dewasa.</p>
                                    <p>Pastikan umur anak yang menginap sesuai dengan detail pemesanan. Jika berbeda, tamu mungkin akan dikenakan biaya tambahan saat check-in.</p>
                                </div>
                                <div class="policy-detail">
                                    <h4>Deposit</h4>
                                    <p>Tamu perlu membayar deposit saat check-in.</p>
                                </div>
                                <div class="policy-detail">
                                    <h4>Umur</h4>
                                    <p>Tamu umur berapa pun bisa menginap di sini.</p>
                                </div>
                                <div class="policy-detail">
                                    <h4>Sarapan</h4>
                                    <p>Sarapan tersedia pukul 07:00 - 10:00 waktu lokal.</p>
                                </div>
                                <div class="policy-detail">
                                    <h4>Hewan peliharaan</h4>
                                    <p>Tidak diperbolehkan membawa hewan peliharaan.</p>
                                </div>
                                <div class="policy-detail">
                                    <h4>Merokok</h4>
                                    <p>Tamu diperbolehkan merokok.</p>
                                </div>
                                <div class="policy-detail">
                                    <h4>Alkohol</h4>
                                    <p>Minuman beralkohol diperbolehkan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            lucide.createIcons();

            // Get the popup elements
            const popup = document.getElementById('roomPopup');
            const closePopup = document.querySelector('.close-popup');
            const roomCards = document.querySelectorAll('.room-card');
            const popupMainImage = document.querySelector('.popup-main-image');
            const popupThumbnails = document.querySelectorAll('.popup-thumbnail');

            // Open popup when a room card is clicked
            roomCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('.book-button')) {
                        const roomId = this.dataset.roomId;
                        // Update the URL with the room_id
                        window.history.pushState({}, '', `?hotel_id=<?php echo $hotel_id; ?>&room_id=${roomId}`);
                        // Reload the page to show the popup with the selected room details
                        location.reload();
                    }
                });
            });

            // Add handling for booking form submission
            const bookingForms = document.querySelectorAll('.booking-form');
            bookingForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Prevent default only if you want to add additional validation
                    // e.preventDefault();
                    
                    // You can add additional validation here if needed
                    const roomId = this.querySelector('input[name="id"]').value;
                    if (!roomId) {
                        e.preventDefault();
                        alert('Error: Room ID is missing');
                        return;
                    }
                    
                    // If everything is valid, the form will submit naturally
                });
            });

            // Close popup when the close button is clicked
            closePopup.addEventListener('click', function() {
                popup.style.display = 'none';
                // Remove the room_id from the URL
                window.history.pushState({}, '', `?hotel_id=<?php echo $hotel_id; ?>`);
            });

            // Close popup when clicking outside the popup content
            window.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    // Remove the room_id from the URL
                    window.history.pushState({}, '', `?hotel_id=<?php echo $hotel_id; ?>`);
                }
            });

            // Change main image when a thumbnail is clicked
            popupThumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    popupMainImage.src = this.src;
                    popupMainImage.alt = this.alt;
                });
            });

            // Show popup if room_id is in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const roomId = urlParams.get('room_id');
            if (roomId) {
                popup.style.display = 'block';
            }
        });
    </script>
</body>
</html>
<?php
// Close the database connection
$stmt->close();
$db->close();
?>