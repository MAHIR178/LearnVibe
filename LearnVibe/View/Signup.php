<?php
require_once "../Model/Database.php";

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
    }

   
    elseif (isset($_POST["instructor_email"])) {
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
    <title>LearnVibe | Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>

    <div class="blur-layer">

        <div class="auth-wrapper">
            <div class="signup-card">

              
                <?php if (!empty($errors)): ?>
                    <div class="error-box">
                        <?php foreach ($errors as $e): ?>
                            <p><?php echo htmlspecialchars($e); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($success): ?>
                    <div class="success-box">
                        <p><?php echo htmlspecialchars($success); ?></p>
                    </div>
                <?php endif; ?>

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
                            <input type="text" name="student_name" required>
                        </div>

                        <div class="form-row">
                            <label>Email</label>
                            <input type="email" name="student_email" required>
                        </div>

                        <div class="form-row">
                            <label>Contact Number</label>
                            <input type="text" name="student_contact_number" required>
                        </div>

                        <div class="form-row">
                            <label>University Name</label>
                            <input type="text" name="student_university_name" required>
                        </div>

                        <div class="form-row inline">
                            <div>
                                <label>Department</label>
                                <input type="text" name="student_department" required>
                            </div>
                            <div>
                                <label>Year</label>
                                <select name="student_year" required>
                                    <option value="">Select year</option>
                                    <option>1st Year</option>
                                    <option>2nd Year</option>
                                    <option>3rd Year</option>
                                    <option>4th Year</option>
                                </select>
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
                                    <button
                                        type="button"
                                        class="toggle-password"
                                        data-target="student_password"
                                        aria-label="Show or hide password"
                                    >
                                        <img src="assets/wired-lineal-69-eye.svg" alt="Toggle password visibility">
                                    </button>
                                </div>
                                <small class="password-hint">
                                    Use at least <strong>8 characters</strong>, including a <strong>number</strong> and a <strong>symbol</strong>.
                                </small>
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
                                    <button
                                        type="button"
                                        class="toggle-password"
                                        data-target="student_confirm_password"
                                        aria-label="Show or hide password"
                                    >
                                        <img src="assets/wired-lineal-69-eye.svg" alt="Toggle password visibility">
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row checkbox-row">
                            <label>
                                <input type="checkbox" required>
                                I agree to the Terms &amp; Conditions.
                            </label>
                        </div>

                        <button type="submit" class="primary-btn">Sign Up as Student</button>
                    </form>

                    <!-- INSTRUCTOR FORM -->
                    <form class="form instructor-form" action="" method="post">
                        <h2>Create Instructor Account</h2>

                        <div class="form-row">
                            <label>Full Name</label>
                            <input type="text" name="instructor_name" required>
                        </div>

                        <div class="form-row">
                            <label>Email</label>
                            <input type="email" name="instructor_email" required>
                        </div>

                        <div class="form-row">
                            <label>Contact Number</label>
                            <!-- FIXED name: remove extra spaces -->
                            <input type="text" name="instructor_contact_number" required>
                        </div>

                        <div class="form-row">
                            <label>University Name</label>
                            <input type="text" name="instructor_university_name" required>
                        </div>

                        <div class="form-row inline">
                            <div>
                                <label>Department</label>
                                <input type="text" name="instructor_department" required>
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
                                    <button
                                        type="button"
                                        class="toggle-password"
                                        data-target="instructor_password"
                                        aria-label="Show or hide password"
                                    >
                                        <img src="assets/wired-lineal-69-eye.svg" alt="Toggle password visibility">
                                    </button>
                                </div>
                                <small class="password-hint">
                                    Use at least <strong>8 characters</strong>, including a <strong>number</strong> and a <strong>symbol</strong>.
                                </small>
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
                                    <button
                                        type="button"
                                        class="toggle-password"
                                        data-target="instructor_confirm_password"
                                        aria-label="Show or hide password"
                                    >
                                        <img src="assets/wired-lineal-69-eye.svg" alt="Toggle password visibility">
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row checkbox-row">
                            <label>
                                <input type="checkbox" required>
                                I confirm that the information provided is accurate.
                            </label>
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
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                if (!input) return;
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                this.classList.toggle('is-visible', !isPassword);
            });
        });
    </script>

</body>
</html>
