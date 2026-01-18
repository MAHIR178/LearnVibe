<?php
session_start();
include '../../Admin/Model/Database.php';

$user = null;
$show_form = false;
$message = null;
$error = null;

if (!isset($_SESSION['email'])) {
    header("Location: student_login.php");
    exit;
}
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

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
                <div class="message success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="message error">
                    <?php echo $error; ?>
                </div>
                <button onclick="window.location.href='s_dashboard.php'" class="cancel-button back-button-full">
                    Back to Dashboard
                </button>
            <?php elseif ($show_form): ?>

                <!-- Edit Form -->
                <form method="POST" action="../Controller/profile_edit_validation.php" class="edit-form">

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-input" 
                               value="<?php echo $user['full_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input readonly-input" 
                               value="<?php echo $user['email']; ?>" readonly>
                        <div class="password-note">Email cannot be changed</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-input" 
                               value="<?php echo $user['contact_number']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">University</label>
                        <input type="text" name="university_name" class="form-input" 
                               value="<?php echo $user['university_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-input" 
                               value="<?php echo $user['department']; ?>" required>
                    </div>
                    <?php if ($user['role'] == 'student'): ?>
                    <div class="form-group">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-input">
                            <option value="">Select Year</option>
                            <option value="1st Year" <?php if($user['year'] == '1st Year') echo 'selected'; ?>>1st Year</option>
                            <option value="2nd Year" <?php if($user['year'] == '2nd Year') echo 'selected'; ?>>2nd Year</option>
                            <option value="3rd Year" <?php if($user['year'] == '3rd Year') echo 'selected'; ?>>3rd Year</option>
                            <option value="4th Year" <?php if($user['year'] == '4th Year') echo 'selected'; ?>>4th Year</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Expertise for instructors -->
                    <?php if ($user['role'] == 'instructor'): ?>
                    <div class="form-group">
                        <label class="form-label">Expertise</label>
                        <input type="text" name="expertise" class="form-input" 
                               value="<?php echo $user['expertise']; ?>">
                    </div>
                    <?php endif; ?>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" 
                               placeholder="Enter new password">
                        <div class="password-note">Leave empty if you don't want to change password</div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-input" 
                               placeholder="Confirm new password">
                    </div>
                    
                    <!-- Buttons -->
                    <div class="button-container">
                        <button type="submit" name="save_changes" class="save-button">
                            Save Changes
                        </button>
                        <button type="button" class="cancel-button" onclick="window.location.href='s_dashboard.php'">
                            Back
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>