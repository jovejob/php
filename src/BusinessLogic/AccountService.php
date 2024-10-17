<?php

namespace App\BusinessLogic;

use App\Repository\CustomerRepository;
use InvalidArgumentException;

class AccountService
{
    private CustomerRepository $repository;

    // Accept the CustomerRepository as a dependency
    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository; // Initialize with the passed repository
    }

    public function getAccountBalance(int $customerId): float
    {
        $customer = $this->repository->find($customerId);
        if (!$customer) {
            throw new InvalidArgumentException('Customer not found.');
        }
        return $customer->getBalance();
    }

    public function deposit(int $customerId, float $funds): void
    {
        $customer = $this->repository->find($customerId);
        if (!$customer) {
            throw new InvalidArgumentException('Customer not found.');
        }
        $customer->setBalance($customer->getBalance() + $funds);
    }

    public function withdraw(int $customerId, float $funds): void
    {
        $customer = $this->repository->find($customerId);
        if (!$customer) {
            throw new InvalidArgumentException('Customer not found.');
        }

        if ($customer->getBalance() < $funds) {
            throw new InvalidArgumentException('Insufficient balance.');
        }
        $customer->setBalance($customer->getBalance() - $funds);
    }

    public function transfer(int $fromId, int $toId, float $funds): void
    {
        $fromCustomer = $this->repository->find($fromId);
        $toCustomer = $this->repository->find($toId);

        if (!$fromCustomer || !$toCustomer) {
            throw new InvalidArgumentException('Customer not found.');
        }

        if ($fromCustomer->getBalance() < $funds) {
            throw new InvalidArgumentException('Insufficient balance for transfer.');
        }

        // Perform the transfer
        $fromCustomer->setBalance($fromCustomer->getBalance() - $funds);
        $toCustomer->setBalance($toCustomer->getBalance() + $funds);
    }
}
