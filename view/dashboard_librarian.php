<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Librarian Dashboard - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Librarian Dashboard</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/add_book.php">Add Book</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/update_book.php">Update Book</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/inventory.php">Inventory</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/archive_book.php">Archive Book</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/manage_archived_books.php">Archived Books</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <!-- Librarian info, book management, inventory here -->
        <p>Welcome, Librarian! Manage your library here.</p>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
