<?php

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? 'info';
$error = $_SESSION['error'] ?? null;

unset($_SESSION['message'], $_SESSION['message_type'], $_SESSION['error']);

if (!$res) {
    $error = "Database error: " . $conn->error;
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'info';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback — Admin</title>
    <link rel="stylesheet" href="../View/admin_dashboard.css">
    <link rel="stylesheet" href="../View/view_feedback.css">
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <h2>Admin Dashboard</h2>
    <div class="top-links">
        <a href="../View/admin_logout.php" class="logout">Logout</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container">
    <div class="table-wrap">

        <div class="table-header">
            <a href="../View/admin_dashboard.php" class="back">← Back to Dashboard</a>
        </div>

        <?php if (isset($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error != ''): ?>
            <div class="card">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php else: ?>

            <h2 class="table-title">All Student Feedback</h2>

            <?php if (!$res || mysqli_num_rows($res) === 0): ?>
            <div class="card">
                <p>No feedback found.</p>
            </div>
        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php while ($f = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($f['course_title'] ?? 'Unknown Course'); ?></td>
                        <td><?php echo htmlspecialchars($f['full_name'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($f['email'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($f['rating'] ?? '') . '/5'; ?></td>
                        <td><?php echo htmlspecialchars($f['comment'] ?? ''); ?></td>
                        <td>
                            <!-- DELETE BUTTON -->
                            <form method="post" action="../Controller/admin_delete_feedback.php">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($f['id']); ?>">
                                <button type="submit" class="btn delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>

            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>© 2025 Learnvibe | Admin Panel</p>
</div>

</body>
</html>
