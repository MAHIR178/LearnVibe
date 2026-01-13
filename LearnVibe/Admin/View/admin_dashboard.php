<?php
session_start();

/* ===== ADMIN ACCESS PROTECTION ===== */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <h2>Admin Dashboard</h2>

    <div class="top-links">
        <a href="admin_logout.php" class="logout">Logout</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container">
    <div class="grid">

        <div class="card">
            <h3>Students</h3>
            <p>View all registered students</p>
            <a href="admin_view_students.php">View</a>
        </div>

        <div class="card">
            <h3>Instructors</h3>
            <p>View all instructors</p>
            <a href="admin_view_instructors.php">View</a>
        </div>

        <div class="card">
            <h3>Courses</h3>
            <p>View & manage courses</p>
            <a href="#">Open</a>
        </div>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>© 2025 Course Project | Admin Panel</p>
</div>
</body>
</html>
