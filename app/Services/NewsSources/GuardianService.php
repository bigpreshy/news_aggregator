<?php
namespace App\Services\NewsSources;

use App\Interfaces\NewsSourceInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GuardianService extends BaseNewsSource implements NewsSourceInterface
{
    protected $sourceName = 'The Guardian';

    public function __construct()
    {
        $config = config('services.guardian');

        if (!isset($config['key'])) {
            throw new \InvalidArgumentException('Missing Guardian API key');
        }

        parent::__construct($config);
    }

    protected function getEndpoint(): string
    {
        return 'search';
    }

    protected function getQueryParams(): array
    {
        return [
            'api-key' => $this->config['key'],
            'show-fields' => 'body,byline',
            'page-size' => 100,
            'show-tags' => 'contributor'
        ];
    }

    protected function normalizeArticle(array $rawArticle): array
    {
        return [
            'source_id' => $rawArticle['id'] ?? 'guardian-' . Str::random(8),
            'title' => $this->sanitizeText($rawArticle['webTitle'] ?? 'No Title'),
            'content' => Str::limit(
                $this->sanitizeText($rawArticle['fields']['body'] ?? ''),
                10000
            ),
            'category' => $rawArticle['sectionName'] ?? 'general',
            'author' => Str::limit(
                $rawArticle['fields']['byline'] ??
                $rawArticle['tags'][0]['webTitle'] ?? 'Guardian Staff',
                512
            ),
            'url' => $rawArticle['webUrl'] ?? '',
        ];
    }

    public function normalizeData(array $rawData): array
    {
        return collect($rawData['response']['results'] ?? [])
            ->map(function ($article) {
                return array_merge(
                    $this->normalizeArticle($article),
                    [
                        'source_name' => $this->sourceName,
                        'published_at' => $this->parseDate(
                            $article['webPublicationDate'] ?? now()->toDateTimeString()
                        )
                    ]
                );
            })
            ->toArray();
    }

    private function sanitizeText(string $text): string
    {
        return htmlspecialchars(strip_tags($text));
    }
}