<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<div class="container py-4">
    <span class="text-gold fw-bold small text-uppercase">Account Settings</span>
    <h2 class="fw-bold mb-1">My profile</h2>
    <p class="text-muted small mb-4">Keep your details current so buyers and property owners can reach you.</p>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success small"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="actions/auth_action.php" method="POST" class="card border-0 shadow-sm p-4">
                <input type="hidden" name="action" value="update_profile">
                <h5 class="fw-bold mb-3">Personal information</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Full name</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Email address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Phone number</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Location</label>
                        <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($user['location']); ?>">
                    </div>
                </div>

                <h5 class="fw-bold mb-3">Notification preferences</h5>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" checked id="n1">
                    <label class="form-check-label small" for="n1">Email me about new inquiries</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" checked id="n2">
                    <label class="form-check-label small" for="n2">Email me about appointment changes</label>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" checked id="n3">
                    <label class="form-check-label small" for="n3">Email me about listing review updates</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold px-4">Save changes</button>
                    <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-2">Your account</h6>
                <span class="badge bg-primary-dark w-auto align-self-start mb-2"><?php echo htmlspecialchars($user['role']); ?></span>
                <p class="fw-bold mb-1"><?php echo htmlspecialchars($user['full_name']); ?> &bull; Account <?php echo htmlspecialchars($user['account_code'] ?? 'BR-0024'); ?></p>
                <small class="text-muted d-block mb-3">Role changes are managed by an administrator, not through this form.</small>
                <div class="bg-light p-3 rounded small">
                    <strong>4 associated listings</strong><br>
                    1 Approved &bull; 1 Draft &bull; 1 Pending Verification &bull; 1 Sold
                    <a href="dashboard.php" class="d-block text-gold fw-bold mt-2 text-decoration-none">Manage my listings &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>