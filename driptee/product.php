<?php
include 'db.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand    = trim($_POST['brand']);
    $price    = $_POST['price'];
    $category = $_POST['category'];
    $status   = 'available'; 

    
    $target_dir = "images/";
    $file_name = time() . "_" . basename($_FILES["product_image"]["name"]);
    $target_file = $target_dir . $file_name;

   
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
           
            $stmt = $conn->prepare("INSERT INTO products (brand, price, category, image_url, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsss", $brand, $price, $category, $file_name, $status);
            
            if ($stmt->execute()) {
                $message = "Product successfully added!";
            } else {
                $error = "Error: Could not save to database.";
            }
            $stmt->close();
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "File is not a valid image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Driptee_ph</title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body class="auth-page"> <div class="auth-container"> <a href="index.php" class="back-link">← Back</a>

        <div class="logo">
             <img src="images/logo/logo1.png" style="" alt="logo">
        </div>

        <p class="login-prompt">ADD NEW PRODUCT</p>

        <?php if($message): ?> 
            <p style="color: green; font-size: 13px; margin-bottom: 15px; text-align: center;"><?php echo $message; ?></p> 
        <?php endif; ?>

        <?php if($error): ?> 
            <p style="color: red; font-size: 13px; margin-bottom: 15px; text-align: center;"><?php echo $error; ?></p> 
        <?php endif; ?>

        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Product Brand/Name</label>
                <input type="text" name="brand" placeholder="Gucci" required>
            </div>

            <div class="input-group">
                <label>Category</label>
                <select name="category" style="width: 100%; padding: 12px; border: 1.5px solid #1a1a1a; border-radius: 8px;">
                    <option value="Shirt">Shirt</option>
                    <option value="Pants">Pants</option>
                    <option value="Hoodie">Hoodie</option>
                    <option value="Denim Jacket">Denim Jacket</option>
                </select>
            </div>
            
            <div class="input-group">
                <label>Price (₱)</label>
                <input type="number" name="price" placeholder="100" required>
            </div>

            <div class="input-group">
                <label>Upload Image</label>
                <input type="file" name="product_image" accept="images/*" required>
            </div>

            <div class="form-footer">
                <button type="submit" class="login-btn">Publish Item</button>
            </div>
        </form>
    </div>
</body>
</html>