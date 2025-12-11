<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/manage_clearance.php');
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/ClearanceController.php';
$clearanceId = isset($_POST['clearance_id']) ? (int)$_POST['clearance_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$controller = new ClearanceController();
$staffId = $_SESSION['user_id'];
if (!$clearanceId) {
    header('Location: /LIBRARYSYSTEM/view/manage_clearance.php?msg=Invalid');
    exit();
}
if ($action === 'approve') {
    $ok = $controller->finalizeClearance($clearanceId, 'Approved', $staffId, 'Approved by staff');
    if ($ok) header('Location: /LIBRARYSYSTEM/view/manage_clearance.php?msg=Approved');
    else header('Location: /LIBRARYSYSTEM/view/manage_clearance.php?msg=Failed');
    exit();
} else {
    $ok = $controller->finalizeClearance($clearanceId, 'Not Cleared', $staffId, 'Marked not cleared');
    if ($ok) header('Location: /LIBRARYSYSTEM/view/manage_clearance.php?msg=Marked not cleared');
    else header('Location: /LIBRARYSYSTEM/view/manage_clearance.php?msg=Failed');
    exit();
}
?>