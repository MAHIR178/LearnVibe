<?php
class FeedbackModel
{
    private $db;

    // Constructor: Initialize database connection
    function __construct()
    {
        require_once dirname(__DIR__) . '/../Admin/Model/Database.php';
        $this->db = new DatabaseConnection();
    }

    // -------------------------
    // CHECK IF FEEDBACK EXISTS
    // -------------------------
    function checkFeedbackExists($user_id, $course_slug)
    {
        $conn = $this->db->openConnection();

        $sql = "SELECT id 
                FROM feedback 
                WHERE user_id = ? AND course_slug = ? 
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("is", $user_id, $course_slug);
        $stmt->execute();

        $result = $stmt->get_result();

        $exists = false;
        $feedback_id = null;

        if ($result && $result->num_rows > 0) {
            $exists = true;
            $row = $result->fetch_assoc();
            $feedback_id = $row['id'];
        }

        $stmt->close();
        $this->db->closeConnection($conn);

        return [
            'exists' => $exists,
            'id' => $feedback_id
        ];
    }

    // -------------------------
    // SUBMIT NEW FEEDBACK
    // -------------------------
    function submitFeedback($user_id, $course_slug, $rating, $comment)
    {
        $conn = $this->db->openConnection();

        $sql = "INSERT INTO feedback (user_id, course_slug, rating, comment) 
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("isis", $user_id, $course_slug, $rating, $comment);
        $success = $stmt->execute();

        $stmt->close();
        $this->db->closeConnection($conn);

        return $success; // returns true on success, false on failure
    }

    // -------------------------
    // UPDATE EXISTING FEEDBACK
    // -------------------------
    function updateFeedback($feedback_id, $rating, $comment)
    {
        $conn = $this->db->openConnection();

        $sql = "UPDATE feedback 
                SET rating = ?, comment = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("isi", $rating, $comment, $feedback_id);
        $success = $stmt->execute();

        $stmt->close();
        $this->db->closeConnection($conn);

        return $success; // returns true on success, false on failure
    }

    // -------------------------
    // GET FEEDBACK BY USER
    // -------------------------
    function getFeedbackByUser($user_id)
    {
        $conn = $this->db->openConnection();

        $sql = "SELECT f.id, f.course_slug, f.rating, f.comment, f.created_at, 
                       c.course_title
                FROM feedback f
                LEFT JOIN course_files c ON c.course_slug = f.course_slug
                WHERE f.user_id = ?
                ORDER BY f.created_at DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        $feedback = [];
        while ($row = $result->fetch_assoc()) {
            $feedback[] = $row;
        }

        $stmt->close();
        $this->db->closeConnection($conn);

        return $feedback; // returns array of user's feedback
    }
}
?>