<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DriverTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drivers')->insert([
                    'name' => 'osama',
                    'username' => 'osama',
                    'email' => 'osama@gmail.com',
                    'password' => Hash::make('osama')
             ]);
    }
}
