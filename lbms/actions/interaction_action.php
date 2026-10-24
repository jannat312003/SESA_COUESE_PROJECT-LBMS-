<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $uid = $_SESSION['user_id'] ?? 3;

    if ($action === 'send_inquiry') {
        $prop_id = intval($_POST['property_id']);
        $rec_id = intval($_POST['recipient_id']);
        $msg = trim($_POST['message']);
        $ref = 'INQ-' . rand(100, 999);
        $stmt = $pdo->prepare("INSERT INTO inquiries (inquiry_ref, property_id, sender_id, recipient_id, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$ref, $prop_id, $uid, $rec_id, $msg]);
        header("Location: ../property_detail.php?id=$prop_id&msg=Inquiry sent to broker!");
        exit;
    }

    if ($action === 'request_appointment') {
        $prop_id = intval($_POST['property_id']);
        $broker_id = intval($_POST['broker_id']);
        $date = $_POST['appt_date'];
        $time = trim($_POST['appt_time']);
        $ref = 'APT-' . rand(100, 999);
        $stmt = $pdo->prepare("INSERT INTO appointments (appt_ref, property_id, buyer_id, broker_id, appt_date, appt_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$ref, $prop_id, $uid, $broker_id, $date, $time]);
        header("Location: ../property_detail.php?id=$prop_id&msg=Appointment requested for $date at $time!");
        exit;
    }

    if ($action === 'reply_inquiry') {
        $inq_id = intval($_POST['inquiry_id']);
        $reply = trim($_POST['reply_text']);
        $pdo->prepare("UPDATE inquiries SET broker_reply = ?, status = 'Replied' WHERE id = ?")->execute([$reply, $inq_id]);
        header("Location: ../inquiries.php?msg=Reply saved and sent!");
        exit;
    }

    if ($action === 'update_appt') {
        $appt_id = intval($_POST['appt_id']);
        $status = $_POST['status'];
        $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?")->execute([$status, $appt_id]);
        header("Location: ../inquiries.php?msg=Appointment updated to $status");
        exit;
    }

    if ($action === 'verify_property') {
        $prop_id = intval($_POST['property_id']);
        $status = $_POST['new_status'];
        $pdo->prepare("UPDATE properties SET status = ? WHERE id = ?")->execute([$status, $prop_id]);
        $pdo->prepare("INSERT INTO verifications (property_id, verifier_id, status) VALUES (?, ?, ?)")->execute([$prop_id, $uid, $status]);
        header("Location: ../verification.php?msg=Property status updated to $status");
        exit;
    }
}
?>