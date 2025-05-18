<?php

namespace App\Core;

use PDO;

abstract class Model
{
    public int $id;
    protected $table;
    public function __construct(private Database $database)
    {
    }
    private function getTable(): string
    {
        if($this->table !== null)
        {
            return $this->table;
        }

        $parts = explode("\\", $this::class);
        $parts = strtolower(array_pop($parts)) . 's';
        return $parts;
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
    public function findAll(): array|bool
    {
        $pdo = $this->database->pdo;
        $data = $pdo->query("SELECT * FROM {$this->getTable()}");
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find(int $id): array|bool
    {
        $pdo = $this->database->pdo;
        $data = $pdo->prepare("SELECT * FROM {$this->getTable()} WHERE id = :id");
        $data->execute(['id' => $id]);
        return $data->fetch(PDO::FETCH_ASSOC);
    }
}