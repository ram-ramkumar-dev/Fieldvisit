<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/data/propertytype.csv"), "r");

        // Skip the first line (header)
        fgetcsv($csvFile);

        while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
            DB::table('property_type')->insert([
                'code' => $data[0],
                'property_type_name' => $data[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($csvFile);
    }
}
