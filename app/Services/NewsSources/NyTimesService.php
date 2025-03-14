<?php
// app/Services/NewsSources/NyTimesService.php
namespace App\Services\NewsSources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NyTimesService extends BaseNewsSource
{
    protected $sourceName = 'The New York Times';

    public function __construct()
    {
        $config = config('services.nytimes');
        parent::__construct($config);
    }

    protected function getEndpoint(): string
    {
        return 'mostpopular/v2/viewed/1.json';
    }

    protected function getQueryParams(): array
    {
        return ['api-key' => $this->config['key']];
    }

    protected function normalizeArticle(array $rawArticle): array
    {
        // Handle author information
        $author = 'New York Times';
        if (isset($rawArticle['byline'])) {
            if (is_array($rawArticle['byline'])) {
                $author = $rawArticle['byline']['original'] ??
                    ($rawArticle['byline']['person'][0]['firstname'] . ' ' .
                     $rawArticle['byline']['person'][0]['lastname'] ?? $author);
            } elseif (is_string($rawArticle['byline'])) {
                $author = str_replace('By ', '', $rawArticle['byline']);
            }
        }

        return [
            'source_id' => $rawArticle['uri'] ?? 'nytimes-' . Str::random(8),
            'title' => $this->sanitizeText($rawArticle['title'] ?? 'No Title'),
            'content' => Str::limit(
                $this->sanitizeText(
                    $rawArticle['abstract'] ??
                    $rawArticle['lead_paragraph'] ??
                    $rawArticle['description'] ?? ''
                ),
                10000
            ),
            'category' => $rawArticle['section'] ?? 'general',
            'author' => Str::limit($author, 512),
            'url' => $rawArticle['url'] ?? '',
        ];
    }

    public function normalizeData(array $rawData): array
    {
        if (!isset($rawData['results']) || !is_array($rawData['results'])) {
            Log::warning('Invalid NYTimes API response structure', $rawData);
            return [];
        }

        return collect($rawData['results'])->map(function ($article) {
            try {
                return array_merge(
                    $this->normalizeArticle($article),
                    [
                        'source_name' => $this->sourceName,
                        'published_at' => $this->parseDate(
                            $article['published_date'] ?? now()->toDateTimeString()
                        )
                    ]
                );
            } catch (\Exception $e) {
                Log::warning('Failed to normalize NYTimes article', [
                    'article' => $article,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        })->filter()->toArray();
    }

    private function sanitizeText(string $text): string
    {
        return htmlspecialchars(strip_tags($text));
    }
}