<?php
require_once __DIR__ . '/../autoload.php';

use App\Controllers\OrderController;
use App\Controllers\PaymentController;

// Check for the payment response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   // print_r($_SESSION);
//print_r($_POST);
//echo "<br /><br />";
 // Process the payment response
 $payment = new PaymentController();
if($payment->is_valid_redirect($_POST) === true) :
    echo "Valid Redirect";

//exit;
    $paymentStatus = $_POST['respStatus'] ?? null;
    $orderId = $_POST['cartId'] ?? null;
    $transactionId = $_POST['tranRef'] ?? null;

    // Validate the required fields
    if (!$paymentStatus || !$orderId || !$transactionId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid payment response']);
        exit;
    }

    // Process the payment response
    $orderController = new OrderController();

    if ($paymentStatus === 'A') {
        $payment->savePayment($_POST['customerEmail'], $orderId, $paymentStatus);
        // Mark the order as paid
        $orderController->markOrderAsPaid($orderId, $transactionId);

        // Redirect to a success page
        header('Location: success.php?order_id=' . urlencode($orderId));
        exit;
    } elseif ($paymentStatus === 'failed') {
        // Mark the order as failed
        $orderController->markOrderAsFailed($orderId);

        // Redirect to a failure page
        header('Location: failure.php?order_id=' . urlencode($orderId));
        exit;
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown payment status']);
        exit;
    }
else:
    echo "Invalid Redirect";

    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
    exit;
endif;
}