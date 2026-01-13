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

$id = null;
if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
}

if (!$id) {
    $_SESSION['admin_error'] = 'No student id provided.';
    header('Location: ../View/admin_view_students.php');
    exit;
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

if (!$connection) {
    $_SESSION['admin_error'] = 'Database connection failed.';
    header('Location: ../View/admin_view_students.php');
    exit;
}

$deleted = $db->deleteStudent($connection, $id);
if ($deleted) {
    $_SESSION['admin_message'] = 'Student deleted successfully.';
} else {
    $dbErr = $connection->error;
    if (!empty($dbErr)) {
        $_SESSION['admin_error'] = 'Delete failed: ' . $dbErr;
    } else {
        $_SESSION['admin_error'] = 'Delete failed or student not found.';
    }
}

$db->closeConnection($connection);
header('Location: ../View/admin_view_students.php');
exit;
?>