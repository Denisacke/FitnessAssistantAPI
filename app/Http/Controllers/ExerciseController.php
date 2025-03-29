<?php

namespace App\Http\Controllers;

use App\Http\Enums\ExerciseCategory;
use App\Models\Exercise;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ExerciseController extends Controller
{
    private array $bodyParts = ["back", "cardio", "chest", "lower arms", "lower legs", "neck", "shoulders", "upper arms", "upper legs", "waist"];
    private array $muscles = ["abductors", "abs", "adductors", "biceps", "calves",
        "cardiovascular system", "delts", "forearms", "glutes", "hamstrings", "lats",
        "levator scapulae", "pectorals", "quads", "serratus anterior", "spine", "traps", "triceps", "upper back"];
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false
        ]);
    }

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

    // Run this only if the 'exercises' table is empty you want to store all the exercises.
    public function getExercises(): JsonResponse
    {
        $response = $this->client->request('GET', 'https://exercisedb.p.rapidapi.com/exercises?limit=10000', [
            'headers' => [
                'X-RapidAPI-Host' => 'exercisedb.p.rapidapi.com',
                'X-RapidAPI-Key' => '47daba2431msh4a910d0f3b0f50dp13a2a9jsnd0e012cc6431',
            ],
        ]);

        $responseArray = array_map(function ($item) {
            $item['body_part'] = $item['bodyPart'];
            $item['gif_url'] = $item['gifUrl'];
            $item['muscle_target'] = $item['target'];
            $item['created_at'] = now();
            $item['updated_at'] = now();
            if (isset($item['instructions']) && is_array($item['instructions'])) {
                $item['instructions'] = implode("\n", $item['instructions']);
            }

            unset($item['bodyPart']);
            unset($item['gifUrl']);
            unset($item['target']);
            unset($item['equipment']);
            unset($item['secondaryMuscles']);

            return $item;
        }, json_decode($response->getBody(), true));

        Exercise::insert($responseArray);

        return response()->json(['response' => $responseArray]);
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
