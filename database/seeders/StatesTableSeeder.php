<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('states')->count() == 0) {
            $states = [
                ['state_name' => 'Johor'],
                ['state_name' => 'Kedah'],
                ['state_name' => 'Kelantan'], 
                ['state_name' => 'Labuan'],
                ['state_name' => 'Melaka'],
                ['state_name' => 'Negeri Sembilan'],
                ['state_name' => 'Pahang'],
                ['state_name' => 'Penang'],
                ['state_name' => 'Perak'],
                ['state_name' => 'Perlis'],
                ['state_name' => 'Sabah'],
                ['state_name' => 'Sarawak'],
                ['state_name' => 'Selangor'],
                ['state_name' => 'Terengganu'],
                ['state_name' => 'Kuala Lumpur'],
                ['state_name' => 'Putrajaya'],
                ['state_name' => 'WILAYAH PERSEKUTUAN'],
            ];

              DB::table('states')->insert($states);
         }
    }
}
