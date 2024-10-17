<?php

namespace App\Repository;

use App\Model\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository
{
    public function create(string $name, string $surname, float $balance): Customer
    {
        return Customer::create([
            'name' => $name,
            'surname' => $surname,
            'balance' => $balance,
        ]);
    }

    public function update(int $id, string $name, string $surname, float $balance): ?Customer
    {
        $customer = $this->find($id);
        if ($customer) {
            $customer->update([
                'name' => $name,
                'surname' => $surname,
                'balance' => $balance,
            ]);
            return $customer;
        }
        return null;
    }

    public function find(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function delete(int $id): bool
    {
        // Find the customer by ID and delete
        $customer = Customer::find($id);
        if ($customer) {
            return $customer->delete();
        }
        return false; // Return false if the customer was not found
    }


    public function getAll(): Collection // Change return type to Collection
    {
        return Customer::all(); // Return the collection of Customer models
    }
}
