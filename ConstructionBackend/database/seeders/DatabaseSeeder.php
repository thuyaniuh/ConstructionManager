<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Task::create([
        //     'name' => 'test 2',
        //     'project_id' => 1,
        //     'user_id' => 1,
        //     'start_day' => '2024-10-10',
        //     'end_day' => '2024-10-12',
        //     'status' => 1,
        // ]);
        $this->call([
            UsersTableSeeder::class,
            ProjectsTableSeeder::class,
            TasksTableSeeder::class,
            SalariesTableSeeder::class,
            EntriesTableSeeder::class,
            PurchasesTableSeeder::class,
            MaterialsTableSeeder::class,
            SuppliersTableSeeder::class,
        ]);


    }
}
