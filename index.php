<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "fashion_store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getProducts($conn) {
    $query = "SELECT * FROM products";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateProductQuantity($conn, $id, $quantity) {
    $query = "UPDATE products SET quantity = quantity - '$quantity', sold = sold + '$quantity' WHERE id = '$id'";
    $conn->query($query);
}

function recordSale($conn, $product_id, $quantity, $total) {
    $query = "INSERT INTO sales (product_id, sale_date, quantity, total) VALUES ('$product_id', NOW(), '$quantity', '$total')";
    $conn->query($query);
}

function getSales($conn) {
    $query = "SELECT * FROM sales";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$products = getProducts($conn);

if (isset($_POST['buy'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $conn->query($query);
    $product = $result->fetch_assoc();
    $total = $product['price'] * $quantity;
    updateProductQuantity($conn, $product_id, $quantity);
    recordSale($conn, $product_id, $quantity, $total);
    echo "Product purchased successfully!";
}

$sales = getSales($conn);
$total_earnings = 0;
foreach ($sales as $sale) {
    $total_earnings += $sale['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="                
                <li><a href="#">Products</a></li>
                <li><a href="                       
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h1>Welcome to Fashion Store</h1>
            <p>Discover the latest fashion trends</p>
        </section>
        <section class="products">
            <h2>Our Products</h2>
            <div class="product-grid">
                <?php foreach ($products as $product) { ?>
                <div class="product">
                    <h3><?php echo $product['name']; ?></h3>
                    <p>$<?php echo $product['price']; ?></p>
                    <p>Quantity: <?php echo $product['quantity']; ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" value="1">
                        <button type="submit" name="buy">Buy</button>
                    </form>
                </div>
                <?php } ?>
            </div>
        </section>
        <section class="sale-offers">
            <h2>Sales</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Sale Date</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale) { ?>
                    <tr>
                        <td><?php echo $sale['product_id']; ?></td>
                        <td><?php echo $sale['sale_date']; ?></td>
                        <td><?php echo $sale['quantity']; ?></td>
                        <td>$<?php echo $sale['total']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total Earnings:</td>
                        <td>$<?php echo $total_earnings; ?></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Fashion Store</p>
    </footer>
</body>
</html>
