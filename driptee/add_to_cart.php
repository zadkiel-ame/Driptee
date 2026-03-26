<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $p_id = intval($_POST['product_id']);

    // Check if item is available in DB
    $stmt = $conn->prepare("SELECT id, brand, price, image_url, status FROM products WHERE id = ?");
    $stmt->bind_param("i", $p_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product && $product['status'] === 'available') {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // THRIFT LOGIC: If already in bag, don't add again or increment
        if (isset($_SESSION['cart'][$p_id])) {
            echo "<script>alert('This unique item is already in your bag!'); window.location.href='index.php';</script>";
        } else {
            // Save as a unique entry
            $_SESSION['cart'][$p_id] = [
                'id' => $product['id'],
                'name' => $product['brand'],
                'price' => $product['price'],
                'image' => $product['image_url']
            ];
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    } else {
        echo "<script>alert('Sorry, this item was just sold!'); window.location.href='index.php';</script>";
    }
    exit();
}

// REMOVE LOGIC
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $remove_id = intval($_GET['id']);
    unset($_SESSION['cart'][$remove_id]);
    header("Location: order.php");
    exit();
}