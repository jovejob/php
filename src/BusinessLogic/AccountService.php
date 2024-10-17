<?php

namespace App\BusinessLogic;

use App\DTO\TransactionDTO;
use App\Model\BalanceAudit;
use App\Repository\CustomerRepository;
use Exception;

class AccountService
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function transfer(TransactionDTO $transactionDTO): bool
    {
        // Ensure targetCustomerId is provided
        if (is_null($transactionDTO->targetCustomerId)) {
            throw new Exception('Target customer is required for a transfer.');
        }

        // Get the balance of the sender's account
        $fromBalance = $this->getAccountBalance($transactionDTO->customerId);

        // Check if the sender has enough funds
        if ($fromBalance < $transactionDTO->amount) {
            throw new Exception('Insufficient funds');
        }

        // Withdraw from the sender's account
        $this->withdraw(new TransactionDTO($transactionDTO->customerId, $transactionDTO->amount));

        // Deposit into the recipient's account
        $this->deposit(new TransactionDTO($transactionDTO->targetCustomerId, $transactionDTO->amount));

        return true;
    }


    public function getAccountBalance(int $customerId): float
    {
        $customer = $this->customerRepository->find($customerId);
        return $customer ? $customer->getBalance() : 0;
    }

    public function withdraw(TransactionDTO $transactionDTO): bool
    {
        // Find the customer by ID
        $customer = $this->customerRepository->find($transactionDTO->customerId);

        // If customer doesn't exist, throw an exception
        if (!$customer) {
            throw new Exception('Customer not found');
        }

        // Check if the balance is insufficient
        if ($customer->getBalance() < $transactionDTO->amount) {
            throw new Exception('Insufficient funds');
        }

        // If balance is sufficient, proceed with the update
        if ($this->updateBalance($transactionDTO->customerId, -$transactionDTO->amount)) {
            BalanceAudit::logTransaction($transactionDTO->customerId, 'withdraw', $transactionDTO->amount);
            return true;
        }

        return false;
    }

    private function updateBalance(int $customerId, float $amount): bool
    {
        $customer = $this->customerRepository->find($customerId);
        if ($customer === null || ($customer->getBalance() + $amount < 0)) {
            return false; // Customer not found or insufficient funds
        }

        $newBalance = $customer->getBalance() + $amount;

        // Use the existing `update` method to update the customer's balance
        $updatedCustomer = $this->customerRepository->update(
            $customerId,
            $customer->getName(),
            $customer->getSurname(),
            $newBalance
        );

        return $updatedCustomer !== null;
    }


    public function deposit(TransactionDTO $transactionDTO): bool
    {
        if ($this->updateBalance($transactionDTO->customerId, $transactionDTO->amount)) {
            BalanceAudit::logTransaction($transactionDTO->customerId, 'deposit', $transactionDTO->amount);
            return true;
        }
        return false;
    }
}
