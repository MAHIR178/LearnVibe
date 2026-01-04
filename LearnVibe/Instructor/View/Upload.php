<?php
session_start();
include "../../Admin/Model/Database.php";

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: Login.php");
    exit;
}

$courses = [
    "Cyber Security",
    "Machine Learning",
    "Artificial Intelligence",
    "Programming in Java",
    "Web Development",
    "Data Science",
    "Python Programming",
    "Database Management",
    "Cloud Computing",
    "Blockchain",
    "Deep Learning",
    "Neural Networks",
    "Software Engineering",
    "Android App Development",
    "C++ Programming",
    "AI & ML Projects"
];

$fileTypes = ["PDF", "Video", "Image", "Document", "ZIP"];

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $courseTitle = trim($_POST["course"] ?? "");
    $fileType    = trim($_POST["file_type"] ?? "");

    if ($courseTitle === "" || !in_array($courseTitle, $courses, true)) {
        $msg = "Please select a valid course.";
    } elseif ($fileType === "" || !in_array($fileType, $fileTypes, true)) {
        $msg = "Please select a valid file type.";
    } elseif (!isset($_FILES["upload_file"]) || $_FILES["upload_file"]["error"] !== UPLOAD_ERR_OK) {
        $msg = "Please choose a file.";
    } else {

        $originalName = basename($_FILES["upload_file"]["name"]);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // block php uploads
        $blocked = ["php","phtml","phar","php3","php4","php5"];
        if (in_array($ext, $blocked, true)) {
            $msg = "This file type is not allowed.";
        } else {

            // Folder: uploads/
            $uploadDirFS = __DIR__ . "/uploads";
            if (!is_dir($uploadDirFS)) {
                mkdir($uploadDirFS, 0777, true);
            }

            // safe file name
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $newName  = time() . "_" . uniqid() . "_" . $safeName;

            $destFS  = $uploadDirFS . "/" . $newName; // filesystem
            $destWEB = "uploads/" . $newName;         // web path

            if (!move_uploaded_file($_FILES["upload_file"]["tmp_name"], $destFS)) {
                $msg = "Upload failed. Check folder permission.";
            } else {

                $db = new DatabaseConnection();
                $con = $db->openConnection();

                // addCourseFile($con, course_slug, course_title, file_type, original_name, file_path)
                $ok = $db->addCourseFile($con, $courseTitle, $fileType, $safeName, $destWEB);


                // if insert fails, show mysql error
                if (!$ok) {
                    $msg = "DB insert failed: " . $con->error;
                }

                $db->closeConnection($con);

                if ($ok) {
                    // ✅ IMPORTANT: redirect to YOUR real dashboard file
                    header("Location: i_dashboard.php?upload=success");
                    // OR: header("Location: view_i_dashboard.php?upload=success");
                    exit;
                }
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
</head>
<body>


   
    <div class="top-links">
        <a href="i_dashboard.php">Dashboard</a>
        <a href="Logout.php">Logout</a>
    </div>
</div>

<div class="form-box">
    <h2>Upload File to a Course</h2>

    <?php if ($msg !== ""): ?>
        <div class="msg msg-error"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-row">
            <label>Select Course</label>
            <select name="course" required>
                <option value="">-- Choose --</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?php echo htmlspecialchars($c); ?>">
                        <?php echo htmlspecialchars($c); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <label>Select File Type</label>
            <select name="file_type" required>
                <option value="">-- Choose --</option>
                <?php foreach ($fileTypes as $t): ?>
                    <option value="<?php echo htmlspecialchars($t); ?>">
                        <?php echo htmlspecialchars($t); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <label>Choose File</label>
            <input type="file" name="upload_file" required>
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Submit</button>
            <button class="btn btn-secondary" type="button" onclick="window.location.href='i_dashboard.php'">Cancel</button>
        </div>
    </form>
</div>

</body>
</html>
