<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/borrower_status.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/PenaltyController.php';
$penaltyId = isset($_POST['penalty_id']) ? (int)$_POST['penalty_id'] : 0;
$method = isset($_POST['method']) ? $_POST['method'] : 'Cash';
$processedBy = $_SESSION['user_id'];
$controller = new PenaltyController();
$ok = $controller->markPenaltyPaid($penaltyId, $processedBy, $method);
if ($ok) {
    header('Location: /LIBRARYSYSTEM/view/borrower_status.php?msg=Penalty marked paid');
} else {
    header('Location: /LIBRARYSYSTEM/view/borrower_status.php?msg=Failed to mark paid');
}
exit();
