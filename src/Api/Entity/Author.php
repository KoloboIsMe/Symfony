<?php

namespace App\Api\Entity;

use DateTimeInterface;

class Author
{
    public function __construct(
        private int $id,
        private string $name,
        private ?DateTimeInterface $birth = null,
        private ?DateTimeInterface $death = null
    ){

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function getDeath(): ?\DateTimeInterface
    {
        return $this->death;
    }
}