<?php

namespace App\Repository;

use App\DTO\CustomerDTO;

class CustomerRepository
{
    private $customers = [];

    public function findAll()
    {
        return $this->customers;
    }

    public function save(CustomerDTO $customer)
    {
        $this->customers[] = $customer;
    }

    public function find($id)
    {
        return $this->customers[$id] ?? null; // Assuming customers are stored in an array
    }

    public function update($id, CustomerDTO $customer)
    {
        if (isset($this->customers[$id])) {
            $this->customers[$id] = $customer; // Update customer data
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        if (isset($this->customers[$id])) {
            unset($this->customers[$id]); // Remove customer
            return true;
        }
        return false;
    }
}
