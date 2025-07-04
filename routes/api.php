<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsumedProductController;
use App\Http\Controllers\ConsumedRecipeController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PerformedExerciseController;
use App\Http\Controllers\PerformedWorkoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [AuthController::class, 'test']);
Route::group(['prefix' => 'exercises'], function () {
    Route::get('/options', [ExerciseController::class, 'getMuscleOptions']);
    Route::get('/find-exercises', [ExerciseController::class, 'getExercises']);
    Route::get('/find-exercises-muscle', [ExerciseController::class, 'getExercisesByMuscle']);
    Route::get('/find-exercises-bodypart', [ExerciseController::class, 'getExercisesByBodyPart']);
});

Route::group(['prefix' => 'products'], function () {
    Route::post('/scrape', [ProductController::class, 'scrape']);
    Route::get('/consumed_foods/{id}', [ProductController::class, 'getConsumedFoodsPerUser']);
    Route::get('/consumed_macros/{id}', [ProductController::class, 'getConsumedNutritionPerDay']);
});

Route::group(['prefix' => 'consumed_products'], function () {
    Route::post('/register_water_intake', [ConsumedProductController::class, 'registerWaterIntake']);
});

Route::group(['prefix' => 'users'], function () {
    Route::post('/share', [UserController::class, 'shareEntity']);
    Route::put('/{id}/set_trainer', [UserController::class, 'setTrainerForUser']);
});

Route::group(['prefix' => 'performed_exercises'], function () {
    Route::get('/{id}', [PerformedExerciseController::class, 'getPerformedExerciseList']);
    Route::get('/statistics/{user_id}/{exercise_id}', [PerformedExerciseController::class, 'getExerciseStatsForDateRange']);
});

Route::resource('workouts', WorkoutController::class);
Route::resource('performed_workouts', PerformedWorkoutController::class);
Route::resource('exercises', ExerciseController::class);
Route::resource('products', ProductController::class);
Route::resource('recipes', RecipeController::class);
Route::resource('consumed_products', ConsumedProductController::class);
Route::resource('consumed_recipes', ConsumedRecipeController::class);
Route::resource('users', UserController::class);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::group(['middleware' => ['auth:api']], function () {
    // Protected routes
});
