<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnVibe- Your Learning Assistant</title>
    <link rel="icon" href="assets/web-favicon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Paytone+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>

 
    <div class="navbar">
        <h1 class="logo">Learn<span>Vibe</span></h1>
        <div class="nav-links">
            <button class="login-btn" onclick="window.location.href='../../Instructor/View/Login.php'">
                Login
            </button>
        </div>
    </div>

   
    <section class="hero" id="home">
        <div class="hero-text">
            <h2>Your Digital</h2>
            <h2>Learning Hub</h2>
            <p>Where Students Learn, Instructors Teach, and Knowledge Connects...</p>
            <button id="join-btn" onclick="window.location.href='../../Instructor/View/Signup.php'">
                Join Us
            </button>
        </div>

        <div class="hero-image">
            <img src="assets/homepage.jpeg" alt="LearnVibe preview">
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer" id="contact">

        <div class="footer-top">
            <div class="footer-col">
                <h1 class="logo">Learn<span>Vibe</span></h1>
                <p id="footer-subtitle">"Empowering students since 2025"</p>
                <h3>Find Us </h3>
                <div class="social-links">
                    <a class="social-facebook" href="https://facebook.com" target="_blank">
                        <img src="assets/facebook.png" alt="Facebook">
                    </a>
                    <a class="social-linkedin" href="https://linkedin.com" target="_blank">
                        <img src="assets/linkedin.png" alt="LinkedIn">
                    </a>
                    <a class="social-instagram" href="https://instagram.com" target="_blank">
                        <img src="assets/instagram.png" alt="Instagram">
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h3>Support</h3>
                <a href="#">FAQ</a>
                <a href="#">Upload Guidelines</a>
                <a href="#">Help & Support</a>
            </div>

            <div class="footer-col">
                <h3>Contact</h3>
                <p>
                    <a href="">
                        <img src="assets/email.png" alt="Email icon">
                        example@learnvibe.com
                    </a>
                </p>
                <p>
                    <a href="">
                        <img src="assets/telephone.png" alt="Phone icon">
                        +123 456 7890
                    </a>
                </p>
                <p>
                    <a href="">
                        <img src="assets/location.png" alt="Location icon">
                        <b>Dhaka, Bangladesh</b>
                    </a>
                </p>
                <p>
                    <a href="">
                        <img src="assets/link.png" alt="Link icon">
                        Privacy Policy
                    </a>
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            © 2025 LearnVibe. All Rights Reserved.
        </div>

    </footer>

    

</body>
</html>
