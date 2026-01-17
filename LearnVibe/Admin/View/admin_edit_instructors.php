<?php
session_start();

/* Admin protection */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

include '../Model/Database.php';

$instructor = null;
$show_form = false;
$message = null;
$message_type = null;
$error = null;

// Get instructor ID from POST or SESSION
$instructor_id = intval($_POST['id'] ?? $_SESSION['instructor_id'] ?? 0);
if (isset($_SESSION['instructor_id'])) {
    unset($_SESSION['instructor_id']);
}

// Fetch instructor data
$db = new DatabaseConnection();
$conn = $db->openConnection();

$instructor = $db->getInstructorById($conn, $instructor_id);
$db->closeConnection($conn);

if ($instructor) {
    $show_form = true;
} else {
    $error = "Instructor not found.";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_edit_instructors.css">
</head>
<body>

<div class="edit-overlay">
    <div class="edit-container">
        <div class="edit-header">
            Edit Instructor
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
            <button onclick="window.location.href='admin_view_instructors.php'" class="cancel-button" style="width: 100%; margin-top: 20px;">
                Back to Instructors
            </button>
        <?php elseif ($show_form ==true && isset($instructor)): ?>
            <form method="POST" action="../Controller/admin_edit_instructor_validation.php" class="edit-form">
                <!-- Instructor ID (Hidden) -->
                <input type="hidden" name="instructor_id" value="<?php echo htmlspecialchars($instructor['id']); ?>">

                <!-- Full Name -->
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-input" 
                           value="<?php echo htmlspecialchars($instructor['full_name']); ?>" required>
                </div>
                
                <!-- Email (Readonly) -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input readonly-input" 
                           value="<?php echo htmlspecialchars($instructor['email']); ?>" readonly>
                    <div class="password-note">Email cannot be changed</div>
                </div>
                
                <!-- Contact Number -->
                <div class="form-group">
                    <label class="form-label">Contact Number *</label>
                    <input type="text" name="contact_number" class="form-input" 
                           value="<?php echo htmlspecialchars($instructor['contact_number']); ?>" required>
                </div>
                
                <!-- University -->
                <div class="form-group">
                    <label class="form-label">University Name *</label>
                    <input type="text" name="university_name" class="form-input" 
                           value="<?php echo htmlspecialchars($instructor['university_name']); ?>" required>
                </div>
                
                <!-- Department -->
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <input type="text" name="department" class="form-input" 
                           value="<?php echo htmlspecialchars($instructor['department']); ?>" required>
                </div>
                
                <!-- Expertise -->
                <div class="form-group">
                    <label class="form-label">Expertise</label>
                    <input type="text" name="expertise" class="form-input" 
                           value="<?php echo htmlspecialchars($instructor['expertise']); ?>">
                </div>
                
                <!-- Buttons -->
                <div class="button-container">
                    <button type="submit" name="save_changes" class="save-button">
                        Save Changes
                    </button>
                    <button type="button" onclick="window.location.href='admin_view_instructors.php'" class="cancel-button">
                        Back
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
