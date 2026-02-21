<?php
include "../db.php";
include "./includes/header.php"; 

$success = "";
$error = "";

/* HANDLE FORM SUBMIT */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if ($name != "" && $phone != "" && $email != "" && $message != "") {

        // (Later you can add DB or email here)

        header("Location: contact.php?status=success");
        exit();

    } else {

        header("Location: contact.php?status=error");
        exit();
    }
}

/* SHOW MESSAGE AFTER REDIRECT */
if (isset($_GET["status"])) {
    if ($_GET["status"] == "success") {
        $success = "Your message has been sent successfully!";
    }
    if ($_GET["status"] == "error") {
        $error = "Please fill all fields!";
    }
}
?>
<link rel="stylesheet" href="../css/contact.css">
<body>

<!-- HERO -->
<section class="contact-hero">
     <div class="contact-hero-inner">
    <h1>Contact Us</h1>
    <p>Get in touch with us. We're here to help!</p>
    </div>
</section>

<!-- MAIN -->
<section class="contact-container">

    <!-- LEFT FORM -->
    <div class="contact-card form-card">
        <h2>Send us a Message</h2>

        <?php if($success) echo "<p class='success'>$success</p>"; ?>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="">
            <label>Full Name *</label>
            <input type="text" name="name" placeholder="Enter your name">

            <label>Phone Number *</label>
            <input type="text" name="phone" placeholder="+91 1234567890">

            <label>Email Address *</label>
            <input type="email" name="email" placeholder="your.email@example.com">

            <label>Message *</label>
            <textarea name="message" placeholder="Tell us how we can help you..."></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right-column">

    
        <div class="contact-card info-card">
    <h2>Contact Information</h2>

    <div class="info-item">
        <i class="fa-solid fa-map-pin"></i>
        <div>
            <strong>Address</strong>
            <p>Bright Future School<br>123 Education Street<br>City - 400001, India</p>
        </div>
    </div>

    <div class="info-item">
        <i class="fa-solid fa-phone"></i>
        <div>
            <strong>Phone</strong>
            <p>Office: +91 22 1234 5678<br>Admission: +91 22 1234 5679</p>
        </div>
    </div>

    <div class="info-item">
        <i class="fa-solid fa-envelope"></i>
        <div>
            <strong>Email</strong>
            <p>info@brightfuture.edu.in<br>admission@brightfuture.edu.in</p>
        </div>
    </div>

    <div class="info-item">
        <i class="fa-solid fa-clock"></i>
        <div>
            <strong>Office Hours</strong>
            <p>Mon–Fri: 8:00 AM – 4:00 PM<br>Saturday: 8:00 AM – 1:00 PM<br>Sunday: Closed</p>
        </div>
    </div>
</div>


        <div class="contact-card map-card">
            <h2>Location Map</h2>
            <div class="map-placeholder">Map...</div>
        </div>

    </div>
</section>
<?php include "./includes/footer.php"; ?>
