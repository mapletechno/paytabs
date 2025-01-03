<?php
require_once __DIR__ . '/autoload.php';

use App\Models\Product;
use App\Controllers\CartController;
$productModel = new Product();


// Initialize the CartController
$cartController = new CartController();

// Get the current cart data
$cart = $cartController->getCart();
$total = $cart['total'];
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Your Shopping Cart</h1>
        <a href="index.php" style="color:red;">Continue Shopping</a>
    </header>

    <main>
    <ul>
        <?php
        // If the cart is empty, redirect to the cart page

        if (!empty($cart['items'])) :

?>
            <?php foreach ($cart['items'] as $item): ?>
                <li>
                    <?= htmlspecialchars($item['name']) ?> 
                    - <?= htmlspecialchars($item['quantity']) ?> 
                    x $<?= htmlspecialchars(number_format($item['price'], 2)) ?> 
                    = $<?= htmlspecialchars(number_format($item['subtotal'], 2)) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total:</strong> $<?= htmlspecialchars(number_format($total, 2)) ?></p>
             <!-- Proceed to Checkout Button -->
             <form action="checkout.php" method="GET">
                    <button type="submit">Proceed to Checkout</button>
                </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>
</body>
</html>