<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/facilitate_borrow.php');
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BorrowController.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../config/db.php';

$user_name = $_POST['user_name'];
$book_title = $_POST['book_title'];
$dueDate = $_POST['due_date'];

// Find user by name
$parts = explode(' ', trim($user_name), 2);
$firstName = $parts[0];
$lastName = isset($parts[1]) ? $parts[1] : '';

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare('SELECT * FROM Users WHERE First_Name LIKE ? AND Last_Name LIKE ? LIMIT 1');
$stmt->execute(['%' . $firstName . '%', '%' . $lastName . '%']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: /LIBRARYSYSTEM/view/facilitate_borrow.php?msg=User not found');
    exit();
}

// Find book by title
$stmt2 = $conn->prepare('SELECT * FROM Book WHERE Title LIKE ? AND Status = "Available" LIMIT 1');
$stmt2->execute(['%' . $book_title . '%']);
$book = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header('Location: /LIBRARYSYSTEM/view/facilitate_borrow.php?msg=Book not found or not available');
    exit();
}

$controller = new BorrowController();
$result = $controller->borrowBook($user['User_ID'], $book['Book_ID'], null, $dueDate, $_SESSION['user_id']);
if (is_array($result)) {
    if ($result['success']) {
        header('Location: /LIBRARYSYSTEM/view/facilitate_borrow.php?msg=Borrowed for ' . htmlspecialchars($user['First_Name']));
    } else {
        header('Location: /LIBRARYSYSTEM/view/facilitate_borrow.php?msg=' . urlencode($result['message']));
    }
} else {
    header('Location: /LIBRARYSYSTEM/view/facilitate_borrow.php?msg=Failed');
}
exit();
