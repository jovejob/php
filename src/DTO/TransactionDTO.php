<?php


namespace App\DTO;

class TransactionDTO
{
    public int $customerId;
    public float $amount;
    public ?int $targetCustomerId;

    public function __construct(int $customerId, float $amount, ?int $targetCustomerId = null)
    {
        $this->customerId = $customerId;
        $this->amount = $amount;
        $this->targetCustomerId = $targetCustomerId;
    }
}