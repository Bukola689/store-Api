<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name', 'details'
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_brand');
    }

    public function productlines()
    {
        return $this->belongsToMany(ProductLine::class, 'brand_product_line');
    }
}
