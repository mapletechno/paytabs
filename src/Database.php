<?php
namespace App;

use App\Utils\EnvLoader;

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        EnvLoader::load(__DIR__ . '/../.env');

        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        $this->conn = new \PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
