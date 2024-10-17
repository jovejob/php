<?php

namespace App\DTO;

use App\Model\Account;

class AccountDTO
{
    public int $customerId;
    public float $balance;
    public string $createdAt;  // Created timestamp
    public string $updatedAt;  // Updated timestamp

    public function __construct(int $customerId, float $balance)
    {
        $this->customerId = $customerId;
        $this->balance = $balance;
    }

    public static function fromEntity(Account $account): self
    {
        $dto = new self(
            customerId: $account->getCustomerId(),
            balance: $account->getBalance()
        );

        // Set the timestamps directly from the Account entity
        $dto->createdAt = $account->created_at;
        $dto->updatedAt = $account->updated_at;

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId,
            'balance' => $this->balance,
            'created_at' => $this->createdAt,  // Include created_at in the array
            'updated_at' => $this->updatedAt   // Include updated_at in the array
        ];
    }
}
