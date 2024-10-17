<?php

namespace App\Service;

use App\Repository\CustomerRepository;
use Exception;

class AccountServiceOLD
{
    private CustomerRepository $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
    }

    public function transfer(int $fromCustomerId, int $toCustomerId, float $amount): void
    {
        $this->withdraw($fromCustomerId, $amount);
        $this->deposit($toCustomerId, $amount);
    }

    public function withdraw(int $customerId, float $amount): void
    {
        $customer = $this->customerRepository->find($customerId);
        if ($customer && ($customer->getBalance() - $amount) >= 0) {
            $customer->setBalance($customer->getBalance() - $amount);
            $this->customerRepository->update($customerId, $customer);
        } else {
            throw new Exception('Insufficient funds');
        }
    }

    public function getBalance(int $customerId): float
    {
        $customer = $this->customerRepository->find($customerId);
        return $customer ? $customer->getBalance() : 0;
    }

    public function deposit(int $customerId, float $amount): void
    {
        $customer = $this->customerRepository->find($customerId);
        if ($customer) {
            $customer->setBalance($customer->getBalance() + $amount);
            $this->customerRepository->update($customerId, $customer);
        }
    }
}
