<?php
namespace App\Repositories;

use App\Models\Article;
use App\Filters\ArticleFilter;

class ArticleRepository
{
    public function getPaginatedArticles(array $filters, int $perPage = 20)
    {
        return ArticleFilter::apply(
            Article::query(),
            $filters
        )->paginate($perPage);
    }
}