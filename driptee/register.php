<?php
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            header("Location: login.php?msg=success");
            exit();
        } else {
            $error = "Registration failed. Try again.";
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Driptee_ph</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <a href="index.php" class="back-link">← Back</a>

        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="images/logo/logo1.png" style="width: 200px; height: auto;" alt="logo">
        </div>

        <p class="login-prompt">CREATE AN ACCOUNT</p>

        <?php if($error): ?> 
            <p class="error" style="color: red; font-size: 13px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </p> 
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="username" placeholder="e.g John Doe" required>
            </div>

            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="e.g john@doe.com" required>
            </div>
            
            <div class="input-group">
                <label>Create Password</label>
                <input type="password" name="password" placeholder="***********" required>
            </div>

            <div class="form-footer">
                <button type="submit" class="login-btn">Sign Up</button>
            </div>
        </form>
        
        <p class="register-footer" style="margin-top: 40px;">already have an account? <a href="login.php">login here</a></p>
    </div>
</body>
</html>