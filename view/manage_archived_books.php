<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/BookController.php';
$controller = new BookController();
$archivedBooks = $controller->getArchivedBooks();
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Archived Books - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Manage Archived Books</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/dashboard.php">Dashboard</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <?php if ($msg): ?>
            <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <?php if (empty($archivedBooks)): ?>
            <p>No archived books.</p>
        <?php else: ?>
            <table class="table">
                <tr><th>Title</th><th>Author</th><th>ISBN</th><th>Total</th><th>Price</th><th>Action</th></tr>
                <?php foreach ($archivedBooks as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['Title']); ?></td>
                        <td><?php echo htmlspecialchars($b['Author']); ?></td>
                        <td><?php echo htmlspecialchars($b['ISBN'] ?? 'N/A'); ?></td>
                        <td><?php echo (int)$b['Total_Copies']; ?></td>
                        <td><?php echo number_format($b['Book_Price'], 2); ?></td>
                        <td>
                            <form method="post" action="/LIBRARYSYSTEM/view/restore_book_action.php" style="display:inline-block;">
                                <input type="hidden" name="book_id" value="<?php echo (int)$b['Book_ID']; ?>">
                                <button type="submit" class="btn" onclick="return confirm('Restore this book?');">Restore</button>
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
