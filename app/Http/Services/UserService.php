<?php

namespace App\Http\Services;

use App\Http\Enums\ActivityLevel;
use App\Http\Enums\Sex;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserService
{
    public static function computeBMI($weight, $height): float
    {
        $heightInMeters = $height / 100;

        $bmi = $weight / ($heightInMeters ** 2);

        return round($bmi);
    }

    public static function calculateRecommendedWaterIntake(User $user): float
    {
        // Base intake: 35ml per kg
        $baseWaterMl = $user->weight * 35;

        // Extra water based on activity
        $activityExtra = [
            ActivityLevel::SEDENTARY->value => 0,
            ActivityLevel::LIGHT->value => 350,
            ActivityLevel::MODERATE->value => 500,
            ActivityLevel::ACTIVE->value => 650,
            ActivityLevel::VERY_ACTIVE->value => 750,
        ];

        $totalWaterMl = $baseWaterMl + $activityExtra[$user->activity_level->value];

        return round($totalWaterMl);
    }

    public static function calculateRecommendedCalories(User $user): float
    {
        $bmr = 0;
        $userAge = Carbon::parse($user->birthDate)->age;
        if ($user->sex === Sex::MALE) {
            $bmr = 88.362 + (13.397 * $user->weight) + (4.799 * $user->height) - (5.677 * $userAge);
        } elseif ($user->sex === Sex::FEMALE) {
            $bmr = 447.593 + (9.247 * $user->weight) + (3.098 * $user->height) - (4.330 * $userAge);
        }

        $activityMultipliers = [
            ActivityLevel::SEDENTARY->value => 1.2,
            ActivityLevel::LIGHT->value => 1.375,
            ActivityLevel::MODERATE->value => 1.55,
            ActivityLevel::ACTIVE->value => 1.725,
            ActivityLevel::VERY_ACTIVE->value => 1.9,
        ];

        return round($bmr * $activityMultipliers[$user->activity_level->value]);
    }
}
