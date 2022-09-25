<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();

        $faker = \Faker\Factory::create();

        // create orders
        for ($i = 0; $i < 50; $i++) {
            Order::create([
                'orderCode' => $faker->regexify('[A-Z0-9]{8}'),
                'productId' => $faker->unique()->numberBetween(1, 10000),
                'quantity' => $faker->unique()->numberBetween(500, 10000),
                'address' => $faker->text(255),
                'shippingDate' => $faker->unique()->dateTimeBetween('now', '+30 days', null)->format('Y-m-d')
            ]);
        }
    }
}
