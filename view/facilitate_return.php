<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BorrowController.php';
$controller = new BorrowController();
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Facilitate Return - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Facilitate Returning</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if ($msg): ?>
            <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <form method="post" action="/LIBRARYSYSTEM/view/facilitate_return_action.php">
            <label>User Name</label>
            <input type="text" name="user_name" placeholder="First and Last Name" required>
            <label>Book Title</label>
            <input type="text" name="book_title" placeholder="Book title" required>
            <button type="submit">Process Return</button>
        </form>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
