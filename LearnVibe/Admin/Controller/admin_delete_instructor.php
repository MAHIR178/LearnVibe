<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['admin_error'] = 'Admin not logged in.';
    header('Location: ../View/admin_login.php');
    exit;
}

require_once '../Model/Database.php';

// Accept id via GET or POST
$id = null;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
}

if (!$id) {
    $_SESSION['admin_error'] = 'No instructor id provided.';
    header('Location: ../View/admin_view_instructors.php');
    exit;
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

if (!$connection) {
    $_SESSION['admin_error'] = 'Database connection failed.';
    header('Location: ../View/admin_view_instructors.php');
    exit;
}

$deleted = $db->deleteInstructor($connection, $id);
if ($deleted) {
    $_SESSION['admin_message'] = 'Instructor deleted successfully.';
} else {
    $dbErr = $connection->error;
    if (!empty($dbErr)) {
        $_SESSION['admin_error'] = 'Delete failed: ' . $dbErr;
    } else {
        $_SESSION['admin_error'] = 'Delete failed or instructor not found.';
    }
}

$db->closeConnection($connection);
header('Location: ../View/admin_view_instructors.php');
exit;
