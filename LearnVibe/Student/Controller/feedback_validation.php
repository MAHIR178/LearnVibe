<?php
// Student/Controller/feedback_validation.php
session_start();

// Check if user is logged in and is a student
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: ../View/s_dashboard.php");
    exit;
}

require_once __DIR__ . '/../Model/StudentModel.php';
require_once __DIR__ . '/../Model/FeedbackModel.php';

// Initialize models
$studentModel = new StudentModel();
$feedbackModel = new FeedbackModel();

// Get user data
$user_email = $_SESSION['email'];
$user = $studentModel->getStudentByEmail($user_email);

// Check if user exists
if (!$user) {
    $_SESSION['feedback_error'] = "User not found!";
    header("Location: ../View/feedback.php");
    exit;
}

$user_id = $user['id'];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $course_slug = trim($_POST['course'] ?? '');
    $rating = trim($_POST['rating'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    
    // Validate required fields
    if (empty($course_slug)) {
        $_SESSION['feedback_error'] = "Please select a course.";
        header("Location: ../View/feedback.php");
        exit;
    }
    
    if (empty($rating)) {
        $_SESSION['feedback_error'] = "Please select a rating.";
        header("Location: ../View/feedback.php");
        exit;
    }
    
    // Sanitize comment (optional, but good practice)
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    
    try {
        // Check if feedback already exists
        $check = $feedbackModel->checkFeedbackExists($user_id, $course_slug);
        
        if ($check['exists']) {
            // Update existing feedback
            $success = $feedbackModel->updateFeedback($check['id'], $rating, $comment);
            if ($success) {
                $_SESSION['feedback_success'] = "Feedback updated successfully!";
            } else {
                $_SESSION['feedback_error'] = "Error updating feedback. Please try again.";
            }
        } else {
            // Submit new feedback
            $success = $feedbackModel->submitFeedback($user_id, $course_slug, $rating, $comment);
            if ($success) {
                $_SESSION['feedback_success'] = "Feedback submitted successfully!";
            } else {
                $_SESSION['feedback_error'] = "Error submitting feedback. Please try again.";
            }
        }
        
        // Redirect back to feedback form
        header("Location: ../View/feedback.php");
        exit;
        
    } catch (Exception $e) {
        // Handle any unexpected errors
        $_SESSION['feedback_error'] = "An error occurred: " . $e->getMessage();
        header("Location: ../View/feedback.php");
        exit;
    }
} else {
    // If not POST request, redirect to feedback form
    header("Location: ../View/feedback.php");
    exit;
}
?>