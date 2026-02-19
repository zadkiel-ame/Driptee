<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $p_id = intval($_POST['product_id']);

    // Fetch product details from DB
    $stmt = $conn->prepare("SELECT id, brand, price, image_url FROM products WHERE id = ?");
    $stmt->bind_param("i", $p_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();

    if ($product) {
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If item exists, increase quantity; otherwise, add new
        if (isset($_SESSION['cart'][$p_id])) {
            $_SESSION['cart'][$p_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$p_id] = [
                'id' => $product['id'],
                'name' => $product['brand'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => 1
            ];
        }
    }
    // Go back to the page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}