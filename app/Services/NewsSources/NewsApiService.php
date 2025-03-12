<?php
namespace App\Services\NewsSources;

use App\Interfaces\NewsSourceInterface;
use Illuminate\Support\Str;

class NewsApiService extends BaseNewsSource implements NewsSourceInterface
{
    protected $sourceName = 'NewsAPI';


    public function __construct()
    {
        $config = config('services.newsapi');

        if (! isset($config['key'])) {
            throw new \InvalidArgumentException('Missing NewsAPI key in configuration');
        }

        parent::__construct($config);
    }

    protected function getEndpoint(): string
    {
        return 'top-headlines';
    }

    protected function getQueryParams(): array
    {
        return [
            'apiKey'   => $this->config['key'],
            'country'  => 'us',
            'pageSize' => 100,
            'category' => 'general',
        ];
    }

    protected function normalizeArticle(array $rawArticle): array
    {
        return [
            'source_id' => $rawArticle['source']['id'] ?? Str::slug($this->sourceName),
            'title'     => $this->sanitizeText($rawArticle['title']),
            'content'   => Str::limit(strip_tags($rawArticle['content'] ?? $rawArticle['description'] ?? ''), 10000),
            'category'  => $rawArticle['category'] ?? 'general',
            'author'    => $rawArticle['author'] ?? 'Unknown',
            'url'       => $rawArticle['url'],
        ];
    }

    private function sanitizeText(string $text): string
    {
        return htmlspecialchars(strip_tags($text));
    }

}
