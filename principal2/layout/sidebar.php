<?php
$current = $_GET['page'] ?? 'dashboard';
?>

<div class="sidebar">

<ul class="menu">

    <li class="<?= ($current=='dashboard') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=dashboard">📊 <span>Dashboard</span></a>
    </li>

    <li class="<?= ($current=='students') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=students">👨‍🎓 <span>Students</span></a>
    </li>

    <li class="<?= ($current=='teachers') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=teachers">👩‍🏫 <span>Teachers</span></a>
    </li>

    <li class="<?= ($current=='attendance') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=attendance">📅 <span>Attendance</span></a>
      

    </li>

    <li class="<?= ($current=='fees') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=fees">💰 <span>Fees</span></a>
    </li>

    <li class="<?= ($current=='exams') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=exams">📝 <span>Exams</span></a>
    </li>

    <li class="<?= ($current=='notice') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=notice">🔔 <span>Notices</span></a>
    </li>

      <li class="<?= ($current=='enquiries') ? 'active' : '' ?>">
        <a href="/SCHOOL-WEBSITE/principal2/index.php?page=enquiries">🔔 <span>enquiries</span></a>
    </li>

</ul>

</div>
