<?php
require_once 'db.php';

$search_query = "";
if (isset($_GET['q'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['q']);
}

// Search by brand or category
$query = "SELECT * FROM products WHERE 
          (brand LIKE '%$search_query%' OR category LIKE '%$search_query%') 
          AND status = 'available' 
          ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Driptee_ph</title>
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

    <div class="promo-banner">
        RESULTS FOR: "<?php echo htmlspecialchars($search_query); ?>"
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
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px 20px;">
            <p style="font-weight: 600; color: #888;">
                Sorry but the shop currently does not have the item you are looking for.
            </p>
        </div>
    <?php endif; ?>
    </main>

    <div class="sidebar">
        <label for="menu-toggle" class="close-btn">×</label>
        <h2 style="margin: 20px 0;">
            <?php echo isset($_SESSION['username']) ? "Hi, " . $_SESSION['username'] : "Menu"; ?>
        </h2>
        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search clothes...">
        </form>
        <nav class="side-nav">
            <a href="index.php">Shop All</a>
            <a href="categories.php">Categories</a>
            <hr>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php" style="color: red;">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
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