<?php

session_start();

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: Login.php");
    exit;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Project</title>
    <link rel="stylesheet" href="../../Student/View/s_dashboard.css">
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <input type="text" placeholder="Search">
    <input type="button"  value="Upload" onclick="window.location.href='upload_course.php'">
    
    <div class="top-links">
        <div class="profile">
            <a href="#" id="profile-btn">My Profile ▼</a>
            <div class="profile-menu" id="profile-menu">
                <a href="#">View</a>
                <a href="#">Edit</a>
            </div>
        </div>
        <a href="Logout.php">Logout</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container">
    <div class="grid">

        <div class="course">
            <a href="#"><img src="https://media.geeksforgeeks.org/wp-content/uploads/20250405172151734944/Difference-Between-Cyber-Security-and-Information-Security.webp" alt="Cyber Security"></a>
            <a href="#" class="course-title">Cyber Security</a>
            <p>Learn to protect networks and systems from cyber threats</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://img.freepik.com/premium-vector/machine-learning-ai-artificial-intelligence-deep-learning-big-data-neural-network-background-with-circuit-board-connections-tech-icons-wireframe-hand-pressing-button-vector-illustration_127544-3668.jpg?semt=ais_hybrid&w=740&q=80" alt="Machine Learning"></a>
            <a href="#" class="course-title">Machine Learning</a>
            <p>Master algorithms to make computers learn from data</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://static.vecteezy.com/system/resources/thumbnails/046/996/176/small/circuit-board-machine-learning-and-artificial-intelligence-are-processing-big-data-and-information-hi-tech-blue-background-vector.jpg" alt="Artificial Intelligence"></a>
            <a href="#" class="course-title">Artificial Intelligence</a>
            <p>Explore AI concepts and build intelligent systems</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://static.vecteezy.com/system/resources/thumbnails/003/665/736/small/business-man-pushing-on-a-touch-screen-interface-java-programming-concept-virtual-machine-photo.jpg" alt="Programming in Java"></a>
            <a href="#" class="course-title">Programming in Java</a>
            <p>Learn Java programming from basics to advanced level</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://media.geeksforgeeks.org/wp-content/cdn-uploads/20221222184908/web-development1.png" alt="Web Development"></a>
            <a href="#" class="course-title">Web Development</a>
            <p>Build modern websites using HTML, CSS, and JavaScript</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://www.mygreatlearning.com/blog/wp-content/uploads/2019/09/What-is-data-science-2.jpg" alt="Data Science"></a>
            <a href="#" class="course-title">Data Science</a>
            <p>Analyze data and extract insights using Python and R</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://4kwallpapers.com/images/wallpapers/python-programming-3840x2160-16102.jpg" alt="Python Programming"></a>
            <a href="#" class="course-title">Python Programming</a>
            <p>Learn Python from basics to advanced for multiple applications</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://assets.cioinsight.com/uploads/2022/05/Database-Management-Systems-scaled.jpeg" alt="Database Management"></a>
            <a href="#" class="course-title">Database Management</a>
            <p>Understand databases and SQL for data storage and retrieval</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://t4.ftcdn.net/jpg/05/07/66/23/360_F_507662376_BTKmPlIGBvKlRHWKRNeFt7bj7H2SynQm.jpg" alt="Cloud Computing"></a>
            <a href="#" class="course-title">Cloud Computing</a>
            <p>Learn about cloud platforms like AWS, Azure, and GCP</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTv06nCBIFYSUtVFzSZTlP9gcdDeoJYDH1aWg&s" alt="Blockchain"></a>
            <a href="#" class="course-title">Blockchain</a>
            <p>Explore blockchain technology and decentralized systems</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://www.mouser.in/blog/Portals/11/Dongang_Machine%20Learning_Theme%20Image-min_1.jpg" alt="Deep Learning"></a>
            <a href="#" class="course-title">Deep Learning</a>
            <p>Build neural networks and learn deep learning techniques</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://img.freepik.com/premium-vector/neural-network-model-with-real-synapses-circle-neurons-connected-full-mesh-illustration_250841-32.jpg?semt=ais_hybrid&w=740&q=80" alt="Neural Networks"></a>
            <a href="#" class="course-title">Neural Networks</a>
            <p>Understand architectures for AI and ML applications</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/e/e3/Software_development_icon.png" alt="Software Engineering"></a>
            <a href="#" class="course-title">Software Engineering</a>
            <p>Learn software development life cycle and best practices</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/f/f5/Android_robot.svg" alt="Android App Development"></a>
            <a href="#" class="course-title">Android App Development</a>
            <p>Create Android apps using Java and Kotlin</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/C%2B%2B_Logo.svg" alt="C++ Programming"></a>
            <a href="#" class="course-title">C++ Programming</a>
            <p>Learn C++ programming from beginner to advanced level</p>
        </div>

        <div class="course">
            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/8/87/Machine_learning_icon.png" alt="AI & ML Projects"></a>
            <a href="#" class="course-title">AI & ML Projects</a>
            <p>Build hands-on projects in AI and Machine Learning</p>
        </div>

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
