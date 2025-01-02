<?php

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Product;

class CartController
{
    private $cartModel;
    private $productModel;
    private $total;
    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        
    }

    /**
     * Add a product to the cart.
     * @param int $productId
     * @param int $quantity
     * @return array
     */
    public function addToCart(int $productId, int $quantity): array
    {


        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
        //    $_SESSION['cart'][$productId] += $quantity;
        $_SESSION['cart'][$productId]['quantity'] += $quantity;

        } else {

            $product = $this->productModel->getProductById(array( $productId));

            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
        //    $_SESSION['cart'][$productId] = $quantity;
        $_SESSION['cart'][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
         //   'price' => $product['price'],
            'quantity' => $quantity
        ];
        }

        return ['success' => true, 'message' => 'Product added to cart'];
    }

    /**
     * Get the current cart data.
     * @return array
     */
    public function getCart(): array
    {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return ['items' => [], 'total' => 0];
        }
    
        $cart = [];
        $this->total = 0;
    
        foreach ($_SESSION['cart'] as $productId => $item) { // $item is an array
            $product = $this->cartModel->getProductById($productId);
            if ($product) {
                $cart[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $item['quantity'], // Extract quantity from the array
                    'subtotal' => $product['price'] * $item['quantity'], // Correct calculation
                ];
                $this->total += $product['price'] * $item['quantity']; // Accumulate total
            }
        }
    
        return ['items' => $cart, 'total' => $this->total];
    }
    

    /**
     * Empty the cart.
     * @return array
     */
    public function emptyCart(): array
    {
        $_SESSION['cart'] = [];
        return ['success' => true, 'message' => 'Cart emptied successfully'];
    }
}
