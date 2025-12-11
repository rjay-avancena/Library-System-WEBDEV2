<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="container">
    <h2 class="header">Smart Library Login</h2>
    <?php if ($msg): ?>
        <div class="alert"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    <form method="post" action="login_action.php">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <p style="text-align:center;">No account? <a href="signup.php">Sign up</a></p>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
