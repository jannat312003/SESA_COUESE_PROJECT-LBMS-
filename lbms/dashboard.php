<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 'Administrator') {
    $stmt = $pdo->query("SELECT * FROM properties ORDER BY id DESC");
} elseif ($role == 'Broker' || $role == 'Seller') {
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE broker_id = ? OR owner_id = ? ORDER BY id DESC");
    $stmt->execute([$uid, $uid]);
} else {
    $stmt = $pdo->query("SELECT * FROM properties WHERE status = 'Approved' ORDER BY id DESC LIMIT 4");
}
$my_listings = $stmt->fetchAll();

$inq_count = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$appt_count = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$tx_stmt = $pdo->query("SELECT t.*, p.listing_ref, p.location, u_b.full_name as buyer_name, u_s.full_name as seller_name, u_br.full_name as broker_name FROM transactions t LEFT JOIN properties p ON t.property_id = p.id LEFT JOIN users u_b ON t.buyer_id = u_b.id LEFT JOIN users u_s ON t.seller_id = u_s.id LEFT JOIN users u_br ON t.broker_id = u_br.id ORDER BY t.id DESC");
$transactions = $tx_stmt->fetchAll();
?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <span class="text-gold fw-bold small text-uppercase"><?php echo htmlspecialchars($role); ?> Account &bull; October 2026</span>
            <h2 class="fw-bold mb-0">Your <?php echo strtolower(htmlspecialchars($role)); ?> workspace</h2>
            <p class="text-muted small mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>. Manage your listings, respond to buyers and keep clear records.</p>
        </div>
        <?php if ($role != 'Buyer'): ?>
            <a href="add_property.php" class="btn btn-gold"><i class="fas fa-plus me-1"></i> Add property</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success small"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3"><small class="text-muted">My listings</small><h3 class="fw-bold mb-0"><?php echo count($my_listings); ?></h3><small class="text-success">Active & tracked</small></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3"><small class="text-muted">Open inquiries</small><h3 class="fw-bold mb-0"><?php echo $inq_count; ?></h3><small class="text-warning">2 awaiting your reply</small></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3"><small class="text-muted">Appointments</small><h3 class="fw-bold mb-0"><?php echo $appt_count; ?></h3><small class="text-primary">1 needs confirmation</small></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3"><small class="text-muted">Transactions</small><h3 class="fw-bold mb-0"><?php echo count($transactions); ?></h3><small class="text-muted">Completed record</small></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3">My listings</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light small">
                            <tr><th>Property</th><th>Price / area</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_listings as $l): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($l['title']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($l['listing_ref']); ?> &bull; <?php echo htmlspecialchars($l['location']); ?></small>
                                </td>
                                <td>
                                    ৳ <?php echo number_format($l['price']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($l['size'].' '.$l['unit']); ?></small>
                                </td>
                                <td>
                                    <?php
                                    $badge = 'bg-secondary';
                                    if ($l['status']=='Approved') $badge='bg-success';
                                    if ($l['status']=='Pending Verification') $badge='bg-warning text-dark';
                                    if ($l['status']=='Sold') $badge='bg-dark';
                                    ?>
                                    <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($l['status']); ?></span>
                                </td>
                                <td><a href="property_detail.php?id=<?php echo $l['id']; ?>" class="btn btn-outline-dark btn-sm">View listing</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3">Transaction & commission records</h5>
                <?php foreach ($transactions as $tx): ?>
                <div class="border rounded p-3 bg-light mb-2">
                    <div class="d-flex justify-content-between">
                        <strong><?php echo htmlspecialchars($tx['tx_ref']); ?> &bull; <?php echo htmlspecialchars($tx['listing_ref']); ?>, <?php echo htmlspecialchars($tx['location']); ?></strong>
                        <span class="badge bg-success"><?php echo htmlspecialchars($tx['status']); ?> <?php echo htmlspecialchars($tx['completed_date']); ?></span>
                    </div>
                    <p class="small text-muted mb-1">Buyer: <?php echo htmlspecialchars($tx['buyer_name']); ?> &bull; Seller: <?php echo htmlspecialchars($tx['seller_name']); ?> &bull; Broker: <?php echo htmlspecialchars($tx['broker_name']); ?></p>
                    <p class="small fw-bold mb-0">Deal: BDT <?php echo number_format($tx['amount']); ?> &bull; Agreed commission: <?php echo $tx['commission_pct']; ?>% (BDT <?php echo number_format($tx['commission_amount']); ?>)</p>
                </div>
                <?php endforeach; ?>
                <small class="text-muted mt-2">Recordkeeping only: LBMS does not process payments or collect commissions.</small>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">Upcoming activity</h6>
                <div class="border-start border-3 border-success ps-3 mb-3">
                    <strong class="small d-block">17 Oct 11:00 AM - Tongi site visit</strong>
                    <span class="text-muted small">Confirmed &bull; Buyer: Arif Hossain (LB-1043)</span>
                </div>
                <div class="border-start border-3 border-warning ps-3">
                    <strong class="small d-block">18 Oct 10:00 AM - Bashundhara visit request</strong>
                    <span class="text-muted small">Pending &bull; Arif Hossain</span>
                    <a href="inquiries.php" class="d-block small text-gold fw-bold text-decoration-none">Review request &rarr;</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Notifications & review follow-up</h6>
                <div class="mb-3 pb-2 border-bottom">
                    <strong class="small d-block">New inquiry on LB-1042</strong>
                    <span class="text-muted small">Arif Hossain asked about road access.</span>
                    <a href="inquiries.php" class="small text-gold fw-bold d-block text-decoration-none">Reply &rarr;</a>
                </div>
                <div>
                    <strong class="small d-block">LB-1047 Supporting document check pending</strong>
                    <span class="text-muted small">Track your submission; an authorized reviewer decides approval.</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>