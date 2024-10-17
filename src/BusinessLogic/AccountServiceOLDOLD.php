<?php

namespace App\BusinessLogic;

use App\DTO\TransactionDTO;
use App\Repository\CustomerRepository;

class AccountServiceOLDOLD
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function deposit(TransactionDTO $transactionDTO): bool
    {
        $customer = $this->customerRepository->getCustomerById($transactionDTO->customerId);
        if ($customer === null) {
            return false; // Customer not found
        }

        // Add logic to update the customer's balance
        $newBalance = $customer->getBalance() + $transactionDTO->amount;
        $customer->setBalance($newBalance);
        // Update the customer in the repository
        return $this->customerRepository->updateCustomer($customer);
    }

    public function withdraw(TransactionDTO $transactionDTO): bool
    {
        $customer = $this->customerRepository->getCustomerById($transactionDTO->customerId);
        if ($customer === null) {
            return false; // Customer not found
        }

        // Ensure the new balance won't go negative
        if ($customer->getBalance() < $transactionDTO->amount) {
            return false; // Insufficient funds
        }

        $newBalance = $customer->getBalance() - $transactionDTO->amount;
        $customer->setBalance($newBalance);
        // Update the customer in the repository
        return $this->customerRepository->updateCustomer($customer);
    }

    public function transfer(TransactionDTO $transactionDTO): bool
    {
        $fromCustomer = $this->customerRepository->getCustomerById($transactionDTO->customerId);
        $toCustomer = $this->customerRepository->getCustomerById($transactionDTO->targetCustomerId);

        if ($fromCustomer === null || $toCustomer === null) {
            return false; // One or both customers not found
        }

        // Ensure the transfer won't result in a negative balance
        if ($fromCustomer->getBalance() < $transactionDTO->amount) {
            return false; // Insufficient funds
        }

        // Perform the transfer
        $fromCustomer->setBalance($fromCustomer->getBalance() - $transactionDTO->amount);
        $toCustomer->setBalance($toCustomer->getBalance() + $transactionDTO->amount);

        // Update both customers in the repository
        $this->customerRepository->updateCustomer($fromCustomer);
        return $this->customerRepository->updateCustomer($toCustomer);
    }
}
