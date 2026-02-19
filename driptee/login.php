<?php
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Driptee_ph</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <a href="index.php" class="back-link">← Back</a>

        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="images/logo/logo1.png" style="width: 200px; height: auto;" alt="logo">
        </div>

        <p class="login-prompt">LOGIN TO CONTINUE</p>

        <?php if($error): ?> 
            <p class="error" style="color: red; font-size: 13px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </p> 
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@gmail.com" required>
            </div>
            
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="***********" required>
            </div>

            <div class="form-footer">
                <button type="submit" class="login-btn">Login</button>
            </div>
        </form>

        <p class="register-footer" style="margin-top: 40px;">haven't an account yet? <a href="register.php">register now</a></p>
    </div>
</body>
</html>