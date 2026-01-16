<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../../Instructor/View/instructor_login.php");
    exit;
}

// DB connect (PDO)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=learnvibe;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $pdo = null;
}

// Courses (AIUB CSE Curriculum)
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

// Short descriptions (simple)
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

// -------------------------
// Load uploaded files (latest 3 per course + count)
// -------------------------
$courseFiles = [];     // title => [files...]
$courseCounts = [];    // title => count

if ($pdo) {
    // ⚠️ Change this table name if yours is different
    $sql = "SELECT course_title, file_path, file_type, uploaded_at
            FROM course_files
            ORDER BY uploaded_at DESC";

    try {
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $title = $row['course_title'] ?? '';

            if ($title === '') continue;

            // count
            if (!isset($courseCounts[$title])) $courseCounts[$title] = 0;
            $courseCounts[$title]++;

            // latest 3 only
            if (!isset($courseFiles[$title])) $courseFiles[$title] = [];
            if (count($courseFiles[$title]) < 3) {
                $courseFiles[$title][] = $row;
            }
        }
    } catch (PDOException $e) {
        // if table/columns mismatch, page still loads
        $courseFiles = [];
        $courseCounts = [];
    }
}

// -------------------------
// AJAX SEARCH (same file)
// URL: thisfile.php?search_query=phy
// -------------------------
if (isset($_GET['search_query'])) {
    $q = strtolower(trim($_GET['search_query']));
    $results = [];

    if ($q !== "") {
        foreach ($all_courses as $course) {
            $title = strtolower($course['title']);
            $slug  = strtolower($course['slug']);

            if (strpos($title, $q) !== false || strpos($slug, $q) !== false) {
                $desc = $course_descriptions[$course['title']] ?? "Course materials";
                $results[] = [
                    "slug" => $course['slug'],
                    "title" => $course['title'],
                    "description" => $desc,
                    "files" => (int)($courseCounts[$course['title']] ?? 0)
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard | LearnVibe</title>

    <link rel="stylesheet" href="s_dashboard.css">
    <link rel="stylesheet" href="search_courses.css">

    <style>
      /* small simple style for files list inside card */
      .course-files { margin-top: 10px; font-size: 12px; }
      .course-files ul { margin: 6px 0 0 18px; }
      .course-files li { margin: 3px 0; }
      .file-count { font-size: 12px; color:#555; margin-top:6px; }
    </style>
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
            $courseTitle = $course['title'];
            $link = "../../Instructor/View/course_files.php?course=" . urlencode($course['slug']);
            $files = $courseFiles[$courseTitle] ?? [];
            $count = (int)($courseCounts[$courseTitle] ?? 0);

            // simple default image (same for all, keep code beginner)
            $image_url = "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80";
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

profileBtn.addEventListener('click', function(e) {
    e.preventDefault();
    profileMenu.style.display = (profileMenu.style.display === 'block') ? 'none' : 'block';
});

document.addEventListener('click', function(e) {
    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.style.display = 'none';
    }
});
</script>

<!-- SIMPLE AJAX SEARCH (same file) -->
<script>
const searchInput = document.getElementById("searchInput");
const searchResults = document.getElementById("searchResults");
let timer = null;

const SEARCH_URL = "<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>";

searchInput.addEventListener("input", () => {
  clearTimeout(timer);

  const q = searchInput.value.trim();
  if (q === "") {
    searchResults.style.display = "none";
    searchResults.innerHTML = "";
    return;
  }

  timer = setTimeout(() => {
    fetch(SEARCH_URL + "?search_query=" + encodeURIComponent(q))
      .then(r => r.json())
      .then(data => {
        searchResults.innerHTML = "";

        data.forEach(course => {
          const div = document.createElement("div");
          div.className = "search-result-item";
          div.textContent = course.title + " (Files: " + course.files + ")";

          div.onclick = () => {
            window.location.href = "../../Instructor/View/course_files.php?course=" + encodeURIComponent(course.slug);
          };

          searchResults.appendChild(div);
        });

        searchResults.style.display = data.length ? "block" : "none";
      })
      .catch(() => {
        searchResults.style.display = "none";
      });
  }, 250);
});

// hide results when clicking outside
document.addEventListener("click", (e) => {
  if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
    searchResults.style.display = "none";
  }
});
</script>

</body>
</html>
