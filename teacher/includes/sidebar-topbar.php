<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$email = $_SESSION['email'] ?? 'Teacher';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<div class="topbar">
    <div class="topbar-left">
        <div class="logo"><i class="fa-solid fa-graduation-cap"></i>
        <span class="school-name">Bright Future School</span>
        </div>
    </div>

    <div class="topbar-right">
        <div class="user-info">
            <span class="email"><?php echo $email; ?></span>
            <span class="role">Teacher</span>
        </div>
        <a href="../auth/logout.php" class="logout-btn">⎋ Logout</a>
    </div>
</div>

<div class="sidebar">
  <ul>
    <li><a href="./dashboard.php">
        <i class="fa-solid fa-table-columns"></i> Dashboard
    </a></li>

    <li><a href="./classes.php">
        <i class="fa-solid fa-book-open"></i> My Classes
    </a></li>

    <li><a href="./students.php">
        <i class="fa-solid fa-users"></i> Students
    </a></li>

    <li><a href="./attendance.php">
        <i class="fa-solid fa-calendar-check"></i> Attendance
    </a></li>

    <li><a href="./exams.php">
        <i class="fa-solid fa-file-lines"></i> Exams
    </a></li>

    <li><a href="./notices.php">
        <i class="fa-solid fa-bell"></i> Notices
    </a></li>
  </ul>
</div>
