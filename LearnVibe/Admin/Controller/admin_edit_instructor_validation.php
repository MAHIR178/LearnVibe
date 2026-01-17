<?php
session_start();

/* Admin protection */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../View/admin_login.php');
    exit();
}

include '../Model/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    
    $instructor_id = intval($_POST['instructor_id'] ?? 0);
    $full_name = trim($_POST['full_name'] ?? "");
    $contact_number = trim($_POST['contact_number'] ?? "");
    $university_name = trim($_POST['university_name'] ?? "");
    $department = trim($_POST['department'] ?? "");
    $expertise = trim($_POST['expertise'] ?? "");

    if ($instructor_id <= 0) {
        $_SESSION['error'] = "Invalid instructor ID.";
    } else {
        $db = new DatabaseConnection();
        $conn = $db->openConnection();

        // Update instructor profile by ID
        $ok = $db->updateInstructorById(
            $conn,
            $instructor_id,
            $full_name,
            $contact_number,
            $university_name,
            $department,
            $expertise
        );

        if ($ok) {
            $_SESSION['message'] = "Instructor profile updated successfully!";
            $_SESSION['message_type'] = "success";
            $_SESSION['instructor_id'] = $instructor_id;
        } else {
            $_SESSION['error'] = "Error updating instructor profile.";
        }

        $db->closeConnection($conn);
    }
}

// Redirect back to edit form
header("Location: ../View/admin_edit_instructors.php");
exit;
?>
