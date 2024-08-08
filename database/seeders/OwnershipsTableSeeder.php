<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;

class OwnershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ownerships')->insert([
            [
                'ownershipname' => 'Owner', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ownershipname' => 'Tenant', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ownershipname' => 'Vacant', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ownershipname' => 'Closed', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
