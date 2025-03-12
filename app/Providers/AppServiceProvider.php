<?php

namespace App\Providers;

use App\Interfaces\NewsSourceInterface;
use App\Services\NewsSources\GuardianService;
use App\Services\NewsSources\NewsApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NewsSourceInterface::class, function ($app) {
            return match(config('news.source')) {
                // 'newsapi' => new NewsApiService(config('services.newsapi')), // Pass config
                'guardian' => new GuardianService(config('services.guardian')),
                // 'nytimes' => new NyTimesService(config('services.nytimes')),
                default => throw new \InvalidArgumentException('Invalid news source')
            };
        });


        // $this->app->bind(\App\Services\NewsSources\NewsApiService::class, function ($app) {
        //     $config = config('services.newsapi'); // Assumes you have a newsapi.php config file
        //     // Ensure that the config has both the API key and the endpoint
        //     // if (!isset($config['endpoint'])) {
        //     //     $config['endpoint'] = 'https://newsapi.org/v2/'; // default endpoint
        //     // }

        //     // if (!isset($config['key'])) {
        //     //     $config['key'] = '31f365eef4ea458189c6e29286450c89'; // default endpoint
        //     // }
        //     return new \App\Services\NewsSources\NewsApiService($config);
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
