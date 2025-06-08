<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'brand',
        'model',
        'storage',
        'color',
        'purchase_price',
        'selling_price',
        'stock',
    ];

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function productItems()
    {
        return $this->hasMany(ProductItem::class);
    }
}
