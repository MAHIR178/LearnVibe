<?php
session_start();

// If using sessions, redirect to login if not logged in
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/instructor_login.php");
    exit;
}

// Get current user details
$current_user_email = $_SESSION['email'];
$current_user_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'User';
$current_user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'student';

// Try database connection (only if needed for other features)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=learnvibe;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $pdo = null;
}

// Define ALL courses array - Complete AIUB CSE Curriculum
$all_courses = [
    // Semester 1
    ['slug' => 'differential-calculus', 'title' => 'Differential Calculus & Co-ordinate Geometry'],
    ['slug' => 'physics-1', 'title' => 'Physics 1'],
    ['slug' => 'physics-1-lab', 'title' => 'Physics 1 Lab'],
    ['slug' => 'english-reading', 'title' => 'English Reading Skills & Public Speaking'],
    ['slug' => 'intro-computer-studies', 'title' => 'Introduction to Computer Studies'],
    ['slug' => 'intro-programming', 'title' => 'Introduction to Programming'],
    ['slug' => 'intro-programming-lab', 'title' => 'Introduction to Programming Lab'],
    
    // Semester 2
    ['slug' => 'discrete-mathematics', 'title' => 'Discrete Mathematics'],
    ['slug' => 'integral-calculus', 'title' => 'Integral Calculus & Ordinary Differential Equations'],
    ['slug' => 'object-oriented-programming-1', 'title' => 'Object Oriented Programming 1'],
    ['slug' => 'physics-2', 'title' => 'Physics 2'],
    ['slug' => 'physics-2-lab', 'title' => 'Physics 2 Lab'],
    ['slug' => 'english-writing', 'title' => 'English Writing Skills & Communications'],
    ['slug' => 'electrical-circuits', 'title' => 'Introduction to Electrical Circuits'],
    ['slug' => 'electrical-circuits-lab', 'title' => 'Introduction to Electrical Circuits Lab'],
    
    // Semester 3
    ['slug' => 'chemistry', 'title' => 'Chemistry'],
    ['slug' => 'complex-variable', 'title' => 'Complex Variable, Laplace & Z-Transformation'],
    ['slug' => 'introduction-database', 'title' => 'Introduction to Database'],
    ['slug' => 'electronic-devices-lab', 'title' => 'Electronic Devices Lab'],
    ['slug' => 'principles-accounting', 'title' => 'Principles of Accounting'],
    ['slug' => 'electronic-devices', 'title' => 'Electronic Devices'],
    ['slug' => 'data-structures', 'title' => 'Data Structure'],
    ['slug' => 'data-structures-lab', 'title' => 'Data Structure Lab'],
    ['slug' => 'computer-aided-design', 'title' => 'Computer Aided Design & Drafting'],
    
    // Semester 4
    ['slug' => 'algorithms', 'title' => 'Algorithms'],
    ['slug' => 'matrices-vectors', 'title' => 'Matrices, Vectors, Fourier Analysis'],
    ['slug' => 'object-oriented-programming-2', 'title' => 'Object Oriented Programming 2'],
    ['slug' => 'object-oriented-analysis', 'title' => 'Object Oriented Analysis and Design'],
    ['slug' => 'bangladesh-studies', 'title' => 'Bangladesh Studies'],
    ['slug' => 'digital-logic', 'title' => 'Digital Logic and Circuits'],
    ['slug' => 'digital-logic-lab', 'title' => 'Digital Logic and Circuits Lab'],
    ['slug' => 'computational-statistics', 'title' => 'Computational Statistics and Probability'],
    
    // Semester 5
    ['slug' => 'theory-computation', 'title' => 'Theory of Computation'],
    ['slug' => 'principles-economics', 'title' => 'Principles of Economics'],
    ['slug' => 'business-communication', 'title' => 'Business Communication'],
    ['slug' => 'numerical-methods', 'title' => 'Numerical Methods for Science and Engineering'],
    ['slug' => 'data-communication', 'title' => 'Data Communication'],
    ['slug' => 'microprocessor', 'title' => 'Microprocessor and Embedded Systems'],
    ['slug' => 'software-engineering', 'title' => 'Software Engineering'],
    
    // Semester 6
    ['slug' => 'artificial-intelligence', 'title' => 'Artificial Intelligence and Expert System'],
    ['slug' => 'computer-networks', 'title' => 'Computer Networks'],
    ['slug' => 'computer-organization', 'title' => 'Computer Organization and Architecture'],
    ['slug' => 'operating-system', 'title' => 'Operating System'],
    ['slug' => 'web-technologies', 'title' => 'Web Technologies'],
    ['slug' => 'engineering-ethics', 'title' => 'Engineering Ethics'],
    ['slug' => 'compiler-design', 'title' => 'Compiler Design'],
    
    
];

