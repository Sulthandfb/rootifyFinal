<?php
include '../filter_wisata/db_connect.php';
// At the start of your PHP file
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '5M');


// Fetch hotels from database
$sql = "SELECT * FROM hotels";
$result = $db->query($sql);

include 'admin-header.php';
include 'admin-navbar.php';
?>

<div class="container-fluid py-4">
    <h2 class="mb-4">Daftar Hotel</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text"><?php echo substr($row['description'], 0, 100); ?>...</p>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary edit-hotel" data-bs-toggle="modal" data-bs-target="#editHotelModal" data-id="<?php echo $row['hotel_id']; ?>">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger delete-hotel" data-id="<?php echo $row['hotel_id']; ?>">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Edit Hotel Modal -->
<div class="modal fade" id="editHotelModal" tabindex="-1" aria-labelledby="editHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHotelModalLabel">Edit Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editHotelForm">
                    <input type="hidden" id="hotel_id" name="hotel_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price per Night</label>
                        <input type="number" class="form-control" id="edit_price" name="price" min="0" step="1000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                            <div class="col">
                                <input type="radio" class="btn-check" name="category" id="edit_hotel" value="hotel" required>
                                <label class="btn btn-outline-primary w-100" for="edit_hotel">Hotel</label>
                            </div>
                            <div class="col">
                                <input type="radio" class="btn-check" name="category" id="edit_villa" value="villa">
                                <label class="btn btn-outline-primary w-100" for="edit_villa">Villa</label>
                            </div>
                            <div class="col">
                                <input type="radio" class="btn-check" name="category" id="edit_apartment" value="apartment">
                                <label class="btn btn-outline-primary w-100" for="edit_apartment">Apartment</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_rating" class="form-label">Rating</label>
                        <input type="number" class="form-control" id="edit_rating" name="rating" min="0" max="5" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Facilities</label>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                            <?php
                            $facilities = ['Kolam Renang', 'WiFi Gratis', 'Spa', 'Restoran', 'Pusat Kebugaran', 'Parkir Gratis'];
                            foreach ($facilities as $facility):
                            ?>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facilities[]" value="<?php echo $facility; ?>" id="edit_<?php echo strtolower(str_replace(' ', '_', $facility)); ?>">
                                    <label class="form-check-label" for="edit_<?php echo strtolower(str_replace(' ', '_', $facility)); ?>">
                                        <?php echo $facility; ?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Edit hotel
    $('.edit-hotel').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'get_hotel.php',
            type: 'GET',
            data: {id:id},
            success: function(response) {
                var hotel = JSON.parse(response);
                $('#hotel_id').val(hotel.hotel_id);
                $('#edit_name').val(hotel.name);
                $('#edit_description').val(hotel.description);
                $('#edit_price').val(hotel.price);
                $(`#edit_${hotel.category}`).prop('checked', true);
                $('#edit_rating').val(hotel.rating);
                
                // Clear all checkboxes first
                $('input[name="facilities[]"]').prop('checked', false);
                
                // Check the facilities that the hotel has
                hotel.facilities.forEach(function(facility) {
                    $(`#edit_${facility.toLowerCase().replace(' ', '_')}`).prop('checked', true);
                });
            }
        });
    });

    // Replace this block in your existing script:
    $('#editHotelForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'update_hotel.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response == 'success') {
                    alert('Hotel updated successfully');
                    location.reload();
                } else {
                    alert('Error updating hotel');
                }
            }
        });
    });

    // With this new code:
    document.getElementById('editHotelForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('update_hotel.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: error.message,
                icon: 'error'
            });
        });
    });

    // Delete hotel
    $('.delete-hotel').click(function() {
        if(confirm('Are you sure you want to delete this hotel?')) {
            var id = $(this).data('id');
            $.ajax({
                url: 'delete_hotel.php',
                type: 'POST',
                data: {id: id},
                success: function(response) {
                    if(response == 'success') {
                        alert('Hotel deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting hotel');
                    }
                }
            });
        }
    });
});
</script>

</body>
</html>