<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('suppliers')->insert([
            [
                'supplier_name' => 'Supplier 1',
                'address' => '123 Main St',
                'phone' => '0123456789'
            ],
            [
                'supplier_name' => 'Supplier 2',
                'address' => '456 Market Ave',
                'phone' => '0987654321'
            ]
        ]);
    }
}
