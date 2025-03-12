<?php
namespace App\Services\NewsSources;

use App\Interfaces\NewsSourceInterface;
use Illuminate\Support\Str;

class GuardianService extends BaseNewsSource implements NewsSourceInterface
{
    protected $sourceName = 'The Guardian';

    public function __construct()
    {
        $config = config('services.guardian');

        if (! isset($config['key'])) {
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
            'api-key'     => $this->config['key'],
            'show-fields' => 'body,byline',
            'page-size'   => 100,
        ];
    }

    protected function normalizeArticle(array $rawArticle): array
    {

        return [

            'source_id' => $rawArticle['id'] ?? 'guardian-' . Str::random(8),
            'title'     => $this->sanitizeText($rawArticle['webTitle'] ?? 'No Title'),
            'content'   => Str::limit(
                $this->sanitizeText($rawArticle['fields']['body'] ?? ''),
                10000
            ),
            'category'  => $rawArticle['sectionName'] ?? 'general',
            'author'    => Str::limit(
                $rawArticle['fields']['byline'] ?? 'Guardian Staff',
                512
            ),

            'url'       => $rawArticle['webUrl'] ?? '',

        ];
    }

    private function sanitizeText(string $text): string
    {
        return htmlspecialchars(strip_tags($text));
    }
}
