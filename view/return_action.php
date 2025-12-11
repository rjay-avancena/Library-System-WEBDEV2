<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/return.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BorrowController.php';
$borrowId = $_POST['borrow_id'];
$controller = new BorrowController();
$result = $controller->returnBook($borrowId);
if ($result['success']) {
    $msg = 'Returned successfully';
    if (!empty($result['late_days'])) $msg .= ' — Late by ' . $result['late_days'] . ' days. Penalty issued.';
    header('Location: /LIBRARYSYSTEM/view/return.php?msg=' . urlencode($msg));
    exit();
} else {
    header('Location: /LIBRARYSYSTEM/view/return.php?msg=' . urlencode($result['message']));
    exit();
}
