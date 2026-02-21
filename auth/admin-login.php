<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND role='admin'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['role'] = 'admin';
            $_SESSION['user_id'] = $user['id'];
            header("Location: register.php");
            exit();
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login | Bright Future School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="admin-login.css">
</head>
<body>
<?php
if (isset($error)) {
    echo "<p style='color:red'>$error</p>";
}
?>
<div class="login-container">
    <div class="login-card">

    <div class="logo">
    <i class="fa-solid fa-graduation-cap"></i>
    <span>Bright Future School</span>
    </div>
        <h2>Welcome Back</h2>
<div class="admin-login-box">
    <h2>Admin Login</h2>
<form method="post">
    <input type="email" name="email" placeholder="Admin Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>
</div>
</div>
</body>
</html>
