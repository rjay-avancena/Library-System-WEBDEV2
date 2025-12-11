<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/manage_archived_books.php');
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BookController.php';
$bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
$controller = new BookController();
$ok = $controller->restoreBook($bookId);
if ($ok) {
    header('Location: /LIBRARYSYSTEM/view/manage_archived_books.php?msg=Book restored successfully');
} else {
    header('Location: /LIBRARYSYSTEM/view/manage_archived_books.php?msg=Failed to restore book');
}
exit();
