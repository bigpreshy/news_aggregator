<?php

namespace App\Services\NewsSources;

use App\Interfaces\NewsSourceInterface;
use App\Models\Article;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

abstract class BaseNewsSource implements NewsSourceInterface
{
    protected $client;
    protected $config;
    protected $sourceName;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->config['endpoint'],
            'timeout'  => 10.0,
        ]);
    }

    protected function saveArticles(array $articles): void
    {
        Article::upsert(
            $articles,
            ['url', 'published_at'],
            ['title', 'content', 'author', 'category', 'source_name']
        );
    }

    public function fetchArticles(): array
    {
        try {
            $response = $this->client->get($this->getEndpoint(), [
                'query' => $this->getQueryParams()
            ]);

            $articles = $this->normalizeData(json_decode($response->getBody(), true));
            $this->saveArticles($articles); // Save articles to DB

            return $articles;
            // return $this->normalizeData(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error("{$this->sourceName} Error: {$e->getMessage()}");
            return [];
        }
    }



    abstract protected function getEndpoint(): string;
    abstract protected function getQueryParams(): array;
    abstract protected function normalizeArticle(array $rawArticle): array;

    public function normalizeData(array $rawData): array
    {
        return collect($rawData['articles'] ?? [])
            ->map(function ($article) {
                return array_merge($this->normalizeArticle($article), [
                    'source_name' => $this->sourceName,
                    'published_at' => $this->parseDate($article['publishedAt'] ?? now()),
                ]);
            })
            ->toArray();
    }

    protected function parseDate(string $date): string
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}
