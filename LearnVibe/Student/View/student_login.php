<?php
session_start();
include '../../Admin/Model/Database.php';

$loginErr = "";
$email = "";

if (!empty($_SESSION["isLoggedIn"])) {
    $role = $_SESSION["role"] ?? null;

    if ($role === "student") {
        header("Location: ../../Student/View/s_dashboard.php");
    } elseif ($role === "instructor") {
        header("Location: i_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $loginErr = "Please enter both email and password.";
    } else {
        $db   = new DatabaseConnection();
        $conn = $db->openConnection();

        // ✅ no SQL here
        $user = $db->loginUser($conn, $email, $password);

        if ($user) {
            $_SESSION["isLoggedIn"] = true;
            $_SESSION["user_id"]    = $user["id"];
            $_SESSION["role"]       = $user["role"] ?? null;
            $_SESSION["full_name"]  = $user["full_name"] ?? null;
            $_SESSION["email"]      = $user["email"];
            $_SESSION["user_email"] = $user["email"];

            if ($_SESSION["role"] === "student") {
                header("Location: ../../Student/View/s_dashboard.php");
            } elseif ($_SESSION["role"] === "instructor") {
                header("Location: i_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $loginErr = "Invalid email or password.";
        }

        $db->closeConnection($conn);
    }
}
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

    
    <div class="auth-wrapper">
        <div class="signin-card">
            <div class="signin-layout">

                <div class="signin-content">
                    <form class="form signin-form" action="" method="post">
                        <h2>Welcome Back</h2>

                        <?php if ($loginErr): ?>
                            <p class="error-msg">
                                <?php echo htmlspecialchars($loginErr); ?>
                            </p>
                        <?php endif; ?>

                        <div class="form-row">
                            <label for="signin_email">Email</label>
                            <input
                                id="signin_email"
                                type="email"
                                name="email"
                                required
                                value="<?php echo htmlspecialchars($email); ?>"
                            >
                        </div>

                        <div class="form-row input-with-icon">
                            <label for="signin_password">Password</label>
                            <div class="field-wrapper">
                                <span class="field-icon-left">🔒</span>
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
                            <label>
                                <input type="checkbox" name="remember">
                                Remember me
                            </label>
                            <a href="#" class="link-small">Forgot password?</a>
                        </div>

                        <button type="submit" class="primary-btn">Sign In</button>

                        <p class="login-text">
                            Don't have an account?
                            <a href="Signup.php">Create one</a>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
