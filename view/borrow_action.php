<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/borrow.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BorrowController.php';
$userId = $_SESSION['user_id'];
$bookId = $_POST['book_id'];
$dueDate = $_POST['due_date'];
$processedBy = $userId; // user processed themselves (could be staff)
$controller = new BorrowController();
$result = $controller->borrowBook($userId, $bookId, null, $dueDate, $processedBy);
if (is_array($result)) {
    if ($result['success']) {
        header('Location: /LIBRARYSYSTEM/view/borrow.php?msg=Borrowed successfully');
        exit();
    } else {
        header('Location: /LIBRARYSYSTEM/view/borrow.php?msg=' . urlencode($result['message']));
        exit();
    }
} else {
    if ($result) header('Location: /LIBRARYSYSTEM/view/borrow.php?msg=Borrowed');
    else header('Location: /LIBRARYSYSTEM/view/borrow.php?msg=Failed to borrow');
}
