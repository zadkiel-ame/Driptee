<?php
include 'db.php';
$orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders List</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="padding: 20px; background: #fdfdfd;">
    <div style="max-width: 600px; margin: auto;">
        <a href="admin_dashboard.php" style="text-decoration:none; color:#000; font-weight:bold;">← BACK</a>

        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="images/logo/logo1.png" style="width: 250px; height: auto;" alt="logo">
        </div>
        
        <h2 style="margin: 20px 0;">CUSTOMER ORDERS</h2>

        <?php while($row = $orders->fetch_assoc()): ?>
        <div style="background:#fff; border:1.5px solid #1a1a1a; padding:15px; border-radius:12px; margin-bottom:15px; box-shadow:4px 4px 0px #1a1a1a;">
            <p style="font-weight:bold; margin:0;"><?php echo $row['fullname']; ?></p>
            <p style="font-size:12px; color:#666;"><?php echo $row['address']; ?> | <?php echo $row['contact']; ?></p>
            <hr style="border:0; border-top:1px solid #eee; margin:10px 0;">
            <p style="font-weight:bold; color:#e74c3c; margin:0;">Total Amount: ₱<?php echo number_format($row['total_amount'], 2); ?></p>
            <p style="font-size:10px; margin-top:5px;">Status: <?php echo $row['order_status']; ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>