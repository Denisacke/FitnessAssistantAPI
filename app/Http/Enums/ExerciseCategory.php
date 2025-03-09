<?php

namespace App\Http\Enums;

enum ExerciseCategory: string
{
    case CARDIO = 'cardio';
    case STRENGTH = 'strength';
    case FLEXIBILITY = 'flexibility';
    case BALANCE = 'balance';
}
