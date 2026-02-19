<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM orders WHERE id = $order_id");
$order = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Success | Driptee</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/payment.css">
</head>
<body class="auth-page">
    <div class="auth-container" style="text-align: center;">
        
        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="images/logo/logo1.png" style="width: 250px; height: auto;" alt="logo">
        </div>

        
        <div class="success-message">
            <p class="login-prompt">ORDER CONFIRMED!</p>
            <p>Thank you for your purchase, <strong><?php echo explode(' ', $order['fullname'])[0]; ?></strong>!</p>
            <p style="font-size: 14px; color: #666; margin-top: 10px;">Your Order #<?php echo $order['id']; ?> is being prepared.</p>
            
            <div style="margin-top: 30px;">
                <a href="index.php" class="confirm-btn" style="text-decoration: none; display: block;">BACK TO SHOP</a>
            </div>
        </div>
    </div>
</body>
</html>