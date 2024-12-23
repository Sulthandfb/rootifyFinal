<?php
include '../filter_wisata/db_connect.php';


// Fetch attractions from database
$sql = "SELECT * FROM tourist_attractions";
$result = $db->query($sql);

include 'admin-header.php';
include 'admin-navbar.php';
?>

<div class="container-fluid py-4">
    <h2 class="mb-4">Daftar Tempat Wisata</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text flex-grow-1"><?php echo substr($row['description'], 0, 100) . '...'; ?></p>
                        <div class="mt-auto">
                            <button type="button" class="btn btn-primary edit-attraction" data-bs-toggle="modal" data-bs-target="#editAttractionModal" data-id="<?php echo $row['id']; ?>">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger delete-attraction" data-id="<?php echo $row['id']; ?>">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Edit Attraction Modal -->
<div class="modal fade" id="editAttractionModal" tabindex="-1" aria-labelledby="editAttractionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttractionModalLabel">Edit Attraction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAttractionForm">
                    <input type="hidden" id="attraction_id" name="attraction_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2">
                            <?php
                            $categories = ['History', 'Nature', 'Culture', 'Beach', 'Shopping', 'Recreation', 'Education', 'Restaurant'];
                            foreach ($categories as $category):
                            ?>
                            <div class="col">
                                <input type="radio" class="btn-check" name="category" id="edit_<?php echo strtolower($category); ?>" value="<?php echo $category; ?>" required>
                                <label class="btn btn-outline-primary w-100" for="edit_<?php echo strtolower($category); ?>"><?php echo $category; ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_rating" class="form-label">Rating</label>
                        <input type="number" class="form-control" id="edit_rating" name="rating" min="0" max="5" step="0.1" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="edit_opening_time" class="form-label">Opening Time</label>
                            <input type="time" class="form-control" id="edit_opening_time" name="opening_time" required>
                        </div>
                        <div class="col">
                            <label for="edit_closing_time" class="form-label">Closing Time</label>
                            <input type="time" class="form-control" id="edit_closing_time" name="closing_time" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_ticket_price" class="form-label">Ticket Price</label>
                        <input type="number" class="form-control" id="edit_ticket_price" name="ticket_price" min="0" step="1000" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Image</label>
                        <div class="d-flex align-items-center gap-3">
                            <img id="current_image" src="" alt="Current image" style="max-width: 150px; max-height: 150px;" class="img-thumbnail">
                            <div class="flex-grow-1">
                                <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>
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
<script>
$(document).ready(function() {
    // Edit attraction
    $('.edit-attraction').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'get_attraction.php',
            type: 'GET',
            data: {id: id},
            success: function(response) {
                var attraction = JSON.parse(response);
                $('#attraction_id').val(attraction.id);
                $('#edit_name').val(attraction.name);
                $('#edit_description').val(attraction.description);
                $('#edit_address').val(attraction.address);
                $(`#edit_${attraction.category.toLowerCase()}`).prop('checked', true);
                $('#edit_rating').val(attraction.rating);
                $('#edit_opening_time').val(attraction.opening_time);
                $('#edit_closing_time').val(attraction.closing_time);
                $('#edit_ticket_price').val(attraction.ticket_price);
                $('#current_image').attr('src', attraction.image_url);
            }
        });
    });

    // Preview image before upload
    $('#edit_image').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#current_image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Update attraction
    $('#editAttractionForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: 'update_attraction.php',
            type: 'POST',
            data: formData,
            processData: false,  // Important!
            contentType: false,  // Important!
            success: function(response) {
                var result = JSON.parse(response);
                if(result.status === 'success') {
                    alert('Updated successfully');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            }
        });
    });

    // Delete attraction
    $('.delete-attraction').click(function() {
        if(confirm('Are you sure you want to delete this attraction?')) {
            var id = $(this).data('id');
            $.ajax({
                url: 'delete_attraction.php',
                type: 'POST',
                data: {id: id},
                success: function(response) {
                    if(response == 'success') {
                        alert('Attraction deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting attraction');
                    }
                }
            });
        }
    });
});
</script>

</body>
</html>

