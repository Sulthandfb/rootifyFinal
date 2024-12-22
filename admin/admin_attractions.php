<?php
include '../filter_wisata/db_connect.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $category = $_POST['category'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $rating = $_POST['rating'];
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];
    $ticket_price = $_POST['ticket_price'];

    // Handle file upload
    $target_dir = "../img/";
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
    $sql = "INSERT INTO tourist_attractions (name, description, image_url, address, category, longitude, latitude, rating, opening_time, closing_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssssssssss", $name, $description, $image_url, $address, $category, $longitude, $latitude, $rating, $opening_time, $closing_time);
    
    if ($stmt->execute()) {
        $attraction_id = $stmt->insert_id;
        
        // Insert into attraction_details
        $sql_details = "INSERT INTO attraction_details (attraction_id, ticket_price) VALUES (?, ?)";
        $stmt_details = $db->prepare($sql_details);
        $stmt_details->bind_param("id", $attraction_id, $ticket_price);
        
        if ($stmt_details->execute()) {
            $success = true;
        } else {
            $error = "Error: " . $sql_details . "<br>" . $db->error;
        }
    } else {
        $error = "Error: " . $sql . "<br>" . $db->error;
    }

    $stmt->close();
    $db->close();
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
                        <h1 class="h2 mb-0 ls-tight">Add New Attraction</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <div class="card shadow border-0 mb-7">
                <div class="card-header">
                    <h5 class="mb-0">Attraction Details</h5>
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
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <pre></pre>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="category" id="history" value="History" required>
                                <label class="btn btn-outline-primary" for="history">History</label>
                                <input type="radio" class="btn-check" name="category" id="nature" value="Nature">
                                <label class="btn btn-outline-primary" for="nature">Nature</label>
                                <input type="radio" class="btn-check" name="category" id="culture" value="Culture">
                                <label class="btn btn-outline-primary" for="culture">Culture</label>
                                <input type="radio" class="btn-check" name="category" id="beach" value="Beach">
                                <label class="btn btn-outline-primary" for="beach">Beach</label>
                                <input type="radio" class="btn-check" name="category" id="shopping" value="Shopping">
                                <label class="btn btn-outline-primary" for="shopping">Shopping</label>
                                <input type="radio" class="btn-check" name="category" id="recreation" value="Recreation">
                                <label class="btn btn-outline-primary" for="recreation">Recreation</label>
                                <input type="radio" class="btn-check" name="category" id="education" value="Education">
                                <label class="btn btn-outline-primary" for="education">Education</label>
                                <input type="radio" class="btn-check" name="category" id="restaurant" value="Restaurant">
                                <label class="btn btn-outline-primary" for="restaurant">Restaurant</label>
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
                        <div class="row mb-3">
                            <div class="col">
                                <label for="opening_time" class="form-label">Opening Time</label>
                                <input type="time" class="form-control" id="opening_time" name="opening_time" required>
                            </div>
                            <div class="col">
                                <label for="closing_time" class="form-label">Closing Time</label>
                                <input type="time" class="form-control" id="closing_time" name="closing_time" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ticket_price" class="form-label">Ticket Price</label>
                            <input type="number" class="form-control" id="ticket_price" name="ticket_price" min="0" step="1000" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Attraction</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

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

    <?php if (isset($success) && $success): ?>
    Swal.fire({
        title: 'Success!',
        text: 'New attraction has been added successfully.',
        icon: 'success',
        confirmButtonText: 'OK'
    });
    <?php elseif (isset($error)): ?>
    Swal.fire({
        title: 'Error!',
        text: '<?php echo $error; ?>',
        icon: 'error',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>
</script>
</body>
</html>