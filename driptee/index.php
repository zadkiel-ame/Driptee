<?php
require_once 'db.php'; 

// 1. Logic to filter by Category (Added back)
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driptee | Mobile Ukay</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="overlay"></div>
    <input type="checkbox" id="menu-toggle">

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
                <a href="order.php">My Orders</a>

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

    <script>
        const slides = document.querySelectorAll('.hero-bg');
        let currentSlide = 0;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        setInterval(nextSlide, 5000);
    </script>
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
                
                <form action="add_to_cart.php" method="POST" style="position: absolute; bottom: 15px; right: 15px;">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="buy-btn" style="background: #000; color: #fff; border: none; padding: 8px 12px; border-radius: 5px; font-weight: 800; cursor: pointer;">
                        +
                    </button>
                </form>
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
            // Remove active class from current
            slides[currentSlide].classList.remove('active');
            
            // Calculate next slide index
            currentSlide = (currentSlide + 1) % slides.length;
            
            // Add active class to next
            slides[currentSlide].classList.add('active');
        }

        // Change slide every 5 seconds (5000ms)
        setInterval(nextSlide, 5000);
    </script>

</body>
</html>