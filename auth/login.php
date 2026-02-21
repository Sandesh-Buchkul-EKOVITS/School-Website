<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../db.php';

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role']; // teacher / principal

    // Security: prepared statement
    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ? AND role = ? LIMIT 1"
    );
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email']   = $user['email'];

            if ($user['role'] === 'teacher') {
                header("Location: .././teacher/dashboard.php");
            } else {
                header("Location: ../principal2/index.php?page=dashboard");
            }
            exit;

        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "Not authorized";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Bright Future School</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">

        <h1 class="school-name">🎓 Bright Future School</h1>
        <h2>Welcome Back</h2>
        <p class="subtitle">Sign in to access your dashboard</p>

        <!-- Error -->
        <?php if ($error != "") { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="post">

            <!-- Role -->
            <input type="hidden" name="role" id="role" value="teacher">

            <div class="role-select">
                <div class="role active" onclick="selectRole('teacher', this)">
                    👤 <span>Teacher</span>
                </div>
                <div class="role" onclick="selectRole('principal', this)">
                    🎓 <span>Principal</span>
                </div>
            </div>

            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <div class="options">
                <label>
                    <input type="checkbox"> Remember me
                </label>
                <a href="#">Forgot password?</a>
            </div>

            <button type="submit" name="login">🔒 Sign In</button>

        </form>

    </div>
</div>

<!-- JS for role select -->
<script>
function selectRole(role, element) {
    document.getElementById('role').value = role;

    document.querySelectorAll('.role').forEach(r => {
        r.classList.remove('active');
    });

    element.classList.add('active');
}
</script>

</body>
</html>