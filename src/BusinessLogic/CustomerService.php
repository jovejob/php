<?php

namespace App\BusinessLogic;

use App\DTO\CustomerDTO;
use App\Model\Customer;
use App\Repository\CustomerRepository;

class CustomerService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createCustomer(CustomerDTO $customerDTO): CustomerDTO
    {
        // Create a new customer using the repository
        $customer = $this->repository->create(
            $customerDTO->getName(),
            $customerDTO->getSurname(),
            $customerDTO->getBalance()
        );

        // Convert Customer to DTO and return it
        return CustomerDTO::fromEntitySelf($customer);
    }

    public function updateCustomer(int $id, CustomerDTO $customerDTO): ?CustomerDTO
    {
        // Find the customer by ID
        $customer = Customer::find($id);

        // If customer is not found, return null
        if ($customer === null) {
            return null;
        }

        // Update the customer's fields
        $customer->name = $customerDTO->getName();
        $customer->surname = $customerDTO->getSurname();
        $customer->balance = $customerDTO->getBalance();

        // Save the updated customer
        $customer->save();

        return CustomerDTO::fromEntitySelf($customer);
    }


    public function getCustomerById(int $id): ?array
    {
        // Fetch the customer by ID using the repository
        $customer = $this->repository->find($id);

        // Check if the customer exists
        if ($customer) {
            // Convert Customer entity to DTO and return as an associative array
            return CustomerDTO::fromEntity($customer);
        } else {
            // Return null if the customer is not found
            return null;
        }
    }
    
    public function getAllCustomers(): array
    {
        // Fetch all customers using the repository
        $customers = $this->repository->getAll(); // This returns an Eloquent Collection

        // Use Eloquent's map method to convert each Customer to an associative array
        return $customers->map(fn(Customer $customer) => CustomerDTO::fromEntity($customer))->toArray();
    }

    public function deleteCustomer(int $id): bool
    {
        // Use the repository to delete the customer by ID
        return $this->repository->delete($id);
    }
}
