<?php
require_once __DIR__ . '/../autoload.php';
use App\Models\Product;
//if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
//}

$productModel = new Product();
$products = $productModel->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Welcome to Our Store</h1>
    </header>
    <main>
        <div id="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>Price: $<?= number_format($product['price'], 2) ?></p>
                    <label for="quantity-<?= $product['id'] ?>">Quantity:</label>
                    <input type="number" id="quantity-<?= $product['id'] ?>" min="1" value="1" class="quantity-input" 
                    data-product-id="<?= $product['id'] ?>">
                      <button class="add-to-cart" data-csrf="<?= $_SESSION['csrf_token']?>" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
         <!-- Proceed to Checkout Button -->
         <form action="cart.php" method="GET">
                    <button id="proceed-to-cart" style="display: none; margin: 20px auto; background-color: red; padding: 15px 30px; font-size: 18px;" type="submit">Proceed to Cart</button>
                </form>
    <script src="js/cart.js?v=1.5.2"></script>
</body>
</html>