// Define course descriptions
$course_descriptions = [
    // Semester 1
    'Differential Calculus & Co-ordinate Geometry' => 'Learn derivatives, limits, and analytical geometry for engineering applications',
    'Physics 1' => 'Fundamental principles of mechanics, thermodynamics, and wave motion',
    'Physics 1 Lab' => 'Hands-on experiments in mechanics and thermodynamics',
    'English Reading Skills & Public Speaking' => 'Develop communication skills and presentation techniques',
    'Introduction to Computer Studies' => 'Overview of computer systems and their applications',
    'Introduction to Programming' => 'Basic programming concepts using C/C++ language',
    'Introduction to Programming Lab' => 'Practical programming exercises and problem solving',
    
    // Semester 2
    'Discrete Mathematics' => 'Mathematical structures for computer science applications',
    'Integral Calculus & Ordinary Differential Equations' => 'Integration techniques and solving differential equations',
    'Object Oriented Programming 1' => 'Learn OOP concepts with Java programming language',
    'Physics 2' => 'Electricity, magnetism, and modern physics concepts',
    'Physics 2 Lab' => 'Experiments in electricity, magnetism, and optics',
    'English Writing Skills & Communications' => 'Technical writing and professional communication',
    'Introduction to Electrical Circuits' => 'Basic circuit theory, laws, and analysis techniques',
    'Introduction to Electrical Circuits Lab' => 'Practical circuit design and measurement',
    
    // Semester 3
    'Chemistry' => 'Chemical principles and reactions for engineering applications',
    'Complex Variable, Laplace & Z-Transformation' => 'Complex analysis and transform methods',
    'Introduction to Database' => 'Database concepts, SQL, and relational database design',
    'Electronic Devices Lab' => 'Practical experiments with semiconductor devices',
    'Principles of Accounting' => 'Basic accounting principles and financial statements',
    'Electronic Devices' => 'Semiconductor devices, diodes, transistors, and amplifiers',
    'Data Structure' => 'Study of arrays, linked lists, stacks, queues, and trees',
    'Data Structure Lab' => 'Implementation and analysis of data structures',
    'Computer Aided Design & Drafting' => 'CAD tools for engineering design and modeling',
    
    // Semester 4
    'Algorithms' => 'Design and analysis of efficient algorithms for problem solving',
    'Matrices, Vectors, Fourier Analysis' => 'Linear algebra and Fourier analysis techniques',
    'Object Oriented Programming 2' => 'Advanced OOP concepts and software development',
    'Object Oriented Analysis and Design' => 'Software design patterns and UML modeling',
    'Bangladesh Studies' => 'History, culture, and development of Bangladesh',
    'Digital Logic and Circuits' => 'Boolean algebra, logic gates, and digital circuit design',
    'Digital Logic and Circuits Lab' => 'Practical digital circuit implementation',
    'Computational Statistics and Probability' => 'Statistical methods and probability theory for data analysis',
    
    // Semester 5
    'Theory of Computation' => 'Formal languages, automata theory, and computational complexity',
    'Principles of Economics' => 'Micro and macro economic principles',
    'Business Communication' => 'Professional communication in business context',
    'Numerical Methods for Science and Engineering' => 'Numerical algorithms for scientific computing',
    'Data Communication' => 'Network protocols, transmission media, and communication systems',
    'Microprocessor and Embedded Systems' => 'Microprocessor architecture and embedded system design',
    'Software Engineering' => 'Software development lifecycle, methodologies, and project management',
    
    // Semester 6
    'Artificial Intelligence and Expert System' => 'AI algorithms, knowledge representation, and expert systems',
    'Computer Networks' => 'Network architectures, protocols, and internet technologies',
    'Computer Organization and Architecture' => 'Computer hardware design and system architecture',
    'Operating System' => 'Process management, memory management, and file systems',
    'Web Technologies' => 'HTML, CSS, JavaScript, and server-side programming',
    'Engineering Ethics' => 'Professional ethics and social responsibility for engineers',
    'Compiler Design' => 'Compiler construction techniques and optimization',
    
    
];

