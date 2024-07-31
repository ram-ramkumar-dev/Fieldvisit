<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'), // Replace 'password' with your desired password
            'groups' => '1', // Add this line if you have a role column in your users table
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
