<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
    header("Location: ../auth/login.php");
    exit();
}
$email = $_SESSION['email'] ?? 'teacher@email.com';
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

    <!-- Card 1 -->
    <div class="class-card">
      <div class="card-top">
        <h3>Class 5-A</h3>
        <div class="icon">📘</div>
      </div>

      <p class="subject">Mathematics</p>

      <ul>
        <li>👥 42 Students</li>
        <li>🏫 Room 101</li>
        <li>⏰ Mon, Wed, Fri – 9:00 AM</li>
      </ul>

      <div class="btns">
        <a href="students.php" class="btn primary">View Students</a>
        <a href="attendance.php" class="btn outline">Mark Attendance</a>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="class-card">
      <div class="card-top">
        <h3>Class 5-B</h3>
        <div class="icon">📘</div>
      </div>

      <p class="subject">Mathematics</p>

      <ul>
        <li>👥 38 Students</li>
        <li>🏫 Room 102</li>
        <li>⏰ Mon, Wed, Fri – 10:00 AM</li>
      </ul>

      <div class="btns">
        <a href="students.php" class="btn primary">View Students</a>
        <a href="attendance.php" class="btn outline">Mark Attendance</a>
      </div>
    </div>

  </div>
</div>

</body>
</html>
