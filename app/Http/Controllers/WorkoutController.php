<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutForm;
use App\Models\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkoutController extends Controller
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
    public function store(WorkoutForm $request): JsonResponse
    {
        $validated = $request->validated();

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

        return response()->json([
            'message' => 'Workout created successfully!',
            'workout' => $workout->load('exercises')
        ]);
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
