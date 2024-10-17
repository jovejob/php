<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['customer_id', 'balance']; // Specify which fields can be mass assigned

    public function getCustomerId(): int
    {
        return $this->customer_id; // Getter for customer ID
    }

    public function getBalance(): float
    {
        return $this->balance; // Getter for balance
    }

    // Optionally, you can define a relationship with the Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
