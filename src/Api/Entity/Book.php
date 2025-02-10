<?php

namespace App\Api\Entity;

use DateTimeInterface;

class Book
{
    public function __construct(
        private int $id,
        private string $title,
        private ?array $authors = null,
        private ?DateTimeInterface $release = null
    ){

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    public function getReleaseDate(): ?DateTimeInterface
    {
        return $this->release;
    }
}