// 🔴 CHECK FOR AJAX REQUEST FIRST - IMPORTANT!
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = strtolower($_GET['search_query']);
    $results = [];
    
    foreach ($all_courses as $course) {
        $title = strtolower($course['title']);
        $slug = strtolower($course['slug']);
        
        // Search in title or slug
        if (strpos($title, $search_query) !== false || strpos($slug, $search_query) !== false) {
            $description = $course_descriptions[$course['title']] ?? 'Explore this course to enhance your knowledge in Computer Science and Engineering';
            
            $results[] = [
                'slug' => $course['slug'],
                'title' => $course['title'],
                'description' => substr($description, 0, 100) . '...'
            ];
        }
    }
    
    // Return JSON response and STOP execution
    header('Content-Type: application/json');
    echo json_encode($results);
    exit; // 🔴 CRITICAL: Stop HTML from rendering
}

// If NOT an AJAX request, continue with normal page rendering below
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard | LearnVibe</title>
    <link rel="stylesheet" href="s_dashboard.css">
    <style>
        /* Add search dropdown styles */
        .search-container {
            position: relative;
            display: inline-block;
        }

        .search-results {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ccc;
            border-radius: 0 0 6px 6px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 2000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .search-result-item {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background-color: #f0f0f0;
        }

        .search-result-item h4 {
            margin: 0 0 5px 0;
            color: #2b82f5;
            font-size: 14px;
            font-weight: 600;
        }

        .search-result-item p {
            margin: 0;
            color: #666;
            font-size: 12px;
            line-height: 1.3;
        }

        .no-results {
            padding: 12px;
            text-align: center;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search courses...">
        <div class="search-results" id="searchResults"></div>
    </div>
    <div class="top-links">
        <!-- Feedback Button in top bar -->
        <a href="feedback.php" class="feedback-btn">Feedback</a>
        
        <div class="profile">
            <a href="#" id="profile-btn">My Profile ▼</a>
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
            $link = "../../Instructor/View/course_files.php?course=" . urlencode($course['slug']);
        ?>
            <div class="course">
                <a href="<?= $link ?>">
                    <?php 
                    // Course images mapping
                    $images = [
                        // Semester 1
                        'Differential Calculus & Co-ordinate Geometry' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Physics 1' => 'https://img.freepik.com/premium-vector/vector-hand-drawing-physics-education-doodle-icon-idea-set_602351-720.jpg?semt=ais_hybrid&w=740&q=80',
                        'Physics 1 Lab' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'English Reading Skills & Public Speaking' => 'https://cdn-icons-png.flaticon.com/512/5526/5526264.png',
                        'Introduction to Computer Studies' => 'https://images.unsplash.com/photo-1516116216624-53e697fedbea?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Introduction to Programming' => 'https://miro.medium.com/1*mDKusLBkGKBWW4aycK4PCA.png',
                        'Introduction to Programming Lab' => 'https://mir-s3-cdn-cf.behance.net/project_modules/fs/5e2115104733947.5f69c10ec4243.jpg',
                        
                        // Semester 2
                        'Discrete Mathematics' => 'https://ivyleaguecenter.org/wp-content/uploads/2015/03/discrete-math.jpg',
                        'Integral Calculus & Ordinary Differential Equations' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Object Oriented Programming 1' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Physics 2' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Physics 2 Lab' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'English Writing Skills & Communications' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Introduction to Electrical Circuits' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0N60W2ilWUgIrCjCbgjpk9GeDxbSIihiQdQ&s',
                        'Introduction to Electrical Circuits Lab' => 'https://www.wonderfulpcb.com/wp-content/uploads/2025/03/Electronic-Devices-Circuit-Components.jpg',
                        
                        // Semester 3
                        'Chemistry' => 'https://img.freepik.com/free-vector/hand-drawn-chemistry-background_23-2148164901.jpg',
                        'Complex Variable, Laplace & Z-Transformation' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Introduction to Database' => 'https://bs-uploads.toptal.io/blackfish-uploads/components/open_graph_image/10698762/og_image/optimized/0712-Bad_Practices_in_Database_Design_-_Are_You_Making_These_Mistakes_Dan_Social-754bc73011e057dc76e55a44a954e0c3.png',
                        'Electronic Devices Lab' => 'https://southelectronicpcb.com/wp-content/uploads/2024/07/image-165-1024x726.png',
                        'Principles of Accounting' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Electronic Devices' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Data Structure' => 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Data Structure Lab' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-Ji69HYeNqt2YPsYqeR_61Vzx3YcQpVT8cg&s',
                        'Computer Aided Design & Drafting' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        
                        // Semester 4
                        'Algorithms' => 'https://images.unsplash.com/photo-1580894894513-541e068a3e2b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Matrices, Vectors, Fourier Analysis' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Object Oriented Programming 2' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Object Oriented Analysis and Design' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Bangladesh Studies' => 'https://static.vecteezy.com/system/resources/thumbnails/012/953/887/small/bangladesh-map-artwork-vector.jpg',
                        'Digital Logic and Circuits' => 'https://cburch.com/books/logic/circ-xor.png',
                        'Digital Logic and Circuits Lab' => 'https://cburch.com/books/logic/circ-xor.png',
                        'Computational Statistics and Probability' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        
                        // Semester 5
                        'Theory of Computation' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Principles of Economics' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Business Communication' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Numerical Methods for Science and Engineering' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Data Communication' => 'https://images.unsplash.com/photo-1581276879432-15e50529f34b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Microprocessor and Embedded Systems' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Software Engineering' => 'https://images.unsplash.com/photo-1526498460520-4c246339dccb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        
                        // Semester 6
                        'Artificial Intelligence and Expert System' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Computer Networks' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Computer Organization and Architecture' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Operating System' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Web Technologies' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Engineering Ethics' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        'Compiler Design' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
                        
                        
                    ];
                    $image_url = $images[$course['title']] ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                    ?>
                    <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                </a>
                
                <a href="<?= $link ?>" class="course-title">
                    <?= htmlspecialchars($course['title']) ?>
                </a>
                
                <p>
                    <?= $course_descriptions[$course['title']] ?? 'Explore this course to enhance your knowledge in Computer Science and Engineering' ?>
                </p>
                
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

<!-- JS for clickable profile -->
<script>
const profileBtn = document.getElementById('profile-btn');
const profileMenu = document.getElementById('profile-menu');

profileBtn.addEventListener('click', function(e) {
    e.preventDefault();
    profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function(e) {
    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.style.display = 'none';
    }
});
</script>

<!-- JS for AJAX Search -->
<script>
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

// Function to perform search
// In s_dashboard.php JavaScript, update the performSearch function:

function performSearch() {
    const query = searchInput.value.trim();
    
    // Show results even for single character
    if (query.length < 1) {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
        return;
    }
    
    // Create XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    
    // Configure the request
    xhr.open('GET', 'search_courses.php?search_query=' + encodeURIComponent(query), true);
    
    // Set up the callback function
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const results = JSON.parse(xhr.responseText);
                    displayResults(results);
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    displayResults([]);
                }
            } else {
                console.error('Request failed with status:', xhr.status);
                displayResults([]);
            }
        }
    };
    
    // Handle errors
    xhr.onerror = function() {
        console.error('Request failed');
        displayResults([]);
    };
    
    // Send the request
    xhr.send();
}

