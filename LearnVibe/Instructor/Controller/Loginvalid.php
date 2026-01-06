<?php
session_start();
require_once '../../Admin/Model/Database.php';

// If already logged in → redirect
if (!empty($_SESSION["isLoggedIn"])) {
    $role = $_SESSION["role"] ?? null;

    if ($role === "student") {
        header("Location: ../../Student/View/s_dashboard.php");
    } elseif ($role === "instructor") {
        header("Location: ../View/i_dashboard.php");
    } else {
        header("Location: ../View/dashboard.php");
    }
    exit;
}

// Only allow POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/instructor_login.php");
    exit;
}

$email    = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($email === "" || $password === "") {
    header("Location: ../View/Login.php?error=" . urlencode("Please enter both email and password.") . "&email=" . urlencode($email));
    exit;
}

$db   = new DatabaseConnection();
$conn = $db->openConnection();

$user = $db->loginUser($conn, $email, $password);

$db->closeConnection($conn);

if ($user) {
    $_SESSION["isLoggedIn"] = true;
    $_SESSION["user_id"]    = $user["id"];
    $_SESSION["role"]       = $user["role"] ?? null;
    $_SESSION["full_name"]  = $user["full_name"] ?? null;
    $_SESSION["email"]      = $user["email"];
    $_SESSION["user_email"] = $user["email"];

    if ($_SESSION["role"] === "student") {
        header("Location: ../../Student/View/s_dashboard.php");
    } elseif ($_SESSION["role"] === "instructor") {
        header("Location: ../View/i_dashboard.php");
    } else {
        header("Location: ../View/dashboard.php");
    }
    exit;
}

// Invalid login
header("Location: ../View/instructor_Login.php?error=" . urlencode("Invalid email or password.") . "&email=" . urlencode($email));
exit;
