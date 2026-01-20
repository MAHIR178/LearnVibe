<?php
session_start();
include '../Model/Database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['admin_error'] = 'Admin not logged in.';
    header('Location: ../View/admin_login.php');
    exit();
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    
    $feedback_id = intval($_POST['id'] ?? 0);

    if ($feedback_id <= 0) {
        $_SESSION['error'] = "Invalid feedback ID.";
    } else {
        // Delete feedback by ID using the database function
        $ok = $db->deleteFeedbackById($conn, $feedback_id);

        if ($ok) {
            $_SESSION['message'] = "Feedback deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['error'] = "Error deleting feedback.";
        }

       
    }
}

// Fetch all feedback
$res = $db->getAllFeedbackById($conn);

$db->closeConnection($conn);

// Include the view
include('../View/view_feedback.php');
?>
