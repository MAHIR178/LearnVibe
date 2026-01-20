<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_unset();
session_destroy();
setcookie("i_name", "", time() - 3600, "/");


header("Location:../../Index.php");
exit;


?>