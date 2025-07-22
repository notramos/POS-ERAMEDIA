<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name'];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
