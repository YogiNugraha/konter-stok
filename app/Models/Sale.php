<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan item produk (IMEI) yang terkait dengan penjualan ini.
     */
    public function productItem()
    {
        return $this->hasOne(ProductItem::class);
    }

    protected $fillable = ['product_id', 'user_id', 'quantity', 'price_at_time', 'status'];
}
