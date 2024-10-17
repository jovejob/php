<?php

namespace Tests;

use App\Model\BalanceAudit;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

class BalanceAuditTest extends TestCase
{
    public function testGetCustomerAuditLogs(): void
    {
        Capsule::table('balance_audits')->insert([
            ['customer_id' => 1, 'transaction_type' => 'deposit', 'amount' => 100],
            ['customer_id' => 1, 'transaction_type' => 'withdraw', 'amount' => 50],
            ['customer_id' => 2, 'transaction_type' => 'deposit', 'amount' => 200]
        ]);

        $logs = BalanceAudit::getCustomerAuditLogs(1);

        // Assert that we have 2 logs for customer 1
        $this->assertCount(2, $logs);
        $this->assertEquals('deposit', $logs[0]->transaction_type);
        $this->assertEquals('withdraw', $logs[1]->transaction_type);
    }

    public function testRebuildCustomerBalance(): void
    {
        Capsule::table('balance_audits')->insert([
            ['customer_id' => 1, 'transaction_type' => 'deposit', 'amount' => 100],
            ['customer_id' => 1, 'transaction_type' => 'withdraw', 'amount' => 50],
            ['customer_id' => 1, 'transaction_type' => 'transfer_in', 'amount' => 30],
            ['customer_id' => 1, 'transaction_type' => 'transfer_out', 'amount' => 20],
        ]);

        $balance = BalanceAudit::rebuildCustomerBalance(1);

        // Expected balance = 100 (deposit) - 50 (withdraw) + 30 (transfer_in) - 20 (transfer_out) = 60
        $this->assertEquals(60, $balance);
    }

    protected function setUp(): void
    {
        // Initialize Capsule
        $this->setUpDatabase();

        // Create the balance_audits table
        Capsule::schema()->create('balance_audits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->string('transaction_type');
            $table->float('amount');
            $table->timestamps();
        });
    }

    private function setUpDatabase(): void
    {
        // Create a new Capsule instance
        $capsule = new Capsule;

        // Add connection (using SQLite in-memory database)
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set the global instance
        $capsule->setAsGlobal();

        // Boot Eloquent
        $capsule->bootEloquent();
    }

    protected function tearDown(): void
    {
        // Clean up after tests
        Capsule::schema()->dropIfExists('balance_audits');
    }
}
