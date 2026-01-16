<?php
// feedback.php
session_start();

// If not logged in, redirect to login
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/instructor_login.php");
    exit;
}

// Get user details
$user_email = $_SESSION['email'];

// Create database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=learnvibe;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get user ID
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$user_email]);
$user = $stmt->fetch();
$user_id = $user['id'] ?? 0;

if ($user_id == 0) {
    $_SESSION['error'] = "User not found!";
    header("Location: s_dashboard.php");
    exit;
}

// Get all courses for dropdown
$courses_stmt = $pdo->prepare("SELECT DISTINCT course_slug, course_title FROM course_files ORDER BY course_title ASC");
$courses_stmt->execute();
$courses = $courses_stmt->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_slug = $_POST['course'] ?? '';
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    
    if (empty($course_slug)) {
        $error = "Please select a course.";
    } elseif ($rating < 1 || $rating > 5) {
        $error = "Please select a rating.";
    } else {
        try {
            // Check if feedback already exists
            $check_stmt = $pdo->prepare("SELECT id FROM feedback WHERE user_id = ? AND course_slug = ?");
            $check_stmt->execute([$user_id, $course_slug]);
            $existing = $check_stmt->fetch();
            
            if ($existing) {
                // Update existing feedback
                $stmt = $pdo->prepare("UPDATE feedback SET rating = ?, comment = ? WHERE id = ?");
                $stmt->execute([$rating, $comment, $existing['id']]);
                $message = "Feedback updated successfully!";
            } else {
                // Insert new feedback
                $stmt = $pdo->prepare("INSERT INTO feedback (user_id, course_slug, rating, comment) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $course_slug, $rating, $comment]);
                $message = "Feedback submitted successfully!";
            }
            
            $_SESSION['success'] = $message;
            header("Location: s_dashboard.php");
            exit;
            
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Feedback | LearnVibe</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>
    <div class="feedback-container">
        <div class="feedback-header">
            <h1>Give Feedback</h1>
        </div>
        
        <div class="feedback-form-container">
            <?php if (isset($error)): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="course">Course *</label>
                    <select name="course" id="course" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course['course_slug']) ?>">
                                <?= htmlspecialchars($course['course_title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="rating">Rating *</label>
                    <select name="rating" id="rating" required>
                        <option value="">-- Select --</option>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="comment">Comments</label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Write here..."></textarea>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Submit</button>
                    <a href="s_dashboard.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>