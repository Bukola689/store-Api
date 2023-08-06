<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'products';

    protected $fillable = [
        'title', 'caption', 'details'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function productlines()
    {
        return $this->belongsToMany(ProductLine::class, 'product_product_line');
    }
}
