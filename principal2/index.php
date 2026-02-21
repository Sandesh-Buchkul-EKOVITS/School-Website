<?php
/* ================= ROUTER ================= */

$page = $_GET['page'] ?? 'dashboard';

/* Allowed pages (security whitelist) */
$allowed = [
    'dashboard',
    'students',
    'teachers',
    'attendance',
    'fees',
    'exams',
    'notice',
    'notices',
    'notice_save',
    'notice_delete',
    'notice_edit',
'notice_update',


    /* STUDENTS */
    'student_form',
    'student_save',
    'student_delete',

    /* TEACHERS */
    'teacher_save',
    'teacher_delete',

    // notice
    'notice',
'notice_form',
'notice_save',
'notice_delete',

'exams',
'exam_form',
'exam_save',
'exam_delete',
'attendance',
'attendance_save',
'attendance_report'




];

/* Validate page */
if (!in_array($page, $allowed)) {
    $page = 'dashboard';
}

/* Map alias (notices -> notice list page) */
if ($page === 'notices') {
    $page = 'notice';
}

/* Absolute server path */
$content = __DIR__ . "/pages/{$page}.php";

/* Fallback if file missing */
if (!file_exists($content)) {
    $content = __DIR__ . "/pages/dashboard.php";
    $page = 'dashboard';
}
?>

<script>

document.addEventListener("click", function(e){

    // OPEN MODAL
    if(e.target.matches("#openNoticeModal")){
        const modal = document.getElementById("noticeModal");
        if(modal){
            modal.classList.add("active");
        }
    }

    // CLOSE BUTTON (X)
    if(e.target.matches(".closeModal")){
        const modal = document.getElementById("noticeModal");
        if(modal){
            modal.classList.remove("active");
        }
    }

    // CLICK OUTSIDE BOX
    if(e.target.classList.contains("modal")){
        e.target.classList.remove("active");
    }

});

</script>

<div class="page">
     <?php if($page == 'dashboard'): ?>
        <link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/style.css">
    <?php endif; ?>

    <!-- Global CSS (absolute path — NO BASE TAG) -->
    <link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/style.css">

    <!-- Page Specific CSS -->
    <?php if($page == 'students'): ?>
        <link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/students.css">
    <?php endif; ?>

    <?php if($page == 'teachers'): ?>
        <link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/teachers.css">
    <?php endif; ?>

    <?php if($page == 'notice'): ?>
      <link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/notices.css">


    <?php endif; ?>

    <?php if($page == 'exams'): ?>
     <link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/exam.css">
     <?php endif; ?>

    <?php if($page =='fees'):?>
    <link rel="stylesheet"href="assets/css/fees.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <?php endif; ?>
        <?php if($page == 'attendance'): ?>
<link rel="stylesheet" href="assets/css/attendance.css">
<?php endif; ?>

           <?php if($page == 'attendance_report'): ?>
<link rel="stylesheet" href="assets/css/attendance.css">
<?php endif; ?>



    <?php if($page == 'enquires'): ?>
      <link rel="stylesheet" href="/SCHOOL-WEBSITE/principal2/assets/css/notices.css">
<?php endif; ?>
</div>

    <title>School CRM</title>




<div class="layout">

    <?php include __DIR__ . "/layout/sidebar.php"; ?>

    <div class="main">

        <?php include __DIR__ . "/layout/header.php"; ?>

        <div class="content">
            <?php include $content; ?>
        </div>

    </div>

</div>


        </div>

    </div>

</div>

<!-- JAVASCRIPT MUST BE BEFORE BODY CLOSE -->
<script>
document.addEventListener("DOMContentLoaded", function(){

    window.addEventListener("click", function(e){

        const modal = document.getElementById("noticeModal");
        if(!modal) return;

        // OPEN MODAL
        if(e.target.closest("#openNoticeModal")){
            modal.classList.add("active");
        }

        // CLOSE BUTTON
        if(e.target.closest(".closeModal")){
            modal.classList.remove("active");
        }

        // CLICK OUTSIDE
        if(e.target === modal){
            modal.classList.remove("active");
        }

    });

});
</script>

</body>
</html>
