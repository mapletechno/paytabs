<?php
namespace App\Models;

use App\Database as Database;

class Payment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPaymentById(string $id)
    {
        $query = $this->db->prepare("SELECT * FROM payments WHERE id = ?");
        $query->execute([$id]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPaymentsByUserId($userId)
    {
        $query = $this->db->prepare("SELECT * FROM payments WHERE user_id = ?");
        $query->execute($userId);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createPayment($userId, $paymentStatus, $order_id)
    {
        $query = $this->db->prepare("INSERT INTO payments (user_email, status, order_id) VALUES (?, ?, ?)");
        $query->execute([$userId, $paymentStatus, $order_id]);
        return $this->getPaymentById($this->db->lastInsertId());
    }
}