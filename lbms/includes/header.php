<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LBMS - Land Brokerage Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary-dark py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="fas fa-home text-gold fs-3 me-2"></i>
            <div class="lh-1">
                <span class="fw-bold fs-4">LBMS</span><br>
                <small style="font-size: 0.65rem; opacity: 0.85;">Land Brokerage Management System</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link <?php echo $current_page=='index.php'?'active text-white fw-bold':''; ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $current_page=='properties.php'?'active text-white fw-bold':''; ?>" href="properties.php">Properties</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $current_page=='about.php'?'active text-white fw-bold':''; ?>" href="about.php">About & Guide</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-white-50 small me-2 d-none d-md-inline">
                        <i class="fas fa-user-circle text-gold"></i> <?php echo htmlspecialchars($_SESSION['full_name']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                    </span>
                    <a href="dashboard.php" class="btn btn-outline-light btn-sm">Workspace</a>
                    <a href="profile.php" class="btn btn-outline-light btn-sm">My Profile</a>
                    <a href="actions/auth_action.php?action=logout" class="btn btn-danger btn-sm">Sign Out</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light btn-sm px-3"><i class="far fa-user me-1"></i> Sign in</a>
                    <a href="register.php" class="btn btn-gold btn-sm px-3">Create account</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?php if (isset($_SESSION['user_id'])): ?>
<div class="workspace-subnav">
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
        <ul class="nav">
            <li class="nav-item"><a class="nav-link <?php echo $current_page=='dashboard.php'?'active':''; ?>" href="dashboard.php">Overview</a></li>
            <?php if ($_SESSION['role'] == 'Broker' || $_SESSION['role'] == 'Seller' || $_SESSION['role'] == 'Administrator'): ?>
                <li class="nav-item"><a class="nav-link <?php echo $current_page=='add_property.php'?'active':''; ?>" href="add_property.php">Add property</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link <?php echo $current_page=='inquiries.php'?'active':''; ?>" href="inquiries.php">Inquiries & appointments</a></li>
            <?php if ($_SESSION['role'] == 'Administrator' || $_SESSION['role'] == 'Broker'): ?>
                <li class="nav-item"><a class="nav-link <?php echo $current_page=='verification.php'?'active':''; ?>" href="verification.php">Listing Verification</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link <?php echo $current_page=='profile.php'?'active':''; ?>" href="profile.php">My profile</a></li>
        </ul>
        <a href="index.php" class="text-muted small text-decoration-none py-2">Public website &rarr;</a>
    </div>
</div>
<?php endif; ?>
<main class="flex-grow-1">