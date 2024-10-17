<?php


namespace App\BusinessLogic;

use App\DTO\TransactionDTO;
use App\Repository\CustomerRepository;
use DateTime;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;

class AccountService
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Transfer funds between two customers
     *
     * @param TransactionDTO $dto
     * @return bool
     * @throws Exception
     */
    public function transfer(TransactionDTO $dto): bool
    {
        return Capsule::transaction(function () use ($dto) {
            $fromCustomer = $this->customerRepository->find($dto->customerId);
            $toCustomer = $this->customerRepository->find($dto->targetCustomerId);

            if (!$fromCustomer || !$toCustomer) {
                throw new Exception('One or both customers not found');
            }

            if ($fromCustomer->balance < $dto->amount) {
                throw new Exception('Insufficient funds for transfer');
            }

            // Withdraw from sender and deposit to receiver
            $this->performBalanceUpdate($fromCustomer, -$dto->amount, 'withdraw');
            $this->performBalanceUpdate($toCustomer, $dto->amount, 'deposit');

            return true;
        });
    }

    /**
     * Update customer balance and log the transaction
     *
     * @param $customer
     * @param float $amount
     * @param string $transactionType
     * @throws Exception
     */
    private function performBalanceUpdate($customer, float $amount, string $transactionType): void
    {
        $customer->balance += $amount;
        $this->customerRepository->save($customer);
        $this->logAudit($customer->id, $amount, $transactionType);
    }

    /**
     * Log the transaction into the balance audit
     *
     * @param int $customerId
     * @param float $amount
     * @param string $transactionType
     */
    private function logAudit(int $customerId, float $amount, string $transactionType): void
    {
        Capsule::table('balance_audits')->insert([
            'customer_id' => $customerId,
            'amount' => $amount,
            'transaction_type' => $transactionType,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }

    /**
     * Get the account balance for a customer
     *
     * @param int $customerId
     * @return float
     */
    public function getAccountBalance(int $customerId): float
    {
        $customer = $this->customerRepository->find($customerId);
        return $customer ? $customer->getBalance() : 0;
    }

    /**
     * Withdraw funds from a customer's account
     *
     * @param TransactionDTO $dto
     * @return bool
     * @throws Exception
     */
    public function withdraw(TransactionDTO $dto): bool
    {
        return Capsule::transaction(function () use ($dto) {
            $customer = $this->customerRepository->find($dto->customerId);

            if (!$customer) {
                throw new Exception('Customer not found');
            }

            if ($customer->balance < $dto->amount) {
                throw new Exception('Insufficient funds');
            }

            $this->performBalanceUpdate($customer, -$dto->amount, 'withdraw');
            return true;
        });
    }

    /**
     * Deposit funds into a customer's account
     *
     * @param TransactionDTO $dto
     * @return bool
     * @throws Exception
     */
    public function deposit(TransactionDTO $dto): bool
    {
        return Capsule::transaction(function () use ($dto) {
            $customer = $this->customerRepository->find($dto->customerId);

            if (!$customer) {
                throw new Exception('Customer not found');
            }

            $this->performBalanceUpdate($customer, $dto->amount, 'deposit');
            return true;
        });
    }
}
