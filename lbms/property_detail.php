<?php
require_once 'config/db.php';
include 'includes/header.php';

$id = intval($_GET['id'] ?? 1);
$stmt = $pdo->prepare("
    SELECT p.*, u.full_name AS broker_name, u.phone AS broker_phone, u.email AS broker_email, pi.file_path
    FROM properties p
    LEFT JOIN users u ON p.broker_id = u.id OR p.owner_id = u.id
    LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_primary = 1
    WHERE p.id = ?
");
$stmt->execute([$id]);
$prop = $stmt->fetch();

if (!$prop) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Property not found.</div></div>";
    include 'includes/footer.php';
    exit;
}
?>
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="properties.php" class="text-decoration-none">Properties</a></li>
            <li class="breadcrumb-item"><?php echo htmlspecialchars($prop['location']); ?></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($prop['listing_ref']); ?></li>
        </ol>
    </nav>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <img src="<?php echo htmlspecialchars($prop['file_path']); ?>" class="img-fluid rounded-3 shadow-sm w-100 mb-4" style="max-height:420px; object-fit:cover;" alt="Property">
            
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                <div>
                    <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($prop['title']); ?></h2>
                    <p class="text-muted mb-0"><?php echo htmlspecialchars($prop['location']); ?> &bull; <?php echo htmlspecialchars($prop['size'].' '.$prop['unit']); ?></p>
                </div>
                <div class="text-end">
                    <h3 class="fw-bold text-dark mb-1">৳ <?php echo number_format($prop['price']); ?></h3>
                    <span class="badge bg-success me-1">For <?php echo htmlspecialchars($prop['listing_type']); ?></span>
                    <span class="badge bg-light text-success border"><i class="fas fa-check me-1"></i>Listing reviewed</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3">About this property</h5>
                <p class="text-secondary"><?php echo nl2br(htmlspecialchars($prop['description'])); ?></p>
                
                <div class="row g-3 pt-3 border-top mt-2">
                    <div class="col-md-4"><small class="text-muted d-block">Category</small><strong><?php echo htmlspecialchars($prop['category']); ?></strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Area</small><strong><?php echo htmlspecialchars($prop['size'].' '.$prop['unit']); ?></strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Availability</small><strong>Available for <?php echo strtolower($prop['listing_type']); ?></strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Listing reference</small><strong><?php echo htmlspecialchars($prop['listing_ref']); ?></strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Listed by</small><strong><?php echo htmlspecialchars($prop['broker_name']); ?>, Broker</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Status</small><strong><?php echo htmlspecialchars($prop['status']); ?></strong></div>
                </div>
            </div>

            <div class="alert alert-warning small border-0">
                <strong>Internal review is not government title certification.</strong> Independently verify ownership and legal records. Ownership documents stay private and are available only to permitted users, not as public downloads.
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-1">Contact the broker</h5>
                <p class="fw-semibold mb-0"><?php echo htmlspecialchars($prop['broker_name']); ?></p>
                <small class="text-muted d-block mb-2">Broker Responsible for listing <?php echo htmlspecialchars($prop['listing_ref']); ?></small>
                <p class="small mb-1"><i class="fas fa-phone me-2 text-muted"></i><?php echo htmlspecialchars($prop['broker_phone']); ?></p>
                <p class="small mb-3"><i class="fas fa-envelope me-2 text-muted"></i><?php echo htmlspecialchars($prop['broker_email']); ?></p>

                <hr>
                <h6 class="fw-bold mb-2">Send an inquiry</h6>
                <form action="actions/interaction_action.php" method="POST" class="mb-4">
                    <input type="hidden" name="action" value="send_inquiry">
                    <input type="hidden" name="property_id" value="<?php echo $prop['id']; ?>">
                    <input type="hidden" name="recipient_id" value="<?php echo $prop['broker_id'] ?? $prop['owner_id']; ?>">
                    <div class="mb-2">
                        <label class="form-label small">Your message</label>
                        <textarea name="message" class="form-control form-control-sm" rows="3" required>I'm interested in this plot. Could you share the road access details?</textarea>
                    </div>
                    <button type="submit" class="btn btn-gold w-100 btn-sm py-2">Send inquiry</button>
                </form>

                <h6 class="fw-bold mb-2">Request a site visit</h6>
                <form action="actions/interaction_action.php" method="POST">
                    <input type="hidden" name="action" value="request_appointment">
                    <input type="hidden" name="property_id" value="<?php echo $prop['id']; ?>">
                    <input type="hidden" name="broker_id" value="<?php echo $prop['broker_id'] ?? $prop['owner_id']; ?>">
                    <div class="mb-2">
                        <label class="form-label small">Preferred date</label>
                        <input type="date" name="appt_date" class="form-control form-control-sm" value="2026-10-18" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Preferred time (Bangladesh time)</label>
                        <input type="text" name="appt_time" class="form-control form-control-sm" value="10:00 AM" required>
                    </div>
                    <button type="submit" class="btn btn-outline-dark w-100 btn-sm py-2">Request appointment</button>
                    <small class="text-muted d-block mt-2" style="font-size:0.75rem;">Sign in to send a message or request a visit. A visit is confirmed only after the broker accepts it.</small>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>