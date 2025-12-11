<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/archive_book.php');
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BookController.php';
$bookId = $_POST['book_id'];
$controller = new BookController();
$ok = $controller->archiveBook($bookId);
if ($ok) header('Location: /LIBRARYSYSTEM/view/archive_book.php?msg=Book archived');
else header('Location: /LIBRARYSYSTEM/view/archive_book.php?msg=Archive failed');
exit();
