<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          \DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'careboarding@admin.com',
            'password' => bcrypt('password')
        ]);
    }
}
