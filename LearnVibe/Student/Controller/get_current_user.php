<?php
session_start();
header('Content-Type: application/json');

include '../../Admin/Model/Database.php';

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$user_email = $_SESSION['email'];

$db = new DatabaseConnection();
$conn = $db->openConnection();

$user = $db->getUserByEmail($conn, $user_email);
$db->closeConnection($conn);

if ($user) {
    if (isset($user['password'])) unset($user['password']);
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    echo json_encode(['success' => false, 'error' => 'User not found']);
}
