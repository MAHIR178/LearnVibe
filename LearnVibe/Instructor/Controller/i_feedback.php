<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: ../View/instructor_login.php");
    exit;
}

$db = new DatabaseConnection();
$con = $db->openConnection();


$res = $db->getAllFeedbackForInstructor($con);


include("../View/feedback_view.php");

$db->closeConnection($con);
