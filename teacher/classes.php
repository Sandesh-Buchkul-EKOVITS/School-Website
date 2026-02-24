<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
    header("Location: ../auth/login.php");
    exit();
}
$query = "
SELECT c.id, c.class_name, c.subject, c.room, c.schedule,
COUNT(s.id) AS total_students
FROM classes c
LEFT JOIN students s 
ON LOWER(REPLACE(c.class_name,'Class ','')) = LOWER(s.class)
GROUP BY c.id
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Classes | Bright Future School</title>
<link rel="stylesheet" href="./includes/sidebar-topbar.css">
<link rel="stylesheet" href="classes.css">
</head>
<body>
  <!-- ✅ Sidebar + Topbar Include -->
<?php include './includes/sidebar-topbar.php'; ?>
<!-- ===== Main ===== -->
<div class="main">

  <!-- Page Title -->
  <div class="page-title">
    <h1>My Classes</h1>
    <p>Your assigned classes and schedules</p>
  </div>

  <!-- Class Grid -->
  <div class="class-grid">

<?php while($row = $result->fetch_assoc()) { ?>

  <div class="class-card">
    <div class="card-top">
      <h3><?= $row['class_name'] ?></h3>
      <div class="icon">📘</div>
    </div>

    <p class="subject"><?= $row['subject'] ?></p>

    <ul>
      <li>👥 <?= $row['total_students'] ?> Students</li>
      <li>🏫 <?= $row['room'] ?></li>
      <li>⏰ <?= $row['schedule'] ?></li>
    </ul>

    <div class="btns">
      <a href="students.php?class_id=<?= $row['id'] ?>" class="btn primary">
        View Students
      </a>
      <a href="attendance.php?class_id=<?= $row['id'] ?>" class="btn outline">
        Mark Attendance
      </a>
    </div>
  </div>

<?php } ?>
</body>
</html>
