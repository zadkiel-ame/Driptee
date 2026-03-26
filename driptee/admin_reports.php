<?php
include 'db.php';

// Security Check: Admin Only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Data Retrieval
$query = "SELECT 
            p.category, 
            SUM(oi.quantity) AS total_items_sold, 
            SUM(oi.quantity * oi.price) AS category_revenue
          FROM order_items oi
          JOIN products p ON oi.product_id = p.id
          JOIN orders o ON oi.order_id = o.id
          WHERE o.order_status != 'Cancelled'
          GROUP BY p.category
          ORDER BY category_revenue DESC";

$report_result = $conn->query($query);

// Initialize Grand Total variable
$grand_total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Reports | Driptee Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: #fdfdfd;">

    <div class="admin-container">
        <a href="admin_dashboard.php" style="text-decoration:none; color:#000; font-weight:bold;">← BACK TO DASHBOARD</a>

        <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="images/logo/logo1.png" style="width: 250px; height: auto;" alt="logo">
        </div>

        <h2 style="text-align: center; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px;">Category Sales Analysis</h2>

        <div class="inventory-box">
            <p style="font-weight: bold; margin: 0 0 10px 0;">REVENUE SUMMARY BY CATEGORY</p>
            <table>
                <thead>
                    <tr>
                        <th>Clothing Category</th>
                        <th>Units Sold</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $report_result->fetch_assoc()): 
                        // Add each row's revenue to the grand total
                        $grand_total += $row['category_revenue'];
                    ?>
                        <tr>
                            <td style="font-weight: bold;"><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo number_format($row['total_items_sold']); ?> pcs</td>
                            <td style="font-weight: 800; color: #1a1a1a;">
                                ₱<?php echo number_format($row['category_revenue'], 2); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f9f9f9; border-top: 2px solid #1a1a1a;">
                        <td colspan="2" style="text-align: right; font-weight: bold; padding: 15px;">GRAND TOTAL REVENUE:</td>
                        <td style="padding: 15px; font-size: 18px; font-weight: 900; color: #2ecc71;">
                            ₱<?php echo number_format($grand_total, 2); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

</body>
</html>