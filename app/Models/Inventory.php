<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'reorder_point',
        'updated_quantity',
        'product_id',
        'store_id',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
