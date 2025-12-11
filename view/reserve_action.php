<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/reserve.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/ReservationController.php';
$userId = $_SESSION['user_id'];
$bookId = $_POST['book_id'];
$expiry = $_POST['expiry_date'];
$controller = new ReservationController();
$ok = $controller->reserveBook($userId, $bookId, $expiry);
if ($ok) header('Location: /LIBRARYSYSTEM/view/reserve.php?msg=Reserved');
else header('Location: /LIBRARYSYSTEM/view/reserve.php?msg=Failed');
exit();
