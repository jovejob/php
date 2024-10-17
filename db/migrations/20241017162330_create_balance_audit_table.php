<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBalanceAuditTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('balance_audits');
        $table->addColumn('customer_id', 'integer', ['null' => false])
            ->addColumn('transaction_type', 'string', ['limit' => 50, 'null' => false]) // e.g., deposit, withdrawal, transfer
            ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false])
            ->addTimestamps()
            ->create();

        // was failing for some reason
//            ->addForeignKey(['customer_id', 'id'],
//                'customers',
//                ['customer_id', 'id'],
//                ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION', 'constraint' => 'customer_id'])

    }
}
