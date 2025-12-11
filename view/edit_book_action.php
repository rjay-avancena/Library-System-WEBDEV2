<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/update_book.php');
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BookController.php';
$bookId = $_POST['book_id'];
$data = [
    'title' => $_POST['title'],
    'author' => $_POST['author'],
    'isbn' => $_POST['isbn'],
    'category_id' => $_POST['category_id'],
    'publisher' => $_POST['publisher'],
    'book_price' => $_POST['book_price'],
    'total_copies' => $_POST['total_copies'],
    'copies_available' => $_POST['copies_available'],
    'status' => $_POST['status']
];
$controller = new BookController();
$ok = $controller->updateBook($bookId, $data);
if ($ok) header('Location: /LIBRARYSYSTEM/view/update_book.php?msg=Book updated');
else header('Location: /LIBRARYSYSTEM/view/update_book.php?msg=Update failed');
exit();
