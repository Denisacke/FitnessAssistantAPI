<?php

namespace App\Http\Controllers;

use App\Http\Enums\ActivityLevel;
use App\Http\Enums\Sex;
use App\Http\Requests\ShareEntityForm;
use App\Http\Requests\UserRegisterForm;
use App\Http\Services\UserService;
use App\Models\EntityAssignment;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

        Log::debug(json_encode($user));

        $user->save();
        return response()->json(['success' => true]);
    }

    public function setTrainerForUser(Request $request, $userId): array
    {
        $user = User::findOrFail($userId);

        $user->update([
            'trainer_id' => $request->get('trainer_id')
        ]);

        return ['success' => true];
    }

    public function shareEntity(ShareEntityForm $request)
    {
        $validated = $request->validated();

        $alreadyAssigned = EntityAssignment::where('entity_id', $validated['entity_id'])
            ->where('entity_type', $validated['entity_type'])
            ->where('client_id', $validated['client_id'])
            ->exists();

        if ($alreadyAssigned) {
            return response()->json(['message' => 'Entity already assigned to this client'], 409);
        }

        $entity = null;
        $clientEntity = null;

        try {
            DB::transaction(function () use ($validated) {
                if ($validated['entity_type'] == Workout::class) {
                    $entity = Workout::find($validated['entity_id']);

                    $clientEntity = Workout::create([
                        ...$entity->toArray(),
                        'created_by' => $validated['created_by'],
                        'user_id' => $validated['client_id'],
                    ]);

                    // Build pivot data
                    $syncData = [];
                    foreach ($entity->exercises as $exercise) {
                        $syncData[$exercise->id] = [
                            'sets' => $exercise->pivot->sets,
                            'reps' => $exercise->pivot->reps,
                        ];
                    }

                    $clientEntity->exercises()->sync($syncData);
                    Log::info('Shared workout and attached exercises.', ['workout_id' => $clientEntity->id]);
                } else {
                    $entity = Recipe::find($validated['entity_id']);

                    $clientEntity = Recipe::create([
                        ...$entity->toArray(),
                        'created_by' => $validated['created_by'],
                        'user_id' => $validated['client_id'],
                    ]);

                    // Build pivot data for product quantities
                    $syncData = [];
                    foreach ($entity->products as $product) {
                        $syncData[$product->id] = [
                            'quantity' => $product->pivot->quantity,
                        ];
                    }
                    $clientEntity->products()->sync($syncData);

                    Log::info('Shared recipe and attached products.', ['recipe_id' => $clientEntity->id]);
                }


                EntityAssignment::create([
                    'entity_id' => $validated['entity_id'],
                    'entity_type' => $validated['entity_type'],
                    'trainer_id' => $validated['created_by'],
                    'client_id' => $validated['client_id'],
                ]);
            });
        }  catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong while registering the entity.',
                'error' => $e->getMessage()
            ], 500);
        }


        return response()->json(['entity' => $clientEntity]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::with(['trainer'])->findOrFail($id);

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

        Log::debug('USER DATA');
        Log::debug(json_encode($userData));
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
