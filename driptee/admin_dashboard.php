<?php
session_start();
include 'db.php';



if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$product_count = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$order_count = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$total_sales = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Driptee</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="background: #fdfdfd;">

    <div class="admin-container">
        <a href="index.php" style="text-decoration:none; color:#000; font-weight:bold;">← BACK</a>

        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="images/logo/logo1.png" style="width: 260px; height: auto;" alt="logo">
        </div>
    
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="margin: 0;">ADMIN PANEL</h2>
            <a href="logout.php"
                style="color: #e74c3c; font-weight: bold; text-decoration: none; font-size: 14px;">LOGOUT</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $product_count; ?></h3>
                <p>Total Products</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $order_count; ?></h3>
                <p>Total Orders</p>
            </div>
            <div class="stat-card">
                <h3>₱<?php echo number_format($total_sales, 2); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>

        <div class="action-bar">
            <a href="add_product.php" class="btn-admin">+ POST NEW ITEM</a>
            <a href="admin_order.php" class="btn-admin btn-secondary">VIEW CUSTOMER ORDERS</a>
        </div>

        <div class="inventory-box">
            <p style="font-weight: bold; margin: 0 0 10px 0;">PRODUCT INVENTORY</p>
            <table>
                <thead>
                    <tr>
                        <th>Brand/Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $products = $conn->query("SELECT * FROM products ORDER BY id DESC");
                    while ($row = $products->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $row['brand']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td>₱<?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="edit-btn">EDIT</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn"
                                    onclick="return confirm('Remove this item?')">REMOVE</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>