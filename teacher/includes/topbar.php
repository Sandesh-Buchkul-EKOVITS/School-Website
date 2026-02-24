<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}
$email = $_SESSION['email'] ?? 'teacher@email.com';
?>

<link rel="stylesheet" href="../../css/topbar.css">

<div class="topbar">
    <div class="topbar-left">
        <span class="logo">🎓</span>
        <span class="school-name">Bright Future School</span>
    </div>

    <div class="topbar-right">
        <div class="user-info">
            <strong><?php echo $email; ?></strong>
            <span>Teacher</span>
        </div>

        <a href="../auth/logout.php" class="logout-btn">
            ⎋ Logout
        </a>
    </div>
</div>
