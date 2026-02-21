  <?php
include "../db.php";
include "./includes/header.php"; 
?>
<link rel="stylesheet" href="../css/about.css">
<body>

<!-- HERO -->
<section class="about-hero">
    <h1>About Bright Future School</h1>
    <p>Learn more about our mission, vision, and values</p>
</section>


<!-- MISSION VISION HISTORY -->
<section class="about-cards">

    <div class="about-card">
       <!-- <i class="fa-solid fa-bullseye fa-lg" style="color: #1056d1;"></i> -->
        <i class="fa-solid fa-bullseye fa-xl" style="color: #1558cb;"></i>
        <h3>Our Mission</h3>
        <p>
            To provide quality education that nurtures intellectual curiosity,
            fosters critical thinking, and develops well-rounded individuals
            prepared to contribute positively to society.
        </p>
    </div>

    <div class="about-card">
        <i class="fa-solid fa-eye fa-xl" style="color: #1558cb;"></i>
        <h3>Our Vision</h3>
        <p>
            To be a leading educational institution recognized for academic
            excellence, innovative teaching, and holistic development of students.
        </p>
    </div>

    <div class="about-card">
       <i class="fa-solid fa-book fa-xl" style="color: #1558cb;"></i>
        <h3>Our History</h3>
        <p>
            Since 1990, we have been committed to excellence in education,
            growing from a small school to a premier institution serving
            thousands of students.
        </p>
    </div>

</section>


<!-- PRINCIPAL MESSAGE -->

<section class="principal-section">
    <h2>Principal's Message</h2>

    <div class="principal-box">

        <div class="principal-image">
        <img src="https://images.unsplash.com/photo-1659355894117-0ae6f8f28d0b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxpbmRpYW4lMjBwcmluY2lwYWwlMjBwcm9mZXNzaW9uYWx8ZW58MXx8fHwxNzcwMzg1Njc2fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral" alt="">
            <h4>Dr. Rajesh Kumar</h4>
            <span>Principal</span>
            
        </div>

        <div class="principal-text">

            <p>Dear Students and Parents,</p>

            <p>
                It is my privilege to welcome you to Bright Future School.
                Our institution stands as a beacon of educational excellence,
                where every child has unique talents waiting to be discovered.
            </p>

            <p>
                We focus on developing critical thinking, creativity,
                leadership, and strong moral values. Our dedicated faculty
                and modern infrastructure ensure students receive the best education.
            </p>

            <p>
                Together, let us continue this journey of learning and growth.
            </p>

            <p class="sign">
                Warm regards,<br>
                <strong>Dr. Rajesh Kumar</strong>
            </p>

        </div>

    </div>
</section>
<?php include "./includes/footer.php"; ?>
