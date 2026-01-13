<?php
session_start();

$admin_error = null;
if (isset($_SESSION['admin_error'])) {
    $admin_error = $_SESSION['admin_error'];
    unset($_SESSION['admin_error']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>

<div class="login-wrapper">
    <form method="post" class="login-card" action="../Controller/admin_login_validation.php" novalidate>
        <h2>Admin Login</h2>
        <?php if ($admin_error): ?>
            <div class="error"><?php echo htmlspecialchars($admin_error); ?></div>
        <?php endif; ?>

        <label for="username">Username</label>
        <input id="username" name="username" type="text" required autofocus>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button type="submit" class="btn">Sign In</button>
    </form>
</div>

</body>
</html>