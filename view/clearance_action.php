<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/clearance.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../controller/ClearanceController.php';
$userId = $_SESSION['user_id'];
$semesterId = $_POST['semester_id'];
$academicYear = $_POST['academic_year'];
$controller = new ClearanceController();
$ok = $controller->requestClearance($userId, $semesterId, $academicYear);
if ($ok) header('Location: /LIBRARYSYSTEM/view/clearance.php?msg=Clearance requested');
else header('Location: /LIBRARYSYSTEM/view/clearance.php?msg=Failed');
exit();
