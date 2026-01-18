<?php
session_start();

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/instructor_login.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "learnvibe");

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
$course_descriptions = [
    'Differential Calculus & Co-ordinate Geometry' => 'Learn derivatives, limits, and analytical geometry.',
    'Physics 1' => 'Fundamental mechanics, thermodynamics, and waves.',
    'Physics 1 Lab' => 'Hands-on experiments in Physics 1 topics.',
    'English Reading Skills & Public Speaking' => 'Improve reading and speaking skills.',
    'Introduction to Computer Studies' => 'Basics of computers and applications.',
    'Introduction to Programming' => 'Learn programming fundamentals.',
    'Introduction to Programming Lab' => 'Practice programming with lab tasks.',
    'Discrete Mathematics' => 'Math for computer science foundations.',
    'Integral Calculus & Ordinary Differential Equations' => 'Integration and ODE basics.',
    'Object Oriented Programming 1' => 'OOP concepts with programming.',
    'Physics 2' => 'Electricity, magnetism, and modern physics.',
    'Physics 2 Lab' => 'Lab experiments for Physics 2.',
    'English Writing Skills & Communications' => 'Improve writing and communication.',
    'Introduction to Electrical Circuits' => 'Circuit laws and analysis.',
    'Introduction to Electrical Circuits Lab' => 'Practical circuit experiments.',
    'Chemistry' => 'Engineering chemistry basics.',
    'Complex Variable, Laplace & Z-Transformation' => 'Transforms and complex analysis.',
    'Introduction to Database' => 'Database concepts and SQL.',
    'Electronic Devices Lab' => 'Lab practice with devices.',
    'Principles of Accounting' => 'Accounting fundamentals.',
    'Electronic Devices' => 'Diodes, transistors and circuits.',
    'Data Structure' => 'Stacks, queues, trees, etc.',
    'Data Structure Lab' => 'Implement data structures.',
    'Computer Aided Design & Drafting' => 'CAD tools and drafting basics.',
    'Algorithms' => 'Algorithm design and analysis.',
    'Matrices, Vectors, Fourier Analysis' => 'Linear algebra and Fourier basics.',
    'Object Oriented Programming 2' => 'Advanced OOP and development.',
    'Object Oriented Analysis and Design' => 'Design patterns and UML.',
    'Bangladesh Studies' => 'History and culture of Bangladesh.',
    'Digital Logic and Circuits' => 'Logic gates and digital circuits.',
    'Digital Logic and Circuits Lab' => 'Digital circuit lab work.',
    'Computational Statistics and Probability' => 'Statistics and probability for computing.',
    'Theory of Computation' => 'Automata and formal languages.',
    'Principles of Economics' => 'Basic economics.',
    'Business Communication' => 'Communication for business.',
    'Numerical Methods for Science and Engineering' => 'Numerical computing methods.',
    'Data Communication' => 'Communication systems and protocols.',
    'Microprocessor and Embedded Systems' => 'Microprocessors and embedded basics.',
    'Software Engineering' => 'SDLC and software project basics.',
    'Artificial Intelligence and Expert System' => 'Basic AI and expert systems.',
    'Computer Networks' => 'Networking concepts and protocols.',
    'Computer Organization and Architecture' => 'Computer hardware organization.',
    'Operating System' => 'Processes, memory, file systems.',
    'Web Technologies' => 'Web development technologies.',
    'Engineering Ethics' => 'Ethics for engineers.',
    'Compiler Design' => 'Basic compiler concepts.',
];
// Course Images
$course_images = [
    'Differential Calculus & Co-ordinate Geometry' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Physics 1' => 'https://img.freepik.com/premium-vector/vector-hand-drawing-physics-education-doodle-icon-idea-set_602351-720.jpg?semt=ais_hybrid&w=740&q=80',
    'Physics 1 Lab' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'English Reading Skills & Public Speaking' => 'https://cdn-icons-png.flaticon.com/512/5526/5526264.png',
    'Introduction to Computer Studies' => 'https://images.unsplash.com/photo-1516116216624-53e697fedbea?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Introduction to Programming' => 'https://miro.medium.com/1*mDKusLBkGKBWW4aycK4PCA.png',
    'Introduction to Programming Lab' => 'https://mir-s3-cdn-cf.behance.net/project_modules/fs/5e2115104733947.5f69c10ec4243.jpg',
    'Discrete Mathematics' => 'https://ivyleaguecenter.org/wp-content/uploads/2015/03/discrete-math.jpg',
    'Integral Calculus & Ordinary Differential Equations' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Object Oriented Programming 1' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Physics 2' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Physics 2 Lab' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'English Writing Skills & Communications' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Introduction to Electrical Circuits' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0N60W2ilWUgIrCjCbgjpk9GeDxbSIihiQdQ&s',
    'Introduction to Electrical Circuits Lab' => 'https://www.wonderfulpcb.com/wp-content/uploads/2025/03/Electronic-Devices-Circuit-Components.jpg',
    'Chemistry' => 'https://img.freepik.com/free-vector/hand-drawn-chemistry-background_23-2148164901.jpg',
    'Complex Variable, Laplace & Z-Transformation' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Introduction to Database' => 'https://bs-uploads.toptal.io/blackfish-uploads/components/open_graph_image/10698762/og_image/optimized/0712-Bad_Practices_in_Database_Design_-_Are_You_Making_These_Mistakes_Dan_Social-754bc73011e057dc76e55a44a954e0c3.png',
    'Electronic Devices Lab' => 'https://southelectronicpcb.com/wp-content/uploads/2024/07/image-165-1024x726.png',
    'Principles of Accounting' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Electronic Devices' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Data Structure' => 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Data Structure Lab' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-Ji69HYeNqt2YPsYqeR_61Vzx3YcQpVT8cg&s',
    'Computer Aided Design & Drafting' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Algorithms' => 'https://images.unsplash.com/photo-1580894894513-541e068a3e2b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Matrices, Vectors, Fourier Analysis' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Object Oriented Programming 2' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Object Oriented Analysis and Design' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Bangladesh Studies' => 'https://static.vecteezy.com/system/resources/thumbnails/012/953/887/small/bangladesh-map-artwork-vector.jpg',
    'Digital Logic and Circuits' => 'https://cburch.com/books/logic/circ-xor.png',
    'Digital Logic and Circuits Lab' => 'https://cburch.com/books/logic/circ-xor.png',
    'Computational Statistics and Probability' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Theory of Computation' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Principles of Economics' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Business Communication' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Numerical Methods for Science and Engineering' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Data Communication' => 'https://images.unsplash.com/photo-1581276879432-15e50529f34b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Microprocessor and Embedded Systems' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Software Engineering' => 'https://images.unsplash.com/photo-1526498460520-4c246339dccb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Artificial Intelligence and Expert System' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Computer Networks' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Computer Organization and Architecture' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Operating System' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Web Technologies' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Engineering Ethics' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Compiler Design' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
];

$courseFiles = [];     // title => [files...]
$courseCounts = [];    // title => count

if (!$conn->connect_error) {
    $sql = "SELECT course_title, file_path, file_type, uploaded_at
            FROM course_files
            ORDER BY uploaded_at DESC";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = $row['course_title'] ?? '';

            if ($title === '')
                continue;

            // count
            if (!isset($courseCounts[$title]))
                $courseCounts[$title] = 0;
            $courseCounts[$title]++;

            // latest 3 only
            if (!isset($courseFiles[$title]))
                $courseFiles[$title] = [];
            if (count($courseFiles[$title]) < 3) {
                $courseFiles[$title][] = $row;
            }
        }
        $result->free(); // Free result set
    } else {
        $courseFiles = [];
        $courseCounts = [];
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
    <script src="/LearnVibe/LearnVibe/Student/Controller/JS/search_courses.js" defer></script>

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


</body>

</html>