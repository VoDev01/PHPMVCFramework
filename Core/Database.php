<?php

namespace App\Core;

use PDO;

class Database
{
    public PDO $pdo;
    public function __construct() 
    {
        $this->pdo = new PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}