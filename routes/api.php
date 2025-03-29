<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::get('/test', [AuthController::class, 'test']);
Route::group(['prefix' => 'exercises'], function () {
    Route::get('/options', [ExerciseController::class, 'getMuscleOptions']);
    Route::post('/find-exercises', [ExerciseController::class, 'getExercises']);
    Route::get('/find-exercises-muscle', [ExerciseController::class, 'getExercisesByMuscle']);
    Route::get('/find-exercises-bodypart', [ExerciseController::class, 'getExercisesByBodyPart']);
});

Route::resource('workouts', WorkoutController::class);
Route::resource('exercises', ExerciseController::class);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::group(['middleware' => ['auth:api']], function () {
    // Protected routes
});
