<?php

namespace App\DTO;

class CustomerDTO
{
    public string $name;
    public string $surname;
    public float $balance;

    public function __construct(string $name, string $surname, float $balance)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->balance = $balance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}

