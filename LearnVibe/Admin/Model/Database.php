<?php

class DatabaseConnection{

    function openConnection(){
        $db_host="localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "learnVibe";

        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);
        if($connection->connect_error){
            die("Failed to connect database ". $connection->connect_error);
        }

        // good for proper text support
        $connection->set_charset("utf8mb4");

        return $connection;
    }
     // Check if email already exists
function isEmailExist($connection, $email){
        $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $exists = ($res && $res->num_rows > 0);

        $stmt->close();
        return $exists; // true/false
    }
    // Insert Student user
function createStudent($connection, $full_name, $email, $contact, $university, $department, $year, $password){
        $role = "student";

        $sql = "INSERT INTO users
                (role, full_name, email, contact_number, university_name, department, year, expertise, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, NULL, ?)";

        $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("ssssssss",
            $role, $full_name, $email, $contact, $university, $department, $year, $password
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Insert Instructor user
function createInstructor($connection, $full_name, $email, $contact, $university, $department, $expertise, $password){
        $role = "instructor";

        $sql = "INSERT INTO users
                (role, full_name, email, contact_number, university_name, department, year, expertise, password)
                VALUES (?, ?, ?, ?, ?, ?, NULL, ?, ?)";

        $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("ssssssss",
            $role, $full_name, $email, $contact, $university, $department, $expertise, $password
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // -------------------------
// LOGIN SYSTEM (DB)
// -------------------------
function loginUser($connection, $email, $password){
    $sql = "SELECT id, role, full_name, email
            FROM users
            WHERE email = ? AND password = ?
            LIMIT 1";

    $stmt = $connection->prepare($sql);
    if(!$stmt){
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    $res = $stmt->get_result();
    $user = null;

    if ($res && $res->num_rows === 1) {
        $user = $res->fetch_assoc(); // return user row
    }

    $stmt->close();
    return $user; // assoc array or null
}
function adminLogin($username, $password)
{
    $conn = $this->openConnection();

    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    $stmt->close();
    $this->closeConnection($conn);

    return $admin; // returns admin data if found, false otherwise
}


// -------------------------
// PROFILE VIEW (DB)
// -------------------------
function getUserProfileByEmail($connection, $email){
    $sql = "SELECT role, full_name, email, contact_number, university_name, department, year, expertise
            FROM users
            WHERE email = ?
            LIMIT 1";

    $stmt = $connection->prepare($sql);
    if(!$stmt){
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $res = $stmt->get_result();
    $user = null;

    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
    }

    $stmt->close();
    return $user; // assoc array or null
}

// -------------------------
// PROFILE EDIT (DB)
// -------------------------

// Get full user row by email (for edit form)
function getUserByEmail($connection, $email){
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $connection->prepare($sql);
    if(!$stmt){
        die("Prepare failed: " . $connection->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    $user = null;
    if($res && $res->num_rows > 0){
        $user = $res->fetch_assoc();
    }
    $stmt->close();
    return $user; // assoc array or null
}

// Update profile WITHOUT password
function updateUserProfile($connection, $email, $full_name, $contact_number, $university_name, $department, $year, $expertise){
    $sql = "UPDATE users SET
                full_name = ?,
                contact_number = ?,
                university_name = ?,
                department = ?,
                year = ?,
                expertise = ?
            WHERE email = ?";

    $stmt = $connection->prepare($sql);
    if(!$stmt){
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("sssssss", $full_name, $contact_number, $university_name, $department, $year, $expertise, $email);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

// Update profile WITH password (plain text, same as your system)
function updateUserProfileWithPassword($connection, $email, $full_name, $contact_number, $university_name, $department, $year, $expertise, $password){
    $sql = "UPDATE users SET
                full_name = ?,
                contact_number = ?,
                university_name = ?,
                department = ?,
                year = ?,
                expertise = ?,
                password = ?
            WHERE email = ?";

    $stmt = $connection->prepare($sql);
    if(!$stmt){
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("ssssssss", $full_name, $contact_number, $university_name, $department, $year, $expertise, $password, $email);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
    // Get all students
function getAllStudents($connection){

        $sql = "SELECT id, full_name, email, contact_number,
                       university_name, department, year,
                       created_at
                FROM users
                WHERE role='student'
                ORDER BY created_at DESC";

        $result = $connection->query($sql);

        return $result;   // return mysqli_result or false on error
    }

    // Get all instructors
function getAllInstructors($connection){

        $sql = "SELECT id, full_name, email, contact_number,
                       university_name, department, year, expertise,
                       created_at
                FROM users
                WHERE role='instructor'
                ORDER BY created_at DESC";

        $result = $connection->query($sql);
        return $result;
    }
    // Delete student by Admin
function deleteStudent($connection, $id)
      {
        $sql = "DELETE FROM users WHERE id = ? AND role = 'student'";
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
}
    // Delete instructor by Admin
function deleteInstructor($connection, $id)
    {
        $sql = "DELETE FROM users WHERE id = ? AND role = 'instructor'";
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }


   
function addCourseFile($connection, $course_title, $file_type, $original_name, $file_path, $uploaded_by){
    $sql = "INSERT INTO course_files (course_slug, course_title, file_type, original_name, file_path, uploaded_by)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($sql);
    if(!$stmt){ die("Prepare failed: " . $connection->error); }

    $stmt->bind_param("sssssi", $course_title, $course_title, $file_type, $original_name, $file_path, $uploaded_by);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}


function getInstructorFiles($connection, $uploaded_by){
    $sql = "SELECT id, course_title, file_type, original_name, file_path, uploaded_at
            FROM course_files
            WHERE uploaded_by = ?
            ORDER BY id DESC";

    $stmt = $connection->prepare($sql);
    if(!$stmt){ die("Prepare failed: " . $connection->error); }

    $stmt->bind_param("i", $uploaded_by);
    $stmt->execute();
    return $stmt->get_result();
}


function getAllCourseFiles($connection){
        $sql = "SELECT course_title, file_type, original_name, file_path, uploaded_at
                FROM course_files
                ORDER BY id DESC";
        return $connection->query($sql);
    }

function getCourseFilesByTitle($connection, $course_title){
        $sql = "SELECT id, file_type, original_name, file_path, uploaded_at
                FROM course_files
                WHERE course_title = ?
                ORDER BY id DESC";
       $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("s", $course_title);
        $stmt->execute();

        $result = $stmt->get_result();
        
        return $result;
    }
function getStudentById($connection, $student_id)
   {
    $sql = "SELECT * FROM users WHERE id = ? AND role = 'student'";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    $stmt->close();
    return $student;
}


    
function updateStudentById($connection, $student_id, $full_name, $contact_number, $university_name, $department, $year){
        $sql = "UPDATE users SET
                    full_name = ?,
                    contact_number = ?,
                    university_name = ?,
                    department = ?,
                    year = ?
                WHERE id = ? AND role = 'student'";

        $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("sssssi", $full_name, $contact_number, $university_name, $department, $year, $student_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

   
function getInstructorById($connection, $instructor_id){
        $sql = "SELECT id, full_name, email, contact_number,
                       university_name, department, expertise,
                       created_at, role
                FROM users
                WHERE id = ? AND role = 'instructor'
                LIMIT 1";

        $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("i", $instructor_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $instructor = null;

        if($result && $result->num_rows > 0){
            $instructor = $result->fetch_assoc();
        }

        $stmt->close();
        return $instructor;
    }

function updateInstructorById($connection, $instructor_id, $full_name, $contact_number, $university_name, $department, $expertise){
        $sql = "UPDATE users SET
                    full_name = ?,
                    contact_number = ?,
                    university_name = ?,
                    department = ?,
                    expertise = ?
                WHERE id = ? AND role = 'instructor'";

        $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("sssssi", $full_name, $contact_number, $university_name, $department, $expertise, $instructor_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    function closeConnection($connection){
        $connection->close();
    }

 function getAllFeedbackForInstructor($con)
{
    
    $sql1 = "
        SELECT
            c.course_title,
            u.full_name,
            u.email,
            f.rating,
            f.comment,
            f.created_at
        FROM feedback f
        LEFT JOIN users u ON u.id = f.user_id
        LEFT JOIN (
            SELECT course_slug, MAX(course_title) AS course_title
            FROM course_files
            GROUP BY course_slug
        ) c ON c.course_slug = f.course_slug
        ORDER BY f.created_at DESC
    ";

    $res = mysqli_query($con, $sql1);

    
    if (!$res) {
        $sql2 = "
            SELECT
                c.course_title,
                u.full_name,
                u.email,
                f.rating,
                f.comment,
                '-' AS created_at
            FROM feedback f
            LEFT JOIN users u ON u.id = f.user_id
            LEFT JOIN (
                SELECT course_slug, MAX(course_title) AS course_title
                FROM course_files
                GROUP BY course_slug
            ) c ON c.course_slug = f.course_slug
            ORDER BY f.id DESC
        ";
        $res = mysqli_query($con, $sql2);
    }

    return $res;
}
}
?>