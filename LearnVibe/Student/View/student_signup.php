



<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Sign Up</title>
  <link rel="stylesheet" href="signup.css">
</head>
<body>

<div class="signup-card">
  <h2>Student Sign Up</h2>

  <?php if (!empty($_POST["error"])): ?>
    <div class="error-box"><?php echo htmlspecialchars($_GET["error"]); ?></div>
  <?php elseif (!empty($_GET["success"])): ?>
    <div class="success-box"><?php echo htmlspecialchars($_GET["success"]); ?></div>
  <?php endif; ?>

  <form class="student-form" action="../../Instructor/Controller/Process.php" method="post" novalidate>

    <div class="form-row">
      <label>Full Name</label>
      <input type="text" name="student_name" id="student_name" >
      <span class="field-error" id="student_name_err"></span>
    </div>

    <div class="form-row">
      <label>Email</label>
      <input type="email" name="student_email" id="student_email" >
      <span class="field-error" id="student_email_err"></span>
    </div>

    <div class="form-row">
      <label>Contact Number</label>
      <input type="text" name="student_contact_number" id="student_contact_number" >
      <span class="field-error" id="student_contact_err"></span>
    </div>

    <div class="form-row">
      <label>University Name</label>
      <input type="text" name="student_university_name" id="student_university_name" >
      <span class="field-error" id="student_university_err"></span>
    </div>

    <div class="form-row">
      <label>Department</label>
      <input type="text" name="student_department" id="student_department" >
      <span class="field-error" id="student_department_err"></span>
    </div>

    <div class="form-row">
      <label>Year</label>
      <select name="student_year" id="student_year" >
        <option value="">Select year</option>
        <option>1st Year</option>
        <option>2nd Year</option>
        <option>3rd Year</option>
        <option>4th Year</option>
      </select>
      <span class="field-error" id="student_year_err"></span>
    </div>

    <div class="form-row">
  <label>Password</label>

  <div class="pass-box">
    <input type="password" name="student_password" id="student_password" minlength="8" required>
    <span class="eye" data-target="student_password">👁️</span>
  </div>

  <span class="field-error" id="student_password_err"></span>
</div>

<div class="form-row">
  <label>Confirm Password</label>

  <div class="pass-box">
    <input type="password" name="student_confirm_password" id="student_confirm_password" minlength="8" required>
    <span class="eye" data-target="student_confirm_password">👁️</span>
  </div>

  <span class="field-error" id="student_confirm_password_err"></span>
</div>

    

    <button class="primary-btn" type="submit">Sign Up</button>
  </form>

  <p class="login-text">
    Want Instructor Signup?
    <a href="../../Instructor/View/instructor_signup.php">Go Instructor</a>
  </p>

  <p class="login-text">
    Already have an account?
    <a href="../../Instructor/View/instructor_login.php">Log in</a>
  </p>
</div>

<script src="../Controller/JS/student_signup_validation.js"></script>
</body>
</html>
