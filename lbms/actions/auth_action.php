<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $email = trim($_POST['email']);
        $pass = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../dashboard.php");
            exit;
        }
        header("Location: ../login.php?error=Invalid email or password");
        exit;
    }

    if ($action === 'register') {
        $name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role = $_POST['role'] ?? 'Buyer';
        $pass = $_POST['password'];
        $conf = $_POST['confirm_password'];

        if ($pass !== $conf) {
            header("Location: ../register.php?error=Passwords do not match");
            exit;
        }
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header("Location: ../register.php?error=Email already registered");
            exit;
        }
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $code = strtoupper(substr($role, 0, 2)) . '-' . rand(1000, 9999);
        $ins = $pdo->prepare("INSERT INTO users (account_code, full_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)");
        $ins->execute([$code, $name, $email, $phone, $hash, $role]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['full_name'] = $name;
        $_SESSION['role'] = $role;
        header("Location: ../dashboard.php");
        exit;
    }

    if ($action === 'update_profile' && isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, location = ? WHERE id = ?");
        $stmt->execute([trim($_POST['full_name']), trim($_POST['email']), trim($_POST['phone']), trim($_POST['location']), $_SESSION['user_id']]);
        $_SESSION['full_name'] = trim($_POST['full_name']);
        header("Location: ../profile.php?msg=Profile updated successfully");
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>