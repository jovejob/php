<?php

namespace App\Controller;

use App\BusinessLogic\CustomerService;
use App\DTO\CustomerDTO;
use App\Repository\CustomerRepository;

class CustomerController
{
    private CustomerService $customerService;

    public function __construct(CustomerRepository $repository)
    {
        $this->customerService = new CustomerService($repository);
    }

    public function getCustomers(): void
    {
        $customers = $this->customerService->getAllCustomers();
        // Set the content type to application/json
        header('Content-Type: application/json');

        // Return the list of CustomerDTOs as JSON
        echo json_encode($customers); // Return the list of CustomerDTOs as JSON
    }


    public function updateCustomer(int $id): void
    {
        // Get the raw POST data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate: At least one of name, surname, or balance must be provided
        if (empty($data['name']) && empty($data['surname']) && !isset($data['balance'])) {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['error' => 'At least one of name, surname, or balance is required.']);
            return;
        }

        // Fetch the customer to be updated (returns an array)
        $existingCustomer = $this->customerService->getCustomerById($id);

        if ($existingCustomer === null) {
            header('HTTP/1.0 404 Not Found');
            echo json_encode(['error' => 'Customer not found.']);
            return;
        }

        // Create a new CustomerDTO with the updated information
        $customerDTO = new CustomerDTO(
            name: $data['name'] ?? $existingCustomer['name'],
            surname: $data['surname'] ?? $existingCustomer['surname'],
            balance: isset($data['balance']) ? (float)$data['balance'] : $existingCustomer['balance']
        );

        // Use the service to update the customer with the CustomerDTO
        $updatedCustomer = $this->customerService->updateCustomer($id, $customerDTO);

        // Set the response code to 200 OK
        header('HTTP/1.0 200 OK');

        // Set the content type to application/json
        header('Content-Type: application/json');

        // Return the updated CustomerDTO as JSON
        echo json_encode($updatedCustomer->toArrayConversion());
    }


    public function getCustomerById(int $id): void
    {
        // Fetch the customer by ID using the service
        $customer = $this->customerService->getCustomerById($id);

        // Set the content type to application/json
        header('Content-Type: application/json');

        // Check if the customer was found
        if ($customer) {
            // Return the CustomerDTO as JSON
            echo json_encode($customer);
        } else {
            // Handle the case where the customer is not found
            header('HTTP/1.0 404 Not Found');
            echo json_encode(['error' => 'Customer not found']);
        }
    }

    public function createCustomer(): void
    {
        // Get the raw POST data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the incoming data
        if (empty($data['name']) || empty($data['surname']) || !isset($data['balance'])) {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['error' => 'Name, surname, and balance are required.']);
            return;
        }

        // Create a new CustomerDTO
        $customerDTO = new CustomerDTO(
            name: $data['name'],
            surname: $data['surname'],
            balance: (float)$data['balance'] // Ensure balance is a float
        );

        // Use the service to save the customer
        $createdCustomer = $this->customerService->createCustomer($customerDTO);

        // Set the response code to 201 Created
        header('HTTP/1.0 201 Created');

        // Set the content type to application/json
        header('Content-Type: application/json');

        // Return the created CustomerDTO as JSON
        echo json_encode($createdCustomer->toArrayConversion()); // Return as array
    }

    public function deleteCustomer(int $id): void
    {
        // Validate that the customer ID is provided
        if (empty($id)) {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['error' => 'Customer ID is required.']);
            return;
        }

        // Use the service to delete the customer
        $isDeleted = $this->customerService->deleteCustomer($id);

        if ($isDeleted) {
            // Set the response code to 204 No Content
            header('HTTP/1.0 204 No Content');
            return; // No content to return
        } else {
            // Customer not found
            header('HTTP/1.0 404 Not Found');
            echo json_encode(['error' => 'Customer not found.']);
        }
    }
}
