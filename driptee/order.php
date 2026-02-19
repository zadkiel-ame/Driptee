<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- QUANTITY LOGIC ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $p_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$p_id])) {
        if ($_GET['action'] == 'add') {
            $_SESSION['cart'][$p_id]['quantity'] += 1;
        } elseif ($_GET['action'] == 'minus') {
            if ($_SESSION['cart'][$p_id]['quantity'] > 1) {
                $_SESSION['cart'][$p_id]['quantity'] -= 1;
            } else {
                unset($_SESSION['cart'][$p_id]);
            }
        } elseif ($_GET['action'] == 'remove') {
            unset($_SESSION['cart'][$p_id]);
        }
    }
    header("Location: order.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your bag is empty!'); window.location.href='index.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// --- FORM SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);
    $payment_method = "Cash on Delivery";

    $stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, address, contact, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssds", $user_id, $fullname, $address, $contact, $total, $payment_method);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        foreach ($_SESSION['cart'] as $item) {
            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $item_stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $item_stmt->execute();
        }
        unset($_SESSION['cart']);
        header("Location: payment.php?order_id=" . $order_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Driptee</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <a href="index.php" class="back-link">← Continue Shopping</a>
        <p class="login-prompt">CHECKOUT SUMMARY</p>

        <div class="order-summary" style="margin-bottom: 20px;">
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div class="cart-item-summary" style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 10px 0;">
                    <div style="display: flex; align-items: center;">
                        <img src="<?php echo $item['image']; ?>" class="summary-img">
                        <div>
                            <p style="margin:0; font-weight: bold; font-size: 13px;"><?php echo strtoupper($item['name']); ?></p>
                            <p style="margin:0; font-size: 12px; color: #666;">₱<?php echo number_format($item['price'], 0); ?></p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <a href="order.php?action=minus&id=<?php echo $item['id']; ?>" style="text-decoration:none; color:#000; background:#f0f0f0; width:22px; height:22px; display:flex; justify-content:center; align-items:center; border-radius:4px; font-weight:bold;">-</a>
                        <span style="font-size: 14px; font-weight: bold;"><?php echo $item['quantity']; ?></span>
                        <a href="order.php?action=add&id=<?php echo $item['id']; ?>" style="text-decoration:none; color:#000; background:#f0f0f0; width:22px; height:22px; display:flex; justify-content:center; align-items:center; border-radius:4px; font-weight:bold;">+</a>
                        <a href="order.php?action=remove&id=<?php echo $item['id']; ?>" style="margin-left:5px; color:red; text-decoration:none; font-size:16px;">remove</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div style="display: flex; justify-content: space-between; margin-top: 15px; font-weight: 900;">
                <span>TOTAL AMOUNT:</span>
                <span>₱<?php echo number_format($total, 2); ?></span>
            </div>
        </div>

        <form method="POST">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="fullname" placeholder="Recipient Name" required>
            </div>
            <div class="input-group">
                <label>Complete Address</label>
                <input type="text" name="address" placeholder="Street, City, Province" required>
            </div>
            <div class="input-group">
                <label>Contact Number</label>
                <input type="text" name="contact" placeholder="09XXXXXXXXX" required>
            </div>
            <div class="form-footer">
                <button type="submit" class="login-btn">Place Order (COD)</button>
            </div>
        </form>
    </div>
</body>
</html>