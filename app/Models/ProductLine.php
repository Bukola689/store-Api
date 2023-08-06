<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'product_lines';

    protected $fillable = [
        'name', 'details'
    ];

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_product_line')->as('brands');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_product_line');
    }

   
}
