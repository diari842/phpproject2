<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config.php";

// Retrieve the total products and sales
$stmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products");
$stmt->execute();
$stmt->bind_result($total_products);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT SUM(quantity) AS total_sales FROM sales");
$stmt->execute();
$stmt->bind_result($total_sales);
$stmt->fetch();
$stmt->close();

echo "<h2>Welcome, " . $_SESSION["username"] . "!</h2>";
echo "<p>Total Products: $total_products</p>";
echo "<p>Total Sales: $total_sales</p>";
echo "<a href='add_product.php'>Add Product</a> | <a href='sell_product.php'>Sell Product</a> | <a href='logout.php'>Logout</a>";
?>
