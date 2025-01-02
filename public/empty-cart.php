<?php
require_once __DIR__ . '/../autoload.php';


// Clear the cart
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}
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
        <a href="index.php">Continue Shopping</a>
    </header>

    <main>
        <p>Your cart is empty.</p>
    </main>
</body>
</html>