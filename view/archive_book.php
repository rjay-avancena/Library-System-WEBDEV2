<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../model/Book.php';
$bookModel = new Book();
$books = $bookModel->getAll();
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Archive Book - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Archive Book</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if ($msg): ?>
            <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <table class="table">
            <tr><th>Title</th><th>Author</th><th>ISBN</th><th>Action</th></tr>
            <?php foreach ($books as $b): ?>
                <tr>
                    <td><?php echo htmlspecialchars($b['Title']); ?></td>
                    <td><?php echo htmlspecialchars($b['Author']); ?></td>
                    <td><?php echo htmlspecialchars($b['ISBN']); ?></td>
                    <td>
                        <form method="post" action="/LIBRARYSYSTEM/view/archive_book_action.php" style="display:inline-block;">
                            <input type="hidden" name="book_id" value="<?php echo $b['Book_ID']; ?>">
                            <button type="submit" onclick="return confirm('Archive this book?');">Archive</button>
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
