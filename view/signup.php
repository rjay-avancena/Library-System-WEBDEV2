<?php
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Smart Library</title>
    <link rel="stylesheet" href="/LIBRARYSYSTEM/assets/css/style.css?v=1">
</head>
<body>
<div class="container">
    <h2 class="header">Sign Up</h2>
    <?php if ($msg): ?>
        <div class="alert"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    <form method="post" action="signup_action.php">
        <label>First Name</label>
        <input type="text" name="first_name" required>
        <label>Last Name</label>
        <input type="text" name="last_name" required>
        <label>Role</label>
        <select name="role" required>
            <option value="Student">Student</option>
            <option value="Teacher">Teacher</option>
            <option value="Librarian">Librarian</option>
            <option value="Staff">Staff</option>
        </select>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <label>Contact Number</label>
        <input type="text" name="contact_number" required>
        <button type="submit">Sign Up</button>
    </form>
    <p style="text-align:center;">Already have an account? <a href="login.php">Login</a></p>
</div>
<script src="/LIBRARYSYSTEM/assets/js/main.js"></script>
</body>
</html>
