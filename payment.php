<?php
require_once __DIR__ . '/autoload.php';

// Ensure the iFrame URL is available
if (!isset($_SESSION['iframe_url'])) {
    die('Payment URL not found.');
}

$iframe_url = $_SESSION['iframe_url'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <style>
        iframe {
            width: 100%;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>
    <h1>Secure Payment</h1>
    <iframe src="<?= htmlspecialchars($iframe_url) ?>" frameborder="0"></iframe>
</body>
</html>
