<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/ReservationController.php';
$controller = new ReservationController();
$reservations = $controller->getUserReservations($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Reservations - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">My Reservations</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if (empty($reservations)): ?>
            <p>No active reservations.</p>
        <?php else: ?>
            <table class="table">
                <tr><th>Book</th><th>Reservation Date</th><th>Expiry Date</th><th>Status</th></tr>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['Title']); ?></td>
                        <td><?php echo htmlspecialchars($r['Reservation_Date']); ?></td>
                        <td><?php echo htmlspecialchars($r['Expiry_Date']); ?></td>
                        <td><?php echo htmlspecialchars($r['Status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
