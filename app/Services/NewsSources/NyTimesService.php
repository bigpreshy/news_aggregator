<?php
// app/Services/NewsSources/NyTimesService.php
namespace App\Services\NewsSources;

use App\Interfaces\NewsSourceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NyTimesService implements NewsSourceInterface
{
    private $client;
    private $config;

    public function __construct()
    {
        $this->config = config('services.nytimes');
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->config['endpoint']
        ]);
    }

    public function fetchArticles()
    {
        try {
            $response = $this->client->get('mostpopular/v2/viewed/1.json', [
                'query' => ['api-key' => $this->config['key']]
            ]);

            return $this->normalizeData(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error("NYTimes API Error: {$e->getMessage()}");
            return [];
        }
    }

    public function normalizeData(array $rawData)
    {
        return collect($rawData['results'])->map(function ($article) {
            try {
                $publishedAt = Carbon::parse($article['published_date'])
                    ->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $publishedAt = now()->format('Y-m-d H:i:s');
            }

            return [
                'source_id' => $article['uri'] ?? 'nytimes-'.Str::random(8),
                'title' => $article['title'] ?? 'No Title',
                'content' => Str::limit(strip_tags($article['lead_paragraph'] ??  $article['description'] ?? ''), 10000),
                'category' => $article['section'] ?? 'general',
                'author' => $article['byline'] ?? 'New York Times',
                'source_name' => 'The New York Times',
                'published_at' => $publishedAt,
                'url' => $article['url'] ?? ''
            ];
        })->toArray();
    }
}