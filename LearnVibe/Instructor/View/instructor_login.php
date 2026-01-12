<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnVibe | Sign In</title>
    <link rel="stylesheet" href="login.css">
    
</head>
<body>

        <div class="signin-card">
            <div class="signin-layout">

                <div class="signin-content">
                    <form class="form signin-form" action="../Controller/instructor_login_validation.php" method="post" novalidate>
                        <h2>Welcome Back</h2>

                        <?php if (!empty($_GET["error"])): ?>
    <p class="error-msg"><?php echo htmlspecialchars($_GET["error"]); ?></p>
               <?php endif; ?>
                  

                        <div class="form-row">
                            <label for="signin_email">Email</label>
                            <input
                                id="signin_email"
                                type="email"
                                name="email"
                                required
                                value="<?php echo htmlspecialchars($_GET["email"] ?? ""); ?>"

                            >
                        </div>

                        <div class="form-row input-with-icon">
                            <label for="signin_password">Password</label>
                            <div class="field-wrapper">
                               
                                <input
                                    type="password"
                                    id="signin_password"
                                    name="password"
                                    minlength="8"
                                    required
                                >
                            </div>
                            
                        </div>

                        <div class="form-row checkbox-row space-between">
                           
                            <a href="#" class="link-small">Forgot password?</a>
                        </div>

                        <button type="submit" class="primary-btn">Sign In</button>

                          <p class="login-text">
                            Login as Student
                        <a href="../../Student/View/student_login.php">Go Student</a>
                        </p>

                        <p class="login-text">
                            Don't have an account?
                            <a href="instructor_signup.php">Create one</a>
                        </p>
                    </form>
                </div>

            </div>
        </div>
   

</body>
</html>
