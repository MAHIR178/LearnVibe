<?php
session_start();
include '../../Admin/Model/Database.php';

$user_email = $_SESSION['email'];
$db = new DatabaseConnection();
$conn = $db->openConnection();
$user = $db->getUserProfileByEmail($conn, $user_email);

$db->closeConnection($conn);

$backPage = "s_dashboard.php"; // Default for students

if ($_SESSION["role"] === "instructor") {
    $backPage = "../../Instructor/View/i_dashboard.php"; // For instructors
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="profile_view.css">
</head>

<body>
    <div class="profile-overlay">
        <div class="profile-container">


            <div class="profile-header">
                My Profile
            </div>

            <div class="profile-info">
                <div class="info-group">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo $user['full_name']; ?></div>
                </div>

                <div class="info-group">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo $user['email']; ?></div>
                </div>

                <div class="info-group">
                    <div class="info-label">Contact Number</div>
                    <div class="info-value"><?php echo $user['contact_number']; ?></div>
                </div>

                <div class="info-group">
                    <div class="info-label">University</div>
                    <div class="info-value"><?php echo $user['university_name']; ?></div>
                </div>

                <div class="info-group">
                    <div class="info-label">Department</div>
                    <div class="info-value"><?php echo $user['department']; ?></div>
                </div>

                <div class="info-group">
                    <div class="info-label">Role</div>
                    <div class="info-value"><?php echo $user['role']; ?></div>
                </div>

                <?php if ($user['role'] == 'student'): ?>
                    <div class="info-group">
                        <div class="info-label">Year</div>
                        <div class="info-value"><?php echo $user['year']; ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($user['role'] == 'instructor'): ?>
                    <div class="info-group">
                        <div class="info-label">Expertise</div>
                        <div class="info-value"><?php echo $user['expertise']; ?></div>
                    </div>
                <?php endif; ?>

            </div>

            <button type="button" class="back-button" onclick="window.location.href='<?php echo $backPage; ?>'">
                Back
            </button>

        </div>
    </div>
</body>

</html>