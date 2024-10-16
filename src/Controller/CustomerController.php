<?php

namespace App\Controller;

use App\DTO\CustomerDTO;
use App\Repository\CustomerRepository;

class CustomerController
{
    private $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
    }

    public function getCustomers()
    {
        $customers = $this->customerRepository->findAll();
        header('Content-Type: application/json');
        echo json_encode($customers);
    }

    public function createCustomer()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name'], $data['surname'], $data['balance'])) {
            $customerDTO = new CustomerDTO($data['name'], $data['surname'], $data['balance']);
            $this->customerRepository->save($customerDTO);
            header('HTTP/1.1 201 Created');
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['error' => 'Invalid input data']);
        }
    }

    public function getCustomerById($id)
    {
        $customer = $this->customerRepository->find($id);
        header('Content-Type: application/json');
        if ($customer) {
            echo json_encode($customer);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(['error' => 'Customer not found']);
        }
    }


    public function updateCustomer($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name'], $data['surname'], $data['balance'])) {
            $customerDTO = new CustomerDTO($data['name'], $data['surname'], $data['balance']);
            if ($this->customerRepository->update($id, $customerDTO)) {
                header('HTTP/1.1 204 No Content');
            } else {
                header("HTTP/1.1 404 Not Found");
                echo json_encode(['error' => 'Customer not found']);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['error' => 'Invalid input data']);
        }
    }

    public function deleteCustomer($id)
    {
        if ($this->customerRepository->delete($id)) {
            header('HTTP/1.1 204 No Content');
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(['error' => 'Customer not found']);
        }
    }
}
