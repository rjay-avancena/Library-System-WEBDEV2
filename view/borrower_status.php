<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../config/db.php';
$database = new Database();
$conn = $database->getConnection();
$user_name = isset($_GET['user_name']) ? $_GET['user_name'] : '';
$borrower = null;
$borrows = [];
$penalties = [];
if (!empty($user_name)) {
    $parts = explode(' ', trim($user_name), 2);
    $firstName = $parts[0];
    $lastName = isset($parts[1]) ? $parts[1] : '';
    $stmt = $conn->prepare('SELECT * FROM Users WHERE First_Name LIKE ? AND Last_Name LIKE ? LIMIT 1');
    $stmt->execute(['%' . $firstName . '%', '%' . $lastName . '%']);
    $borrower = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($borrower) {
        $stmt2 = $conn->prepare('SELECT b.*, bk.Title FROM Borrow b JOIN Book bk ON b.Book_ID = bk.Book_ID WHERE b.User_ID = ? AND b.Status = "Borrowed"');
        $stmt2->execute([$borrower['User_ID']]);
        $borrows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $stmt3 = $conn->prepare('SELECT * FROM Penalty WHERE User_ID = ? AND Status = "Unpaid"');
        $stmt3->execute([$borrower['User_ID']]);
        $penalties = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrower Status - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Borrower Status Lookup</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <form method="get" style="margin-bottom:20px;">
            <label>Search by Name</label>
            <input type="text" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" placeholder="First and Last Name" required>
            <button type="submit">Search</button>
        </form>
        <?php if ($borrower): ?>
            <h3><?php echo htmlspecialchars($borrower['First_Name'] . ' ' . $borrower['Last_Name']); ?> (<?php echo $borrower['Role']; ?>)</h3>
            <h4>Borrowed Books</h4>
            <?php if (empty($borrows)): ?>
                <p>No borrowed books.</p>
            <?php else: ?>
                <table class="table">
                    <tr><th>Book</th><th>Due Date</th></tr>
                    <?php foreach ($borrows as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['Title']); ?></td>
                            <td><?php echo htmlspecialchars($b['Due_Date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <h4>Penalties</h4>
            <?php if (empty($penalties)): ?>
                <p>No unpaid penalties.</p>
            <?php else: ?>
                <table class="table">
                    <tr><th>Amount</th><th>Description</th><th>Action</th></tr>
                    <?php foreach ($penalties as $p): ?>
                        <tr>
                            <td><?php echo number_format($p['Amount'],2); ?></td>
                            <td><?php echo htmlspecialchars($p['Description']); ?></td>
                            <td>
                                <form method="post" action="/LIBRARYSYSTEM/view/mark_penalty_paid_action.php" style="display:inline;">
                                    <input type="hidden" name="penalty_id" value="<?php echo (int)$p['Penalty_ID']; ?>">
                                    <button type="submit" class="btn">Mark Paid</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
