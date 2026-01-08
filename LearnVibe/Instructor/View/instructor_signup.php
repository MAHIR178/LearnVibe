
<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructor Sign Up</title>
  <link rel="stylesheet" href="signup.css">
</head>
<body>

<div class="signup-card">
  <h2>Instructor Sign Up</h2>

  <?php if (!empty($_GET["error"])): ?>
    <div class="error-box"><?php echo htmlspecialchars($_GET["error"]); ?></div>
  <?php elseif (!empty($_GET["success"])): ?>
    <div class="success-box"><?php echo htmlspecialchars($_GET["success"]); ?></div>
  <?php endif; ?>

  <form class="instructor-form" action="../Controller/Process.php" method="post" >

    <div class="form-row">
      <label>Full Name</label>
      <input type="text" name="instructor_name" id="instructor_name" >
      <span class="field-error" id="instructor_name_err"></span>
    </div>

    <div class="form-row">
      <label>Email</label>
      <input type="email" name="instructor_email" id="instructor_email" >
      <span class="field-error" id="instructor_email_err"></span>
    </div>

    <div class="form-row">
      <label>Contact Number</label>
      <input type="text" name="instructor_contact_number" id="instructor_contact_number" >
      <span class="field-error" id="instructor_contact_err"></span>
    </div>

    <div class="form-row">
      <label>University Name</label>
      <input type="text" name="instructor_university_name" id="instructor_university_name" >
      <span class="field-error" id="instructor_university_err"></span>
    </div>

    <div class="form-row">
      <label>Department</label>
      <input type="text" name="instructor_department" id="instructor_department" >
      <span class="field-error" id="instructor_department_err"></span>
    </div>

    <div class="form-row">
      <label>Area of Expertise</label>
      <input type="text" name="expertise" id="expertise" >
      <span class="field-error" id="instructor_expertise_err"></span>
    </div>

   <div class="form-row">
  <label>Password</label>

  <div class="pass-box">
    <input type="password" name="instructor_password" id="instructor_password" minlength="8" >
    <span class="eye" data-target="instructor_password">👁️</span>
  </div> 

  <span class="field-error" id="instructor_password_err"></span>
</div>

<div class="form-row">
  <label>Confirm Password</label>

  <div class="pass-box">
    <input type="password" name="instructor_confirm_password" id="instructor_confirm_password" minlength="8" >
    <span class="eye" data-target="instructor_confirm_password">👁️</span>
  </div>

  <span class="field-error" id="instructor_confirm_password_err"></span>
</div>


    <button class="primary-btn" type="submit">Sign Up</button>
  </form>

  <p class="login-text">
    Want Student Signup?
    <a href="../../Student/View/student_signup.php">Go Student</a>
  </p>

  <p class="login-text">
    Already have an account?
    <a href="instructor_login.php">Log in</a>
  </p>
</div>

<script src="../Controller/SignValidation.js"></script>
</body>
</html>
