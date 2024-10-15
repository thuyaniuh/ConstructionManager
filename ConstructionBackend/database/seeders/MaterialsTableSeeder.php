<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('materials')->insert([
            [
                'material_name' => 'Cement',
                'unit' => 'kg',
                'price' => 50.00
            ],
            [
                'material_name' => 'Steel',
                'unit' => 'kg',
                'price' => 100.00
            ]
        ]);
    }
}
