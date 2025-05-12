<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerformedWorkoutForm;
use App\Models\PerformedWorkout;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformedWorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PerformedWorkoutForm $request): JsonResponse
    {
        $validated = $request->validated();
        Log::info('validated data: ' . json_encode($validated));
        try {
            $workout = DB::transaction(function () use ($validated) {
                $workout = PerformedWorkout::create([
                    'user_id' => $validated['user_id'],
                    'workout_id' => $validated['workout_id'],
                    'performed_date' => $validated['performed_date'] ?? Carbon::now(),
                ]);

                $pivotData = array_map(function ($exercise) use ($validated) {
                    return [
                        'exercise_id' => $exercise['exercise_id'],
                        'sets' => $exercise['sets'],
                        'reps' => $exercise['reps'],
                        'weight' => $exercise['weight'],
                        'performed_date' => $validated['performed_date'] ?? Carbon::now(),
                    ];
                }, $validated['exercises']);

                $workout->performedExercises()->createMany($pivotData);

                return $workout;
            });

            return response()->json([
                'message' => 'Registered performed workout!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong while registering the performed workout.',
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
