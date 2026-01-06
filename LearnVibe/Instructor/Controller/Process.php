<?php
require_once '../../Admin/Model/Database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../../Student/View/student_signup.php");
    exit;
}

function clean($v){ return trim($v ?? ""); }

$errors  = [];
$success = "";

// detect which form submitted (student or instructor)
$isStudent    = isset($_POST["student_email"]);
$isInstructor = isset($_POST["instructor_email"]);

// ✅ redirect pages (CHANGE PATHS if your files are in different folders)
$studentRedirect    = "../../Student/View/student_signup.php";
$instructorRedirect = "../View/instructor_signup.php";

$redirectPage = $isStudent ? $studentRedirect : $instructorRedirect;

$db   = new DatabaseConnection();
$conn = $db->openConnection();

/* ---------- STUDENT ---------- */
if ($isStudent) {

    $full_name  = clean($_POST["student_name"]);
    $email      = clean($_POST["student_email"]);
    $contact    = clean($_POST["student_contact_number"]);
    $university = clean($_POST["student_university_name"]);
    $department = clean($_POST["student_department"]);
    $year       = clean($_POST["student_year"]);
    $password   = clean($_POST["student_password"]);
    $confirm    = clean($_POST["student_confirm_password"]);

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // ✅ FIXED function name: isEmailExists
    if (empty($errors) && $db->isEmailExist($conn, $email)) {
        $errors[] = "An account already exists with this email.";
    }

    if (empty($errors)) {
        $ok = $db->createStudent($conn, $full_name, $email, $contact, $university, $department, $year, $password);
        if ($ok) $success = "Student account created successfully. You can now log in.";
        else $errors[] = "Account creation failed.";
    }

/* ---------- INSTRUCTOR ---------- */
} elseif ($isInstructor) {

    $full_name  = clean($_POST["instructor_name"]);
    $email      = clean($_POST["instructor_email"]);
    $contact    = clean($_POST["instructor_contact_number"]);
    $university = clean($_POST["instructor_university_name"]);
    $department = clean($_POST["instructor_department"]);
    $expertise  = clean($_POST["expertise"]);
    $password   = clean($_POST["instructor_password"]);
    $confirm    = clean($_POST["instructor_confirm_password"]);

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // ✅ FIXED function name: isEmailExists
    if (empty($errors) && $db->isEmailExist($conn, $email)) {
        $errors[] = "An account already exists with this email.";
    }

    if (empty($errors)) {
        $ok = $db->createInstructor($conn, $full_name, $email, $contact, $university, $department, $expertise, $password);
        if ($ok) $success = "Instructor account created successfully. You can now log in.";
        else $errors[] = "Account creation failed.";
    }

} else {
    $errors[] = "Invalid form submission.";
}

$db->closeConnection($conn);

// redirect back with message
if (!empty($errors)) {
    header("Location: " . $redirectPage . "?error=" . urlencode($errors[0]));
    exit;
}

header("Location: " . $redirectPage . "?success=" . urlencode($success));
exit;
