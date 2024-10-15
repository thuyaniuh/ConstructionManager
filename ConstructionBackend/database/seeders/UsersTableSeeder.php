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
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'password' => Hash::make('123456'),
                'birth' => '1990-01-01',
                'phone' => '0123456789',
                'email' => 'admin@example.com',
                'role' => 'admin'
            ],
            [
                'name' => 'Worker User',
                'password' => Hash::make('123456'),
                'birth' => '1992-02-02',
                'phone' => '0987654321',
                'email' => 'worker@example.com',
                'role' => 'worker'
            ]
        ]);
    }
}
