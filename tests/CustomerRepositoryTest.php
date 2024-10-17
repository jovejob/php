<?php

namespace Tests;

use App\Model\Customer;
use App\Repository\CustomerRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

class CustomerRepositoryTest extends TestCase
{
    protected CustomerRepository $customerRepository;

    public function testCreateCustomer()
    {
        $customer = $this->customerRepository->create('John', 'Doe', 100.00);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('John', $customer->getName());
        $this->assertEquals('Doe', $customer->getSurname());
        $this->assertEquals(100.00, $customer->getBalance());
    }

    public function testGetCustomerById()
    {
        $customer = $this->customerRepository->create('Jane', 'Doe', 200.00);
        $foundCustomer = $this->customerRepository->find($customer->id);

        $this->assertInstanceOf(Customer::class, $foundCustomer);
        $this->assertEquals($customer->id, $foundCustomer->id);
    }

    public function testUpdateCustomer()
    {
        $customer = $this->customerRepository->create('Alice', 'Smith', 300.00);
        $updatedCustomer = $this->customerRepository->update($customer->id, 'Alice', 'Johnson', 400.00);

        $this->assertInstanceOf(Customer::class, $updatedCustomer);
        $this->assertEquals('Johnson', $updatedCustomer->getSurname());
        $this->assertEquals(400.00, $updatedCustomer->getBalance());
    }

    public function testDeleteCustomer()
    {
        $customer = $this->customerRepository->create('Bob', 'Brown', 150.00);
        $result = $this->customerRepository->delete($customer->id);

        $this->assertTrue($result);
        $this->assertNull($this->customerRepository->find($customer->id));
    }

    public function testGetAllCustomers()
    {
        $this->customerRepository->create('Tom', 'Hanks', 50.00);
        $this->customerRepository->create('Emma', 'Watson', 75.00);

        $customers = $this->customerRepository->getAll();

        $this->assertInstanceOf(Collection::class, $customers);
        $this->assertCount(2, $customers);
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

        // Instantiate the repository
        $this->customerRepository = new CustomerRepository();
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        Capsule::schema()->dropIfExists('customers');

        parent::tearDown();
    }
}