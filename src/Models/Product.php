<?php
namespace App\Models;
use  App\Database as Database;
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $query = $this->db->prepare("SELECT * FROM products");
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductsByIds($ids) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = $this->db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $query->execute($ids);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
  
    }
    public function getProductById($id) {
        $query = $this->db->prepare("SELECT id, name FROM products WHERE id = ?");
        $query->execute($id);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
}
?>