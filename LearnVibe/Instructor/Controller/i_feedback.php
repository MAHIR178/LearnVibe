<?php
// Instructor/Controller/instructor_feedback.php
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

/* courses for dropdown */
$courses = $pdo->query("
    SELECT DISTINCT course_slug, course_title
    FROM course_files
    WHERE course_slug <> ''
    ORDER BY course_title ASC
")->fetchAll(PDO::FETCH_ASSOC);

$selected = trim($_GET["course"] ?? "");

$feedbacks = [];
$courseTitle = "";
$avgRating = null;
$total = 0;

if ($selected !== "") {

    // course title
    $t = $pdo->prepare("SELECT course_title FROM course_files WHERE course_slug = ? LIMIT 1");
    $t->execute([$selected]);
    $courseTitle = $t->fetchColumn() ?: $selected;

    // stats
    $s = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total FROM feedback WHERE course_slug = ?");
    $s->execute([$selected]);
    $stats = $s->fetch(PDO::FETCH_ASSOC);
    $avgRating = $stats["avg_rating"] ?? null;
    $total = (int)($stats["total"] ?? 0);

    // feedback list (try with created_at, fallback if column doesn't exist)
    try {
        $stmt = $pdo->prepare("
            SELECT f.rating, f.comment, f.created_at, u.full_name, u.email
            FROM feedback f
            LEFT JOIN users u ON u.id = f.user_id
            WHERE f.course_slug = ?
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$selected]);
        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // fallback (no created_at column)
        $stmt = $pdo->prepare("
            SELECT f.rating, f.comment, u.full_name, u.email
            FROM feedback f
            LEFT JOIN users u ON u.id = f.user_id
            WHERE f.course_slug = ?
            ORDER BY f.id DESC
        ");
        $stmt->execute([$selected]);
        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructor Feedback</title>
  <style>
    body{font-family:Arial,sans-serif;padding:20px;}
    table{border-collapse:collapse;width:100%;margin-top:15px;}
    th,td{border:1px solid #ddd;padding:10px;text-align:left;}
    th{background:#f2f2f2;}
    .muted{color:#666;}
  </style>
</head>
<body>

<a href="../View/i_dashboard.php">← Dashboard</a>
<h2>Course Feedback</h2>

<form method="GET">
  <label><b>Select Course:</b></label>
  <select name="course" onchange="this.form.submit()">
    <option value="">-- Select --</option>
    <?php foreach($courses as $c): ?>
      <option value="<?= htmlspecialchars($c["course_slug"]) ?>"
        <?= ($selected === $c["course_slug"]) ? "selected" : "" ?>>
        <?= htmlspecialchars($c["course_title"]) ?>
      </option>
    <?php endforeach; ?>
  </select>
</form>

<?php if ($selected === ""): ?>
  <p class="muted">Select a course to see student feedback.</p>
<?php else: ?>
  <h3><?= htmlspecialchars($courseTitle) ?></h3>
  <p class="muted">
    Total Feedback: <?= $total ?> |
    Average Rating: <?= $avgRating ? number_format((float)$avgRating, 1) : "N/A" ?>
  </p>

  <?php if (count($feedbacks) === 0): ?>
    <p>No feedback found for this course.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Student</th>
        <th>Email</th>
        <th>Rating</th>
        <th>Comment</th>
        <th>Date</th>
      </tr>

      <?php foreach($feedbacks as $f): ?>
        <tr>
          <td><?= htmlspecialchars($f["full_name"] ?? "Unknown") ?></td>
          <td><?= htmlspecialchars($f["email"] ?? "") ?></td>
          <td><?= htmlspecialchars($f["rating"] ?? "") ?>/5</td>
          <td><?= htmlspecialchars($f["comment"] ?? "") ?></td>
          <td><?= htmlspecialchars($f["created_at"] ?? "-") ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
<?php endif; ?>

</body>
</html>
