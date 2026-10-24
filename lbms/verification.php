<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'Administrator' && $_SESSION['role'] != 'Broker')) {
    header("Location: login.php");
    exit;
}

$pending = $pdo->query("SELECT p.*, u.full_name as owner_name FROM properties p LEFT JOIN users u ON p.owner_id = u.id ORDER BY p.id DESC")->fetchAll();
?>
<div class="container py-4">
    <span class="text-gold fw-bold small text-uppercase">Verification Workflow (FR-VER-01 to 05)</span>
    <h2 class="fw-bold mb-3">Property Verification & Approval</h2>
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success small"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>
    <div class="card border-0 shadow-sm p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light small">
                    <tr><th>Ref</th><th>Title & Location</th><th>Owner</th><th>Price</th><th>Current Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($pending as $p): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($p['listing_ref']); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['title']); ?><br><small class="text-muted"><?php echo htmlspecialchars($p['location']); ?></small></td>
                        <td><?php echo htmlspecialchars($p['owner_name']); ?></td>
                        <td>৳ <?php echo number_format($p['price']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($p['status']); ?></span></td>
                        <td>
                            <form action="actions/interaction_action.php" method="POST" class="d-flex gap-1">
                                <input type="hidden" name="action" value="verify_property">
                                <input type="hidden" name="property_id" value="<?php echo $p['id']; ?>">
                                <button name="new_status" value="Approved" class="btn btn-success btn-sm">Approve</button>
                                <button name="new_status" value="Rejected" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>