<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get wishlist items
$query = "SELECT p.* FROM products p 
          JOIN wishlist w ON p.id = w.product_id 
          WHERE w.user_id = ? 
          ORDER BY w.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if product is in wishlist function
function isInWishlist($product_id, $user_id, $conn) {
    $check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    return $check->get_result()->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My Wishlist | Driptee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="overlay"></div>
    <input type="checkbox" id="menu-toggle">

    <header>
        <label for="menu-toggle" class="menu-icon">
            <img src="https://img.icons8.com/material-outlined/24/000000/menu--v1.png" alt="Menu">
        </label>
        <div class="logo">
             <img src="images/logo/logo1.png" alt="logo">
        </div>
        <a href="order.php" class="cart-icon" style="text-decoration: none;">
            <img src="https://img.icons8.com/material-outlined/24/000000/shopping-bag.png" alt="Cart">
            <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
            <?php endif; ?>
        </a>
    </header>

    <div class="promo-banner">MY WISHLIST</div>

    <main class="product-grid">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <img src="<?php echo $row['image_url']; ?>" class="product-image" alt="Clothes">
                
                <div class="product-info">
                    <p class="product-brand"><?php echo strtoupper($row['brand']); ?></p>
                    <p class="product-price">₱<?php echo number_format($row['price'], 0); ?></p>
                </div>
                
                <div style="position: absolute; bottom: 15px; right: 15px; display: flex; gap: 5px;">
                    <form action="add_to_cart.php" method="POST" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="buy-btn" style="background: #000; color: #fff; border: none; padding: 8px 12px; border-radius: 5px; font-weight: 800; cursor: pointer;">
                            +
                        </button>
                    </form>
                    
                    <form action="add_to_wishlist.php" method="POST" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="wishlist-btn" style="background: #fff; color: #000; border: 1px solid #000; padding: 8px 12px; border-radius: 5px; font-weight: 800; cursor: pointer; font-size: 11px;">
                            Remove Favorite
                        </button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1/3; text-align: center; padding: 50px;">Your wishlist is empty.</p>
        <?php endif; ?>
    </main>

    <div class="sidebar">
        <label for="menu-toggle" class="close-btn">×</label>
        <h2 style="margin: 20px 0;">
            <?php echo isset($_SESSION['username']) ? "Hi, " . $_SESSION['username'] : "Menu"; ?>
        </h2>

        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search clothes...">
        </form>
        
        <nav class="side-nav">
            <a href="index.php">Shop All</a>
            <a href="categories.php">Categories</a>
            <a href="wishlist.php" style="color: #e74c3c; font-weight: bold;">My Wishlist</a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="order_history.php">My Orders</a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" style="color: #2ecc71; font-weight: 800;">
                    ADMIN DASHBOARD
                </a>
            <?php endif; ?>
            
                <hr>
                <a href="logout.php" style="color: red;">Logout</a>
            <?php else: ?>
                <hr>
                <a href="login.php" style="font-weight: bold; color: #000;">Login</a>
                <a href="register.php" style="color: #666;">Register Now</a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="tool-bar">
        <a href="index.php">HOME</a>
        <span class="divider">|</span>
        <a href="categories.php">CATEGORIES</a>
    </div>

</body>
</html>
