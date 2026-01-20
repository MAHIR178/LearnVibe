<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: ../View/s_dashboard.php");
    exit;
}

require_once __DIR__ . '/../Model/StudentModel.php';
require_once __DIR__ . '/../Model/FeedbackModel.php';

$studentModel = new StudentModel();
$feedbackModel = new FeedbackModel();

$user_email = $_SESSION['email'];
$user = $studentModel->getStudentByEmail($user_email);

if (!$user) {
    $_SESSION['feedback_error'] = "User not found!";
    header("Location: ../View/feedback.php");
    exit;
}

$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_slug = trim($_POST['course'] ?? '');
    $rating = trim($_POST['rating'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    
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
    
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    
    try {
        $check = $feedbackModel->checkFeedbackExists($user_id, $course_slug);
        
        if ($check['exists']) {
            $success = $feedbackModel->updateFeedback($check['id'], $rating, $comment);
            if ($success) {
                $_SESSION['feedback_success'] = "Feedback updated successfully!";
            } else {
                $_SESSION['feedback_error'] = "Error updating feedback. Please try again.";
            }
        } else {
            $success = $feedbackModel->submitFeedback($user_id, $course_slug, $rating, $comment);
            if ($success) {
                $_SESSION['feedback_success'] = "Feedback submitted successfully!";
            } else {
                $_SESSION['feedback_error'] = "Error submitting feedback. Please try again.";
            }
        }
        
        header("Location: ../View/feedback.php");
        exit;
        
    } catch (Exception $e) {
        $_SESSION['feedback_error'] = "An error occurred: " . $e->getMessage();
        header("Location: ../View/feedback.php");
        exit;
    }
} else {
    header("Location: ../View/feedback.php");
    exit;
}
?>