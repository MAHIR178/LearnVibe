<?php

class DatabaseConnection{

    function openConnection(){
        $db_host="localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "LearnVibe";

        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);
        if($connection->connect_error){
            die("Failed to connect database ". $connection->connect_error);
        }

        // good for proper text support
        $connection->set_charset("utf8mb4");

        return $connection;
    }

    // -------------------------
    // NEW: UPLOAD SYSTEM (DB)
    // -------------------------

    // Insert uploaded file metadata into DB
    // Insert uploaded file (simple: use course title as key)
   // Insert uploaded file (course title used as key)
    function addCourseFile($connection, $course_title, $file_type, $original_name, $file_path){
        $sql = "INSERT INTO course_files (course_slug, course_title, file_type, original_name, file_path)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        if(!$stmt){
            die("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("sssss", $course_title, $course_title, $file_type, $original_name, $file_path);
        return $stmt->execute();
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
        return $stmt->get_result();
    }

    function closeConnection($connection){
        $connection->close();
    }
}



?>
