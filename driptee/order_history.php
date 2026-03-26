<?php
session_start();
include("db.php");

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
        }

        .header {
            background: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
            border-bottom: 1px solid #ddd;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: black;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            transition: 0.2s;
        }

        .order-card:hover {
            transform: scale(1.01);
        }

        .order-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .order-id {
            font-size: 14px;
        }

        .order-date {
            font-size: 12px;
            color: gray;
        }

        .order-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .processing {
            background: #ffeaa7;
            color: #2d3436;
        }

        .completed {
            background: #55efc4;
            color: #2d3436;
        }

        .pending {
            background: #ff7675;
            color: white;
        }

        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: black;
            padding: 10px 20px;
            border-radius: 30px;
            display: flex;
            gap: 20px;
        }

        .bottom-nav a {
            color: white;
            text-decoration: none;
            font-size: 12px;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>

<div class="header">ORDER HISTORY</div>

<div class="container">

    <a href="javascript:history.back()" class="back-btn">← BACK</a>

    <?php
    while($row = mysqli_fetch_assoc($result)) {

        $status = $row['order_status'];
        $status_class = '';

        if($status == 'Processing') $status_class = 'processing';
        elseif($status == 'Completed') $status_class = 'completed';
        else $status_class = 'pending';
    ?>

    <div class="order-card">

        <div class="order-top">
            <div class="order-id">Order #<?php echo $row['id']; ?></div>
            <div class="order-date"><?php echo $row['created_at']; ?></div>
        </div>

        <div class="order-details">
            <div class="price">₱<?php echo $row['total_amount']; ?></div>

            <div class="status <?php echo $status_class; ?>">
                <?php echo strtoupper($status); ?>
            </div>
        </div>

    </div>

    <?php } ?>

</div>

<div class="bottom-nav">
    <a href="index.php">HOME</a>
    <a href="#">CATEGORIES</a>
</div>

</body>
</html>