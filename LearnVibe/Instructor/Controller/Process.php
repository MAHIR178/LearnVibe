<?php
require_once '../../Admin/Model/Database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/instructor_signup.php");
    exit;
}

$db   = new DatabaseConnection();
$conn = $db->openConnection();

$clean = function($v){ return trim($v ?? ""); };

$errors = [];
$success = "";

// ---------- STUDENT ----------
if (isset($_POST["student_email"])) {

    $full_name  = $clean($_POST["student_name"]);
    $email      = $clean($_POST["student_email"]);
    $contact    = $clean($_POST["student_contact_number"]);
    $university = $clean($_POST["student_university_name"]);
    $department = $clean($_POST["student_department"]);
    $year       = $clean($_POST["student_year"]);
    $password   = $clean($_POST["student_password"]);

    // DB duplicate check (you wrote isEmailExist, your real function is isEmailExists)
    if ($db->isEmailExist($conn, $email)) {
        $errors[] = "An account already exists with this email.";
    } else {
        $ok = $db->createStudent($conn, $full_name, $email, $contact, $university, $department, $year, $password);
        if ($ok) $success = "Student account created successfully. You can now log in.";
        else $errors[] = "Database error: " . $conn->error;
    }

// ---------- INSTRUCTOR ----------
} elseif (isset($_POST["instructor_email"])) {

    $full_name  = $clean($_POST["instructor_name"]);
    $email      = $clean($_POST["instructor_email"]);
    $contact    = $clean($_POST["instructor_contact_number"]);
    $university = $clean($_POST["instructor_university_name"]);
    $department = $clean($_POST["instructor_department"]);
    $expertise  = $clean($_POST["expertise"]);
    $password   = $clean($_POST["instructor_password"]);

    if ($db->isEmailExist($conn, $email)) {
        $errors[] = "An account already exists with this email.";
    } else {
        $ok = $db->createInstructor($conn, $full_name, $email, $contact, $university, $department, $expertise, $password);
        if ($ok) $success = "Instructor account created successfully. You can now log in.";
        else $errors[] = "Database error: " . $conn->error;
    }
}

$db->closeConnection($conn);

// Redirect back with message
if (!empty($errors)) {
    header("Location: ../View/instructor_signup.php?error=" . urlencode($errors[0]));
    exit;
} else {
    header("Location: ../View/instructor_signup.php?success=" . urlencode($success));
    exit;
}
