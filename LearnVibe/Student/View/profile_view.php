<?php
session_start();

// Check if user is logged in - use the same check as dashboard
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/Login.php");
    exit;
}

// Get user email from session (not from GET parameter)
$user_email = $_SESSION['email'];

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "learnvibe";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user data using session email
$sql = "SELECT role, full_name, email, contact_number, university_name, department, year, expertise 
        FROM users WHERE email = ?";
    
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    $error = "User not found.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
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
            
            <button onclick="window.location.href='s_dashboard.php'" class="back-button">
                Back
            </button>
        </div>
    </div>
<?php endif; ?>

</body>
</html>