<?php
$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
if (!$orderId) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Thank You!</h1>
        <p>Your order (#<?= htmlspecialchars($orderId) ?>) has been placed successfully.</p>
        <a href="index.php">Continue Shopping</a>
    </div>
</body>
</html>
