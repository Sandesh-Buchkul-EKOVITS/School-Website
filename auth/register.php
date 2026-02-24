<?php
session_start();
include '../db.php';

// Only admin can access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    die("Access Denied. Only admin can register teacher/principal.");
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Only allow teacher or principal
    if($role != 'teacher' && $role != 'principal'){
        die("You can only register teacher or principal.");
    }

    // ✅ Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        // Email already registered
        $error = "User already registered! Please login.";
    } else {
        // ✅ Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if($stmt->execute()){
            $success = "User registered successfully!";
        } else {
            $error = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="register.css">
</head>
<body>

<div class="container">
    <div class="logo">
    <i class="fa-solid fa-graduation-cap"></i>
    <span>Bright Future School</span>
    </div>
    <h2>Register Teacher / Principal</h2>

    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(isset($error)){ ?>
    <p class="error"><?php echo $error; ?></p>

    <a href="../auth/login.php" class="login-btn">Go to Login</a>
<?php } ?>


    <form method="POST" action="">
        <label>Name:</label>
        <input type="text" name="name" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <label>Role:</label>
        <select name="role" required>
            <option value="teacher">Teacher</option>
            <option value="principal">Principal</option>
        </select>

        <input type="submit" name="submit" value="Register">
    </form>

    <a href="logout.php">Logout</a>
</div>
</body>
</html>
