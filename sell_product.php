<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Retrieve product info
    $stmt = $conn->prepare("SELECT price, stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($price, $stock);
    $stmt->fetch();
    $stmt->close();

    if ($stock >= $quantity) {
        $total_price = $price * $quantity;

        // Insert sale and update stock
        $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, total_price) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $product_id, $quantity, $total_price);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        echo "Sale successful! <a href='dashboard.php'>Back</a>";
    } else {
        echo "Not enough stock.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sell Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Sell Product</h2>
    <form method="POST">
        <label>Product:</label>
        <select name="product_id">
            <?php
            $result = $conn->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row["id"]}'>{$row["name"]} ({$row["stock"]} left)</option>";
            }
            ?>
        </select><br>
        <label>Quantity:</label>
        <input type="number" name="quantity" required><br>
        <button type="submit">Sell</button>
    </form>
</body>
</html>
