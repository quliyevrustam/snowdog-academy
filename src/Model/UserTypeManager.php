<?php

namespace Snowdog\Academy\Model;

use Snowdog\Academy\Core\Database;

class UserTypeManager
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll(): array
    {
        $query = $this->database->query('SELECT * FROM user_type');

        return $query->fetchAll(Database::FETCH_CLASS);
    }

    public function create(int $id, string $name): int
    {
        $statement = $this->database->prepare('INSERT INTO user_type (id, name) VALUES (:id, :name)');
        $binds = [
            ':id' => $id,
            ':name' => $name,
        ];

        return $statement->execute($binds);
    }
}
