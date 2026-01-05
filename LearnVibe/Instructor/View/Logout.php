<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_unset();
session_destroy();

header("Location:../../Index.php");
exit;

?>