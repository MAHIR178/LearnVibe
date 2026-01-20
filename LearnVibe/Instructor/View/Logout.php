<?php
session_start();


$_SESSION = [];


session_unset();
session_destroy();
setcookie("i_name", "", time() - 3600, "/");


header("Location:../../Index.php");
exit;


?>