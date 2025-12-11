<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../model/Book.php';
$bookModel = new Book();
$books = $bookModel->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Inventory - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Book Inventory</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <table class="table">
            <tr><th>Title</th><th>Author</th><th>Total</th><th>Available</th><th>Price</th><th>Status</th></tr>
            <?php foreach ($books as $b): ?>
                <tr>
                    <td><?php echo htmlspecialchars($b['Title']); ?></td>
                    <td><?php echo htmlspecialchars($b['Author']); ?></td>
                    <td><?php echo (int)$b['Total_Copies']; ?></td>
                    <td><?php echo (int)$b['Copies_Available']; ?></td>
                    <td><?php echo number_format($b['Book_Price'],2); ?></td>
                    <td><?php echo htmlspecialchars($b['Status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
