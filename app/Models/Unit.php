<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
     protected $fillable = ['name','price_per_unit'];

     public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
