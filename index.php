<?php
// index.php - Entry point for Smart Library System
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    header('Location: view/dashboard.php');
    exit();
} else {
    header('Location: view/login.php');
    exit();
}
?>