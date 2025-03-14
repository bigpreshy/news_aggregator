<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsSources\NewsApiService;

class SyncArticles extends Command
{
    protected $signature = 'articles:sync';
    protected $description = 'Sync articles from news sources';
    public function handle(NewsApiService $service)
    {
        // try {
        //     $articles = $service->fetchArticles();

        //     $this->info("Synced " . count($articles) . " articles from NewsAPI");
        //     return 0;
        // } catch (\Exception $e) {
        //     $this->error("Sync failed: " . $e->getMessage());
        //     return 1;
        // }
    }
}
