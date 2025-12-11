<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BorrowController.php';
$controller = new BorrowController();
$borrowed = $controller->getBorrowedByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Return Book - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Return Book</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if (empty($borrowed)): ?>
            <p>You have no borrowed books.</p>
        <?php else: ?>
            <table class="table">
                <tr><th>Title</th><th>Borrow Date</th><th>Due Date</th><th>Action</th></tr>
                <?php foreach ($borrowed as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['Title']); ?></td>
                        <td><?php echo htmlspecialchars($b['Borrow_Date']); ?></td>
                        <td><?php echo htmlspecialchars($b['Due_Date']); ?></td>
                        <td>
                            <form method="post" action="/LIBRARYSYSTEM/view/return_action.php">
                                <input type="hidden" name="borrow_id" value="<?php echo $b['Borrow_ID']; ?>">
                                <button type="submit">Return</button>
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