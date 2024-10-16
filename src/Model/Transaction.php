<?php

namespace App\Model;

class Transaction
{
    private string $type;
    private float $amount;
    private int $customerId;

    public function __construct(string $type, float $amount, int $customerId)
    {
        $this->type = $type;
        $this->amount = $amount;
        $this->customerId = $customerId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }
}
