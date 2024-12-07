<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function subProductType()
    {
        return $this->hasMany(SubProductType::class, 'product_type_id');
    }
    public function product()
    {
        return $this->hasMany(Product::class, 'product_type_id');
    }
}
