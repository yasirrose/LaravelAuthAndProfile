<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        DB::table('users')->insert([
        [
            'name' => 'admin',
            'user_name' =>'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'user_role' => 'admin',
            'avatar' => 'users/images/1618332582.png',
            'is_active' => 1,
        ],
        [
            'name' => 'test',
            'user_name' =>'test006',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
            'user_role' => 'user',
            'avatar' => 'users/images/1618332582.png',
            'is_active' => 1,
        ]
        ]);       
    }
}
