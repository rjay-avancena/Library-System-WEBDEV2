<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Staff Dashboard</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/facilitate_borrow.php">Facilitate Borrow</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/facilitate_return.php">Facilitate Return</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/borrower_status.php">Borrower Status</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/clearance.php">Clearance</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <p>Welcome, Staff! Use the buttons above to facilitate library operations.</p>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
