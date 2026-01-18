<?php
session_start();
include '../../Admin/Model/Database.php';

$user = null;
$show_form = false;
$message = null;
$message_type = null;
$error = null;

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: student_login.php");
    exit;
}

// Fetch current user data for displaying in form
$user_email = $_SESSION['email'];
$db = new DatabaseConnection();
$conn = $db->openConnection();

$user = $db->getUserByEmail($conn, $user_email);
$db->closeConnection($conn);

if ($user) {
    $show_form = true;
} else {
    $error = "User not found.";
}

// Check for messages from validation controller
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'info';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Check for errors from validation controller
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="profile_edit.css">
</head>
<body>

<div class="edit-overlay">
    <div class="edit-container">
        <div class="edit-header">
            Edit Profile
        </div>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <button onclick="window.location.href='s_dashboard.php'" class="cancel-button" style="width: 100%; margin-top: 20px;">
                Back to Dashboard
            </button>
        <?php elseif ($show_form ==true && isset($user)): ?>
            <form method="POST" action="../Controller/profile_edit_validation.php" class="edit-form">
                <!-- Full Name -->
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-input" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                
                <!-- Email (Readonly) -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input readonly-input" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    <div class="password-note">Email cannot be changed</div>
                </div>
                
                <!-- Contact Number -->
                <div class="form-group">
                    <label class="form-label">Contact Number *</label>
                    <input type="text" name="contact_number" class="form-input" 
                           value="<?php echo htmlspecialchars($user['contact_number']); ?>" required>
                </div>
                
                <!-- University -->
                <div class="form-group">
                    <label class="form-label">University Name *</label>
                    <input type="text" name="university_name" class="form-input" 
                           value="<?php echo htmlspecialchars($user['university_name']); ?>" required>
                </div>
                
                <!-- Department -->
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <input type="text" name="department" class="form-input" 
                           value="<?php echo htmlspecialchars($user['department']); ?>" required>
                </div>
                
                <!-- Year (for students only) -->
                <?php if ($user['role'] == 'student'): ?>
                <div class="form-group">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-input">
                        <option value="" <?php echo empty($user['year']) ? 'selected' : ''; ?>>Select Year</option>
                        <option value="1st Year" <?php echo ($user['year'] == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                        <option value="2nd Year" <?php echo ($user['year'] == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                        <option value="3rd Year" <?php echo ($user['year'] == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                        <option value="4th Year" <?php echo ($user['year'] == '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                    </select>
                </div>
                <?php endif; ?>
                
                <!-- Expertise (for instructors only) -->
                <?php if ($user['role'] == 'instructor'): ?>
                <div class="form-group">
                    <label class="form-label">Expertise</label>
                    <input type="text" name="expertise" class="form-input" 
                           value="<?php echo htmlspecialchars($user['expertise']); ?>">
                </div>
                <?php endif; ?>
                
                <!-- Password Change -->
                <div class="form-group">
                    <label class="form-label">New Password </label>
                    <input type="password" name="password" class="form-input" 
                           placeholder="Enter new password">
                    
                </div>
                
                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-input" 
                           placeholder="Confirm new password">
                </div>
                
                <!-- Buttons -->
                <div class="button-container">
                    <button type="submit" name="save_changes" class="save-button">
                        Save Changes
                    </button>
                    <button type="button" class="cancel-button" onclick="window.location.href='<?= $backPage ?>'">
                        Cancel
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="../Controller/JS/profile_edit.js"></script>

</body>
</html>