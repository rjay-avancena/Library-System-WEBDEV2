<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Book - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Add Book</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if ($msg): ?>
            <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <form method="post" action="/LIBRARYSYSTEM/view/add_book_action.php">
            <label>Title</label>
            <input type="text" name="title" required>
            <label>Author</label>
            <input type="text" name="author" required>
            <label>ISBN</label>
            <input type="text" name="isbn" required>
            <label>Category ID</label>
            <input type="number" name="category_id" required>
            <label>Publisher</label>
            <input type="text" name="publisher" required>
            <label>Book Price</label>
            <input type="number" step="0.01" name="book_price" required>
            <label>Total Copies</label>
            <input type="number" name="total_copies" value="1" required>
            <label>Copies Available</label>
            <input type="number" name="copies_available" value="1" required>
            <label>Status</label>
            <input type="text" name="status" value="Available" required>
            <button type="submit">Add Book</button>
        </form>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
