<?php

namespace App\Http\Enums;

enum ExerciseCategory: string
{
    case BICYCLING = 'Bicycling';
    case CONDITIONING_EXERCISE = 'Conditioning Exercise';
    case DANCING = 'Dancing';
    case FISHING_HUNTING = 'Fishing & Hunting';
    case HOME_ACTIVITIES = 'Home Activities';
    case HOME_REPAIR = 'Home Repair';
    case INACTIVITY = 'Inactivity';
    case LAWN_GARDEN = 'Lawn & Garden';
    case MISCELLANEOUS = 'Miscellaneous';
    case MUSIC_PLAYING = 'Music Playing';
    case OCCUPATION = 'Occupation';
    case RUNNING = 'Running';
    case SELF_CARE = 'Self Care';
    case SEXUAL_ACTIVITY = 'Sexual Activity';
    case SPORTS = 'Sports';
    case TRANSPORTATION = 'Transportation';
    case WALKING = 'Walking';
    case WATER_ACTIVITIES = 'Water Activities';
    case WINTER_ACTIVITIES = 'Winter Activities';
    case RELIGIOUS_ACTIVITIES = 'Religious Activities';
    case VOLUNTEER_ACTIVITIES = 'Volunteer Activities';
    case VIDEO_GAMES = 'Video Games';
}
