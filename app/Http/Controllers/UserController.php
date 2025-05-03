<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Enums\ActivityLevel;
use App\Http\Enums\Sex;
use App\Http\Requests\UserRegisterForm;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRegisterForm $userRegisterForm): JsonResponse
    {
        $userData = $userRegisterForm->validated();
        $user = new User([
            ...$userData,
            'sex' => Sex::from($userData['sex']),
            'activity_level' => ActivityLevel::from($userData['activity_level']),
            'password' => Hash::make($userData['password'])
        ]);

        $user->recommended_water_intake = UserService::calculateRecommendedWaterIntake($user);
        $user->recommended_calories = UserService::calculateRecommendedCalories($user);
        $user->bmi = UserService::computeBMI($user->weight, $user->height);


        User::create($user);
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
