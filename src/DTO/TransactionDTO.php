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


//
//namespace App\DTO;
//
//class TransactionDTO
//{
//    public int $customerId;
//    public float $amount;
//    public string $type; // e.g., 'deposit', 'withdraw', 'transfer'
//    public ?int $targetCustomerId; // Used for transfers
//
//    public function __construct(int $customerId, float $amount, string $type, ?int $targetCustomerId = null)
//    {
//        $this->customerId = $customerId;
//        $this->amount = $amount;
//        $this->type = $type;
//        $this->targetCustomerId = $targetCustomerId;
//    }
//}
