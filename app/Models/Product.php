<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'name',
    'price',
    'stock',
    'unit_id',
    'detail',
  ];


  public function getFormattedPriceAttribute()
  {
    return 'Rp ' . number_format($this->price, 0, ',', '.');
  }

  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }
}
