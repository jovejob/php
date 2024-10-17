<?php

namespace Tests;

use App\BusinessLogic\CustomerService;
use App\DTO\CustomerDTO;
use App\Repository\CustomerRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

class CustomerServiceTest extends TestCase
{
    protected CustomerService $customerService;
    protected CustomerRepository $customerRepository;

    public function testCreateCustomer()
    {
        $customerDTO = new CustomerDTO('John', 'Doe', 100.00);
        $result = $this->customerService->createCustomer($customerDTO);

        $this->assertInstanceOf(CustomerDTO::class, $result);
        $this->assertEquals('John', $result->getName());
        $this->assertEquals('Doe', $result->getSurname());
        $this->assertEquals(100.00, $result->getBalance());
    }

    public function testUpdateCustomer()
    {
        // Create an initial customer
        $customer = $this->customerRepository->create('Alice', 'Smith', 300.00);
        $customerDTO = new CustomerDTO('Alice', 'Johnson', 400.00);

        // Update the customer using the service
        $updatedCustomerDTO = $this->customerService->updateCustomer($customer->id, $customerDTO);

        $this->assertInstanceOf(CustomerDTO::class, $updatedCustomerDTO);
        $this->assertEquals('Johnson', $updatedCustomerDTO->getSurname());
        $this->assertEquals(400.00, $updatedCustomerDTO->getBalance());
    }

    public function testGetCustomerById()
    {
        // Create a customer
        $customer = $this->customerRepository->create('Jane', 'Doe', 200.00);

        // Retrieve customer by ID using the service
        $result = $this->customerService->getCustomerById($customer->id);

        // Check if the result is an array
        $this->assertIsArray($result); // Change this line to check for an array
        $this->assertEquals($customer->id, $result['id']); // Adjust accordingly to check the customer ID
        $this->assertEquals('Jane', $result['name']);
        $this->assertEquals('Doe', $result['surname']);
        $this->assertEquals(200.00, $result['balance']);
    }


    public function testGetAllCustomers()
    {
        $this->customerRepository->create('Tom', 'Hanks', 50.00);
        $this->customerRepository->create('Emma', 'Watson', 75.00);

        $customers = $this->customerService->getAllCustomers();

        $this->assertIsArray($customers);
        $this->assertCount(2, $customers);
    }

    public function testDeleteCustomer()
    {
        $customer = $this->customerRepository->create('Bob', 'Brown', 150.00);
        $result = $this->customerService->deleteCustomer($customer->id);

        $this->assertTrue($result);
        $this->assertNull($this->customerRepository->find($customer->id));
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Set up the sqlite/in memory database connection for faster testing
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        // Create the customers table
        Capsule::schema()->create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->decimal('balance', 10, 2);
            $table->timestamps();
        });

        // Instantiate the repository and service
        $this->customerRepository = new CustomerRepository();
        $this->customerService = new CustomerService($this->customerRepository);
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        Capsule::schema()->dropIfExists('customers');

        parent::tearDown();
    }
}
