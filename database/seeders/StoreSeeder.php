<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fashionStore = Store::factory(1)
             ->hasAttached(
              User::factory()->count(2)
                ->create()
                  ->each(
                function($user) {
                    $user->assignRole('store-admin');
                }
            )
        );
       
            User::factory()->count(2)
               ->has($fashionStore)
               ->create()
                ->each(
                 function($user) {
                  $user->assignRole('store-owner');
                 }
            );

            $luxuryPhoneStore = Store::factory(1)
            ->hasAttached(
             User::factory()->count(3)
               ->create()
                 ->each(
               function($user) {
                   $user->assignRole('store-admin');
               }
           )
       );

             $budgetPhoneStore = Store::factory(3)
            ->hasAttached(
             User::factory()->count(2)
               ->has($luxuryPhoneStore)
               ->create()
                 ->each(
               function($user) {
                   $user->assignRole('store-admin');
               }
           )
       );

         User::factory()->count(3)
               ->has($luxuryPhoneStore)
               ->has($budgetPhoneStore)
               ->create()
                ->each(
                 function($user) {
                  $user->assignRole('store-owner');
                 }
            );

       
    }
}
