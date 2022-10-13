<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $price = [12, 100, 200, 120];
        
        for($i=0; $i<100; $i++){
            $product_name = Str::random(1);
        DB::table('mst_products')->insert([
            'product_id' => Str::random(20),
            'product_name' => 'Sản phẩm '.$product_name,
            'product_image' => Str::random(1).'png',
            'product_price' => $price[array_rand($price,1)],
            'description' => 'Chức năng sản phẩm '.$product_name,
            'is_sales' => 1,
            
        ]);
    }
}
}