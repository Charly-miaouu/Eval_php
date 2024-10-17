<?php

namespace App\Config;

use \PDO;

class Database {
    private $host = 'localhost';
    private $dbname = 'eval';
    private $username = 'root';
    private $password = 'Oublibelot8';
    private $pdo;

    public function __construct() {
        $this->pdo = new \PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection() {
        return $this->pdo;
    }
}
