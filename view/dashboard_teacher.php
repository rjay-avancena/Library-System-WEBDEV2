<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Teacher') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Teacher Dashboard</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/borrow.php">Borrow Book</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/return.php">Return Book</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/reserve.php">Reserve Book</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/view_reservations.php">View Reservations</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/penalties.php">View Penalties</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/clearance.php">Clearance</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <!-- Teacher info, borrowed books, reservations, penalties, clearance status here -->
        <p>Welcome, Teacher! All your library activities are here.</p>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
