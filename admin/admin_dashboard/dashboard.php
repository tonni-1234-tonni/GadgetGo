
<?php
include '../../connection/connection.php';

// Fetch Total Sales
$total_sales_query = "SELECT SUM(amount) AS total_sales FROM orders";
$total_sales_result = $conn->query($total_sales_query);
$total_sales = ($total_sales_result->num_rows > 0) ? $total_sales_result->fetch_assoc()['total_sales'] : 0;

// Fetch Total Orders
$total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = ($total_orders_result->num_rows > 0) ? $total_orders_result->fetch_assoc()['total_orders'] : 0;

// Fetch Total Products
$total_products_query = "SELECT COUNT(*) AS total_products FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = ($total_products_result->num_rows > 0) ? $total_products_result->fetch_assoc()['total_products'] : 0;

// Fetch Latest Orders
$latest_orders_query = "SELECT * FROM orders ORDER BY date DESC LIMIT 6";
$latest_orders_result = $conn->query($latest_orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
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
        .dashboard-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .dashboard-stats .stat {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            width: 30%;
            text-align: center;
        }

        .latest-orders table {
            width: 100%;
        }
        .latest-orders table th, .latest-orders table td {
            padding: 10px;
            text-align: left;
        }
        .delivered {
            background-color: #d4f8d4;
            color: #2d8a2d;
            text-align: center;
        }
    
        .cancelled {
            background-color: #f8d4d4;
            color: #8a2d2d;
            text-align: center;
        }
        .pending {
            background-color: #f8e4b4;
            color: #8a6f2d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="#">Dashboard</a>
        <a class="nav-link" href="/admin/order/order.php">Orders</a>
        <a class="nav-link" href="/admin/admin_product/product.php">Products</a>
        <a class="nav-link" href="/admin/customer/customer.php">Customers</a>
        <a class="nav-link" href="/admin/settings/settings.php">Settings</a>
    </div>
    <div class="main-content">
        <h2>Dashboard</h2>
        <div class="dashboard-stats">
            <div class="stat">
                <h3>Total Sales</h3>
                <p>$<?php echo number_format($total_sales, 2); ?></p>
            </div>
            <div class="stat">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>
            <div class="stat">
                <h3>Total Products</h3>
                <p><?php echo $total_products; ?></p>
            </div>
        </div>
       
    </div>
</body>
</html>
