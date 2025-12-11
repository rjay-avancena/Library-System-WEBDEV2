<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../model/Book.php';
$bookModel = new Book();
$books = $bookModel->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reserve Book - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">>
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Reserve Book</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <table class="table">
            <tr><th>Title</th><th>Author</th><th>Available</th><th>Action</th></tr>
            <?php foreach ($books as $b): ?>
                <tr>
                    <td><?php echo htmlspecialchars($b['Title']); ?></td>
                    <td><?php echo htmlspecialchars($b['Author']); ?></td>
                    <td><?php echo (int)$b['Copies_Available']; ?></td>
                    <td>
                        <form method="post" action="/LIBRARYSYSTEM/view/reserve_action.php">
                            <input type="hidden" name="book_id" value="<?php echo $b['Book_ID']; ?>">
                            <label>Expiry</label>
                            <input type="date" name="expiry_date" value="<?php $d=new DateTime('+7 days'); echo $d->format('Y-m-d'); ?>">
                            <button type="submit">Reserve</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>