<?php

namespace App\Console\Commands;

use App\Http\Services\ProductService;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FoodScrapper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape_foods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for scraping and inserting foods into the database';

    public function __construct(protected ProductService $productService)
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $foodsToInsert = $this->productService->scrapeAllFoods();
        collect($foodsToInsert)->chunk(200)->each(function ($chunk) {
            Product::upsert($chunk->toArray(), ['name'], ['calories', 'protein', 'carbs', 'fat', 'fibre']);
        });
        Log::info('Successfully scraped food data');
    }
}
