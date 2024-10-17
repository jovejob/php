<?php

namespace Tests;

use App\BusinessLogic\AccountService;
use App\DTO\TransactionDTO;
use App\Repository\CustomerRepository;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

class AccountServiceTest extends TestCase
{
    protected AccountService $accountService;
    protected CustomerRepository $customerRepository;

    public function testGetAccountBalance()
    {
        // Create a customer
        $customer = $this->customerRepository->create('John', 'Doe', 100.00);

        // Get balance using AccountService
        $balance = $this->accountService->getAccountBalance($customer->id);

        $this->assertEquals(100.00, $balance);
    }

    public function testDeposit()
    {
        // Create a customer
        $customer = $this->customerRepository->create('John', 'Doe', 100.00);
        $transactionDTO = new TransactionDTO($customer->id, 50.00);

        // Deposit 50 into the account
        $result = $this->accountService->deposit($transactionDTO);

        $this->assertTrue($result);

        // Retrieve updated customer to verify balance
        $updatedCustomer = $this->customerRepository->find($customer->id);
        $this->assertEquals(150.00, $updatedCustomer->balance);
    }

    public function testWithdraw()
    {
        // Create a customer
        $customer = $this->customerRepository->create('Alice', 'Smith', 300.00);
        $transactionDTO = new TransactionDTO($customer->id, 100.00);

        // Withdraw 100 from the account
        $result = $this->accountService->withdraw($transactionDTO);

        $this->assertTrue($result);

        // Retrieve updated customer to verify balance
        $updatedCustomer = $this->customerRepository->find($customer->id);
        $this->assertEquals(200.00, $updatedCustomer->balance);
    }

    public function testWithdrawWithInsufficientFunds()
    {
        // Create a customer with a balance of 100
        $customer = $this->customerRepository->create('John', 'Doe', 100.00);

        // Create a transaction DTO trying to withdraw more than the available balance
        $transactionDTO = new TransactionDTO($customer->id, 150); // Attempting to withdraw 150

        // Expect an exception due to insufficient funds
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient funds');

        // Attempt to withdraw and trigger the exception
        $this->accountService->withdraw($transactionDTO);
    }


    public function testTransfer()
    {
        // Create two customers
        $customer1 = $this->customerRepository->create('Tom', 'Hanks', 200.00);
        $customer2 = $this->customerRepository->create('Emma', 'Watson', 50.00);

        $transactionDTO = new TransactionDTO($customer1->id, 100.00, $customer2->id);

        // Perform transfer from customer1 to customer2
        $result = $this->accountService->transfer($transactionDTO);

        $this->assertTrue($result);

        // Verify updated balances
        $updatedCustomer1 = $this->customerRepository->find($customer1->id);
        $updatedCustomer2 = $this->customerRepository->find($customer2->id);

        $this->assertEquals(100.00, $updatedCustomer1->balance);
        $this->assertEquals(150.00, $updatedCustomer2->balance);
    }

    public function testTransferWithInsufficientFunds()
    {
        // Set up customers with specific balances
        $fromCustomer = $this->customerRepository->create('Alice', 'Smith', 30.00); // Balance of 30
        $toCustomer = $this->customerRepository->create('Bob', 'Brown', 50.00); // Balance of 50

        // Create a transaction DTO to transfer more than the available balance
        $transactionDTO = new TransactionDTO($fromCustomer->id, 50.00, $toCustomer->id); // Attempting to transfer 50

        // Expect an exception due to insufficient funds
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient funds');

        // Attempt the transfer, which should throw the exception
        $this->accountService->transfer($transactionDTO);
    }

    public function testSuccessfulTransfer()
    {
        // Set up customers with specific balances
        $fromCustomer = $this->customerRepository->create('Alice', 'Smith', 100.00); // Balance of 100
        $toCustomer = $this->customerRepository->create('Bob', 'Brown', 50.00); // Balance of 50

        // Create a transaction DTO for a successful transfer
        $transactionDTO = new TransactionDTO($fromCustomer->id, 50.00, $toCustomer->id);

        // Perform the transfer
        $result = $this->accountService->transfer($transactionDTO);

        // Assert the transfer was successful
        $this->assertTrue($result);

        // Check that the balances have been updated
        $this->assertEquals(50.00, $this->accountService->getAccountBalance($fromCustomer->id)); // From customer should have 50 left
        $this->assertEquals(100.00, $this->accountService->getAccountBalance($toCustomer->id));  // To customer should have 100
    }


    public function testWithdrawNonExistentCustomer()
    {
        $transactionDTO = new TransactionDTO(999, 50); // Using an ID that doesn't exist.

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Customer not found');

        $this->accountService->withdraw($transactionDTO);
    }

    public function testPreventNegativeBalance()
    {
        // Create a customer with a balance of 100
        $customer = $this->customerRepository->create('John', 'Doe', 100.00);

        // Create a transaction DTO trying to withdraw more than the available balance
        $transactionDTO = new TransactionDTO($customer->id, 500); // Trying to withdraw 500

        // Expect an exception due to insufficient funds
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient funds');

        // Attempt to withdraw and trigger the exception
        $this->accountService->withdraw($transactionDTO);
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

        Capsule::schema()->create('balance_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_type'); // deposit, withdrawal, transfer
            $table->timestamps();
        });

        // Instantiate the repository and service
        $this->customerRepository = new CustomerRepository();
        $this->accountService = new AccountService($this->customerRepository);
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        Capsule::schema()->dropIfExists('customers');
        Capsule::schema()->dropIfExists('balance_audits');

        parent::tearDown();
    }
}
