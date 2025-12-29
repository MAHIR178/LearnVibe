<?php
session_start();


require_once __DIR__ . '/../../Admin/Model/Database.php';

$loginErr = "";
$email    = ""; 

if (!empty($_SESSION["isLoggedIn"])) {
    $role = $_SESSION["role"] ?? null;

    if ($role === "student") {
        header("Location: s_dashboard.php");
    } elseif ($role === "instructor") {
        header("Location: i_dashboard.php");
    } else {
        header("Location: dashboard.php"); 
    }
    exit;
}

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $loginErr = "Please enter both email and password.";
    } else {
        $db   = new DatabaseConnection();
        $conn = $db->openConnection();

        $emailEsc    = $conn->real_escape_string($email);
        $passwordEsc = $conn->real_escape_string($password);

        // Plain-text check to match how you stored passwords in signup
        $sql = "SELECT * FROM users 
                WHERE email = '$emailEsc' 
                  AND password = '$passwordEsc'
                LIMIT 1";

        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $_SESSION["isLoggedIn"] = true;
            $_SESSION["user_id"]    = $user["id"];
            $_SESSION["role"]       = $user["role"] ?? null;
            $_SESSION["full_name"]  = $user["full_name"] ?? null;
            $_SESSION["email"]      = $user["email"];

            if ($_SESSION["role"] === "student") {
                header("Location: s_dashboard.php");
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
    <style>
        .error-msg {
            color: #b30000;
            font-size: 13px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- no blur-layer, just a simple centered card -->
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
