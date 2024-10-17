<?php


use Phinx\Seed\AbstractSeed;

class BalanceAuditSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        // Sample data for balance audits
        $data = [
            [
                'customer_id' => 1,
                'transaction_type' => 'deposit',
                'amount' => 100.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'customer_id' => 1,
                'transaction_type' => 'withdraw',
                'amount' => 50.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'customer_id' => 1,
                'transaction_type' => 'transfer_in',
                'amount' => 25.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'customer_id' => 1,
                'transaction_type' => 'transfer_out',
                'amount' => 10.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'customer_id' => 2,
                'transaction_type' => 'deposit',
                'amount' => 200.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'customer_id' => 2,
                'transaction_type' => 'withdraw',
                'amount' => 100.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'customer_id' => 3,
                'transaction_type' => 'deposit',
                'amount' => 500.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'customer_id' => 3,
                'transaction_type' => 'transfer_out',
                'amount' => 200.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert the data into the balance_audits table
        $this->table('balance_audits')->insert($data)->save();
    }
}
