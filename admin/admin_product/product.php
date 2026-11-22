<?php
// Include database connection
include('../connection/connection.php');

// Fetch products from database
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #111;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #575757;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a class="nav-link" href="/admin/admin_dashboard/dashboard.php">Dashboard</a>
        <a class="nav-link" href="/admin/order/order.php">Orders</a>
        <a href="#">Products</a>
        <a class="nav-link" href="/admin/customer/customer.php">Customers</a>
        <a class="nav-link" href="/admin/settings/settings.php">Settings</a>
    </div>
    
    <div class="main-content">
        <div class="d-flex justify-content-between">
            <input type="text" class="form-control w-25" placeholder="Search for Product">
            <select class="form-select w-25">
                <option>All Categories</option>
            </select>
            <select class="form-select w-25">
                <option>All Products</option>
            </select>
            <button class="btn btn-success"><a class="nav-link text-white" href="/admin/admin_product/add_product.php">Add Product</a></button>
            <button class="btn btn-danger"><a class="nav-link text-white" href="/admin/admin_product/delete_product.php">Delete Product</a></button>
        </div>
        
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Stock</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <?php 
                                if ($row['status'] == 'Available') {
                                    echo '<span class="text-success">Available</span>';
                                } else {
                                    echo '<span class="text-danger">Disabled</span>';
                                }
                            ?>
                        </td>
                        <td class="<?php echo ($row['stock'] <= 5) ? 'text-danger' : ''; ?>">
                            <?php echo $row['stock']; ?>
                        </td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
