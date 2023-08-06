<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Perfume',
            'details' => 'Perfumes Store'
        ]);

        Category::create([
            'name' => 'Men Wears',
            'details' => 'Mens Store'
        ]);

        Category::create([
            'name' => 'Woman Wears',
            'details' => 'womans Store'
        ]);

        Category::create([
            'name' => 'LifeStyle',
            'details' => 'Lifestyles Store'
        ]);

        Category::create([
            'name' => 'Toys',
            'details' => 'Toys Store'
        ]);
    }
}
