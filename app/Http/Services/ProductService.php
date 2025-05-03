<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ProductService
{
    protected $browser;

    public function __construct()
    {
        $this->browser = new HttpBrowser(HttpClient::create());
    }

    public function scrapeAllFoods(): array
    {
        $baseUrl = 'https://calorii.oneden.com/';
        $this->browser->request('GET', $baseUrl);

        $crawler = $this->browser->getCrawler();

        $categoryLinks = $crawler->filter('a')->reduce(function (Crawler $node) {
            return str_contains($node->text(), 'vezi toate');
        });

        $allFoods = [];

        $categoryLinks->each(function (Crawler $node) use (&$allFoods, $baseUrl) {
            $categoryUrl = $node->link()->getUri();
            $this->browser->request('GET', $categoryUrl);
            $categoryCrawler = $this->browser->getCrawler();

            $foods = $categoryCrawler->filter('tr')->each(function (Crawler $foodNode) {
                $tds = $foodNode->filter('td');
                if ($tds->count() !== 8) {
                    return null;
                }

                return [
                    'name' => trim($tds->eq(0)->filter('a')->text()),
                    'calories' => trim($tds->eq(1)->text()),
                    'protein' => trim($tds->eq(2)->text()),
                    'fat' => trim($tds->eq(3)->text()),
                    'carbs' => trim($tds->eq(4)->text()),
                    'fibre' => trim($tds->eq(5)->text()),
                ];
            });

            $allFoods = array_merge($allFoods, array_filter($foods));
        });

        return $allFoods;
    }
}
