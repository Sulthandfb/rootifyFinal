<?php
include '../filter_wisata/db_connect.php';

// Fetch all attractions for the dropdown
$attractions_query = "SELECT id, name FROM tourist_attractions";
$attractions_result = $db->query($attractions_query);

// Fetch all guides for the dropdown
$guides_query = "SELECT guide_id, name FROM tour_guides";
$guides_result = $db->query($guides_query);

// Fetch all hotels for the dropdown
$hotels_query = "SELECT hotel_id, name FROM hotels";
$hotels_result = $db->query($hotels_query);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $short_description = $_POST['short_description'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $discounted_price = $_POST['discounted_price'];
    $max_participants = $_POST['max_participants'];
    $age_range = $_POST['age_range'];
    $meeting_point = $_POST['meeting_point'];
    $start_time = $_POST['start_time'];
    $includes = $_POST['includes'];
    $excludes = $_POST['excludes'];
    $highlights = $_POST['highlights'];
    $cancellation_policy = $_POST['cancellation_policy'];
    $attractions = $_POST['attractions'];
    $guide_id = $_POST['guide_id'];
    $hotel_id = $_POST['hotel_id'];

    // Insert data into tourist_packets table
    $sql = "INSERT INTO tourist_packets (name, description, short_description, duration, price, discounted_price, max_participants, age_range, meeting_point, start_time, includes, excludes, highlights, cancellation_policy) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssssddisssssss", $name, $description, $short_description, $duration, $price, $discounted_price, $max_participants, $age_range, $meeting_point, $start_time, $includes, $excludes, $highlights, $cancellation_policy);
    
    if ($stmt->execute()) {
        $packet_id = $stmt->insert_id;
        
        // Insert attractions into packet_attractions table
        foreach ($attractions as $index => $attraction_id) {
            $sql_attraction = "INSERT INTO packet_attractions (packet_id, attraction_id, sequence) VALUES (?, ?, ?)";
            $stmt_attraction = $db->prepare($sql_attraction);
            $sequence = $index + 1;
            $stmt_attraction->bind_param("iii", $packet_id, $attraction_id, $sequence);
            $stmt_attraction->execute();
        }

        // Insert guide into packet_guides table
        $sql_guide = "INSERT INTO packet_guides (packet_id, guide_id, is_primary) VALUES (?, ?, 1)";
        $stmt_guide = $db->prepare($sql_guide);
        $stmt_guide->bind_param("ii", $packet_id, $guide_id);
        $stmt_guide->execute();

        // Insert hotel into packet_hotels table (assuming you have this table)
        $sql_hotel = "INSERT INTO packet_hotels (packet_id, hotel_id) VALUES (?, ?)";
        $stmt_hotel = $db->prepare($sql_hotel);
        $stmt_hotel->bind_param("ii", $packet_id, $hotel_id);
        $stmt_hotel->execute();

        $success = true;
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
                        <h1 class="h2 mb-0 ls-tight">Add New Tour Package</h1>
                    </div>
                    <pre></pre>
                </div>
            </div>
        </div>
    </header>
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <div class="card shadow border-0 mb-7">
                <div class="card-header">
                    <h5 class="mb-0">Tour Package Details</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <input type="text" class="form-control" id="short_description" name="short_description" required>
                        </div>
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="duration" name="duration" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" step="1000" required>
                        </div>
                        <div class="mb-3">
                            <label for="discounted_price" class="form-label">Discounted Price</label>
                            <input type="number" class="form-control" id="discounted_price" name="discounted_price" min="0" step="1000">
                        </div>
                        <div class="mb-3">
                            <label for="max_participants" class="form-label">Max Participants</label>
                            <input type="number" class="form-control" id="max_participants" name="max_participants" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="age_range" class="form-label">Age Range</label>
                            <input type="text" class="form-control" id="age_range" name="age_range" required>
                        </div>
                        <div class="mb-3">
                            <label for="meeting_point" class="form-label">Meeting Point</label>
                            <input type="text" class="form-control" id="meeting_point" name="meeting_point" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="includes" class="form-label">Includes</label>
                            <textarea class="form-control" id="includes" name="includes" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="excludes" class="form-label">Excludes</label>
                            <textarea class="form-control" id="excludes" name="excludes" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="highlights" class="form-label">Highlights</label>
                            <textarea class="form-control" id="highlights" name="highlights" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="cancellation_policy" class="form-label">Cancellation Policy</label>
                            <textarea class="form-control" id="cancellation_policy" name="cancellation_policy" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attractions" class="form-label">Attractions</label>
                            <select multiple class="form-control" id="attractions" name="attractions[]" required>
                                <?php while($row = $attractions_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple attractions.</small>
                        </div>
                        <div class="mb-3">
                            <label for="guide_id" class="form-label">Primary Guide</label>
                            <select class="form-control" id="guide_id" name="guide_id" required>
                                <?php while($row = $guides_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['guide_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hotel_id" class="form-label">Accommodation</label>
                            <select class="form-control" id="hotel_id" name="hotel_id" required>
                                <?php while($row = $hotels_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['hotel_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Tour Package</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if (isset($success) && $success): ?>
    Swal.fire({
        title: 'Success!',
        text: 'New tour package has been added successfully.',
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