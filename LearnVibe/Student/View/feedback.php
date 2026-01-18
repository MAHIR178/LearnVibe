<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: s_dashboard.php"); 
    exit;
}
$conn = new mysqli("localhost", "root", "", "learnvibe");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_email = $_SESSION['email'];
$error = "";
$courses = [];
$sql = "SELECT id FROM users WHERE email = '$user_email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (!$user) {
    $error = "User not found!";
} else {
    $user_id = $user['id'];
    $sql = "SELECT DISTINCT course_slug, course_title FROM course_files WHERE course_slug != '' ORDER BY course_title";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_slug = $_POST['course'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    if (empty($course_slug)) {
        $error = "Please select a course.";
    } elseif (empty($rating)) {
        $error = "Please select a rating.";
    } else {
        // Check if feedback already exists
        $check_sql = "SELECT id FROM feedback WHERE user_id = $user_id AND course_slug = '$course_slug'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            // Update existing feedback
            $row = $check_result->fetch_assoc();
            $feedback_id = $row['id'];
            $update_sql = "UPDATE feedback SET rating = $rating, comment = '$comment' WHERE id = $feedback_id";
            
            if ($conn->query($update_sql)) {
                $_SESSION['success'] = "Feedback updated successfully!";
            } else {
                $error = "Error updating feedback.";
            }
        } else {
            // Insert new feedback
            $insert_sql = "INSERT INTO feedback (user_id, course_slug, rating, comment) VALUES ($user_id, '$course_slug', $rating, '$comment')";
            
            if ($conn->query($insert_sql)) {
                $_SESSION['success'] = "Feedback submitted successfully!";
            } else {
                $error = "Error submitting feedback.";
            }
        }
        
        if (empty($error)) {
            header("Location: s_dashboard.php");
            exit;
        }
    }
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
            <?php if ($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <!-- Course Selection -->
                <div class="form-group">
                    <label for="course">Course</label>
                    <select name="course" id="course" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['course_slug']; ?>">
                                <?php echo $course['course_title']; ?>
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