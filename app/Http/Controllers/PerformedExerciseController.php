<?php

namespace App\Http\Controllers;

use App\Models\PerformedExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PerformedExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getPerformedExerciseList($userId): JsonResponse
    {
        $performedExercises = PerformedExercise::whereHas('exercise')
            ->where('user_id', $userId)
            ->with('exercise:id,name')
            ->get()
            ->groupBy('exercise_id')
            ->map(function ($group) {
                $exercise = $group->first()->exercise;
                return [
                    'exercise_id' => $exercise->id,
                    'exercise_name' => $exercise->name,
                ];
            })
            ->values();

        Log::debug('returning list of exercises ' . $performedExercises);
        return response()->json(['exercises' => $performedExercises]);
    }

    public function getExerciseStatsForDateRange(Request $request, $userId, $exerciseId): JsonResponse
    {
//        $exerciseId = $request->query('exercise_id');
        $startDate = Carbon::parse($request->query('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->query('end_date'))->endOfDay();

        $exercises = PerformedExercise::where('user_id', $userId)
            ->where('exercise_id', $exerciseId)
            ->whereBetween('performed_date', [$startDate, $endDate])
            ->orderBy('performed_date')
            ->get([
                'sets',
                'reps',
                'weight',
                'performed_date'
            ]);

        return response()->json(['exercises' => $exercises]);
    }

}
