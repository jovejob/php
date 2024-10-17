<?php

namespace App\DTO;

use App\Model\Customer;

class CustomerDTO
{
    public string $created_at;
    public string $updated_at;
    private string $name;
    private string $surname;
    private float $balance;

    public function __construct(string $name, string $surname, float $balance)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->balance = $balance;
    }

    public static function fromEntity(Customer $customer): array
    {
        return [
            'id' => $customer->getKey(),
            'name' => $customer->getName(),
            'surname' => $customer->getSurname(),
            'balance' => (float)$customer->getBalance(),
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ];
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

    public static function toArray(Customer $customer): array
    {
        return [
            'id' => $customer->getKey(),
            'name' => $customer->getName(),
            'surname' => $customer->getSurname(),
            'balance' => (float)$customer->getBalance(),
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ];
    }

    public static function fromEntitySelf(Customer $customer): self
    {
        $dto = new self(
            name: $customer->getName(),
            surname: $customer->getSurname(),
            balance: $customer->getBalance()
        );

        // Set the timestamps from the Customer entity
        $dto->created_at = $customer->created_at;
        $dto->updated_at = $customer->updated_at;

        return $dto;
    }

    public function toArrayConversion(): array
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'balance' => $this->balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}