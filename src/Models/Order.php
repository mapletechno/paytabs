<?php
namespace App\Models;
use  App\Database as Database;

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getOrders($userId) {
        $query = $this->db->prepare("
            SELECT orders.*, GROUP_CONCAT(products.name, ' (', order_items.quantity, ')') AS items
            FROM orders
            JOIN order_items ON orders.id = order_items.order_id
            JOIN products ON order_items.product_id = products.id
            WHERE orders.user_id = :userId
            GROUP BY orders.id
        ");
        $query->execute(['userId' => $userId]);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createOrder(string $name, string $email, string $address, string $pickup, array $cart): int
    {
        try {
            $this->db->beginTransaction();
    
            // Insert order details
            $query = "INSERT INTO orders (user_name, user_email, shipping_address, status, created_at) VALUES (:name, :email, :address, 'pending', NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'address' => $pickup === 'pickup' ? null : $address,
            ]);
            $orderId = $this->db->lastInsertId();
    
            // Insert order items
            $query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
            $stmt = $this->db->prepare($query);
   //print_r($cart);
   $x = 0;
            foreach ($cart as $productId => $item) {
                foreach ($item as $product) {
                //    echo $productId['id'];
                print_r($product);
                echo " is the product<br>";
                    $stmt->execute([
                        'order_id' => $orderId,
                        'product_id' => $product['id'],
                        'quantity' => $product['quantity']
                    ]);
                    }
            }
    
            $this->db->commit();
    
            return $orderId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return 0;
        }
    
    
    }
    public function getOrderById($orderId)
    {
        try {

            // Prepare the SQL query
            $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :order_id");
            $stmt->bindParam(':order_id', $orderId, \PDO::PARAM_STR);
            $stmt->execute();

            // Fetch the result as an associative array
            $order = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $order ? (object) $order : null; // Convert to object for consistency
        } catch (\Exception $e) {
            throw new \Exception("Error fetching order by ID: " . $e->getMessage());
        }
    }

    /**
     * Find an order by its ID.
     *
     * @param string $orderId The unique ID of the order.
     * @return object|null Returns the order as an object if found, or null if not found.
     */
    public static function find($orderId)
    {
        try {
            
            // Query to find the order
            
        $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM orders WHERE id = :order_id");
            $stmt->bindParam(':order_id', $orderId, \PDO::PARAM_STR);
            $stmt->execute();

            // Fetch the order as an associative array
            $order = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $order ? (object) $order : null; // Convert array to object if found
        } catch (\Exception $e) {
            throw new \Exception("Error fetching order: " . $e->getMessage());
        }
    }


public static function update($orderId, array $fields)
{
    try {
      
        $db = Database::getInstance()->getConnection();
        // Build the SQL query dynamically based on the fields provided
        $setClause = [];
        foreach ($fields as $key => $value) {
            $setClause[] = "`$key` = :$key";
        }
        $setClause = implode(', ', $setClause);

        $sql = "UPDATE orders SET $setClause WHERE id = :order_id";

        $stmt = $db->prepare($sql);

        // Bind the values dynamically
        foreach ($fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':order_id', $orderId, \PDO::PARAM_STR);

        return $stmt->execute();
    } catch (\Exception $e) {
        throw new \Exception("Error updating order: " . $e->getMessage());
    }
}

    /*    public function createOrder($userId, $items, $status = 'Pending') {
        try {
            $this->db->beginTransaction();
            $orderQuery = $this->db->prepare("INSERT INTO orders (user_id, status) VALUES (:userId, :status)");
            $orderQuery->execute(['userId' => $userId, 'status' => $status]);
            $orderId = $this->db->lastInsertId();

            foreach ($items as $productId => $quantity) {
                $itemQuery = $this->db->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity)
                    VALUES (:orderId, :productId, :quantity)
                ");
                $itemQuery->execute([
                    'orderId' => $orderId,
                    'productId' => $productId,
                    'quantity' => $quantity
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }*/
}
?>
