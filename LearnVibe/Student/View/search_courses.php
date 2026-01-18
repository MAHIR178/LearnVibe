<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../Instructor/View/instructor_login.php");
    exit;
}

// Define all courses
$all_courses = [
    ['slug' => 'differential-calculus', 'title' => 'Differential Calculus & Co-ordinate Geometry'],
    ['slug' => 'physics-1', 'title' => 'Physics 1'],
    ['slug' => 'physics-1-lab', 'title' => 'Physics 1 Lab'],
    ['slug' => 'english-reading', 'title' => 'English Reading Skills & Public Speaking'],
    ['slug' => 'intro-computer-studies', 'title' => 'Introduction to Computer Studies'],
    ['slug' => 'intro-programming', 'title' => 'Introduction to Programming'],
    ['slug' => 'intro-programming-lab', 'title' => 'Introduction to Programming Lab'],
    ['slug' => 'discrete-mathematics', 'title' => 'Discrete Mathematics'],
    ['slug' => 'integral-calculus', 'title' => 'Integral Calculus & Ordinary Differential Equations'],
    ['slug' => 'object-oriented-programming-1', 'title' => 'Object Oriented Programming 1'],
    ['slug' => 'physics-2', 'title' => 'Physics 2'],
    ['slug' => 'physics-2-lab', 'title' => 'Physics 2 Lab'],
    ['slug' => 'english-writing', 'title' => 'English Writing Skills & Communications'],
    ['slug' => 'electrical-circuits', 'title' => 'Introduction to Electrical Circuits'],
    ['slug' => 'electrical-circuits-lab', 'title' => 'Introduction to Electrical Circuits Lab'],
    ['slug' => 'chemistry', 'title' => 'Chemistry'],
    ['slug' => 'complex-variable', 'title' => 'Complex Variable, Laplace & Z-Transformation'],
    ['slug' => 'introduction-database', 'title' => 'Introduction to Database'],
    ['slug' => 'electronic-devices-lab', 'title' => 'Electronic Devices Lab'],
    ['slug' => 'principles-accounting', 'title' => 'Principles of Accounting'],
    ['slug' => 'electronic-devices', 'title' => 'Electronic Devices'],
    ['slug' => 'data-structures', 'title' => 'Data Structure'],
    ['slug' => 'data-structures-lab', 'title' => 'Data Structure Lab'],
    ['slug' => 'computer-aided-design', 'title' => 'Computer Aided Design & Drafting'],
    ['slug' => 'algorithms', 'title' => 'Algorithms'],
    ['slug' => 'matrices-vectors', 'title' => 'Matrices, Vectors, Fourier Analysis'],
    ['slug' => 'object-oriented-programming-2', 'title' => 'Object Oriented Programming 2'],
    ['slug' => 'object-oriented-analysis', 'title' => 'Object Oriented Analysis and Design'],
    ['slug' => 'bangladesh-studies', 'title' => 'Bangladesh Studies'],
    ['slug' => 'digital-logic', 'title' => 'Digital Logic and Circuits'],
    ['slug' => 'digital-logic-lab', 'title' => 'Digital Logic and Circuits Lab'],
    ['slug' => 'computational-statistics', 'title' => 'Computational Statistics and Probability'],
    ['slug' => 'theory-computation', 'title' => 'Theory of Computation'],
    ['slug' => 'principles-economics', 'title' => 'Principles of Economics'],
    ['slug' => 'business-communication', 'title' => 'Business Communication'],
    ['slug' => 'numerical-methods', 'title' => 'Numerical Methods for Science and Engineering'],
    ['slug' => 'data-communication', 'title' => 'Data Communication'],
    ['slug' => 'microprocessor', 'title' => 'Microprocessor and Embedded Systems'],
    ['slug' => 'software-engineering', 'title' => 'Software Engineering'],
    ['slug' => 'artificial-intelligence', 'title' => 'Artificial Intelligence and Expert System'],
    ['slug' => 'computer-networks', 'title' => 'Computer Networks'],
    ['slug' => 'computer-organization', 'title' => 'Computer Organization and Architecture'],
    ['slug' => 'operating-system', 'title' => 'Operating System'],
    ['slug' => 'web-technologies', 'title' => 'Web Technologies'],
    ['slug' => 'engineering-ethics', 'title' => 'Engineering Ethics'],
    ['slug' => 'compiler-design', 'title' => 'Compiler Design'],
];

// Handle search request
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
                    'title' => $title
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
            <!-- Simple instructions -->
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