<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: ../View/instructor_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/i_dashboard.php");
    exit;
}

$courseTitle = trim($_POST["course"] ?? "");
$fileType    = trim($_POST["file_type"] ?? "");

if ($courseTitle === "" || $fileType === "") {
    $_SESSION["upload_error"] = "Please select course and file type.";
    header("Location: ../View/i_dashboard.php");
    exit;
}

if (!isset($_FILES["upload_file"]) || $_FILES["upload_file"]["error"] !== 0) {
    $_SESSION["upload_error"] = "Please choose a file.";
    header("Location: ../View/i_dashboard.php");
    exit;
}

$originalName = basename($_FILES["upload_file"]["name"]);
$tmpName      = $_FILES["upload_file"]["tmp_name"];


$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

$blocked = ["php", "phtml", "phar", "php3", "php4", "php5"];
if (in_array($ext, $blocked, true)) {
    $_SESSION["upload_error"] = "This file type is not allowed.";
    header("Location: ../View/i_dashboard.php");
    exit;
}


$typeMap = [
    "PDF"      => ["pdf"],
    "PPTX"     => ["ppt", "pptx"],
    "PPT"      => ["ppt", "pptx"],       
    "DOC"      => ["doc", "docx"],       
    "DOCX"     => ["docx"],              
    "DOCUMENT" => ["doc", "docx"],       
];


$typeKey = strtoupper($fileType);


if (!isset($typeMap[$typeKey])) {
    $_SESSION["upload_error"] = "Only PDF, PPT/PPTX, and DOC/DOCX files are allowed.";
    header("Location: ../View/i_dashboard.php");
    exit;
}


$allowedExtsForType = $typeMap[$typeKey];
if (!in_array($ext, $allowedExtsForType, true)) {
    $_SESSION["upload_error"] =
        "Selected file type is {$fileType}, but you uploaded a .{$ext} file. Please upload the correct file.";
    header("Location: ../View/i_dashboard.php");
    exit;
}


$uploadFolderFS = __DIR__ . "/uploads";
if (!is_dir($uploadFolderFS)) {
    mkdir($uploadFolderFS, 0777, true);
}


$safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
$newName  = time() . "_" . uniqid() . "_" . $safeName;

$destFS  = $uploadFolderFS . "/" . $newName;
$destWEB = "uploads/" . $newName;

if (!move_uploaded_file($tmpName, $destFS)) {
    $_SESSION["upload_error"] = "Upload failed. Check folder permission.";
    header("Location: ../View/i_dashboard.php");
    exit;
}


$db  = new DatabaseConnection();
$con = $db->openConnection();

$uploadedBy = (int)($_SESSION["user_id"] ?? 0);


$ok = $db->addCourseFile($con, $courseTitle, $fileType, $safeName, $destWEB, $uploadedBy);

if (!$ok) {
    $_SESSION["upload_error"] = "Database insert failed: " . $con->error;
    $db->closeConnection($con);
    header("Location: ../View/i_dashboard.php");
    exit;
}

$db->closeConnection($con);


header("Location: ../View/i_dashboard.php?upload=success");
exit;
