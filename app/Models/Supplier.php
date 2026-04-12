<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = ['id'];

    public function supplierItems()
    {
        return $this->hasMany(SupplierItem::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

}