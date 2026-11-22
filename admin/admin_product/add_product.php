<?php
// Include database connection
include('../connection/connection.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $label = $_POST['label'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $price = $_POST['price'];
    $currency = $_POST['currency'];
    $publish = isset($_POST['publish']) ? 1 : 0;
    
    // Handle image upload
    $imagePath = "";
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "uploads/";
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
    }

    // Insert into database
    $sql = "INSERT INTO products (name, label, category, subcategory, price, currency, image, publish) 
            VALUES ('$title', '$label', '$category', '$subcategory', '$price', '$currency', '$imagePath', '$publish')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product added successfully!'); window.location.href='products.php';</script>";
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
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { display: flex; }
        .sidebar { width: 250px; background: #111; color: white; height: 100vh; padding: 20px; }
        .sidebar a { color: white; display: block; padding: 10px; text-decoration: none; }
        .sidebar a:hover { background: #575757; }
        .main-content { flex-grow: 1; padding: 20px; }
        .create-product-form { background: #f8f9fa; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="/admin/admin_dashboard/dashboard.php">Dashboard</a>
        <a href="#">Orders</a>
        <a href="#">Products</a>
        <a href="#">Customers</a>
        <a href="#">Settings</a>
    </div>
    <div class="main-content">
        <h2>Add Product</h2>
        <form class="create-product-form" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Label</label>
                <input type="text" class="form-control" name="label" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" name="image">
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category" required>
                    <option value="select">Select</option>
                    <option value="Clothes">Clothes</option>
                    <option value="Electronics">Electronics</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Subcategory</label>
                <select class="form-select" name="subcategory" required>
                    <option value="select">Select</option>
                    <option value="Men">Men</option>
                    <option value="Women">Women</option>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Price</label>
                    <input type="number" class="form-control" name="price" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Currency</label>
                    <select class="form-select" name="currency">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="publish">
                <label class="form-check-label">Publish on site</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit Item</button>
        </form>
    </div>
</body>
</html>
