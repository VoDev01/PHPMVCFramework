<?php

namespace App\Core;

use PDO;
use App\Core\Exceptions\PageNotFoundException;

abstract class Model
{
    public int $id;
    protected $table;
    public function __construct(protected Database $database)
    {
    }
    private function getTable(): string
    {
        if ($this->table !== null)
        {
            return $this->table;
        }

        $parts = explode("\\", $this::class);
        $parts = strtolower(array_pop($parts)) . 's';
        return $parts;
    }
    public function loadData($data)
    {
        foreach ($data as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->{$key} = $value;
            }
        }
    }
    public function getAll(): array|bool
    {
        $pdo = $this->database->pdo;
        $data = $pdo->query("SELECT * FROM {$this->getTable()}");
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find(int $id): array
    {
        $pdo = $this->database->pdo;
        $data = $pdo->prepare("SELECT * FROM {$this->getTable()} WHERE id = :id");
        $result = $data->execute(['id' => $id]);
        if (!$result)
            throw new PageNotFoundException("Requested resource of model " . get_class($this) . " with id {$id} not found");
        return $data->fetch(PDO::FETCH_ASSOC);
    }
    public function insert(array $data): bool
    {
        $pdo = $this->database->pdo;
        $columns = implode(", ", array_keys($data));
        $params = implode(", ", array_fill(0, count($data), "?"));
        $result = $pdo->prepare("INSERT INTO {$this->getTable()} ($columns) VALUES ($params)");
        $i = 1;
        foreach ($data as $value)
        {
            $type = match (gettype($value))
            {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
            $result->bindValue($i++, $value, $type);
        }
        $this->loadData($data);
        return $result->execute();
    }
    public function update(array $data, string $id): bool
    {
        $pdo = $this->database->pdo;

        $sql = "UPDATE {$this->getTable()} ";

        unset($data['id']);

        $assignments = array_keys($data);

        array_walk($assignments, function (&$value)
        {
            $value = "$value = ?";
        });

        $sql .= "SET " . implode(", ", $assignments);

        $sql .= " WHERE id = ?";

        $result = $pdo->prepare($sql);
        $i = 1;

        foreach ($data as $value)
        {
            $type = match (gettype($value))
            {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
            $result->bindValue($i++, $value, $type);
        }
        $this->loadData($data);

        $result->bindValue($i, $id, PDO::PARAM_INT);

        return $result->execute();
    }
    public function getInsertId(): string
    {
        return $this->database->pdo->lastInsertId();
    }
    public function delete(string $id):bool
    {
        $sql = "DELETE FROM {$this->getTable()} WHERE id = :id";

        $pdo = $this->database->pdo->prepare($sql);

        $pdo->bindValue("id", $id, PDO::PARAM_INT);

        return $pdo->execute();
    }
    public function getTotal(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->getTable()}";

        $pdo = $this->database->pdo->query($sql);

        $row = $pdo->fetch(PDO::FETCH_ASSOC);

        return (int)$row['total'];
    }
}
