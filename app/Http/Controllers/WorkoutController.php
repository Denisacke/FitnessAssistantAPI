<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutForm;
use App\Models\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workouts = Workout::with(['exercises', 'author'])
            ->filter()
            ->get()
            ->map(function ($workout) {
                return [
                    'workoutId' => $workout->id,
                    'name' => $workout->name,
                    'author' => $workout->author->name,
                    'exercises' => $workout->exercises->map(function ($exercise) {
                        return [
                            'id' => $exercise->id,
                            'name' => $exercise->name,
                            'sets' => $exercise->pivot->sets,
                            'reps' => $exercise->pivot->reps,
                        ];
                    })->toArray(),
                ];
            });

        return response()->json(['workouts' => $workouts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkoutForm $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $workout = DB::transaction(function () use ($validated) {
                $workout = Workout::create([
                    'name' => $validated['name'],
                    'user_id' => $validated['user_id'],
                    'created_by' => $validated['created_by']
                ]);

                $pivotData = [];
                foreach ($validated['exercises'] as $exercise) {
                    $pivotData[$exercise['exercise_id']] = [
                        'sets' => $exercise['sets'],
                        'reps' => $exercise['reps']
                    ];
                }

                $workout->exercises()->sync($pivotData);

                return $workout;
            });

            return response()->json([
                'message' => 'Workout created successfully!',
                'workout' => $workout->load('exercises')
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong while creating the workout.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkoutForm $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        try {
            $workout = DB::transaction(function () use ($validated, $id) {
                $workout = Workout::find($id);
                $pivotData = [];
                foreach ($validated['exercises'] as $exercise) {
                    $pivotData[$exercise['exercise_id']] = [
                        'sets' => $exercise['sets'],
                        'reps' => $exercise['reps']
                    ];
                }

                $workout->exercises()->sync($pivotData);

                return $workout;
            });

            return response()->json([
                'message' => 'Workout updated successfully!',
                'workout' => $workout->load('exercises')
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong while updating the workout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workout = Workout::findOrFail($id);
        $workout->delete();

        return ['success' => true];
    }
}
