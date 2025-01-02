<?php

require_once __DIR__ . '/../autoload.php';

use App\Controllers\CartController;


// Initialize the CartController
$cartController = new CartController();

// Get the current cart data
$cart = $cartController->getCart();

// If the cart is empty, redirect to the cart page
if (empty($cart['items'])) {
    header('Location: cart.php');
    exit;
}

// CSRF token for form submission
//if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
//}
$total = $cart['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>

        <!-- Display cart summary -->
        <h2>Order Summary</h2>
        <ul>
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

    <!-- Checkout Form -->
    <h2>Shipping & Payment Information</h2>
        <form action="process-checkout.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="total" value="<?= $total ?>">

            <h3>Shipping Information</h3>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="address">Shipping Address:</label>
            <textarea id="address" name="address" required></textarea>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>

            <button type="submit">Proceed to Payment</button>
        </form>    </div>
</body>
</html>
