<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'slug' => 'first_lesson',
                'name' => 'First Step',
                'description' => 'Completed your first lesson',
                'icon_url' => '👣',
                'color' => 'blue',
                'condition_type' => 'first_lesson',
                'sort_order' => 1,
            ],
            [
                'slug' => 'streak_3',
                'name' => '3-Day Streak',
                'description' => 'Learned for 3 days in a row',
                'icon_url' => '🔥',
                'color' => 'orange',
                'condition_type' => 'streak_3',
                'sort_order' => 2,
            ],
            [
                'slug' => 'streak_7',
                'name' => '7-Day Streak',
                'description' => 'Learned for 7 days in a row',
                'icon_url' => '🔥',
                'color' => 'red',
                'condition_type' => 'streak_7',
                'sort_order' => 3,
            ],
            [
                'slug' => 'streak_30',
                'name' => '30-Day Streak',
                'description' => 'Learned for 30 days in a row',
                'icon_url' => '🚀',
                'color' => 'purple',
                'condition_type' => 'streak_30',
                'sort_order' => 4,
            ],
            [
                'slug' => 'course_completed',
                'name' => 'Course Completed',
                'description' => 'Completed your first course',
                'icon_url' => '🎓',
                'color' => 'green',
                'condition_type' => 'course_completed',
                'sort_order' => 5,
            ],
            [
                'slug' => 'courses_completed_3',
                'name' => 'Triple Graduate',
                'description' => 'Completed 3 courses',
                'icon_url' => '🎖️',
                'color' => 'amber',
                'condition_type' => 'courses_completed_3',
                'sort_order' => 6,
            ],
            [
                'slug' => 'hours_10',
                'name' => '10 Hours Learner',
                'description' => 'Spent 10+ hours learning',
                'icon_url' => '⏰',
                'color' => 'cyan',
                'condition_type' => 'hours_10',
                'sort_order' => 7,
            ],
            [
                'slug' => 'hours_50',
                'name' => 'Half Century',
                'description' => 'Spent 50+ hours learning',
                'icon_url' => '🏆',
                'color' => 'yellow',
                'condition_type' => 'hours_50',
                'sort_order' => 8,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}
