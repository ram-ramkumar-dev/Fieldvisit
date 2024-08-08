<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classification')->insert([
            [
                'classification_name' => 'Domestic', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classification_name' => 'Commercial', 
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'classification_name' => 'Industrial', 
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'classification_name' => 'Government Quarters', 
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'classification_name' => 'Government Premise', 
                'created_at' => now(),
                'updated_at' => now(),
            ], 
        ]);
    }
}
