<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../View/admin_login.php');
    exit();
}

include '../Model/Database.php';

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {

    $old['full_name'] = trim($_POST['full_name'] ?? "");
    $old['email'] = trim($_POST['email'] ?? "");
    $old['contact_number'] = trim($_POST['contact_number'] ?? "");
    $old['university_name'] = trim($_POST['university_name'] ?? "");
    $old['department'] = trim($_POST['department'] ?? "");
    $old['year'] = trim($_POST['year'] ?? "");

    $password = trim($_POST['password'] ?? "");
    $confirm_password = trim($_POST['confirm_password'] ?? "");

    // Validation
    if ($old['full_name'] === "") {
        $errors['full_name'] = "Full name is required.";
    } elseif (strlen($old['full_name']) < 4) {
        $errors['full_name'] = "Full name must be at least 4 characters.";
    }

    if ($old['email'] === "") {
        $errors['email'] = "Email is required.";
    }
    elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Please enter a valid email address.";
    }

    if ($old['university_name'] === "") {
        $errors['university_name'] = "University name is required.";
    }

    if ($old['department'] === "") {
        $errors['department'] = "Department is required.";
    }

    if ($old['year'] === "") {
        $errors['year'] = "Year is required.";
    }
    if ($old['contact_number'] === "") {
        $errors['contact_number'] = "Contact number is required.";
    }
    elseif (!preg_match('/^[0-9]{10,15}$/', $old['contact_number'])) {
        $errors['contact_number'] = "Please enter a valid contact number (10-15 digits).";
    }

    if ($password === "") {
        $errors['password'] = "Password is required.";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    if (!isset($errors['email']) && $db->isEmailExist($conn, $old['email'])) {
        $errors['email'] = "Email already exists.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $old;
        header("Location: ../View/admin_add_student.php");
        exit;
    }

    // Insert student
    $ok = $db->createStudent(
        $conn,
        $old['full_name'],
        $old['email'],
        $old['contact_number'],
        $old['university_name'],
        $old['department'],
        $old['year'],
        $password
    );

    if ($ok) {
        $_SESSION['message'] = "Student added successfully!";
        $_SESSION['message_type'] = "success";
    }

    $db->closeConnection($conn);
}

header("Location: ../View/admin_add_student.php");
exit;
