<?php

namespace App\Core;

use PDO;

class Database
{
    public PDO $pdo;
    public function __construct(
        private string $driver,
        private string $host,
        private string $dbname,
        private string $user,
        private string $password
    ) 
    {
        $dsn = "$driver:host=$host;dbname=$dbname";  
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}