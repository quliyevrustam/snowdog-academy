<?php

namespace Snowdog\Academy\Controller\Admin;

use Snowdog\Academy\Component\Helper;
use Snowdog\Academy\Model\Book;
use Snowdog\Academy\Model\BookManager;

class Books extends AdminAbstract
{
    private BookManager $bookManager;
    private ?Book $book;

    private ?int $borrowedDays = null;

    public function __construct(BookManager $bookManager)
    {
        parent::__construct();
        $this->bookManager = $bookManager;
        $this->book = null;
    }

    public function index(): void
    {
        require __DIR__ . '/../../view/admin/books/list.phtml';
    }

    public function newBook(): void
    {
        require __DIR__ . '/../../view/admin/books/edit.phtml';
    }

    public function newBookPost(): void
    {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];

        if (empty($title) || empty($author) || empty($isbn)) {
            $_SESSION['flash'] = 'Missing data';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return;
        }

        $this->bookManager->create($title, $author, $isbn);

        $_SESSION['flash'] = "Book $title by $author saved!";
        header('Location: /admin');
    }

    public function edit(int $id): void
    {
        $book = $this->bookManager->getBookById($id);
        if ($book instanceof Book) {
            $this->book = $book;
            require __DIR__ . '/../../view/admin/books/edit.phtml';
        } else {
            header('HTTP/1.0 404 Not Found');
            require __DIR__ . '/../../view/errors/404.phtml';
        }
    }

    public function editPost(int $id): void
    {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];

        if (empty($title) || empty($author) || empty($isbn)) {
            $_SESSION['flash'] = 'Missing data';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return;
        }

        $this->bookManager->update($id, $title, $author, $isbn);

        $_SESSION['flash'] = "Book $title by $author saved!";
        header('Location: /admin');
    }

    private function getBooks(): array
    {
        return $this->bookManager->getAllBooks();
    }

    /* View "Import Books From CSV" Page */
    public function importFromCsv(): void
    {
        require __DIR__ . '/../../view/admin/books/import_from_csv.phtml';
    }

    /* Import Books From CSV */
    public function importFromCsvPost(): void
    {
        // Validate form value
        if(!file_exists($_FILES['csv_file']['tmp_name']) || !is_readable($_FILES['csv_file']['tmp_name']))
        {
            $_SESSION['flash'] = "Error in csv file Import!";
            header('Location: /admin');
        }

        // Parse CSV file - from file to array
        $books = Helper::parseCsvToArray($_FILES['csv_file']['tmp_name'], '|');

        foreach ($books as $book)
        {
            // Validate parsed book
            if (empty($book["title"]) || empty($book["﻿author"]) || empty($book["isbn"]))
            {
                $_SESSION['flash'] = 'Missing data';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                return;
            }

            if($this->bookManager->create($book["title"], $book["﻿author"], $book["isbn"]))
            {
                $_SESSION['flash'] .= "Book ".$book["title"]." by ".$book["author"]."  saved!".'<br/>';
            }
        }

        header('Location: /admin');
    }

    /* View "Borrowed Book List" Page */
    public function borrowedBooks(): void
    {
        require __DIR__ . '/../../view/admin/books/borrowed.phtml';
    }

    /*
        List of Borrowed Books.
        Property borrowedDays used for filter. By default borrowedDays is null.
        When $borrowedDays is null - will be shown all borrowed books
        When $borrowedDays is not null - table will be filtered by $borrowedDays
    */
    private function getBorrowedBooks(): array
    {
        return $this->bookManager->getBorrowedBooks($this->borrowedDays);
    }

    // Filter Borrowed Books and Reset Filter
    public function borrowedBooksPost(): void
    {
        // Reset Filter
        if(isset($_POST['reset_form']))
        {
            $_SESSION['flash'] = "Filter reset!";
            header('Location: /admin/borrowed_books');
        }

        // Validate Filter
        if(isset($_POST['days']) && !is_numeric($_POST['days']))
        {
            $_SESSION['flash'] = "Wrong filter data!";
            header('Location: /admin/borrowed_books');
        }

        // Updating property $borrowedDays will be used for filter table "Borrowed Books List"
        $this->borrowedDays = $_POST['days'];

        require __DIR__ . '/../../view/admin/books/borrowed.phtml';
    }

    /* Restore Book Details by ISBN */
    public function restoreByIsbn(int $id): void
    {
        // Get Book by Id
        $book = $this->bookManager->getBookById($id);
        if (!($book instanceof Book))
        {
            header('HTTP/1.0 404 Not Found');
            require __DIR__ . '/../../view/errors/404.phtml';
        }

        // Validate ISBN isset
        if (empty($book->getIsbn())) {
            $_SESSION['flash'] = 'Missing data ISBN';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return;
        }

        // Get Book Details by ISBN
        $bookDetail = $book->getBookDetailByIsbn();

        if(isset($bookDetail['error']))
        {
            $_SESSION['flash'] = "Error: ".$bookDetail['error'];
            header('Location: /admin/edit_book/'.$id);
        }

        // Validate Book Detail
        if (empty($bookDetail['title']) || empty($bookDetail['author'])) {
            $_SESSION['flash'] = 'Missing data';
            header('Location: /admin/edit_book/'.$id);
            return;
        }

        $this->bookManager->update($id, $bookDetail['title'], $bookDetail['author'], $book->getIsbn());

        $_SESSION['flash'] = "Book ".$bookDetail['title']." by ".$bookDetail['author']." updated by Open Library Books Database!";
        header('Location: /admin/edit_book/'.$id);
    }
}
