<?php

namespace Tests;

use App\DTO\CustomerDTO;
use App\Repository\CustomerRepository;
use PHPUnit\Framework\TestCase;

class CustomerRepositoryTest extends TestCase
{
    private CustomerRepository $customerRepository;

    public function testSaveAndFind(): void
    {
        $customerDTO = new CustomerDTO('John', 'Doe', 100.0);
        $this->customerRepository->save($customerDTO);

        $foundCustomer = $this->customerRepository->find(0);
        $this->assertNotNull($foundCustomer);
        $this->assertEquals('John', $foundCustomer->getName());
    }

    protected function setUp(): void
    {
        $this->customerRepository = new CustomerRepository();
    }

//    public function testSaveAndFind()
//    {
//        $repository = new CustomerRepository();
//        $customerDTO = new CustomerDTO('John', 'Doe', 1000);
//        $repository->save($customerDTO);
//
//        // Assuming the find method returns a customer object
//        $foundCustomer = $repository->find(0);
//        $this->assertNotNull($foundCustomer);
//        $this->assertEquals('John', $foundCustomer->getName());
//        $this->assertEquals('Doe', $foundCustomer->getSurname());
//        $this->assertEquals(1000, $foundCustomer->getBalance());
//    }
}
