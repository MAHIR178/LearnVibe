<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? 'info';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset(
    $_SESSION['message'],
    $_SESSION['message_type'],
    $_SESSION['errors'],
    $_SESSION['old']
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Instructor</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_edit_students.css">
</head>
<body>

<div class="edit-overlay">
    <div class="edit-container">
        <div class="edit-header">Add New Instructor</div>

        <!-- Success / Info message -->
        <?php if ($message): ?>
            <div class="message <?php echo htmlspecialchars($message_type); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST"
              action="../Controller/admin_add_instructor_validation.php"
              class="edit-form"
              novalidate>

            <!-- Full Name -->
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="full_name" class="form-input"
                       value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>">
                <?php if (isset($errors['full_name'])): ?>
                    <span class="field-error"><?php echo $errors['full_name']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-input"
                       value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?>
                    <span class="field-error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Contact -->
            <div class="form-group">
                <label class="form-label">Contact Number *</label>
                <input type="text" name="contact_number" class="form-input"
                       value="<?php echo htmlspecialchars($old['contact_number'] ?? ''); ?>">
                <?php if (isset($errors['contact_number'])): ?>
                    <span class="field-error"><?php echo $errors['contact_number']; ?></span>
                <?php endif; ?>
            </div>

            <!-- University -->
            <div class="form-group">
                <label class="form-label">University Name *</label>
                <input type="text" name="university_name" class="form-input"
                       value="<?php echo htmlspecialchars($old['university_name'] ?? ''); ?>">
                <?php if (isset($errors['university_name'])): ?>
                    <span class="field-error"><?php echo $errors['university_name']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Department -->
            <div class="form-group">
                <label class="form-label">Department *</label>
                <input type="text" name="department" class="form-input"
                       value="<?php echo htmlspecialchars($old['department'] ?? ''); ?>">
                <?php if (isset($errors['department'])): ?>
                    <span class="field-error"><?php echo $errors['department']; ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Expertise -->
            <div class="form-group">
                <label class="form-label">Expertise</label>
                <input type="text" name="expertise" class="form-input"
                       value="<?php echo htmlspecialchars($old['expertise'] ?? ''); ?>">
                <?php if (isset($errors['expertise'])): ?>
                    <span class="field-error"><?php echo $errors['expertise']; ?></span>
                <?php endif; ?>
            </div>    

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-input">
                <?php if (isset($errors['password'])): ?>
                    <span class="field-error"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="confirm_password" class="form-input">
                <?php if (isset($errors['confirm_password'])): ?>
                    <span class="field-error"><?php echo $errors['confirm_password']; ?></span>
                <?php endif; ?>
            </div>

            <!-- Buttons -->
            <div class="button-container">
                <button type="submit" name="add_student" class="save-button">
                    Add Student
                </button>
                <button type="button"
                        onclick="window.location.href='admin_view_students.php'"
                        class="cancel-button">
                    Back
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
