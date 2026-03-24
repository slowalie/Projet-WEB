<?php
declare(strict_types=1);

namespace App\Models;

class Database
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct(string $host, string $username, string $password, string $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
            $this->conn = new \PDO($dsn, $this->username, $this->password);
            // Set the PDO error mode to exception
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        
    }

    public function getConnection(): \PDO
    {
        return $this->conn;
    }
}