<?php

namespace App\Models;

use App\Database as Database;

class Cart
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get product details by ID from the database.
     * @param int $productId
     * @return array|null
     */
    public function getProductById(int $productId): ?array
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $productId]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get all products from the database.
     * @return array
     */
    public function getAllProducts(): array
    {
        $query = "SELECT * FROM products";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
