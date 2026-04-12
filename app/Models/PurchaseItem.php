<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseItem extends Model
{
    protected $guarded = ['id'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier(){
        return $this->belongsTo(SupplierItem::class, 'supplier_item_id');
    }
    
}
