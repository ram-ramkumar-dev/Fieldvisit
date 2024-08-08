<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;

class DrcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/data/dr_codes.csv"), "r");

        // Skip the first line (header)
        fgetcsv($csvFile);

        while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
            DB::table('dr_code')->insert([
                'code' => $data[0],
                'dr_code_name' => $data[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($csvFile);
    }
}
