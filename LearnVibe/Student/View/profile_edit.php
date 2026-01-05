<?php
session_start();
require_once('../../Admin/Model/Database.php');

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/Login.php");
    exit;
}

$user_email = $_SESSION['email'];

$db = new DatabaseConnection();
$conn = $db->openConnection();

$show_form = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {

    $full_name = trim($_POST['full_name'] ?? "");
    $contact_number = trim($_POST['contact_number'] ?? "");
    $university_name = trim($_POST['university_name'] ?? "");
    $department = trim($_POST['department'] ?? "");
    $year = trim($_POST['year'] ?? "");
    $expertise = trim($_POST['expertise'] ?? "");
    $password = trim($_POST['password'] ?? "");

    // Update (with or without password)
    if ($password !== "") {
        $ok = $db->updateUserProfileWithPassword(
            $conn,
            $user_email,
            $full_name,
            $contact_number,
            $university_name,
            $department,
            $year,
            $expertise,
            $password
        );
    } else {
        $ok = $db->updateUserProfile(
            $conn,
            $user_email,
            $full_name,
            $contact_number,
            $university_name,
            $department,
            $year,
            $expertise
        );
    }

    if ($ok) {
        $message = "Profile updated successfully!";
        $message_type = "success";
        $_SESSION['full_name'] = $full_name;
    } else {
        $error = "Error updating profile.";
    }
}

// Fetch current user data (for form)
$user = $db->getUserByEmail($conn, $user_email);

if ($user) {
    $show_form = true;
} else {
    $error = "User not found.";
    $show_form = false;
}

$db->closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="profile_edit.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        
        .password-note {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }
        
        .readonly-input {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
    </style>
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
        <?php elseif ($show_form && isset($user)): ?>
            <form method="POST" class="edit-form">
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
                    <button type="button" onclick="window.location.href='s_dashboard.php'" class="cancel-button">
                        Back
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
// JavaScript to confirm password match
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            // Only check if password is not empty
            if (password !== '' && password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
    }
    
    // Auto-hide messages after 5 seconds
    setTimeout(function() {
        const messages = document.querySelectorAll('.message');
        messages.forEach(function(msg) {
            msg.style.display = 'none';
        });
    }, 5000);
});
</script>

</body>
</html>