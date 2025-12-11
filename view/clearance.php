<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/ClearanceController.php';
require_once __DIR__ . '/../model/Semester.php';
$sem = new Semester();
$current = $sem->getCurrentSemester();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clearance - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Clearance Request</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <?php if (!$current): ?>
            <p>No active semester configured.</p>
        <?php else: ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Staff'): ?>
                <form method="post" action="/LIBRARYSYSTEM/view/staff_clearance_action.php">
                    <input type="hidden" name="semester_id" value="<?php echo $current['Semester_ID']; ?>">
                    <label>User Name</label>
                    <input type="text" name="user_name" placeholder="First and Last Name" required>
                    <label>Academic Year</label>
                    <input type="text" name="academic_year" required placeholder="e.g. 2024-2025">
                    <label>Set Status (optional)</label>
                    <select name="manual_status">
                        <option value="">-- Submit request (auto-check) --</option>
                        <option value="Approved">Cleared / Approved</option>
                        <option value="Not Cleared">Not Cleared</option>
                    </select>
                    <button type="submit">Submit Clearance</button>
                </form>
            <?php else: ?>
                <form method="post" action="/LIBRARYSYSTEM/view/clearance_action.php">
                    <input type="hidden" name="semester_id" value="<?php echo $current['Semester_ID']; ?>">
                    <label>Academic Year</label>
                    <input type="text" name="academic_year" required placeholder="e.g. 2024-2025">
                    <button type="submit">Request Clearance</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>