<?php
require_once __DIR__ . '/../autoload.php';


//require_once __DIR__ . '/../src/Models/Product.php';
use App\Models\Product;
$productModel = new Product();
echo json_encode($productModel->getAll());
?>
