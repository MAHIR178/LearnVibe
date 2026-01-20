<?php
session_start();
require_once '../../Admin/Model/Database.php';

if (empty($_SESSION["isLoggedIn"]) || ($_SESSION["role"] ?? '') !== 'instructor') {
    header("Location: instructor_login.php");
    exit;
}

$db = new DatabaseConnection();
$con = $db->openConnection();

$uploadedBy = (int)($_SESSION["user_id"] ?? 0);
$res = $db->getInstructorFiles($con, $uploadedBy);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Uploaded Files</title>
</head>
<body>

<h2>My Uploaded Files</h2>
<button type="button" onclick="window.location.href='../View/i_dashboard.php'">Dashboard</button>

<hr>

<!-- ✅ Search box -->
<input
    type="text"
    id="searchBox"
    placeholder="Search files..."
    onkeyup="searchMyFiles()"
    style="padding:8px; width:280px;"
>
<br><br>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>Course</th>
            <th>Type</th>
            <th>File Name</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody id="filesBody">
    <?php if (!$res || $res->num_rows == 0): ?>
        <tr><td colspan="4">No uploaded files yet.</td></tr>
    <?php else: ?>
        <?php while($row = $res->fetch_assoc()): ?>
            <?php
                // ✅ actual uploaded file name
                $realName = $row["original_name"]
                            ?? ($row["file_name"] ?? basename($row["file_path"] ?? ""));
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row["course_title"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($row["file_type"] ?? ""); ?></td>
                <td>
                    <a href="<?php echo htmlspecialchars($row["file_path"] ?? "#"); ?>" download>
                        <?php echo htmlspecialchars($realName); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($row["uploaded_at"] ?? ""); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
    </tbody>
</table>

<script>
function searchMyFiles() {
    var q = document.getElementById("searchBox").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // server returns <tr>...</tr> rows
            document.getElementById("filesBody").innerHTML = this.responseText;
        }
    };

    xhttp.open("POST", "../Controller/searchfile.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("q=" + encodeURIComponent(q));
}
</script>

</body>
</html>
<?php
$db->closeConnection($con);
?>
