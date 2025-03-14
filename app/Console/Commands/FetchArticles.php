<?php
namespace App\Console\Commands;

use App\Models\Article;
use App\Services\NewsSources\GuardianService;
use App\Services\NewsSources\NewsApiService;
use App\Services\NewsSources\NyTimesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $services = [
            NewsApiService::class,
            GuardianService::class,
            NyTimesService::class,
        ];

        foreach ($services as $serviceClass) {
            try {
                $service  = app($serviceClass);
                $articles = $service->fetchArticles();
                Article::upsert($articles, ['url', 'published_at']);
            } catch (\Exception $e) {
                Log::error("Data Sync Error: {$e->getMessage()}");
            }
        }

        $this->info('Articles fetched and stored successfully.');
    }
}
