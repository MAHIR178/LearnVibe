<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: instructor_login.php");
    exit;
}

$db = new DatabaseConnection();
$con = $db->openConnection();

$uploadedBy = (int)$_SESSION["user_id"];
$res = $db->getInstructorFiles($con, $uploadedBy);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Uploaded Files</title>
</head>
<body>

<h2>My Uploaded Files</h2>
<a href="../View/i_dashboard.php">Dashboard</a>
<hr>

<?php if (!$res || $res->num_rows == 0): ?>
    <p>No uploaded files yet.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Course</th>
            <th>Type</th>
            <th>File</th>
            <th>Date</th>
        </tr>
        <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["course_title"]); ?></td>
                <td><?php echo htmlspecialchars($row["file_type"]); ?></td>
                <td>
                    <a href="<?php echo htmlspecialchars($row["file_path"]); ?>" download>
                        Download
                    </a>
                </td>
                <td><?php echo htmlspecialchars($row["uploaded_at"]); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

</body>
</html>
<?php
$db->closeConnection($con);
?>
