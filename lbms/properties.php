<?php
require_once 'config/db.php';
include 'includes/header.php';

$location = trim($_GET['location'] ?? '');
$category = $_GET['category'] ?? '';
$listing_type = $_GET['listing_type'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$min_size = $_GET['min_size'] ?? '';
$max_size = $_GET['max_size'] ?? '';

$sql = "SELECT p.*, pi.file_path FROM properties p LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_primary = 1 WHERE p.status = 'Approved'";
$params = [];

if ($location !== '') {
    $sql .= " AND (p.location LIKE ? OR p.title LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
}
if ($category !== '') {
    $sql .= " AND p.category = ?";
    $params[] = $category;
}
if ($listing_type !== '') {
    $sql .= " AND p.listing_type = ?";
    $params[] = $listing_type;
}
if ($min_price !== '') {
    $sql .= " AND p.price >= ?";
    $params[] = floatval($min_price);
}
if ($max_price !== '') {
    $sql .= " AND p.price <= ?";
    $params[] = floatval($max_price);
}
if ($min_size !== '') {
    $sql .= " AND p.size >= ?";
    $params[] = floatval($min_size);
}
if ($max_size !== '') {
    $sql .= " AND p.size <= ?";
    $params[] = floatval($max_size);
}

$sql .= " ORDER BY p.id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$properties = $stmt->fetchAll();
?>
<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Find a property</h2>
        <p class="text-muted">Compare land by location, budget and size. Only approved, active listings appear here.</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Filter properties</h6>
                    <a href="properties.php" class="small text-muted text-decoration-none">Reset</a>
                </div>
                <form action="properties.php" method="GET">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Location or keyword</label>
                        <input type="text" name="location" class="form-control form-control-sm" placeholder="All locations" value="<?php echo htmlspecialchars($location); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All categories</option>
                            <option value="Residential" <?php echo $category=='Residential'?'selected':''; ?>>Residential</option>
                            <option value="Commercial" <?php echo $category=='Commercial'?'selected':''; ?>>Commercial</option>
                            <option value="Agricultural" <?php echo $category=='Agricultural'?'selected':''; ?>>Agricultural</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Minimum price (BDT)</label>
                        <input type="number" name="min_price" class="form-control form-control-sm" placeholder="No minimum" value="<?php echo htmlspecialchars($min_price); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Maximum price (BDT)</label>
                        <input type="number" name="max_price" class="form-control form-control-sm" placeholder="No maximum" value="<?php echo htmlspecialchars($max_price); ?>">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Min. size</label>
                            <input type="number" name="min_size" class="form-control form-control-sm" placeholder="Any" value="<?php echo htmlspecialchars($min_size); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Max. size</label>
                            <input type="number" name="max_size" class="form-control form-control-sm" placeholder="Any" value="<?php echo htmlspecialchars($max_size); ?>">
                        </div>
                        <small class="text-muted" style="font-size:0.75rem;">Area in Katha (approximate)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Availability</label>
                        <select name="listing_type" class="form-select form-select-sm">
                            <option value="">Sale or rent</option>
                            <option value="Sale" <?php echo $listing_type=='Sale'?'selected':''; ?>>For Sale</option>
                            <option value="Rent" <?php echo $listing_type=='Rent'?'selected':''; ?>>For Rent</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gold w-100 btn-sm py-2">Apply filters</button>
                </form>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><?php echo count($properties); ?> properties found</h5>
                <span class="text-muted small">Sorted by: Newest listed</span>
            </div>
            <?php foreach ($properties as $p): ?>
            <div class="card border-0 shadow-sm mb-3 p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <img src="<?php echo htmlspecialchars($p['file_path']); ?>" class="img-fluid rounded" style="height:160px; width:100%; object-fit:cover;" alt="Land">
                    </div>
                    <div class="col-md-5">
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($p['title']); ?></h5>
                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($p['location']); ?></p>
                        <p class="text-muted small mb-2"><?php echo htmlspecialchars($p['size'].' '.$p['unit']); ?> &bull; <?php echo htmlspecialchars($p['category']); ?></p>
                        <span class="badge <?php echo $p['listing_type']=='Rent'?'bg-primary':'bg-secondary'; ?> me-2">For <?php echo htmlspecialchars($p['listing_type']); ?></span>
                        <span class="text-success small fw-semibold"><i class="fas fa-check me-1"></i>Listing reviewed</span>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <h5 class="fw-bold mb-1">৳ <?php echo number_format($p['price']); ?><?php echo $p['listing_type']=='Rent'?'/month':''; ?></h5>
                        <p class="text-muted small mb-3">Listing <?php echo htmlspecialchars($p['listing_ref']); ?></p>
                        <a href="property_detail.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-dark btn-sm px-4">View details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($properties)): ?>
                <div class="alert alert-warning">No approved properties match your filter criteria.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>