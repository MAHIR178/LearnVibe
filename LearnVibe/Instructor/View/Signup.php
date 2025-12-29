<?php

require_once ('../../Admin/Model/Database.php');

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db   = new DatabaseConnection();
    $conn = $db->openConnection();

    function esc($conn, $value) {
        return $conn->real_escape_string(trim($value));
    }

    if (isset($_POST["student_email"])) {
        $role       = 'student';
        $full_name  = esc($conn, $_POST["student_name"]);
        $email      = esc($conn, $_POST["student_email"]);
        $contact    = esc($conn, $_POST["student_contact_number"]);
        $university = esc($conn, $_POST["student_university_name"]);
        $department = esc($conn, $_POST["student_department"]);
        $year       = esc($conn, $_POST["student_year"]);
        $password   = esc($conn, $_POST["student_password"]);
        $confirm    = esc($conn, $_POST["student_confirm_password"]);

        if ($password !== $confirm) {
            $errors[] = "Student passwords do not match.";
        }

        if (empty($errors)) {
            $checkSql = "SELECT id FROM users WHERE email = '$email'";
            $checkRes = $conn->query($checkSql);
            if ($checkRes && $checkRes->num_rows > 0) {
                $errors[] = "An account already exists with this email.";
            }
        }

        if (empty($errors)) {
            $sql = "
                INSERT INTO users 
                    (role, full_name, email, contact_number, university_name, department, year, expertise, password)
                VALUES
                    ('$role', '$full_name', '$email', '$contact', '$university', '$department', '$year', NULL, '$password')
            ";
            if ($conn->query($sql)) {
                $success = "Student account created successfully. You can now log in.";
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    } elseif (isset($_POST["instructor_email"])) {
        $role       = 'instructor';
        $full_name  = esc($conn, $_POST["instructor_name"]);
        $email      = esc($conn, $_POST["instructor_email"]);
        $contact    = esc($conn, $_POST["instructor_contact_number"]);
        $university = esc($conn, $_POST["instructor_university_name"]);
        $department = esc($conn, $_POST["instructor_department"]);
        $expertise  = esc($conn, $_POST["expertise"]);
        $password   = esc($conn, $_POST["instructor_password"]);
        $confirm    = esc($conn, $_POST["instructor_confirm_password"]);

        if ($password !== $confirm) {
            $errors[] = "Instructor passwords do not match.";
        }

        if (empty($errors)) {
            $checkSql = "SELECT id FROM users WHERE email = '$email'";
            $checkRes = $conn->query($checkSql);
            if ($checkRes && $checkRes->num_rows > 0) {
                $errors[] = "An account already exists with this email.";
            }
        }

        if (empty($errors)) {
            $sql = "
                INSERT INTO users 
                    (role, full_name, email, contact_number, university_name, department, year, expertise, password)
                VALUES
                    ('$role', '$full_name', '$email', '$contact', '$university', '$department', NULL, '$expertise', '$password')
            ";
            if ($conn->query($sql)) {
                $success = "Instructor account created successfully. You can now log in.";
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    }

    $db->closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
    <style>
        .field-error {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <!-- removed blur-layer, content starts directly -->
    <div class="auth-wrapper">
        <div class="signup-card">

            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $e): ?>
                        <p><?php echo htmlspecialchars($e); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php elseif (!empty($success)): ?>
                <div class="success-box">
                    <p><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>

            <!-- Tabs -->
            <input type="radio" name="role" id="student-tab" checked>
            <input type="radio" name="role" id="instructor-tab">

            <div class="tabs">
                <label for="student-tab" class="tab-label">Student</label>
                <label for="instructor-tab" class="tab-label">Instructor</label>
            </div>

            <div class="forms">

                <!-- STUDENT FORM -->
                <form class="form student-form" action="" method="post">
                    <h2>Create Student Account</h2>

                    <div class="form-row">
                        <label>Full Name</label>
                        <input type="text" id="student_name" name="student_name" required>
                        <span id="student_name_err" class="field-error"></span>
                    </div>

                    <div class="form-row">
                        <label>Email</label>
                        <input type="email" id="student_email" name="student_email" required>
                        <span id="student_email_err" class="field-error"></span>
                    </div>

                    <div class="form-row">
                        <label>Contact Number</label>
                        <input type="text" id="student_contact_number" name="student_contact_number" required>
                        <span id="student_contact_err" class="field-error"></span>
                    </div>

                    <div class="form-row">
                        <label>University Name</label>
                        <input type="text" id="student_university_name" name="student_university_name" required>
                        <span id="student_university_err" class="field-error"></span>
                    </div>

                    <div class="form-row inline">
                        <div>
                            <label>Department</label>
                            <input type="text" id="student_department" name="student_department" required>
                            <span id="student_department_err" class="field-error"></span>
                        </div>
                        <div>
                            <label>Year</label>
                            <select id="student_year" name="student_year" required>
                                <option value="">Select year</option>
                                <option>1st Year</option>
                                <option>2nd Year</option>
                                <option>3rd Year</option>
                                <option>4th Year</option>
                            </select>
                            <span id="student_year_err" class="field-error"></span>
                        </div>
                    </div>

                    <div class="form-row inline">
                        <div class="input-with-icon">
                            <label>Password</label>
                            <div class="field-wrapper">
                                <span class="field-icon-left">🔒</span>
                                <input
                                    type="password"
                                    id="student_password"
                                    name="student_password"
                                    minlength="8"
                                    required
                                >
                            </div>
                            <small class="password-hint">
                                Use at least <strong>8 characters</strong>, including a <strong>number</strong> and a <strong>symbol</strong>.
                            </small>
                            <span id="student_password_err" class="field-error"></span>
                        </div>

                        <div class="input-with-icon">
                            <label>Confirm Password</label>
                            <div class="field-wrapper">
                                <span class="field-icon-left">✅</span>
                                <input
                                    type="password"
                                    id="student_confirm_password"
                                    name="student_confirm_password"
                                    minlength="8"
                                    required
                                >
                            </div>
                            <span id="student_confirm_password_err" class="field-error"></span>
                        </div>
                    </div>

                    <div class="form-row checkbox-row">
                        <label>
                            <input type="checkbox" id="student_terms" required>
                            I agree to the Terms &amp; Conditions.
                        </label>
                        <span id="student_terms_err" class="field-error"></span>
                    </div>

                    <button type="submit" class="primary-btn">Sign Up as Student</button>
                </form>

               
                <form class="form instructor-form" action="" method="post">
                    <h2>Create Instructor Account</h2>

                    <div class="form-row">
                        <label>Full Name</label>
                        <input type="text" id="instructor_name" name="instructor_name" required>
                        <span id="instructor_name_err" class="field-error"></span>
                    </div>

                    <div class="form-row">
                        <label>Email</label>
                        <input type="email" id="instructor_email" name="instructor_email" required>
                        <span id="instructor_email_err" class="field-error"></span>
                    </div>

                    <div class="form-row">
                        <label>Contact Number</label>
                        <input type="text" id="instructor_contact_number" name="instructor_contact_number" required>
                        <span id="instructor_contact_err" class="field-error"></span>
                    </div>

                    <div class="form-row">
                        <label>University Name</label>
                        <input type="text" id="instructor_university_name" name="instructor_university_name" required>
                        <span id="instructor_university_err" class="field-error"></span>
                    </div>

                    <div class="form-row inline">
                        <div>
                            <label>Department</label>
                            <input type="text" id="instructor_department" name="instructor_department" required>
                            <span id="instructor_department_err" class="field-error"></span>
                        </div>
                        <div>
                            <label>Area of Expertise</label>
                            <input
                                id="expertise-text"
                                type="text"
                                name="expertise"
                                placeholder="e.g. Machine Learning, Web Dev"
                                required
                            >
                            <span id="instructor_expertise_err" class="field-error"></span>
                        </div>
                    </div>

                    <div class="form-row inline">
                        <div class="input-with-icon">
                            <label>Password</label>
                            <div class="field-wrapper">
                                <span class="field-icon-left">🔒</span>
                                <input
                                    type="password"
                                    id="instructor_password"
                                    name="instructor_password"
                                    minlength="8"
                                    required
                                >
                            </div>
                            <small class="password-hint">
                                Use at least <strong>8 characters</strong>, including a <strong>number</strong> and a <strong>symbol</strong>.
                            </small>
                            <span id="instructor_password_err" class="field-error"></span>
                        </div>

                        <div class="input-with-icon">
                            <label>Confirm Password</label>
                            <div class="field-wrapper">
                                <span class="field-icon-left">✅</span>
                                <input
                                    type="password"
                                    id="instructor_confirm_password"
                                    name="instructor_confirm_password"
                                    minlength="8"
                                    required
                                >
                            </div>
                            <span id="instructor_confirm_password_err" class="field-error"></span>
                        </div>
                    </div>

                    <div class="form-row checkbox-row">
                        <label>
                            <input type="checkbox" id="instructor_terms" required>
                            I confirm that the information provided is accurate.
                        </label>
                        <span id="instructor_terms_err" class="field-error"></span>
                    </div>

                    <button type="submit" class="primary-btn">Sign Up as Instructor</button>
                </form>
            </div>

            <p class="login-text">
                Already have an account?
                <a href="Login.php">Log in</a>
            </p>
        </div>
    </div>

   
    <script src="../Controller/SignValidation.js"></script>

</body>
</html>
