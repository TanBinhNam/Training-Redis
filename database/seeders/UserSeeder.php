<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group_role = ['Admin', 'Reviewer' , 'Editor'];
        
        for($i=0; $i<20; $i++){
            $random_group_role = $group_role[array_rand($group_role,1)];
        DB::table('mst_users')->insert([
            'name' => Str::random(4),
            'email' => Str::random(4).'@gmail.com',
            'password' => Hash::make('123'),
            'is_active' => 1,
            'is_delete' => 0,
            'group_role' => $random_group_role,
        ]);
    }
    }
}
