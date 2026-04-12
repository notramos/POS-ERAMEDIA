<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'name',
        'price',
        'stok',
        'unit_id'
    ];

    // Relasi
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
