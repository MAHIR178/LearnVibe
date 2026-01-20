<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../Instructor/View/instructor_login.php");
    exit;
}

require_once __DIR__ . '/../Model/StudentModel.php';

$courseData = require __DIR__ . '/../Config/courses.php';
$all_courses = $courseData['courses'];

$studentModel = new StudentModel();
$courseCounts = $studentModel->getCourseFilesCount();

if (isset($_GET['search_query'])) {
    $search_query = trim($_GET['search_query']);
    $results = [];
    
    if (!empty($search_query)) {
        $search_lower = strtolower($search_query);
        
        foreach ($all_courses as $course) {
            $title = $course['title'];
            $title_lower = strtolower($title);
            
            if (strpos($title_lower, $search_lower) !== false) {
                $results[] = [
                    'slug' => $course['slug'],
                    'title' => $title,
                    'files' => (int) ($courseCounts[$title] ?? 0)
                ];
            }
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Courses</title>
    <link rel="stylesheet" href="search_courses.css">
</head>
<body class="search-page">
    <div class="header">
        <h1>Search Courses</h1>
        <p>Type course name to search</p>
    </div>
    
    <div class="container">
        <div class="search-section">
            
            <div class="search-info">
                <p>Type any course name in the search box below</p>
            </div>
            
            <div class="center-search">
                <div class="search-container">
                    <input type="text" id="searchInput" class="search-input large" 
                           placeholder="Type course name...">
                    <div class="search-results" id="searchResults"></div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="s_dashboard.php" class="back-link">← Back to Dashboard</a>
            </div>
        </div>
    </div>
    
    <script>
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            fetch(`search_courses.php?search_query=${encodeURIComponent(query)}`)
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