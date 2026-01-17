<?php
session_start();

/* Admin protection */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

include '../Model/Database.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();

$error = '';
$result = $db->getAllInstructors($conn);
$instructors = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $instructors[] = $row;
    }
}
if (!$result) {
    $error = "Database error.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructors — Admin</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_view_instructors.css">
</head>
<body>

<!-- TOP BAR (reuse markup) -->
<div class="top-bar">
    <h2>Admin Dashboard</h2>
    <div class="top-links">
        <a href="admin_logout.php" class="logout">Logout</a>
    </div>
</div>


<div class="container">
    <div class="table-wrap">
        <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>

        <?php if ($error != '') { ?>
            <div class="card">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php } else { ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>University</th>
                        <th>Department</th>
                        <th>Year</th>
                        <th>Expertise</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
    
        <?php for ($i = 0; $i < count($instructors); $i++) { ?>
    <tr>
        <td><?php echo htmlspecialchars($instructors[$i]['id']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['full_name']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['email']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['contact_number']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['university_name']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['department']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['year']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['expertise']); ?></td>
        <td><?php echo htmlspecialchars($instructors[$i]['created_at']); ?></td>
        <td>

    <!-- EDIT BUTTON -->
    <form method="post" action="admin_edit_instructors.php">
        <input type="hidden"name="id" value="<?php echo $instructors[$i]['id']; ?>">
        <button type="submit"class="btn edit-btn"> Edit </button>
    </form>

    <!-- DELETE BUTTON -->
    <form method="post" action="../Controller/admin_delete_instructor.php">
        <input type="hidden" name="id" value="<?php echo $instructors[$i]['id']; ?>">
        <button type="submit" class="btn delete-btn"> Delete </button>
    </form>
</td>
    </tr>
<?php } ?>  
                </tbody>
            </table>

        <?php } ?>
    </div>
</div>

<div class="footer">
    <p>© 2025 Course Project | Admin Panel</p>
</div>
</body>
</html>
