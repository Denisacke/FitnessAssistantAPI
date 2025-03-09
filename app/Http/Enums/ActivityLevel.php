<?php

namespace App\Http\Enums;

enum ActivityLevel: string
{
    case SEDENTARY = 'sedentary'; // Little to no exercise
    case LIGHT = 'light'; // Light exercise 1–3 days a week
    case MODERATE = 'moderate'; // Moderate exercise 3–5 days a week
    case ACTIVE = 'active'; // Intense exercise 6–7 days a week
    case VERY_ACTIVE = 'very_active'; // Very intense physical job or twice-a-day training
}
