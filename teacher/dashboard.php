<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
    header("Location: ../auth/login.php");
    exit();
}

include '../db.php';   // ✅ DB connection

// ✅ Logged-in teacher email
$teacher_email = $_SESSION['email'] ?? 'teacher@email.com';

// ===== Today Date =====
$today = date('Y-m-d');

// ===== Classes Today (attendance table) =====
$q1 = mysqli_query($conn,
    "SELECT COUNT(DISTINCT class) as total
     FROM attendance
     WHERE date='$today' AND teacher_email='$teacher_email'"
);
$r1 = mysqli_fetch_assoc($q1);
$classes_today = $r1['total'] ?? 0;

// ===== Total Students =====
$q2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
$r2 = mysqli_fetch_assoc($q2);
$total_students = $r2['total'] ?? 0;

// ===== Notices =====
// Fetch all notices from notices table
$q3 = mysqli_query($conn, "SELECT * FROM notices ORDER BY notice_date DESC");
$notices = [];
if($q3 && mysqli_num_rows($q3) > 0){
    while($row = mysqli_fetch_assoc($q3)){
        $notices[] = $row;
    }
}
$new_notices = count($notices);

// ===== Pending assignments (dummy for now) =====
$pending_assignments = 3;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Teacher Dashboard</title>

<link rel="stylesheet" href="./includes/sidebar-topbar.css">
<link rel="stylesheet" href="dashboard.css">
</head>

<body>

<?php include './includes/sidebar-topbar.php'; ?>

<div class="main">

  <div class="page-title">
    <h1>Teacher Dashboard</h1>
    <p>Welcome back! Here's your schedule for today.</p>
  </div>

  <!-- ===== Cards ===== -->
  <div class="cards">

    <div class="card">
      <div class="icon blue">📘</div>
      <h2><?php echo $classes_today; ?></h2>
      <span>Classes Today</span>
    </div>

    <div class="card">
      <div class="icon green">👨‍🎓</div>
      <h2><?php echo $total_students; ?></h2>
      <span>Total Students</span>
    </div>

    <div class="card">
      <div class="icon orange">📝</div>
      <h2><?php echo $pending_assignments; ?></h2>
      <span>Pending Assignments</span>
    </div>

    <div class="card">
      <div class="icon purple">🔔</div>
      <h2><?php echo $new_notices; ?></h2>
      <span>New Notices</span>
    </div>

  </div>
  <!-- ===== Today's Classes ===== -->
  <div class="section">
    <h3>Today's Classes</h3>

    <div class="class-row">
      <div class="class-info">
        <div class="time">
          9:00 AM – 9:45 AM
          <span class="badge">Mathematics</span>
        </div>
        <div>Class 5-A • Room 101</div>
      </div>
      <a href="attendance.php" class="take-btn">Take Attendance</a>
    </div>

    <div class="class-row">
      <div class="class-info">
        <div class="time">
          2:00 PM – 2:45 PM
          <span class="badge">Mathematics</span>
        </div>
        <div>Class 6-B • Room 104</div>
      </div>
      <a href="attendance.php" class="take-btn">Take Attendance</a>
    </div>

  </div>


  <!-- ===== Latest Notices ===== -->
  <div class="section-notice">
    <h3>Latest Notices</h3>

    <?php if(!empty($notices)): ?>
        <?php foreach($notices as $notice): ?>
        <div class="notice-row">
            <h4><?php echo htmlspecialchars($notice['title']); ?></h4>
            <p><?php echo htmlspecialchars($notice['description']); ?></p>
            <small>Uploaded on: <?php echo date('d M Y', strtotime($notice['notice_date'])); ?></small>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notices uploaded yet.</p>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
