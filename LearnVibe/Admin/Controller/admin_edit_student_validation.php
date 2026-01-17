<?php
session_start();

/* Admin protection */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../View/admin_login.php');
    exit();
}

include '../Model/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    
    $student_id = intval($_POST['student_id'] ?? 0);
    $full_name = trim($_POST['full_name'] ?? "");
    $contact_number = trim($_POST['contact_number'] ?? "");
    $university_name = trim($_POST['university_name'] ?? "");
    $department = trim($_POST['department'] ?? "");
    $year = trim($_POST['year'] ?? "");

    if ($student_id <= 0) {
        $_SESSION['error'] = "Invalid student ID.";
    } else {
        $db = new DatabaseConnection();
        $conn = $db->openConnection();

        // Update student profile by ID
        $ok = $db->updateStudentById(
            $conn,
            $student_id,
            $full_name,
            $contact_number,
            $university_name,
            $department,
            $year
        );

        if ($ok) {
            $_SESSION['message'] = "Student profile updated successfully!";
            $_SESSION['message_type'] = "success";
            $_SESSION['student_id'] = $student_id;
        } else {
            $_SESSION['error'] = "Error updating student profile.";
        }

        $db->closeConnection($conn);
    }
}

// Redirect back to edit form
header("Location: ../View/admin_edit_students.php");
exit;
?>
