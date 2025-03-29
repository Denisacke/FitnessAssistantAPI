<?php

namespace App\Http\Enums;

enum UserRole: string
{
    case REGULAR_USER = 'user';

    case TRAINER = 'trainer';
}
