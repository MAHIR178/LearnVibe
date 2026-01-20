<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: s_dashboard.php"); 
    exit;
}

require_once __DIR__ . '/../Model/StudentModel.php';
require_once __DIR__ . '/../Model/FeedbackModel.php';

$error = "";
$courses = [];

$studentModel = new StudentModel();
$feedbackModel = new FeedbackModel();

$user_email = $_SESSION['email'];
$user = $studentModel->getStudentByEmail($user_email);

if (!$user) {
    $error = "User not found!";
} else {
    $user_id = $user['id'];
    $courses = $studentModel->getStudentCourses();
}

if (isset($_SESSION['feedback_success'])) {
    $success = $_SESSION['feedback_success'];
    unset($_SESSION['feedback_success']);
}

if (isset($_SESSION['feedback_error'])) {
    $error = $_SESSION['feedback_error'];
    unset($_SESSION['feedback_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Give Feedback</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>
    <div class="feedback-container">
        <div class="feedback-header">
            <h1>Give Feedback</h1>
        </div>
        
        <div class="feedback-form-container">
            <?php if (isset($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="../Controller/feedback_validation.php">
                <!-- Course Selection -->
                <div class="form-group">
                    <label for="course">Course</label>
                    <select name="course" id="course" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo htmlspecialchars($course['course_slug']); ?>">
                                <?php echo htmlspecialchars($course['course_title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Rating Selection -->
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select name="rating" id="rating" required>
                        <option value="">-- Select Rating --</option>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
           
                <div class="form-group">
                    <label for="comment">Comments</label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Write your feedback here..."></textarea>
                </div>
        
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Submit Feedback</button>
                    <a href="s_dashboard.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>