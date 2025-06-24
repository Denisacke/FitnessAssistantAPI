<?php

namespace App\Console\Commands;

use App\Models\Exercise;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ExercisePersist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:exercise_persist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for inserting exercises into the database';

    private Client $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            'verify' => false
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
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
            unset($item['difficulty']);
            unset($item['description']);
            unset($item['category']);

            return $item;
        }, json_decode($response->getBody(), true));

        Exercise::upsert(
            $responseArray,
            ['name'],
            ['body_part', 'gif_url', 'muscle_target', 'instructions', 'updated_at']
        );
    }
}
