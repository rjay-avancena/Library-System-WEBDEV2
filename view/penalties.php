<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/PenaltyController.php';
$controller = new PenaltyController();
$penalties = $controller->getUserPenalties($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Penalties - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Your Penalties</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if (empty($penalties)): ?>
            <p>No unpaid penalties.</p>
        <?php else: ?>
            <table class="table">
                <tr><th>Type</th><th>Amount</th><th>Description</th><th>Issued</th><th>Status</th></tr>
                <?php foreach ($penalties as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['Penalty_Type']); ?></td>
                        <td><?php echo number_format($p['Amount'],2); ?></td>
                        <td><?php echo htmlspecialchars($p['Description']); ?></td>
                        <td><?php echo htmlspecialchars($p['Issued_Date']); ?></td>
                        <td><?php echo htmlspecialchars($p['Status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>