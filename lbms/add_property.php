<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<div class="container py-4">
    <div class="col-lg-9 mx-auto">
        <div class="card border-0 shadow-sm p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="text-gold fw-bold small text-uppercase">Broker Listing Form</span>
                    <h3 class="fw-bold mb-0">Add a property</h3>
                </div>
                <span class="badge bg-light text-dark border">Draft LB-1048</span>
            </div>
            <p class="text-muted small mb-4">Enter clear, accurate details. Save your progress or send the listing for internal review.</p>

            <form action="actions/property_action.php" method="POST" enctype="multipart/form-data">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Property information</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Property title *</label>
                        <input type="text" name="title" class="form-control" value="Residential Plot in Uttara" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Category *</label>
                        <select name="category" class="form-select">
                            <option value="Residential">Residential</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Agricultural">Agricultural</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Listing type *</label>
                        <select name="listing_type" class="form-select">
                            <option value="Sale">For Sale</option>
                            <option value="Rent">For Rent</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Location/address *</label>
                        <input type="text" name="location" class="form-control" value="Sector 15, Uttara, Dhaka" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Land size *</label>
                        <input type="number" step="0.01" name="size" class="form-control" value="4" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Unit *</label>
                        <input type="text" name="unit" class="form-control" value="Katha (Approx.)" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Asking price (BDT) *</label>
                        <input type="number" name="price" class="form-control" value="4800000" required>
                        <small class="text-muted">For rentals, enter the monthly asking price.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Submission status *</label>
                        <select name="status" class="form-select">
                            <option value="Pending Verification">Submit for Review (Pending Verification)</option>
                            <option value="Draft">Save as Draft</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Description *</label>
                    <textarea name="description" class="form-control" rows="3" required>A residential plot in Sector 15 with local road access. Contact the broker for exact boundaries and a site visit.</textarea>
                </div>

                <h6 class="fw-bold border-bottom pb-2 mb-3">Property photos & Supporting documents</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Property photo (JPG/PNG)</label>
                        <input type="file" name="property_image" class="form-control" accept="image/*">
                        <small class="text-muted">Permitted formats and size limits are checked on upload.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Supporting document (Private PDF)</label>
                        <input type="file" name="document_file" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="text-muted">Restricted to permitted users. Internal review is not government title certification.</small>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold px-4">Submit for review</button>
                    <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>