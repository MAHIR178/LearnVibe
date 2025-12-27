<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_unset();
session_destroy();

// Optional: extra safety — generate a new session ID
// session_regenerate_id(true);

// Redirect back to login page
header("Location: Login.php");
exit;
?>