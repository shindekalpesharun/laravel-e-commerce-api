<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'John Doe',
        //     'email' => 'john@example.com',
        //     'password' => bcrypt('secret'),
        // ]);

        // User::create([
        //     'name' => 'Jane Doe',
        //     'email' => 'jane@example.com',
        //     'password' => bcrypt('secret'),
        // ]);

        \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'test',
        //     'email' => 'test@test.com',
        // ]);
    }
}
