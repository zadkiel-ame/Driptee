<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 1. REMOVE LOGIC
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $p_id = intval($_GET['id']);
    unset($_SESSION['cart'][$p_id]);
    header("Location: order.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    echo "<script>alert('Your bag is empty!'); window.location.href='index.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$total = 0;


// 2. INTEGRATED FORM PROCESSING (Replaces process_order.php)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);
    
    // CALCULATE TOTAL FIRST
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'];
    }

    // NOW INSERT WITH THE CALCULATED TOTAL
    $stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, address, contact, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, 'Cash on Delivery')");
    $stmt->bind_param("isssd", $user_id, $fullname, $address, $contact, $total);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        // SAVE INDIVIDUAL ITEMS TO DATABASE
        foreach ($_SESSION['cart'] as $item) {
            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, 1, ?)");
            $item_stmt->bind_param("iid", $order_id, $item['id'], $item['price']);
            $item_stmt->execute();

            // Mark product as sold so others can't buy it
            $conn->query("UPDATE products SET status = 'sold' WHERE id = " . $item['id']);
        }

        // CLEAR THE SESSION BAG
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
    <title>Checkout | Driptee</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order.css">
    <style>
        /* The "Smooth Square" Design with Shadow */
        .neo-box-smooth {
            background: white; 
            padding: 20px; 
            border-radius: 15px; /* Semi-roundish corners */
            border: 1.5px solid #1a1a1a; 
            box-shadow: 5px 5px 0px #1a1a1a; /* Hard shadow */
            margin-bottom: 25px;
        }

        /* Sharp Square only for Products */
        .product-img-sharp {
            width: 65px; 
            height: 65px; 
            object-fit: cover; 
            border: 1.5px solid #1a1a1a; 
            border-radius: 0px; /* Sharp square */
            margin-right: 15px;
        }

        .neo-btn-smooth {
            width: 100%; 
            background: #1a1a1a; 
            color: white; 
            padding: 15px; 
            border: none; 
            border-radius: 12px; /* Smooth corners */
            font-weight: 900; 
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 4px 4px 0px #666;
            transition: 0.2s;
        }

        .neo-btn-smooth:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px #666;
        }

        .remove-link {
            color: #ff4444; 
            text-decoration: none; 
            font-size: 11px; 
            font-weight: bold; 
            text-transform: uppercase;
            padding: 5px 10px;
            margin-left: 25px; /* Spaced away */
            border: 1px solid #ff4444;
            border-radius: 4px;
        }

        .remove-link:hover {
            background: #ff4444;
            color: white;
        }

        .input-group input {
            border: 1.5px solid #ddd;
            border-radius: 10px; /* Smooth inputs */
            padding: 12px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="auth-page">
    <div class="order-container">
        <a href="index.php" class="back-link" style="font-weight: 800; font-size: 12px;">← CONTINUE SHOPPING</a>
        
        <div class="logo-area" style="text-align: center; margin-top: 15px; margin-bottom: 25px;">
            <img src="images/logo/logo1.png" style="width: 180px;" alt="logo">
        </div>

        <div class="neo-box-smooth">
            <p style="font-weight: 900; font-size: 14px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">My Bag</p>
            
            <?php foreach ($_SESSION['cart'] as $id => $item): 
                $total += $item['price']; ?>
                
                <div class="cart-item-summary" style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f5f5f5; padding: 12px 0;">
                    <div style="display: flex; align-items: center;">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" class="product-img-sharp">
                        <div>
                            <p style="margin:0; font-weight: 900; font-size: 13px;"><?php echo strtoupper($item['name']); ?></p>
                            <p style="margin:0; font-size: 14px; color: #1a1a1a; font-weight: 600;">₱<?php echo number_format($item['price'], 0); ?></p>
                        </div>
                    </div>
                    
                    <div>
                        <a href="order.php?action=remove&id=<?php echo $id; ?>" class="remove-link">Remove</a>
                    </div>
                </div>

            <?php endforeach; ?>

            <div style="display: flex; justify-content: space-between; margin-top: 20px; font-weight: 900; font-size: 16px;">
                <span style="color: #666;">TOTAL</span>
                <span>₱<?php echo number_format($total, 2); ?></span>
            </div>
        </div>

        <form method="POST">
            <div class="neo-box-smooth">
                <p style="font-weight: 900; margin-bottom: 15px; font-size: 14px; text-transform: uppercase;">Shipping Information</p>
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
            </div class="form-footer">
            <button type="submit" class="neo-btn-smooth">Confirm Order (COD)</button>
        </form>
    </div>
</body>
</html>