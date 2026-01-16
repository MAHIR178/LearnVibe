<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    exit; // no output if not allowed
}

$q = trim($_GET["q"] ?? "");

$db = new DatabaseConnection();
$con = $db->openConnection();

$uploadedBy = (int)$_SESSION["user_id"];
$res = $db->getInstructorFiles($con, $uploadedBy);

$found = 0;

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {

        // ✅ search inside these fields (safe even if some keys don't exist)
        $course = $row["course_title"] ?? "";
        $type   = $row["file_type"] ?? "";
        $path   = $row["file_path"] ?? "";
        $date   = $row["uploaded_at"] ?? "";

        // match if q empty OR q appears in any field
        $haystack = $course . " " . $type . " " . $path . " " . $date;

        if ($q === "" || stripos($haystack, $q) !== false) {
            $found++;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($course) . "</td>";
            echo "<td>" . htmlspecialchars($type) . "</td>";
            echo "<td><a href='" . htmlspecialchars($path) . "' download>Download</a></td>";
            echo "<td>" . htmlspecialchars($date) . "</td>";
            echo "</tr>";
        }
    }
}

if ($found === 0) {
    echo "<tr><td colspan='4'>No matching files found.</td></tr>";
}

$db->closeConnection($con);
