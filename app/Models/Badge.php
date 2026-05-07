<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon_url',
        'color',
        'condition_type', // first_lesson, streak_7, streak_30, course_completed, courses_completed_3, etc.
        'condition_value',
        'sort_order',
    ];

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    // Badge types and conditions
    public static function getAvailableBadges()
    {
        return [
            'first_lesson' => [
                'name' => 'First Step',
                'description' => 'Completed your first lesson',
                'icon' => '👣',
                'color' => 'blue',
            ],
            'streak_3' => [
                'name' => '3-Day Streak',
                'description' => 'Learned for 3 days in a row',
                'icon' => '🔥',
                'color' => 'orange',
            ],
            'streak_7' => [
                'name' => '7-Day Streak',
                'description' => 'Learned for 7 days in a row',
                'icon' => '🔥',
                'color' => 'red',
            ],
            'streak_30' => [
                'name' => '30-Day Streak',
                'description' => 'Learned for 30 days in a row',
                'icon' => '🚀',
                'color' => 'purple',
            ],
            'course_completed' => [
                'name' => 'Course Completed',
                'description' => 'Completed your first course',
                'icon' => '🎓',
                'color' => 'green',
            ],
            'courses_completed_3' => [
                'name' => 'Triple Graduate',
                'description' => 'Completed 3 courses',
                'icon' => '🎖️',
                'color' => 'amber',
            ],
            'hours_10' => [
                'name' => '10 Hours Learner',
                'description' => 'Spent 10+ hours learning',
                'icon' => '⏰',
                'color' => 'cyan',
            ],
            'hours_50' => [
                'name' => 'Half Century',
                'description' => 'Spent 50+ hours learning',
                'icon' => '🏆',
                'color' => 'yellow',
            ],
        ];
    }
}
