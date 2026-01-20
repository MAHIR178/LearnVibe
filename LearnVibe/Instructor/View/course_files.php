<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"])) {
    header("Location: instructor_login.php");
    exit;
}

$courseParam = trim($_GET["course"] ?? "");
if ($courseParam === "") {
    header("Location: ../../Student/View/s_dashboard.php");
    exit;
}

$db  = new DatabaseConnection();
$con = $db->openConnection();


$courseTitle = $courseParam;

$stmt = $con->prepare("SELECT course_title FROM course_files WHERE course_slug = ? LIMIT 1");
$stmt->bind_param("s", $courseParam);
$stmt->execute();
$r = $stmt->get_result();
if ($r && $r->num_rows > 0) {
    $courseTitle = $r->fetch_assoc()["course_title"];
}
$stmt->close();


$stmt2 = $con->prepare("
    SELECT original_name, file_type, file_path, uploaded_at
    FROM course_files
    WHERE course_title = ?
      AND file_path <> ''
      AND original_name <> ''
      AND uploaded_by <> 0
    ORDER BY uploaded_at DESC
");
$stmt2->bind_param("s", $courseTitle);
$stmt2->execute();
$res = $stmt2->get_result();
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
    <div class="top-links">
        <a href="javascript:history.back()">Back</a>
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
                <?php
                    $filePath = $f["file_path"]; 
                   
                    if (strpos($filePath, "uploads/") === 0) {
                        $filePath = "../Controller/" . $filePath;
                    }
                ?>
                <li class="file-item">
                    <div>
                        <b><?php echo htmlspecialchars($f["original_name"]); ?></b>
                        <div style="font-size:12px;color:#666;">
                            <?php echo htmlspecialchars($f["file_type"]); ?> |
                            <?php echo htmlspecialchars($f["uploaded_at"]); ?>
                        </div>
                    </div>

                    <a class="download-btn" href="<?php echo htmlspecialchars($filePath); ?>" download>
                        Download
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>
<?php
$stmt2->close();
$db->closeConnection($con);
?>
