<?php
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driptee_ph | Categories</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="overlay"></div>
    <input type="checkbox" id="menu-toggle">

    

    <header>
        <label for="menu-toggle" class="menu-icon">
            <img src="https://img.icons8.com/material-outlined/24/000000/menu--v1.png" alt="Menu">
        </label>
        <div class="logo">Driptee_ph</div>
        <a href="order.php" class="cart-icon" style="text-decoration: none;">
            <img src="https://img.icons8.com/material-outlined/24/000000/shopping-bag.png" alt="Cart">
            <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
            <?php endif; ?>
        </a>
    </header>

    <div class="promo-banner">SHOP BY CATEGORY</div>

    <main class="cat-page-container">
        
        <a href="index.php?cat=Hoodie" class="cat-item">
            <div class="cat-overlay"></div>
            <img src="images/hoodies/fendi_white.PNG" alt="Hoodies">
            <span>HOODIES</span>
        </a>

        <a href="index.php?cat=Denim Jacket" class="cat-item">
            <div class="cat-overlay"></div>
            <img src="images/denim_jacket/blue_denim.PNG" alt="Denim">
            <span>DENIM JACKETS</span>
        </a>

        <a href="index.php?cat=Pants" class="cat-item">
            <div class="cat-overlay"></div>
            <img src="images/pants/8pocket.PNG" alt="Pants">
            <span>PANTS</span>
        </a>

        <a href="index.php?cat=Shirt" class="cat-item">
            <div class="cat-overlay"></div>
            <img src="images/tshirt/balenciaga.PNG" alt="Shirts">
            <span>SHIRTS</span>
        </a>

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
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="orders.php">My Orders</a>
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
        <a href="categories.php">BROWSE</a>
    </div>

</body>
</html>