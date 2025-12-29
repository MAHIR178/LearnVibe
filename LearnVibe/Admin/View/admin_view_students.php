 <?php
session_start();

/* Protect page for admin only */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

require_once __DIR__ . '/../Model/Database.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();

$students = [];
$error = '';

// Query the `users` table for students only and select relevant columns (exclude password)
$sql = "SELECT `id`,`full_name`,`email`,`contact_number`,`university_name`,`department`,`year`,`expertise`,`created_at` FROM `users` WHERE `role`='student' ORDER BY `created_at` DESC";
$result = $conn->query($sql);
if ($result) {
    $students = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = 'Database error: ' . htmlspecialchars($conn->error);
}

$db->closeConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students — Admin</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_view_students.css">
</head>
<body>

<!-- TOP BAR (reuse markup) -->
<div class="top-bar">
    <h2>Admin Dashboard</h2>
    <div class="top-links">
        <div class="profile">
            <a href="#" id="profile-btn">Admin ▼</a>
            <div class="profile-menu" id="profile-menu">
                <a href="#">Profile</a>
            </div>
        </div>
        <a href="admin_logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="container">
    <div class="table-wrap">
        <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>

        <?php if ($error): ?>
            <div class="card"><p><?php echo $error; ?></p></div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        // show table headers from first row keys
                        $first = reset($students);
                        foreach (array_keys($first) as $col): ?>
                            <th><?php echo htmlspecialchars($col); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $row): ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td><?php echo htmlspecialchars((string)$cell); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div class="footer">
    <p>© 2025 Course Project | Admin Panel</p>
</div>

<!-- JS: Profile dropdown -->
<script>
const profileBtn = document.getElementById('profile-btn');
const profileMenu = document.getElementById('profile-menu');
if (profileBtn) {
    profileBtn.addEventListener('click', function (e) {
        e.preventDefault();
        profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
    });
}
document.addEventListener('click', function (e) {
    if (profileBtn && !profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.style.display = 'none';
    }
});
</script>

</body>
</html>
