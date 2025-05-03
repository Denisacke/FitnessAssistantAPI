<?php

namespace App\Http;

use App\Console\Commands\FoodScrapper;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $commands = [
        FoodScrapper::class,
    ];

//    protected function schedule(Schedule $schedule)
//    {
//        $schedule->command('app:scrape_foods')->monthly();
//    }
}

