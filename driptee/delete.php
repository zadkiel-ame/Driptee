<?php
include 'db.php';
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM products WHERE id = $id");
}
header("Location: admin_dashboard.php");
exit();
?>