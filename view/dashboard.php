<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}
$role = $_SESSION['role'];
switch ($role) {
    case 'Student':
        header('Location: dashboard_student.php');
        break;
    case 'Teacher':
        header('Location: dashboard_teacher.php');
        break;
    case 'Librarian':
        header('Location: dashboard_librarian.php');
        break;
    case 'Staff':
        header('Location: dashboard_staff.php');
        break;
    default:
        header('Location: login.php');
}
exit();
