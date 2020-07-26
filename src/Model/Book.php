<?php

namespace Snowdog\Academy\Model;

use DateInterval;
use DateTime;
use Snowdog\Academy\Component\Helper;

class Book
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    private int $id;
    private string $title;
    private string $author;
    private string $isbn;
    private bool $borrowed;
    private ?string $borrowed_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function isBorrowed(): bool
    {
        return $this->borrowed;
    }

    public function getReturnTime(): string
    {
        $dateTime = DateTime::createFromFormat(self::DATETIME_FORMAT, $this->borrowed_at);
        $dateTime->add(new DateInterval('P14D'));

        return $dateTime->format(self::DATETIME_FORMAT);
    }

    public function getBorrowedTime(): string
    {
        return $this->borrowed_at;
    }

    // Get Book Details by ISBN
    public function getBookDetailByIsbn(): array
    {
        $return = [
            'title' => '',
            'author' => '',
        ];

        // Get Book Detail from Open Library Book API
        $key = 'ISBN:'.$this->getIsbn();
        $url = 'http://openlibrary.org/api/books?bibkeys='.$key.'&jscmd=data&format=json';
        $result = Helper::sendCurlRequest($url);

        // Get title
        if(isset($result[$key]['title'])) $return['title'] = $result[$key]['title'];

        // Get Author
        if(isset($result[$key]['authors']))
        {
            $return['author'] =
                implode (', ',
                    array_map (function ($v){ return $v['name']; }, $result[$key]['authors'])
                );
        }

        return $return;
    }
}
