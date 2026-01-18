<?php
session_start();

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: ../View/instructor_login.php");
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=learnvibe;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed!");
}

/*
  Show ALL feedback:
  - course name from course_files (using slug)
  - student name/email from users
*/
try {
    $stmt = $pdo->query("
        SELECT
            f.rating,
            f.comment,
            f.created_at,
            u.full_name,
            u.email,
            c.course_title
        FROM feedback f
        LEFT JOIN users u ON u.id = f.user_id
        LEFT JOIN (
            SELECT course_slug, MAX(course_title) AS course_title
            FROM course_files
            GROUP BY course_slug
        ) c ON c.course_slug = f.course_slug
        ORDER BY f.created_at DESC
    ");
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    
    $stmt = $pdo->query("
        SELECT
            f.rating,
            f.comment,
            u.full_name,
            u.email,
            c.course_title
        FROM feedback f
        LEFT JOIN users u ON u.id = f.user_id
        LEFT JOIN (
            SELECT course_slug, MAX(course_title) AS course_title
            FROM course_files
            GROUP BY course_slug
        ) c ON c.course_slug = f.course_slug
        ORDER BY f.id DESC
    ");
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Feedback</title>
  <style>
  table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #000;
  padding: 8px;
}

th {
  font-weight: bold;
}
  </style>
</head>
<body>

<a href="../View/i_dashboard.php">← Dashboard</a>
<h2>All Student Feedback</h2>

<?php if (empty($feedbacks)): ?>
  <p class="muted">No feedback found.</p>
<?php else: ?>
  <table>
    <tr>
      <th>Course</th>
      <th>Student</th>
      <th>Email</th>
      <th>Rating</th>
      <th>Comment</th>
      <th>Date</th>
    </tr>

    <?php foreach ($feedbacks as $f): ?>
      <tr>
        <td><?= htmlspecialchars($f["course_title"] ?? "Unknown Course") ?></td>
        <td><?= htmlspecialchars($f["full_name"] ?? "Unknown") ?></td>
        <td><?= htmlspecialchars($f["email"] ?? "") ?></td>
        <td><?= htmlspecialchars($f["rating"] ?? "") ?>/5</td>
        <td><?= htmlspecialchars($f["comment"] ?? "") ?></td>
        <td><?= htmlspecialchars($f["created_at"] ?? "-") ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

</body>
</html>
