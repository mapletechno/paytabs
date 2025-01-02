<?php
namespace App\Controllers;
use App\Models\Order;
use App\Utils\EnvLoader;

class OrderController {
    private $orderModel;
    private $server_key;

    public function __construct() {
        EnvLoader::load(__DIR__ . '/../../.env');

        $this->orderModel = new Order();
        $this->server_key = $_ENV['PAYTABS_SERVER_KEY'];
    }




    /**
     * Get a list of orders for a specific user.
     * @param int $userId
     * @return array
     */
    public function listOrders($userId) {
        return $this->orderModel->getOrders($userId);
    }
    /**
        * Create a new order.
        * @param string $name
        * @param string $email
        * @param string $address
        * @param string $pickup 
        * @return int
        */
    public function createOrder(string $name, string $email, string $address, string $pickup, array $cart): int
    {
        $orderId = $this->orderModel->createOrder($name, $email, $address, $pickup, $cart);
        return $orderId;
    }
    

    /**
     * Get details of a specific order.
     * @param int $orderId
     * @return array
     */
    public function getOrderDetails(int $orderId): array
    {
        return $this->orderModel->getOrderById($orderId);
    }


     /**
     * Mark an order as paid.
     *
     * @param string $orderId The unique ID of the order.
     * @param string $transactionId The payment transaction ID.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function markOrderAsPaid($orderId, $transactionId)
    {
        // Find the order
        $order = Order::find($orderId);

        if (!$order) {
            throw new \Exception("Order not found: $orderId");
        }

        // Update the order status and transaction ID
        $updateSuccess = Order::update($orderId, [
            'status' => 'paid',
            'transaction_id' => $transactionId,
            'payment_date' => date('Y-m-d H:i:s'),
        ]);

        if (!$updateSuccess) {
            throw new \Exception("Failed to update order as paid for order ID: $orderId");
        }

        return true;
    }

     /**
     * Mark an order as failed.
     *
     * @param string $orderId The unique ID of the order.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function markOrderAsFailed($orderId)
    {
        // Find the order
        $order = Order::find($orderId);

        if (!$order) {
            throw new \Exception("Order not found: $orderId");
        }

        // Update the order status to failed
        $updateSuccess = Order::update($orderId, [
            'status' => 'failed',
            'failure_reason' => 'Payment failed',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$updateSuccess) {
            throw new \Exception("Failed to update order as failed for order ID: $orderId");
        }

        return true;
    }

}
?>
