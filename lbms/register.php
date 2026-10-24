<?php include 'includes/header.php'; ?>
<div class="container py-5">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm p-4 p-md-5">
            <h3 class="fw-bold mb-1">Create your LBMS account</h3>
            <p class="text-muted small mb-4">Choose how you will use the system. You can update your contact information later.</p>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger small"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <form action="actions/auth_action.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Full name *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Arif Hossain" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Email address *</label>
                        <input type="email" name="email" class="form-control" placeholder="arif.hossain@example.com" required>
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Phone number *</label>
                        <input type="text" name="phone" class="form-control" placeholder="+880 1812-345678" required>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block"><strong>Keep your contact details up to date</strong><br>Brokers use these details to respond to inquiries and coordinate appointments.</small>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Password *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Confirm password *</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                </div>
                <label class="form-label small fw-bold">I'm joining as *</label>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="border rounded p-3 d-block h-100 bg-light">
                            <input type="radio" name="role" value="Buyer" checked class="form-check-input me-2"><strong>Buyer</strong>
                            <small class="d-block text-muted mt-1">Find land and arrange visits.</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="border rounded p-3 d-block h-100 bg-light">
                            <input type="radio" name="role" value="Seller" class="form-check-input me-2"><strong>Seller / Owner</strong>
                            <small class="d-block text-muted mt-1">Share and manage your property.</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="border rounded p-3 d-block h-100 bg-light">
                            <input type="radio" name="role" value="Broker" class="form-check-input me-2"><strong>Broker</strong>
                            <small class="d-block text-muted mt-1">Manage listings and inquiries.</small>
                        </label>
                    </div>
                </div>
                <small class="text-muted d-block mb-3">Administrator accounts are assigned internally and cannot be created here.</small>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" required id="terms">
                    <label class="form-check-label small" for="terms">I agree to the terms of use and understand the document privacy policy.</label>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-gold px-5 py-2">Create account</button>
                    <a href="login.php" class="small fw-bold text-gold text-decoration-none">Already registered? Sign in</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>