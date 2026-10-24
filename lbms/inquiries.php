<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$inquiries = $pdo->query("
    SELECT i.*, p.listing_ref, p.location, u.full_name AS sender_name 
    FROM inquiries i 
    LEFT JOIN properties p ON i.property_id = p.id 
    LEFT JOIN users u ON i.sender_id = u.id 
    ORDER BY i.id DESC
")->fetchAll();

$appointments = $pdo->query("
    SELECT a.*, p.listing_ref, p.location, u.full_name AS buyer_name 
    FROM appointments a 
    LEFT JOIN properties p ON a.property_id = p.id 
    LEFT JOIN users u ON a.buyer_id = u.id 
    ORDER BY a.id DESC
")->fetchAll();

$docs = $pdo->query("
    SELECT d.*, p.listing_ref, p.location 
    FROM documents d 
    LEFT JOIN properties p ON d.property_id = p.id
")->fetchAll();
?>
<div class="container py-4">
    <span class="text-gold fw-bold small text-uppercase">Broker Workspace</span>
    <h2 class="fw-bold mb-1">Inquiries & appointments</h2>
    <p class="text-muted small mb-4">Keep every conversation and site visit connected to the right property.</p>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success small"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">Inquiries</h5>
        <div class="row g-4">
            <div class="col-md-5 border-end">
                <?php foreach ($inquiries as $inq): ?>
                <div class="p-3 border rounded mb-2 bg-light">
                    <div class="d-flex justify-content-between">
                        <strong><?php echo htmlspecialchars($inq['sender_name']); ?></strong>
                        <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($inq['status']); ?></span>
                    </div>
                    <div class="small fw-semibold"><?php echo htmlspecialchars($inq['subject']); ?></div>
                    <small class="text-muted"><?php echo htmlspecialchars($inq['location']); ?> &bull; <?php echo htmlspecialchars($inq['listing_ref']); ?></small>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-7">
                <?php if (!empty($inquiries)): $active = $inquiries[0]; ?>
                <div class="p-3 border rounded">
                    <div class="small text-muted mb-2">
                        <strong>From:</strong> <?php echo htmlspecialchars($active['sender_name']); ?> &bull; 
                        <strong>Ref:</strong> <?php echo htmlspecialchars($active['inquiry_ref'] ?? 'INQ-086'); ?> (<?php echo htmlspecialchars($active['listing_ref']); ?>)
                    </div>
                    <p class="bg-light p-3 rounded small"><?php echo nl2br(htmlspecialchars($active['message'])); ?></p>
                    <form action="actions/interaction_action.php" method="POST">
                        <input type="hidden" name="action" value="reply_inquiry">
                        <input type="hidden" name="inquiry_id" value="<?php echo $active['id']; ?>">
                        <label class="form-label small fw-bold">Reply to <?php echo htmlspecialchars($active['sender_name']); ?></label>
                        <textarea name="reply_text" class="form-control form-control-sm mb-2" rows="3" required placeholder="Write a helpful reply..."><?php echo htmlspecialchars($active['broker_reply'] ?? ''); ?></textarea>
                        <button type="submit" class="btn btn-gold btn-sm">Send reply</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">Appointments</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light small">
                    <tr><th>Date & time</th><th>Property / buyer</th><th>Status</th><th>Broker action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $ap): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($ap['appt_date']); ?> &bull; <?php echo htmlspecialchars($ap['appt_time']); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($ap['appt_ref'] ?? 'APT'); ?> &bull; <?php echo htmlspecialchars($ap['notes'] ?? ''); ?></small>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($ap['location']); ?> (<?php echo htmlspecialchars($ap['listing_ref']); ?>)</strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($ap['buyer_name']); ?></small>
                        </td>
                        <td>
                            <span class="badge <?php echo $ap['status']=='Confirmed'?'bg-success':($ap['status']=='Pending'?'bg-warning text-dark':'bg-info text-dark'); ?>">
                                <?php echo htmlspecialchars($ap['status']); ?>
                            </span>
                        </td>
                        <td>
                            <form action="actions/interaction_action.php" method="POST" class="d-inline-flex gap-1">
                                <input type="hidden" name="action" value="update_appt">
                                <input type="hidden" name="appt_id" value="<?php echo $ap['id']; ?>">
                                <button name="status" value="Confirmed" class="btn btn-success btn-sm">Accept</button>
                                <button name="status" value="Rescheduled" class="btn btn-outline-secondary btn-sm">Reschedule</button>
                                <button name="status" value="Declined" class="btn btn-outline-danger btn-sm">Decline</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <h5 class="fw-bold mb-1">Restricted document records</h5>
        <p class="small text-muted mb-3">Visible to assigned broker and permitted users only. Never public listing downloads.</p>
        <?php foreach ($docs as $d): ?>
        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2 bg-light">
            <div>
                <strong class="small"><?php echo htmlspecialchars($d['file_name']); ?></strong>
                <span class="text-muted small ms-2">(<?php echo htmlspecialchars($d['listing_ref']); ?>, <?php echo htmlspecialchars($d['location']); ?>)</span>
            </div>
            <span class="badge bg-secondary"><?php echo htmlspecialchars($d['doc_status']); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>