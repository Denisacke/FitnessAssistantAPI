<?php

namespace App\Http\Controllers;

use App\Http\Enums\ActivityLevel;
use App\Http\Enums\Sex;
use App\Http\Requests\UserRegisterForm;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['users' => User::filter()->get()]);
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


        $user->save();
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
    public function update(UserRegisterForm $request, string $id)
    {
        $validatedData = $request->validated();
        $userData = new User([
            ...$validatedData,
            'sex' => Sex::from($validatedData['sex']),
            'activity_level' => ActivityLevel::from($validatedData['activity_level']),
            'password' => Hash::make($validatedData['password']),
        ]);

        $userData->recommended_water_intake = UserService::calculateRecommendedWaterIntake($userData);
        $userData->recommended_calories = UserService::calculateRecommendedCalories($userData);
        $userData->bmi = UserService::computeBMI($userData->weight, $userData->height);

        $user = User::find($id);
        $user->update([
            ...$userData->toArray()
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
