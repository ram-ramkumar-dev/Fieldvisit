<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OccupancyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        DB::table('occupancy_status')->insert([
            [
                'occupancy_status_name' => 'Malaysian', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'occupancy_status_name' => 'Foreigner', 
                'created_at' => now(),
                'updated_at' => now(),
            ], 
        ]);
    }
}
