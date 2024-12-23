<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../filter_wisata/db_connect.php';

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} else {
    echo "Connected successfully";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $rating = $_POST['rating'];
    $price = $_POST['price'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $category = $_POST['category'];

    // Handle file upload
    $target_dir = "../akomodasi/hotel-img/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // If everything is ok, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert data into database
    $sql = "INSERT INTO hotels (name, description, rating, price, latitude, longitude, category, image_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssddddss", $name, $description, $rating, $price, $latitude, $longitude, $category, $image_url);
    
    if ($stmt->execute()) {
        $hotel_id = $stmt->insert_id;
        
        // Insert facilities
        if(isset($_POST['facilities'])) {
            foreach($_POST['facilities'] as $facility) {
                $sql_facility = "INSERT INTO facilities (hotel_id, facility_name) VALUES (?, ?)";
                $stmt_facility = $db->prepare($sql_facility);
                $stmt_facility->bind_param("is", $hotel_id, $facility);
                $stmt_facility->execute();
            }
        }
        
        // Insert additional images
        if(isset($_FILES['additional_images'])) {
            $file_count = count($_FILES['additional_images']['name']);
            for($i=0; $i<$file_count; $i++) {
                $target_file = $target_dir . basename($_FILES["additional_images"]["name"][$i]);
                if (move_uploaded_file($_FILES["additional_images"]["tmp_name"][$i], $target_file)) {
                    $sql_image = "INSERT INTO hotel_images (hotel_id, image_url) VALUES (?, ?)";
                    $stmt_image = $db->prepare($sql_image);
                    $stmt_image->bind_param("is", $hotel_id, $target_file);
                    $stmt_image->execute();
                }
            }
        }
        
        $success = true;
        $message = 'New hotel has been added successfully.';
    } else {
        $success = false;
        $message = "Error: " . $sql . "<br>" . $db->error;
    }

    $stmt->close();
    $db->close();
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

include 'admin-header.php';
include 'admin-navbar.php';
?>

<div class="h-screen flex-grow-1 overflow-y-lg-auto">
    <header class="bg-surface-primary border-bottom pt-6">
        <div class="container-fluid">
            <div class="mb-npx">
                <div class="row align-items-center">
                    <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                        <h1 class="h2 mb-0 ls-tight">Add New Hotel</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <div class="card shadow border-0 mb-7">
                <div class="card-header">
                    <h5 class="mb-0">Hotel Details</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Primary Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="additional_images" class="form-label">Upload Additional Images</label>
                            <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="category" id="hotel" value="hotel" required>
                                <label class="btn btn-outline-primary" for="hotel">Hotel</label>
                                <input type="radio" class="btn-check" name="category" id="villa" value="villa">
                                <label class="btn btn-outline-primary" for="villa">Villa</label>
                                <input type="radio" class="btn-check" name="category" id="apartment" value="apartment">
                                <label class="btn btn-outline-primary" for="apartment">Apartment</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <div id="map" style="height: 300px;"></div>
                            <input type="hidden" id="latitude" name="latitude" required>
                            <input type="hidden" id="longitude" name="longitude" required>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <input type="number" class="form-control" id="rating" name="rating" min="0" max="5" step="0.1" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price per Night</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" step="1000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Facilities</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]" value="Kolam Renang" id="pool">
                                <label class="form-check-label" for="pool">Kolam Renang</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]" value="WiFi Gratis" id="wifi">
                                <label class="form-check-label" for="wifi">WiFi Gratis</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]" value="Spa" id="spa">
                                <label class="form-check-label" for="spa">Spa</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]" value="Restoran" id="restaurant">
                                <label class="form-check-label" for="restaurant">Restoran</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]" value="Pusat Kebugaran" id="gym">
                                <label class="form-check-label" for="gym">Pusat Kebugaran</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]" value="Parkir Gratis" id="parking">
                                <label class="form-check-label" for="parking">Parkir Gratis</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Hotel</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var map = L.map('map').setView([-7.7956, 110.3695], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    function onMapClick(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
    }

    map.on('click', onMapClick);

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        fetch('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred: ' + error.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
</body>
</html>

