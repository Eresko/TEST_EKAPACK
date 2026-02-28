<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Customer;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        Customer::factory()->count(5)->create();

        Product::factory()->count(20)->create();

    }
}