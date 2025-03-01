<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    // Prepared statement for security
    $stmt = $conn->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $name, $price, $stock);

    if ($stmt->execute()) {
        echo "Product added successfully! <a href='dashboard.php'>Back</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Add Product</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Price:</label>
        <input type="number" name="price" required><br>
        <label>Stock:</label>
        <input type="number" name="stock" required><br>
        <button type="submit">Add</button>
    </form>
</body>
</html>
