<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: ../View/instructor_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location:../View/Upload.php");
    exit;
}

$courseTitle = trim($_POST["course"] ?? "");
$fileType    = trim($_POST["file_type"] ?? "");

if ($courseTitle === "" || $fileType === "") {
    $_SESSION["upload_error"] = "Please select course and file type.";
    header("Location:../View/Upload.php");
    exit;
}

if (!isset($_FILES["upload_file"]) || $_FILES["upload_file"]["error"] !== 0) {
    $_SESSION["upload_error"] = "Please choose a file.";
    header("Location:../View/Upload.php");
    exit;
}

$originalName = basename($_FILES["upload_file"]["name"]);
$tmpName      = $_FILES["upload_file"]["tmp_name"];

// block dangerous extensions
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$blocked = ["php", "phtml", "phar", "php3", "php4", "php5"];

if (in_array($ext, $blocked, true)) {
    $_SESSION["upload_error"] = "This file type is not allowed.";
    header("Location:../View/Upload.php");
    exit;
}

// uploads folder
$uploadFolderFS = __DIR__ . "/uploads";
if (!is_dir($uploadFolderFS)) {
    mkdir($uploadFolderFS, 0777, true);
}

// safe name + unique
$safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
$newName  = time() . "_" . uniqid() . "_" . $safeName;

$destFS  = $uploadFolderFS . "/" . $newName;   // server path
$destWEB = "uploads/" . $newName;              // saved in DB

if (!move_uploaded_file($tmpName, $destFS)) {
    $_SESSION["upload_error"] = "Upload failed. Check folder permission.";
    header("Location:../View/Upload.php");
    exit;
}

$db  = new DatabaseConnection();
$con = $db->openConnection();

$uploadedBy = (int)($_SESSION["user_id"] ?? 0);

// insert into DB
$ok = $db->addCourseFile($con, $courseTitle, $fileType, $safeName, $destWEB, $uploadedBy);

if (!$ok) {
    $_SESSION["upload_error"] = "Database insert failed: " . $con->error;
    $db->closeConnection($con);
    header("Location:../View/Upload.php");
    exit;
}

$db->closeConnection($con);

// success -> back to dashboard
header("Location:../View/i_dashboard.php?upload=success");
exit;
