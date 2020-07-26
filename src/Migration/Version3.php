<?php

namespace Snowdog\Academy\Migration;

use Snowdog\Academy\Core\Database;
use Snowdog\Academy\Model\BookManager;

class Version3
{
    private Database $database;
    private BookManager $bookManager;

    public function __construct(Database $database, BookManager $bookManager)
    {
        $this->database = $database;
        $this->bookManager = $bookManager;
    }

    public function __invoke()
    {
        $this->createBooksTable();
        $this->addBooks();
    }

    private function createBooksTable(): void
    {
        $createQuery = <<<SQL
CREATE TABLE `books` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `borrowed` boolean NOT NULL default 0,
  `user_type` tinyint(2) NOT NULL DEFAULT '2' COMMENT '1-child, 2-adult',
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `FK_BOOK_USER_TYPE` (`user_type`),
  CONSTRAINT `FK_BOOK_USER_TYPE` FOREIGN KEY (`user_type`) REFERENCES `user_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function addBooks(): void
    {
        $this->bookManager->create('Harry Potter and the Chamber of Secrets', 'J. K. Rowling', '9780439064873', '1');
        $this->bookManager->create('It: A Novel', 'Stephen King', '9781501142970', '2');
        $this->bookManager->create('The Da Vinci Code', 'Dan Brown', '9780307474278', '2');
        $this->bookManager->create('Wiedźmin. Ostatnie życzenie', 'Andrzej Sapkowski', '9788375780635', '2');
    }
}
