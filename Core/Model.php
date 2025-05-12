<?php

namespace App\Core;

use PDO;

class Model
{
    public function __construct(private Database $database)
    {
        
    }
    public function loadData($data)
    {
        foreach($data as $key => $value)
        {
            if(property_exists($this, $key))
            {
                $this->{$key} = $value;
            }
        }
    }
    public function getData(): array
    {
        $pdo = $this->database->pdo;
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}