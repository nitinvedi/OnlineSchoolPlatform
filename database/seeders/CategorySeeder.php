<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Programming', 'icon' => '💻'],
            ['name' => 'Data Science', 'icon' => '📊'],
            ['name' => 'Design', 'icon' => '🎨'],
            ['name' => 'Business', 'icon' => '📈'],
            ['name' => 'Language Learning', 'icon' => '🌍'],
            ['name' => 'Personal Development', 'icon' => '🚀'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => ucfirst($category['name']) . ' courses for learners of all levels.',
                'icon' => $category['icon'],
            ]);
        }
    }
}
