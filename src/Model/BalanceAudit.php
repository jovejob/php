<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BalanceAudit extends Model
{
    protected $fillable = ['customer_id', 'transaction_type', 'amount'];

    // Assuming your table is named 'balance_audits'
    protected $table = 'balance_audits';

    /**
     * Log a balance transaction
     *
     * @param int $customerId
     * @param string $transactionType
     * @param float $amount
     * @return void
     */
    public static function logTransaction(int $customerId, string $transactionType, float $amount): void
    {
        self::create([
            'customer_id' => $customerId,
            'transaction_type' => $transactionType,
            'amount' => $amount
        ]);
    }

    /**
     * Rebuild the balance for a specific customer based on audit logs
     *
     * @param int $customerId
     * @return float
     */
    public static function rebuildCustomerBalance(int $customerId): float
    {
        $auditLogs = self::getCustomerAuditLogs($customerId);
        $balance = 0;

        foreach ($auditLogs as $log) {
            switch ($log->transaction_type) {
                case 'deposit':
                case 'transfer_in':
                    $balance += $log->amount;
                    break;
                case 'withdraw':
                case 'transfer_out':
                    $balance -= $log->amount;
                    break;
            }
        }

        return $balance;
    }

    /**
     * Retrieve audit logs for a specific customer
     *
     * @param int $customerId
     * @return Collection
     */
    public static function getCustomerAuditLogs(int $customerId)
    {
        return self::where('customer_id', $customerId)->orderBy('created_at', 'desc')->get();
    }

}
