<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Feedback</title>
  <style>
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 8px; }
    th { font-weight: bold; }
  </style>
</head>
<body>

<button type="button" onclick="window.location.href='../View/i_dashboard.php'">Dashboard</button>

<h2>All Student Feedback</h2>

<?php if (!$res || mysqli_num_rows($res) == 0): ?>
  <p>No feedback found.</p>
<?php else: ?>
  <table>
    <tr>
      <th>Course</th>
      <th>Student</th>
      <th>Email</th>
      <th>Rating</th>
      <th>Comment</th>
      <th>Date</th>
    </tr>

    <?php while($f = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= htmlspecialchars($f["course_title"] ?? "Unknown Course") ?></td>
        <td><?= htmlspecialchars($f["full_name"] ?? "Unknown") ?></td>
        <td><?= htmlspecialchars($f["email"] ?? "") ?></td>
        <td><?= htmlspecialchars($f["rating"] ?? "") ?>/5</td>
        <td><?= htmlspecialchars($f["comment"] ?? "") ?></td>
        <td><?= htmlspecialchars($f["created_at"] ?? "-") ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php endif; ?>

</body>
</html>
