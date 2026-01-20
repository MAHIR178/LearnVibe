<?php
session_start();
require_once "../Model/Database.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../View/admin_login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

$res = $db->getAllFeedbackById($conn);

$db->closeConnection($conn);

/* LOAD VIEW */
include "../View/view_feedback.php";
