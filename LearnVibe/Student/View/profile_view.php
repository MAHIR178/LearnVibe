<?php
session_start();
include '../../Admin/Model/Database.php';

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/Login.php");
    exit;
}

$user_email = $_SESSION['email'];

$db = new DatabaseConnection();
$conn = $db->openConnection();


$user = $db->getUserProfileByEmail($conn, $user_email);

$db->closeConnection($conn);

if (!$user) {
    $error = "User not found.";
}

$backPage = "s_dashboard.php"; // default

if (isset($_SESSION["role"]) && $_SESSION["role"] === "instructor") {
    $backPage = "../../Instructor/View/i_dashboard.php";   // adjust path if needed
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile View</title>
    <link rel="stylesheet" href="profile_view.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>

    <?php if (isset($error)): ?>
        <div class="profile-overlay">
            <div class="profile-container">
                <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
                <button onclick="window.location.href='s_dashboard.php'" class="back-button">
                    Back to Dashboard
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="profile-overlay">
            <div class="profile-container">
                <div class="profile-header">
                    Profile Information
                </div>

                <div class="profile-info">
                    <div class="info-group">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['contact_number']); ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">University</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['university_name']); ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Department</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['department']); ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Role</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['role']); ?></div>
                    </div>

                    <?php if ($user['role'] == 'student' && !empty($user['year'])): ?>
                        <div class="info-group">
                            <div class="info-label">Year</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['year']); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($user['role'] == 'instructor' && !empty($user['expertise'])): ?>
                        <div class="info-group">
                            <div class="info-label">Expertise</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['expertise']); ?></div>
                        </div>
                    <?php endif; ?>


                </div>

                <button type="button" class="back-button" onclick="window.location.href='<?= $backPage ?>'">
                    Back
                </button>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>