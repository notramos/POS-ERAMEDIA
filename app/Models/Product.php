<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'name',
    'price',
    'stock',
  ];

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function getFormattedPriceAttribute()
  {
    return 'Rp ' . number_format($this->price, 0, ',', '.');
  }
}
