<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Platform Admin',
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Lead Instructor',
            'email' => 'instructor@example.com',
            'role' => User::ROLE_INSTRUCTOR,
        ]);

        User::factory(3)->create();

        $this->call([
            CategorySeeder::class,
            CourseSeeder::class,
        ]);
    }
}
