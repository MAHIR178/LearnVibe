<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"])) {
    header("Location: instructor_login.php");
    exit;
}

$courseTitle = $_GET["course"] ?? "";
$courseTitle = trim($courseTitle);

if ($courseTitle === "") {
    header("Location: i_dashboard.php");
    exit;
}

$db  = new DatabaseConnection();
$con = $db->openConnection();

$res = $db->getCourseFilesByTitle($con, $courseTitle);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($courseTitle); ?> - Files</title>
    <link rel="stylesheet" href="course_files.css">
    <link rel="stylesheet" href="../../Student/View/s_dashboard.css">
</head>
<body>

<div class="top-bar">
    <input type="text" placeholder="Search">
    <div class="top-links">
        <a href="i_dashboard.php">Back</a>
        <a href="Logout.php">Logout</a>
    </div>
</div>

<div class="files-box">
    <h2><?php echo htmlspecialchars($courseTitle); ?> — Files</h2>

    <?php if (!$res || $res->num_rows == 0): ?>
        <div class="empty">No files uploaded for this course yet.</div>
    <?php else: ?>
        <ul class="file-list">
            <?php while($f = $res->fetch_assoc()): ?>
                <li class="file-item">
                    <?php echo htmlspecialchars($f["original_name"]); ?>
                    <a class="download-btn" href="<?php echo htmlspecialchars($f["file_path"]); ?>" download>Download</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>

