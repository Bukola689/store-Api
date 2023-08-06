<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLine;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<15; $i++) {
            Product::factory(random_int(4,12))
                     ->hasAttached(Category::all()->random())
                     ->hasAttached(ProductLine::all()->random())
                     ->create();
        }
    }
}
