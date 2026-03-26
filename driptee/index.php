<?php
require_once 'db.php'; 

// Fetch user's wishlist ONCE into an array to prevent N+1 query performance issues
$wishlist_array = [];
if (isset($_SESSION['user_id'])) {
    $u_id = intval($_SESSION['user_id']);
    $w_res = $conn->query("SELECT product_id FROM wishlist WHERE user_id = $u_id");
    while ($w_row = $w_res->fetch_assoc()) {
        $wishlist_array[] = $w_row['product_id'];
    }
}

// Notification
$new_drop = null;
$show_notification = false;

$check_query = "SELECT * FROM products 
                WHERE status = 'available' 
                AND created_at >= NOW() - INTERVAL 1 MINUTE 
                ORDER BY id DESC LIMIT 1";
$check_result = mysqli_query($conn, $check_query);

if ($check_result && mysqli_num_rows($check_result) > 0) {
    $new_drop = mysqli_fetch_assoc($check_result);
    
    // Admin will not be notified, User only
    $is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    
    if (!$is_admin && (!isset($_SESSION['last_notified_id']) || $_SESSION['last_notified_id'] != $new_drop['id'])) {
        $show_notification = true;
        $_SESSION['last_notified_id'] = $new_drop['id'];
    }
}

// Logic to filter by Category
$query = "SELECT * FROM products WHERE status = 'available'";
if (isset($_GET['cat'])) {
    $cat = mysqli_real_escape_string($conn, $_GET['cat']);
    $query .= " AND category = '$cat'";
}
$query .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="refresh" content="60">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driptee | Mobile Ukay</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="overlay"></div>
    <input type="checkbox" id="menu-toggle">

    <?php if ($show_notification && $new_drop): ?>
        <div class="drop-popup">
            <img src="<?php echo $new_drop['image_url']; ?>" alt="Drop">
            <div class="drop-info">
                <span class="drop-tag">Just Dropped</span>
                <p><?php echo htmlspecialchars($new_drop['brand']); ?></p>
            </div>
        </div>
    <?php endif; ?>

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

            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="wishlist.php" style="color: #e74c3c; font-weight: bold;">My Wishlist</a>
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

    <header>
        <label for="menu-toggle" class="menu-icon">
            <img src="https://img.icons8.com/material-outlined/24/000000/menu--v1.png" alt="Menu">
        </label>
        <div class="logo">
             <img src="images/logo/logo1.png" alt="logo">
             <span class="logo-text"></span>
        </div>
        <a href="order.php" class="cart-icon" style="text-decoration: none;">
            <img src="https://img.icons8.com/material-outlined/24/000000/shopping-bag.png" alt="Cart">
            <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
            <?php endif; ?>
        </a>
    </header>

    <?php if (!isset($_GET['cat'])): ?>
    <section class="hero-landing">
        <div class="hero-bg active" style="background-image: url('images/background/bg_1.PNG');"></div>
        <div class="hero-bg" style="background-image: url('images/background/bg_2.PNG');"></div>
        <div class="hero-bg" style="background-image: url('images/background/bg_3.PNG');"></div>
        <div class="hero-bg" style="background-image: url('images/background/bg_4.PNG');"></div>
        <div class="hero-bg" style="background-image: url('images/background/bg_5.PNG');"></div>

        <div class="hero-content">
            <div class="logo-area" style="display: flex; justify-content: center; margin-bottom: 20px;">
                <img src="images/logo/logo1.png" style="width: 500px; height: auto;" alt="logo">
            </div>
            <p class="hero-subtitle">Click. Thrift. Slay</p>
            <a href="categories.php" class="shop-now-btn">Shop Now</a>
        </div>
        
        <div style="position: absolute; bottom: 30px; color: white; animation: bounce 2s infinite;">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/expand-arrow--v1.png" width="30" style="opacity: 0.8;">
        </div>
    </section>
    <?php endif; ?>

    <div class="promo-banner">
        ✨ <?php echo isset($_GET['cat']) ? strtoupper($_GET['cat']) : "Forever Discount - All Items 10% Off"; ?> ✨
    </div>

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
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <form action="add_to_wishlist.php" method="POST" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="wishlist-btn" style="background: #fff; color: #000; border: 1px solid #000; padding: 8px 12px; border-radius: 5px; font-weight: 800; cursor: pointer; font-size: 11px;">
                            <?php echo in_array($row['id'], $wishlist_array) ? 'Remove Favorite' : 'Add Favorite'; ?>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>

            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1/3; text-align: center; padding: 50px;">No items found.</p>
        <?php endif; ?>
    </main>

    <div class="tool-bar">
        <a href="index.php">HOME</a>
        <span class="divider">|</span>
        <a href="categories.php">CATEGORIES</a>
    </div>

    <script>
        const slides = document.querySelectorAll('.hero-bg');
        let currentSlide = 0;

        function nextSlide() {
            if(slides.length > 0) {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }
        }
        setInterval(nextSlide, 5000);
    </script>

</body>
</html>