<?php


class StudentModel
{
    private $db;


    function __construct()
    {
        require_once dirname(__DIR__) . '/../Admin/Model/Database.php';
        $this->db = new DatabaseConnection();
    }


    function getStudentByEmail($email)
    {
        $conn = $this->db->openConnection();

        $sql = "SELECT id, full_name, email, contact_number, university_name, department, year 
                FROM users 
                WHERE email = ? AND role = 'student' 
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $student = null;

        if ($result && $result->num_rows > 0) {
            $student = $result->fetch_assoc();
        }

        $stmt->close();
        $this->db->closeConnection($conn);

        return $student; 
    }

  
    function getStudentCourses()
    {
        $conn = $this->db->openConnection();

        $sql = "SELECT DISTINCT course_slug, course_title 
                FROM course_files 
                WHERE course_slug != '' 
                ORDER BY course_title";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }

        $stmt->close();
        $this->db->closeConnection($conn);

        return $courses; // returns array of courses
    }

    // -------------------------
    // GET COURSE FILES COUNT
    // -------------------------
    function getCourseFilesCount()
    {
        $conn = $this->db->openConnection();

        $sql = "SELECT course_title, COUNT(*) as count 
                FROM course_files 
                WHERE file_path IS NOT NULL AND file_path != '' 
                GROUP BY course_title";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $counts = [];
        while ($row = $result->fetch_assoc()) {
            $counts[$row['course_title']] = $row['count'];
        }

        $stmt->close();
        $this->db->closeConnection($conn);

        return $counts; // returns associative array [course_title => count]
    }

    // -------------------------
    // GET COURSE FILES BY TITLE
    // -------------------------
    function getCourseFiles($course_title)
    {
        $conn = $this->db->openConnection();

        $sql = "SELECT file_type, original_name, file_path, uploaded_at 
                FROM course_files 
                WHERE course_title = ? 
                AND file_path IS NOT NULL 
                AND file_path != '' 
                ORDER BY uploaded_at DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $course_title);
        $stmt->execute();

        $result = $stmt->get_result();

        $files = [];
        while ($row = $result->fetch_assoc()) {
            $files[] = $row;
        }

        $stmt->close();
        $this->db->closeConnection($conn);

        return $files; // returns array of files
    }

    // -------------------------
    // SEARCH COURSES
    // -------------------------
    function searchCourses($search_query)
    {
        $conn = $this->db->openConnection();

        $search_term = "%" . $search_query . "%";
        $sql = "SELECT DISTINCT course_slug, course_title 
                FROM course_files 
                WHERE course_title LIKE ? OR course_slug LIKE ? 
                ORDER BY course_title";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $search_term, $search_term);
        $stmt->execute();

        $result = $stmt->get_result();

        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }

        $stmt->close();
        $this->db->closeConnection($conn);

        return $courses; // returns array of matching courses
    }
}
?>