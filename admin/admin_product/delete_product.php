<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password if set
$dbname = "gadgets_and_go";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];

    $sql = "DELETE FROM products WHERE id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
</head>
<body>
    <h1>Delete a Product</h1>
    <form method="POST" action="">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>
        <button type="submit">Delete Product</button>
    </form>
</body>
</html>
