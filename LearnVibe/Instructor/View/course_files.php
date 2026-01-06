<?php
session_start();
include "../../Admin/Model/Database.php";

if (empty($_SESSION["isLoggedIn"])) {
    header("Location: instrutor_login.php");
    exit;
}

$courseTitle = trim($_GET["course"] ?? "");
if ($courseTitle === "") {
    header("Location: i_dashboard.php");
    exit;
}

$db = new DatabaseConnection();
$con = $db->openConnection();

$res = $db->getCourseFilesByTitle($con, $courseTitle);

$files = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $files[] = $row;
    }
}

$db->closeConnection($con);
function getExt($pathOrName) {
    return strtolower(pathinfo($pathOrName, PATHINFO_EXTENSION));
}

function fileSrc($p) {
    if (preg_match('/^(https?:\/\/|\/)/i', $p)) return $p;
    return $p; // relative path
}
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
    <h2><?php echo htmlspecialchars($courseTitle); ?> — Uploaded Files</h2>

    <?php if (empty($files)): ?>
        <div class="empty">No files uploaded for this course yet.</div>
    <?php else: ?>
        <?php foreach ($files as $f): ?>
            <?php
                $type = $f["file_type"];
                $name = $f["original_name"];
                $path = $f["file_path"];
                $time = $f["uploaded_at"];

                $src = fileSrc($path);
                $ext = getExt($name ?: $path);
            ?>
            <div class="file-card">
                <div class="file-head">
                    <span class="badge"><?php echo htmlspecialchars($type); ?></span>
                    <a class="file-name" href="<?php echo htmlspecialchars($src); ?>" target="_blank">
                        <?php echo htmlspecialchars($name); ?>
                    </a>
                    <span class="time"><?php echo htmlspecialchars($time); ?></span>
                </div>

                <?php if (strtolower($type) === "image" || in_array($ext, ["jpg","jpeg","png","gif","webp"])): ?>
                    <img class="preview-img" src="<?php echo htmlspecialchars($src); ?>" alt="<?php echo htmlspecialchars($name); ?>">

                <?php elseif (strtolower($type) === "pdf" || $ext === "pdf"): ?>
                    <iframe class="preview-pdf" src="<?php echo htmlspecialchars($src); ?>"></iframe>

                <?php elseif (strtolower($type) === "video" || in_array($ext, ["mp4","webm","ogg","mov"])): ?>
                    <video class="preview-video" controls>
                        <source src="<?php echo htmlspecialchars($src); ?>">
                        Your browser does not support the video tag.
                    </video>

                <?php else: ?>
                    <a class="download" href="<?php echo htmlspecialchars($src); ?>" download>Download</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
