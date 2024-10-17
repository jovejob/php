<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'surname', 'balance']; // Specify which fields can be mass assigned

    public function getName(): string
    {
        return $this->name; // Getter for name
    }

    public function getSurname(): string
    {
        return $this->surname; // Getter for surname
    }

    public function getBalance(): float
    {
        return $this->balance; // Getter for balance
    }

    public function setBalance(float $balance)
    {
        $customer = $this->find($id);
        if ($customer) {
            $customer->update([
//                'name' => $name,
//                'surname' => $surname,
                'balance' => $balance,
            ]);
            return $customer;
        }
        return null;
    }
}