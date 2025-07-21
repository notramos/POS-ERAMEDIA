<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Kolom yang boleh diisi
    protected $fillable = [
        'total_price',
        'paid_amount',
        'change_amount'
    ];

    // Relasi ke detail transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Helper method untuk format ID
    public function getFormattedIdAttribute()
    {
        return '#' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    // Helper method untuk format currency
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedPaidAmountAttribute()
    {
        return 'Rp ' . number_format($this->paid_amount, 0, ',', '.');
    }

    public function getFormattedChangeAmountAttribute()
    {
        return 'Rp ' . number_format($this->change_amount, 0, ',', '.');
    }

    // Helper method untuk format tanggal
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}
