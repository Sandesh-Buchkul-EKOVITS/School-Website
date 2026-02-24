<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$email = $_SESSION['email'] ?? 'Principal';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/layouut.css">

<div class="topbar">
    <div class="topbar-left">
        <div class="logo"><i class="fa-solid fa-graduation-cap"></i>
        <span class="school-name">Bright Future School</span>
        </div>
    </div>

    <div class="topbar-right">
        <div class="user-info">
            <span class="email"><?php echo $email; ?></span>
            <span class="role">Principal</span>
        </div>
        <a href="/SCHOOL-WEBSITE/auth/logout.php" class="logout-btn">⎋ Logout</a>
    </div>
</div>