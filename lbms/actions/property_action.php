<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref = 'LB-' . rand(1050, 9999);
    $uid = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $cat = $_POST['category'];
    $type = $_POST['listing_type'];
    $loc = trim($_POST['location']);
    $size = floatval($_POST['size']);
    $unit = trim($_POST['unit']);
    $price = floatval($_POST['price']);
    $desc = trim($_POST['description']);
    $status = $_POST['status'] ?? 'Pending Verification';

    $stmt = $pdo->prepare("INSERT INTO properties (listing_ref, owner_id, broker_id, title, location, category, listing_type, size, unit, price, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$ref, $uid, $uid, $title, $loc, $cat, $type, $size, $unit, $price, $desc, $status]);
    $prop_id = $pdo->lastInsertId();

    $img_path = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80';
    if (!empty($_FILES['property_image']['name']) && $_FILES['property_image']['error'] === 0) {
        $fname = time() . '_' . basename($_FILES['property_image']['name']);
        $target = '../assets/uploads/images/' . $fname;
        if (move_uploaded_file($_FILES['property_image']['tmp_name'], $target)) {
            $img_path = 'assets/uploads/images/' . $fname;
        }
    }
    $pdo->prepare("INSERT INTO property_images (property_id, file_path, is_primary) VALUES (?, ?, 1)")->execute([$prop_id, $img_path]);

    if (!empty($_FILES['document_file']['name']) && $_FILES['document_file']['error'] === 0) {
        $dname = time() . '_' . basename($_FILES['document_file']['name']);
        $dtarget = '../assets/uploads/documents/' . $dname;
        move_uploaded_file($_FILES['document_file']['tmp_name'], $dtarget);
        $pdo->prepare("INSERT INTO documents (property_id, user_id, file_name, file_path) VALUES (?, ?, ?, ?)")->execute([$prop_id, $uid, $_FILES['document_file']['name'], 'assets/uploads/documents/' . $dname]);
    }

    header("Location: ../dashboard.php?msg=Listing $ref saved with status: $status");
    exit;
}
?>