<?php
require_once 'config/db.php';
include 'includes/header.php';

$stmt = $pdo->query("
    SELECT p.*, pi.file_path 
    FROM properties p 
    LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_primary = 1 
    WHERE p.status = 'Approved' 
    ORDER BY p.id ASC LIMIT 4
");
$featured = $stmt->fetchAll();
?>
<section class="text-white py-5" style="background: linear-gradient(rgba(17, 30, 54, 0.78), rgba(17, 30, 54, 0.78)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1920&q=80') center/cover;">
    <div class="container py-4">
        <div class="col-lg-8">
            <p class="text-gold text-uppercase fw-bold small mb-2" style="letter-spacing:1.5px;">Land Brokerage, Made Clear</p>
            <h1 class="display-4 fw-bold mb-3">Find the right land, with <span class="text-gold">confidence</span></h1>
            <p class="lead text-white-50 mb-4">Explore reviewed listings, speak with a broker and arrange a visit. A clearer starting point for your next property decision.</p>
            
            <div class="bg-white p-2 rounded-3 shadow">
                <form action="properties.php" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="location" class="form-control border-0" placeholder="Enter an area, city or property name...">
                        </div>
                    </div>
                    <div class="col-md-3 border-start">
                        <select name="category" class="form-select border-0 text-muted">
                            <option value="">All categories</option>
                            <option value="Residential">Residential</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Agricultural">Agricultural</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-gold w-100 py-2"><i class="fas fa-search me-1"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md"><i class="fas fa-home text-gold fs-3 mb-2"></i><h6 class="fw-bold mb-1">Wide Range of Properties</h6><p class="text-muted small mb-0">Find residential, commercial and agricultural lands.</p></div>
            <div class="col-6 col-md"><i class="fas fa-user-check text-gold fs-3 mb-2"></i><h6 class="fw-bold mb-1">Verified Brokers</h6><p class="text-muted small mb-0">Work with trusted and verified brokers.</p></div>
            <div class="col-6 col-md"><i class="fas fa-shield-alt text-gold fs-3 mb-2"></i><h6 class="fw-bold mb-1">Secure Transactions</h6><p class="text-muted small mb-0">Your data and deals are always protected.</p></div>
            <div class="col-6 col-md"><i class="fas fa-sliders-h text-gold fs-3 mb-2"></i><h6 class="fw-bold mb-1">Easy Search & Filter</h6><p class="text-muted small mb-0">Find the right property with advanced filters.</p></div>
            <div class="col-12 col-md"><i class="fas fa-headset text-gold fs-3 mb-2"></i><h6 class="fw-bold mb-1">Dedicated Support</h6><p class="text-muted small mb-0">Clear communication and appointment tracking.</p></div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-gold fw-bold small text-uppercase">— Featured Properties</span>
                <h2 class="fw-bold mb-0">Find Your Perfect Land</h2>
            </div>
            <a href="properties.php" class="text-dark fw-semibold text-decoration-none">View All Properties &rarr;</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featured as $prop): ?>
            <div class="col-md-6 col-lg-3">
                <div class="property-card h-100 d-flex flex-column">
                    <div class="property-img-wrapper">
                        <span class="badge-status <?php echo $prop['listing_type']=='Rent'?'bg-primary':'bg-success'; ?> text-white">
                            For <?php echo htmlspecialchars($prop['listing_type']); ?>
                        </span>
                        <img src="<?php echo htmlspecialchars($prop['file_path']); ?>" alt="Land">
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-light text-success border"><i class="fas fa-check me-1"></i>Listing reviewed</span>
                            <small class="text-muted"><?php echo htmlspecialchars($prop['listing_ref']); ?></small>
                        </div>
                        <h6 class="fw-bold mt-1 mb-1"><?php echo htmlspecialchars($prop['title']); ?></h6>
                        <p class="text-muted small mb-1"><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($prop['location']); ?></p>
                        <p class="text-muted small mb-2"><i class="fas fa-vector-square me-1"></i><?php echo htmlspecialchars($prop['size'].' '.$prop['unit']); ?></p>
                        <div class="mt-auto">
                            <h5 class="fw-bold text-dark mb-3">৳ <?php echo number_format($prop['price']); ?><?php echo $prop['listing_type']=='Rent'?' <small class="fw-normal">/month</small>':''; ?></h5>
                            <a href="property_detail.php?id=<?php echo $prop['id']; ?>" class="btn btn-outline-gold w-100 btn-sm">View Details &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="alert alert-light border mt-4 mb-0 small text-muted">
            <strong>What does "Listing reviewed" mean?</strong> Listings are checked internally before publication. This is not government title certification; independently verify ownership before any agreement.
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>