<?php

namespace App\Repository;

use App\Model\Account;
use Illuminate\Database\Eloquent\Collection;

class AccountRepository
{
    public function create(int $customerId, float $initialBalance): Account
    {
        return Account::create([
            'customer_id' => $customerId,
            'balance' => $initialBalance,
        ]);
    }

    public function updateBalance(int $id, float $newBalance): bool
    {
        $account = $this->find($id);
        if ($account) {
            $account->balance = $newBalance;
            return $account->save();
        }
        return false;
    }

    public function find(int $id): ?Account
    {
        return Account::find($id);
    }

    public function delete(int $id): bool
    {
        $account = $this->find($id);
        return $account ? $account->delete() : false;
    }

    public function getAll(): Collection
    {
        return Account::all();
    }
}
