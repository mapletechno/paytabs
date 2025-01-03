<?php
require_once __DIR__ . '/autoload.php';

use App\Controllers\OrderController;
use App\Controllers\CartController;
use App\Controllers\PaymentController;
use App\Utils\EnvLoader;

// Load .env file
    try {
        EnvLoader::load(realpath(__DIR__ . '/.env'));
        
    } catch (Exception $e) {
        die('Error loading environment file in process checkout.php: ' . $e->getMessage(). " + " .realpath(__DIR__ . '/../.env'));
    }
// Initialize the CartController
$cartController = new CartController();

// Get the current cart data
$cart = $cartController->getCart();

// get the total
$total = $cart['total'];

// Validate CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

// Validate form inputs
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

$pickup = filter_input(INPUT_POST, 'pickup', FILTER_SANITIZE_FULL_SPECIAL_CHARS );


$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS );


//$total = floatval($_POST['total']);


if (!$name || !$email || (!$address && $pickup !== 'pickup')) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid form inputs']);
    exit;
}

// Get the cart data
//$cart = $_SESSION['cart'] ?? null;

if (empty($cart)) {
    http_response_code(400);
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

/*

// PayTabs credentials from .env
$profile_id = $_ENV['PAYTABS_PROFILE_ID'] ?? null;
$server_key = $_ENV['PAYTABS_SERVER_KEY'] ?? null;
$paytabs_base_url = $_ENV['PAYTABS_BASE_URL'] ?? null;

// Check if the payment credentials are set
if (!$profile_id || !$server_key || !$paytabs_base_url) {
    die('Payment credentials or base URL not found in environment file.');
}

// Build PayTabs endpoints dynamically
$paytabs_request_url = $paytabs_base_url . '/payment/request';
// Cart ID and Order Details
$order_id = uniqid();  // Generate a unique cart ID
$_SESSION['order_id'] = $order_id;

// Prepare PayTabs Payment Payload
$payment_data = [
    'profile_id' => $profile_id,
    'tran_type' => 'sale',
    'tran_class' => 'ecom',
    'cart_id' => $order_id,
    'cart_description' => 'Order Checkout',
    'cart_currency' => 'EGP',
    'cart_amount' => $total,
    'framed' => true,
    'framed_return_top' => true,
    'framed_return_parent' => true,
    'return' => 'https://phpstack-1383605-5136042.cloudwaysapps.com/pytb/public/handle-payment-response.php',
    'customer_details' => [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'country' => $country,
        'address' => $address,
        'city' => $city,
    ],
];

// Send Payment Request to PayTabs
$ch = curl_init($paytabs_request_url); // Use dynamically built request URL
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $server_key,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Get and Parse Response
$response = curl_exec($ch);
curl_close($ch);

//print_r($response);
$response = json_decode($response, true);
echo 'hi';
print_r($response);

*/

// Initialize the PaymentController
$paymentController = new PaymentController();
// Create a payment
// Cart ID and Order Details
$order_id = uniqid();  // Generate a unique cart ID
$orderController = new OrderController();
$orderId = $orderController->createOrder($name, $email, $address, $pickup = false, $cart);
//print_r($orderId);
if (!$orderId) {
    http_response_code(500);
    echo json_encode(['error' => print_r($orderId)]);
    exit;
}
$_SESSION['order_id'] = $orderId;
$customerData = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'street1' => $address,
    'city' => $city,
    'state' => 'CAI', // Cairo for now
    'country' => 'EG', // Egypt for now
    'ip' => $_SERVER['REMOTE_ADDR']
];

$response = $paymentController->createPayment($orderId, $total, $customerData);
//print_r($response);
//exit;
if (isset($response['redirect_url'])) {
    $_SESSION['iframe_url'] = $response['redirect_url'];
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    header('Location: payment.php');
    exit;
} else {
    echo 'Payment initialization failed: ' . $response['message'];
}


exit;
// Process the order
$orderController = new OrderController();
$orderId = $orderController->createOrder($name, $email, $address, $pickup, $cart);

if ($orderId) {
    // Clear the cart after successful order
    unset($_SESSION['cart']);

    // Redirect to a success pagef
    header('Location: success.php?order_id=' . $orderId);
    exit;
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to process the order']);
    exit;
}
