<?php
// Student/View/feedback.php
session_start();

/* ✅ Only logged-in STUDENTS can access this page */
if (!isset($_SESSION['email']) || empty($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'student') {
    // If instructor/other tries, block and send them away
    header("Location: s_dashboard.php"); // change if you want a login page
    exit;
}

$user_email = $_SESSION['email'];

/* DB connection */
try {
    $pdo = new PDO("mysql:host=localhost;dbname=learnvibe;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/* Get user ID */
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$user_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = (int)($user['id'] ?? 0);

if ($user_id === 0) {
    $_SESSION['error'] = "User not found!";
    header("Location: s_dashboard.php");
    exit;
}

/* Course dropdown (only valid courses) */
$courses_stmt = $pdo->query("
    SELECT DISTINCT course_slug, course_title
    FROM course_files
    WHERE course_slug <> ''
    ORDER BY course_title ASC
");
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);

/* Handle form submit */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_slug = trim($_POST['course'] ?? '');
    $rating      = (int)($_POST['rating'] ?? 0);
    $comment     = trim($_POST['comment'] ?? '');

    if ($course_slug === '') {
        $error = "Please select a course.";
    } elseif ($rating < 1 || $rating > 5) {
        $error = "Please select a rating (1 to 5).";
    } else {
        try {
            // Check existing feedback by this student for this course
            $check = $pdo->prepare("SELECT id FROM feedback WHERE user_id = ? AND course_slug = ?");
            $check->execute([$user_id, $course_slug]);
            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update
                $up = $pdo->prepare("UPDATE feedback SET rating = ?, comment = ? WHERE id = ?");
                $up->execute([$rating, $comment, $existing['id']]);
                $_SESSION['success'] = "Feedback updated successfully!";
            } else {
                // Insert
                $ins = $pdo->prepare("INSERT INTO feedback (user_id, course_slug, rating, comment) VALUES (?, ?, ?, ?)");
                $ins->execute([$user_id, $course_slug, $rating, $comment]);
                $_SESSION['success'] = "Feedback submitted successfully!";
            }

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
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
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
