<?php
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && $product) {
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE products SET brand=?, price=?, status=? WHERE id=?");
    $stmt->bind_param("sdsi", $brand, $price, $status, $id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <a href="admin_dashboard.php" class="back-link">← Cancel</a>
        <div class="logo-area"><span class="logo-text">DRIPTEE</span></div>
        <form method="POST">
            <div class="input-group"><label>Brand</label><input type="text" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required></div>
            <div class="input-group"><label>Price</label><input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required></div>
            <div class="input-group">
                <label>Status</label>
                <select name="status" style="width:100%; border:none; background:transparent;">
                    <option value="available" <?php if($product['status']=='available') echo 'selected'; ?>>Available</option>
                    <option value="sold" <?php if($product['status']=='sold') echo 'selected'; ?>>Sold Out</option>
                </select>
            </div>
            <button type="submit" class="login-btn">UPDATE</button>
        </form>
    </div>
</body>
</html>