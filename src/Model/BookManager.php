<?php

namespace Snowdog\Academy\Model;

use Snowdog\Academy\Core\Database;

class BookManager
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(string $title, string $author, string $isbn): int
    {
        $statement = $this->database->prepare('INSERT INTO books (title, author, isbn) VALUES (:title, :author, :isbn)');
        $binds = [
            ':title' => $title,
            ':author' => $author,
            ':isbn' => $isbn
        ];
        $statement->execute($binds);

        return (int) $this->database->lastInsertId();
    }

    public function update(int $id, string $title, string $author, string $isbn): void
    {
        $statement = $this->database->prepare('UPDATE books SET title = :title, author = :author, isbn = :isbn WHERE id = :id');
        $binds = [
            ':id' => $id,
            ':title' => $title,
            ':author' => $author,
            ':isbn' => $isbn
        ];

        $statement->execute($binds);
    }

    public function getBookById(int $id)
    {
        $query = $this->database->prepare('SELECT * FROM books WHERE id = :id');
        $query->setFetchMode(Database::FETCH_CLASS, Book::class);
        $query->execute([':id' => $id]);

        return $query->fetch(Database::FETCH_CLASS);
    }

    public function getAllBooks(): array
    {
        $query = $this->database->query('SELECT * FROM books');

        return $query->fetchAll(Database::FETCH_CLASS, Book::class);
    }

    public function getAvailableBooks(): array
    {
        $query = $this->database->query('SELECT * FROM books WHERE borrowed = 0');

        return $query->fetchAll(Database::FETCH_CLASS, Book::class);
    }

    public function getBorrowedBooks(?int $days): array
    {
        $sqlPart = '';
        if(!empty($days)) $sqlPart = " AND br.`borrowed_at` < DATE_SUB(NOW(), INTERVAL ".$days." DAY)";

        $query = $this->database->query("
            SELECT 
                b.`id`, b.`title`, b.`author`, b.`isbn`, br.`borrowed_at`
            FROM 
                books b LEFT JOIN `borrows` br ON b.`id` = br.`book_id` 
            WHERE 
                borrowed = 1 $sqlPart
	    ");

        return $query->fetchAll(Database::FETCH_CLASS, Book::class);
    }
}
