<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ItemPenjualan;
use App\Models\User;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'user_id',
        'total_pembayaran',
        'metode_pembayaran',
        'uang_dibayar',
        'kembalian',
        'status',
        'customer_name',   
        'customer_phone',  
        'due_date',        
        'fine_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'penjualan_id');
    }

    public function itemsPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'penjualan_id');
    }
}
