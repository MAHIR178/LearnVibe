<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    exit;
}

$q = strtolower(trim($_POST["q"] ?? ""));

$db = new DatabaseConnection();
$con = $db->openConnection();

$uploadedBy = (int)($_SESSION["user_id"] ?? 0);
$res = $db->getInstructorFiles($con, $uploadedBy);

$found = 0;

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {

        $course = $row["course_title"] ?? "";
        $type   = $row["file_type"] ?? "";
        $path   = $row["file_path"] ?? "";
        $date   = $row["uploaded_at"] ?? "";

        // ✅ actual uploaded filename (best -> fallback)
        $realName = $row["original_name"] ?? ($row["file_name"] ?? basename($path));

        // ✅ search in BOTH course + file name
        $hay = strtolower($course . " " . $realName);

        if ($q !== "" && strpos($hay, $q) === false) {
            continue;
        }

        $found++;

        echo "<tr>";
        echo "<td>" . htmlspecialchars($course) . "</td>";
        echo "<td>" . htmlspecialchars($type) . "</td>";
        echo "<td><a href='" . htmlspecialchars($path) . "' download>" . htmlspecialchars($realName) . "</a></td>";
        echo "<td>" . htmlspecialchars($date) . "</td>";
        echo "</tr>";
    }
}

if ($found === 0) {
    echo "<tr><td colspan='4'>No matching files found.</td></tr>";
}

$db->closeConnection($con);
