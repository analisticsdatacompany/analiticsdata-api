<?php

namespace App\Dao;

use PDO;
use PDOException;

class DB {
    private static ?DB $instance = null;
    private PDO $pdo;

    // Construtor privado para impedir instâncias externas
    private function __construct() {
        $config = require __DIR__ . '/../config.php';

        $host    = $config['host'];
        $port    = $config['port'];
        $dbname  = $config['dbname'];
        $user    = $config['user'];
        $pass    = $config['pass'];
        $charset = $config['charset'];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    // Impede clonagem da instância
    private function __clone() {}

    // Impede desserialização
    public function __wakeup() {
        throw new \Exception("Não é possível desserializar uma instância de Singleton.");
    }

    // Método de acesso global à instância
    public static function getInstance(): DB {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Método para obter o PDO
    public function getConnection(): PDO {
        return $this->pdo;
    }
}
