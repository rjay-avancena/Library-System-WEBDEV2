<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Librarian') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../model/Book.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: /LIBRARYSYSTEM/view/update_book.php');
    exit();
}
$bookModel = new Book();
$book = $bookModel->getById($id);
if (!$book) {
    header('Location: /LIBRARYSYSTEM/view/update_book.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Book - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="landscape-container">
    <h2 class="header">Edit Book</h2>
    <div class="horizontal-btns">
        <a class="btn" href="/LIBRARYSYSTEM/view/update_book.php">Back</a>
        <a class="btn" href="/LIBRARYSYSTEM/view/logout.php">Logout</a>
    </div>
    <div class="dashboard-section">
        <form method="post" action="/LIBRARYSYSTEM/view/edit_book_action.php">
            <input type="hidden" name="book_id" value="<?php echo $book['Book_ID']; ?>">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['Title']); ?>" required>
            <label>Author</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($book['Author']); ?>" required>
            <label>ISBN</label>
            <input type="text" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>" required>
            <label>Category ID</label>
            <input type="number" name="category_id" value="<?php echo (int)$book['Category_ID']; ?>" required>
            <label>Publisher</label>
            <input type="text" name="publisher" value="<?php echo htmlspecialchars($book['Publisher']); ?>" required>
            <label>Book Price</label>
            <input type="number" step="0.01" name="book_price" value="<?php echo number_format($book['Book_Price'],2); ?>" required>
            <label>Total Copies</label>
            <input type="number" name="total_copies" value="<?php echo (int)$book['Total_Copies']; ?>" required>
            <label>Copies Available</label>
            <input type="number" name="copies_available" value="<?php echo (int)$book['Copies_Available']; ?>" required>
            <label>Status</label>
            <input type="text" name="status" value="<?php echo htmlspecialchars($book['Status']); ?>" required>
            <button type="submit">Update Book</button>
        </form>
    </div>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
