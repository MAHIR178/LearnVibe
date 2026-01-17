<?php
session_start();
include "../../Admin/Model/Database.php";

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: instructor_login.php");
    exit;
}
 if (isset($_GET["upload"]) && $_GET["upload"] === "success"): ?>
<script>
    alert("File uploaded successfully!");
</script>

<?php endif; 
$msg = $_SESSION["upload_error"] ?? "";
unset($_SESSION["upload_error"]);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Project</title>
    <link rel="stylesheet" href="../../Student/View/s_dashboard.css">
    <link rel="stylesheet" href="Upload.css">
</head>


<body>

<!-- TOP BAR -->
<div class="top-bar">


    
    <a class="myuploadbutton" href="../Controller/Myuploads.php">Uploaded-Files</a>
    <br><br>
    <a class="feedbackbutton" href="../Controller/i_feedback.php">Feedback</a>


    <div class="top-links">
        <div class="profile">
            <a href="#" id="profile-btn">My Profile ▼</a>
            <div class="profile-menu" id="profile-menu">
                <a href="../../Student/View/profile_view.php">View</a>
                <a href="../../Student/View/profile_edit.php">Edit</a>
            </div>
        </div>
        <a href="Logout.php">Logout</a>
    </div>
</div>

<<div class="form-box">
    <h2>Upload File</h2>

    <?php if ($msg !== ""): ?>
        <div class="msg msg-error"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    
    <form method="POST" action="../Controller/uploadvalid.php" enctype="multipart/form-data">

        <div class="form-row">
  <label>Course</label>
  <select name="course" required>
    <option value="">Select Course</option>
    <option>Differential Calculus &amp; Co-ordinate Geometry</option>
    <option>Physics 1</option>
    <option>Physics 1 Lab</option>
    <option>English Reading Skills &amp; Public Speaking</option>
    <option>Introduction to Computer Studies</option>
    <option>Introduction to Programming</option>
    <option>Introduction to Programming Lab</option>
    <option>Discrete Mathematics</option>
    <option>Integral Calculus &amp; Ordinary Differential Equations</option>
    <option>Object Oriented Programming 1</option>
    <option>Physics 2</option>
    <option>Physics 2 Lab</option>
    <option>English Writing Skills &amp; Communications</option>
    <option>Introduction to Electrical Circuits</option>
    <option>Introduction to Electrical Circuits Lab</option>
    <option>Chemistry</option>
    <option>Complex Variable, Laplace &amp; Z-Transformation</option>
    <option>Introduction to Database</option>
    <option>Electronic Devices Lab</option>
    <option>Principles of Accounting</option>
    <option>Electronic Devices</option>
    <option>Data Structure</option>
    <option>Data Structure Lab</option>
    <option>Computer Aided Design &amp; Drafting</option>
    <option>Algorithms</option>
    <option>Matrices, Vectors, Fourier Analysis</option>
    <option>Object Oriented Programming 2</option>
    <option>Object Oriented Analysis and Design</option>
    <option>Bangladesh Studies</option>
    <option>Digital Logic and Circuits</option>
    <option>Digital Logic and Circuits Lab</option>
    <option>Computational Statistics and Probability</option>
    <option>Theory of Computation</option>
    <option>Principles of Economics</option>
    <option>Business Communication</option>
    <option>Numerical Methods for Science and Engineering</option>
    <option>Data Communication</option>
    <option>Microprocessor and Embedded Systems</option>
    <option>Software Engineering</option>
    <option>Artificial Intelligence and Expert System</option>
    <option>Computer Networks</option>
    <option>Computer Organization and Architecture</option>
    <option>Operating System</option>
    <option>Web Technologies</option>
    <option>Engineering Ethics</option>
    <option>Compiler Design</option>
  </select>
</div>


 <div class="form-row">
  <label>File Type</label>
   <select name="file_type" required>
  <option value="">Select File type</option>
  <option>PDF</option>
  <option>PPTX</option>
  <option>Document</option>
</select>

        </div>

        <div class="form-row">
            <label>Choose File</label>
            <input type="file" name="upload_file" required>
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Upload</button>
            <button class="btn btn-secondary" type="button" onclick="window.location.href='i_dashboard.php'">Cancel</button>
        </div>

    </form>
</div>

</body>
</html>

<!-- FOOTER -->
<div class="footer">
    <p>Contact: example@email.com | 0123456789</p>
    <p class="social">
        <a href="#">Facebook</a> |
        <a href="#">Twitter</a> |
        <a href="#">LinkedIn</a>
    </p>
</div>

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
