<?php
session_start();

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/instructor_login.php");
    exit;
}
$courseData = include '../Config/courses.php';
$all_courses = $courseData['courses'];
$course_descriptions = $courseData['descriptions'];
$course_images = $courseData['images'];

$conn = new mysqli("localhost", "root", "", "learnvibe");

$courseFiles = [];
$courseCounts = [];

if (!$conn->connect_error) {
    $sql = "SELECT course_title, file_path, file_type, uploaded_at
            FROM course_files
            WHERE file_path IS NOT NULL AND file_path != ''
            ORDER BY uploaded_at DESC";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = $row['course_title'] ?? '';
            
            if ($title === '' || empty($row['file_path'])) {
                continue;
            }

            if (!isset($courseCounts[$title])) {
                $courseCounts[$title] = 0;
            }
            $courseCounts[$title]++;

            if (!isset($courseFiles[$title])) {
                $courseFiles[$title] = [];
            }
            $courseFiles[$title][] = $row; 
        }
        $result->free();
    }
}

foreach ($all_courses as $course) {
    $courseTitle = $course['title'];
    if (!isset($courseCounts[$courseTitle])) {
        $courseCounts[$courseTitle] = 0;
    }
}

if (isset($_GET['search_query'])) {
    $q = strtolower(trim($_GET['search_query']));
    $results = [];

    if ($q !== "") {
        foreach ($all_courses as $course) {
            $title = strtolower($course['title']);
            $slug = strtolower($course['slug']);

            if (strpos($title, $q) !== false || strpos($slug, $q) !== false) {
                $desc = $course_descriptions[$course['title']] ?? "Course materials";
                $results[] = [
                    "slug" => $course['slug'],
                    "title" => $course['title'],
                    "description" => $desc,
                    "files" => (int) ($courseCounts[$course['title']] ?? 0)
                ];
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array_slice($results, 0, 10));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Course Dashboard | LearnVibe</title>
    <link rel="stylesheet" href="s_dashboard.css">
    <link rel="stylesheet" href="search_courses.css">
</head>

<body>

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Search courses...">
            <div class="search-results" id="searchResults"></div>
        </div>

        <div class="top-links">
            <a href="feedback.php" class="feedback-btn">Feedback</a>

            <div class="profile">
                <a href="#" id="profile-btn">My Profile</a>
                <div class="profile-menu" id="profile-menu">
                    <a href="profile_view.php">View</a>
                    <a href="profile_edit.php">Edit</a>
                </div>
            </div>

            <a href="../../Instructor/View/Logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container">
        <div class="grid">

            <?php foreach ($all_courses as $course):
                $courseTitle = $course['title'];
                $link = "../../Instructor/View/course_files.php?course=" . urlencode($course['slug']);
                $files = $courseFiles[$courseTitle] ?? [];
                $count = (int) ($courseCounts[$courseTitle] ?? 0);
                $image_url = $course_images[$courseTitle]
                    ?>
                <div class="course">
                    <a href="<?= $link ?>">
                        <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($courseTitle) ?>">
                    </a>

                    <a href="<?= $link ?>" class="course-title">
                        <?= htmlspecialchars($courseTitle) ?>
                    </a>

                    <p>
                        <?= htmlspecialchars($course_descriptions[$courseTitle] ?? 'Explore this course to enhance your knowledge.') ?>
                    </p>

                    <div class="file-count">Total files uploaded: <?= $count ?></div>

                    <div class="course-actions">
                        <a href="<?= $link ?>" class="btn">View Files</a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Contact: example@email.com | 0123456789</p>
        <p class="social">
            <a href="#">Facebook</a> |
            <a href="#">Twitter</a> |
            <a href="#">LinkedIn</a>
        </p>
    </div>

    <!-- Profile dropdown -->
    <script>
        const profileBtn = document.getElementById('profile-btn');
        const profileMenu = document.getElementById('profile-menu');

        profileBtn.addEventListener('click', function (e) {
            e.preventDefault();
            profileMenu.style.display = (profileMenu.style.display === 'block') ? 'none' : 'block';
        });

        document.addEventListener('click', function (e) {
            if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.style.display = 'none';
            }
        });
    </script>

    <!-- Search functionality -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            fetch(`s_dashboard.php?search_query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        searchResults.innerHTML = '<div class="no-results">No courses found</div>';
                        searchResults.style.display = 'block';
                        return;
                    }
                    
                    let html = '';
                    data.forEach(course => {
                        html += `
                            <div class="search-result-item" onclick="goToCourse('${course.slug}')">
                                <h4>${course.title}</h4>      
                            </div>
                        `;
                    });
                    
                    searchResults.innerHTML = html;
                    searchResults.style.display = 'block';
                });
        });
        
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                searchResults.style.display = 'none';
            }
        });
        
        function goToCourse(slug) {
            window.location.href = `../../Instructor/View/course_files.php?course=${slug}`;
        }
    </script>

</body>

</html>