<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../config/db.php';
$db = new Database();
$conn = $db->getConnection();

// fetch pending clearances
$stmt = $conn->query('SELECT c.*, u.First_Name, u.Last_Name FROM Clearance c JOIN Users u ON c.User_ID = u.User_ID WHERE c.Library_Clearance = "Pending" ORDER BY c.Clearance_Date DESC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Clearances - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Pending Clearances</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if ($msg): ?>
            <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <?php if (empty($rows)): ?>
            <p>No pending clearances.</p>
        <?php else: ?>
            <table class="table">
                <tr><th>Name</th><th>Semester</th><th>Academic Year</th><th>Remarks</th><th>Action</th></tr>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['First_Name'] . ' ' . $r['Last_Name']); ?></td>
                        <td><?php echo htmlspecialchars($r['Semester_ID']); ?></td>
                        <td><?php echo htmlspecialchars($r['Academic_Year']); ?></td>
                        <td><?php echo htmlspecialchars($r['Remarks']); ?></td>
                        <td>
                            <form method="post" action="/LIBRARYSYSTEM/view/manage_clearance_action.php" style="display:inline;">
                                <input type="hidden" name="clearance_id" value="<?php echo (int)$r['Clearance_ID']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn">Approve</button>
                            </form>
                            <form method="post" action="/LIBRARYSYSTEM/view/manage_clearance_action.php" style="display:inline; margin-left:6px;">
                                <input type="hidden" name="clearance_id" value="<?php echo (int)$r['Clearance_ID']; ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn btn-danger">Not Cleared</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
