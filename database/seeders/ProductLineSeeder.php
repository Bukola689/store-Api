<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\ProductLine;
use Illuminate\Database\Seeder;

class ProductLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i < 10; $i++) {
            ProductLine::factory(2)
                    ->hasAttached(Brand::all()->random())
                    ->create();
        }
    }
}
