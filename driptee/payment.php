<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) { die("Order not found."); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update = $conn->prepare("UPDATE orders SET payment_status = 'Confirmed', order_status = 'Processing' WHERE id = ?");
    $update->bind_param("i", $order_id);
    if ($update->execute()) {
        header("Location: confirmation.php?id=" . $order_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Review | Driptee</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/payment.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="logo-area">
            <span class="logo-text">Driptee</span>
        </div>
        <p class="login-prompt">PAYMENT REVIEW</p>

        <div class="order-summary">
            <div class="item-row">
                <span>Order Reference:</span>
                <span>#<?php echo $order['id']; ?></span>
            </div>
            <div class="item-row">
                <span>Payment Method:</span>
                <span class="payment-method"><?php echo $order['payment_method']; ?></span>
            </div>
            <div class="total-row">
                <span>TOTAL TO PAY:</span>
                <span>₱<?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
        </div>

        <div class="shipping-review">
            <p><strong>Shipping to:</strong><br>
            <?php echo $order['fullname']; ?><br>
            <?php echo $order['address']; ?><br>
            <?php echo $order['contact']; ?></p>
        </div>

        <form method="POST">
            <button type="submit" class="confirm-btn">CONFIRM ORDER</button>
        </form>
    </div>
</body>
</html>