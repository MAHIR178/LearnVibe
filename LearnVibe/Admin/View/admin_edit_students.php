<?php
session_start();

/* Admin protection */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

include '../Model/Database.php';

$student = null;
$show_form = false;
$message = null;
$message_type = null;
$error = null;

// Get student ID from POST or SESSION
$student_id = intval($_POST['id'] ?? $_SESSION['student_id'] ?? 0);
if (isset($_SESSION['student_id'])) {
    unset($_SESSION['student_id']);
}

// Fetch student data
$db = new DatabaseConnection();
$conn = $db->openConnection();

$student = $db->getStudentById($conn, $student_id);
$db->closeConnection($conn);

if ($student) {
    $show_form = true;
} else {
    $error = "Student not found.";
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
    <title>Edit Student</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_edit_students.css">
</head>
<body>

<div class="edit-overlay">
    <div class="edit-container">
        <div class="edit-header">
            Edit Student
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
            <button onclick="window.location.href='admin_view_students.php'" class="cancel-button" style="width: 100%; margin-top: 20px;">
                Back to Students
            </button>
        <?php elseif ($show_form ==true && isset($student)): ?>
            <form method="POST" action="../Controller/admin_edit_student_validation.php" class="edit-form">
                <!-- Student ID (Hidden) -->
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">

                <!-- Full Name -->
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-input" 
                           value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                </div>
                
                <!-- Email (Readonly) -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input readonly-input" 
                           value="<?php echo htmlspecialchars($student['email']); ?>" readonly>
                    <div class="password-note">Email cannot be changed</div>
                </div>
                
                <!-- Contact Number -->
                <div class="form-group">
                    <label class="form-label">Contact Number *</label>
                    <input type="text" name="contact_number" class="form-input" 
                           value="<?php echo htmlspecialchars($student['contact_number']); ?>" required>
                </div>
                
                <!-- University -->
                <div class="form-group">
                    <label class="form-label">University Name *</label>
                    <input type="text" name="university_name" class="form-input" 
                           value="<?php echo htmlspecialchars($student['university_name']); ?>" required>
                </div>
                
                <!-- Department -->
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <input type="text" name="department" class="form-input" 
                           value="<?php echo htmlspecialchars($student['department']); ?>" required>
                </div>
                
                <!-- Year -->
                <div class="form-group">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-input">
                        <option value="" <?php echo empty($student['year']) ? 'selected' : ''; ?>>Select Year</option>
                        <option value="1st Year" <?php echo ($student['year'] == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                        <option value="2nd Year" <?php echo ($student['year'] == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                        <option value="3rd Year" <?php echo ($student['year'] == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                        <option value="4th Year" <?php echo ($student['year'] == '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                    </select>
                </div>
                
                <!-- Buttons -->
                <div class="button-container">
                    <button type="submit" name="save_changes" class="save-button">
                        Save Changes
                    </button>
                    <button type="button" onclick="window.location.href='admin_view_students.php'" class="cancel-button">
                        Back
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
