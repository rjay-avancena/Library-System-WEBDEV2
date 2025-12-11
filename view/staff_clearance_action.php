<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /LIBRARYSYSTEM/view/dashboard.php');
    exit();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /LIBRARYSYSTEM/view/login.php');
    exit();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controller/ClearanceController.php';

$user_name = $_POST['user_name'];
$semester_id = $_POST['semester_id'];
$academic_year = $_POST['academic_year'];

// Find user by name
$parts = explode(' ', trim($user_name), 2);
$firstName = $parts[0];
$lastName = isset($parts[1]) ? $parts[1] : '';

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare('SELECT * FROM Users WHERE First_Name LIKE ? AND Last_Name LIKE ? LIMIT 1');
$stmt->execute(['%' . $firstName . '%', '%' . $lastName . '%']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: /LIBRARYSYSTEM/view/dashboard.php?msg=User not found');
    exit();
}

$controller = new ClearanceController();
$processedBy = $_SESSION['user_id'];

// If staff provided a manual status, insert clearance with that status directly
$manualStatus = isset($_POST['manual_status']) ? trim($_POST['manual_status']) : '';
if (!empty($manualStatus) && in_array($manualStatus, ['Approved', 'Not Cleared'])) {
    // Directly call the model to record chosen status and set cleared_by/date when Approved
    require_once __DIR__ . '/../model/Clearance.php';
    $clearModel = new Clearance();
    $remarks = ($manualStatus === 'Approved') ? 'Approved by staff' : 'Marked not cleared by staff';
    $ok = $clearModel->requestClearance($user['User_ID'], $semester_id, $academic_year, $manualStatus, $processedBy, $remarks);
} else {
    // Default behaviour: request clearance with auto-check
    $ok = $controller->requestClearance($user['User_ID'], $semester_id, $academic_year, $processedBy);
}

if ($ok) {
    header('Location: /LIBRARYSYSTEM/view/dashboard.php?msg=Clearance processed for ' . htmlspecialchars($user['First_Name']));
} else {
    header('Location: /LIBRARYSYSTEM/view/dashboard.php?msg=Clearance failed');
}
exit();
?>
