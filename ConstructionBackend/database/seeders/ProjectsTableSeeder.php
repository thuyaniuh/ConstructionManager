<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('projects')->insert([
            [
                'type' => 'type1',
                'description' => 'Project 1 description',
                'start_day' => now(),
                'end_day' => now()->addDays(30),
                'status' => 'active'
            ],
            [
                'type' => 'type2',
                'description' => 'Project 2 description',
                'start_day' => now(),
                'end_day' => now()->addDays(60),
                'status' => 'completed'
            ]
        ]);
    }
}
