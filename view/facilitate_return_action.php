<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/facilitate_return.php');
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BorrowController.php';
require_once __DIR__ . '/../config/db.php';

$user_name = $_POST['user_name'];
$book_title = $_POST['book_title'];

// Find user and book, then find the borrow record
$parts = explode(' ', trim($user_name), 2);
$firstName = $parts[0];
$lastName = isset($parts[1]) ? $parts[1] : '';

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare('SELECT * FROM Users WHERE First_Name LIKE ? AND Last_Name LIKE ? LIMIT 1');
$stmt->execute(['%' . $firstName . '%', '%' . $lastName . '%']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: /LIBRARYSYSTEM/view/facilitate_return.php?msg=User not found');
    exit();
}

$stmt2 = $conn->prepare('SELECT * FROM Book WHERE Title LIKE ? LIMIT 1');
$stmt2->execute(['%' . $book_title . '%']);
$book = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header('Location: /LIBRARYSYSTEM/view/facilitate_return.php?msg=Book not found');
    exit();
}

// Find active borrow record
$stmt3 = $conn->prepare('SELECT * FROM Borrow WHERE User_ID = ? AND Book_ID = ? AND Status = "Borrowed" LIMIT 1');
$stmt3->execute([$user['User_ID'], $book['Book_ID']]);
$borrow = $stmt3->fetch(PDO::FETCH_ASSOC);

if (!$borrow) {
    header('Location: /LIBRARYSYSTEM/view/facilitate_return.php?msg=No active borrow found for this user and book');
    exit();
}

$controller = new BorrowController();
$result = $controller->returnBook($borrow['Borrow_ID'], $_SESSION['user_id']);
if ($result['success']) {
    $msg = 'Returned. Late days: ' . $result['late_days'];
    header('Location: /LIBRARYSYSTEM/view/facilitate_return.php?msg=' . urlencode($msg));
} else {
    header('Location: /LIBRARYSYSTEM/view/facilitate_return.php?msg=' . urlencode($result['message']));
}
exit();
