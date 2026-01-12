<?php
session_start();
require_once '../../Admin/Model/Database.php';

// If already logged in → redirect
if (!empty($_SESSION["isLoggedIn"])) {
    $role = $_SESSION["role"] ?? null;

    if ($role === "student") {
        header("Location: ../View/s_dashboard.php");
    } 
   
    else {
        header("Location: ../View/dashboard.php");
    }
    exit;
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/student_login.php");
    exit;
}

$email    = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($email === "" || $password === "") {
    header("Location: ../View/student_login.php?error=" . urlencode("Please enter both email and password.") . "&email=" . urlencode($email));
    exit;
}

$db   = new DatabaseConnection();
$conn = $db->openConnection();

$user = $db->loginUser($conn, $email, $password);

$db->closeConnection($conn);

if ($user) {
    $role = $user["role"] ?? null;

    // Only allow students to log in from the student login page
    if ($role !== "student") {
        header("Location: ../View/student_login.php?error=" . urlencode("This account is not a student account. Please use the instructor login.") . "&email=" . urlencode($email));
        exit;
    }

    // proceed with student login
    $_SESSION["isLoggedIn"] = true;
    $_SESSION["user_id"]    = $user["id"];
    $_SESSION["role"]       = $role;
    $_SESSION["full_name"]  = $user["full_name"] ?? null;
    $_SESSION["email"]      = $user["email"];
    $_SESSION["user_email"] = $user["email"];

    header("Location: ../View/s_dashboard.php");
    exit;
}

// Invalid login
header("Location: ../View/student_login.php?error=" . urlencode("Invalid email or password.") . "&email=" . urlencode($email));
exit;
