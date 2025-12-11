<?php
require_once __DIR__ . '/../controller/AuthController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'role' => $_POST['role'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'contact_number' => $_POST['contact_number']
    ];
    $auth = new AuthController();
    $result = $auth->signup($data);
    if ($result === true) {
        header('Location: login.php?msg=Signup successful! Please login.');
        exit();
    } elseif ($result === 'Email already exists') {
        header('Location: signup.php?msg=Email already exists');
        exit();
    } else {
        header('Location: signup.php?msg=Signup failed');
        exit();
    }
} else {
    header('Location: signup.php');
    exit();
}
