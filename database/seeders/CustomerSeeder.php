<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        for($i=1; $i<100; $i++){

        DB::table('mst_customers')->insert([
            'customer_name' => Str::random(5),
            'email' => Str::random(5).'@gmail.com',
            'tel_num' => '1234567'.$faker->randomNumber(2),
            'address' => $faker->address,
            'is_active' => 1,

        ]);
    }
}
}