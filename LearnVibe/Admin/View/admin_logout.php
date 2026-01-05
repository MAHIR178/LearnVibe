<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_unset();
session_destroy();

// Redirect to site index (Student index)
header('Location: ../../Index.php');
exit();

?>
