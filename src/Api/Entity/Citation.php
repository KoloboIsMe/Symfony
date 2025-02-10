<?php

namespace App\Api\Entity;

class Citation
{
    public function __construct(
        private int $id,
        private string $text,
        private ?Author $author = null,
        private ?Book $book = null
    ){

    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

}