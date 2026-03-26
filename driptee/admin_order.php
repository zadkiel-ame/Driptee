<?php
include 'db.php';

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle Status Updates
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    // 1. Update the order status
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        // 2. If cancelled, put products back on the shop
        if ($new_status === 'Cancelled') {
            $items_query = $conn->prepare("SELECT product_id FROM order_items WHERE order_id = ?");
            $items_query->bind_param("i", $order_id);
            $items_query->execute();
            $result = $items_query->get_result();
            
            while ($item = $result->fetch_assoc()) {
                $p_id = $item['product_id'];
                $conn->query("UPDATE products SET status = 'available' WHERE id = $p_id");
            }
        }
        
        // PLACEHOLDER FOR NOTIFICATION FEATURE
        // toggle_notification($order_id, $new_status); 
    }
}

$orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Management | Driptee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="padding: 20px; background: #fdfdfd;">
    <div style="max-width: 650px; margin: auto;">
        <a href="admin_dashboard.php" style="text-decoration:none; color:#000; font-weight:bold;">← BACK</a>

        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="images/logo/logo1.png" style="width: 250px; height: auto;" alt="logo">
        </div>

        <h2 style="text-align: center; margin-bottom: 20px;">CUSTOMER ORDERS</h2>

        <?php while($row = $orders->fetch_assoc()): ?>
            <div style="background:white; border:1.5px solid #1a1a1a; padding:20px; border-radius:12px; margin-bottom:20px; box-shadow: 4px 4px 0px #1a1a1a;">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <p style="font-weight:900; margin:0; font-size: 18px;">Order #<?php echo $row['id']; ?></p>
                        <p style="font-size:13px; color:#1a1a1a; margin-top: 5px;">
                            <strong>Customer:</strong> <?php echo htmlspecialchars($row['fullname']); ?><br>
                            <strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?>
                        </p>
                    </div>
                    <span class="badge <?php echo strtolower($row['order_status']); ?>" 
                          style="padding: 5px 12px; border: 1px solid #1a1a1a; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase;">
                        <?php echo $row['order_status']; ?>
                    </span>
                </div>

                <hr style="border:0; border-top:1px solid #eee; margin:15px 0;">

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <p style="font-weight:900; color:#1a1a1a; margin:0; font-size: 16px;">Total: ₱<?php echo number_format($row['total_amount'], 2); ?></p>
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ddd ">
                        <p style="font-size: 11px; font-weight: bold; color: #666; margin-bottom: 5px; text-transform: uppercase;">Items Ordered:</p>
                        <?php
                        $current_order_id = $row['id'];
                        $items_sql = "SELECT oi.*, p.brand, p.category FROM order_items oi 
                                    JOIN products p ON oi.product_id = p.id 
                                    WHERE oi.order_id = $current_order_id";
                        $items_res = $conn->query($items_sql);
                        
                        while($item = $items_res->fetch_assoc()):
                        ?>
                            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 3px;">
                                <span>• <?php echo htmlspecialchars($item['brand']); ?> (<?php echo htmlspecialchars($item['category']); ?>)</span>
                                <span style="font-weight: bold;">₱<?php echo number_format($item['price'], 2); ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <form method="POST" style="display: flex; gap: 8px;">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="update_status" value="1">
                        
                        <select name="status" onchange="this.form.submit()" 
                                style="padding: 8px; border-radius: 6px; border: 1.5px solid #1a1a1a; font-weight: bold; font-size: 12px; cursor: pointer;">
                            <option value="Pending" <?php if($row['order_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if($row['order_status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Shipped" <?php if($row['order_status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="Delivered" <?php if($row['order_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="Cancelled" <?php if($row['order_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>