// Function to display search results
function displayResults(results) {
    searchResults.innerHTML = '';
    
    if (results.length === 0) {
        const noResults = document.createElement('div');
        //noResults.className = 'no-results';
        //noResults.textContent = 'No courses found';
        searchResults.appendChild(noResults);
    } else {
        // Limit to top 10 results
        const limitedResults = results.slice(0, 10);
        
        limitedResults.forEach(course => {
            const resultItem = document.createElement('div');
            resultItem.className = 'search-result-item';
            resultItem.innerHTML = `
                <h4>${escapeHtml(course.title)}</h4>
                <p>${escapeHtml(course.description)}</p>
            `;
            
            // Add click event to navigate to course
            resultItem.addEventListener('click', function() {
                window.location.href = `../../Instructor/View/course_files.php?course=${encodeURIComponent(course.slug)}`;
            });
            
            searchResults.appendChild(resultItem);
        });
    }
    
    searchResults.style.display = 'block';
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Event listeners for search
searchInput.addEventListener('input', function() {
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Set new timeout (debouncing - wait 300ms after typing stops)
    searchTimeout = setTimeout(performSearch, 300);
});

searchInput.addEventListener('focus', function() {
    if (searchInput.value.trim().length >= 2) {
        performSearch();
    }
});

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// Keyboard navigation
searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        searchResults.style.display = 'none';
        searchInput.blur();
    } else if (e.key === 'Enter' && searchInput.value.trim().length > 0) {
        // If user presses Enter, navigate to first result or show all results
        const firstResult = searchResults.querySelector('.search-result-item');
        if (firstResult) {
            firstResult.click();
        }
        e.preventDefault();
    }
});

// Show results when input is focused and has content
searchInput.addEventListener('focus', function() {
    if (searchInput.value.trim().length >= 2) {
        performSearch();
    }
});
</script>

</body>
</html>