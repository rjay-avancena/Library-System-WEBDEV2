<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../model/Book.php';
require_once __DIR__ . '/../model/Semester.php';
$bookModel = new Book();
$books = $bookModel->getAvailableBooks();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Borrow Book</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if (empty($books)): ?>
            <p>No available books at the moment.</p>
        <?php else: ?>
            <table class="table">
                <tr><th>Title</th><th>Author</th><th>Price</th><th>Available</th><th>Action</th></tr>
                <?php foreach ($books as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['Title']); ?></td>
                        <td><?php echo htmlspecialchars($b['Author']); ?></td>
                        <td><?php echo number_format($b['Book_Price'],2); ?></td>
                        <td><?php echo (int)$b['Copies_Available']; ?></td>
                        <td>
                            <form method="post" action="/LIBRARYSYSTEM/view/borrow_action.php" style="display:inline-block;">
                                <input type="hidden" name="book_id" value="<?php echo $b['Book_ID']; ?>">
                                <label>Due Date:</label>
                                <input type="date" name="due_date" value="<?php $d = new DateTime('+14 days'); echo $d->format('Y-m-d'); ?>" required>
                                <button type="submit">Borrow</button>
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