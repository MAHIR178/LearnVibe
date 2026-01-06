
<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
   
</head>
<body>

    <!-- removed blur-layer, content starts directly -->
    <div class="auth-wrapper">
        <div class="signup-card">

           <?php if (!empty($_GET["error"])): ?>
    <div class="error-box">
        <p><?php echo htmlspecialchars($_GET["error"]); ?></p>
    </div>
<?php elseif (!empty($_GET["success"])): ?>
    <div class="success-box">
        <p><?php echo htmlspecialchars($_GET["success"]); ?></p>
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
                <form class="form student-form" action="../Controller/Process.php" method="post">
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


                <form class="form instructor-form" action="../Controller/Process.php" method="post">
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
                <a href="instructor_login.php">Log in</a>
            </p>
        </div>
    </div>

   
    <script src="../Controller/Validation.js"></script>

</body>
</html>
