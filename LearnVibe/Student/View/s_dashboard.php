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

// Define courses array
$all_courses = [
    ['slug' => 'Cyber Security', 'title' => 'Cyber Security'],
    ['slug' => 'Machine Learning', 'title' => 'Machine Learning'],
    ['slug' => 'Web Development', 'title' => 'Web Development'],
    ['slug' => 'Python Programming', 'title' => 'Python Programming'],
    ['slug' => 'Artificial Intelligence', 'title' => 'Artificial Intelligence'],
    ['slug' => 'Data Science', 'title' => 'Data Science'],
    ['slug' => 'Programming in Java', 'title' => 'Programming in Java'],
    ['slug' => 'Database Management', 'title' => 'Database Management'],
    ['slug' => 'Cloud Computing', 'title' => 'Cloud Computing'],
    ['slug' => 'Blockchain', 'title' => 'Blockchain'],
    ['slug' => 'Deep Learning', 'title' => 'Deep Learning'],
    ['slug' => 'Neural Networks', 'title' => 'Neural Networks'],
    ['slug' => 'Software Engineering', 'title' => 'Software Engineering'],
    ['slug' => 'Android App Development', 'title' => 'Android App Development'],
    ['slug' => 'C++ Programming', 'title' => 'C++ Programming'],
    ['slug' => 'AI & ML Projects', 'title' => 'AI & ML Projects'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard | LearnVibe</title>
    <link rel="stylesheet" href="s_dashboard.css">
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <input type="text" placeholder="Search">
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
                    $images = [
                        'Cyber Security' => 'https://media.geeksforgeeks.org/wp-content/uploads/20250405172151734944/Difference-Between-Cyber-Security-and-Information-Security.webp',
                        'Machine Learning' => 'https://img.freepik.com/premium-vector/machine-learning-ai-artificial-intelligence-deep-learning-big-data-neural-network-background-with-circuit-board-connections-tech-icons-wireframe-hand-pressing-button-vector-illustration_127544-3668.jpg?semt=ais_hybrid&w=740&q=80',
                        'Web Development' => 'https://media.geeksforgeeks.org/wp-content/cdn-uploads/20221222184908/web-development1.png',
                        'Python Programming' => 'https://4kwallpapers.com/images/wallpapers/python-programming-3840x2160-16102.jpg',
                        'Artificial Intelligence' => 'https://static.vecteezy.com/system/resources/thumbnails/046/996/176/small/circuit-board-machine-learning-and-artificial-intelligence-are-processing-big-data-and-information-hi-tech-blue-background-vector.jpg',
                        'Data Science' => 'https://www.mygreatlearning.com/blog/wp-content/uploads/2019/09/What-is-data-science-2.jpg',
                    ];
                    $image_url = $images[$course['title']] ?? 'https://via.placeholder.com/300x210/2b82f5/ffffff?text=' . urlencode($course['title']);
                    ?>
                    <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                </a>
                
                <a href="<?= $link ?>" class="course-title">
                    <?= htmlspecialchars($course['title']) ?>
                </a>
                
                <p>
                    <?php 
                    $descriptions = [
                        'Cyber Security' => 'Learn to protect networks and systems from cyber threats',
                        'Machine Learning' => 'Master algorithms to make computers learn from data',
                        'Web Development' => 'Build modern websites using HTML, CSS, and JavaScript',
                        'Python Programming' => 'Learn Python from basics to advanced for multiple applications',
                        'Artificial Intelligence' => 'Explore AI concepts and build intelligent systems',
                        'Data Science' => 'Analyze data and extract insights using Python and R',
                        'Programming in Java' => 'Learn Java programming from basics to advanced level',
                        'Database Management' => 'Understand databases and SQL for data storage and retrieval',
                        'Cloud Computing' => 'Learn about cloud platforms like AWS, Azure, and GCP',
                        'Blockchain' => 'Explore blockchain technology and decentralized systems',
                        'Deep Learning' => 'Build neural networks and learn deep learning techniques',
                        'Neural Networks' => 'Understand architectures for AI and ML applications',
                        'Software Engineering' => 'Learn software development life cycle and best practices',
                        'Android App Development' => 'Create Android apps using Java and Kotlin',
                        'C++ Programming' => 'Learn C++ programming from beginner to advanced level',
                        'AI & ML Projects' => 'Build hands-on projects in AI and Machine Learning',
                    ];
                    echo $descriptions[$course['title']] ?? 'Explore this course to enhance your knowledge';
                    ?>
                </p>
                
                <div class="course-actions">
                    <a href="<?= $link ?>" class="btn">View Files</a>
                    <!-- Removed Give Feedback button -->
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

</body>
</html>