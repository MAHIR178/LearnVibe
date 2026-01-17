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

/* -----------------------
   1) Extension validation
------------------------*/
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

// block dangerous extensions (keep your rule)
$blocked = ["php", "phtml", "phar", "php3", "php4", "php5"];
if (in_array($ext, $blocked, true)) {
    $_SESSION["upload_error"] = "This file type is not allowed.";
    header("Location: ../View/i_dashboard.php");
    exit;
}

/* ---------------------------------------------
   2) Allow only PDF / PPT/PPTX / DOC/DOCX
   3) Match extension with selected file_type
----------------------------------------------*/

// Map dropdown file type -> allowed extensions
// NOTE: Your dropdown currently has "Document" -> we treat it as DOC/DOCX
$typeMap = [
    "PDF"      => ["pdf"],
    "PPTX"     => ["ppt", "pptx"],
    "PPT"      => ["ppt", "pptx"],       // optional
    "DOC"      => ["doc", "docx"],       // optional
    "DOCX"     => ["docx"],              // optional
    "DOCUMENT" => ["doc", "docx"],       // your existing option
];

// Normalize selected file type
$typeKey = strtoupper($fileType);

// If instructor selected something else (Video/Image/ZIP), reject
if (!isset($typeMap[$typeKey])) {
    $_SESSION["upload_error"] = "Only PDF, PPT/PPTX, and DOC/DOCX files are allowed.";
    header("Location: ../View/i_dashboard.php");
    exit;
}

// Must match the chosen type
$allowedExtsForType = $typeMap[$typeKey];
if (!in_array($ext, $allowedExtsForType, true)) {
    $_SESSION["upload_error"] =
        "Selected file type is {$fileType}, but you uploaded a .{$ext} file. Please upload the correct file.";
    header("Location: ../View/i_dashboard.php");
    exit;
}

/* -----------------------
   Upload folder + save
------------------------*/
$uploadFolderFS = __DIR__ . "/uploads";
if (!is_dir($uploadFolderFS)) {
    mkdir($uploadFolderFS, 0777, true);
}

// safe name + unique
$safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
$newName  = time() . "_" . uniqid() . "_" . $safeName;

$destFS  = $uploadFolderFS . "/" . $newName;
$destWEB = "uploads/" . $newName;

if (!move_uploaded_file($tmpName, $destFS)) {
    $_SESSION["upload_error"] = "Upload failed. Check folder permission.";
    header("Location: ../View/i_dashboard.php");
    exit;
}

/* -----------------------
   DB insert
------------------------*/
$db  = new DatabaseConnection();
$con = $db->openConnection();

$uploadedBy = (int)($_SESSION["user_id"] ?? 0);

// Save original uploaded name (safeName is fine too)
$ok = $db->addCourseFile($con, $courseTitle, $fileType, $safeName, $destWEB, $uploadedBy);

if (!$ok) {
    $_SESSION["upload_error"] = "Database insert failed: " . $con->error;
    $db->closeConnection($con);
    header("Location: ../View/i_dashboard.php");
    exit;
}

$db->closeConnection($con);

// success
header("Location: ../View/i_dashboard.php?upload=success");
exit;
