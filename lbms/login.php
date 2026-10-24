<?php include 'includes/header.php'; ?>
<div class="container py-5">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <span class="text-gold fw-bold small text-uppercase">Welcome back to LBMS</span>
            <h2 class="fw-bold mt-1 mb-3">One account for your property journey.</h2>
            <p class="text-muted mb-4">Sign in to continue conversations, manage listings or keep track of your next site visit.</p>
            <div class="card border-0 shadow-sm p-3 mb-3">
                <h6 class="fw-bold mb-1">Your account, your workspace</h6>
                <p class="small text-muted mb-0">Buyers follow inquiries and appointments. Sellers/Owners manage their land information. Brokers coordinate listings and property conversations.</p>
            </div>
            <div class="alert alert-info small mb-0">
                <strong>Demo Logins (Password for all: <code>password</code>):</strong><br>
                &bull; Broker: <code>nadia.rahman@example.com</code><br>
                &bull; Buyer: <code>arif.hossain@example.com</code><br>
                &bull; Admin: <code>admin@example.com</code>
            </div>
        </div>
        <div class="col-lg-5 offset-lg-1">
            <div class="card border-0 shadow p-4">
                <h3 class="fw-bold mb-1">Sign in</h3>
                <p class="text-muted small mb-4">Enter the email address linked to your account.</p>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger small"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>
                <form action="actions/auth_action.php" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email address</label>
                        <input type="email" name="email" class="form-control" value="nadia.rahman@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" value="password" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4 small">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked id="rem">
                            <label class="form-check-label" for="rem">Remember me</label>
                        </div>
                        <span class="text-gold fw-semibold">Forgot password?</span>
                    </div>
                    <button type="submit" class="btn btn-gold w-100 py-2 mb-3">Sign in</button>
                    <div class="text-center">
                        <a href="register.php" class="small fw-bold text-gold text-decoration-none">New to LBMS? Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>