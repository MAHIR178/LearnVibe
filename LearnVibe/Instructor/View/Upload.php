<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: instructor_login.php");
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $courseTitle = trim($_POST["course"] ?? "");
    $fileType    = trim($_POST["file_type"] ?? "");

    if ($courseTitle === "" || $fileType === "") {
        $msg = "Please select course and file type.";
    } elseif (!isset($_FILES["upload_file"]) || $_FILES["upload_file"]["error"] !== 0) {
        $msg = "Please choose a file.";
    } else {

        $originalName = basename($_FILES["upload_file"]["name"]);
        $tmpName      = $_FILES["upload_file"]["tmp_name"];

        // block dangerous extensions
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $blocked = ["php", "phtml", "phar", "php3", "php4", "php5"];
        if (in_array($ext, $blocked, true)) {
            $msg = "This file type is not allowed.";
        } else {

            // make uploads folder (inside this folder)
            $uploadFolderFS = __DIR__ . "/uploads";
            if (!is_dir($uploadFolderFS)) {
                mkdir($uploadFolderFS, 0777, true);
            }

            // safe name + unique name
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $newName  = time() . "_" . uniqid() . "_" . $safeName;

            $destFS  = $uploadFolderFS . "/" . $newName;   // real server path
            $destWEB = "uploads/" . $newName;              // path saved in DB

            if (move_uploaded_file($tmpName, $destFS)) {

                $db  = new DatabaseConnection();
                $con = $db->openConnection();

                $uploadedBy = (int)($_SESSION["user_id"] ?? 0);

                // ✅ save in DB (with uploaded_by)
                $ok = $db->addCourseFile($con, $courseTitle, $fileType, $safeName, $destWEB, $uploadedBy);

                if (!$ok) {
                    $msg = "Database insert failed: " . $con->error;
                }

                $db->closeConnection($con);

                if ($ok) {
                    header("Location: i_dashboard.php?upload=success");
                    exit;
                }

            } else {
                $msg = "Upload failed. Check folder permission.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Upload Course File</title>

    <link rel="stylesheet" href="Upload.css">
    <link rel="stylesheet" href="../../Student/View/s_dashboard.css">
</head>
<body>

<div class="top-bar">
    <div class="top-links">
        <a href="i_dashboard.php">Dashboard</a>
    </div>
</div>

<div class="form-box">
    <h2>Upload File</h2>

    <?php if ($msg !== ""): ?>
        <div class="msg msg-error"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
     

    

    <form method="POST" enctype="multipart/form-data">

        <div class="form-row">
            <label>Course</label>
            <select name="course" required>
                <option value="">-- Select --</option>
                <option>Cyber Security</option>
                <option>Machine Learning</option>
                <option>Artificial Intelligence</option>
                <option>Programming in Java</option>
                <option>Web Development</option>
                <option>Data Science</option>
                <option>Python Programming</option>
                <option>Database Management</option>
                <option>Cloud Computing</option>
                <option>Blockchain</option>
                <option>Deep Learning</option>
                <option>Neural Networks</option>
                <option>Software Engineering</option>
                <option>Android App Development</option>
                <option>C++ Programming</option>
                <option>AI & ML Projects</option>
            </select>
        </div>

        <div class="form-row">
            <label>File Type</label>
            <select name="file_type" required>
                <option value="">-- Select --</option>
                <option>PDF</option>
                <option>Video</option>
                <option>Image</option>
                <option>Document</option>
                <option>ZIP</option>
            </select>
        </div>

        <div class="form-row">
            <label>Choose File</label>
            <input type="file" name="upload_file" required>
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Upload</button>
            <button class="btn btn-secondary" type="button" onclick="window.location.href='i_dashboard.php'">Cancel</button>
        </div>

    </form>
</div>

</body>
</html>
