<?php
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-primary">Report a Lost Item</h2>
                    <form class="needs-validation" id="lostForm" novalidate enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Your Name</label>
                                <input type="text" class="form-control" name="owner_name" required>
                                <div class="invalid-feedback">Please provide your name.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="owner_email" required>
                                <div class="invalid-feedback">A valid email is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone (optional)</label>
                                <input type="text" class="form-control" name="owner_phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Item Name</label>
                                <input type="text" class="form-control" name="item_name" required>
                                <div class="invalid-feedback">Item name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date Lost</label>
                                <input type="date" class="form-control" name="date_lost" required>
                                <div class="invalid-feedback">Please select the date.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" required>
                                <div class="invalid-feedback">Location is required.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="4" required></textarea>
                                <div class="invalid-feedback">Please add a description.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Image (optional)</label>
                                <input type="file" class="form-control" name="item_image" accept="image/*">
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button class="btn btn-success btn-lg" type="submit">Submit Lost Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

