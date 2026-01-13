<?php
session_start();

require_once '../Model/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $_SESSION['admin_error'] = "Username and password are required";
        header("Location: ../View/admin_login.php");
        exit;
    }

    $db = new DatabaseConnection();
    $admin = $db->adminLogin($username, $password);

    if ($admin) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        header("Location: ../View/admin_dashboard.php");
        exit;

    } else {
        $_SESSION['admin_error'] = "Invalid admin username or password";
        header("Location: ../View/admin_login.php");
        exit;
    }
}

header("Location: ../View/admin_login.php");
exit;
