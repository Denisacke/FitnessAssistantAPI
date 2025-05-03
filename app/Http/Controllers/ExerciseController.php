<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    private array $bodyParts = ["back", "cardio", "chest", "lower arms", "lower legs", "neck", "shoulders", "upper arms", "upper legs", "waist"];
    private array $muscles = ["abductors", "abs", "adductors", "biceps", "calves",
        "cardiovascular system", "delts", "forearms", "glutes", "hamstrings", "lats",
        "levator scapulae", "pectorals", "quads", "serratus anterior", "spine", "traps", "triceps", "upper back"];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['exercises' => Exercise::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function getMuscleOptions(): JsonResponse
    {
        return response()->json([
            'bodyParts' => $this->bodyParts,
            'muscles' => $this->muscles
        ]);
    }

    public function getExercises(Request $request): JsonResponse
    {
        return response()->json(['exercises' => Exercise::filter()->get()]);
    }

    /**
     * @throws GuzzleException
     */
    public function getExercisesByBodyPart(Request $request): JsonResponse
    {
        $selectedBodyPart = $request->query->get('bodyPart');

        return response()->json(['exercises' => Exercise::where('body_part', $selectedBodyPart)->get()]);
    }

    /**
     * @throws GuzzleException
     */
    public function getExercisesByMuscle(Request $request): JsonResponse
    {
        $selectedMuscle = $request->query->get('muscle');

        return response()->json(['exercises' => Exercise::where('muscle_target', $selectedMuscle)->get()]);
    }
}
