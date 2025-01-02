<?php

require_once __DIR__ . '/../autoload.php';



use App\Controllers\CartController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Read the request payload
    $request = json_decode(file_get_contents("php://input"), true);

    // Validate CSRF token
if (!isset($request['csrf']) || $request['csrf'] !== $_SESSION['csrf_token']) { 
    http_response_code(403);
    echo json_encode(["success" => false, "message" =>"bb "+$_SESSION['csrf_token']]);//
    exit;
}


    $productId =   $request['product_id']; // (int)$_POST['product_id'];
   // $quantity = $request['product_id']; //(int)$_POST['quantity'];

    $quantity = isset($request['quantity']) && is_numeric($request['quantity']) ? intval($request['quantity']) : 1;
    // Validate input
    if (!isset($request['product_id']) || !is_numeric($request['product_id'])) {
        echo json_encode(["success" => false, "message" => "Invalid product ID."]);
        exit;
    }
    if ($quantity < 1)
    {
        echo json_encode(["success" => false, "message" => "Invalid quantity."]);
        exit;
    } 
    $cartController = new CartController();
    $response = $cartController->addToCart($productId, $quantity);
    header("Content-Type: application/json");
   
    
    echo json_encode($response);
}

exit;



use App\Models\Product;
use App\Database;

$productModel = new Product();


header("Content-Type: application/json");

// Read the request payload
$request = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($request['product_id']) || !is_numeric($request['product_id'])) {
    echo json_encode(["success" => false, "message" => "Invalid product ID."]);
    exit;
}

$productId = intval($request['product_id']);
$quantity = isset($request['quantity']) && is_numeric($request['quantity']) ? intval($request['quantity']) : 1;


$product = $productModel->getProductById(array( $productId));

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update the product in the cart
if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$productId] = [
        'id' => $product['id'],
        'name' => $product['name'],
     //   'price' => $product['price'],
        'quantity' => $quantity
    ];
}

echo json_encode(["success" => true, "message" => "Product added to cart."]);
exit;
