<?php
session_start();

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../View/student_login.php");
    exit;
}

require_once __DIR__ . '/../../Admin/Model/Database.php';

$user_email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    
    $full_name = trim($_POST['full_name'] ?? "");
    $contact_number = trim($_POST['contact_number'] ?? "");
    $university_name = trim($_POST['university_name'] ?? "");
    $department = trim($_POST['department'] ?? "");
    $year = trim($_POST['year'] ?? "");
    $expertise = trim($_POST['expertise'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $confirm_password = trim($_POST['confirm_password'] ?? "");

    
    if ($password !== "" && $password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        $db = new DatabaseConnection();
        $conn = $db->openConnection();

        
        if ($password !== "") {
            $ok = $db->updateUserProfileWithPassword(
                $conn,
                $user_email,
                $full_name,
                $contact_number,
                $university_name,
                $department,
                $year,
                $expertise,
                $password
            );
        } else {
            $ok = $db->updateUserProfile(
                $conn,
                $user_email,
                $full_name,
                $contact_number,
                $university_name,
                $department,
                $year,
                $expertise
            );
        }

        if ($ok) {
            $_SESSION['message'] = "Profile updated successfully!";
            $_SESSION['message_type'] = "success";
            $_SESSION['full_name'] = $full_name;
        } else {
            $_SESSION['error'] = "Error updating profile.";
        }

        $db->closeConnection($conn);
    }
}
header("Location: ../View/profile_edit.php");
exit;
?>